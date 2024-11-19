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

// ini_set('display_errors', 0);


require_once("../../loader.php");
require_once("../../helpers/querys.php");
require_once("../../helpers/functions.php");
require_once("../../helpers/phpmailer/class.phpmailer.php");
require_once("../../helpers/phpmailer/class.smtp.php");
require_once("../notify_whatsapp/api_whatsapp_service.php");



$user = new User;
$core = new Core;
$errors = array();

$db = new Conexion;

if (empty($_POST['t_date']))

    $errors['t_date'] = $lang['validate_field_ajax159'];

// if (empty($_POST['address']))

//     $errors['address'] = $lang['validate_field_ajax88'];

if (intval($_POST['status_courier']) <= 0)

    $errors['status_courier'] = $lang['validate_field_ajax160'];

// if (intval($_POST['office']) <= 0)

//     $errors['office'] = $lang['validate_field_ajax84'];

// if (empty($_POST['country']))

//     $errors['country'] = $lang['validate_field_ajax102'];



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


    $date = date('Y-m-d', strtotime(cdp_sanitize($_POST["t_date"])));
    $time = date("H:i:s");
    $date = $date . ' ' . $time;

    $status = intval($_POST['status_courier']);
    $shipment_id = intval($_POST['shipment_id']);


    $shipment = cdp_getCourier($shipment_id);


    if ($shipment) {

        $update = updateCourierStatusFromTracking($status, $shipment_id);
		
		if (isset($_FILES['filesMultiple']) && count($_FILES['filesMultiple']['name']) > 0 && $_FILES['filesMultiple']['tmp_name'][0] != '') {

            $target_dir = "../../files/";
            $deleted_file_ids = array();

            if (isset($_POST['deleted_file_ids']) && !empty($_POST['deleted_file_ids'])) {
                $deleted_file_ids = explode(",", $_POST['deleted_file_ids']);
            }
			$order_track = $shipment->order_prefix . $shipment->order_no;
            foreach ($_FILES["filesMultiple"]['tmp_name'] as $key => $tmp_name) {

                if (!in_array($key, $deleted_file_ids)) {
					$image_name = $order_track .  date("Y-m-d-H-i-s") . "_" . basename($_FILES["filesMultiple"]["name"][$key]);
					$target_file = $target_dir . $image_name;
					 $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
					 $imageFileZise = $_FILES["filesMultiple"]["size"][$key];
					if ($imageFileType == 'jpeg' || $imageFileType == 'jpg' || $imageFileType == 'JPEG' || $imageFileType == 'JPG' || $imageFileType == 'png' || $imageFileType == 'PNG' || $imageFileType == 'gif' || $imageFileType == 'GIF') {
							if ($imageFileZise > 0) {
								move_uploaded_file($_FILES["filesMultiple"]["tmp_name"][$key], $target_file);
								$imagen = basename($_FILES["filesMultiple"]["name"][$key]);
							}

							$target_file_db = "files/" . $image_name;
							cdp_insertDeliverFiles($shipment_id, $target_file_db, $image_name, date("Y-m-d H:i:s"), '0', $imageFileType,intval($status));
					}
				}
            }
        }
		
        $order_track = $shipment->order_prefix . $shipment->order_no;

        $dataTrack = array(
            'user_id' =>  $_SESSION['userid'],
            'order_track' =>  $order_track,
            't_date' => $date,
            'status_courier' =>  cdp_sanitize(intval($status)),
            'comments' =>   cdp_sanitize($_POST['comments']),
            'office' => cdp_sanitize(intval($_POST["office"]))
        );

        insertCourierShipmentTrack($dataTrack);

        $dataHistory = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'order_track' =>  $order_track,
            'action' =>  $lang['notification_shipment11'],
            'date_history' =>  cdp_sanitize(date("Y-m-d H:i:s")),
        );

        //INSERT HISTORY USER
        cdp_insertCourierShipmentUserHistory(
            $dataHistory
        );

        $dataNotification = array(
            'user_id' =>  $_SESSION['userid'],
            'order_id' =>  $shipment_id,
            'order_track' =>  $order_track,
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
        cdp_insertNotificationsUsers($notification_id, intval($shipment->sender_id));




        $sender_data = cdp_getSenderCourier(intval($shipment->sender_id));
        $receiver_data = cdp_getRecipientCourier(intval($shipment->receiver_id));

        $fullshipment = $shipment->order_prefix . $shipment->order_no;
        $date_ship   = date("Y-m-d H:i:s a");

        $app_url = $settings->site_url . 'track.php?order_track=' . $fullshipment;
        // $subject = $lang['notification_shipment9'] . ' ' . $lang['notification_shipment6'] .  $fullshipment;
        $subject = $lang['notification_shipment31'] . ' ' . $lang['notification_shipment3'] .  $fullshipment;


        $status_courier_deliver = "" . $_POST['status_courier'] . "";

        $db->cdp_query("SELECT * FROM cdb_styles where id= '" . $status_courier_deliver . "'");
        $status_data = $db->cdp_registro();


        $email_template = cdp_getEmailTemplatesdg1i4(10);

        $body = str_replace(
            array(
                '[NAME]',
                '[TRACKING]',
                '[DELIVERY_TIME]',
                '[COURIER]',
                '[NEW_ADDRESS]',
                '[COMMENT]',
                '[URL]',
                '[URL_LINK]',
                '[SITE_NAME]',
                '[URL_SHIP]'
            ),
            array(
                $sender_data->fname . ' ' . $sender_data->lname,
                $fullshipment,
                $date_ship,
                $status_data->mod_style,
                $_POST['country'] . ' | ' . $_POST['address'],
                $_POST['comments'],
                $msite_url,
                $mlogo,
                $msnames,
                $app_url
            ),
            $email_template->body
        );

        $newbody = cdp_cleanOut($body);
        $userDt = $user->cdp_getUserData();
        if($userDt->email_subscription){     
        //SENDMAIL PHP

            if ($check_mail == 'PHP') {

                $message = $newbody;
                $to = $sender_data->email;
                $from = $site_email;

                $header = "MIME-Version: 1.0\r\n";
                $header .= "Content-type: text/html; charset=UTF-8 \r\n";
                $header .= "From: " . $from . " \r\n";

                mail($to, $subject, $message, $header);
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
                $mail->addCC($site_email,  $lang['notification_shipment9']);

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

        //NOTIFY WHATSAPP API

        if (isset($_POST['notify_whatsapp_sender']) && intval($_POST['notify_whatsapp_sender']) == 1) {
            $notification_result =  sendNotificationWhatsApp($sender_data, 4, null, $fullshipment);
        }

        if (isset($_POST['notify_whatsapp_receiver']) && intval($_POST['notify_whatsapp_receiver']) == 1) {
            $notification_result =  sendNotificationWhatsApp($receiver_data, 4, null, $fullshipment);
        }


        $messages[] = $lang['notification_shipment11'];
    } else {
        $errors['critical_error'] = "Error";
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
        // 'shipment_id' => $shipment_id,
    ]);
}
