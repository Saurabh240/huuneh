<?php

/**
 * @OA\Info(
 *     title="Pickup Client Webhook API",
 *     version="1.0.0",
 *     description="API for adding a pickup client via webhook."
 * )
 */

/**
 * @OA\Post(
 *     path="/webhooks/add_pickup_client_webhook.php",
 *     summary="Add a pickup client",
 *     description="This endpoint allows you to add a pickup client with specific details.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"api_token", "delivery_type", "pickup_address", "recipient_full_name", "dropoff_address", "no_of_rx"},
 *                 @OA\Property(
 *                     property="api_token",
 *                     type="string",
 *                     description="API token for authentication",
 *                     example="s09O3QaEU1ngQI2s"
 *                 ),
 *                 @OA\Property(
 *                     property="delivery_type",
 *                     type="string",
 *                     description="Type of delivery",
 *                     example="RUSH (4 HOURS)"
 *                 ),
 *                 @OA\Property(
 *                     property="pickup_address",
 *                     type="string",
 *                     description="Address for pickup",
 *                     example="80 Hutcherson Square"
 *                 ),
 *                 @OA\Property(
 *                     property="recipient_full_name",
 *                     type="string",
 *                     description="Full name of the recipient",
 *                     example="Kormans LLP"
 *                 ),
 *                 @OA\Property(
 *                     property="dropoff_address",
 *                     type="string",
 *                     description="Address for dropoff",
 *                     example="46 Village Centre Pl, Suite 200"
 *                 ),
 *                 @OA\Property(
 *                     property="notes",
 *                     type="string",
 *                     description="Additional notes",
 *                     example="These are some example notes."
 *                 ),
 *                 @OA\Property(
 *                     property="charge",
 *                     type="number",
 *                     format="float",
 *                     description="Charge for the service",
 *                     example=120.00
 *                 ),
 *                 @OA\Property(
 *                     property="notes_for_driver",
 *                     type="string",
 *                     description="Notes for the driver",
 *                     example="any thing"
 *                 ),
 *                 @OA\Property(
 *                     property="no_of_rx",
 *                     type="integer",
 *                     description="Number of prescriptions",
 *                     example=12
 *                 ),
 *               @OA\Property(
 *                     property="tags",
 *                     type="array",
 *                     description="Array of tags",
 *                     @OA\Items(type="string"),
 *                     example={
 *                          "Fridge Item (2-4 C)",
 *                      }
 *                )

 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Pickup client added successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Pickup client added successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Missing required fields")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     )
 * )
 */


ini_set('display_errors', 0);
require_once("../loader.php");
require_once("../helpers/functions.php");
require_once("../helpers/querys.php");
require_once("../helpers/phpmailer/class.phpmailer.php");
require_once("../helpers/phpmailer/class.smtp.php");
// require_once("../notify_whatsapp/api_whatsapp_service.php");

function processIncomingData($lang, $db) {

$user = new User();
$core = new Core();

$deliveryTypes = [
  "SAME DAY (1PM to 4PM)" => "SAME DAY (1PM to 4PM)",
  "SAME DAY (BEFORE 5PM)" => "SAME DAY (BEFORE 5PM)",
  "RUSH (4 HOURS)" => "RUSH (4 HOURS)",
  "RUSH (3 HOURS)" => "RUSH (3 HOURS)",
  "RUSH (2 HOURS)" => "RUSH (2 HOURS)",
  "URGENT (90 MINUTES)" => "URGENT (90 MINUTES)",
  "NEXT DAY (BEFORE 5PM)" => "NEXT DAY (BEFORE 5PM)",
  "NEXT DAY (BEFORE 2PM)" => "NEXT DAY (BEFORE 2PM)",
  "NEXT DAY (BEFORE 11:30AM)" => "NEXT DAY (BEFORE 11:30AM)",
  "NEXT DAY (BEFORE 10:30AM)" => "NEXT DAY (BEFORE 10:30AM)"
];

try {
  // Validate the token
  if (empty($_POST['api_token'])) {
    throw new Exception('Sender Not found');
  }
  $token = $_POST['api_token'];
  $sender = $user->cdp_tokenCheck($token);

  if (!$sender || empty($sender->id)) {
    throw new Exception('Invalid API Token.');
  }

  // Validate delivery type
  if (empty($_POST['delivery_type']) || !array_key_exists($_POST['delivery_type'], $deliveryTypes)) {
    throw new Exception('Invalid or missing delivery type.');
  }

  // Validate pickup address
  if (empty($_POST['pickup_address'])) {
    throw new Exception('Pickup address is missing.');
  }

  // Validate recipient email
  if (empty($_POST['recipient_full_name'])) {
    throw new Exception('Recipient full name is missing.');
  }

  // Validate dropoff address
  if (empty($_POST['dropoff_address'])) {
    throw new Exception('Dropoff address is missing.');
  }

  $allowed_tags = [
    "Fridge Item (2-4 C)",
    "Hand Deliver",
    "Narcotics",
    "Pickup Rx Paper",
    "Pick up Old Medication"
];
  $charge = 0;
  $no_of_rx = 0;
  $notes_for_driver = "";
  $tags = json_encode([]);

  if($sender->business_type == "pharmacy"){
    if(is_array($_POST['tags']) && !empty($_POST['tags'])){
        $invalid_tags = array_diff($_POST['tags'], $allowed_tags);
        if (!empty($invalid_tags)) {
            throw new Exception('Invalid tag value.');
        }
    }
    $charge = cdp_sanitize($_POST['charge']);
    $no_of_rx = cdp_sanitize($_POST['no_of_rx']);
    $notes_for_driver = cdp_sanitize($_POST['notes_for_driver']);
    $tags = !empty($_POST['tags']) && is_array($_POST['tags']) ? json_encode($_POST['tags']) : $tags;
  }

  

  // Validate notes
  $notes = isset($_POST['notes']) ? $_POST['notes'] : '';

  // Get sender ID and address ID
  $sender_id = $sender->id;
  $sender_address_id = $user->cdp_getUserAddress($_POST['pickup_address'], $sender_id);
  
  if (empty($sender_address_id)) {
    throw new Exception('Sender address not found.');
  }
  $business_type = $sender_address_id->business_type;
  $sender_address_id = $sender_address_id->id_addresses;

  // Get recipient ID and address ID
  $recipient_id = $user->cdp_getRecipient($_POST['recipient_full_name'], $sender_id);
  if (empty($recipient_id)) {
    throw new Exception('Recipient not found.');
  }
  $recipient_id = $recipient_id->id;

  $recipient_address_id = $user->cdp_getRecipientAddress($recipient_id, $_POST['dropoff_address']);
  if (empty($recipient_address_id)) {
    throw new Exception('Recipient address not found.');
  }
  $recipient_address_id = $recipient_address_id->id_addresses;

  // Calculate distance and shipping price
  $delivery_type = $_POST['delivery_type'];

  $origin = urlencode($_POST["pickup_address"]);
  $destination = urlencode($_POST["dropoff_address"]);
  
  
  $distance = calculateDistance1($origin, $destination);
    
  if (empty($distance)) {
    $distance = 0;
  }

  $baseRate = 0;
  $additionalRatePerKm = 0;
  $baseKm = 0;
  $shippingPrice = 0;

  if ($distance) {
      // Calculate shipping price based on distance and delivery type
      $rates = getRatesByDeliveryTypeAndBusinessType1($delivery_type, $business_type);
      if ($rates) {
          $baseRate = $rates['baseRate'];
          $additionalRatePerKm = $rates['additionalRatePerKm'];
          $baseKm = $rates['baseKm'];
          $shippingPrice = calculateShippingPrice1($distance, $baseRate, $additionalRatePerKm, $baseKm);
      } else {
          echo "<p>Invalid delivery type or business type.</p>";
      }
  } else {
      echo "<p>Error calculating distance.</p>";
  }

  $shippingPriceFloat = (float)$shippingPrice;
  $total_order = $shippingPriceFloat + ($shippingPriceFloat * 0.13);

  $data_to_save = array(
    'notes' => $notes,
    'fixed_rate' => '10',              
    'pickuptotal' => $shippingPriceFloat, // This is a string, consider rounding if it's supposed to be a float
    'sender_id' => $sender_id,               // ID is generally numeric, convert to integer if needed
    'sender_address_id' => $sender_address_id,       // Same as above, could be converted to integer
    'recipient_id' => $recipient_id,            // Same as above
    'recipient_address_id' => $recipient_address_id,    // Same as above
    'order_date' => date('Y-m-d'),      // Date should be in Y-m-d format
    'price_lb' => '3.55',              // Ensure it should be a string, or convert to float if needed
    'insured_value' => '100',          // Convert to float if this should be numeric
    'reexpedicion_value' => '0',       // Convert to float if this should be numeric
    'discount_value' => '0',           // Convert to float if this should be numeric
    'tax_value' => ($shippingPriceFloat * 0.13),               // Convert to float if this should be numeric
    'declared_value_tax' => '3',       // Convert to float if this should be numeric
    'tariffs_value' => '0.1',          // Ensure it should be a string, or convert to float if needed
    'insurance_value' => '2',          // Convert to float if this should be numeric
    'total_order' => $total_order,          // Ensure it should be a string, or convert to float if needed
    'delivery_type' => $delivery_type, // This is a string and fine as is
    'distance' => $distance,            // Ensure it should be a string, or convert to float if needed
    'sub_total' => $shippingPriceFloat,            // Ensure it should be a string, or convert to float if needed
);




$errors = array();

if (empty($data_to_save['sender_id']))
    $errors['sender_id'] = $lang['validate_field_ajax150'];

if (empty($data_to_save['delivery_type']))
    $errors['delivery_type'] = $lang['Delivery type is required'];

    
if (empty($data_to_save['distance']))
$errors['distance'] = $lang['Distance is required'];

if (empty($data_to_save['sender_address_id']))
    $errors['sender_address_id'] = $lang['validate_field_ajax145'];

if (empty($data_to_save['recipient_id']))
    $errors['recipient_id'] = $lang['validate_field_ajax146'];

if (empty($data_to_save['recipient_address_id']))
    $errors['recipient_address_id'] = $lang['validate_field_ajax147'];

/*if (empty($data_to_save['order_item_category']))
    $errors['order_item_category'] = $lang['validate_field_ajax151'];*/

/*if (empty($data_to_save['order_package']))
    $errors['order_package'] = $lang['validate_field_ajax152'];*/


if (empty($errors)) {

    $settings = cdp_getSettingsCourier();

    $order_prefix = $settings->prefix;
    $site_email = $settings->email_address;
    $check_mail = $settings->mailer;
    $names_info = $settings->smtp_names;
    $mlogo = $settings->logo;
    $msite_url = $settings->site_url;
    $msnames = $settings->site_name;
    //SMTP
    $smtphoste = $settings->smtp_host;
    $smtpuser = $settings->smtp_user;
    $smtppass = $settings->smtp_password;
    $smtpport = $settings->smtp_port;
    $smtpsecure = $settings->smtp_secure;
    $value_weight = $settings->value_weight;
    $meter = $settings->meter;


    $next_order = $core->cdp_order_track();
    $min_cost_tax = $core->min_cost_tax;
    $min_cost_declared_tax = $core->min_cost_declared_tax;

    $date = date('Y-m-d', strtotime(cdp_sanitize($data_to_save["order_date"])));
    $time = date("H:i:s");
    $date = $date . ' ' . $time;

    $status = 14;
    $is_pickup = true;
    $order_incomplete = 0;
    $days = 0;

    $days = intval($days);

    $sale_date   = date("Y-m-d H:i:s");
    $due_date = cdp_sumardias($sale_date, $days);
    $status_invoice = 2;

    $dataShipment = array(
        'user_id' =>  $data_to_save['sender_id'],
        'order_prefix' =>  $order_prefix,
        'order_incomplete' =>  $order_incomplete,
        'is_pickup' =>  $is_pickup,
        'order_no' => cdp_sanitize($next_order),
        'order_datetime' =>  cdp_sanitize($date),
        'sender_id' =>  cdp_sanitize(intval($data_to_save["sender_id"])),
        'recipient_id' =>  cdp_sanitize(intval($data_to_save["recipient_id"])),
        'sender_address_id' =>  cdp_sanitize(intval($data_to_save["sender_address_id"])),
        'recipient_address_id' =>  cdp_sanitize(intval($data_to_save["recipient_address_id"])),
        'order_date' =>  date("Y-m-d H:i:s"),
       // 'order_package' =>  cdp_sanitize(intval($data_to_save["order_package"])),
       // 'order_item_category' =>  cdp_sanitize(intval($data_to_save["order_item_category"])),
       'order_service_options' =>  null,
       'notes' =>  cdp_sanitize($data_to_save["notes"]),
        'status_courier' =>  cdp_sanitize(intval($status)),
        'due_date' =>  $due_date,
        'status_invoice' =>  $status_invoice,
        'volumetric_percentage' =>  $meter,
        'charge' => cdp_sanitize($charge),
        'no_of_rx' => cdp_sanitize($no_of_rx),
        'notes_for_driver' => cdp_sanitize($notes_for_driver),
        'tags' => cdp_sanitize($tags)
    );

    $shipment_id = cdp_insertCourierPickupFromCustomer($dataShipment);

    $tariffs_value = $data_to_save["tariffs_value"];
    $declared_value_tax = $data_to_save["declared_value_tax"];
    $insurance_value = $data_to_save["insurance_value"];
    $tax_value = $data_to_save["tax_value"];
    $discount_value = $data_to_save["discount_value"];
    $reexpedicion_value = $data_to_save["reexpedicion_value"];
    $price_lb = $data_to_save["price_lb"];
    $insured_value = $data_to_save["insured_value"];

    $dataAddresses = array(
        'order_id' =>  $shipment_id,
        'qty' =>   1,
        'description' =>  'package',
        //'length' =>  $package->length,
        //'width' =>  $package->width,
        //'height' =>  $package->height,
        //'weight' =>  $package->weight,
        //'declared_value' =>  $package->declared_value,
        'fixed_value' =>  $data_to_save["fixed_rate"],
    );

    cdp_insertCourierShipmentPackages($dataAddresses);

    $total_envio = $data_to_save["pickuptotal"];

    if ($shipment_id !== null) {

        $total_envio = $data_to_save['total_order'];

        $dataShipmentUpdateTotals = array(
            'order_id' =>  $shipment_id,
            'value_weight' =>  floatval($price_lb),
            'sub_total' =>  $data_to_save["pickuptotal"],
            'tax_discount' =>  floatval($discount_value),
            'total_insured_value' => floatval($insured_value),
            'tax_insurance_value' => floatval($insurance_value),
            'tax_custom_tariffis_value' => floatval($tariffs_value),
            'tax_value' => floatval($tax_value),
            'declared_value' =>  floatval($declared_value_tax),
            'total_reexp' =>  floatval($reexpedicion_value),
            'total_declared_value' =>  floatval($total_valor_declarado),
            'total_fixed_value' =>  floatval($max_fixed_charge),
            'total_tax_discount' =>  floatval($total_descuento),
            'total_tax_insurance' =>  floatval($total_seguro),
            'total_tax_custom_tariffis' =>  floatval($total_impuesto_aduanero),
            'total_tax' =>  floatval($total_impuesto),
            'total_weight' =>  floatval($total_peso),
            'total_order' =>  floatval($data_to_save["total_order"]),
            'delivery_type' => $data_to_save['delivery_type'],
            'distance' => $data_to_save['distance']
        );

        $update = cdp_updateCourierShipmentTotals($dataShipmentUpdateTotals);
        $order_track = $order_prefix . $next_order;

        if (isset($_FILES['filesMultiple']) && count($_FILES['filesMultiple']['name']) > 0 && $_FILES['filesMultiple']['tmp_name'][0] != '') {

            $target_dir = "../../order_files/";
            $deleted_file_ids = array();

            if (isset($data_to_save['deleted_file_ids']) && !empty($data_to_save['deleted_file_ids'])) {
                $deleted_file_ids = explode(",", $data_to_save['deleted_file_ids']);
            }

            foreach ($_FILES["filesMultiple"]['tmp_name'] as $key => $tmp_name) {

                if (!in_array($key, $deleted_file_ids)) {
                    $image_name = $order_track .  date("Y-m-d") . "_" . basename($_FILES["filesMultiple"]["name"][$key]);
                    $target_file = $target_dir . $image_name;
                    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
                    $imageFileZise = $_FILES["filesMultiple"]["size"][$key];

                    if ($imageFileZise > 0) {
                        move_uploaded_file($_FILES["filesMultiple"]["tmp_name"][$key], $target_file);
                        $imagen = basename($_FILES["filesMultiple"]["name"][$key]);
                    }

                    $target_file_db = "order_files/" . $image_name;
                    cdp_insertOrdersFiles($shipment_id, $target_file_db, $image_name, date("Y-m-d H:i:s"), '0', $imageFileType);
                }
            }
        }
        $sender_data = cdp_getSenderCourier(intval($data_to_save["sender_id"]));

        $dataTrack = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'order_track' =>  $order_track,
            't_date' =>  date("Y-m-d H:i:s"),
            'status_courier' =>  cdp_sanitize(intval($status)),
            'comments' => $lang['messagesform39'] . ' ' . $sender_data->fname . ' ' . $sender_data->lname,
            'office' =>  null,
        ); 

        cdp_insertCourierShipmentTrack($dataTrack);

        
        $receiver_data = cdp_getRecipientCourier(intval($data_to_save["recipient_id"]));

        $fullshipment = $order_prefix . $next_order;
        $add_status =   intval($status);
        $date_ship   = date("Y-m-d H:i:s a");

        $app_url = $settings->site_url . 'track.php?order_track=' . $fullshipment;
        // $subject = $lang['notification_shipment2'] . $lang['notification_shipment6'] .  $fullshipment;
        $subject = $lang['notification_shipment2'] . $lang['notification_shipment3'] . $fullshipment;


        $email_template = cdp_getEmailTemplatesdg1i4(16);

        $userData = $user->cdp_getUserData();

        $body = str_replace(
            array(
                '[NAME]',
                '[TRACKING]',
                '[DELIVERY_TIME]',
                '[URL]',
                '[URL_LINK]',
                '[SITE_NAME]',
                '[URL_SHIP]'
            ),
            array(
                $sender_data->fname . ' ' . $sender_data->lname,
                $fullshipment,
                $date_ship,
                $msite_url,
                $mlogo,
                $msnames,
                $app_url
            ),
            $email_template->body
        );

        $newbody = cdp_cleanOut($body);

        //SENDMAIL PHP
        if($userData->email_subscription){
            if ($check_mail == 'PHP') {

                $message = $newbody;
                $to = $sender_data->email;
                $from = $site_email;

                $header = "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html; charset=UTF-8 \r\n";
                $header .= "From: " . $from . " \r\n";
                try {
                    mail($to, $subject, $message, $header);
                } catch (Exception $e) {
                }
            } elseif ($check_mail == 'SMTP') {

                //PHPMAILER PHP
                $destinatario = $sender_data->email;

                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->Port = $smtpport;
                $mail->IsHTML(true);
                $mail->CharSet = 'UTF-8';

                // Datos de la cuenta de correo utilizada para enviar vía SMTP
                $mail->Host = $smtphoste;       // Dominio alternativo brindado en el email de alta
                $mail->Username = $smtpuser;    // Mi cuenta de correo
                $mail->Password = $smtppass;    //Mi contraseña


                $mail->From = $site_email; // Email desde donde envío el correo.
                $mail->FromName = $names_info;
                $mail->AddAddress($destinatario); // Esta es la dirección a donde enviamos los datos del formulario
                $mail->addCC($site_email);


                $mail->Subject = $subject; // Este es el titulo del email.
                $mail->Body = "
                        <html> 
                        <body> 
                        <p>{$newbody}</p>
                        </body> 
                        </html>
                        <br />"; // Texto del email en formato HTML

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                try {
                    $estadoEnvio = $mail->Send();
                    // echo "El correo fue enviado correctamente.";
                } catch (Exception $e) {
                    // echo "Ocurrió un error inesperado.";
                }
            }
        }

        $dataHistory = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'action' =>  $lang['notification_shipment8'],
            'date_history' =>  cdp_sanitize(date("Y-m-d H:i:s")),
            'order_track' =>  $order_track,
        );

        //INSERT HISTORY USER
        cdp_insertCourierShipmentUserHistory(
            $dataHistory
        );

        $dataNotification = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'notification_description' => $lang['notification_shipment'],
            'shipping_type' => '1',
            'notification_date' =>  cdp_sanitize(date("Y-m-d H:i:s")),
        );
        // SAVE NOTIFICATION
        cdp_insertNotification(
            $dataNotification
        );

        $notification_id = $db->dbh->lastInsertId();

        //NOTIFICATION TO ADMIN AND EMPLOYEES
        $users_employees = cdp_getUsersAdminEmployees();

        foreach ($users_employees as $key) {
            cdp_insertNotificationsUsers($notification_id, $key->id);
        }
        //NOTIFICATION TO CUSTOMER
        cdp_insertNotificationsUsers($notification_id, intval($data_to_save['sender_id']));

        $sender_address_data = cdp_getSenderAddress(intval($data_to_save["sender_address_id"]));
        $sender_country = $sender_address_data->country;
        $sender_state = $sender_address_data->state;
        $sender_city = $sender_address_data->city;
        $sender_zip_code = $sender_address_data->zip_code;
        $sender_address = $sender_address_data->address;

        $_sender_country = cdp_getCountry($sender_country);
        $final_sender_country = $_sender_country['data'];

        $_sender_state = cdp_getState($sender_state);
        $final_sender_state = $_sender_state['data'];

        $sender_city = cdp_getCity($sender_city);
        $final_sender_city = $sender_city['data'];


        $recipient_address_data = cdp_getRecipientAddress(intval($data_to_save["recipient_address_id"]));

        $recipient_address = $recipient_address_data->address;
        $recipient_country = $recipient_address_data->country;
        $recipient_city = $recipient_address_data->city;
        $recipient_state = $recipient_address_data->state;
        $recipient_zip_code = $recipient_address_data->zip_code;

        $_recipient_country = cdp_getCountry($recipient_country);
        $final_recipient_country = $_recipient_country['data'];

        $_recipient_state = cdp_getState($recipient_state);
        $final_recipient_state = $_recipient_state['data'];

        $recipient_city = cdp_getCity($recipient_city);
        $final_recipient_city = $recipient_city['data'];

        // SAVE ADDRESS FOR Shipments
        $dataAddresses = array(
            'order_id' =>   $shipment_id,
            'order_track' =>   $order_track,
            'sender_country' =>   $final_sender_country->name,
            'sender_state' =>   $final_sender_state->name,
            'sender_city' =>   $final_sender_city->name,
            'sender_zip_code' =>   $sender_zip_code,
            'sender_address' =>   $sender_address,
            'recipient_country' =>   $final_recipient_country->name,
            'recipient_state' =>   $final_recipient_state->name,
            'recipient_city' =>   $final_recipient_city->name,
            'recipient_zip_code' =>   $recipient_zip_code,
            'recipient_address' =>   $recipient_address,
        );

        cdp_insertCourierShipmentAddresses($dataAddresses);

        $messages[] = $lang['message_ajax_success_add_pickup'];
    } else {
        $errors['critical_error'] = $lang['message_ajax_error2'];
    }
}

if (!empty($errors)) {
    echo json_encode([
        'success' => false,
        'errors' => $errors
    ]);
} else {
    echo json_encode([
        'success' => true,
        'messages' => $messages,
        'shipment_id' => $shipment_id,
    ]);
}

} catch (Exception $e) {
  // Handle errors
  header('HTTP/1.1 400 Bad Request');
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
  exit;
}
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    processIncomingData($lang, $db);
}