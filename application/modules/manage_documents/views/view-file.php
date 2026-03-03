<div class="text-center d-none d-md-block">
	<div style="width:92%;height:400px;background-color: red;position: absolute;opacity: 0;"></div>
	<iframe style="pointer-events:visibleStroke;" onclick="cek(e)" oncontextmenu="cek(r)" src="<?= base_url("directory/$folderMain/$company_id/$parent_name/$file->file_name"); ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="500px"></iframe>
</div>
<div class="text-center d-block d-lg-none">
	<a target="_blank" href="<?= base_url("directory/$folderMain/$company_id/$parent_name/$file->file_name"); ?>" class="btn btn-primary">Open File <i class="fas fa-external-link-alt text-sm" aria-hidden="true"></i></a>
</div>

<script>
	function cek(e) {
		console.log(e);
		alert(e);
	}
</script>