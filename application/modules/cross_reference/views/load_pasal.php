<div class="row mb-3">
	<div class="col-12">
		<?php if (isset($Data) && $Data) : ?>
			<h3 class="mb-3">Standard : <?= $Data->name; ?> <?= ($Data->year || $Data->number) ? '(' . $Data->year . ' - ' . $Data->number . ')' : ''; ?></h3>
		<?php endif; ?>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th width="40" class="text-center">No</th>
					<th width="120">Pasal</th>
					<th>Desc. Indonesian</th>
					<th>Desc. English</th>
					<th width="25%">Proses Terkait</th>
					<th width="20%">Dokumen Lain</th>
				</tr>
			</thead>
			<tbody>
				<?php if (isset($Data_detail) && $Data_detail) :
					$n = 0;
					foreach ($Data_detail as $key => $val) : $n++; ?>
						<tr>
							<td class="text-center"><?= $n; ?></td>
							<td><?= $val->chapter; ?></td>
							<td>
								<?= ($val->desc_indo) ? limit_text(strip_tags($val->desc_indo), 100) . ' <a href="javascript:void(0)" class="link view_pasal" data-id="' . $val->id . '">[read]</a>' : ''; ?>
							</td>
							<td>
								<?= ($val->desc_eng) ? limit_text(strip_tags($val->desc_eng), 100) . ' <a href="javascript:void(0)" class="link view_pasal" data-id="' . $val->id . '">[read]</a>' : ''; ?>
							</td>
							<td>
								<?php
								if (isset($Procedure[$val->id]) && $Procedure[$val->id]) {
									$explode = explode(',', $Procedure[$val->id]);
									if (isset($explode) && $explode) {
										foreach ($explode as $exp) {
											echo isset($list_procedure[$exp]) ? $list_procedure[$exp] : '';
										}
									}
								}
								?>
							</td>
							<td>
								<?= isset($other_docs[$val->id]) ? $other_docs[$val->id] : ''; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr>
						<td colspan="6" class="text-center text-muted">~ Not available data ~</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>