<style>
	.cursor-pointer:hover div.dir-tools .btn-dropdown {
		display: block !important;
	}
</style>

<div class="content d-flex flex-column flex-column-fluid">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header justify-content-between d-flex align-items-center">
					<h3 class="m-0">
						<a href="<?= base_url($this->uri->segment(1) . '/?p=' . $parent->id . '&sub=' . $sub->id); ?>" title="Back" class="btn btn-light btn-sm btn-icon"><i class="fa fa-arrow-left text-dark"></i></a> List Checksheet
					</h3>
					<button type="button" id="add" class="btn btn-primary"><i class="fa fa-plus"></i> New Checksheet</button>
				</div>
				<div class="card-body">
					<div class="d-flex justify-content-between align-items-center">
						<div class="searching">
							<div class="input-group">
								<div class="input-group-text input-group-prepend rounded-right-0"><i class="fa fa-search"></i></div>
								<input data type="text" name="search" id="search" class="form-control w-200px d-inline-block" placeholder="Search">
							</div>
						</div>

						<nav class="breadcrumb py-2 line-height-0 m-0">
							<a class="breadcrumb-item" href="<?= base_url($this->uri->segment(1)); ?>"><i class="fa fa-home"></i></a>
							<a class="breadcrumb-item" href="<?= base_url($this->uri->segment(1) . "/?p=" . $parent->id); ?>"><?= $parent->name; ?></a>
							<a class="breadcrumb-item" href="<?= base_url($this->uri->segment(1) . "/?p=" . $parent->id . "&sub=" . $sub->id); ?>"><?= $sub->name; ?></a>
							<span class="breadcrumb-item active"><?= $dir->name; ?></span>
						</nav>
						<input type="hidden" id="dir" value="<?= $dir->id; ?>">
					</div>
					<table class="table table-sm table-bordered responsive datatable">
						<thead class="table-light">
							<tr>
								<th class="p-2">Checkseet Name</th>
								<th class="p-2">Freq. Excecution</th>
								<th class="p-2">Periode</th>
								<th class="p-2">Freq. Checking</th>
								<th>Last Update</th>
								<th width="100">Opsi</th>
							</tr>
						</thead>
						<tbody>
							<?php $n = 0;
							if ($data) foreach ($data as $dt) : $n++; ?>
								<tr>
									<td class="py-2">
										<h4 class="mb-0 d-flex align-items-end">
											<i class="fa fa-file-alt mr-2 text-success" style="font-size: 28px;"></i><?= $dt->checksheet_name; ?>
										</h4>
									</td>
									<td class="py-2">
										<?= $fExecution[$dt->frequency_execution]; ?>
									</td>
									<td class="py-2">
										<?= $dt->periode; ?>
									</td>
									<td class="py-2">
										<?= $fChecking[$dt->frequency_checking]; ?>
									</td>
									<td class="py-2">
										<div class="d-flex justify-content-between">
											<span><?= $dt->created_at; ?></span>
										</div>
									</td>
									<td class="py-2 text-center">
										<button type="button" data-toggle="dropdown" class="btn dropdown-toggle btn-xs py-1 px-2 btn-primary"><i class="fa fa-cog"></i></button>
										<div class="dropdown-menu text-center px-2 w-50 w-lg-auto" aria-labelledby="triggerId">
											<button type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-primary view"><i class="fa fa-eye"></i></button>
											<a href="<?= base_url($this->uri->segment(1) . '/edit_checkhseet/' . $dt->id); ?>" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-warning" title="Edit Checksheet"><i class="fa fa-pen"></i></a>
											<a href="<?= base_url($this->uri->segment(1) . '/checking/?sheet=' . $dt->id); ?>" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-info exec"><i class="fas fa-arrow-right"></i></a>
											<button type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-success check"><i class="fas fa-clipboard-check"></i></button>
											<button type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-danger delete"><i class="fa fa-trash"></i></button>
											<a target="_blank" href="<?= base_url($this->uri->segment(1) . '/print_sheet/?sheet=' . $dt->id); ?>" type="button" data-id="<?= $dt->id; ?>" class="btn btn-xs btn-icon btn-secondary"><i class="fa fa-print text-"></i></a>
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
			responsive: true
		})

		$(document).on('input paste', '#search', function() {
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
				$('#modalId .modal-body table tbody').html(data)
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