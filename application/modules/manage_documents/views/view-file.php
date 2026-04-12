<?php
$isLink = isset($file->flag_type) && $file->flag_type === 'LINK';
$linkUrl = $isLink && !empty($file->file_link) ? $file->file_link : null;
$fileUrl = !$isLink ? base_url("directory/$folderMain/$company_id/$parent_name/$file->file_name") : null;
?>

<div class="text-center d-none d-md-block">
	<iframe
		style="pointer-events:visibleStroke;"
		src="<?= $isLink ? htmlspecialchars($linkUrl, ENT_QUOTES, 'UTF-8') : htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8'); ?>#toolbar=0&navpanes=0"
		frameborder="0"
		width="100%"
		height="500px">
	</iframe>
	<?php if ($isLink) : ?>
	<div class="alert alert-warning mt-3 text-left" role="alert">
		Preview mungkin tidak tersedia jika link dibuka di dalam iframe. Gunakan tombol langsung di bawah untuk membuka link di tab baru.
	</div>
	<?php endif; ?>
</div>
<div class="text-center d-block d-lg-none mb-3">
	<a target="_blank" href="<?= $isLink ? htmlspecialchars($linkUrl, ENT_QUOTES, 'UTF-8') : htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary">
		Open <?= $isLink ? 'Link' : 'File'; ?> <i class="fas fa-external-link-alt text-sm" aria-hidden="true"></i>
	</a>
</div>
<?php if ($isLink && $linkUrl) : ?>
<div class="text-center mb-3">
	<a target="_blank" href="<?= htmlspecialchars($linkUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-light-primary">
		Direct ke Link <i class="fas fa-external-link-alt text-sm" aria-hidden="true"></i>
	</a>
</div>
<?php endif; ?>

<script>
	// no-op script kept for compatibility
</script>