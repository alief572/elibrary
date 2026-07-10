<form id="frm-guide-standalone" enctype="multipart/form-data">
<div class="modal-body">
	<div class="container">
		<div class="form-group row">
			<label class="col-12 col-form-label"><span class="text-danger">*</span> Procedure :</label>
			<div class="col-12">
				<select name="forms[procedure_id]" class="form-control select2-modal-guide" data-placeholder="Select Procedure" required>
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
			<input type="hidden" name="forms[type]" value="guide">
		</div>

		<div class="form-group row">
			<label class="col-12 col-form-label"><span class="text-danger">*</span> Prepared By :</label>
			<div class="col-12">
				<select name="forms[prepared_by]" class="form-control select2-modal-prepared" data-placeholder="Choose an options" required>
					<option value=""></option>
					<?php if (!empty($users)) foreach ($users as $usr) : ?>
						<option value="<?= $usr->id_user; ?>" <?= (isset($data) && $data->prepared_by == $usr->id_user) ? 'selected' : ''; ?>><?= $usr->full_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>

		<div class="form-group row">
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
	</div>
</div>

<div class="modal-footer justify-content-between">
	<button type="button" class="btn btn-primary" id="save-guide-standalone"><i class="fa fa-save mr-1"></i> Save</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times mr-1"></i> Cancel</button>
</div>
</form>

<script>
$(document).ready(function() {
	$('.select2-modal-guide').select2({
		placeholder: 'Select Procedure',
		width: '100%',
		allowClear: true,
		dropdownParent: $('#modalGuide')
	});
	$('.select2-modal-prepared').select2({
		placeholder: 'Choose an options',
		width: '100%',
		allowClear: true,
		dropdownParent: $('#modalGuide')
	});
});
</script>
