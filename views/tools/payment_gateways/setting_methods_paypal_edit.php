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
    $data = cdp_getPaymentMethodAPIEdit($_GET['id']);
}

if (!isset($_GET['id']) or $data['rowCount'] != 1) {
    cdp_redirect_to("setting_payment_list.php");
}

$row_off = $data['data'];

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
    <title><?php echo $lang['tools-config61'] ?> | <?php echo $core->site_name ?></title>
    <!-- This Page CSS -->
    <!-- Custom CSS -->
    <?php include 'views/inc/head_scripts.php'; ?>
    <link href="assets/template/assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet">
    <link href="assets/template/dist/css/custom_swicth.css" rel="stylesheet">
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

            <!-- Button group part -->
            <div class="p-15">
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


            <div class="container-fluid">

                <div class="row">
                    <!-- Column -->

                    <div class="col-lg-12 col-xl-12 col-md-12">

                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['tools-methodpay2'] ?> <i class="icon-double-angle-right"></i> <?php echo $row_off->name_pay; ?></span></h3>
                                    </div>
                                </div>
                                <div><hr><br></div>

                                <div id="msgholder"></div>
                                <form class="form-horizontal form-material" id="update_data_paypal" name="update_data_paypal" method="post">

                                    <section>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['tools-config117'] ?></label>
                                                    <input type="text" class="form-control required" name="name_pay" value="<?php echo $row_off->name_pay; ?>" placeholder="<?php echo $lang['tools-config117'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="lastName1"><?php echo $lang['tools-config118'] ?></label>
                                                    <input type="text" class="form-control required" name="detail_pay" value="<?php echo $row_off->detail_pay; ?>" placeholder="<?php echo $lang['tools-config118'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="lastName1"> <?php echo $lang['tools-config119'] ?></label>
                                                    <input type="text" class="form-control required" name="paypal_client_id" value="<?php echo $row_off->paypal_client_id; ?>" placeholder="<?php echo $lang['tools-config119'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['tools-config120'] ?></label>
                                                    <label class="custom-control custom-checkbox" style="font-size: 18px;">
                                                        <input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1" 
                                                        <?php if ($row_off->is_active == 1) {
                                                                echo 'checked';
                                                            } ?>>

                                                        <span class="custom-control-indicator"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit"><?php echo $lang['tools-methodpay4'] ?> <span><i class="icon-ok"></i></span></button>
                                            <a href="payment_mode_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['tools-methodpay5'] ?></a>
                                        </div>
                                    </div>
                                    <input name="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
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

    <?php include('helpers/languages/translate_to_js.php'); ?>

    <script src="assets/template/dist/js/app-style-switcher.js"></script>
    <script src="assets/template/assets/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>

    <script src="dataJs/setting_payment_list.js"></script>

</body>

</html>