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
	$data = cdp_getCourierCompanyEdit($_GET['id']);
}

if (!isset($_GET['id']) or $data['rowCount'] != 1) {
	cdp_redirect_to("courier_company_list.php");
}

$row_courier = $data['data'];

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
                                        <h3 class="card-title"><span><?php echo $lang['tools-courier2'] ?> <i class="icon-double-angle-right"></i> <?php echo $row_courier->name_com; ?></span></h3>
                                    </div>
                                </div>
                                <div><hr><br></div>

								<form class="form-horizontal form-material" id="update_data" name="update_data" method="post">

									<section>
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="firstName1"><?php echo $lang['tools-courier3'] ?></label>
													<input type="text" class="form-control required" name="name_com" id="name_com" value="<?php echo $row_courier->name_com; ?>" placeholder="<?php echo $lang['tools-courier3'] ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="lastName1"><?php echo $lang['tools-courier4'] ?></label>
													<input type="text" class="form-control required" name="address_cou" id="address_cou" value="<?php echo $row_courier->address_cou; ?>" placeholder="<?php echo $lang['tools-courier4'] ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="lastName1"><?php echo $lang['tools-courier5'] ?></label>
													<input type="text" class="form-control required" name="phone_cou" id="phone_cou" value="<?php echo $row_courier->phone_cou; ?>" placeholder="<?php echo $lang['tools-courier5'] ?>">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="firstName1"><?php echo $lang['tools-courier6'] ?></label>
													<input type="text" class="form-control required" name="country_cou" id="country_cou" value="<?php echo $row_courier->country_cou; ?>" placeholder="<?php echo $lang['tools-courier6'] ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="lastName1"><?php echo $lang['tools-courier7'] ?></label>
													<input type="text" class="form-control required" name="city_cou" id="city_cou" value="<?php echo $row_courier->city_cou; ?>" placeholder="<?php echo $lang['tools-courier7'] ?>">
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="lastName1"><?php echo $lang['tools-courier8'] ?></label>
													<input type="text" class="form-control required" name="postal_cou" id="postal_cou" value="<?php echo $row_courier->postal_cou; ?>" placeholder="<?php echo $lang['tools-courier8'] ?>">
												</div>
											</div>
										</div>
									</section>
									<br><br>
									<div class="form-group">
										<div class="col-sm-12">
											<button class="btn btn-outline-primary btn-confirmation" name="dosubmit" type="submit"><?php echo $lang['tools-courier9'] ?> <span><i class="icon-ok"></i></span></button>
											<a href="courier_company_list.php" class="btn btn-outline-secondary btn-confirmation"><span><i class="ti-share-alt"></i></span> <?php echo $lang['tools-courier10'] ?></a>
										</div>
									</div>
									<input name="id" id="id" type="hidden" value="<?php echo $_GET['id']; ?>" />
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


	<script src="dataJs/courier_company.js"></script>
</body>

</html>