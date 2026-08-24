<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<div class="mt-4 float-right ">
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
					</div>
				</div>
				<div class="card-body">
					<form id="form-cross">
						<div class="row mb-3">
							<label for="exampleInputEmail1" class="col-2 col-form-label">Select Standard</label>
							<div class="col-3">
								<select name="standard" id="standard" class="form-control form-control-solid select2" data-dropdown-parent=".card-body">
									<option value=""></option>
									<?php foreach ($data as $dt) : ?>
										<option value="<?= $dt->id; ?>"><?= $dt->name; ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="load_data"></div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal View Pasal -->
<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">View Detail</h5>
				<span class="btn-close cursor-pointer" data-dismiss="modal" aria-label="Close">
					<div class="fa fa-times"></div>
				</span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal View Procedure -->
<div class="modal fade" id="modalViewProcedure" tabindex="-1" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="">View Procedure</h5>
				<span class="btn-close" data-dismiss="modal" aria-label="Close">
					<div class="fa fa-times"></div>
				</span>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: "Choose an options",
			width: "100%",
			allowClear: true
		});

		$(document).on('change', '#standard', function() {
			let id = $(this).val();

			if (id) {
				$.ajax({
					url: siteurl + active_controller + 'select_standard/' + id,
					type: 'GET',
					success: function(result) {
						if (result) {
							$('.load_data').html(result)
						} else {
							Swal.fire('Warning', "Can't show data. Please try again!", 'warning', 2000)
						}
					},
					error: function() {
						Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 3000)
					}
				})
			} else {
				$('.load_data').html('')
			}
		})

		$(document).on('click', '.view_pasal', function() {
			let id = $(this).data('id')
			$.ajax({
				url: siteurl + active_controller + 'view_pasal/' + id,
				type: 'GET',
				dataType: 'JSON',
				success: function(result) {
					if (result) {
						let html = `
						<div class="form-group">
							<label class="font-weight-bold"><strong>Pasal</strong></label>
							<div class="">
							` + (result.chapter || '') + `
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
							` + (result.desc_indo || '-') + `
							</div>
							<div class="tab-pane pt-4 pb-4" id="eng" role="tabpanel" aria-labelledby="eng-tab">
							` + (result.desc_eng || '-') + `
							</div>
						</div>
						`;
						$('#modalView .modal-title').text('View Pasal');
						$('#modalView .modal-body').html(html);
						$('#modalView').modal('show');
					} else {
						Swal.fire('Warning', 'Data not valid. Please try again!', 'warning', 3000)
					}

				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
				}
			})
		})

		$(document).on('click', '.view-procedure', function() {
			let id = $(this).data('id')
			$('#modalViewProcedure').modal('show')
			$('#modalViewProcedure .modal-dialog').css("max-width", "70%")
			$('#modalViewProcedure .modal-title').text('View Procedure')
			$.ajax({
				url: siteurl + 'procedures/view/' + id,
				type: 'POST',
				success: function(result) {
					if (result) {
						$('#modalViewProcedure .modal-body').html(result);
						let download = `<a href="${siteurl+active_controller}download/${id}" target="_blank" class="btn btn-sm btn-info"><i class="fa fa-download"></i>Download</a>`
						$('#modalViewProcedure .modal-body').prepend(download);
					}
				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
				}
			})
		})
	})
</script>