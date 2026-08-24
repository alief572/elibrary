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
<form id="form-checking-sheet">
	<input type="hidden" name="id" id="data-id" value="<?= $data->id; ?>">

	<!-- Info Box Tanpa Background Hijau & Text Hitam Pekat -->
	<div class="card card-custom border mb-4 bg-white shadow-none">
		<div class="card-body py-3 px-4">
			<div class="row">
				<div class="col-md-6">
					<table class="table table-borderless table-sm mb-0">
						<tr>
							<td width="35%" class="font-weight-bold">Nama Checksheet</td>
							<td width="5%">:</td>
							<td class="font-weight-bolder"><?= $data->checksheet_name; ?></td>
						</tr>
						<tr class="text-dark">
							<td class="font-weight-bold">Periode</td>
							<td>:</td>
							<td class="font-weight-bolder">
								<i class="fa fa-calendar-alt mr-1" ></i>
								<?= (!empty($data->periode) && strtotime($data->periode)) ? date('F Y', strtotime($data->periode)) : '-'; ?>
							</td>
						</tr>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table table-borderless table-sm mb-0">
						<tr class="text-dark">
							<td width="40%" class="font-weight-bold">Frekuensi Eksekusi</td>
							<td width="5%">:</td>
							<td class="font-weight-bolder"><?= isset($fExecution[$data->frequency_execution]) ? $fExecution[$data->frequency_execution] : '-'; ?></td>
						</tr>
						<tr>
							<td class="font-weight-bold" >Frekuensi Checking</td>
							<td >:</td>
							<td class="font-weight-bolder" ><?= isset($fChecking[$data->frequency_checking]) ? $fChecking[$data->frequency_checking] : '-'; ?></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>

	<h5 class="font-weight-bolder mb-3" >
		<i class="fa fa-user-check mr-2 text-info" ></i> Lembar Verifikasi Checker
	</h5>
	
	<div class="table-responsive" style="overflow-x:auto;">
		<table class="table table-bordered table-sm" style="width: auto; min-width: 100%; ">
			<thead class="table-light">
				<tr>
					<th rowspan="2" class="p-2 text-center align-middle font-weight-bold" style="min-width: 45px; ">No</th>
					<th rowspan="2" class="p-2 align-middle font-weight-bold" style="min-width: 220px; ">Item Pengecekan</th>
					<th rowspan="2" class="p-2 align-middle font-weight-bold" style="min-width: 220px; ">Standar Pengecekan</th>
					<th colspan="<?= $count; ?>" class="p-2 text-center font-weight-bold" style="">Hasil Eksekusi & Verifikasi</th>
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
						$headerStyle = $isOff ? "min-width: 200px; width: 200px; color: #b71c1c;" : "min-width: 200px; width: 200px; ";
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
						<td class="text-center align-middle font-weight-bold" style=""><?= $n; ?></td>
						<td class="align-middle font-weight-bold" style=""><?= $it->item_name; ?></td>
						<td class="align-middle" style=""><?= $it->standard_check; ?></td>
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
						?>
							<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : '' ?> <?= ($val == '' && !$isOff) ? 'bg-light' : ''; ?>" style="min-width: 200px; width: 200px; ">
								<?php if ($isOff) : ?>
									<span class="font-weight-bold" style="color: #b71c1c;">-</span>
								<?php elseif ($it->check_type == 'boolean') : ?>
									<?php if ($val == 'no') : ?>
										<span class="badge badge-danger font-weight-bold px-2 py-1" style="font-size: 11px;">NO</span>
										<?php if (isset($ArrNotes[$it->id]->$Nn) && $ArrNotes[$it->id]->$Nn) : ?>
											<div class="alert alert-light-danger border border-danger p-2 my-1 text-left" style="font-size: 11px; line-height: 1.3; ">
												<strong>Note:</strong> <?= $ArrNotes[$it->id]->$Nn; ?>
											</div>
										<?php endif; ?>
									<?php elseif ($val == 'yes') : ?>
										<span class="badge badge-success font-weight-bold px-2 py-1" style="font-size: 11px;">YES</span>
									<?php else : ?>
										<span class="text-muted font-italic" style="font-size: 11px;">Belum Diisi</span>
									<?php endif; ?>
								<?php else : ?>
									<span class="font-weight-bold" style=""><?= $val ?: '-'; ?></span>
								<?php endif; ?>
							</td>
						<?php endfor; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
			<tfoot>
				<!-- Row 1: Execution By -->
				<tr class="table-light">
					<th colspan="3" class="p-2 text-right align-middle font-weight-bold text-primary" >
						<i class="fa fa-user-edit mr-1 text-primary"></i> Eksekutor (Dijalankan Oleh)
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
						<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : '' ?>" style="min-width: 200px; width: 200px; ">
							<?php if ($isOff) : ?>
								<span style="color: #b71c1c;">-</span>
							<?php elseif (isset($ArrExe[$data->id]->$dayCheck) && $ArrExe[$data->id]->$dayCheck) : ?>
								<span class="d-block font-weight-bold" style="font-size: 12px; ">
									<?= isset($ArrUsers[$ArrExe[$data->id]->$dayCheck]) ? $ArrUsers[$ArrExe[$data->id]->$dayCheck] : '-'; ?>
								</span>
								<small class="d-block font-weight-bold" style="font-size: 10px; color: #333;">
									<?= isset($ArrExeDate[$data->id]->$dateCheck) ? date('d/m/Y H:i', strtotime($ArrExeDate[$data->id]->$dateCheck)) : ''; ?>
								</small>
							<?php else : ?>
								<span class="text-muted font-italic" style="font-size: 11px;">Belum dieksekusi</span>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
				</tr>

				<!-- Row 2: Checker Status (Y/N) -->
				<tr class="table-secondary">
					<th colspan="3" class="p-2 text-right align-middle font-weight-bold text-info" >
						<i class="fa fa-user-check mr-1 text-info" ></i> Verifikasi Checker (Konsistensi)
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
						<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : '' ?>" style="min-width: 200px; width: 200px; ">
							<?php if ($isOff) : ?>
								<span class="font-weight-bold" style="color: #b71c1c;">Libur</span>
							<?php else : ?>
								<div class="d-flex flex-column align-items-center">
									<div class="btn-group btn-group-toggle btn-group-sm mb-1" data-toggle="buttons">
										<label class="btn border btn-sm py-1 px-3 <?= ($currentCheckVal == 'yes') ? 'active bg-success text-white border-success' : ''; ?>" title="Konsisten / Sesuai">
											<input type="radio" name="checker_values[<?= $i; ?>]" class="checker-radio-val" data-day="<?= $i; ?>" value="yes" autocomplete="off" <?= ($currentCheckVal == 'yes') ? 'checked' : ''; ?>>
											<strong>YES</strong>
										</label>
										<label class="btn border btn-sm py-1 px-3 <?= ($currentCheckVal == 'no') ? 'active bg-danger text-white border-danger' : ''; ?>" title="Tidak Konsisten / Temuan">
											<input type="radio" name="checker_values[<?= $i; ?>]" class="checker-radio-val" data-day="<?= $i; ?>" value="no" autocomplete="off" <?= ($currentCheckVal == 'no') ? 'checked' : ''; ?>>
											<strong>NO</strong>
										</label>
									</div>
									<?php if ($hasChecked) : ?>
										<small class="d-block font-weight-bold mt-1" style="font-size: 10px; ">
											By: <?= isset($ArrUsers[$ArrCheck[$data->id]->$dayCheck]) ? $ArrUsers[$ArrCheck[$data->id]->$dayCheck] : ''; ?>
										</small>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
				</tr>

				<!-- Row 3: Daily Checker Note (Textarea 3 Baris & Aktif Hanya Saat NO) -->
				<tr>
					<th colspan="3" class="p-2 text-right align-middle font-weight-bold" style="">
						<i class="fa fa-comment-dots mr-1 text-warning"></i> Catatan Harian Checker
						<small class="d-block text-danger font-weight-normal" style="font-size: 10px;">*Wajib diisi jika pilih NO</small>
					</th>
					<?php
					for ($i = 1; $i <= $count; $i++) :
						$dayNoteCol = 'day' . $i;
						$checkValCol = 'check' . $i;
						$isOff = false;
						if ($data->frequency_execution == 3 && !empty($data->periode)) {
							$tanggalkolom = date("Y-m", strtotime($data->periode)) . "-" . sprintf('%02d', $i);
							$dayNum = (int)date('w', strtotime($tanggalkolom));
							if ($dayNum === 0 || $dayNum === 6 || (isset($ArrHolidays) && isset($ArrHolidays[$tanggalkolom]))) {
								$isOff = true;
							}
						}
						$currentNote = isset($ArrCheckNote[$data->id]->$dayNoteCol) ? $ArrCheckNote[$data->id]->$dayNoteCol : '';
						$currentCheckVal = (isset($ArrCheckVal[$data->id]->$checkValCol)) ? strtolower($ArrCheckVal[$data->id]->$checkValCol) : '';
						$isNoteDisabled = ($currentCheckVal !== 'no');
					?>
						<td class="text-center align-middle p-2 <?= $isOff ? 'table-danger' : '' ?>" style="min-width: 200px; width: 200px; ">
							<?php if ($isOff) : ?>
								<span style="color: #b71c1c;">-</span>
							<?php else : ?>
								<textarea name="checker_notes[<?= $i; ?>]" id="checker_note_<?= $i; ?>" class="form-control form-control-sm checker-note-input" rows="3" placeholder="Input catatan jika No..." style="font-size: 11px; resize: vertical;" <?= $isNoteDisabled ? 'disabled' : ''; ?>><?= htmlspecialchars($currentNote); ?></textarea>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
				</tr>
			</tfoot>
		</table>
	</div>

	<!-- Section Kesimpulan & Saran Checker -->
	<div class="card card-custom border mt-4 mb-3 bg-white shadow-none">
		<div class="card-header py-3 bg-light border-bottom">
			<h6 class="card-title font-weight-bolder m-0" style="">
				<i class="fa fa-award mr-2 text-warning" style=""></i> Evaluasi, Kesimpulan & Saran Checker
			</h6>
		</div>
		<div class="card-body p-4" style="">
			<div class="row">
				<div class="col-md-4 mb-3">
					<label class="font-weight-bold" style="">Status Verifikasi Keseluruhan <span class="text-danger">*</span></label>
					<select name="checker_status" id="checker_status" class="form-control font-weight-bold" style="">
						<option value="Approved" <?= ($data->checker_status == 'Approved' || empty($data->checker_status)) ? 'selected' : ''; ?>>Approved (Konsisten / Sesuai)</option>
						<option value="Needs Improvement" <?= ($data->checker_status == 'Needs Improvement') ? 'selected' : ''; ?>>Needs Improvement (Perlu Perbaikan)</option>
						<!-- <option value="Revision" <?= ($data->checker_status == 'Revision') ? 'selected' : ''; ?>>Revision (Ditolak / Perlu Pengulangan)</option> -->
					</select>
					<small class="form-text font-weight-bold" style="color: #444;">Tentukan status hasil akhir pengecekan periode ini.</small>
				</div>
				<div class="col-md-8 mb-3">
					<label class="font-weight-bold" style="">Catatan Kesimpulan / Rekomendasi Saran Checker</label>
					<textarea name="checker_summary_note" id="checker_summary_note" class="form-control" rows="3" placeholder="Tuliskan evaluasi, saran perbaikan, atau catatan kesimpulan untuk tim eksekutor..." style=" font-size: 12px;"><?= $data->checker_summary_note; ?></textarea>
					<?php if (!empty($data->checked_at) && !empty($data->checked_by)) : ?>
						<small class="form-text font-weight-bold mt-1" style="">
							<i class="fa fa-check-circle mr-1" style=""></i> Terakhir diverifikasi oleh <strong><?= isset($ArrUsers[$data->checked_by]) ? $ArrUsers[$data->checked_by] : 'User ID ' . $data->checked_by; ?></strong> pada <?= date('d M Y H:i', strtotime($data->checked_at)); ?>
						</small>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div class="d-flex justify-content-center align-items-center mt-3">
		<!-- <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Tutup</button> -->
		<button type="button" class="btn btn-primary font-weight-bold px-4" id="btn-save-checker">
			<i class="fa fa-save mr-1"></i> Simpan Verifikasi Checker
		</button>
	</div>
</form>

<script>
	$(document).ready(function() {
		// Handler saat memilih Yes/No pada verifikasi
		$(document).on('change', '.checker-radio-val', function() {
			const $radio = $(this);
			const day = $radio.data('day');
			const val = $radio.val();
			const $note = $('#checker_note_' + day);
			const $parentTd = $radio.closest('td');
			const existingText = $note.val().trim();

			if (val === 'yes') {
				if (existingText !== '') {
					// Konfirmasi sebelum menghapus catatan yang sudah diketik
					Swal.fire({
						title: 'Hapus Catatan Harian?',
						text: 'Catatan pada kolom tanggal ini sudah terisi. Mengubah status ke "YES" akan menghapus catatan tersebut.',
						icon: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Ya, Hapus & Pilih YES',
						cancelButtonText: 'Batal'
					}).then((result) => {
						if (result.isConfirmed) {
							// Hapus teks catatan dan disable textarea
							$note.val('').prop('disabled', true).removeClass('is-invalid');
							$parentTd.find('label').removeClass('active bg-success bg-danger text-white border-success border-danger');
							$radio.closest('label').addClass('active bg-success text-white border-success');
						} else {
							// Kembalikan pilihan ke NO
							const $noRadio = $parentTd.find('.checker-radio-val[value="no"]');
							$noRadio.prop('checked', true);
							$parentTd.find('label').removeClass('active bg-success bg-danger text-white border-success border-danger');
							$noRadio.closest('label').addClass('active bg-danger text-white border-danger');
							$note.prop('disabled', false);
						}
					});
				} else {
					$note.val('').prop('disabled', true).removeClass('is-invalid');
					$parentTd.find('label').removeClass('active bg-success bg-danger text-white border-success border-danger');
					$radio.closest('label').addClass('active bg-success text-white border-success');
				}
			} else if (val === 'no') {
				$parentTd.find('label').removeClass('active bg-success bg-danger text-white border-success border-danger');
				$radio.closest('label').addClass('active bg-danger text-white border-danger');
				$note.prop('disabled', false).focus();
			}
		});

		// Submit Verifikasi
		$('#btn-save-checker').on('click', function(e) {
			e.preventDefault();
			const $btn = $(this);

			// Validasi jika ada yang pilih NO tapi catatannya belum diisi
			let missingNote = false;
			$('.checker-radio-val:checked').each(function() {
				if ($(this).val() === 'no') {
					const day = $(this).data('day');
					const noteVal = $('#checker_note_' + day).val().trim();
					if (noteVal === '') {
						missingNote = true;
						$('#checker_note_' + day).addClass('is-invalid').focus();
					} else {
						$('#checker_note_' + day).removeClass('is-invalid');
					}
				}
			});

			if (missingNote) {
				Swal.fire('Perhatian!', 'Mohon isi Catatan Harian pada tanggal yang dipilih "NO" sebagai keterangan ketidakkonsistenan / temuan.', 'warning');
				return false;
			}

			// Enable all textareas temporarily so disabled ones can be processed properly or use serialize
			$('.checker-note-input').prop('disabled', false);
			const formData = $('#form-checking-sheet').serialize();

			// Re-disable non-NO textareas
			$('.checker-radio-val:checked').each(function() {
				const day = $(this).data('day');
				if ($(this).val() !== 'no') {
					$('#checker_note_' + day).prop('disabled', true);
				}
			});

			Swal.fire({
				title: 'Simpan Verifikasi Checker?',
				text: 'Hasil verifikasi status harian, catatan, dan kesimpulan akan disimpan.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...');

					$.ajax({
						url: siteurl + 'process_checksheets/save_checker',
						type: 'POST',
						data: formData,
						dataType: 'json',
						success: function(res) {
							$btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Verifikasi Checker');
							if (res.status == 1) {
								Swal.fire({
									title: 'Berhasil!',
									text: res.msg,
									icon: 'success',
									timer: 2000,
									showConfirmButton: false
								}).then(() => {
									$('#modalId').modal('hide');
									location.reload();
								});
							} else {
								Swal.fire('Gagal!', res.msg, 'error');
							}
						},
						error: function() {
							$btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Verifikasi Checker');
							Swal.fire('Error', 'Terjadi kesalahan sistem saat menyimpan verifikasi.', 'error');
						}
					});
				}
			});
		});
	});
</script>