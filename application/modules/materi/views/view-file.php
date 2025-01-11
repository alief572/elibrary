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
			<div style="width:98%;background-color: aquamarine; position: absolute;opacity: 0;height:103%"></div>
			<iframe style="pointer-events:visibleStroke;" onclick="cek(e)" oncontextmenu="cek(r)" src="<?= base_url("directory/MATERI/$data->company_id/$data->document"); ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="550"></iframe>
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