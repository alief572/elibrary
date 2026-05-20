<form id="form-upload">
	<?php
		$uploadSelected = isset($file) && is_object($file) && !empty($file->file_name);
		$linkSelected = isset($file) && is_object($file) && !empty($file->file_link);
		$source = $linkSelected ? 'link' : 'upload';
	?>
	<div class="row">
		<label class="col-12 col-form-label">Document Name :</label>
		<div class="col-12">
			<input type="hidden" name="folder" value="<?= isset($folder)? $folder:''; ?>">
			<input type="hidden" id="id" name="id" class="form-control" placeholder="" value="<?= isset($file) && is_object($file) ? $file->id : ''; ?>" />
			<input type="hidden" id="parent_id" name="parent_id" class="form-control" placeholder="" value="<?= $parent_id; ?>" />
			<input type="text" class="form-control" id="description" placeholder="Description" name="description" value="<?= isset($file) && is_object($file) ? $file->name : ''; ?>" autocomplete="off" />
			<span class="form-text text-danger invalid-feedback">Deskripsi harus di isi</span>
		</div>
	</div>

	<div class="row">
		<label class="col-12 col-form-label">Prepared By :</label>
		<div class="col-12">
			<select name="prepared_by" id="prepared_by" class="form-control select2">;
				<option value=""></option>
				<?php foreach ($users as $usr) : ?>
					<option value="<?= $usr->id_user; ?>" <?= (isset($file) && is_object($file) && $file->prepared_by == $usr->id_user) ? 'selected' : ''; ?>><?= $usr->full_name; ?></option>
				<?php endforeach; ?>
			</select>
			<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
		</div>
	</div>

	<div class="row">
		<!-- <label class="col-12 col-form-label">File Type :</label> -->
		<div class="col-12 col-form-label">
			<input type="radio" class="d-none" checked name="flag_record" value="Y" />
			<!-- <div class="radio-inline">
						<label class="radio radio-primary">
							<input type="radio" name="flag_record" checked="checked" value="N" />
							<span></span>
							Need Approval
						</label>
						<label class="radio radio-primary">
							<span></span>
							Without Approval
						</label>
					</div>
					<span class="form-text text-muted">pilih salah satu</span> -->
		</div>
	</div>

	<!-- <div id="file-type">
				<div class="row">
					<label class="col-12 col-form-label">Review By :</label>
					<div class="col-12">
						<select name="reviewer_id" id="reviewer_id" class="form-control select2">;
							<option value=""></option>
							<?php foreach ($jabatan as $jbt) : ?>
								<option value="<?= $jbt->id; ?>" <?= (isset($file) && $file->reviewer_id == $jbt->id) ? 'selected' : ''; ?>><?= $jbt->nm_jabatan; ?></option>
							<?php endforeach; ?>
						</select>
						<span class="form-text text-danger invalid-feedback">Review By harus di isi</span>
					</div>
				</div>

				<div class="row">
					<label class="col-12 col-form-label">Approval By :</label>
					<div class="col-12">
						<select name="approval_id" id="approval_id" class="form-control select2">;
							<option value=""></option>
							<?php foreach ($jabatan as $jbt) : ?>
								<option value="<?= $jbt->id; ?>" <?= (isset($file) && $file->approval_id == $jbt->id) ? 'selected' : ''; ?>><?= $jbt->nm_jabatan; ?></option>
							<?php endforeach; ?>
						</select>
						<span class="form-text text-danger invalid-feedback">Approval By harus di isi</span>
					</div>
				</div>

				<div class="row">
					<label class="col-12 col-form-label">Distribusi :</label>
					<div class="col-12">
						<select name="distribute_id[]" multiple id="distribute_id" data-placeholder="Choose an options" class="form-control select2">;
							<option value=""></option>
							<?php foreach ($jabatan as $jbt) : ?>
								<option value="<?= $jbt->id; ?>" <?= isset($file) ? ((in_array($jbt->id, explode(',', $file->distribute_id))) ? 'selected' : '') : ''; ?>><?= $jbt->nm_jabatan; ?></option>
							<?php endforeach; ?>
						</select>
						<span class="form-text text-danger invalid-feedback">Distribusi By harus di isi</span>
					</div>
				</div>
			</div> -->

	<div class="form-group row mb-0">
		<label class="col-12 col-form-label">Document Source :</label>
		<div class="col-12">
			<div class="radio-inline">
				<label class="radio radio-primary">
					<input type="radio" name="document_source" value="upload" <?= $source === 'upload' ? 'checked' : ''; ?>>
					<span></span> Upload File
				</label>
				<label class="radio radio-primary ml-3">
					<input type="radio" name="document_source" value="link" <?= $source === 'link' ? 'checked' : ''; ?>>
					<span></span> Input Link
				</label>
			</div>
		</div>
	</div>

	<div class="form-group row mb-0 document-source document-source-upload" style="display: <?= $source === 'upload' ? 'block' : 'none'; ?>;">
		<label class="col-12 col-form-label">Upload Document :</label>
		<div class="col-12">
			<input type="file" name="image" accept=".png,.jpg,.pdf,.xlsx,.docx" id="image" class="form-control" placeholder="Upload File">
			<span class="form-text text-muted">File type : PDF/PNG/JPG/XLSX/DOCX</span>
			<span class="form-text text-danger invalid-feedback">Upload Document By harus di isi</span>
		</div>
		<?php if (isset($file) && is_object($file) && !empty($file->file_name)) : ?>
			<input type="hidden" name="old_file" id="old_file" value="<?= $file->file_name; ?>">
		<?php endif; ?>
	</div>

	<div class="form-group row mb-0 document-source document-source-link" style="display: <?= $source === 'link' ? 'block' : 'none'; ?>;">
		<label class="col-12 col-form-label">Document Link :</label>
		<div class="col-12">
			<input type="url" name="document_link" id="document_link" class="form-control" placeholder="https://example.com/document.pdf" value="<?= isset($file) && is_object($file) ? $file->file_link : ''; ?>" />
			<span class="form-text text-muted">Masukkan URL file atau dokumen yang dapat diakses.</span>
			<span class="form-text text-danger invalid-feedback">Input Link harus di isi</span>
			<div id="document_link_preview" class="mt-3" style="display: <?= isset($file) && is_object($file) && !empty($file->file_link) ? 'block' : 'none'; ?>;">
				<span class="form-text text-muted">Preview: <a id="document_link_preview_url" href="<?= isset($file) && is_object($file) ? $file->file_link : ''; ?>" target="_blank"><?= isset($file) && is_object($file) ? $file->file_link : ''; ?></a></span>
				<div class="mt-2">
					<a id="document_link_preview_button" href="<?= isset($file) && is_object($file) ? $file->file_link : ''; ?>" target="_blank" class="btn btn-light-primary btn-sm">Buka Link</a>
				</div>
			</div>
		</div>
	</div>

</form>

<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: 'Choose an options',
			width: '100%',
			allowClear: true
		})

		function updateLinkPreview() {
			var link = $('#document_link').val().trim();
			var isValid = link.length > 0;
			$('#document_link_preview').toggle(isValid);
			$('#document_link_preview_url').attr('href', link).text(link);
			$('#document_link_preview_button').attr('href', link);
		}

		function updateSourceDisplay(value) {
			$('.document-source-upload').toggle(value === 'upload');
			$('.document-source-link').toggle(value === 'link');
			$('#image').prop('disabled', value !== 'upload');
			$('#document_link').prop('disabled', value !== 'link');
			updateLinkPreview();
		}

		$('input[name="document_source"]').on('change', function() {
			updateSourceDisplay($(this).val());
		})

		$('#document_link').on('input', updateLinkPreview);
		updateSourceDisplay($('input[name="document_source"]:checked').val());
	})
</script>