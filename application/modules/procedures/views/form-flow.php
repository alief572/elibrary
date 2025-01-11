<input type="hidden" name="procedure_id" value="<?= $procedure_id; ?>">
<div class="modal-body">
	<div class="form-group">
		<label class="">Nomor <span class="text-danger">*</span></label>
		<div class="">
			<input type="hidden" name="flow[id]" class="form-control" value="<?= ($flow) ? $flow->id : ''; ?>">
			<input type="text" name="flow[number]" id="number" class="form-control" value="<?= ($flow) ? $flow->number : ''; ?>" required placeholder="Nomor" aria-describedby="helpId">
			<small class="text-danger invalid-feedback">Nomor</small>
		</div>
	</div>
	<div class="form-group">
		<label class="">PIC <span class="text-danger">*</span></label>
		<div class="">
			<input type="text" name="flow[pic]" id="pic" class="form-control" value="<?= ($flow) ? $flow->pic : ''; ?>" required placeholder="PIC" aria-describedby="helpId">
			<small class="text-danger invalid-feedback">PIC</small>
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="">Deskripsi <span class="text-danger">*</span></label>
		<div class="">
			<textarea rows="5" name="flow[description]" id="description" class="form-control" placeholder="Deskripsi" aria-describedby="helpId"><?= ($flow) ? $flow->description : ''; ?></textarea>
			<small class="text-danger invalid-feedback">Deskripsi</small>
		</div>
	</div>
	<div class="form-group">
		<label class="">Dok. Terkait</label>
		<h5 class="">Form</h5>
		<div class="mb-3">
			<!-- <textarea rows="5" name="flow[relate_doc]" id="relate_doc" class="form-control" required placeholder="Dokumen terkait" aria-describedby="helpId" /></textarea> -->
			<select multiple name="flow[relate_doc][]" class="select2 form-control">
				<?php $relDocs = json_decode($flow->relate_doc); ?>
				<?php if ($forms) : ?>
					<?php foreach ($forms as $form) : ?>
						<option value="<?= $form->id; ?>" <?= ($relDocs) ? (in_array($form->id, $relDocs) ? 'selected' : '') : ''; ?>><?= $form->name; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<small class="text-danger invalid-feedback">Dokumen terkait</small>
		</div>

		<h5 class="">IK</h5>
		<div class="mb-3">
			<!-- <textarea rows="5" name="flow[relate_doc]" id="relate_doc" class="form-control" required placeholder="Dokumen terkait" aria-describedby="helpId" /></textarea> -->
			<select multiple name="flow[relate_ik_doc][]" class="select2 form-control">
				<?php $relDocs = json_decode($flow->relate_ik_doc); ?>
				<?php if ($guides) : ?>
					<?php foreach ($guides as $guide) : ?>
						<option value="<?= $guide->id; ?>" <?= ($relDocs) ? (in_array($guide->id, $relDocs) ? 'selected' : '') : ''; ?>><?= $guide->name; ?></option>
					<?php endforeach; ?>
				<?php endif; ?>
			</select>
			<small class="text-danger invalid-feedback">Dokumen IK terkait</small>
		</div>

	</div>
</div>
<div class="modal-footer justify-content-between align-items-center mb-3">
	<button type="submit" class="btn btn-sm btn-primary min-w-100px save"><i class="fas fa-save"></i>Save</button>
	<button type="button" class="btn btn-sm btn-danger w-100px" onclick="setTimeout(function(){$('#content_modal').html('')},500)" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
</div>

<script>
	$('.select2').select2({
		placeholder: 'Choose an options',
		width: '100%',
		allowClear: true
	})
</script>