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
	<title><?= $data->checksheet_name; ?> - <?= date_format(date_create($data->periode), 'M Y'); ?></title>
	<style>
		* {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 10px;
			color: #000;
		}

		body {
			margin: 10px;
			color: #000;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		.main-table th,
		.main-table td {
			border: 1px solid #000;
			padding: 5px;
		}

		.weekend {
			background-color: #ffebee !important;
			color: #c62828 !important;
		}

		.bg-gray {
			background-color: #f0f0f0;
		}

		.badge-yes {
			border: 1px solid #000;
			padding: 2px 4px;
			font-weight: bold;
			font-size: 9px;
			background-color: #e8f5e9;
		}

		.badge-no {
			border: 1px solid #000;
			padding: 2px 4px;
			font-weight: bold;
			font-size: 9px;
			background-color: #ffebee;
			color: #c62828;
		}

		.summary-box {
			border: 1px solid #000;
			padding: 10px;
			margin-top: 15px;
			background-color: #fff;
		}
	</style>
</head>

<body>
	<table style="width: 100%; margin-bottom: 12px; border: none;">
		<tr>
			<td style="width: 60%; vertical-align: top; border: none;">
				<h2 style="margin: 0 0 6px 0; font-size: 14px; font-weight: bold; color: #000;"><?= strtoupper($data->checksheet_name); ?></h2>
				<table style="width: 100%; border: none;">
					<tr>
						<td style="width: 130px; font-weight: bold; border: none;">Periode</td>
						<td style="width: 10px; border: none;">:</td>
						<td style="border: none; font-weight: bold;"><?= (!empty($data->periode) && strtotime($data->periode)) ? date('F Y', strtotime($data->periode)) : '-'; ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold; border: none;">Frekuensi Eksekusi</td>
						<td style="border: none;">:</td>
						<td style="border: none;"><?= isset($fExecution[$data->frequency_execution]) ? $fExecution[$data->frequency_execution] : '-'; ?></td>
					</tr>
				</table>
			</td>
			<td style="width: 40%; vertical-align: top; border: none;">
				<table style="width: 100%; border: none;">
					<tr>
						<td style="width: 130px; font-weight: bold; border: none;">Frekuensi Checking</td>
						<td style="width: 10px; border: none;">:</td>
						<td style="border: none;"><?= isset($fChecking[$data->frequency_checking]) ? $fChecking[$data->frequency_checking] : '-'; ?></td>
					</tr>
					<tr>
						<td style="font-weight: bold; border: none;">Status Verifikasi</td>
						<td style="border: none;">:</td>
						<td style="font-weight: bold; border: none;"><?= $data->checker_status ? $data->checker_status : 'Pending'; ?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>

	<table class="main-table">
		<thead>
			<tr class="bg-gray">
				<th rowspan="2" style="width: 30px; text-align: center;">No</th>
				<th rowspan="2" style="width: 160px; text-align: left;">Item Pengecekan</th>
				<th rowspan="2" style="width: 160px; text-align: left;">Standar Pengecekan</th>
				<th colspan="<?= $count; ?>" style="text-align: center;">Hasil Pengecekan</th>
			</tr>
			<tr class="bg-gray">
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
					<th class="<?= $weekendClass; ?>" style="text-align: center; min-width: 50px;">
						<?php if ($data->frequency_execution == 3 && $dayName) : ?>
							<div style="font-size: 8.5px; font-weight: bold; color: <?= $isOff ? '#c62828' : '#000'; ?>"><?= $dayName; ?></div>
							<div style="font-weight: bold;"><?= $i; ?></div>
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
					<td style="text-align: center; font-weight: bold;"><?= $n; ?></td>
					<td style="font-weight: bold;"><?= $it->item_name; ?></td>
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
						$nn = "n" . $i;
						$Nn = "note" . $i;
						$val = isset($it->$nn) ? $it->$nn : '';
						$weekendClass = $isOff ? "weekend" : "";
					?>
						<td class="<?= $weekendClass ?>" style="text-align: center; font-size: 9px;">
							<?php if ($isOff) : ?>
								-
							<?php elseif ($it->check_type == 'boolean') : ?>
								<?php if ($val == 'no') : ?>
									<span class="badge-no">NO</span>
									<?php if (isset($ArrNotes[$it->id]->$Nn) && $ArrNotes[$it->id]->$Nn) : ?>
										<div style="font-size: 8px; color: #c62828; margin-top: 2px;">
											<?= $ArrNotes[$it->id]->$Nn; ?>
										</div>
									<?php endif; ?>
								<?php elseif ($val == 'yes') : ?>
									<span class="badge-yes">YES</span>
								<?php else : ?>
									-
								<?php endif; ?>
							<?php else : ?>
								<span style="font-weight: bold;"><?= $val ?: '-'; ?></span>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<!-- Execution By -->
			<tr class="bg-gray">
				<th colspan="3" style="text-align: right; font-weight: bold;">Eksekutor</th>
				<?php
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck = 'day' . $i;
					$dateCheck = 'date' . $i;
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
					<td class="<?= $weekendClass ?>" style="text-align: center; font-size: 8.5px;">
						<?php if ($isOff) : ?>
							-
						<?php elseif (isset($ArrExe[$data->id]->$dayCheck) && $ArrExe[$data->id]->$dayCheck) : ?>
							<div style="font-weight: bold;"><?= isset($ArrUsers[$ArrExe[$data->id]->$dayCheck]) ? $ArrUsers[$ArrExe[$data->id]->$dayCheck] : ''; ?></div>
							<div><?= isset($ArrExeDate[$data->id]->$dateCheck) ? date('H:i', strtotime($ArrExeDate[$data->id]->$dateCheck)) : ''; ?></div>
						<?php else : ?>
							-
						<?php endif; ?>
					</td>
				<?php endfor; ?>
			</tr>

			<!-- Checker Status -->
			<tr class="bg-gray">
				<th colspan="3" style="text-align: right; font-weight: bold;">Checker (Konsistensi)</th>
				<?php
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck   = 'day' . $i;
					$dateCheck  = 'date' . $i;
					$checkValCol = 'check' . $i;
					$isOff = false;
					if ($data->frequency_execution == 3 && !empty($data->periode)) {
						$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
						$dayNum = (int)date('w', strtotime($tanggalkolom));
						if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
							$isOff = true;
						}
					}
					$currentCheckVal = (isset($ArrCheckVal[$data->id]->$checkValCol)) ? strtolower($ArrCheckVal[$data->id]->$checkValCol) : '';
					$hasChecked = (isset($ArrCheck[$data->id]->$dayCheck) && $ArrCheck[$data->id]->$dayCheck);
					$weekendClass = $isOff ? "weekend" : "";
				?>
					<td class="<?= $weekendClass ?>" style="text-align: center; font-size: 8.5px;">
						<?php if ($isOff) : ?>
							-
						<?php elseif ($hasChecked || $currentCheckVal) : ?>
							<?php if ($currentCheckVal == 'yes') : ?>
								<span class="badge-yes">Y</span>
							<?php elseif ($currentCheckVal == 'no') : ?>
								<span class="badge-no">N</span>
							<?php endif; ?>
							<div style="font-size: 8px; font-weight: bold; margin-top: 1px;"><?= isset($ArrUsers[$ArrCheck[$data->id]->$dayCheck]) ? $ArrUsers[$ArrCheck[$data->id]->$dayCheck] : ''; ?></div>
						<?php else : ?>
							-
						<?php endif; ?>
					</td>
				<?php endfor; ?>
			</tr>

			<!-- Checker Daily Note (3 Baris Display) -->
			<tr>
				<th colspan="3" style="text-align: right; font-weight: bold;">Catatan Harian Checker</th>
				<?php
				for ($i = 1; $i <= $count; $i++) :
					$dayNoteCol = 'day' . $i;
					$isOff = false;
					if ($data->frequency_execution == 3 && !empty($data->periode)) {
						$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
						$dayNum = (int)date('w', strtotime($tanggalkolom));
						if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
							$isOff = true;
						}
					}
					$currentNote = isset($ArrCheckNote[$data->id]->$dayNoteCol) ? $ArrCheckNote[$data->id]->$dayNoteCol : '';
					$weekendClass = $isOff ? "weekend" : "";
				?>
					<td class="<?= $weekendClass ?>" style="text-align: left; font-size: 8px; height: 40px; vertical-align: top;">
						<?= $isOff ? '-' : ($currentNote ? htmlspecialchars($currentNote) : '-'); ?>
					</td>
				<?php endfor; ?>
			</tr>
		</tfoot>
	</table>

	<!-- Evaluasi, Kesimpulan & Tanda Tangan -->
	<div class="summary-box">
		<table style="width: 100%; border: none;">
			<tr>
				<td style="width: 65%; vertical-align: top; border: none;">
					<strong style="font-size: 11px;">Kesimpulan & Saran Checker:</strong>
					<div style="margin-top: 5px; line-height: 1.4; font-size: 10px;">
						<?= !empty($data->checker_summary_note) ? nl2br(htmlspecialchars($data->checker_summary_note)) : 'Tidak ada catatan khusus.'; ?>
					</div>
					<?php if (!empty($data->checked_at) && !empty($data->checked_by)) : ?>
						<div style="margin-top: 8px; font-size: 9px;">
							Diverifikasi oleh: <strong><?= isset($ArrUsers[$data->checked_by]) ? $ArrUsers[$data->checked_by] : 'User ID ' . $data->checked_by; ?></strong> pada <?= date('d/m/Y H:i', strtotime($data->checked_at)); ?>
						</div>
					<?php endif; ?>
				</td>
				<td style="width: 35%; vertical-align: top; border: none; text-align: center;">
					<table style="width: 100%; border: none;">
						<tr>
							<td style="text-align: center; border: none;">
								<strong>Checker / Supervisor</strong>
								<br><br><br><br>
								( <?= (!empty($data->checked_by) && isset($ArrUsers[$data->checked_by])) ? $ArrUsers[$data->checked_by] : '_______________________'; ?> )
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>

	<script>
		window.print();
	</script>
</body>

</html>