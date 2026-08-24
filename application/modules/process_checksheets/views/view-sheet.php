<?php
$dayNamesIndo = [
	0 => 'Min', // Minggu
	1 => 'Sen', // Senin
	2 => 'Sel', // Selasa
	3 => 'Rab', // Rabu
	4 => 'Kam', // Kamis
	5 => 'Jum', // Jumat
	6 => 'Sab'  // Sabtu
];
?>
<div class="row mb-3">
	<label for="" class="col-md-2 control-label">Checksheet Name</label>
	<div class="col-md-4">:
		<input type="hidden" name="id" value="<?= $data->id; ?>">
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
				<?php for ($i = 1; $i <= $count; $i++) {
					$isWeekend = false;
					$isHoliday = false;
					$holidayName = "";
					$dayName = "";
					if ($data->frequency_execution == 3 && !empty($data->periode)) {
						$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
						$dayNum = (int)date('w', strtotime($tanggalkolom));
						$dayName = isset($dayNamesIndo[$dayNum]) ? $dayNamesIndo[$dayNum] : '';
						if ($dayNum === 0 || $dayNum === 6) {
							$isWeekend = true;
						}
						if (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom])) {
							$isHoliday = true;
							$holidayName = $ArrHolidays[$tanggalkolom];
						}
					}
					$isOff = ($isWeekend || $isHoliday);
					$offClass = $isOff ? "table-danger text-danger font-weight-bold" : "";
				?>
					<th class="text-center <?= $offClass ?>" title="<?= $holidayName ? htmlspecialchars($holidayName) : ''; ?>">
						<?php if ($data->frequency_execution == 3 && $dayName) : ?>
							<span class="d-block" style="font-size:11px;"><?= $dayName; ?></span>
							<span><?= $i; ?></span>
							<?php if ($isHoliday) : ?>
								<small class="d-block text-danger" style="font-size:9px;line-height:1;"><?= htmlspecialchars($holidayName); ?></small>
							<?php endif; ?>
						<?php elseif ($data->frequency_execution == 5 && is_array($name_col)) : ?>
							<?= $name_col[$i]; ?>
						<?php else : ?>
							<?= $name_col . " " . $i; ?>
						<?php endif; ?>
					</th>
				<?php } ?>
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
					<td>
						<?= $it->standard_check; ?>
						<?php
						$get_checksheet_data = $this->db->get_where('checksheet_data_items', array('id' => $it->checksheet_item_id))->row();
						if (
							!empty($get_checksheet_data->upload_standard_check) &&
							file_exists($get_checksheet_data->upload_standard_check)
						) {
							echo '<br>';
							echo '<a href="' . base_url($get_checksheet_data->upload_standard_check) . '" class="btn btn-sm btn-primary" target="_blank"><i class="fa fa-file"></i> View File </a>';
						}
						?>
					</td>
					<?php for ($i = 1; $i <= $count; $i++) {
						$isWeekend = false;
						$isHoliday = false;
						if ($data->frequency_execution == 3 && !empty($data->periode)) {
							$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
							$dayNum = (int)date('w', strtotime($tanggalkolom));
							if ($dayNum === 0 || $dayNum === 6) {
								$isWeekend = true;
							}
							if (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom])) {
								$isHoliday = true;
							}
						}
						$isOff = ($isWeekend || $isHoliday);
						$nn = "n" . $i;
						$Nn = "note" . $i;
					?>
						<td class=" 
							<?php if (!$isOff) { ?>
								<?= ($it->$nn == '') ? 'bg-light' : ''; ?>
							<?php
							} else {
								echo "table-danger";
							}
							?>">
							<?php if ($it->check_type == 'boolean') : ?>
								<?php if ($it->$nn == 'no') : ?>
									<label for="" class="label-danger label"><?= ucfirst($it->$nn); ?></label>
									<?php if (isset($ArrNotes[$it->id]->$Nn) && $ArrNotes[$it->id]->$Nn) : ?>
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
					<?php } ?>
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
					$isOff = false;
					if ($data->frequency_execution == 3 && !empty($data->periode)) {
						$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
						$dayNum = (int)date('w', strtotime($tanggalkolom));
						if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
							$isOff = true;
						}
					}
				?>
					<td class="text-muted p-1 <?= $isOff ? 'table-danger' : ''; ?>">
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
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck = $day . $i;
					$dateCheck = $date . $i;
					$isOff = false;
					if ($data->frequency_execution == 3 && !empty($data->periode)) {
						$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
						$dayNum = (int)date('w', strtotime($tanggalkolom));
						if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
							$isOff = true;
						}
					}
				?>
					<td class="text-muted p-1 <?= $isOff ? 'table-danger' : ''; ?>">
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