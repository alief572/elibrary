<form id="form-upload" enctype="multipart/form-data">
	<input type="hidden" name="checksheet_detail_id" value="<?= $checksheet_detail_id; ?>">
	<div class="row mb-3">
		<label class="col-2 col-form-label">Name <span class="text-danger">*</span></label>
		<div class="col-6">
			<input type="text" name="name" id="name" placeholder="Checksheet Name" class="form-control">
		</div>
	</div>
	<div class="row mb-3">
		<label class="col-2 col-form-label">Periode & Frequency <span class="text-danger">*</span></label>
		<div class="col-6">
			<select name="periode" id="periode" class="form-control select2">
				<option value=""></option>
				<option value="1">Once Time</option>
				<option value="2">Weekly-Daily</option>
				<option value="3">Monthly-Daily</option>
				<option value="4">Weekly-Monthly</option>
				<option value="5">Yearly-Monthly</option>
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
		<tbody></tbody>
	</table>

	<button type="button" id="add-item" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Item</button>
</form>

<script>
	$('.select2').select2({
		width: '100%',
		placeholder: 'Choose an options',
		allowClear: true,
		// closeOnSelect: false
	})
</script>