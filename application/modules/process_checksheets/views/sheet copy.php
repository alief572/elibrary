<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h2 class="">New Checksheet</h2>
				</div>
				<div class="card-body overflow-auto">
					<form id="form-checksheet">
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Directory</label>
							<div class="col-md-4">:
								<input type="hidden" name="checksheet_data_number" value="<?= $data->number; ?>">
								<label for=""><?= $data->checksheet_name; ?></label>
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Sub Directory</label>
							<div class="col-md-4">:
								<label for=""><?= $data->checksheet_detail_name; ?></label>
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Frequency Execution</label>
							<div class="col-md-4">:
								<label for=""><?= $periode[$data->periode]; ?></label>
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Periode</label>
							<div class="col-md-4">
								<input type="text" name="periode_check" placeholder="Bulan, Tahun" id="periode-check" class="datepicker form-control">
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Checksheet Name</label>
							<div class="col-md-4">
								<input type="text" placeholder="Checksheet Name" name="checksheet_name" id="checksheet-name" class="form-control">
							</div>
						</div>
						<div class="row mb-3">
							<label for="" class="col-md-2 control-label">Frequency Checking</label>
							<div class="col-md-4">
								<select name="frequency_chekcking" id="frequency-chekcking" class="select2 form-control">
									<option value=""></option>
									<option value="1">Daily</option>
									<option value="2">Weekly</option>
									<option value="3">Monthly</option>
								</select>
							</div>
						</div>
						<hr>
						<h5>List Checksheets</h5>
						<div class="table-responsive" style="overflow-x:auto;">
							<table class="table table-bordered" style="width:<?= $width; ?>;">
								<thead class="table-light">
									<tr>
										<th rowspan="2" class="p-2" width="50">No</th>
										<th rowspan="2" class="p-2" width="">Items</th>
										<th rowspan="2" class="p-2" width="">Standard</th>
										<th colspan="7" class="p-2 text-center" width="20%">Result</th>
									</tr>
									<tr>

										<?php for ($i = 1; $i <= $count; $i++) : ?>
											<th><?php $name_col . " " . $i; ?></th>
										<?php endfor; ?>
									</tr>
								</thead>
								<tbody>
									<?php $n = 0;
									if ($items) foreach ($items as $it) : $n++; ?>
										<tr>
											<td>
												<?= $n; ?>
												<input type="hidden" name="results[<?= $n; ?>][checksheet_item_id]" value="<?= $it->id; ?>">
												<input type="hidden" name="results[<?= $n; ?>][item_name]" value="<?= $it->item_name; ?>">
												<input type="hidden" name="results[<?= $n; ?>][standard_check]" value="<?= $it->standard_check; ?>">
												<input type="hidden" name="results[<?= $n; ?>][check_type]" value="<?= $it->check_type; ?>">
											</td>
											<td><?= $it->item_name; ?></td>
											<td><?= $it->standard_check; ?></td>
											<?php for ($i = 1; $i <= $count; $i++) : ?>
												<td>
													<?php if ($it->check_type == 'boolean') : ?>
														<div class="" id="r_<?= $n . '_c_' . $i; ?>">
															<div class="d-flex justify-content-start align-items-center gap-4">
																<div class="form-check form-check-custom form-check-solid mr-10">
																	<label class="form-check-label font-weight-bolder text-dark">
																		<input class="form-check-input" type="radio" value="yes" name="results[<?= $n; ?>][n<?= $i; ?>]" id="boolean_<?= $i . $n; ?>">
																		Yes
																	</label>
																</div>
																<div class="form-check form-check-custom form-check-danger form-check-solid mr-10">
																	<label class="form-check-label font-weight-bolder text">
																		<input class="form-check-input" type="radio" value="no" name="results[<?= $n; ?>][n<?= $i; ?>]" id="boolean_<?= $i . $n; ?>">
																		No
																	</label>
																</div>
															</div>
														</div>
														<span class="invalid-feedback">Please choose one</span>
													<?php else : ?>
														<textarea name="results[<?= $n; ?>][n<?= $i; ?>]" id="r_<?= $n . '_c_' . $i; ?>" class="form-control" placeholder="Result"></textarea>
														<span class="invalid-feedback"> Can not be empty</span>
													<?php endif; ?>
												</td>
											<?php endfor; ?>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</form>
					<hr>
					<div class="text-right">
						<button type="button" class="btn btn-primary" id="save"><i class="fa fa-save"></i> Save</button>
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			width: '100%',
			allowClear: true,
			placeholder: 'Choose an options'
		})

		// $('.datepicker').MonthPicker('option', 'AltField': '#OtherField');

		$('.datepicker').MonthPicker({
			ShowIcon: false,
			MonthFormat: 'MM, yy',
			Button: false,
			MinMonth: "0",
			StartYear: 2023
			// Position: {
			// 	collision: 'fit flip'
			// }
		});

		$('input[type=month]').MonthPicker().css('backgroundColor', 'lightyellow');

		$('.datatable').DataTable()

		$(document).on('click', '#save', function() {
			const date = $('#date-checking').val();
			const operator = $('#checking-by').val();

			if (!date) {
				$('#date-checking').addClass('is-invalid')
				return false;
			}
			if (!operator) {
				$('#checking-by').addClass('is-invalid')
				return false;
			}

			$('#date-checking').removeClass('is-invalid')
			$('#checking-by').removeClass('is-invalid')

			var valid = 0;
			var validText = 0;
			for (let r = 1; r <= <?= count($items); ?>; r++) {
				validText
				for (let i = 1; i <= <?= $count; ?>; i++) {
					if ($('input[name="results[' + r + '][n' + i + ']"]').is(':checked') == false) {
						// console.log(r + '-n' + i + ' not checked')
						$('div#r_' + r + "_c_" + i).addClass('is-invalid')
						valid++
					} else {
						$('div#r_' + r + "_c_" + i).removeClass('is-invalid')
						valid--;
					}

					if ($('textarea[name="results[' + r + '][n' + i + ']"]').val() == '') {
						console.log($(this).val())
						$('textarea#r_' + r + "_c_" + i).addClass('is-invalid')
						validText++
					} else {
						$('textarea#r_' + r + "_c_" + i).removeClass('is-invalid')
						validText - 1;
					}
				}
			}
			console.log(valid);
			console.log(validText);
			console.log(valid + validText);
			if ((valid + validText) != 0) {
				return false
			}

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
									location.reload()
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