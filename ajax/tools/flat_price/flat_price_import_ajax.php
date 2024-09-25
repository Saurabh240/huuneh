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
require_once("helpers/querys.php");
require_once("vendor/autoload.php");
use PhpOffice\PhpSpreadsheet\IOFactory;


$errors = array();
$response = array();

if (empty($_POST['business_type']))
    $errors['business_type'] =  $lang['flat-price-6'];

if (!isset($_FILES['excel_file'])) {
    $errors['excel_file'] = $lang['flat-price-7'];
}elseif (isset($_FILES['excel_file']))
$file = $_FILES['excel_file'];
    
    // Get file details
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];
    
    // Allowed file types
    $allowed = array('xls', 'xlsx');
    
    // Extract file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    // Check if file type is allowed
    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 5000000) { // Limit file size to 5MB
                //$fileDestination = 'uploads/' . $fileName;
               // move_uploaded_file($fileTmpName, $fileDestination);
               // $csvFile = "../../assets/city_Price_List.xlsx";
	
					$spreadsheet = IOFactory::load($file);

					$sheetNames = $spreadsheet->getSheetNames();
					$data=array();
					if(count($sheetNames)>0){
						foreach($sheetNames as $k=>$v){
							  $sender_city=str_replace(" UPDATE","",$v);
							  $spreadsheet->setActiveSheetIndexByName($k);
							  $sheet = $spreadsheet->getActiveSheet();
							  $highestColumn = $sheet->getHighestColumn();
						
						for ($row = 9; $row <= 34; $row++) {
							$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
									// Check if the cell contains the search text
									if (isset($rowData[0])){
										echo "<pre>";
										print_r($rowData);
										die;
											 /* $data = array(
													'user_id' => 1,
													'business_type' => cdp_sanitize($_POST['business_type']),
												   'sender_city' => cdp_sanitize($sender_city),
													'recipient_city' => $rowData[0]?cdp_sanitize($rowData[0]):'',
													'price' => $rowData[0]??'',
													'price_with_tax' => $rowData[0]??'',
													'active' => 1
												);
												$insert = cdp_insertFlatPrice($data);

												if ($insert) {
													$response['status'] = 'success';
													$response['message'] = $lang['message_ajax_success_add'];
												} else {
													$response['status'] = 'error';
													$response['message'] = $lang['message_ajax_error1'];
												}
												*/
																					
								}
						}
							
						}
					}
            } else {
                 $errors['excel_file']= $lang['flat-price-8'];
            }
        } else {
             $errors['excel_file']= $lang['flat-price-9'];
        }
    } else {
         $errors['excel_file']= $lang['flat-price-10'];
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
            <script>
                $("#save_data")[0].reset();
            </script>
        </p>
    </div>

<?php
}

?>
