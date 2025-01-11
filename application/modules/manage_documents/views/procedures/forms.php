<style>
	.opsi-btn {
		display: none;
	}

	tr:hover .opsi-btn {
		display: block;
	}
</style>
<div class="border border-1 border-left py-2 overflow-auto h-550px">
	<?php if ($procedure) : $n = 0; ?>
		<table class="table table-hover table-borderless">
			<?php
			foreach ($procedure as $list) : $n++; ?>
				<tr class="cursor-pointer h6 py-0 view" data-id="<?= $list->id; ?>">
					<td style="vertical-align: middle;" class="text-dark-75 py-2">
						<i class='fa fa-file-alt text-success mr-2 fa-2x py-0' style='vertical-align:middle;'></i><span class="my-auto"><?= $list->name; ?></span>
					</td>
					<td class="text-right py-1">
						<div class="opsi-btn">
							<button class="btn btn-light-default btn-sm btn-icon" type="button"><i class="fa fa-eye"></i></button>
						</div>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="#"><i class="fab fa-telegram text-success mr-2"></i>Process to Review</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="#"><i class="fa fa-edit text-info mr-2"></i>Rename</a>
							<a class="dropdown-item" href="#"><i class="fa fa-arrows-alt text-primary mr-2"></i>Move</a>
							<a class="dropdown-item" href="#"><i class="fa fa-trash text-danger mr-2"></i>Delete</a>
						</div>

					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php else : ?>
		<div class="d-flex justify-content-center align-items-center py-10">
			<img src="<?= base_url('assets\images\dashboard\folder-file.gif'); ?>" alt="" width="300px" class="img-responsive text-center opacity-30">
		</div>
	<?php endif; ?>
</div>