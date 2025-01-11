<div class="border border-1 border-left py-2 overflow-auto h-550px">
	<?php if ($group_process) : $n = 0; ?>
		<table class="table table-hover table-borderless">
			<?php
			foreach ($group_process as $list) : $n++; ?>
				<tr class="cursor-pointer h6 py-0 folder-procedure" data-id="<?= $list->id; ?>">
					<td style="vertical-align: middle;" class="text-dark-75 py-2">
						<i class='fa fa-folder text-warning mr-2 fa-2x py-0' style='vertical-align:middle;'></i><span class="my-auto"><?= $list->name; ?></span>
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