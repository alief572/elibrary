<div class="card-body">
	<input type="hidden" name="modal_action" id="modal_action" value="<?= htmlspecialchars($action); ?>">
	<input type="hidden" name="modal_record_ids" id="modal_record_ids" value="<?= htmlspecialchars($ids_str); ?>">
	<input type="hidden" name="modal_procedure_id" id="modal_procedure_id" value="<?= htmlspecialchars($procedure_id); ?>">

	<div class="alert alert-custom alert-light-<?= ($action == 'copy') ? 'primary' : 'warning'; ?> mb-4 p-3">
		<div class="alert-icon">
			<i class="fa <?= ($action == 'copy') ? 'fa-copy text-primary' : 'fa-cut text-warning'; ?> fa-2x"></i>
		</div>
		<div class="alert-text">
			<strong class="h6"><?= ($action == 'copy') ? 'Salin / Copy' : 'Pindahkan / Move'; ?> (<?= count($records); ?> item):</strong>
			<ul class="mb-0 mt-2 pl-4" style="max-height: 120px; overflow-y: auto;">
				<?php foreach ($records as $r) : ?>
					<li>
						<i class="fa <?= ($r->flag_type == 'FOLDER') ? 'fa-folder text-warning' : 'fa-file-alt text-primary'; ?> mr-1"></i>
						<?= htmlspecialchars($r->name); ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-3 col-form-label text-right font-weight-bold">
			<?= ($action == 'copy') ? 'Copy to :' : 'Move to :'; ?>
		</label>
		<div class="col-9">
			<select name="modal_target_folder_id" id="modal_target_folder_id" class="form-control select2">
				<option value="root">Root (/) </option>
				<?php foreach ($folders as $folder) : ?>
					<?php if ($action == 'copy' || !in_array($folder->id, $descendants)) : ?>
						<option value="<?= $folder->id; ?>">
							<?= str_repeat('&nbsp;&nbsp;&nbsp;', $folder->level); ?>
							<?= ($folder->level > 0) ? '└─ ' : ''; ?>
							<?= htmlspecialchars($folder->name); ?>
						</option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-<?= ($action == 'copy') ? 'success' : 'primary'; ?>" id="btn-submit-destination-modal">
		<i class="fa <?= ($action == 'copy') ? 'fa-paste' : 'fa-check'; ?>"></i>
		<?= ($action == 'copy') ? 'Paste / Copy Here' : 'Move Here'; ?>
	</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
</div>
<script>
	function formatModalFolder(folder) {
		if (!folder.id) {
			return folder.text;
		}
		var icon = (folder.id == 'root') ? 'fa-home text-primary' : 'fa-folder text-warning';
		return $('<span><i class="fa ' + icon + ' mr-2"></i> ' + folder.text + '</span>');
	}

	$('#modal_target_folder_id').select2({
		width: '100%',
		placeholder: 'Choose destination folder',
		dropdownParent: $('#modelId'),
		templateResult: formatModalFolder,
		templateSelection: formatModalFolder,
		escapeMarkup: function(m) { return m; }
	});
</script>
