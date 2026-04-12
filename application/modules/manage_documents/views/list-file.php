<!-- <div class="card-header px-0 py-1 min-h-10px"> -->
<!-- <button type="button" class="btn btn- btn-icon prev" data-id="<?= $prev; ?>"><i class="fa fa-arrow-left mr-2 <?= ($prev != '0') ? 'text-dark-75' : ''; ?>"></i></button>
	<button type="button" class="btn btn-light-warning btn-md" onclick="new_folder('<?= $parent_id; ?>')"><i class="fa fa-folder-plus mr-2"></i>Add Folder</button>
	<button type="button" class="btn btn-light-success btn-md" onclick="upload_file('<?= $parent_id; ?>')"><i class="fa fa-file mr-2"></i>Upload File</button> -->
<!-- </div> -->

<div class="card-body px-0 border border-1 border-left py-2 overflow-auto h-550px">
	<table class="table table-hover">
		<thead>
			<tr>
				<th># File Name</th>
				<th> </th>
				<th>Last Updated</th>
			</tr>
		</thead>
		<tbody>
			<?php $n = 0;
			if ($list_file) :
				foreach ($list_file as $list) : $n++; ?>
					<tr class="cursor-pointer py-0 <?= ($list->flag_type == 'FOLDER') ? 'folder' : 'file'; ?>" data-id="<?= $list->id; ?>" data-parent_id="<?= $parent_id; ?>" data-folder="<?= $main; ?>">
						<td style="vertical-align: middle;" class="text-dark-75 py-0">
							<div class="dropdown dropdown-inline">
								<button type="button" class="btn btn-light-default btn-icon btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="ki ki-bold-more-ver"></i>
								</button>
								<div class="dropdown-menu">
									<?php if ($list->flag_type == 'FILE') : ?>
										<a class="dropdown-item <?= ($list->status != 'OPN') ? 'disabled' : ''; ?>" onclick="edit_file('<?= $list->id; ?>','<?= $list->parent_id; ?>')" data-id="<?= $list->id; ?>" href="#"><i class="fa fa-file text-warning mr-2"></i>Edit File</a>
										<a class="dropdown-item <?= ($list->status != 'OPN' || $list->flag_record == 'Y') ? 'disabled' : ''; ?>" onclick=" review_process('<?= $list->id; ?>','<?= $list->parent_id; ?>')" href="#"><i class="fab fa-telegram text-success mr-2"></i>Process to Review</a>
										<div class="dropdown-divider"></div>
									<?php endif; ?>
									<a class="dropdown-item" onclick="rename('<?= $list->id; ?>')" href="#"><i class="fa fa-edit text-info mr-2"></i>Rename</a>
									<a class="dropdown-item <?= ($list->flag_type == 'FOLDER') ? 'd-none' : ''; ?>" onclick="delete_file('<?= $list->id; ?>','<?= $list->parent_id; ?>')" href="#"><i class="fa fa-trash text-danger mr-2"></i>Delete</a>
									<a class="dropdown-item <?= ($list->flag_type == 'FILE') ? 'd-none' : ''; ?>" onclick="delete_folder('<?= $list->id; ?>','<?= $list->parent_id; ?>')" href="#"><i class="fa fa-trash text-danger mr-2"></i>Delete</a>
								</div>
							</div>
							<?php if ($list->flag_type == 'FOLDER'): ?>
								<div class="open_folder d-inline" data-id="<?= $list->id; ?>" data-parent_id="<?= $parent_id; ?>" data-folder="<?= $main; ?>">
									<i class='fa fa-folder text-warning mr-2 py-0' style='vertical-align:middle;'></i><span class="my-auto"><?= $list->name; ?></span>
								</div>
							<?php else: ?>
								<i class='text-success fa fa-file mr-2 py-0' style='vertical-align:middle;'></i>
								<span class="my-auto"><?= $list->name; ?></span>
							<?php endif; ?>
						</td>
						<td style="vertical-align: middle;" class="py-0">
							<?php if ($list->flag_type == 'LINK') : ?>
								<a target="_blank" href="<?= $list->file_link; ?>" title="Direct Preview Link">
									<i class="text-success fas fa-external-link-square-alt"></i>
								</a>
							<?php elseif ($list->flag_type == 'FILE'): ?>
								<i class="text-info fa fa-file-alt"></i>
							<?php endif; ?>
						</td>
						<td style="vertical-align: middle;" class="py-0">
							<small><?= $list->created_at; ?></small><br>
							<small><?= $list->full_name; ?></small>
						</td>
						<!-- <td style="vertical-align: middle;" class="mt-1"></td> -->
					</tr>
				<?php endforeach;
			else : ?>
				<tr>
					<td style="vertical-align: middle;" class="text-dark-50 py-4 text-center h5">Not available data</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>