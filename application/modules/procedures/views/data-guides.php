	<table class="table table-bordered table-hover">
		<thead>
			<tr class="table-light">
				<th width="50" class="text-center">No</th>
				<th class="text-center">Name</th>
				<th width="50" class="text-center">File</th>
				<th width="200" class="text-center">Update</th>
				<th width="150" class="text-center">Opis</th>
			</tr>
		</thead>
		<tbody>

			<?php if (isset($getGuides)) : $n = 0; ?>
				<?php foreach ($getGuides as $guide) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td class=""><?= $guide->name; ?></td>
						<td class="text-center">
							<?php if ($guide->file_name) : ?>
								<?php
								$ext = pathinfo($guide->file_name, PATHINFO_EXTENSION);
								if (in_array($ext, ['xls', 'xlsx'])) {
									$iconClass = 'fas fa-file-excel';
									$colorClass = 'text-warning';
								} else {
									$iconClass = 'fas fa-file-pdf';
									$colorClass = 'text-primary';
								}
								?>
								<button type="button" class="btn p-0 btn-sm btn-link btn-icon view-guide" data-id="<?= $guide->id; ?>"><i class="<?= $iconClass; ?> <?= $colorClass; ?>"></i></button>
							<?php else : ?>
								<i class="fa fa-times text-danger"></i>
							<?php endif; ?>
						</td>
						<td class="text-center"><?= $guide->created_at; ?></td>
						<td class="text-center">
							<button type="button" class="btn btn-sm btn-icon btn-warning shadow-sm edit-guide" data-id="<?= $guide->id; ?>"><i class="fa fa-edit"></i></button>
							<button type="button" class="btn btn-sm btn-icon btn-danger shadow-sm delete-guide" data-id="<?= $guide->id; ?>"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else : ?>
				<tr>
					<td colspan="5" class="text-center py-3">
						<h5 class="text-light-secondary">~ No data available~ </h5>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>