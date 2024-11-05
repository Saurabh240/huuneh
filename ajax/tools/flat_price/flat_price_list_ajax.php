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

$db = new Conexion;



$sWhere = "";

if (isset($_REQUEST['business_type']) && $_REQUEST['business_type']!='') {
	$sWhere .= "where business_type = '" . $_REQUEST['business_type'] . "'";
}else{
	$sWhere = "where business_type IN ('flat_1','flat_2')";
}
if (isset($_REQUEST['origin']) && $_REQUEST['origin']!='') {
	$sWhere .= " and  sender_city = '" . $_REQUEST['origin'] . "'";
}
if (isset($_REQUEST['destiny']) && $_REQUEST['destiny']!='') {
	$sWhere .= " and  recipient_city = '" . $_REQUEST['destiny'] . "'";
}

// $sWhere .= " order_service_options LIKE '%" . $search . "%'";

// // pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = 10; //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;

 $sql = "SELECT * FROM `cdb_flat_price_lists` 
		$sWhere
		order by id desc 
";


$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();


$db->cdp_query($sql . " limit $offset, $per_page");
$data = $db->cdp_registros();

$total_pages = ceil($numrows / $per_page);

if ($numrows > 0) { ?>
	<table id="file_export" class="table border table-striped table-bordered display dataTable" aria-describedby="file_export_info">
		<thead>
			<tr>
				<th><b><?php echo $lang['flat-price-4'] ?></b></th>
				<th><b><?php echo $lang['lorigin'] ?></b></th>
				<th><b><?php echo $lang['ldestination'] ?></b></th>
				<th><b><?php echo $lang['leftorder292'] ?></b></th>
				
				<th><b><?php echo $lang['flat-price-12'] ?></b></th>
				<th><b><?php echo $lang['left367'] ?></b></th>
			</tr>
		</thead>


		<?php if (!$data) { ?>
			<tr>
				<td colspan="6">
					<?php echo "
				<i align='center' class='display-3 text-warning d-block'><img src='assets/images/alert/ohh_shipment.png' width='150' /></i>								
				", false; ?>
				</td>
			</tr>
		<?php } else { ?>
			<?php foreach ($data as $row) {

			?>
				<tr>
				
					<td><?php echo $row->business_type; ?></td>
					<td><?php echo $row->sender_city; ?></td>
					<td><?php echo $row->recipient_city; ?></td>
					<td><?php echo  number_format($row->price, 2, '.', '.'); ?></td>
					<td><?php echo  number_format($row->price_with_tax, 2, '.', '.'); ?></td>
					
					<td class="text-center">
						<a id="item_<?php echo $row->id; ?>" onclick="cdp_eliminar('<?php echo $row->id; ?>');" class="delete" data-rel="<?php echo $row->id; ?>">
							<div class="icon-holder"><i class="fi fi-rr-trash"></i></div>
						</a>

					</td>
				</tr>
			<?php } ?>

		<?php } ?>

	</table>


	<div class="pull-right">
		<?php echo cdp_paginate($page, $total_pages, $adjacents, $lang);	?>
	</div>
	</div>
<?php } ?>