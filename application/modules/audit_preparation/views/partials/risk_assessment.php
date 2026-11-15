<?php
$default_subjects = isset($default_subjects) ? $default_subjects : [
	'Planning',
	'Resources',
	'Selection of audit team',
	'Communication',
	'Implementation',
	'Documented information',
	'Monitoring, review and improvement',
	'Auditee cooperation and availability',
	'Audit methods, remote and ICT',
	'Information security and confidentiality',
	'Peluang penggunaan data ERP',
];
?>
<div class="risk-assessment-section">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h5 class="font-weight-bold m-0"><i class="fa fa-shield-alt text-primary mr-2"></i>Audit Risk Assessment</h5>
		<button type="button" class="btn btn-sm btn-primary" id="btn-add-risk">
			<i class="fa fa-plus mr-1"></i> Add Row
		</button>
	</div>

	<div class="table-responsive">
		<table id="table-risk" class="table table-sm table-bordered table-hover">
			<thead class="table-light text-center">
				<tr>
					<th width="40">No</th>
					<th width="22%">Subject risk</th>
					<th width="24%">Risiko/ Oppotrunity</th>
					<th width="24%">Mitigasi</th>
					<th width="16%">PIC</th>
					<th width="12%">Due date</th>
					<th width="40">Action</th>
				</tr>
			</thead>
			<tbody id="tbody-risk">
				<?php if (!empty($risk_assessments)) : ?>
					<?php foreach ($risk_assessments as $k => $risk) : ?>
						<?php $is_fixed = in_array($risk->subject_risk, $default_subjects); ?>
						<tr class="risk-row <?= $is_fixed ? 'risk-row-fixed' : ''; ?>">
							<td class="text-center align-middle risk-row-number"><?= $k + 1; ?></td>
							<td>
								<input type="hidden" name="risk_id[]" value="<?= isset($risk->id) ? $risk->id : ''; ?>">
								<?php if ($is_fixed) : ?>
									<input type="text" name="risk_subject[]" class="form-control form-control-sm bg-light text-dark font-weight-bold" value="<?= htmlspecialchars($risk->subject_risk); ?>" readonly title="Item Subject Tetap (Fixed)">
								<?php else : ?>
									<input type="text" name="risk_subject[]" class="form-control form-control-sm" placeholder="Subject risk..." value="<?= htmlspecialchars($risk->subject_risk); ?>">
								<?php endif; ?>
							</td>
							<td>
								<textarea name="risk_opportunity[]" class="form-control form-control-sm" rows="2" placeholder="Risiko/ Opportunity..."><?= isset($risk->risk_opportunity) ? htmlspecialchars($risk->risk_opportunity) : ''; ?></textarea>
							</td>
							<td>
								<textarea name="risk_mitigation[]" class="form-control form-control-sm" rows="2" placeholder="Mitigasi..."><?= isset($risk->mitigation) ? htmlspecialchars($risk->mitigation) : ''; ?></textarea>
							</td>
							<td>
								<select name="risk_pic_id[]" class="form-control form-control-sm select2-risk-pic" data-placeholder="Select PIC">
									<option value=""></option>
									<?php if (!empty($users)) foreach ($users as $u) : ?>
										<option value="<?= $u->id_user; ?>" <?= (isset($risk->pic_id) && $u->id_user == $risk->pic_id) ? 'selected' : ''; ?>><?= htmlspecialchars($u->full_name); ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td>
								<input type="date" name="risk_due_date[]" class="form-control form-control-sm" value="<?= isset($risk->due_date) ? $risk->due_date : ''; ?>">
							</td>
							<td class="text-center align-middle">
								<?php if ($is_fixed) : ?>
									<button type="button" class="btn btn-xs btn-icon btn-secondary" disabled title="Item tetap tidak dapat dihapus"><i class="fa fa-lock"></i></button>
								<?php else : ?>
									<button type="button" class="btn btn-xs btn-icon btn-danger btn-delete-risk" title="Delete"><i class="fa fa-trash"></i></button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
	<div class="d-flex justify-content-between align-items-center mt-2">
		<button type="button" class="btn btn-sm btn-primary" id="btn-add-risk-bottom">
			<i class="fa fa-plus mr-1"></i> Add Row
		</button>
		<small class="text-muted" id="risk-count-info">0 baris risk assessment</small>
	</div>
</div>

<script>
$(document).ready(function() {
	var userOptionsHtml = '<option value=""></option>';
	<?php if (!empty($users)) : ?>
		<?php foreach ($users as $u) : ?>
			userOptionsHtml += '<option value="<?= $u->id_user; ?>"><?= addslashes(htmlspecialchars($u->full_name)); ?></option>';
		<?php endforeach; ?>
	<?php endif; ?>

	function initRiskSelect2(context) {
		var $elements = context ? $(context).find('.select2-risk-pic') : $('.select2-risk-pic');
		$elements.each(function() {
			if (!$(this).hasClass('select2-hidden-accessible')) {
				$(this).select2({
					placeholder: "Select PIC",
					allowClear: true,
					width: "100%"
				});
			}
		});
	}

	function reindexRiskRows() {
		var count = 0;
		var fixedCount = 0;
		$('#tbody-risk .risk-row').each(function(index) {
			$(this).find('.risk-row-number').text(index + 1);
			count++;
			if ($(this).hasClass('risk-row-fixed')) {
				fixedCount++;
			}
		});
		var infoText = count + ' baris risk assessment';
		if (fixedCount > 0 && count > fixedCount) {
			infoText += ' (' + fixedCount + ' fix, ' + (count - fixedCount) + ' tambahan)';
		}
		$('#risk-count-info').text(infoText);
	}

	function addRiskRow() {
		var rowHtml = '<tr class="risk-row">' +
			'<td class="text-center align-middle risk-row-number"></td>' +
			'<td>' +
				'<input type="hidden" name="risk_id[]" value="">' +
				'<input type="text" name="risk_subject[]" class="form-control form-control-sm" placeholder="Subject risk...">' +
			'</td>' +
			'<td>' +
				'<textarea name="risk_opportunity[]" class="form-control form-control-sm" rows="2" placeholder="Risiko/ Opportunity..."></textarea>' +
			'</td>' +
			'<td>' +
				'<textarea name="risk_mitigation[]" class="form-control form-control-sm" rows="2" placeholder="Mitigasi..."></textarea>' +
			'</td>' +
			'<td>' +
				'<select name="risk_pic_id[]" class="form-control form-control-sm select2-risk-pic" data-placeholder="Select PIC">' +
					userOptionsHtml +
				'</select>' +
			'</td>' +
			'<td>' +
				'<input type="date" name="risk_due_date[]" class="form-control form-control-sm">' +
			'</td>' +
			'<td class="text-center align-middle">' +
				'<button type="button" class="btn btn-xs btn-icon btn-danger btn-delete-risk" title="Delete"><i class="fa fa-trash"></i></button>' +
			'</td>' +
		'</tr>';

		var $newRow = $(rowHtml);
		$('#tbody-risk').append($newRow);
		initRiskSelect2($newRow);
		reindexRiskRows();
		$newRow.find('input[name="risk_subject[]"]').focus();
	}

	// Add row click handlers
	$(document).on('click', '#btn-add-risk, #btn-add-risk-bottom', function(e) {
		e.preventDefault();
		addRiskRow();
	});

	// Delete row handler
	$(document).on('click', '.btn-delete-risk', function(e) {
		e.preventDefault();
		var $row = $(this).closest('.risk-row');
		if ($row.hasClass('risk-row-fixed')) {
			return false;
		}
		$row.remove();
		reindexRiskRows();
	});

	// Only initialize immediately when this tab is already visible.
	// Hidden-tab Select2 instances are initialized by form.php on shown.bs.tab.
	if ($('#tab-risk').hasClass('active') || $('#tab-risk').is(':visible')) {
		initRiskSelect2();
	}
	reindexRiskRows();
});
</script>
