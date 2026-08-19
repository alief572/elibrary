<style>
	#form-car textarea {
		overflow: hidden;
		resize: none;
	}
	#form-car select:disabled,
	#form-car input[readonly] {
		color: #333 !important;
		opacity: 1 !important;
		-webkit-text-fill-color: #333 !important;
	}
	#form-car .select2-container--disabled .select2-selection__rendered {
		color: #333 !important;
	}
	#form-car textarea[readonly] {
		color: #333 !important;
		background-color: #f5f5f5 !important;
	}
</style>
<?php $is_edit = (isset($data->id) && $data->id); ?>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex align-items-center">
					<h2 class="m-0"><i class="fa fa-check-double mr-2"></i>Corrective Action Internal - Form</h2>
					<a href="<?= base_url('corrective_internal'); ?>" class="btn btn-secondary"><i class="fa fa-arrow-left mr-1"></i>Back</a>
				</div>

				<div class="card-body">
					<form id="form-car" enctype="multipart/form-data">
						<input type="hidden" name="car_id" value="<?= isset($data->id) ? $data->id : ''; ?>">
						<?php if ($is_edit) : ?>
						<input type="hidden" name="department_pembuat_id" value="<?= $data->department_pembuat_id; ?>">
						<input type="hidden" name="pic_pembuat_id" value="<?= $data->pic_pembuat_id; ?>">
						<input type="hidden" name="pic_car_id" value="<?= $data->pic_car_id; ?>">
						<input type="hidden" name="department_pic_car_id" value="<?= $data->department_pic_car_id; ?>">
						<input type="hidden" name="tanggal_car" value="<?= $data->tanggal_car; ?>">
						<input type="hidden" name="deadline_car" value="<?= $data->deadline_car; ?>">
						<?php endif; ?>

						<!-- Header Fields -->
						<div class="row mb-5">
							<div class="col-md-4">
								<div class="form-group">
									<label class="font-weight-bold text-dark">Department Pembuat <span class="text-danger">*</span></label>
									<?php if ($is_edit) : ?>
										<input type="text" class="form-control" value="<?php foreach ($depts as $d) { if ($d->id == $data->department_pembuat_id) echo $d->name; } ?>" readonly>
									<?php else : ?>
									<select name="department_pembuat_id" id="department_pembuat_id" class="form-control select2" required>
										<option value="">Pilih Department Pembuat</option>
										<?php foreach ($depts as $dept) : ?>
											<option value="<?= $dept->id; ?>"><?= $dept->name; ?></option>
										<?php endforeach; ?>
									</select>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="font-weight-bold text-dark">PIC Pembuat <span class="text-danger">*</span></label>
									<?php if ($is_edit) : ?>
										<input type="text" class="form-control" value="<?php foreach ($users as $u) { if ($u->id_user == $data->pic_pembuat_id) echo $u->full_name; } ?>" readonly>
									<?php else : ?>
									<select name="pic_pembuat_id" id="pic_pembuat_id" class="form-control select2" required>
										<option value="">Pilih PIC Pembuat</option>
										<?php foreach ($users as $usr) : ?>
											<option value="<?= $usr->id_user; ?>"><?= $usr->full_name; ?></option>
										<?php endforeach; ?>
									</select>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="font-weight-bold text-dark">Date CAR <span class="text-danger">*</span></label>
									<?php if ($is_edit) : ?>
										<input type="text" class="form-control" value="<?= date('d-m-Y', strtotime($data->tanggal_car)); ?>" readonly>
									<?php else : ?>
										<input type="date" name="tanggal_car" id="tanggal_car" class="form-control" required>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="row mb-5">
							<div class="col-md-4">
								<div class="form-group">
									<label class="font-weight-bold text-dark">Department PIC CAR <span class="text-danger">*</span></label>
									<?php if ($is_edit) : ?>
										<input type="text" class="form-control" value="<?php foreach ($depts as $d) { if ($d->id == $data->department_pic_car_id) echo $d->name; } ?>" readonly>
									<?php else : ?>
									<select name="department_pic_car_id" id="department_pic_car_id" class="form-control select2" required>
										<option value="">Pilih Department</option>
										<?php foreach ($depts as $dept) : ?>
											<option value="<?= $dept->id; ?>"><?= $dept->name; ?></option>
										<?php endforeach; ?>
									</select>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="font-weight-bold text-dark">PIC CAR <span class="text-danger">*</span></label>
									<?php if ($is_edit) : ?>
										<input type="text" class="form-control" value="<?php foreach ($users as $u) { if ($u->id_user == $data->pic_car_id) echo $u->full_name; } ?>" readonly>
									<?php else : ?>
									<select name="pic_car_id" id="pic_car_id" class="form-control select2" required>
										<option value="">Pilih PIC</option>
										<?php foreach ($users as $usr) : ?>
											<option value="<?= $usr->id_user; ?>"><?= $usr->full_name; ?></option>
										<?php endforeach; ?>
									</select>
									<?php endif; ?>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="font-weight-bold text-dark">Deadline CAR</label>
									<?php if ($is_edit) : ?>
										<input type="text" class="form-control bg-light" value="<?= date('d-m-Y', strtotime($data->deadline_car)); ?>" readonly>
									<?php else : ?>
										<input type="date" name="deadline_car" id="deadline_car" class="form-control bg-light" readonly required>
									<?php endif; ?>
									<small class="text-muted">Note: Otomatis dari sistem, max 2 hari dari Date CAR</small>
								</div>
							</div>
						</div>
						<hr class="my-5">

						<!-- CAR Items Container -->
						<div id="car-items-container">
							<?php if (isset($details) && $details) : ?>
								<?php foreach ($details as $idx => $dtl) : ?>
								<div class="car-item border rounded p-4 mb-4">
									<?php if ($idx > 0) : ?>
									<div class="d-flex justify-content-between align-items-center mb-4">
										<h5 class="font-weight-bold mb-0">Corrective Action Internal #<?= $idx + 1; ?></h5>
										<button type="button" class="btn btn-sm btn-danger btn-remove-car"><i class="fa fa-trash"></i></button>
									</div>
									<?php else : ?>
									<h5 class="font-weight-bold mb-4">Corrective Action Internal #1</h5>
									<?php endif; ?>

									<div class="form-group">
										<label class="font-weight-bold text-dark">Deskripsi Masalah <span class="text-danger">*</span></label>
										<textarea name="items[<?= $idx; ?>][deskripsi_masalah]" class="form-control bg-light" rows="4" maxlength="2000" placeholder="Jelaskan masalah yang ditemukan..." readonly required><?= $dtl->deskripsi_masalah; ?></textarea>
										<small class="text-muted float-right"><span class="char-count"><?= strlen($dtl->deskripsi_masalah); ?></span>/2000</small>
									</div>
									<div class="form-group mt-4">
										<label class="font-weight-bold text-dark">Fakta <span class="text-danger">*</span></label>
										<textarea name="items[<?= $idx; ?>][fakta]" class="form-control" rows="4" maxlength="2000" placeholder="1. ...&#10;2. ...&#10;3. ..." required><?= $dtl->fakta; ?></textarea>
										<small class="text-muted float-right"><span class="char-count"><?= strlen($dtl->fakta); ?></span>/2000</small>
									</div>
									<div class="form-group mt-4">
										<label class="font-weight-bold text-dark">Kesimpulan Penyebab <span class="text-danger">*</span></label>
										<textarea name="items[<?= $idx; ?>][kesimpulan_penyebab]" class="form-control" rows="4" maxlength="2000" placeholder="Akar penyebab masalah..." required><?= $dtl->kesimpulan_penyebab; ?></textarea>
										<small class="text-muted float-right"><span class="char-count"><?= strlen($dtl->kesimpulan_penyebab); ?></span>/2000</small>
									</div>
									<div class="form-group mt-4">
										<label class="font-weight-bold text-dark">Correction <span class="text-danger">*</span></label>
										<textarea name="items[<?= $idx; ?>][correction]" class="form-control" rows="4" maxlength="2000" placeholder="Tindakan koreksi segera..." required><?= $dtl->correction; ?></textarea>
										<small class="text-muted float-right"><span class="char-count"><?= strlen($dtl->correction); ?></span>/2000</small>
									</div>
									<div class="form-group mt-4">
										<label class="font-weight-bold text-dark">Corrective Action <span class="text-danger">*</span></label>
										<textarea name="items[<?= $idx; ?>][corrective_action]" class="form-control" rows="4" maxlength="2000" placeholder="Tindakan perbaikan agar tidak terulang..." required><?= $dtl->corrective_action; ?></textarea>
										<small class="text-muted float-right"><span class="char-count"><?= strlen($dtl->corrective_action); ?></span>/2000</small>
									</div>
									<div class="form-group mt-4">
										<label class="font-weight-bold text-dark">Upload Evidence</label>
										<?php if ($dtl->evidence_original_name) : ?>
											<p class="mb-1"><i class="fa fa-file mr-1"></i><?= $dtl->evidence_original_name; ?></p>
										<?php endif; ?>
										<div class="d-flex align-items-center mt-2">
											<button type="button" class="btn btn-outline-primary btn-sm mr-2 btn-camera" data-target="evidence_<?= $idx; ?>">
												<i class="fa fa-camera mr-1"></i> Ambil Foto
											</button>
											<button type="button" class="btn btn-outline-secondary btn-sm btn-file-pick" data-target="evidence_<?= $idx; ?>">
												<i class="fa fa-folder-open mr-1"></i> Pilih File
											</button>
											<span class="ml-3 file-name-display" id="fname_evidence_<?= $idx; ?>"></span>
										</div>
										<input type="file" name="evidence_<?= $idx; ?>" id="evidence_<?= $idx; ?>" class="d-none" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
																				<small class="text-muted d-block mt-1">Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX (Max 10MB)</small>
									</div>
								</div>
								<?php endforeach; ?>
							<?php else : ?>
							<div class="car-item border rounded p-4 mb-4">
								<h5 class="font-weight-bold mb-4">Corrective Action Internal #1</h5>
								<div class="form-group">
									<label class="font-weight-bold text-dark">Deskripsi Masalah <span class="text-danger">*</span></label>
									<textarea name="items[0][deskripsi_masalah]" class="form-control" rows="4" maxlength="2000" placeholder="Jelaskan masalah yang ditemukan..." required></textarea>
									<small class="text-muted float-right"><span class="char-count">0</span>/2000</small>
								</div>
							</div>
							<?php endif; ?>
						</div>

						<?php if ($is_edit && isset($data->status) && $data->status == 'reject' && $data->alasan_reject) : ?>
						<div class="alert alert-danger mt-4">
							<strong><i class="fa fa-times-circle mr-1"></i>Alasan Reject:</strong><br>
							<?= $data->alasan_reject; ?>
						</div>
						<?php endif; ?>

						<!-- Action Buttons -->
						<div class="text-center mt-5">
							<button type="button" class="btn btn-success mr-2" id="btn-save">
								<i class="fa fa-save mr-1"></i> Save
							</button>
							<?php if (isset($details) && $details) : ?>
								<button type="button" class="btn btn-warning" id="btn-ajukan">
									<i class="fa fa-paper-plane mr-1"></i> Ajukan
								</button>
							<?php endif; ?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><i class="fa fa-camera mr-2"></i>Ambil Foto</h5>
				<button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
			</div>
			<div class="modal-body text-center">
				<video id="cameraPreview" style="width:100%;max-height:400px;border-radius:8px;background:#000;" autoplay playsinline></video>
				<canvas id="cameraCanvas" class="d-none"></canvas>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-success btn-lg" id="btn-capture">
					<i class="fa fa-camera mr-1"></i> Ambil Foto
				</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('.select2').select2();

	// Auto-set Deadline CAR = Date CAR + 2 days
	$('#tanggal_car').on('change', function() {
		const dateVal = $(this).val();
		if (dateVal) {
			const date = new Date(dateVal);
			date.setDate(date.getDate() + 2);
			const year = date.getFullYear();
			const month = String(date.getMonth() + 1).padStart(2, '0');
			const day = String(date.getDate()).padStart(2, '0');
			$('#deadline_car').val(year + '-' + month + '-' + day);
		} else {
			$('#deadline_car').val('');
		}
	});

	// Camera button - open webcam modal
	$(document).on('click', '.btn-camera', function() {
		const target = $(this).data('target');
		$('#cameraModal').data('target', target);
		$('#cameraModal').modal('show');
		startCamera();
	});

	function startCamera() {
		navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
		.then(function(stream) {
			const video = document.getElementById('cameraPreview');
			video.srcObject = stream;
			video.play();
			$('#cameraModal').data('stream', stream);
		})
		.catch(function(err) {
			Swal.fire({ title: 'Error', text: 'Tidak bisa mengakses kamera: ' + err.message, icon: 'error' });
			$('#cameraModal').modal('hide');
		});
	}

	function stopCamera() {
		const stream = $('#cameraModal').data('stream');
		if (stream) {
			stream.getTracks().forEach(track => track.stop());
		}
	}

	$('#btn-capture').on('click', function() {
		const video = document.getElementById('cameraPreview');
		const canvas = document.getElementById('cameraCanvas');
		canvas.width = video.videoWidth;
		canvas.height = video.videoHeight;
		canvas.getContext('2d').drawImage(video, 0, 0);

		canvas.toBlob(function(blob) {
			const target = $('#cameraModal').data('target');
			const file = new File([blob], 'foto_' + Date.now() + '.jpg', { type: 'image/jpeg' });
			const dt = new DataTransfer();
			dt.items.add(file);
			document.getElementById(target).files = dt.files;
			$('#fname_' + target).text(file.name);
			stopCamera();
			$('#cameraModal').modal('hide');
		}, 'image/jpeg', 0.8);
	});

	$('#cameraModal').on('hidden.bs.modal', function() {
		stopCamera();
	});

	// File pick button - open file browser
	$(document).on('click', '.btn-file-pick', function() {
		const target = $(this).data('target');
		$('#' + target).click();
	});

	// When file picked, show name
	$(document).on('change', '[id^="evidence_"]', function() {
		const id = $(this).attr('id');
		if (this.files[0]) {
			$('#fname_' + id).text(this.files[0].name);
		}
	});

	// Auto-resize textarea
	function autoResize(el) {
		el.style.height = 'auto';
		el.style.height = el.scrollHeight + 'px';
	}
	// Apply on page load for existing textareas with content
	$('#form-car textarea').each(function() {
		if (this.value) autoResize(this);
	});
	// Apply on input
	$(document).on('input', '#form-car textarea', function() {
		autoResize(this);
	});

	// Add new CAR item
	$('#btn-add-car').on('click', function() {
		const currentCount = $('#car-items-container .car-item').length;
		const newNum = currentCount + 1;
		const newItem = `
		<div class="car-item border rounded p-4 mb-4">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h5 class="font-weight-bold mb-0">Corrective Action Internal #${newNum}</h5>
				<button type="button" class="btn btn-sm btn-danger btn-remove-car"><i class="fa fa-trash"></i></button>
			</div>
			<div class="form-group">
				<label class="font-weight-bold text-dark">Deskripsi Masalah <span class="text-danger">*</span></label>
				<textarea name="items[${newNum - 1}][deskripsi_masalah]" class="form-control" rows="4" maxlength="2000" placeholder="Jelaskan masalah yang ditemukan..." required></textarea>
				<small class="text-muted float-right"><span class="char-count">0</span>/2000</small>
			</div>
			<div class="form-group mt-4">
				<label class="font-weight-bold text-dark">Fakta <span class="text-danger">*</span></label>
				<textarea name="items[${newNum - 1}][fakta]" class="form-control" rows="4" maxlength="2000" placeholder="1. ...&#10;2. ...&#10;3. ..." required></textarea>
				<small class="text-muted float-right"><span class="char-count">0</span>/2000</small>
			</div>
			<div class="form-group mt-4">
				<label class="font-weight-bold text-dark">Kesimpulan Penyebab <span class="text-danger">*</span></label>
				<textarea name="items[${newNum - 1}][kesimpulan_penyebab]" class="form-control" rows="4" maxlength="2000" placeholder="Akar penyebab masalah..." required></textarea>
				<small class="text-muted float-right"><span class="char-count">0</span>/2000</small>
			</div>
			<div class="form-group mt-4">
				<label class="font-weight-bold text-dark">Correction <span class="text-danger">*</span></label>
				<textarea name="items[${newNum - 1}][correction]" class="form-control" rows="4" maxlength="2000" placeholder="Tindakan koreksi segera..." required></textarea>
				<small class="text-muted float-right"><span class="char-count">0</span>/2000</small>
			</div>
			<div class="form-group mt-4">
				<label class="font-weight-bold text-dark">Corrective Action <span class="text-danger">*</span></label>
				<textarea name="items[${newNum - 1}][corrective_action]" class="form-control" rows="4" maxlength="2000" placeholder="Tindakan perbaikan agar tidak terulang..." required></textarea>
				<small class="text-muted float-right"><span class="char-count">0</span>/2000</small>
			</div>
			<div class="form-group mt-4">
				<label class="font-weight-bold text-dark">Upload Evidence</label>
				<input type="file" name="evidence_${newNum - 1}" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx">
				<small class="text-muted">Format: PDF, JPG, JPEG, PNG, DOC, DOCX, XLS, XLSX (Max 10MB)</small>
			</div>
		</div>`;
		$('#car-items-container').append(newItem);
	});

	// Remove CAR item
	$(document).on('click', '.btn-remove-car', function() {
		$(this).closest('.car-item').remove();
		$('#car-items-container .car-item').each(function(index) {
			$(this).find('h5').first().text('Corrective Action Internal #' + (index + 1));
		});
	});

	// Character counter
	$(document).on('input', 'textarea[maxlength]', function() {
		const count = $(this).val().length;
		$(this).closest('.form-group').find('.char-count').text(count);
	});

	// Submit form
	let isSubmitting = false;
	function submitForm(action) {
		if (isSubmitting) return;

		// Validasi hanya untuk ajukan, save langsung submit
		if (action == 'submit') {
			<?php if (!$is_edit) : ?>
			const requiredSelects = ['#department_pembuat_id', '#pic_pembuat_id', '#tanggal_car', '#deadline_car', '#pic_car_id', '#department_pic_car_id'];
			for (let sel of requiredSelects) {
				if (!$(sel).val()) {
					Swal.fire({ title: 'Warning!', text: 'Semua field header harus diisi.', icon: 'warning' });
					$(sel).focus();
					return;
				}
			}
			<?php endif; ?>

			let hasEmpty = false;
			$('#car-items-container .car-item').each(function() {
				$(this).find('textarea[required]').each(function() {
					if (!$(this).val().trim()) {
						hasEmpty = true;
						$(this).addClass('is-invalid');
					} else {
						$(this).removeClass('is-invalid');
					}
				});
			});
			if (hasEmpty) {
				Swal.fire({ title: 'Warning!', text: 'Semua field yang bertanda * harus diisi.', icon: 'warning' });
				return;
			}
		}

		let formData = new FormData($('#form-car')[0]);
		formData.append('action', action);

		isSubmitting = true;
		$.ajax({
			url: siteurl + 'corrective_internal/save',
			data: formData,
			type: 'POST',
			dataType: 'JSON',
			processData: false,
			contentType: false,
			cache: false,
			beforeSend: function() {
				$('#btn-save, #btn-ajukan').attr('disabled', true).addClass('disabled');
			},
			complete: function() {
				isSubmitting = false;
				$('#btn-save, #btn-ajukan').attr('disabled', false).removeClass('disabled');
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire({ title: 'Success!', icon: 'success', text: result.msg, timer: 2000 })
					.then(function() { window.location.href = siteurl + 'corrective_internal'; });
				} else {
					Swal.fire({ title: 'Warning!', icon: 'warning', text: result.msg });
				}
			},
			error: function() {
				Swal.fire({ title: 'Error!', icon: 'error', text: 'Server error, please try again!' });
			}
		});
	}

	$('#btn-save').on('click', function() { submitForm('save'); });

	$('#btn-ajukan').on('click', function() {
		Swal.fire({
			title: 'Ajukan CAR?',
			text: 'Setelah diajukan, data tidak bisa diedit lagi. Lanjutkan?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, Ajukan'
		}).then((result) => {
			if (result.isConfirmed) { submitForm('submit'); }
		});
	});
});
</script>
