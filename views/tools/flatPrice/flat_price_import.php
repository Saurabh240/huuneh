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


require 'helpers/querys.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
$errors = array();
$response = array();


if(isset($_POST) && isset($_POST['dosubmit']) && isset($_FILES['excel_file'])){
	
if(empty($_POST['business_type']))
    $errors['business_type'] =  $lang['flat-price-6'];

if(!isset($_FILES['excel_file'])) {
$errors['excel_file'] = $lang['flat-price-7'];
}elseif(isset($_FILES['excel_file'])){

$file = $_FILES['excel_file'];
    
    
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];
   
    $allowed = array('xls', 'xlsx');
    
    // Extract file extension
    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));
    
    // Check if file type is allowed
    if (in_array($fileActualExt, $allowed)) {
		
        if ($fileError === 0) {
		
            if ($fileSize < 5000000) {

					$spreadsheet = IOFactory::load($fileTmpName);					
					$sheetCount = $spreadsheet->getSheetCount();
					
					$data=array();
					$update = cdp_inactiveFlatPrice(0,$_POST['business_type']);
							for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
								 $sheet = $spreadsheet->getSheet($sheetIndex);
								  $sheetTitle = $sheet->getTitle();
							
							  $sender_city=str_replace(" UPDATE","",$sheetTitle);
							  
							   $highestColumn = $sheet->getHighestColumn();
					
						for ($row = 9; $row <= 40; $row++) {
							$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
									if (isset($rowData[0])){
										for($l=0;$l<=count($rowData[0]);$l=$l+4){
										if(isset($rowData[0][$l]) && $rowData[0][$l]!=''){
											$data = array(
													'user_id' => 1,
													'business_type' => cdp_sanitize($_POST['business_type']),
												   'sender_city' => cdp_sanitize($sender_city),
													'recipient_city' => $rowData[0][$l]?cdp_sanitize($rowData[0][$l]):'',
													'price' => $rowData[0][$l+1]??'',
													'price_with_tax' => $rowData[0][$l+2]??'',
													'active' => 1
												);
												$insert = cdp_insertFlatPrice($data);
										}										
										}
								}
						}
						$response['status']= "success";
						$response['msg']= $lang['flat-price-11'];
					}
					$delete = cdp_deleteAllFlatPrice(0,$_POST['business_type']);
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
}

if (!$user->cdp_is_Admin())
    cdp_redirect_to("login.php");


$userData = $user->cdp_getUserData();

$db = new Conexion;

$db->cdp_query("SELECT * FROM cdb_info_ship_default where id= '1'");
$infoship = $db->cdp_registro();


$db->cdp_query("SELECT * FROM cdb_category where id= '" . $infoship->logistics_default1 . "'");
$s_logistics = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_packaging where id= '" . $infoship->packaging_default2 . "'");
$packaging_box = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $infoship->courier_default3 . "'");
$courier_comp = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_shipping_mode where id= '" . $infoship->service_default4 . "'");
$ship_modes = $db->cdp_registro();

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
    <title><?php echo $lang['leftorder300'] ?>| <?php echo $core->site_name ?></title>
    <?php include 'views/inc/head_scripts.php'; ?>
    <link rel="stylesheet" type="text/css" href="assets/template/assets/libs/select2/dist/css/select2.min.css">

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

        <?php $packrow = $core->cdp_getPack(); ?>
        <?php $moderow = $core->cdp_getShipmode(); ?>
        <?php $courierrow = $core->cdp_getCouriercom(); ?>
        <?php $categories = $core->cdp_getCategories(); ?>


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
                    <div class="col-lg-12 col-xl-12 col-md-12">
                        <div class="card">
                            <div class="card-body">

                                 <div class="d-md-flex align-items-center">
                                    <div>
                                        <h3 class="card-title"><span><?php echo $lang['flat-price-1'] ?></span></h3>
                                    </div>
                                </div>
                                <div><hr><br></div>

                                <form class="form-horizontal form-material" id="save_data" name="save_data" method="post" enctype="multipart/form-data">
                                    <section>
                                        <div class="row">
                                            

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> <?php echo $lang['flat-price-4'] ?></label>
                                                    <select style="width: 100% !important;" class="select2 form-control required" name="business_type" id="business_type">
															<option value="">Select Type</option>
															<option value="special">Special</option>
															<option value="flat_1">Flat 1</option>
                                                            <option value="flat_2">Flat 2</option>
                                                            <option value="karensflowershop_next_day">Karensflowershop Next Day</option>
                                                            <option value="karensflowershop_same_day">Karensflowershop Same Day</option>
                                                                                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label> <?php echo $lang['flat-price-5'] ?></label>
                                                   <input type="file" name="excel_file" id="excel_file"  accept=".xls, .xlsx">
                                                </div>
                                            </div>
                                           
                                        </div>

                                       
                                    </section>
                                    <br><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit">
                                                <?php echo $lang['flat-price-3'] ?>
                                                <span><i class="icon-ok"></i></span></button>
                                            <a href="flat_price_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['global-buttons-3'] ?></a>
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

    <script src="assets/template/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="assets/template/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="dataJs/flat_price_import.js"></script>
	<script>
	$(function () {

	<?php if(count($errors)>0){ ?>
			
			Swal.fire({
                   title: message_error_form6,
                    text: '<?php echo implode(', ',$errors); ?>',
                    type: 'error',
                    confirmButtonColor: '#336aea',
                    showConfirmButton: true,
                    allowOutsideClick: false,
                });
				
		<?php }elseif($response['status']== "success"){	?>
			
			 Swal.fire({
                        type: 'success',
                        title: '<?php echo $response['msg']; ?>',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    }).then(() => {
                        // Redirigir al listado de clientes
                        window.location.href = 'flat_price_list.php';
                    });
				
		<?php }	?>
		   
		   
		});
</script>
</body>

</html>