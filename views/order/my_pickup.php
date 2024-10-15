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
$statusrow = $core->cdp_getStatus();

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
    <title><?php echo $lang['left1072'] ?> | <?php echo $core->site_name ?></title>
    <link rel="stylesheet" href="assets/template/assets/libs/sweetalert2/sweetalert2.min.css">
    <?php include 'views/inc/head_scripts.php'; ?>
	 <link rel="stylesheet" href="assets/template/assets/libs/daterangepicker/daterangepicker.css">
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

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-5 align-self-center">
            <h4 class="page-title"> <?php echo $lang['left-menu-sidebar-65']; ?></h4>

        </div>
    </div>
</div>


<div class="container-fluid">

    <div class="row">
        <!-- Column -->
        <div class="col-lg-12 col-xl-12 col-md-12">
            <div class="card">
                <div class="card-body">


                    <div class="row">

                        <!-- <div class="col-md-6 <?php if ($direction_layout === 'rtl') {
                                                        echo 'pull-left';
                                                    } else {
                                                        echo 'pull-right';
                                                    } ?>">


                            <?php if ($userData->userlevel == 9 || $userData->userlevel == 3) { ?>
                                <div class="form-group">
                                    <a href="pickup_add_full.php"><button type="button" class="btn btn btn-outline-dark"><i class="ti-plus" aria-hidden="true"></i> <?php echo $lang['left77'] ?></button></a>
                                </div>

                            <?php

                            } else { ?>

                                <div class="form-group">
                                    <a href="pickup_add.php"><button type="button" class="btn btn-outline-dark"><i class="ti-plus" aria-hidden="true"></i> <?php echo $lang['left77'] ?></button></a>
                                </div>
                            <?php } ?>

                        </div> -->

                        <div class="col-sm-6">

                            <div class="input-group">
                                <input type="text" name="search" id="search" class="form-control input-sm float-right" placeholder="Search by Invoice/client/recipient address" onkeyup="cdp_load(1);">
                                <div class="input-group-append input-sm">
                                    <button type="submit" class="btn btn-outline-dark"><i class="fa fa-search"></i></button>
                                </div>

                            </div>
                        </div>
						
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
									
							<div class=" col-sm-12 col-md-2 mb-2">
                                        <div class="input-group">
                                            <select onchange="cdp_load(1);" class="form-control custom-select " id="status_courier" name="status_courier">
                                                
                                                <?php foreach ($statusrow as $row) : if($row->mod_style=='Approved') { ?>
                                                    <option value="<?php echo $row->id; ?>"><?php echo $row->mod_style; ?></option>

                                                <?php } endforeach; ?>
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
    </div>

   
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

    <?php include('views/modals/modal_cancel_pickup.php'); ?>
    <?php include('views/modals/modal_send_email.php'); ?>
    <?php include('views/modals/modal_update_driver.php'); ?>

    <?php include('views/modals/modal_charges_list.php'); ?>
    <?php include('views/modals/modal_charges_add.php'); ?>
    <?php include('views/modals/modal_charges_edit.php'); ?>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <?php include('helpers/languages/translate_to_js.php'); ?>
	<script src="assets/template/assets/libs/moment/moment.min.js"></script>
    <script src="assets/template/assets/libs/sweetalert2/sweetalert2.min.js"></script>
	<script src="assets/template/assets/libs/daterangepicker/daterangepicker.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>

    <script src="dataJs/my_pickup.js"></script>
  

</body>

</html>