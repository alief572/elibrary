<div class="opportunity-section">
	<h5 class="font-weight-bold mb-4">Potensi Peluang / Masalah</h5>

	<!-- Input Form -->
	<div class="row mb-3">
		<div class="col-md-4">
			<label class="font-weight-bold">Procedure/Process <span class="text-danger">*</span></label>
			<select id="select_procedure" class="form-control select2" data-placeholder="Select Procedure/Process">
				<option></option>
				<?php if (!empty($procedures)) foreach ($procedures as $proc) : ?>
					<option value="<?= $proc->id; ?>"><?= $proc->name; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-6">
			<label class="font-weight-bold">Description <span class="text-danger">*</span></label>
			<textarea id="opportunity_description" class="form-control required" maxlength="1000" rows="3" placeholder="Enter opportunity/problem description (max 1000 characters)"></textarea>
		</div>
		<div class="col-md-2 d-flex align-items-end">
			<button type="button" id="btn-add-opportunity" class="btn btn-primary btn-block">
				<i class="fa fa-plus mr-1"></i> Add
			</button>
		</div>
	</div>

	<!-- Opportunities Table -->
	<div class="table-responsive">
		<table id="table-opportunities" class="table table-bordered table-sm table-hover">
			<thead class="table-light">
				<tr class="text-center">
					<th width="40">No</th>
					<th>Process/Procedure</th>
					<th>Description</th>
					<th width="100">Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($opportunities)) foreach ($opportunities as $k => $opp) : ?>
					<tr data-row="<?= $k; ?>">
						<td class="text-center row-number"><?= $k + 1; ?></td>
						<td><?= isset($opp->procedure_name) ? $opp->procedure_name : ''; ?></td>
						<td><?= isset($opp->description) ? $opp->description : ''; ?></td>
						<td class="text-center">
							<button type="button" class="btn btn-xs btn-icon btn-warning edit-opportunity" title="Edit">
								<i class="fa fa-edit"></i>
							</button>
							<button type="button" class="btn btn-xs btn-icon btn-danger delete-opportunity" title="Delete">
								<i class="fa fa-trash"></i>
							</button>
							<input type="hidden" name="procedure_id[]" value="<?= $opp->procedure_id; ?>">
							<input type="hidden" name="opportunity_desc[]" value="<?= htmlspecialchars($opp->description); ?>">
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<small class="text-muted">Maximum 50 entries allowed.</small>
</div>

<script>
$(document).ready(function() {
	// Initialize Select2 for procedure dropdown
	$('#select_procedure').select2({
		allowClear: true,
		width: '100%',
		placeholder: 'Select Procedure/Process'
	});

	var opportunityEditIndex = -1;
	var maxOpportunities = 50;

	// Add opportunity entry
	$(document).on('click', '#btn-add-opportunity', function() {
		var procedureId = $('#select_procedure').val();
		var procedureName = $('#select_procedure option:selected').text();
		var description = $.trim($('#opportunity_description').val());

		// Validate
		var hasError = false;
		if (!procedureId || procedureId == '') {
			$('#select_procedure').next('.select2-container').find('.select2-selection').addClass('is-invalid border-danger');
			hasError = true;
		} else {
			$('#select_procedure').next('.select2-container').find('.select2-selection').removeClass('is-invalid border-danger');
		}

		if (description == '') {
			$('#opportunity_description').addClass('is-invalid');
			hasError = true;
		} else {
			$('#opportunity_description').removeClass('is-invalid');
		}

		if (hasError) return false;

		// Check max limit
		var currentCount = $('#table-opportunities tbody tr').length;
		if (opportunityEditIndex === -1 && currentCount >= maxOpportunities) {
			Swal.fire({
				title: 'Limit Reached',
				icon: 'info',
				text: 'Maximum ' + maxOpportunities + ' entries allowed.'
			});
			return false;
		}

		if (opportunityEditIndex >= 0) {
			// Update existing row
			var row = $('#table-opportunities tbody tr').eq(opportunityEditIndex);
			row.find('td:eq(1)').text(procedureName);
			row.find('td:eq(2)').text(description);
			row.find('input[name="procedure_id[]"]').val(procedureId);
			row.find('input[name="opportunity_desc[]"]').val(description);
			opportunityEditIndex = -1;
			$('#btn-add-opportunity').html('<i class="fa fa-plus mr-1"></i> Add');
		} else {
			// Append new row
			var rowNum = currentCount + 1;
			var newRow = '<tr>' +
				'<td class="text-center row-number">' + rowNum + '</td>' +
				'<td>' + escapeHtml(procedureName) + '</td>' +
				'<td>' + escapeHtml(description) + '</td>' +
				'<td class="text-center">' +
					'<button type="button" class="btn btn-xs btn-icon btn-warning edit-opportunity" title="Edit"><i class="fa fa-edit"></i></button> ' +
					'<button type="button" class="btn btn-xs btn-icon btn-danger delete-opportunity" title="Delete"><i class="fa fa-trash"></i></button>' +
					'<input type="hidden" name="procedure_id[]" value="' + procedureId + '">' +
					'<input type="hidden" name="opportunity_desc[]" value="' + escapeHtml(description) + '">' +
				'</td>' +
			'</tr>';
			$('#table-opportunities tbody').append(newRow);
		}

		// Reset inputs
		$('#select_procedure').val(null).trigger('change');
		$('#opportunity_description').val('');
	});

	// Edit opportunity entry
	$(document).on('click', '.edit-opportunity', function() {
		var row = $(this).closest('tr');
		var procedureId = row.find('input[name="procedure_id[]"]').val();
		var description = row.find('input[name="opportunity_desc[]"]').val();

		$('#select_procedure').val(procedureId).trigger('change');
		$('#opportunity_description').val(description);

		opportunityEditIndex = row.index();
		$('#btn-add-opportunity').html('<i class="fa fa-check mr-1"></i> Update');
	});

	// Delete opportunity entry
	$(document).on('click', '.delete-opportunity', function() {
		var row = $(this).closest('tr');
		Swal.fire({
			title: 'Delete Entry?',
			text: 'Are you sure you want to remove this entry?',
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Yes, Delete',
			cancelButtonText: 'Cancel'
		}).then(function(result) {
			if (result.isConfirmed) {
				row.remove();
				// Reset edit mode if the deleted row was being edited
				opportunityEditIndex = -1;
				$('#btn-add-opportunity').html('<i class="fa fa-plus mr-1"></i> Add');
				// Renumber rows
				$('#table-opportunities tbody tr').each(function(idx) {
					$(this).find('.row-number').text(idx + 1);
				});
			}
		});
	});

	// Helper function to escape HTML
	function escapeHtml(text) {
		var div = document.createElement('div');
		div.appendChild(document.createTextNode(text));
		return div.innerHTML;
	}
});
</script>
