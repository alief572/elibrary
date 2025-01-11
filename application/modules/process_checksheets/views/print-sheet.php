<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $data->checksheet_name; ?></title>
	<style>
		* {
			font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
			font-size: 11px;
		}
	</style>
</head>

<body>
	<table>
		<thead>
			<tr>
				<th style="text-align: left;">Checksheet Name</th>
				<th>:</th>
				<th style="text-align: left;"><?= $data->checksheet_name; ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Frequency Execution</th>
				<th>:</th>
				<th style="text-align: left;"><?= $fExecution[$data->frequency_execution]; ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Periode</th>
				<th>:</th>
				<th style="text-align: left;"><?= date_format(date_create($data->periode), 'M, Y'); ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Checksheet Name</th>
				<th>:</th>
				<th style="text-align: left;"><?= $data->checksheet_name; ?></th>
			</tr>
			<tr>
				<th style="text-align: left;">Frequency Checking</th>
				<th>:</th>
				<th style="text-align: left;"><?= $fChecking[$data->frequency_checking]; ?></th>
			</tr>
		</thead>
	</table>
	<hr>
	<h3>List Checksheets</h3>
	<table border="1" style="width:100%;border-collapse: collapse;">
		<thead class="table-light">
			<tr>
				<th rowspan="2" class="p-2" width="50">No</th>
				<th rowspan="2" class="p-2" width="">Items</th>
				<th rowspan="2" class="p-2" width="">Standard</th>
				<th colspan="<?= $count; ?>" class="p-2 text-center">Result <?= $name_col; ?></th>
			</tr>
			<tr>
				<?php for ($i = 1; $i <= $count; $i++) : ?>
					<th class="text-center"><?= $i; ?></th>
				<?php endfor; ?>
			</tr>
		</thead>
		<tbody>
			<?php $n = 0;
			if ($details) foreach ($details as $it) : $n++; ?>
				<tr>
					<td>
						<?= $n; ?>
					</td>
					<td><?= $it->item_name; ?></td>
					<td><?= $it->standard_check; ?></td>
					<?php for ($i = 1; $i <= $count; $i++) : ?>
						<?php $nn = "n" . $i; ?>
						<?php $Nn = "note" . $i; ?>
						<td class="<?= ($it->$nn == '') ? 'bg-light' : ''; ?>">
							<?php if ($it->check_type == 'boolean') : ?>
								<?php if ($it->$nn == 'no') : ?>
									<label for="" class="label-danger label"><?= ucfirst($it->$nn); ?></label>
									<?php if (isset($ArrNotes[$it->id]->$Nn)) : ?>
										<div class="alert alert-light p-2 my-1 font-italic" role="alert">
											<?= $ArrNotes[$it->id]->$Nn; ?>
										</div>
									<?php endif; ?>
								<?php elseif ($it->$nn == 'yes') : ?>
									<label for="" class="label-success label"><?= ucfirst($it->$nn); ?></label>
								<?php endif; ?>
							<?php else : ?>
								<?= ($it->$nn) ?: ''; ?>
							<?php endif; ?>
						</td>
					<?php endfor; ?>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1 text-right" width="">Execution By</th>
				<?php
				$day = 'day';
				$date = 'date';
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck = $day . $i;
					$dateCheck = $date . $i;
				?>
					<td class="text-muted p-1">
						<small for="">
							<?= isset($ArrExe[$data->id]->$dayCheck) ? $ArrUsers[$ArrExe[$data->id]->$dayCheck] . " | " : ''; ?>
						</small><small for="">
							<?= isset($ArrExeDate[$data->id]->$dateCheck) ? $ArrExeDate[$data->id]->$dateCheck : '' ?>
						</small>
					</td>
				<?php endfor; ?>
			</tr>
			<tr>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1" width=""></th>
				<th rowspan="" class="p-1 text-right" width="">Checker By</th>
				<?php
				$day = 'day';
				$date = 'date';
				for ($i = 1; $i <= $count; $i++) :
					$dayCheck = $day . $i;
					$dateCheck = $date . $i;
				?>
					<td class="text-muted p-1">
						<small for="">
							<?= isset($ArrCheck[$data->id]->$dayCheck) ? $ArrUsers[$ArrCheck[$data->id]->$dayCheck] . " | " : ''; ?>
						</small><small for="">
							<?= isset($ArrCheckDate[$data->id]->$dateCheck) ? $ArrCheckDate[$data->id]->$dateCheck : '' ?>
						</small>
					</td>
				<?php endfor; ?>
			</tr>
		</tfoot>
	</table>
</body>

</html>