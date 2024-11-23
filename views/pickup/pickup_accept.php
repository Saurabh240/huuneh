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
if ($userData->userlevel == 1)
    cdp_redirect_to("login.php");


if (isset($_GET['id'])) {
    $data = cdp_getCourierPrint($_GET['id']);
}

if (!isset($_GET['id']) or $data['rowCount'] != 1) {
    cdp_redirect_to("courier_list.php");
}

$row_order = $data['data'];

$db->cdp_query("SELECT * FROM cdb_add_order_item WHERE order_id='" . $_GET['id'] . "'");
$order_items = $db->cdp_registros();

$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row_order->sender_id . "'");
$sender_data = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_recipients where id= '" . $row_order->receiver_id . "'");
$receiver_data = $db->cdp_registro();


$db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row_order->order_prefix . $row_order->order_no . "'");
$address_order = $db->cdp_registro();

$tags = $db->getAllTags();

$rowTags = [];
if(($sender_data->business_type == 'pharmacy' || $sender_data->business_type == 'pharmacy_2' || $sender_data->business_type == 'pharmacy_3') && !empty($row_order->tags)){
    $rowTags = json_decode($row_order->tags, TRUE);
}

$rowTagsFlower = [];
if(($sender_data->business_type == 'flower_shop' || $sender_data->business_type == 'flat_1' || $sender_data->business_type == 'flat_2') && !empty($row_order->tags)){
    $rowTagsFlower = json_decode($row_order->tags, TRUE);
}

$tagsFlower = ['Wreath','Standing/Casket Spray'];
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
    <title><?php echo $lang['leftorder73'] ?> | <?php echo $core->site_name ?></title>
    <link rel="stylesheet" href="assets/template/assets/libs/intlTelInput/intlTelInput.css">
    <link rel="stylesheet" href="assets/template/assets/libs/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/template/assets/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">
    <link href="assets/template/dist/css/custom_swicth.css" rel="stylesheet">


    <?php include 'views/inc/head_scripts.php'; ?>
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

        .pre {
            border: solid 1px red;
        }

        .pre:focus {
            border: 1px solid blue;
            outline: none;
        }
		#loadingIcon {
		   position: absolute;
			  top: 0;
			  left: 0;
			  width: 100%;
			  height: 100%;
			  background-color: rgba(255, 255, 255, 0.8); /* White background with transparency */
			  display: flex;
			  justify-content: center;
			  align-items: center;
			  z-index: 10;
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

        <?php $office = $core->cdp_getOffices(); ?>
        <?php $agencyrow = $core->cdp_getBranchoffices(); ?>
        <?php $courierrow = $core->cdp_getCouriercom(); ?>
        <?php $statusrow = $core->cdp_getStatusPickup(); ?>
        <?php $packrow = $core->cdp_getPack(); ?>
        <?php $payrow = $core->cdp_getPayment(); ?>
        <?php $paymethodrow = $core->cdp_getPaymentMethod(); ?>

        <?php $itemrow = $core->cdp_getItem(); ?>
        <?php $moderow = $core->cdp_getShipmode(); ?>
        <?php $driverrow = $user->cdp_userAllDriver(); ?>
        <?php $delitimerow = $core->cdp_getDelitime(); ?>
        <?php $track = $core->cdp_order_track(); ?>
        <?php $categories = $core->cdp_getCategories(); ?>

        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 align-self-center">
                        <h4 class="page-title"><i class="ti-package" aria-hidden="true"></i> <?php echo $lang['leftorder73'] ?></h4>
                    </div>
                </div>
            </div>

            <form method="post" id="invoice_form" name="invoice_form">

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-row">

                                        <div class="form-group col-md-4">

                                            <label for="inputcom" class="control-label col-form-label"><?php echo $lang['add-title24'] ?></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"><span style="color:#ff0000"><b><?php echo $lang['inv-shipping9'] ?></b></span></div>
                                                </div>
                                                <input type="text" class="form-control" name="order_no" id="order_no" value="<?php echo $row_order->order_prefix . $row_order->order_no; ?>" readonly>
                                            </div>

                                        </div>

                                        <!-- <div class="form-group col-md-4">
                                            <label for="inputlname" class="control-label col-form-label"><?php echo $lang['left201'] ?> </label>
                                            <div class="input-group mb-3">
                                                <select class="custom-select col-12" id="agency" name="agency">
                                                    <?php foreach ($agencyrow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>" <?php if ($row_order->agency == $row->id) {
                                                                                                    echo 'selected';
                                                                                                } ?>><?php echo $row->name_branch; ?>

                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div> -->

                                        <?php if ($user->access_level = 'Admin') { ?>

                                            <!-- <div class="form-group col-md-4">
                                                <label for="inputname" class="control-label col-form-label"><?php echo $lang['add-title14'] ?></label>
                                                <div class="input-group mb-3">
                                                    <select class="custom-select col-12" id="origin_off" name="origin_off">

                                                        <?php foreach ($office as $row) : ?>
                                                            <option value="<?php echo $row->id; ?>" <?php if ($row_order->origin_off == $row->id) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?php echo $row->name_off; ?>

                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div> -->

                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-information-outline" style="color:#36bea6"></i><?php echo $lang['langs_010']; ?></h4>
                                    <hr>

                                    <div class="resultados_ajax_add_user_modal_sender"></div>
                                    <br>
                                    <?php
                                    if ($core->active_whatsapp == 1) {
                                    ?>
                                        <label class="custom-control custom-checkbox" style="font-size: 18px; padding-left: 0px">
                                            <input type="checkbox" class="custom-control-input" name="notify_whatsapp_sender" id="notify_whatsapp_sender" value="1" checked>
                                            <b>
                                                <?php echo $lang['leftorder14443']; ?>

                                                <i class="fa fa-whatsapp" style="font-size: 22px; color:#07bc4c;"></i>
                                            </b>
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    <?php } ?>

                                    <div class="row">
                                        <div class="col-md-12 ">

                                            <label class="control-label col-form-label"><?php echo $lang['sender_search_title'] ?></label>


                                            <div class="input-group">
                                                <select class="select2 form-control custom-select" id="sender_id" name="sender_id" disabled>
                                                    <option value="<?php echo $sender_data->id; ?>"><?php echo $sender_data->fname . " " . $sender_data->lname; ?></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-12 ">

                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['sender_search_address_title'] ?></label>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <select class="select2 form-control" id="sender_address_id" name="sender_address_id"    >
                                                            <option value="<?php echo $row_order->sender_address_id; ?>" data-address="<?php echo $address_order->sender_city . "," . $address_order->sender_state ?>"><?php echo $address_order->sender_address; ?></option>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-2">
                                                    <div class="input-group-append input-sm">
                                                        <button id="add_address_sender" data-type_user="user_customer" data-toggle="modal" data-target="#myModalAddRecipientAddresses" type="button" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-information-outline" style="color:#36bea6"></i><?php echo $lang['left334']; ?></h4>
                                    <hr>
                                    <div class="resultados_ajax_add_user_modal_recipient"></div>
                                    <br>
                                    <?php
                                    if ($core->active_whatsapp == 1) {
                                    ?>
                                        <label class="custom-control custom-checkbox" style="font-size: 18px; padding-left: 0px">
                                            <input type="checkbox" class="custom-control-input" name="notify_whatsapp_receiver" id="notify_whatsapp_receiver" value="1">
                                            <b>
                                                <?php echo $lang['leftorder14443']; ?>

                                                <i class="fa fa-whatsapp" style="font-size: 22px; color:#07bc4c;"></i>
                                            </b>
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    <?php } ?>
                                    <div class="row">

                                        <div class="col-md-12">
                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['recipient_search_title'] ?></label>

                                            <div class="input-group">

                                                <select class="select2 form-control custom-select" id="recipient_id" name="recipient_id" disabled>
                                                    <option value="<?php echo $receiver_data->id; ?>"><?php echo $receiver_data->fname . " " . $receiver_data->lname; ?></option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-md-12">

                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['recipient_search_address_title'] ?></label>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <select class="select2 form-control" id="recipient_address_id" name="recipient_address_id">
                                                            <option value="<?php echo $row_order->receiver_address_id; ?>"  data-address="<?php echo $address_order->recipient_city . "," . $address_order->recipient_state ?>"><?php echo $address_order->recipient_address; ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="input-group-append input-sm">
                                                        <button id="add_address_recipient" type="button" data-type_user="user_recipient" data-toggle="modal" data-target="#myModalAddRecipientAddresses" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row -->

					<?php if ($userData->userlevel==9){ 
  if( $sender_data->business_type == "flower_shop" || $sender_data->business_type == "flat_1" || $sender_data->business_type == "flat_2" ) { ?>
				 <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                
                                <div class="mb-3">
                                <h5> &nbsp;<b>Tags</b></h5>
                                    <?php foreach ($tagsFlower as $tag){ ?>
                                    <div class="form-check">
                                        <p class="text-muted  m-l-5">
                                            <input class="form-check-input" type="checkbox" id="<?php echo strtolower(str_replace(' ', '', $tag)); ?>" name="tags[]" value="<?php echo htmlspecialchars($tag); ?>" <?php if (isTagChecked($tag, $rowTagsFlower)) echo 'checked'; ?> disabled>
                                            <label class="form-check-label" for="<?php echo strtolower(str_replace(' ', '', $tag)); ?>"><?php echo htmlspecialchars($tag); ?></label>
                                        </p>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="row">
                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b>Pieces</b></h5>
                                            <input type="number" class="form-control" name="pieces" id="pieces" placeholder="No of pieces" value="<?php echo $row_order->no_of_pieces; ?>" disabled>
											
                                        </div>
                                    </div>

                              
                                </div>
                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notesForDriver" name="notes_for_driver" rows="3" min=0 placeholder="Please be brief" readonly><?php echo $row_order->notes_for_driver ?></textarea>
                                    <div class="form-text"></div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
				
				
				<?php }elseif($sender_data->business_type == "warehouses") { ?>
				<div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
				<div class="card" id="warehouseCard">
                    <div class="card-body">
                        <!-- Charge and Rx Number Row -->
                                             

                        <div class="mb-3 row" id="piece_div_warehouse">
                            <div class="col-md-6">
                            <label for="pieces_warehouse" class="form-label">Pieces</label>
                            <input type="number" class="form-control" name="pieces" id="pieces_warehouse" placeholder="No of pieces" min=0 value="<?php echo $row_order->no_of_pieces; ?>" disabled>
							 <div class="form-text">Each piece is to be $2</div>
                            </div>
                            
                        </div>
						
                     
                    </div>
                </div>
                </div>
                </div>
				<?php }elseif( $sender_data->business_type == "pharmacy" || $sender_data->business_type == "pharmacy_2" || $sender_data->business_type == "pharmacy_3" ) { ?>
                <div class="row">
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b>Charge</b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $row_order->charge; ?></p>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-4 mb-2">
                                        <div class="">
                                            <h5> &nbsp;<b>No of Rx</b></h5>
                                            <p class="text-muted  m-l-5"><?php echo $row_order->no_of_rx; ?></p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                <h5> &nbsp;<b>Tags</b></h5>
                                    <?php foreach ($tags as $tag){ ?>
                                    <div class="form-check">
                                        <p class="text-muted  m-l-5">
                                            <input class="form-check-input" type="checkbox" id="<?php echo strtolower(str_replace(' ', '', $tag)); ?>" name="tags[]" value="<?php echo htmlspecialchars($tag); ?>" <?php if (isTagChecked($tag, $rowTags)) echo 'checked'; ?> disabled>
                                            <label class="form-check-label" for="<?php echo strtolower(str_replace(' ', '', $tag)); ?>"><?php echo htmlspecialchars($tag); ?></label>
                                        </p>
                                    </div>
                                    <?php } ?>
                                </div>

                                
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notesForDriver" name="notes_for_driver" rows="3" placeholder="Please be brief" readonly><?php echo $row_order->notes_for_driver ?></textarea>
                                    <div class="form-text"></div>
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
            <?php  }  } ?>
								
								
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-book-multiple" style="color:#36bea6"></i> <?php echo "Delivery Details"; ?></h4>
                                    <br>
                                    <input type="hidden" name="sender_id_temp" id="sender_id_temp" value="<?php echo $userData->id; ?>">
                                    <div class="row">
                                        <!-- <div class="form-group col-md-4">
                                            <label for="inputlname" class="control-label col-form-label"><?php echo $lang['itemcategory'] ?></label>
                                            <div class="input-group">
                                                <select class="custom-select col-12" id="order_item_category" name="order_item_category" required>
                                                    <option value="0">--Select logistics service--</option>
                                                    <?php foreach ($categories as $row) :

                                                    ?>
                                                        <option value="<?php echo $row->id; ?>" <?php if ($row_order->order_item_category == $row->id) {
                                                                                                    echo 'selected';
                                                                                                }  ?>><?php echo $row->name_item; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                        </div> -->
                                        <input type="hidden" name="order_item_category" value="1" />

                                        <!-- <div class="form-group col-md-4">
                                            <label for="inputlname" class="control-label col-form-label"><?php echo $lang['add-title17'] ?></label>
                                            <div class="input-group mb-3">
                                                <select class="custom-select col-12" id="order_package" name="order_package" required>
                                                    <option value="0">--<?php echo $lang['left203'] ?>--</option>
                                                    <?php foreach ($packrow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>" <?php if ($row_order->order_package == $row->id) {
                                                                                                    echo 'selected';
                                                                                                } ?>><?php echo $row->name_pack; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div> -->
                                        <input type="hidden" name="order_package" value="1" />


                                        <div class="form-group col-md-4">
                                            <label for="inputEmail3" class="control-label col-form-label"><?php echo $lang['payment_methods'] ?></label>
                                            <div class="input-group mb-3">
                                                <select class="custom-select col-12" id="order_payment_method" name="order_payment_method" required>
                                                    <?php foreach ($paymethodrow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>" <?php if ($row_order->order_payment_method == $row->id) {
                                                                                                    echo 'selected';
                                                                                                } ?>><?php echo $row->label; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                       

                                        <div class="form-group col-md-4">
                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['add-title19'] ?> <i style="color:#ff0000" class="fas fa-shipping-fast"></i></label>
                                            <div class="input-group mb-3">
                                                <select class="custom-select col-12 pre" id="status_courier" name="status_courier" required>
                                                    <option value="0">--<?php echo $lang['left210'] ?>--</option>
                                                    <?php foreach ($statusrow as $row) : ?>

                                                        <option value="<?php echo $row->id; ?>" <?php if ($row_order->status_courier == $row->id) {
                                                                                                    echo 'selected';
                                                                                                } ?>><?php echo $row->mod_style; ?></option>

                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                 

                                    <div class="row">
                            
                                        <?php
                                            if ($userData->userlevel == 3) { ?>

                                        <div class="col-md-4">
                                            <label for="inputname" class="control-label col-form-label"><?php echo $lang['left208'] ?></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="color:#ff0000"><i class="fas fa-car"></i></span>
                                                </div>
                                                <input type="hidden" name="driver_id" id="driver_id" value="<?php echo $_SESSION['userid']; ?>">

                                                <select class="custom-select col-12" id="driver_name" name="driver_name">
                                                    <option value="<?php echo $_SESSION['userid']; ?>"><?php echo $_SESSION['name'];  ?></option>
                                                </select>
                                            </div>
                                        </div>
                                        <?php

                                        } else { ?>

                                        <div class="col-md-6">
                                            <label for="inputname" class="control-label col-form-label"><?php echo $lang['left208'] ?></label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" style="color:#ff0000"><i class="fas fa-car"></i></span>
                                                </div>
                                                <select class="custom-select col-12 pre" id="driver_id" name="driver_id">
                                                    <option value="0">--<?php echo $lang['left209'] ?>--</option>
                                                    <?php foreach ($driverrow as $row) : ?>
                                                        <option <?php if ($row_order->driver_id == $row->id) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $row->id; ?>">
                                                            <?php echo $row->fname . ' ' . $row->lname; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                       
                                    
									
										<div class="col-md-2">
										 <label for="admin_discount" class="control-label col-form-label">Discount (in $)</label>
										 <div class="input-group mb-3">
											   <input type="number" id="admin_discount" name="admin_discount" step="0.01" value=<?php echo $row_order->admin_discount??''; ?> class="form-control">
											   <input type="hidden" id="total_price">
											</div>
										</div>
										<?php } ?>
                                    </div>
								
								


                                    <div class="col-md-12 row" id="image_preview"></div>
                                    <div class="col-md-4 mt-4">
                                        <div id="clean_files" class="hide">
                                            <button type="button" id="clean_file_button" class="btn btn-danger ml-3">
                                                <i class='fa fa-trash' style="font-size:18px; cursor:pointer;"></i> <?php echo $lang['leftorder17']; ?> </button>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="resultados_file col-md-4 pull-right mt-4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



								
                    <div class="row">
                    <div class="col-lg-4 h-70">
                            <div style="height: 26rem;" class=" card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-book-multiple" style="color:#36bea6"></i> Delivery Details:</h4>
                                    <br>
                                        <div class="row">
											<!-- <div class="form-group col-md-4">
												<label for="inputEmail3" class="control-label col-form-label">Distance</label>
												<div class="input-group mb-3">
                                                    </div>
                                                </div> -->
                                            <input type="hidden" name="distance" class="form-control" id="distance" value="<?php echo $row_order->distance; ?>">
                                            <div class="form-group col-md-7">
                                            <label for="inputEmail3" class="control-label col-form-label">Delivery Type</label>
												<div class="input-group mb-3">
													<select class="form-control custom-select" id="deliveryType" name="deliveryType" required style="width: 100%;">
                                                    <option value="" selected>Select Delivery Type</option>
														<option value="SAMEDAY (BEFORE 9PM)"
                                                        <?php if($row_order->delivery_type == "SAMEDAY (BEFORE 9PM)") { ?> selected <?php } ?>  
                                                        >SAMEDAY (BEFORE 9PM)</option>
														<?php  if( $sender_data->business_type == "pharmacy" || $sender_data->business_type == "pharmacy_2" || $sender_data->business_type == "pharmacy_3" ) { ?>
														<option <?php if($row_order->delivery_type == "NEXT DAY (BEFORE 9PM)") { ?> selected <?php } ?>  value="NEXT DAY (BEFORE 9PM)">NEXT DAY (BEFORE 9PM)</option>
														 <?php }else{ ?>
                                                        <option value="SAMEDAY (BEFORE 7PM)"
                                                        <?php if($row_order->delivery_type == "SAMEDAY (BEFORE 7PM)") { ?> selected <?php } ?>
                                                        >SAMEDAY (BEFORE 7PM)</option>
                                                        <option value="SAME DAY (1PM to 4PM)"
                                                        <?php if($row_order->delivery_type == "SAME DAY (1PM to 4PM)") { ?> selected <?php } ?>
                                                        >SAME DAY (1PM to 4PM)</option>
														<option value="SAME DAY (BEFORE 5PM)"
                                                        <?php if($row_order->delivery_type == "SAME DAY (BEFORE 5PM)") { ?> selected <?php } ?>
                                                        >SAME DAY (BEFORE 5PM)</option>
														<option value="RUSH (4 HOURS)"
                                                        <?php if($row_order->delivery_type == "RUSH (4 HOURS)") { ?> selected <?php } ?>
                                                        >RUSH (4 HOURS)</option>
														<option value="RUSH (3 HOURS)"
                                                        <?php if($row_order->delivery_type == "RUSH (3 HOURS)") { ?> selected <?php } ?>
                                                        >RUSH (3 HOURS)</option>
														<option value="RUSH (2 HOURS)"
                                                        <?php if($row_order->delivery_type == "RUSH (2 HOURS)") { ?> selected <?php } ?>
                                                        >RUSH (2 HOURS)</option>
														<option value="URGENT (90 MINUTES)"
                                                        <?php if($row_order->delivery_type == "URGENT (90 MINUTES)") { ?> selected <?php } ?>
                                                        >URGENT (90 MINUTES)</option>
														
														<option value="NEXT DAY (BEFORE 7PM)"
                                                        <?php if($row_order->delivery_type == "NEXT DAY (BEFORE 7PM)") { ?> selected <?php } ?>
                                                        >NEXT DAY (BEFORE 7PM)</option>
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
														 <?php } ?>
													</select>
												</div>
											</div>

											<div class="col-md-4" style="display: none;">
												<label for="inputcontact" class="control-label col-form-label">Estimated pickup date</label>
												<div class="input-group">
													<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
														<div class="input-group-text"><i style="color:#ff0000" class="fa fa-calendar"></i></div>
													</div>
													<input type="text" class="form-control" name="order_date" id="order_date" placeholder="--Shipment Pickup Date--" data-toggle="tooltip" data-placement="bottom" title="Estimated pickup date" readonly="" value="2024-05-27">
												</div>
											</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div>
                                                    <label class="control-label"> Delivery Notes</label>
                                                </div>
                                                <textarea class="form-control" name="delivery_notes" id="delivery_notes" rows="4" cols="50" placeholder="-Unit and Buzzer (If applicable) 
-Package Description 
-Pickup and Drop off instructions"><?php if(!empty($row_order->notes)){ echo $row_order->notes;} ?></textarea>

                                               

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div>
                                                    <label class="control-label" id="selectItem"> Attach Files</label>
                                                </div>

                                                <input class="custom-file-input" id="filesMultiple" name="filesMultiple[]" multiple type="file" style="display: none;" onchange="cdp_preview_images();" accept="image/jpeg, image/jpg, image/png, image/gif">


                                                <button type="button" id="openMultiFile" class="btn btn-default pull-left  mb-4"> <i class="fa fa-paperclip" id="openMultiFile" style="font-size:18px; cursor:pointer;"></i> Upload files </button>


                                            </div>
                                        </div>

                                        <div class="col-md-12 row" id="image_preview"></div>
                                        <div class="col-md-4 mt-4">
                                            <div id="clean_files" class="hide">
                                                <button type="button" id="clean_file_button" class="btn btn-danger ml-3">
                                                    <i class="fa fa-trash" style="font-size:18px; cursor:pointer;"></i>
                                                    Cancel attachments                                                </button>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <!--div class="resultados_file col-md-4 pull-right mt-4">


                                            </div-->
                                        </div>


                                        

                                </div>
                            </div>
                    </div>    
                     <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                     <!-- <div class="row">
                                        <div class="col-md-12">
                                            
                                        </div>
                                    </div>  -->
                                    <!-- Listas item caja -->
                                    <!-- <div id="data_items"></div> -->

                                    <!-- Boton adicionar caja al listado -->
                                    <!-- <div class="col-md-3 text-left"> -->
                                        <!-- <button type="button"  name="add_rows" id="add_rows" class="btn btn-outline-dark"><span class="fa fa-plus"></span> <php echo $lang['left231'] ?></button> -->
                                    <!-- </div> -->

                                    <!-- <div><br></div>
                                    <div class="row">
                                        <div class="col-md-4"> -->
                                            <!-- <span class="text-secondary text-left"><php echo $lang['leftorder17713'] ?></span> -->
                                        <!-- </div>
                                        <div class="col-md-1"> -->
                                            <!-- <span class="text-secondary text-center" id="total_weight">0.00</span> -->
                                        <!-- </div>
                                        <div class="col-md-1 offset-3"> -->
                                            <!-- <span class="text-secondary text-center" id="total_vol_weight">0.00</span> -->
                                        <!-- </div>
                                        <div class="col-md-1"> -->
                                            <!-- <span class="text-secondary text-center" id="total_fixed">0.00</span> -->
                                        <!-- </div>
                                        <div class="col-md-1"> -->
                                            <!-- <span class="text-secondary text-center" id="total_declared">0.00</span> -->
                                        <!-- </div>
                                    </div> -->
                                    <hr>
                                    
                                    <div class="row" style="margin-top: 20px;">
									<div id="loadingIcon" style="display: none;">
									<img src="assets/images/loader-small.gif" class="loader_small" id="loader_small">
									 </div>
                                        <div class="table-responsive d-none" id="table-totals">
                                            <!-- <table id="insvoice-item-table" class="table">
                                                <tfoot>
                                                    <tr class="card-hover">
                                                        <td colspan="4" class="text-right"><b>Subtotal</b></td>
                                                        <td colspan="1"></td>
                                                        <td class="text-right">
                                                                                                                            <b> $ </b>
                                                                                                                        <span id="subtotal"> 0.00</span>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table> -->


                                            <!-- Listado de impuestos-->

                                            <div class="card" id="row">
                                                <div class="col-md-6">
                                                    <h4 class="card-title">
                                                        <i class="ti ti-briefcase " style="color:#36bea6"></i>
                                                        Total Cost                                                    </h4>
                                                </div>
                                                <hr>
                                                <div class="row row-shadow input-container"> 
                                                <!-- <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">Fixed Rate</label>
                                                                                                                            <b> $ </b>
                                                                                                                        <span id="fixed_value_label"> 0.00</span>
                                                            <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax">
                                                         </div>
                                                    </div>
                                                    
                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">Distance</label>
                                                           
                                                            <span id="total_distance"> 0.00</span>
                                                          
                                                         </div>
                                                    </div> -->
                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Distance (In KM)</label>
                                                            
                                                            
                                                            <span id="total_distance"> 0.00</span>
                                                            
                                                    </div>
                                                 </div>

                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                     <div class="form-group">
                                                            <label for="emailAddress1">Subtotal  </label>
                                                               
                                                                                                                                    <b> $ </b>
                                                                                                                                <span id="total_before_tax">0.00</span>
                                                                <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax" value="">
                                                                 <input type="hidden" name="total_envio_ajax" id="total_envio_ajax" value="">
                                                            </div>
                                                 </div>
												
                                                 <div class="col-sm-12 col-md-4 col-lg-2">
                                                     <div class="form-group">
                                                            <label for="tax_13">TaxL (13%)</label>
                                                                
                                                                
                                                            <b> $ </b>
                                                            <span id="tax_13">NaN</span>
                                                                
                                                            </div>
                                                 </div>
                                                

                                                <div class="col-sm-12 col-md-4 col-lg-2">
                                                     <div class="form-group">
                                                            <label for="emailAddress1">TOTAL</label>
                                                                
                                                                
                                                            <b> $ </b>
                                                            <span id="total_after_tax">NaN</span>
                                                                
                                                            </div>
                                                 </div>
                                                
                                                 
                                                 
                                                
                                                  <!-- <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Price &nbsp; kg </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="3.55" name="price_lb" id="price_lb" style="border: 1px solid red;" readonly>
                                                        </div>
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Discount % </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" value="0" name="discount_value" id="discount_value" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        
                                                                                                                    <b> $ </b>
                                                                                                                <span id="discount"> 0.00</span>
                                                        
                                                     </div>
                                                  </div>

                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Value assured </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="100" name="insured_value" id="insured_value" style="border: 1px solid darkorange;" readonly>
                                                        </div>
                                                        
                                                        <td class="text-center" id="insured_label"></td>
                                                        
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Shipping Insurance % </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="2" name="insurance_value" id="insurance_value" style="border: 1px solid darkorange;" readonly>
                                                        </div>
                                                        
                                                                                                                    <b> $ </b>
                                                                                                                <span id="insurance"> 0.00</span>
                                                        
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Customs Duties % </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="0.1" name="tariffs_value" id="tariffs_value" readonly>
                                                        </div>
                                                        
                                                                                                                    <b> $ </b>
                                                                                                                <span id="total_impuesto_aduanero"> 0.00</span>
                                                        
                                                     </div>
                                                  </div>


                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">Tax % </label>
                                                            <div class="input-group">
                                                              <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="19" name="tax_value" id="tax_value" readonly>
                                                            </div>
                                                            
                                                                                                                            <b> $ </b>
                                                                                                                        <span id="impuesto"> 0.00</span>
                                                            
                                                         </div>
                                                    </div>
                                                
                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">Declared value % </label>
                                                            <div class="input-group">
                                                              <input type="text" onchange="calculateFinalTotal(this);" value="3" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" name="declared_value_tax" id="declared_value_tax" readonly>
                                                            </div>
                                                            
                                                                                                                            <b> $ </b>
                                                                                                                        <span id="declared_value_label"> 0.00</span>
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">Reissue </label>
                                                            <div class="input-group">
                                                              <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="0" name="reexpedicion_value" id="reexpedicion_value" readonly>
                                                            </div>
                                                            
                                                            <td class="text-right" id="reexpedicion_label"></td>
                                                            
                                                        </div>
                                                    </div>



                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">Fixed charge</label>
                                                                                                                            <b> $ </b>
                                                                                                                        <span id="fixed_value_label"> 0.00</span>
                                                            <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax">
                                                         </div>
                                                    </div>


                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1">TOTAL</label>
                                                                                                                            <b> $ </b>
                                                                                                                        <span id="total_envio" class="green-bold"> 0.00</span>
                                                            <input type="hidden" name="total_envio_ajax" id="total_envio_ajax">
                                                            
                                                         </div>
                                                    </div> -->

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-actions">
                                                <div class="card-body">
                                                    <div class="text-right">
                                                        <input type="hidden" name="total_item_files" id="total_item_files" value="0">
                                                        <input type="hidden" name="deleted_file_ids" id="deleted_file_ids">
                                                        <button type="button" name="calculate_invoice" id="calculate_invoice" class="btn btn-info">
                                                            <i class="fas fa-calculator"></i>
                                                            <span class="ml-1">
                                                                Calculate                                                            </span>
                                                        </button>
                                                        &nbsp;
                                                        <button disabled type="submit" name="create_invoice" id="create_invoice" class="btn btn-success">
                                                            <i class="fas fa-save"></i>
                                                            <span class="ml-1" >Submit Order</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="3.55" name="price_lb" id="price_lb">
                            <input type="hidden" value="0" name="discount_value" id="discount_value">
                            <input type="hidden" value="100" name="insured_value" id="insured_value">
                            <input type="hidden" value="2" name="insurance_value" id="insurance_value">
                            <input type="hidden" value="0.1" name="tariffs_value" id="tariffs_value">
                            <input type="hidden" value="13" name="tax_value" id="tax_value">
                            <input type="hidden" value="3" name="declared_value_tax" id="declared_value_tax">
                            <input type="hidden" value="0" name="reexpedicion_value" id="reexpedicion_value">
							 <input type="hidden" value="0" name="total_tax_val" id="total_tax_val">

                            <input type="hidden" name="core_meter" id="core_meter" value="500">
                            <input type="hidden" name="core_min_cost_tax" id="core_min_cost_tax" value="300">
                            <input type="hidden" name="core_min_cost_declared_tax" id="core_min_cost_declared_tax" value="250">
                            <input type="hidden" name="total_cost" id="total_cost" value="0" />
                            <input type="hidden" name="subtotal" id="subtotal" value="0" />                                        
                            <input type="hidden" name="businessType" id="businessType" value="<?php echo $sender_data->business_type; ?>" />                                        
                
                    </div> 
                    </div>

                    <?php

                    $db->cdp_query("SELECT * FROM cdb_order_files where order_id='" . $_GET['id'] . "' ORDER BY date_file");
                    $files_order = $db->cdp_registros();
                    $numrows = $db->cdp_rowCount();


                    if ($numrows > 0) {
                    ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="resultados_ajax_delete_file"></div>
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="fa fa-paperclip"></i> <?php echo $lang['leftorder16']; ?></h5>
                                        <hr>
                                        <div class="col-md-12 row">
                                            <?php
                                            $count = 0;
                                            $count_hr = 0;

                                            foreach ($files_order as $file) {

                                                $date_add = date("Y-m-d h:i A", strtotime($file->date_file));

                                                $src = 'assets/images/no-preview.jpeg';

                                                if (
                                                    $file->file_type == 'jpg' ||
                                                    $file->file_type == 'jpeg' ||
                                                    $file->file_type == 'png' ||
                                                    $file->file_type == 'gif' ||
                                                    $file->file_type == 'ico'
                                                ) {

                                                    $src = $file->url;
                                                }

                                                $count++;
                                            ?>

                                                <div class="col-md-3" id="file_delete_item_<?php echo $file->id; ?>">
                                                    <img style="width: 180px; height: 180px;" class="img-thumbnail" src="<?php echo $src; ?>">
                                                    <div class="row ">
                                                        <div class=" col-md-12 mb-3 mt-2">
                                                            <p class="text-justify"><a style="color:#7460ee;" target="_blank" href="<?php echo $file->url; ?>" class=""><?php echo $file->name; ?> </a></p>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="mb-2">
                                                            <button type="button" class="btn btn-danger btn-sm" onclick="cdp_deleteImgAttached('<?php echo $file->id; ?>');"><i class="fa fa-trash"></i></button>
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

                    <input type="hidden" name="order_id" id="order_id" value="<?php echo @$_GET['id']; ?>" />
                    <input type="hidden" name="core_meter" id="core_meter" value="<?php echo @$row_order->volumetric_percentage; ?>" />
                    <input type="hidden" name="core_min_cost_tax" id="core_min_cost_tax" value="<?php echo $core->min_cost_tax; ?>" />
                    <input type="hidden" name="core_min_cost_declared_tax" id="core_min_cost_declared_tax" value="<?php echo $core->min_cost_declared_tax; ?>" />

                    
                    <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax">
                    <input type="hidden" name="total_envio_ajax" id="total_envio_ajax">


                    <input type="hidden" value="<?php echo $core->value_weight; ?>" name="price_lb" id="price_lb">
                    <input type="hidden" value="0" name="discount_value" id="discount_value">
                    <input type="hidden" value="100" name="insured_value" id="insured_value">
                    <input type="hidden" value="<?php echo $core->insurance; ?>" name="insurance_value" id="insurance_value">
                    <input type="hidden" value="<?php echo $core->c_tariffs; ?>" name="tariffs_value" id="tariffs_value">
                    <input type="hidden" value="<?php echo '13' ?>" name="tax_value" id="tax_value">
                    <input type="hidden" value="<?php echo $core->declared_tax; ?>" name="declared_value_tax" id="declared_value_tax">
                    <input type="hidden" value="0" name="reexpedicion_value" id="reexpedicion_value">
            </form>
            <?php include('views/modals/modal_add_user_shipment.php'); ?>
            <?php include('views/modals/modal_add_recipient_shipment.php'); ?>
            <?php include('views/modals/modal_add_addresses_user.php'); ?>
            <?php include('views/modals/modal_add_addresses_recipient.php'); ?>


        </div>
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


    <?php include('helpers/languages/translate_to_js.php'); ?>

    <script src="assets/template/assets/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="assets/template/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="assets/template/assets/libs/intlTelInput/intlTelInput.js"></script>
    <script src="dataJs/pickup_accept.js?v=4"></script>



</body>

</html>