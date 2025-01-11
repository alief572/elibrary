<div class="modal-body">
	<div class="row">
		<div class="col-6">
			<div class="row">
				<label class="col-12 col-form-label"><span class="text-danger">*</span> Document Name :</label>
				<div class="col-12">
					<input type="hidden" id="id" name="forms[id]" class="form-control" value="<?= isset($data) ? $data->id : ''; ?>" />
					<input type="hidden" name="forms[procedure_id]" class="form-control" value="<?= isset($data) ? $data->procedure_id : $procedure_id; ?>" />
					<input type="hidden" name="forms[parent_id]" class="form-control" value="<?= isset($data) ? $data->parent_id :$parent_id; ?>" />
					<input type="text" class="form-control" id="description" placeholder="Document Name" name="forms[description]" value="<?= isset($data) ? $data->name : ''; ?>" autocomplete="off" />
					<span class="form-text text-danger invalid-feedback">Deskripsi harus di isi</span>
				</div>
				<div class="type-form">
					<input type="hidden" name="forms[type]" value="record">
				</div>
			</div>


			<div class="form-group row mb-0">
				<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
				<div class="col-12">
					<input type="file" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps" name="forms_image" id="image" class="form-control" placeholder="Upload File">
					<span class="form-text text-muted">File type : PDF</span>
					<span class="form-text text-danger invalid-feedback">Upload Document By harus di isi</span>
				</div>
				<?php if (isset($data)) : ?>
					<input type="hidden" name="forms[old_file]" id="old_file" value="<?= isset($data) ? $data->file_name : ''; ?>">
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<div class="modal-footer justify-content-between align-items-center">
	<button type="button" class="btn btn-primary save" id="save-record"><i class="fa fa-save"></i>Save</button>
	<button type="button" class="btn btn-danger" onclick="setTimeout(function(){$('#record-content').html('')},500)" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
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