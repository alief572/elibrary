<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<div class="mt-4 float-right ">
						<!-- <a href="<?= base_url($this->uri->segment(1) . '/add'); ?>" class="btn btn-primary" title="Add Company Audit">
							<i class="fa fa-plus mr-1"></i>Add Company Audit
						</a> -->
						<button class="btn btn-primary" id="add" title="Add Company Reference"><i class="fa fa-plus"></i> Add Company Reference</button>
					</div>
				</div>
				<div class="card-body">
					<!-- Nav tabs -->
					<!-- <ul class="nav nav-tabs nav-pills pb-3" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active btn-sm" id="Process-tab" data-toggle="tab" data-target="#Process" type="button" role="tab" aria-controls="Process" aria-selected="true">Process <span class="badge badge-circle badge-white text-primary ml-2"><?= count($data); ?></span></button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link btn-sm" id="Done-tab" data-toggle="tab" data-target="#Done" type="button" role="tab" aria-controls="Done" aria-selected="false">Done <span class="badge badge-circle badge-white text-primary ml-2"><?= count($done); ?></span></button>
						</li>
					</ul> -->

					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane fade active show" id="Process" role="tabpanel" aria-labelledby="Process-tab">
							<table class="table table-bordered table-sm table-hover datatable">
								<thead class="text-center table-light">
									<tr class="text-center">
										<th width="50">No.</th>
										<th class="text-left p-2" width="150">Company</th>
										<th class="text-left p-2" width="150">Branch</th>
										<th class="p-2" width="150">Standard</th>
										<th class="p-2">Regulation</th>
										<th class="p-2" width="150">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($data) && $data) :
										$n = 0;
										foreach ($data as $dt) : $n++; ?>
											<tr class="text-center p-2">
												<td class="align-top"><?= $n; ?></td>
												<td class="text-left p-2" style="vertical-align: top;"><?= $dt->nm_perusahaan; ?></td>
												<td class="text-left p-2" style="vertical-align: top;"><?= ($dt->branch_name) ?: 'Main Company'; ?></td>
												<td class="p-2" style="vertical-align: top;">
													<?php if (isset($ArrStd[$dt->id])) : ?>
														<?= implode(",<br>", array_slice($ArrStd[$dt->id], 0, 5)); ?>
														<br>
														<?php if (count($ArrStd[$dt->id]) - 5 > 0): ?>
															<button type="button" class="btn btn-link">More <?= count($ArrStd[$dt->id]) - 5; ?>+</button>
														<?php endif; ?>
													<?php endif; ?>
												</td>
												<td class="p-2 text-left" style="vertical-align: top;">
													<?php if (isset($ArrReg[$dt->id])) : ?>
														<ul class="text-left mb-0 pl-5">
															<?php foreach (array_slice($ArrReg[$dt->id], 0, 3) as $reg) : ?>
																<li><?= $reg; ?></li>
															<?php endforeach; ?>
														</ul>
														<?php if (count($ArrReg[$dt->id]) - 3 > 0): ?>
															<button type="button" class="btn btn-link">More <?= count($ArrReg[$dt->id]) - 3; ?>+</button>
														<?php endif; ?>
													<?php endif; ?>
												</td>
												<td class="p-2" style="vertical-align: top;">
													<button type="button" class="btn btn-xs btn-icon rounded-circle btn-info view" data-id="<?= $dt->id; ?>" title="View Data"><i class="fa fa-search"></i></button>
													<a href="<?= base_url($this->uri->segment(1) . '/edit/' . $dt->id.'/'.$dt->branch_id); ?>" class="btn btn-xs btn-icon rounded-circle btn-warning edit" data-id="<?= $dt->id; ?>" title="View Data"><i class="fa fa-edit"></i></a>
													<button type="button" class="btn btn-xs btn-icon rounded-circle btn-danger delete" data-id="<?= $dt->id; ?>" title="View Data"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
									<?php endforeach;
									endif; ?>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade" id="Done" role="tabpanel" aria-labelledby="Done-tab">
							<table id="example2" class="table table-bordered table-sm table-hover datatable">
								<thead class="text-center table-light">
									<tr class="text-center">
										<th width="50">No.</th>
										<th class="text-left">Nama</th>
										<th>Tahun</th>
										<th>Nomor</th>
										<th width="150">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($done) && $done) :
										$n = 0;
										foreach ($done as $dn) : $n++; ?>
											<tr class="text-center">
												<td><?= $n; ?></td>
												<td class="text-left"><?= $dn->name; ?></td>
												<td><?= $dn->year; ?></td>
												<td><?= $dn->number; ?></td>
												<td>
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-info view" data-id="<?= $dn->id; ?>" title="View Data"><i class="fa fa-search"></i></button>
													<a href="<?= base_url($this->uri->segment(1) . '/edit/' . $dn->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-warning edit" data-id="<?= $dn->id; ?>" title="View Data"><i class="fa fa-edit"></i></a>
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-danger delete" data-id="<?= $dn->id; ?>" title="View Data"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
									<?php endforeach;
									endif; ?>
								</tbody>
							</table>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<span class="close" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" id="save" class="btn btn-primary min-w-100px save"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('button[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true
			}).columns.adjust();
		});

		$('.datatable').DataTable({
			orderCellsTop: false,
			// fixedHeader: true,
			// scrollX: true,
			// ordering: false,
			// info: false
		});

		$(document).on('click', '#add', function() {
			$('#modalView').modal('show')
			$('#modalView .modal-title').text('Add Company Reference')
			$('#modalView .modal-body').load(base_url + active_controller + 'add')
		})

		$(document).on('click', '.view', function() {
			let id = $(this).data('id')
			$('#modalView').modal('show')
			$('#modalView .modal-title').text('View Company Reference')
			$('#modalView .modal-body').load(base_url + active_controller + 'view/' + id)
		})

		$(document).on('click', '.delete', function() {
			let id = $(this).data('id')

			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this data?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: base_url + active_controller + 'delete/' + id,
						type: 'GET',
						dataType: 'JSON',
						success: function(res) {
							if (res.status == 1) {
								Swal.fire({
									title: 'Success!',
									icon: 'success',
									text: res.msg,
									timer: 3000
								}).then(() => {
									location.reload()
								})
							} else {
								Swal.fire({
									title: 'Warinng!',
									icon: 'warning',
									text: res.msg,
									timer: 3000
								})
							}
						},
						error: function(res) {
							Swal.fire({
								title: 'Error!',
								icon: 'error',
								text: 'Server timeout, error..',
								timer: 3000
							})
						}
					})
				}
			})

		})

		$(document).on('click', '#save', function(e) {
			$('#company_id').next('span').find('span.select2-selection').removeClass('is-invalid')
			$('#branch_id').next('span').find('span.select2-selection').removeClass('is-invalid')
			const company_id = $('#company_id').val()
			const branch = $('#branch_id').val()

			if (!company_id) {
				$('#company_id').next('span').find('span.select2-selection').addClass('is-invalid')
				// $('body,html').animate({
				// 	scrollTop: $("#company_id").offset().top - 220
				// }, 1000);
				return false;
			}

			// typeof(variable) != "undefined"
			if (typeof(branch) !== "undefined") {
				console.log(branch);
				if (!branch) {
					$('#branch_id').next('span').find('span.select2-selection').addClass('is-invalid')
					// $('body,html').animate({
					// 	scrollTop: $("#branch_id").offset().top - 220
					// }, 1000);
					return false;
				}
			}

			let formdata = new FormData($('#form-reference')[0])
			let btn = $(this)
			Swal.fire({
				title: 'Confirmation!',
				text: 'Are you sure you want to save this data?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'save',
						data: formdata,
						type: 'POST',
						dataType: 'JSON',
						processData: false,
						contentType: false,
						cache: false,
						beforeSend: function() {
							btn.attr('disabled', true).html('<i class="spinner spinner-border-sm"></i>Loading...')
						},
						complete: function() {
							btn.attr('disabled', false).html('<i class="fa fa-save"></i>Save')
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire({
									title: 'Success!',
									icon: 'success',
									text: result.msg,
									timer: 2000
								})
								$('#modelId').modal('hide')
								location.href = siteurl + active_controller + 'edit/' + result.id
								console.log(result);
							} else {
								Swal.fire({
									title: 'Warning!',
									icon: 'warning',
									text: result.msg,
									timer: 2000
								})
							}
						},
						error: function(result) {
							Swal.fire({
								title: 'Error!',
								icon: 'error',
								text: 'Server timeout, becuase error!',
								timer: 4000
							})
						}
					})

				}
			})
		})

	})
</script>