<style>
	*,
	body {
		margin: 0;
		padding: 0;
	}
</style>
<iframe id="file" src="<?= base_url() ; ?>/directory/COMPLIANCE/<?= $comp ; ?>/<?= $file; ?>#toolbar=0&navpanes=0&scrollbar=0" oncontextmenu="return false;" frameborder="0" width="100%" height="100%">
	<script>
		window.frames["file"].contentDocument.oncontextmenu = function() {
			return false;
		}
	</script>
</iframe>

<script language="JavaScript">
	document.addEventListener("contextmenu", function(e) {
		e.preventDefault();
	}, false);
</script>