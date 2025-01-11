<div class="modal-body">
	<div class="container">
		<div class="">
			<div class=" row">
				<label class="col-12 col-form-label"><span class="text-danger">*</span> Document Name :</label>
				<div class="col-12">
					<input type="hidden" id="id" name="forms[id]" class="form-control" value="<?= isset($data) ? $data->id : ''; ?>" />
					<input type="hidden" name="forms[procedure_id]" class="form-control" value="<?= $procedure_id; ?>" />
					<input type="text" class="form-control" id="description" placeholder="Document Name" name="forms[description]" value="<?= isset($data) ? $data->name : ''; ?>" autocomplete="off" />
					<span class="form-text text-danger invalid-feedback">Deskripsi harus di isi</span>
				</div>
				<input type="hidden" name="forms[type]" value="form">
			</div>

			<div class="form-group row mb-0">
				<label class="col-12 col-form-label"><span class="text-danger"></span> Type Form :</label>
				<div class="col-12">
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" <?= (isset($data) && $data->file_name) ? 'checked' : ''; ?> name="form_type" value="upload_file"> Upload File
						</label>
					</div>
					<div class="form-check form-check-inline">
						<label class="form-check-label">
							<input class="form-check-input" type="radio" <?= (isset($data) && $data->link_form) ? 'checked' : ''; ?> name="form_type" value="online_form"> Online Form
						</label>
					</div>
				</div>
			</div>

			<div id="type-form">
				<?php if (isset($data) && $data->file_name) : ?>
					<div class="form-group row mb-0">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
						<div class="col-12">
							<input type="file" name="forms_image" id="image" class="form-control" placeholder="Upload File">
							<span class="form-text text-muted">File type : PDF</span>
							<span class="form-text text-danger invalid-feedback">Upload Document By harus di isi</span>
						</div>
						<?php if (isset($data)) : ?>
							<input type="hidden" name="forms[old_file]" id="old_file" value="<?= isset($data) ? $data->file_name : ''; ?>">
						<?php endif; ?>
					</div>
				<?php elseif (isset($data) && $data->link_form) : ?>
					<div class="form-group row">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Link Google Form</label>
						<div class="col-12">
							<div class="input-group mb-3">
								<span class="input-group-text rounded-right-0"><i class="fa fa-link"></i></span>
								<input type="text" class="form-control" id="link-form" placeholder="Link Form" name="forms[link_form]" value="<?= isset($data) ? $data->link_form : ''; ?>" autocomplete="off" />
							</div>
							<span class="form-text text-danger invalid-feedback">Link Form harus di isi</span>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

	</div>
</div>

<div class="modal-footer justify-content-between align-items-center">
	<button type="button" class="btn btn-primary save" id="save-form"><i class="fa fa-save"></i>Save</button>
	<button type="button" class="btn btn-danger close-btn" onclick="setTimeout(function(){$('#record-content').html('')},500)" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
</div>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: 'Choose an options',
			width: '100%',
			allowClear: true
		})
	})
</script>