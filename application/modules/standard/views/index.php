<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<div class="mt-4 float-right ">
						<a href="<?= base_url($this->uri->segment(1) . '/add'); ?>" class="btn btn-primary" data-toggle="tooltip" title="Add New">
							<i class="fa fa-plus mr-1"></i>Add New
						</a>
					</div>
				</div>
				<div class="card-body">
					<!-- Nav tabs -->
					<div class="d-flex justify-content-between border border-top-0 border-right-0 border-left-0">
						<ul class="nav nav-tabs nav-pills pb-3 border-0" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<button class="nav-link active btn-sm" id="Published-tab" data-toggle="tab" data-target="#Published" type="button" role="tab" aria-controls="Published" aria-selected="true">Published <span class="badge badge-circle badge-white text-primary ml-2"><?= count($data); ?></span></button>
							</li>
							<li class="nav-item" role="presentation">
								<button class="nav-link btn-sm" id="Draft-tab" data-toggle="tab" data-target="#Draft" type="button" role="tab" aria-controls="Draft" aria-selected="false">Draft <span class="badge badge-circle badge-white text-primary ml-2"><?= count($drafts); ?></span></button>
							</li>
						</ul>
						<ul class="nav nav-tabs nav-pills pb-3 border-0">
							<li class="nav-item" role="presentation">
								<a href="<?= base_url($this->uri->segment(1) . '/export_excel/'); ?>" class="btn btn-sm btn-default" type="button" role="tab" aria-controls="Published" aria-selected="true">Export <i class="fa fa-file-export"></i></a>
							</li>
						</ul>
					</div>

					<!-- Tab panes -->
					<div class="tab-content mt-3">
						<div class="tab-pane fade active show" id="Published" role="tabpanel" aria-labelledby="Published-tab">
							<table id="example1" class="table table-bordered table-condensed table-sm table-hover datatable">
								<thead class="text-center table-light">
									<tr class="text-center">
										<th width="5%" class="text-center p-2">No.</th>
										<th width="15%" class="p-2">Scopes</th>
										<th class="text-left p-2">Standard Name</th>
										<th class="text-left p-2">Year</th>
										<th width="150" class="p-2">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($data) && $data) :
										$n = 0;
										foreach ($data as $dt) : $n++; ?>
											<tr>
												<td class="p-2 text-center"><?= $n; ?></td>
												<td class="p-2 text-center"><?= isset($ArrScopes[$dt->scope_id]) ? $ArrScopes[$dt->scope_id] : '<span class="text-muted">undefined</span>'; ?></td>
												<td class="p-2"><?= $dt->name; ?></td>
												<td class="p-2"><?= $dt->year; ?></td>
												<td class="text-center p-2">

													<button type="button" class="btn btn-xs btn-icon btn-info view" data-id="<?= $dt->id; ?>" data-toggle="tooltip" title="View"><i class="fa fa-search"></i></button>
													<a href="<?= base_url($this->uri->segment(1) . '/edit/' . $dt->id); ?>" class="btn btn-xs btn-icon btn-warning edit" role="tootltip" data-id="<?= $dt->id; ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil-alt"></i></a>
													<button type="button" class="btn btn-xs btn-icon btn-danger delete" data-id="<?= $dt->id; ?>" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
									<?php endforeach;
									endif; ?>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade" id="Draft" role="tabpanel" aria-labelledby="Draft-tab">
							<table id="example2" class="table table-bordered table-sm table-hover datatable">
								<thead class="text-center table-light">
									<tr class="text-center">
										<th width="80">No.</th>
										<th width="15%">Scopes</th>
										<th class="text-left">Standard Name</th>
										<th class="text-left">Year</th>
										<th width="150">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($drafts) && $drafts) :
										$n = 0;
										foreach ($drafts as $draft) : $n++; ?>
											<tr class="text-center">
												<td><?= $n; ?></td>
												<td class="p-2"><?= $ArrScopes[$draft->scope_id]; ?></td>
												<td class="p-2"><?= $draft->name; ?></td>
												<td class="p-2"><?= $draft->year; ?></td>
												<td class="p-2">
													<a href="<?= base_url($this->uri->segment(1) . '/edit/' . $draft->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-warning edit" data-toggle="tooltip" data-id="<?= $draft->id; ?>" title="View"><i class="fa fa-pencil-alt"></i></a>
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-danger delete" data-id="<?= $draft->id; ?>" data-toggle="tooltip" title="Delete"><i class="fa fa-trash"></i></button>
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
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">View Standard</h5>
				<span class="close" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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

		$('#example1,#example2').DataTable({
			orderCellsTop: false,
			// fixedHeader: true,
			// scrollX: true,
			ordering: false,
			// info: false
		});

		$(document).on('click', '.view', function() {
			let id = $(this).data('id')
			if (id) {
				$('#modalView').modal('show')
				$('#modalView .modal-body').load(base_url + active_controller + 'view/' + id)
			} else {
				Swal.fire({
					title: 'Warning!',
					text: 'Data not valid',
					icon: 'warning',
					timer: 2000
				})
			}
		})

		$(document).on('click', '.delete', function() {
			let id = $(this).data('id')
			if (id) {
				Swal.fire({
					title: 'Confirm!',
					text: 'Are you sure you want to delete this data??',
					icon: 'question',
					showCancelButton: true
				}).then((value) => {
					if (value.isConfirmed) {
						$.ajax({
							url: base_url + active_controller + 'delete/' + id,
							type: 'POST',
							dataType: 'JSON',
							data: {
								id
							},
							success: function(res) {
								if (res.status == 1) {
									Swal.fire({
										title: 'Success!',
										icon: 'success',
										text: res.msg,
										timer: 3000
									}).then(() => {
										location.reload();
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
			}
		})

	})
</script>