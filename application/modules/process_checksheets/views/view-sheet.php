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
<!-- Info Box Tanpa Background Hijau & Text Hitam Pekat -->
<div class="card card-custom border mb-4 bg-white shadow-none">
	<div class="card-body py-3 px-4" style="color: #000;">
		<div class="row">
			<div class="col-md-6">
				<table class="table table-borderless table-sm mb-0">
					<tr>
						<td width="35%" class="font-weight-bold" style="color: #000;">Nama Checksheet</td>
						<td width="5%" style="color: #000;">:</td>
						<td class="font-weight-bolder" style="color: #000;"><?= $data->checksheet_name; ?></td>
					</tr>
					<tr>
						<td class="font-weight-bold" style="color: #000;">Periode</td>
						<td style="color: #000;">:</td>
						<td class="font-weight-bolder" style="color: #000;">
							<i class="fa fa-calendar-alt mr-1" style="color: #000;"></i>
							<?= (!empty($data->periode) && strtotime($data->periode)) ? date('F Y', strtotime($data->periode)) : '-'; ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-6">
				<table class="table table-borderless table-sm mb-0">
					<tr>
						<td width="40%" class="font-weight-bold" style="color: #000;">Frekuensi Eksekusi</td>
						<td width="5%" style="color: #000;">:</td>
						<td class="font-weight-bolder" style="color: #000;"><?= isset($fExecution[$data->frequency_execution]) ? $fExecution[$data->frequency_execution] : '-'; ?></td>
					</tr>
					<tr>
						<td class="font-weight-bold" style="color: #000;">Frekuensi Checking</td>
						<td style="color: #000;">:</td>
						<td class="font-weight-bolder" style="color: #000;"><?= isset($fChecking[$data->frequency_checking]) ? $fChecking[$data->frequency_checking] : '-'; ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<h5 class="font-weight-bolder mb-3" style="color: #000;">
	<i class="fa fa-list-alt mr-2" style="color: #000;"></i> Hasil Checksheet
</h5>

<div class="table-responsive" style="overflow-x:auto;">
	<table class="table table-bordered table-sm" style="width: auto; min-width: 100%; color: #000;">
		<thead class="table-light">
			<tr>
				<th rowspan="2" class="p-2 text-center align-middle font-weight-bold" style="min-width: 45px; color: #000;">No</th>
				<th rowspan="2" class="p-2 align-middle font-weight-bold" style="min-width: 220px; color: #000;">Item Pengecekan</th>
				<th rowspan="2" class="p-2 align-middle font-weight-bold" style="min-width: 220px; color: #000;">Standar Pengecekan</th>
				<th colspan="<?= $count; ?>" class="p-2 text-center font-weight-bold" style="color: #000;">Hasil Eksekusi</th>
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
					$offClass = $isOff ? "table-danger font-weight-bold" : "";
					$headerStyle = $isOff ? "min-width: 200px; width: 200px; color: #b71c1c;" : "min-width: 200px; width: 200px; color: #000;";
				?>
					<th class="text-center p-1 <?= $offClass ?>" style="<?= $headerStyle ?>" title="<?= $holidayName ? htmlspecialchars($holidayName) : ''; ?>">
						<?php if ($data->frequency_execution == 3 && $dayName) : ?>
							<span class="d-block" style="font-size: 11px; font-weight: bold;"><?= $dayName; ?></span>
							<span class="font-weight-bolder" style="font-size: 13px;"><?= $i; ?></span>
							<?php if ($isHoliday) : ?>
								<small class="d-block font-weight-normal text-truncate" style="font-size: 10px; max-width: 190px; color: #b71c1c;"><?= htmlspecialchars($holidayName); ?></small>
							<?php endif; ?>
						<?php elseif ($data->frequency_execution == 5 && is_array($name_col)) : ?>
							<?= $name_col[$i]; ?>
						<?php else : ?>
							<?= $name_col . " " . $i; ?>
						<?php endif; ?>
					</th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php $n = 0;
			if ($details) foreach ($details as $it) : $n++; ?>
				<tr>
					<td class="text-center align-middle font-weight-bold" style="color: #000;"><?= $n; ?></td>
					<td class="align-middle font-weight-bold" style="color: #000;"><?= $it->item_name; ?></td>
					<td class="align-middle" style="color: #000;"><?= $it->standard_check; ?></td>
					<?php for ($i = 1; $i <= $count; $i++) {
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
					?>
						<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : '' ?> <?= ($val == '' && !$isOff) ? 'bg-light' : ''; ?>" style="min-width: 200px; width: 200px; color: #000;">
							<?php if ($isOff) : ?>
								<span style="color: #b71c1c;">-</span>
							<?php elseif ($it->check_type == 'boolean') : ?>
								<?php if ($val == 'no') : ?>
									<span class="badge badge-danger font-weight-bold px-2 py-1" style="font-size: 11px;">NO</span>
									<?php if (isset($ArrNotes[$it->id]->$Nn) && $ArrNotes[$it->id]->$Nn) : ?>
										<div class="alert alert-light-danger border border-danger p-2 my-1 text-left" style="font-size: 11px; line-height: 1.3; color: #000;">
											<strong>Note:</strong> <?= $ArrNotes[$it->id]->$Nn; ?>
										</div>
									<?php endif; ?>
								<?php elseif ($val == 'yes') : ?>
									<span class="badge badge-success font-weight-bold px-2 py-1" style="font-size: 11px;">YES</span>
								<?php else : ?>
									<span class="text-muted font-italic">-</span>
								<?php endif; ?>
							<?php else : ?>
								<span class="font-weight-bold" style="color: #000;"><?= $val ?: '-'; ?></span>
							<?php endif; ?>
						</td>
					<?php } ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<!-- Row 1: Execution By -->
			<tr class="table-light">
				<th colspan="3" class="p-2 text-right align-middle font-weight-bold" style="color: #000;">
					<i class="fa fa-user-edit mr-1" style="color: #000;"></i> Eksekutor (Dijalankan Oleh)
				</th>
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
				?>
					<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : ''; ?>" style="min-width: 200px; width: 200px; color: #000;">
						<?php if ($isOff) : ?>
							<span style="color: #b71c1c;">-</span>
						<?php elseif (isset($ArrExe[$data->id]->$dayCheck) && $ArrExe[$data->id]->$dayCheck) : ?>
							<span class="d-block font-weight-bold" style="font-size: 12px; color: #000;">
								<?= isset($ArrUsers[$ArrExe[$data->id]->$dayCheck]) ? $ArrUsers[$ArrExe[$data->id]->$dayCheck] : '-'; ?>
							</span>
							<small class="d-block font-weight-bold" style="font-size: 10px; color: #333;">
								<?= isset($ArrExeDate[$data->id]->$dateCheck) ? date('d/m/Y H:i', strtotime($ArrExeDate[$data->id]->$dateCheck)) : ''; ?>
							</small>
						<?php else : ?>
							<span class="text-muted font-italic">-</span>
						<?php endif; ?>
					</td>
				<?php endfor; ?>
			</tr>

			<!-- Row 2: Checker By -->
			<tr class="table-secondary">
				<th colspan="3" class="p-2 text-right align-middle font-weight-bold" style="color: #000;">
					<i class="fa fa-user-check mr-1" style="color: #000;"></i> Verifikasi Checker (Konsistensi)
				</th>
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
				?>
					<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : ''; ?>" style="min-width: 200px; width: 200px; color: #000;">
						<?php if ($isOff) : ?>
							<span class="font-weight-bold" style="color: #b71c1c;">Libur</span>
						<?php elseif ($hasChecked || $currentCheckVal) : ?>
							<?php if ($currentCheckVal == 'yes') : ?>
								<span class="badge badge-success font-weight-bold px-2 py-1" style="font-size: 11px;">YES</span>
							<?php elseif ($currentCheckVal == 'no') : ?>
								<span class="badge badge-danger font-weight-bold px-2 py-1" style="font-size: 11px;">NO</span>
							<?php endif; ?>
							<span class="d-block font-weight-bold mt-1" style="font-size: 11px; color: #000;">
								By: <?= isset($ArrUsers[$ArrCheck[$data->id]->$dayCheck]) ? $ArrUsers[$ArrCheck[$data->id]->$dayCheck] : ''; ?>
							</span>
							<small class="d-block font-weight-bold" style="font-size: 9.5px; color: #333;">
								<?= isset($ArrCheckDate[$data->id]->$dateCheck) ? date('d/m/Y H:i', strtotime($ArrCheckDate[$data->id]->$dateCheck)) : ''; ?>
							</small>
						<?php else : ?>
							<span class="text-muted font-italic">-</span>
						<?php endif; ?>
					</td>
				<?php endfor; ?>
			</tr>

			<!-- Row 3: Daily Checker Note (3 Baris Display) -->
			<tr>
				<th colspan="3" class="p-2 text-right align-middle font-weight-bold" style="color: #000;">
					<i class="fa fa-comment-dots mr-1" style="color: #000;"></i> Catatan Harian Checker
				</th>
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
				?>
					<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : '' ?>" style="min-width: 200px; width: 200px; color: #000;">
						<?php if ($isOff) : ?>
							<span style="color: #b71c1c;">-</span>
						<?php elseif ($currentNote) : ?>
							<div class="border rounded p-2 text-left bg-light" style="font-size: 11px; line-height: 1.3; min-height: 60px; color: #000; word-break: break-word;">
								<?= nl2br(htmlspecialchars($currentNote)); ?>
							</div>
						<?php else : ?>
							<span class="text-muted font-italic">-</span>
						<?php endif; ?>
					</td>
				<?php endfor; ?>
			</tr>
		</tfoot>
	</table>
</div>

<!-- Section Evaluasi & Kesimpulan Checker -->
<?php if (!empty($data->checker_summary_note) || !empty($data->checker_status)) : ?>
	<div class="card card-custom border mt-4 mb-2 bg-white shadow-none">
		<div class="card-header py-3 bg-light border-bottom">
			<h6 class="card-title font-weight-bolder m-0" style="color: #000;">
				<i class="fa fa-award mr-2" style="color: #000;"></i> Evaluasi, Kesimpulan & Saran Checker
			</h6>
		</div>
		<div class="card-body p-4" style="color: #000;">
			<div class="row">
				<div class="col-md-4 mb-3">
					<span class="font-weight-bold d-block mb-1" style="color: #000;">Status Verifikasi Akhir:</span>
					<?php if ($data->checker_status == 'Approved') : ?>
						<span class="badge badge-success font-weight-bold px-3 py-2" style="font-size: 13px;">
							<i class="fa fa-check-circle text-white mr-1"></i> Approved (Konsisten / Sesuai)
						</span>
					<?php elseif ($data->checker_status == 'Needs Improvement') : ?>
						<span class="badge badge-warning font-weight-bold px-3 py-2 text-dark" style="font-size: 13px;">
							<i class="fa fa-exclamation-triangle mr-1"></i> Needs Improvement (Perlu Perbaikan)
						</span>
					<?php elseif ($data->checker_status == 'Revision') : ?>
						<span class="badge badge-danger font-weight-bold px-3 py-2" style="font-size: 13px;">
							<i class="fa fa-times-circle text-white mr-1"></i> Revision (Perlu Pengulangan)
						</span>
					<?php else : ?>
						<span class="badge badge-secondary font-weight-bold px-3 py-2"><?= $data->checker_status ?: 'Belum Diverifikasi'; ?></span>
					<?php endif; ?>
				</div>
				<div class="col-md-8 mb-3">
					<span class="font-weight-bold d-block mb-1" style="color: #000;">Catatan Kesimpulan / Rekomendasi Saran:</span>
					<div class="border rounded p-3 mb-1 bg-light" style="font-size: 13px; line-height: 1.5; white-space: pre-wrap; color: #000;">
						<?= htmlspecialchars($data->checker_summary_note ?: '- Tidak ada catatan khusus -'); ?>
					</div>
					<?php if (!empty($data->checked_at) && !empty($data->checked_by)) : ?>
						<small class="font-weight-bold mt-1 d-block" style="color: #000;">
							<i class="fa fa-user-check mr-1" style="color: #000;"></i> Diverifikasi oleh <strong><?= isset($ArrUsers[$data->checked_by]) ? $ArrUsers[$data->checked_by] : 'User ID ' . $data->checked_by; ?></strong> pada <?= date('d M Y H:i', strtotime($data->checked_at)); ?>
						</small>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>