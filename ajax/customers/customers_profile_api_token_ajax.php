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



require_once("../../loader.php");
require_once("../../helpers/querys.php");


$user = new User;
$core = new Core;
$errors = array();

if (empty($_POST['user_id']))
    $errors['fname'] = "Something went wrong";
if (empty($_POST['api_token']))
    $errors['api_token'] = "Api token is required";




if (CDP_APP_MODE_DEMO === true) {
?>

    <div class="alert alert-warning" id="success-alert">
        <p><span class="icon-minus-sign"></span><i class="close icon-remove-circle"></i>
            <span>Error! </span> There was an error processing the request
        <ul class="error">

            <li>
                <i class="icon-double-angle-right"></i>
                This is a demo version, this action is not allowed, <a class="btn waves-effect waves-light btn-xs btn-success" href="https://codecanyon.net/item/courier-deprixa-pro-integrated-web-system-v32/15216982" target="_blank">Buy DEPRIXA PRO</a> the full version and enjoy all the functions...

            </li>


        </ul>
        </p>
    </div>
    <?php
} else {

    if (empty($errors)) {

        
        header('Content-type: application/json; charset=UTF-8');
    
        $response = array();
    
        $user_id = $_POST['user_id'];

        $api_token = $_POST['api_token'];
    
        $datos = array(
            'id' => $user_id,
            'api_token' => cdp_sanitize($_POST['api_token'])
        );

        $userDataEdit = cdp_getUserEditByToken($api_token);

        $update = cdp_updateCustomersApiToken($datos);
        
        if (!empty($userDataEdit['data']->id)) {
            $response['status'] = 'error';
            $response['message'] = 'Token Already Exists, Please generate a new one.';
        } else {
            $update = cdp_updateCustomersApiToken($datos);
        
            if ($update) {
                $response['status'] = 'success';
                $response['message'] = 'API token saved successfully';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Something went wrong';
            }
        }

        echo json_encode($response);
    }


    if (!empty($errors)) {
    ?>
        <div class="alert alert-danger" id="success-alert">
            <p><span class="icon-minus-sign"></span><i class="close icon-remove-circle"></i>
                <?php echo $lang['message_ajax_error2']; ?>
            <ul class="error">
                <?php
                foreach ($errors as $error) { ?>
                    <li>
                        <i class="icon-double-angle-right"></i>
                        <?php
                        echo $error;

                        ?>

                    </li>
                <?php

                }
                ?>


            </ul>
            </p>
        </div>



    <?php
    }

    if (isset($messages)) {

    ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <p><span class="icon-info-sign"></span>
                <?php
                foreach ($messages as $message) {
                    echo $message;
                }
                ?>
            </p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

<?php
    }
}

?>