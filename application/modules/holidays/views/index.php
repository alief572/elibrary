<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<div class="mt-4 float-right d-flex flex-wrap align-items-center gap-2">
						<select id="filter-year" class="form-control form-control-sm mr-2" style="width: 140px;">
							<option value="all" <?= ($selected_year == 'all') ? 'selected' : ''; ?>>-- Semua Tahun --</option>
							<?php
							$currentY = date('Y');
							$allYears = [];
							if (isset($years)) {
								foreach ($years as $y) {
									$allYears[] = $y->y;
								}
							}
							for ($y = $currentY - 2; $y <= $currentY + 2; $y++) {
								if (!in_array($y, $allYears)) $allYears[] = $y;
							}
							rsort($allYears);
							foreach ($allYears as $yr) : ?>
								<option value="<?= $yr; ?>" <?= ($selected_year == $yr) ? 'selected' : ''; ?>>Tahun <?= $yr; ?></option>
							<?php endforeach; ?>
						</select>
						
						<a href="<?= base_url('holidays/download_template?year=' . ($selected_year != 'all' ? $selected_year : date('Y'))); ?>" class="btn btn-sm btn-outline-success mr-2" id="btn-download-template" title="Download Template Excel (.xlsx)">
							<i class="fa fa-file-excel mr-1"></i> Download Template Excel
						</a>

						<button type="button" class="btn btn-sm btn-success mr-2" id="btn-import-excel" title="Upload File Excel">
							<i class="fa fa-upload mr-1"></i> Import Excel
						</button>

						<button type="button" class="btn btn-sm btn-primary" id="add" title="Tambah Hari Libur Manual">
							<i class="fa fa-plus mr-1"></i> Tambah Libur
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="alert alert-custom alert-light-primary fade show mb-5" role="alert">
						<div class="alert-icon"><i class="flaticon-calendar-with-a-clock-time-tools text-primary"></i></div>
						<div class="alert-text">
							Daftar tanggal merah / hari libur nasional ini secara otomatis terintegrasi ke seluruh <strong>Checksheet</strong>. Anda dapat menginput satu per satu atau menggunakan fitur <strong>Import Excel</strong> untuk memasukkan seluruh daftar hari libur dalam 1 tahun sekaligus.
						</div>
					</div>

					<div class="table-responsive">
						<table id="table-holidays" class="table table-bordered table-sm table-hover datatable">
							<thead class="text-center table-light">
								<tr>
									<th width="5%">No.</th>
									<th width="15%">Tanggal</th>
									<th width="10%">Hari</th>
									<th>Nama Hari Libur</th>
									<th width="15%">Tipe</th>
									<th>Keterangan</th>
									<th width="12%">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$dayNamesIndo = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
								if (isset($data) && $data) :
									$n = 0;
									foreach ($data as $dt) : $n++;
										$dayNum = (int)date('w', strtotime($dt->holiday_date));
										$dayName = isset($dayNamesIndo[$dayNum]) ? $dayNamesIndo[$dayNum] : '';
								?>
										<tr>
											<td class="text-center"><?= $n; ?></td>
											<td class="text-center font-weight-bold text-danger">
												<i class="fa fa-calendar-alt text-danger mr-1"></i>
												<?= date('d M Y', strtotime($dt->holiday_date)); ?>
											</td>
											<td class="text-center font-weight-bolder text-dark"><?= $dayName; ?></td>
											<td class="font-weight-bold text-dark"><?= $dt->holiday_name; ?></td>
											<td class="text-center">
												<?php if ($dt->holiday_type == 'Nasional') : ?>
													<span class="badge badge-danger font-weight-bold">Libur Nasional</span>
												<?php elseif ($dt->holiday_type == 'Cuti Bersama') : ?>
													<span class="badge badge-warning font-weight-bold">Cuti Bersama</span>
												<?php else : ?>
													<span class="badge badge-info font-weight-bold"><?= $dt->holiday_type; ?></span>
												<?php endif; ?>
											</td>
											<td class="text-muted"><?= $dt->descriptions ?: '-'; ?></td>
											<td class="text-center">
												<button type="button" class="btn btn-sm btn-icon rounded-circle btn-warning edit" data-id="<?= $dt->id; ?>" title="Edit Data"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm btn-icon rounded-circle btn-danger delete" data-id="<?= $dt->id; ?>" data-name="<?= $dt->holiday_name; ?>" data-date="<?= $dt->holiday_date; ?>" title="Hapus Data"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
								<?php endforeach;
								endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Form Manual -->
<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalTitle">Tambah Hari Libur</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer justify-content-end">
				<button type="button" class="btn btn-primary" id="btn-save"><i class="fa fa-save"></i> Simpan</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal Import Excel -->
<div class="modal fade" id="modalImport" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold" id="modalImportLabel"><i class="fa fa-file-excel text-success mr-2"></i> Import Data Hari Libur via Excel</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-import-excel" enctype="multipart/form-data">
				<div class="modal-body">
					<div class="alert alert-light-success p-3 mb-4 rounded" role="alert">
						<strong><i class="fa fa-info-circle mr-1 text-success"></i> Petunjuk Import:</strong>
						<ol class="pl-4 mb-1 mt-1 text-muted" style="font-size: 12px;">
							<li>Klik tombol <strong>Download Template Excel</strong> di bawah ini.</li>
							<li>Buka file <code>.xlsx</code> tersebut di Microsoft Excel / Spreadsheet dan isi daftar hari libur.</li>
							<li>Upload kembali file Excel yang telah Anda lengkapi pada form di bawah.</li>
						</ol>
					</div>

					<div class="mb-3 text-center">
						<a href="<?= base_url('holidays/download_template?year=' . ($selected_year != 'all' ? $selected_year : date('Y'))); ?>" class="btn btn-sm btn-outline-success font-weight-bold">
							<i class="fa fa-file-excel mr-1"></i> Download Template Excel (.xlsx)
						</a>
					</div>

					<div class="form-group mb-0">
						<label class="font-weight-bolder text-dark" for="file_excel">Pilih File Excel <span class="text-danger">*</span></label>
						<div class="custom-file">
							<input type="file" name="file_excel" id="file_excel" class="custom-file-input" accept=".xlsx, .xls, .csv" required>
							<label class="custom-file-label text-truncate" for="file_excel">Pilih file .xlsx / .xls...</label>
						</div>
						<small class="form-text text-muted">Format file: <strong>.xlsx</strong> / <strong>.xls</strong> / <strong>.csv</strong></small>
					</div>
				</div>
				<div class="modal-footer justify-content-end">
					<button type="submit" class="btn btn-success" id="btn-submit-import">
						<i class="fa fa-upload mr-1"></i> Upload & Import
					</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#table-holidays').DataTable({
			ordering: true,
			order: [[1, 'asc']]
		});

		$('#filter-year').on('change', function() {
			const year = $(this).val();
			location.href = siteurl + 'holidays?year=' + year;
		});

		// Custom file input label update
		$('#file_excel').on('change', function() {
			let fileName = $(this).val().split('\\').pop();
			$(this).next('.custom-file-label').addClass("selected").html(fileName || 'Pilih file .xlsx / .xls / .csv...');
		});

		// Open Import Modal
		$(document).on('click', '#btn-import-excel', function() {
			$('#form-import-excel')[0].reset();
			$('#file_excel').next('.custom-file-label').removeClass("selected").html('Pilih file .xlsx / .xls / .csv...');
			$('#modalImport').modal('show');
		});

		// Handle Form Import Submit
		$('#form-import-excel').on('submit', function(e) {
			e.preventDefault();
			const fileInput = $('#file_excel')[0];
			if (!fileInput.files || fileInput.files.length === 0) {
				Swal.fire('Perhatian', 'Pilih file Excel atau CSV terlebih dahulu!', 'warning');
				return false;
			}

			const formData = new FormData(this);
			const $btn = $('#btn-submit-import');
			$btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Memproses...');

			$.ajax({
				url: siteurl + 'holidays/import_excel',
				type: 'POST',
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(res) {
					$btn.prop('disabled', false).html('<i class="fa fa-upload mr-1"></i> Upload & Import');
					if (res.status == 1) {
						$('#modalImport').modal('hide');
						Swal.fire({
							title: 'Berhasil!',
							text: res.msg,
							icon: 'success',
							confirmButtonText: 'OK'
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire('Gagal!', res.msg, 'error');
					}
				},
				error: function() {
					$btn.prop('disabled', false).html('<i class="fa fa-upload mr-1"></i> Upload & Import');
					Swal.fire('Error', 'Terjadi kesalahan saat mengupload atau memproses file Excel.', 'error');
				}
			});
		});

		// Add Manual Form
		$(document).on('click', '#add', function() {
			const url = siteurl + 'holidays/add';
			$('#modalTitle').html('<i class="fa fa-plus text-primary mr-2"></i> Tambah Hari Libur');
			$('#modalView').modal('show');
			$('.modal-body').load(url);
		});

		// Edit Manual Form
		$(document).on('click', '.edit', function() {
			const id = $(this).data('id');
			const url = siteurl + 'holidays/edit/' + id;
			$('#modalTitle').html('<i class="fa fa-edit text-warning mr-2"></i> Edit Hari Libur');
			$('#modalView').modal('show');
			$('.modal-body').load(url);
		});

		// Save Manual
		$(document).on('click', '#btn-save', function(e) {
			e.preventDefault();
			const holiday_date = $('#holiday_date').val();
			const holiday_name = $('#holiday_name').val();

			if (!holiday_date) {
				Swal.fire('Perhatian', 'Pilih tanggal hari libur!', 'warning');
				return false;
			}
			if (!holiday_name.trim()) {
				Swal.fire('Perhatian', 'Isi nama hari libur!', 'warning');
				return false;
			}

			const formData = $('#form-holiday').serialize();
			$.ajax({
				url: siteurl + 'holidays/save',
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(res) {
					if (res.status == 1) {
						Swal.fire({
							title: 'Berhasil!',
							text: res.msg,
							icon: 'success',
							timer: 1500,
							showConfirmButton: false
						}).then(() => {
							location.reload();
						});
					} else {
						Swal.fire('Gagal!', res.msg, 'error');
					}
				},
				error: function() {
					Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
				}
			});
		});

		// Delete
		$(document).on('click', '.delete', function() {
			const id = $(this).data('id');
			const name = $(this).data('name');
			const date = $(this).data('date');

			Swal.fire({
				title: 'Hapus Hari Libur?',
				text: "Apakah Anda yakin ingin menghapus '" + name + "' (" + date + ")?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#d33',
				cancelButtonColor: '#3085d6',
				confirmButtonText: 'Ya, Hapus!',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + 'holidays/delete/' + id,
						type: 'POST',
						dataType: 'json',
						success: function(res) {
							if (res.status == 1) {
								Swal.fire({
									title: 'Dihapus!',
									text: res.msg,
									icon: 'success',
									timer: 1500,
									showConfirmButton: false
								}).then(() => {
									location.reload();
								});
							} else {
								Swal.fire('Gagal!', res.msg, 'error');
							}
						},
						error: function() {
							Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
						}
					});
				}
			});
		});
	});
</script>
