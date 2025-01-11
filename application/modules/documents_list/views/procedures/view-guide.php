<div class="card" id="file" role="tabpanel" aria-labelledby="file-tab">
	<div style="width:92%;height:400px;background-color: red;position: absolute;opacity: 0;"></div>
	<?php if ($guide->ext == '.pdf' || $guide->ext == '.PDF') : ?>
		<iframe src="<?= base_url("directory/GUIDES/$guide->company_id/$guide->file_name"); ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="400px"></iframe>
	<?php else : ?>
		<iframe src="https://docs.google.com/gview?embedded=true&url=<?= base_url("directory/GUIDES/$guide->company_id/$form->file_name"); ?>&rm=minimal#toolbar=0&navpanes=0" frameborder="0" width="100%" height="400px"></iframe>
	<?php endif; ?>
	<hr>
</div>