<form id="form-upload" enctype="multipart/form-data">
	<input type="hidden" name="number" value="<?= $data->number; ?>">
	<input type="hidden" name="id" value="<?= $data->id; ?>">
	<div class="row mb-3">
		<label class="col-2 col-form-label">Name <span class="text-danger">*</span></label>
		<div class="col-6">
			<input type="text" name="name" id="name" placeholder="Checksheet Name" class="form-control" value="<?= $data->name; ?>">
		</div>
	</div>
	<div class="row mb-3">
		<label class="col-2 col-form-label">Frequency <span class="text-danger">*</span></label>
		<div class="col-6">
			<select name="frequency_execution" id="frequency-execution" class="form-control select2">
				<option value=""></option>
				<option value="1" <?= ($data->frequency_execution == '1') ? 'selected' : ''; ?>>Once Time</option>
				<option value="2" <?= ($data->frequency_execution == '2') ? 'selected' : ''; ?>>Weekly-Daily</option>
				<option value="3" <?= ($data->frequency_execution == '3') ? 'selected' : ''; ?>>Monthly-Daily</option>
				<option value="4" <?= ($data->frequency_execution == '4') ? 'selected' : ''; ?>>Weekly-Monthly</option>
				<option value="5" <?= ($data->frequency_execution == '5') ? 'selected' : ''; ?>>Yearly-Monthly</option>
			</select>
		</div>
	</div>
	<hr>

	<h6>List Item Checksheet</h6>
	<table id="table-item" class="table table-sm table-bordered">
		<thead class="table-light">
			<tr>
				<th class="py-2" width="50">No</th>
				<th class="py-2" width="530">Item Check</th>
				<th class="py-2">Standard Check</th>
				<th class="py-2" width="180">Result Type Check</th>
				<th class="py-2" width="50">Opsi</th>
			</tr>
		</thead>
		<tbody>
			<?php $n = 0;
			if ($data_item) foreach ($data_item as $item) : $n++ ?>
				<tr>
					<td class="py-2 text-center">
						<?= $n; ?>
						<input type="hidden" name="items[<?= $n; ?>][id]" value="<?= $item->id; ?>">
					</td>
					<td class="py-2"><textarea class="form-control" name="items[<?= $n; ?>][item_name]" placeholder="Item Name"><?= $item->item_name; ?></textarea></td>
					<td class="py-2"><textarea class="form-control" name="items[<?= $n; ?>][standard_check]" placeholder="Standard Check"><?= $item->standard_check; ?></textarea></td>
					<td class="py-2">
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" <?= ($item->check_type == 'boolean') ? 'checked' : ''; ?> name="items[<?= $n; ?>][check_type]" id="check-type" value="boolean">
								Yes/No
							</label>
						</div>
						<div class="form-check">
							<label class="form-check-label">
								<input type="radio" class="form-check-input" <?= ($item->check_type == 'text') ? 'checked' : ''; ?> name="items[<?= $n; ?>][check_type]" id="check-type" value="text">
								Input Text
							</label>
						</div>

						<?php if ($item->check_type == 'boolean') : ?>
						<?php endif; ?>
					</td>
					<td class="py-2 text-center">
						<button type="button" data-id="<?= $item->id; ?>" class="remove-item btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<button type="button" id="add-item" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Item</button>
</form>
<style>
	video::-internal-media-controls-download-button {
		display: none;
	}

	video::-webkit-media-controls-enclosure {
		overflow: hidden;
	}

	video::-webkit-media-controls-panel {
		width: calc(100% + 30px);
		/* Adjust as needed */
	}
</style>
<script>
	$('.select2').select2({
		width: '100%',
		placeholder: 'Choose an options',
		allowClear: true,
		// closeOnSelect: false
	})
</script>