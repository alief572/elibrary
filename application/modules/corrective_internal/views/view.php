<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex align-items-center">
					<h2 class="m-0"><i class="fa fa-check-double mr-2"></i>Corrective Action Internal - Detail</h2>
					<a href="<?= base_url('corrective_internal'); ?>" class="btn btn-secondary"><i class="fa fa-arrow-left mr-1"></i>Back</a>
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
					<div class="row mb-4">
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">Status</label>
							<p>
								<?php
								switch ($data->status) {
									case 'draft': echo '<span class="label label-primary label-inline">Open</span>'; break;
									case 'waiting_approval': echo '<span class="label label-warning label-inline">Waiting Approval</span>'; break;
									case 'approved': echo '<span class="label label-info label-inline">Approved</span>'; break;
									case 'closed': echo '<span class="label label-success label-inline">Closed</span>'; break;
									case 'reject': echo '<span class="label label-danger label-inline">Reject</span>'; break;
								}
								?>
							</p>
						</div>
						<div class="col-md-6">
							<label class="font-weight-bold text-dark mb-0">Nomor CAR</label>
							<p class="font-size-lg"><?= $data->nomor_car ?: '-'; ?></p>
						</div>
					</div>

					<hr class="my-5">

					<!-- Detail Items -->
					<?php if ($details) : foreach ($details as $idx => $dtl) : ?>
					<div class="card border rounded p-4 mb-4">
						<div class="card-body">
							<h5 class="font-weight-bold mb-3">Corrective Action Internal #<?= $idx + 1; ?></h5>

							<div class="form-group">
								<label class="font-weight-bold text-dark">Deskripsi Masalah</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->deskripsi_masalah; ?></div>
							</div>
							<div class="form-group mt-3">
								<label class="font-weight-bold text-dark">Fakta</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->fakta; ?></div>
							</div>
							<div class="form-group mt-3">
								<label class="font-weight-bold text-dark">Kesimpulan Penyebab</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->kesimpulan_penyebab; ?></div>
							</div>
							<div class="form-group mt-3">
								<label class="font-weight-bold text-dark">Correction</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->correction; ?></div>
							</div>
							<div class="form-group mt-3">
								<label class="font-weight-bold text-dark">Corrective Action</label>
								<div class="form-control bg-light" style="min-height:40px;height:auto;white-space:pre-wrap;"><?= $dtl->corrective_action; ?></div>
							</div>
							<?php if ($dtl->evidence_original_name) : ?>
							<div class="form-group mt-3">
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

					<?php if ($data->status == 'reject' && $data->alasan_reject) : ?>
					<div class="alert alert-danger mt-4">
						<strong><i class="fa fa-times-circle mr-1"></i>Alasan Reject:</strong><br>
						<?= $data->alasan_reject; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
