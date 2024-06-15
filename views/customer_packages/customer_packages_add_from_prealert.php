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



if (!$user->cdp_is_Admin())
    cdp_redirect_to("login.php");
require_once("helpers/querys.php");

require_once("helpers/phpmailer/class.phpmailer.php");
require_once("helpers/phpmailer/class.smtp.php");



if (isset($_GET['id'])) {
    $data_pre_alert = cdp_getPreAlert($_GET['id']);
}

if (!isset($_GET['id']) or $data_pre_alert['rowCount'] != 1) {
    cdp_redirect_to("prealert_list.php");
}

$data_pre_alert = $data_pre_alert['data'];

$userData = $user->cdp_getUserData();


$db->cdp_query("SELECT * FROM cdb_users where id= '" . $data_pre_alert->customer_id . "'");
$sender_data = $db->cdp_registro();

$db = new Conexion;

//Prefix tracking   
$sql = "SELECT * FROM cdb_settings";

$db->cdp_query($sql);

$db->cdp_execute();

$settings = $db->cdp_registro();

$order_prefix = $settings->prefix_online_shopping;

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
        <?php $track = $core->cdp_online_shopping_track(); ?>
        <?php $categories = $core->cdp_getCategories(); ?>
        <?php $code_countries = $core->cdp_getCodeCountries(); ?>

        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-12 align-self-center">
                        <h4 class="page-title"><i class="ti-package" aria-hidden="true"></i> <?php echo $lang['add-courier'] ?></h4>
                    </div>
                </div>
            </div>

            <form method="post" id="invoice_form" name="invoice_form" enctype="multipart/form-data">

                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <?php
                                            if (isset($_GET['prefix_error']) && $_GET['prefix_error'] == 1) { ?>

                                                <div class="alert alert-danger" id="success-alert">
                                                    <p><span class="icon-minus-sign"></span><i class="close icon-remove-circle"></i>
                                                        <?php echo $lang['message_ajax_error2']; ?>
                                                        <br>
                                                        Select the country code
                                                    </p>
                                                </div>
                                            <?php
                                            }
                                            ?>

                                            <label for="inputcom" class="control-label col-form-label">
                                                <?php echo $lang['leftorder12'] ?>
                                            </label>

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="prefix_check" id="prefix_check">
                                                            <label class="form-check-label" for="prefix_check">
                                                                <?php echo $lang['leftorder13'] ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="text" class="form-control" name="code_prefix" id="code_prefix" value="<?php echo $order_prefix; ?>" readonly>

                                                <select class="custom-select input-sm hide" id="code_prefix2" name="code_prefix2">
                                                    <option value=""><?php echo $lang['leftorder14'] ?></option>
                                                    <?php foreach ($code_countries as $row) : ?>
                                                        <option value="<?php echo $row->iso3; ?>"><?php echo $row->iso3 . ' - ' . $row->name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>

                                            </div>


                                        </div>


                                        <?php
                                        if ($core->code_number == 1) {
                                        ?>
                                            <div class="form-group col-md-6">
                                                <label for="inputcom" class="control-label col-form-label"><?php echo $lang['add-title24'] ?></label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" name="order_no" id="order_no" value="<?php echo $track; ?>" onchange="cdp_validateTrackNumber(this.value, '<?php echo $trackDigitsx; ?>');">
                                                    <input type="hidden" name="order_no_main" id="order_no_main" value="<?php echo $track; ?>">
                                                </div>
                                            </div>
                                        <?php } elseif ($core->code_number == 0) {

                                        ?>
                                            <div class="form-group col-md-6">
                                                <label for="inputcom" class="control-label col-form-label"><?php echo $lang['leftorder14442'] ?></label>
                                                <div class="input-group mb-3">
                                                    <input type="number" class="form-control" name="order_no" id="order_no" value="<?php print_r(cdp_generarCodigo('' . $core->digit_random . '')); ?>" onchange="cdp_validateTrackNumber(this.value, '<?php echo $trackDigitsx; ?>');">
                                                    <input type="hidden" name="order_no_main" id="order_no_main" value="<?php echo $track; ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="inputlname" class="control-label col-form-label"><?php echo $lang['left201'] ?> </label>
                                            <div class="input-group mb-3">
                                                <select class="custom-select col-12" id="agency" name="agency">
                                                    <?php foreach ($agencyrow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->name_branch; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group col-md-6">
                                            <label for="inputname" class="control-label col-form-label"><?php echo $lang['add-title14'] ?></label>
                                            <div class="input-group mb-3">
                                                <select class="custom-select col-12" id="origin_off" name="origin_off">

                                                    <?php foreach ($office as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->name_off; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Row -->
                    <!-- Row -->

                    <div class="row">
                        <div class="col-lg-12">
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
                                            <input type="checkbox" class="custom-control-input" name="notify_whatsapp_sender" id="notify_whatsapp_sender" value="1">
                                            <b> <?php echo $lang['leftorder14443']; ?> <i class="mdi mdi-whatsapp" style="font-size: 22px; color:#07bc4c;"></i></b>
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    <?php } ?>

                                    <?php
                                    if ($core->active_sms == 1) {
                                    ?>
                                        <label class="custom-control custom-checkbox" style="font-size: 18px; padding-left: 0px">
                                            <input type="checkbox" class="custom-control-input" name="notify_sms_sender" id="notify_sms_sender" value="1">
                                            <b> <?php echo $lang['leftorder14444']; ?> <i class="fa fa-envelope" style="font-size: 22px; color:#07bc4c;"></i></b>
                                            <span class="custom-control-indicator"></span>
                                        </label>
                                    <?php } ?>
                                    <div class="row">

                                        <div class="col-md-6 ">

                                            <label class="control-label col-form-label"><?php echo $lang['sender_search_title'] ?></label>

                                            <div class="row">
                                                <div class="col-md-10">
                                                    <div class="input-group">
                                                        <select class="select2 form-control custom-select" id="sender_id" name="sender_id">
                                                            <option value="<?php echo $sender_data->id; ?>"><?php echo $sender_data->fname . " " . $sender_data->lname; ?></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <div class="input-group-append input-sm">
                                                        <button type="button" class="btn btn-default" data-type_user="user_customer" data-toggle="modal" data-target="#myModalAddUser"><i class="fa fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-6 ">

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
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><i class="mdi mdi-book-multiple" style="color:#36bea6"></i> <?php echo $lang['add-title13'] ?></h4>
                                    <br>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sum2"><i class="fa fa-cube mr-1"></i><strong> <?php echo $lang['left63'] ?></strong></label>
                                                <input type="text" class="form-control" name="tracking_purchase" id="tracking_purchase" placeholder="Example: 009785454545554" value="<?php echo $data_pre_alert->tracking; ?>" style="border: 1; color: blueviolet;">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="ReceiptKind"><strong><?php echo $lang['left64'] ?></strong></label>
                                                <input type="text" class="form-control" name="provider_purchase" id="provider_purchase" placeholder="<?php echo $lang['left65'] ?>" value="<?php echo $data_pre_alert->provider_shop; ?>" style="border: 1; color: blueviolet;">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sum2"><strong> <?php echo $lang['left66'] ?></strong></label>
                                                <input onkeypress="return cdp_soloNumeros(event)" type="text" class="form-control" name="price_purchase" id="price_purchase" placeholder="<?php echo $lang['left67'] ?>" value="<?php echo $data_pre_alert->purchase_price; ?>" style="border: 1; color: blueviolet;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <label for="inputlname" class="control-label col-form-label"><?php echo $lang['add-title17'] ?></label>
                                            <div class="input-group">
                                                <select class="custom-select col-12" id="order_package" name="order_package" required="" style="border: 1px solid red;">
                                                    <option value="">--<?php echo $lang['left203'] ?>--</option>
                                                    <?php foreach ($packrow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->name_pack; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-4">

                                            <label for="inputlname" class="control-label col-form-label"><?php echo $lang['itemcategory'] ?></label>
                                            <div class="input-group">
                                                <select class="custom-select col-12" id="order_item_category" name="order_item_category" required>
                                                    <?php foreach ($categories as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->name_item; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['add-title18'] ?></label>
                                            <div class="input-group">
                                                <select class="custom-select col-12" id="order_courier" name="order_courier" required="">
                                                    <option value="">--<?php echo $lang['left204'] ?>--</option>
                                                    <?php foreach ($courierrow as $row) : ?>

                                                        <option value="<?php echo $row->id; ?>" <?php if ($row->id == $data_pre_alert->courier_com) {
                                                                                                    echo 'selected';
                                                                                                } ?>><?php echo $row->name_com; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <label for="inputEmail3" class="control-label col-form-label"><?php echo $lang['add-title22'] ?></label>
                                            <div class="input-group">
                                                <select class="custom-select col-12" id="order_service_options" name="order_service_options" required="" style="border: 1px solid red;">
                                                    <option value="">--<?php echo $lang['left205'] ?>--</option>
                                                    <?php foreach ($moderow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->ship_mode; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <!--/span-->
                                        <div class="form-group col-md-4">
                                            <label for="inputEmail3" class="control-label col-form-label"><?php echo $lang['add-title20'] ?></label>
                                            <div class="input-group">
                                                <select class="custom-select col-12" id="order_deli_time" name="order_deli_time" required="" style="border: 1px solid red;">
                                                    <option value="">--<?php echo $lang['left207'] ?>--</option>
                                                    <?php foreach ($delitimerow as $row) : ?>
                                                        <option value="<?php echo $row->id; ?>"><?php echo $row->delitime; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <!--/span-->

                                        <div class="col-md-4 hide">
                                            <label for="inputcontact" class="control-label col-form-label"><?php echo $lang['add-title15'] ?></i></label>
                                            <div class="input-group">
                                                <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i style="color:#ff0000" class="fa fa-calendar"></i></div>
                                                </div>
                                                <input type='text' class="form-control" name="order_date" id="order_date" placeholder="--<?php echo $lang['left206'] ?>--" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['add-title16'] ?>" readonly value="<?php echo date('Y-m-d'); ?>" />
                                            </div>
                                        </div>

                                        <?php

                                        if ($userData->userlevel == 3) { ?>

                                            <div class="col-md-4">
                                                <label for="inputname" class="control-label col-form-label"><?php echo $lang['left208'] ?></label>
                                                <div class="input-group">
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

                                            <div class="col-md-4">
                                                <label for="inputname" class="control-label col-form-label"><?php echo $lang['left208'] ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" style="color:#ff0000"><i class="fas fa-car"></i></span>
                                                    </div>
                                                    <select class="custom-select col-12" id="driver_id" name="driver_id">
                                                        <option value="0">--<?php echo $lang['left209'] ?>--</option>
                                                        <?php foreach ($driverrow as $row) : ?>
                                                            <option value="<?php echo $row->id; ?>"><?php echo $row->fname . ' ' . $row->lname; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php
                                        } ?>
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
                                            <button type="button" id="clean_file_button" class="btn btn-danger ml-3"> <i class='fa fa-trash' style="font-size:18px; cursor:pointer;"></i> <?php echo $lang['leftorder17']; ?> </button>

                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="resultados_file col-md-4 pull-right mt-4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="card-title">
                                                <i class="fas fas fa-boxes" style="color:#36bea6"></i>
                                                <?php echo $lang['left212'] ?>
                                            </h4>
                                        </div>
                                    </div>

                                    <!-- Listas item caja -->
                                    <div id="data_items"></div>

                                    <!-- Boton adicionar caja al listado -->
                                    <div class="col-md-3 text-left">
                                        <button type="button" onclick="addPackage()" name="add_rows" id="add_rows" class="btn btn-outline-dark"><span class="fa fa-plus"></span> <?php echo $lang['left231'] ?></button>
                                    </div>

                                    <div><br></div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <span class="text-secondary text-left"><?php echo $lang['leftorder17713'] ?></span>
                                        </div>
                                        <div class="col-md-1">
                                            <span class="text-secondary text-center" id="total_weight">0.00</span>
                                        </div>
                                        <div class="col-md-1 offset-3">
                                            <span class="text-secondary text-center" id="total_vol_weight">0.00</span>
                                        </div>
                                        <div class="col-md-1">
                                            <span class="text-secondary text-center" id="total_fixed">0.00</span>
                                        </div>
                                        <div class="col-md-1">
                                            <span class="text-secondary text-center" id="total_declared">0.00</span>
                                        </div>
                                    </div>
                                    <hr>

                                    <div class="row" style="margin-top: 20px;">
                                        <div class="table-responsive " id="table-totals">
                                            <table id="insvoice-item-table" class="table">
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
                                            </table>


                                            <!-- Listado de impuestos-->

                                            <div  class="" id="row">
                                                <div class="col-md-6">
                                                    <h4 class="card-title">
                                                        <i class="ti ti-briefcase " style="color:#36bea6"></i>
                                                        <?php echo $lang['messageerrorform30'] ?>
                                                    </h4>
                                                </div>
                                                <hr>
                                                <div class="row row-shadow input-container"> 
                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['left905'] ?> &nbsp; <?php echo $core->weight_p; ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->value_weight; ?>" name="price_lb" id="price_lb" style="border: 1px solid red;">
                                                        </div>
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['leftorder21'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" value="0" name="discount_value" id="discount_value" class="form-control form-control-sm">
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
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="100" name="insured_value" id="insured_value" style="border: 1px solid darkorange;">
                                                        </div>
                                                        
                                                        <td class="text-center" id="insured_label"></td>
                                                        
                                                     </div>
                                                  </div>


                                                  <div class="col-sm-12 col-md-6 col-lg-2">
                                                    <div class="form-group">
                                                        <label for="emailAddress1"><?php echo $lang['leftorder24'] ?> <?php echo $lang['leftorder222221'] ?> </label>
                                                        <div class="input-group">
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->insurance; ?>" name="insurance_value" id="insurance_value" style="border: 1px solid darkorange;">
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
                                                          <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->c_tariffs; ?>" name="tariffs_value" id="tariffs_value">
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
                                                              <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="<?php echo $core->tax; ?>" name="tax_value" id="tax_value">
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
                                                              <input type="text" onchange="calculateFinalTotal(this);" value="<?php echo $core->declared_tax; ?>" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" name="declared_value_tax" id="declared_value_tax">
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
                                                              <input type="text" onchange="calculateFinalTotal(this);" onkeypress="return isNumberKey(event, this)" class="form-control form-control-sm" value="0" name="reexpedicion_value" id="reexpedicion_value">
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
                                                            
                                                         </div>
                                                    </div>

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

                                                        <button type="submit" name="create_invoice" id="create_invoice" class="btn btn-success">
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

                            <input type="hidden" name="prealert_description" id="prealert_description" value="<?php echo $data_pre_alert->package_description; ?>" />
                            <input type="hidden" name="prealert_id" id="prealert_id" value="<?php echo $_GET['id']; ?>" />
                            <input type="hidden" name="core_meter" id="core_meter" value="<?php echo $core->meter; ?>" />
                            <input type="hidden" name="core_min_cost_tax" id="core_min_cost_tax" value="<?php echo $core->min_cost_tax; ?>" />
                            <input type="hidden" name="core_min_cost_declared_tax" id="core_min_cost_declared_tax" value="<?php echo $core->min_cost_declared_tax; ?>" />
                        </div>
                    </div>
            </form>



            <?php include('views/modals/modal_add_user_shipment.php'); ?>
            <?php include('views/modals/modal_add_addresses_user.php'); ?>



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
    <script src="assets/template/dist/js/app-style-switcher.js"></script>
    <script src="assets/template/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>


    <script src="dataJs/customers_packages_add_from_prealert.js"></script>



</body>

</html>