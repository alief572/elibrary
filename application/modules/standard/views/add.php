<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<form id="form-standard">
				<div class="card card-stretch shadow card-custom">
					<div class="card-header justify-content-between d-flex align-items-center">
						<h2 class="m-0"><i class="fa fa-plus mr-2"></i><?= $title; ?></h2>
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Standard Category</label>
									<div class="col-8">
										<select name="standard_category" id="standard_category" onchange="getName()" class="form-control select2">
											<option value=""></option>
											<?php foreach ($category as $cat) : ?>
												<option value="<?= $cat->id; ?>"><?= $cat->name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="scope" class="col-4 col-form-label font-weight-bold">Scope</label>
									<div class="col-8">
										<select name="scopes" id="scope" data-allow-clear="true" class="form-control select2">
											<option value=""></option>
											<?php if ($scopes) : ?>
												<?php foreach ($scopes as $scp) : ?>
													<option value="<?= $scp->id; ?>"><?= $scp->name; ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label class="col-4 col-form-label font-weight-bold">Number</label>
									<div class="col-8">
										<input type="text" name="number" id="number" autocomplete="off" onchange="getName()" class="form-control" placeholder="-------">
										</input>
									</div>
								</div>
								<div class="mb-3 row flex-nowrap">
									<label for="Name" class="col-4 col-form-label font-weight-bold">Subject</label>
									<div class="col-8">
										<textarea name="subject" id="subject" placeholder="Subject" onchange="getName()" class="form-control"></textarea>
									</div>
								</div>

							</div>

							<div class="col-md-6">
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Year</label>
									<div class="col-4">
										<input type="text" name="year" autocomplete="off" id="year" onchange="getName()" maxlength="4" class="form-control numeric" placeholder="2022">
										<span class="invalid-feedback" id="invalid-feedbacek-year"></span>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="source" class="col-4 col-form-label font-weight-bold">Source</label>
									<div class="col-8">
										<textarea name="source" id="source" class="form-control" placeholder="Lorem ipsum dolor sit amet!"></textarea>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Frequence Check</label>
									<div class="col-8">

										<div class="input-group">
											<input type="number" name="frequence_check" id="frequence_check" class="form-control numeric">
											<div class="input-group-text">Tahun</div>
										</div>

									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Status</label>
									<div class="col-8">
										<select name="status" id="status" class="form-control select2">
											<option value="PUB">Publish</option>
											<option value="DFT">Draft</option>
										</select>
									</div>
								</div>

							</div>

							<!--  -->
							<div class="col-md-12">
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="Name" class="col-2 col-form-label font-weight-bold">Standard Name</label>
									<div class="col-10">
										<textarea name="name" class="form-control font-weight-bolder h4 form-control-solid" id="standard_name" placeholder="Standard Name" readonly rows="3"></textarea>
									</div>
								</div>
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="revision_desc" class="col-2 col-form-label font-weight-bold">Revision Description</label>
									<div class="col-10">
										<textarea name="revision_desc" id="revision_desc" class="form-control" placeholder="Description..." rows="4"></textarea>
									</div>
								</div>
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="document" class="col-2 col-form-label font-weight-bold">Upload Document</label>
									<div class="col-10">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text">Upload</span>
											</div>
											<div class="custom-file">
												<input type="file" name="document" id="document" placeholder="Document" class="custom-file-input" aria-describedby="fileHelpId">
												<label class="custom-file-label">Choose file</label>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold"></label>
									<div class="col-10">
										<div class="d-flex justify-content-between align-items-center">
											<button type="submit" class="btn btn-primary w-100px save"><i class="fa fa-save"></i>Save</button>
											<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>

				<!-- Modal -->
				<div class="modal fade" id="modelId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
						<div class="modal-content">
							<div id="contentModal"></div>
							<div class="modal-footer justify-content-between align-items-center">
								<button type="submit" class="btn btn-primary w-100px save" id="save_chapter"><i class="fa fa-save"></i>Save</button>
								<button type="button" class="btn btn-danger" onclick=";setTimeout(()=>{$('#contentModal').html('');tinymce.remove()},500)" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="modelTitle" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<form id="form-title">
					<div class="mb-5">
						<label class="col-form-label font-weight-bold">Pasal</label>
						<input type="text" name="chapter" class="form-control" id="point" placeholder="Pasal ..." />
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between align-items-center">
				<button type="submit" class="btn btn-primary w-100px" id="save_pasal"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" id="reset" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

		$(document).on('paste keypress', '.numeric', function(e) {
			const element = $(this)
			element.removeClass('is-invalid')
			element.css(
				'text-decoration', 'none'
			)
			// Only ASCII character in that range allowed
			let key = (e.which) ? e.which : e.keyCode
			if (key > 31 && (key < 48 || key > 57)) {
				element.css(
					'text-decoration', 'line-through'
				)
				element.addClass('is-invalid')
				element.next('span.invalid-feedback').text('Karakter yang diinput harus Angka!')
				// return false;
			}
		})

		$('.select2').select2({
			placeholder: 'Choose an Option',
			width: '100%',
			allowClear: true,
		})

		$(document).on('submit', '#form-standard', function(e) {
			e.preventDefault();
			let formdata = new FormData($(this)[0])
			let btn = $('.save')
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
						location.href = siteurl + active_controller
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


		$(document).on('click', '#reset', function() {
			$('#form-title').find("input[type=text], textarea").val("");

		})

		$(document).on('change', '#year', function() {
			const inputYear = $(this)
			const currYear = new Date().getFullYear();

			if (jQuery.type(parseInt(inputYear.val())) != "number" || parseInt(inputYear.val()) > parseInt(currYear)) {
				inputYear.addClass('is-invalid')
				inputYear.css(
					'text-decoration', 'line-through'
				)
				$('#invalid-feedbacek-year').text('Tahun tidak valid')
			}
		})

	})

	function getName() {
		const Cat = $('#standard_category option:selected').text()
		const number = $('#number').val() || ''
		const year = $('#year').val() || ''
		const subject = $('#subject').val() || ''

		if (Cat && year && subject) {
			var no = y = a = '';
			if (number) {
				no = " " + number;
			}
			if (year) {
				y = ", " + year;
			}
			if (subject) {
				s = ", " + subject;
			}

			$('#standard_name').val(Cat + no + y + s)
		}

	}
</script>