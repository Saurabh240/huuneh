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


if (empty($_POST['tracking_purchase']))

    $errors['tracking_purchase'] = $lang['validate_field_ajax170'];

if (empty($_POST['provider_purchase']))

    $errors['provider_purchase'] = $lang['validate_field_ajax172'];

if (empty($_POST['price_purchase']))

    $errors['price_purchase'] = $lang['validate_field_ajax174'];



if (empty($_POST['sender_id']))
    $errors['sender_id'] = $lang['validate_field_ajax150'];

if (empty($_POST['sender_address_id']))
    $errors['sender_address_id'] = $lang['validate_field_ajax145'];


if (empty($_POST['agency']))
    $errors['agency'] = $lang['validate_field_ajax148'];

if (empty($_POST['origin_off']))
    $errors['origin_off'] = $lang['validate_field_ajax149'];

if (empty($_POST['order_no']))
    $errors['order_no'] = $lang['validate_field_ajax150'];

if (empty($_POST['order_item_category']))
    $errors['order_item_category'] = $lang['validate_field_ajax151'];

if (empty($_POST['order_package']))
    $errors['order_package'] = $lang['validate_field_ajax152'];

if (empty($_POST['order_courier']))
    $errors['order_courier'] = $lang['validate_field_ajax153'];

if (empty($_POST['order_service_options']))
    $errors['order_service_options'] = $lang['validate_field_ajax154'];

if (empty($_POST['order_deli_time']))
    $errors['order_deli_time'] = $lang['validate_field_ajax155'];


if (empty($errors)) {

    $settings = cdp_getSettingsCourier();

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


    $next_order = $core->cdp_online_shopping_track();
    $min_cost_tax = $core->min_cost_tax;
    $min_cost_declared_tax = $core->min_cost_declared_tax;

    $date = date('Y-m-d', strtotime(cdp_sanitize($_POST["order_date"])));
    $time = date("H:i:s");
    $date = $date . ' ' . $time;

    $status_invoice = 2;
    $status_courier = 2;
    $is_prealert = 1;

    if (isset($_POST["prefix_check"]) && intval($_POST["prefix_check"]) == 1) {
        $code_prefix = cdp_sanitize($_POST["code_prefix2"]);
    } else {
        $code_prefix = cdp_sanitize($_POST["code_prefix"]);
    }

    $dataShipment = array(
        'order_prefix' =>  $code_prefix,
        'order_no' => cdp_sanitize($_POST["order_no"]),
        'agency' =>  cdp_sanitize(intval($_POST["agency"])),
        'origin_off' =>  cdp_sanitize(intval($_POST["origin_off"])),
        'sender_id' =>  cdp_sanitize(intval($_POST["sender_id"])),
        'sender_address_id' =>  cdp_sanitize(intval($_POST["sender_address_id"])),
        'tracking_purchase' =>  cdp_sanitize($_POST["tracking_purchase"]),
        'provider_purchase' =>  cdp_sanitize($_POST["provider_purchase"]),
        'price_purchase' =>  cdp_sanitize(floatval($_POST["price_purchase"])),
        'order_package' =>  cdp_sanitize(intval($_POST["order_package"])),
        'order_item_category' =>  cdp_sanitize(intval($_POST["order_item_category"])),
        'order_courier' =>  cdp_sanitize(intval($_POST["order_courier"])),
        'order_service_options' =>  cdp_sanitize(intval($_POST["order_service_options"])),
        'order_deli_time' =>  cdp_sanitize(intval($_POST["order_deli_time"])),
        'status_courier' =>  cdp_sanitize(intval($status_courier)),
        'driver_id' =>  cdp_sanitize(intval($_POST["driver_id"])),
        'order_date' =>  date("Y-m-d H:i:s"),
        'order_datetime' =>  cdp_sanitize($date),
        'status_invoice' =>  $status_invoice,
        'is_prealert' =>  $is_prealert,
        'user_id' =>  $_SESSION['userid'],
        'volumetric_percentage' =>  $meter
    );

    $shipment_id = cdp_insertCustomerPackages($dataShipment);

    if ($shipment_id !== null) {

        cdp_updatedPreAlertPackage($_POST["prealert_id"]);

        if (isset($_POST["packages"])) {

            $packages = json_decode($_POST['packages']);

            $sumador_total = 0;
            $sumador_valor_declarado = 0;
            $max_fixed_charge = 0;
            $sumador_libras = 0;
            $sumador_volumetric = 0;

            $precio_total = 0;
            $total_impuesto = 0;
            $total_descuento = 0;
            $total_seguro = 0;
            $total_peso = 0;
            $total_impuesto_aduanero = 0;
            $total_valor_declarado = 0;

            $tariffs_value = $_POST["tariffs_value"];
            $declared_value_tax = $_POST["declared_value_tax"];
            $insurance_value = $_POST["insurance_value"];
            $tax_value = $_POST["tax_value"];
            $discount_value = $_POST["discount_value"];
            $reexpedicion_value = $_POST["reexpedicion_value"];
            $price_lb = $_POST["price_lb"];
            $insured_value = $_POST["insured_value"];

            foreach ($packages as $package) {

                $dataAddresses = array(
                    'order_id' =>  $shipment_id,
                    'qty' =>  $package->qty,
                    'description' =>  $package->description,
                    'length' =>  $package->length,
                    'width' =>  $package->width,
                    'height' =>  $package->height,
                    'weight' =>  $package->weight,
                    'declared_value' =>  $package->declared_value,
                    'fixed_value' =>  $package->fixed_value,
                );

                cdp_insertCustomerPackagesItems($dataAddresses);

                // calculate weight columetric box size
                $total_metric = $package->length * $package->width * $package->height / $meter;
                $weight = $package->weight;

                // calculate weight x price
                if ($weight > $total_metric) {
                    $calculate_weight = $weight;
                    $sumador_libras += $weight;
                } else {
                    $calculate_weight = $total_metric;
                    $sumador_volumetric += $total_metric;
                }

                $precio_total = $calculate_weight * $price_lb;
                $sumador_total += $precio_total;
                $sumador_valor_declarado += $package->declared_value;
                $max_fixed_charge += $package->fixed_value;

                if ($sumador_total > $min_cost_tax) {
                    $total_impuesto = $sumador_total * $tax_value / 100;
                }

                if ($sumador_valor_declarado > $min_cost_declared_tax) {
                    $total_valor_declarado = $sumador_valor_declarado * $declared_value_tax / 100;
                }
            }

            $total_descuento = $sumador_total * $discount_value / 100;
            $total_peso = $sumador_libras + $sumador_volumetric;
            $total_seguro = $insured_value * $insurance_value / 100;
            $total_impuesto_aduanero = $total_peso * $tariffs_value;
            $total_envio = ($sumador_total - $total_descuento) + $total_seguro + $total_impuesto + $total_impuesto_aduanero + $total_valor_declarado + $max_fixed_charge + $reexpedicion_value;
        }

        $dataShipmentUpdateTotals = array(
            'order_id' =>  $shipment_id,
            'value_weight' =>  floatval($price_lb),
            'sub_total' =>  floatval($sumador_total),
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
            'total_order' =>  floatval($total_envio),
        );

        $update = cdp_updateCustomerPackagesTotals($dataShipmentUpdateTotals);
        $order_track = $code_prefix . $_POST["order_no"];


        if (isset($_FILES['filesMultiple']) && count($_FILES['filesMultiple']['name']) > 0 && $_FILES['filesMultiple']['tmp_name'][0] != '') {

            $target_dir = "../../order_files/";
            $deleted_file_ids = array();

            if (isset($_POST['deleted_file_ids']) && !empty($_POST['deleted_file_ids'])) {
                $deleted_file_ids = explode(",", $_POST['deleted_file_ids']);
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
                    cdp_insertCustomerPackagesFiles($shipment_id, $target_file_db, $image_name, date("Y-m-d H:i:s"), '0', $imageFileType);
                }
            }
        }

        $dataTrack = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'order_track' =>  $order_track,
            't_date' =>  date("Y-m-d H:i:s"),
            'status_courier' =>  cdp_sanitize(intval($status_courier)),
            'comments' =>  $lang['notification_shipment21'],
            'office' => cdp_sanitize(intval($_POST["origin_off"]))
        );

        cdp_insertCourierShipmentTrack($dataTrack);

        $sender_data = cdp_getSenderCourier(intval($_POST["sender_id"]));

        $fullshipment = $code_prefix . $_POST["order_no"];
        $add_status =   intval($status_courier);
        $date_ship   = date("Y-m-d H:i:s a");

        $app_url = $settings->site_url . 'track.php?order_track=' . $fullshipment;
        $subject = $lang['notification_shipment2'] . $lang['notification_shipment6'] .  $fullshipment;

        $email_template = cdp_getEmailTemplatesdg1i4(16);

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
        $userDt = $user->cdp_getUserData();
        if($userDt->email_subscription){
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

                $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

                //Server settings

                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = $smtphoste;                       // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = $smtpuser;                   // SMTP username
                $mail->Password = $smtppass;               // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                //Recipients
                $mail->setFrom($site_email, $names_info);
                $mail->addAddress($destinatario);     // Add a recipient
                $mail->addCC($site_email, $lang['notification_shipment21']);

                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->CharSet = 'UTF-8';
                $mail->Subject = $subject;
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
                    //echo "El correo fue enviado correctamente.";
                } catch (Exception $e) {
                    //echo "Ocurrió un error inesperado.";
                }
            }
        }



        $dataHistory = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'order_track' =>  $order_track,
            'action' => $lang['notification_shipment22'],
            'date_history' =>  cdp_sanitize(date("Y-m-d H:i:s")),
        );

        //INSERT HISTORY USER
        cdp_insertCourierShipmentUserHistory(
            $dataHistory
        );

        $dataNotification = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'notification_description' => $lang['notification_shipment23'],
            'shipping_type' => '4',
            'notification_date' =>  cdp_sanitize(date("Y-m-d H:i:s")),
        );
        // SAVE NOTIFICATION
        cdp_insertNotification(
            $dataNotification
        );

        $notification_id = $db->dbh->lastInsertId();

        //NOTIFICATION TO DRIVER
        cdp_insertNotificationsUsers($notification_id, intval($_POST["driver_id"]));
        //NOTIFICATION TO ADMIN AND EMPLOYEES
        $users_employees = cdp_getUsersAdminEmployees();

        foreach ($users_employees as $key) {
            cdp_insertNotificationsUsers($notification_id, $key->id);
        }
        //NOTIFICATION TO CUSTOMER
        cdp_insertNotificationsUsers($notification_id, intval($_POST['sender_id']));

        $sender_address_data = cdp_getSenderAddress(intval($_POST["sender_address_id"]));
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


        // SAVE ADDRESS FOR Shipments
        $dataAddresses = array(
            'order_id' =>   $shipment_id,
            'order_track' =>   $order_track,
            'sender_country' =>   $final_sender_country->name,
            'sender_state' =>   $final_sender_state->name,
            'sender_city' =>   $final_sender_city->name,
            'sender_zip_code' =>   $sender_zip_code,
            'sender_address' =>   $sender_address,
            'recipient_country' =>   '',
            'recipient_state' =>   '',
            'recipient_city' =>  '',
            'recipient_zip_code' =>   '',
            'recipient_address' =>   '',
        );

        cdp_insertCourierShipmentAddresses($dataAddresses);
        if (isset($_POST['notify_whatsapp_sender']) && $_POST['notify_whatsapp_sender'] == 1) {
            sendNotificationWhatsAppWithPDFPackages($sender_data, $shipment_id, 8);
        }

        $messages[] = $lang['message_ajax_success_add_shipment'];
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
