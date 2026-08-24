<form id="form-holiday" class="form-horizontal">
	<?php if (!empty($data->id)) : ?>
		<input type="hidden" name="id" value="<?= $data->id; ?>">
	<?php endif; ?>

	<div class="form-group mb-4">
		<label class="font-weight-bold text-dark" for="holiday_date">Tanggal Libur <span class="text-danger">*</span></label>
		<input type="date" name="holiday_date" id="holiday_date" class="form-control" value="<?= !empty($data->holiday_date) ? $data->holiday_date : date('Y-m-d'); ?>" required>
	</div>

	<div class="form-group mb-4">
		<label class="font-weight-bold text-dark" for="holiday_name">Nama Hari Libur <span class="text-danger">*</span></label>
		<input type="text" name="holiday_name" id="holiday_name" class="form-control" placeholder="Contoh: Hari Kemerdekaan RI" value="<?= !empty($data->holiday_name) ? htmlspecialchars($data->holiday_name) : ''; ?>" required>
	</div>

	<div class="form-group mb-4">
		<label class="font-weight-bold text-dark" for="holiday_type">Tipe Libur</label>
		<select name="holiday_type" id="holiday_type" class="form-control">
			<option value="Nasional" <?= (!empty($data->holiday_type) && $data->holiday_type == 'Nasional') ? 'selected' : ''; ?>>Libur Nasional</option>
			<option value="Cuti Bersama" <?= (!empty($data->holiday_type) && $data->holiday_type == 'Cuti Bersama') ? 'selected' : ''; ?>>Cuti Bersama</option>
			<option value="Khusus" <?= (!empty($data->holiday_type) && $data->holiday_type == 'Khusus') ? 'selected' : ''; ?>>Libur Khusus / Internal Perusahaan</option>
		</select>
	</div>

	<div class="form-group mb-0">
		<label class="font-weight-bold text-dark" for="descriptions">Keterangan (Opsional)</label>
		<textarea name="descriptions" id="descriptions" class="form-control" rows="2" placeholder="Catatan tambahan mengenai hari libur ini..."><?= !empty($data->descriptions) ? htmlspecialchars($data->descriptions) : ''; ?></textarea>
	</div>
</form>
