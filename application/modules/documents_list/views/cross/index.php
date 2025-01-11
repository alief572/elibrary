<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="d-flex align-items-baseline flex-wrap mr-5 mb-10">
				<a href="<?= base_url('dashboard'); ?>">
					<h4 class="text-dark font-weight-bold my-1 mr-2"><i class="fa fa-home"></i></h4>
				</a>
			</div>
			<h1 class="text-white fa-3x">CROSS REFERENCE</h1>
			<div class="row mb-10">
				<div class="col-md-4">
					<!-- <input type="text" name="serarch" id="search" placeholder="Pencarian" class="form-control rounded form-control-sm"> -->
				</div>
			</div>
			<div class="card shadow card-custom">
				<div class="card-body">
					<div class="d-flex justify-content-end">
						<button type="button" class="btn btn-success mb-3 all-cross"><i class="fa fa-file-alt" aria-hidden="true"></i> All Cross Reference</button>
					</div>
					<table id="example1" class="table table-bordered table-hover table-sm datatable">
						<thead class="text-center">
							<tr class="text-center">
								<th class="p-2" width="40">No.</th>
								<th class="p-2 text-left">Nama</th>
								<th class="p-2" width="25%">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (isset($data) && $data) :
								$n = 0;
								foreach ($data as $dt) : $n++; ?>
									<tr class="text-center">
										<td class="p-2"><?= $n; ?></td>
										<td class="p-2 text-left"><?= $dt->name; ?></td>
										<td class="p-2">
											<button type="button" class="btn btn-primary proses_pasal" data-id="<?= $dt->standard_id; ?>" data-toggle="tooltip" title="View Data">Proses to Pasal</button>
											<button type="button" class="btn btn-info pasal_proses" data-id="<?= $dt->id; ?>" data-toggle="tooltip" title="View Data">Pasal to Proses</button>
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

<!-- Modal -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<form class="form-horiontal" id="form-input">
				<div class="modal-header">
					<h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
					<span onclick="$('#name').val('')" class="btn-close" data-dismiss="modal" aria-label="Close">
						<div class="fa fa-times"></div>
					</span>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer">
					<!-- <button type="submit" class="btn btn-primary" id="save"><i class="fa fa-save"></i>Save</button> -->
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#example1').DataTable({
			orderCellsTop: false,
			// fixedHeader: true,
			// scrollX: true,
			// ordering: false,
			// info: false
		});

		$(document).on('click', '.pasal_proses', function() {
			let id = $(this).data('id')
			$.ajax({
				url: siteurl + active_controller + 'cross_pasal_proses/' + id,
				type: 'GET',
				success: function(result) {
					if (result) {
						$('.modal-title').text('Cross Reference Pasal to Proses')
						$('.modal-body').html(result);
						$('#modalForm').modal('show')
						$('.modal-dialog').css("max-width", "90%")
					} else {
						Swal.fire('Warning', 'Data not valid. Please try again!', 'warning', 3000)
					}
				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
				}
			})
		})

		$(document).on('click', '.proses_pasal', function() {
			let id = $(this).data('id')
			$.ajax({
				url: siteurl + active_controller + 'cross_process_pasal/' + id,
				type: 'GET',
				success: function(result) {
					if (result) {
						$('.modal-title').text('Cross Reference Process to Pasal')
						$('.modal-body').html(result);
						$('#modalForm').modal('show')
						$('.modal-dialog').css("max-width", "90%")
					} else {
						Swal.fire('Warning', 'Data not valid. Please try again!', 'warning', 3000)
					}
				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
				}
			})
		})

		$(document).on('click', '.all-cross', function() {
			alert('all-cross')
			$.ajax({
				url: siteurl + active_controller + 'cross/all_cross',
				type: 'POST',
				success: function(result) {
					if (result) {
						$('.modal-title').text('Cross Reference All ISO')
						$('.modal-body').html(result);
						$('#modalForm').modal('show')
						$('.modal-dialog').css("max-width", "90%")
					} else {
						Swal.fire('Warning', 'Data not valid. Please try again!', 'warning', 3000)
					}
				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
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
				confirmButtonText: 'Yes, Delete <i class="fa fa-trash"></i>',
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete/' + id,
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

		$(document).on('submit', '#form-input', function(e) {
			e.preventDefault();
			let btn = $('#save')
			let formdata = new FormData($(this)[0])

			$.ajax({
				url: siteurl + active_controller + 'save',
				data: formdata,
				dataType: 'JSON',
				type: 'POST',
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
					if (result.status == '1') {
						Swal.fire({
							title: 'Success!',
							text: result.msg,
							icon: 'success',
							timer: 2000
						})
						$('#modalForm').modal('hide')
						$('#name').val('')
						location.reload();
					} else {
						Swal.fire({
							title: 'Warning!',
							text: result.msg,
							icon: 'warning',
							timer: 3000
						})
					}
				},
				error: function() {
					Swal.fire({
						title: 'Error!',
						text: 'Server timeout, becuase error. Please try again!',
						icon: 'error',
						timer: 5000
					})
				}
			})
		})
	})
</script>