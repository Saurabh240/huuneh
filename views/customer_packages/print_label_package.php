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

require_once('helpers/querys.php');

if (isset($_GET['id'])) {
	$data = cdp_getCustomerPackagePrint($_GET['id']);
}

if (!isset($_GET['id']) or $data['rowCount'] != 1) {
	cdp_redirect_to("customer_packages_list.php");
}



$row = $data['data'];


$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row->sender_id . "'");
$sender_data = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $row->order_courier . "'");
$courier_com = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_met_payment where id= '" . $row->order_pay_mode . "'");
$met_payment = $db->cdp_registro();

$db->cdp_query("SELECT * FROM cdb_shipping_mode where id= '" . $row->order_service_options . "'");
$order_service_options = $db->cdp_registro();


$db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row->order_prefix . $row->order_no . "'");
$address_order = $db->cdp_registro();

// total shipping cost
$total = $row->total_order;
$total = cdb_money_format($total);


$db->cdp_query("SELECT SUM(order_item_length) as total0 FROM cdb_customers_packages_detail where order_id='" . $row->order_id . "'");
$db->cdp_execute();
$row0 = $db->cdp_registro();

$rw_add0 = $row0->total0;


// volumetric query of the box width

$db->cdp_query("SELECT SUM(order_item_width) as total1 FROM cdb_customers_packages_detail where order_id='" . $row->order_id . "'");
$db->cdp_execute();
$row1 = $db->cdp_registro();

$rw_add1 = $row1->total1;

// volumetric query of the box width

$db->cdp_query("SELECT SUM(order_item_height) as total2 FROM cdb_customers_packages_detail where order_id='" . $row->order_id . "'");
$db->cdp_execute();
$row2 = $db->cdp_registro();

$rw_add2 = $row2->total2;



$length = $rw_add0; //Length
$width = $rw_add1; //Width
$height = $rw_add2; //Height


$total_metric = $length * $width * $height / $row->volumetric_percentage;


$db->cdp_query("SELECT * FROM cdb_customers_packages_detail WHERE order_id='" . $_GET['id'] . "'");
$order_items = $db->cdp_registros();



$sumador_libras = 0;
$sumador_volumetric = 0;
$count = 0;
foreach ($order_items as $row_item) {

	$weight_item = $row_item->order_item_weight;

	$total_metric = $row_item->order_item_length * $row_item->order_item_width * $row_item->order_item_height / $row->volumetric_percentage;

	// calculate weight x price
	if ($weight_item > $total_metric) {

		$calculate_weight = $weight_item;
		$sumador_libras += $weight_item; //Sumador

	} else {

		$calculate_weight = $total_metric;
		$sumador_volumetric += $total_metric; //Sumador
	}

	$count++;
}


$text_status = '';
$label_class = '';

if ($row->status_invoice == 1) {
    $text_status = 'Paid';
    $label_class = 'label label-custom label-custom-success'; // Clase para color verde
} else if ($row->status_invoice == 2) {
    $text_status = 'No payed';
    $label_class = 'label label-custom label-custom-warning'; // Clase para color naranja
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="<?php echo $direction_layout; ?>">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="assets/uploads/favicon.png">
	<title><?php echo $lang['status-ship2'] ?> - <?php echo $row->order_prefix . $row->order_no; ?></title>
	<link type='text/css' href='assets/css/label_custom.css' rel='stylesheet' />
	<link type='text/css' href="assets/custom_dependencies/print_pacakges_label.css" rel='stylesheet' />

</head>

<body>
	<div id="page-wrap">

		<div class="container_etiqueta" style="min-height: 540px; max-height: 540px; min-width: 420px; max-width: 420px;">
			<div class="print_ticket_zebra" style="max-height: 110px; min-height: 110px;">
				<div style="width: 40%;line-height:110px;margin:0px auto;text-align:center;">
					<?php echo ($core->logo) ? '<img class="logo" style="vertical-align:middle" src="assets/' . $core->logo . '" alt="' . $core->site_name . '" width="145" height="35"/>' : $core->site_name; ?>
				</div>
				<div style="width: 60%;">
					<strong><?php echo $core->site_name; ?><br></strong>

					<?php echo $core->c_country; ?>, <?php echo $core->c_city; ?>, <?php echo $core->c_postal; ?>.<br>
					<?php echo $lang['print-text8'] ?>: <?php echo $core->c_phone; ?>
				</div>
			</div>
			<div class="app_print_ticket_zebra_barcode">
			    <img class="barcode-image" src='https://barcode.tec-it.com/barcode.ashx?data=<?php echo $row->order_prefix . $row->order_no; ?>&code=EANUCC128&multiplebarcodes=false&translate-esc=true&unit=Fit&dpi=96&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0' alt='' />
			</div>
			<div class="app_easypack_ticket_zebra" style="padding-top:50px; max-width: 420px; text-align: center">
				<div class="track_courier"><strong> <?php echo $row->order_prefix . $row->order_no; ?></strong></div>
			</div>
			<div class="app_easypack_print" style="padding-top: 25px;font-weight: bold">
				<div><?php echo $lang['print-text7'] ?>: &nbsp;</div>
				<div>&nbsp;</div>
			</div>
			<div class="datas_easypack_print" style="padding-top:20px; max-width: 420px; text-align: center">
				<div><?php echo $lang['print-text6'] ?>: &nbsp;<?php echo $row->order_date; ?> &nbsp;|&nbsp;<?php echo $lang['print-text4'] ?>: &nbsp;<?php echo $count; ?>&nbsp;|&nbsp;<?php echo $lang['print-text0'] ?>:
					<?php if ($sumador_libras > $sumador_volumetric) {
						echo  cdp_round_out($sumador_libras);
					} else {
						echo cdp_round_out($sumador_volumetric);
					} ?> <?php echo $core->weight_p; ?>&nbsp;|&nbsp;<?php echo $lang['print-text9'] ?>: &nbsp;<?php echo $row->total_order; ?> </div>
			</div>
			<div class="app_easypack_print" style="padding-top:20px; max-width: 420px; text-align: center">
				<div><?php echo $lang['print-text3'] ?>: <?php echo $length; ?> &nbsp;</div>
				<div><?php echo $lang['print-text2'] ?>: <?php echo $width; ?> <?php echo $lang['print-text1'] ?>: <?php echo $height; ?></div>
			</div>


			<div class="app_easypack_print" style="padding-top:20px; max-width: 420px; text-align: center">
				<div><strong><?php echo $lang['print-text5'] ?>: </strong>&nbsp;</div>
				<div><?php if ($order_service_options != null) {
							echo $order_service_options->ship_mode;
						} ?> &nbsp;|&nbsp; <?php if ($courier_com != null) {
												echo $courier_com->name_com;
											} ?></div>
			</div>

			<div><br><br></div>
			<div class="app_easypack_ticket_zebra" style="padding-top: 10px; padding-bottom: 3px; font-size: 22px; max-height: 18px; min-height: 25px; display: flex; flex-direction: column; align-items: center; justify-content: center;">
			    <span style="margin-bottom: 5px;">Payment status</span>
			    <strong>
			        <span class="label label-large <?php echo $label_class; ?>"><?php echo $text_status; ?></span>
			    </strong>
			</div>

			<div><br><br></div>
			<div class="app_easypack_ticket_zebra" style="font-size: 37px; margin-top: 1px; margin-left: 0px; ">
				<strong><?php echo $address_order->sender_country . " - " . $address_order->sender_city; ?></strong>
			</div>

			<div class="app_easypack_qr_code" style="display: flex; align-items: center; max-height: 110px; min-height: 110px;">
			    <div style="width: 40%; padding: 10px;">
			        <img src='https://barcode.tec-it.com/barcode.ashx?data=Tracking:+<?php echo $row->order_prefix . $row->order_no; ?>&code=QRCode&multiplebarcodes=false&translate-esc=false&unit=Fit&dpi=96&imagetype=Gif&rotation=0&color=%23000000&bgcolor=%23ffffff&qunit=Mm&quiet=0&eclevel=L' alt='QR Code' style="max-width: 100%; height: auto;">
			    </div>
			    <div style="width: 60%; padding-left: 10px;">
			    	<span style="margin-bottom: 5px;">Sender</span>
			        <div style="font-size: 20px; font-weight: bold;"><?php echo $sender_data->fname . ' ' . $sender_data->lname; ?></div>
			        <div style="font-size: 16px;"><?php echo $address_order->sender_address; ?></div>
			        <div style="font-size: 16px;"><?php echo $sender_data->phone; ?></div>
			    </div>
			</div>

			<div class="app-print-data-list-unico">
				<div><strong><?php echo $lang['left-menu-sidebar-00'] ?> : <?php echo $sender_data->locker; ?></strong></div>
			</div>
		</div>
	</div>

	<br><br><br><br><br><br><br><br><br><br><br>
	<button class='button -dark center no-print' onClick="window.print();" style="font-size:16px"><?php echo $lang['inv-label9'] ?> &nbsp;&nbsp; </button>
	</div>
</body>

</html>