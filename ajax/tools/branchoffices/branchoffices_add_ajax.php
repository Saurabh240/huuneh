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



require_once("../../../loader.php");
require_once("../../../helpers/querys.php");

$errors = array();

if (empty($_POST['name_branch']))
  $errors['name_branch'] =  $lang['validate_field_ajax92'];


if (empty($_POST['branch_address']))
  $errors['branch_address'] =  $lang['validate_field_ajax94'];

if (empty($_POST['branch_city']))
  $errors['branch_city'] = $lang['validate_field_ajax95'];

if (empty($_POST['phone_branch']))
  $errors['phone_branch'] =  $lang['validate_field_ajax96'];



    $response = array();


    if (cdp_branchofficeExistsr9ufr($_POST['name_branch'])) {

        $response['status'] = 'error';
        $response['message'] = $lang['validate_field_ajax93'];
    }


    if (!isset($response['status'])) {
        $data = array(
          'name_branch' => cdp_sanitize($_POST['name_branch']),
          'branch_address' => cdp_sanitize($_POST['branch_address']),
          'branch_city' => cdp_sanitize($_POST['branch_city']),
          'phone_branch' => cdp_sanitize($_POST['phone_branch'])
        );

        $update = cdp_insertBranchOffices($data);

        if ($update) {
            $response['status'] = 'success';
            $response['message'] = $lang['message_ajax_success_add'];
        } else {
            $response['status'] = 'error';
            $response['message'] = $lang['message_ajax_error1'];
        }
    }

    header('Content-type: application/json; charset=UTF-8');
    echo json_encode($response);


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
  <div class="alert alert-info" id="success-alert">
    <p><span class="icon-info-sign"></span><i class="close icon-remove-circle"></i>
      <?php
      foreach ($messages as $message) {
        echo $message;
      }
      ?>
    </p>
  </div>

<?php
}
?>