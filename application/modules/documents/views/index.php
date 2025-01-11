<style type="text/css">
	#preview-container {
		margin: 50px auto;
		/* width: 600px; */
	}

	#upload-dialog {
		padding: 5px;
		border: 1px solid #336699;
		background-color: white;
		color: #336699;
		background: none;
		font-size: inherit;
		font-family: inherit;
		outline: none;
		display: inline-block;
		vertical-align: middle;
		cursor: pointer;
		border-radius: 2px;
	}

	/* #pdf-file {
		display: none;
	} */

	#pdf-loader {
		display: none;
		vertical-align: middle;
		color: #cccccc;
		font-size: 12px;
	}

	#pdf-preview {
		display: none;
		vertical-align: middle;
		border: 1px solid rgba(0, 0, 0, 0.2);
		border-radius: 2px;
	}

	#pdf-name {
		display: none;
		vertical-align: middle;
		color: #336699;
		margin: 0 15px;
		max-width: 200px;
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
	}

	#upload-button {
		padding: 5px;
		border: 1px solid #336699;
		background-color: #336699;
		color: white;
		font-size: inherit;
		font-family: inherit;
		outline: none;
		display: none;
		vertical-align: middle;
		cursor: pointer;
		border-radius: 2px;
	}

	/* #cancel-pdf {
		display: none;
		vertical-align: middle;
		padding: 0px;
		border: none;
		color: #777777;
		background-color: white;
		font-size: inherit;
		font-family: inherit;
		outline: none;
		vertical-align: middle;
		cursor: pointer;
		margin: 0 0 0 15px;
	} */
</style>

<style>
	.cursor-pointer:hover div.dir-tools .btn-dropdown {
		display: block !important;
	}
</style>

<div class="bg-light h-100">
	<div class="card-header rounded-0 d-flex justify-content-between px-5 py-2 align-items-center" style="box-shadow: 5px 0px 10px #272727;">
		<h3 class="my-0 text-dark "><i class="fas fa-folder-open text-blue" style="font-size: 24px;"></i> Document Manager</h3>
		<div class="d-flex justify-content-between align-items-center">
			<div class="search mr-1">
				<div class="input-group">
					<input id="search" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="input1" />
					<label for="search" class="input-group-text bg-transparent border-left-0 rounded-left-0" id="input1"><i class="fa fa-search"></i></;>
				</div>
			</div>
			<button type="button" class="btn btn-light mr-1"><i class="fa fa-folder-plus text-inverse-light" aria-hidden="true"></i> New Folder</button>
			<button type="button" class="btn btn-light mr-1"><i class="fa fa-file text-inverse-light" aria-hidden="true"></i> New File</button>
		</div>
	</div>

	<div class="m-5 p-0 card">
		<table class="table mb-0 table-sm table-hover table-bordered table-">
			<thead>
				<tr>
					<th class="text-center" width="30">
						<input type="checkbox" class="form-checkbox">
					</th>
					<th width="" class="h6">Name</th>
					<th width="150" class="h6">Size</th>
					<th width="200" class="h6">Modified</th>
					<th width="250" class="h6">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($parent !== false) : ?>
					<tr>
						<?php if (!$FM_READONLY) : ?>
							<td class="nosort"></td>
						<?php endif; ?>
						<td class="border-0" data-sort><a href="?d=<?php echo urlencode($parent) ?>"><i class="fa fa-chevron-circle-left go-back"></i> ..</a></td>
						<td class="border-0" data-order></td>
						<td class="border-0" data-order></td>
						<td class="border-0"></td>
						<?php if (!$FM_IS_WIN) { ?>
							<td class="border-0"></td>
							<td class="border-0"></td>
						<?php } ?>
					</tr>

					<?php else :

					$ii = 3399;
					foreach ($folders as $f) :
						$is_link = is_link($path . '/' . $f);
						$img = $is_link ? 'fa fa-link' : 'fa fa-folder text-warning';
						$modif_raw = filemtime($path . '/' . $f);
						$modif = date(FM_DATETIME_FORMAT, $modif_raw);
						$date_sorting = strtotime(date("F d Y H:i:s.", $modif_raw));
						$filesize_raw = "";
						$filesize = ('Folder');
						$perms = substr(decoct(fileperms($path . '/' . $f)), -4);
					?>
						<tr>
							<?php if (!$FM_READONLY) { ?>
								<td class="custom-checkbox-td pl-4">
									<div class="custom-control custom-checkbox text-center">
										<input type="checkbox" class="custom-control-input" id="<?php echo $ii ?>" name="file[]" value="">
										<label class="custom-control-label" for="<?php echo $ii ?>"></label>
									</div>
								</td>
							<?php }; ?>
							<td data-sort="">
								<h6 class="filename">
									<a href="?p=<?php echo urlencode(trim($FM_PATH . '/' . $f, '/')) ?>" class="text-dark">
										<i class="<?php echo $img ?>"></i> <?php echo fm_convert_win(fm_enc($f)) ?>
									</a><?php echo ($is_link ? ' &rarr; <i>' . readlink($FM_PATH . '/' . $f) . '</i>' : '') ?>
								</h6>
							</td>
							<td data-order="a-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>">
								<?php echo $filesize; ?>
							</td>
							<td data-order="a-<?php echo $date_sorting; ?>"><?php echo $modif ?></td>
							<td class="inline-actions">
								<a class="btn btn-xs btn-icon btn-danger" title="<?php echo ('Delete') ?>" href="?p=<?php echo urlencode($FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="confirmDailog(event, '1028','<?php echo ('Delete') . ' ' . ('Folder'); ?>','<?php echo urlencode($f) ?>', this.href);"> <i class="fa fa-trash" aria-hidden="true"></i></a>
								<a class="btn btn-xs btn-icon btn-warning" title="<?php echo ('Rename') ?>" href="#" onclick="rename('<?php echo fm_enc(addslashes($FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><i class="fa fa-pen" aria-hidden="true"></i></a>
								<a class="btn btn-xs btn-icon btn-info" title="<?php echo ('CopyTo') ?>" href="?p=&amp;copy=<?php echo urlencode(trim($FM_PATH . '/' . $f, '/')) ?>"><i class="fa fa-copy" aria-hidden="true"></i></a>
								<!-- <a class="btn btn-xs btn-icon btn-secondary" title="<?php echo ('DirectLink') ?>" href="<?php echo fm_enc($FM_ROOT_URL . ($FM_PATH != '' ? '/' . $FM_PATH : '') . '/' . $f . '/') ?>" target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a> -->
							</td>
						</tr>
					<?php
						flush();
						$ii++;
					endforeach; ?>

				<?php endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="4" class="p-2">
						<h6 for="">Total Items : <strong class="text-dark"><?= count($files); ?></strong> |</h6>
					</th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalId" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer py-2">
				<!-- <button type="button" class="btn btn-primary save"><i class="fa fa-save"></i>Save</button> -->
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalView" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" style="max-width:90%" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-2"></div>
			<div class="modal-footer py-2">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
			</div>
		</div>
	</div>
</div>

<?php
function fm_convert_win($filename)
{
	if (FM_IS_WIN && function_exists('iconv')) {
		$filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
	}
	return $filename;
};

function fm_enc($text)
{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

?>
<script>
	$(document).ready(function() {
		$('a[data-toggle="pill"]').on('shown.bs.tab', function(e) {
			$.fn.dataTable.tables({
				visible: true,
				api: true,
				searching: false,
				lengthChange: false,
				paging: false,
				info: false,
				stateSave: true,
				fixedHeader: true,
				pageLength: 50,
				scrollCollapse: true
			}).columns.adjust();
		});

		$('.datatable').DataTable({
			searching: false,
			lengthChange: false,
			paging: false,
			info: false,
			stateSave: true,
			fixedHeader: true,
			pageLength: 50,
			scrollCollapse: true
		})
	})

	/* DIRECTORY */

	$(document).on('click', '.add-directory', function() {
		$('#modalId .modal-title').text('Add Directory')
		$('#modalId').modal('show')
		$('#modalId .modal-body').html(`
		<label for="">Directory Name</label>
		<input type="text" id="dir_name" class="form-control" placeholder="New Folder">
		<span class="invalid-feedback">Directory Name not be empty</span>
		`)
		$('.save-sub-folder,.save-files,.update-files')
			.removeClass('save-sub-folder')
			.removeClass('save-files')
			.removeClass('update-files')
			.addClass('save')
	})

	$(document).on('click', '.save', function() {
		const name = $('#dir_name').val();
		const id = $('#id_dir').val();

		if (!name) {
			$('#dir_name').addClass('is-invalid')
			return false;
		}

		$('#dir_name').removeClass('is-invalid')

		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this new directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						name,
						id
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire("Success!", result.msg, "success", 3000).then(function() {
								location.reload()
							})
						} else {
							Swal.fire("Warning!", result.msg, "warning", 3000)
						}
					},
					error: function(result) {
						Swal.fire("Error!", "Server time out.", "error", 3000)

					}
				})
			}
		})
	})

	$(document).on('click', '.edit_dir', function() {
		const id = $(this).data('id')
		$.getJSON(siteurl + active_controller + 'edit_dir/' + id, function(data) {
			var items = [];
			$('#modalId .modal-title').text('Edit Directory')
			$('#modalId').modal('show')
			$('#modalId .modal-body').html(`
			<label for="">Directory Name</label>
			<input type="text" id="id_dir" class="form-control d-none" value="` + data.data.id + `">
			<input type="text" id="dir_name" class="form-control" placeholder="New Folder" value="` + data.data.name + `">
			<span class="invalid-feedback">Directory Name not be empty</span>
			`)
			$('.save-sub-folder,.save-files,.update-files')
				.removeClass('save-sub-folder')
				.removeClass('save-files')
				.removeClass('update-files')
				.addClass('save')
		});
	})

	$(document).on('click', '.delete_dir', function() {
		const id = $(this).data('id')
		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire("Success!", result.msg, "success", 3000).then(function() {
								location.reload()
							})
						} else {
							Swal.fire("Warning!", result.msg, "warning", 3000)
						}
					},
					error: function(result) {
						Swal.fire("Error!", "Server time out.", "error", 3000)

					}
				})
			}
		})
	})

	/* SUB DIRECTORY */

	$(document).on('click', '.add-sub-directory', function() {
		const materi_id = $(this).data('id')

		$('#modalId .modal-title').text('Add Sub Directory')
		$('#modalId').modal('show')
		$('#modalId .modal-body').html(`
		<label for="">Directory Name</label>
		<input type="hidden" id="materi_id" class="form-control" value="` + materi_id + `">
		<input type="text" id="sub_dir_name" class="form-control" placeholder="New Sub Folder">
		<span class="invalid-feedback">Directory Name not be empty</span>
		`)
		$('.save,.save-files,.update-files')
			.removeClass('save')
			.removeClass('save-files')
			.removeClass('update-files')
			.addClass('save-sub-folder')
	})

	$(document).on('click', '.edit-sub-folder', function() {
		const id = $(this).data('id')
		$.getJSON(siteurl + active_controller + 'edit_sub_dir/' + id, function(data) {
			var items = [];
			$('#modalId .modal-title').text('Edit Sub Directory')
			$('#modalId').modal('show')
			$('#modalId .modal-body').html(`
			<label for="">Directory Name</label>
			<input type="hidden" id="id_sub_dir" class="form-control" value="` + data.data.id + `">
			<input type="hidden" id="materi_id" class="form-control" value="` + data.data.materi_id + `">
			<input type="text" id="sub_dir_name" class="form-control" placeholder="New Folder" value="` + data.data.name + `">
			<span class="invalid-feedback">Directory Name not be empty</span>
			`)
			$('.save,.save-files,.update-files')
				.removeClass('save')
				.removeClass('save-files')
				.removeClass('update-files')
				.addClass('save-sub-folder')
		});


	})

	$(document).on('click', '.save-sub-folder', function() {
		const name = $('#sub_dir_name').val();
		const materi_id = $('#materi_id').val();
		const id = $('#id_sub_dir').val();

		if (!name) {
			$('#sub_dir_name').addClass('is-invalid')
			return false;
		}

		$('#sub_dir_name').removeClass('is-invalid')

		Swal.fire({
			title: 'Confirm!',
			text: 'Are you sure you want to save this new directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'save_sub_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						name,
						materi_id,
						id
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire("Success!", result.msg, "success", 3000).then(function() {
								location.reload()
							})
						} else {
							Swal.fire("Warning!", result.msg, "warning", 3000)
						}
					},
					error: function(result) {
						Swal.fire("Error!", "Server time out.", "error", 3000)

					}
				})
			}
		})
	})

	$(document).on('click', '.delete-sub-folder', function() {
		const id = $(this).data('id')
		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this directory?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_sub_dir',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire("Success!", result.msg, "success", 3000).then(function() {
								location.reload()
							})
						} else {
							Swal.fire("Warning!", result.msg, "warning", 3000)
						}
					},
					error: function(result) {
						Swal.fire("Error!", "Server time out.", "error", 3000)

					}
				})
			}
		})
	})

	/* UPLOAD FILES */
	$(document).on('click', '.upload-file', function() {
		const cat = $(this).data('cat')
		const materi_detail_id = $(this).data('materi_detail_id')
		$('#modalId .modal-title').text('Upload Files')
		$('#modalId').modal('show')
		$('#modalId .modal-dialog').addClass('modal-lg')
		$('#modalId .modal-body').html(`
		<form id="form-header" class="mb-4">
			<input type="hidden" name="materi_detail_id" class="form-control mb-3" placeholder="Document Name" value="` + materi_detail_id + `" aria-describedby="helpId">
			<input type="hidden" name="category" class="form-control mb-3" placeholder="Document Name" value="` + cat + `" aria-describedby="helpId">
			<div class="row">
				<div class="col-md-2">Name</div>
				<div class="col-md-10">
					<div class="form flex-grow-1">
						<input type="text" name="name-file" id="name-file" class="form-control mb-3" placeholder="Document Name" aria-describedby="helpId">
						<span class="invalid-feedback">Name document can't be empty</span>
					</div>
				</div>
			</div>
		</form>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-10">
				<!-- Nav tabs -->
				<ul class="nav nav-tabs" id="tab-upload" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link active" id="upload-document-tab" data-toggle="tab" data-target="#upload-document" type="button" role="tab" aria-controls="upload-document" aria-selected="false">Upload Document</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="from-external-url-tab" data-toggle="tab" data-target="#from-external-url" type="button" role="tab" aria-controls="from-url" aria-selected="true">External Link <i class="ml-2 fa fa-link text-primary" aria-hidden="true"></i></button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link" id="from-url-tab" data-toggle="tab" data-target="#from-url" type="button" role="tab" aria-controls="from-url" aria-selected="true">From YouTube <i class="fab fa-youtube ml-1 text-danger"></i></button>
					</li>
				</ul>
		
				<!-- Tab panes -->
				<div class="tab-content">
					<div class="tab-pane active p-3 border border-top-0 rounded-bottom" id="upload-document" role="tabpanel" aria-labelledby="upload-file-tab">
						<form id="form-document" enctype="multipart/form-data">
							<div class="dropzone-wrapper mr-2 d-flex flex-column justify-content-center align-items-center" style="width: 100%;height:310px">
								<div class="dropzone-desc">
									<i class="fa fa-upload"></i>
									<p>Choose an image file or drag it here.</p>
								</div>
								<input type="file" id="pdf-file" name="documents" accept="application/pdf" class="dropzone dropzone-1" />
								<canvas id="pdf-preview" width="250"></canvas>
								<div class="for-delete"></div>
							</div>
							<span class="invalid-feedback">File or Document can't be empty</span>
							<div class="d-flex justify-content-between mt-3">
								<button type="button" class="btn btn-primary save-files"><i class="fa fa-save"></i>Save</button>
								<button id="cancel-pdf" type="button" class="btn btn-danger d-none rounded-circle btn-icon btn-sm"><i class="fa fa-trash"></i></button>
							</div>
						</form>
					</div>
					<div class="tab-pane p-3 border border-top-0 rounded-bottom" id="from-external-url" role="tabpanel" aria-labelledby="from-external-url-tab">
						<form id="form-external-link">
							<div class="input-group">
								<div class="input-group-text rounded-right-0"><i class="fa fa-link" aria-hidden="true"></i></div>
								<input type="url" name="url_link" id="url-link" class="form-control" placeholder="Url">
								<span class="invalid-feedback">Name document can't be empty</span>
							</div>
							<button type="button" class="btn btn-primary mt-3 save-external-link"><i class="fa fa-save"></i>Save</button>
						</form>
					</div>
					<div class="tab-pane p-3 border border-top-0 rounded-bottom" id="from-url" role="tabpanel" aria-labelledby="from-url-tab">
						<form id="form-youtube-video">
							<div class="input-group">
								<div class="input-group-text rounded-right-0">https://youtube.com/embed/</div>
								<input type="text"  name="video_link" id="video-link" class="form-control" placeholder="Video Id">
							</div>
							<button type="button" class="btn btn-primary mt-3 save-video-youtube"><i class="fa fa-save"></i>Save</button>
						</form>
					</div>
				</div>
			</div>
		</div>
			
		`)

		// $('.save,.save-sub-folder,.update-files')
		// 	.removeClass('save-sub-folder')
		// 	.removeClass('update-files')
		// 	.removeClass('save')
		// 	.addClass('save-files').html('<i class="fa fa-upload"></i> Upload File')
	})


	$(document).on('click', '.edit-file', function() {
		const id = $(this).data('id')
		$('#modalId .modal-title').text('Upload Files')
		$('#modalId').modal('show')
		$('#modalId .modal-dialog').addClass('modal-lg')
		$('#modalId .modal-body').html('')

		$.getJSON(siteurl + active_controller + 'edit_file/' + id, function(result) {
			var data = result.data
			var d = ''

			if (!data.document) {
				d = 'd-none'
			}

			$('#modalId .modal-body').html(`
			<form id="form-header" class="mb-4">
				<input type="hidden" name="id" class="form-control mb-3" value="` + data.id + `" aria-describedby="helpId">
				<div class="row">
					<div class="col-md-2">Name</div>
					<div class="col-md-10">
						<div class="form flex-grow-1">
							<input type="text" name="name-file" id="name-file" class="form-control mb-3" value="` + data.name + `" placeholder="Document Name" aria-describedby="helpId">
							<span class="invalid-feedback">Name document can't be empty</span>
						</div>
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-md-2"></div>
				<div class="col-md-10">
				
					<!-- Nav tabs -->
					<ul class="nav nav-tabs" id="tab-upload" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="upload-document-tab" data-toggle="tab" data-target="#upload-document" type="button" role="tab" aria-controls="upload-document" aria-selected="false">Upload Document</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="from-external-url-tab" data-toggle="tab" data-target="#from-external-url" type="button" role="tab" aria-controls="from-url" aria-selected="true">External Link <i class="ml-2 fa fa-link text-primary" aria-hidden="true"></i></button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="from-url-tab" data-toggle="tab" data-target="#from-url" type="button" role="tab" aria-controls="from-url" aria-selected="true">From YouTube <i class="fab fa-youtube ml-1 text-danger"></i></button>
						</li>
					</ul>
			
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active p-3 border border-top-0 rounded-bottom" id="upload-document" role="tabpanel" aria-labelledby="upload-file-tab">
							<form id="form-document" enctype="multipart/form-data">
								<div class="dropzone-wrapper mr-2 d-flex flex-column justify-content-center align-items-center" style="width: 100%;height:310px">
									<div class="dropzone-desc">
										<i class="fa fa-upload"></i>
										<p>Choose an image file or drag it here.</p>
									</div>
									<input type="file" id="pdf-file" name="documents" accept="application/pdf" class="dropzone dropzone-1" />
									<canvas id="pdf-preview" width="250"></canvas>
									<div class="for-delete"></div>
									<input type="hidden" name="old_file" class="form-control mb-3" value="` + data.document + `" aria-describedby="helpId">
									<input id="remove-document" name="remove-document" type="hidden">
								</div>
								<span class="invalid-feedback">File or Document can't be empty</span>
								<div class="d-flex justify-content-between mt-3">
									<button type="button" class="btn btn-primary save-files"><i class="fa fa-save"></i>Save</button>
									<button id="cancel-pdf" type="button" class="btn btn-danger d-none rounded-circle btn-icon btn-sm"><i class="fa fa-trash"></i></button>
									<button id="remove-file" type="button" class="btn ` + d + ` btn-danger rounded-circle btn-icon btn-sm"><i class="fa fa-trash"></i></button>
								</div>
							</form>
						</div>
						<div class="tab-pane p-3 border border-top-0 rounded-bottom" id="from-external-url" role="tabpanel" aria-labelledby="from-external-url-tab">
							<form id="form-external-link">
								<div class="input-group">
									<div class="input-group-text rounded-right-0"><i class="fa fa-link" aria-hidden="true"></i></div>
									<input type="url" name="url_link" id="url-link" class="form-control" placeholder="Url"  value="` + (data.url_link !== null ? data.url_link : '') + `" >
									<span class="invalid-feedback">Name document can't be empty</span>
								</div>
								<button type="button" class="btn btn-primary mt-3 save-external-link"><i class="fa fa-save"></i>Save</button>
							</form>
						</div>
						<div class="tab-pane p-3 border border-top-0 rounded-bottom" id="from-url" role="tabpanel" aria-labelledby="from-url-tab">
							<form id="form-youtube-video">
								<div class="input-group">
									<div class="input-group-text rounded-right-0">https://youtube.com/embed/</div>
									<input type="text"  name="video_link" id="video-link" class="form-control" placeholder="Video Id"  value="` + (data.video_link !== null ? data.video_link : '') + `" >
								</div>
								<button type="button" class="btn btn-primary mt-3 save-video-youtube"><i class="fa fa-save"></i>Save</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			`)

			const url = siteurl + 'directory/MATERI/' + data.company_id + '/' + data.document;
			if (data.document) {
				fetch(url)
					.then((res) => res.blob())
					.then((myBlob) => {
						console.log(myBlob);
						// logs: Blob { size: 1024, type: "image/jpeg" }
						myBlob.name = 'pdf-file.pdf';
						myBlob.lastModified = new Date();
						console.log(myBlob instanceof File);
						// logs: false
						_OBJECT_URL = URL.createObjectURL(myBlob)
						console.log(_OBJECT_URL);
						showPDF(_OBJECT_URL);
					});
			}
			// $('.save,.save-sub-folder,.save-files')
			// 	.removeClass('save-sub-folder')
			// 	.removeClass('save-files')
			// 	.removeClass('save')
			// 	.addClass('update-files').html('<i class="fa fa-upload"></i> Upload File')
		});
	})

	$(document).on('click', '.save-files', function() {
		const name = $('#name-file').val()
		const document = $('#pdf-file').val()
		const btn = $(this)
		if (!name) {
			$('#name-file').addClass('is-invalid')
			return false;
		}
		$('#name-file').removeClass('is-invalid')

		if (!document) {
			$('#pdf-file').addClass('is-invalid')
			Swal.fire('Warning!', "File or Document can't be empty. Please upload document first.", 'warning', 3000)
			return false;
		}
		$('#pdf-file').removeClass('is-invalid')
		let formData = new FormData($('#form-header')[0])
		let formExternalLink = new FormData($('#form-document')[0])
		for (var pair of formExternalLink.entries()) {
			formData.append(pair[0], pair[1]);
		}

		// const formdata = new FormData($('#form-upload')[0])
		$.ajax({
			url: siteurl + active_controller + 'save_document',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i> Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-save" aria-hidden="true"></i> Save').prop('disabled', false)
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire("Success!", result.msg, "success", 3000).then(function() {
						location.reload()
					})
				} else {
					Swal.fire("Warning!", result.msg, "warning", 3000)
				}
			},
			error: function(result) {
				Swal.fire("Error!", "Server time out.", "error", 3000)

			}
		})
	})

	$(document).on('click', '.update-files', function() {
		const name = $('#name-file').val()
		const document = $('#pdf-file').val()
		const btn = $(this)
		if (!name) {
			$('#name-file').addClass('is-invalid')
			return false;
		}

		$('#name-file').removeClass('is-invalid')
		let remove_document = $('#remove-document').val()

		if (!document && (remove_document == 'x')) {
			$('#pdf-file').addClass('is-invalid')
			Swal.fire('Warning!', "File or Document can't be empty. Please upload document first.", 'warning', 3000)
			return false;
		}

		$('#pdf-file').removeClass('is-invalid')
		let formData = new FormData($('#form-header')[0])
		let formExternalLink = new FormData($('#form-document')[0])
		for (var pair of formExternalLink.entries()) {
			formData.append(pair[0], pair[1]);
		}
		$.ajax({
			url: siteurl + active_controller + 'save_document',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i> Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-upload" aria-hidden="true"></i> Upload File').prop('disabled', false)
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire("Success!", result.msg, "success", 3000).then(function() {
						location.reload()
					})
				} else {
					Swal.fire("Warning!", result.msg, "warning", 3000)
				}
			},
			error: function(result) {
				Swal.fire("Error!", "Server time out.", "error", 3000)

			}
		})
	})

	$(document).on('click', '.view-file', function() {
		const id = $(this).data('id')
		$('#modalView .modal-title').text('View Files')
		$('#modalView').modal('show')
		$('#modalView .modal-dialog')
		$('#modalView .modal-body').load(siteurl + active_controller + 'view_file/' + id)
	})

	$(document).on('click', '.delete-file', function() {
		const id = $(this).data('id')
		Swal.fire({
			title: 'Confirm',
			text: 'Are sure you want to delete this file?',
			icon: 'question',
			showCancelButton: true,
		}).then((value) => {
			if (value.isConfirmed) {
				$.ajax({
					url: siteurl + active_controller + 'delete_file',
					dataType: 'JSON',
					type: 'POST',
					data: {
						id
					},
					success: function(result) {
						if (result.status == 1) {
							Swal.fire("Success!", result.msg, "success", 3000).then(function() {
								location.reload()
							})
						} else {
							Swal.fire("Warning!", result.msg, "warning", 3000)
						}
					},
					error: function(result) {
						Swal.fire("Error!", "Server time out.", "error", 3000)

					}
				})
			}
		})
	})

	/* Save External Link */

	$(document).on('click', '.save-external-link', function() {
		const name = $('#name-file').val()
		const url = $('#url-link').val()
		const btn = $(this)

		$('#name-file').removeClass('is-invalid')
		if (!name) {
			$('#name-file').addClass('is-invalid')
			return false;
		}

		$('#url-link').removeClass('is-invalid')
		if (!url) {
			$('#url-link').addClass('is-invalid')
			return false;
		}

		let formData = new FormData($('#form-header')[0])
		let formExternalLink = new FormData($('#form-external-link')[0])
		for (var pair of formExternalLink.entries()) {
			formData.append(pair[0], pair[1]);
		}

		$.ajax({
			url: siteurl + active_controller + 'save_document',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i>Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-save" aria-hidden="true"></i>Save').prop('disabled', false)
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire("Success!", result.msg, "success", 3000).then(function() {
						location.reload()
					})
				} else {
					Swal.fire("Warning!", result.msg, "warning", 3000)
				}
			},
			error: function(result) {
				Swal.fire("Error!", "Server time out.", "error", 3000)

			}
		})
	})

	$(document).on('click', '.save-video-youtube', function() {
		const name = $('#name-file').val()
		const video = $('#video-link').val()
		const btn = $(this)

		$('#name-file').removeClass('is-invalid')
		if (!name) {
			$('#name-file').addClass('is-invalid')
			return false;
		}

		$('#video-link').removeClass('is-invalid')
		if (!video) {
			$('#video-link').addClass('is-invalid')
			return false;
		}

		let formData = new FormData($('#form-header')[0])
		let formVideo = new FormData($('#form-youtube-video')[0])
		for (var pair of formVideo.entries()) {
			formData.append(pair[0], pair[1]);
		}

		$.ajax({
			url: siteurl + active_controller + 'save_document',
			type: 'POST',
			dataType: 'JSON',
			data: formData,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border-sm" aria-hidden="true"></i>Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-save" aria-hidden="true"></i>Save').prop('disabled', false)
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire("Success!", result.msg, "success", 3000).then(function() {
						location.reload()
					})
				} else {
					Swal.fire("Warning!", result.msg, "warning", 3000)
				}
			},
			error: function(result) {
				Swal.fire("Error!", "Server time out.", "error", 3000)

			}
		})
	})


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
		console.log(page_no);
		console.log(_PDF_DOC.getPage(page_no));
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
		// console.log($(this)[0].files[0]);
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
		$("#remove-file").removeClass('d-none');
		// $("#upload-button").css('display', 'inline-block');

		// Show the PDF preview loader
		$("#pdf-loader").css('display', 'inline-block');

		// object url of PDF 
		console.log(file);
		_OBJECT_URL = URL.createObjectURL(file)

		// send the object url of the pdf to the PDF preview function
		showPDF(_OBJECT_URL);
	});

	/* Reset file input */
	$(document).on('click', "#cancel-pdf", function() {
		// show upload dialog button
		$("#upload-dialog").css('display', 'inline-block');

		// reset to no selection
		$("#pdf-file").val('');

		// hide elements that are not required
		$("#pdf-name").css('display', 'none');
		$("#pdf-preview").css('display', 'none');
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
		$("#pdf-preview").css('display', 'none');
		$("#pdf-loader").css('display', 'none');
		$("#cancel-pdf").addClass('d-none');
		$("#upload-button").css('display', 'none');
	});

	/* Upload file to server */
	$(document).on('click', "#upload-button", function() {
		// AJAX request to server
		alert('This will upload file to server');
	});

	/* video */
	$(document).on('change', '#video-file', function() {
		let file = $(this)[0].files[0];

		// allowed MIME types
		var mime_types = ['video/mp4'];

		// Validate whether PDF
		if (mime_types.indexOf(file.type) == -1) {
			Swal.fire('Warning', 'Error : Incorrect file type', 'warning', 3000)
			return;
		}

		// validate file size
		if (file.size > 50 * 1024 * 1024) {
			Swal.fire('Warning', 'Error : Exceeded size 50MB', 'warning', 3000)
			return;
		}

		let blobURL = URL.createObjectURL(file);
		$("#video-preview").attr('src', blobURL + "#t=10").removeClass('d-none');
		$('#btn-play-pause').html('<button type="button" id="play-pause" class="btn btn-sm btn-primary btn-icon"><i id="icon-play-pause" class="fa fa-play" aria-hidden="true"></i></button>')
		$('#remove-video').removeClass('d-none')
	})

	$('#play-pause').click(function() {
		ChangeButtonText();
	});

	function ChangeButtonText() {
		let video = $('#video-file')[0]
		console.log(video)
		if (video.video.paused) {
			video.video.play();
			$("#icon-play-pause").addClass('fa fa-stop');
		} else {
			video.video.pause();
			// video.stopListen();
			$("#icon-play-pause").addClass('fa fa-play');
		}
	}

	$(function() {
		$("#myImg1").hover(
			function() {
				$(this).attr("src", "assets/images/dashboard/folder-file.gif");
			},
			function() {
				$(this).attr("src", "assets/images/dashboard/folder-file.png");
			}
		);
	});
</script>