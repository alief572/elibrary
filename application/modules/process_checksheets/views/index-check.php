<?php
$menus_perm = json_decode(has_permission_v2(15), true);
?>
<style>
	.cursor-pointer:hover div.dir-tools .btn-dropdown {
		display: block !important;
	}

	.bg-disabled {
		background-color: #ccc;
	}
</style>

<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex flex-wrap align-items-center py-3 px-3 px-md-4 gap-2">
					<h3 class="m-0 font-weight-bolder d-flex align-items-center" style="font-size: 1.15rem;">
						<a href="<?= base_url($this->uri->segment(1) . '/?p=' . $parent->id . '&sub=' . $sub->id . '&sub2=' . $sub2->id); ?>" title="Back" class="btn btn-light btn-sm btn-icon mr-2 shadow-xs"><i class="fa fa-arrow-left text-dark"></i></a> 
						<span>List Checksheet</span>
					</h3>
					<?php if ($menus_perm['create'] == '1') : ?>
						<button type="button" id="add" class="btn btn-primary btn-sm font-weight-bold px-3"><i class="fa fa-plus mr-1"></i> New Checksheet</button>
					<?php endif; ?>
				</div>
				<div class="card-body p-3 p-md-4">
					<div class="row align-items-center mb-3">
						<div class="col-12 col-md-5 col-lg-4 mb-2 mb-md-0">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
								</div>
								<input type="text" name="search" id="searchText" class="form-control border-left-0 pl-0" placeholder="Search checksheet...">
							</div>
						</div>

						<div class="col-12 col-md-7 col-lg-8">
							<nav class="breadcrumb py-2 px-3 m-0 bg-light rounded d-flex flex-wrap align-items-center" style="font-size: 12px; line-height: 1.6;">
								<a class="breadcrumb-item text-primary" href="<?= base_url($this->uri->segment(1)); ?>"><i class="fa fa-home mr-1"></i>Home</a>
								<a class="breadcrumb-item text-muted" href="<?= base_url($this->uri->segment(1) . "/?p=" . $parent->id); ?>"><?= $parent->name; ?></a>
								<a class="breadcrumb-item text-muted" href="<?= base_url($this->uri->segment(1) . "/?p=" . $parent->id . "&sub=" . $sub->id); ?>"><?= $sub->name; ?></a>
								<a class="breadcrumb-item text-muted" href="<?= base_url($this->uri->segment(1) . "/?p=" . $parent->id . "&sub=" . $sub->id . '&sub2=' . $sub2->id); ?>"><?= $sub2->name; ?></a>
								<span class="breadcrumb-item active font-weight-bold text-dark"><?= $dir->name; ?></span>
							</nav>
						</div>
						<input type="hidden" id="dir" value="<?= $dir->id; ?>">
					</div>
					<div class="table-responsive">
						<table class="table table-sm table-bordered datatable">
							<thead class="table-light">
								<tr>
									<th class="py-2 px-3">Checksheet Name</th>
									<th width="80" class="py-2 text-center">Opsi</th>
								</tr>
							</thead>
							<tbody>
								<?php $n = 0;

								$period_active = (!empty($sub->periode_tahun)) ? (($sub->periode_tahun == date('Y')) ? 1 : 0) : 1;

								if ($data) foreach ($data as $dt) : $n++; ?>
									<?php
									$diss = '';
									if (date('d', strtotime($dt->updated_at)) == date('d')) {
										$diss = 'table-warning';
									}
									if ($fChecking[$dt->frequency_checking] == 'Daily') {
										if (date('m') > date('m', strtotime($dt->periode))) {
											$diss = 'bg-disabled';
										}
									}
									if ($fChecking[$dt->frequency_checking] == 'Monthly') {
										if (date('Y') > date('Y', strtotime($dt->periode))) {
											$diss = 'bg-disabled';
										}
									}
									?>
									<tr class="<?= $diss ?>">
										<td class="py-3 px-3 align-middle">
											<div class="d-flex align-items-start">
												<div class="symbol symbol-35 symbol-light-success mr-3 flex-shrink-0 mt-1">
													<span class="symbol-label bg-light-success p-2 rounded">
														<i class="fa fa-file-alt text-success" style="font-size: 18px;"></i>
													</span>
												</div>
												<div class="flex-grow-1">
													<span class="font-weight-bolder text-dark d-block" style="font-size: 14px; line-height: 1.35; word-break: break-word;">
														<?= $dt->checksheet_name; ?>
													</span>
													<div class="d-flex flex-wrap align-items-center mt-1" style="font-size: 11px;">
														<span class="badge badge-light-primary font-weight-bold mr-2 mb-1">
															<i class="fa fa-sync-alt mr-1 text-primary"></i><?= $fExecution[$dt->frequency_execution]; ?>
														</span>
														<span class="badge badge-light font-weight-bold text-dark mr-2 mb-1">
															<i class="fa fa-calendar-alt mr-1 text-muted"></i><?= $dt->periode; ?>
														</span>
														<?php if (!empty($dt->checker_status)) : ?>
															<?php if ($dt->checker_status == 'Approved') : ?>
																<span class="badge badge-light-success font-weight-bold text-success mr-2 mb-1">
																	<i class="fa fa-check-circle mr-1 text-success"></i>Approved
																</span>
															<?php elseif ($dt->checker_status == 'Needs Improvement') : ?>
																<span class="badge badge-light-warning font-weight-bold text-warning mr-2 mb-1">
																	<i class="fa fa-exclamation-triangle mr-1 text-warning"></i>Needs Improvement
																</span>
															<?php elseif ($dt->checker_status == 'Revision') : ?>
																<span class="badge badge-light-danger font-weight-bold text-danger mr-2 mb-1">
																	<i class="fa fa-times-circle mr-1 text-danger"></i>Revision
																</span>
															<?php endif; ?>
														<?php endif; ?>
														<span class="text-muted mb-1">
															<i class="fa fa-clock mr-1 text-muted"></i><?= ($dt->updated_at) ?: $dt->created_at; ?>
														</span>
													</div>
												</div>
											</div>
										</td>
										<td class="py-3 text-center align-middle">
											<button type="button" data-toggle="dropdown" class="btn dropdown-toggle btn-xs py-1 px-2 btn-primary"><i class="fa fa-cog"></i></button>
											<div class="dropdown-menu text-center px-2 w-50 w-lg-auto" aria-labelledby="triggerId">
												<button type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-primary view" title="View Detail"><i class="fa fa-eye"></i></button>
												<?php if ($menus_perm['update'] == '1' && $period_active == '1' && ($diss == '' || $diss == 'table-warning')) : ?>
													<a href="<?= base_url($this->uri->segment(1) . '/edit_checkhseet/' . $dt->id); ?>" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-warning" title="Edit Checksheet"><i class="fa fa-pen"></i></a>
												<?php endif; ?>
												<?php if ($period_active == '1' && ($diss == '' || $diss == 'table-warning')) : ?>
													<a href="<?= base_url($this->uri->segment(1) . '/checking/?sheet=' . $dt->id); ?>" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-info exec" title="Eksekusi Checksheet"><i class="fas fa-arrow-right"></i></a>
													<button type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-success check" title="Checker / Verifikasi"><i class="fas fa-user-check"></i></button>
												<?php endif; ?>
												<?php if ($menus_perm['delete'] == '1' && $period_active == '1' && ($diss == '' || $diss == 'table-warning')) : ?>
													<button type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-danger delete" title="Hapus"><i class="fa fa-trash"></i></button>
												<?php endif; ?>
												<?php if ($period_active == '1' && ($diss == '' || $diss == 'table-warning')) : ?>
													<a target="_blank" href="<?= base_url($this->uri->segment(1) . '/print_sheet/?sheet=' . $dt->id); ?>" type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-secondary" title="Print"><i class="fa fa-print"></i></a>
												<?php endif; ?>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-" style="max-width:90%" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<div class="btn-save"></div>
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
		var oTable = $('.datatable').DataTable({
			// dom: 'Pfrtip',
			// searchPanes: {
			// 	cascadePanes: true
			// },
			// language: {
			// 	searchPanes: {
			// 		i18n: {
			// 			emptyMessage: "<i></b>No results returned</b></i>"
			// 		}
			// 	},
			// 	infoEmpty: "No results returned",
			// 	zeroRecords: "No results returned",
			// 	emptyTable: "No results returned",
			// },
			lengthChange: false,
			stateSave: true,
			info: true,
			pageLength: 20,
			responsive: false,
			ordering: false,
			stateLoadParams: function(settings, data) {
				$('#searchText').val(data.search.search)
			}
		})

		$(document).on('input paste', '#searchText', function() {
			oTable.search($(this).val()).draw();
		})

		$(document).on('click', '.edit', function() {
			const id = $(this).data('id');
			$('#modalId .modal-title').text('Edit Checksheet')
			$('#modalId').modal('show')
			$.get(siteurl + active_controller + 'load_details/' + id, function(data) {
				$('#modalId .modal-body').html(data)
			})
		})

		$(document).on('change', '#checksheet_id', function() {
			const id = $(this).val();
			if (id) {
				$.get(siteurl + active_controller + 'load_details/' + id, function(data) {
					$('#checksheet_detail_id').html(data)
				})
			}
		})

		$(document).on('change', '#checksheet_detail_id', function() {
			const id = $(this).val();
			const dir = $('#dir').val();
			$.get(siteurl + active_controller + 'load_detail_data/' + id + "/" + dir, function(data) {
				$('#modalId .modal-body .table-responsive table tbody').html(data)
			})
		})

		$(document).on('click', '.delete', function() {
			const id = $(this).data('id')
			Swal.fire({
				title: 'Confirm',
				text: 'Are sure you want to delete this checkheet?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete/' + id,
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
	})

	/* DIRECTORY */

	$(document).on('click', '#add', function() {
		$('#modalId .modal-title').text('Add Directory')
		$('#modalId').modal('show')
		const id_dir = '<?= $_GET['checksheet']; ?>';
		$('#modalId .modal-body').load(siteurl + active_controller + 'load_sheet/' + <?= $parent->id; ?> + '/' + id_dir)
		// $('.btn-save').html(`<button type="button" class="btn btn-primary save"><i class="fa fa-save"></i>Save</button>`)
	})

	$(document).on('click', '#save-directory', function() {
		const name = $('#directory').val()
		const checksheet_id = $('#checksheet_id').val()
		const checksheet_detail_id = $('#checksheet_detail_id').val()
		if (name == '') {
			Swal.fire({
				title: 'Warning!',
				text: 'Please input name directory!',
				icon: 'warning',
				timer: 3000
			})
		} else {
			$.ajax({
				url: siteurl + active_controller + 'save_directory_results',
				dataType: 'JSON',
				type: 'POST',
				data: {
					name,
					checksheet_id,
					checksheet_detail_id,
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

	$(document).on('click', '.view', function() {
		const id = $(this).data('id')
		if (id) {
			$('#modalId .modal-title').text('View Checksheet')
			$('#modalId').modal('show')
			$('#modalId .modal-body').load(siteurl + active_controller + 'view_sheet/' + id)
		}
	})

	$(document).on('click', '.check', function() {
		const id = $(this).data('id')
		if (id) {
			$('#modalId .modal-title').text('Checking Checksheet')
			$('#modalId').modal('show')
			$('#modalId .modal-body').load(siteurl + active_controller + 'checking_sheet/' + id)
		}
	})

	$(document).on('click', '#check-done', function() {
		const id = $('#data-id').val()
		const field = $('#field').val() || '';

		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this checkseet?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save_done',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id,
						field
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire({
								title: "Success!",
								text: result.msg,
								icon: "success",
								timer: 3000
							}).then(function() {
								location.reload();
							})
						} else {
							Swal.fire({
								title: "Warning!",
								text: result.msg,
								icon: "warning",
								timer: 3000
							})
						}
					},
					error: function(result) {
						Swal.fire({
							title: "Error!",
							text: "Server time out.",
							icon: "error",
							timer: 3000
						})

					}
				})
			}
		})
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