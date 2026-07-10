<form id="frm-form-standalone" enctype="multipart/form-data">
<div class="modal-body">
	<div class="container">
		<div class="form-group row">
			<label class="col-12 col-form-label"><span class="text-danger">*</span> Procedure :</label>
			<div class="col-12">
				<select name="forms[procedure_id]" class="form-control select2-modal" data-placeholder="Select Procedure" required>
					<option value=""></option>
					<?php if (!empty($procedures)) foreach ($procedures as $proc) : ?>
						<option value="<?= $proc->id; ?>" <?= (isset($procedure_id) && $procedure_id == $proc->id) ? 'selected' : ''; ?>><?= $proc->name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-12 col-form-label"><span class="text-danger">*</span> Document Name :</label>
			<div class="col-12">
				<input type="hidden" name="forms[id]" value="<?= isset($data) ? $data->id : ''; ?>" />
				<input type="text" class="form-control" placeholder="Document Name" name="forms[description]" value="<?= isset($data) ? $data->name : ''; ?>" autocomplete="off" required />
			</div>
			<input type="hidden" name="forms[type]" value="form">
		</div>

		<div class="form-group row">
			<label class="col-12 col-form-label">Form Number :</label>
			<div class="col-12">
				<input type="text" class="form-control" placeholder="Form Number" name="forms[number]" value="<?= isset($data) ? $data->number : ''; ?>" autocomplete="off" />
			</div>
		</div>

		<div class="form-group row mb-0">
			<label class="col-12 col-form-label">Type Form :</label>
			<div class="col-12">
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input type-form-radio" type="radio" <?= (isset($data) && $data->file_name) || !isset($data) ? 'checked' : ''; ?> name="form_type" value="upload_file"> Upload File
					</label>
				</div>
				<div class="form-check form-check-inline">
					<label class="form-check-label">
						<input class="form-check-input type-form-radio" type="radio" <?= (isset($data) && $data->link_form) ? 'checked' : ''; ?> name="form_type" value="online_form"> Online Form
					</label>
				</div>
			</div>
		</div>

		<div id="type-form-standalone">
			<?php if (isset($data) && $data->link_form) : ?>
				<div class="form-group row" id="section-link-form">
					<label class="col-12 col-form-label"><span class="text-danger">*</span> Link Google Form</label>
					<div class="col-12">
						<div class="input-group">
							<span class="input-group-text rounded-right-0"><i class="fa fa-link"></i></span>
							<input type="text" class="form-control" placeholder="Link Form" name="forms[link_form]" value="<?= isset($data) ? $data->link_form : ''; ?>" autocomplete="off" />
						</div>
					</div>
				</div>
			<?php else : ?>
				<div class="form-group row" id="section-upload-file">
					<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
					<div class="col-12">
						<input type="file" name="forms_image" class="form-control" accept=".pdf,.xls,.xlsx">
						<span class="form-text text-muted">File type : PDF, Excel (xls, xlsx)</span>
					</div>
					<?php if (isset($data) && $data->file_name) : ?>
						<input type="hidden" name="forms[old_file]" value="<?= $data->file_name; ?>">
						<div class="col-12 mt-1"><small class="text-info">Current: <?= $data->file_name; ?></small></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>

<div class="modal-footer justify-content-between">
	<button type="button" class="btn btn-primary" id="save-form-standalone"><i class="fa fa-save mr-1"></i> Save</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Cancel</button>
</div>
</form>

<script>
$(document).ready(function() {
	$('.select2-modal').select2({
		placeholder: 'Select Procedure',
		width: '100%',
		allowClear: true,
		dropdownParent: $('#modalForm')
	});

	// Toggle upload/link based on radio
	$('.type-form-radio').on('change', function() {
		var val = $(this).val();
		$('#type-form-standalone').html('');
		if (val === 'upload_file') {
			$('#type-form-standalone').html(`
				<div class="form-group row">
					<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
					<div class="col-12">
						<input type="file" name="forms_image" class="form-control" accept=".pdf,.xls,.xlsx">
						<span class="form-text text-muted">File type : PDF, Excel (xls, xlsx)</span>
					</div>
				</div>
			`);
		} else {
			$('#type-form-standalone').html(`
				<div class="form-group row">
					<label class="col-12 col-form-label"><span class="text-danger">*</span> Link Google Form</label>
					<div class="col-12">
						<div class="input-group">
							<span class="input-group-text rounded-right-0"><i class="fa fa-link"></i></span>
							<input type="text" class="form-control" placeholder="https://forms.google.com/..." name="forms[link_form]" autocomplete="off" />
						</div>
					</div>
				</div>
			`);
		}
	});
});
</script>
