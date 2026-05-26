
<div class="card" id="file" role="tabpanel" aria-labelledby="file-tab">
	<?php
	$ext = strtolower($guide->ext);
	$isExcel = in_array($ext, ['.xls', '.xlsx']);
	$fileUrl = base_url("directory/GUIDES/$guide->company_id/$guide->file_name");
	?>
	<?php if ($isExcel) : ?>
		<!-- Excel file: show download button -->
		<div class="text-center py-5">
			<i class="fas fa-file-excel fa-5x text-success mb-4"></i>
			<h5 class="mb-3"><?= $guide->name; ?></h5>
			<p class="text-muted mb-4">File Excel tidak dapat ditampilkan di browser. Silakan download untuk melihat.</p>
			<a href="<?= $fileUrl; ?>" download class="btn btn-success btn-lg">
				<i class="fa fa-download mr-2"></i> Download Excel
			</a>
		</div>
	<?php else : ?>
		<!-- PDF file: show in iframe -->
		<div style="width:92%;height:400px;background-color: red;position: absolute;opacity: 0;"></div>
		<iframe src="<?= $fileUrl; ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="400px"></iframe>
	<?php endif; ?>
	<hr>
</div>
