<div class="card-body">
	<input type="hidden" name="record_id" value="<?= $record->id; ?>">
	<div class="form-group row">
		<label class="col-3 col-form-label text-right">Move to :</label>
		<div class="col-9">
			<select name="target_folder_id" id="target_folder_id" class="form-control select2">
				<option value="root">Root (/) </option>
				<?php foreach ($folders as $folder) : ?>
					<?php if ($folder->id != $record->id && !in_array($folder->id, $descendants)) : ?>
						<option value="<?= $folder->id; ?>" <?= ($folder->id == $record->parent_id) ? 'selected' : ''; ?>>
							<?= str_repeat('&nbsp;', $folder->level); ?>
							<?= ($folder->level > 0) ? '└' : ''; ?>
							<?= $folder->name; ?>
						</option>
					<?php endif; ?>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="submit" class="btn btn-primary save-move" id="save-move"><i class="fa fa-save"></i> Move</button>
	<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
</div>
<script>
	function formatFolder(folder) {
		if (!folder.id) {
			return folder.text;
		}
		var icon = (folder.id == 'root') ? 'fa-home text-primary' : 'fa-folder text-warning';
		var $folder = $(
			'<span><i class="fa ' + icon + ' mr-2"></i> ' + folder.text + '</span>'
		);
		return $folder;
	};

	$('.select2').select2({
		width: '100%',
		placeholder: 'Choose folder',
		dropdownParent: $('#modelId'),
		templateResult: formatFolder,
		templateSelection: formatFolder,
		escapeMarkup: function(m) {
			return m;
		}
	});
</script>
