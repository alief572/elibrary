
<div class="card" id="file" role="tabpanel" aria-labelledby="file-tab">
	<?php
	$ext = strtolower($guide->ext);
	$isExcel = in_array($ext, ['.xls', '.xlsx']);
	$fileUrl = base_url("directory/GUIDES/$guide->company_id/$guide->file_name");
	?>
	<?php if ($isExcel) : ?>
		<!-- Mobile View: technical explanation + download button -->
		<div class="text-center py-4 px-3 d-block d-md-none">
			<i class="fas fa-file-excel fa-4x text-success mb-3"></i>
			<h5 class="mb-2"><?= $guide->name; ?></h5>
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
		<div class="d-none d-md-block p-2">
			<div class="mb-2 d-flex justify-content-between align-items-center bg-light p-2 rounded border">
				<span class="small text-muted"><i class="fas fa-info-circle mr-1"></i> Preview Excel (Gunakan browser extension <strong>Office Editing</strong> jika iframe belum merender otomatis)</span>
				<a href="<?= $fileUrl; ?>" download class="btn btn-sm btn-outline-success">
					<i class="fa fa-download mr-1"></i> Download File
				</a>
			</div>
			<iframe src="<?= $fileUrl; ?>" frameborder="0" width="100%" height="450px" style="min-height: 450px;"></iframe>
		</div>
	<?php else : ?>
		<!-- Mobile View PDF: technical explanation + download button -->
		<div class="text-center py-4 px-3 d-block d-md-none">
			<i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
			<h5 class="mb-2"><?= $guide->name; ?></h5>
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
			<iframe src="<?= $fileUrl; ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="450px"></iframe>
		</div>
	<?php endif; ?>
</div>
