<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
				</div>
				<div class="card-body">

					<div class="row mb-3">
						<div class="col-md-6">
							<h2 class="mb-4"><i class="fa fa-desktop"></i> Current Project</h2>
							<table class="table table-striped table-bordered table-sm">
								<tr>
									<th width="150">Company</th>
									<td><?= ($reference) ? $reference->nm_perusahaan : ''; ?></td>
								</tr>
								<tr>
									<th>Branch</th>
									<td><?= ($reference->branch_name) ? $reference->branch_name : 'Main Company'; ?></td>
								</tr>
								<tr>
									<th>Created On</th>
									<td><?= ($reference) ? $reference->sdate : ''; ?></td>
								</tr>
								<tr>
									<th>Last Review</th>
									<td><?= ($reference) ? $reference->last_review : ''; ?></td>
								</tr>
								<tr>
									<th>Count Review</th>
									<td><?= ($reference) ? $reference->counter_review : ''; ?></td>
								</tr>
								<tr>
									<th>Review By</th>
									<td><?= ($reference) && isset($ArrUsers[$reference->review_by]) ? $ArrUsers[$reference->review_by] : ''; ?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-4">
							<!-- <h2 class="mb-4"><i class="fa fa-stream"></i> Current Summary Compliance</h2>
							<table class="table table-bordered table-sm">
								<tbody>
									<tr>
										<th>Compliance</th>
										<td class="text-center"><?= ($summary) ? $summary->total_compliance : '0'; ?></td>
									</tr>
									<tr>
										<th>Not Compliance</th>
										<td class="text-center"><?= ($summary) ? $summary->total_not_compliance : '0'; ?></td>
									</tr>
									<tr>
										<th>Not Applicable</th>
										<td class="text-center"><?= ($summary) ? $summary->total_not_applicable : '0'; ?></td>
									</tr>
								</tbody>
								<tfoot>
									<tr>
										<th>% Compliance</th>
										<th class="text-center"><?= ($summary && $summary->total_compliance) ? round(($summary->total_compliance / ($summary->total_compliance + $summary->total_not_compliance)) * 100) : '0'; ?>%</th>
									</tr>
								</tfoot>
							</table> -->
						</div>
						<!-- <div class="col-md-2">
							<h2 class="mb-4"><i class="fa fa-tools"></i> Options</h2>
							<div class="btn-block">
								<button type="button" class="btn btn-success btn-block" data-id="<?= ($reference) ? $reference->id : ''; ?>" id="compilation"><i class="fa fa-bolt"></i> Compilation</button>
								<button type="button" class="btn btn-info btn-block" data-id="<?= ($reference) ? $reference->id : ''; ?>" id="view-compilation"><i class="fa fa-eye"></i> View Compilation</button>
								<a target="_blank" href="<?= base_url($this->uri->segment(1) . '/export_pdf/' . (($reference) ? $reference->id : '')); ?>" class="btn btn-light btn-block to-pdf" data-comp_id="<?= (($reference) ? $reference->company_id : ''); ?>"><i class="fa fa-file-pdf"></i>Export PDF</a>
							</div>
						</div> -->
					</div>
					<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply" aria-hidden="true"></i> Back</a>

					<!-- <hr> -->
					<div class="mb-5 mt-5">
						<h2 class="mb-5"><i class="fa fa-desktop"></i> List Regulations</h2>
						<hr>
						<div id="accSub" role="tablist" aria-multiselectable="true">
							<?php if ($subjects) foreach ($subjects as $k => $sub): $k++; ?>
								<div class="card mb-3 border-0 " style="border-radius: 10px;">
									<div class="card-header bg-light py-4 border-0 cursor-pointer" role="tab" id="sectionDetail" style="border-radius: 10px;">
										<h4 class="mb-0 font-weight-bolder" data-toggle="collapse" data-parent="#accSub" href="#sub<?= $k; ?>" aria-expanded="true" aria-controls="sub<?= $k; ?>">
											<?= $k . ". " . $sub->name; ?>
										</h4>
									</div>

									<div id="sub<?= $k; ?>" class="collapse" role="tabpanel" aria-labelledby="sectionDetail">
										<div class="card-body">
											<table class="table dataTable display table-bordered table-sm table-condensed table-hover mb-5">
												<thead class="text-center table-light">
													<tr class="text-center">
														<th width="3%">No.</th>
														<th class="text-left">Regulation</th>
														<!-- <th class="text-left" width="100">Last Update</th> -->
														<th class="text-center table-success" width="40">C</th>
														<th class="text-center table-danger" width="40">NC</th>
														<th class="text-center table-secondary" width="40">NA</th>
														<th width="80" class="no-order">Action</th>
													</tr>
												</thead>
												<tbody>
													<?php if (isset($ArrReg[$sub->id]) && $ArrReg[$sub->id]) :
														$n = 0;
														$C = $NC = $NA = 0;
														foreach ($ArrReg[$sub->id] as $dt) : $n++;
															$C += $dt->total_compliance;
															$NC += $dt->total_not_compliance;
															$NA += $dt->total_not_applicable;

													?>
															<tr class="">
																<td><?= $n; ?></td>
																<td class="text-left"><?= $dt->name; ?></td>
																<!-- <td class="text-center"><?= $dt->last_update; ?></td> -->
																<td class="text-center align-middle table-success"><?= $dt->total_compliance; ?></td>
																<td class="text-center align-middle table-danger"><?= $dt->total_not_compliance; ?></td>
																<td class="text-center align-middle table-secondary"><?= $dt->total_not_applicable; ?></td>
																<td class="text-center">
																	<button type="button" class="btn btn-primary btn-sm btn-icon rounded-circle view-comp-reg" data-id="<?= $dt->id; ?>"><i class="fa fa-eye"></i></button>
																	<a href="<?= base_url($this->uri->segment(1) . "/details/" . $dt->id); ?>" class="btn btn-sm btn-icon rounded-circle btn-warning detail" data-id="<?= $dt->id; ?>" title="Edit Data"><i class="fas fa-tasks"></i></a>
																</td>
															</tr>
													<?php endforeach;
													endif; ?>
												</tbody>
											</table>

											<div class="row mt-5">
												<div class="col-md-6">
													<!-- <h4 class="mb-4">Summary</h4> -->
													<table class="table table-bordered table-sm">
														<tbody>
															<tr>
																<th>Compliance</th>
																<td class="text-center"><?= ($C) ? $C : '0'; ?></td>
															</tr>
															<tr>
																<th>Not Compliance</th>
																<td class="text-center"><?= ($NC) ? $NC : '0'; ?></td>
															</tr>
															<tr>
																<th>Not Applicable</th>
																<td class="text-center"><?= ($NA) ? $NA : '0'; ?></td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<th>% Compliance</th>
																<th class="text-center"><?= ($C && $NC) ? round(($C / ($C + $NC)) * 100) : '0'; ?>%</th>
															</tr>
														</tfoot>
													</table>
												</div>
												<div class="col-md-6">
													<div class="d-flex w-150px flex-column flex-column-auto">
														<button type="button" class="btn btn-sm btn-primary mb-2 review" data-id="<?= ($sub->id) ? $sub->id : ''; ?>"><i class="fa fa-bolt" aria-hidden="true"></i> Submit Review </button>
														<button type="button" class="btn btn-sm btn-danger history" data-id="<?= ($sub->id) ? $sub->id : ''; ?>"><i class="fa fa-history" aria-hidden="true"></i> History Review </button>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- <div class="row py-4">
						<div class="col-md-6">
							<div class="card shadow-sm border-0">
								<div class="card-body p-3">
									<div class="">
										<h2 class="mb-5"><i class="fa fa-list"></i> Complete Review</h2>
										<table class="table table-bordered table-striped">
											<thead class="table-light">
												<tr style="vertical-align: middle;" class="text-center">
													<th style="vertical-align: middle;" width="50">No</th>
													<th style="vertical-align: middle;" width="100">Review Date</th>
													<th style="vertical-align: middle;">Compliance</th>
													<th style="vertical-align: middle;">Not Compliance</th>
													<th style="vertical-align: middle;">Not Aplicable</th>
													<th style="vertical-align: middle;">%</th>
													<th style="vertical-align: middle;">Docs.</th>
												</tr>
											</thead>
											<tbody>
												<?php if (isset($reviews)) : $n = 0; ?>
													<?php foreach ($reviews as $review) : $n++; ?>
														<tr>
															<td class="text-center"><?= $n; ?></td>
															<td class="text-center"><?= $review->last_review; ?></td>
															<td class="text-center"><?= $review->total_compliance; ?></td>
															<td class="text-center"><?= $review->total_not_compliance; ?></td>
															<td class="text-center"><?= $review->total_not_applicable; ?></td>
															<td class="text-center"><?= (isset($review->total_compliance) && $review->total_compliance) ? round(($review->total_compliance / ($review->total_compliance + $review->total_not_compliance)) * 100) : '0'; ?>%</td>
															<td class="text-center">
																<a target="_blank" href="<?= base_url("directory/COMPILATIONS/" . $review->document); ?>" title="Download Compilation Review">
																	<div class="fa fa-file-pdf font-size-h3 text-danger"></div>
																</a>
															</td>
														</tr>
													<?php endforeach; ?>
												<?php endif; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div> -->
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 90%;">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<span class="close btn-cls" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer justify-content-end">
				<!-- <button type="button" class="btn btn-primary save w-100px"><i class="fa fa-save"></i>Save</button> -->
				<button type="button" class="btn btn-danger text-end" onclick="clear($('.modal-body'));setTimeout(()=>{$('.save').removeClass('d-none')},500)" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalView2" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<span class="btn btn-xs btn-icon" data-dismiss="modal" aria-label="Close">
					<i class="fa fa-times" aria-hidden="true"></i>
				</span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer justify-content-end">
				<!-- <button type="button" class="btn btn-primary save w-100px"><i class="fa fa-save"></i>Save</button> -->
				<button type="button" class="btn btn-danger text-end" onclick="clear($('.modal-body'));setTimeout(()=>{$('.save').removeClass('d-none')},500)" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		$('h4[data-toggle="collapse"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true
			}).columns.adjust();
		});

		$('.dataTable').DataTable({
			orderCellsTop: false,
			fixedHeader: true,
			// scrollX: true,
			autoWidth: false,
			// paging: false,
			ordering: true,
			// info: false
			"columnDefs": [{
				"targets": 5,
				"orderable": false
			}]
		});


		$(document).on('click', '.review', function(e) {
			const subject = $(this).data('id')
			$('#modalView .modal-dialog').addClass('modal-xl')
			$('#modalView').modal('show')
			$('#modalView .modal-body').load(siteurl + active_controller + 'review/' + subject)
		})

		$(document).on('click', '.history', function(e) {
			const subject = $(this).data('id')
			$('#modalView2 .modal-dialog')
			$('#modalView2').modal('show')
			$('#modalView2 .modal-title').html('<i class="fa fa-history" aria-hidden="true"></i>  History Review')
			$('#modalView2 .modal-body').load(siteurl + active_controller + 'history/' + subject)
		})


		$(document).on('click', '#view-compilation', function(e) {
			const id = $(this).data('id')
			$('#modalView .modal-dialog')
			$('#modalView').modal('show')
			$('#modalView .modal-body').load(siteurl + active_controller + 'view_compliance/' + id)
		})

		$(document).on('click', '#compilation', function(e) {
			const id = $(this).data('id')
			$('#modalView .modal-dialog').addClass('modal-xl')
			$('#modalView').modal('show')
			$('#modalView .modal-body').load(siteurl + active_controller + 'compilation/' + id)
		})

		$(document).on('click', '.view-comp-reg', function(e) {
			const id = $(this).data('id')
			$('#modalView .modal-dialog').addClass('modal-xl')
			$('#modalView .modal-dialog').css('max-width', '90%')
			$('#modalView').modal('show')
			$('#modalView .modal-body').load(siteurl + active_controller + 'view_compliance_regulation/' + id)
		})

		$(document).on('click', '#process_compilation', function(e) {
			const id = $(this).data('id')
			const subject = $(this).data('subject')
			let btn = $(this)
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to porcess review?',
				icon: 'question',
				showCancelButton: true
			}).then((value) => {
				// let formdata = new FormData($('#form')[0]);
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'save_review',
						data: {
							id,
							subject
						},
						dataType: 'JSON',
						type: 'POST',
						beforeSend: function() {
							btn.html('<i class="spinner spinner-border-sm"></i>Loading...').attr('disabled')
						},
						complete: function() {
							btn.html('<i class="fa fa-sync"></i>Process').attr('disabled')
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire({
									title: 'Success!',
									text: result.msg,
									icon: 'success',
								}).then(() => {
									location.reload();
								})
							} else {
								Swal.fire({
									title: 'Warning!',
									text: result.msg,
									icon: 'warning',
								})
							}
						},
						error: function() {
							Swal.fire('Error!!', 'Server timeout', 'error', 3000)
						}
					})
				}
			})
		})

		$(document).on('change', '#sort_status', function() {
			const id = $(this).data('id')
			const status = $('#sort_status').val() || ''
			// alert(id + " " + status)

			if (id) {
				$('#show-data').load(siteurl + active_controller + 'show_compilation/' + id + "/" + status)
			} else {
				Swal.fire('Error!!', 'Server timeout', 'error', 3000)
			}
		})

		$(document).on('click', '#export_pdf', function() {
			const id = $(this).data('id')
			const status = $('#sort_status').val() || ''
			if (id) {
				window.open(siteurl + active_controller + 'export_pdf/' + id + "/" + status, "_blank")
			}
		})


	})

	function validation(field) {
		if (field.val() == '' || field.val() == null) {
			field.addClass('is-invalid')
		} else {
			field.removeClass('is-invalid')
		}
	}

	function clear(e) {
		setTimeout(() => {
			$(e).html('');
		}, 500)
	}
</script>