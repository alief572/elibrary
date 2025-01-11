<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<form id="form-chapter">
				<div class="card card-stretch shadow card-custom">
					<div class="card-header d-flex justify-content-between align-items-center">
						<h2><i class="fa fa-plus mr-2"></i><?= $title; ?></h2>
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<input type="hidden" name="id" id="id" value="<?= $Data->id; ?>">
								<div class="mb-3 row flex-nowrap">
									<label for="Name" class="col-2 col-form-label font-weight-bold">Name</label>
									<div class="col-10">
										<input type="text" name="name" class="form-control bg-light-warning form-control-solid" id="Name" placeholder="Name of Requirement" value="<?= $Data->name; ?>" />
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Year</label>
									<div class="col-4">
										<input type="text" name="year" id="year" class="form-control" placeholder="2022" value="<?= $Data->year; ?>">
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Number</label>
									<div class="col-4">
										<input type="text" name="number" id="number" class="form-control" placeholder="09123" value="<?= $Data->number; ?>">
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Status</label>
									<div class="col-4">
										<select name="status" id="status" class="form-control select2">
											<option value="1" <?= ($Data->status == '1') ? 'selected' : ''; ?>>Publish</option>
											<option value="DFT" <?= ($Data->status == 'DFT') ? 'selected' : ''; ?>>Draft</option>
										</select>
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold"></label>
									<div class="col-4">
										<button type="submit" class="btn btn-primary w- save"><i class="fa fa-save"></i>Save</button>

									</div>
								</div>

							</div>
						</div>

						<hr>
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h4 class="">List Pasal</h4>
							<button type="button" class="btn btn-primary btn-sm" id="add_pasal"><i class="fa fa-plus mr-2"></i>Add Pasal</button>
						</div>
						<table class="table datatable table-sm table-condensed table-bordered">
							<thead class="text-center ">
								<tr class="table-light">
									<th width="40">No</th>
									<th width="150">Pasal</th>
									<th width="450">Desc. Indonesian</th>
									<th width="450">Desc. English</th>
									<th width="150">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($Data_list) :
									$n = 0;
									foreach ($Data_list as $dtl) : $n++; ?>
										<tr>
											<td class="text-center"><?= $n; ?></td>
											<td class="">
												<?= $dtl->chapter; ?>
											</td>
											<td class="">
												<?= ($dtl->desc_indo) ? limit_text(strip_tags($dtl->desc_indo), 200) . ' <a href="#read" class="link view" data-id="' . $dtl->id . '">[read]</a>' : ''; ?>
											</td>
											<td class="">

												<?= ($dtl->desc_eng) ? limit_text(strip_tags($dtl->desc_eng), 200) . ' <a href="#read" class="link view" data-id="' . $dtl->id . '">[read]</a>' : ''; ?>
											</td>
											<td class="text-center" style="vertical-align: middle;">
												<button type="button" class="btn btn-sm btn-info rounded-circle btn-icon view" data-id="<?= $dtl->id; ?>"><i class="fa fa-file-alt"></i></button>
												<button type="button" class="btn btn-sm btn-warning rounded-circle btn-icon edit" data-id="<?= $dtl->id; ?>"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm btn-danger rounded-circle btn-icon delete" data-id="<?= $dtl->id; ?>"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
								<?php endforeach;
								endif; ?>
							</tbody>
						</table>
					</div>
				</div>
				<!-- <button class="btn btn-primary" type="button" data-target="#modelId" data-toggle="modal">Modal</button> -->
				<!-- Modal -->
				<div class="modal fade" id="modelId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Modal title</h5>
								<button type="button" class="close" onclick="tinymce.remove()" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="container-fluid" id="modal_content">

								</div>
							</div>
							<div class="modal-footer justify-content-between align-items-center">
								<button type="submit" class="btn btn-primary w-100px" id="save_chapter"><i class="fa fa-save"></i>Save</button>
								<button type="button" class="btn btn-danger" onclick="tinymce.remove()" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modalView" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="container-fluid" id="modal_content_view">

				</div>
			</div>
			<div class="modal-footer justify-content-end align-items-center">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '#add_pasal', function() {
		let html = `
			<div class="mb-5">
				<label for="chapter" class="col-form-label font-weight-bold">Pasal</label>
				<input type="text" name="list[chapter]" class="form-control" id="chapter" placeholder="Chapter of Requirement" />
			</div>
			<div class="mb-5">
				<label for="chapter" class="font-weight-bold">Description in Indonesian</label>
				<textarea name="list[desc_indo]" class="form-control summernote" id="desc_indo" rows="10" placeholder="Description"></textarea>
			</div>
			<div class="mb-5">
				<label for="chapter" class="font-weight-bold">Description in English</label>
				<textarea name="list[desc_eng]" class="form-control summernote" id="desc_eng" rows="10" placeholder="Description"></textarea>
			</div>`;
		$('#modal_content').html(html)
		$('.modal-title').html('Add Detail')
		$('#modelId').modal('show')

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

		$('textarea.summernote').summernote({
			height: 300
		})
		// tinymce.init({
		// 	selector: 'textarea.textarea',
		// 	height: 500,
		// 	resize: true,
		// 	plugins: 'preview importcss searchreplace autolink autosave save ' +
		// 		'directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
		// 	toolbar: 'undo redo | blocks | ' +
		// 		'bold italic backcolor forecolor | alignleft aligncenter ' +
		// 		'alignright alignjustify | template codesample bullist numlist outdent indent | link image ' +
		// 		'removeformat | help',
		// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
		// 	// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
		// });

	})

	$(document).ready(function() {
		$('.datatable').DataTable()
		$('.select2').select2({
			placeholder: 'Choose an Option',
			width: '100%',
			allowClear: true,
		})

		$(document).on('click', '.edit', function() {
			let id = $(this).data('id')
			$('.modal-title').html('Edit Detail')
			$.ajax({
				url: siteurl + active_controller + 'edit_detail/' + id,
				type: 'GET',
				dataType: 'JSON',
				success: function(result) {
					let html = `
						<div class="mb-5">
							<label for="chapter" class="col-form-label font-weight-bold">Pasal</label>
							<input type="hidden" name="list[id]" class="form-control" id="id" value="` + result.id + `" placeholder="Chapter of Requirement" />
							<input type="text" name="list[chapter]" class="form-control" id="chapter" value="` + result.chapter + `" placeholder="Chapter of Requirement" />
						</div>
						<div class="mb-5">
							<label for="chapter" class="font-weight-bold">Description in Indonesian</label>
							<textarea name="list[desc_indo]" class="form-control summernote" id="desc_indo" rows="10" placeholder="Description">` + result.desc_indo + `</textarea>
						</div>
						<div class="mb-5">
							<label for="chapter" class="font-weight-bold">Description in English</label>
							<textarea name="list[desc_eng]" class="form-control summernote" id="desc_eng" rows="10" placeholder="Description">` + result.desc_eng + `</textarea>
						</div>
						`;
					$('#modal_content').html(html)
					$('#modelId').modal('show')

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

					$('textarea.summernote').summernote({
						height: 300
					})

					// tinymce.init({
					// 	selector: 'textarea.textarea',
					// 	height: 500,
					// 	resize: true,
					// 	plugins: 'preview importcss  searchreplace autolink autosave save ' +
					// 		'directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
					// 	toolbar: 'undo redo | blocks | ' +
					// 		'bold italic backcolor forecolor | alignleft aligncenter ' +
					// 		'alignright alignjustify | template codesample bullist numlist outdent indent | link image ' +
					// 		'removeformat | help',
					// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
					// 	// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
					// });

				},
				error: function(result) {
					Swal.fire({
						title: 'Error!!',
						text: 'Server timeout, becuase error. Please try again.',
						icon: 'error',
						timer: 3000
					})
				}
			})
		})

		$(document).on('click', '.view', function() {
			let id = $(this).data('id')
			$('.modal-title').html('View Detail')
			$.ajax({
				url: siteurl + active_controller + 'view_detail/' + id,
				type: 'GET',
				dataType: 'JSON',
				success: function(result) {
					let html = `
						<div class="form-group">
							<label class="font-weight-bold"><strong>Pasal</strong></label>
							<div class="">
							` + result.chapter + `
							</div>
						</div>

						<!-- Nav tabs -->
						<ul class="nav nav-fill nav-pills" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link nav-pill active" id="indo-tab" data-toggle="tab" data-target="#indo" type="button" role="tab" aria-controls="indo" aria-selected="true">Indonesian</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link nav-pill" id="eng-tab" data-toggle="tab" data-target="#eng" type="button" role="tab" aria-controls="eng" aria-selected="false">English</a>
							</li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content mt-4 border rounded-lg p-5">
							<div class="tab-pane active pt-4 pb-4" id="indo" role="tabpanel" aria-labelledby="indo-tab">
							` + result.desc_indo + `
							</div>
							<div class="tab-pane pt-4 pb-4" id="eng" role="tabpanel" aria-labelledby="eng-tab">
							` + result.desc_eng + `
							</div>
						</div>
					`;
					$('#modal_content_view').html(html)
					$('#modalView').modal('show')
				},
				error: function(result) {
					Swal.fire({
						title: 'Error!!',
						text: 'Server timeout, becuase error. Please try again.',
						icon: 'error',
						timer: 3000
					})
				}
			})
		})

		$(document).on('submit', '#form-chapter', function(e) {
			e.preventDefault();
			let form = $(this)[0]
			let formdata = new FormData(form)
			let btn = $('#save_chapter')
			$.ajax({
				url: siteurl + active_controller + '/save',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {
					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-border-sm"></i>Loading...')
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
						$('#modelId').modal('hide')
						location.reload();
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

		$(document).on('click', '.delete', function() {
			let id = $(this).data('id')
			Swal.fire({
				title: 'Are you sure to delete this data?',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#DD6B55',
				confirmButtonText: 'Yes, Delete <i class="fa fa-trash text-white"></i>',
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_pasal/' + id,
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
									location.reload()
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
	})
</script>