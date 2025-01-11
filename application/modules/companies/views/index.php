<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> mr-2"></i><?= $title; ?></h2>
					<div class="mt-4 float-right ">
						<?php if ($this->session->company->id_perusahaan == '1') : ?>
							<button type="button" class="btn btn-primary" id="addCompany" title="Add New Company">
								<i class="fa fa-plus mr-1"></i>Add New Company
							</button>
						<?php endif; ?>

					</div>
				</div>
				<div class="card-body">
					<!-- Nav tabs -->
					<!-- <ul class="nav nav-tabs nav-pills pb-3" id="myTab" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active btn-sm" id="Published-tab" data-toggle="tab" data-target="#Published" type="button" role="tab" aria-controls="Published" aria-selected="true">Published <span class="badge badge-circle badge-white text-primary ml-2"></span></button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link btn-sm" id="Draft-tab" data-toggle="tab" data-target="#Draft" type="button" role="tab" aria-controls="Draft" aria-selected="false">Draft <span class="badge badge-circle badge-white text-primary ml-2"></span></button>
						</li>
					</ul> -->

					<!-- Tab panes -->
					<div class="tab-content mt-3">
						<div class="tab-pane fade active show" id="Published" role="tabpanel" aria-labelledby="Published-tab">
							<table id="example1" class="table table-bordered table-sm table-hover datatable">
								<thead class="text-center table-light">
									<tr class="text-center">
										<th width="3%">No.</th>
										<th class="text-left" width="200">Company Nama</th>
										<th class="text-left">Address</th>
										<th>City</th>
										<th>Inisial</th>
										<th width="150">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php if (isset($data) && $data) :
										$n = 0;
										foreach ($data as $dt) : $n++; ?>
											<tr class="">
												<td><?= $n; ?></td>
												<td class="text-left"><?= $dt->nm_perusahaan; ?></td>
												<td><?= $dt->alamat; ?></td>
												<td><?= $dt->kota; ?></td>
												<td><?= $dt->inisial; ?></td>
												<td>
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-info view" data-id="<?= $dt->id_perusahaan; ?>" title="View Data"><i class="fa fa-search"></i></button>
													<button type="button" class="btn btn-sm btn-icon rounded-circle btn-warning edit" data-id="<?= $dt->id_perusahaan; ?>" title="Edit Data"><i class="fa fa-edit"></i></button>
													<?php if ($this->session->company->id_perusahaan == '1') : ?>
														<button type="button" class="btn btn-sm btn-icon rounded-circle btn-danger delete" data-id="<?= $dt->id_perusahaan; ?>" title="View Data"><i class="fa fa-trash"></i></button>
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

<div class="modal fade" id="modalView" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel"></h5>
				<span class="close btn-cls" data-dismiss="modal" aria-label="Close"></span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary save min-w-100px"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger text-right" onclick="clear($('.modal-body'));setTimeout(()=>{$('.save').removeClass('d-none')},500)" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i> Close</button>
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

		$('#example1').DataTable({
			orderCellsTop: false,
			// fixedHeader: true,
			// scrollX: true,
			ordering: false,
			// info: false
		});

		$(document).on('click', '#add-branch', function() {
			let branchList, n = 0;
			n = $('.card.branch').length + 1
			branchList = `
			<div class="card branch border-info mb-3">
				<div class="card-header p-3">
					<h4 class="card-title font-weight-bolder mb-0"><i class="fas fa-code-branch text-dark"></i> Branch #${n}</h4>
				</div>
				<div class="card-body p-4">
					<div class="mb-3 row flex-nowrap">
						<label for="" class="col-3 col-form-label font-weight-bold">Company Branch Name <span class="text-danger">*</span></label>
						<div class="col">
							<input type="text" name="branch[${n}][branch_name]" id="branch${n}" class="form-control required" placeholder="Branch Name">
							<span class="invalid-feedback">Company Branch Name can't be empty</span>
						</div>
					</div>
					<div class="mb-3 row flex-nowrap">
						<label for="" class="col-3 col-form-label font-weight-bold">Address <span class="text-danger">*</span></label>
						<div class="col">
							<textarea name="branch[${n}][address]" id="branch${n}" class="form-control required" placeholder="Addrass"></textarea>
							<span class="invalid-feedback">Address Branch can't be empty</span>
						</div>
					</div>
					<div class="mb-3 row flex-nowrap">
						<label for="" class="col-3 col-form-label font-weight-bold">City <span class="text-danger">*</span></label>
						<div class="col-4">
							<input type="text" name="branch[${n}][city]" id="branch${n}" class="form-control required" placeholder="City">
							<span class="invalid-feedback">City Branch can't be empty</span>
						</div>
					</div>
				</div>
			</div>
			`;
			$('div#branch-list').append(branchList)
		})

		$(document).on('click', '#addCompany', function() {
			const url = siteurl + active_controller + 'add';
			$('.modal-title').html('<i class="fa fa-building text-dark" aria-hidden="true"></i> Add New Company')
			$('#modalView').modal('show')
			$('.modal-body').load(url)
		})

		$(document).on('click', '.edit', function() {
			const id = $(this).data('id')
			const url = siteurl + active_controller + 'edit/' + id;
			$('.modal-title').html('Edit Company')
			$('#modalView').modal('show')
			$('.modal-body').load(url)
		})

		$(document).on('click', '.view', function() {
			const id = $(this).data('id')
			const url = siteurl + active_controller + 'view/' + id;
			$('.modal-title').html('Edit Company')
			$('#modalView').modal('show')
			$('.modal-body').load(url)
			$('.save').addClass('d-none')

		})

		$(document).on('click', '.save', function(e) {
			validation($('.required'))
			let formdata = new FormData($('#form-company')[0])
			let btn = $(this)
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
					btn.html('<i class="spinner spinner-border-sm mr-3"></i>Loading...')
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
						}).then(function() {
							$('#modalView').modal('hide')
							clear($('.modal-body'))
							// $('table#example1 tbody').load(siteurl + active_controller + 'load_data')
							location.reload();
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

		$(document).on('click', '.delete', function(e) {
			const id = $(this).data('id')
			Swal.fire({
				title: 'Delete!',
				icon: 'question',
				text: 'Are you sure to delete this data?',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					alert('delete')
					alert(id)
				} else {
					alert('no delete')
				}
			})
			exit();

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
					btn.html('<i class="spinner spinner-border-sm mr-3"></i>Loading...')
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
						}).then(function() {
							$('#modalView').modal('hide')
							clear($('.modal-body'))
							// $('table#example1 tbody').load(siteurl + active_controller + 'load_data')
							location.reload();
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

	})

	function validation(field) {
		let err = 0;
		$.each($('.required'), function(i, field) {
			if ($(field).val() == '' || $(field).val() == null) {
				$(field).addClass('is-invalid')
				err++
			} else {
				$(field).removeClass('is-invalid')
			}
		})
		if (err > 0) {
			exit();
		}
	}

	function clear(e) {
		setTimeout(() => {
			$(e).html('');

		}, 500)
	}
</script>