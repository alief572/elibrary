<form id="form-reference">
	<div class="row">
		<div class="col-md-10">
			<div class="mb-3 row flex-nowrap">
				<label for="" class="col-3 col-form-label font-weight-bold">Select Company</label>
				<div class="col-9">
					<select name="company_id" id="company_id" class="form-control select2">
						<option value=""></option>
						<?php foreach ($Companies as $comp) : ?>
							<option value="<?= $comp->id_perusahaan; ?>"><?= $comp->nm_perusahaan; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<!-- Branch -->
			<?php if ($branch): ?>
				<div class="mb-3 row flex-nowrap">
					<label for="" class="col-3 col-form-label font-weight-bold">Select Branch</label>
					<div class="col-9">
						<select name="branch_id" id="branch_id" class="form-control select2">
							<option value=""></option>
							<?php foreach ($branch as $b) : ?>
								<option value="<?= $b->id; ?>"><?= $b->branch_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</form>

<style>
	span.select2-selection.select2-selection--single.is-invalid {
		border-color: #f64e60;
		padding-right: calc(1.5em + 1.3rem);
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23F64E60' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23F64E60' stroke='none'/%3e%3c/svg%3e");
		background-repeat: no-repeat;
		background-position: right calc(0.375em + 0.325rem) center;
		background-size: calc(0.75em + 0.65rem) calc(0.75em + 0.65rem);
	}
</style>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: 'Choose an Option',
			width: '100%',
			allowClear: true,
		})
	})
</script>