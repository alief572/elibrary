<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<?php $mode = isset($mode) ? $mode : 'input'; ?>
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<?php if ($mode == 'input') : ?>
					<div class="mt-4 float-right">
						<a href="<?= base_url('corrective_internal/add'); ?>" class="btn btn-primary" title="Input CAR">
							<i class="fa fa-plus mr-1"></i>+ Input CAR
						</a>
					</div>
					<?php endif; ?>
				</div>
				<div class="card-body">
					<div class="tab-content mt-3">
						<div class="tab-pane fade active show">
							<table id="example1" class="table table-bordered table-sm table-hover datatable">
								<thead class="text-center table-light">
									<tr>
										<th width="3%">No</th>
										<th>Tanggal CAR</th>
										<th>Deadline CAR</th>
										<th>Department</th>
										<th>PIC</th>
										<th width="140">Status</th>
										<th width="120">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($data) && $data) :
										$n = 0;
										foreach ($data as $dt) : $n++; ?>
											<tr>
												<td class="text-center"><?= $n; ?></td>
												<td><?= isset($dt->tanggal_car) ? date('d-m-Y', strtotime($dt->tanggal_car)) : '-'; ?></td>
												<td><?= isset($dt->deadline_car) ? date('d-m-Y', strtotime($dt->deadline_car)) : '-'; ?></td>
												<td><?= isset($dt->department_name) ? $dt->department_name : '-'; ?></td>
												<td><?= isset($dt->pic_name) ? $dt->pic_name : '-'; ?></td>
												<td class="text-center">
													<?php
													$status = isset($dt->status) ? $dt->status : '';
													switch ($status) {
														case 'draft':
															if (isset($dt->deadline_car) && strtotime($dt->deadline_car) < strtotime(date('Y-m-d'))) {
																echo '<span class="label label-danger label-inline">Overdue</span>';
															} else {
																echo '<span class="label label-primary label-inline">Open</span>';
															}
															break;
														case 'waiting_approval':
															echo '<span class="label label-warning label-inline">Waiting Approval</span>';
															break;
														case 'closed':
															echo '<span class="label label-success label-inline">Closed</span>';
															break;
														case 'reject':
															echo '<span class="label label-danger label-inline">Reject</span>';
															break;
														default:
															echo '<span class="label label-secondary label-inline">-</span>';
													}
													?>
												</td>
												<td class="text-center">
													<a href="<?= base_url('corrective_internal/view/' . $dt->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-info" title="View"><i class="fa fa-eye"></i></a>
													<?php if ($mode == 'input') : ?>
														<?php if (isset($current_user_id) && $dt->pic_pembuat_id == $current_user_id) : ?>
															<button type="button" class="btn btn-sm btn-icon rounded-circle btn-danger btn-delete" data-id="<?= $dt->id; ?>" title="Cancel / Hapus"><i class="fa fa-trash"></i></button>
														<?php endif; ?>
													<?php else : ?>
														<?php if (isset($current_user_id) && $dt->pic_car_id == $current_user_id) : ?>
															<a href="<?= base_url('corrective_internal/add/' . $dt->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-warning" title="Edit"><i class="fa fa-edit"></i></a>
														<?php endif; ?>
													<?php endif; ?>
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
<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Input CAR</h5>
				<span class="close btn-cls" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer justify-content-end">
				<button type="button" class="btn btn-primary save w-100px"><i class="fa fa-save mr-1"></i>Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#example1').DataTable({
			orderCellsTop: false,
			ordering: false
		});

		$(document).on('click', '.btn-delete', function() {
			const id = $(this).data('id');
			Swal.fire({
				title: 'Delete?',
				text: 'Apakah Anda yakin ingin menghapus data ini?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#dc3545',
				confirmButtonText: 'Ya, Hapus'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: siteurl + 'corrective_internal/delete',
						data: { id: id },
						type: 'POST',
						dataType: 'JSON',
						success: function(res) {
							if (res.status == 1) {
								Swal.fire({ title: 'Deleted!', icon: 'success', text: res.msg, timer: 2000 })
								.then(() => { location.reload(); });
							} else {
								Swal.fire({ title: 'Error!', icon: 'error', text: res.msg });
							}
						}
					});
				}
			});
		});

		$(document).on('click', '.edit', function() {
			const id = $(this).data('id');
			const url = siteurl + active_controller + 'add/' + id;
			$('.modal-title').html('Edit CAR');
			$('#modalView').modal('show');
			$('.modal-body').load(url);
		});

		$(document).on('click', '.view-data', function() {
			const id = $(this).data('id');
			const url = siteurl + active_controller + 'view/' + id;
			$('.modal-title').html('View CAR');
			$('.save').addClass('d-none');
			$('#modalView').modal('show');
			$('.modal-body').load(url);
		});

		$(document).on('click', '.save', function(e) {
			let formdata = new FormData($('#form')[0]);
			let btn = $(this);
			$.ajax({
				url: siteurl + active_controller + 'save',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true);
					btn.html('<i class="spinner spinner-border-sm"></i> Loading...');
				},
				complete: function() {
					btn.attr('disabled', false);
					btn.html('<i class="fa fa-save mr-1"></i>Save');
				},
				success: function(result) {
					if (result.status == 1) {
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 2000
						}).then(function() {
							$('#modalView').modal('hide');
							location.reload();
						});
					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						});
					}
				},
				error: function() {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server error, please try again!',
						timer: 4000
					});
				}
			});
		});
	});
</script>
