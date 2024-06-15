<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $lang['langs_010112'] ?> | <?php echo $core->site_name; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Courier DEPRIXA-Integral Web System">
    <meta name="author" content="Jaomweb">
    <meta name="description" content="">
    <!-- favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/<?php echo $core->favicon ?>">
    <!-- Bootstrap -->
    <link href="assets/css_main_deprixa/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons -->
    <link href="assets/css_main_deprixa/css/materialdesignicons.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
    <!-- Main Css -->
    <link href="assets/css_main_deprixa/css/style.css" rel="stylesheet" type="text/css" id="theme-opt" />
    <link href="assets/css_main_deprixa/css/colors/default.css" rel="stylesheet" id="color-opt">

    <link rel="stylesheet" href="assets/template/assets/libs/intlTelInput/intlTelInput.css">
    <link rel="stylesheet" href="assets/template/assets/libs/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">

    <script type="text/javascript" src="assets/js/jquery.js"></script>
    <script type="text/javascript" src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/jquery.ui.touch-punch.js"></script>
    <script src="assets/js/jquery.wysiwyg.js"></script>
    <script src="assets/js/global.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/checkbox.js"></script>

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
        .cover-container{
            max-width: 35em;
        }
    </style>

</head>

<body>
    <div id="preloader">
        <div id="status">
            <div class="spinner">
                <div class="double-bounce1"></div>
                <div class="double-bounce2"></div>
            </div>
        </div>
    </div>
    <!-- Loader -->
    <div class="back-to-home">
        <a href="" class="back-button btn btn-icon btn-primary"><i data-feather="arrow-left" class="icons"></i></a>
    </div>

    

    <!-- Hero Start -->
    <div class="cover-container d-flex h-100 p-3 mx-auto flex-column"> 
        <main role="main" class="container">
        
  
                                <div class="card border-0" style="z-index: 1">
                                <div class="card-title text-center">
                                            <a class="logo" href="index.php">
                                                <?php echo ($core->logo_web) ? '<img src="assets/' . $core->logo_web . '" alt="' . $core->site_name . '" width="' . $core->thumb_web . '" height="' . $core->thumb_hweb . '"/>' : $core->site_name; ?>


                                            </a>
                                        </div>
                                        <div><br></div>
                                    <div class="card-body p-0">
                                        <div class="text-center">
                                            <h4 class="card-title text-center"><?php echo $lang['left136'] ?></h4>
                                            <p><?php echo $lang['left137'] ?></p>
                                        </div>

                                        <?php if (!$core->reg_allowed) : ?>

                                            <div class="alert alert-warning" id="success-alert">
                                                <p><span class="icon-exclamation-sign"></span><i class="close icon-remove-circle"></i>
                                                    <?php echo $lang['langs_010133']; ?>
                                                </p>
                                            </div>

                                        <?php else : ?>

                                            <form class="login-form mt-4" id="new_register" name="new_register" method="post">
                                                <div class="row">

                                                <!-- Type of Account Radio Buttons -->
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Type of Account <span class="text-danger">*</span></label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="account_type" id="personal_account" value="personal" required>
                                                        <label class="form-check-label" for="personal_account">Personal Account</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="account_type" id="business_account" value="business" required>
                                                        <label class="form-check-label" for="business_account">Business Account</label>
                                                    </div>
                                                </div>
                                            </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['left138'] ?> <span class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="user" class="fea icon-sm icons"></i>
                                                                <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['left139'] ?>" name="fname" id="fname">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--end col-->

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['left140'] ?> <span class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="user" class="fea icon-sm icons"></i>
                                                                <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['left141'] ?>" name="lname" id="lname">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                    <!--end col-->
                                                 <div class="row">
                                                  <!-- <div class="col-md-6">
                                                        <div class="mb-3">
                                                           <label class="form-label"><?php echo $lang['leftorder164'] ?></label>
                                                           <div class="form-icon position-relative">
                                                            <i data-feather="list" class="fea icon-sm icons"></i>
                                                                <select class="custom-select form-control ps-5" id="document_type" name="document_type">
                                                                    <option value="DNI"><?php //echo $lang['leftorder165'] ?></option>
                                                                    <option value="RIC"><?php //echo $lang['leftorder166'] ?></option>
                                                                    <option value="CI"><?php //echo $lang['leftorder167'] ?></option>
                                                                    <option value="CIE"><?php //echo $lang['leftorder168'] ?></option>
                                                                    <option value="CIN"><?php //echo $lang['leftorder169'] ?></option>
                                                                    <option value="CIE"><?php //echo $lang['leftorder170'] ?></option>
                                                                    <option value="CC"><?php //echo $lang['leftorder171'] ?></option>
                                                                    <option value="TI"><?php //echo $lang['leftorder172'] ?></option>
                                                                    <option value="CE"><?php //echo $lang['leftorder173'] ?></option>
                                                                    <option value="PSP"><?php //echo $lang['leftorder174'] ?></option>
                                                                    <option value="NIT"><?php //echo $lang['leftorder1745'] ?></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div> -->
        
        
                                                   <!--  <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['leftorder175'] ?></label>
                                                            <div class="form-icon position-relative">
                                                            <i data-feather="more-horizontal" class="fea icon-sm icons"></i>
                                                                <input type="text" class="form-control ps-5" id="document_number" name="document_number" placeholder="<?php echo $lang['leftorder175'] ?>">
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['left142'] ?> <span class="text-danger">*</span></label>
                                                            <div class="form-icon position-relative">
                                                                <i data-feather="mail" class="fea icon-sm icons"></i>
                                                                <input type="email" class="form-control ps-5" placeholder="<?php echo $lang['left143'] ?>" name="email" id="email">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label for="phone_custom" class="form-label"><?php echo $lang['user_manage9'] ?> <span class="text-danger">*</span></label>
                                                            <input type="tel" class="form-control iti__tel-input ps-5" name="phone_custom" id="phone_custom" autocomplete="off" data-intl-tel-input-id="0" placeholder="<?php echo $lang['user_manage9'] ?>">
                                                        </div>
                                                        <span id="valid-msg" class="hide"></span>
                                                        <div id="error-msg" class="hide text-danger"></div>
                                                    </div>
                                                    <input type="hidden" name="phone" id="phone" />
                                                </div>

                                                

                                                <div class="row">

                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['translate_search_address_country'] ?> <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <select style="height: 45px !important;" class="select2 form-control ps-5" name="country" id="country">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['translate_search_address_state'] ?> <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <select style="width: 100% !important;" disabled class="select2 form-control ps-5" id="state" name="state">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="mb-3">
                                                            <label class="form-label"><?php echo $lang['translate_search_address_city'] ?> <span class="text-danger">*</span></label>
                                                            <div class="form-group">
                                                                <select style="width: 100% !important;" disabled class="select2 form-control ps-5" id="city" name="city">
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><?php echo $lang['user_manage14'] ?> <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="flag" class="fea icon-sm icons"></i>
                                                            <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['user_manage14'] ?>" name="postal" id="postal">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><?php echo $lang['user_manage10'] ?> <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="map-pin" class="fea icon-sm icons"></i>
                                                            <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['user_manage10'] ?>" name="address" id="address">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><?php echo $lang['user_manage58'] ?> <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="map-pin" class="fea icon-sm icons"></i>
                                                            <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['user_manage59'] ?>" name="address2" id="address2">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->
                                            </div>


                                            <!-- Type of Business Dropdown Menu -->
                                            
                                            <!-- Type of Business Dropdown Menu -->
                                            <div class="col-md-12" id="business_type_section" style="display: none;">
                                                <div class="mb-3">
                                                    <label class="form-label">Type of Business <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                        <select class="custom-select form-control ps-5" id="business_type" name="business_type">
                                                            <option value="law_office">Law Office</option>
                                                            <option value="pharmacy">Pharmacy</option>
                                                            <option value="flower_shop">Flower Shop</option>
                                                            <option value="bakery">Bakery/Custom Cakes</option>
                                                            <option value="warehouses">Warehouses</option>
                                                            <option value="grocery_stores">Grocery Stores</option>
                                                            <option value="other">Other (Include typing option)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mt-2" id="other_business_type" style="display: none;">
                                                        <input type="text" class="form-control ps-5" placeholder="Please specify" name="other_business" id="other_business">
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Business Name <span class="text-danger">*</span></label>
                                                    <div class="form-icon position-relative">
                                                            
                                                            <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['user_manage57'] ?>" name="business_name" id="business_name">
                                                        </div>
                                                   
                                                </div>
                                                <!-- Billing Choice Dropdown Menu -->
                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Billing Choice <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <select class="custom-select form-control ps-5" id="billing_choice" name="billing_choice">
                                                                <option value="weekly">Weekly Billing</option>
                                                                <option value="monthly">Monthly Billing</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            



                                                <div class="col-md-12">
                                                    <div class="mb-3">
                                                        <label class="form-label"><?php echo $lang['left144'] ?> <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="users" class="fea icon-sm icons"></i>
                                                            <input type="text" class="form-control ps-5" placeholder="<?php echo $lang['left145'] ?>" name="username" id="username">
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end col-->


                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><?php echo $lang['left146'] ?> <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                                            <input type="password" class="form-control ps-5" placeholder="<?php echo $lang['left147'] ?>" name="pass" id="pass">
                                                        </div>
                                                        <div id="password-strength-meter"></div> <!-- Aquí se mostrará la fortaleza de la contraseña -->
                                                    </div>
                                                </div>
                                                <!--end col-->

                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label"><?php echo $lang['left148'] ?> <span class="text-danger">*</span></label>
                                                        <div class="form-icon position-relative">
                                                            <i data-feather="key" class="fea icon-sm icons"></i>
                                                            <input type="password" class="form-control ps-5" name="pass2" id="pass2" placeholder="<?php echo $lang['left149'] ?>">
                                                        </div>
                                                        <div id="passwordMatch" style="color: red;"></div> <!-- Aquí se mostrará la validación de coincidencia de contraseñas -->
                                                    </div>
                                                </div>
                                                <!--end col-->
                                            </div>


                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="terms" name="terms" value="yes">
                                                        <label class="form-check-label" for="flexCheckDefault">
                                                            <?php echo $lang['left164'] ?>
                                                            <a href="views/terms_and_condition.php" target="_blank" class="text-primary">
                                                                <?php echo $lang['left165'] ?>
                                                            </a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>



                                            <!--end col-->

                                            <div class="col-md-12">
                                                <div class="d-grid">
                                                    <button class="btn btn-grad-register" name="dosubmit"><?php echo $lang['left166'] ?></button>

                                                    <?php
                                                    if ($core->code_number_locker == 1) {
                                                    ?>
                                                        <div class="form-group col-md-6" style="display:none;">
                                                            <label for="inputcom" class="control-label col-form-label"><?php echo $lang['add-title24'] ?></label>
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" name="locker" id="locker" value="<?php echo $lockerauto; ?>" onchange="cdp_validateLockerNumber(this.value, '<?php echo $verifylocker; ?>');">
                                                                <input type="hidden" name="order_no_main" id="order_no_main" value="<?php echo $lockerauto; ?>">
                                                            </div>
                                                        </div>
                                                    <?php } elseif ($core->code_number_locker == 2) {

                                                    ?>
                                                        <div class="form-group col-md-6" style="display:none;">
                                                            <label for="inputcom" class="control-label col-form-label"><?php echo $lang['leftorder14442'] ?></label>
                                                            <div class="input-group mb-3">
                                                                <input type="number" class="form-control" name="locker" id="locker" value="<?php print_r(cdp_generarCodigo('' . $core->digit_random_locker . '')); ?>" onchange="cdp_validateLockerNumber(this.value, '<?php echo $verifylocker; ?>');">
                                                                <input type="hidden" name="order_no_main" id="order_no_main" value="<?php echo $lockerauto; ?>">
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                   
                                                </div>


                                                <div class="mx-auto">
                                                    <p class="mb-0 mt-3"><small class="text-dark me-2"><?php echo $lang['left167'] ?></small> <a href="index.php" class="text-dark fw-bold"><?php echo $lang['left168'] ?></a></p>
                                                </div> 

                                                 
                                            </div>
                                            <!--end col-->
                                        </form>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            

               <!-- <div class="col-lg-7 offset-lg-5 padding-less img order-1" style="background-image:url('assets/css_main_deprixa/images/user/login.png')" data-jarallax='{"speed": 0.5}'>


               
      
                </div> --><!-- end col -->
                </main>
            <!--end row-->
        </div> 
            
    <!--end section-->
    <!-- Hero End -->



    <?php include('helpers/languages/translate_to_js.php'); ?>

    <!-- javascript -->
    <script src="assets/css_main_deprixa/main_deprixa/js/jquery.min.js"></script>
    <script src="assets/css_main_deprixa/js/bootstrap.bundle.min.js"></script>
    <!-- Icons -->
    <script src="assets/css_main_deprixa/js/feather.min.js"></script>
    <!-- Main Js -->
    <script src="assets/css_main_deprixa/js/plugins.init.js"></script>
    <!--Note: All init js like tiny slider, counter, countdown, maintenance, lightbox, gallery, swiper slider, aos animation etc.-->
    <script src="assets/css_main_deprixa/js/app.js"></script>
    <script src="assets/template/assets/libs/intlTelInput/intlTelInput.js"></script>

    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="assets/template/assets/libs/sweetalert2/sweetalert2.min.js"></script>    

    <script> 
        function cdp_validateLockerNumber(value, lockDigits) {
          cdp_convertStrPad(value, lockDigits);

          $.ajax({
            type: "POST",
            dataType: "json",
            url: "./ajax/validate_locker_virtual.php?track=" + value,
            success: function (data) {
              var main = $("#order_no_main").val();

              if (data) {
                alert(message_error_exist_locker);
                $("#digitslockers").val(main);
              }
            },
          });
        }

        function cdp_convertStrPad(value, dbDigits) {
          var pad = value.padStart(dbDigits, "0");

          $("#digitslockers").val(pad);
        }

        var input = document.getElementById("digitslockers");


    </script>
     <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCAP41rsfjKCKORsVRuSM_4ff6f7YGV7kQ&callback=initAutocomplete&libraries=places&v=weekly"
      defer
    ></script>
    <script src="dataJs/sign-up.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const businessAccountRadio = document.getElementById('business_account');
        const personalAccountRadio = document.getElementById('personal_account');
        const businessTypeSection = document.getElementById('business_type_section');
        const businessTypeSelect = document.getElementById('business_type');
        const otherBusinessType = document.getElementById('other_business_type');
        const otherBusinessInput = document.getElementById('other_business');

        // Show/hide business type section based on account type
        businessAccountRadio.addEventListener('change', function () {
            if (this.checked) {
                businessTypeSection.style.display = 'block';
            }
        });

        personalAccountRadio.addEventListener('change', function () {
            if (this.checked) {
                businessTypeSection.style.display = 'none';
                otherBusinessType.style.display = 'none';
                otherBusinessInput.value = '';
            }
        });

        // Show/hide other business input based on selected business type
        businessTypeSelect.addEventListener('change', function () {
            if (this.value === 'other') {
                otherBusinessType.style.display = 'block';
            } else {
                otherBusinessType.style.display = 'none';
                otherBusinessInput.value = '';
            }
        });
    });
</script>


</body>

</html>