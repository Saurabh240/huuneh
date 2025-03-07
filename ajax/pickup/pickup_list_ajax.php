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

$db = new Conexion;
$user = new User;
$core = new Core;
$userData = $user->cdp_getUserData();

$search = cdp_sanitize($_REQUEST['search']);
$status_courier = intval($_REQUEST['status_courier']);
$range = cdp_sanitize($_REQUEST['daterange']);

$sWhere = "";

if ($status_courier > 0) {

	$sWhere .= " and  a.status_courier = '" . $status_courier . "'";
}

if (!empty($range)) {

	$fecha =  explode(" - ", $range);
	$fecha = str_replace('/', '-', $fecha);

	$fecha_inicio = date('Y-m-d', strtotime($fecha[0]));
	$fecha_fin = date('Y-m-d', strtotime($fecha[1]));


	$sWhere .= " and  a.order_date between '" . $fecha_inicio . "'  and '" . $fecha_fin . "'";
}


if ($userData->userlevel == 3) {

	$sWhere .= " and  a.driver_id = '" . $_SESSION['userid'] . "'";
} else if ($userData->userlevel == 1) {

	$sWhere .= " and  a.sender_id = '" . $_SESSION['userid'] . "'";
} else {
	$sWhere .= "";
}
if ($search != null) {

	$sWhere .= " and ( (CONCAT(a.order_prefix,a.order_no) LIKE '%" . $search . "%') OR (CONCAT(u.fname, ' ', u.lname) LIKE '%" . $search . "%') OR addrs.recipient_address LIKE '%" . $search . "%' )";
}

// // pagination variables
$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
$per_page = 25; //how much records you want to show
$adjacents  = 4; //gap between pages after number of adjacents
$offset = ($page - 1) * $per_page;


$sql = "SELECT  a.is_consolidate, a.notes_for_driver, a.delivery_type,a.sub_total, a.distance, a.order_incomplete, a.status_invoice, a.is_pickup, a.total_order, a.order_id, a.order_prefix, a.order_no, a.order_date, a.sender_id, a.receiver_id, a.order_courier, a.order_pay_mode, a.status_courier, a.driver_id, a.order_service_options, a.total_order,  b.mod_style, b.color, 
			 u.username, u.fname, u.lname, addrs.recipient_address,a.notes,a.tags,a.no_of_pieces,a.charge FROM
			 cdb_add_order as a
			 LEFT JOIN cdb_users as u ON a.sender_id = u.id
			 INNER JOIN cdb_address_shipments as addrs ON addrs.order_id = a.order_id
			 INNER JOIN cdb_styles as b ON a.status_courier = b.id
			 $sWhere
			 GROUP BY
				a.order_id,
				a.is_consolidate,
				a.notes_for_driver,
				a.delivery_type,
				a.order_prefix,
				a.order_no,
				a.order_date,
				a.sender_id,
				a.receiver_id,
				a.order_courier,
				a.order_pay_mode,
				a.status_courier,
				a.driver_id,
				a.order_service_options,
				b.mod_style,
				b.color,
				u.username,
				u.fname,
				u.lname,
				addrs.recipient_address,
				a.notes
			 order by order_id desc
			 ";

// echo '<pre>'; print_r($sql); exit;
$query_count = $db->cdp_query($sql);
$db->cdp_execute();
$numrows = $db->cdp_rowCount();


$db->cdp_query($sql . " limit $offset, $per_page");
$data = $db->cdp_registros();

$total_pages = ceil($numrows / $per_page);

if ($numrows > 0) { ?>
	<div class="table-responsive">


		<table id="zero_config" class=" table table-condensed table-hover table-striped">
			<thead>
				<tr>
					<th><b><?php echo $lang['ltracking'] ?></b></th>
					<th class="text-center"><b><?php echo $lang['ddate'] ?></b></th>
					<th class="text-center"><b>Delivery Type</b></th>
					<th class="text-center"><b><?php echo $lang['left498'] ?></b></th>
					<th class="text-center"><b><?php echo $lang['left499'] ?></b></th>
					<th class="text-center"><b><?php echo $lang['lorigin'] ?></b></th>
					<th class="text-center"><b><?php echo $lang['ldestination'] ?></b></th>
					<th class="text-center"><b>Notes for driver</b></th>
					<th class="text-center"><b>Charge</b></th>
					
					<th class="text-center"><b>Tags</b></th>
					<th class="text-center"><b>Pieces</b></th>
					<th class="text-center"><b>Total KM</b></th>
					<?php if($_SESSION['userid']!=52){ ?>
					<th class="text-center"><b>Subtotal</b></th>
					<?php } ?>
					<th class="text-center"><b><?php echo $lang['lstatusshipment'] ?></b></th> 
					<?php if($_SESSION['userid']!=52){ ?>
					<th class="text-center"><b>Total Cost</b></th>
					<?php } ?>

					<th></th>
					<?php if ($userData->userlevel != 1) { ?>
						<th class="text-center"><b><?php
													?></b></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody id="projects-tbl">


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

						$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row->sender_id . "'");
						$sender_data = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_recipients where id= '" . $row->receiver_id . "'");
						$receiver_data = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_users where id= '" . $row->driver_id . "'");
						$driver_data = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_courier_com where id= '" . $row->order_courier . "'");
						$courier_com = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_met_payment where id= '" . $row->order_pay_mode . "'");
						$met_payment = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_shipping_mode where id= '" . $row->order_service_options . "'");
						$order_service_options = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_styles where id= '14'");
						$status_style_pickup = $db->cdp_registro();

						$db->cdp_query("SELECT * FROM cdb_address_shipments where order_track='" . $row->order_prefix . $row->order_no . "'");
						$address_order = $db->cdp_registro();


					?>
						<tr class="card-hovera">
							<td><b><a href="courier_view.php?id=<?php echo $row->order_id; ?>"><?php echo $row->order_prefix . $row->order_no; ?></a></b></td>
							<td class="text-center">
								<?php echo $row->order_date; ?>
							</td>

							<td class="text-center">
								<?php echo $row->delivery_type; ?>
							</td>

							<td class="text-center">
								<?php echo $sender_data->fname; ?> <?php echo $sender_data->lname; ?>
							</td>

							<td class="text-center">
								<?php echo $receiver_data->fname; ?> <?php echo $receiver_data->lname; ?>
							</td>

							<td class="text-center"><?php echo $address_order->sender_address.', '.$address_order->sender_city.', '.$address_order->sender_state; ?></td>
							<td class="text-center"><?php echo $address_order->recipient_address.', '.$address_order->recipient_city.', '.$address_order->recipient_state; ?></td>
							<td class="text-center"><?php echo $row->notes_for_driver!=''?$row->notes_for_driver:$row->notes; ?></td>
							<td class="text-center"><?php echo $row->charge>0?'$'.number_format($row->charge,2):''; ?></td>
							<td class="text-center">
								<?php echo implode(',',json_decode($row->tags,TRUE)); ?>
							</td>
							
							<td class="text-center">
								<?php echo $row->no_of_pieces>0?$row->no_of_pieces:''; ?>
							</td>
							<td class="text-center">
								<?php echo $row->distance; ?>
							</td>
							<?php if($_SESSION['userid']!=52){ ?>
							<td class="text-center">
								$<?php echo $row->sub_total; ?>
							</td>
							<?php } ?>
							<td class="text-center">

								
								<br>
								<?php if ($row->is_pickup != 0) { ?>
									<?php if ($row->status_courier == 14) { ?>
										<span style="background: #5BE472;" class="label label-large"><?php echo $lang['left533020020']; ?></span>

									<?php } else{ ?>
										<span style="background: <?php echo $row->color; ?>;" class="label label-large"><?php echo $row->mod_style; ?></span>
										<?php
										}
									?>

								<?php } ?>

								
								
								
							</td>
							<?php if($_SESSION['userid']!=52){ ?>
							<td class="text-center"><?php echo cdb_money_format($row->total_order); ?></td>
							<?php } ?>

							<td align='center'>
								<?php if ($row->status_courier != 14) { ?>


									<div class="btn-group">
										<button class="btn btn-block btn-outline-dark btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									        <i class="fas fa-ellipsis-v"></i> <!-- Utiliza el icono de puntos suspensivos -->
									    </button>
										<div class="dropdown-menu" style="overflow-y: auto; max-height: 200px;">
											<a class="dropdown-item" href="courier_view.php?id=<?php echo $row->order_id; ?>" title="<?php echo $lang['tooledit'] ?>"><i style="color:#343a40" class="fa fa-search"></i>&nbsp;<?php echo $lang['leftorder266']; ?></a>
											<?php if ($row->order_incomplete == 0) { ?>
												<?php if ($row->is_pickup == 0) { ?>
													<?php if ($userData->userlevel != 1) { ?>
														<a class="dropdown-item" href="pickup_accept.php?id=<?php echo $row->order_id; ?>" title="<?php echo $lang['tooledit'] ?>"><i style="color:#343a40" class="ti-pencil"></i>&nbsp; <?php echo $lang['modal-text7']; ?></a>
													<?php } ?>
													<a class="dropdown-item" href="print_label_ship.php?id=<?php echo $row->order_id; ?>" target="_blank"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toollabel'] ?> </a>
												<?php } ?>
											<?php } ?>

											<?php if ($row->order_incomplete == 1) { ?>


												<?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>


													<?php if ($row->status_courier != 21) { ?>
														<?php if ($row->status_courier != 12) { ?>
															<a class="dropdown-item" target="blank" href="print_inv_ship.php?id=<?php echo $row->order_id; ?>"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toolprint'] ?></a>
															<a class="dropdown-item" href="print_label_ship.php?id=<?php echo $row->order_id; ?>" target="_blank"> <i style="color:#343a40" class="ti-printer"></i>&nbsp;<?php echo $lang['toollabel'] ?> </a>

														<?php } ?>

													<?php } ?>
												<?php } ?>
											<?php } ?>

										</div>

									</div>
								<?php
								} else { ?>


									<div class="btn-group">
										<button class="btn btn-info btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<?php echo $lang['left906']; ?>
										</button>
										<div class="dropdown-menu">

											<a class="dropdown-item" href="courier_view.php?id=<?php echo $row->order_id; ?>" title="<?php echo $lang['tooledit'] ?>"><i style="color:#343a40" class="fa fa-search"></i>&nbsp;<?php echo $lang['leftorder266']; ?></a>

											<?php if ($userData->userlevel == 9 || $userData->userlevel == 2) { ?>

												<?php if ($row->status_courier == 14) { ?>

													<a class="dropdown-item" href="pickup_accept.php?id=<?php echo $row->order_id; ?>">
														<i style="color:#36bea6" class="fas fa-check-circle"></i>&nbsp; <?php echo $lang['left533020020']; ?>
													</a>

												<?php } ?>
											<?php } ?>

											<a class="dropdown-item" data-id="<?php echo $row->order_id; ?>" href="#" data-toggle="modal" data-target="#myModalCancel">
												<i style="color:#f62d51" class="fas fa-times-circle"></i>&nbsp;<?php echo $lang['left533020021']; ?></a>

										</div>
									</div>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>

				<?php } ?>
			</tbody>

		</table>


		<div class="pull-right">
			<?php echo cdp_paginate($page, $total_pages, $adjacents, $lang);	?>
		</div>
	</div>
<?php } ?>