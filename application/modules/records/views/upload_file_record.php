<div class="modal-body">
	<div class="container">
		<div class="">
			<div class="row">
				<label class="col-12 col-form-label"><span class="text-danger">*</span> Document Name :</label>
				<div class="col-12">
					<input type="hidden" id="id" name="forms[id]" class="form-control" value="<?= isset($data) ? $data->id : ''; ?>" />
					<input type="hidden" name="forms[procedure_id]" class="form-control" value="<?= isset($data) ? $data->procedure_id : $procedure_id; ?>" />
					<input type="hidden" name="forms[parent_id]" class="form-control" value="<?= isset($data) ? $data->parent_id : $parent_id; ?>" />
					<input type="text" class="form-control" id="name" placeholder="Document Name" name="forms[name]" value="<?= isset($data) ? $data->name : ''; ?>" autocomplete="off" />
					<span class="form-text text-danger invalid-feedback">Document Name harus di isi</span>
				</div>
				<input type="hidden" name="forms[type]" value="record">
			</div>

			<div class="form-group row mb-0">
				<label class="col-12 col-form-label"><span class="text-danger"></span> Type Record :</label>
				<div class="col-12">
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" <?= (!isset($data) || (isset($data) && $data->file_name)) ? 'checked' : ''; ?> name="record_type" value="upload_file"> Upload File
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" <?= (isset($data) && $data->link_url) ? 'checked' : ''; ?> name="record_type" value="online_link"> Online Form
						</label>
					</div>
				</div>
			</div>

			<div id="type-record">
				<?php if (!isset($data) || (isset($data) && $data->file_name)) : ?>
					<div class="form-group row mb-0">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
						<div class="col-12">
							<input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv" name="forms_image" id="image" class="form-control" placeholder="Upload File">
							<span class="form-text text-muted">File type : PDF, Word, Excel, PowerPoint</span>
							<span class="form-text text-danger invalid-feedback">Upload Document harus di isi</span>
						</div>
						<?php if (isset($data)) : ?>
							<input type="hidden" name="forms[old_file]" id="old_file" value="<?= isset($data) ? $data->file_name : ''; ?>">
						<?php endif; ?>
					</div>
				<?php elseif (isset($data) && $data->link_url) : ?>
					<div class="form-group row mb-2">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Link Google Form</label>
						<div class="col-12">
							<div class="input-group mb-3">
								<span class="input-group-text rounded-right-0"><i class="fa fa-link"></i></span>
								<input type="text" class="form-control" id="link-url" placeholder="Link Form" name="forms[link_url]" value="<?= isset($data) ? $data->link_url : ''; ?>" autocomplete="off" />
							</div>
							<span class="form-text text-danger invalid-feedback">Link harus di isi</span>
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-12 col-form-label">Description :</label>
						<div class="col-12">
							<textarea class="form-control" id="description" placeholder="Description" name="forms[description]" rows="3" autocomplete="off"><?= isset($data) ? $data->description : ''; ?></textarea>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer justify-content-between align-items-center">
	<button type="button" class="btn btn-primary save" id="save-record"><i class="fa fa-save"></i> Save</button>
	<button type="button" class="btn btn-danger" onclick="setTimeout(function(){$('#record-content').html('')},500)" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
</div>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: 'Choose an options',
			width: '100%',
			allowClear: true
		})

		// Toggle between Upload File and Link
		$(document).off('change', 'input[name="record_type"]').on('change', 'input[name="record_type"]', function() {
			const type = $(this).val();
			if (type === 'upload_file') {
				$('#type-record').html(`
					<div class="form-group row mb-0">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
						<div class="col-12">
							<input type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.csv" name="forms_image" id="image" class="form-control" placeholder="Upload File">
							<span class="form-text text-muted">File type : PDF, Word, Excel, PowerPoint</span>
							<span class="form-text text-danger invalid-feedback">Upload Document harus di isi</span>
						</div>
					</div>
				`);
			} else {
				$('#type-record').html(`
					<div class="form-group row mb-2">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Link Google Form</label>
						<div class="col-12">
							<div class="input-group mb-3">
								<span class="input-group-text rounded-right-0"><i class="fa fa-link"></i></span>
								<input type="text" class="form-control" id="link-url" placeholder="Link Form" name="forms[link_url]" value="" autocomplete="off" />
							</div>
							<span class="form-text text-danger invalid-feedback">Link harus di isi</span>
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-12 col-form-label">Description :</label>
						<div class="col-12">
							<textarea class="form-control" id="description" placeholder="Description" name="forms[description]" rows="3" autocomplete="off"></textarea>
						</div>
					</div>
				`);
			}
		});
	})
</script>
