<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<form id="form-chapter">
				<div class="card card-stretch shadow card-custom">
					<div class="card-header justify-content-between d-flex align-items-center">
						<h2 class="m-0"><i class="fa fa-plus mr-2"></i><?= $title; ?></h2>
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3 row flex-nowrap">
									<label for="Name" class="col-2 col-form-label font-weight-bold">Name</label>
									<div class="col-10">
										<input type="text" name="name" class="form-control" id="Name" placeholder="Standard Name" />
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Year</label>
									<div class="col-4">
										<input type="text" name="year" id="year" class="form-control" placeholder="2022">
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Number</label>
									<div class="col-4">
										<input type="text" name="number" id="number" class="form-control" placeholder="09123">
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Status</label>
									<div class="col-4">
										<select name="status" id="status" class="form-control select2">
											<option value="1">Publish</option>
											<option value="DFT">Draft</option>
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
									<th width="80">No</th>
									<th>Pasal</th>
									<th>Desc. Indonesian</th>
									<th>Desc. English</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>

				</div>
				<!-- Modal -->
				<div class="modal fade" id="modelId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Modal title</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="container-fluid">
									<div class="mb-5">
										<label for="chapter" class="col-form-label font-weight-bold">Chapter</label>
										<input type="text" name="list[chapter]" class="form-control" id="chapter" placeholder="Chapter" />
									</div>

									<div class="mb-5">
										<label for="chapter" class="font-weight-bold">Description in Indonesian</label>
										<textarea name="list[desc_indo]" class="form-control summernote" id="desc_indo" rows="10" placeholder="Description"></textarea>
									</div>

									<div class="mb-5">
										<label for="chapter" class="font-weight-bold">Description in English</label>
										<textarea name="list[desc_eng]" class="form-control summernote" id="desc_eng" rows="10" placeholder="Description"></textarea>
									</div>

								</div>
							</div>
							<div class="modal-footer justify-content-between align-items-center">
								<button type="submit" class="btn btn-primary w- save" id="save_chapter"><i class="fa fa-save"></i>Save</button>
								<button type="button" class="btn btn-danger"  data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.datatable').DataTable()
		$('.select2').select2({
			placeholder: 'Choose an Option',
			width: '100%',
			allowClear: true,
		})

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
			height:150
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

		$(document).on('click', '#add_pasal', function() {
			$('#modelId').modal('show')
		})

		$(document).on('submit', '#form-chapter', function(e) {
			e.preventDefault();
			let formdata = new FormData($(this)[0])
			let btn = $('.save')
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
						location.href = siteurl + active_controller + '/edit/' + result.id
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
	})
</script>