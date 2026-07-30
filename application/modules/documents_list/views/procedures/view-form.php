<div class="modal-header py-2 px-3 d-flex justify-content-between align-items-center">
	<ul class="nav nav-pills nav-light-success py-0" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#file">
				<span class="nav-icon">
					<i class="fa fa-file-alt"></i>
				</span>
				<span class="nav-text">File</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#history">
				<span class="nav-icon">
					<i class="fa fa-history"></i>
				</span>
				<span class="nav-text">History</span>
			</a>
		</li>
	</ul>
	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		<span aria-hidden="true">&times;</span>
	</button>
</div>
<div class="tab-content mt-5">
	<div class="tab-pane fade show active" id="file" role="tabpanel" aria-labelledby="file-tab">
		<?php if ($form->link_form) : ?>
			<!-- Online Form Link -->
			<div class="text-center py-5">
				<i class="fas fa-link fa-5x text-primary mb-4"></i>
				<h5 class="mb-3"><?= $form->name; ?></h5>
				<p class="text-muted mb-4">Dokumen ini berupa Online Form (link external).</p>
				<a href="<?= $form->link_form; ?>" target="_blank" class="btn btn-primary btn-lg">
					<i class="fa fa-external-link-alt mr-2"></i> Buka Link Form
				</a>
			</div>
		<?php elseif ($form->file_name) : ?>
			<?php
			$ext = strtolower($form->ext);
			$isExcel = in_array($ext, ['.xls', '.xlsx']);
			$fileUrl = base_url("directory/FORMS/$form->company_id/$form->file_name");
			?>
			<?php if ($isExcel) : ?>
				<!-- Mobile View: technical explanation + download button -->
				<div class="text-center py-4 px-3 d-block d-md-none">
					<i class="fas fa-file-excel fa-4x text-success mb-3"></i>
					<h5 class="mb-2"><?= $form->name; ?></h5>
					<div class="alert alert-warning text-left mx-auto mb-4" style="max-width: 550px;">
						<div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Informasi Preview Mobile:</div>
						<ul class="pl-3 mb-2 small text-dark" style="line-height: 1.5;">
							<li>Browser di HP/Mobile tidak memiliki fitur renderer bawaan untuk menampilkan file Excel/Word di dalam frame browser.</li>
							<li>Browser mobile (Android Chrome / Safari iOS) juga tidak mendukung ekstensi browser seperti <em>Office Editing</em>.</li>
							<li>Server ini berada di jaringan lokal (intranet), sehingga cloud viewer (seperti Google Docs/Office Online) tidak dapat mengakses file ini dari luar.</li>
						</ul>
						<div class="border-top pt-2 mt-2 small text-dark">
							<i class="fas fa-lightbulb text-warning mr-1"></i> <strong>Saran:</strong> Untuk membuka & melihat preview file Excel/Word langsung di browser tanpa mengunduh, disarankan menggunakan browser di <strong>PC Desktop/Laptop</strong> yang terinstall ekstensi <strong>Office Editing for Docs, Sheets & Slides</strong>.
						</div>
					</div>
					<a href="<?= $fileUrl; ?>" download class="btn btn-success btn-lg">
						<i class="fa fa-download mr-2"></i> Download File Excel
					</a>
				</div>
				<!-- Desktop View: standard iframe for Office Editing extension -->
				<div class="d-none d-md-block">
					<div class="mb-2 d-flex justify-content-between align-items-center bg-light p-2 rounded border">
						<span class="small text-muted"><i class="fas fa-info-circle mr-1"></i> Preview Excel (Gunakan browser extension <strong>Office Editing</strong> jika iframe belum merender otomatis)</span>
						<a href="<?= $fileUrl; ?>" download class="btn btn-sm btn-outline-success">
							<i class="fa fa-download mr-1"></i> Download File
						</a>
					</div>
					<iframe src="<?= $fileUrl; ?>" frameborder="0" width="100%" style="height:70vh; min-height:450px;"></iframe>
				</div>
			<?php else : ?>
				<!-- Mobile View PDF: technical explanation + download button -->
				<div class="text-center py-4 px-3 d-block d-md-none">
					<i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
					<h5 class="mb-2"><?= $form->name; ?></h5>
					<div class="alert alert-warning text-left mx-auto mb-4" style="max-width: 550px;">
						<div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Informasi Preview Mobile:</div>
						<ul class="pl-3 mb-2 small text-dark" style="line-height: 1.5;">
							<li>Browser di HP/Mobile (seperti Android Chrome) tidak memiliki fitur renderer bawaan untuk merender dokumen PDF di dalam frame browser.</li>
							<li>Beberapa perangkat mobile memerlukan aplikasi pembaca PDF eksternal untuk membaca berkas PDF.</li>
						</ul>
						<div class="border-top pt-2 mt-2 small text-dark">
							<i class="fas fa-lightbulb text-warning mr-1"></i> <strong>Saran:</strong> Tekan tombol di bawah untuk membuka / mengunduh PDF secara langsung di tab baru atau aplikasi PDF HP Anda.
						</div>
					</div>
					<a href="<?= $fileUrl; ?>" target="_blank" class="btn btn-primary btn-lg">
						<i class="fas fa-external-link-alt mr-2"></i> Buka / Download PDF
					</a>
				</div>
				<!-- Desktop View PDF: show in iframe -->
				<div class="d-none d-md-block">
					<iframe src="<?= $fileUrl; ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" style="height:70vh;"></iframe>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="text-center py-5">
				<i class="fa fa-times-circle fa-3x text-danger mb-3"></i>
				<p class="text-muted">No file available</p>
			</div>
		<?php endif; ?>
	</div>
	<div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
		<div class="row overflow-auto">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<label for="">Tracking File</label>
				<div class="timeline timeline-5">
					<div class="timeline-items">
						<!-- <div class="timeline-item">
                            <div class="timeline-media bg-light-primary">
                                <i class="fa fa-upload text-success"></i>
                            </div>
                            <div class="timeline-desc timeline-desc-light-primary">
                                <span class="font-weight-bolder text-primary"> <?= date('Y-m-d'); ?> 09:30 AM</span>
                                <span class="label label-pill label-inline label-light-danger">Upload File</span>
                                <p class="font-weight-normal text-dark-50 pb-2">
                                    To start a blog, think of a topic about and first brainstorm ways to write details
                                </p>
                            </div>
                        </div> -->
						<?php if (isset($history)) :
							foreach ($history as $his) : ?>
								<div class="timeline-item">
									<div class="timeline-media <?= ($his->new_status == 'OPN') ? 'bg-light-success' : 'bg-light-danger'; ?>">
										<span class="<?= ($his->new_status == 'OPN') ? 'fa fa-upload text-success' : 'fa fa-circle text-danger'; ?>"></span>
									</div>

									<div class="timeline-desc timeline-desc-light-danger">
										<span class="font-weight-bolder text-danger"> <?= $his->updated_at; ?></span>
										<?= $sts[$his->new_status]; ?>
										<p class="font-weight-normal text-dark-50 pt-1">
											<strong for="">Processed by <?= isset($ArrUsr[$his->updated_by]) ? $ArrUsr[$his->updated_by]->full_name : '-'; ?></strong>
										</p>
										<p>
											<?= $his->note; ?>
										</p>
									</div>
								</div>
						<?php endforeach;
						endif; ?>
					</div>
				</div>
			</div>
		</div>


	</div>
</div>
<div class="modal-footer py-2 px-3 border-top mt-3">
	<button type="button" class="btn btn-secondary btn-sm px-4" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Close</button>
</div>