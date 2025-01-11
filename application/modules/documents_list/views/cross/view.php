<style>
	li p {
		margin-bottom: 0;
	}
</style>
<table class="table-bordered table-sm table-condensed table">
	<thead>
		<tr>
			<th colspan="4" class="py-2 text-center table-secondary">
				<h2 class="font-weight-bolder"><?= strtoupper($Data->name); ?></h2>
			</th>
		</tr>
		<tr class="table-light">
			<th width="50" class="text-center">No</th>
			<th width="">Pasal</th>
			<th>Proses Terkait</th>
			<th width="">Dokumen Lain</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$n = 0;
		foreach ($Detail as $dtl) : $n++; ?>
			<tr>
				<td class="text-center"><?= $n; ?></td>
				<td><?= $dtl->chapter; ?></td>
				<td>
					<ul class="mb-0 pl-8">
						<?php
						if (isset($Procedure[$dtl->id])) {
							$explode = explode(',', $Procedure[$dtl->id]);
							if (isset($explode) && $explode) {
								foreach ($explode as $exp) {
									echo isset($list_procedure[$exp]) ? "<li class='mb-0'>" . $list_procedure[$exp] . "</li>" : '';
								}
							}
						}
						?>
					</ul>
				</td>
				<td><?= isset($other_docs[$dtl->id]) ? ($other_docs[$dtl->id]) : ''; ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>