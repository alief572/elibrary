<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb">
	<ol class="breadcrumb bg-light py-2 px-3 mb-3 font-weight-bold font-size-sm rounded">
		<li class="breadcrumb-item">
			<?php if (empty($breadcrumbs)) : ?>
				<span class="text-dark"><i class="fa fa-home mr-1"></i> Home</span>
			<?php else : ?>
				<a href="javascript:void(0)" class="record-breadcrumb text-primary" data-id="" data-procedure="<?= $procedure_id; ?>">
					<i class="fa fa-home mr-1"></i> Home
				</a>
			<?php endif; ?>
		</li>
		<?php if (!empty($breadcrumbs)) : ?>
			<?php foreach ($breadcrumbs as $index => $bc) : ?>
				<?php if ($index == count($breadcrumbs) - 1) : ?>
					<li class="breadcrumb-item active text-dark" aria-current="page">
						<i class="fa fa-folder-open text-warning mr-1"></i> <?= htmlspecialchars($bc->name); ?>
					</li>
				<?php else : ?>
					<li class="breadcrumb-item">
						<a href="javascript:void(0)" class="record-breadcrumb text-primary" data-id="<?= $bc->id; ?>" data-procedure="<?= $procedure_id; ?>">
							<i class="fa fa-folder text-warning mr-1"></i> <?= htmlspecialchars($bc->name); ?>
						</a>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</ol>
</nav>

<!-- Nav tabs -->
<ul class="nav pb-2 nav-success nav-tabs nav-pills">
	<li class="nav-item">
		<a href="javascript:void(0)" id="home" data-procedure="<?= $procedure_id; ?>" class="nav-link py-2 px-3">
			<i class="fa fa-home mr-2"></i>
			Home
		</a>
	</li>
	<li class="nav-item">
		<a href="javascript:void(0)" id="back" data-id="<?= ($id) ?: ''; ?>" data-procedure="<?= $procedure_id; ?>" class="nav-link py-2 px-3 <?= ($EOF) ? 'disabled' : ''; ?>">
			<i class="fa fa-arrow-up mr-2"></i>
			Up Folder
		</a>
	</li>
	<li class="nav-item">
		<a href="javascript:void(0)" id="refresh" data-id="<?= ($id) ?: ''; ?>" data-procedure="<?= $procedure_id; ?>" class="nav-link py-2 px-3">
			<i class="fa fa-sync-alt mr-2"></i>
			Refresh
		</a>
	</li>
</ul>
<table class="table table-condensed table-hover">
	<thead>
		<tr class="">
			<th class="py-1">File Name</th>
			<th class="py-1 text-center" width="50px"></th>
			<th class="py-1 text-right" width="150">Last Update</th>
		</tr>
	</thead>
	<tbody>
		<?php if (($records)) :
			$no = 0;
			foreach ($records as $lsRec) :
				// Filter confidential files
				if ($lsRec->flag_type == 'FILE' && $lsRec->flag_confidential == '1') {
					$has_level_restriction = (isset($lsRec->confidential_group_ids) && $lsRec->confidential_group_ids);
					
					if ($has_level_restriction) {
						// File has level restriction: check if user's group is in allowed groups
						$allowed_groups = explode(',', $lsRec->confidential_group_ids);
						if (isset($user_group_id) && in_array($user_group_id, $allowed_groups)) {
							// User's group matches, now check if user has confidential flag
							if (!isset($user_confidential) || $user_confidential != '1') continue;
						} else {
							// User's group not in allowed list
							continue;
						}
					} else {
						// File has no level restriction: only check user's confidential flag
						if (!isset($user_confidential) || $user_confidential != '1') continue;
					}
				}
				$no++; ?>
				<tr class="cursor-pointer <?= ($lsRec->flag_type == 'FOLDER') ? 'record-item' : ''; ?>  " data-procedure="<?= $procedure_id; ?>" data-id="<?= $lsRec->id; ?>">
					<td class="h4 text-dark d-flex align-items-center my-0 pt-1">
						<?php if ($lsRec->flag_type == 'FOLDER') : ?>
							<i class="fa fa-folder text-warning fa-2x mr-4"></i>
						<?php elseif (isset($lsRec->link_url) && $lsRec->link_url) : ?>
							<i class="fas fa-link text-info fa-2x mr-4"></i>
						<?php else : 
							$recExt = isset($lsRec->ext) && $lsRec->ext ? strtolower(str_replace('.', '', $lsRec->ext)) : (isset($lsRec->file_name) ? strtolower(pathinfo($lsRec->file_name, PATHINFO_EXTENSION)) : '');
							if ($recExt == 'pdf') { $recIcon = 'fas fa-file-pdf text-danger'; }
							elseif (in_array($recExt, ['xls', 'xlsx', 'csv'])) { $recIcon = 'fas fa-file-excel text-success'; }
							elseif (in_array($recExt, ['doc', 'docx'])) { $recIcon = 'fas fa-file-word text-primary'; }
							elseif (in_array($recExt, ['ppt', 'pptx'])) { $recIcon = 'fas fa-file-powerpoint text-warning'; }
							elseif (in_array($recExt, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) { $recIcon = 'fas fa-file-image text-info'; }
							elseif (in_array($recExt, ['mp4', 'avi', 'mkv', 'mov', 'wmv'])) { $recIcon = 'fas fa-file-video text-danger'; }
							elseif (in_array($recExt, ['zip', 'rar', '7z'])) { $recIcon = 'fas fa-file-archive text-warning'; }
							else { $recIcon = 'fas fa-file-alt text-secondary'; }
						?>
							<i class="<?= $recIcon; ?> fa-2x mr-4"></i>
						<?php endif; ?>
						<span class="mt-2"><?= $lsRec->name; ?></span>
					</td>
					<td class="h6 text-center pt-1" style="vertical-align: middle;">
						<?php if ($lsRec->flag_type == 'FILE') : ?>
							<?php if (isset($lsRec->link_url) && $lsRec->link_url) : ?>
								<a href="<?= $lsRec->link_url; ?>" target="_blank" class="btn btn-icon btn-xs shadow-xs btn-info" data-toggle="tooltip" data-theme="dark" title="Open Link"><i class="fa fa-external-link-alt"></i></a>
							<?php else : ?>
								<button type="button" class="btn btn-icon btn-xs shadow-xs btn-info view-record" data-id="<?= $lsRec->id; ?>" data-toggle="tooltip" data-theme="dark" title="View Document"><i class="fa fa-eye"></i></button>
							<?php endif; ?>
						<?php endif; ?>
					</td>
					<td style="vertical-align: middle;" class="text-right pt-1"><?= ($lsRec->modified_at) ?: $lsRec->created_at; ?></td>
				</tr>
			<?php endforeach;
		else : ?>
			<tr>
				<td colspan="3" class="text-center h4"><i>No data available</i></td>
			</tr>
		<?php endif; ?>
	</tbody>
</table>