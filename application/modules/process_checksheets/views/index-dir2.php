<?php
$menus_perm = json_decode(has_permission_v2(15), true);
?>
<style>
	.cursor-pointer:hover div.dir-tools .btn-dropdown {
		display: block !important;
	}
</style>

<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex flex-wrap align-items-center py-3 px-3 px-md-4 gap-2">
					<h3 class="m-0 font-weight-bolder d-flex align-items-center" style="font-size: 1.15rem;">
						<a href="<?= base_url($this->uri->segment(1) . '/?p=' . $parent->id . '&sub=' . $sub2->id_sub); ?>" title="Back" class="btn btn-light btn-sm btn-icon mr-2 shadow-xs"><i class="fa fa-arrow-left text-dark"></i></a> 
						<span>List Folder</span>
					</h3>
					<?php if ($menus_perm['create'] == '1') : ?>
						<button type="button" id="add-folder" class="btn btn-primary btn-sm font-weight-bold px-3"><i class="fa fa-plus mr-1"></i> Add Folder</button>
					<?php endif; ?>
				</div>
				<div class="card-body p-3 p-md-4">
					<div class="row align-items-center mb-3">
						<div class="col-12 col-md-5 col-lg-4 mb-2 mb-md-0">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-white border-right-0"><i class="fa fa-search text-muted"></i></span>
								</div>
								<input type="text" name="search" id="search" class="form-control border-left-0 pl-0" placeholder="Search folder...">
							</div>
						</div>
						<div class="col-12 col-md-7 col-lg-8">
							<input type="hidden" id="process_id" value="<?= $sub2->process_id; ?>">
							<input type="hidden" id="id_sub" value="<?= $sub2->id; ?>">
							<nav class="breadcrumb py-2 px-3 m-0 bg-light rounded d-flex flex-wrap align-items-center" style="font-size: 12px; line-height: 1.6;">
								<a class="breadcrumb-item text-primary" href="<?= base_url($this->uri->segment(1)); ?>"><i class="fa fa-home mr-1"></i>Home</a>
								<a class="breadcrumb-item text-muted" href="<?= base_url($this->uri->segment(1)) . '?p=' . $parent->id; ?>"><?= $parent->name; ?></a>
								<a class="breadcrumb-item text-muted" href="<?= base_url($this->uri->segment(1)) . '?p=' . $parent->id . '&sub=' . $sub->id; ?>"><?= $sub->name; ?></a>
								<span class="breadcrumb-item active font-weight-bold text-dark"><?= $sub2->name; ?></span>
							</nav>
						</div>
					</div>
					<div class="table-responsive">
						<table class="table table-sm table-bordered datatable">
							<thead class="table-light">
								<tr>
									<th class="p-2">Directory Name</th>
									<th class="p-2 text-center" width="20%">Last Created</th>
									<th class="p-2 text-center" width="70">Opsi</th>
								</tr>
							</thead>
							<tbody>
								<?php $n = 0;
								if ($data) foreach ($data as $dt) : $n++; ?>
									<tr>
										<td class="py-2 align-middle">
											<a href="<?= base_url($this->uri->segment(1) . "/?p=" . $dt->process_id . "&sub=" . $_GET['sub'] . "&sub2=" . $dt->sub_id . "&checksheet=" . $dt->id); ?>" class="text-dark">
												<div class="d-flex align-items-center">
													<div class="symbol symbol-30 mr-2 flex-shrink-0">
														<span class="symbol-label bg-light-warning p-2 rounded">
															<i class="fa fa-folder text-warning" style="font-size: 16px;"></i>
														</span>
													</div>
													<div>
														<span class="font-weight-bolder d-block" style="font-size: 13.5px; line-height: 1.3; word-break: break-word;">
															<?= $dt->name; ?>
														</span>
													</div>
												</div>
											</a>
										</td>
										<td class="py-2 text-center align-middle text-muted" style="font-size: 12px;"><?= $dt->created_at; ?></td>
										<td class="py-2 text-center align-middle">
											<?php if ($menus_perm['update'] == '1') : ?>
												<button type="button" class="btn btn-xs btn-icon btn-warning edit" data-id="<?= $dt->id; ?>" title="Edit"><i class="fa fa-edit"></i></button>
											<?php endif; ?>
											<?php if ($menus_perm['delete'] == '1') : ?>
												<button type="button" class="btn btn-xs btn-icon btn-danger delete" data-id="<?= $dt->id; ?>" title="Hapus"><i class="fa fa-trash"></i></button>
											<?php endif; ?>
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
				<div class="btn-save"></div>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalView" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
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
			// paging: true,
			info: false,
			pageLength: 20,
		})

		$(document).on('input paste', '#search', function() {
			oTable.search($(this).val()).draw();
		})
	})

	/* DIRECTORY */

	$(document).on('click', '#add-folder', function() {
		$('#modalId .modal-title').text('New Folder')
		$('#modalId').modal('show')
		$('#modalId .modal-body').html(`
			<label for="">Folder Name</label>
			<input type="text" id="folder-name" class="form-control" placeholder="Folder Name">
			<span class="invalid-feedback">Folder Name not be empty</span>`)
		$('.btn-save').html(`
		<button type="button" class="btn btn-primary" id="save-folder"><i class="fa fa-save"></i> Save</button>
		`)
	})

	$(document).on('click', '.edit', function() {
		const id = $(this).data('id')
		$.getJSON(siteurl + active_controller + 'edit_process_dir/' + id, function(data) {
			var items = [];
			$('#modalId .modal-title').text('Edit Folder')
			$('#modalId').modal('show')
			$('#modalId .modal-body').html(`
			<label for="">Folder Name</label>
			<input type="text" id="id" class="form-control d-none" value="` + data.data.id + `">
			<input type="text" id="folder-name" class="form-control" placeholder="New Folder" value="` + data.data.name + `">
			<span class="invalid-feedback">Folder Name not be empty</span>
			`)
			$('.btn-save').html(`<button type="button" class="btn btn-primary" id="save-folder"><i class="fas fa-save"></i> Save</button>`)
		});
	})

	$(document).on('click', '#save-folder', function() {
		const name = $('#folder-name').val();
		const process_id = $('#process_id').val();
		const sub_id = $('#id_sub').val();
		const id = $('#id').val();

		if (!name) {
			$('#folder-name').addClass('is-invalid')
			return false;
		}

		$('#folder-name').removeClass('is-invalid')

		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this new folder?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save_process_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						process_id,
						sub_id,
						name,
						id,
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire({
								title: "Success!",
								text: result.msg,
								icon: "success",
								timer: 3000
							}).then(function() {
								location.reload()
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

	$(document).on('click', '.delete', function() {
		const id = $(this).data('id')

		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this folder?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_process_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire({
								title: "Success!",
								text: result.msg,
								icon: "success",
								timer: 3000
							}).then(function() {
								location.reload()
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
</script>