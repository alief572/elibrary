<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="card card-stretch shadow card-custom">
				<div class="card-header">
					<h2 class="mt-5"><i class="<?= $icon; ?> text-primary mr-2"></i><?= $title; ?></h2>
				</div>
				<div class="card-body">
					<form id="formEmail">
						<div class="form-group row">
							<label class="col-md-3 col-form-label font-weight-bold">SMTP Host</label>
							<div class="col-md-9">
								<input type="text" name="smtp_host" class="form-control" placeholder="ssl://smtp.googlemail.com" value="<?= isset($settings['smtp_host']) ? htmlspecialchars($settings['smtp_host']) : ''; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-3 col-form-label font-weight-bold">SMTP Port</label>
							<div class="col-md-9">
								<input type="text" name="smtp_port" class="form-control" placeholder="465" value="<?= isset($settings['smtp_port']) ? htmlspecialchars($settings['smtp_port']) : ''; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-3 col-form-label font-weight-bold">SMTP Email User</label>
							<div class="col-md-9">
								<input type="email" name="smtp_user" class="form-control" placeholder="contoh: admin@perusahaan.com" value="<?= isset($settings['smtp_user']) ? htmlspecialchars($settings['smtp_user']) : ''; ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-3 col-form-label font-weight-bold">SMTP App Password</label>
							<div class="col-md-9">
								<div class="input-group">
									<input type="password" name="smtp_pass" id="smtp_pass" class="form-control" placeholder="16 karakter khusus" value="">
									<div class="input-group-append">
										<button type="button" class="btn btn-outline-secondary" id="togglePass"><i class="fa fa-eye"></i></button>
									</div>
								</div>
								<small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-3 col-form-label font-weight-bold">Enkripsi (Crypto)</label>
							<div class="col-md-9">
								<select name="smtp_crypto" class="form-control">
									<option value="ssl" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'ssl') ? 'selected' : ''; ?>>SSL (Port 465)</option>
									<option value="tls" <?= (isset($settings['smtp_crypto']) && $settings['smtp_crypto'] == 'tls') ? 'selected' : ''; ?>>TLS (Port 587)</option>
								</select>
							</div>
						</div>

						<div class="form-group row mt-5">
							<div class="col-md-9 offset-md-3 text-right">
								<button type="button" class="btn btn-warning mr-2" id="btnTestEmail"><i class="fa fa-paper-plane mr-1"></i> Kirim Test Email</button>
								<button type="button" class="btn btn-success" id="btnSave"><i class="fa fa-save mr-1"></i> Simpan Pengaturan</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	// Toggle password visibility
	$('#togglePass').on('click', function() {
		var input = $('#smtp_pass');
		if (input.attr('type') === 'password') {
			input.attr('type', 'text');
			$(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
		} else {
			input.attr('type', 'password');
			$(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
		}
	});

	// Save settings
	$('#btnSave').on('click', function() {
		var btn = $(this);
		Swal.fire({
			title: 'Simpan Pengaturan Email?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Ya, Simpan',
			cancelButtonText: 'Batal'
		}).then(function(result) {
			if (result.isConfirmed) {
				$.ajax({
					url: siteurl + 'setting_email/save',
					data: $('#formEmail').serialize(),
					type: 'POST',
					dataType: 'JSON',
					beforeSend: function() { btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...'); },
					complete: function() { btn.prop('disabled', false).html('<i class="fa fa-save mr-1"></i> Simpan Pengaturan'); },
					success: function(res) {
						if (res.status == 1) {
							Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 2000 });
						} else {
							Swal.fire({ title: 'Warning!', icon: 'warning', text: res.msg });
						}
					},
					error: function() {
						Swal.fire({ title: 'Error!', icon: 'error', text: 'Server error!' });
					}
				});
			}
		});
	});

	// Test email
	$('#btnTestEmail').on('click', function() {
		var btn = $(this);
		Swal.fire({
			title: 'Kirim Test Email',
			input: 'email',
			inputLabel: 'Masukkan email tujuan',
			inputPlaceholder: 'email@contoh.com',
			showCancelButton: true,
			confirmButtonText: 'Kirim',
			cancelButtonText: 'Batal',
			inputValidator: function(value) {
				if (!value) return 'Email wajib diisi!';
			}
		}).then(function(result) {
			if (result.isConfirmed) {
				btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Mengirim...');
				$.ajax({
					url: siteurl + 'setting_email/test',
					data: { test_email: result.value },
					type: 'POST',
					dataType: 'JSON',
					complete: function() { btn.prop('disabled', false).html('<i class="fa fa-paper-plane mr-1"></i> Kirim Test Email'); },
					success: function(res) {
						if (res.status == 1) {
							Swal.fire({ title: 'Success!', icon: 'success', text: res.msg, timer: 3000 });
						} else {
							Swal.fire({ title: 'Warning!', icon: 'warning', text: res.msg });
						}
					},
					error: function() {
						Swal.fire({ title: 'Error!', icon: 'error', text: 'Server error!' });
					}
				});
			}
		});
	});
});
</script>
