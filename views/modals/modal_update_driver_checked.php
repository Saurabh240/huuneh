	<!-- Modal -->

	<?php $driverrow = $user->cdp_userAllDriver(); ?>

	<div class="modal fade" id="modalDriverCheckbox" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="myModalLabel"><?php echo $lang['left208'] ?></h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<form class="form-horizontal" method="post" id="driver_update_multiple" name="driver_update_multiple">

						<div class="resultados_ajax_mail text-center"></div>

						<div class="row">
							<div class="col-sm-12">
								<div class="input-group">

									<select class="form-control custom-select" id="driver_id_multiple" name="driver_id_multiple" required>

										<option value="">--<?php echo $lang['left209'] ?>--</option>
										<?php foreach ($driverrow as $row) : ?>
											<option value="<?php echo $row->id; ?>"><?php echo $row->fname . ' ' . $row->lname; ?></option>

										<?php endforeach; ?>
									</select>
								</div>

							</div>
						</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $lang['modal-text5'] ?></button>
					<button type="submit" class="btn btn-success" id="update_driver2"><?php echo $lang['modal-text28'] ?></button>
				</div>
				</form>
			</div>
		</div>
	</div>