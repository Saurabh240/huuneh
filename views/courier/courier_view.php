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



require_once('helpers/querys.php');




$userData = $user->cdp_getUserData();
$core = new Core;
$db = new Conexion;

if (isset($_GET['id'])) {
    $data = cdp_getCourierPrint($_GET['id']);
}

if (!isset($_GET['id']) or $data['rowCount'] != 1) {
    cdp_redirect_to("courier_list.php");
}

if (isset($_GET['id_notification'])) {
    # code...

    $user_log = $_SESSION['userid'];
    $id_notification = $_GET['id_notification'];

    cdp_updateNotificationRead($user_log, $id_notification);
}



$row_order = $data['data'];

$db->cdp_query("SELECT * FROM cdb_styles where id= '" . $row_order->status_courier . "'");
$status_courier = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row_order->sender_id . "'");
$sender_data = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_recipients where id= '" . $row_order->receiver_id . "'");
$receiver_data = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row_order->order_prefix . $row_order->order_no . "'");
$address_order = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $row_order->order_courier . "'");
$courier_com = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_category where id= '" . $row_order->order_item_category . "'");
$category = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_shipping_mode where id= '" . $row_order->order_service_options . "'");
$order_service_options = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_packaging where id= '" . $row_order->order_package . "'");
$packaging = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_delivery_time where id= '" . $row_order->order_deli_time . "'");
$delivery_time = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_branchoffices where id= '" . $row_order->agency . "'");
$branchoffices = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_offices where id= '" . $row_order->origin_off . "'");
$offices = $db->cdp_registro();


$db->cdp_query("SELECT * FROM cdb_add_order_item WHERE order_id='" . $_GET['id'] . "'");
$order_items = $db->cdp_registros();

$dias_ = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
$meses_ = array(
    '01' => $lang['translate_graphic_0'],
    '02' => $lang['translate_graphic_1'],
    '03' => $lang['translate_graphic_2'],
    '04' => $lang['translate_graphic_3'],
    '05' => $lang['translate_graphic_4'],
    '06' => $lang['translate_graphic_5'],
    '07' => $lang['translate_graphic_6'],
    '08' => $lang['translate_graphic_7'],
    '09' => $lang['translate_graphic_8'],
    '10' => $lang['translate_graphic_9'],
    '11' => $lang['translate_graphic_10'],
    '12' => $lang['translate_graphic_11']
);


$fecha = strtotime($row_order->order_datetime);
$anio = date("Y", $fecha);
$mes = date("m", $fecha);
$dia = date("d", $fecha);

if ($row_order->status_invoice == 1) {

    $text_status = $lang['invoice_paid'];
    $label_class = "label-success";
} else if ($row_order->status_invoice == 2) {

    $text_status = $lang['invoice_pending'];
    $label_class = "label-warning";
} else if ($row_order->status_invoice == 3) {
    $text_status = $lang['verify_payment'];
    $label_class = "label-info";
}

// These are  basically delivery types.
$moderow = $core->cdp_getShipmode(); 

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
    <title> <?php echo $lang['left492'] ?> <?php echo $row_order->order_prefix . $row_order->order_no; ?> | <?php echo $core->site_name ?></title>
    <!-- This Page CSS -->
    <!-- Custom CSS -->
    <?php include 'views/inc/head_scripts.php'; ?>

</head>

<body>
<!-- <php echo"<pre>"; print_r($row_order); die("it is here"); ?> -->
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



        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">


            <div class="">
                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card" >
                            <div class="card-body">

                                <div class="mb-3" id="resultados_ajax_cancel"></div>
                                <div class="mb-3" id="resultados_ajax"></div>

                                <div class="row"> 
                                    <div class=" col-sm-12 col-md-6 mb-2">
                                        <h3><b class="text-danger"><?php echo $lang['left533020013'] ?></b> <span>#<?php echo $row_order->order_prefix . $row_order->order_no; ?></span></h3>
                                    </div>

                                    <?php if ($row_order->status_courier != 14) { ?>

                                        <div class=" col-sm-12 col-md-6 mb-2">
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-block btn-outline-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo $lang['left533020014'] ?>
                                                    </button>
                                                    <div class="dropdown-menu scrollable-menu" style="overflow-y: auto; max-height: 500px;">

                                                        <?php if ($row_order->status_invoice == 2) { ?>
                                                            <?php if ($userData->userlevel == 1) { ?>
                                                                <a class="dropdown-item" href="add_payment_gateways_courier.php?id_order=<?php echo $row_order->order_id; ?>">
                                                                    <i style="color:#343a40" class="fas fa-dollar-sign"></i>&nbsp;<?php echo $lang['left533020015'] ?></a>
                                                            <?php } ?>
                                                        <?php } ?>


                                                        <?php if ($row_order->status_invoice == 3) { ?>
                                                            <?php if ($userData->userlevel != 1) { ?>
                                                                <a class="dropdown-item" data-toggle="modal" data-target="#detail_payment_packages" data-id="<?php echo $row_order->order_id; ?>" data-customer="<?php echo $row_order->sender_id; ?>">
                                                                    <i style="color:#343a40" class="fas fa-dollar-sign"></i>&nbsp; <?php echo $lang['left533020016'] ?></a>
                                                            <?php } ?>

                                                        <?php } ?>


                                                        <?php if ($row_order->order_incomplete == 0) { ?>
                                                            <?php if ($row_order->is_pickup == 0) { ?>

                                                                <?php if ($userData->userlevel != 1) { ?>

                                                                    <a class="dropdown-item" href="courier_accept.php?id=<?php echo $row_order->order_id; ?>">
                                                                        <i style="color:#343a40" class="ti-pencil"></i>&nbsp; <?php echo $lang['left533020017'] ?></a>
                                                                <?php } ?>

                                                                <a class="dropdown-item" href="print_label_ship.php?id=<?php echo $row_order->order_id; ?>" target="_blank">
                                                                    <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toollabel'] ?> </a>
                                                            <?php } ?>
                                                        <?php } ?>


                                                        <?php if ($row_order->order_incomplete == 1) { ?>

                                                            <?php if ($row_order->is_consolidate == 0) { ?>

                                                                <?php if ($row_order->status_courier != 8) { ?>

                                                                    <?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>

                                                                        <a class="dropdown-item" href="courier_edit.php?id=<?php echo $_GET['id']; ?>">
                                                                            <i style="color:#343a40" class="ti-pencil"></i>&nbsp;<?php echo $lang['tooledit'] ?></a>

                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>


                                                            <?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>

                                                                <?php if ($row_order->status_courier != 21) { ?>
                                                                    <?php if ($row_order->status_courier != 12) { ?>
                                                                        <?php if ($row_order->status_invoice != 1) { ?>
                                                                            <a class="dropdown-item" data-toggle="modal" data-target="#charges_list" data-id="<?php echo $row_order->order_id; ?>"><i style="color:#343a40" class="fas fa-dollar-sign"></i>&nbsp; <?php echo $lang['left533020018'] ?></a>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>


                                                            <?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>

                                                                <?php if ($row_order->status_courier != 8) { ?>
                                                                    <?php if ($row_order->status_courier != 21) { ?>
                                                                        <?php if ($row_order->status_courier != 12) { ?>

                                                                            <a class="dropdown-item" data-toggle="modal" data-target="#modalDriver" data-id_shipment="<?php echo $row_order->order_id; ?>"><i style="color:#ff0000" class="fas fa-car"></i>&nbsp; <?php echo $lang['left208'] ?></a>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>



                                                            <?php if ($row_order->status_courier != 21) { ?>
                                                                <?php if ($row_order->status_courier != 12) { ?>
                                                                    <a class="dropdown-item" target="blank" href="print_inv_ship.php?id=<?php echo $_GET['id']; ?>">
                                                                        <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toolprint'] ?></a>

                                                                    <a class="dropdown-item" href="print_label_ship.php?id=<?php echo $_GET['id']; ?>" target="_blank"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toollabel'] ?> </a>


                                                                    <?php if ($userData->userlevel == 9 || $userData->userlevel == 3 || $userData->userlevel == 2) { ?>

                                                                        <?php if ($row_order->is_consolidate == 0) { ?>

                                                                            <?php if ($row_order->status_courier != 8) { ?>
                                                                                <?php if ($row_order->status_courier != 21) { ?>
                                                                                    <?php if ($row_order->status_courier != 12) { ?>


                                                                                        <a class="dropdown-item" href="courier_shipment_tracking.php?id=<?php echo $_GET['id']; ?>" title="<?php echo $lang['toolupdate'] ?>"><i style="color:#20c997" class="ti-reload">&nbsp;</i><?php echo $lang['toolupdate'] ?></a>

                                                                                        <a class="dropdown-item" href="courier_deliver_shipment.php?id=<?php echo $row_order->order_id; ?>" title="<?php echo $lang['tooldeliver'] ?>"><i style="color:#2962FF" class="ti-package"></i>&nbsp;<?php echo $lang['tooldeliver'] ?></a>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>


                                                                    <?php if ($userData->userlevel == 9 || $userData->userlevel == 3 || $userData->userlevel == 2) { ?>

                                                                        <a class="dropdown-item" href="#" data-toggle="modal" data-id="<?php echo $row_order->order_id; ?>" data-email="<?php echo $sender_data->email; ?>" data-order="<?php echo $row_order->order_prefix . $row_order->order_no; ?>" data-target="#myModal">
                                                                            <i class="fas fa-envelope"></i>&nbsp;<?php echo $lang['left533020019'] ?></a>

                                                                    <?php } ?>

                                                                <?php } ?>

                                                            <?php } ?>

                                                        <?php } ?>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    <?php } else { ?>

                                        <div class=" col-sm-12 col-md-6 mb-2">
                                            <div class="pull-right">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <?php echo $lang['left533020014'] ?>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <?php if ($userData->userlevel == 9 || $userData->userlevel == 3 || $userData->userlevel == 2) { ?>
                                                            <?php if ($row_order->status_courier == 14) { ?>
                                                                <a class="dropdown-item" href="pickup_accept.php?id=<?php echo $row_order->order_id; ?>"> <i style="color:#36bea6" class="fas fa-check-circle"></i>&nbsp; <?php echo $lang['left533020020'] ?></a>
                                                            <?php
                                                            }else{
                                                            ?>

                                                            <?php } ?>
                                                            <a class="dropdown-item" data-id="<?php echo $row_order->order_id; ?>" href="#" data-toggle="modal" data-target="#myModalCancel"><i style="color:#f62d51" class="fas fa-times-circle"></i>&nbsp; <?php echo $lang['left533020021'] ?></a>
                                                        <?php
                                                        }else{ ?>
                                                                <span class="label" style="background-color: <?php echo $status_courier->color; ?>"><?php echo $status_courier->mod_style; ?>
                                        
                                                        <?php }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php

                                    } ?>

                                </div>

                                <div class="row">

                                    <div class=" col-sm-12 col-md-6 mb-2">
                                        <b class=""><?php echo $lang['left506'] ?></b>
                                        
                                        

                                        <?php if ($row_order->is_pickup != 0) { ?>
                                            <?php if ($row_order->status_courier == 14) { ?>
                                                <span style="background: #5BE472;" class="label label-large"><?php echo $lang['left533020020']; ?></span>

                                            <?php }else{ ?>
                                                
                                                <?php
                                                    if ($row_order->status_courier == 12 || $row_order->status_courier == 21 || $row_order->status_courier == 8) { ?>

                                                        <span style="background: <?php echo $status_courier->color; ?>;" class="label label-large"><?php echo $status_courier->mod_style; ?></span>
                                                    <?php
                                                    }else{
                                                ?>

                                                    <span class="label" style="background-color: <?php echo $status_courier->color; ?>"><?php echo $status_courier->mod_style; ?>
                                                    </span>
                                                    <?php } ?>
                                                <?php } ?>
                                        <?php } ?>
                                       

                                    </div>

                                    <div class=" col-sm-12 col-md-6 mb-2">
                                        <b class=""><?php echo $lang['left533020022'] ?></b>
                                        <span class="label <?php echo $label_class; ?>"><?php echo $text_status; ?>
                                        </span>
                                    </div>

                                </div>
                                <br>

                                <div class="row">
                                    <!-- <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php // echo $lang['tools-branchOffice4'] ?></b></h5>
                                            <p class="text-muted  m-l-5">
                                                <?php // if ($branchoffices != null) {
                                                   // echo $branchoffices->name_branch;
                                                //} ?></p>
                                        </div>
                                    </div> -->

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <!-- <div class="">
                                            <h5> &nbsp;<b><?php //echo $lang['tools-office1'] ?></b></h5>
                                            <p class="text-muted  m-l-5">
                                                <?php //if ($offices != null) {
                                                  //  echo $offices->name_off;
                                               // } ?></p>
                                        </div> -->
                                    </div>


                                    <!-- <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php // echo $lang['itemcategory'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php //echo $category->name_item; ?></p>
                                        </div>
                                    </div> -->
                                </div>


                                <div class="row">
                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['track-shipment19'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $meses_[$mes] . ' ' . $dia . ', ' . $anio; ?></p>

                                            <!-- <h5> &nbsp;<b><?php // echo $lang['langs_034'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php  if ($delivery_time != null) {
                                                                                 echo $delivery_time->delitime;
                                                                            } ?></p> -->
                                        </div>

                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <!-- Delivery Type added -->
                                              <h5> &nbsp;<b>Delivery Type</b></h5>

                                            <!-- <p class="text-muted  m-l-5"> -->
                                            <?php date_default_timezone_set("America/Montreal"); ?>
											<div>
                                                    <div class="">
                                                <div class="input-group mb-3">
													<select class="form-control custom-select" id="deliveryType" name="deliveryType" required style="width: 100%;">
														<option value="" selected>Select Delivery Type</option>
														<option <?php if(time() > strtotime("12:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?>value="SAME DAY (1PM to 4PM)"
                                                        <?php if($row_order->delivery_type == "SAME DAY (1PM to 4PM)") { ?> selected <?php } ?>
                                                        >SAME DAY (1PM to 4PM)</option>
														<option <?php if(time() > strtotime("12:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="SAME DAY (BEFORE 5PM)"
                                                        <?php if($row_order->delivery_type == "SAME DAY (BEFORE 5PM)") { ?> selected <?php } ?>
                                                        >SAME DAY (BEFORE 5PM)</option>
														<option <?php if(time() > strtotime("1:30 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (4 HOURS)"
                                                        <?php if($row_order->delivery_type == "RUSH (4 HOURS)") { ?> selected <?php } ?>
                                                        >RUSH (4 HOURS)</option>
														<option <?php if(time() > strtotime("2:30 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (3 HOURS)"
                                                        <?php if($row_order->delivery_type == "RUSH (3 HOURS)") { ?> selected <?php } ?>
                                                        >RUSH (3 HOURS)</option>
														<option <?php if(time() > strtotime("9:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (2 HOURS)"
                                                        <?php if($row_order->delivery_type == "RUSH (2 HOURS)") { ?> selected <?php } ?>
                                                        >RUSH (2 HOURS)</option>
														<option <?php if(time() > strtotime("9:00 PM")) { echo "disabled='disabled' class='disabled-cls'"; }?> value="URGENT (90 MINUTES)"
                                                        <?php if($row_order->delivery_type == "URGENT (90 MINUTES)") { ?> selected <?php } ?>
                                                        >URGENT (90 MINUTES)</option>
														<option value="NEXT DAY (BEFORE 5PM)"
                                                        <?php if($row_order->delivery_type == "NEXT DAY (BEFORE 5PM)") { ?> selected <?php } ?>
                                                        >NEXT DAY (BEFORE 5PM)</option>
														<option value="NEXT DAY (BEFORE 2PM)"
                                                        <?php if($row_order->delivery_type == "NEXT DAY (BEFORE 2PM)") { ?> selected <?php } ?>
                                                        >NEXT DAY (BEFORE 2PM)</option>
														<option value="NEXT DAY (BEFORE 11:30AM)"
                                                        <?php if($row_order->delivery_type == "NEXT DAY (BEFORE 11:30AM)") { ?> selected <?php } ?>
                                                        >NEXT DAY (BEFORE 11:30AM)</option>
														<option value="NEXT DAY (BEFORE 10:30AM)"
                                                        <?php if($row_order->delivery_type == "NEXT DAY (BEFORE 10:30AM)") { ?> selected <?php } ?>
                                                        >NEXT DAY (BEFORE 10:30AM)</option>
                                                      
													</select>
												</div>
											</div>

                                                    </div>
											</div>                                            

                                            </p>
                                                                        
                                            

                                            <!-- <h5> &nbsp;<b><?php  echo $lang['tools-courier1'] ?></b></h5>

                                            <p class="text-muted  m-l-5"><?php  if ($courier_com != null) {
                                                                                echo $courier_com->name_com;
                                                                            } ?></p> -->
                                            <!-- <h5> &nbsp;<b><?php  echo $lang['tools-shipmode1'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php  if ($order_service_options != null) {
                                                                                echo $order_service_options->ship_mode;
                                                                            } ?></p> -->
                                        </div>
                                    </div>

                                    <?php if ($row_order->status_courier == 21) { ?>
                                        <?php if ($row_order->reason_cancel != null) { ?>

                                            <div class="col-md-12 pt-4">
                                                <div class="">
                                                    <h5> &nbsp;<b><?php echo $lang['left533020023'] ?></b></h5>
                                                    <b class="text-danger  m-l-5">
                                                        <?php if ($row_order->reason_cancel != null) {
                                                            echo $row_order->reason_cancel;
                                                        } ?></b>
                                                </div>
                                            </div>

                                    <?php

                                        }
                                    }

                                    ?>
                                </div>

                                <?php
                                $track_c = $row_order->order_prefix . $row_order->order_no;


                                $db->cdp_query("SELECT * FROM cdb_payments_gateway  where order_track ='" . $track_c . "'");

                                $order_ = $db->cdp_registro();
                                
                                if ($order_) {

                                    if ($order_->status === 'COMPLETED' || $order_->status === 'succeeded' || $order_->status === 'success') {
                                        $text_status_payment = $lang['left533020024'];
                                        $label_class_payment = "label-success";
                                    } else {

                                        $text_status_payment = $order_->status;
                                        $label_class_payment = "label-warning";
                                    }
                                ?>

                                    <div class="row">

                                        <div class=" col-sm-12 col-md-12 mb-2">
                                            <br>
                                            <br>

                                            <h4><span><b><?php echo $lang['tools-config118'] ?></b></span></h4>
                                            <br>
                                            <br>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b><?php echo $lang['leftorder157'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo date('Y-m-d h:i A', strtotime($order_->date_payment)); ?></p>
                                            </div>

                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b><?php echo $lang['left533020025'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo $order_->gateway; ?></p>
                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">

                                            <div class="">
                                                <h5> &nbsp;<b><?php echo $lang['left533020026'] ?></b></h5>

                                                <b class="text-muted  m-l-5"><?php echo $order_->payment_transaction; ?></b>
                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b><?php echo $lang['payment5'] ?></b></h5>
                                                <b class="text-muted  m-l-5"><?php echo $order_->amount; ?></b>
                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b><?php echo $lang['tools-config52'] ?></b></h5>
                                                <b class="text-muted  m-l-5"><?php echo $order_->currency; ?></b>
                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b><?php echo $lang['tools-statuscourier7'] ?></b></h5>
                                                <span class="label <?php echo $label_class_payment; ?>"><?php echo $text_status_payment; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                <?php

                                }

                                ?>

                                <?php

                                if ($row_order->url_payment_attach != null || $row_order->status_invoice == 3) { ?>
                                    <div class="row">
                                        <div class=" col-sm-12 col-md-12 mb-2">
                                            <br>
                                            <br>

                                            <h5><span> <?php echo $lang['left533020027'] ?></span></h5>

                                        </div>

                                        <div class=" col-sm-12 col-md-12 mb-2">
                                            <a href="assets/<?php echo $row_order->url_payment_attach; ?>" target="blank" class="btn btn-info text- btn-sm">
                                                <?php echo $lang['left533020028'] ?>
                                            </a>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <br>
                                            <br>
                                            <div>
                                                <h5> &nbsp;<b><?php echo $lang['leftorder157'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo date('Y-m-d h:i A', strtotime($row_order->payment_date)); ?></p>
                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <br>
                                            <br>
                                            <div>
                                                <h5> &nbsp;<b><?php echo $lang['left603'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo $met_payment->name_pay; ?></p>
                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <br>
                                            <br>

                                            <div>
                                                <h5> &nbsp;<b><?php echo $lang['user_manage31'] ?></b></h5>

                                                <b class="text-muted  m-l-5">
                                                    <?php if ($row_order->notes != null) {
                                                        echo $row_order->notes;
                                                    } ?></b>
                                            </div>
                                        </div>
                                    </div>

                                <?php

                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <?php
                if ($row_order->status_courier == 8) {


                    $db->cdp_query("SELECT * FROM cdb_courier_track where order_track='" . $row_order->order_prefix . $row_order->order_no . "'");
                    $courier_track = $db->cdp_registro();

                    $fecha_delivered = strtotime($courier_track->t_date);
                    $anio_delivered = date("Y", $fecha_delivered);
                    $mes_delivered = date("m", $fecha_delivered);
                    $dia_delivered = date("d", $fecha_delivered);
                    $time_delivered = date("h:i A", $fecha_delivered);


                    $db->cdp_query("SELECT * FROM cdb_users where id='" . $courier_track->user_id . "'");
                    $user_delivered = $db->cdp_registro();

                ?>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-md-flex align-items-center">
                                        <div>
                                            <h3 class="card-title"><span><?php echo $lang['left533020029'] ?></span></h3>
                                        </div>
                                    </div>
                                    <div><hr></div>
                                    <div class="row">
                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b> <?php echo $lang['leftorder51'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo $meses_[$mes_delivered] . ' ' . $dia_delivered . ', ' . $anio_delivered . ' ' . $time_delivered; ?></p>

                                            </div>
                                        </div>

                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b> <?php echo $lang['leftorder52'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo $user_delivered->fname . ' ' . $user_delivered->lname; ?></p>
                                            </div>
                                        </div>
                                        <div class=" col-sm-12 col-md-4 mb-2">
                                            <div class="">
                                                <h5> &nbsp;<b> <?php echo $lang['leftorder53'] ?></b></h5>
                                                <p class="text-muted  m-l-5"><?php echo $row_order->person_receives; ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    $dir = 'doc_signs/shipments_courier/' . $row_order->order_id . '.png';

                                    ?>
                                    <div id="row_order" style="display:none;"><?php var_dump($row_order);?></div>
                                    <div class="row">
                                        <div class=" col-sm-12 col-md-6 mb-2">
                                            <h5> &nbsp;<b> <?php echo $lang['leftorder54'] ?></b></h5>
                                            <a href="doc_signs/shipments_courier/<?php echo $row_order->order_id; ?>.png" target="_blank"><?php echo $lang['leftorder54'] ?></a>
                                            <!-- <img src="doc_signs/shipments_courier/<?php echo $row_order->order_id; ?>.png" style="max-width:100%;width:auto;height:auto;"> -->
                                        </div>
                                        <?php

                                        if (!empty($row_order->photo_delivered)) { ?>

                                            <div class=" col-sm-12 col-md-6 mb-2">
                                                <h5> &nbsp;<b> <?php echo $lang['leftorder55'] ?></b></h5>
                                                <a href="<?php echo $row_order->photo_delivered; ?>" target="_blank"><?php echo $lang['leftorder55'] ?></a>
                                                <!-- <img src="<?php echo $row_order->photo_delivered; ?>" width="400" height="250" style="max-width:100%;width:auto;height:auto;"> -->
                                            </div>
                                        <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

                <?php

                $db->cdp_query("SELECT * FROM cdb_order_files where order_id='" . $_GET['id'] . "' ORDER BY date_file");
                $files_order = $db->cdp_registros();
                $numrows = $db->cdp_rowCount();


                if ($numrows > 0) {
                ?>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-md-flex align-items-center">
                                        <div>
                                            <h3 class="card-title"><span><?php echo $lang['leftorder56'] ?></span></h3>
                                        </div>
                                    </div>
                                    <div><hr></div> 
                                    <div class="table-responsive">
                                        <table id="zero_config" class="table table-striped">
                                            <thead class="bg-inverse text-white">
                                                <tr>
                                                    <th><?php echo $lang['left533020029'] ?></th>
                                                    <th><?php echo $lang['left533020030'] ?></th>
                                                    <th><?php echo $lang['left533020031'] ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="projects-tbl">

                                                <?php
                                                $count = 0;
                                                foreach ($files_order as $file) {
                                                    $date_add = date("Y-m-d h:i A", strtotime($file->date_file));
                                                    $count++;
                                                ?>

                                                    <tr class="card-hover">
                                                        <td><?php echo $count; ?></td>
                                                        <td> <a style="color:#7460ee;" target="_blank" href="<?php echo $file->url; ?>" class=""><?php echo $file->name; ?> </a></td>
                                                        <td><?php echo $date_add; ?></td>

                                                    </tr>
                                                <?php
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>


                <?php

                $db->cdp_query("SELECT * FROM cdb_order_files where order_id='" . $_GET['id'] . "' ORDER BY date_file");
                $files_order = $db->cdp_registros();
                $numrows = $db->cdp_rowCount();

                if ($numrows > 0) {
                ?>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-md-flex align-items-center">
                                        <div>
                                            <h3 class="card-title"><span><?php echo $lang['leftorder59'] ?></span></h3>
                                        </div>
                                    </div>
                                    <div><hr></div>
                                    <div class="col-md-12 row">

                                        <?php
                                        $count = 0;
                                        foreach ($files_order as $file) {

                                            $date_add = date("Y-m-d h:i A", strtotime($file->date_file));

                                            $src = 'assets/images/no-preview.jpeg';

                                            if (
                                                $file->file_type == 'jpg' ||
                                                $file->file_type == 'jpeg' ||
                                                $file->file_type == 'png' ||
                                                $file->file_type == 'ico'
                                            ) {

                                                $src = $file->url;
                                            }

                                            $count++;
                                        ?>

                                            <div class=" col-sm-12 col-md-3 mb-2">

                                                <img style="width: 180px; height: 180px;" class="img-thumbnail" src="<?php echo $src; ?>">

                                                <div class="row ">
                                                    <div class=" col-md-12 mb-2 mt-2">
                                                        <p class="text-justify"><a style="color:#7460ee;" target="_blank" href="<?php echo $file->url; ?>" class=""><?php echo $file->name; ?> </a></p>

                                                    </div>

                                                </div>
                                            </div>
                                        <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>


                <?php

                $db->cdp_query("SELECT * FROM cdb_courier_track where order_track='" . $row_order->order_prefix . $row_order->order_no . "' ORDER BY t_date");
                $courier_track_items = $db->cdp_registros();
                $numrows = $db->cdp_rowCount();
                ?>

                <?php
                if ($user->cdp_is_Admin()) {

                    $db->cdp_query("SELECT * FROM cdb_order_user_history where order_id='" . $_GET['id'] . "' ORDER BY history_id");
                    $db->cdp_query("SELECT * FROM cdb_order_user_history where order_track='" . $row_order->order_prefix . $row_order->order_no . "' ORDER BY history_id");

                    $order_user_history = $db->cdp_registros();
                    $numrows = $db->cdp_rowCount();

                    if ($numrows > 0) {
                ?>
                        <!-- <div class="row">
                            <div class="col-lg-12 col-xl-12 col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-md-flex align-items-center">
                                            <div>
                                                <h3 class="card-title"><span><?php echo $lang['left533020001'] ?></span></h3>
                                            </div>
                                        </div>
                                        <div><hr></div>

                                        <div class="table-responsive">
                                            <table id="zero_config" class="table table-striped">
                                                <thead class="bg-inverse text-white">
                                                    <tr>
                                                        <th><?php echo $lang['left503'] ?></th>
                                                        <th><?php echo $lang['left504'] ?></th>
                                                        <th><?php echo $lang['left533020002'] ?></th>
                                                        <th><?php echo $lang['left533020003'] ?></th>
                                                        <th><?php echo $lang['left533020004'] ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="projects-tbl">

                                                    <?php
                                                    foreach ($order_user_history as $track_item) {

                                                        $date_update = date("Y-m-d", strtotime($track_item->date_history));
                                                        $time_update = date("h:i A", strtotime($track_item->date_history));


                                                        $db->cdp_query("SELECT * FROM cdb_users where id= '" . $track_item->user_id . "'");
                                                        $sender_data2 = $db->cdp_registro();


                                                        $role = '';

                                                        switch ($sender_data2->userlevel) {
                                                            case '1':
                                                                $role =  $lang['left533020005'];
                                                                break;

                                                            case '2':

                                                                $role =  $lang['left533020006'];

                                                                break;

                                                            case '3':

                                                                $role = $lang['left533020007'];

                                                                break;

                                                            case '9':

                                                                $role =  $lang['left533020008'];

                                                                break;

                                                            default:
                                                                # code...
                                                                break;
                                                        }

                                                    ?>
                                                        <tr class="card-hover">
                                                            <td><?php echo $date_update; ?></td>
                                                            <td><?php echo $time_update; ?></td>
                                                            <td><?php echo $sender_data2->fname . ' ' . $sender_data2->lname; ?></td>
                                                            <td><?php echo $role; ?></td>
                                                            <td><?php echo $track_item->action; ?></td>

                                                        </tr>
                                                    <?php
                                                    } ?>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> -->
                <?php
                    }
                } ?>


                <!-- Row SENDER DETAILS -->
                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['left533020010'] ?></span></h3>
                                    </div>
                                </div>
                                <div><hr></div>

                                <div class="row">

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien6'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $sender_data->fname . ' ' . $sender_data->lname; ?></p>

                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien5'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $sender_data->email; ?></p>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien9'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $sender_data->phone; ?></p>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien10'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $address_order->sender_address; ?></p>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien12'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $address_order->sender_country; ?></p>
                                        </div>
                                    </div>


                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien13'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $address_order->sender_city; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                
                <!-- RECEIPIENT DETAILS -->
                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['left533020011'] ?></span></h3>
                                    </div>
                                </div>
                                <div><hr></div>
                                <div class="row">

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien6'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $receiver_data->fname . ' ' . $receiver_data->lname; ?></p>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien5'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $receiver_data->email; ?></p>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien9'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $receiver_data->phone; ?></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien10'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $address_order->recipient_address; ?></p>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien12'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $address_order->recipient_country; ?></p>

                                        </div>
                                    </div>


                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b><?php echo $lang['edit-clien13'] ?></b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $address_order->recipient_city; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if ($numrows > 0) {
                ?>
                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-md-flex align-items-center">
                                        <div>
                                            <h3 class="card-title"><span><?php echo $lang['left502'] ?></span></h3>
                                        </div>
                                    </div>
                                    <div><hr></div>

                                    <div class="table-responsive">
                                        <table id="zero_config" class="table table-striped">
                                            <thead class="bg-inverse text-white">
                                                <tr class="text-white">
                                                    <th><?php echo $lang['left503'] ?></th>
                                                    <th><?php echo $lang['left504'] ?></th>
                                                    <!-- <th><?php echo $lang['left505'] ?></th> -->
                                                    <th><?php echo $lang['left506'] ?></th>
                                                    <th><?php echo $lang['left507'] ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="projects-tbl">

                                                <?php
                                                foreach ($courier_track_items as $track_item) {
                                                    
                                                    $date_update = date("Y-m-d", strtotime($track_item->t_date));
                                                    $time_update = date("h:i A", strtotime($track_item->t_date));

                                                    $db->cdp_query("SELECT * FROM cdb_styles where id= '" . $track_item->status_courier . "'");
                                                    $status_courier_item = $db->cdp_registro();
                                                ?>
                                                    <tr class="card-hover">
                                                        <td><?php echo $date_update; ?></td>
                                                        <td><?php echo $time_update; ?></td>
                                                        <!-- <td><?php echo $track_item->t_dest; ?> /<br> 
                                                            <?php echo $track_item->t_city; ?></td> -->
                                                        <td>
                                                        <!-- <span class="label" style="background-color: <?php echo $status_courier->color; ?>"><?php echo $status_courier->mod_style; ?> -->
                                                        <?php if ($row_order->is_pickup != 0) { ?>
                                                            <?php if ($row_order->status_courier == 14) { ?>
                                                                <span style="background: #5BE472;" class="label label-large"><?php echo $lang['left533020020']; ?></span>

                                                            <?php } else{ ?>
                                                                <span style="background: <?php echo $status_courier->color; ?>;" class="label label-large"><?php echo $status_courier->mod_style; ?></span>
                                                                <?php
                                                                }
                                                            ?>

                                                        <?php } ?>
                                                        </td>
                                                        <td><?php echo $track_item->comments; ?></td>
                                                    </tr>
                                                <?php
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } ?>

                  <!-- Row Total Cost with commented shipping details  -->
                  <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <!-- <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['left533020009'] ?></span></h3>
                                    </div>
                                </div>
                                <div><hr></div>

                                <div class="table-responsive">
                                    <table class="table table-hover" id="tabla">
                                        <thead class="bg-inverse text-white">
                                            <tr>
                                                <th><b><?php echo $lang['left214'] ?></b></th>
                                                <th><b><?php echo $lang['left213'] ?></b></th>
                                                <th><b></b></th>
                                                <th><b><?php echo $lang['left215'] ?></b></th>
                                                <th><b><?php echo $lang['left216'] ?></b></th>
                                                <th><b><?php echo $lang['left217'] ?></b></th>
                                                <th><b><?php echo $lang['left218'] ?></b></th>
                                                <th><b><?php echo $lang['left219'] ?></b></th>
                                                <th><b><?php echo $lang['left231c9'] ?></b></th>
                                                <th><b><?php echo $lang['left239'] ?></b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="projects-tbl">
                                            <?php
                                            if ($order_items) {

                                                $sumador_total = 0;
                                                $sumador_valor_declarado = 0;
                                                $sumador_fixed_charge = 0;
                                                $max_fixed_charge = 0;
                                                $sumador_libras = 0;
                                                $sumador_volumetric = 0;
                                                $precio_total = 0;
                                                $total_impuesto = 0;
                                                $total_seguro = 0;
                                                $total_peso = 0;
                                                $total_descuento = 0;
                                                $total_impuesto_aduanero = 0;
                                                $count_item = 0;

                                                $total_valor_declarado = 0;

                                                foreach ($order_items as $row_order_item) {
                                                    
                                                    $description_item = $row_order_item->order_item_description;
                                                    $weight_item =  (float) $row_order_item->order_item_weight;
                                                    $length_item =  (float) $row_order_item->order_item_length;
                                                    $width_item =  (float) $row_order_item->order_item_width;
                                                    $height_item =  (float) $row_order_item->order_item_height;
                                                    $meter = (float) $row_order->volumetric_percentage;

                                                    $total_metric =  ($length_item *  $width_item *  $height_item) /  $meter;
                                                    $total_metric = round($total_metric, 2);
                                                    // calculate weight x price
                                                    if ($weight_item > $total_metric) {
                                                        $calculate_weight = $weight_item;
                                                        $sumador_libras += $weight_item; //Sumador
                                                    } else {
                                                        $calculate_weight = $total_metric;
                                                        $sumador_volumetric += $total_metric; //Sumador
                                                    }


                                                    (float)$sumador_valor_declarado += $row_order_item->order_item_declared_value;
                                                    (float) $sumador_fixed_charge += $row_order_item->order_item_fixed_value;
                                                    (float) $max_fixed_charge += $row_order_item->order_item_fixed_value;


                                            ?>

                                                    <tr class="card-hover">
                                                        <td><?php echo $row_order_item->order_item_quantity; ?></td>
                                                        <td><?php echo $description_item; ?></td>
                                                        <td></td>
                                                        <td><?php echo $weight_item; ?></td>
                                                        <td><?php echo $row_order_item->order_item_length; ?></td>
                                                        <td><?php echo $row_order_item->order_item_width; ?></td>
                                                        <td><?php echo $row_order_item->order_item_height; ?></td>
                                                        <td><?php echo $total_metric; ?></td>
                                                        <td class="text-center"><?php echo $row_order_item->order_item_fixed_value; ?></td>
                                                        <td class="text-center"><?php echo $row_order_item->order_item_declared_value; ?></td>
                                                    </tr>
                                                <?php

                                                }

                                                $sumador_total =  (float)$row_order->value_weight;

                                                if ($sumador_total > $core->min_cost_tax) {
                                                    $total_impuesto = $sumador_total * $row_order->tax_value / 100;
                                                }

                                                if ($sumador_valor_declarado > $core->min_cost_declared_tax) {
                                                    $total_valor_declarado = $sumador_valor_declarado * $row_order->declared_value / 100;
                                                }
                                                
                                                $total_descuento = $sumador_total * $row_order->tax_discount / 100;
                                                $total_peso = $sumador_libras + $sumador_volumetric;
                                                $total_seguro = $row_order->tax_insurance_value * $row_order->total_insured_value / 100;
                                                $total_impuesto_aduanero = $total_peso * $row_order->tax_custom_tariffis_value;
                                                $total_envio = ($sumador_total - $total_descuento) + $total_impuesto + $total_seguro + $total_impuesto_aduanero + $total_valor_declarado + $max_fixed_charge + $row_order->total_reexp;
                                                $sumador_total = cdb_money_format($sumador_total);
                                                $sumador_libras = $sumador_libras;
                                                $sumador_volumetric = $sumador_volumetric;
                                                $total_envio = cdb_money_format($total_envio);
                                                $total_seguro = cdb_money_format($total_seguro);
                                                $total_peso = $total_peso;
                                                $total_impuesto_aduanero = cdb_money_format($total_impuesto_aduanero);
                                                $total_impuesto = cdb_money_format($total_impuesto);
                                                $total_descuento = cdb_money_format($total_descuento);
                                                $sumador_valor_declarado = cdb_money_format($sumador_valor_declarado);
                                                $sumador_fixed_charge = cdb_money_format($sumador_fixed_charge);
                                                $total_valor_declarado = cdb_money_format($total_valor_declarado);
                                                ?>

                                            <?php }  ?>
                                        </tbody>

                                      
                                        <tfoot>
                                            <tr class="card-hover">
                                                <td colspan="3"><b><?php echo $lang['left905'] ?> &nbsp; <?php echo $core->weight_p; ?>:</b> <?php echo $row_order->value_weight; ?></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="text-right"><b><?php echo $lang['leftorder2021'] ?></b></td>
                                                <td class="text-center"><?php echo floatval($row_order->total_order); ?></td>
                                                <td></td>
                                            </tr>

                                            <tr class="card-hover">
                                                <td colspan="3"><b><?php echo $lang['left232'] ?></b> <span id="total_libras">: <?php echo $sumador_libras; ?></span></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="2" class="text-right">
                                                    <b><?php echo $lang['leftorder21'] ?> <?php echo $row_order->tax_discount; ?> <?php echo $lang['leftorder222221'] ?> </b>
                                                </td>
                                                <td class="text-center"><?php echo $total_descuento; ?></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="3"><b><?php echo $lang['left234'] ?></b> <span id="total_volumetrico">: <?php echo $sumador_volumetric; ?></span></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="3" class="text-right"></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td colspan="3"><b><?php echo $lang['left236'] ?></b> <span id="total_peso"> :<?php echo $total_peso; ?></span></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td colspan="3" class="text-right"></td>
                                                <td></td>
                                                <td></td>

                                            </tr>

                                        </tfoot>
                                        
                                    </table>
                                </div>

                                <div><br></div> -->
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['messageerrorform30'] ?></span></h3>
                                    </div>
                                </div>
                                <div><hr></div>
                                <div class="table-responsive">
                                    <table class="table table-hover" id="tabla">
                                        <thead class="bg-inverse text-white">
                                            <tr>
                                                <!-- <th><b><?php echo $lang['leftorder22'] ?></b></th>
                                                <th><b><?php echo $lang['leftorder25'] ?> <?php echo $row_order->tax_custom_tariffis_value; ?> <?php echo $lang['leftorder222221'] ?></b></th>
                                                <th><b><?php echo $lang['leftorder23'] ?></b></b></th> -->
                                                <th><b>Distance(In KM)</b></th>
                                                <!-- <th><b><?php echo $lang['leftorder1878'] ?></b></th>
                                                <th><b><?php echo $lang['langs_048'] ?></b></th> -->
                                                <th><b><?php echo $lang['left240'] ?></th>
                                                <th><b><?php echo $lang['leftorder67'] ?> (13%) </b></th>
                                                <th><b><?php echo $lang['leftorder2020'] ?></b></th>
                                            </tr>
                                        </thead>
                                        <tbody id="projects-tbl">
                                            <tr class="card-hover">
                                                <!-- <td class="text-center" id="insurance"><?php echo $row_order->total_insured_value; ?></td>
                                                <td class="text-center" id="total_impuesto_aduanero"><?php echo $total_impuesto_aduanero; ?></td>
                                                <td><?php echo $sumador_valor_declarado; ?></td> -->
                                                
                                                <td class="text-left" id="impuesto"><?php echo $row_order->distance ?></td>
                                                <!-- <td class="text-center" id="reexp"><?php echo $sumador_fixed_charge; ?></td>
                                                <td class="text-center" id="reexp"><?php echo "$ " . cdb_money_format($row_order->total_reexp); ?></td> -->
                                                <td class="text-left" id="total_envio"><b><?php echo "$ " . cdb_money_format_bar(floatval($row_order->sub_total)); ?></b></td>
                                                <td class="text-left" id="impuesto"><?php 
                                                    if (floatval($row_order->total_order) && floatval($row_order->total_order) > floatval($row_order->sub_total)) {
                                                        $tax = floatval($row_order->total_order) - floatval($row_order->sub_total);
                                                        echo "$ " . cdb_money_format_bar($tax);
                                                    } else {
                                                        echo "$ 0.00";
                                                    }
                                                ?></td>
                                                <td class="text-left" id="total_envio"><b><?php echo "$ " . cdb_money_format_bar(floatval($row_order->total_order)); ?></b></td>
                                            </tr>
                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                
            </div>
            <?php include('views/modals/modal_send_email.php'); ?>
            <?php include('views/modals/modal_update_driver.php'); ?>
            <?php include('views/modals/modal_verify_payment_packages.php'); ?>


            <?php include 'views/inc/footer.php'; ?>
        </div>


    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper  -->
    <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <?php include('views/modals/modal_cancel_pickup.php'); ?>

    <?php include('views/modals/modal_charges_list.php'); ?>
    <?php include('views/modals/modal_charges_add.php'); ?>
    <?php include('views/modals/modal_charges_edit.php'); ?>


    <?php include('helpers/languages/translate_to_js.php'); ?>

    <script src="dataJs/courier_view.js"></script>


</body>

</html>