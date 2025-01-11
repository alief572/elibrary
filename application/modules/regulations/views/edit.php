<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<form id="form-regulation">
				<input type="hidden" name="id" value="<?= $data->id; ?>">
				<div class="card card-stretch shadow card-custom">
					<div class="card-header justify-content-between d-flex align-items-center">
						<h2 class="m-0"><i class="fa fa-plus mr-2"></i><?= $title; ?></h2>
						<a href="<?= base_url($this->uri->segment(1)); ?>" class="btn btn-danger"><i class="fa fa-reply"></i>Back</a>
					</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Regulation Category</label>
									<div class="col-8">
										<select name="regulation_category" id="regulation_category" onchange="getName()" class="form-control select2">
											<option value=""></option>
											<?php foreach ($category as $cat) : ?>
												<option value="<?= $cat->id; ?>" <?= ($cat->id == $data->regulation_category) ? 'selected' : ''; ?>><?= $cat->name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="nomenclature" class="col-4 col-form-label font-weight-bold">Nomenclature</label>
									<div class="col-8">
										<input type="text" name="nomenclature" value="<?= $data->nomenclature; ?>" onchange="getName()" class="form-control" id="nomenclature" placeholder="Nomenclature" />
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Number</label>
									<div class="col-8">
										<input type="text" name="number" value="<?= $data->number; ?>" id="number" autocomplete="off" onchange="getName()" class="form-control numeric" placeholder="---------">
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="Name" class="col-4 col-form-label font-weight-bold">Subject</label>
									<div class="col-8">
										<?php
										$inArray = isset($ArrRegSjb[$data->id]) ? $ArrRegSjb[$data->id] : [];
										?>
										<select name="subjects[]" multiple id="subject" data-allow-clear="true" class="form-control select2">
											<option value=""></option>
											<?php if ($subjects) : ?>
												<?php foreach ($subjects as $sub) : ?>
													<option value="<?= $sub->id; ?>" <?= (($inArray) && in_array($sub->id, $inArray)) ? 'selected' : ''; ?>><?= $sub->name; ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="scope" class="col-4 col-form-label font-weight-bold">Scope</label>
									<div class="col-8">
										<?php
										$inArray = isset($ArrRegScp[$data->id]) ? $ArrRegScp[$data->id] : '';
										?>
										<select name="scopes[]" multiple id="scope" data-allow-clear="true" class="form-control select2">
											<option value=""></option>
											<?php if ($scopes) : ?>
												<?php foreach ($scopes as $scp) : ?>
													<option value="<?= $scp->id; ?>" <?= (($inArray) && in_array($scp->id, $inArray)) ? 'selected' : ''; ?>><?= $scp->name; ?></option>
												<?php endforeach; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>

							</div>

							<div class="col-md-6">
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Year</label>
									<div class="col-4">
										<input type="text" name="year" value="<?= $data->year; ?>" autocomplete="off" id="year" onchange="getName()" maxlength="4" class="form-control numeric" placeholder="2022">
										<span class="invalid-feedback" id="invalid-feedbacek-year"></span>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">About</label>
									<div class="col-8">
										<textarea name="about" id="about" class="form-control" onchange="getName()" placeholder="Lorem ipsum dolor sit amet!"><?= $data->about; ?></textarea>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="source" class="col-4 col-form-label font-weight-bold">Source</label>
									<div class="col-8">
										<textarea name="source" id="source" class="form-control" placeholder="Lorem ipsum dolor sit amet!"><?= $data->source; ?></textarea>
									</div>
								</div>

								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Status</label>
									<div class="col-8">
										<select name="status" id="status" class="form-control select2">
											<option value="DFT" <?= ($data->status == 'DFT') ? 'selected' : ''; ?>>Draft</option>
											<option value="PUB" <?= ($data->status == 'PUB') ? 'selected' : ''; ?>>Berlaku</option>
											<option value="EXP" <?= ($data->status == 'EXP') ? 'selected' : ''; ?>>Dicabut</option>
											<option value="CH" <?= ($data->status == 'CH') ? 'selected' : ''; ?>>Diubah</option>
										</select>
									</div>
								</div>
								<div id="reg_relate" class="mb-3 <?= ($data->regulation_relation)?'':'d-none' ; ?> row flex-nowrap">
									<label for="" class="col-4 col-form-label font-weight-bold">Regulation Relate</label>
									<div class="col-8">
										<select name="regulation_relation" id="regulation_relation" class="form-control select2">
											<option value=""></option>
											<?php if ($listReg) foreach ($listReg as $reg):
												$name = $reg->category_name . " " . $reg->nomenclature . (($reg->number) ? " No. " . $reg->number : '') . " " . $reg->year;
											?>
												<option value="<?= $reg->id; ?>" <?= ($reg->id == $data->regulation_relation) ? 'selected' : ''; ?>><?= $name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>

							<!--  -->
							<div class="col-md-12">
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="Name" class="col-2 col-form-label font-weight-bold">Regulation Name</label>
									<div class="col-10">
										<textarea name="name" class="form-control font-weight-bolder h4 form-control-solid" id="regulation_name" placeholder="Regulation Name" readonly rows="3"><?= $data->name; ?></textarea>
									</div>
								</div>
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="revision_desc" class="col-2 col-form-label font-weight-bold">Revision Description</label>
									<div class="col-10">
										<textarea name="revision_desc" id="revision_desc" class="form-control" placeholder="Description..." rows="4"><?= $data->revision_desc; ?></textarea>
									</div>
								</div>
								<hr>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold">Upload Document</label>
									<div class="col-10">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text">Upload</span>
											</div>
											<div class="custom-file">
												<input type="file" name="document" id="document" placeholder="Document" class="custom-file-input" aria-describedby="fileHelpId">
												<label class="custom-file-label">Choose file</label>
											</div>
											<input type="hidden" name="old_file" id="old_file" value="<?= $data->document; ?>">
										</div>
									</div>
								</div>
								<hr>
								<?php if ($data->document) : ?>
									<div class="mb-3 row flex-nowrap">
										<label for="" class="col-2 col-form-label font-weight-bold"></label>
										<div class="col-10">
											<a target="_blank" href="<?= base_url('/directory/REGULATIONS/' . $data->company_id . "/" . $data->document); ?>">
												<div class="d-flex align-items-center">
													<i class="fa fa-file-alt text-success fa-3x mr-3"></i><?= $data->name; ?>
												</div>
											</a>
										</div>
									</div>
									<hr>
								<?php endif; ?>
								<div class="mb-3 row flex-nowrap">
									<label for="" class="col-2 col-form-label font-weight-bold"></label>
									<div class="col-4">
										<button type="button" class="btn btn-primary w-100px" id="save_regulation"><i class="fa fa-save"></i>Save</button>
									</div>
								</div>
							</div>

						</div>
						<hr>
						<div class="d-flex justify-content-between align-items-center mb-3">
							<h4 class="">List Pasal</h4>
							<button type="button" class="btn btn-primary btn-sm" data-id="<?= $data->id; ?>" id="add_pasal"><i class="fa fa-plus mr-2"></i>Add New Pasal</button>
						</div>

						<div class="card">
							<div class="card-body">
								<?php if ($pasal) : ?>
									<div class="row">
										<div class="col-3 border-right-secondary border border-bottom-0 border-left-0 border-top-0">
											<ul class="nav flex-column nav-pills nav-secondary">
												<?php foreach ($pasal as $k => $psl) : ?>
													<li class="nav-item text-left">
														<a class="nav-link h4 text-dark d-inline d-flex justify-content-between align-items-center <?= ($k == 0) ? 'active' : ''; ?>" id="pasal-<?= $psl->id; ?>" data-toggle="pill" data-target="#psl-desc-<?= $psl->id; ?>" type="button" role="tab" aria-selected="true">
															<span><?= ucfirst($psl->name); ?></span>
															<span class="">
																<button id='<?= $psl->id; ?>' class='btn px-0 btn-xs text-hover-warning btn-icon edit-pasal id-<?= $psl->id; ?>' title='Edit'><i class='fa fa-pen'></i></button>
																<button id='<?= $psl->id; ?>' class='btn px-0 btn-xs text-hover-danger btn-icon delete-pasal id-<?= $psl->id; ?>' title='Delete'><i class='fa fa-trash'></i></button>
															</span>
														</a>
														<!-- <a href='javascript:void(0)' class="pasal-title d-inline text-hover-primary" role="button" data-toggle="popover" data-trigger="click" data-html="true" data-content="
															<a href='javascript:void(0)' id='<?= $psl->id; ?>' class='btn btn-xs text-hover-success btn-icon add-phar id-<?= $psl->id; ?>' title='Add'><i class='fa fa-plus'></i></a>
															
															"><i class="fa fa-cog"></i></a> -->
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
										<div class="col-9">
											<div class="tab-content" id="list-pasal">
												<?php foreach ($pasal as $k => $psl) : ?>
													<div class="tab-pane fade show px-5 <?= ($k == 0) ? 'active' : ''; ?>" id="psl-desc-<?= $psl->id; ?>" role="tabpanel">
														<button type="button" id='<?= $psl->id; ?>' class='btn btn-light-success btn-sm add-phar id-<?= $psl->id; ?> mb-5' title='Add Description'><i class='fa fa-plus-circle'></i> Add Description</button>
														<table class="table table-borderless mb-5">
															<thead class="text-center">
																<tr class="table-light">
																	<th width="50" class="text-center py-2">Ayat</th>
																	<th class="py-2">Description</th>
																	<th class="py-2" width="50">Opsi</th>
																</tr>
															</thead>
															<tbody>
																<?php if (isset($ArrPhar[$psl->id])) : $n = 0; ?>
																	<?php foreach ($ArrPhar[$psl->id] as $phar) : $n++; ?>
																		<tr>
																			<td width="100" class="text-center"><?= $phar->name; ?></td>
																			<td class="font-size-lg text-left">
																				<?= $phar->description; ?>
																			</td>
																			<td width="80" class="text-center">
																				<button type="button" data-id="<?= $phar->id; ?>" class="btn btn-xs btn-warning btn-icon edit-desc"><i class="fa fa-edit"></i></button>
																				<button type="button" data-id="<?= $phar->id; ?>" class="btn btn-xs btn-danger btn-icon del-desc"><i class="fa fa-trash"></i></button>
																			</td>
																		</tr>
																	<?php endforeach; ?>
																<?php else : ?>
																	<tr>
																		<td colspan="2" class="text-muted">~ No data avilable ~</td>
																	</tr>
																<?php endif; ?>
															</tbody>
														</table>
													</div>
												<?php endforeach; ?>
											</div>
										</div>
									</div>
								<?php else : ?>
									<h5 class="text-center text-muted">~ Not available data ~</h5>
								<?php endif; ?>
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
								<button type="submit" class="btn btn-primary w-100px" id="save_"><i class="fa fa-save"></i>Save</button>
								<button type="button" class="btn btn-danger" onclick=";setTimeout(()=>{$('#contentModal').html('');tinymce.remove()},500)" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
							</div>
						</div>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>
<style>
	.popover {
		z-index: 1000 !important;
	}
</style>
<div class="modal fade" id="modelTitle" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<form id="form-title">
					<div class="mb-5">
						<label class="col-form-label font-weight-bold">Pasal</label>
						<input type="hidden" name="regulation_id" class="form-control" value="<?= $data->id; ?>" />
						<input type="text" name="pasal" id="pasal" placeholder="Pasal 1" class="form-control" />
					</div>
					<div class="mb-5">
						<label class="col-form-label font-weight-bold">Order</label>
						<input type="number" name="order" id="order" placeholder="Order" class="form-control w-25" />
					</div>
				</form>
			</div>
			<div class="modal-footer justify-content-between align-items-center">
				<button type="button" class="btn btn-primary px-4" style="transition: width ease 1s" id="save_pasal"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" id="reset" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modelEditPasal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-body">
			</div>
			<div class="modal-footer justify-content-between align-items-center">
				<button type="button" class="btn btn-primary px-4" style="transition: width ease 1s" id="save_pasal"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" id="reset" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modelPhar" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="z-index:1041">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p class="text-center">No Data</p>
			</div>
			<div class="modal-footer justify-content-between align-items-center">
				<button type="button" class="btn btn-primary px-4" style="transition: width ease 1s" id="save_phar"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modelEditDesc" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="z-index:1041">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<p class="text-center">No Data</p>
			</div>
			<div class="modal-footer justify-content-between align-items-center">
				<button type="button" class="btn btn-primary px-4" style="transition: width ease 1s" id="update_desc"><i class="fa fa-save"></i>Save</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Cancel</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(document).on('change', '#status', function(e) {
			if ($(this).val() == 'CH') {
				$('#reg_relate').removeClass('d-none')
			} else {
				$('#reg_relate').addClass('d-none').find('select#regulation_relation').val('').change()
			}
		})

		$(document).on('paste keypress', '.-numeric', function(e) {
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

		$(document).on('click', '#add_pasal', function() {
			const id = $(this).data('id')
			$('#modelTitle').modal('show')
			$('#modelTitle .modal-body').load(siteurl + active_controller + 'add_pasal/' + id)
		})

		$(document).on('click', '#save_regulation', function(e) {
			let formdata = new FormData($('#form-regulation')[0])
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
						location.href = siteurl + active_controller + 'edit/' + result.id
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

		$(document).on('click', '#save_pasal', function() {
			$('#pasal').removeClass('is-invalid')
			let formdata = new FormData($('#form-title')[0])
			let btn = $(this)
			$.ajax({
				url: siteurl + active_controller + 'save_pasal',
				data: formdata,
				type: 'POST',
				dataType: 'JSON',
				processData: false,
				contentType: false,
				cache: false,
				beforeSend: function() {

					btn.attr('disabled', true)
					btn.html('<i class="spinner spinner-sm mr-5"></i>Loading...')
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
							$('#modelTitle').modal('hide')
							location.reload();
						})
						// location.href = siteurl + active_controller + '/edit/' + result.id
					} else if (result.status == 2) {
						Swal.fire({
							title: 'Warning!',
							icon: 'warning',
							text: result.msg,
							timer: 3000
						})
						$('#pasal').addClass('is-invalid')
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

		/* PHAR */

		$(document).on('click', '.add-phar', function() {
			let id = $(this).attr('id')
			if (id) {
				$('#modelPhar').modal('show')
				$('#modelPhar .modal-body').load(siteurl + active_controller + 'load_form/' + id)
			}
		})

		$(document).on('click', '.edit-pasal', function() {
			let id = $(this).attr('id')
			// alert(siteurl + active_controller + 'edit_pasal/' + id)
			if (id) {
				$('#modelTitle').modal('show')
				$('#modelTitle .modal-body').load(siteurl + active_controller + 'edit_pasal/' + id)
			}
			// alert(id)
		})

		$(document).on('click', '.delete-pasal', function() {
			let id = $(this).attr('id')
			const btn = $(this)
			if (id) {
				Swal.fire({
					title: 'Confirm!',
					text: 'Are you sure you want to delete this data?',
					icon: 'question',
					showCancelButton: true,
				}).then((value) => {
					if (value.isConfirmed) {
						$.ajax({
							url: siteurl + active_controller + 'delete_pasal',
							data: {
								id
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(result) {
								if (result.status == 1) {
									btn.parents('tr').remove();
									Swal.fire({
										title: 'Success!',
										icon: 'success',
										text: result.msg,
										timer: 3000
									}).then(() => {
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
									timer: 3000
								})
							}
						})
					}
				})
			} else {
				$(this).parents('tr').remove();
			}
		})

		/* Desc */

		$(document).on('click', '#add-desc', function() {
			let row = $('.pharagraps tbody tr').length + 1
			html = `
				<tr>
					<td>
						<span>+</span>
					</td>
					<td>
						<input type="text" class="form-control" name="dtl[` + row + `][name]" aria-describedby="helpId" placeholder="Ayat">
					</td>
					<td>
						<input type="number" class="form-control" name="dtl[` + row + `][order]" aria-describedby="helpId" placeholder="Number">
					</td>
					<td>
						<textarea class="form-control tinymce" name="dtl[` + row + `][desc]" placeholder="Description"></textarea>
					</td>
					<td width="20">
						<button type="button" data-id="" class="btn btn-xs btn-icon btn-danger btn-del"><i class="fa fa-times"></i></button>
					</td>
				</tr>
			`;

			$('.pharagraps tbody').append(html)
			// load_tm()
		})

		$(document).on('click', '.edit-desc', function() {
			let id = $(this).data('id')
			if (id) {
				$('#modelEditDesc').modal('show')
				$('#modelEditDesc .modal-body').load(siteurl + active_controller + 'load_form_edit/' + id)
			}
		})

		$(document).on('click', '.del-desc', function() {
			const id = $(this).data('id')
			const btn = $(this)
			if (id) {
				Swal.fire({
					title: 'Confirm!',
					text: 'Are you sure you want to delete this data?',
					icon: 'question',
					showCancelButton: true,
				}).then((value) => {
					if (value.isConfirmed) {
						$.ajax({
							url: siteurl + active_controller + 'del_desc',
							data: {
								id
							},
							type: 'POST',
							dataType: 'JSON',
							success: function(result) {
								if (result.status == 1) {
									btn.parents('tr').remove();
									Swal.fire({
										title: 'Success!',
										icon: 'success',
										text: result.msg,
										timer: 3000
									}).then(() => {
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
									timer: 3000
								})
							}
						})
					}
				})
			} else {
				$(this).parents('tr').remove();
			}
		})

		$(document).on('click', '#save_phar', function() {
			let btn = $(this)
			let formdata = new FormData($('#form-desc')[0])
			if (formdata) {
				$.ajax({
					url: siteurl + active_controller + 'save_desc',
					data: formdata,
					type: 'POST',
					dataType: 'JSON',
					processData: false,
					contentType: false,
					cache: false,
					beforeSend: function() {
						btn.attr('disabled', true)
						btn.html('<i class="spinner spinner-sm mr-5"></i> Loading...')
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
								timer: 3000
							}).then(() => {
								$('#modelPhar').modal('hide')
								location.reload();
							})
						} else if (result.status == 2) {
							Swal.fire({
								title: 'Warning!',
								icon: 'warning',
								text: result.msg,
								timer: 3000
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
			}
		})

		$(document).on('click', '#update_desc', function() {
			let btn = $(this)
			let formdata = new FormData($('#form-desc')[0])
			if (formdata) {
				$.ajax({
					url: siteurl + active_controller + 'update_desc',
					data: formdata,
					type: 'POST',
					dataType: 'JSON',
					processData: false,
					contentType: false,
					cache: false,
					beforeSend: function() {
						btn.attr('disabled', true)
						btn.html('<i class="spinner spinner-sm mr-5"></i> Loading...')
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
								timer: 3000
							}).then(() => {
								$('#modelPhar').modal('hide')
								location.reload();
							})
						} else if (result.status == 2) {
							Swal.fire({
								title: 'Warning!',
								icon: 'warning',
								text: result.msg,
								timer: 3000
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
			}
		})

		$(document).on('click', '.btn-del', function() {
			$(this).parents('tr').remove();
		})

		/* end desc */

	})

	function load_tm() {
		tinymce.init({
			selector: 'textarea.tinymce',
			height: 100,
			resize: true,
			plugins: 'autoresize autosave emoticons preview importcss searchreplace autolink autosave save ' +
				'directionality  visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
			toolbar: 'restoredraft preview searchreplace | undo redo | blocks ' +
				'bold italic backcolor forecolor | alignleft aligncenter ' +
				'alignright alignjustify | template codesample bullist numlist outdent indent | link image ' +
				'table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol' +
				'removeformat emoticons | help',
			content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
			autoresize_bottom_margin: 50,
			link_default_protocol: 'https'
			// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
		});

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

	function getName() {
		const regCat = $('#regulation_category option:selected').text()
		const nomenclature = $('#nomenclature').val() || ''
		const number = $('#number').val() || ''
		const year = $('#year').val() || ''
		const about = $('#about').val() || ''

		if (regCat && nomenclature && number && year && about) {
			var no = y = a = '';
			if (number) {
				no = "Nomor " + number;
			}
			if (year) {
				y = "Tahun " + year;
			}
			if (about) {
				a = "Tentang " + about;
			}

			$('#regulation_name').val(regCat + " " + nomenclature + " " + no + " " + y + " " + a)
		}

	}
</script>