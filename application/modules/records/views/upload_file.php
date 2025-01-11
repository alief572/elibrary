<div class="modal-body">
	<div class="row">
		<label class="col-12 col-form-label"><span class="text-danger">*</span> Document Name :</label>
		<div class="col-12">
			<input type="hidden" id="id" name="forms[id]" class="form-control" value="<?= isset($data) ? $data->id : ''; ?>" />
			<input type="hidden" name="forms[procedure_id]" class="form-control" value="<?= $procedure_id; ?>" />
			<input type="text" class="form-control" id="description" placeholder="Document Name" name="forms[description]" value="<?= isset($data) ? $data->name : ''; ?>" autocomplete="off" />
			<span class="form-text text-danger invalid-feedback">Deskripsi harus di isi</span>
		</div>

		<div class="type-form">
			<?php if ($type == 'form') : ?>
				<input type="hidden" name="forms[type]" value="form">
			<?php elseif ($type == 'guide') : ?>
				<input type="hidden" name="forms[type]" value="guide">
			<?php elseif ($type == 'record') : ?>
				<input type="hidden" name="forms[type]" value="record">
			<?php endif; ?>
		</div>
	</div>

	<?php if ($type !== 'record') : ?>
		<div class="row">
			<label class="col-12 col-form-label"><span class="text-danger">*</span> Prepared By :</label>
			<div class="col-12">
				<select name="forms[prepared_by]" id="prepared_by" class="form-control select2">;
					<option value=""></option>
					<?php foreach ($users as $usr) : ?>
						<option value="<?= $usr->id_user; ?>" <?= (isset($data) && $data->prepared_by == $usr->id_user) ? 'selected' : ''; ?>><?= $usr->full_name; ?></option>
					<?php endforeach; ?>
				</select>
				<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
			</div>
		</div>

		<div class="row">
			<label class="col-12 col-form-label"><span class="text-danger">*</span> This document requires approval :</label>
			<!-- <div class="col-12 col-form-label">
					<div class="radio-inline">
						<label class="radio radio-primary">
							<input type="radio" name="forms[need_approval]" checked="checked" value="N" />
							<span></span>
							Need Approval
						</label>
						<label class="radio radio-primary">
							<input type="radio" name="forms[need_approval]" value="Y" />
							<span></span>
							Without Approval
						</label>
					</div>
					<span class="form-text text-muted">pilih salah satu</span>
				</div> -->
		</div>


		<div id="file-type">
			<div class="form-group row">
				<label class="col-lg-3 col-form-label text-lg-right"><span class="text-danger">*</span> Review By :</label>
				<div class="col-lg-9">
					<select name="forms[reviewer_id]" id="reviewer_id" class="form-control select2">;
						<option value=""></option>
						<?php foreach ($jabatan as $jbt) : ?>
							<option value="<?= $jbt->id; ?>" <?= (isset($data) && $data->reviewer_id == $jbt->id) ? 'selected' : ''; ?>><?= $jbt->nm_jabatan; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="form-text text-danger invalid-feedback"><span class="text-danger">*</span> Review By harus di isi</span>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-lg-3 col-form-label text-lg-right"><span class="text-danger">*</span> Approval By :</label>
				<div class="col-lg-9">
					<select name="forms[approval_id]" id="approval_id" class="form-control select2">;
						<option value=""></option>
						<?php foreach ($jabatan as $jbt) : ?>
							<option value="<?= $jbt->id; ?>" <?= (isset($data) && $data->approval_id == $jbt->id) ? 'selected' : ''; ?>><?= $jbt->nm_jabatan; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="form-text text-danger invalid-feedback">Approval By harus di isi</span>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-lg-3 col-form-label text-lg-right"><span class="text-danger">*</span> Distribusi :</label>
				<div class="col-lg-9">
					<select name="forms[distribute_id][]" multiple id="distribute_id" data-placeholder="Choose an options" class="form-control select2">;
						<option value=""></option>
						<?php foreach ($jabatan as $jbt) : ?>
							<option value="<?= $jbt->id; ?>" <?= isset($data) ? ((in_array($jbt->id, explode(',', $data->distribute_id))) ? 'selected' : '') : ''; ?>><?= $jbt->nm_jabatan; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="form-text text-danger invalid-feedback">Distribusi By harus di isi</span>
				</div>
			</div>
		</div>
	<?php endif; ?>

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

	<?php if ($type !== 'record') : ?>
		<!-- <div class="col-6">
				<div class="row">
					<label class="col-12 col-form-label">Nomor :</label>
					<div class="col-12">
						<input type="text" name="forms[number]" id="number" class="form-control" placeholder="Nomor" value="<?= isset($data) ? $data->number : ''; ?>">
						<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
					</div>
				</div>

				<div class="row">
					<label class="col-12 col-form-label">Determination Date :</label>
					<div class="col-12">
						<input type="date" name="forms[determination_date]" id="determination_date" class="form-control datepicker" placeholder="<?= date('Y-m-d'); ?>" value="<?= isset($data) ? $data->determination_date : ''; ?>">
						<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
					</div>
				</div>

				<div class="row">
					<label class="col-12 col-form-label">About :</label>
					<div class="col-12">
						<input type="text" name="forms[about]" id="about" class="form-control" placeholder="About" value="<?= isset($data) ? $data->about : ''; ?>">
						<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
					</div>
				</div>

				<div class="row">
					<label class="col-12 col-form-label">Status :</label>
					<div class="col-12">
						<input type="text" name="forms[doc_status]" id="doc_status" class="form-control" placeholder="Doc. Status" value="<?= isset($data) ? $data->doc_status : ''; ?>">
						<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
					</div>
				</div>

				<div class="row">
					<label class="col-12 col-form-label">Publisher :</label>
					<div class="col-12">
						<input type="text" name="forms[publisher]" id="publisher" class="form-control" placeholder="Publisher" value="<?= isset($data) ? $data->publisher : ''; ?>">
						<span class="form-text text-danger invalid-feedback">Prepared By harus di isi</span>
					</div>
				</div>
			</div> -->
	<?php endif; ?>
</div>

<div class="modal-footer justify-content-between align-items-center">
	<button type="submit" class="btn btn-primary save"><i class="fa fa-save"></i>Save</button>
	<button type="button" class="btn btn-danger" onclick="setTimeout(function(){$('#content_modal').html('')},500)" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
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