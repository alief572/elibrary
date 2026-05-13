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
				<td><?= (isset($ArrDtl) && isset($ArrDtl[$v->id])) ? $ArrDtl[$v->id]->description : ''; ?></td>
				<td class="text-center"><?= (isset($ArrDtl) && isset($ArrDtl[$v->id]->category)) ? $category[$ArrDtl[$v->id]->category] : ''; ?></td>
				<td class="text-center"><?= (isset($ArrDtl) && isset($ArrDtl[$v->id]->standard) && isset($ArrDtlStd[$ArrDtl[$v->id]->standard])) ? $ArrDtlStd[$ArrDtl[$v->id]->standard] : ''; ?></td>
				<td><?= (isset($ArrDtl) && isset($ArrDtl[$v->id]->pasal) && isset($ArrPro[$ArrDtl[$v->id]->pasal])) ? $ArrPro[$ArrDtl[$v->id]->pasal] : ''; ?></td>
				<td>
					<?php if (isset($ArrDtl) && isset($ArrDtl[$v->id]->file_name) && $ArrDtl[$v->id]->file_name) : ?>
						<a href="<?= base_url('directory/AUDIT/' . $company . '/' . $ArrDtl[$v->id]->file_name); ?>" target="_blank" class="btn btn-info btn-icon btn-sm"><i class="fa fa-file" aria-hidden="true"></i></a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<div class="modal fade" id="modalViewPasal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">View Pasal</h5>
				<span class="btn-close cursor-pointer" data-dismiss="modal" aria-label="Close">
					<div class="fa fa-times"></div>
				</span>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(document).on('click', '.read', function() {
			let id = $(this).data('id')
			$.ajax({
				url: siteurl + active_controller + 'view_pasal/' + id,
				type: 'GET',
				dataType: 'JSON',
				success: function(result) {
					if (result) {
						let html = `
						<div class="form-group">
							<label class="font-weight-bold"><strong>Pasal</strong></label>
							<div class="">
							` + result.chapter + `
							</div>
						</div>

						<!-- Nav tabs -->
						<ul class="nav nav-fill nav-pills" id="myTab" role="tablist">
							<li class="nav-item" role="presentation">
								<a class="nav-link nav-pill active" id="indo-tab" data-toggle="tab" data-target="#indo" type="button" role="tab" aria-controls="indo" aria-selected="true">Indonesian</a>
							</li>
							<li class="nav-item" role="presentation">
								<a class="nav-link nav-pill" id="eng-tab" data-toggle="tab" data-target="#eng" type="button" role="tab" aria-controls="eng" aria-selected="false">English</a>
							</li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content mt-4 border rounded-lg p-5">
							<div class="tab-pane active pt-4 pb-4" id="indo" role="tabpanel" aria-labelledby="indo-tab">
							` + result.desc_indo + `
							</div>
							<div class="tab-pane pt-4 pb-4" id="eng" role="tabpanel" aria-labelledby="eng-tab">
							` + result.desc_eng + `
							</div>
						</div>
						`;
						$('#modalViewPasal .modal-body').html(html);
						$('#modalViewPasal').modal('show')
					} else {
						Swal.fire('Warning', 'Data not valid. Please try again!', 'warning', 3000)
					}

				},
				error: function() {
					Swal.fire('Error!', 'Server timeout. Please try again!', 'error', 5000)
				}
			})
		})
	})
</script>