<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
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
										<th width="150">Status</th>
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
													<a href="<?= base_url('approval_corrective_internal/view/' . $dt->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-info" title="View"><i class="fa fa-eye"></i></a>
													<?php if (isset($dt->status) && $dt->status == 'waiting_approval' && isset($current_user_id) && $dt->pic_pembuat_id == $current_user_id) : ?>
														<a href="<?= base_url('approval_corrective_internal/approve/' . $dt->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-success" title="Approve/Reject"><i class="fa fa-check"></i></a>
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

<script>
	$(document).ready(function() {
		$('#example1').DataTable({
			orderCellsTop: false,
			ordering: false
		});
	});
</script>
