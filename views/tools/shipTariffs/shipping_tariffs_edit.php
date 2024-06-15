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

$userData = $user->cdp_getUserData();

require_once('helpers/querys.php');

if (isset($_GET['id'])) {
    $data = cdp_getTariffsEdit($_GET['id']);
}

if (!isset($_GET['id']) or $data['rowCount'] != 1) {
    cdp_redirect_to("shipping_tariffs_list.php");
} 

$row_data = $data['data'];

$db->cdp_query("SELECT * FROM cdb_countries where id= '" . $row_data->origin . "'");
$origin = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_countries where id= '" . $row_data->destiny . "'");
$destiny = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_states where id= '" . $row_data->state . "'");
$destinystate = $db->cdp_registro();


$db->cdp_query("SELECT * FROM cdb_cities where id= '" . $row_data->city . "'");
$destinycity = $db->cdp_registro();

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
    <title><?php echo $lang['leftorder302'] ?> | <?php echo $core->site_name ?></title>
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
                                <form class="form-horizontal form-material" id="save_data" name="save_data" method="post">
                                    <header><span><?php echo $lang['leftorder302'] ?></span></header> <br><br>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><?php echo $lang['leftorder296'] ?></label>
                                                    <select style="width: 100% !important;" class="select2 form-control" name="country_origin" id="country_origin">
                                                        <option value="<?php echo $origin->id; ?>"><?php echo $origin->name; ?></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo $lang['leftorder293'] ?></label>
                                                    <select style="width: 100% !important;" class="select2 form-control" name="country_destiny" id="country_destiny">
                                                        <option value="<?php echo $destiny->id; ?>"><?php echo $destiny->name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo $lang['leftorder295'] ?></label>
                                                    <select style="width: 100% !important;" class="select2 form-control" name="state_destinystates" id="state_destinystates">
                                                        <option value="<?php echo $destinystate->id; ?>"><?php echo $destinystate->name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label><?php echo $lang['leftorder294'] ?></label>
                                                    <select style="width: 100% !important;" class="select2 form-control" name="city_destinycities" id="city_destinycities">
                                                        <option value="<?php echo $destinycity->id; ?>"><?php echo $destinycity->name; ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">

                                            <div class="col-md-6">
                                                <label for="lastName1"><?php echo $lang['leftorder297'] ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-amount-down"></i></span>
                                                    </div>
                                                    <input type="text" value="<?php echo $row_data->initial_range; ?>" class="form-control requited" name="initial_range" id="initial_range" placeholder="<?php echo $lang['leftorder297'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="lastName1"><?php echo $lang['leftorder298'] ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-sort-amount-up"></i></span>
                                                    </div>
                                                    <input type="text" value="<?php echo $row_data->final_range; ?>" class="form-control requited" name="final_range" id="final_range" placeholder="<?php echo $lang['leftorder298'] ?>">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="lastName1"><?php echo $lang['leftorder299'] ?></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">$</span>
                                                    </div>
                                                    <input type="text" value="<?php echo $row_data->price; ?>" class="form-control requited" name="tariff_price" id="tariff_price" placeholder="<?php echo $lang['leftorder299'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <input name="id" id="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
                                    </section>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit"><?php echo $lang['leftorder302'] ?> <span><i class="icon-ok"></i></span></button>
                                            <a href="shipping_tariffs_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['global-buttons-3'] ?></a>
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

    <script src="dataJs/shipping_tariffs_edit.js"></script>
</body>

</html>