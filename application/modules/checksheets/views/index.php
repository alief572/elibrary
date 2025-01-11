<style type="text/css">
	#preview-container {
		margin: 50px auto;
		/* width: 600px; */
	}

	#upload-dialog {
		padding: 5px;
		border: 1px solid #336699;
		background-color: white;
		color: #336699;
		background: none;
		font-size: inherit;
		font-family: inherit;
		outline: none;
		display: inline-block;
		vertical-align: middle;
		cursor: pointer;
		border-radius: 2px;
	}

	/* #pdf-file {
		display: none;
	} */

	#pdf-loader {
		display: none;
		vertical-align: middle;
		color: #cccccc;
		font-size: 12px;
	}

	#pdf-preview {
		display: none;
		vertical-align: middle;
		border: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 2px;
	}

	#pdf-name {
		display: none;
		vertical-align: middle;
		color: #336699;
		margin: 0 15px;
		max-width: 200px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	#upload-button {
		padding: 5px;
		border: 1px solid #336699;
		background-color: #336699;
		color: white;
		font-size: inherit;
		font-family: inherit;
		outline: none;
		display: none;
		vertical-align: middle;
		cursor: pointer;
		border-radius: 2px;
	}

	/* #cancel-pdf {
		display: none;
		vertical-align: middle;
		padding: 0px;
		border: none;
		color: #777777;
		background-color: white;
		font-size: inherit;
		font-family: inherit;
		outline: none;
		vertical-align: middle;
		cursor: pointer;
		margin: 0 0 0 15px;
	} */
</style>

<style>
	.cursor-pointer:hover div.dir-tools .btn-dropdown {
		display: block !important;
	}

	span.select2-selection--single.is-invalid {
		border-color: #f64e60 !important;
		padding-right: calc(1.5em + 1.3rem);
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23F64E60' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23F64E60' stroke='none'/%3e%3c/svg%3e");
		background-repeat: no-repeat;
		background-position: right calc(0.375em + 0.325rem) center;
		background-size: calc(0.75em + 0.65rem) calc(0.75em + 0.65rem);
	}
</style>

<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex align-items-center">
					<h2 class="m-0"><i class="fa fa-folder-open"></i> Data Checksheets</h2>
				</div>
				<div class="row">
					<div class="col-md-3 pr-0">
						<div class="card-header px-3 pt-3 h-50px">
							<button type="button" class="btn btn-sm btn-light-primary add-directory"><i class="fa fa-plus"></i> Add Directory</button>
						</div>

						<div class="card-body px-0 py-1 overflow-auto h-500px">
							<table class="table table-sm table-hover">
								<tbody>
									<?php
									if (!$data) : ?>
										<div class="text-center">No Data!</div>
										<?php else :
										foreach ($data as $dt) :
										?>
											<tr class="<?= ($dt->id == $selected) ? 'bg-secondary' : ''; ?>">
												<th class="cursor-pointer pl-5 pr-1">
													<div class="d-flex justify-content-between align-items-center">
														<a class="text-dark w-100" href="<?= base_url($this->uri->segment(1) . '/?d=' . $dt->id); ?>">
															<h4><i class="fa fa-folder fa-2x text-warning mr-3" style="vertical-align: middle;"></i>
																<?= $dt->name; ?>
															</h4>
														</a>
														<div class="dir-tools">
															<div class="dropdown btn-dropdown d-">
																<button class="btn btn-xs p-0 m-0" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																	<i class="fas fa-chevron-down"></i>
																</button>
																<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
																	<a class="dropdown-item edit_dir" data-id="<?= $dt->id; ?>" href="#"><i class="fa fa-pen text-warning mr-1"></i>Edit</a>
																	<a class="dropdown-item delete_dir" data-id="<?= $dt->id; ?>" href="#"><i class="fa fa-trash text-danger mr-1"></i>Delete</a>
																</div>
															</div>
														</div>
													</div>
												</th>
											</tr>
									<?php endforeach;
									endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<div class="col-md-9 pl-0">
						<div class="card-header px-0 border-1 border-bottom-0 rounded-left-0 border-left pb-11 pt-0 h-50px">
							<div class="d-flex justify-content-between align-items-center">
								<h3 class="fw-bold p-2"><i class="fa fa-list mr-2"></i><?= ($breadcumb) ? implode(" / ", $breadcumb) : ''; ?></h3>
								<div class="tools p-2 w-50">
									<div class="input-group">
										<span class="input-group-text bg-transparent border-right-0 rounded-right-0"><i class="fa fa-search"></i></span>
										<input type="text" id="search" class="form-control" placeholder="Search" aria-label="search" aria-describedby="input1" />
									</div>
								</div>
							</div>
						</div>
						<div class="card-body border border-1 border-left overflow-auto py-2 px-1 h-550px">
							<?php if (!isset($details) || !$details) : ?>
								<?php if (!$selected == '') : ?>
									<div class="px-3">
										<button type="button" class="btn mb-2 btn-sm btn-light-success add-sub-directory" data-id="<?= $selected; ?>" title="New Sub Directory"><i class="fa fa-plus"></i> New Sub Directory</button>
									</div>
									<hr class="m-0">
									<div class="d-flex justify-content-center align-items-center py-10">
										<img src="<?= base_url('assets\images\dashboard\folder-file.gif'); ?>" alt="" width="300px" class="img-responsive text-center opacity-30">
									</div>
								<?php endif; ?>
								<p class="text-muted text-center">~Not selected data~</p>
							<?php else : ?>
								<?php if ($details && $details_data == 0) : ?>
									<div class="px-3">
										<button type="button" class="btn mb-2 btn-sm btn-light-success add-sub-directory" data-id="<?= $selected; ?>" title="New Sub Directory"><i class="fa fa-plus"></i> New Sub Directory</button>
										<?php if ($details) : ?>
										<?php endif; ?>
									</div>
									<hr class="m-0">
									<table class="table table-hover datatable" style="margin:0px 0px !important;">
										<thead>
											<tr class="table-light">
												<th class="py-2">Name</th>
												<th width="150">Last update</th>
												<th class="py-2 text-center" width="100">Opsi</th>
											</tr>
										</thead>
										<tbody>
											<?php if (isset($details)) foreach ($details as $dtl) : ?>
												<tr>
													<!-- <td><?= $dtl->id; ?></td> -->
													<td class="py-3"><a href="?d=<?= $dtl->checksheet_id . "&sub=" . $dtl->id ?>" class="text-dark h4 text-hover-primary"><i class="fa fa-folder text-success mr-2"></i><?= $dtl->checksheet_detail_name; ?></a></td>
													<td class="py-3"><?= ($dtl->modified_at) ?: $dtl->created_at; ?></td>
													<td class="py-3 text-center">
														<button type="button" class="btn btn-icon btn-xs btn-info info-sub-folder" data-id="<?= $dtl->id; ?>"><i class="fas fa-question-circle"></i></button>
														<button type="button" class="btn btn-icon btn-xs btn-warning edit-sub-folder" data-id="<?= $dtl->id; ?>"><i class="fa fa-pencil-alt"></i></button>
														<button type="button" class="btn btn-icon btn-xs btn-danger delete-sub-folder" data-id="<?= $dtl->id; ?>"><i class="fa fa-trash-alt"></i></button>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php else : ?>
									<div class="d-flex justify-content-between align-items-center">
										<div class="tools pr-3">
											<button type="button" class="btn btn-outline-primary btn-sm upload-file" data-checksheet_detail_id="<?= $sub; ?>" title="Create Checksheet" data-toggle="tooltip"><i class="fa fa-plus"></i> Create Checksheet</button>
										</div>
									</div>
									<hr class="my-1">
									<table class="table py-0 table-sm table-hover datatable">
										<thead>
											<tr>
												<th class="py-2" width="30">No.</th>
												<th class="py-2">Checksheet Nama</th>
												<th class="py-2">Periode</th>
												<th class="py-2 text-center" width="100">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php $n = 0;
											if (isset($details_data)) : foreach ($details_data as $dtDtl) : $n++; ?>
													<tr>
														<td><?= $n; ?></td>
														<td class="cursor-pointer"><i class="fa fa-file-alt mr-2 text-primary"></i><?= $dtDtl->checksheet_detail_data_name; ?></td>
														<td class="cursor-pointer"><?= $freq[$dtDtl->frequency_execution]; ?></td>
														<td class="text-center">
															<button type="button" class="btn btn-xs btn-icon btn-info view-file" data-checksheet_detail_id="<?= $sub; ?>" data-id="<?= $dtDtl->id; ?>"><i class="fa fa-eye"></i></button>
															<button type="button" class="btn btn-xs btn-icon btn-warning edit-file" data-checksheet_detail_id="<?= $sub; ?>" data-id="<?= $dtDtl->id; ?>"><i class="fa fa-edit"></i></button>
															<button type="button" class="btn btn-xs btn-icon btn-danger delete-file" data-checksheet_detail_id="<?= $sub; ?>" data-id="<?= $dtDtl->id; ?>"><i class="fa fa-trash-alt"></i></button>
														</td>
													</tr>
											<?php endforeach;
											endif; ?>
										</tbody>
									</table>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalView" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" style="max-width: 90%;" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<style>
	div#DataTables_Table_0_filter {
		display: none;
	}
</style>

<script>
	$(document).ready(function() {
		$('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true,
				searching: false,
				lengthChange: false,
				paging: true,
				info: false,
				stateSave: true,
				fixedHeader: true,
				pageLength: 10,
				scrollCollapse: true
			}).columns.adjust();
		});

		oTable = $('.datatable').DataTable({
			dom: 'Pfrtip',
			searchPanes: {
				cascadePanes: true
			},
			language: {
				searchPanes: {
					i18n: {
						emptyMessage: "<i></b>No results returned</b></i>"
					}
				},
				infoEmpty: "No results returned",
				zeroRecords: "No results returned",
				emptyTable: "No results returned",
			},
			lengthChange: true,
			paging: true,
			info: false,
			stateSave: false,
			pageLength: 20,
			// scrollCollapse: true
		})

		$(document).on('input paste', '#search', function() {
			oTable.search($(this).val()).draw();
		})
	})

	/* DIRECTORY */

	$(document).on('click', '.add-directory', function() {
		$('#modalId .modal-title').text('Add Directory')
		$('#modalId').modal('show')
		$('#modalId .modal-body').html(`
		<label for="">Directory Name</label>
		<input type="text" id="dir_name" class="form-control" placeholder="New Folder">
		<span class="invalid-feedback">Directory Name not be empty</span>
		`)
		$('.save-sub-folder,.save-files,.update-files')
			.removeClass('save-sub-folder')
			.removeClass('save-files')
			.removeClass('update-files')
			.addClass('save')
	})

	$(document).on('click', '.save', function() {
		const name = $('#dir_name').val();
		const id = $('#id_dir').val();

		if (!name) {
			$('#dir_name').addClass('is-invalid')
			return false;
		}

		$('#dir_name').removeClass('is-invalid')

		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this new directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						name,
						id
					},
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

	$(document).on('click', '.edit_dir', function() {
		const id = $(this).data('id')
		$.getJSON(siteurl + active_controller + 'edit_dir/' + id, function(data) {
			var items = [];
			$('#modalId .modal-title').text('Edit Directory')
			$('#modalId').modal('show')
			$('#modalId .modal-body').html(`
			<label for="">Directory Name</label>
			<input type="text" id="id_dir" class="form-control d-none" value="` + data.data.id + `">
			<input type="text" id="dir_name" class="form-control" placeholder="New Folder" value="` + data.data.name + `">
			<span class="invalid-feedback">Directory Name not be empty</span>
			`)
			$('.save-sub-folder,.save-files,.update-files')
				.removeClass('save-sub-folder')
				.removeClass('save-files')
				.removeClass('update-files')
				.addClass('save')
		});
	})

	$(document).on('click', '.delete_dir', function() {
		const id = $(this).data('id')
		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
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

	/* SUB DIRECTORY */

	$(document).on('click', '.add-sub-directory', function() {
		const checksheet_id = $(this).data('id')

		$('#modalId .modal-title').text('Add Sub Directory')
		$('#modalId').modal('show')
		$('#modalId .modal-body').html(`
		<label for="">Directory Name</label>
		<input type="hidden" id="checksheet_id" class="form-control" value="` + checksheet_id + `">
		<input type="text" id="sub_dir_name" class="form-control" placeholder="New Sub Folder">
		<span class="invalid-feedback">Directory Name not be empty</span>
		`)
		$('.save,.save-files,.update-files')
			.removeClass('save')
			.removeClass('save-files')
			.removeClass('update-files')
			.addClass('save-sub-folder')
	})

	$(document).on('click', '.edit-sub-folder', function() {
		const id = $(this).data('id')
		$.getJSON(siteurl + active_controller + 'edit_sub_dir/' + id, function(data) {
			var items = [];
			$('#modalId .modal-title').text('Edit Sub Directory')
			$('#modalId').modal('show')
			$('#modalId .modal-body').html(`
			<label for="">Directory Name</label>
			<input type="hidden" id="id_sub_dir" class="form-control" value="` + data.data.id + `">
			<input type="hidden" id="checksheet_id" class="form-control" value="` + data.data.checksheet_id + `">
			<input type="text" id="sub_dir_name" class="form-control" placeholder="New Folder" value="` + data.data.name + `">
			<span class="invalid-feedback">Directory Name not be empty</span>
			`)
			$('.save,.save-files,.update-files')
				.removeClass('save')
				.removeClass('save-files')
				.removeClass('update-files')
				.addClass('save-sub-folder')
		});


	})

	$(document).on('click', '.save-sub-folder', function() {
		const name = $('#sub_dir_name').val();
		const checksheet_id = $('#checksheet_id').val();
		const id = $('#id_sub_dir').val();

		if (!name) {
			$('#sub_dir_name').addClass('is-invalid')
			return false;
		}

		$('#sub_dir_name').removeClass('is-invalid')

		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this new directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save_sub_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						name,
						checksheet_id,
						id
					},
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

	$(document).on('click', '.delete-sub-folder', function() {
		const id = $(this).data('id')
		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_sub_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
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

	/* UPLOAD FILES */
	$(document).on('click', '.upload-file', function() {
		const checksheet_detail_id = $(this).data('checksheet_detail_id')
		$('#modalId .modal-title').text('Add Checksheet item')
		$('#modalId').modal('show')
		$('#modalId .modal-dialog').css('max-width', '90%')
		$('#modalId .modal-body').load(siteurl + active_controller + 'upload_file/?dtl=' + checksheet_detail_id)
		$('.save,.save-sub-folder,.update-files')
			.removeClass('save-sub-folder')
			.removeClass('update-files')
			.removeClass('save')
			.addClass('save-files').html('<i class="fa fa-save"></i> Save')
	})

	$(document).ajaxSend(function() {
		$('#modalId .modal-body').html(`<i class="spinner-border spinner-border-sm" aria-hidden="true"></i> Loading...`);
	});

	$(document).on('click', '.edit-file', function() {
		const id = $(this).data('id')
		$('#modalId .modal-title').text('Update Checksheet item')
		$('#modalId').modal('show')
		$('#modalId .modal-dialog').css('max-width', '90%')
		$('#modalId .modal-body').load(siteurl + active_controller + 'edit_file/' + id)

		$('.save,.save-sub-folder,.save-files')
			.removeClass('save-sub-folder')
			.removeClass('save-files')
			.removeClass('save')
			.addClass('update-files').html('<i class="fa fa-save"></i> Save')
	})

	$(document).on('click', '.save-files', function() {
		// const name = $('#name').val()
		// const group_id = $('#group_id').val()
		const periode = $('#periode').val()
		// const reference = $('#reference').val()
		// const range_measure = $('input[name="range_measure[]"]');
		// const publish_date = $('#publish_date').val()
		// const revision_date = $('#revision_date').val()
		// const revision_number = $('#revision_number').val()
		// const document = $('#pdf-file').val()
		const btn = $(this)

		// $('select#group_id').next().find('span.selection .select2-selection.select2-selection--single').removeClass('is-invalid')
		// if (!group_id) {
		// 	$('select#group_id').next().find('span.selection .select2-selection.select2-selection--single').addClass('is-invalid')
		// 	return false;
		// }

		// $('#name').removeClass('is-invalid')

		// if (!name) {
		// 	$('#name').addClass('is-invalid')
		// 	return false;
		// }

		// // $('#range_maesure').removeClass('is-invalid')
		// let c = 0;
		// range_measure.each(function() {
		// 	$(this).removeClass('is-invalid')
		// 	if ($(this).val().length == 0) {
		// 		$(this).addClass('is-invalid')
		// 		c++
		// 	}
		// })

		// if (c > 0) {
		// 	return false;
		// }

		// $('#publish_date').removeClass('is-invalid')
		// if (!publish_date) {
		// 	$('#publish_date').addClass('is-invalid')
		// 	return false;
		// }

		$('select#periode').next().find('span.selection .select2-selection.select2-selection--multiple').removeClass('is-invalid')
		if (!periode) {
			$('select#periode').next().find('span.selection .select2-selection.select2-selection--multiple').addClass('is-invalid')
			return false;
		}

		// $('select#reference').next().find('span.selection .select2-selection.select2-selection--multiple').removeClass('is-invalid')
		// if (!reference) {
		// 	$('select#reference').next().find('span.selection .select2-selection.select2-selection--multiple').addClass('is-invalid')
		// 	return false;
		// }

		// if (!document) {
		// 	$('#pdf-file').addClass('is-invalid')
		// 	Swal.fire('Warning!', "File or Document can't be empty. Please upload document first.", 'warning', 3000)
		// 	return false;
		// }

		// $('#name-file').removeClass('is-invalid')


		const formdata = new FormData($('#form-upload')[0])
		$.ajax({
			url: siteurl + active_controller + 'upload_document',
			type: 'POST',
			dataType: 'JSON',
			data: formdata,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i> Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-save" aria-hidden="true"></i> Save').prop('disabled', false)
			},
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
	})

	$(document).on('click', '.update-files', function() {
		const name = $('#name').val()
		const frequency = $('#frequency-execution').val()
		// const range_measure = $('input[name="range_measure[]"]')
		// const publish_date = $('#publish_date').val()
		// const revision_date = $('#revision_date').val()
		// const revision_number = $('#revision_number').val()
		// const methode = $('#methode').val()
		// const reference = $('#reference').val()
		// const document = $('#pdf-file').val()
		const btn = $(this)

		$('#name').removeClass('is-invalid')
		if (!name) {
			$('#name').addClass('is-invalid')
			return false;
		}
		$('select#periode').next().find('span.selection .select2-selection.select2-selection--single').removeClass('is-invalid')
		if (!frequency) {
			$('select#periode').next().find('span.selection .select2-selection.select2-selection--single').addClass('is-invalid')
			return false;
		}

		const formdata = new FormData($('#form-upload')[0])
		$.ajax({
			url: siteurl + active_controller + 'upload_document',
			type: 'POST',
			dataType: 'JSON',
			data: formdata,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i> Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-save" aria-hidden="true"></i> Save').prop('disabled', false)
			},
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
	})

	$(document).on('click', '.delete-file', function() {
		const id = $(this).data('id')
		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this Document?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_sheet',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
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

	function remove_image(e) {
		$("#remove-document").val('x');

		// reset to no selection
		$("#pdf-file").val('');
	}

	$(document).on('click', '.view-file', function() {
		const id = $(this).data('id')
		$('#modalView .modal-title').text('View Checksheet item')
		$('#modalView').modal('show')
		$('#modalView .modal-dialog')
		$('#modalView .modal-body').load(siteurl + active_controller + 'view_file/' + id)
	})

	var _PDF_DOC,
		_CANVAS = document.querySelector('#pdf-preview'),
		_OBJECT_URL;

	function showPDF(pdf_url) {

		PDFJS.getDocument({
			url: pdf_url
		}).then(function(pdf_doc) {
			_PDF_DOC = pdf_doc;

			// Show the first page
			showPage(1);

			// destroy previous object url
			URL.revokeObjectURL(_OBJECT_URL);
		}).catch(function(error) {
			// trigger Cancel on error
			$("#cancel-pdf").click();

			// error reason
			alert(error.message);
		});;
	}

	function showPage(page_no) {
		var _CANVAS = document.querySelector('#pdf-preview');
		// fetch the page
		console.log(page_no);
		console.log(_PDF_DOC.getPage(page_no));
		_PDF_DOC.getPage(page_no).then(function(page) {
			// set the scale of viewport
			var scale_required = _CANVAS.width / page.getViewport(1).width;

			// get viewport of the page at required scale
			var viewport = page.getViewport(scale_required);

			// set canvas height
			_CANVAS.height = viewport.height;

			var renderContext = {
				canvasContext: _CANVAS.getContext('2d'),
				viewport: viewport
			};

			// render the page contents in the canvas
			page.render(renderContext).then(function() {
				$("#pdf-preview").css('display', 'inline-block');
				$("#pdf-loader").css('display', 'none');
			});
		});
	}

	$(document).on('click', '.change-image', function() {
		$('#pdf-file').click()
	})

	/* Selected File has changed */
	$(document).on('change', "#pdf-file", function() {
		// user selected file
		console.log($(this));
		var file = $(this)[0].files[0];

		// allowed MIME types
		var mime_types = ['application/pdf'];

		// Validate whether PDF
		if (mime_types.indexOf(file.type) == -1) {
			alert('Error : Incorrect file type');
			return;
		}

		// validate file size
		if (file.size > 10 * 1024 * 1024) {
			alert('Error : Exceeded size 10MB');
			return;
		}

		// validation is successful

		// hide upload dialog button
		$("#upload-dialog").css('display', 'none');

		// set name of the file
		// $("#pdf-name").text(file.name);
		// $("#pdf-name").css('display', 'inline-block');

		// show cancel and upload buttons now
		$("#cancel-pdf").removeClass('d-none');
		$("#remove-file").removeClass('d-none');
		// $("#upload-button").css('display', 'inline-block');

		// Show the PDF preview loader
		$("#pdf-loader").css('display', 'inline-block');

		// object url of PDF 
		// console.log(file);
		_OBJECT_URL = URL.createObjectURL(file)

		// send the object url of the pdf to the PDF preview function
		showPDF(_OBJECT_URL);
	});

	/* Reset file input */
	$(document).on('click', "#cancel-pdf,.remove-image", function() {
		// show upload dialog button
		$("#upload-dialog").css('display', 'inline-block');

		// reset to no selection
		$("#pdf-file").val('');

		// hide elements that are not required
		$("#pdf-name").css('display', 'none');
		$("#pdf-preview").css('display', 'none');
		$("#pdf-loader").css('display', 'none');
		$("#cancel-pdf").addClass('d-none');
		$("#upload-button").css('display', 'none');
	});

	$(document).on('click', "#remove-file", function() {
		// show upload dialog button
		$("#upload-dialog").css('display', 'inline-block');
		$("#remove-document").val('x');

		// reset to no selection
		$("#pdf-file").val('');

		// hide elements that are not required
		$("#pdf-name").css('display', 'none');
		$("#pdf-preview").css('display', 'none');
		$("#pdf-loader").css('display', 'none');
		$("#cancel-pdf").addClass('d-none');
		$("#upload-button").css('display', 'none');
	});

	/* Upload file to server */
	$(document).on('click', "#upload-button", function() {
		// AJAX request to server
		alert('This will upload file to server');
	});


	/* UPLOAD VIDEO */
	$(document).on('click', '#upload-video', function() {
		$('#video-file').click();
	})

	$(document).on('change', '#video-file', function() {
		let file = $(this)[0].files[0];

		// allowed MIME types
		var mime_types = ['video/mp4'];

		// Validate whether PDF
		if (mime_types.indexOf(file.type) == -1) {
			Swal.fire('Warning', 'Error : Incorrect file type', 'warning', 3000)
			return;
		}

		// validate file size
		if (file.size > 50 * 1024 * 1024) {
			Swal.fire('Warning', 'Error : Exceeded size 50MB', 'warning', 3000)
			return;
		}

		let blobURL = URL.createObjectURL(file);
		console.log(blobURL);
		$("#video-preview").attr('src', blobURL).removeClass('d-none');
		$('#remove-video').removeClass('d-none')
	})

	$(document).on('click', '#remove-video', function() {
		$('#remove-video').addClass('d-none')
		$("#video-preview").attr('src', '').addClass('d-none');
		$("#video-file").val('');
	})

	function remove_image(e) {
		$(".remove-video").val('x');
		// reset to no selection
		$("#video-file").val('');
	}

	/* MUlTI */

	$(document).on('click', '#add-item', function() {
		let e = '';
		let n = $('#table-item tbody tr').length + 1
		e = `
		<tr>
			<td class="text-center"><span class="h2">*</span></td>
			<td><textarea name="items[` + n + `][item_name]" class="form-control" placeholder="Item Check"></textarea></td>
			<td><textarea name="items[` + n + `][standard_check]" class="form-control" placeholder="Standard Check"></textarea></td>
			<td>
				<div class="form-check">
					<label class="form-check-label">
					<input type="radio" class="form-check-input" name="items[` + n + `][check_type]" id="check-type" value="boolean">
						Yes/No
					</label>
				</div>
				<div class="form-check">
					<label class="form-check-label">
						<input type="radio" class="form-check-input" name="items[` + n + `][check_type]" id="check-type" value="text">
						Input Text
				</label>
				</div>
			</td>
			<td  class="text-center"><button type="button" class="remove-item btn btn-xs btn-icon btn-danger"><i class="fa fa-trash"</i></button></td>
		<tr>`
		$('#table-item tbody').append(e)
	})

	$(document).on('click', '.remove-item', function() {
		const id = $(this).data('id')
		const btn = $(this)
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delet this data?',
				icon: 'question',
				showCancelButton: true
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_item',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									btn.parents('tr').remove();
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
		} else {
			$(this).parents('tr').remove();
		}
	})

	$(function() {
		$("#myImg1").hover(
			function() {
				$(this).attr("src", "assets/images/dashboard/folder-file.gif");
			},
			function() {
				$(this).attr("src", "assets/images/dashboard/folder-file.png");
			}
		);
	});
</script>