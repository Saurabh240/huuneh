<?php
// *************************************************************************
// *                                                                       *
// * DEPRIXA PRO -  Integrated Web Shipping System                         *
// * Copyright (c) JAOMWEB. All Rights Reserved                            *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * Email: support@jaom.info                                              *
// * Website: http://www.jaom.info                                         *
// *                                                                       *
// *************************************************************************
// *                                                                       *
// * This software is furnished under a license and may be used and copied *
// * only  in  accordance  with  the  terms  of such  license and with the *
// * inclusion of the above copyright notice.                              *
// * If you Purchased from Codecanyon, Please read the full License from   *
// * here- http://codecanyon.net/licenses/standard                         *
// *                                                                       *
// *************************************************************************

ini_set('display_errors', 0);


require_once("../../loader.php");
require_once("../../helpers/querys.php");
require_once("../../helpers/phpmailer/class.phpmailer.php");
require_once("../../helpers/phpmailer/class.smtp.php");
require_once("../notify_whatsapp/api_whatsapp_service.php");

$user = new User;
$core = new Core;
$errors = array();

if (empty($_POST['sender_id']))
    $errors['sender_id'] = $lang['validate_field_ajax150'];

if (empty($_POST['delivery_type']))
    $errors['delivery_type'] = $lang['Delivery type is required'];

    
if (empty($_POST['distance']))
$errors['distance'] = $lang['Distance is required'];

if (empty($_POST['sender_address_id']))
    $errors['sender_address_id'] = $lang['validate_field_ajax145'];

if (empty($_POST['recipient_id']))
    $errors['recipient_id'] = $lang['validate_field_ajax146'];

if (empty($_POST['recipient_address_id']))
    $errors['recipient_address_id'] = $lang['validate_field_ajax147'];

/*if (empty($_POST['order_item_category']))
    $errors['order_item_category'] = $lang['validate_field_ajax151'];*/

/*if (empty($_POST['order_package']))
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

    $date = date('Y-m-d', strtotime(cdp_sanitize($_POST["order_date"])));
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
        'user_id' =>  $_SESSION['userid'],
        'order_prefix' =>  $order_prefix,
        'order_incomplete' =>  $order_incomplete,
        'is_pickup' =>  $is_pickup,
        'order_no' => cdp_sanitize($next_order),
        'order_datetime' =>  cdp_sanitize($date),
        'sender_id' =>  cdp_sanitize(intval($_POST["sender_id"])),
        'recipient_id' =>  cdp_sanitize(intval($_POST["recipient_id"])),
        'sender_address_id' =>  cdp_sanitize(intval($_POST["sender_address_id"])),
        'recipient_address_id' =>  cdp_sanitize(intval($_POST["recipient_address_id"])),
        'order_date' =>  date("Y-m-d H:i:s"),
       // 'order_package' =>  cdp_sanitize(intval($_POST["order_package"])),
       // 'order_item_category' =>  cdp_sanitize(intval($_POST["order_item_category"])),
       'order_service_options' =>  null,
       'notes' =>  cdp_sanitize($_POST["notes"]),
        'status_courier' =>  cdp_sanitize(intval($status)),
        'due_date' =>  $due_date,
        'status_invoice' =>  $status_invoice,
        'volumetric_percentage' =>  $meter,
        'charge' => !empty($_POST['charge']) ? cdp_sanitize($_POST['charge']) : 0.00,
        'no_of_rx' => !empty($_POST['no_of_rx']) ? cdp_sanitize($_POST['no_of_rx']) : 0,
        'notes_for_driver' => cdp_sanitize($_POST['notes_for_driver']),
        'no_of_pieces' =>  !empty($_POST['no_of_pieces']) ? $_POST['no_of_pieces'] : 0,
        'total_tax' =>  !empty($_POST['total_tax']) ? $_POST['total_tax'] : 0,
        'tags' => !empty($_POST['tags']) && is_array($_POST['tags']) ? json_encode($_POST['tags']) : json_encode([])
    );

    $shipment_id = cdp_insertCourierPickupFromCustomer($dataShipment);

    $tariffs_value = $_POST["tariffs_value"];
    $declared_value_tax = $_POST["declared_value_tax"];
    $insurance_value = $_POST["insurance_value"];
    $tax_value = $_POST["tax_value"];
    $discount_value = $_POST["discount_value"];
    $reexpedicion_value = $_POST["reexpedicion_value"];
    $price_lb = $_POST["price_lb"];
    $insured_value = $_POST["insured_value"];

    $dataAddresses = array(
        'order_id' =>  $shipment_id,
        'qty' =>   1,
        'description' =>  'package',
        //'length' =>  $package->length,
        //'width' =>  $package->width,
        //'height' =>  $package->height,
        //'weight' =>  $package->weight,
        //'declared_value' =>  $package->declared_value,
        'fixed_value' =>  $_POST["fixed_rate"],
    );

    cdp_insertCourierShipmentPackages($dataAddresses);

    $total_envio = $_POST["pickuptotal"];

    if ($shipment_id !== null) {

        $total_envio = $_POST['total_order'];

        $dataShipmentUpdateTotals = array(
            'order_id' =>  $shipment_id,
            'value_weight' =>  floatval($price_lb),
            'sub_total' =>  floatval($_POST["sub_total"]),
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
            'total_order' =>  floatval($_POST["total_order"]),
            'delivery_type' => $_POST['delivery_type'],
            'distance' => $_POST['distance']
        );

        $update = cdp_updateCourierShipmentTotals($dataShipmentUpdateTotals);
        $order_track = $order_prefix . $next_order;

        if (isset($_FILES['filesMultiple']) && count($_FILES['filesMultiple']['name']) > 0 && $_FILES['filesMultiple']['tmp_name'][0] != '') {

            $target_dir = "../../order_files/";
            $deleted_file_ids = array();

            if (isset($_POST['deleted_file_ids']) && !empty($_POST['deleted_file_ids'])) {
                $deleted_file_ids = explode(",", $_POST['deleted_file_ids']);
            }

            foreach ($_FILES["filesMultiple"]['tmp_name'] as $key => $tmp_name) {

                if (!in_array($key, $deleted_file_ids)) {
                    $image_name = $order_track .  date("Y-m-d-H-i-s") . "_" . basename($_FILES["filesMultiple"]["name"][$key]);
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
        $sender_data = cdp_getSenderCourier(intval($_POST["sender_id"]));

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

        
        $receiver_data = cdp_getRecipientCourier(intval($_POST["recipient_id"]));

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

          //SENDMAIL PHP to the user
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
		
		//Send Email to the ADMIN
		$sender_address_data = cdp_getSenderAddress(intval($_POST["sender_address_id"]));
        $sender_country = $sender_address_data->country;
        $sender_state = $sender_address_data->state;
        $sender_city = $sender_address_data->city;
        $sender_zip_code = $sender_address_data->zip_code;
        $sender_address = $sender_address_data->address;
		
		
		
		$recipient_address_data = cdp_getRecipientAddress(intval($_POST["recipient_address_id"]));

        $recipient_address = $recipient_address_data->address;
        $recipient_country = $recipient_address_data->country;
        $recipient_city = $recipient_address_data->city;
        $recipient_state = $recipient_address_data->state;
        $recipient_zip_code = $recipient_address_data->zip_code;
		
		
		$_sender_country = cdp_getCountry($sender_country);
        $final_sender_country = $_sender_country['data'];

        $_sender_state = cdp_getState($sender_state);
        $final_sender_state = $_sender_state['data'];

        $sender_city = cdp_getCity($sender_city);
        $final_sender_city = $sender_city['data'];


        
        $_recipient_country = cdp_getCountry($recipient_country);
        $final_recipient_country = $_recipient_country['data'];

        $_recipient_state = cdp_getState($recipient_state);
        $final_recipient_state = $_recipient_state['data'];

        $recipient_city = cdp_getCity($recipient_city);
        $final_recipient_city = $recipient_city['data'];
		
		
		$email_template = cdp_getEmailTemplatesdg1i4(27);
		
         $body = str_replace(
            array(
                '[NAME]',
                '[sender_name]',
                '[TRACKING]',
                '[DELIVERY_TIME]',
                '[URL]',
                '[URL_LINK]',
                '[SITE_NAME]',
                '[URL_SHIP]',
                '[sender_address]',
                '[recipient_name]',
                '[recipient_address]',
                '[order_price]',
                '[order_type]'
            ),
            array(
                'Admin',
                $sender_data->fname . ' ' . $sender_data->lname,
                $fullshipment,
                $date_ship,
                $msite_url,
                $mlogo,
                $msnames,
                $app_url,
				$sender_address . ', ' . $final_sender_city->name . ', ' . $final_sender_state->name. ', ' . $final_sender_country->name. ' ' . $sender_zip_code,
                $receiver_data->fname . ' ' . $receiver_data->lname,
                $recipient_address . ', ' . $final_recipient_city->name. ', ' . $final_recipient_state->name. ', ' . $final_recipient_country->name. ' ' . $recipient_zip_code,
                '$' . floatval($_POST["total_order"]),
                $_POST['delivery_type']
            ),
            $email_template->body
        );

			
        $newbody = cdp_cleanOut($body);
		if ($check_mail == 'PHP') {

                $message = $newbody;
                $to = $site_email;
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
               $destinatario = $site_email;
                

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


				$subject = $lang['email_title_1'].' - '. $sender_data->business_name;
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
        cdp_insertNotificationsUsers($notification_id, intval($_POST['sender_id']));

        


        

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
