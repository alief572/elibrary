<div class="row mb-3">
	<label for="" class="col-md-2 control-label">Checksheet Name</label>
	<div class="col-md-4">:
		<input type="hidden" id="data-id" value="<?= $data->id; ?>">
		<label for=""><?= $data->checksheet_name; ?></label>
	</div>
</div>
<div class="row mb-3">
	<label for="" class="col-md-2 control-label">Frequency Execution</label>
	<div class="col-md-4">:
		<label for=""><?= $fExecution[$data->frequency_execution]; ?></label>
	</div>
</div>
<div class="row mb-3">
	<label for="" class="col-md-2 control-label">Periode</label>
	<div class="col-md-4">:
		<label><?= date_format(date_create($data->periode), 'M, Y'); ?></label>
	</div>
</div>
<div class="row mb-3">
	<label for="" class="col-md-2 control-label">Checksheet Name</label>
	<div class="col-md-4">:
		<label for=""><?= $data->checksheet_name; ?></label>
	</div>
</div>
<div class="row mb-3">
	<label for="" class="col-md-2 control-label">Frequency Checking</label>
	<div class="col-md-4">:
		<label for=""><?= $fChecking[$data->frequency_checking]; ?></label>
	</div>
</div>
<hr>
<h5>List Checksheets</h5>
<div class="table-responsive" style="overflow-x:auto;">
	<table class="table table-bordered" style="width:<?= $width; ?>;">
		<thead class="table-light">
			<tr>
				<th rowspan="2" class="p-2" width="50">No</th>
				<th rowspan="2" class="p-2" width="">Items</th>
				<th rowspan="2" class="p-2" width="">Standard</th>
				<th colspan="<?= $count; ?>" class="p-2 text-center" width="<?= $col_width; ?>">Result</th>
			</tr>
			<tr>
				<?php for ($i = 1; $i <= $count; $i++) : ?>
					<th class="text-center"><?= $name_col . " " . $i; ?>
						<?php if ($weekOfMonth) : ?>
							<?php if ($weekOfMonth == $i) : ?>
								<input type="hidden" id="field" value="<?= $i; ?>">
							<?php endif; ?>
						<?php else : ?>
							<?php if ($i == (date('d'))) : ?>
								<input type="hidden" id="field" value="<?= $i; ?>">
							<?php endif; ?>
						<?php endif; ?>
					</th>

				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php $n = 0;
			if ($details) foreach ($details as $it) : $n++; ?>
				<tr>
					<td>
						<?= $n; ?>
					</td>
					<td><?= $it->item_name; ?></td>
					<td><?= $it->standard_check; ?></td>
					<?php for ($i = 1; $i <= $count; $i++) : ?>
						<?php $nn = "n" . $i; ?>
						<?php $Nn = "note" . $i; ?>
						<td class="<?= ($it->$nn == '') ? 'bg-light' : ''; ?>">
							<?php if ($it->check_type == 'boolean') : ?>
								<?php if ($it->$nn == 'no') : ?>
									<label for="" class="label-danger label"><?= ucfirst($it->$nn); ?></label>
									<?php if (isset($ArrNotes[$it->id]->$Nn)) : ?>
										<div class="alert alert-light p-2 my-1 font-italic" role="alert">
											<?= $ArrNotes[$it->id]->$Nn; ?>
										</div>
									<?php endif; ?>
								<?php elseif ($it->$nn == 'yes') : ?>
									<label for="" class="label-success label"><?= ucfirst($it->$nn); ?></label>
								<?php endif; ?>
							<?php else : ?>
								<?= ($it->$nn) ?: ''; ?>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1 text-right" width="">Execution By</th>
				<?php
				$day = 'day';
				$date = 'date';
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck = $day . $i;
					$dateCheck = $date . $i;
				?>
					<td class="text-muted p-1">
						<small for="">
							<?= isset($ArrExe[$data->id]->$dayCheck) ? $ArrUsers[$ArrExe[$data->id]->$dayCheck] . " | " : ''; ?>
						</small><small for="">
							<?= isset($ArrExeDate[$data->id]->$dateCheck) ? $ArrExeDate[$data->id]->$dateCheck : '' ?>
						</small>
					</td>
				<?php endfor; ?>
			</tr>
			<tr>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1 text-right" width="">Checker By</th>
				<?php
				$day = 'day';
				$date = 'date';
				$showButton = '<label class="btn btn-outline-success">Checked</label>';
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck 	= $day . $i;
					$dateCheck 	= $date . $i;
					if (isset($weekOfMonth)) {
						if ($weekOfMonth == $i) {
							if (!isset($ArrCheck[$data->id]->$dayCheck)) {
								$showButton = '
								<button type="button" class="btn btn-primary" id="check-done">
									<i class="fa fa-check"></i>
									Check Done
								</button>';
							}
						}
					} elseif ($i == (date('d'))) {
						if (!isset($ArrCheck[$data->id]->$dayCheck)) {
							$showButton = '
							<button type="button" class="btn btn-primary" id="check-done">
								<i class="fa fa-check"></i>
								Check Done
							</button>';
						}
					}
				?>
					<td class="text-muted p-1">
						<small for="">
							<?= isset($ArrCheck[$data->id]->$dayCheck) ? $ArrUsers[$ArrCheck[$data->id]->$dayCheck] . " | " : ''; ?>
						</small><small for="">
							<?= isset($ArrCheckDate[$data->id]->$dateCheck) ? $ArrCheckDate[$data->id]->$dateCheck : '' ?>
						</small>
					</td>
				<?php endfor; ?>
			</tr>
		</tfoot>
	</table>
</div>
<div class="p-3">
	<?= $showButton; ?>
</div>