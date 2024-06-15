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


$search = cdp_sanitize($_REQUEST['search']);

$tables = "cdb_users";
$fields = "*,CONCAT(fname,' ', lname) as name,
                DATE_FORMAT(created, '%d. %b. %Y %H:%i') as cdate,
                DATE_FORMAT(lastlogin, '%d. %b. %Y %H:%i') as adate";

$sWhere = "(userlevel=2 or userlevel=9 or userlevel=3)";

if ($search != null) {

	$sWhere .= " and (username LIKE '%" . $search . "%' or fname LIKE '%" . $search . "%' or lname LIKE '%" . $search . "%' or locker LIKE '%" . $search . "%' or email LIKE '%" . $search . "%' or phone LIKE '%" . $search . "%')";
}

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


	<table id="multi_control"
                      class="table table-striped table-bordered display text-nowrap"
                      style="width: 100%">
		<thead>
			<tr>
				<th><b><?php echo $lang['user_manage3'] ?></b></th>
				<th><b><?php echo $lang['user_manage54'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['user_manage38'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['user_manage40'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['user_manage41'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['user_manage42'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['edit-clien61'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['edit-clien60'] ?></b></th>
				<th class="text-center"><b><?php echo $lang['user_manage43'] ?></b></th>
				
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
			<?php foreach ($data as $user) { ?>
				<tr>


					<td><?php echo $user->username; ?></td>
					<td><?php echo $user->name_off; ?></td>
					<td><?php echo $user->name; ?></td>
					<td class="text-center"><?php echo cdp_userStatus($user->active, $user->id, $lang); ?></td>
					<td class="text-center"><?php echo cdp_isAdmin($user->userlevel, $lang); ?></td>
					<td class="text-center"><?php echo ($user->adate) ? $user->adate : "-/-"; ?></td>
					<?php if ($user->userlevel == 9 || $user->userlevel == 2) : ?>
					<td class="text-center"><?php echo $user->enrollment; ?></td>
					<td class="text-center"> <?php echo $user->vehiclecode; ?></td>
					<?php elseif ($user->userlevel == 3) : ?>
					<td class="text-center"><i class="icon-prepend icon-truck"></i> <?php echo $user->enrollment; ?></td>
					<td class="text-center"><i class="icon-prepend icon-tag"></i> <?php echo $user->vehiclecode; ?></td>
					<?php endif; ?>
					<td align='center'>
					<?php if ($user->userlevel == 9 || $user->userlevel == 2) : ?>
					    <a href="users_edit.php?user=<?php echo $user->id; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['edit-clien46'] ?>">
					        <i class="ti-pencil" aria-hidden="true"></i>
					    </a>
					<?php elseif ($user->userlevel == 3) : ?>
					    <a href="drivers_edit.php?user=<?php echo $user->id; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['edit-clien46'] ?>">
					        <i class="ti-pencil" aria-hidden="true"></i>
					    </a>
					<?php endif; ?>


						<a href="newsletter.php?email=<?php echo $user->email; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['edit-clien45'] ?>">
							<i style="color:#F5590D" class="ti-email"></i></a>

						<?php if ($user->id == 1) : ?>
							<a data-rel="<?php echo $user->username; ?>" data-toggle="tooltip" data-placement="top" title="Master Admin"><i style="color:#343a40" class="ti-lock"></i></a>
						<?php else : ?>
							<?php if ($user->userlevel == 9 || $user->userlevel == 2) : ?>
								<a onclick="cdp_eliminar('<?php echo $user->id; ?>')" id="item_<?php echo $user->id; ?>" data-rel="<?php echo $user->username; ?>" class="delete" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['edit-clien47'] ?>">
									<div class="icon-holder"><i class="fi fi-rr-trash"></i></div>
								</a>
							<?php elseif ($user->userlevel == 3) : ?>
								<a onclick="cdp_eliminar_driver('<?php echo $user->id; ?>')" id="itemdriver_<?php echo $user->id; ?>" class="delete" data-toggle="tooltip" data-placement="top" title="<?php echo $lang['edit-clien47'] ?>">
									<div class="icon-holder"><i class="fi fi-rr-trash"></i></div>
								</a>

							<?php endif; ?>	
						<?php endif; ?>
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