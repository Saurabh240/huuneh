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

$db = new Conexion;


$response = [];

if (!empty($_POST['address_modal_recipient'])) {
    $recipeint_address = cdp_sanitize($_POST['address_modal_recipient']);

    if ($fullAddress = cdp_recipientAddressExists($recipeint_address)) {
        $response['status'] = true;
        $response['message'] = "Full Address found"; 
        $response['fullAddress'] = $fullAddress;
    } else {
        $response['status'] = false;
        $response['message'] = "Full Address not found"; 
    }
} else {
    $response['status'] = false;
    $response['message'] = "Address is required";
}

header('Content-Type: application/json');
echo json_encode($response);

