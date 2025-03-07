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
    <title> <?php echo $lang['leftorder288'] ?> | <?php echo $core->site_name ?></title>
    <?php include 'views/inc/head_scripts.php'; ?>
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">



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


        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">


            <div class="container-fluid mb-5">


                <div class="row">
                    <!-- Column -->

                    <div class="col-lg-12 col-xl-12 col-md-12">

                        <!-- Action part -->
                        <div class="bg-light">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="resultados_ajax"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Action part -->

                        <div class="card">
                            <div class="card-body"> 

                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['flat-price-2'] ?></span></h3>
                                    </div>
                                </div>
                                <div><hr></div>

                                <div class="m-t-40">
                                    <div class="d-flex">
                                        <div class="mr-auto">
                                            <div class="form-group">
                                                <a href="flat_price_import.php">
                                                    <button type="button" class="btn btn-default btn">
                                                        <i class="ti-plus" aria-hidden="true"></i>
                                                        <?php echo $lang['flat-price-1'] ?>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select onchange="cdp_load(1)" style="width: 100% !important;" class="select2 form-control" name="business_type" id="business_type">
											<option value="">Select Type</option>
															<option value="special">Special</option>
															<option value="flat_1">Flat 1</option>
                                                            <option value="flat_2">Flat 2</option>
															 <option value="karensflowershop_next_day">Karensflowershop Next Day</option>
                                                            <option value="karensflowershop_same_day">Karensflowershop Same Day</option>
															 <option value="karensflowershop_next_day_10AM_5PM">karensflowershop Next Day 10AM 5PM</option>
                                                            <option value="karensflowershop_same_day_1PM_5PM">Karensflowershop Same Day 1PM 5PM</option>
                                            </select>
                                        </div>
                                    </div>
									
									<div class="col-md-4">
                                        <div class="form-group">
                                            <select onchange="cdp_load(1)" style="width: 100% !important;" class="select2 form-control" name="country_origin" id="country_origin">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select onchange="cdp_load(1)" style="width: 100% !important;" class="select2 form-control" name="country_destiny" id="country_destiny">
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive-sm">

                                    <div class="outer_div"></div>

                                </div>

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
    <script src="dataJs/flat_price_list.js"></script>

</body>

</html>