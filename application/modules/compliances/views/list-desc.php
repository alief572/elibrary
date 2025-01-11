<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">

			<form id="form">
				<div class="card card-stretch shadow card-custom">
					<div class="card-header">
						<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					</div>
					<div class="card-body">
						<?php if (isset($data) && $data) : ?>
							<input type="hidden" name="compliance_id" value="<?= $compliance->id; ?>">
							<input type="hidden" name="regulation_id" value="<?= $compliance->regulation_id; ?>">
							<input type="hidden" name="reference_id" value="<?= $compliance->reference_id; ?>">
							<div class="row">
								<div class="col-2">
									<div class="d-flex justify-content-between">
										<span class="font-weight-bolder h5">Company</span>
										<span>:</span>
									</div>
								</div>
								<div class="col-10">
									<h5 for="" class="font-weight-bolder mb-5"><?= $compliance->nm_perusahaan; ?></h5>
								</div>
							</div>
							<div class="row">
								<div class="col-2">
									<div class="d-flex justify-content-between">
										<span class="font-weight-bolder h5">Regulation</span>
										<span>:</span>
									</div>
								</div>
								<div class="col-10">
									<h5 for="" class="font-weight-bolder mb-5"><?= $compliance->name; ?></h5>
								</div>
							</div>
							<hr>
							<button type="button" class="btn btn-primary mb-5" id="save_detail"><i class="fa fa-save"></i>Save</button>
							<a href="<?= base_url($this->session->previous_page); ?>" class="btn btn-danger mb-5"><i class="fa fa-reply"></i>Back</a>

							<table class="table datatable table-bordered table-sm">
								<thead>
									<tr class="text-center">
										<th style="vertical-align: middle;" width="50">No</th>
										<th style="vertical-align: middle;" width="100">Pasal</th>
										<th style="vertical-align: middle;" width="100">Ayat</th>
										<th style="vertical-align: middle;">Description</th>
										<th style="vertical-align: middle;" width="300">Compliance Description</th>
										<th style="vertical-align: middle;" width="150">Status</th>
										<th style="vertical-align: middle;" width="10">Opport/ Risks</th>
										<th style="vertical-align: middle;" width="100">Doc.</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$n = 0;
									foreach ($ArrPasal as $k => $dt) : ?>
										<?php foreach ($dt as $j => $l) : $n++; ?>
											<tr class="">
												<td>
													<?= $n; ?>
													<input type="hidden" name="detail[<?= $n; ?>][id]" id="id_<?= isset($ArrCompl[$l->id]->id) ? $ArrCompl[$l->id]->id : ''; ?>" value="<?= isset($ArrCompl[$l->id]->id) ? $ArrCompl[$l->id]->id : ''; ?>">
													<input type="hidden" name="detail[<?= $n; ?>][prgh_id]" value="<?= $l->id; ?>">
													<input type="hidden" name="detail[<?= $n; ?>][pasal_id]" value="<?= $l->pasal_id; ?>">
												</td>
												<?php if ($j == '0') : ?>
													<th rowspan="<?= count($dt); ?>" class="text-center" style="vertical-align:middle;">
														<span class=""><?= $l->pasal_name; ?></span>
													</th>
												<?php endif; ?>
												<td class="text-center">
													<?= $l->name; ?>
												</td>
												<td>
													<input type="hidden" name="detail[<?= $n; ?>][description]" value="<?= $l->description; ?>">
													<?= $l->description; ?>
												</td>
												<td>
													<textarea name="detail[<?= $n; ?>][complience_desc]" class="form-control" placeholder="Description"><?= (isset($ArrCompl[$l->id]) ? $ArrCompl[$l->id]->compliance_desc : ''); ?></textarea>
												</td>
												<td>
													<select name="detail[<?= $n; ?>][status]" class="form-control required select2" data-placeholder="Choose an options" data-allow-clear="true">
														<option value=""></option>
														<option value="CMP" <?= (isset($ArrCompl[$l->id]) && $ArrCompl[$l->id]->status == 'CMP') ? 'selected' : ''; ?>>Compliance</option>
														<option value="NCM" <?= (isset($ArrCompl[$l->id]) && $ArrCompl[$l->id]->status == 'NCM') ? 'selected' : ''; ?>>Not Compliance</option>
														<option value="NAP" <?= (isset($ArrCompl[$l->id]) && $ArrCompl[$l->id]->status == 'NAP') ? 'selected' : ''; ?>>Not Aplicable</option>
													</select>
													<span class="invalid-feedback">Status can't be empty</span>
												</td>
												<td class="text-center" style="vertical-align: middle;">
													<button type="button" class="btn btn-xs btn-icon btn-light-primary risk_opp" data-id="<?= $l->id; ?>" data-toggle="modal" data-target="#modalData<?= $l->id; ?>"><i class="fa fa-list-alt"></i></button>

													<!-- Modal -->
													<div class="modal fade" id="modalData<?= $l->id; ?>" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalDataLabel" aria-hidden="true">
														<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%;">
															<div class="modal-content">
																<div class="modal-header">
																	<h5 class="modal-title">Opportunities and Risk</h5>
																	<button type="button" class="btn" data-dismiss="modal" aria-label="Close">
																		<span aria-hidden="true">&times;</span>
																	</button>
																</div>
																<div class="modal-body text-left">
																	<table class="table table-bordered table-sm table_data_<?= $l->id; ?>">
																		<thead>
																			<tr>
																				<th width="20">No</th>
																				<th width="150">Category</th>
																				<th width="">Description</th>
																				<th width="300">Action Plan</th>
																				<th width="80">PIC</th>
																				<th width="80">Due Date</th>
																			</tr>
																		</thead>
																		<tbody>
																			<?php if (isset($ArrOpports[$l->id])) : $i = 0; ?>
																				<?php foreach ($ArrOpports[$l->id] as $opport) : $i++; ?>
																					<tr class="text-left rows">
																						<td class="text-center">
																							<input type="hidden" name="opport[<?= $l->id; ?>][<?= $i; ?>][id]" value="<?= $opport->id; ?>">
																							<input type="hidden" name="opport[<?= $l->id; ?>][<?= $i; ?>][prgh_id]" value="<?= $l->id; ?>">
																							<button type="button" class="btn btn-xs btn-icon btn-light-danger del_list">x</button>
																						</td>
																						<td class="text-left">
																							<select name="opport[<?= $l->id; ?>][<?= $i; ?>][category]" class="form-select select2" data-placeholder="Choose an options" data-allow-clear="true">
																								<option value=""></option>
																								<option value="OPP" <?= ($opport->category == 'OPP') ? 'selected' : ''; ?>>Opportunities</option>
																								<option value="RSK" <?= ($opport->category == 'RSK') ? 'selected' : ''; ?>>Risk</option>
																							</select>
																						</td>
																						<td class="">
																							<textarea name="opport[<?= $l->id; ?>][<?= $i; ?>][description]" class="form-control" placeholder="Description"><?= $opport->description; ?></textarea>
																						</td>
																						<td>
																							<textarea name="opport[<?= $l->id; ?>][<?= $i; ?>][action_plan]" class="form-control" placeholder="Action Plan"><?= $opport->action_plan; ?></textarea>
																						</td>
																						<td>
																							<select name="opport[<?= $l->id; ?>][<?= $i; ?>][pic]" class="form-select select2" data-placeholder="Choose an options" data-allow-clear="true">
																								<option value=""></option>
																								<?php if ($users) : ?>
																									<?php foreach ($users as $usr) : ?>
																										<option value="<?= $usr->id_user; ?>" <?= ($opport->pic == $usr->id_user) ? 'selected' : ''; ?>><?= $usr->full_name; ?></option>
																									<?php endforeach; ?>
																								<?php endif; ?>
																							</select>
																						</td>
																						<td>
																							<input type="date" name="opport[<?= $l->id; ?>][<?= $i; ?>][due_date]" class="form-control" value="<?= ($opport->due_date) ?: ''; ?>">
																						</td>
																					</tr>
																				<?php endforeach; ?>
																			<?php endif; ?>

																		</tbody>
																	</table>
																	<hr>
																	<button type="button" class="btn btn-sm btn-primary text-left add_list" data-id="<?= $l->id; ?>"><i class="fa fa-plus"></i>Add</button>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-success" data-dismiss="modal">
																		<i class="fa fa-check"></i>OK
																	</button>
																</div>
															</div>
														</div>
													</div>
												</td>
												<td class="align-middle text-center">
													<?php if(isset($ArrCompl[$l->id])): ?>
													<div class="d-none">
														<input type="file" accept="application/pdf" class="upload_file" data-id="<?= $ArrCompl[$l->id]->id; ?>" id="upload_<?= $ArrCompl[$l->id]->id; ?>" name="detail[<?= $n; ?>][status]">
													</div>
													<?php if ($ArrCompl[$l->id]->file) : ?>
														<button type="button" title="Change File" onclick="$('#upload_<?= $ArrCompl[$l->id]->id; ?>').click()" class="btn btn-xs btn-icon btn-warning" id="btn-upload-<?= $ArrCompl[$l->id]->id; ?>"><i class="fa fa-upload"></i></button>
														<a target="_blank" title="View File" href="<?= base_url($this->uri->segment(1) . '/file/' . $ArrCompl[$l->id]->file); ?>?_init=<?= $ArrCompl[$l->id]->id; ?>" class="btn btn-xs btn-icon btn-primary">
															<div class="fa fa-file"></div>
														</a>
														<button type="button" data-id="<?= $ArrCompl[$l->id]->id; ?>" class="btn btn-xs btn-icon btn-danger remove-file">
															<div class="fa fa-times" title="Remove File"></div>
														</button>
													<?php else : ?>
														<button type="button" title="Upload File" onclick="$('#upload_<?= $ArrCompl[$l->id]->id; ?>').click()" class="btn btn-xs btn-icon btn-warning" id="btn-upload-<?= $ArrCompl[$l->id]->id; ?>"><i class="fa fa-upload"></i></button>
													<?php endif; ?>
													<?php endif; ?>
												</td>
											</tr>
									<?php endforeach;
									endforeach; ?>
								</tbody>
							</table>
						<?php else : ?>
							<div class="text-center">
								<h3 class="text-center text-muted">Data not valid</h3>
								<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i> Back</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</form>

		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.select2').select2({
			width: "100%"
		})
	})


	$(document).on('click', '.add_list', function() {
		let id = $(this).data('id')
		let n = parseInt($('table.table_data_' + id + ' tbody tr').length) + 1
		let html = '';
		html += `
      <tr class="text-left rows">
        <td class="text-center">
			<input type="hidden" name="opport[` + id + `][` + n + `][prgh_id]" value="` + id + `">
          	<button type="button" class="btn btn-xs btn-icon btn-light-danger del_list">x</button>
        </td>
        <td class="text-left">
          <select name="opport[` + id + `][` + n + `][category]" class="form-select select2" data-placeholder="Choose an options" data-allow-clear="true">
            <option value=""></option>
            <option value="OPP">Opportunities</option>
            <option value="RSK">Risk</option>
          </select>
        </td>
        <td class=""><textarea name="opport[` + id + `][` + n + `][description]" class="form-control" placeholder="Description"></textarea></td>
        <td><textarea name="opport[` + id + `][` + n + `][action_plan]" class="form-control" placeholder="Action Plan"></textarea></td>
        <td>
          <select name="opport[` + id + `][` + n + `][pic]" class="form-select select2" data-placeholder="Choose an options" data-allow-clear="true">
            <option value=""></option>
            <?php if ($users) : ?>
              <?php foreach ($users as $usr) : ?>
                <option value="<?= $usr->id_user; ?>"><?= $usr->full_name; ?></option>
              <?php endforeach; ?>
            <?php endif; ?>
          </select>
        </td>
        <td><input type="date" name="opport[` + id + `][` + n + `][due_date]" class="form-control"></td>
      </tr>`;

		$('table.table_data_' + id + ' tbody').append(html)

		$('.select2').select2({
			width: "100%"
		})

	})

	$(document).on('click', '.del_list', function() {
		$(this).parents('tr.rows').remove()
	})

	/* SAVE */
	$(document).on('click', '#save_detail', function() {
		const valid = $('.required').length
		let n = 0;
		$('.required').each(function() {
			// console.log($(this).select2('val'));
			if ($(this).select2('val') == '') {
				$(this).addClass('is-invalid')
				n++
			} else {
				$(this).removeClass('is-invalid')
			}
		})

		if (n > 0) {
			console.log(n);
			return false
		}
		// $('.required').removeClass('is-invalid')



		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this data?',
			icon: 'question',
			showCancelButton: true
		}).then((value) => {
			console.log(value);
			let formdata = new FormData($('#form')[0]);
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save',
					data: formdata,
					dataType: 'JSON',
					type: 'POST',
					processData: false,
					contentType: false,
					cache: false,
					success: function(result) {
						if (result.status == 1) {
							Swal.fire({
								title: 'Success!',
								text: result.msg,
								icon: 'success',
							}).then(() => {
								location.reload();
							})
						} else {
							Swal.fire({
								title: 'Warning!',
								text: result.msg,
								icon: 'warning',
							})
						}
					},
					error: function() {
						Swal.fire('Error!!', 'Server timeout', 'error', 3000)
					}
				})
			}
		})
	})

	$(document).on('change', '.upload_file', function() {
		let id = $(this).data('id')
		let btn = $('#btn-upload-' + id)
		let myFile = $('#upload_' + id)[0]
		let fd = new FormData()
		fd.append('file', myFile.files[0])
		fd.append('id', id)
		$.ajax({
			url: siteurl + active_controller + 'saveFile',
			data: fd,
			dataType: 'JSON',
			type: 'POST',
			processData: false,
			contentType: false,
			// cache: false,
			beforeSend: function() {
				btn.html('<i class="fa fa-spinner spinner spinner-white spinner-sm spinner-center" aria-hidden="true"></i>')
			},
			complete: function() {
				btn.html('<i class="fa fa-s" aria-hidden="true"></i>')
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire({
						title: 'Success!',
						text: result.msg,
						icon: 'success',
					}).then(() => {
						location.reload();
					})
				} else {
					Swal.fire({
						title: 'Warning!',
						text: result.msg,
						icon: 'warning',
					})
				}
			},
			error: function() {
				Swal.fire('Error!!', 'Server timeout', 'error', 3000)
			}
		})
	})

	$(document).on('click', '.remove-file', function() {
		const id = $(this).data('id')
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to remove this file?',
				icon: 'question',
				showCancelButton: true
			}).then((value) => {
				console.log(value);
				let formdata = new FormData($('#form')[0]);
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'removeFile',
						data: {
							id
						},
						dataType: 'JSON',
						type: 'POST',
						success: function(result) {
							if (result.status == 1) {
								Swal.fire({
									title: 'Success!',
									text: result.msg,
									icon: 'success',
								}).then(() => {
									location.reload();
								})
							} else {
								Swal.fire({
									title: 'Warning!',
									text: result.msg,
									icon: 'warning',
								})
							}
						},
						error: function() {
							Swal.fire('Error!!', 'Server timeout', 'error', 3000)
						}
					})
				}
			})
		}
	})

	function validation(form) {

	}
</script>