<div id="msg-upload" style="display: none;"></div>
<div class="row">
	<div class="col-md-6 offset-3">
		<form enctype="multipart/form-data" id="dataUpload" class="text-center">
			<img id="preview" src="<?= base_url('assets/img/'); ?><?= ($picture->pictures) ? $picture->pictures : 'default.png'; ?>" alt="" width="" class="mb-2 img-thumbnail img-fluid">
			<input type="file" class="d-none" id="picture" name="picture">
			<input type="hidden" name="old_picture" id="old_picture" value="<?= $picture->pictures; ?>">
			<input type="hidden" name="id" id="id_picture" value="<?= ($picture->id) ? $picture->id : ''; ?>">
			<button type="button" class="btn btn-success btn-block" onclick="$('#picture').click()">
				<i class="fa fa-upload mr-1"></i>Upload Picture
			</button>
		</form>
	</div>
</div>