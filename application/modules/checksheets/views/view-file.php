<div class="row mb-3">
	<label class="col-2 col-form-label">Name</label>
	<div class="col-6">
		<?= $data->name; ?>
	</div>
</div>
<div class="row mb-3">
	<label class="col-2 col-form-label">Periode & Frequency</label>
	<div class="col-6">
		<?= $freq[$data->frequency_execution]; ?>
	</div>
</div>
<hr>

<h6>List Item Checksheet</h6>
<table id="table-item" class="table table-sm table-bordered">
	<thead class="table-light">
		<tr>
			<th class="py-2 text-center" width="50">No</th>
			<th class="py-2" width="530">Item Check</th>
			<th class="py-2">Standard Check</th>
			<th class="py-2" width="180">Result Type Check</th>
		</tr>
	</thead>
	<tbody>
		<?php $n = 0;
		if ($data_item) foreach ($data_item as $item) : $n++ ?>
			<tr>
				<td class="py-2 text-center">
					<?= $n; ?>
				</td>
				<td class="py-2"><?= $item->item_name; ?></td>
				<td class="py-2"><?= $item->standard_check; ?></td>
				<td class="py-2">
					<?php if ($item->check_type == 'boolean') : ?>
						<label for="">Yes/No</label>
					<?php else : ?>
						<label for="">Input Text</label>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>