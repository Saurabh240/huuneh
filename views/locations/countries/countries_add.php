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
    <title><?php echo $lang['leftorder311'] ?> | <?php echo $core->site_name ?></title>
    <?php include 'views/inc/head_scripts.php'; ?>

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
                    <!-- Column -->

                    <div class="col-lg-12 col-xl-12 col-md-12">

                        <div class="card">
                            <div class="card-body">

                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><?php echo $lang['leftorder311'] ?></h3>
                                    </div>
                                </div>
                                <div><hr><br></div>
                                <form class="form-horizontal form-material" id="save_data" name="save_data" method="post">

                                    <section>
                                        <div class="row">
                                            <div class="col-md-3 mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder303'] ?></label>
                                                    <input type="text" class="form-control" name="name" id="name" placeholder="<?php echo $lang['leftorder303'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3 mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder304'] ?></label>
                                                    <input type="text" class="form-control" name="iso3" id="iso3" placeholder="<?php echo $lang['leftorder304'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3  mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder313'] ?></label>
                                                    <input type="text" class="form-control" name="phone_code" id="phone_code" placeholder="<?php echo $lang['leftorder313'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3  mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder312'] ?></label>
                                                    <input type="text" class="form-control" name="capital" id="capital" placeholder="<?php echo $lang['leftorder312'] ?>">
                                                </div>
                                            </div>

                                            
                                        </div>

                                        <div class="row">

                                            <div class="col-md-3 mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder305'] ?></label>
                                                    <input type="text" class="form-control" name="region" id="region" placeholder="<?php echo $lang['leftorder305'] ?>">
                                                </div>
                                            </div>

                                            

                                            <div class="col-md-3  mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder306'] ?></label>
                                                    <input type="text" class="form-control" name="currency_name" id="currency_name" placeholder="<?php echo $lang['leftorder306'] ?>">
                                                </div>
                                            </div>

                                            <div class="col-md-3  mt-3">
                                                <div class="form-group">
                                                    <label for="firstName1"><?php echo $lang['leftorder307'] ?></label>
                                                    <input type="text" class="form-control" name="currency_symbol" id="currency_symbol" placeholder="<?php echo $lang['leftorder307'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                    </section>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit"><?php echo $lang['leftorder314'] ?></i></span></button>
                                            <a href="countries_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['global-buttons-3'] ?></a>
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
    <script src="dataJs/countries.js"></script>
</body>

</html>