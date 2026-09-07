<?php
$exec = $data->frequency_execution;
$dayNamesIndo = [
	0 => 'Minggu',
	1 => 'Senin',
	2 => 'Selasa',
	3 => 'Rabu',
	4 => 'Kamis',
	5 => 'Jumat',
	6 => 'Sabtu'
];

$todayDateStr = date('Y-m-d');
$todayDayNum = (int)date('w');
$todayIsHoliday = (isset($ArrHolidays) && isset($ArrHolidays[$todayDateStr])) ? $ArrHolidays[$todayDateStr] : false;
$todayIsWeekend = ($exec == 3 && ($todayDayNum === 0 || $todayDayNum === 6));
$todayIsOff = ($exec == 3 && ($todayIsWeekend || $todayIsHoliday));

// Menentukan kolom hari aktif (active day column)
$activeCol = 1;
if ($fChecking[$data->frequency_checking] == 'Daily') {
	$activeCol = (int)date('d');
} elseif ($fChecking[$data->frequency_checking] == 'Weekly') {
	$activeCol = (int)$weekOfMonth;
} elseif ($fChecking[$data->frequency_checking] == 'Monthly') {
	$activeCol = (int)date('m');
}
?>

<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">

			<!-- Header Card -->
			<div class="card card-custom border shadow-sm mb-4 bg-white">
				<!-- 1. Top Navbar / Title Bar -->
				<div class="card-header border-bottom py-3 px-3 px-md-4">
					<div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
						<div class="d-flex align-items-center">
							<a href="<?= base_url($this->uri->segment(1) . '/?p=' . $data->process_id . '&sub=' . $dataSub2->id_sub . '&sub2=' . $data->sub_id . '&checksheet=' . $data->dir_id); ?>" class="btn btn-light btn-sm btn-icon mr-3 shadow-xs" title="Kembali">
								<i class="fa fa-arrow-left text-dark"></i>
							</a>
							<div>
								<h3 class="card-title font-weight-bolder text-dark m-0" style="font-size: 1.15rem;">
									Eksekusi Checksheet
								</h3>
							</div>
						</div>

						<div>
							<span class="badge badge-light-primary font-weight-bolder px-3 py-2" style="font-size: 12px;">
								<i class="fa fa-calendar-check mr-1 text-primary"></i> <?= $dayNamesIndo[$todayDayNum]; ?>, <?= date('d M Y'); ?>
							</span>
						</div>
					</div>
				</div>

				<!-- 2. Checksheet Metadata Strip -->
				<div class="py-3 px-3 px-md-4 border-bottom bg-white">
					<div class="row align-items-center">
						<div class="col-12 col-md-8 mb-2 mb-md-0">
							<h4 class="font-weight-bolder text-dark mb-1 d-flex align-items-center" style="font-size: 1.1rem;">
								<i class="fa fa-clipboard-list text-primary mr-2"></i> <?= $data->checksheet_name; ?>
							</h4>
							<div class="d-flex flex-wrap align-items-center mt-1">
								<span class="badge badge-light font-weight-bold text-dark border mr-2 mb-1" style="font-size: 11px;">
									<i class="fa fa-sync-alt text-primary mr-1"></i> Frekuensi: <?= $fExecution[$data->frequency_execution]; ?>
								</span>
								<span class="badge badge-light font-weight-bold text-dark border mr-2 mb-1" style="font-size: 11px;">
									<i class="fa fa-calendar-alt text-muted mr-1"></i> Periode: <?= (!empty($data->periode) && strtotime($data->periode)) ? date('F Y', strtotime($data->periode)) : '-'; ?>
								</span>
								<span class="badge badge-light font-weight-bold text-dark border mb-1" style="font-size: 11px;">
									<i class="fa fa-clock text-info mr-1"></i> Checking: <?= $fChecking[$data->frequency_checking]; ?>
								</span>
							</div>
						</div>

						<div class="col-12 col-md-4 text-md-right text-left">
							<div class="d-inline-flex align-items-center bg-light rounded px-3 py-2 border">
								<div class="text-left mr-3">
									<small class="text-muted d-block font-weight-bold" style="font-size: 10px; line-height: 1;">JADWAL HARI AKTIF</small>
									<strong class="text-dark" style="font-size: 13px;">
										<?= ($data->frequency_execution == 5 && is_array($name_col)) ? $name_col[$activeCol] : 'Hari Ke-' . $activeCol; ?>
									</strong>
								</div>
								<span class="badge badge-success font-weight-bold px-2 py-1" style="font-size: 10px;">AKTIF</span>
							</div>
						</div>
					</div>
				</div>

				<?php if ($todayIsOff && $fChecking[$data->frequency_checking] == 'Daily') : ?>
					<div class="card-body p-4">
						<div class="alert alert-danger mb-0 text-center p-4">
							<i class="fa fa-calendar-times fa-3x text-danger mb-3 d-block"></i>
							<h4 class="font-weight-bolder text-danger">HARI INI LIBUR (TIDAK ADA EKSEKUSI)</h4>
							<p class="mb-0 text-dark font-weight-bold">
								Hari ini (<strong><?= $dayNamesIndo[$todayDayNum]; ?>, <?= date('d M Y'); ?></strong>) adalah hari libur 
								<strong><?= $todayIsHoliday ? "Nasional (" . htmlspecialchars($todayIsHoliday) . ")" : "akhir pekan (Sabtu/Minggu)"; ?></strong>.<br>
								Checksheet dinonaktifkan dan tidak dapat diinput pada hari libur.
							</p>
						</div>
					</div>
				<?php else : ?>

					<!-- 3. Quick Actions & Live Progress Bar -->
					<div class="card-body py-3 px-3 px-md-4 bg-light border-bottom">
						<div class="row align-items-center">
							<div class="col-12 col-md-6 mb-2 mb-md-0">
								<div class="d-flex align-items-center justify-content-between mb-1">
									<span class="font-weight-bold text-dark" style="font-size: 12px;">
										<i class="fa fa-tasks text-primary mr-1"></i> Progress Pengisian:
									</span>
									<span class="font-weight-bolder" id="progress-text" style="font-size: 12px; color: #000;">
										0 / <?= count($details); ?> Item (0%)
									</span>
								</div>
								<div class="progress" style="height: 10px; border-radius: 5px; background-color: #e0e0e0;">
									<div class="progress-bar progress-bar-striped progress-bar-animated bg-warning" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
								</div>
							</div>

							<div class="col-12 col-md-6 text-md-right text-left mt-2 mt-md-0">
								<button type="button" class="btn btn-success btn-sm font-weight-bold mr-2 mb-1" id="btn-set-all-yes" title="Set semua item menjadi YES jika semua kondisi normal">
									<i class="fa fa-check-double mr-1"></i> Set Semua YES (Normal)
								</button>
								<button type="button" class="btn btn-outline-secondary btn-sm font-weight-bold mb-1" id="btn-reset-all" title="Reset pilihan">
									<i class="fa fa-undo mr-1"></i> Reset
								</button>
							</div>
						</div>
					</div>

					<div class="card-body p-4">
						<form id="form-checksheet" enctype="multipart/form-data">
							<input type="hidden" name="id" value="<?= $data->id; ?>">

							<!-- List Checksheet Items (Modern Card Layout) -->
							<div class="checksheet-items-container">
								<?php 
								$n = 0;
								if ($details) foreach ($details as $it) : 
									$n++;
									$i = $activeCol;
									$nn = "n" . $i;
									$Nn = "note" . $i;
									$NBukti = "bukti_" . $i;
									$currentVal = isset($it->$nn) ? $it->$nn : '';
									$currentNote = isset($ArrNote[$it->id]->$Nn) ? $ArrNote[$it->id]->$Nn : '';
									$currentBukti = isset($ArrNote[$it->id]->$NBukti) ? $ArrNote[$it->id]->$NBukti : '';
								?>
									<input type="hidden" name="detail[<?= $n . "_" . $i; ?>][id]" value="<?= $it->id; ?>">
									<input type="hidden" name="detail[<?= $n . "_" . $i; ?>][field]" value="<?= $i; ?>">

									<div class="card border mb-3 checksheet-item-card shadow-xs <?= ($currentVal == 'yes') ? 'border-success' : (($currentVal == 'no') ? 'border-danger' : 'border-secondary'); ?>" id="card_item_<?= $n; ?>" style="transition: all 0.2s ease;">
										<div class="card-body p-3 p-md-4">
											<div class="row align-items-center">
												
												<!-- Item Name & Standard Info -->
												<div class="col-lg-7 col-md-6 mb-3 mb-md-0">
													<div class="d-flex align-items-start">
														<span class="badge badge-dark font-weight-bolder mr-3 mt-1" style="font-size: 13px; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;">
															<?= $n; ?>
														</span>
														<div class="flex-grow-1">
															<h5 class="font-weight-bolder text-dark mb-1" style="font-size: 14px;">
																<?= $it->item_name; ?>
															</h5>
															<div class="d-flex flex-wrap align-items-center text-muted" style="font-size: 12px;">
																<span class="mr-2">
																	<strong>Standar:</strong> <?= $it->standard_check; ?>
																</span>
																<?php if (!empty($it->upload_standard_check) && (file_exists($it->upload_standard_check) || file_exists(FCPATH . $it->upload_standard_check))) : 
																	$fileExt = strtolower(pathinfo($it->upload_standard_check, PATHINFO_EXTENSION));
																	$isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);
																?>
																	<?php if ($isImage) : ?>
																		<button type="button" class="btn btn-xs btn-outline-info font-weight-bold btn-preview-standard ml-2 shadow-xs" 
																			data-image-url="<?= base_url($it->upload_standard_check); ?>" 
																			data-item-name="<?= htmlspecialchars($it->item_name); ?>" 
																			data-standard="<?= htmlspecialchars($it->standard_check); ?>"
																			title="Preview Gambar Standar">
																			<i class="fa fa-image mr-1 text-info"></i> Preview Gambar Standar
																		</button>
																	<?php else : 
																		$docIcon = 'fa fa-file';
																		$docColor = '#6c757d';
																		if ($fileExt == 'pdf') {
																			$docIcon = 'fa fa-file-pdf';
																			$docColor = '#e5252a';
																		} elseif (in_array($fileExt, ['xls', 'xlsx', 'csv'])) {
																			$docIcon = 'fa fa-file-excel';
																			$docColor = '#217346';
																		} elseif (in_array($fileExt, ['doc', 'docx'])) {
																			$docIcon = 'fa fa-file-word';
																			$docColor = '#2b579a';
																		} elseif (in_array($fileExt, ['ppt', 'pptx'])) {
																			$docIcon = 'fa fa-file-powerpoint';
																			$docColor = '#d24726';
																		}
																	?>
																		<a href="<?= base_url($it->upload_standard_check); ?>" target="_blank" class="badge badge-light text-dark font-weight-bold ml-2 border shadow-xs" style="font-size: 11px;">
																			<i class="<?= $docIcon; ?> mr-1" style="color: <?= $docColor; ?>;"></i> Dokumen Standar
																		</a>
																	<?php endif; ?>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</div>

												<!-- Input Controls (Big Segmented Touch Buttons) -->
												<div class="col-lg-5 col-md-6">
													<?php if ($it->check_type == 'boolean') : ?>
														<div class="d-flex justify-content-md-end justify-content-start align-items-center">
															<div class="btn-group btn-group-toggle w-100 w-md-auto" data-toggle="buttons">
																<label class="btn btn-outline-success font-weight-bolder px-4 py-2 <?= ($currentVal == 'yes') ? 'active bg-success text-white border-success' : ''; ?>" style="font-size: 13px; min-width: 110px;">
																	<input type="radio" name="detail[<?= $n . "_" . $i; ?>][n<?= $i; ?>]" class="check-radio-item check-yes" data-row="<?= $n . $i; ?>" data-item-index="<?= $n; ?>" value="yes" autocomplete="off" <?= ($currentVal == 'yes') ? 'checked' : ''; ?>>
																	<i class="fa fa-check mr-1"></i> YES / OK
																</label>
																<label class="btn btn-outline-danger font-weight-bolder px-4 py-2 <?= ($currentVal == 'no') ? 'active bg-danger text-white border-danger' : ''; ?>" style="font-size: 13px; min-width: 110px;">
																	<input type="radio" name="detail[<?= $n . "_" . $i; ?>][n<?= $i; ?>]" class="check-radio-item check-no" data-row="<?= $n . $i; ?>" data-item-index="<?= $n; ?>" value="no" autocomplete="off" <?= ($currentVal == 'no') ? 'checked' : ''; ?>>
																	<i class="fa fa-times mr-1"></i> NO / NG
																</label>
															</div>
														</div>
													<?php else : ?>
														<input type="text" name="detail[<?= $n . "_" . $i; ?>][n<?= $i; ?>]" class="form-control check-text-item font-weight-bold" data-item-index="<?= $n; ?>" placeholder="Input hasil pengukuran / nilai..." value="<?= htmlspecialchars($currentVal); ?>">
													<?php endif; ?>
												</div>
											</div>

											<!-- Conditional Expansion for NO (Alasan Temuan & Upload Bukti) -->
											<?php if ($it->check_type == 'boolean') : ?>
												<div class="no-reason-container mt-3 pt-3 border-top <?= ($currentVal == 'no') ? '' : 'd-none'; ?>" id="reason_container_<?= $n . $i; ?>">
													<div class="row bg-light-danger rounded p-3 m-0 border border-danger">
														<div class="col-md-7 mb-2 mb-md-0">
															<label class="font-weight-bold text-danger" style="font-size: 12px;">
																<i class="fa fa-exclamation-circle text-danger mr-1"></i> Keterangan Temuan / Alasan NO <span class="text-danger">*</span>
															</label>
															<textarea name="detail[<?= $n . "_" . $i; ?>][note<?= $i; ?>]" id="note<?= $n . $i; ?>" class="form-control form-control-sm text-dark item-note-input" rows="2" placeholder="Tuliskan temuan atau kendala pengecekan..." style="font-size: 12px;" <?= ($currentVal == 'no') ? '' : 'disabled'; ?>><?= htmlspecialchars($currentNote); ?></textarea>
														</div>
														<div class="col-md-5">
															<label class="font-weight-bold text-dark" style="font-size: 12px;">
																<i class="fa fa-camera text-primary mr-1"></i> Upload Foto Bukti (Opsional)
															</label>
															<input type="file" name="bukti<?= $n . $i; ?>" class="form-control-file" accept="image/*,.pdf">
															<?php if (!empty($currentBukti) && file_exists($currentBukti)) : ?>
																<div class="mt-1">
																	<a href="<?= base_url($currentBukti); ?>" target="_blank" class="badge badge-primary font-weight-bold">
																		<i class="fa fa-image mr-1"></i> Lihat Bukti Terupload
																	</a>
																</div>
															<?php endif; ?>
														</div>
													</div>
												</div>
											<?php endif; ?>

										</div>
									</div>
								<?php endforeach; ?>
							</div>

							<!-- Action Buttons -->
							<div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
								<a href="<?= base_url($this->uri->segment(1) . '/?p=' . $data->process_id . '&sub=' . $dataSub2->id_sub . '&sub2=' . $data->sub_id . '&checksheet=' . $data->dir_id); ?>" class="btn btn-secondary font-weight-bold">
									<i class="fa fa-reply mr-1"></i> Batal / Kembali
								</a>
								<button type="submit" class="btn btn-primary font-weight-bolder px-5 py-3" id="save" style="font-size: 14px;">
									<i class="fa fa-save mr-1"></i> Simpan Eksekusi Checksheet
								</button>
							</div>

						</form>
					</div>

				<?php endif; ?>

			</div>
		</div>
	</div>
</div>

<!-- Modal Preview Gambar Standar -->
<div class="modal fade" id="modalPreviewStandard" tabindex="-1" role="dialog" aria-labelledby="modalPreviewStandardLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content border-0 shadow-lg">
			<div class="modal-header bg-primary py-3 px-4">
				<h5 class="modal-title text-white font-weight-bolder d-flex align-items-center" id="modalPreviewStandardLabel">
					<i class="fa fa-image mr-2 text-white"></i> Preview Standar Pengecekan
				</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-4 bg-light">
				<!-- Card Info Item & Standar -->
				<div class="card border border-info mb-3 bg-white shadow-xs">
					<div class="card-body py-2 px-3">
						<div class="row">
							<div class="col-12 col-md-6 mb-1 mb-md-0">
								<small class="text-muted font-weight-bold d-block">Item Pengecekan:</small>
								<strong class="text-dark" id="modal-preview-item-name" style="font-size: 13px;">-</strong>
							</div>
							<div class="col-12 col-md-6">
								<small class="text-muted font-weight-bold d-block">Standar Acuan:</small>
								<strong class="text-info" id="modal-preview-standard-text" style="font-size: 13px;">-</strong>
							</div>
						</div>
					</div>
				</div>

				<!-- Gambar Container -->
				<div class="d-flex justify-content-center align-items-center bg-white p-3 rounded border shadow-xs" style="min-height: 250px;">
					<img id="modal-preview-img" src="" alt="Gambar Standar" class="img-fluid rounded" style="max-height: 60vh; max-width: 100%; object-fit: contain; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
				</div>
			</div>
			<div class="modal-footer py-2 px-4 bg-white d-flex justify-content-between">
				<a href="#" id="modal-preview-direct-link" target="_blank" class="btn btn-sm btn-light-primary font-weight-bold">
					<i class="fa fa-external-link-alt mr-1"></i> Buka Ukuran Penuh
				</a>
				<button type="button" class="btn btn-sm btn-secondary font-weight-bold px-4" data-dismiss="modal">
					<i class="fa fa-times mr-1"></i> Tutup
				</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		const totalItems = <?= count($details); ?>;

		// Fungsi untuk mengupdate progress bar secara live
		function updateProgress() {
			let filledCount = 0;
			$('.checksheet-item-card').each(function(index) {
				const itemIdx = index + 1;
				const isBoolean = $(this).find('.check-radio-item').length > 0;
				let isFilled = false;

				if (isBoolean) {
					isFilled = $(this).find('.check-radio-item:checked').length > 0;
				} else {
					isFilled = $(this).find('.check-text-item').val().trim() !== '';
				}

				if (isFilled) {
					filledCount++;
				}
			});

			const percent = Math.round((filledCount / totalItems) * 100);
			$('#progress-text').text(filledCount + ' / ' + totalItems + ' Item (' + percent + '%)');
			$('#progress-bar').css('width', percent + '%');

			if (percent === 100) {
				$('#progress-bar').removeClass('bg-warning bg-info').addClass('bg-success');
				$('#progress-text').css('color', '#2e7d32');
			} else if (percent > 50) {
				$('#progress-bar').removeClass('bg-warning bg-success').addClass('bg-info');
				$('#progress-text').css('color', '#000');
			} else {
				$('#progress-bar').removeClass('bg-info bg-success').addClass('bg-warning');
				$('#progress-text').css('color', '#000');
			}
		}

		// Update pertama kali saat halaman dimuat
		updateProgress();

		// Handler saat memilih YES
		$(document).on('change', '.check-yes', function() {
			const row = $(this).data('row');
			const itemIndex = $(this).data('item-index');
			const $card = $('#card_item_' + itemIndex);

			$card.removeClass('border-secondary border-danger').addClass('border-success');
			$('#reason_container_' + row).addClass('d-none');
			$('#note' + row).val('').prop('disabled', true);

			$(this).closest('.btn-group').find('label').removeClass('active bg-danger text-white border-danger');
			$(this).closest('label').addClass('active bg-success text-white border-success');

			updateProgress();
		});

		// Handler saat memilih NO
		$(document).on('change', '.check-no', function() {
			const row = $(this).data('row');
			const itemIndex = $(this).data('item-index');
			const $card = $('#card_item_' + itemIndex);

			$card.removeClass('border-secondary border-success').addClass('border-danger');
			$('#reason_container_' + row).removeClass('d-none');
			$('#note' + row).prop('disabled', false).focus();

			$(this).closest('.btn-group').find('label').removeClass('active bg-success text-white border-success');
			$(this).closest('label').addClass('active bg-danger text-white border-danger');

			updateProgress();
		});

		// Handler input nilai teks
		$(document).on('input', '.check-text-item', function() {
			const itemIndex = $(this).data('item-index');
			const $card = $('#card_item_' + itemIndex);
			if ($(this).val().trim() !== '') {
				$card.removeClass('border-secondary border-danger').addClass('border-success');
			} else {
				$card.removeClass('border-success border-danger').addClass('border-secondary');
			}
			updateProgress();
		});

		// Shortcut: Set Semua YES
		$('#btn-set-all-yes').on('click', function() {
			$('.check-yes').each(function() {
				$(this).prop('checked', true).trigger('change');
			});
			Swal.fire({
				toast: true,
				position: 'top-end',
				icon: 'success',
				title: 'Seluruh item telah diatur ke YES (Normal)',
				showConfirmButton: false,
				timer: 2000
			});
		});

		// Shortcut: Reset Semua
		$('#btn-reset-all').on('click', function() {
			Swal.fire({
				title: 'Reset Pilihan?',
				text: 'Semua isian pada form ini akan dikosongkan.',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Reset',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$('.check-radio-item').prop('checked', false);
					$('.btn-group-toggle label').removeClass('active bg-success bg-danger text-white border-success border-danger');
					$('.no-reason-container').addClass('d-none');
					$('.item-note-input').val('').prop('disabled', true);
					$('.check-text-item').val('');
					$('.checksheet-item-card').removeClass('border-success border-danger').addClass('border-secondary');
					updateProgress();
				}
			});
		});

		// Submit Form
		$(document).on('submit', '#form-checksheet', function(e) {
			e.preventDefault();

			// Validasi kelengkapan
			let unanswered = 0;
			let emptyNotes = 0;

			$('.checksheet-item-card').each(function(index) {
				const itemIdx = index + 1;
				const isBoolean = $(this).find('.check-radio-item').length > 0;

				if (isBoolean) {
					const $checked = $(this).find('.check-radio-item:checked');
					if ($checked.length === 0) {
						unanswered++;
						$(this).removeClass('border-secondary border-success').addClass('border-warning');
					} else if ($checked.val() === 'no') {
						const noteVal = $(this).find('.item-note-input').val().trim();
						if (noteVal === '') {
							emptyNotes++;
							$(this).find('.item-note-input').addClass('is-invalid');
						} else {
							$(this).find('.item-note-input').removeClass('is-invalid');
						}
					}
				} else {
					if ($(this).find('.check-text-item').val().trim() === '') {
						unanswered++;
					}
				}
			});

			if (unanswered > 0) {
				Swal.fire({
					title: 'Belum Lengkap!',
					text: 'Terdapat ' + unanswered + ' item yang belum diisi. Mohon lengkapi seluruh item sebelum menyimpan.',
					icon: 'warning'
				});
				return false;
			}

			if (emptyNotes > 0) {
				Swal.fire({
					title: 'Catatan Temuan Wajib Diisi!',
					text: 'Terdapat item yang dipilih NO tetapi belum memiliki keterangan temuan.',
					icon: 'warning'
				});
				return false;
			}

			const formdata = new FormData($('#form-checksheet')[0]);
			const $btnSave = $('#save');

			Swal.fire({
				title: 'Simpan Eksekusi Checksheet?',
				text: 'Seluruh hasil pengecekan hari ini akan disimpan ke sistem.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya, Simpan!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$btnSave.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...');

					$.ajax({
						url: siteurl + active_controller + 'save_process_checksheet',
						type: 'POST',
						data: formdata,
						dataType: 'JSON',
						contentType: false,
						processData: false,
						cache: false,
						success: function(res) {
							$btnSave.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Eksekusi Checksheet');
							if (res.status == 1) {
								Swal.fire({
									title: "Berhasil!",
									text: res.msg,
									icon: "success",
									timer: 2500,
									showConfirmButton: false
								}).then(function() {
									window.location.href = siteurl + active_controller + '?p=' + <?= $data->process_id; ?> + '&sub=' + <?= $dataSub2->id_sub ?> + '&sub2=' + <?= $data->sub_id; ?> + '&checksheet=' + <?= $data->dir_id; ?>;
								});
							} else {
								Swal.fire('Gagal!', res.msg, 'warning');
							}
						},
						error: function() {
							$btnSave.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Eksekusi Checksheet');
							Swal.fire('Error', 'Terjadi kesalahan sistem / koneksi server.', 'error');
						}
					});
				}
			});
		});
		// Handler Preview Gambar Standar dalam Modal
		$(document).on('click', '.btn-preview-standard', function(e) {
			e.preventDefault();
			const imgUrl = $(this).data('image-url');
			const itemName = $(this).data('item-name');
			const standard = $(this).data('standard');

			$('#modal-preview-item-name').text(itemName || '-');
			$('#modal-preview-standard-text').text(standard || '-');
			$('#modal-preview-img').attr('src', imgUrl);
			$('#modal-preview-direct-link').attr('href', imgUrl);

			$('#modalPreviewStandard').modal('show');
		});
	});
</script>