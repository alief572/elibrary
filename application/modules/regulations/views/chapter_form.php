<div class="container-fluid">
	<div class="mb-5">
		<label for="chapter" class="col-form-label font-weight-bold">Pasal</label>
		<input type="text" name="list[chapter]" class="form-control" id="chapter" placeholder="Pasal" />
	</div>
	<div class="mb-5">
		<label for="chapter" class="font-weight-bold">Descripsi</label>
		<textarea name="list[desc_indo]" class="form-control textarea" id="desc_indo" rows="10" placeholder="Description"></textarea>
	</div>
</div>

<script>
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

	tinymce.init({
		selector: 'textarea.textarea',
		height: 500,
		resize: true,
		plugins: 'preview importcss  searchreplace autolink autosave save ' +
			'directionality visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
		toolbar: 'undo redo | blocks | ' +
			'bold italic backcolor forecolor | alignleft aligncenter ' +
			'alignright alignjustify | template codesample bullist numlist outdent indent | link image ' +
			'removeformat | help',
		content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
		// 	content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
	});
</script>