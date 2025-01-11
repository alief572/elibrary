<!-- DETAIL PROCEDURE -->
<table class="table table-bordered rounded-lg mb-6">
	<tr>
		<th class="table-dark text-center">
			<h1><?= $docs->name; ?></h1>
		</th>
	</tr>
	<tr>
		<td class="py-6">
			<h2 class="fw-extra-bold"><strong><u>Tujuan</u></strong></h2>
			<di class="font-size-h4">
				<?= $docs->object; ?>
			</di>
		</td>
	</tr>
	<tr>
		<td class="py-6">
			<h2 class="fw-extra-bold"><strong><u>RUANG LINGKUP</u></strong></h2>
			<di class="font-size-h4">
				<?= $docs->scope; ?>
			</di>
		</td>
	</tr>
	<tr>
		<td class="py-6">
			<h2 class="fw-extra-bold"><strong><u>DEFINISI</u></strong></h2>
			<di class="font-size-h4">
				<?= $docs->define; ?>
			</di>
		</td>
	</tr>
	<tr>
		<td class="py-6">
			<h2 class="fw-extra-bold"><strong><u>Performa Indikator</u></strong></h2>
			<di class="font-size-h4">
				<?= $docs->performance; ?>
			</di>
		</td>
	</tr>
</table>

<!-- SIPOCOR -->
<table class="table table-bordered mb-6">
	<thead>
		<tr class="table-secondary">
			<th colspan="2">
				<h3>SIPOCOR</h3>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td width="50%">
				<label for="Supplier" class="font-weight-bold font-size-"><strong>1. Supplier</strong></label>
				<div class="">
					<?= $docs->supplier; ?>
				</div>
			</td>
			<td>
				<label for="Input" class="font-weight-bold font-size-"><strong>2. Input</strong></label>
				<div class="">
					<?= $docs->input; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<label for="Proses" class="font-weight-bold font-size-"><strong>3. Proses</strong></label>
				<div class="">
					<?= $docs->process; ?>
				</div>
			</td>
			<td>
				<label for="Output" class="font-weight-bold font-size-"><strong>4. Output</strong></label>
				<div class="">
					<?= $docs->output; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<label for="Customer" class="font-weight-bold font-size-"><strong>5. Customer</strong></label>
				<div class="">
					<?= $docs->customer; ?>
				</div>
			</td>
			<td>
				<label for="Objective" class="font-weight-bold font-size-"><strong>6. Objective</strong></label>
				<div class="">
					<?= $docs->objective; ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<label for="Risk" class="font-weight-bold font-size-"><strong>7. Risk</strong></label>
				<div class="">
					<?= $docs->risk; ?>
				</div>
			</td>
			<td>
				<label for="mitigation" class="font-weight-bold font-size-"><strong>8. Mitigation</strong></label>
				<div class="">
					<?= $docs->mitigation; ?>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<!-- FLOW IMAGE -->
<table class="table table-bordered mb-6">
	<thead>
		<tr class="table-secondary">
			<th>
				<h3>FLOW IMAGE</h3>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<?php if ($docs->image_flow_1 || $docs->image_flow_2 || $docs->image_flow_3) : ?>
					<div class="d-flex justify-content-start align-items-center">
						<?php if ($docs->image_flow_1) : ?>
							<div class="dropzone-wrapper mr-2 d-flex align-items-center" style="width: 200px;height:200px;border:1px solid #eaeaea">
								<div class="dropzone-desc">
									<?php if ($docs->image_flow_1) : ?>
										<img src="<?= base_url("directory/FLOW_IMG/$docs->company_id/$docs->image_flow_1"); ?>" alt="image_flow_1" class="img-fluid">
									<?php endif; ?>
								</div>
								<?php if ($docs->image_flow_1) : ?>
									<div class="middle d-flex justify-content-center align-items-center">
										<button type="button" class="btn btn-sm mr-1 btn-icon btn-default view-image rounded-circle"><i class="fa fa-search"></i></button>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ($docs->image_flow_2) : ?>
							<div class="dropzone-wrapper mr-2 d-flex align-items-center" style="width: 200px;height:200px;border:1px solid #eaeaea">
								<div class="dropzone-desc">
									<?php if ($docs->image_flow_2) : ?>
										<img src="<?= base_url("directory/FLOW_IMG/$docs->company_id/$docs->image_flow_2"); ?>" alt="image_flow_2" class="img-fluid">
									<?php endif; ?>
								</div>
								<?php if ($docs->image_flow_2) : ?>
									<div class="middle d-flex justify-content-center align-items-center">
										<button type="button" class="btn btn-sm mr-1 btn-icon btn-default view-image rounded-circle"><i class="fa fa-search"></i></button>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<?php if ($docs->image_flow_3) : ?>
							<div class="dropzone-wrapper mr-2 d-flex align-items-center" style="width: 200px;height:200px;border:1px solid #eaeaea">
								<div class="dropzone-desc">
									<?php if ($docs->image_flow_3) : ?>
										<img src="<?= base_url("directory/FLOW_IMG/$docs->company_id/$docs->image_flow_3"); ?>" alt="image_flow_3" class="img-fluid">
									<?php endif; ?>
								</div>
								<?php if ($docs->image_flow_3) : ?>
									<div class="middle d-flex justify-content-center align-items-center">
										<button type="button" class="btn btn-sm mr-1 btn-icon btn-default view-image rounded-circle"><i class="fa fa-search"></i></button>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<span class="text-center">~ Not available data ~</span>
				<?php endif; ?>
			</td>
		</tr>
	</tbody>
</table>

<!-- VIDEO -->
<table class="table table-bordered mb-6">
	<thead>
		<tr class="table-secondary">
			<th>
				<h3>VIDEO</h3>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($docs->link_video) : ?>
			<tr>
				<td class="text-center">
					<?= $docs->link_video; ?>
				</td>
			</tr>
		<?php endif; ?>
</table>

<!-- FLOW -->
<table class="table table-bordered">
	<thead>
		<tr class="table-secondary">
			<th colspan="4">
				<h4>DETAIL FLOW PROCEDURE</h4>
			</th>
		</tr>
		<tr>
			<th class="text-center">No.</th>
			<th class="text-center">PIC/TANGGUNG JAWAB</th>
			<th class="text-center">DESKRIPSI</th>
			<th class="text-center">DOKUMEN TERKAIT</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($detail) :
			foreach ($detail as $dtl) : ?>
				<tr>
					<td class="text-center"><?= $dtl->number; ?></td>
					<td class="text-center"><?= $dtl->pic; ?></td>
					<td><?= $dtl->description; ?></td>
					<td class="">
						<?php $relDocs = json_decode($dtl->relate_doc); ?>
						<?php if (is_array($relDocs)) : ?>
							<?php foreach ($relDocs as $relDoc) { ?>
								<span class="badge btn <?= ($ArrForms[$relDoc]->status == 'DEL') ? 'btn-light' : 'bg-success btn-success'; ?>  view-form-2 mb-1" data-id="<?= $relDoc; ?>"><?= $ArrForms[$relDoc]->name; ?> <?= ($ArrForms[$relDoc]->status == 'DEL') ? '<i class="fa fa-exclamation-circle text-danger" title="File has been deleted!"></i>' : ''; ?></span>
							<?php } ?>
						<?php endif; ?>

						<?php $relIK = json_decode($dtl->relate_ik_doc); ?>
						<?php if (is_array($relIK)) : ?>
							<?php foreach ($relIK as $ik) { ?>
								<span class="badge btn <?= ($ArrGuides[$ik]->status == 'DEL') ? 'btn-light' : 'bg-danger btn-danger'; ?> view-guide-2 mb-1" data-id="<?= $ik; ?>"><?= $ArrGuides[$ik]->name; ?> <?= ($ArrGuides[$ik]->status == 'DEL') ? '<i class="fa fa-exclamation-circle text-danger"  title="File has been deleted!"></i>' : ''; ?></span>
							<?php } ?>
						<?php endif; ?>
					</td>
				</tr>
		<?php endforeach;
		endif; ?>
	</tbody>
</table>

<!-- APPROVAL -->
<table class="table table-bordered">
	<thead>
		<tr class="table-secondary">
			<th>
				<h3>DATA APPROVAL</h3>
			</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
				<table class="table table-bordered table-sm">
					<tr>
						<th width="180">Prepared By</th>
						<td><?= ($docs->reviewer_id) ? $ArrUsr[$docs->prepared_by]->full_name : '-'; ?></td>
					</tr>
					<tr>
						<th style="vertical-align: middle;" rowspan="2">Review By</th>
						<td><?= ($docs->reviewer_id) ? $ArrJab[$docs->reviewer_id]->name : '-'; ?></td>
					</tr>
					<tr>
						<td><?= ($docs->reviewed_by) ? $ArrUsr[$docs->reviewed_by]->full_name : '-'; ?></td>
					</tr>
					<tr>
						<th style="vertical-align: middle;" rowspan="2">Approval By</th>
						<td><?= ($docs->approval_id) ? $ArrJab[$docs->approval_id]->name : '-'; ?></td>
					</tr>
					<tr>
						<td><?= ($docs->approved_by) ? $ArrUsr[$docs->approved_by]->full_name : '-'; ?></td>
					</tr>
					<tr>
						<th style="vertical-align: middle;">Distribution By</th>
						<td>
							<?php $lsJab = explode(',', $docs->distribute_id);
							foreach ($lsJab as $jab) {
								echo ($jab) ? $ArrJab[$jab]->name . "<br>" : '-';
							}
							?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tbody>
</table>