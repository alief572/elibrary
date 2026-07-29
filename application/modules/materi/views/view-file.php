<!-- Nav tabs -->
<ul class="nav nav-tabs" id="tab-upload" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link active" id="upload-document-tab" data-toggle="tab" data-target="#upload-document" type="button" role="tab" aria-controls="upload-document" aria-selected="false">Upload Document</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="from-external-url-tab" data-toggle="tab" data-target="#from-external-url" type="button" role="tab" aria-controls="from-url" aria-selected="true">External Link <i class="ml-2 fa fa-link text-primary" aria-hidden="true"></i></button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link" id="from-url-tab" data-toggle="tab" data-target="#from-url" type="button" role="tab" aria-controls="from-url" aria-selected="true">From YouTube <i class="fab fa-youtube ml-1 text-danger"></i></button>
	</li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane active p-0 border border-top-0 rounded-bottom" id="upload-document" role="tabpanel" aria-labelledby="upload-file-tab">
		<?php if ($data->document) : ?>
			<?php $pdfMateriUrl = base_url("directory/MATERI/$data->company_id/$data->document"); ?>
			<!-- Mobile View PDF: technical explanation + download button -->
			<div class="text-center py-4 px-3 d-block d-md-none">
				<i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
				<h5 class="mb-2"><?= isset($data->name) ? $data->name : 'Dokumen Materi PDF'; ?></h5>
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
				<a href="<?= $pdfMateriUrl; ?>" target="_blank" class="btn btn-primary btn-lg">
					<i class="fas fa-external-link-alt mr-2"></i> Buka / Download PDF
				</a>
			</div>
			<!-- Desktop View PDF -->
			<div class="d-none d-md-block">
				<div class="p-2 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap">
					<span class="text-muted small"><i class="fa fa-info-circle mr-1"></i> Preview Dokumen PDF</span>
					<a href="<?= $pdfMateriUrl; ?>" target="_blank" class="btn btn-sm btn-primary my-1">
						Buka / Download PDF <i class="fas fa-external-link-alt ml-1" aria-hidden="true"></i>
					</a>
				</div>
				<div class="position-relative w-100" style="height: 550px;">
					<iframe src="<?= $pdfMateriUrl; ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="550"></iframe>
				</div>
			</div>
		<?php else : ?>
			<h5 class="text-center mt-5">~ Not available data ~</h5>
		<?php endif; ?>
	</div>
	<div class="tab-pane p-0 border border-top-0 rounded-bottom" id="from-external-url" role="tabpanel" aria-labelledby="from-external-url-tab">
		<?php if ($data->url_link) : ?>
			<div class="position-absolute d-flex justify-content-center zindex-5 w-100">
				<a href="<?= $data->url_link; ?>" target="_blank" class="btn-xs btn shadow-sm">Original File <i class="fa fa-link" aria-hidden="true"></i></a>
			</div>
			<div style="width:98%;background-color: aquamarine; position: absolute;opacity: 0;height:103%"></div>
			<iframe style="pointer-events:visibleStroke;" onclick="cek(e)" oncontextmenu="cek(r)" src="<?= $data->url_link ?>" frameborder="0" width="100%" height="550"></iframe>
		<?php else : ?>
			<h5 class="text-center mt-5">~ Not available data ~</h5>
		<?php endif; ?>
	</div>
	<div class="tab-pane p-0 border border-top-0 rounded-bottom" id="from-url" role="tabpanel" aria-labelledby="from-url-tab">
		<?php if ($data->video_link) : ?>
			<iframe style="pointer-events:visibleStroke;" onclick="cek(e)" oncontextmenu="cek(r)" src="https://youtube.com/embed/<?= $data->video_link; ?>" frameborder="0" width="100%" height="550"></iframe>
		<?php else : ?>
			<h5 class="text-center mt-5">~ Not available data ~</h5>
		<?php endif; ?>
	</div>
</div>

<script>
	function cek(e) {
		console.log(e);
		alert(e);
	}
</script>