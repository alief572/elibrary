<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<div class="mt-4 float-right ">
						<button type="button" class="btn btn-primary" id="add" title="Add New Standard">
							<i class="fa fa-plus mr-1"></i>Add New Update
						</button>
					</div>
				</div>
				<div class="card-body">
					<div class="tab-content mt-3">
						<div class="tab-pane fade active show" id="Published" role="tabpanel" aria-labelledby="Published-tab">
							<table id="example1" class="table table-bordered table-sm table-condensed table-hover datatable">
								<thead class="text-center table-light">
									<tr class="text-center">
										<th width="3%">No.</th>
										<th class="text-left">Name</th>
										<th class="text-left">Year</th>
										<th class="text-left">Source</th>
										<th class="text-left">Checking Date</th>
										<th class="text-left">Status</th>
										<th class="text-left">Description</th>
										<th width="150">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($data) && $data) :
										$n = 0;
										foreach ($data as $dt) : $n++; ?>
											<tr class="">
												<td><?= $n; ?></td>
												<td class="text-left"><?= $dt->name; ?></td>
												<td class="text-center"><?= $dt->year; ?></td>
												<td class="text-left"><?= $dt->source; ?></td>
												<td class="text-center"><?= $dt->checking_date; ?></td>
												<td class="text-center"><?= (($dt->status) ? $status[$dt->status] : '-'); ?></td>
												<td class="text-left"><?= $dt->descriptions; ?></td>
												<td class="text-center">
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-warning edit" data-id="<?= $dt->id; ?>" title="Edit Data"><i class="fa fa-edit"></i></button>
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-danger delete" data-id="<?= $dt->id; ?>" title="Delete Data"><i class="fa fa-trash"></i></button>
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
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">View Standard</h5>
				<span class="close btn-cls" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer justify-content-end">
				<button type="button" class="btn btn-primary save w-100px"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger text-end" onclick="clear($('.modal-body'));setTimeout(()=>{$('.save').removeClass('d-none')},500)" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<style>
	span.select2-selection.is-invalid {
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
		$('button[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true
			}).columns.adjust();
		});

		$('#example1').DataTable({
			orderCellsTop: false,
			// fixedHeader: true,
			// scrollX: true,
			ordering: false,
			// info: false
		});

		$(document).on('change', '#data_id', function() {
			const id = $(this).val()
			if (id) {
				$.ajax({
					url: siteurl + active_controller + 'load_standards',
					type: 'POST',
					dataType: 'JSON',
					data: {
						id
					},
					success: function(result) {
						if (result) {
							$('#name').val(result.name)
							$('#year').val(result.year)
						}
					}
				})

			}

		})

		$(document).on('click', '#add', function() {
			const url = siteurl + active_controller + 'add_update_std';
			$('.modal-title').html('Add New Update')
			$('#modalView').modal('show')
			$('.modal-body').load(url)
		})

		$(document).on('click', '.edit', function() {
			const id = $(this).data('id')
			const url = siteurl + active_controller + 'edit_std/' + id;
			$('.modal-title').html('Edit Update Controls')
			$('#modalView').modal('show')
			$('.modal-body').load(url)
		})


		$(document).on('click', '.save', function(e) {
			var c = 0;
			if ($('#data_id').val() == '' || $('#data_id').val() == null) {
				c = c + 1
				$('#data_id').addClass('is-invalid')
				$('#data_id').next().find('span.select2-selection').addClass('is-invalid')
			} else if ($('#source').val() == '' || $('#source').val() == null) {
				$('#source').addClass('is-invalid')
				c = c + 1
			} else if ($('#checking_date').val() == '' || $('#checking_date').val() == null) {
				$('#checking_date').addClass('is-invalid')
				c = c + 1
			} else if ($('#status').val() == '' || $('#status').val() == null) {
				$('#status').addClass('is-invalid')
				$('#status').next().find('span.select2-selection').addClass('is-invalid')
				c = c + 1
			} else if ($('#descriptions').val() == '' || $('#descriptions').val() == null) {
				$('#descriptions').addClass('is-invalid')
				c = c + 1
			} else {
				$('#data_id').removeClass('is-invalid')
				$('#data_id').next().find('span.select2-selection').removeClass('is-invalid')
				$('#source').removeClass('is-invalid')
				$('#checking_date').removeClass('is-invalid')
				$('#status').removeClass('is-invalid')
				$('#status').next().find('span.select2-selection').removeClass('is-invalid')
				$('#descriptions').removeClass('is-invalid')
				c = 0;
			}

			if (c <= 0) {
				let formdata = new FormData($('#form')[0])
				let btn = $(this)
				$.ajax({
					url: siteurl + active_controller + 'save',
					data: formdata,
					type: 'POST',
					dataType: 'JSON',
					processData: false,
					contentType: false,
					cache: false,
					beforeSend: function() {
						btn.attr('disabled', true)
						btn.html('<i class="spinner spinner-border-sm"></i>Loading...')
					},
					complete: function() {
						btn.attr('disabled', false)
						btn.html('<i class="fa fa-save"></i>Save')
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire({
								title: 'Success!',
								icon: 'success',
								text: result.msg,
								timer: 2000
							}).then(function() {
								$('#modalView').modal('hide')
								clear($('.modal-body'))
								// $('table#example1 tbody').load(siteurl + active_controller + 'load_data')
								location.reload();
							})

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

		$(document).on('click', '.choose-user', function(e) {
			const user_id = $(this).data('id')
			const position = $(this).data('position')
			let btn = $(this)
			Swal.fire({
				title: 'Select User!',
				icon: 'question',
				text: 'Are you sure you want to select this user??',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'save_assign',
						data: {
							user_id,
							position
						},
						type: 'POST',
						dataType: 'JSON',
						beforeSend: function() {
							btn.attr('disabled', true).removeClass('btn-icon')
							btn.html('<i class="spinner spinner-border-sm"></i> Loading..')
						},
						complete: function() {
							btn.attr('disabled', false).addClass('btn-icon')
							btn.html('<i class="fa fa-check"></i>')
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire({
									title: 'Success!',
									icon: 'success',
									text: result.msg,
									timer: 2000
								}).then(function() {
									$('#modalView').modal('hide')
									clear($('.modal-body'))
									// $('table#example1 tbody').load(siteurl + active_controller + 'load_data')
									location.reload();
								})

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

		$(document).on('click', '.remove-user', function(e) {
			const user_id = $(this).data('id')
			const position = $(this).data('position')
			let btn = $(this)
			Swal.fire({
				title: 'Select User!',
				icon: 'question',
				text: 'Are you sure you want to remove this user??',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'remove_assign',
						data: {
							user_id,
							position
						},
						type: 'POST',
						dataType: 'JSON',
						beforeSend: function() {
							btn.attr('disabled', true).removeClass('btn-icon')
							btn.html('<i class="spinner spinner-border-sm"></i> Loading..')
						},
						complete: function() {
							btn.attr('disabled', false).addClass('btn-icon')
							btn.html('<i class="fa fa-check"></i>')
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire({
									title: 'Success!',
									icon: 'success',
									text: result.msg,
									timer: 2000
								}).then(function() {
									$('#modalView').modal('hide')
									clear($('.modal-body'))
									// $('table#example1 tbody').load(siteurl + active_controller + 'load_data')
									location.reload();
								})

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

		$(document).on('click', '.delete', function(e) {
			const id = $(this).data('id')
			const btn = $(this)
			Swal.fire({
				title: 'Delete!',
				icon: 'question',
				text: 'Are you sure to delete this data?',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete',
						data: {
							id
						},
						type: 'POST',
						dataType: 'JSON',
						success: function(result) {
							if (result.status == 1) {
								Swal.fire({
									title: 'Success!',
									icon: 'success',
									text: result.msg,
									timer: 2000
								}).then(function() {
									$('#modalView').modal('hide')
									clear($('.modal-body'))
									// $('table#example1 tbody').load(siteurl + active_controller + 'load_data')
									location.reload();
								})

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

	function validation(field, c) {
		if (field.val() == '' || field.val() == null) {
			field.addClass('is-invalid')
			return c + 1;
		} else {
			field.removeClass('is-invalid')
		}

	}

	function validation_select(field) {
		if (field.val() == '' || field.val() == null) {
			field.next().find('span.select2-selection').addClass('is-invalid')
		} else {
			field.next().find('span.select2-selection').removeClass('is-invalid')
		}
	}

	function clear(e) {
		setTimeout(() => {
			$(e).html('');
		}, 500)
	}
</script>