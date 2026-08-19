<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex align-items-center">
					<h2 class="m-0"><i class="fa fa-check-double mr-2"></i><?= $title; ?></h2>
					<a href="<?= base_url('approval_corrective_internal'); ?>" class="btn btn-secondary"><i class="fa fa-arrow-left mr-1"></i>Back</a>
				</div>

				<div class="card-body">
					<!-- Header Info -->
					<div class="row mb-4">
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">Department Pembuat</label>
							<p class="font-size-lg"><?= isset($dept_pembuat->department_name) ? $dept_pembuat->department_name : '-'; ?></p>
						</div>
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">PIC Pembuat</label>
							<p class="font-size-lg"><?= isset($pic_pembuat->full_name) ? $pic_pembuat->full_name : '-'; ?></p>
						</div>
					</div>
					<div class="row mb-4">
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">Date CAR</label>
							<p class="font-size-lg"><?= date('d-m-Y', strtotime($data->tanggal_car)); ?></p>
						</div>
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">Deadline CAR</label>
							<p class="font-size-lg"><?= date('d-m-Y', strtotime($data->deadline_car)); ?></p>
						</div>
					</div>
					<div class="row mb-4">
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">PIC CAR</label>
							<p class="font-size-lg"><?= isset($pic_car->full_name) ? $pic_car->full_name : '-'; ?></p>
						</div>
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">Department PIC CAR</label>
							<p class="font-size-lg"><?= isset($dept_pic->department_name) ? $dept_pic->department_name : '-'; ?></p>
						</div>
					</div>

					<hr class="my-5">

					<!-- CAR Details -->
					<?php if ($details) : foreach ($details as $idx => $dtl) : ?>
					<div class="card border rounded p-4 mb-4">
						<div class="card-body">
							<h5 class="font-weight-bold mb-3">Corrective Action Internal #<?= $idx + 1; ?></h5>

							<p class="mb-3"><strong>Deskripsi Masalah:</strong> <?= $dtl->deskripsi_masalah; ?></p>

							<div class="form-group mt-4">
								<label class="font-weight-bold text-dark">Fakta</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->fakta; ?></div>
							</div>
							<div class="form-group mt-4">
								<label class="font-weight-bold text-dark">Kesimpulan Penyebab</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->kesimpulan_penyebab; ?></div>
							</div>
							<div class="form-group mt-4">
								<label class="font-weight-bold text-dark">Correction</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->correction; ?></div>
							</div>
							<div class="form-group mt-4">
								<label class="font-weight-bold text-dark">Corrective Action</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->corrective_action; ?></div>
							</div>
							<?php if ($dtl->evidence_original_name) : ?>
							<div class="form-group mt-4">
								<label class="font-weight-bold text-dark">Evidence</label>
								<div>
									<a href="<?= base_url('directory/CAR/' . $data->company_id . '/' . $data->id . '/' . $dtl->evidence_file); ?>" class="btn btn-outline-primary btn-sm" target="_blank">
										<i class="fa fa-download mr-1"></i><?= $dtl->evidence_original_name; ?>
									</a>
								</div>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; endif; ?>

					<hr class="my-5">

					<!-- Keputusan Approval -->
					<?php if ($data->status == 'waiting_approval') : ?>
					<div class="card border rounded p-4">
						<div class="card-body">
							<h5 class="font-weight-bold mb-4"><i class="fa fa-edit mr-2"></i>Keputusan Approval</h5>

							<div class="form-group">
								<label class="font-weight-bold text-dark">Alasan Reject</label>
								<textarea name="alasan_reject" id="alasan_reject" class="form-control" rows="4" maxlength="2000" placeholder="Isi alasan penolakan jika ingin menolak..."></textarea>
								<small class="text-muted float-right"><span id="reject-char-count">0</span>/2000 karakter</small>
							</div>

							<div class="mt-5">
								<button type="button" class="btn btn-success mr-2" id="btn-approve">
									<i class="fa fa-check mr-1"></i> Approve
								</button>
								<button type="button" class="btn btn-danger mr-2" id="btn-reject">
									<i class="fa fa-times mr-1"></i> Reject
								</button>
								<a href="<?= base_url('approval_corrective_internal'); ?>" class="btn btn-secondary">
									<i class="fa fa-arrow-left mr-1"></i> Back
								</a>
							</div>
						</div>
					</div>
					<?php elseif ($data->status == 'reject' && $data->alasan_reject) : ?>
					<div class="alert alert-danger">
						<strong><i class="fa fa-times-circle mr-1"></i>Alasan Reject:</strong><br>
						<?= $data->alasan_reject; ?>
					</div>
					<?php elseif ($data->status == 'closed') : ?>
					<div class="alert alert-success">
						<strong><i class="fa fa-check-circle mr-1"></i>Status: Closed / Approved</strong>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#alasan_reject').on('input', function() {
		$('#reject-char-count').text($(this).val().length);
	});

	$('#btn-approve').on('click', function() {
		Swal.fire({
			title: 'Approve?',
			text: 'Apakah Anda yakin ingin meng-approve CAR ini?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#28a745',
			confirmButtonText: 'Ya, Approve'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: siteurl + 'approval_corrective_internal/do_approve',
					data: { id: '<?= $data->id; ?>', action: 'approve' },
					type: 'POST',
					dataType: 'JSON',
					success: function(res) {
						if (res.status == 1) {
							Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 })
							.then(() => { window.location.href = siteurl + 'approval_corrective_internal'; });
						} else {
							Swal.fire({ title: 'Error!', icon: 'error', text: res.msg });
						}
					}
				});
			}
		});
	});

	$('#btn-reject').on('click', function() {
		const alasan = $('#alasan_reject').val();
		if (!alasan || alasan.trim() == '') {
			Swal.fire({ title: 'Warning!', text: 'Alasan reject harus diisi!', icon: 'warning' });
			$('#alasan_reject').addClass('is-invalid');
			return;
		}
		Swal.fire({
			title: 'Reject?',
			text: 'Apakah Anda yakin ingin me-reject CAR ini?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#dc3545',
			confirmButtonText: 'Ya, Reject'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: siteurl + 'approval_corrective_internal/do_approve',
					data: { id: '<?= $data->id; ?>', action: 'reject', alasan_reject: alasan },
					type: 'POST',
					dataType: 'JSON',
					success: function(res) {
						if (res.status == 1) {
							Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 })
							.then(() => { window.location.href = siteurl + 'approval_corrective_internal'; });
						} else {
							Swal.fire({ title: 'Error!', icon: 'error', text: res.msg });
						}
					}
				});
			}
		});
	});
});
</script>
