<form id="form-upload" enctype="multipart/form-data">
	<input type="hidden" name="number" value="<?= $data->number; ?>">
	<input type="hidden" name="id" value="<?= $data->id; ?>">
	<div class="row mb-3">
		<label class="col-12 col-md-3 col-form-label font-weight-bold">Name <span class="text-danger">*</span></label>
		<div class="col-12 col-md-9">
			<input type="text" name="name" id="name" placeholder="Checksheet Name" class="form-control" value="<?= $data->name; ?>">
		</div>
	</div>
	<div class="row mb-3">
		<label class="col-12 col-md-3 col-form-label font-weight-bold">Frequency <span class="text-danger">*</span></label>
		<div class="col-12 col-md-9">
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
	<div class="table-responsive">
		<table id="table-item" class="table table-sm table-bordered">
			<thead class="table-light">
				<tr>
					<th class="py-2 text-center" width="50">No</th>
					<th class="py-2" style="min-width: 250px;">Item Check</th>
					<th class="py-2" style="min-width: 250px;">Standard Check</th>
					<th class="py-2 text-center" width="180">Result Type Check</th>
					<th class="py-2 text-center" width="50">Opsi</th>
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
						<td class="py-2">
							<textarea class="form-control mb-2" name="items[<?= $n; ?>][standard_check]" placeholder="Standard Check"><?= $item->standard_check; ?></textarea>
							<input type="file" name="items[<?= $n; ?>][upload_standard_check]" class="form-control" id="">
							<?php  
								if(!empty($item->upload_standard_check) && file_exists($item->upload_standard_check)) {
									echo '<div class="mt-2"><a href="'.base_url($item->upload_standard_check).'" class="btn btn-xs btn-light-primary" target="_blank"><i class="fa fa-file mr-1"></i> View File</a></div>';
								}
							?>
						</td>
						<td class="py-2">
							<div class="form-check mb-1">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" <?= ($item->check_type == 'boolean') ? 'checked' : ''; ?> name="items[<?= $n; ?>][check_type]" id="check-type-bool-<?= $n; ?>" value="boolean">
									Yes/No
								</label>
							</div>
							<div class="form-check">
								<label class="form-check-label">
									<input type="radio" class="form-check-input" <?= ($item->check_type == 'text') ? 'checked' : ''; ?> name="items[<?= $n; ?>][check_type]" id="check-type-text-<?= $n; ?>" value="text">
									Input Text
								</label>
							</div>
						</td>
						<td class="py-2 text-center">
							<button type="button" data-id="<?= $item->id; ?>" class="remove-item btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

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