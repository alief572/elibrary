<form id="form-checksheet">
	<div class="form-group row">
		<label for="" class="col-md-2">Directory</label>
		<div class="col-md-3">
			<select name="checksheet_id" id="checksheet_id" class="form-control select2">
				<option value=""></option>
				<?php if ($checksheets) foreach ($checksheets as $chk) : ?>
					<option value="<?= $chk->id; ?>"><?= $chk->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label for="" class="col-md-2">Sub Directory</label>
		<div class="col-md-3">
			<select name="checksheet_detail_id" id="checksheet_detail_id" class="form-control select2">
				<option value=""></option>
			</select>
		</div>
	</div>

	<table class="table table-sm table-bordered datatable">
		<thead class="table-light">
			<tr>
				<th class="p-2" width="5%">No</th>
				<th class="p-2">Checksheet Name</th>
				<th class="p-2">Frequency</th>
				<th class="p-2 text-center" width="10%">Opsi</th>
			</tr>
		</thead>
		<tbody>
			<!-- <?php if ($checksheets) $n = 0;
					foreach ($checksheets as $cs) : $n++; ?>
				<tr>
					<td class="py-2" width="5%"><?= $n; ?></td>
					<td class="py-2"><?= $cs->checksheet_detail_data_name; ?></td>
					<td class="py-2"><?= $fExecution[$cs->frequency_execution]; ?></td>
					<td class="py-2 text-center" width="10%">
						<a href="<?= base_url($this->uri->segment(1) . '/create_checksheet/' . $cs->id . '/' . $dir); ?>" class="btn btn-success btn-sm">Select <i class="fa fa-arrow-circle-right ml-1"></i></a>
					</td>
				</tr>
			<?php endforeach; ?> -->
		</tbody>
	</table>
</form>

<script>
	$(document).ready(function() {
		$('.select2').select2({
			width: '100%',
			allowClear: true,
			placeholder: 'Choose an options'
		})
		$('.datatable').DataTable()
	})
</script>