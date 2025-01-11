	<table class="table datatable table-bordered table-hover">
		<thead>
			<tr class="table-light">
				<th width="50" class="text-center p-2">No</th>
				<th class="text-center p-2">Name</th>
				<th width="" class="text-center p-2">Link Form</th>
				<th width="50" class="text-center p-2">File</th>
				<th width="200" class="text-center p-2">Update</th>
				<th width="150" class="text-center p-2">Opis</th>
			</tr>
		</thead>
		<tbody>
			<?php if (isset($getForms)) : $n = 0; ?>
				<?php foreach ($getForms as $form) : $n++; ?>
					<tr>
						<td class="p-2 text-center"><?= $n; ?></td>
						<td class="p-2"><?= $form->name; ?></td>
						<td class="p-2">
							<?php if ($form->link_form) : ?>
								<a href="<?= $form->link_form; ?>">Link Form</a>
							<?php else : ?>
								-
							<?php endif; ?>
						</td>
						<td class="p-2 text-center">
							<?php if ($form->file_name) : ?>
								<button type="button" class="btn p-0 btn-sm btn-link text-success btn-icon view-form" data-id="<?= $form->id; ?>"><i class="fas fa-file-pdf text-success"></i></button>
							<?php else : ?>
								<i class="fa fa-times text-danger"></i>
							<?php endif; ?>
						</td>
						<td class="p-2 text-center"><?= $form->created_at; ?></td>
						<td class="p-2 text-center">
							<button type="button" class="btn btn-sm btn-icon btn-warning shadow-sm edit-form" data-id="<?= $form->id; ?>"><i class="fa fa-edit"></i></button>
							<button type="button" class="btn btn-sm btn-icon btn-danger shadow-sm delete-form" data-id="<?= $form->id; ?>"><i class="fa fa-trash"></i></button>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>