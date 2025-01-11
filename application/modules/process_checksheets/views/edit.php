<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h2 class="">Edit Checksheet</h2>
				</div>
				<div class="card-body overflow-auto">
					<form id="form-checksheet">
						<input type="hidden" name="id" value="<?= $data->id; ?>">
						<input type="hidden" name="dir" value="<?= $dataDir->id; ?>">
						<input type="hidden" name="number" value="<?= isset($data->number) ? $data->number : ''; ?>">
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Directory</label>
							<div class="col-md-4">:
								<i class="fa fa-folder text-warning"></i>
								<label for=""><?= $dataDir->process_name; ?></label>
								<i class="fa fa-angle-right"></i>
								<label for=""><?= $dataDir->sub_name; ?></label>
								<i class="fa fa-angle-right"></i>
								<label for=""><?= $dataDir->dir_name; ?></label>
							</div>
						</div>

						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Checksheet File</label>
							<div class="col-md-4">:
								<input type="hidden" name="checksheet_data_number" value="<?= $data->checksheet_data_number; ?>">
								<label for=""><?= $data->checksheet_name; ?></label>
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Freq. Execution</label>
							<div class="col-md-4">:
								<input type="hidden" name="frequency_execution" value="<?= $data->frequency_execution; ?>">
								<label for=""><?= $fExecution[$data->frequency_execution]; ?></label>
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Periode</label>
							<div class="col-md-4">
								<input type="text" autocomplete="off" placeholder="Bulan, Tahun" value="<?= date_format(date_create($data->periode), 'M, Y'); ?>" id="periode-check" class="datepicker form-control">
								<input type="hidden" name="periode" value="<?= $data->periode; ?>" class="form-control">
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Checksheet Name</label>
							<div class="col-md-4">
								<input type="text" placeholder="Checksheet Name" value="<?= $data->checksheet_name; ?>" name="checksheet_name" id="checksheet-name" class="form-control">
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Frequency Checking</label>
							<div class="col-md-4">
								<select name="frequency_checking" id="frequency-checking" class="select2 form-control">
									<option value=""></option>
									<option value="1" <?= ($data->frequency_checking == '1') ? 'selected' : ''; ?>>Daily</option>
									<option value="2" <?= ($data->frequency_checking == '2') ? 'selected' : ''; ?>>Weekly</option>
									<option value="3" <?= ($data->frequency_checking == '3') ? 'selected' : ''; ?>>Monthly</option>
								</select>
							</div>
						</div>
						<hr>
						<h5>List Checksheets</h5>
						<div class="table-responsive" style="overflow-x:auto;">
							<table class="table table-bordered">
								<thead class="table-light">
									<tr>
										<th class="p-2" width="50">No</th>
										<th class="p-2" width="">Items</th>
										<th class="p-2" width="">Standard</th>
										<th class="p-2 text-center">Result</th>
									</tr>
								</thead>
								<tbody>
									<?php $n = 0;
									if ($items) foreach ($items as $it) : $n++; ?>
										<tr>
											<td>
												<?= $n; ?>
											</td>
											<td><?= $it->item_name; ?></td>
											<td><?= $it->standard_check; ?></td>
											<td>
												<?php if ($it->check_type == 'boolean') : ?>
													<span>Yes/No</span>
												<?php else : ?>
													<span>Input Text</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</form>
					<hr>
					<div class="text-right">
						<button type="button" class="btn btn-primary" id="save"><i class="fa fa-save"></i> Save</button>
						<a href="<?= base_url($this->uri->segment(1) . '/?p=' . $data->process_id . "&sub=".$dataSub2->id_sub."&sub2=" . $data->sub_id . "&checksheet=" . $data->dir_id); ?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	span.select2-selection--single.is-invalid {
		border-color: #f64e60 !important;
		padding-right: calc(1.5em + 1.3rem);
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23F64E60' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23F64E60' stroke='none'/%3e%3c/svg%3e");
		background-repeat: no-repeat;
		background-position: right calc(0.375em + 0.325rem) center;
		background-size: calc(0.75em + 0.65rem) calc(0.75em + 0.65rem);
	}
</style>
<script>
	$(document).ready(function() {
		$('.datepicker').MonthPicker({
			ShowIcon: false,
			MonthFormat: 'MM, yy',
			AltField: '#periode-date',
			AltFormat: 'yy-mm-dd',
			Button: false,
			MinMonth: "0",
			StartYear: 2023,
			Disabled: true,
			Position: {
				collision: 'fit flip'
			}
		});


		$('input[type=month]').MonthPicker().css('backgroundColor', 'lightyellow');

		$('.datatable').DataTable()

		$(document).on('click', '#save', function() {
			const periode = $('#periode-check').val();
			const checksheet_name = $('#checksheet-name').val();
			const frChecking = $('#frequency-checking').val();

			if (!periode) {
				$('#periode-check').addClass('is-invalid')
				return false;
			}

			if (!checksheet_name) {
				$('#checksheet-name').addClass('is-invalid')
				return false;
			}

			$('select#frequency-checking').next().find('span.selection .select2-selection.select2-selection--single').removeClass('is-invalid')
			if (!frChecking) {
				$('select#frequency-checking').next().find('span.selection .select2-selection.select2-selection--single').addClass('is-invalid')
				return false;
			}

			$('#periode-check').removeClass('is-invalid')
			$('#checksheet-name').removeClass('is-invalid')
			$('#frequency-checking').removeClass('is-invalid')

			const formdata = new FormData($('#form-checksheet')[0])
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to save this new directory?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'save_checksheet',
						dataType: 'JSON',
						type: 'POST',
						data: formdata,
						contentType: false,
						processData: false,
						cache: false,
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									window.location.href = siteurl + active_controller + '?p=' + <?= $data->process_id; ?> + '&sub='+ <?= $dataSub2->id_sub ?> +'&sub2=' + <?= $data->sub_id; ?> + '&checksheet=' + <?= $data->dir_id; ?>
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		})
	})
</script>