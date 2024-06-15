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
    <title> <?php echo $lang['left55'] ?> | <?php echo $core->site_name ?></title>
    <?php include 'views/inc/head_scripts.php'; ?>
    <link rel="stylesheet" href="assets/template/assets/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css">
    <style type="text/css">
        .custom-file-input.is-invalid {
            border-color: #dc3545; /* Color rojo para el borde */
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
        <?php $courierrow = $core->cdp_getCouriercom(); ?>



        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <div class="container-fluid" style="margin-bottom:100px ;">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="card">
                            <div class="">
                                <div class="row">
                                    <div class="col-lg-12 mx-auto text-center">
                                        <h2 class="h1 text-danger">
                                            <?php echo $lang['left56'] ?>
                                        </h2>
                                        <div class="u-h-4 u-w-50 bg-primary rounded mt-4 u-mb-40 mx-auto"></div>
                                        <p>
                                            <?php echo $lang['left57'] ?>
                                        </p>
                                    </div>
                                </div> <!-- END row-->
                                <div id="resultados_ajax"></div>
                                <div class="">
                                    <div class="col-lg-12 ml-auto mt-8 ">
                                        <form method="post" accept-charset="utf-8" name="form_prealert" id="form_prealert" enctype="multipart/form-data">
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ReceiptKind"><strong><?php echo $lang['add-title15'] ?></strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                                                <div class="input-group-text">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                            </div>
                                                            <input type='text' class="form-control" name="date_prealert" id="date_prealert" placeholder="--<?php echo $lang['left206'] ?>--" data-toggle="tooltip" data-placement="bottom" title="<?php echo $lang['add-title16'] ?>" readonly / value="<?php echo date('Y-m-d'); ?>" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ReceiptKind"><strong><?php echo $lang['add-title18'] ?></strong></label>

                                                        <select class="form-control custom-select" name="courier_prealert" id="courier_prealert">
                                                            <option value="">--<?php echo $lang['left204'] ?>--</option>

                                                            <?php foreach ($courierrow as $row) : ?>
                                                                <option value="<?php echo $row->id; ?>"><?php echo $row->name_com; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="sum2"><i class="fa fa-cube mr-1"></i><strong><?php echo $lang['left63'] ?></strong></label>

                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button class="btn btn-secondary" type="button"><i class="ti-package"></i></button>
                                                            </div>
                                                            <input type="text" class="form-control add-listing_form required" name="tracking_prealert" id="tracking_prealert" placeholder="<?php echo $lang['left63'] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="ReceiptKind"><strong><?php echo $lang['left64'] ?></strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <button class="btn btn-secondary" type="button"><i class="ti-shopping-cart"></i></button>
                                                            </div>
                                                            <input type="text" class="form-control add-listing_form required" name="provider_prealert" id="provider_prealert" placeholder="<?php echo $lang['left65'] ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sum2"><strong><?php echo $lang['left66'] ?> <?php echo $core->currency; ?></strong></label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">$</span>
                                                            </div>
                                                            <input type="text" onkeypress="return cdp_soloNumeros(event)" class="form-control add-listing_form required" name="price_prealert" id="price_prealert" placeholder="<?php echo $lang['left67'] ?>">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">.00</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 nondoc">
                                                    <div class="form-group">
                                                        <label for="sum2"><strong><?php echo $lang['left68'] ?></strong></label>
                                                        <textarea class="form-control" rows="2" name="description_prealert" id="description_prealert" placeholder="<?php echo $lang['left69'] ?>"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div>
                                                        <label class="control-label" id="selectItem"> <?php echo $lang['messagesform40'] ?></label>
                                                    </div>

                                                    <input class="custom-file-input" id="file_invoice" name="file_invoice" type="file" style="display: none;" onchange="cdp_validateZiseFiles();" accept="image/*,.pdf" />


                                                    <button type="button" id="openMultiFile" class="btn btn-default  pull-left "> <i class='fa fa-paperclip' id="openMultiFile" style="font-size:18px; cursor:pointer;"></i> <?php echo $lang['leftorder01215'] ?> </button>

                                                    <div id="clean_files" class="hide">
                                                        <button type="button" id="clean_file_button" class="btn btn-danger ml-3"> <i class='fa fa-trash' style="font-size:18px; cursor:pointer;"></i> <?php echo $lang['leftorder17'] ?> </button>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <div class="pull-right">

                                                        <a href="prealert_list.php" class="btn btn-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['global-buttons-3'] ?></a>
                                                        <button type="submit" name="create_prealert" id="create_prealert" class=" ml-2 btn  btn-success btn-confirmation pull-right"><i class="mdi mdi-bell mr-1"></i> <?php echo $lang['left70'] ?></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div> <!-- END     row-->

                                <hr class="u-my-60">
                            </div> <!-- END container-->
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

    <script src="assets/template/assets/libs/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <script src="dataJs/pre_alert_add.js" type="text/javascript"></script>


</body>

</html>