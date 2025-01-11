<form id="form-title">
	<div class="mb-5">
		<input type="hidden" name="id" class="form-control" value="<?= ((isset($data) && $data) ? $data->id : ''); ?>" />
		<input type="hidden" name="regulation_id" class="form-control" value="<?= ((isset($data) && $data) ? $data->regulation_id : (($reg_id) ?: '')); ?>" />
		<label class="col-form-label font-weight-bold">Pasal</label>
		<input type="text" name="name" id="name" placeholder="Pasal 1" class="form-control" value="<?= (isset($data) && $data) ? $data->name : ''; ?>">
	</div>
	<div class="mb-5">
		<label class="col-form-label font-weight-bold">Order</label>
		<input type="number" name="order" id="order" placeholder="Order" class="form-control w-25" value="<?= (isset($data) && $data) ? $data->order : ''; ?>">
	</div>
</form>