<style>
	.record-item:hover td,
	.record-item:hover td>span {
		color: #0bb783;
	}
</style>
<div class="content d-flex flex-column flex-column-fluid p-0">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="d-flex align-items-baseline flex-wrap mr-5">
				<a href="<?= base_url('dashboard'); ?>">
					<h4 class="text-dark font-weight-bold my-1 mr-2"><i class="fa fa-home"></i></h4>
				</a>
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<li class="breadcrumb-item text-muted">
						<a href="<?= base_url('list/procedures/'); ?>" class="text-muted">PROSEDUR, FORM, IK DAN RECORD</a>
					</li>
					<li class="breadcrumb-item text-muted">
						<span class="text-muted"><?= $procedure[0]->name; ?></span>
					</li>
				</ul>
			</div>
			<a href="#back" onclick="history.go(-1)" class="btn btn-primary btn-sm btn-icon mb-3"><i class="fa fa-arrow-left"></i></a>
			<h1 class="text-white fa-3x"><?= $procedure[0]->name; ?>
				<?php if ($procedure[0]->status != 'PUB') : ?>
					<?php
					switch ($procedure[0]->status) {
						case 'DFT':
							echo '<span class="badge bg-secondary text-white font-weight-bold ml-2" style="font-size: 14px; vertical-align: middle;">Draft</span>';
							break;
						case 'REV':
							echo '<span class="badge bg-warning text-white font-weight-bold ml-2" style="font-size: 14px; vertical-align: middle;">Review</span>';
							break;
						case 'COR':
							echo '<span class="badge bg-danger text-white font-weight-bold ml-2" style="font-size: 14px; vertical-align: middle;">Correction</span>';
							break;
						case 'APV':
							echo '<span class="badge bg-info text-white font-weight-bold ml-2" style="font-size: 14px; vertical-align: middle;">Approval</span>';
							break;
						case 'RVI':
							echo '<span class="badge bg-danger text-white font-weight-bold ml-2" style="font-size: 14px; vertical-align: middle;">Revisi</span>';
							break;
						default:
							echo '<span class="badge bg-danger text-white font-weight-bold ml-2" style="font-size: 14px; vertical-align: middle;">Dalam Proses Revisi</span>';
							break;
					}
					?>
				<?php endif; ?>
			</h1>
			<div class="row mb-10">
				<div class="col-md-4">
					<input type="text" name="serarch" id="search" placeholder="Pencarian" class="form-control rounded form-control-sm">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-warning nav-pills nav-bolder" id="myTab2" role="tablist">
						<li class="nav-item mx-0">
							<a class="rounded-bottom-0 nav-link active" id="tab_procedure" data-toggle="tab" href="#data_procedure">
								<span class="nav-icon ">
									<i class="fa fa-file-alt"></i>
								</span>
								<span class="text-white h5 my-0">Prosedur
									<small class="">
										<div class="badge bg-white rounded-circle text-warning"><?= (isset($procedure)) ? count($procedure) : '0'; ?></div>
									</small>
								</span>
							</a>
						</li>
						<li class="nav-item mx-0">
							<a class="rounded-bottom-0 nav-link" id="tab_form" data-toggle="tab" href="#data_form">
								<span class="nav-icon ">
									<i class="fa fa-file-alt"></i>
								</span>
								<span class="text-white h5 my-0">Form
									<small class="">
										<div class="badge bg-white rounded-circle text-warning"><?= (isset($forms)) ? count($forms) : '0'; ?></div>
									</small>
								</span>
							</a>
						</li>
						<li class="nav-item mx-0">
							<a class="rounded-bottom-0 nav-link" id="tab_guide" data-toggle="tab" href="#data_guide">
								<span class="nav-icon ">
									<i class="fa fa-file-alt"></i>
								</span>
								<span class="text-white h5 my-0">IK
									<small class="">
										<div class="badge bg-white rounded-circle text-warning"><?= (isset($guides)) ? count($guides) : '0'; ?></div>
									</small>
								</span>
							</a>
						</li>
						<li class="nav-item mx-0">
							<a class="rounded-bottom-0 nav-link" id="tab_record" data-toggle="tab" href="#data_record">
								<span class="nav-icon ">
									<i class="fa fa-file-alt"></i>
								</span>
								<span class="text-white h5 my-0">Records
									<small class="">
										<div class="badge bg-white rounded-circle text-warning"><?= (isset($countRecords)) ? ($countRecords) : '0'; ?></div>
									</small>
								</span>
							</a>
						</li>
					</ul>
					<div class="card rounded-top-0 border-0" style="background-color: zrgba(255,255,255,0.85);">
						<div class="card-body py-3">
							<div class="tab-content" id="myTabContent2">
								<div class="tab-pane fade active show" id="data_procedure" role="tabpanel" aria-labelledby="tab_procedure">
									<table class="table table-condensed table-hover">
										<thead>
											<tr class="">
												<th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
												<th class="h5 border-2 border-bottom-secondary text-center">File Name</th>
												<th class="h5 border-2 border-bottom-secondary text-center" width="50px">View</th>
											</tr>
										</thead>
										<tbody>
											<?php if (isset($procedure)) :
												$no = 0;
												foreach ($procedure as $lsPro) : $no++; ?>
													<tr class="cursor-pointer">
														<td class="h6 text-dark"><?= $no; ?></td>
														<td class="h4 text-dark d-flex align-items-center my-0"><i class="fa fa-file-alt text-success fa-2x mr-4"></i>
															<span class="mt-2"><?= $lsPro->name; ?></span>
															<?php if ($lsPro->status != 'PUB') : ?>
																<?php
																switch ($lsPro->status) {
																	case 'DFT':
																		echo '<span class="badge bg-secondary text-white font-weight-bold ml-2">Draft</span>';
																		break;
																	case 'REV':
																		echo '<span class="badge bg-warning text-white font-weight-bold ml-2">Review</span>';
																		break;
																	case 'COR':
																		echo '<span class="badge bg-danger text-white font-weight-bold ml-2">Correction</span>';
																		break;
																	case 'APV':
																		echo '<span class="badge bg-info text-white font-weight-bold ml-2">Approval</span>';
																		break;
																	case 'RVI':
																		echo '<span class="badge bg-danger text-white font-weight-bold ml-2">Revisi</span>';
																		break;
																	default:
																		echo '<span class="badge bg-danger text-white font-weight-bold ml-2">Dalam Proses Revisi</span>';
																		break;
																}
																?>
															<?php endif; ?>
														</td>
														<td class="h6 text-center" style="vertical-align: middle;">
															<button type="button" class="btn btn-icon btn-xs shadow-xs btn-info view-procedure" data-id="<?= $lsPro->id; ?>" data-toggle="tooltip" data-theme="dark" title="View Document"><i class="fa fa-eye"></i></button>
														</td>
													</tr>
												<?php endforeach;
											else : ?>
												<tr>
													<td colspan="2" class="text-center h4"><i>No data available</i></td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="data_form" role="tabpanel" aria-labelledby="tab_form">
									<table class="table table-condensed table-hover">
										<thead>
											<tr class="">
												<th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
												<th class="h5 border-2 border-bottom-secondary text-center">File Name</th>
												<th class="h5 border-2 border-bottom-secondary text-center" width="50px">View</th>
											</tr>
										</thead>
										<tbody>
											<?php if (isset($forms)) :
												$no = 0;
												foreach ($forms as $lsFrm) : $no++; ?>
													<tr class="cursor-pointer">
														<td class="h6 text-dark"><?= $no; ?></td>
														<td class="h4 text-dark d-flex align-items-center my-0"><i class="fa fa-file-alt text-primary fa-2x mr-4"></i>
															<span class="mt-2"><?= $lsFrm->name; ?></span>
														</td>
														<td class="h6 text-center" style="vertical-align: middle;">
															<button type="button" class="btn btn-icon btn-xs shadow-xs btn-info view-form" data-id="<?= $lsFrm->id; ?>" data-toggle="tooltip" data-theme="dark" title="View Document"><i class="fa fa-eye"></i></button>
														</td>
													</tr>
												<?php endforeach;
											else : ?>
												<tr>
													<td colspan="2" class="text-center h4"><i>No data available</i></td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="data_guide" role="tabpanel" aria-labelledby="tab_guide">
									<table class="table table-condensed table-hover">
										<thead>
											<tr class="">
												<th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
												<th class="h5 border-2 border-bottom-secondary text-center">File Name</th>
												<th class="h5 border-2 border-bottom-secondary text-center" width="50px">View</th>
											</tr>
										</thead>
										<tbody>
											<?php if (isset($guides)) :
												$no = 0;
												foreach ($guides as $lsGui) : $no++; ?>
													<tr class="cursor-pointer">
														<td class="h6 text-dark"><?= $no; ?></td>
														<td class="h4 text-dark d-flex align-items-center my-0"><i class="fa fa-file-alt text-info fa-2x mr-4"></i>
															<span class="mt-2"><?= $lsGui->name; ?></span>
														</td>
														<td class="h6 text-center" style="vertical-align: middle;">
															<button type="button" class="btn btn-icon btn-xs shadow-xs btn-info view-guide" data-id="<?= $lsGui->id; ?>" data-toggle="tooltip" data-theme="dark" title="View Document"><i class="fa fa-eye"></i></button>
														</td>
													</tr>
												<?php endforeach;
											else : ?>
												<tr>
													<td colspan="2" class="text-center h4"><i>No data available</i></td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<div class="tab-pane fade" id="data_record" role="tabpanel" aria-labelledby="tab_record">
									<div id="data-records">
										<?php $this->load->view('procedures/records'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered" role="document" style="height:85vh; max-height:850px;">
		<div class="modal-content overflow-hidden" style="height:100%;">
			<div class="modal-body p-0 overflow-hidden h-100" id="data-file">
				<h3 class="text-center py-5">File not found</h3>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modalViewForm" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content" data-scroll="true" data-height="700">
			<div class="modal-header">
				<h5 class="modal-title">View Document</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-1  py-5" id="data-file">
				<h3 class="text-center">File not found</h3>
			</div>
			<div class="modal-footer py-2">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modelId2" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width:90%">
		<div class="modal-content" data-scroll="true" data-height="700">
			<div class="modal-header">
				<h5 class="modal-title">View Document</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body pt-1">
				File not found
			</div>
			<div class="modal-footer py-2">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div id="modalViewImg" class="modal-img">
	<div class="d-flex w-100 position-fixed justify-content-center" style="top:20px;z-index: inherit;">
		<button type="button" class="btn btn-sm shadow-xs border w-60px btn-icon bg-white mr-2 no-zoom" title="Normal"><i class="fa fa-sync-alt"></i></button>
		<button type="button" class="btn btn-sm shadow-xs border w-60px btn-icon bg-white mr-2 zoom-out" title="Zoom Out"><i class="fa fa-minus"></i></button>
		<button type="button" class="btn btn-sm shadow-xs border w-60px btn-icon bg-white mr-2 zoom-in" title="Zoom In"><i class="fa fa-plus"></i></button>
		<button type="button" class="btn btn-sm shadow-xs border w-60px btn-icon bg-white mr-2" id="close-modal"><i class="fa fa-times"></i></button>
	</div>

	<img class="modal-content-img" id="img01">
</div>
<style>
	span p {
		margin-bottom: 0;
	}

	/* The Modal (background) */
	.modal-img {
		display: none;
		/* Hidden by default */
		position: fixed;
		/* Stay in place */
		z-index: 9999;
		/* Sit on top */
		padding-top: 100px;
		/* Location of the box */
		left: 0;
		top: 0;
		width: 100%;
		/* Full width */
		height: 100%;
		/* Full height */
		overflow: auto;
		/* Enable scroll if needed */
		background-color: rgb(0, 0, 0);
		/* Fallback color */
		background-color: rgba(0, 0, 0, 0.9);
		/* Black w/ opacity */
	}

	/* Modal Content (image) */
	.modal-img .modal-content-img {
		margin: auto;
		display: block;
		width: 100%;
		/* max-width: 700px; */
	}

	/* Caption of Modal Image */
	#caption {
		margin: auto;
		display: block;
		width: 80%;
		max-width: 700px;
		text-align: center;
		color: #ccc;
		padding: 10px 0;
		height: 150px;
	}

	/* Add Animation */
	.modal-img .modal-content-img,
	#caption {
		-webkit-animation-name: zoom;
		-webkit-animation-duration: 0.6s;
		animation-name: zoom;
		animation-duration: 0.6s;
	}

	@-webkit-keyframes zoom {
		from {
			-webkit-transform: scale(0)
		}

		to {
			-webkit-transform: scale(1)
		}
	}

	@keyframes zoom {
		from {
			transform: scale(0)
		}

		to {
			transform: scale(1)
		}
	}

	/* The Close Button */
	.close-modal {
		position: absolute;
		top: 15px;
		right: 35px;
		color: #f1f1f1;
		font-size: 40px;
		font-weight: bold;
		transition: 0.3s;
	}

	.close-modal:hover,
	.close-modal:focus {
		color: #bbb;
		text-decoration: none;
		cursor: pointer;
	}

	/* 100% Image Width on Smaller Screens */
	@media only screen and (max-width: 700px) {
		.modal-content-img {
			width: 100%;
		}
	}
</style>


<script>
	// Search functionality
	$(document).ready(function() {
		$('#search').on('keyup', function() {
			var value = $(this).val().toLowerCase();
			var activeTab = $('.tab-content .tab-pane.active').attr('id');

			if (activeTab === 'data_record') {
				// Records tab: search via AJAX across all subfolders
				var keyword = $(this).val();
				var procedure_id = '<?= $procedure[0]->id; ?>';
				if (keyword.length > 2) {
					$.post(siteurl + active_controller + 'search_records', { procedure_id: procedure_id, keyword: keyword }, function(html) {
						$('#data-records').html(html);
					});
				} else if (keyword.length == 0) {
					$('#data-records').load(siteurl + active_controller + 'getRecords/home/' + procedure_id);
				}
			} else {
				// Other tabs: simple client-side filter
				$('.tab-content .tab-pane.active table tbody tr').each(function() {
					var text = $(this).text().toLowerCase();
					$(this).toggle(text.indexOf(value) > -1);
				});
			}
		});

		// Prevent Enter key from submitting
		$('#search').on('keydown', function(e) {
			if (e.keyCode === 13) { e.preventDefault(); return false; }
		});

		// Re-apply search when switching tabs
		$('a[data-toggle="tab"]').on('shown.bs.tab', function() {
			var value = $('#search').val();
			var valueLower = value.toLowerCase();
			var activeTab = $('.tab-content .tab-pane.active').attr('id');

			if (value.length > 0) {
				if (activeTab === 'data_record') {
					// Records tab: trigger AJAX search
					var procedure_id = '<?= $procedure[0]->id; ?>';
					if (value.length > 2) {
						$.post(siteurl + active_controller + 'search_records', { procedure_id: procedure_id, keyword: value }, function(html) {
							$('#data-records').html(html);
						});
					}
				} else {
					// Other tabs: client-side filter
					$('.tab-content .tab-pane.active table tbody tr').each(function() {
						var text = $(this).text().toLowerCase();
						$(this).toggle(text.indexOf(valueLower) > -1);
					});
				}
			}
		});
	});

	function show(id) {
		$('#modelId').modal('show')
		$('#data-file').load(siteurl + active_controller + 'show/' + id)
	}

	$(document).ready(function() {
		$(document).on('click', '.view-procedure', function() {
			const id = $(this).data('id') || ''
			if (id) {
				$('#modelId').modal('show')
				$('#data-file').load(siteurl + active_controller + 'view_procedure/' + id)
			}
		})
	})

	$(document).ready(function() {
		$(document).on('click', '.view-record', function() {
			const id = $(this).data('id') || ''
			if (id) {
				$('#modelId').modal('show')
				$('#data-file').load(siteurl + active_controller + 'view_record/' + id)
			}
		})
	})

	$(document).on('click', '.view-form', function() {
		const id = $(this).data('id') || ''
		if (id) {
			$('#modelId').modal('show')
			$('#data-file').load(siteurl + active_controller + 'view_form/' + id)
		}
	})

	$(document).on('click', '.view-guide', function() {
		const id = $(this).data('id') || ''
		if (id) {
			$('#modalViewForm').modal('show')
			$('#modalViewForm').find('#data-file').load(siteurl + active_controller + 'view_guide/' + id)
		}
	})


	$(document).on('click', '.view-guide-2', function() {
		const id = $(this).data('id')
		if (id) {
			$('#modelId2 .modal-title').html('View IK')
			$('#modelId2 .modal-body').load(siteurl + active_controller + 'view_guide/' + id)
			$('#modelId2').modal('show')
		} else {
			Swal.fire('Warning!!', 'Not available data to process', 'waring', 2000);
		}
	})

	$(document).on('click', '.view-form-2', function() {
		const id = $(this).data('id')
		if (id) {
			$('#modelId2 .modal-title').html('View Form')
			$('#modelId2 .modal-body').load(siteurl + active_controller + 'view_form/' + id)
			$('#modelId2').modal('show')
		} else {
			Swal.fire('Warning!!', 'Not available data to process', 'waring', 2000);
		}
	})

	$(document).on('click', '.record-breadcrumb', function() {
		const id = $(this).data('id') || '';
		const procedure_id = $(this).data('procedure');
		let url = '';
		if (id) {
			url = siteurl + active_controller + 'getRecords/find/' + procedure_id + '/' + id;
		} else {
			url = siteurl + active_controller + 'getRecords/home/' + procedure_id;
		}
		$('#data-records').load(url);
	});

	$(document).on('click', '#home', function() {
		const procedure_id = $(this).data('procedure')
		const url = siteurl + active_controller + 'getRecords/home/' + procedure_id;
		$('#data-records').load(url)
		// alert(url)
	})

	$(document).on('click', '#back', function() {
		const id = $(this).data('id')
		const procedure_id = $(this).data('procedure')
		const url = siteurl + active_controller + 'getRecords/back/' + procedure_id + "/" + id
		$('#data-records').load(url)
		// alert(url)
	})

	$(document).on('click', '#refresh', function() {
		const id = $(this).data('id') || ''
		const procedure_id = $(this).data('procedure')
		const url = siteurl + active_controller + 'getRecords/refresh/' + procedure_id + "/" + id
		$('#data-records').load(url)
		// alert(url)
	})

	$(document).on('click', '.record-item', function() {
		const id = $(this).data('id')
		const procedure_id = $(this).data('procedure')
		const url = siteurl + active_controller + 'getRecords/find/' + procedure_id + '/' + id
		$('#data-records').load(url)
		// alert(url)
	})

	/* PREVIEW IMAGE */
	$(document).on('click', '.view-image', function() {
		const urlImg = $(this).parents('.dropzone-wrapper').find('img').attr('src')
		const img = "<img src='" + urlImg + "' class='img-'>"
		// Get the modal
		var modal = document.getElementById("modalViewImg");

		// Get the image and insert it inside the modal - use its "alt" text as a caption
		// var img = document.getElementById("myImg");
		var modalImg = document.getElementById("img01");
		// var captionText = document.getElementById("caption");
		// img.onclick = function() {
		modal.style.display = "block";
		modalImg.src = urlImg;
		// 	captionText.innerHTML = this.alt;
		// }

		// Get the <span> element that closes the modal
		var span = document.getElementById("close-modal");

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
		}
	})

	/* ZOOM-IN & ZOOM-OUT */

	let n = 100
	$(document).on('click', '.zoom-in', function() {
		n = n + 5
		$('.modal-img img.modal-content-img ').css('width', n + '%')
	})
	$(document).on('click', '.zoom-out', function() {
		n = n - 5
		$('.modal-img img.modal-content-img ').css('width', n + "%")
	})
	$(document).on('click', '.no-zoom', function() {
		n = 100
		$('.modal-img img.modal-content-img ').css('width', n + "%")
	})
</script>