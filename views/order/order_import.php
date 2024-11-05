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


require 'helpers/querys.php';
require 'vendor/autoload.php';
require_once("helpers/phpmailer/class.phpmailer.php");
require_once("helpers/phpmailer/class.smtp.php");
use PhpOffice\PhpSpreadsheet\IOFactory;

$errors = array();
$response = array();



if(isset($_POST) && isset($_POST['dosubmit']) && isset($_FILES['excel_file'])){
	

if(!isset($_FILES['excel_file'])) {
$errors['excel_file'] = $lang['flat-price-7'];
}elseif(isset($_FILES['excel_file'])){

$file = $_FILES['excel_file'];
    
    
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];
   
    $allowed = array('xls', 'xlsx');
    
    // Extract file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    // Check if file type is allowed
    if (in_array($fileActualExt, $allowed)) {
		
        if ($fileError === 0) {
		
            if ($fileSize < 5000000) {

					$spreadsheet = IOFactory::load($fileTmpName);					
					$sheetCount = $spreadsheet->getSheetCount();
					
					$data=array();
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
							
							
							$date = date('Y-m-d');
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
							for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
								 $sheet = $spreadsheet->getSheet($sheetIndex);
								  		
							  
							$highestColumn = $sheet->getHighestColumn();
							$highestrow = $sheet->getHighestRow();
							$header = $sheet->rangeToArray('A1:' . $highestColumn . '1', NULL, TRUE, FALSE);
						   for ($row = 2; $row <= $highestrow; $row++) {
						    //for ($row = 5; $row <=6 ; $row++) {
						    
							$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
							
							if(isset($rowData[0])){
								
							$order_data = array_combine($header[0], $rowData[0]);
							
							$error_msg=array();
							$next_order = $core->cdp_order_track();
							$min_cost_tax = $core->min_cost_tax;
							$min_cost_declared_tax = $core->min_cost_declared_tax;
							if($order_data['Sender Country*']=='' || $order_data['Sender Address*']=='' || $order_data['Sender City*']=='' || $order_data['Sender State*']=='' || $order_data['Sender Postal Code*']=='' || $order_data['Recipient Country*']=='' || $order_data['Recipient Address*']=='' || $order_data['Recipient State*']=='' || $order_data['Recipient City*']=='' || $order_data['Recipient Postal Code*']==''){
								
								//send_email
								$error_msg[]= "These fields data are mandatory: Sender Address*, Sender City*, Sender State*, Sender Country*, Sender Postal Code*, Recipient Address*, Recipient City*, Recipient State*,Recipient Country*, Recipient Postal Code*.";
							}else{
								//GET Sender id
								$sender_data=array();
								if($order_data['Sender Username']!=''){
								$sender_data=cdp_getSenderByUsername($order_data['Sender Username']); }
								if(empty($sender_data)){
									 $data = array(
											'username' => cdp_sanitize($order_data['Sender Username']),
											'branch_office' => '',
											'email' => $order_data['Sender Email']?cdp_sanitize($order_data['Sender Email']):'',
											'fname' => $order_data['Sender Fname']?cdp_sanitize($order_data['Sender Fname']):'',
											'lname' => $order_data['Sender Lname']?cdp_sanitize($order_data['Sender Lname']):'',
											'business_type' => $order_data['Sender Business Type']?cdp_sanitize($order_data['Sender Business Type']):'',
											'newsletter' => 1,
											'notes' => '',
											'phone' => $order_data['Sender Phone']?cdp_sanitize($order_data['Sender Phone']):'',
											'gender' => '',
											'userlevel' => 1,
											'active' => 1,
											'password' => '',
											'created' => date("Y-m-d H:i:s")
										);
										$a  = cdp_insertUserImportOrder($data);
										$sender_id = $db->dbh->lastInsertId();
										
								}else{
									$sender_id=$sender_data->id;
								}
								//Get Recipient ID
								
								$data_rec = array(
										'lname' => cdp_sanitize($order_data['Recipient Lname']),
										'fname' => cdp_sanitize($order_data['Recipient Fname']),
										'phone' => cdp_sanitize($order_data['Recipient Phone']),
										'email' => cdp_sanitize($order_data['Recipient Email']),
										'sender_id' => $sender_id,
									);
								$recipient_data = cdp_recipientExist($data_rec);
								
								
								if(isset($recipient_data->id)){
									$recipient_id = $recipient_data->id;
								}else{
									 $recipient_id = cdp_insertRecipient($data_rec);
								}
								 
									//Get Sender Address id
									
									$Sender_country = cdp_getCountryByName($order_data['Sender Country*']);
									if(empty($Sender_country)){
										//send Mail 
										$error_msg[]='Sender Country* "'.$order_data['Sender Country*'].'" did not found into database';
									}else{
										$Sender_state = cdp_stateIdByNameCountry($order_data['Sender State*'],$Sender_country->id);
										if(empty($Sender_state)){
											//send Mail 
											$error_msg[]='Sender State* "'.$order_data['Sender State*'].'" did not found into database';
										}else{
											$Sender_city = cdp_cityIdByNameState($order_data['Sender City*'],$Sender_state->id);
											if(empty($Sender_city)){
											//send Mail 
											$error_msg[]='Sender City* "'.$order_data['Sender City*'].'" did not found into database';
											}else{
												$sender_array=array();
												$sender_array['country']=$Sender_country->id;
												$sender_array['state']=$Sender_state->id;
												$sender_array['city']=$Sender_city->id;
												$sender_array['postal']=$order_data['Sender Postal Code*'];
												$sender_array['address']=$order_data['Sender Address*'];
												$sender_array['user_id']=$sender_id;
													
												 $sender_address=cdp_getSenderAddressWithAll($sender_array);
												 if(empty($sender_address)){
													$sender_address=cdp_insertAddressCustomer($sender_array);
													$sender_address_id = $db->dbh->lastInsertId();
												 }else{
													 $sender_address_id = $sender_address->id_addresses;
												 }
												 
												 $Recipient_country= cdp_getCountryByName($order_data['Recipient Country*']);
									if(empty($Recipient_country)){
										//send Mail 
										$error_msg[]='Recipient Country* "'.$order_data['Recipient Country*'].'" did not found into database';
									}else{
										$Recipient_state = cdp_stateIdByNameCountry($order_data['Recipient State*'],$Recipient_country->id);
										if(empty($Recipient_state)){
											//send Mail 
											$error_msg[]='Recipient State* "'.$order_data['Recipient State*'].'" did not found into database';
										}else{
											$Recipient_city = cdp_cityIdByNameState($order_data['Recipient City*'],$Recipient_state->id);
											if(empty($Recipient_city)){
											//send Mail 
											$error_msg[]='Recipient City* "'.$order_data['Recipient City*'].'" did not found into database';
											}else{
												$recipient_array=array();
												$recipient_array['country']=$Recipient_country->id;
												$recipient_array['state']=$Recipient_state->id;
												$recipient_array['city']=$Recipient_city->id;
												$recipient_array['postal']=$order_data['Recipient Postal Code*'];
												$recipient_array['address']=$order_data['Recipient Address*'];
												$recipient_array['recipient_id']=$recipient_id;
													
												 $recipient_data=cdp_getRecipientAddressWithAll($recipient_array);
												 if(empty($recipient_data)){
													$recipient_data=cdp_insertAddressRecipient($recipient_array);
													$recipient_address_id = $db->dbh->lastInsertId();
												 }else{
													 $recipient_address_id = $recipient_data->id_addresses;
												 }
												
										
												//Calculate Price and distance
												
													$url = 'https://huuneh.com/dashboard/ajax/courier/calculate_distance.php';
													//$url = 'http://localhost:8081/huuneh/ajax/courier/calculate_distance.php';
													$origin=$order_data['Sender Address*'].', '.$order_data['Sender City*'].', '.$order_data['Sender State*'].', '.$order_data['Sender Country*'].', '.$order_data['Sender Postal Code*'];
													$destination=$order_data['Recipient Address*'].', '.$order_data['Recipient City*'].', '.$order_data['Recipient State*'].', '.$order_data['Recipient Country*'].', '.$order_data['Recipient Postal Code*'];
													$data = ['origin'=>$order_data['Sender Address*'], 'destination'=>$order_data['Recipient Address*'], 'deliveryType'=> $order_data['Delivery Type*'], 'sender_id'=> $sender_id,'send_sender_id'=>$sender_id,'send_recipient_id'=> $recipient_id, 'origin_id'=> $sender_address_id, 'destination_id'=> $recipient_address_id];
											
													$ch = curl_init();
													curl_setopt($ch, CURLOPT_URL, $url);
													curl_setopt($ch, CURLOPT_POST, 1);
													curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
													curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

													$response_data = curl_exec($ch);

													if ($response_data === false) {
														$error_msg[]='Error occurred while fetching the data: '. curl_error($ch);
													}

													curl_close($ch);
													$data_price=json_decode($response_data);
													
													if(empty($data_price)){ 
														$error_msg[]=$response_data;
													}else{
														$distance=$data_price->distance;
														 $subtotal=$data_price->shipmentfee;
														if($order_data['Discount']!=''){
															$subtotal=$subtotal-floatval($order_data['Discount']);
														}
														if($order_data['Pieces']!=''){
															$subtotal=$subtotal+(floatval($order_data['Pieces'])*3);
														}
														$subtotal=number_format($subtotal,2);
														$tax_value=13;
														$total_order=number_format(($subtotal+(($subtotal*$tax_value)/100)),2);
													}



									
												 $data = array(
													'user_id' =>  $_SESSION['userid'],
													'order_prefix' =>  $order_prefix,
													'order_incomplete' =>  $order_incomplete,
													'is_pickup' =>  $is_pickup,
													'order_no' => $next_order,
													'order_datetime' =>  cdp_sanitize($date),
													'sender_id' =>  $sender_id,
													'recipient_id' => $recipient_id,
													'sender_address_id' =>  $sender_address_id,
													'recipient_address_id' =>  $recipient_address_id,
													'order_date' =>  date("Y-m-d H:i:s"),
													'order_package' =>  '',
													'order_item_category' =>  '',
				
												   'order_service_options' =>  null,
												   'notes' =>  $order_data['Notes']?cdp_sanitize($order_data['Notes']):'',
													'status_courier' =>  cdp_sanitize(intval($status)),
													'due_date' =>  $due_date,
													'status_invoice' =>  $status_invoice,
													'volumetric_percentage' =>  $meter,
													'charge' =>  0.00,
													'no_of_rx' => 0,
													'notes_for_driver' => $order_data['Notes']?cdp_sanitize($order_data['Notes']):'',
													'admin_discount' =>  $order_data['Discount']?cdp_sanitize($order_data['Discount']):'',
													'no_of_pieces' =>  !empty($order_data['Pieces']) ? $order_data['Pieces'] : 0,
													'total_tax' =>   0,
													'tags' => $order_data['Tags'] ? $order_data['Tags']  : json_encode([]),
													'distance' => $distance??'',
													'sub_total' => $subtotal??'',
													'total_order' => $total_order??'',
													'tax_value' => $tax_value??'',
													'delivery_type' => $order_data['Delivery Type*']?cdp_sanitize($order_data['Delivery Type*']):'',
												);
												

												$order_id = cdp_insertOrderImport($data);
												if($order_id){
													
												//Add order item details
												
												$orderItem = array(
														'order_id' =>  $order_id,
														'qty' =>   1,
														'description' =>  'package',
													);

													cdp_insertCourierShipmentPackages($orderItem);
													
												// SAVE ADDRESS FOR Shipments
												$dataAddresses = array(
													'order_id' =>   $order_id,
													'order_track' =>  $order_prefix . $next_order,
													'sender_country' =>  $order_data['Sender Country*'],
													'sender_state' =>   $order_data['Sender State*'],
													'sender_city' =>   $order_data['Sender City*'],
													'sender_zip_code' =>   $order_data['Sender Postal Code*'],
													'sender_address' =>   $order_data['Sender Address*'],
													'recipient_country' =>  $order_data['Recipient Country*'],
													'recipient_state' =>   $order_data['Recipient State*'],
													'recipient_city' =>   $order_data['Recipient City*'],
													'recipient_zip_code' =>   $order_data['Recipient Postal Code*'],
													'recipient_address' =>   $order_data['Recipient Address*']
												);
												cdp_insertCourierShipmentAddresses($dataAddresses);
												
												//Insert Courier Track
												
												 $dataTrack = array(
													'user_id' =>  $_SESSION['userid'],
													'order_id' =>  $order_id,
													'order_track' =>  $order_prefix . $next_order,
													't_date' =>  date("Y-m-d H:i:s"),
													'status_courier' =>  cdp_sanitize(intval($status)),
													'comments' => $lang['messagesform39'] . ' ' . $order_data['Sender Fname'] . ' ' . $order_data['Sender Lname'],
													'office' =>  null,
												); 

												cdp_insertCourierShipmentTrack($dataTrack);
												
												// Add order User History
												
												 $dataHistory = array(
													'user_id' =>  $_SESSION['userid'],
													'order_id' =>  $order_id,
													'action' =>  $lang['notification_shipment8'],
													'date_history' =>  cdp_sanitize(date("Y-m-d H:i:s")),
													'order_track' =>  $order_prefix . $next_order,
												);

												
												cdp_insertCourierShipmentUserHistory($dataHistory);
												
												//Add Notification for order 
												
												$dataNotification = array(
													'user_id' =>  $_SESSION['userid'],
													'order_id' =>  $order_id,
													'notification_description' => $lang['notification_shipment'],
													'shipping_type' => '1',
													'notification_date' =>  cdp_sanitize(date("Y-m-d H:i:s")),
												);
												// SAVE NOTIFICATION
												cdp_insertNotification($dataNotification);
												
												$notification_id = $db->dbh->lastInsertId();

													//NOTIFICATION TO ADMIN AND EMPLOYEES
													$users_employees = cdp_getUsersAdminEmployees();

													foreach ($users_employees as $key) {
														cdp_insertNotificationsUsers($notification_id, $key->id);
													}
													//NOTIFICATION TO CUSTOMER
													cdp_insertNotificationsUsers($notification_id, intval($sender_id));
												}else{
													$error_msg[]='Order not created!';
												}
												}
										}
									}
											}
										}
									}
												
																			
										
								}
								
								if(!empty($error_msg) && count($error_msg)>0){
								
								//Sent Admin Email
								
								$subject = "Import Order Failed";


        $email_template = cdp_getEmailTemplatesdg1i4(28);

        $body = str_replace(
            array(
                '[excel_no]',
                '[Sender_Username]',
                '[Sender_Fname]'
                
            ),
            array(
                $order_data['No'],
                $order_data['Sender Username'],
                $order_data['Sender Fname']
            ),
            $email_template->body
        );

        $newbody = cdp_cleanOut($body);

        //SENDMAIL PHP to the user
        
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
               //$destinatario = $site_email;
               $destinatario = "kam.2391@gmail.com";
                      
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
                   // $estadoEnvio = $mail->Send();
                    // echo "El correo fue enviado correctamente.";
                } catch (Exception $e) {
                    // echo "Ocurrió un error inesperado.";
                }
            }
			
							}
						
							
        
		
		
							}
							
						}
						$response['status']= "success";
						$response['msg']= $lang['flat-price-11'];
					}
					
            } else {
                 $errors['excel_file']= $lang['flat-price-8'];
            }
        } else {
             $errors['excel_file']= $lang['flat-price-9'];
        }
    } else {
         $errors['excel_file']= $lang['flat-price-10'];
    }
}	
}

if (!$user->cdp_is_Admin())
    cdp_redirect_to("login.php");


$userData = $user->cdp_getUserData();

$db = new Conexion;

$db->cdp_query("SELECT * FROM cdb_info_ship_default where id= '1'");
$infoship = $db->cdp_registro();


$db->cdp_query("SELECT * FROM cdb_category where id= '" . $infoship->logistics_default1 . "'");
$s_logistics = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_packaging where id= '" . $infoship->packaging_default2 . "'");
$packaging_box = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $infoship->courier_default3 . "'");
$courier_comp = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_shipping_mode where id= '" . $infoship->service_default4 . "'");
$ship_modes = $db->cdp_registro();

?>
<!DOCTYPE html>
<html dir="<?php echo $direction_layout; ?>" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/<?php echo $core->favicon ?>">
    <title><?php echo $lang['leftorder300'] ?>| <?php echo $core->site_name ?></title>
    <?php include 'views/inc/head_scripts.php'; ?>
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">

    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 35px !important;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }
    </style>
	
</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->


    <?php include 'views/inc/preloader.php'; ?>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->

        <?php include 'views/inc/topbar.php'; ?>

        <!-- End Topbar header -->


        <!-- Left Sidebar - style you can find in sidebar.scss  -->

        <?php include 'views/inc/left_sidebar.php'; ?>

        <?php $packrow = $core->cdp_getPack(); ?>
        <?php $moderow = $core->cdp_getShipmode(); ?>
        <?php $courierrow = $core->cdp_getCouriercom(); ?>
        <?php $categories = $core->cdp_getCategories(); ?>


        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            <!-- Action part -->
            <!-- Button group part -->
            <div class="bg-light">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-12">
                                <!-- <div id="loader" style="display:none"></div> -->
                                <div id="resultados_ajax"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Action part -->


            <div class="container-fluid mb-4">

                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">

                                 <div class="d-md-flex">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['order-import-1'] ?></span></h3>
                                    </div>
									
                                </div>
                                <div><hr><br></div>

                                <form class="form-horizontal form-material" id="save_data" name="save_data" method="post" enctype="multipart/form-data">
                                    <section>
                                        <div class="row">
                                        
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> <?php echo $lang['flat-price-5'] ?></label>
                                                   <input type="file" name="excel_file" id="excel_file"  accept=".xls, .xlsx">
                                                </div>
                                            </div>
                                           
                                        </div>

                                       
                                    </section>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit">
                                                <i class="ti-upload"></i> <?php echo $lang['flat-price-3'] ?>
                                                <span><i class="icon-ok"></i></span></button>
                                            <a href="pickup_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['global-buttons-3'] ?></a>
											<a href="https://huuneh.com/dashboard/order_files/order_import_data_sample.xlsx" class="btn btn-outline-primary" download="order.xlsx" target="_blank"><span><i class="ti-download"></i> Download Format</span></a>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
            </div>

            <?php include 'views/inc/footer.php'; ?>

        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->

    <?php include('helpers/languages/translate_to_js.php'); ?>

    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="dataJs/flat_price_import.js"></script>
	<script>
	$(function () {

	<?php if(count($errors)>0){ ?>
			
			Swal.fire({
                   title: message_error_form6,
                    text: '<?php echo implode(', ',$errors); ?>',
                    type: 'error',
                    confirmButtonColor: '#336aea',
                    showConfirmButton: true,
                    allowOutsideClick: false,
                });
				
		<?php }elseif(isset($response['status']) && $response['status']== "success"){	?>
			
			 Swal.fire({
                        type: 'success',
                        title: '<?php echo $response['msg']; ?>',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    }).then(() => {
                        // Redirigir al listado de clientes
                       window.location.href = 'pickup_list.php';
                    });
				
		<?php }	?>
		   
		   
		});
</script>
</body>

</html>