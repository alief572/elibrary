<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header d-flex justify-content-between align-items-center">
					<h2 class="mt-5"><i class="<?= $icon; ?> text-primary mr-2"></i><?= $title; ?></h2>
					<button type="button" class="btn btn-primary" id="btn-add-guide"><i class="fa fa-plus mr-1"></i> Add IK</button>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table id="dtGuides" class="table table-bordered table-sm table-condensed table-hover">
							<thead class="text-center table-light">
								<tr>
									<th width="40">No.</th>
									<th width="150">Procedure</th>
									<th>Name</th>
									<th width="60">File</th>
									<th width="130">Update</th>
									<th width="100">Opsi</th>
								</tr>
							</thead>
							<tbody>
								<?php if (!empty($guides)) : $n = 0; foreach ($guides as $gui) : $n++; ?>
									<tr>
										<td class="text-center"><?= $n; ?></td>
										<td><?= isset($gui->procedure_name) ? $gui->procedure_name : '-'; ?></td>
										<td><?= $gui->name; ?></td>
										<td class="text-center">
											<?php if (!empty($gui->file_name)) : ?>
												<a href="<?= base_url('directory/GUIDES/' . $gui->company_id . '/' . $gui->file_name); ?>" target="_blank" title="<?= $gui->file_name; ?>">
													<?php
													$ext = pathinfo($gui->file_name, PATHINFO_EXTENSION);
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
										<td class="text-center"><?= isset($gui->modified_at) && $gui->modified_at ? date('Y-m-d H:i', strtotime($gui->modified_at)) : (isset($gui->created_at) ? date('Y-m-d H:i', strtotime($gui->created_at)) : '-'); ?></td>
										<td class="text-center">
											<button type="button" class="btn btn-xs btn-icon btn-warning btn-edit-guide" data-id="<?= $gui->id; ?>" title="Edit"><i class="fa fa-edit"></i></button>
											<button type="button" class="btn btn-xs btn-icon btn-danger btn-delete-guide" data-id="<?= $gui->id; ?>" title="Delete"><i class="fa fa-trash"></i></button>
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

<!-- Modal Add/Edit Guide -->
<div class="modal fade" id="modalGuide" data-backdrop="static" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add IK</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
			</div>
			<div id="modal-guide-content">
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	$('#dtGuides').DataTable({
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
	$('#dtGuides').on('order.dt search.dt draw.dt', function() {
		var i = 1;
		$('#dtGuides').DataTable().cells(null, 0, { search: 'applied', order: 'applied' }).every(function() {
			this.data(i++);
		});
	}).DataTable().draw();

	// Add Guide
	$(document).on('click', '#btn-add-guide', function() {
		$('#modalGuide .modal-title').text('Add IK');
		$('#modal-guide-content').load(siteurl + 'procedures/upload_guide_standalone');
		$('#modalGuide').modal('show');
	});

	// Edit Guide
	$(document).on('click', '.btn-edit-guide', function() {
		var id = $(this).data('id');
		$('#modalGuide .modal-title').text('Edit IK');
		$('#modal-guide-content').load(siteurl + 'procedures/edit_guide_standalone/' + id);
		$('#modalGuide').modal('show');
	});

	// Save Guide
	$(document).on('click', '#save-guide-standalone', function() {
		var formData = new FormData($('#frm-guide-standalone')[0]);
		$.ajax({
			url: siteurl + 'procedures/saveFileGeneric/guide',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: 'json',
			beforeSend: function() { $('#save-guide-standalone').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...'); },
			success: function(res) {
				$('#save-guide-standalone').prop('disabled', false).html('<i class="fa fa-save"></i> Save');
				if (res.status == 1) {
					Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 }).then(function() { location.reload(); });
					$('#modalGuide').modal('hide');
				} else {
					Swal.fire({ title: 'Warning!', icon: 'warning', text: res.msg });
				}
			},
			error: function() { $('#save-guide-standalone').prop('disabled', false).html('<i class="fa fa-save"></i> Save'); Swal.fire({ title: 'Error!', icon: 'error', text: 'Server error' }); }
		});
	});

	// Delete Guide
	$(document).on('click', '.btn-delete-guide', function() {
		var id = $(this).data('id');
		Swal.fire({
			title: 'Hapus IK?', icon: 'question', showCancelButton: true,
			confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				$.post(siteurl + 'procedures/delete_guide', { id: id }, function(res) {
					if (res.status == 1) { Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 }).then(function() { location.reload(); }); }
					else { Swal.fire({ title: 'Warning!', icon: 'warning', text: res.msg }); }
				}, 'json');
			}
		});
	});
});
</script>
