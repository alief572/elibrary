<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<!-- <div class="col-xl-3">
					<div class="card card-custom card-stretch gutter-b bg-transparent shadow-none">
						<button type="button" class="btn btn-light-primary">Create Documnets</button>
					</div>
				</div> -->
			</div>
			<div class="row">
				<div class="col-xl-3 col-sm-6">
					<div class="card card-custom card-stretch gutter-b">
						<div class="card-header border-0 py-2">
							<img src="<?= base_url('assets/img/') ?><?= ($pictures[0]->pictures) ? $pictures[0]->pictures : 'default.png'; ?>" style=" height:130px" class="m-auto px-3 img-fluid">
							<div class="card-toolbar p-0 m-0 align-content-start position-absolute pr-3" style="right:0px">
								<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Options" data-placement="left">
									<a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:/keen/theme/demo6/dist/assets/media/svg/icons/Text/Dots.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1">
													<rect x="14" y="9" width="6" height="6" rx="3" fill="black" />
													<rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-md dropdown-menu-right shadow-lg">
										<!--begin::Navigation-->
										<ul class="navi navi-hover p-3 rounded-lg">
											<li class="navi-item">
												<a href="javascript:void(0)" class="navi-link change-picture" data-id="1" data-toggle="modal" data-target="#exampleModal">
													<span class="navi-icon">
														<span class="svg-icon svg-icon-primary svg-icon-md">
															<!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/keen/releases/2021-04-21-040700/theme/demo6/dist/../src/media/svg/icons/Home/Picture.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="16" rx="2" />
																	<polygon fill="#000000" opacity="0.3" points="4 20 10.5 11 17 20" />
																	<polygon fill="#000000" points="11 20 15.5 14 20 20" />
																	<circle fill="#000000" opacity="0.3" cx="18.5" cy="8.5" r="1.5" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</span>
													<span class="navi-text">Change Photo</span>
												</a>
											</li>
										</ul>
										<!--end::Navigation-->
									</div>
								</div>
							</div>
						</div>
						<div class="card-body d-flex align-items-center justify-content-center p-1 flex-wrap">
							<span class="font-weight-bolder display5 text-info"><?= $doc; ?></span>
						</div>
						<div class="card-footer text-center border-0 justify-content-center p-0">
							<h3 class="card-title p-5 m-0">
								<a href="<?= base_url('folders'); ?>" class="text-hover-primary" title="Documents List">
									<span class="card-label text-center text-info m-0 font-weight-bolder">Create Documents</span>
								</a>
							</h3>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<div class="card card-custom card-stretch gutter-b text-primary">
						<div class="card-header border-0 p-2">
							<img src="<?= base_url('assets/img/') ?><?= ($pictures[1]->pictures) ? $pictures[1]->pictures : 'default.png'; ?>" style=" max-height:130px" class="m-auto px-3 img-fluid">
							<div class="card-toolbar p-0 m-0 align-content-start position-absolute pr-3" style="right:0px">
								<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Options" data-placement="left">
									<a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:/keen/theme/demo6/dist/assets/media/svg/icons/Text/Dots.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1">
													<rect x="14" y="9" width="6" height="6" rx="3" fill="black" />
													<rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-md dropdown-menu-right shadow-lg">
										<ul class="navi navi-hover p-3">
											<li class="navi-item">
												<a href="javascript:void(0)" class="navi-link change-picture" data-id="2">
													<span class="navi-icon">
														<span class="svg-icon svg-icon-primary svg-icon-md">
															<!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/keen/releases/2021-04-21-040700/theme/demo6/dist/../src/media/svg/icons/Home/Picture.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="16" rx="2" />
																	<polygon fill="#000000" opacity="0.3" points="4 20 10.5 11 17 20" />
																	<polygon fill="#000000" points="11 20 15.5 14 20 20" />
																	<circle fill="#000000" opacity="0.3" cx="18.5" cy="8.5" r="1.5" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</span>
													<span class="navi-text">Change Photo</span>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="card-body d-flex align-items-center justify-content-center p-1 flex-wrap">
							<span class="font-weight-bolder display5 text-danger"><?= $docCor; ?></span>
						</div>
						<div class="card-footer text-center border-0 justify-content-center p-0">
							<h3 class="card-title p-5 m-0">
								<a href="<?= base_url('dokumen/koreksi'); ?>" class="text-hover-primary" title="Correction Documents">
									<span class="card-label m-0 text-danger text-center font-weight-bolder">Correction Documents</span>
								</a>
							</h3>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<div class="card card-custom card-stretch gutter-b">
						<div class="card-header border-0 p-2">
							<img src="<?= base_url('assets/img/') ?><?= ($pictures[2]->pictures) ? $pictures[2]->pictures : 'default.png'; ?>" style="height:130px" class="m-auto px-3 img-fluid">
							<div class="card-toolbar p-0 m-0 align-content-start position-absolute pr-3" style="right:0px">
								<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Options" data-placement="left">
									<a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:/keen/theme/demo6/dist/assets/media/svg/icons/Text/Dots.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1">
													<rect x="14" y="9" width="6" height="6" rx="3" fill="black" />
													<rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-md dropdown-menu-right shadow-lg">
										<!--begin::Navigation-->
										<ul class="navi navi-hover p-3">
											<li class="navi-item">
												<a href="javascript:void(0)" class="navi-link change-picture" data-id="3" data-toggle="modal" data-target="#exampleModal">
													<span class="navi-icon">
														<span class="svg-icon svg-icon-primary svg-icon-md">
															<!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/keen/releases/2021-04-21-040700/theme/demo6/dist/../src/media/svg/icons/Home/Picture.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="16" rx="2" />
																	<polygon fill="#000000" opacity="0.3" points="4 20 10.5 11 17 20" />
																	<polygon fill="#000000" points="11 20 15.5 14 20 20" />
																	<circle fill="#000000" opacity="0.3" cx="18.5" cy="8.5" r="1.5" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</span>
													<span class="navi-text">Change Photo</span>
												</a>
											</li>
										</ul>
										<!--end::Navigation-->
									</div>
								</div>
							</div>
						</div>
						<div class="card-body d-flex align-items-center justify-content-center p-1 flex-wrap">
							<span class="font-weight-bolder display5 text-warning"><?= $docRev; ?></span>
						</div>
						<div class="card-footer text-center border-0 p-0 justify-content-center">
							<h3 class="card-title p-5 m-0">
								<a href="<?= base_url('dokumen/review'); ?>" class="text-hover-primary" title="Review Documents">
									<span class="card-label text-center text-warning m-0 font-weight-bolder">Review Documents</span>
								</a>
							</h3>
						</div>
					</div>
				</div>
				<div class="col-xl-3 col-sm-6">
					<div class="card card-custom card-stretch gutter-b">
						<div class="card-header border-0 py-2">
							<img src="<?= base_url('assets/img/') ?><?= ($pictures[3]->pictures) ? $pictures[3]->pictures : 'default.png'; ?>" style="height:130px" class="m-auto px-3 img-fluid">
							<div class="card-toolbar p-0 m-0 align-content-start position-absolute pr-3" style="right:0px">
								<div class="dropdown dropdown-inline" data-toggle="tooltip" title="Options" data-placement="left">
									<a href="#" class="btn btn-icon-primary btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:/keen/theme/demo6/dist/assets/media/svg/icons/Text/Dots.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1">
													<rect x="14" y="9" width="6" height="6" rx="3" fill="black" />
													<rect x="3" y="9" width="6" height="6" rx="3" fill="black" fill-opacity="0.7" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</a>
									<div class="dropdown-menu dropdown-menu-md dropdown-menu-right shadow-lg">
										<!--begin::Navigation-->
										<ul class="navi navi-hover p-3">
											<li class="navi-item">
												<a href="javascript:void(0)" class="navi-link change-picture" data-id="4" data-toggle="modal" data-target="#exampleModal">
													<span class="navi-icon">
														<span class="svg-icon svg-icon-primary svg-icon-md">
															<!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/keen/releases/2021-04-21-040700/theme/demo6/dist/../src/media/svg/icons/Home/Picture.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
																<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<polygon points="0 0 24 0 24 24 0 24" />
																	<rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="16" rx="2" />
																	<polygon fill="#000000" opacity="0.3" points="4 20 10.5 11 17 20" />
																	<polygon fill="#000000" points="11 20 15.5 14 20 20" />
																	<circle fill="#000000" opacity="0.3" cx="18.5" cy="8.5" r="1.5" />
																</g>
															</svg>
															<!--end::Svg Icon-->
														</span>
													</span>
													<span class="navi-text">Change Photo</span>
												</a>
											</li>
										</ul>
										<!--end::Navigation-->
									</div>
								</div>
							</div>
						</div>
						<div class="card-body d-flex align-items-center justify-content-center p-1 flex-wrap">
							<span class="font-weight-bolder display5 text-primary"><?= $docApv; ?></span>
						</div>
						<div class="card-footer border-0 text-center justify-content-center p-0">
							<h3 class="card-title p-5 m-0">
								<a href="<?= base_url('dokumen/approve'); ?>" class="text-hover-primary" title="Approvel Documents">
									<span class="card-label text-center text-primary m-0 font-weight-bolder">Approval Documents</span>
								</a>
							</h3>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Change Picture</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close"></i>
				</button>
			</div>
			<div class="modal-body" id="viewData"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-primary font-weight-bold" onclick="location.reload()" data-dismiss="modal">OK</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('change', '#picture', function(event) {
		let old_picture = $('#old_picture').val();
		// alert(old_photo)
		var reader = new FileReader();
		reader.onload = function() {
			var output = document.getElementById('preview');
			output.src = reader.result;
			console.log(reader);
			let dataUpload = new FormData($('#dataUpload')[0]);
			$.ajax({
				url: baseurl + active_controller + 'upload',
				type: 'POST',
				data: dataUpload,
				dataType: 'JSON',
				cache: false,
				processData: false,
				contentType: false,
				success: function(result) {
					console.log(result.msg);
					if (result.status == 1) {
						$('#msg-upload').fadeIn('ease').html(`
							<div class="alert alert-custom py-3 alert-light-primary fade show mb-5" role="alert">
								<div class="alert-icon"><i class="fa fa-info-circle"></i></div>
								<div class="alert-text">` + result.msg + `</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="ki ki-close"></i></span>
									</button>
								</div>
							</div>`)
						$('#old_picture').val(result.photo);
						setTimeout(function() {
							$('#msg-upload').fadeOut('ease')
						}, 5000)
					} else {
						$('#msg-upload').fadeIn('ease').html(`\
							<div class="alert alert-danger">
								<div class="alert alert-custom py-3 alert-light-danger fade show mb-5" role="alert">
									<div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
									<div class="alert-text">` + result.msg + `</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="ki ki-close"></i></span>
										</button>
									</div>
								</div>`)
						// return false;
						setTimeout(function() {
							$('#msg-upload').fadeOut('ease')
							$('#preview').attr('src', './assets/img/' + old_picture);
						}, 5000)
					}
				},
				error: function(result) {
					alert('Internal Error!');
					console.log(result);
				}
			})
		}
		reader.readAsDataURL(event.target.files[0]);
	})

	$(document).on('click', '#upload', function() {
		let id = $(this).data('id')
		let dataUpload = new FormData($('#dataUpload')[0]);
		$.ajax({
			url: baseurl + active_controller + 'upload',
			type: 'POST',
			data: dataUpload,
			dataType: 'JSON',
			cache: false,
			processData: false,
			contentType: false,
			success: function(result) {
				console.log(result.msg);
				if (result.status == 1) {
					$('#msg-upload').fadeIn('ease').html(`
							<div class="alert alert-custom py-3 alert-light-primary fade show mb-5" role="alert">
								<div class="alert-icon"><i class="fa fa-info-circle"></i></div>
								<div class="alert-text">` + result.msg + `</div>
								<div class="alert-close">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true"><i class="ki ki-close"></i></span>
									</button>
								</div>
							</div>`)
					$('#old_picture').val(result.photo);
					setTimeout(function() {
						$('#msg-upload').fadeOut('ease')
					}, 5000)
				} else {
					$('#msg-upload').fadeIn('ease').html(`\
							<div class="alert alert-danger">
								<div class="alert alert-custom py-3 alert-light-danger fade show mb-5" role="alert">
									<div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
									<div class="alert-text">` + result.msg + `</div>
									<div class="alert-close">
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true"><i class="ki ki-close"></i></span>
										</button>
									</div>
								</div>`)
					// return false;
					setTimeout(function() {
						$('#msg-upload').fadeOut('ease')
						$('#preview').attr('src', './assets/img/' + old_picture);
					}, 5000)
				}
			},
			error: function(result) {
				alert('Internal Error!');
				console.log(result);
			}
		})
	})

	$(document).on('click', '.change-picture', function() {
		let id = $(this).data('id')
		$.ajax({
			url: baseurl + active_controller + 'picture',
			type: 'POST',
			data: {
				id
			},
			success: function(result) {
				if (result) {
					$('#exampleModal').modal('show');
					$('#viewData').html(result);
				} else {
					$('#exampleModal').modal('show');
					$('#viewData').html('-- No Data --');
				}
			},
			error: function(result) {
				alert('Internal Error!');
				console.log(result);
			}
		})
	})
</script>