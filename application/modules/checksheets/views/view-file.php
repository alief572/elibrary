<div class="row mb-3">
	<label class="col-12 col-md-3 col-form-label font-weight-bold">Name</label>
	<div class="col-12 col-md-9 pt-2">
		<?= $data->name; ?>
	</div>
</div>
<div class="row mb-3">
	<label class="col-12 col-md-3 col-form-label font-weight-bold">Periode & Frequency</label>
	<div class="col-12 col-md-9 pt-2">
		<?= isset($freq[$data->frequency_execution]) ? $freq[$data->frequency_execution] : '-'; ?>
	</div>
</div>
<hr>

<h6>List Item Checksheet</h6>
<div class="table-responsive">
	<table id="table-item" class="table table-sm table-bordered">
		<thead class="table-light">
			<tr>
				<th class="py-2 text-center" width="50">No</th>
				<th class="py-2" style="min-width: 200px;">Item Check</th>
				<th class="py-2" style="min-width: 250px;">Standard Check</th>
				<th class="py-2 text-center" width="180">Result Type Check</th>
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
					<td class="py-2">
						<?= $item->standard_check; ?>
						<?php if (!empty($item->upload_standard_check) && file_exists($item->upload_standard_check)) : ?>
							<div class="mt-2">
								<a href="<?= base_url($item->upload_standard_check); ?>" class="btn btn-xs btn-light-primary" target="_blank"><i class="fa fa-file mr-1"></i> View File</a>
							</div>
						<?php endif; ?>
					</td>
					<td class="py-2 text-center">
						<?php if ($item->check_type == 'boolean') : ?>
							<span class="badge badge-light-primary">Yes/No</span>
						<?php else : ?>
							<span class="badge badge-light-info">Input Text</span>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>