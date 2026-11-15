<style>
	.btn-opsi {
		display: none;
	}

	tr:hover .btn-opsi {
		display: block;
	}

	tr:hover .text-name {
		color: #0bb783;
	}

	.cut-item {
		opacity: 0.55 !important;
		background-color: #fff8dd !important;
		border-left: 3px dashed #ffa800 !important;
	}

	tr.table-active {
		background-color: #f1faff !important;
	}

	tr.tree-row {
		transition: background-color 0.5s ease;
	}

	tr.tree-row.row-updated {
		background-color: #d4edda !important;
	}
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<form id="form-procedure" enctype="multipart/form-data">
				<input type="hidden" name="id" id="procedure_id" value="<?= $data->id; ?>">

				<div class="card card-stretch shadow card-custom">
					<div class="card-header justify-content-between d-flex align-items-center">
						<h2 class="m-0"><i class="fa fa-folder-open text-warning mr-2"></i><?= $title; ?> : <span class="text-primary"><?= isset($data->name) ? $data->name : ''; ?></span></h2>
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
					</div>

					<div class="card-body pt-3">
						<div class="d-flex justify-content-between align-items-center mb-3">
							<div class="d-flex align-items-center flex-wrap">
								<button type="button" class="btn btn-warning add_folder mr-2"><i class="fa fa-folder-plus"></i> Add Folder</button>
								<div id="selection-actions" style="display:none;" class="btn-group">
									<button type="button" class="btn btn-outline-primary" id="btn-copy-selected" title="Copy selected items">
										<i class="fa fa-copy"></i> Copy (<span class="selected-count">0</span>)
									</button>
									<button type="button" class="btn btn-outline-warning" id="btn-move-selected" title="Move selected items">
										<i class="fa fa-cut"></i> Move (<span class="selected-count">0</span>)
									</button>
									<button type="button" class="btn btn-outline-secondary" id="btn-choose-dest-selection" title="Choose destination...">
										<i class="fa fa-folder-open"></i> To...
									</button>
									<button type="button" class="btn btn-clean text-danger" id="btn-clear-selection" title="Clear selection">
										<i class="fa fa-times"></i>
									</button>
								</div>
							</div>
							<div class="d-flex align-items-center">
								<button type="button" class="btn btn-success mr-2 font-weight-bold" id="btn-paste-toolbar" style="display:none;">
									<i class="fa fa-paste"></i> Paste (<span id="clipboard-count">0</span>)
								</button>
								<div class="input-group" style="width: 300px;">
									<div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-search"></i></span></div>
									<input type="text" class="form-control" id="search-record" placeholder="Search files or folders...">
								</div>
							</div>
						</div>

						<!-- Clipboard Notification Bar -->
						<div id="clipboard-bar" class="alert alert-custom alert-light-success fade show mb-3" style="display:none; border: 1px dashed #1bc5bd;">
							<div class="alert-icon"><i class="fa fa-clipboard-check fa-2x text-success"></i></div>
							<div class="alert-text">
								<span class="font-weight-bolder h6 mb-0 d-block" id="clipboard-title">0 Item(s) in clipboard</span>
								<span class="text-muted small" id="clipboard-desc">Ready to paste. Click "Paste Here" or pick destination.</span>
							</div>
							<div class="alert-close d-flex align-items-center">
								<button type="button" class="btn btn-sm btn-success font-weight-bold mr-2" id="btn-paste-current">
									<i class="fa fa-paste"></i> Paste Here (Root)
								</button>
								<button type="button" class="btn btn-sm btn-primary font-weight-bold mr-2" id="btn-choose-dest">
									<i class="fa fa-folder-open"></i> Choose Destination...
								</button>
								<button type="button" class="btn btn-sm btn-secondary font-weight-bold" id="btn-cancel-clipboard">
									<i class="fa fa-times"></i> Cancel
								</button>
							</div>
						</div>

						<hr>
						<div id="data-records">
							<input type="hidden" id="refresh_id" value="">
							<table id="records-tree-table" class="table table-hover">
								<thead>
									<tr>
										<th class="py-0 text-center" width="40">
											<label class="checkbox checkbox-single checkbox-primary mb-0 justify-content-center">
												<input type="checkbox" id="check-all-records" title="Select All">
												<span></span>
											</label>
										</th>
										<th class="py-0">File or Folder Name</th>
										<th class="py-0 text-left">Description</th>
										<th class="py-0 text-right">Last Update</th>
										<th class="py-0 text-center" width="50">Opsi</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($getRecords) && count($getRecords) > 0) : $n = 0; ?>
										<?php foreach ($getRecords as $form) : $n++;
											// Determine if this file is locked for current user
											$is_locked = false;
											if ($form->flag_type == 'FILE' && $form->flag_confidential == '1') {
												$has_level = (isset($form->confidential_group_ids) && $form->confidential_group_ids);
												if ($has_level) {
													$allowed = explode(',', $form->confidential_group_ids);
													if (isset($user_group_id) && in_array($user_group_id, $allowed)) {
														// User's group is in allowed list, now check if user has confidential flag
														$is_locked = (!isset($user_confidential) || $user_confidential != '1');
													} else {
														// User's group not in allowed list
														$is_locked = true;
													}
												} else {
													// No level restriction, just check user's confidential flag
													$is_locked = (!isset($user_confidential) || $user_confidential != '1');
												}
											}
										?>
											<tr class="tree-row" data-id="<?= $form->id; ?>" data-parent-id="" data-depth="0" data-loaded="false">
												<td class="py-1 text-center align-middle" width="40">
													<label class="checkbox checkbox-single checkbox-primary mb-0 justify-content-center">
														<input type="checkbox" class="record-checkbox" value="<?= $form->id; ?>" data-type="<?= $form->flag_type; ?>" data-name="<?= htmlspecialchars($form->name); ?>">
														<span></span>
													</label>
												</td>
												<td class="py-1">
													<a href="javascript:void(0)" data-id="<?= $form->id; ?>" class="cursor-pointer <?= ($form->flag_type == 'FOLDER') ? 'folder' : 'file'; ?> text-dark">
														<div class="d-flex justify-content-start align-items-center">
															<?php if ($form->flag_type == 'FOLDER') : ?>
																<i class="fa fa-chevron-right mr-2 toggle-icon" style="transition: transform 0.2s;"></i>
																<i class="fa fa-folder text-warning fa-2x mr-3"></i>
															<?php elseif (isset($form->link_url) && $form->link_url) : ?>
																<i class="fa fa-link text-info mr-3 ml-4"></i>
															<?php else : 
																$ext = isset($form->ext) && $form->ext ? strtolower(str_replace('.', '', $form->ext)) : (isset($form->file_name) ? strtolower(pathinfo($form->file_name, PATHINFO_EXTENSION)) : '');
																$iconColor = '#6c757d';
																if ($ext == 'pdf') { $icon = 'fas fa-file-pdf'; $iconColor = '#e5252a'; }
																elseif (in_array($ext, ['xls', 'xlsx', 'csv'])) { $icon = 'fas fa-file-excel'; $iconColor = '#217346'; }
																elseif (in_array($ext, ['doc', 'docx'])) { $icon = 'fas fa-file-word'; $iconColor = '#2b579a'; }
																elseif (in_array($ext, ['ppt', 'pptx'])) { $icon = 'fas fa-file-powerpoint'; $iconColor = '#d24726'; }
																elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'])) { $icon = 'fas fa-file-image'; $iconColor = '#17a2b8'; }
																elseif (in_array($ext, ['zip', 'rar', '7z', 'tar', 'gz'])) { $icon = 'fas fa-file-archive'; $iconColor = '#ffc107'; }
																elseif (in_array($ext, ['txt', 'rtf'])) { $icon = 'fas fa-file-alt'; $iconColor = '#6c757d'; }
																else { $icon = 'fas fa-file-alt'; $iconColor = '#6c757d'; }
															?>
																<i class="<?= $icon ?> mr-3 ml-4" style="color: <?= $iconColor ?>;"></i>
															<?php endif; ?>
															<span class="text-name mt-3 h5"><?= $form->name; ?></span>
														</div>
													</a>
												</td>
												<td class="py-1 text-left">
													<span class="text-muted mt-3 h6"><?= (isset($form->description) && $form->description) ? $form->description : '-'; ?></span>
												</td>
												<td class="py-1 text-right">
													<div class="d-flex justify-content-end align-items-center">
														<h6 class="mt-4 ml-4"><?= $form->created_at; ?></h6>
													</div>
												</td>
												<td class="py-1 text-center">
													<div class="btn-opsi mt-1">
														<div class="dropdown">
															<button class="btn btn-sm btn-icon btn-primary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i></button>
															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($form->flag_type == 'FILE'): ?>
																	<?php if ($is_locked) : ?>
																		<a href="javascript:void(0)" class="dropdown-item disabled" style="pointer-events:none;opacity:0.6;"><i class="fa fa-lock text-danger mr-2"></i> Confidential Document</a>
																	<?php elseif (isset($form->link_url) && $form->link_url) : ?>
																		<a href="<?= $form->link_url; ?>" target="_blank" class="dropdown-item" title="Open Link"><i class="fa fa-external-link-alt text-info mr-2"></i> Open Link</a>
																	<?php else: ?>
																		<a href="javascript:void(0)" class="dropdown-item view-record" title="View Document" data-id="<?= $form->id; ?>"><i class="<?= isset($icon) ? $icon : 'fas fa-file-pdf' ?> mr-2" style="color: <?= isset($iconColor) ? $iconColor : '#2b579a' ?>;"></i> View</a>
																	<?php endif; ?>
																	<?php if (isset($user_confidential) && $user_confidential == '1' && !$is_locked) : ?>
																	<a href="javascript:void(0)" class="dropdown-item toggle-confidential" data-id="<?= $form->id; ?>" data-value="<?= $form->flag_confidential; ?>">
																		<i class="fa fa-lock text-dark mr-2"></i> Confidential
																		<?php if ($form->flag_confidential == '1') : ?>
																			<span class="label label-inline label-danger ml-2">ON</span>
																		<?php else : ?>
																			<span class="label label-inline label-secondary ml-2">OFF</span>
																		<?php endif; ?>
																	</a>
																	<?php endif; ?>
																	<?php if (!$is_locked) : ?>
																	<div class="dropdown-divider"></div>
																	<a href="javascript:void(0)" class="dropdown-item edit-record" title="Edit Document" data-id="<?= $form->id; ?>" data-name="<?= $form->name; ?>"><i class="fa fa-edit text-warning mr-2"></i> Edit</a>
																	<?php endif; ?>
																<?php else: ?>
																	<a href="javascript:void(0)" class="dropdown-item add-folder-inside" data-id="<?= $form->id; ?>"><i class="fa fa-folder-plus text-warning mr-2"></i> Add Folder</a>
																	<a href="javascript:void(0)" class="dropdown-item add-record-inside" data-id="<?= $form->id; ?>"><i class="fa fa-file-medical text-primary mr-2"></i> Add Record</a>
																	<a href="javascript:void(0)" class="dropdown-item paste-into-folder" data-id="<?= $form->id; ?>" data-name="<?= htmlspecialchars($form->name); ?>" style="display:none;"><i class="fa fa-paste text-success mr-2"></i> Paste into this folder</a>
																	<div class="dropdown-divider"></div>
																	<a href="javascript:void(0)" class="dropdown-item edit-folder" title="Edit Folder" data-id="<?= $form->id; ?>" data-name="<?= $form->name; ?>"><i class="fa fa-edit text-warning mr-2"></i> Edit</a>
																<?php endif; ?>
																<?php if (!($form->flag_type == 'FILE' && $is_locked)) : ?>
																<a href="javascript:void(0)" class="dropdown-item copy-single-record" title="Copy" data-id="<?= $form->id; ?>" data-name="<?= htmlspecialchars($form->name); ?>" data-type="<?= $form->flag_type; ?>"><i class="fa fa-copy text-info mr-2"></i> Copy</a>
																<a href="javascript:void(0)" class="dropdown-item move-record" title="Move" data-id="<?= $form->id; ?>"><i class="fa fa-random text-success mr-2"></i> Move</a>
																<div class="dropdown-divider"></div>
																<a href="javascript:void(0)" class="dropdown-item delete-record" title="Delete" data-id="<?= $form->id; ?>"><i class="fa fa-trash text-danger mr-2"></i>Delete</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="5" class="text-center py-5">
												<i class="fa fa-folder-open text-muted fa-4x mb-3"></i>
												<h5 class="text-muted">~ Folder Kosong ~</h5>
												<button type="button" class="btn btn-warning mt-3 add_folder"><i class="fa fa-folder-plus"></i> Add Folder</button>
											</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" onclick="$('#content_modal').html('')" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-flow-detail">
				<div id="content_modal"></div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalRecord" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Upload Record</h5>
				<button type="button" class="close btn" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form-records">
				<div id="record-content">
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

		let id = '<?= $data->id; ?>'
		$.getJSON(siteurl + 'procedures/load_file_flow/' + id, function(result) {
			var data = result.data
			var d = ''
			const url = siteurl + 'directory/FLOW_FILE/' + data.company_id + '/' + data.flow_file;
			console.log(url);
			if (!data.flow_file) {
				$("#pdf-preview").css('display', 'none');
			}
			if (data.flow_file) {
				fetch(url)
					.then((res) => res.blob())
					.then((myBlob) => {
						// console.log(myBlob);
						// logs: Blob { size: 1024, type: "image/jpeg" }
						myBlob.name = data.flow_file;
						myBlob.lastModified = new Date();
						// console.log(myBlob instanceof File);
						// logs: false
						_OBJECT_URL = URL.createObjectURL(myBlob)
						// console.log(_OBJECT_URL);
						showPDF(_OBJECT_URL);
					});
			}
		});


		$('button[data-toggle="tab"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true
			}).columns.adjust();
		});

		$('.datatable').DataTable()
		$('.select2').select2({
			placeholder: "Choose an options",
			width: "100%",
			allowClear: true
		})

		// Restore tree expansion on initial load
		try {
			const savedExpanded = JSON.parse(sessionStorage.getItem('records_expanded_' + $('#procedure_id').val()) || '[]');
			if (savedExpanded && savedExpanded.length > 0) {
				setTimeout(function() {
					restoreExpandedFolders(savedExpanded);
				}, 200);
			}
		} catch (e) {
			console.error('Error restoring expanded tree state:', e);
		}

		function handlePromise(promiseList) {
			return promiseList.map(promise =>
				promise.then((res) => ({
					status: 'ok',
					res
				}), (err) => ({
					status: 'not ok',
					err
				}))
			)
		}

		Promise.allSettled = function(promiseList) {
			return Promise.all(handlePromise(promiseList))
		}


		// tinymce.init({
		// 	selector: 'textarea',
		// 	height: 100,
		// 	resize: true,
		// 	fontsize_formats: "6pt 8pt 10pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 28pt 30pt 32pt 34pt 36pt 38pt 40pt",
		// 	plugins: 'autoresize autosave emoticons preview importcss searchreplace autolink autosave save ' +
		// 		'directionality  visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
		// 	toolbar: 'restoredraft preview searchreplace | undo redo | blocks ' +
		// 		'fontsizeselect bold italic backcolor forecolor | alignleft aligncenter ' +
		// 		'alignright alignjustify | template codesample bullist numlist outdent indent | link image ' +
		// 		'table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol' +
		// 		'removeformat emoticons | help',
		// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
		// 	autoresize_bottom_margin: 50,
		// 	link_default_protocol: 'https'
		// 	// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
		// });

		$(document).on('click', '#add_flow', function() {
			const proc_id = $(this).data('id')
			const url = siteurl + active_controller + 'add_flow/' + proc_id
			$('#content_modal').load(url)
			$('#modelId').modal('show')
		})

		$(document).on('click', '.edit_flow', function() {
			let id = $(this).data('id')
			const proc_id = $(this).data('proc_id')
			const url = siteurl + active_controller + 'edit_flow/' + proc_id + "/" + id
			$('#content_modal').load(url)
			$('#modelId').modal('show')
		})

		$(document).on('click', '.delete_flow', function() {
			let id = $(this).data('id')
			const btn = $(this)
			Swal.fire({
				title: 'Are you sure to delete this data?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#ee2d41',
				confirmButtonText: 'Yes, Delete <i class="fa fa-trash text-white"></i>',
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_flow/' + id,
						type: 'GET',
						dataType: 'JSON',
						success: function(result) {
							if (result.status == '1') {
								Swal.fire({
									title: 'Success!!',
									text: result.msg,
									icon: 'success',
									timer: 1500
								}).then(() => {
									btn.parents('tr').fadeOut('1500')
								})

							} else {
								Swal.fire('Warning', "Can't delete data. Please try again!", 'warning', 2000)
							}
						},
						error: function() {
							Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
						}
					})
				}
			})
		})


		/* SAVE */
		$(document).on('submit', '#form-procedure', function(e) {
			e.preventDefault();
			let formdata = new FormData($(this)[0])
			let btn = $('.save')

			$('#description').removeClass('is-invalid')
			$('#prepared_by').removeClass('is-invalid')
			$('#approval_id').removeClass('is-invalid')
			$('#reviewer_id').removeClass('is-invalid')
			$('#distribute_id').removeClass('is-invalid')
			$('#image').removeClass('is-invalid')

			const description = $('#description').val();
			const prepared_by = $('#prepared_by').val();
			const reviewer_id = $('#reviewer_id').val();
			const approval_id = $('#approval_id').val();
			const distribute_id = $('#distribute_id').val();
			const id_master = $('#id_master').val();
			const image = $('#image').val();
			const parent_id = $('#parent_id').val();

			// console.log(description);
			if (prepared_by !== undefined && (prepared_by == '' || prepared_by == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty User Prepared, please input User Prepared  first.....',
					icon: "warning"
				});
				$('#prepared_by').addClass('is-invalid')
				$('#approvalDocs').addClass('show');
				return false;
			}

			// if ((reviewer_id == '' && reviewer_id != undefined) || (reviewer_id == null && reviewer_id != undefined)) {
			// 	Swal.fire({
			// 		title: "Error Message!",
			// 		text: 'Empty reviewer, please input reviewer first.....',
			// 		icon: "warning"
			// 	});
			// 	$('#reviewer_id').addClass('is-invalid')
			// 	$('#approvalDocs').addClass('show');
			// 	return false;
			// }

			// if ((approval_id == '' && approval_id != undefined) || (approval_id == null && approval_id != undefined)) {
			// 	Swal.fire({
			// 		title: "Error Message!",
			// 		text: 'Empty approval, please input approval first!',
			// 		icon: "warning"
			// 	});
			// 	$('#approval_id').addClass('is-invalid')
			// 	$('#approvalDocs').addClass('show');
			// 	return false;
			// }


			var validate = true
			$('input[name="img_flow[]"]').each(function() {
				console.log(this.files[0]);
				if (this.files[0]) {
					size = this.files[0].size
					if (size > 5 * 1024 * 1024) {
						Swal.fire('Warning', 'ukuran file lebih dari 5MB', 'warning', 3000)
						validate = false
					}
				}
			});

			if (!validate) {
				return false
			}
			// console.log(img);
			$.ajax({
				url: siteurl + active_controller + 'save',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...')
				},
				complete: function() {
					btn.attr('disabled', false)
					btn.html('<i class="fa fa-save"></i>Save')
				},

				success: function(result) {
					if (result.status == 1) {
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 2000
						}).then(() => {
							location.reload()
							$('#modelId').modal('hide')
							$('#flowDetail table tbody').load(siteurl + active_controller + 'loadFlow/' + result.id)
						})

					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						})
					}
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout, becuase error!',
						timer: 4000
					})
				}
			})
		})

		/* FLOW DETAIL */
		$(document).on('submit', '#form-flow-detail', function(e) {
			e.preventDefault();
			let formdata = new FormData($(this)[0])
			let btn = $('.save')

			$.ajax({
				url: siteurl + active_controller + 'saveFlowDetail',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...')
				},
				complete: function() {
					btn.attr('disabled', false)
					btn.html('<i class="fa fa-save"></i>Save')
				},

				success: function(result) {
					if (result.status == 1) {
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 2000
						}).then(() => {
							// location.reload()
							$('#modelId').modal('hide')
							$('#flowDetail table tbody').load(siteurl + active_controller + 'loadFlow/' + result.id)
						})

					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						})
					}
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout, becuase error!',
						timer: 4000
					})
				}
			})
		})

		$(document).on('change', ".dropzone", function() {
			readFile(this);
		});

		$('.dropzone-wrapper').on('dragover', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).addClass('dragover');
		});

		$('.dropzone-wrapper').on('dragleave', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$(this).removeClass('dragover');
		});

		$('.remove-preview').on('click', function() {
			var boxZone = $(this).parents('.preview-zone').find('.box-body');
			var previewZone = $(this).parents('.preview-zone');
			var dropzone = $(this).parents('.form-group').find('.dropzone');
			boxZone.empty();
			previewZone.addClass('hidden');
			reset(dropzone);
		});


		/*    FORMS    */
		/* =========== */

		$(document).on('click', '.view-form', function() {
			const id = $(this).data('id')
			if (id) {
				$('.modal-title').html('View Form')
				$('#modelId .modal-dialog').removeClass('modal-md modal-lg').addClass('modal-xl');
				$('#content_modal').load(siteurl + active_controller + 'view_form/' + id)
				$('#modelId').modal('show')
			} else {
				Swal.fire('Warning!!', 'Not available data to process', 'waring', 2000);
			}
		})

		$(document).on('click', '#add_form', function() {
			const id = $('#procedure_id').val() || null;
			$('#modalRecord').modal('show')
			// $('.modal-dialog').css('max-width', '70%')
			$('.modal-title').text('Add Form')
			$('#record-content').load(siteurl + active_controller + 'upload_form/' + id)

		})

		$(document).on('click', '.edit-form', function() {
			const id = $(this).data('id') || null;
			const procedure_id = $('#procedure_id').val() || null;
			$('#modalRecord').modal('show')

			$('.modal-title').text('Edit Form')
			$('#record-content').load(siteurl + active_controller + 'edit_form/' + id)

		})


		/* change form type */
		$(document).on('change', 'input[name="form_type"]:checked', function() {
			const form_type = $(this).val()

			if (form_type == 'upload_file') {
				html = `
					<div class="form-group row mb-0">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Upload Document :</label>
						<div class="col-12">
							<input type="file" name="forms_image" id="image" class="form-control" placeholder="Upload File">
							<span class="form-text text-muted">File type : PDF</span>
							<span class="form-text text-danger invalid-feedback">Upload Document By harus di isi</span>
						</div>
					</div>`
			} else if (form_type == 'online_form') {
				html = `
					<div class="form-group row">
						<label class="col-12 col-form-label"><span class="text-danger">*</span> Link Google Form</label>
						<div class="col-12">
							<div class="input-group mb-3">
								<span class="input-group-text rounded-right-0"><i class="fa fa-link"></i></span>
								<input type="text" class="form-control" id="link-form" placeholder="Link Form" name="forms[link_form]" value="" autocomplete="off" />
							</div>
							<span class="form-text text-danger invalid-feedback">Link Form harus di isi</span>
						</div>
					</div>`
			}
			$('#type-form').html(html)
		})

		$(document).on('click', '.delete-form', function() {
			const id = $(this).data('id') || null;
			const btn = $(this)
			Swal.fire({
				title: 'Are you sure to delete this data?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#ee2d41',
				confirmButtonText: 'Yes, Delete <i class="fa fa-trash text-white"></i>',
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_form/' + id,
						type: 'GET',
						dataType: 'JSON',
						success: function(result) {
							if (result.status == '1') {
								Swal.fire({
									title: 'Success!!',
									text: result.msg,
									icon: 'success',
									timer: 1500
								});


								$(btn).parents('tr').remove();
								// $(btn).parents('tr').css('display', 'none').slideUp('slow');
								// setTimeout(function() {
								// }, 800)

							} else {
								Swal.fire('Warning', "Can't delete data. Please try again!", 'warning', 2000)
							}
						},
						error: function() {
							Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
						}
					})
				}
			})

		})

		/* SAVE */
		$(document).on('click', '#save-form', function() {
			const pro_id = $('#procedure_id').val() || '';
			let formdata = new FormData($('#form-records')[0])
			let btn = $('.save')

			$('#description').removeClass('is-invalid')
			$('#prepared_by').removeClass('is-invalid')
			$('#approval_id').removeClass('is-invalid')
			$('#reviewer_id').removeClass('is-invalid')
			$('#distribute_id').removeClass('is-invalid')
			$('#image').removeClass('is-invalid')
			$('#group_procedure').removeClass('is-invalid')
			$('#status').removeClass('is-invalid')
			$('#name').removeClass('is-invalid')
			$('#scope').removeClass('is-invalid')
			$('#object').removeClass('is-invalid')
			$('#performance').removeClass('is-invalid')
			$('#link-form').removeClass('is-invalid')



			const group_procedure = $('#group_procedure').val();
			const description = $('#description').val();
			const prepared_by = $('#prepared_by').val();
			const reviewer_id = $('#reviewer_id').val();
			const approval_id = $('#approval_id').val();
			const distribute_id = $('#distribute_id').val();
			const id_master = $('#id_master').val();
			const image = $('#image').val();
			const parent_id = $('#parent_id').val();
			const status = $('#status').val();
			const name = $('#name').val()
			const scope = $('#scope').val()
			const object = $('#object').val()
			const performance = $('#performance').val()
			const link_form = $('#link-form').val()

			if (group_procedure !== undefined && (group_procedure == '' || group_procedure == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Group Procedure, please input Group Procedure  first.....',
					icon: "warning"
				});
				$('#group_procedure').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}
			if (status !== undefined && (status == '' || status == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Status, please input Status first.....',
					icon: "warning"
				});
				$('#status').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}
			if (name !== undefined && (name == '' || name == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Name Procedure, please input Name Procedure first.....',
					icon: "warning"
				});
				$('#name').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}
			if (scope !== undefined && (scope == '' || scope == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Ruang Lingkup, please input Ruang Lingkup first.....',
					icon: "warning"
				});
				$('#scope').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}
			if (object !== undefined && (object == '' || object == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Objective Process, please input Objective Process first.....',
					icon: "warning"
				});
				$('#object').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}
			if (performance !== undefined && (performance == '' || performance == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Performa Indikator, please input Performa Indikator first.....',
					icon: "warning"
				});
				$('#performance').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}
			if (description !== undefined && (description == '' || description == null)) {
				$('#description').addClass('is-invalid')
				return false;
			}
			if (link_form !== undefined && (link_form == '' || name == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Link form, please input link form first.....',
					icon: "warning"
				});
				$('#link-form').addClass('is-invalid')
				// $('#approvalDocs').addClass('show');
				return false;
			}

			if (prepared_by !== undefined && (prepared_by == '' || prepared_by == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty User Prepared, please input User Prepared  first.....',
					icon: "warning"
				});
				$('#prepared_by').addClass('is-invalid')

				return false;
			}
			if ((reviewer_id == '' && reviewer_id != undefined) || (reviewer_id == null && reviewer_id != undefined)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty reviewer, please input reviewer first.....',
					icon: "warning"
				});
				$('#reviewer_id').addClass('is-invalid')

				return false;
			}
			if ((approval_id == '' && approval_id != undefined) || (approval_id == null && approval_id != undefined)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty approval, please input approval first!',
					icon: "warning"
				});
				$('#approval_id').addClass('is-invalid')

				return false;
			}

			// if ((distribute_id == '' && distribute_id != undefined) || (distribute_id == null && distribute_id != undefined)) {
			// 	$('#distribute_id').addClass('is-invalid')
			// 	Swal.fire({
			// 		title: "Error Message!",
			// 		text: 'Empty distribusi, please input distribusi first.....',
			// 		icon: "warning"
			// 	});

			// 	return false;
			// }

			if (image !== undefined && (image == '' || image == null)) {
				$('#image').addClass('is-invalid')
				Swal.fire({
					title: "Error Message!",
					text: 'Empty file, please input file first.....',
					icon: "warning"
				});

				return false;
			}

			$.ajax({
				url: siteurl + active_controller + 'saveForm',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...')
				},
				complete: function() {
					btn.attr('disabled', false)
					btn.html('<i class="fa fa-save"></i>Save')
				},
				success: function(result) {
					console.log(result);
					if (result.status == 1) {
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 2000
						})
						$('.close-btn').click();
						reload_form(pro_id)
					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						})
					}
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout, becuase error!',
						timer: 4000
					})
				}
			})
		})


		/* UPLOAD IK */

		$(document).on('click', '.view-guide', function() {
			const id = $(this).data('id')
			if (id) {
				$('.modal-title').html('View IK')
				$('#modelId .modal-dialog').removeClass('modal-md modal-lg').addClass('modal-xl');
				$('#content_modal').load(siteurl + active_controller + 'view_guide/' + id)
				$('#modelId').modal('show')
			} else {
				Swal.fire('Warning!!', 'Not available data to process', 'waring', 2000);
			}
		})

		$(document).on('click', '#add_guide', function() {
			const id = $('#procedure_id').val() || null;
			$('#modalRecord').modal('show')
			// $('.modal-dialog').css('max-width', '70%')
			$('.modal-title').text('Add IK')
			$('#record-content').load(siteurl + active_controller + 'upload_guide/' + id)

		})

		$(document).on('click', '.edit-guide', function() {
			const id = $(this).data('id') || null;
			const procedure_id = $('#procedure_id').val() || null;
			$('#modalRecord').modal('show')
			$('.modal-title').text('Edit IK')
			$('#record-content').load(siteurl + active_controller + 'edit_guide/' + id)

		})

		$(document).on('click', '.delete-guide', function() {
			const id = $(this).data('id') || null;
			const btn = $(this)
			Swal.fire({
				title: 'Are you sure to delete this data?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#ee2d41',
				confirmButtonText: 'Yes, Delete <i class="fa fa-trash text-white"></i>',
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_guide/' + id,
						type: 'GET',
						dataType: 'JSON',
						success: function(result) {
							if (result.status == '1') {
								Swal.fire({
									title: 'Success!!',
									text: result.msg,
									icon: 'success',
									timer: 1500
								});
								$(btn).parents('tr').remove();

							} else {
								Swal.fire('Warning', "Can't delete data. Please try again!", 'warning', 2000)
							}
						},
						error: function() {
							Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
						}
					})
				}
			})

		})

		$(document).on('change', 'input[name="forms[flag_record]"]:checked', function() {
			const mode = $(this).val()

			if (mode == 'Y') {
				$('#file-type').html('')
			} else {
				const html = `
			<div class="form-group row">
				<label class="col-lg-3 col-form-label text-lg-right">Review By :</label>
				<div class="col-lg-9">
					<select name="reviewer_id" id="reviewer_id" class="form-control select2">;
						<option value=""></option>
						<?php foreach ($jabatan as $jbt) : ?>
							<option value="<?= $jbt->id; ?>" <?= (isset($file) && $file->reviewer_id == $jbt->id) ? 'selected' : ''; ?>><?= $jbt->name; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="form-text text-danger invalid-feedback">Review By harus di isi</span>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-3 col-form-label text-lg-right">Approval By :</label>
				<div class="col-lg-9">
					<select name="approval_id" id="approval_id" class="form-control select2">;
						<option value=""></option>
						<?php foreach ($jabatan as $jbt) : ?>
							<option value="<?= $jbt->id; ?>" <?= (isset($file) && $file->approval_id == $jbt->id) ? 'selected' : ''; ?>><?= $jbt->name; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="form-text text-danger invalid-feedback">Approval By harus di isi</span>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-3 col-form-label text-lg-right">Distribusi :</label>
				<div class="col-lg-9">
					<select name="distribute_id[]" multiple id="distribute_id" data-placeholder="Choose an options" class="form-control select2">;
						<option value=""></option>
						<?php foreach ($jabatan as $jbt) : ?>
							<option value="<?= $jbt->id; ?>" <?= isset($file) ? ((in_array($jbt->id, explode(',', $file->distribute_id))) ? 'selected' : '') : ''; ?>><?= $jbt->name; ?></option>
						<?php endforeach; ?>
					</select>
					<span class="form-text text-danger invalid-feedback">Distribusi By harus di isi</span>
				</div>
			</div>`
				$('#file-type').html(html)
				$('.select2').select2({
					placeholder: 'Choose an options',
					width: '100%',
					allowClear: true
				})

			}

		})

		/* SAVE */
		$(document).on('click', '#save-guide', function() {
			const pro_id = $('#procedure_id').val() || '';
			let formdata = new FormData($('#form-records')[0])
			let btn = $('.save')

			$('#description').removeClass('is-invalid')
			$('#prepared_by').removeClass('is-invalid')
			$('#approval_id').removeClass('is-invalid')
			$('#reviewer_id').removeClass('is-invalid')
			$('#distribute_id').removeClass('is-invalid')
			$('#image').removeClass('is-invalid')

			const description = $('#description').val();
			const prepared_by = $('#prepared_by').val();
			const reviewer_id = $('#reviewer_id').val();
			const approval_id = $('#approval_id').val();
			const distribute_id = $('#distribute_id').val();
			const id_master = $('#id_master').val();
			const image = $('#image').val();
			const parent_id = $('#parent_id').val();

			if (description !== undefined && (description == '' || description == null)) {
				$('#description').addClass('is-invalid')
				return false;
			}
			if (prepared_by !== undefined && (prepared_by == '' || prepared_by == null)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty User Prepared, please input User Prepared  first.....',
					icon: "warning"
				});
				$('#prepared_by').addClass('is-invalid')

				return false;
			}
			if ((reviewer_id == '' && reviewer_id != undefined) || (reviewer_id == null && reviewer_id != undefined)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty reviewer, please input reviewer first.....',
					icon: "warning"
				});
				$('#reviewer_id').addClass('is-invalid')

				return false;
			}
			if ((approval_id == '' && approval_id != undefined) || (approval_id == null && approval_id != undefined)) {
				Swal.fire({
					title: "Error Message!",
					text: 'Empty approval, please input approval first!',
					icon: "warning"
				});
				$('#approval_id').addClass('is-invalid')

				return false;
			}

			// if ((distribute_id == '' && distribute_id != undefined) || (distribute_id == null && distribute_id != undefined)) {
			// 	$('#distribute_id').addClass('is-invalid')
			// 	Swal.fire({
			// 		title: "Error Message!",
			// 		text: 'Empty distribusi, please input distribusi first.....',
			// 		icon: "warning"
			// 	});

			// 	return false;
			// }

			if (image !== undefined && (image == '' || image == null)) {
				$('#image').addClass('is-invalid')
				Swal.fire({
					title: "Error Message!",
					text: 'Empty file, please input file first.....',
					icon: "warning"
				});
				return false;
			}

			$.ajax({
				url: siteurl + active_controller + 'saveGuide',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...')
				},
				complete: function() {
					btn.attr('disabled', false)
					btn.html('<i class="fa fa-save"></i>Save')
				},
				success: function(result) {
					if (result.status == 1) {
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 2000
						})
						$('.close-btn').click();
						reload_guides(pro_id)
					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						})
					}
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout, becuase error!',
						timer: 4000
					})
				}
			})
		})


		/* UPLOAD RECORDS */

		$(document).on('click', '.view-record', function() {
			const id = $(this).data('id')
			if (id) {
				$('.modal-title').html('View Records')
				$('#modelId .modal-dialog').removeClass('modal-md modal-lg').addClass('modal-xl');
				$('#content_modal').load(siteurl + active_controller + 'view_record/' + id)
				$('#modelId').modal('show')
			} else {
				Swal.fire('Warning!!', 'Not available data to process', 'waring', 2000);
			}
		})

		$(document).on('click', '#add_record', function() {
			const id = $('#procedure_id').val() || null;
			const parent_id = $('#refresh_id').val() || null
			$('#modalRecord').modal('show')
			$('.modal-title').text('Add Records')
			$('#record-content').load(siteurl + active_controller + 'upload_record/' + id + "/" + parent_id)
		})

		$(document).on('click', '.edit-record', function() {
			const id = $(this).data('id') || null;
			const procedure_id = $('#procedure_id').val() || null;
			$('#modalRecord').modal('show')
			$('.modal-title').text('Edit Records')
			$('#record-content').load(siteurl + active_controller + 'edit_record/' + id)
		})

		$(document).on('click', '#save-record', function() {
			let formdata = new FormData($('#form-records')[0])
			let btn = $('.save')
			$('#name').removeClass('is-invalid')
			$('#description').removeClass('is-invalid')
			$('#image').removeClass('is-invalid')
			$('#link-url').removeClass('is-invalid')
			const docName = $('#name').length ? $('#name').val() : $('#description').val();
			const record_type = $('input[name="record_type"]:checked').val();

			if (docName !== undefined && (docName == '' || docName == null)) {
				if ($('#name').length) $('#name').addClass('is-invalid');
				else $('#description').addClass('is-invalid');
				return false;
			}

			if (record_type === 'upload_file') {
				const image = $('#image').val();
				const oldFile = $('#old_file').val();
				if (!oldFile && (image == undefined || image == '' || image == null)) {
					$('#image').addClass('is-invalid')
					Swal.fire({
						title: "Error Message!",
						text: 'Empty file, please input file first.....',
						icon: "warning"
					});
					return false;
				}
			} else if (record_type === 'online_link') {
				const linkUrl = $('#link-url').val();
				if (linkUrl == undefined || linkUrl == '' || linkUrl == null) {
					$('#link-url').addClass('is-invalid')
					Swal.fire({
						title: "Error Message!",
						text: 'Link harus di isi.',
						icon: "warning"
					});
					return false;
				}
			}

			const recordId = $('#form-records input[name="forms[id]"]').val() || '';
			const isEdit = (recordId !== '');
			const recordName = $('#name').val() || '';
			const parentId = $('#form-records input[name="forms[parent_id]"]').val() || '';
			const desc = $('#description').val();

			$.ajax({
				url: siteurl + active_controller + 'saveFileRecord',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...')
				},
				complete: function() {
					btn.attr('disabled', false)
					btn.html('<i class="fa fa-save"></i>Save')
				},
				success: function(result) {
					if (result.status == 1) {
						$('#modalRecord').modal('hide');
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 1500,
							showConfirmButton: false
						});

						if (isEdit) {
							const row = $(`#records-tree-table tr.tree-row[data-id="${recordId}"]`);
							if (row.length > 0) {
								row.find('.text-name').first().text(recordName);
								row.find('.edit-record').data('name', recordName).attr('data-name', recordName);
								row.find('.record-checkbox').data('name', recordName).attr('data-name', recordName);
								row.find('.copy-single-record').data('name', recordName).attr('data-name', recordName);
								if (desc !== undefined) {
									row.find('td:nth-child(3) span').text(desc || '-');
								}
								row.addClass('row-updated');
								setTimeout(() => row.removeClass('row-updated'), 1500);
							}
						} else {
							if (parentId) {
								reloadFolder(parentId);
							} else {
								refreshRootTree();
							}
						}
					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						})
					}
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout, becuase error!',
						timer: 4000
					})
				}
			})
		})

		$(document).on('click', '.delete-record', function() {
			const id = $(this).data('id') || null;
			const btn = $(this)
			Swal.fire({
				title: 'Are you sure to delete this data?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#ee2d41',
				confirmButtonText: 'Yes, Delete <i class="fa fa-trash text-white"></i>',
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_record/' + id,
						type: 'GET',
						dataType: 'JSON',
						success: function(result) {
							if (result.status == '1') {
								Swal.fire({
									title: 'Success!!',
									text: result.msg,
									icon: 'success',
									timer: 1500,
									showConfirmButton: false
								});
								const row = $(btn).closest('tr.tree-row');
								const recordId = id || row.data('id');
								removeDescendants(recordId);
								row.fadeOut(350, function() {
									$(this).remove();
									saveExpandedState();
								});

							} else {
								Swal.fire('Warning', "Can't delete data. Please try again!", 'warning', 2000)
							}
						},
						error: function() {
							Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
						}
					})
				}
			})

		})

		/* TOGGLE CONFIDENTIAL */
		$(document).on('click', '.toggle-confidential', function() {
			const id = $(this).data('id');
			$('#modelId .modal-dialog').removeClass('modal-xl modal-lg').addClass('modal-md');
			$('#modelId').modal('show');
			$('.modal-title').text('Set Confidential Access');
			$('#content_modal').html(`
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bold">Confidential</label>
						<div class="checkbox-inline">
							<label class="checkbox">
								<input type="checkbox" id="conf_flag" value="1" checked>
								<span></span> Enable Confidential
							</label>
						</div>
					</div>
					<div class="form-group" id="conf_levels_group">
						<label class="font-weight-bold">Restricted to Level</label>
						<select id="conf_group_ids" class="form-control" multiple="multiple" style="width:100%;">
						</select>
						<div class="form-text text-muted">Only users with these levels can access this document (leave empty for all confidential users)</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" id="save_confidential" data-id="${id}"><i class="fa fa-save mr-1"></i> Save</button>
				</div>
			`);

			// Load levels first, then load current data
			$.getJSON(siteurl + active_controller + 'get_levels', function(levels) {
				let options = '';
				levels.forEach(function(lvl) {
					options += `<option value="${lvl.id_group}">${lvl.nm_group}</option>`;
				});
				$('#conf_group_ids').html(options);
				$('#conf_group_ids').select2({ placeholder: 'Select levels...', width: '100%', allowClear: true });

				// After levels loaded, load current data
				$.getJSON(siteurl + active_controller + 'get_confidential_data/' + id, function(data) {
					// Only uncheck if explicitly already set to OFF (user wants to turn off)
					if (data.flag_confidential == '0') {
						// Keep checked as default (user is opening modal to enable it)
						$('#conf_flag').prop('checked', true);
						$('#conf_levels_group').show();
					} else {
						$('#conf_flag').prop('checked', true);
						$('#conf_levels_group').show();
					}
					if (data.confidential_group_ids) {
						let ids = data.confidential_group_ids.split(',');
						$('#conf_group_ids').val(ids).trigger('change');
					}
				});
			});

			// Toggle visibility
			$(document).off('change', '#conf_flag').on('change', '#conf_flag', function() {
				if ($(this).is(':checked')) {
					$('#conf_levels_group').show();
				} else {
					$('#conf_levels_group').hide();
				}
			});
		});

		$(document).on('click', '#save_confidential', function() {
			const id = $(this).data('id');
			const flag = $('#conf_flag').is(':checked') ? '1' : '0';
			const group_ids = $('#conf_group_ids').val() || [];

			$.ajax({
				url: siteurl + active_controller + 'toggle_confidential',
				type: 'POST',
				dataType: 'JSON',
				data: { id: id, flag_confidential: flag, group_ids: group_ids },
				success: function(res) {
					if (res.status == '1') {
						$('#modelId').modal('hide');
						Swal.fire({ title: 'Success!', text: res.msg, icon: 'success', timer: 1500, showConfirmButton: false });
						const row = $(`#records-tree-table tr.tree-row[data-id="${id}"]`);
						if (row.length > 0) {
							const confLink = row.find('.toggle-confidential');
							confLink.data('value', flag).attr('data-value', flag);
							if (flag === '1') {
								confLink.html('<i class="fa fa-lock text-dark mr-2"></i> Confidential <span class="label label-inline label-danger ml-2">ON</span>');
							} else {
								confLink.html('<i class="fa fa-lock text-dark mr-2"></i> Confidential <span class="label label-inline label-secondary ml-2">OFF</span>');
							}
							row.addClass('row-updated');
							setTimeout(() => row.removeClass('row-updated'), 1500);
						}
					} else {
						Swal.fire('Warning', res.msg, 'warning');
					}
				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error');
				}
			});
		});

		/* FOLDER RECORDS */

		$(document).on('click', '.add_folder', function() {
			$('#refresh_id').val('');
			$('#modelId .modal-dialog').removeClass('modal-xl modal-lg').addClass('modal-md');
			$('#modelId').modal('show');
			$('.modal-title').text('Add Folder');
			$('#content_modal').html(`
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group mb-0">
							<label for="folder_name" class="font-weight-bold">Folder Name <span class="text-danger">*</span></label>
							<input type="hidden" id="folder_parent_id" value="">
							<input type="text" name="folder_name" id="folder_name" class="form-control" placeholder="Folder Name">
							<span class="invalid-feedback">Folder Name is required</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-end">
				<button type="button" class="btn btn-success save-folder"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			</div>`);
		});

		$(document).on('click', '.edit-folder', function() {
			const id = $(this).data('id')
			const name = $(this).data('name')
			$('#modelId .modal-dialog').removeClass('modal-xl modal-lg').addClass('modal-md');
			$('#modelId').modal('show')
			$('.modal-title').text('Edit Folder')
			$('#content_modal').html(`
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group mb-0">
							<label for="folder_name" class="font-weight-bold">Folder Name <span class="text-danger">*</span></label>
							<input type="hidden" class="form-control" placeholder="Folder Name" id="folder_id" name="folder_id" value="` + id + `">
							<input type="text" class="form-control" placeholder="Folder Name" id="folder_name" name="folder_name" value="` + name + `">
							<span class="invalid-feedback">Folder Name is required</span>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-end">
			<button type="button" class="btn btn-success save-folder"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
			<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
			</div>`);
		})

		/* ==========================================================
		   TREE STATE PRESERVATION & DYNAMIC RELOADING HELPERS
		   ========================================================== */

		function getExpandedFolderIds() {
			const list = [];
			$('#records-tree-table tr.tree-row.expanded').each(function() {
				const id = $(this).data('id');
				if (id && !list.includes(id)) {
					list.push(id);
				}
			});
			return list;
		}

		function saveExpandedState() {
			const expanded = getExpandedFolderIds();
			sessionStorage.setItem('records_expanded_' + $('#procedure_id').val(), JSON.stringify(expanded));
		}

		function removeDescendants(parentId) {
			$('#records-tree-table tr.tree-row[data-parent-id="' + parentId + '"]').each(function() {
				const id = $(this).data('id');
				if (id) {
					removeDescendants(id);
				}
				$(this).remove();
			});
		}

		function expandFolderDirectly(folderId, callback) {
			const row = $(`#records-tree-table tr.tree-row[data-id="${folderId}"]`);
			if (row.length === 0) {
				if (callback) callback();
				return;
			}

			if (row.hasClass('expanded')) {
				showChildren(folderId);
				if (callback) callback();
				return;
			}

			const procedure_id = $('#procedure_id').val();
			const depth = parseInt(row.data('depth')) || 0;
			const isLoaded = row.data('loaded');
			const icon = row.find('.toggle-icon');

			row.addClass('expanded');
			icon.css('transform', 'rotate(90deg)');

			if (!isLoaded || isLoaded === "false" || isLoaded === false) {
				$.get(siteurl + 'records/child_rows/' + folderId + "/" + procedure_id + "/" + (depth + 1), function(html) {
					row.after(html);
					row.data('loaded', 'true');
					row.attr('data-loaded', 'true');
					renderClipboardUI();
					if (callback) callback();
				}).fail(function() {
					if (callback) callback();
				});
			} else {
				showChildren(folderId);
				if (callback) callback();
			}
		}

		function restoreExpandedFolders(folderIds, onDone) {
			if (!folderIds || folderIds.length === 0) {
				if (onDone) onDone();
				return;
			}

			const queue = [...new Set(folderIds)];

			function processQueue() {
				if (queue.length === 0) {
					saveExpandedState();
					if (onDone) onDone();
					return;
				}

				let foundIndex = -1;
				for (let i = 0; i < queue.length; i++) {
					const id = queue[i];
					const row = $(`#records-tree-table tr.tree-row[data-id="${id}"]`);
					if (row.length > 0 && !row.hasClass('expanded')) {
						foundIndex = i;
						break;
					}
				}

				if (foundIndex === -1) {
					saveExpandedState();
					if (onDone) onDone();
					return;
				}

				const folderId = queue.splice(foundIndex, 1)[0];
				expandFolderDirectly(folderId, function() {
					processQueue();
				});
			}

			processQueue();
		}

		function reloadFolder(folderId, callback) {
			if (!folderId || folderId === 'root' || folderId === 'null') {
				refreshRootTree(callback);
				return;
			}

			const row = $(`#records-tree-table tr.tree-row[data-id="${folderId}"]`);
			if (row.length === 0) {
				refreshRootTree(callback);
				return;
			}

			const openDescendants = [];
			$('#records-tree-table tr.tree-row.expanded').each(function() {
				const id = $(this).data('id');
				if (id && id !== folderId) {
					openDescendants.push(id);
				}
			});

			const procedure_id = $('#procedure_id').val();
			const depth = parseInt(row.data('depth')) || 0;
			const icon = row.find('.toggle-icon');

			removeDescendants(folderId);

			$.get(siteurl + 'records/child_rows/' + folderId + "/" + procedure_id + "/" + (depth + 1), function(html) {
				row.after(html);
				row.addClass('expanded');
				row.data('loaded', 'true');
				row.attr('data-loaded', 'true');
				icon.css('transform', 'rotate(90deg)');
				renderClipboardUI();

				restoreExpandedFolders(openDescendants, function() {
					saveExpandedState();
					if (callback) callback();
				});
			}).fail(function() {
				if (callback) callback();
			});
		}

		function refreshRootTree(callback) {
			const procedure_id = $('#procedure_id').val();
			const openFolders = getExpandedFolderIds();

			$.get(siteurl + 'records/refresh/null/' + procedure_id, function(html) {
				$('#data-records').html(html);
				renderClipboardUI();
				restoreExpandedFolders(openFolders, function() {
					saveExpandedState();
					if (callback) callback();
				});
			}).fail(function() {
				if (callback) callback();
			});
		}

		$(document).on('click', '.save-folder', function() {
			const folder_id = $('#folder_id').val() || '';
			const procedure_id = $('#procedure_id').val();
			const parent_id = $('#folder_parent_id').length ? $('#folder_parent_id').val() : ($('#refresh_id').val() || '');
			const btn = $(this);
			const folder_name = $('#folder_name').val();

			if (folder_name !== undefined && (folder_name == '' || folder_name == null)) {
				$('#folder_name').addClass('is-invalid');
				Swal.fire({
					title: "Error Message!",
					text: 'Empty Folder name, please input folder name first.....',
					icon: "warning"
				});
				return false;
			}

			$.ajax({
				url: siteurl + active_controller + 'saveFolder',
				data: {
					folder_id,
					procedure_id,
					parent_id,
					folder_name
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function() {
					btn.attr('disabled', true);
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...');
				},
				complete: function() {
					btn.attr('disabled', false);
					btn.html('<i class="fa fa-save"></i> Save');
				},
				success: function(result) {
					if (result.status == 1) {
						$('#modelId').modal('hide');
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 1500,
							showConfirmButton: false
						});

						if (folder_id) {
							// In-place edit
							const row = $(`#records-tree-table tr.tree-row[data-id="${folder_id}"]`);
							if (row.length > 0) {
								row.find('.text-name').first().text(folder_name);
								row.find('.edit-folder').data('name', folder_name).attr('data-name', folder_name);
								row.find('.record-checkbox').data('name', folder_name).attr('data-name', folder_name);
								row.find('.copy-single-record').data('name', folder_name).attr('data-name', folder_name);
								row.addClass('row-updated');
								setTimeout(() => row.removeClass('row-updated'), 1500);
							}
						} else {
							// New folder added
							if (parent_id) {
								reloadFolder(parent_id);
							} else {
								refreshRootTree();
							}
						}
					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						});
					}
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout, because error!',
						timer: 4000
					});
				}
			});
		});

		$(document).on('click', '.folder', function() {
			const procedure_id = $('#procedure_id').val();
			const folder_id = $(this).data('id');
			const row = $(this).closest('tr.tree-row');
			const depth = parseInt(row.data('depth')) || 0;
			const isLoaded = row.data('loaded');
			const icon = $(this).find('.toggle-icon');

			if (row.hasClass('expanded')) {
				row.removeClass('expanded');
				icon.css('transform', 'rotate(0deg)');
				hideDescendants(folder_id);
				saveExpandedState();
			} else {
				row.addClass('expanded');
				icon.css('transform', 'rotate(90deg)');
				
				if (!isLoaded || isLoaded === "false" || isLoaded === false) {
					$.get(siteurl + 'records/child_rows/' + folder_id + "/" + procedure_id + "/" + (depth + 1), function(html) {
						row.after(html);
						row.data('loaded', 'true');
						row.attr('data-loaded', 'true');
						renderClipboardUI();
						saveExpandedState();
					});
				} else {
					showChildren(folder_id);
					saveExpandedState();
				}
			}
		});

		function hideDescendants(parentId) {
			$('#records-tree-table tr[data-parent-id="' + parentId + '"]').each(function() {
				$(this).hide();
				if ($(this).hasClass('expanded')) {
					hideDescendants($(this).data('id'));
				}
			});
		}

		function showChildren(parentId) {
			$('#records-tree-table tr[data-parent-id="' + parentId + '"]').each(function() {
				$(this).show();
				if ($(this).hasClass('expanded')) {
					showChildren($(this).data('id'));
				}
			});
		}

		$(document).on('click', '.add-folder-inside', function() {
			const parent_id = $(this).data('id');
			const procedure_id = $('#procedure_id').val();
			if (parent_id) {
				$('#refresh_id').val(parent_id); // Required by .save-folder logic
				$('.modal-title').html('Add Folder');
				$('#modelId .modal-dialog').removeClass('modal-xl modal-lg').addClass('modal-lg');
				$('#content_modal').html(`
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group mb-0">
									<label for="folder_name" class="font-weight-bold">Folder Name <span class="text-danger">*</span></label>
									<input type="hidden" id="folder_parent_id" value="` + parent_id + `">
									<input type="text" name="folder_name" id="folder_name" class="form-control" placeholder="Folder Name">
									<span class="invalid-feedback">Folder Name is required</span>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-end">
					<button type="button" class="btn btn-success save-folder"><i class="fa fa-save" aria-hidden="true"></i> Save</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cancel</button>
					</div>
				`);
				$('#modelId').modal('show');
			}
		});

		$(document).on('click', '.add-record-inside', function() {
			const parent_id = $(this).data('id');
			const procedure_id = $('#procedure_id').val();
			if (parent_id) {
				$('.modal-title').html('Upload Record');
				$('#record-content').load(siteurl + 'records/upload_record/' + procedure_id + '/' + parent_id);
				$('#modalRecord').modal('show');
			}
		});

		$(document).on('click', '#refresh', function() {
			refreshRootTree();
		});
		$(document).on('click', '.move-record', function() {
			const id = $(this).data('id')
			if (id) {
				$('.modal-title').html('Move File/Folder')
				$('#modelId .modal-dialog').removeClass('modal-xl modal-lg').addClass('modal-lg');
				$('#content_modal').load(siteurl + active_controller + 'move_record/' + id)
				$('#modelId').modal('show')
			}
		})

		$(document).on('click', '#save-move', function() {
			const btn = $(this)
			const record_id = $('input[name="record_id"]').val()
			const target_folder_id = $('#target_folder_id').val()

			$.ajax({
				url: siteurl + active_controller + 'save_move',
				data: {
					record_id,
					target_folder_id
				},
				type: 'POST',
				dataType: 'JSON',
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm mr-3"></i> Loading...')
				},
				complete: function() {
					btn.attr('disabled', false)
					btn.html('<i class="fa fa-save"></i> Move')
				},
				success: function(result) {
					if (result.status == 1) {
						$('#modelId').modal('hide');
						Swal.fire({
							title: 'Success!',
							icon: 'success',
							text: result.msg,
							timer: 1500,
							showConfirmButton: false
						});

						const oldRow = $(`#records-tree-table tr.tree-row[data-id="${record_id}"]`);
						removeDescendants(record_id);
						oldRow.remove();

						if (target_folder_id && target_folder_id !== 'root' && target_folder_id !== 'null') {
							reloadFolder(target_folder_id);
						} else {
							refreshRootTree();
						}
					} else {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 2000
						})
					}
				},
				error: function() {
					Swal.fire({
						title: 'Error!',
						icon: 'error',
						text: 'Server timeout!',
						timer: 4000
					})
				}
			})
		})

		$(document).on('keydown', '#search-record', function(e) {
			if (e.keyCode === 13) {
				e.preventDefault();
				return false;
			}
		});

		$(document).on('keydown', '#search-record', function(e) {
			if (e.key === 'Enter' || e.keyCode === 13) {
				e.preventDefault();
			}
		});

		$(document).on('keyup', '#search-record', function() {
			const keyword = $(this).val();
			const procedure_id = $('#procedure_id').val();
			if (keyword.length > 2) {
				$.post(siteurl + 'records/search_records', { procedure_id: procedure_id, keyword: keyword }, function(html) {
					$('#data-records').html(html);
					renderClipboardUI();
				});
			} else if (keyword.length == 0) {
				refreshRootTree();
			}
		});

		/* ==========================================================
		   MULTI-SELECT, COPY, MOVE, AND BATCH PASTE FEATURE
		   ========================================================== */

		function getClipboard() {
			try {
				const data = sessionStorage.getItem('records_clipboard');
				return data ? JSON.parse(data) : null;
			} catch (e) {
				return null;
			}
		}

		function setClipboard(action, ids, names) {
			const procedure_id = $('#procedure_id').val();
			const payload = {
				action: action,
				ids: ids,
				names: names,
				procedure_id: procedure_id,
				time: new Date().getTime()
			};
			sessionStorage.setItem('records_clipboard', JSON.stringify(payload));
			renderClipboardUI();
		}

		function clearClipboard() {
			sessionStorage.removeItem('records_clipboard');
			renderClipboardUI();
		}

		function updateSelectionUI() {
			const checked = $('.record-checkbox:checked');
			const count = checked.length;
			$('.selected-count').text(count);

			if (count > 0) {
				$('#selection-actions').show();
			} else {
				$('#selection-actions').hide();
				$('#check-all-records').prop('checked', false);
			}

			// Highlight selected rows
			$('.tree-row').each(function() {
				const isChecked = $(this).find('.record-checkbox').is(':checked');
				if (isChecked) {
					$(this).addClass('table-active');
				} else {
					$(this).removeClass('table-active');
				}
			});
		}

		function renderClipboardUI() {
			const clip = getClipboard();
			const procedure_id = $('#procedure_id').val();

			// Remove all cut highlights first
			$('.tree-row').removeClass('cut-item');

			if (clip && clip.ids && clip.ids.length > 0 && clip.procedure_id == procedure_id) {
				const count = clip.ids.length;
				const actionText = (clip.action === 'copy') ? 'Copy' : 'Move';
				const badgeClass = (clip.action === 'copy') ? 'alert-light-primary border-primary' : 'alert-light-warning border-warning';

				$('#clipboard-bar').removeClass('alert-light-primary alert-light-warning border-primary border-warning')
					.addClass(badgeClass).show();

				$('#clipboard-title').html(`<strong>${count} item(s) siap di-paste</strong> (${actionText})`);
				$('#clipboard-desc').text(`Mode: ${actionText}. Klik "Paste Here" di bawah, buka/klik opsi folder target, atau klik "Choose Destination".`);
				$('#btn-paste-toolbar').show().find('#clipboard-count').text(count);
				
				// Show paste action in folder dropdowns
				$('.paste-into-folder').show();

				// If action is move, mark cut rows visually
				if (clip.action === 'move') {
					clip.ids.forEach(function(id) {
						$(`tr.tree-row[data-id="${id}"]`).addClass('cut-item');
					});
				}
			} else {
				$('#clipboard-bar').hide();
				$('#btn-paste-toolbar').hide();
				$('.paste-into-folder').hide();
			}
		}

		// Initial render of clipboard UI on load
		renderClipboardUI();

		// Checkbox toggle
		$(document).on('change', '.record-checkbox', function(e) {
			e.stopPropagation();
			updateSelectionUI();
		});

		// Check all toggle
		$(document).on('change', '#check-all-records', function() {
			const isChecked = $(this).is(':checked');
			$('.record-checkbox:visible').prop('checked', isChecked);
			updateSelectionUI();
		});

		// Clear selection
		$(document).on('click', '#btn-clear-selection', function() {
			$('.record-checkbox').prop('checked', false);
			$('#check-all-records').prop('checked', false);
			updateSelectionUI();
		});

		// Copy Selected button
		$(document).on('click', '#btn-copy-selected', function() {
			const ids = [];
			const names = [];
			$('.record-checkbox:checked').each(function() {
				ids.push($(this).val());
				names.push($(this).data('name'));
			});

			if (ids.length === 0) {
				Swal.fire('Warning', 'Pilih minimal 1 item untuk disalin!', 'warning');
				return;
			}

			setClipboard('copy', ids, names);
			$('.record-checkbox').prop('checked', false);
			$('#check-all-records').prop('checked', false);
			updateSelectionUI();

			Swal.fire({
				toast: true,
				position: 'top-end',
				icon: 'info',
				title: `${ids.length} item disalin ke clipboard! Pilih folder tujuan lalu klik Paste.`,
				showConfirmButton: false,
				timer: 3000
			});
		});

		// Move Selected button
		$(document).on('click', '#btn-move-selected', function() {
			const ids = [];
			const names = [];
			$('.record-checkbox:checked').each(function() {
				ids.push($(this).val());
				names.push($(this).data('name'));
			});

			if (ids.length === 0) {
				Swal.fire('Warning', 'Pilih minimal 1 item untuk dipindahkan!', 'warning');
				return;
			}

			setClipboard('move', ids, names);
			$('.record-checkbox').prop('checked', false);
			$('#check-all-records').prop('checked', false);
			updateSelectionUI();

			Swal.fire({
				toast: true,
				position: 'top-end',
				icon: 'warning',
				title: `${ids.length} item siap dipindahkan! Pilih folder tujuan lalu klik Paste.`,
				showConfirmButton: false,
				timer: 3000
			});
		});

		// Single item Copy from dropdown
		$(document).on('click', '.copy-single-record', function() {
			const id = $(this).data('id');
			const name = $(this).data('name');
			if (id) {
				setClipboard('copy', [id], [name]);
				Swal.fire({
					toast: true,
					position: 'top-end',
					icon: 'info',
					title: `"${name}" disalin ke clipboard! Pilih folder tujuan lalu klik Paste.`,
					showConfirmButton: false,
					timer: 3000
				});
			}
		});

		// Cancel clipboard
		$(document).on('click', '#btn-cancel-clipboard', function() {
			clearClipboard();
			Swal.fire({
				toast: true,
				position: 'top-end',
				icon: 'secondary',
				title: 'Clipboard dibatalkan.',
				showConfirmButton: false,
				timer: 2000
			});
		});

		// Choose destination modal (from clipboard banner or selection toolbar)
		$(document).on('click', '#btn-choose-dest, #btn-choose-dest-selection', function() {
			let action = 'copy';
			let ids = [];

			if ($(this).attr('id') === 'btn-choose-dest-selection') {
				$('.record-checkbox:checked').each(function() {
					ids.push($(this).val());
				});
				if (ids.length === 0) {
					Swal.fire('Warning', 'Pilih minimal 1 item terlebih dahulu!', 'warning');
					return;
				}
				action = 'move';
			} else {
				const clip = getClipboard();
				if (!clip || !clip.ids || clip.ids.length === 0) {
					Swal.fire('Warning', 'Clipboard kosong!', 'warning');
					return;
				}
				action = clip.action;
				ids = clip.ids;
			}

			const procedure_id = $('#procedure_id').val();
			$('.modal-title').html((action === 'copy' ? 'Copy' : 'Move') + ' ' + ids.length + ' Item(s)');
			$('#modelId .modal-dialog').removeClass('modal-xl modal-lg').addClass('modal-lg');
			$('#content_modal').load(siteurl + active_controller + 'destination_modal?action=' + action + '&ids=' + ids.join(',') + '&procedure_id=' + procedure_id);
			$('#modelId').modal('show');
		});

		// Submit from destination modal
		$(document).on('click', '#btn-submit-destination-modal', function() {
			const action = $('#modal_action').val();
			const ids_str = $('#modal_record_ids').val();
			const target_folder_id = $('#modal_target_folder_id').val();
			const ids = ids_str.split(',');

			if (!target_folder_id) {
				Swal.fire('Warning', 'Pilih folder tujuan!', 'warning');
				return;
			}

			$('#modelId').modal('hide');
			executePaste(action, target_folder_id, ids);
		});

		// Paste into folder from folder row dropdown
		$(document).on('click', '.paste-into-folder', function() {
			const clip = getClipboard();
			if (!clip || !clip.ids || clip.ids.length === 0) {
				Swal.fire('Warning', 'Clipboard kosong!', 'warning');
				return;
			}
			const targetFolderId = $(this).data('id');
			const targetFolderName = $(this).data('name') || 'Folder ini';
			executePaste(clip.action, targetFolderId, clip.ids, targetFolderName);
		});

		// Paste in current / root toolbar button
		$(document).on('click', '#btn-paste-toolbar, #btn-paste-current', function() {
			const clip = getClipboard();
			if (!clip || !clip.ids || clip.ids.length === 0) {
				Swal.fire('Warning', 'Clipboard kosong!', 'warning');
				return;
			}

			const currentFolderId = $('#refresh_id').val() || 'root';
			const folderName = (currentFolderId === 'root') ? 'Root (/)' : 'Folder saat ini';
			executePaste(clip.action, currentFolderId, clip.ids, folderName);
		});

		function executePaste(action, targetFolderId, recordIds, targetName) {
			if (!recordIds || recordIds.length === 0) {
				Swal.fire('Warning', 'Tidak ada item yang dipilih!', 'warning');
				return;
			}

			const displayTarget = targetName || ((targetFolderId === 'root') ? 'Root (/)' : 'Folder Target');
			const actionTitle = (action === 'copy') ? 'Salin / Copy' : 'Pindahkan / Move';

			Swal.fire({
				title: `${actionTitle} ${recordIds.length} item?`,
				html: `Tujuan: <strong>${displayTarget}</strong>`,
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: `<i class="fa fa-paste"></i> Ya, ${actionTitle}`,
				cancelButtonText: 'Batal',
				confirmButtonColor: (action === 'copy') ? '#1bc5bd' : '#ffa800'
			}).then((result) => {
				if (result.isConfirmed) {
					Swal.fire({
						title: 'Sedang memproses...',
						text: 'Mohon tunggu sebentar',
						allowOutsideClick: false,
						didOpen: () => {
							Swal.showLoading();
						}
					});

					$.ajax({
						url: siteurl + active_controller + 'batch_paste',
						type: 'POST',
						data: {
							action: action,
							target_folder_id: targetFolderId,
							record_ids: recordIds,
							procedure_id: $('#procedure_id').val()
						},
						dataType: 'JSON',
						success: function(res) {
							if (res.status == 1) {
								clearClipboard();
								Swal.fire({
									title: 'Berhasil!',
									html: res.msg,
									icon: 'success',
									timer: 1500,
									showConfirmButton: false
								});

								if (action === 'move') {
									recordIds.forEach(function(recId) {
										removeDescendants(recId);
										$(`#records-tree-table tr.tree-row[data-id="${recId}"]`).remove();
									});
								}

								if (targetFolderId && targetFolderId !== 'root' && targetFolderId !== 'null') {
									reloadFolder(targetFolderId);
								} else {
									refreshRootTree();
								}
							} else {
								Swal.fire('Peringatan!', res.msg, 'warning');
							}
						},
						error: function() {
							Swal.fire('Error!', 'Terjadi kesalahan komunikasi dengan server.', 'error');
						}
					});
				}
			});
		}

	})


	function reload_form(pro_id) {
		$('#form-data-content').load(siteurl + active_controller + 'loadDataForm/' + pro_id)
	}

	function reload_guides(pro_id) {
		$('#guide-data-content').load(siteurl + active_controller + 'loadDataGuide/' + pro_id)
	}

	function readFile(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			index = $(input).data('index')

			reader.onload = function(e) {
				console.log(e)
				var htmlPreview = '<img width="150" src="' + e.target.result + '" />';

				var overlay = `<div class="middle d-flex justify-content-center align-items-center">
					<button type="button" onclick="$(this).parent().parent().find('.dropzone').click()" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle"><i class="fa fa-edit"></i></button>
					<button type="button" onclick="remove_image(this)" class="btn btn-sm mr-1 btn-icon btn-danger remove-image rounded-circle"><i class="fa fa-trash text-white"></i></button>
					</div>`;
				var wrapperZone = $(input).parent();
				var previewZone = $(input).parent().parent().find('.preview-zone');
				var boxZone = $(input).parent().find('.dropzone-desc');

				wrapperZone.removeClass('dragover');
				previewZone.removeClass('hidden');
				boxZone.html('');
				boxZone.append(htmlPreview);
				wrapperZone.find('.middle').remove();
				wrapperZone.append(overlay);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	function reset(e) {
		// e.wrap('<form>').closest('form').get(0).reset();
		// e.unwrap();
	}

	function remove_image(e) {
		let id = $(e).data('id')
		let dataImg = $(e).data('img')
		Swal.fire({
			title: 'Are you sure to delete this data?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#ee2d41',
			confirmButtonText: 'Yes, Delete <i class="fa fa-trash text-white"></i>',
		}).then((value) => {
			if (value.isConfirmed) {
				if (id && dataImg) {
					$.ajax({
						url: siteurl + active_controller + 'delete_img/' + id + '/' + dataImg,
						type: 'GET',
						dataType: 'JSON',
						success: function(result) {
							if (result.status == '1') {
								Swal.fire({
									title: 'Success!!',
									text: result.msg,
									icon: 'success',
									timer: 1500
								});

								let srcFile = $(e).parent().parent().find('.dropzone-desc').find('img').attr('src')
								$(e).parent().parent().find('input.dropzone').val('');
								$(e).parent().parent().find('input.dropzone').off();
								$(e).parent().parent().find('.dropzone-desc').empty().append('<i class="fa fa-upload"></i><p> Choose an image file or drag it here. </p>');
								// $(e).parent().parent().find('.for-delete').empty().append('<input type="hidden" name="delete_image[]" value="' + srcFile + '">');
								$(e).parent().remove();

							} else {
								Swal.fire('Warning', "Can't delete data. Please try again!", 'warning', 2000)
							}
						},
						error: function() {
							Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
						}
					})
				} else {
					let srcFile = $(e).parent().parent().find('.dropzone-desc').find('img').attr('src')
					$(e).parent().parent().find('input.dropzone').val('');
					$(e).parent().parent().find('input.dropzone').off();
					$(e).parent().parent().find('.dropzone-desc').empty().append('<i class="fa fa-upload"></i><p> Choose an image file or drag it here. </p>');
					// $(e).parent().parent().find('.for-delete').empty().append('<input type="hidden" name="delete_image[]" value="' + srcFile + '">');
					$(e).parent().remove();
				}
			}
		})

	}



	var _PDF_DOC,
		_CANVAS = document.querySelector('#pdf-preview'),
		_OBJECT_URL;

	function showPDF(pdf_url) {

		PDFJS.getDocument({
			url: pdf_url
		}).then(function(pdf_doc) {
			_PDF_DOC = pdf_doc;

			// Show the first page
			showPage(1);

			// destroy previous object url
			URL.revokeObjectURL(_OBJECT_URL);
		}).catch(function(error) {
			// trigger Cancel on error
			$("#cancel-pdf").click();

			// error reason
			alert(error.message);
		});;
	}

	function showPage(page_no) {
		var _CANVAS = document.querySelector('#pdf-preview');
		// fetch the page
		// console.log(page_no);
		// console.log(_PDF_DOC.getPage(page_no));
		_PDF_DOC.getPage(page_no).then(function(page) {
			// set the scale of viewport
			var scale_required = _CANVAS.width / page.getViewport(1).width;

			// get viewport of the page at required scale
			var viewport = page.getViewport(scale_required);

			// set canvas height
			_CANVAS.height = viewport.height;

			var renderContext = {
				canvasContext: _CANVAS.getContext('2d'),
				viewport: viewport
			};

			// render the page contents in the canvas
			page.render(renderContext).then(function() {
				$("#pdf-preview").css('display', 'inline-block');
				$("#pdf-loader").css('display', 'none');
			});
		});
	}

	/* Selected File has changed */
	$(document).on('change', "#pdf-file", function() {
		// user selected file
		console.log($(this));
		var file = $(this)[0].files[0];

		// allowed MIME types
		var mime_types = ['application/pdf'];

		// Validate whether PDF
		if (mime_types.indexOf(file.type) == -1) {
			alert('Error : Incorrect file type');
			return;
		}

		// validate file size
		if (file.size > 10 * 1024 * 1024) {
			alert('Error : Exceeded size 10MB');
			return;
		}

		// validation is successful

		// hide upload dialog button
		$("#upload-dialog").css('display', 'none');

		// set name of the file
		// $("#pdf-name").text(file.name);
		// $("#pdf-name").css('display', 'inline-block');

		// show cancel and upload buttons now
		$("#cancel-pdf").removeClass('d-none');
		$("#pdf-preview").removeClass('d-none');
		$("#remove-file").removeClass('d-none');
		// $("#upload-button").css('display', 'inline-block');

		// Show the PDF preview loader
		$("#pdf-loader").css('display', 'inline-block');

		// object url of PDF 
		// console.log(file);
		_OBJECT_URL = URL.createObjectURL(file)

		// send the object url of the pdf to the PDF preview function
		showPDF(_OBJECT_URL);
	});

	/* Reset file input */
	$(document).on('click', "#cancel-pdf,.remove-image", function() {
		// show upload dialog button
		$("#upload-dialog").css('display', 'inline-block');

		// reset to no selection
		$("#pdf-file").val('');

		// hide elements that are not required
		$("#pdf-name").css('display', 'none');
		// $("#pdf-preview").css('display', 'none');
		$("#pdf-preview").addClass('d-none');
		$("#pdf-loader").css('display', 'none');
		$("#cancel-pdf").addClass('d-none');
		$("#upload-button").css('display', 'none');
	});

	$(document).on('click', "#remove-file", function() {
		// show upload dialog button
		$("#upload-dialog").css('display', 'inline-block');
		$("#remove-document").val('x');

		// reset to no selection
		$("#pdf-file").val('');

		// hide elements that are not required
		$("#pdf-name").css('display', 'none');
		$("#pdf-preview").addClass('d-none');
		$("#pdf-loader").css('display', 'none');
		$("#cancel-pdf").addClass('d-none');
		$("#upload-button").css('display', 'none');
	});
</script>