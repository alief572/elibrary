<style>
	.nav-custom {
		border-bottom: 1px solid #ebedf2;
		padding-bottom: 5px;
		display: flex;
		flex-wrap: nowrap !important;
		overflow-x: auto !important;
		-webkit-overflow-scrolling: touch;
		white-space: nowrap;
	}

	.nav-custom::-webkit-scrollbar {
		height: 4px;
	}

	.nav-custom::-webkit-scrollbar-thumb {
		background-color: #cbd5e1;
		border-radius: 4px;
	}

	.nav-custom .nav-item {
		flex-shrink: 0;
	}

	.nav-custom .nav-link {
		color: #7e8299;
		font-weight: 600;
		font-size: 13px;
		border: none;
		border-radius: 6px;
		padding: 8px 14px;
		margin-right: 6px;
		background: transparent;
		transition: all 0.2s ease;
		display: inline-block;
	}

	.nav-custom .nav-link:hover {
		color: #1bc5bd;
		background-color: #f3f6f9;
	}

	.nav-custom .nav-link.active {
		color: #1bc5bd !important;
		background-color: #e8f9f5 !important;
	}

	.nav-custom .nav-link i {
		margin-right: 5px;
		font-size: 13px;
	}

	@media (max-width: 575.98px) {
		.nav-custom .nav-link {
			font-size: 12px;
			padding: 6px 10px;
			margin-right: 4px;
		}
		.modal-footer {
			flex-direction: column-reverse;
			gap: 8px;
		}
		.modal-footer > div {
			width: 100%;
			text-align: center;
		}
		.modal-footer .btn {
			width: 100%;
			margin-bottom: 4px;
		}
	}
</style>

<?php
// Fallback if $file is passed as $data
if (!isset($file) && isset($data)) {
	$file = $data;
}
?>

<div class="d-flex flex-column h-100 overflow-hidden">
	<!-- FIXED MODAL HEADER (TAB NAV BAR) -->
	<div class="flex-shrink-0 py-2 px-3 border-bottom d-flex justify-content-between align-items-center bg-white" style="gap: 10px;">
		<div class="overflow-hidden flex-grow-1">
			<ul class="nav nav-custom" id="procedureViewTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="tab-data-procedure-link" data-toggle="tab" href="#tab-data-procedure" role="tab" aria-controls="tab-data-procedure" aria-selected="true">
						<i class="fa fa-list-ul"></i> Data Procedure
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="tab-history-revision-link" data-toggle="tab" href="#tab-history-revision" role="tab" aria-controls="tab-history-revision" aria-selected="false">
						<i class="fa fa-history"></i> History Revision
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="tab-data-approval-link" data-toggle="tab" href="#tab-data-approval" role="tab" aria-controls="tab-data-approval" aria-selected="false">
						<i class="fa fa-list-alt"></i> Data Approval
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="tab-preview-file-link" data-toggle="tab" href="#tab-preview-file" role="tab" aria-controls="tab-preview-file" aria-selected="false">
						<i class="fa fa-file-alt"></i> Preview File
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="tab-activity-log-link" data-toggle="tab" href="#tab-activity-log" role="tab" aria-controls="tab-activity-log" aria-selected="false">
						<i class="fa fa-stream"></i> Activity Log
					</a>
				</li>
			</ul>
		</div>
		<button type="button" class="close flex-shrink-0" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<!-- SCROLLABLE MODAL BODY (TAB CONTENT ONLY) -->
	<div class="modal-body flex-grow-1 overflow-auto p-2 p-md-3">
		<div class="tab-content">
			<!-- TAB 1: DATA PROCEDURE (HTML VIEW) -->
			<div class="tab-pane fade show active" id="tab-data-procedure" role="tabpanel" aria-labelledby="tab-data-procedure-link">
				<?php if ($file) : ?>
					<div class="container-fluid p-0">
						<!-- Header Document -->
						<div class="table-responsive mb-3">
							<table class="table table-bordered mb-0">
								<tr>
									<td rowspan="3" width="150" class="text-center align-middle">
										<img src="<?= base_url('assets/logo.png'); ?>" width="90" alt="Logo" onerror="this.style.display='none'">
										<h6 class="mt-2 font-weight-bold text-muted small"><?= isset($company_name) ? $company_name : ''; ?></h6>
									</td>
									<td rowspan="3" class="text-center align-middle">
										<h4 class="font-weight-bolder text-dark mb-0"><?= strtoupper($file->name); ?></h4>
									</td>
									<td width="120" class="font-weight-bold small">Nomor</td>
									<td class="small"><?= $file->nomor; ?></td>
								</tr>
								<tr>
									<td class="font-weight-bold small">Tgl. Terbit</td>
									<td class="small"><?= ($file->approved_at) ? date("d M Y", strtotime($file->approved_at)) : '~'; ?></td>
								</tr>
								<tr>
									<td class="font-weight-bold small">Revisi</td>
									<td class="small"><?= (isset($file->revision) && $file->revision >= 0) ? "Rev. " . $file->revision : '~'; ?></td>
								</tr>
							</table>
						</div>

						<!-- Define & Scope -->
						<div class="table-responsive mb-3">
							<table class="table table-bordered mb-0">
								<tr>
									<td class="py-3">
										<h6 class="font-weight-bold text-primary mb-2">Tujuan</h6>
										<div><?= ($file->object) ? $file->object : '-'; ?></div>
									</td>
								</tr>
								<tr>
									<td class="py-3">
										<h6 class="font-weight-bold text-primary mb-2">Ruang Lingkup</h6>
										<div><?= ($file->scope) ? $file->scope : '-'; ?></div>
									</td>
								</tr>
								<tr>
									<td class="py-3">
										<h6 class="font-weight-bold text-primary mb-2">Definisi</h6>
										<div><?= ($file->define) ? $file->define : '-'; ?></div>
									</td>
								</tr>
								<tr>
									<td class="py-3">
										<h6 class="font-weight-bold text-primary mb-2">Performa Indikator</h6>
										<div><?= ($file->performance) ? $file->performance : '-'; ?></div>
									</td>
								</tr>
							</table>
						</div>

						<!-- SIPOCOR -->
						<div class="table-responsive mb-3">
							<table class="table table-bordered mb-0">
								<thead>
									<tr class="bg-light">
										<th colspan="2">
											<h6 class="font-weight-bold mb-0">SIPOCOR</h6>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td width="50%">
											<label class="font-weight-bold text-dark small">1. Supplier</label>
											<div><?= ($file->supplier) ? $file->supplier : '-'; ?></div>
										</td>
										<td width="50%">
											<label class="font-weight-bold text-dark small">2. Input</label>
											<div><?= ($file->input) ? $file->input : '-'; ?></div>
										</td>
									</tr>
									<tr>
										<td>
											<label class="font-weight-bold text-dark small">3. Proses</label>
											<div><?= ($file->process) ? $file->process : '-'; ?></div>
										</td>
										<td>
											<label class="font-weight-bold text-dark small">4. Output</label>
											<div><?= ($file->output) ? $file->output : '-'; ?></div>
										</td>
									</tr>
									<tr>
										<td>
											<label class="font-weight-bold text-dark small">5. Customer</label>
											<div><?= ($file->customer) ? $file->customer : '-'; ?></div>
										</td>
										<td>
											<label class="font-weight-bold text-dark small">6. Objective</label>
											<div><?= ($file->objective) ? $file->objective : '-'; ?></div>
										</td>
									</tr>
									<tr>
										<td>
											<label class="font-weight-bold text-dark small">7. Risk</label>
											<div><?= ($file->risk) ? $file->risk : '-'; ?></div>
										</td>
										<td>
											<label class="font-weight-bold text-dark small">8. Mitigation</label>
											<div><?= ($file->mitigation) ? $file->mitigation : '-'; ?></div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<!-- Flow Image & File -->
						<?php if ($file->image_flow_1 || $file->image_flow_2 || $file->image_flow_3 || $file->flow_file) : ?>
							<div class="table-responsive mb-3">
								<table class="table table-bordered mb-0">
									<thead>
										<tr class="bg-light">
											<th>
												<h6 class="font-weight-bold mb-0">Flow Image & File</h6>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="d-flex justify-content-start align-items-center flex-wrap">
													<?php if ($file->image_flow_1) : ?>
														<div class="border rounded p-2 mr-3 mb-2 text-center" style="width:160px;">
															<img src="<?= base_url("directory/FLOW_IMG/$file->company_id/$file->image_flow_1"); ?>" class="img-fluid rounded mb-2" style="max-height:120px;">
															<a href="<?= base_url("directory/FLOW_IMG/$file->company_id/$file->image_flow_1"); ?>" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fa fa-search"></i> View Image</a>
														</div>
													<?php endif; ?>
													<?php if ($file->image_flow_2) : ?>
														<div class="border rounded p-2 mr-3 mb-2 text-center" style="width:160px;">
															<img src="<?= base_url("directory/FLOW_IMG/$file->company_id/$file->image_flow_2"); ?>" class="img-fluid rounded mb-2" style="max-height:120px;">
															<a href="<?= base_url("directory/FLOW_IMG/$file->company_id/$file->image_flow_2"); ?>" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fa fa-search"></i> View Image</a>
														</div>
													<?php endif; ?>
													<?php if ($file->image_flow_3) : ?>
														<div class="border rounded p-2 mr-3 mb-2 text-center" style="width:160px;">
															<img src="<?= base_url("directory/FLOW_IMG/$file->company_id/$file->image_flow_3"); ?>" class="img-fluid rounded mb-2" style="max-height:120px;">
															<a href="<?= base_url("directory/FLOW_IMG/$file->company_id/$file->image_flow_3"); ?>" target="_blank" class="btn btn-xs btn-outline-primary"><i class="fa fa-search"></i> View Image</a>
														</div>
													<?php endif; ?>
													<?php if ($file->flow_file) : ?>
														<div class="border rounded p-3 mb-2 text-center bg-light" style="width:160px;">
															<i class="fa fa-file-pdf text-danger fa-3x mb-2"></i>
															<div class="small font-weight-bold text-truncate mb-2"><?= $file->flow_file; ?></div>
															<a href="<?= base_url("directory/FLOW_FILE/$file->company_id/$file->flow_file"); ?>" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i> Buka File</a>
														</div>
													<?php endif; ?>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endif; ?>

						<!-- Video Link -->
						<?php if ($file->link_video) : ?>
							<div class="table-responsive mb-3">
								<table class="table table-bordered mb-0">
									<thead>
										<tr class="bg-light">
											<th>
												<h6 class="font-weight-bold mb-0">Video Tutorial / Flow</h6>
											</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-center p-2">
												<div class="embed-responsive embed-responsive-16by9 mx-auto" style="max-width: 500px;">
													<iframe class="embed-responsive-item rounded" src="https://www.youtube.com/embed/<?= ($file->link_video); ?>" allowfullscreen></iframe>
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endif; ?>

						<!-- Proses Terkait -->
						<div class="table-responsive mb-3">
							<table class="table table-bordered mb-0">
								<thead>
									<tr class="bg-light">
										<th>
											<h6 class="font-weight-bold mb-0">Proses Terkait</h6>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="p-0">
											<table class="table table-sm table-striped mb-0">
												<thead>
													<tr class="bg-secondary text-dark">
														<th width="40" class="text-center small">No.</th>
														<th width="160" class="small">PIC / Tanggung Jawab</th>
														<th class="small">Deskripsi</th>
														<th width="200" class="small">Dokumen Terkait</th>
													</tr>
												</thead>
												<tbody>
													<?php if (isset($detail) && $detail) : ?>
														<?php foreach ($detail as $dtl) : ?>
															<tr>
																<td class="text-center small"><?= $dtl->number; ?></td>
																<td class="font-weight-bold small"><?= $dtl->pic; ?></td>
																<td class="small"><?= $dtl->description; ?></td>
																<td>
																	<?php $relDocs = json_decode($dtl->relate_doc); ?>
																	<?php if (is_array($relDocs)) : ?>
																		<?php foreach ($relDocs as $relDoc) { ?>
																			<?php if (isset($ArrForms[$relDoc])) : ?>
																				<span class="badge badge-success view-form mb-1 mr-1 p-2" style="cursor:pointer;" data-id="<?= $relDoc; ?>" title="Klik untuk lihat detail Form">
																					<?= $ArrForms[$relDoc]->name; ?>
																				</span>
																			<?php endif; ?>
																		<?php } ?>
																	<?php endif; ?>

																	<?php $relIk = json_decode($dtl->relate_ik_doc); ?>
																	<?php if (is_array($relIk)) : ?>
																		<?php foreach ($relIk as $ik) { ?>
																			<?php if (isset($ArrGuides[$ik])) : ?>
																				<span class="badge badge-danger view-guide mb-1 mr-1 p-2" style="cursor:pointer;" data-id="<?= $ik; ?>" title="Klik untuk lihat detail IK">
																					<?= $ArrGuides[$ik]->name; ?>
																				</span>
																			<?php endif; ?>
																		<?php } ?>
																	<?php endif; ?>
																</td>
															</tr>
														<?php endforeach; ?>
													<?php else : ?>
														<tr>
															<td colspan="4" class="text-center text-muted py-3 small">~ Data proses terkait tidak tersedia ~</td>
														</tr>
													<?php endif; ?>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				<?php else : ?>
					<div class="text-center py-5 text-muted">
						<i class="fa fa-folder-open fa-3x mb-3 text-secondary"></i>
						<h5>Data Prosedur Tidak Ditemukan</h5>
					</div>
				<?php endif; ?>
			</div>

			<!-- TAB 2: HISTORY REVISION -->
			<div class="tab-pane fade" id="tab-history-revision" role="tabpanel" aria-labelledby="tab-history-revision-link">
				<div class="p-0">
					<div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded flex-wrap" style="gap:10px;">
						<div>
							<h6 class="font-weight-bold mb-1"><i class="fa fa-info-circle text-primary mr-1"></i> Informasi Riwayat Revisi Dokumen</h6>
							<span class="text-muted small">Daftar riwayat resmi versi revisi dokumen (Rev. 0, Rev. 1, Rev. 2, ...) dan berkas masanya.</span>
						</div>
						<div>
							<span class="badge badge-primary px-3 py-2" style="font-size: 12px;">Revisi Saat Ini: Rev. <?= isset($file->revision) ? $file->revision : '0'; ?></span>
						</div>
					</div>

					<div class="table-responsive">
						<table class="table table-bordered table-hover mb-0">
							<thead>
								<tr class="bg-light">
									<th width="70" class="text-center small">Revisi</th>
									<th width="140" class="small">Tanggal Rilis</th>
									<th width="160" class="small">Disetujui Oleh</th>
									<th width="120" class="text-center small">Status</th>
									<th class="small">Deskripsi / Catatan Perubahan</th>
									<th width="110" class="text-center small">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($revisions) && $revisions) : ?>
									<?php foreach ($revisions as $rev) : ?>
										<?php
										$pdfUrl = base_url("procedures/printOut/" . $file->id);
										if (!empty($rev->file_path) && file_exists(FCPATH . $rev->file_path)) {
											$pdfUrl = base_url($rev->file_path);
										}
										$approver = !empty($rev->approver_name) ? $rev->approver_name : (!empty($rev->creator_name) ? $rev->creator_name : (isset($ArrUsr[$rev->approved_by]) ? $ArrUsr[$rev->approved_by]->full_name : '-'));
										?>
										<tr>
											<td class="text-center font-weight-bold align-middle small">
												<span class="badge badge-pill badge-light-primary">Rev. <?= $rev->revision_no; ?></span>
											</td>
											<td class="align-middle small font-weight-bold text-dark">
												<?= date('d M Y H:i', strtotime(!empty($rev->approved_at) ? $rev->approved_at : $rev->created_at)); ?>
											</td>
											<td class="align-middle small">
												<i class="fa fa-user-check text-success mr-1"></i>
												<?= $approver; ?>
											</td>
											<td class="text-center align-middle small">
												<?php if ($rev->revision_no == (isset($file->revision) ? $file->revision : 0)) : ?>
													<span class="badge badge-light-success">Aktif (Current)</span>
												<?php else : ?>
													<span class="badge badge-light-secondary">Disetujui</span>
												<?php endif; ?>
											</td>
											<td class="align-middle small">
												<?= ($rev->description) ? nl2br($rev->description) : '<span class="text-muted font-italic">- Tidak ada catatan perubahan -</span>'; ?>
											</td>
											<td class="text-center align-middle">
												<a href="<?= $pdfUrl; ?>" target="_blank" class="btn btn-xs btn-outline-primary" title="Buka PDF Revisi ini">
													<i class="fa fa-file-pdf"></i> PDF
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php else : ?>
									<tr>
										<td colspan="6" class="text-center text-muted py-4 small">
											<i class="fa fa-info-circle mr-1 text-warning"></i> Belum ada riwayat revisi (Dokumen belum disetujui & dipublish)
										</td>
									</tr>
								<?php endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<!-- TAB 3: DATA APPROVAL -->
			<div class="tab-pane fade" id="tab-data-approval" role="tabpanel" aria-labelledby="tab-data-approval-link">
				<div class="p-0">
					<div class="card border">
						<div class="card-header bg-light py-3">
							<h6 class="card-title font-weight-bold mb-0 text-dark"><i class="fa fa-user-check text-success mr-2"></i> Matriks Otoritas & Data Approval Dokumen</h6>
						</div>
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table table-bordered mb-0">
									<tbody>
										<tr>
											<th width="180" class="bg-light align-middle small">Prepared By (Dibuat Oleh)</th>
											<td class="align-middle font-weight-bold text-dark small">
												<?php if ($file && isset($ArrUsr[$file->prepared_by])) : ?>
													<i class="fa fa-user text-primary mr-1"></i>
													<?= $ArrUsr[$file->prepared_by]->full_name; ?>
													<span class="badge badge-light ml-2">Pembuat Prosedur</span>
												<?php else : ?>
													<span class="text-muted">-</span>
												<?php endif; ?>
											</td>
										</tr>
										<tr>
											<th class="bg-light align-middle small" rowspan="2">Review By (Pemeriksa)</th>
											<td class="align-middle">
												<strong class="text-muted small">Jabatan / Departemen Peninjau:</strong><br>
												<span class="font-weight-bold text-dark small">
													<?= ($file && $file->reviewer_id && isset($ArrJab[$file->reviewer_id])) ? $ArrJab[$file->reviewer_id]->name : '-'; ?>
												</span>
											</td>
										</tr>
										<tr>
											<td class="align-middle">
												<strong class="text-muted small">Status & Nama Pemeriksa:</strong><br>
												<?php if ($file && $file->reviewed_at && $file->reviewed_by && isset($ArrUsr[$file->reviewed_by])) : ?>
													<span class="font-weight-bold text-dark small">
														<i class="fa fa-user-check text-success mr-1"></i>
														<?= $ArrUsr[$file->reviewed_by]->full_name; ?>
													</span>
													<span class="badge badge-light-success ml-2 small"><i class="fa fa-check text-success mr-1"></i> Reviewed pada <?= date('d M Y H:i', strtotime($file->reviewed_at)); ?></span>
												<?php else : ?>
													<span class="badge badge-light-warning small"><i class="fa fa-clock text-warning mr-1"></i> Belum Di-review (Menunggu Review)</span>
												<?php endif; ?>
											</td>
										</tr>
										<tr>
											<th class="bg-light align-middle small" rowspan="2">Approval By (Penyetuju)</th>
											<td class="align-middle">
												<strong class="text-muted small">Jabatan / Departemen Penyetuju:</strong><br>
												<span class="font-weight-bold text-dark small">
													<?= ($file && $file->approval_id && isset($ArrJab[$file->approval_id])) ? $ArrJab[$file->approval_id]->name : '-'; ?>
												</span>
											</td>
										</tr>
										<tr>
											<td class="align-middle">
												<strong class="text-muted small">Status & Nama Penyetuju:</strong><br>
												<?php if ($file && $file->approved_at && $file->approved_by && isset($ArrUsr[$file->approved_by])) : ?>
													<span class="font-weight-bold text-dark small">
														<i class="fa fa-user-check text-success mr-1"></i>
														<?= $ArrUsr[$file->approved_by]->full_name; ?>
													</span>
													<span class="badge badge-light-success ml-2 small"><i class="fa fa-check text-success mr-1"></i> Approved pada <?= date('d M Y H:i', strtotime($file->approved_at)); ?></span>
												<?php else : ?>
													<span class="badge badge-light-warning small"><i class="fa fa-clock text-warning mr-1"></i> Belum Disetujui (Menunggu Approval)</span>
												<?php endif; ?>
											</td>
										</tr>
										<tr>
											<th class="bg-light align-middle small">Distribution List (Distribusi)</th>
											<td class="align-middle small">
												<?php
												if ($file && $file->distribute_id) {
													$lsJab = explode(',', $file->distribute_id);
													$distNames = [];
													foreach ($lsJab as $jId) {
														if (trim($jId) && isset($ArrJab[trim($jId)])) {
															$distNames[] = '<span class="badge badge-light-info mr-1 mb-1">' . $ArrJab[trim($jId)]->name . '</span>';
														}
													}
													echo implode(' ', $distNames);
												} else {
													echo '<span class="text-muted">- Tidak ada distribusi khusus -</span>';
												}
												?>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- TAB 4: PREVIEW FILE (PDF IFRAME) -->
			<div class="tab-pane fade" id="tab-preview-file" role="tabpanel" aria-labelledby="tab-preview-file-link">
				<?php if ($file) : ?>
					<?php $pdfPrintUrl = base_url("procedures/printOut/" . $file->id); ?>
					<!-- Mobile View PDF: technical explanation + download/open button -->
					<div class="text-center py-4 px-3 d-block d-md-none">
						<i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
						<h5 class="mb-2"><?= strtoupper($file->name); ?></h5>
						<div class="alert alert-warning text-left mx-auto mb-4" style="max-width: 550px;">
							<div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Informasi Preview Mobile:</div>
							<ul class="pl-3 mb-2 small text-dark" style="line-height: 1.5;">
								<li>Browser di HP/Mobile (seperti Android Chrome) tidak memiliki fitur renderer bawaan untuk merender dokumen PDF di dalam <code>iframe</code> secara native.</li>
								<li>Beberapa perangkat mobile memerlukan aplikasi pembaca PDF eksternal untuk membaca berkas PDF.</li>
							</ul>
							<div class="border-top pt-2 mt-2 small text-dark">
								<i class="fas fa-lightbulb text-warning mr-1"></i> <strong>Saran:</strong> Tekan tombol di bawah untuk membuka PDF di tab baru atau mengunduhnya ke aplikasi PDF HP Anda.
							</div>
						</div>
						<a href="<?= $pdfPrintUrl; ?>" target="_blank" class="btn btn-primary btn-lg">
							<i class="fas fa-external-link-alt mr-2"></i> Buka / Download PDF
						</a>
					</div>
					<!-- Desktop View PDF -->
					<div class="position-relative w-100 border rounded d-none d-md-block" style="height: 520px; min-height: 350px;">
						<iframe src="<?= $pdfPrintUrl; ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="100%" style="min-height:350px;"></iframe>
					</div>
				<?php else : ?>
					<div class="text-center py-5 text-muted">
						<h5>Preview file PDF tidak tersedia</h5>
					</div>
				<?php endif; ?>
			</div>

			<!-- TAB 5: ACTIVITY LOG -->
			<div class="tab-pane fade" id="tab-activity-log" role="tabpanel" aria-labelledby="tab-activity-log-link">
				<div class="container-fluid p-0">
					<label class="font-weight-bold text-dark mb-3 small"><i class="fa fa-history text-warning mr-1"></i> Timeline History Aktivitas Dokumen</label>
					<div class="timeline timeline-5">
						<div class="timeline-items">
							<?php if (isset($history) && $history) :
								foreach ($history as $his) : ?>
									<div class="timeline-item">
										<div class="timeline-media <?= (isset($his->new_status) && ($his->new_status == 'OPN' || $his->new_status == 'PUB')) ? 'bg-light-success' : 'bg-light-danger'; ?>">
											<span class="<?= (isset($his->new_status) && ($his->new_status == 'OPN' || $his->new_status == 'PUB')) ? 'fa fa-check text-success' : 'fa fa-circle text-danger'; ?>"></span>
										</div>

										<div class="timeline-desc timeline-desc-light-danger">
											<span class="font-weight-bolder text-danger small"> <?= isset($his->updated_at) ? $his->updated_at : ''; ?></span>
											<?= (isset($his->new_status) && isset($sts[$his->new_status])) ? $sts[$his->new_status] : (isset($his->new_status) ? $his->new_status : ''); ?>
											<p class="font-weight-normal text-dark-50 pt-1 mb-1 small">
												<strong>Diproses oleh: <?= (isset($his->full_name) && $his->full_name) ? $his->full_name : (isset($his->updated_by) ? $his->updated_by : '-'); ?></strong>
											</p>
											<p class="text-muted small">
												<?= (isset($his->note) && $his->note) ? nl2br($his->note) : '-'; ?>
											</p>
										</div>
									</div>
								<?php endforeach;
							else : ?>
								<div class="text-center py-4 text-muted">
									<span class="small">~ Belum ada log aktivitas ~</span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- FIXED MODAL FOOTER -->
	<div class="modal-footer flex-shrink-0 justify-content-between border-top bg-light py-2 px-3">
		<div>
			<?php if (isset($view_data) && $view_data == false && isset($file)) : ?>
				<button type="button" class="btn btn-default revision mr-2 mb-1" data-id="<?= $file->id; ?>" data-type="procedure"><i class="fa fa-sync text-warning mr-1"></i> Submit for Revision</button>
				<button type="button" class="btn btn-light-danger deletion mr-2 mb-1" data-id="<?= $file->id; ?>" data-type="procedure"><i class="fa fa-trash text-danger mr-1"></i> Submit for Deletion</button>
			<?php endif; ?>
		</div>
		<div>
			<button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
				<i class="fa fa-times mr-1"></i> Close
			</button>
		</div>
	</div>
</div>