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


$search = cdp_sanitize($_REQUEST['search']);

$tables = "cdb_countries";
$fields = "*";

$sWhere = "";


$sWhere .= " name LIKE '%" . $search . "%'";


$sWhere .= " order by name";

// // pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = 10; //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;

$sql = "SELECT $fields FROM  $tables where $sWhere";
$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();


$db->cdp_query($sql . " limit $offset, $per_page");
$data = $db->cdp_registros();

$total_pages = ceil($numrows / $per_page);


if ($numrows > 0) { ?>


	<table id="zero_config" class="table table-condensed table-hover table-striped" data-pagination="true" data-page-size="5">
		<thead>
			<tr>
				<th><b><?php echo $lang['leftorder303'] ?></b></th>
				<th><b><?php echo $lang['leftorder304'] ?></b></th>
				<th><b><?php echo $lang['leftorder305'] ?></b></th>
				<th><b><?php echo $lang['leftorder306'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['leftorder307'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['leftorder308'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['left367'] ?></b></th>

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
			<?php foreach ($data as $row) { ?>
				<tr>
					<td><?php echo $row->name; ?></td>
					<td><?php echo $row->iso3; ?></td>
					<td><?php echo $row->region; ?></td>
					<td><?php echo $row->currency_name; ?></td>
					<td class="text-center"><?php echo $row->currency_symbol; ?></td>
					<td class="text-center"><?php echo cdp_locationStatus($row->is_active, $row->id, $lang); ?></td>
					<td class="text-center">
						<a href="countries_edit.php?id=<?php echo $row->id; ?>">
							<i class="ti-pencil" aria-hidden="true"></i>
						</a>
						<a id="item_<?php echo $row->id; ?>" onclick="cdp_eliminar('<?php echo $row->id; ?>');" class="delete">
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