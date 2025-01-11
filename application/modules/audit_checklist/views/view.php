<div class="row">
	<div class="col-md-6">
		<div class="mb-2 row">
			<label for="" class="d-none" id="procedure_id"><?= $cklst->procedure_id; ?></label>
			<label for="" class="col-md-4 h6 font-weight-bold">Procedure</label>
			<label for="" class="col h6">: <?= isset($cklst->name) ? $cklst->name : ''; ?></label>

		</div>
		<div class="mb-2 row">
			<label for="" class="col-md-4 h6 font-weight-bold">Date</label>
			<label for="" class="col h6">: <?= (isset($audit) && $audit) ? $audit->date : ''; ?>
			</label>
		</div>
	</div>
	<div class="col-md-6">
		<div class="mb-2 row">
			<label for="" class="col-md-4 h6 font-weight-bold">Auditor</label>
			<div for="" class="col h6">:
				<?php if ($users) foreach ($users as $a) : ?>
					<?= ($audit && $audit->auditor) ? (in_array($a->id_user, json_decode($audit->auditor)) ? $a->full_name . "," : '') : ''; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="mb-2 row">
			<label for="" class="col-md-4 h6 font-weight-bold">Auditee</label>
			<div for="" class="col h6">:
				<?php if ($users) foreach ($users as $a) : ?>
					<?= ($audit && $audit->auditee) ? (in_array($a->id_user, json_decode($audit->auditee)) ? $a->full_name . "," : '') : ''; ?>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="load_data mb-10">
	<div class="accordion" id="accordionExample">
		<div class="card">
			<div class="card-header" id="headingOne">
				<h4 class="mb-0 p-5" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
					List Standard
				</h4>
			</div>

			<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
				<div class="card-body">
					<?php if ($ArrStd) : ?>
						<?php foreach ($ArrStd as $std) : ?>
							<h3>Standard : <?= $std->name; ?></h3>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>No</th>
										<th width="100">Pasal</th>
										<th>Desc. Indonesian</th>
										<th>Desc. English</th>
									</tr>
								</thead>
								<tbody>
									<?php if ($ArrData['standards'][$std->requirement_id]) : ?>
										<?php $n = 0;
										foreach ($ArrData['standards'][$std->requirement_id] as $dtStd) : $n++; ?>

											<tr>
												<td><?= $n; ?></td>
												<td><?= $dtStd->chapter; ?>
												</td>
												<td>
													<?= limit_text(strip_tags($dtStd->desc_indo), 100) . ' <a href="javascript:void(0)" class="link read" data-id="' . $dtStd->chapter_id . '">[read]</a>'; ?>
												</td>
												<td>
													<?= limit_text(strip_tags($dtStd->desc_eng), 100) . ' <a href="javascript:void(0)" class="link read" data-id="' . $dtStd->chapter_id . '">[read]</a>'; ?>
												</td>
											</tr>
									<?php endforeach;
									endif; ?>
								</tbody>
							</table>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="text-center">~ Not available data ~</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<h4 class="card-title mb-3"><i class="far fa-check-circle text-primary" aria-hidden="true"></i> Audit Non Checklist</h4>
<div class="mb-15">
	<table id="tblTemuan" class="table datatable table-bordered table-sm table-condensed mb-5">
		<thead class="text-center table-light">
			<tr>
				<th width="10">No</th>
				<th width="">Temuan <span class="text-danger">*</span></th>
				<th width="150">Kategori</th>
				<th width="150">ISO</th>
				<th width="250">Pasal</th>
			</tr>
		</thead>
		<tbody class="">
			<?php if (isset($AdtAudit)) foreach ($AdtAudit as $k => $a) : $k++; ?>
				<tr>
					<td class="text-center"><?= $k; ?></td>
					<td><?= $a->description; ?></td>
					<td class="text-center"><?= $category[$a->category]; ?></td>
					<td class="text-center"><?= isset($ArrDtlStd[$a->standard]) ? $ArrDtlStd[$a->standard] : '-'; ?></td>
					<td class="text-"><?= isset($ArrPro[$a->pasal]) ? $ArrPro[$a->pasal] : '-'; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<hr class="mb-10">
<!-- CHECKLIT TEMUAN -->
<h4 class="card-title mb-3"><i class="fa fa-check-circle text-primary" aria-hidden="true"></i> Checklist</h4>
<table id="tblChecklist" class="table table-sm table-bordered table-condensed table-hover">
	<thead class="table-light">
		<tr class="text-center">
			<th width="30">No</th>
			<th class="">Checklist</th>
			<th class="w-md-400px">Temuan</th>
			<th width="150">Kategori</th>
			<th width="150">ISO</th>
			<th width="250">Pasal</th>
		</tr>
	</thead>
	<tbody>
		<?php if ($checklist) foreach ($checklist as $n => $v) : $n++; ?>
			<tr>
				<td class="text-center"><?= $n; ?></td>
				<td class="bg-light"><?= $v->description; ?></td>
				<td><?= isset($ArrDtl) ? $ArrDtl[$v->id]->description : ''; ?></td>
				<td class="text-center"><?= isset($ArrDtl[$v->id]->category) ? $category[$ArrDtl[$v->id]->category] : ''; ?></td>
				<td class="text-center"><?= isset($ArrDtlStd[$ArrDtl[$v->id]->standard]) ? $ArrDtlStd[$ArrDtl[$v->id]->standard] : ''; ?></td>
				<td><?= isset($ArrPro[$ArrDtl[$v->id]->pasal]) ? $ArrPro[$ArrDtl[$v->id]->pasal] : ''; ?></td>
				<td>
					<?php if ($ArrDtl[$v->id]->file_name) : ?>
						<a href="<?= base_url('directory/AUDIT/1/' . $ArrDtl[$v->id]->file_name); ?>" target="_blank" class="btn btn-info btn-icon btn-sm"><i class="fa fa-file" aria-hidden="true"></i></a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>