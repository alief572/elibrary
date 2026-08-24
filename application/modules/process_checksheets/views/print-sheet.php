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
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $data->checksheet_name; ?></title>
	<style>
		* {
			font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
			font-size: 11px;
		}
		.weekend {
			background-color: #f8d7da !important;
			color: #721c24 !important;
		}
	</style>
</head>

<body>
	<table>
		<thead>
			<tr>
				<th style="text-align: left;">Checksheet Name</th>
				<th>:</th>
				<th style="text-align: left;"><?= $data->checksheet_name; ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Frequency Execution</th>
				<th>:</th>
				<th style="text-align: left;"><?= $fExecution[$data->frequency_execution]; ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Periode</th>
				<th>:</th>
				<th style="text-align: left;"><?= date_format(date_create($data->periode), 'M, Y'); ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Checksheet Name</th>
				<th>:</th>
				<th style="text-align: left;"><?= $data->checksheet_name; ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Frequency Checking</th>
				<th>:</th>
				<th style="text-align: left;"><?= $fChecking[$data->frequency_checking]; ?></th>
			</tr>
		</thead>
	</table>
	<hr>
	<h3>List Checksheets</h3>
	<table border="1" style="width:100%;border-collapse: collapse;">
		<thead class="table-light">
			<tr>
				<th rowspan="2" class="p-2" width="50">No</th>
				<th rowspan="2" class="p-2" width="">Items</th>
				<th rowspan="2" class="p-2" width="">Standard</th>
				<th colspan="<?= $count; ?>" class="p-2 text-center">Result <?= $name_col; ?></th>
			</tr>
			<tr>
				<?php for ($i = 1; $i <= $count; $i++) :
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
					$weekendClass = $isOff ? "weekend" : "";
				?>
					<th class="text-center <?= $weekendClass; ?>" title="<?= $holidayName ? htmlspecialchars($holidayName) : ''; ?>">
						<?php if ($data->frequency_execution == 3 && $dayName) : ?>
							<span style="display:block;font-size:9px;"><?= $dayName; ?></span>
							<span><?= $i; ?></span>
							<?php if ($isHoliday) : ?>
								<span style="display:block;font-size:7px;color:#721c24;"><?= htmlspecialchars($holidayName); ?></span>
							<?php endif; ?>
						<?php elseif ($data->frequency_execution == 5 && is_array($name_col)) : ?>
							<?= $name_col[$i]; ?>
						<?php else : ?>
							<?= $i; ?>
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
					<?php for ($i = 1; $i <= $count; $i++) :
						$isOff = false;
						if ($data->frequency_execution == 3 && !empty($data->periode)) {
							$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
							$dayNum = (int)date('w', strtotime($tanggalkolom));
							if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
								$isOff = true;
							}
						}
						$weekendClass = $isOff ? "weekend" : "";
					?>
						<?php $nn = "n" . $i; ?>
						<?php $Nn = "note" . $i; ?>
						<td class="<?= $weekendClass ?> <?= ($it->$nn == '' && !$isOff) ? 'bg-light' : ''; ?>">
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
					$isOff = false;
					if ($data->frequency_execution == 3 && !empty($data->periode)) {
						$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
						$dayNum = (int)date('w', strtotime($tanggalkolom));
						if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
							$isOff = true;
						}
					}
					$weekendClass = $isOff ? "weekend" : "";
				?>
					<td class="text-muted p-1 <?= $weekendClass ?>">
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
					$weekendClass = $isOff ? "weekend" : "";
				?>
					<td class="text-muted p-1 <?= $weekendClass ?>">
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
</body>

</html>