<div class="" style="width: 130vw;">
	<?php if (isset($data) && $data) : ?>
		<input type="hidden" name="compliance_id" value="<?= $compliance->id; ?>">
		<input type="hidden" name="regulation_id" value="<?= $compliance->regulation_id; ?>">
		<input type="hidden" name="reference_id" value="<?= $compliance->reference_id; ?>">
		<div class="row">
			<div class="col-2">
				<div class="d-flex justify-content-between">
					<span class="font-weight-bolder h5">Regulation</span>
					<span>:</span>
				</div>
			</div>
			<div class="col-10">
				<h5 for="" class="font-weight-bolder mb-5"><?= $compliance->name; ?></h5>
			</div>
		</div>
		<table class="table datatable table-bordered table-sm">
			<thead>
				<tr class="text-center table-light">
					<th style="vertical-align: middle;" width="50">No</th>
					<th style="vertical-align: middle;" width="80">Pasal</th>
					<th style="vertical-align: middle;" width="">Description Ayat</th>
					<th style="vertical-align: middle;" width="500">Compliance Description</th>
					<th style="vertical-align: middle;" width="100">Status</th>
					<th style="vertical-align: middle;" width="">Opport/ Risks</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$n = 0;
				foreach ($ArrPasal as $k => $dt) : ?>
					<?php foreach ($dt as $j => $l) : $n++; ?>
						<tr class="">
							<td>
								<?= $n; ?>
							</td>
							<?php if ($j == '0') : ?>
								<th rowspan="<?= count($dt); ?>" class="text-center">
									<span class=""><?= $l->pasal_name; ?></span>
								</th>
							<?php endif; ?>
							<td>
								<p>(<?= $l->name; ?>)</p>
								<?= $l->description; ?>
							</td>
							<td>
								<?= ($ArrCompl) ? $ArrCompl[$l->id]->compliance_desc : '';  ?>
							</td>
							<td>
								<?= isset($ArrCompl[$l->id]->status) ? $status[$ArrCompl[$l->id]->status] : ''; ?>
							</td>
							<td width="40%" class="text-center">
								<table class="table table-sm table-bordered">
									<?php if (isset($ArrOpports[$l->id])) : $i = 0; ?>
										<?php foreach ($ArrOpports[$l->id] as $opport) : $i++; ?>
											<tr class="text-left rows">
												<td><?= $i; ?></td>
												<td class="text-left" width="120">
													<?= ($opport->category == 'OPP') ? 'Opportunities' : ''; ?>
													<?= ($opport->category == 'RSK') ? 'Risk' : ''; ?>
												</td>
												<td class="">
													<?= $opport->description; ?>
												</td>
												<td>
													<?= $opport->action_plan; ?>
												</td>
												<td width="120">
													<?= isset($ArrUsers[$opport->pic]) ? $ArrUsers[$opport->pic] : ''; ?>
												</td>
												<td width="120">
													<?= ($opport->due_date) ?: ''; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php endif; ?>
								</table>
							</td>
						</tr>
				<?php endforeach;
				endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>