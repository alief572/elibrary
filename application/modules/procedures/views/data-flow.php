<?php if ($detail) :
	$n = 0;
	foreach ($detail as $key => $dtl) : $n++; ?>
		<tr>

			<td style="vertical-align:middle;" class="text-center"><?= $dtl->number; ?></td>
			<td style="vertical-align:middle;" class="text-center"><?= $dtl->pic; ?></td>
			<td style="white-space:pre-line;"><?= $dtl->description; ?></td>
			<td style="vertical-align: middle;">
				<?php $relDocs = json_decode($dtl->relate_doc); ?>
				<?php $relIK = json_decode($dtl->relate_ik_doc); ?>
				<?php if (is_array($relDocs)) : ?>
					<?php foreach ($relDocs as $relDoc) { ?>
						<?php if (isset($ArrForms[$relDoc])) : ?>
							<span class="badge btn <?= ($ArrForms[$relDoc]->status == 'DEL') ? 'btn-light' : 'bg-success btn-success'; ?> view-form mb-1" data-id="<?= $relDoc; ?>"><?= $ArrForms[$relDoc]->name; ?> <?= ($ArrForms[$relDoc]->status == 'DEL') ? '<i class="fa fa-exclamation-circle text-danger" title="File has been deleted!"></i>' : ''; ?></span>
						<?php endif; ?>
					<?php } ?>
				<?php endif; ?>

				<?php if (is_array($relIK)) : ?>
					<?php foreach ($relIK as $ik) { ?>
						<?php if (isset($ArrGuides[$ik])) : ?>
							<span class="badge btn <?= ($ArrGuides[$ik]->status == 'DEL') ? 'btn-light' : 'bg-danger btn-danger'; ?> view-guide mb-1" data-id="<?= $ik; ?>"><?= $ArrGuides[$ik]->name; ?> <?= ($ArrGuides[$ik]->status == 'DEL') ? '<i class="fa fa-exclamation-circle text-danger" title="File has been deleted!"></i>' : ''; ?></span>
						<?php endif; ?>
					<?php } ?>
				<?php endif; ?>
			</td>
			<td class="text-center" style="vertical-align: middle;">
				<button type="button" class="btn btn-warning btn-icon rounded-circle btn-sm edit_flow" data-proc_id="<?= $dtl->procedure_id; ?>" data-id="<?= $dtl->id; ?>"><i class="fa fa-edit"></i></button>
				<button type="button" class="btn btn-danger btn-icon rounded-circle btn-sm delete_flow" data-id="<?= $dtl->id; ?>"><i class="fa fa-trash"></i></button>
			</td>
		</tr>
	<?php endforeach;
else : ?>
	<tr>
		<td colspan="5" class="text-center text-muted">~ No data avilable ~</td>
	</tr>
<?php endif; ?>