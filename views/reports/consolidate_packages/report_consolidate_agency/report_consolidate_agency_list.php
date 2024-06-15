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
$statusrow = $core->cdp_getStatus();
$paymethodrow = $core->cdp_getPaymentMethod();


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
    <title><?php echo $lang['report-text78'] ?></title>

    <?php include 'views/inc/head_scripts.php'; ?>

    <link rel="stylesheet" href="assets/template/assets/libs/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">
    <style type="text/css">
        table {
            table-layout: fixed;
            width: 250px;
        }

        th,
        td {
            border: 0px solid black;
            width: 100px;
            word-wrap: break-word;
        }
    </style>

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <?php $agencyrow = $core->cdp_getBranchoffices(); ?>


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

            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">

                    </div>
                </div>
            </div>

            <div class="container-fluid">

                <div class="row">
                    <!-- Column -->

                    <div class="col-lg-12 col-xl-12 col-md-12">

                        <div class="card card-outline" style="border-top: 3px solid #bbb;">
                            <h4 class="card-title  ml-4 mt-3"> <?php echo $lang['report-text78'] ?></h4>

                            <div class="card-body">

                                <div class="row mb-4">

                                    <div class=" col-sm-12 col-md-3 mb-2">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                            </div>
                                            <input type="text" name="daterange" id="daterange" class="form-control float-right">
                                        </div>

                                    </div>

                                    <div class=" col-sm-12 col-md-3 mb-2">
                                        <div class="input-group">
                                            <select onchange="cdp_load(1);" class="form-control custom-select " id="status_courier" name="status_courier">
                                                <option value="0">--<?php echo $lang['left210'] ?>--</option>
                                                <?php foreach ($statusrow as $row) : ?>
                                                    <option value="<?php echo $row->id; ?>"><?php echo $row->mod_style; ?></option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-3 mb-2">
                                        <div class="input-group">
                                            <select onchange="cdp_load(1);" class="form-control custom-select " id="agency" name="agency">
                                                <option value="0"><?php echo $lang['report-text58'] ?></option>
                                                <?php foreach ($agencyrow as $row) : ?>
                                                    <option value="<?php echo $row->id; ?>"><?php echo $row->name_branch; ?></option>

                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class=" col-sm-12 col-md-3 mb-2">
                                        <div class="btn-group pull-right">
                                            <button onclick="cdp_exportPrint();" class="btn waves-effect waves-light btn-secondary mr-2">
                                                <i class='fa fa-print'></i> <?php echo $lang['report-text5'] ?></button>
                                            <button onclick="cdp_exportExcel();" class="btn waves-effect waves-light btn-secondary">
                                                <i class=' fas fa-file-excel'></i> <?php echo $lang['report-text6'] ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="outer_div"></div>


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


    <script src="assets/template/assets/libs/moment/moment.min.js"></script>
    <script src="assets/template/assets/libs/daterangepicker/daterangepicker.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>


    <script src="dataJs/report_consolidate_packages_agency.js"></script>
</body>

</html>