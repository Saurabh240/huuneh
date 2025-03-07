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



$userData = $user->cdp_getUserData();

/*echo 'Current Date time=>'.time().'<br>';
echo 'Fixed Time ->'.strtotime("2:00 PM");exit;*/

//echo 'Current Date time=>'.date('Y-m-d h:i:s a',time()).'<br>';
//echo 'Fixed Time ->'.date('Y-m-d h:i:s a',strtotime("2:00 PM"));exit;
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
    <title><?php echo $lang['add-courier'] ?> | <?php echo $core->site_name ?></title>
    <link rel="stylesheet" href="assets/template/assets/libs/intlTelInput/intlTelInput.css">
    <link rel="stylesheet" href="assets/template/assets/libs/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/template/assets/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">
    <link href="assets/template/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
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
        .disabled-cls{
            background:#EBEBE4;
        }
        .pac-container {
			z-index: 10000 !important;
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
        <?php $statusrow = $core->cdp_getStatus(); ?>
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
                        <h4 class="page-title"><i class="ti-package" aria-hidden="true"></i> <?php echo $lang['left82'] ?></h4> <br>
                    </div>
                </div>
            </div>

            <form method="post" id="invoice_form" name="invoice_form" enctype="multipart/form-data">

                <div class="container-fluid">
                    <?php if (isset($_GET['success']) && $_GET['success'] == 1) { ?>

                        <div class="alert alert-info" id="success-alert">
                            <p><span class="icon-info-sign"></span><i class="close icon-remove-circle"></i>
                                Your collection has been created successfully!
                            </p>
                        </div>
                    <?php
                    } else if (isset($_GET['success']) && $_GET['success'] == 0) { ?>

                        <div class="alert alert-danger" id="success-alert">
                            <p><span class="icon-minus-sign"></span><i class="close icon-remove-circle"></i>
                                <?php echo $lang['message_ajax_error2']; ?>
                            </p>
                        </div>
                    <?php
                    }
                    ?>


                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-information-outline" style="color:#36bea6"></i><?php echo $lang['langs_010']; ?></h4>
                                    <hr>
                                    <div class="resultados_ajax_add_user_modal_sender"></div>
                                    <div class="row">
                                        <div class="col-md-12 ">

                                            <label class="control-label col-form-label"><?php echo $lang['sender_search_title'] ?></label>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <select class="select2 form-control custom-select" id="sender_id" name="sender_id">
                                                            <option value="<?php echo $userData->id; ?>" selected> <?php echo $userData->fname . ' ' . $userData->lname; ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">

                                                    <input type="hidden" name="sender_id_temp" id="sender_id_temp" value="<?php echo $userData->id; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12 ">

                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['sender_search_address_title'] ?></label>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <select class="select2 form-control" id="sender_address_id" name="sender_address_id">
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="input-group-append input-sm">
                                                        <button id="add_address_sender" data-type_user="user_customer" data-toggle="modal" data-target="#myModalAddUserAddresses" type="button" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <!-- <div class="row">
                                                 <div class="col-md-12">
                                                    <div class="input-group-append input-sm">
                                                    <label><input type="checkbox" id="id_of_your_checkboxsender" style="margin-left: 12px; margin-top: 10px;"> Send to a different Address </label>
                                                 </div>
                                                </div>
                                                </div> -->
                                            
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
                                      <div class="row">
                                            <div class="col-md-12">
                                                <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['recipient_search_title'] . ' (To add a new recipient click the + sign)' ?></label>

                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="input-group">
                                                            <select class="select2 form-control custom-select" id="recipient_id" name="recipient_id">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="input-group-append input-sm">
                                                            <button id="add_recipient" type="button" data-type_user="user_recipient" data-toggle="modal" data-target="#myModalAddRecipient" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="col-md-12">

                                                <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['recipient_search_address_title'] ?></label>

                                                <div class="row">
                                                    <div class="col-md-10">
                                                        <div class="input-group">
                                                            <select class="select2 form-control" id="recipient_address_id" name="recipient_address_id" disabled="">
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="input-group-append input-sm">
                                                            <button disabled id="add_address_recipient" type="button" data-type_user="user_recipient" data-toggle="modal" data-target="#myModalAddRecipientAddresses" class="btn btn-default"><i class="fa fa-plus"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="input-group-append input-sm">
                                                                <label><input type="checkbox" id="id_of_your_checkboxreceiver" style="margin-left: 12px; margin-top: 10px;"> Send to a different Address</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>    
  

                                </div>
                            </div>
                        </div>        
                    </div>


                    <input type="hidden" id="businessType" value="<?php echo $userData->business_type; ?>" />

                <?php  if( $userData->business_type == "flower_shop" || $userData->business_type == "flat_1" || $userData->business_type == "flat_2" ) { ?>
				<div class="card" id="flowerBusinessCard">
                    <div class="card-body">
                        <!-- Charge and Rx Number Row -->
                        
                        
                        <!-- Tags Section -->
                        <div class="mb-3">
                            <label class="form-label">Tags</label>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="wreath" name="tags[]" value="Wreath">
                            <label class="form-check-label" for="wreath">Wreath</label>
                            </div>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="standing_casket_spray" name="tags[]" value="Standing/Casket Spray">
                            <label class="form-check-label" for="standing_casket_spray">Standing/Casket Spray</label>
                            </div>
                            
                        </div>

                        <div class="mb-3 row" id="piece_div">
                            <div class="col-md-6">
                            <label for="pieces" class="form-label">Pieces</label>
                            <input type="number" class="form-control" name="pieces" id="pieces" placeholder="No of pieces" min=0 onchange="pieces_check()">
							 <div class="form-text">Each piece is to be $3</div>
                            </div>
                            
                        </div>
						
                        <!-- Notes Section -->
                        <!--div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notesForDriver_flower" name="notes_for_driver" rows="3" placeholder="Please be brief"></textarea>
                            <div class="form-text">Add any special instruction notes for the driver</div>
                        </div-->
                        
                       
                    </div>
                </div>

				<?php } ?>
				 <?php  if( $userData->business_type == "warehouses" ) { ?>
				 
				<div class="card" id="warehouseCard">
                    <div class="card-body">
                        <!-- Charge and Rx Number Row -->
                        <div class="mb-3 row" id="piece_div_warehouse">
                            <div class="col-md-6">
                            <label for="pieces_warehouse" class="form-label">Pieces</label>
                            <input type="number" class="form-control" name="pieces" id="pieces_warehouse" placeholder="No of pieces" min=0 onchange="calculateFinalTotal()">
							 <div class="form-text">Each piece is to be $2</div>
                            </div>
                        </div>
                    </div>
                </div>
				 <?php } ?>
				 
                <?php  if( $userData->business_type == "pharmacy" || $userData->business_type == "pharmacy_2" || $userData->business_type == "pharmacy_3" ) { ?>
                    <div class="card">
                        <div class="card-body">
                            <!-- Charge and Rx Number Row -->
                            <div class="mb-3 row">
                                <div class="col-md-6">
                                <label for="charge" class="form-label">Charge</label>
                                <input type="text" class="form-control" name="charge" id="charge" placeholder="$ amount to be collected">
                                </div>
                                <div class="col-md-6">
                                <label for="rxNumber" class="form-label"># of Rx - <span class="text-muted">For Pharmacy tracking purpose only</span></label>
                                <input type="text" class="form-control" name="no_of_rx" id="rxNumber" placeholder="# of Rx">
                                </div>
                            </div>
                            
                            <!-- Tags Section -->
                            <div class="mb-3">
                                <label class="form-label">Tags</label>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="fridgeItem" name="tags[]" value="Fridge Item (2-4 C)">
                                <label class="form-check-label" for="fridgeItem">Fridge Item (2-4 C)</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="handDeliver" name="tags[]" value="Hand Deliver">
                                <label class="form-check-label" for="handDeliver">Hand Deliver</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="narcotics" name="tags[]" value="Narcotics">
                                <label class="form-check-label" for="narcotics">Narcotics</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pickupRxPaper" name="tags[]" value="Pickup Rx Paper">
                                <label class="form-check-label" for="pickupRxPaper">Pickup Rx Paper</label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pickUpOldMedication" name="tags[]" value="Pick up Old Medication">
                                <label class="form-check-label" for="pickUpOldMedication">Pick up Old Medication</label>
                                </div>
                            </div>

                            
                            <!-- Notes Section -->
                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control" id="notesForDriver" name="notes_for_driver" rows="3" placeholder="Please be brief"></textarea>
                                <div class="form-text">Add any special instruction notes for the driver</div>
                            </div>
                            
                            <!-- Dispatch Button -->
                            <!-- <div class="text-end">
                                <button type="submit" class="btn btn-primary">Dispatch</button>
                            </div> -->
                        </div>
                    </div>
                <?php  } ?>


                    <!-- Row -->
                    <div class="row">
                    <div class="col-lg-4 h-70"  >
                            <div style="height: 26rem;" class=" card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-book-multiple" style="color:#36bea6"></i> <?php echo $lang['add-title13'] ?></h4>
                                    <br>
                                        <div class="row">
											<!-- <div class="form-group col-md-4">
												<label for="inputEmail3" class="control-label col-form-label">Distance</label>
												<div class="input-group mb-3">
                                                    </div>
                                                </div> -->
                                            <input type="hidden" name="distance" class="form-control" id="distance">
                                            <?php date_default_timezone_set("America/Montreal"); ?>
											<div class="form-group col-md-7">
												<label for="inputEmail3" class="control-label col-form-label">Delivery Type</label>
												<div class="input-group mb-3">
													<select class="form-control custom-select" id="deliveryType" name="deliveryType" required style="width: 100%;">
														<option value="" selected>Select Delivery Type</option>
														
														<option <?php if(time() > strtotime("1:00 PM") || date('l') === 'Sunday' || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?>value="SAMEDAY (BEFORE 9PM)">SAMEDAY (BEFORE 9PM)</option>
														
														
														<?php  if( $userData->business_type == "pharmacy" || $userData->business_type == "pharmacy_2" || $userData->business_type == "pharmacy_3" ) { ?>
														<option  <?php if(date('l') === 'Saturday'  || date("d-m") === "25-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "24-12"  || date("d-m") === "30-12") { echo "disabled='disabled' class='disabled-cls'"; }?>   value="NEXT DAY (BEFORE 9PM)">NEXT DAY (BEFORE 9PM)</option>
														
														 <?php }else{ ?>
														
														 
														<option <?php if(time() > strtotime("12:30 PM") || date('l') === 'Sunday'  || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?>value="SAMEDAY (BEFORE 7PM)">SAMEDAY (BEFORE 7PM)</option>
														<option <?php if(time() > strtotime("10:30 AM") || date('l') === 'Sunday'  || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?>value="SAME DAY (1PM to 4PM)">SAME DAY (1PM to 4PM)</option>
														<option <?php if(time() > strtotime("12:00 PM") || date('l') === 'Sunday' || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?> value="SAME DAY (BEFORE 5PM)">SAME DAY (BEFORE 5PM)</option>
														<option <?php if(time() > strtotime("11:00 AM") || date('l') === 'Sunday'  || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (4 HOURS)">RUSH (4 HOURS)</option>
														<option <?php if(time() > strtotime("12:30 PM") || date('l') === 'Sunday' || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (3 HOURS)">RUSH (3 HOURS)</option>
														<option <?php if(time() > strtotime("1:30 PM") || date('l') === 'Sunday' || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?> value="RUSH (2 HOURS)">RUSH (2 HOURS)</option>
														<option <?php if(time() > strtotime("2:30 PM") || date('l') === 'Sunday' || date("d-m") === "25-12" || date("d-m") === "26-12" || date("d-m") === "31-12" || date("d-m") === "01-01" || date("d-m") === "02-01") { echo "disabled='disabled' class='disabled-cls'"; }?> value="URGENT (90 MINUTES)">URGENT (90 MINUTES)</option>
														<?php if(date("d-m") !== "24-12" && date("d-m") !== "30-12"){ ?>
														<option  <?php if(date('l') === 'Saturday'  || date("d-m") === "25-12" || date("d-m") === "31-12" || date("d-m") === "01-01") { echo "disabled='disabled' class='disabled-cls'"; }?>  value="NEXT DAY (BEFORE 7PM)">NEXT DAY (BEFORE 7PM)</option>
														<option <?php if(date('l') === 'Saturday'  || date("d-m") === "25-12" || date("d-m") === "31-12" || date("d-m") === "01-01") { echo "disabled='disabled' class='disabled-cls'"; }?> value="NEXT DAY (BEFORE 5PM)">NEXT DAY (BEFORE 5PM)</option>
														<option <?php if(date('l') === 'Saturday'  || date("d-m") === "25-12" || date("d-m") === "31-12" || date("d-m") === "01-01") { echo "disabled='disabled' class='disabled-cls'"; }?>  value="NEXT DAY (BEFORE 2PM)">NEXT DAY (BEFORE 2PM)</option>
														<option <?php if(date("d-m") === "25-12" || date("d-m") === "31-12" || date("d-m") === "01-01") { echo "disabled='disabled' class='disabled-cls'"; }?>  value="NEXT DAY (BEFORE 11:30AM)">NEXT DAY (BEFORE 11:30AM)</option>
														<option <?php if(date("d-m") === "25-12" || date("d-m") === "31-12" || date("d-m") === "01-01") { echo "disabled='disabled' class='disabled-cls'"; }?>  value="NEXT DAY (BEFORE 10:30AM)">NEXT DAY (BEFORE 10:30AM)</option>
														
														
														 <?php } ?>
														 
														 <?php } ?>
														
														 
														  
														
                                                      
													</select>
												</div>
											</div>

											<!-- <div class="form-group col-md-3">
												<label for="inputlname" class="control-label col-form-label"><?php echo $lang['itemcategory'] ?></label>
												<div class="input-group">
													<select class="custom-select col-12" id="order_item_category" name="order_item_category" required>
														<?php foreach ($categories as $row) : ?>
															<option value="<?php echo $row->id; ?>"><?php echo $row->name_item; ?></option>
														<?php endforeach; ?>
													</select>
												</div>

											</div>

											<div class="form-group col-md-3">
												<label for="inputlname" class="control-label col-form-label"><?php echo $lang['add-title17'] ?></label>
												<div class="input-group mb-3">
													<select class="custom-select col-12" id="order_package" name="order_package">
														<?php foreach ($packrow as $row) : ?>
															<option value="<?php echo $row->id; ?>"><?php echo $row->name_pack; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div> -->
                                        
                                        

											<div class="col-md-4" style="display: none;">
												<label for="inputcontact" class="control-label col-form-label"><?php echo $lang['add-title1555'] ?></i></label>
												<div class="input-group">
													<div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
														<div class="input-group-text"><i style="color:#ff0000" class="fa fa-calendar"></i></div>
													</div>
													<input type='text' class="form-control" name="order_date" id="order_date" placeholder="--<?php echo $lang['left206'] ?>--" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['add-title1555'] ?>" readonly value="<?php echo date('Y-m-d'); ?>" />
												</div>
											</div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div>
                                                    <label class="control-label" id="selectItem"> <?php echo $lang['left21552']; ?></label>
                                                </div>
                                                <textarea  class="form-control" name="delivery_notes" id="delivery_notes" rows="4" cols="50" placeholder="<?php echo $lang['left21553'] ?>"  ></textarea>

                                               

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div>
                                                    <label class="control-label" id="selectItem"> <?php echo $lang['leftorder15']; ?></label>
                                                </div>

                                                <input class="custom-file-input" id="filesMultiple" name="filesMultiple[]" multiple="multiple" type="file" style="display: none;" onchange="cdp_validateZiseFiles(); cdp_preview_images();" />


                                                <button type="button" id="openMultiFile" class="btn btn-default  pull-left  mb-4"> <i class='fa fa-paperclip' id="openMultiFile" style="font-size:18px; cursor:pointer;"></i> <?php echo $lang['leftorder16']; ?> </button>


                                            </div>
                                        </div>

                                        <div class="col-md-12 row" id="image_preview"></div>
                                        <div class="col-md-4 mt-4">
                                            <div id="clean_files" class="hide">
                                                <button type="button" id="clean_file_button" class="btn btn-danger ml-3">
                                                    <i class='fa fa-trash' style="font-size:18px; cursor:pointer;"></i>
                                                    <?php echo $lang['leftorder17']; ?>
                                                </button>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="resultados_file col-md-4 pull-right mt-4">


                                            </div>
                                        </div>


                                        <div class="row">

                                            <div class="resultados_file col-md-4 pull-right mt-4">

                                            </div>
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
                                                        <td colspan="4" class="text-right"><b><?php echo $lang['leftorder2021'] ?></b></td>
                                                        <td colspan="1"></td>
                                                        <td class="text-right">
                                                            <?php
                                                            if ($core->for_symbol !== null) {
                                                            ?>
                                                                <b> <?php echo $core->for_symbol; ?> </b>
                                                            <?php
                                                            }
                                                            ?>
                                                            <span id="subtotal"> 0.00</span>
                                                        </td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                            </table> -->


                                            <!-- Listado de impuestos-->

                                            <div  class="card" id="row">
                                                <div class="col-md-6">
                                                    <h4 class="card-title">
                                                        <i class="ti ti-briefcase " style="color:#36bea6"></i>
                                                        <?php echo $lang['messageerrorform30'] ?>
                                                    </h4>
                                                </div>
												
                                                <hr>
                                                <div class="row row-shadow input-container"> 
                                                <!-- <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder1879'] ?></label>
                                                            <?php
                                                            if ($core->for_symbol !== null) {
                                                            ?>
                                                                <b> <?php echo $core->for_symbol; ?> </b>
                                                            <?php
                                                            }
                                                            ?>
                                                            <span id="fixed_value_label"> 0.00</span>
                                                            <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax">
                                                         </div>
                                                    </div>
                                                    
                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder1880'] ?></label>
                                                           
                                                            <span id="total_distance"> 0.00</span>
                                                          
                                                         </div>
                                                    </div> -->
                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                    <div class="form-group">
                                                        <label for="emailAddress1">Distance(In KM)</label>
                                                            
                                                            
                                       
                                                            <span id="total_distance"> 0.00</span>
                                                            
                                                    </div>
                                                 </div>

                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                    <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder2021'] ?>  </label>
                                                            
                                                                <?php
                                                                if ($core->for_symbol !== null) {
                                                                ?>
                                                                    <b> <?php echo $core->for_symbol; ?> </b>
                                                                <?php
                                                                }
                                                                ?>
                                                            <span id="total_before_tax"> 0.00</span>
                                                            <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax">
                                                            <input type="hidden" name="total_envio_ajax" id="total_envio_ajax">
                                                    </div>
                                                 </div>

                                                 <div class="col-sm-12 col-md-4 col-lg-3">
                                                     <div class="form-group">
                                                            <label for="emailAddress1"><?php echo "Tax" ;?> (<?php echo '13%';//echo $core->tax; ?>)</label>
                                                                
                                                                
                                                                <?php
                                                                if ($core->for_symbol !== null) {
                                                                ?>
                                                                    <b> <?php echo $core->for_symbol; ?> </b>
                                                                <?php
                                                                }
                                                                ?>
                                                                <span id="tax_13"> 0.00</span>
                                                                
                                                        </div>
                                                 </div>

                                                <div class="col-sm-12 col-md-4 col-lg-3">
                                                     <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder2020'] ?></label>
                                                                
                                                                
                                                                <?php
                                                                if ($core->for_symbol !== null) {
                                                                ?>
                                                                    <b> <?php echo $core->for_symbol; ?> </b>
                                                                <?php
                                                                }
                                                                ?>
                                                                <span id="total_after_tax"> 0.00</span>
                                                                
                                                        </div>
                                                 </div>
                                                
                                                 
                                                 
                                                
                                                  <!-- <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['left905'] ?> &nbsp; <?php echo $core->weight_p; ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->value_weight; ?>" name="price_lb" id="price_lb" style="border: 1px solid red;" readonly>
                                                        </div>
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['leftorder21'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" value="0" name="discount_value" id="discount_value" class="form-control form-control-sm" readonly>
                                                        </div>
                                                        
                                                        <?php
                                                        if ($core->for_symbol !== null) {
                                                        ?>
                                                            <b> <?php echo $core->for_symbol; ?> </b>
                                                        <?php
                                                        }
                                                        ?>
                                                        <span id="discount"> 0.00</span>
                                                        
                                                     </div>
                                                  </div>

                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['leftorder22'] ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="100" name="insured_value" id="insured_value" style="border: 1px solid darkorange;" readonly>
                                                        </div>
                                                        
                                                        <td class="text-center" id="insured_label"></td>
                                                        
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['leftorder24'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->insurance; ?>" name="insurance_value" id="insurance_value" style="border: 1px solid darkorange;" readonly>
                                                        </div>
                                                        
                                                        <?php
                                                        if ($core->for_symbol !== null) {
                                                        ?>
                                                            <b> <?php echo $core->for_symbol; ?> </b>
                                                        <?php
                                                        }
                                                        ?>
                                                        <span id="insurance"> 0.00</span>
                                                        
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['leftorder25'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->c_tariffs; ?>" name="tariffs_value" id="tariffs_value" readonly>
                                                        </div>
                                                        
                                                        <?php
                                                        if ($core->for_symbol !== null) {
                                                        ?>
                                                            <b> <?php echo $core->for_symbol; ?> </b>
                                                        <?php
                                                        }
                                                        ?>
                                                        <span id="total_impuesto_aduanero"> 0.00</span>
                                                        
                                                     </div>
                                                  </div>


                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder67'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                            <div class="input-group">
                                                              <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->tax; ?>" name="tax_value" id="tax_value" readonly>
                                                            </div>
                                                            
                                                            <?php
                                                            if ($core->for_symbol !== null) {
                                                            ?>
                                                                <b> <?php echo $core->for_symbol; ?> </b>
                                                            <?php
                                                            }
                                                            ?>
                                                            <span id="impuesto"> 0.00</span>
                                                            
                                                         </div>
                                                    </div>
                                                
                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder19'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                            <div class="input-group">
                                                              <input type="text" onchange="calculateFinalTotal(this);" value="<?php echo $core->declared_tax; ?>" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" name="declared_value_tax" id="declared_value_tax" readonly>
                                                            </div>
                                                            
                                                            <?php
                                                            if ($core->for_symbol !== null) {
                                                            ?>
                                                                <b> <?php echo $core->for_symbol; ?> </b>
                                                            <?php
                                                            }
                                                            ?>
                                                            <span id="declared_value_label"> 0.00</span>
                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['langs_048'] ?> </label>
                                                            <div class="input-group">
                                                              <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="0" name="reexpedicion_value" id="reexpedicion_value" readonly>
                                                            </div>
                                                            
                                                            <td class="text-right" id="reexpedicion_label"></td>
                                                            
                                                        </div>
                                                    </div>



                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder1878'] ?></label>
                                                            <?php
                                                            if ($core->for_symbol !== null) {
                                                            ?>
                                                                <b> <?php echo $core->for_symbol; ?> </b>
                                                            <?php
                                                            }
                                                            ?>
                                                            <span id="fixed_value_label"> 0.00</span>
                                                            <input type="hidden" name="fixed_value_ajax" id="fixed_value_ajax">
                                                         </div>
                                                    </div>


                                                    <div class="col-sm-12 col-md-6 col-lg-2">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['leftorder2020'] ?></label>
                                                            <?php
                                                            if ($core->for_symbol !== null) {
                                                            ?>
                                                                <b> <?php echo $core->for_symbol; ?> </b>
                                                            <?php
                                                            }
                                                            ?>
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
                                                        <input type="hidden" name="total_item_files" id="total_item_files" value="0" />
                                                        <input type="hidden" name="deleted_file_ids" id="deleted_file_ids" />
                                                        
                                                        &nbsp;
                                                        <button type="submit" name="create_invoice" id="create_invoice" class="btn btn-success" disabled>
                                                            <i class="fas fa-save"></i>
                                                            <span class="ml-1"><?php echo $lang['left1103'] ?></span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" value="<?php echo $core->value_weight; ?>" name="price_lb" id="price_lb">
                            <input type="hidden" value="0" name="discount_value" id="discount_value">
                            <input type="hidden" value="100" name="insured_value" id="insured_value">
                            <input type="hidden" value="<?php echo $core->insurance; ?>" name="insurance_value" id="insurance_value">
                            <input type="hidden" value="<?php echo $core->c_tariffs; ?>" name="tariffs_value" id="tariffs_value">
                            <input type="hidden" value="<?php echo '13' ?>" name="tax_value" id="tax_value">
                            <input type="hidden" value="<?php echo $core->declared_tax; ?>" name="declared_value_tax" id="declared_value_tax">
                            <input type="hidden" value="0" name="reexpedicion_value" id="reexpedicion_value">

                            <input type="hidden" name="core_meter" id="core_meter" value="<?php echo $core->meter; ?>" />
                            <input type="hidden" name="core_min_cost_tax" id="core_min_cost_tax" value="<?php echo $core->min_cost_tax; ?>" />
                            <input type="hidden" name="core_min_cost_declared_tax" id="core_min_cost_declared_tax" value="<?php echo $core->min_cost_declared_tax; ?>" />

                    </div> 
                    </div>   
                   
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
	<?php  
	if(isset($_SESSION['show_login_popup']) && $_SESSION['show_login_popup']){ 
	unset($_SESSION['show_login_popup']); 
	?>
<div class="modal" tabindex="-1" role="dialog" id="all_user_alert">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
			<p><b>Dear valued Huuneh client,</b> </p>

			<p>Please note that Huuneh will be closed and will not be open on family day which is on Monday, February 17, 2025.</p>

			<p>Wishing you all an awesome family day on the 17th and enjoy the day with your loved ones!! </p>

			 <p>Huuneh Management</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
	   $("#all_user_alert").modal('show');
 });
 </script>
 <?php /*if($userData->business_type == "pharmacy_3") { ?>
	<div class="modal" tabindex="-1" role="dialog" id="myModal_alert">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
			<p><b>Hi all valued Huuneh PHARMACY CLIENTS:</b> </p>

			<p>We had a great 2024 because of awesome people and businesses like you!! ❤️</p>

			<p>just some updates/exciting news for 2025 from Huuneh: </p>

			<p>1. To continue to serve you at the highest level and happyness, we are going to be slightly bumping up price from $4+tax to $5+tax as of February 1st. This is to make sure we can continue to keep the drivers happy that deliver your prescriptions day in and day out as you already know cost of living/gas/inflation is at a all time high and we want to make sure you continue to receive the top tier service from us. </p>

			<p>2. Also moving forward, for all of our pharmacies we will be returning your cash/consent forms/rx slips every Monday/Tuesday the following week instead of you having to wait super long to receive your property back. We will be adding a $5 return charge on your weekly invoice that week to fulfill this return in a timely manner so that way you get your property back asap. </p>

			 <p>3. We will also be shortly introducing a brand new customer service phone line for you and your team to call moving forward for any questions/concerns you may have. This will be launched on February 1st.  So once that customer service line is live, you won’t be calling our cell phones directly, instead you and your team will be calling our customer service line directly only. All communication between us will only take place on that recorded customer service line.</p>

			 <p>We are super excited about these changes as this is gonna make the delivery journey for you much easier and smoother with us. </p>

			 <p>Should you have any questions, feel free to call us directly! </p>

			 <p>We look forward to an exciting 2025 working with you!! </p>

			 <p>Thanks!</p>
      </div>
      <div class="modal-footer">
     
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
	   $("#myModal_alert").modal('show');
 });
 </script>
	<?php }*/ ?>
<?php } ?>
    <?php include('helpers/languages/translate_to_js.php'); ?>

    <script src="assets/template/assets/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="assets/template/assets/libs/sweetalert2/sweetalert2.min.js"></script>
    <script src="assets/template/assets/libs/intlTelInput/intlTelInput.js"></script>
    <script src="assets/template/dist/js/app-style-switcher.js"></script>
    <script src="assets/template/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ&callback=initAutocomplete&libraries=places&v=weekly"
      defer
    ></script>
    <script src="dataJs/pickup_add.js?v=10"></script>

</body>

</html>
