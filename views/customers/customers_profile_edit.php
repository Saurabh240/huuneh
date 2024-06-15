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

if (intval($userData->id) !== intval($_GET['user']) && !$user->cdp_is_Admin()) {
    cdp_redirect_to("login.php");
}

require_once('helpers/querys.php');

if (isset($_GET['user'])) {
    $data = cdp_getUserEdit4bozo($_GET['user']);
}

if (!isset($_GET['user']) or $data['rowCount'] != 1) {
    cdp_redirect_to("users_list.php");
}

$row = $data['data'];

$db->cdp_query("SELECT * FROM cdb_senders_addresses WHERE user_id='" . $_GET['user'] . "'");
$user_addreses = $db->cdp_registros();

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
    <title><?php echo $lang['left-menu-sidebar-63'] ?> | <?php echo $core->site_name ?></title>

    <link rel="stylesheet" href="assets/template/assets/libs/intlTelInput/intlTelInput.css">
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">

    <?php include 'views/inc/head_scripts.php'; ?>
    <link href="assets/template/dist/css/custom_swicth.css" rel="stylesheet">

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


        <!-- End Left Sidebar - style you can find in sidebar.scss  -->

        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            
            <div class="container-fluid">
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['left-menu-sidebar-63']; ?></span></h3>
                                    </div>
                                </div>
                                <div><hr><br></div>

                                <center class="m-t-30">
                                    <label for="avatarInput">
                                        <img src="assets/<?php echo ($row->avatar) ? $row->avatar : "/uploads/blank.png"; ?>" class="rounded-circle" width="150" />
                                    </label>
                                    <div><br><br></div>

                                    <form class="form-horizontal form-material" id="edit_avatar_form" name="edit_avatar_form" method="post" enctype="multipart/form-data">
                                        <div class="col-md-6 mb-3">
                                            <div class="form-group" style="display: none;">
                                                <!-- Este input está oculto y se activa haciendo clic en la imagen -->
                                                <input class="form-control" id="avatarInput" name="avatar" type="file" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <button type="submit" class="btn btn-outline-warning btn-confirmation"><?php echo $lang['messageerrorform13'] ?></button>
                                        </div>
                                        <input name="id" id="id" type="hidden" value="<?php echo $row->id; ?>" />
                                    </form>

                                    <h4 class="card-title m-t-10"><?php echo $row->fname; ?> <?php echo $row->lname; ?></h4>
                                    <h6 class="card-subtitle"><span><?php echo $lang['user_manage2'] ?> <i class="icon-double-angle-right"></i></span>
                                        <div class="badge badge-pill badge-light font-16"><span class="ti-user text-warning"></span> <?php echo $row->username; ?></div>
                                    </h6>
                                    <!-- <h6 class="card-subtitle"><span><?php echo $lang['user-account21000'] ?> <i class="icon-double-angle-right"></i></span>
                                        <div class="badge badge-pill badge-light font-16"> <?php echo $row->locker; ?></div>
                                    </h6> -->
                                </center>
                            </div>
                            <div>
                                <hr>
                            </div>
                            <div class="card-body"> <small class="text-muted"><?php echo $lang['user-account4'] ?> </small>
                                <h6><?php echo $row->email; ?></h6> <small class="text-muted p-t-30 db"><?php echo $lang['user-account8'] ?></small>
                                <h6> <?php echo $row->phone; ?></h6>
                            </div>
                            <div class="card-body row text-center">
                                <div class="col-6 border-right">
                                    <h6><?php echo $row->created; ?></h6>
                                    <span><?php echo $lang['user-account18'] ?></span>
                                </div>
                                <div class="col-6">
                                    <h6><?php echo $row->lastlogin; ?></h6>
                                    <span><?php echo $lang['user-account19'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <!-- Tabs -->
                            <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-setting-tab" data-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="false"><span><?php echo $lang['edit-clien2'] ?> <i class="icon-double-angle-right"></i> <?php echo $row->username; ?></span></a>
                                </li>
                            </ul>
                            <!-- Tabs -->
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                                    <div class="card-body">
                                        <!-- <div id="loader" style="display:none"></div> -->
                                        
                                        <form class="form-horizontal form-material" id="edit_user" name="edit_user" method="post" enctype="multipart/form-data">
                                            <section>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1"><?php echo $lang['user_manage3'] ?></label>
                                                            <input type="text" class="form-control" disabled="disabled" name="username" readonly="readonly" value="<?php echo $row->username; ?>" placeholder="<?php echo $lang['user_manage3'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastName1"><?php echo $lang['user_manage4'] ?></label>
                                                            <input type="text" class="form-control" id="password" name="password" placeholder="<?php echo $lang['user_manage32'] ?>">
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phoneNumber1"><?php echo $lang['leftorder164'] ?></label>
                                                            <input type="text" class="form-control" id="document_type" name="document_type" value="<?php echo $row->document_type; ?>" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phoneNumber1"><?php echo $lang['leftorder175'] ?></label>
                                                            <input type="text" class="form-control" id="document_number" name="document_number" value="<?php echo $row->document_number; ?>" placeholder="<?php echo $lang['leftorder175'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div> -->


                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['user_manage6'] ?></label>
                                                            <input type="text" class="form-control" name="fname" id="fname" value="<?php echo $row->fname; ?>" placeholder="<?php echo $lang['user_manage6'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phoneNumber1"><?php echo $lang['user_manage7'] ?></label>
                                                            <input type="text" class="form-control" name="lname" id="lname" value="<?php echo $row->lname; ?>" placeholder="<?php echo $lang['user_manage7'] ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['user_manage5'] ?></label>
                                                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $row->email; ?>" placeholder="<?php echo $lang['user_manage5'] ?>" readonly>
                                                        </div>

                                                    </div>
                                                   

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phoneNumber1"><?php echo $lang['user_manage9'] ?></label>
                                                            <input type="text" class="form-control" id="phone_custom" name="phone_custom" value="<?php echo $row->phone; ?>" placeholder="<?php echo $lang['user_manage9'] ?>" readonly>
                                                            <span id="valid-msg" class="hide"></span>
                                                            <div id="error-msg" class="hide text-danger"></div>
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="phoneNumber1"><?php echo $lang['user_manage11'] ?></label>
                                                            <select class="custom-select form-control" id="gender" name="gender" value="<?php echo $row->gender; ?>" placeholder="<?php echo $lang['user_manage11'] ?>">
                                                                <option value="Male" <?php if ($row->gender == 'Male') {
                                                                                            echo 'selected';
                                                                                        } ?>><?php echo $lang['leftorder179'] ?></option>
                                                                <option value="Female" <?php if ($row->gender == 'Female') {
                                                                                            echo 'selected';
                                                                                        } ?>><?php echo $lang['leftorder178'] ?></option>
                                                                <option value="Other" <?php if ($row->gender == 'Other') {
                                                                                            echo 'selected';
                                                                                        } ?>><?php echo $lang['leftorder180'] ?></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="account_type"><?php echo "Account Type" ?></label>
                                                            <input type="text" class="form-control" id="account_type" name="account_type" value="<?php echo $row->account_type; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if(  $row->account_type !== 'personal') { ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="business_type"><?php echo "Business Type" ?></label>
                                                                <input type="text" class="form-control" id="business_type" name="business_type" value="<?php echo $row->business_type; ?>" readonly>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="business_name"><?php echo "Business Name" ?></label>
                                                                <input type="text" class="form-control" id="business_name" name="business_name" value="<?php echo $row->business_name; ?>" readonly>
                                                            </div>
                                                        </div>
													</div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="billing_choice"><?php echo "Billing Choice" ?></label>
                                                                <input type="text" class="form-control" id="billing_choice" name="billing_choice" value="<?php echo $row->billing_choice; ?>" readonly>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="address_line_2 "><?php echo "Address Line 2" ?></label>
                                                                <input type="text" class="form-control" id="address_line_2 " name="address_line_2 " value="<?php echo $row->address_line_2 ; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="address_line_2 "><?php echo "Address Line 2" ?></label>
                                                                <input type="text" class="form-control" id="address_line_2 " name="address_line_2 " value="<?php echo $row->address_line_2 ; ?>" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                                <hr>
                                                <!-- <h4><?php echo $lang['leftorder176'] ?></h4> -->
                                                <br>

                                                <div id="resultados_ajax"></div>

                                                <?php

                                                $count = 0;

                                                foreach ($user_addreses as $rowAddress) {
                                                    $count++;

                                                    $db->cdp_query("SELECT * FROM cdb_countries where id= '" . $rowAddress->country . "'");
                                                    $country = $db->cdp_registro();

                                                    $db->cdp_query("SELECT * FROM cdb_states where id= '" . $rowAddress->state . "'");
                                                    $state = $db->cdp_registro();

                                                    $db->cdp_query("SELECT * FROM cdb_cities where id= '" . $rowAddress->city . "'");
                                                    $city = $db->cdp_registro();

                                                ?>
                                                    <div id="div_parent_<?php echo $count; ?>">

                                                        <?php if ($count > 1) {
                                                            echo '<hr>';
                                                        } ?>

                                                        <!-- <h4><?php echo $lang['laddress'];
                                                            echo ' ' . $count; ?> </h4> -->
                                                            <h4> My Address </h4>



                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-form-label"><?php echo $lang['leftorder318'] ?></label>
                                                                    <select class="select2 form-control custom-select" name="country[]" id="country<?php echo $count; ?>">
                                                                        <option value="<?php echo $country->id; ?>"><?php echo $country->name; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-form-label"><?php echo $lang['leftorder319'] ?></label>
                                                                    <select class="select2 form-control custom-select" id="state<?php echo $count; ?>" name="state[]">
                                                                        <option value="<?php echo $state->id; ?>"><?php echo $state->name; ?></option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 mb-3">
                                                                <div class="form-group">
                                                                    <label class="control-label col-form-label"><?php echo $lang['leftorder320'] ?></label>
                                                                    <select class="select2 form-control custom-select" id="city<?php echo $count; ?>" name="city[]">
                                                                        <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="phoneNumber1"><?php echo $lang['user_manage14'] ?></label>
                                                                    <input type="text" class="form-control form-control-sm" value="<?php echo $rowAddress->zip_code; ?>" name="postal[]" id="postal<?php echo $count; ?>" placeholder="<?php echo $lang['user_manage14'] ?>">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="phoneNumber1"><?php echo $lang['user_manage10'] ?></label>
                                                                    <input type="text" class="form-control form-control-sm" value="<?php echo $rowAddress->address; ?>" name="address[]" id="address<?php echo $count; ?>" placeholder="<?php echo $lang['user_manage10'] ?>">
                                                                </div>
                                                            </div>

                                                            <input type="hidden" name="address_id[]" id="address_id<?php echo $count; ?>" value="<?php echo $rowAddress->id_addresses; ?>" />

                                                            <?php

                                                            if ($count > 1) { ?>
                                                                <div align="center" class="col-md-4">
                                                                    <label> &nbsp;</label>
                                                                    <div class="form-group">
                                                                        <button type="button" name="remove_row" id="<?php echo $count; ?>" class="btn btn-danger remove_row">
                                                                            <span class="fa fa-trash"></span> <?php echo $lang['delete_address_recepient'] ?>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        </div>

                                                    </div>
                                                <?php
                                                }

                                                ?>

                                                <input type="hidden" name="total_address" id="total_address" value="<?php echo $count; ?>" />
                                                <input type="hidden" name="phone" id="phone" value="<?php echo $row->phone; ?>" />

                                                <div id="div_address_multiple"></div>


                                                <div align="left">
                                                    <button type="button" name="add_row" id="add_row" class="btn btn-success mb-2"><span class="fa fa-plus"></span> <?php echo $lang['add_address_recepient'] ?></button>
                                                </div>


                                                <hr />
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="emailAddress1"><?php echo $lang['user_manage28'] ?></label>
                                                            <textarea class="form-control" name="notes" id="notes" rows="6" name="notes" placeholder="<?php echo $lang['user_manage31'] ?>">
                                                            <?php echo $row->notes; ?>
                                                        </textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                            </section>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <button class="btn btn-outline-primary btn-confirmation" name="save_data" id="save_data" type="submit"><?php echo $lang['user-account20'] ?><span><i class="icon-ok"></i></span></button>
                                                    <a href="customers_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['user_manage30'] ?></a>
                                                </div>
                                                <input name="id" id="id" type="hidden" value="<?php echo $row->id; ?>" />
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>

                <input type="hidden" name="count_address" id="count_address" value="<?php echo $count; ?>" />

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
    <script src="assets/template/assets/libs/intlTelInput/intlTelInput.js"></script>

    <script src="dataJs/customers_profile_edit.js"></script>


</body>

</html>