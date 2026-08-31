<?php
if (!function_exists('render_doc_status_donut')) {
	function render_doc_status_donut($pub, $rev, $cor, $rvi = 0)
	{
		$total = $pub + $rev + $cor + $rvi;
		if ($total <= 0) $total = 1;

		$p_pct = $pub / $total;
		$r_pct = $rev / $total;
		$c_pct = $cor / $total;
		$rv_pct = $rvi / $total;

		$radius = 32;
		$circumference = 2 * M_PI * $radius;

		$p_dash = $p_pct * $circumference;
		$r_dash = $r_pct * $circumference;
		$c_dash = $c_pct * $circumference;
		$rv_dash = $rv_pct * $circumference;

		$p_offset = 0;
		$r_offset = -$p_dash;
		$c_offset = -($p_dash + $r_dash);
		$rv_offset = -($p_dash + $r_dash + $c_dash);

		return '
        <svg width="85" height="85" viewBox="0 0 85 85" style="transform: rotate(-90deg);">
            <circle cx="42.5" cy="42.5" r="' . $radius . '" fill="transparent" stroke="rgba(0,0,0,0.15)" stroke-width="10"></circle>
            <circle cx="42.5" cy="42.5" r="' . $radius . '" fill="transparent" stroke="#00e6a8" stroke-width="10" stroke-dasharray="' . $p_dash . ' ' . $circumference . '" stroke-dashoffset="' . $p_offset . '"></circle>
            <circle cx="42.5" cy="42.5" r="' . $radius . '" fill="transparent" stroke="#ffb800" stroke-width="10" stroke-dasharray="' . $r_dash . ' ' . $circumference . '" stroke-dashoffset="' . $r_offset . '"></circle>
            <circle cx="42.5" cy="42.5" r="' . $radius . '" fill="transparent" stroke="#ff5252" stroke-width="10" stroke-dasharray="' . $c_dash . ' ' . $circumference . '" stroke-dashoffset="' . $c_offset . '"></circle>
            <circle cx="42.5" cy="42.5" r="' . $radius . '" fill="transparent" stroke="#8950fc" stroke-width="10" stroke-dasharray="' . $rv_dash . ' ' . $circumference . '" stroke-dashoffset="' . $rv_offset . '"></circle>
        </svg>';
	}
}

if (!function_exists('render_compliance_donut')) {
	function render_compliance_donut($percentage = 85)
	{
		$radius = 36;
		$circumference = 2 * M_PI * $radius;
		$dash = ($percentage / 100) * $circumference;

		return '
        <div style="position: relative; display: flex; align-items: center; justify-content: center; width: 95px; height: 95px; margin: 0 auto;">
            <svg width="95" height="95" viewBox="0 0 95 95" style="transform: rotate(-90deg);">
                <circle cx="47.5" cy="47.5" r="' . $radius . '" fill="transparent" stroke="rgba(0,0,0,0.15)" stroke-width="9"></circle>
                <circle cx="47.5" cy="47.5" r="' . $radius . '" fill="transparent" stroke="#00e6a8" stroke-width="9" stroke-dasharray="' . $dash . ' ' . $circumference . '" stroke-linecap="round"></circle>
            </svg>
            <div style="position: absolute; font-size: 18px; font-weight: 800; color: #000000;">
                ' . $percentage . '%
            </div>
        </div>';
	}
}
?>

<style>
	.page-title-text {
		color: #ffffff !important;
		font-weight: 800;
		font-size: 24px;
		letter-spacing: 0.5px;
	}

	.section-header {
		font-size: 13px;
		font-weight: 700;
		letter-spacing: 1.2px;
		color: #ffffff;
		text-transform: uppercase;
		margin-bottom: 14px;
		margin-top: 28px;
	}

	.section-header:first-of-type {
		margin-top: 10px;
	}

	.dash-card {
		background-color: #868da8 !important;
		border-radius: 16px;
		padding: 22px 24px;
		height: 100%;
		position: relative;
		border: 1px solid rgba(255, 255, 255, 0.2);
		box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		transition: transform 0.2s ease, box-shadow 0.2s ease;
	}

	.dash-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
	}

	.card-label-top {
		font-size: 12px;
		font-weight: 700;
		color: #111111 !important;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.card-number-huge {
		font-size: 38px;
		font-weight: 800;
		color: #000000 !important;
		line-height: 1;
		margin-top: 14px;
		cursor: pointer;
		display: inline-block;
		transition: color 0.15s ease;
	}

	.card-number-huge:hover {
		color: #0033aa !important;
	}

	.card-icon-badge {
		width: 36px;
		height: 36px;
		border-radius: 10px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 16px;
		color: #ffffff !important;
	}

	.card-icon-badge i {
		color: #ffffff !important;
	}

	.badge-red {
		background-color: #f64e60;
		color: #ffffff !important;
	}

	.badge-blue {
		background-color: #3699ff;
		color: #ffffff !important;
	}

	.badge-yellow {
		background-color: #ffa800;
		color: #ffffff !important;
	}

	.legend-dot {
		width: 10px;
		height: 10px;
		border-radius: 50%;
		display: inline-block;
		margin-right: 8px;
	}

	.dot-teal {
		background-color: #00e6a8;
	}

	.dot-yellow {
		background-color: #ffb800;
	}

	.dot-red {
		background-color: #ff5252;
	}

	.dot-purple {
		background-color: #8950fc;
	}

	.status-legend-table {
		width: 100%;
		font-size: 13px;
	}

	.status-legend-table td {
		padding: 3px 0;
	}

	.status-label-text {
		color: #111111 !important;
		font-weight: 600;
	}

	.status-val-text {
		color: #000000 !important;
		font-weight: 800;
		text-align: right;
		cursor: pointer;
	}

	.status-val-text:hover {
		color: #0033aa !important;
		text-decoration: underline;
	}

	.task-item {
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 14px 0;
		border-bottom: 1px solid rgba(0, 0, 0, 0.12);
	}

	.task-item:last-child {
		border-bottom: none;
	}

	.task-left {
		display: flex;
		align-items: center;
	}

	.task-icon {
		width: 34px;
		height: 34px;
		border-radius: 8px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 14px;
		margin-right: 14px;
		color: #ffffff !important;
	}

	.task-icon i {
		color: #ffffff !important;
	}

	.task-text {
		font-size: 14px;
		font-weight: 700;
		color: #000000 !important;
	}

	.task-due {
		font-size: 12px;
		color: #222222 !important;
		font-weight: 600;
	}
</style>

<div class="content d-flex flex-column flex-column-fluid p-0">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container mt-5 mb-10">
			<div class="d-flex justify-content-between align-items-center mb-4">
				<h1 class="page-title-text mb-0 mt-0 pt-0 font-weight-bolder">DASHBOARD</h1>
			</div>

			<!-- SECTION 1: DOCUMENT CONTROL -->
			<div class="section-header">DOCUMENT CONTROL</div>
			<div class="row mb-5">
				<div class="col-md-3 col-sm-6 mb-4 mb-md-0">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">PROSEDUR</span>
							<div class="card-icon-badge badge-red">
								<i class="fa fa-file-alt text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('procedure', 'PUB')" title="Klik untuk lihat daftar Prosedur Published"><?= number_format($doc_control['procedur']['total']); ?></span>
							<div class="d-flex align-items-center mt-2" onclick="openDocModal('procedure', 'PUB')" style="cursor: pointer;">
								<span class="legend-dot dot-teal"></span>
								<span class="font-weight-bolder" style="font-size: 12px; color: #111111;">Status: Published</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 mb-4 mb-md-0">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">WORK INSTRUCTION</span>
							<div class="card-icon-badge badge-blue">
								<i class="fa fa-book text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('wi')" title="Klik untuk lihat daftar Work Instruction"><?= number_format($doc_control['wi']['total']); ?></span>
							<div class="d-flex align-items-center mt-2" onclick="openDocModal('wi')" style="cursor: pointer;">
								<span class="legend-dot dot-teal"></span>
								<span class="font-weight-bolder" style="font-size: 12px; color: #111111;">Status: Active (Non-DEL)</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6 mb-4 mb-md-0">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">FORM</span>
							<div class="card-icon-badge badge-yellow">
								<i class="fa fa-file-invoice text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('form')" title="Klik untuk lihat daftar Form"><?= number_format($doc_control['form']['total']); ?></span>
							<div class="d-flex align-items-center mt-2" onclick="openDocModal('form')" style="cursor: pointer;">
								<span class="legend-dot dot-teal"></span>
								<span class="font-weight-bolder" style="font-size: 12px; color: #111111;">Status: Active (Non-DEL)</span>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-6">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">RECORDS</span>
							<div class="card-icon-badge badge-info">
								<i class="fa fa-folder-open text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('records')" title="Klik untuk lihat daftar Records"><?= number_format($doc_control['records']['total']); ?></span>
							<div class="d-flex align-items-center mt-2" onclick="openDocModal('records')" style="cursor: pointer;">
								<span class="legend-dot dot-teal"></span>
								<span class="font-weight-bolder" style="font-size: 12px; color: #111111;">Status: Active (Non-DEL)</span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- SECTION 2: AUDIT, CAR, & COMPLIANCE -->
			<div class="section-header">AUDIT, CAR, & COMPLIANCE</div>
			<div class="row mb-5">
				<div class="col-md-3 mb-4 mb-md-0">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">CAR INTERNAL AUDIT OPEN</span>
							<div class="card-icon-badge badge-red">
								<i class="fa fa-exclamation-triangle text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('car')" title="Klik untuk lihat daftar CAR Open"><?= number_format($audit_compliance['car_open']); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 mb-4 mb-md-0">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">CAR INTERNAL OPEN</span>
							<div class="card-icon-badge badge-red">
								<i class="fa fa-check-double text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('car_internal')" title="Klik untuk lihat daftar CAR Internal Open"><?= number_format($audit_compliance['car_internal_open']); ?></span>
						</div>
					</div>
				</div>
				<div class="col-md-3 mb-4 mb-md-0">
					<div class="dash-card justify-content-center text-center py-4">
						<div onclick="openDocModal('compliance')" style="cursor: pointer;" title="Klik untuk lihat daftar Compliance & Regulasi">
							<?= render_compliance_donut($audit_compliance['compliance_rate']); ?>
						</div>
						<div class="card-label-top mt-3" style="font-size: 11px; cursor: pointer;" onclick="openDocModal('compliance')">COMPLIANCE TO REGULATION</div>
						<div class="mt-2 text-center text-dark font-weight-bolder" style="font-size: 13px;" onclick="openDocModal('compliance')" title="Total Comply / Subject Regulations">
							<?= number_format($audit_compliance['total_compliance_items']); ?> / <?= number_format($audit_compliance['total_subject_regulations']); ?> <span class="text-muted font-weight-bold" style="font-size: 11px;">Subject Regulations</span> (<?= $audit_compliance['compliance_rate']; ?>%)
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="dash-card">
						<div class="d-flex justify-content-between align-items-start">
							<span class="card-label-top">ACTION PLAN COMPLIANCE</span>
							<div class="card-icon-badge badge-blue">
								<i class="fa fa-thumbtack text-white"></i>
							</div>
						</div>
						<div>
							<span class="card-number-huge" onclick="openDocModal('action_plan')" title="Klik untuk lihat daftar Action Plan"><?= number_format($audit_compliance['action_plan']); ?></span>
						</div>
					</div>
				</div>
			</div>

			<!-- SECTION 3: STATUS DOKUMEN -->
			<div class="section-header">STATUS DOKUMEN</div>
			<div class="row mb-5">
				<!-- Prosedur Status Card -->
				<div class="col-md-3 col-sm-6 mb-4 mb-md-0">
					<div class="dash-card">
						<span class="card-label-top mb-3">PROSEDUR</span>
						<div class="d-flex align-items-center justify-content-around">
							<div onclick="openDocModal('procedure')" style="cursor: pointer;">
								<?= render_doc_status_donut($doc_control['procedur']['pub'], $doc_control['procedur']['rev'], $doc_control['procedur']['cor'], $doc_control['procedur']['rvi']); ?>
							</div>
							<div style="min-width: 120px;">
								<table class="status-legend-table">
									<tr>
										<td><span class="legend-dot dot-teal"></span><span class="status-label-text">Published</span></td>
										<td class="status-val-text" onclick="openDocModal('procedure', 'PUB')" title="Filter Published"><?= $doc_control['procedur']['pub']; ?></td>
									</tr>
									<tr>
										<td><span class="legend-dot dot-yellow"></span><span class="status-label-text">Review</span></td>
										<td class="status-val-text" onclick="openDocModal('procedure', 'REV')" title="Filter Under Review"><?= $doc_control['procedur']['rev']; ?></td>
									</tr>
									<tr>
										<td><span class="legend-dot dot-red"></span><span class="status-label-text">Koreksi</span></td>
										<td class="status-val-text" onclick="openDocModal('procedure', 'COR')" title="Filter Perlu Revisi"><?= $doc_control['procedur']['cor']; ?></td>
									</tr>
									<tr>
										<td><span class="legend-dot dot-purple"></span><span class="status-label-text">Revision</span></td>
										<td class="status-val-text" onclick="openDocModal('procedure', 'RVI')" title="Filter Revision"><?= $doc_control['procedur']['rvi']; ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Work Instruction Status Card -->
				<div class="col-md-3 col-sm-6 mb-4 mb-md-0">
					<div class="dash-card">
						<span class="card-label-top mb-3">WORK INSTRUCTION</span>
						<div class="d-flex align-items-center justify-content-around">
							<div onclick="openDocModal('wi')" style="cursor: pointer;" title="Klik untuk lihat daftar Work Instruction">
								<svg width="85" height="85" viewBox="0 0 85 85">
									<circle cx="42.5" cy="42.5" r="32" fill="transparent" stroke="rgba(0,0,0,0.15)" stroke-width="10"></circle>
									<circle cx="42.5" cy="42.5" r="32" fill="transparent" stroke="#3699ff" stroke-width="10" stroke-dasharray="201 201"></circle>
									<text x="42.5" y="47" text-anchor="middle" fill="#000000" font-size="15" font-weight="800"><?= number_format($doc_control['wi']['total']); ?></text>
								</svg>
							</div>
							<div style="min-width: 120px;">
								<table class="status-legend-table">
									<tr>
										<td><span class="legend-dot dot-teal"></span><span class="status-label-text">Active Files</span></td>
										<td class="status-val-text" onclick="openDocModal('wi')" title="Lihat Semua WI"><?= $doc_control['wi']['total']; ?></td>
									</tr>
									<tr>
										<td colspan="2" class="text-muted pt-2" style="font-size: 11px;"><i>Status: Non-DEL</i></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Form Status Card -->
				<div class="col-md-3 col-sm-6 mb-4 mb-md-0">
					<div class="dash-card">
						<span class="card-label-top mb-3">FORM</span>
						<div class="d-flex align-items-center justify-content-around">
							<div onclick="openDocModal('form')" style="cursor: pointer;" title="Klik untuk lihat daftar Form">
								<svg width="85" height="85" viewBox="0 0 85 85">
									<circle cx="42.5" cy="42.5" r="32" fill="transparent" stroke="rgba(0,0,0,0.15)" stroke-width="10"></circle>
									<circle cx="42.5" cy="42.5" r="32" fill="transparent" stroke="#3699ff" stroke-width="10" stroke-dasharray="201 201"></circle>
									<text x="42.5" y="47" text-anchor="middle" fill="#000000" font-size="15" font-weight="800"><?= number_format($doc_control['form']['total']); ?></text>
								</svg>
							</div>
							<div style="min-width: 120px;">
								<table class="status-legend-table">
									<tr>
										<td><span class="legend-dot dot-teal"></span><span class="status-label-text">Active Files</span></td>
										<td class="status-val-text" onclick="openDocModal('form')" title="Lihat Semua Form"><?= $doc_control['form']['total']; ?></td>
									</tr>
									<tr>
										<td colspan="2" class="text-muted pt-2" style="font-size: 11px;"><i>Status: Non-DEL</i></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>

				<!-- Records Status Card (Ujung Kanan: Tidak pakai status, semua file kecuali DEL) -->
				<div class="col-md-3 col-sm-6">
					<div class="dash-card">
						<span class="card-label-top mb-3">RECORDS</span>
						<div class="d-flex align-items-center justify-content-around">
							<div onclick="openDocModal('records')" style="cursor: pointer;" title="Klik untuk lihat daftar Records">
								<svg width="85" height="85" viewBox="0 0 85 85">
									<circle cx="42.5" cy="42.5" r="32" fill="transparent" stroke="rgba(0,0,0,0.15)" stroke-width="10"></circle>
									<circle cx="42.5" cy="42.5" r="32" fill="transparent" stroke="#3699ff" stroke-width="10" stroke-dasharray="201 201"></circle>
									<text x="42.5" y="47" text-anchor="middle" fill="#000000" font-size="15" font-weight="800"><?= number_format($doc_control['records']['total']); ?></text>
								</svg>
							</div>
							<div style="min-width: 120px;">
								<table class="status-legend-table">
									<tr>
										<td><span class="legend-dot dot-teal"></span><span class="status-label-text">Active Files</span></td>
										<td class="status-val-text" onclick="openDocModal('records')" title="Lihat Semua Records"><?= $doc_control['records']['total']; ?></td>
									</tr>
									<tr>
										<td colspan="2" class="text-muted pt-2" style="font-size: 11px;"><i>Status: Non-DEL</i></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- SECTION 4: TUGAS SAYA -->
			<div class="section-header">TUGAS SAYA</div>
			<div class="row">
				<div class="col-12">
					<div class="dash-card">
						<div class="task-list">
							<?php if (!empty($my_tasks)) : ?>
								<?php foreach ($my_tasks as $task) : ?>
									<div class="task-item">
										<div class="task-left">
											<div class="task-icon" style="background-color: <?= isset($task['bg']) ? $task['bg'] : '#f64e60'; ?>; color: #ffffff;">
												<i class="fa <?= $task['icon']; ?> text-white"></i>
											</div>
											<span class="task-text"><?= htmlspecialchars($task['text']); ?></span>
										</div>
										<span class="task-due"><?= htmlspecialchars($task['due']); ?></span>
									</div>
								<?php endforeach; ?>
							<?php else : ?>
								<div class="text-dark font-weight-bold py-4 text-center">Tidak ada tugas yang tertunda saat ini.</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal Popup List Dokumen -->
<div class="modal fade" id="modalDocList" tabindex="-1" role="dialog" aria-labelledby="modalDocListLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered" style="max-width: 92%;" role="document">
		<div class="modal-content" style="border-radius: 16px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
			<div class="modal-header border-bottom py-4 px-6">
				<h5 class="modal-title font-weight-bolder text-dark" id="modalDocListLabel">Daftar Dokumen</h5>
				<button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-6" style="max-height: 480px; overflow-y: auto;">
				<div id="modalLoading" class="text-center py-8">
					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
					</div>
					<p class="mt-3 text-muted">Memuat data...</p>
				</div>
				<div id="modalContent" style="display: none;">
					<div class="table-responsive">
						<table class="table table-hover table-vertical-center">
							<thead id="modalTableHead">
							</thead>
							<tbody id="modalTableBody">
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer bg-light py-3 px-6 d-flex justify-content-between align-items-center">
				<div>
					<span id="modalCounterText" class="text-dark font-weight-bolder">Menampilkan 0 data</span>
				</div>
				<div>
					<button type="button" class="btn btn-secondary font-weight-bold mr-2" data-dismiss="modal">Tutup</button>
					<a href="#" id="btnSeeAllData" class="btn btn-primary font-weight-bold">
						<i class="fa fa-list mr-1"></i> Lihat Semua Data (<span id="modalTotalBadge">0</span>)
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function openDocModal(type, status) {
	$('#modalDocList').modal('show');
	$('#modalLoading').show();
	$('#modalContent').hide();

	var typeName = 'Prosedur';
	if (type === 'wi') typeName = 'Work Instruction';
	if (type === 'records') typeName = 'Records';
	if (type === 'form') typeName = 'Form';
	if (type === 'car') typeName = 'CAR Internal Audit Open';
	if (type === 'car_internal') typeName = 'CAR Internal Open';
	if (type === 'compliance') typeName = 'Compliance to Regulation';
	if (type === 'action_plan') typeName = 'Action Plan Compliance';

	var statusText = '';
	if (status === 'PUB') statusText = ' (Published)';
	if (status === 'REV') statusText = ' (Under Review)';
	if (status === 'COR') statusText = ' (Perlu Koreksi)';
	if (status === 'RVI') statusText = ' (Revision)';

	$('#modalDocListLabel').text('Daftar ' + typeName + statusText);

	var headHtml = '<tr class="text-uppercase text-muted font-weight-bolder">';
	headHtml += '<th width="40" class="text-center">No.</th>';
	if (type === 'wi') {
		headHtml += '<th>Name</th><th>Procedure Name</th><th>Status</th>';
	} else if (type === 'records') {
		headHtml += '<th>Name</th><th>Procedure</th><th>Status</th>';
	} else if (type === 'form') {
		headHtml += '<th>Name</th><th>Procedure</th><th>Effective Date</th><th class="text-center">Rev. Number</th><th>Status</th>';
	} else if (type === 'car') {
		headHtml += '<th>ID Audit</th><th>Deskripsi Temuan</th><th>Departemen</th><th>Tanggal</th><th>Status</th>';
	} else if (type === 'car_internal') {
		headHtml += '<th>Nomor CAR</th><th>Tanggal CAR</th><th>Deadline</th><th>Department</th><th>Status</th>';
	} else if (type === 'compliance') {
		headHtml += '<th width="100">Kode</th>' +
			'<th>Subjek Regulasi</th>' +
			'<th class="text-center" width="110">Jml Regulasi</th>' +
			'<th class="text-center table-success" width="60">C</th>' +
			'<th class="text-center table-danger" width="60">NC</th>' +
			'<th class="text-center" width="180">% Pemenuhan</th>' +
			'<th class="text-center" width="100">Status</th>';
	} else if (type === 'action_plan') {
		headHtml += '<th>ID Action Plan</th><th>Rencana Aksi</th><th>PIC</th><th>Status</th>';
	} else {
		headHtml += '<th>Nama</th><th>Nomor</th><th>Kelompok</th><th>Status</th>';
	}
	headHtml += '</tr>';
	$('#modalTableHead').html(headHtml);

	$.ajax({
		url: '<?= base_url("dashboard/get_doc_list"); ?>',
		type: 'GET',
		data: { type: type, status: status },
		dataType: 'json',
		success: function(res) {
			$('#modalLoading').hide();
			$('#modalContent').show();

			var shownCount = (res.data && res.data.length) ? res.data.length : 0;
			var totalCount = res.total_count || shownCount;

			$('#modalCounterText').html('Menampilkan <b>' + shownCount + '</b> dari <b>' + totalCount + '</b> total data');
			$('#btnSeeAllData').attr('href', res.redirect_url);
			$('#modalTotalBadge').text(totalCount);

			var html = '';
			if (res.data && res.data.length > 0) {
				$.each(res.data, function(idx, item) {
					var stsBadge = '<span class="badge badge-primary">Published</span>';
					if (type === 'action_plan' && item.status === 'OPN') {
						stsBadge = '<span class="badge badge-success">Open</span>';
					} else if (type === 'wi' || type === 'form') {
						stsBadge = '<span class="badge badge-primary">Published</span>';
					} else if (item.status === 'REV' || item.status === 'OPN' || item.status === 'APV') {
						stsBadge = '<span class="badge badge-warning">Under Review</span>';
					} else if (item.status === 'COR' || item.status === 'REJ') {
						stsBadge = '<span class="badge badge-danger">Perlu Koreksi</span>';
					} else if (item.status === 'RVI') {
						stsBadge = '<span class="badge badge-purple" style="background-color:#8950fc;color:#fff;">Revision</span>';
					} else if (item.status === 'DFT') {
						stsBadge = '<span class="badge badge-secondary">Draft</span>';
					} else if (item.status === 'PRO') {
						stsBadge = '<span class="badge badge-info">Progress</span>';
					} else if (item.status === 'CMP') {
						stsBadge = '<span class="badge badge-success">Comply</span>';
					}

					var no = idx + 1;
					var nameBold = '<span class="font-weight-bolder text-dark d-block">' + (item.name || '-') + '</span>';
					var numBold = '<span class="font-weight-bold text-dark">' + (item.number || '-') + '</span>';

					if (type === 'wi') {
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td>' + (item.procedure_name || '-') + '</td>' +
							'<td>' + stsBadge + '</td>' +
							'</tr>';
					} else if (type === 'records') {
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td>' + (item.procedure_name || '-') + '</td>' +
							'<td>' + stsBadge + '</td>' +
							'</tr>';
					} else if (type === 'form') {
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td>' + (item.procedure_name || '-') + '</td>' +
							'<td>' + (item.effective_date || '-') + '</td>' +
							'<td class="text-center">' + (item.revision_number || '00') + '</td>' +
							'<td>' + stsBadge + '</td>' +
							'</tr>';
					} else if (type === 'car') {
						var carAuditSts = '<span class="badge badge-primary">Open</span>';
						if (item.status === 'draft' || item.status === 'Draft' || item.status === 'DFT') {
							carAuditSts = '<span class="badge badge-primary">Open</span>';
						} else if (item.status === 'waiting_approval') {
							carAuditSts = '<span class="badge badge-info">Waiting Approval</span>';
						} else if (item.status === 'approved' || item.status === 'Approved') {
							carAuditSts = '<span class="badge badge-success">Approved</span>';
						} else if (item.status === 'closed' || item.status === 'Closed') {
							carAuditSts = '<span class="badge badge-success">Closed</span>';
						}
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + numBold + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td>' + (item.departement_name || '-') + '</td>' +
							'<td>' + (item.date || '-') + '</td>' +
							'<td>' + carAuditSts + '</td>' +
							'</tr>';
					} else if (type === 'car_internal') {
						var carSts = '<span class="badge badge-primary">Open</span>';
						if (item.status === 'reject') {
							carSts = '<span class="badge badge-danger">Reject</span>';
						} else if (item.status === 'draft') {
							var today = new Date();
							var deadline = item.deadline_car ? new Date(item.deadline_car) : null;
							if (deadline && deadline < today) {
								carSts = '<span class="badge badge-danger">Overdue</span>';
							} else {
								carSts = '<span class="badge badge-primary">Open</span>';
							}
						}
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td><span class="font-weight-bold">' + (item.nomor_car || '-') + '</span></td>' +
							'<td>' + (item.tanggal_car || '-') + '</td>' +
							'<td>' + (item.deadline_car || '-') + '</td>' +
							'<td>' + (item.department_name || '-') + '</td>' +
							'<td>' + carSts + '</td>' +
							'</tr>';
					} else if (type === 'compliance') {
						var pct = item.percentage !== undefined ? item.percentage : 0;
						var barColor = '#00e6a8';
						if (pct < 50) {
							barColor = '#f64e60';
						} else if (pct < 80) {
							barColor = '#ffa800';
						}

						var pctProgress = '<div class="d-flex align-items-center justify-content-center">' +
							'<div class="progress flex-grow-1 mr-2" style="height: 8px; background-color: rgba(0,0,0,0.1); border-radius: 4px; overflow: hidden;">' +
							'<div class="progress-bar" role="progressbar" style="width: ' + pct + '%; background-color: ' + barColor + ';" aria-valuenow="' + pct + '" aria-valuemin="0" aria-valuemax="100"></div>' +
							'</div>' +
							'<span class="font-weight-bolder text-dark" style="font-size: 12px; min-width: 38px; text-align: right;">' + pct + '%</span>' +
							'</div>';

						var compBadge = '<span class="badge badge-success">Comply</span>';
						if (pct < 100 && pct > 0) {
							compBadge = '<span class="badge badge-warning">Partial</span>';
						} else if (pct == 0) {
							compBadge = '<span class="badge badge-danger">Not Comply</span>';
						}

						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + numBold + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td class="text-center font-weight-bold">' + (item.total_regulations !== undefined ? item.total_regulations : '-') + '</td>' +
							'<td class="text-center font-weight-bold text-success">' + (item.compliance !== undefined ? item.compliance : '-') + '</td>' +
							'<td class="text-center font-weight-bold text-danger">' + (item.not_compliance !== undefined ? item.not_compliance : '-') + '</td>' +
							'<td>' + pctProgress + '</td>' +
							'<td class="text-center">' + compBadge + '</td>' +
							'</tr>';
					} else if (type === 'action_plan') {
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + numBold + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td>' + (item.pic || '-') + '</td>' +
							'<td>' + stsBadge + '</td>' +
							'</tr>';
					} else {
						html += '<tr>' +
							'<td class="text-center">' + no + '</td>' +
							'<td>' + nameBold + '</td>' +
							'<td>' + numBold + '</td>' +
							'<td>' + (item.group_name || '-') + '</td>' +
							'<td>' + stsBadge + '</td>' +
							'</tr>';
					}
				});
			} else {
				html = '<tr><td colspan="7" class="text-center text-muted py-5">Tidak ada data dokumen ditemukan.</td></tr>';
			}
			$('#modalTableBody').html(html);
		},
		error: function() {
			$('#modalLoading').hide();
			$('#modalContent').show();
			$('#modalTableBody').html('<tr><td colspan="7" class="text-center text-danger py-5">Gagal mengambil data.</td></tr>');
		}
	});
}
</script>