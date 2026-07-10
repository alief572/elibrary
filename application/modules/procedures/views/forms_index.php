<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h2 class="mt-5"><i class="<?= $icon; ?> text-primary mr-2"></i><?= $title; ?></h2>
					<button type="button" class="btn btn-primary" id="btn-add-form"><i class="fa fa-plus mr-1"></i> Add Form</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="dtForms" class="table table-bordered table-sm table-condensed table-hover">
							<thead class="text-center table-light">
								<tr>
									<th width="40">No.</th>
									<th width="150">Procedure</th>
									<th width="120">Number</th>
									<th>Name</th>
									<th width="80">Link</th>
									<th width="60">File</th>
									<th width="130">Update</th>
									<th width="100">Opsi</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($forms)) : $n = 0; foreach ($forms as $frm) : $n++; ?>
									<tr>
										<td class="text-center"><?= $n; ?></td>
										<td><?= isset($frm->procedure_name) ? $frm->procedure_name : '-'; ?></td>
										<td><?= isset($frm->number) && $frm->number ? $frm->number : '-'; ?></td>
										<td><?= $frm->name; ?></td>
										<td class="text-center">
											<?php if (!empty($frm->link_form)) : ?>
												<a href="<?= $frm->link_form; ?>" target="_blank" class="btn btn-xs btn-icon btn-info" title="<?= $frm->link_form; ?>"><i class="fa fa-link"></i></a>
											<?php else : ?>
												-
											<?php endif; ?>
										</td>
										<td class="text-center">
											<?php if (!empty($frm->file_name)) : ?>
												<a href="<?= base_url('directory/FORMS/' . $frm->company_id . '/' . $frm->file_name); ?>" target="_blank" title="<?= $frm->file_name; ?>">
													<?php
													$ext = pathinfo($frm->file_name, PATHINFO_EXTENSION);
													if (in_array($ext, ['xls', 'xlsx'])) : ?>
														<i class="fa fa-file-excel text-success fa-lg"></i>
													<?php else : ?>
														<i class="fa fa-file-pdf text-danger fa-lg"></i>
													<?php endif; ?>
												</a>
											<?php else : ?>
												-
											<?php endif; ?>
										</td>
										<td class="text-center"><?= isset($frm->modified_at) && $frm->modified_at ? date('Y-m-d H:i', strtotime($frm->modified_at)) : (isset($frm->created_at) ? date('Y-m-d H:i', strtotime($frm->created_at)) : '-'); ?></td>
										<td class="text-center">
											<button type="button" class="btn btn-xs btn-icon btn-warning btn-edit-form" data-id="<?= $frm->id; ?>" title="Edit"><i class="fa fa-edit"></i></button>
											<button type="button" class="btn btn-xs btn-icon btn-danger btn-delete-form" data-id="<?= $frm->id; ?>" title="Delete"><i class="fa fa-trash"></i></button>
										</td>
									</tr>
								<?php endforeach; endif; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Add/Edit Form -->
<div class="modal fade" id="modalForm" data-backdrop="static" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Form</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
			</div>
			<div id="modal-form-content">
				<!-- Content loaded via AJAX -->
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#dtForms').DataTable({
		fixedHeader: true,
		processing: true,
		destroy: true,
		order: [],
		columnDefs: [{
			targets: 0,
			searchable: false,
			orderable: false
		}]
	});

	// Auto-number the "No" column after every draw
	$('#dtForms').on('order.dt search.dt draw.dt', function() {
		var i = 1;
		$('#dtForms').DataTable().cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
			this.data(i++);
		});
	}).DataTable().draw();

	// Add Form
	$(document).on('click', '#btn-add-form', function() {
		$('#modalForm .modal-title').text('Add Form');
		$('#modal-form-content').load(siteurl + 'procedures/upload_form_standalone');
		$('#modalForm').modal('show');
	});

	// Edit Form
	$(document).on('click', '.btn-edit-form', function() {
		var id = $(this).data('id');
		$('#modalForm .modal-title').text('Edit Form');
		$('#modal-form-content').load(siteurl + 'procedures/edit_form_standalone/' + id);
		$('#modalForm').modal('show');
	});

	// Save Form (delegated)
	$(document).on('click', '#save-form-standalone', function() {
		var formData = new FormData($('#frm-form-standalone')[0]);
		$.ajax({
			url: siteurl + 'procedures/saveFileGeneric/form',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			beforeSend: function() { $('#save-form-standalone').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...'); },
			success: function(res) {
				$('#save-form-standalone').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
				if (res.status == 1) {
					Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 }).then(function() { location.reload(); });
					$('#modalForm').modal('hide');
				} else {
					Swal.fire({ title: 'Warning!', icon: 'warning', text: res.msg });
				}
			},
			error: function() { $('#save-form-standalone').prop('disabled', false).html('<i class="fa fa-save"></i> Save'); Swal.fire({ title: 'Error!', icon: 'error', text: 'Server error' }); }
		});
	});

	// Delete Form
	$(document).on('click', '.btn-delete-form', function() {
		var id = $(this).data('id');
		Swal.fire({
			title: 'Hapus Form?', icon: 'question', showCancelButton: true,
			confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				$.post(siteurl + 'procedures/delete_form', { id: id }, function(res) {
					if (res.status == 1) { Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 }).then(function() { location.reload(); }); }
					else { Swal.fire({ title: 'Warning!', icon: 'warning', text: res.msg }); }
				}, 'json');
			}
		});
	});
});
</script>
