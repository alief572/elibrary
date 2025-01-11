<div class="row">
	<div class="col-6">
		<table class="table table-borderless">
			<tbody>
				<tr>
					<td width="150">Nomor</td>
					<td width="10">:</td>
					<td width=""><?= $data->number; ?></td>
				</tr>
				<tr>
					<td>Kelompok</td>
					<td>:</td>
					<td><?= $data->group_name; ?></td>
				</tr>
				<tr>
					<td>Jenis Alat</td>
					<td>:</td>
					<td><?= $data->guide_detail_data_name; ?></td>
				</tr>
				<tr>
					<td width="">Metode</td>
					<td width="">:</td>
					<td width=""><?php if ($data->methode) foreach (json_decode($data->methode) as $mth) echo "<span class='badge badge-success'>$methode[$mth]</span> "; ?></td>
				</tr>
				<tr>
					<td>Referensi</td>
					<td>:</td>
					<td>
						<?php if ($data->reference) : ?>
							<ul style="list-style-type: none;" class="px-0">
								<?php foreach (json_decode($data->reference) as $ref) : ?>
									<li><?= (isset($ArrStd[$ref])) ? $ArrStd[$ref] : ''; ?></li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-6">
		<table class="table table-borderless">
			<tbody>
				<tr>
					<td width="150">Tanggal Terbit</td>
					<td width="10">:</td>
					<td><?= $data->publish_date; ?></td>
				</tr>
				<tr>
					<td>Tanggal Revisi</td>
					<td>:</td>
					<td><?= $data->revision_date; ?></td>
				</tr>
				<tr>
					<td>Nomor Revisi</td>
					<td>:</td>
					<td><?= $data->revision_number; ?></td>
				</tr>
			</tbody>
		</table>
		<table class="table table-sm">
			<thead class="table-light">
				<tr>
					<th class="text-center py-1">Sub Alat <span class="text-danger">*</span></th>
					<th class="text-center py-1">Rentang Ukur <span class="text-danger">*</span></th>
					<th class="text-center py-1">Ketidakpastian</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($ArrCombine) : ?>
					<?php foreach ($ArrCombine as $k => $rm) : reset($ArrCombine) ?>
						<tr>
							<td class="text-center"><?= (isset($ArrSubTools[$k]) && $ArrSubTools[$k] ? $ArrSubTools[$k] : ''); ?></td>
							<td class="text-center"><?= $k; ?></td>
							<td class="text-center"><?= $rm; ?></td>
						</tr>
				<?php endforeach;
				endif; ?>
			</tbody>
		</table>
	</div>
</div>
<hr>


<div class="d-flex justify-content-between align-items-center mb-2">
	<h5>Documents</h5>
</div>

<!-- Nav tabs -->
<ul class="nav nav-tabs nav-fill nav-success nav-pills mb-3 border-0" id="myTab" role="tablist">
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder active w-100" id="IK-tab" data-toggle="tab" data-target="#IK" type="button" role="tab" aria-controls="IK" aria-selected="true">IK</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="CMC-tab" data-toggle="tab" data-target="#CMC" type="button" role="tab" aria-controls="CMC" aria-selected="false">CMC</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="Template-tab" data-toggle="tab" data-target="#Template" type="button" role="tab" aria-controls="Template" aria-selected="false">Template</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="UBLK-tab" data-toggle="tab" data-target="#UBLK" type="button" role="tab" aria-controls="UBLK" aria-selected="false">UBLK</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="Sertifikat-tab" data-toggle="tab" data-target="#Sertifikat" type="button" role="tab" aria-controls="Sertifikat" aria-selected="false">Format Sertifikat</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="Analisa-tab" data-toggle="tab" data-target="#Analisa" type="button" role="tab" aria-controls="Analisa" aria-selected="false">Analisa Drift</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="SertCalibrator-tab" data-toggle="tab" data-target="#SertCalibrator" type="button" role="tab" aria-controls="SertCalibrator" aria-selected="false">Sertifikat Calibrator</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="CekAntara-tab" data-toggle="tab" data-target="#CekAntara" type="button" role="tab" aria-controls="CekAntara" aria-selected="false">Cek Antara</button>
	</li>
	<li class="nav-item" role="presentation">
		<button class="nav-link font-weight-bolder w-100" id="Video-tab" data-toggle="tab" data-target="#Video" type="button" role="tab" aria-controls="Video" aria-selected="false">Video</button>
	</li>
</ul>

<!-- Tab panes -->
<div class="tab-content ">
	<div class="tab-pane active" id="IK" role="tabpanel" aria-labelledby="IK-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocIK) $n = 0;
				foreach ($DocIK as $ik) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/IK/' . $ik->file); ?>#toolbar=0&navpanes=0"><?= $ik->name; ?></a>
						</td>
						<td class="text-center"><?= $ik->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="CMC" role="tabpanel" aria-labelledby="CMC-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocCMC) $n = 0;
				foreach ($DocCMC as $cmc) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/CMC/' . $cmc->file); ?>#toolbar=0&navpanes=0"><?= $cmc->name; ?></a>
						</td>
						<td class="text-center"><?= $cmc->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="Template" role="tabpanel" aria-labelledby="Template-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocTEMP) $n = 0;
				foreach ($DocTEMP as $temp) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/TEMPLATE/' . $temp->file); ?>#toolbar=0&navpanes=0"><?= $temp->name; ?></a>
						</td>
						<td class="text-center"><?= $temp->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="UBLK" role="tabpanel" aria-labelledby="UBLK-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocUBLK) $n = 0;
				foreach ($DocUBLK as $ublk) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/UBLK/' . $ublk->file); ?>#toolbar=0&navpanes=0"><?= $ublk->name; ?></a>
						</td>
						<td class="text-center"><?= $ublk->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="Sertifikat" role="tabpanel" aria-labelledby="Sertifikat-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocSERT) $n = 0;
				foreach ($DocSERT as $sert) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/FORMAT_SERTIFIKAT/' . $sert->file); ?>#toolbar=0&navpanes=0"><?= $sert->name; ?></a>
						</td>
						<td class="text-center"><?= $sert->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="Analisa" role="tabpanel" aria-labelledby="Analisa-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocDRIFT) $n = 0;
				foreach ($DocDRIFT as $drift) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/ANALISA_DRIFT/' . $drift->file); ?>#toolbar=0&navpanes=0"><?= $drift->name; ?></a>
						</td>
						<td class="text-center"><?= $drift->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="SertCalibrator" role="tabpanel" aria-labelledby="SertCalibrator-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocSERTCAL) $n = 0;
				foreach ($DocSERTCAL as $sertcal) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/SERTIFIKAT_KALIBRATOR/' . $sertcal->file); ?>#toolbar=0&navpanes=0"><?= $sertcal->name; ?></a>
						</td>
						<td class="text-center"><?= $sertcal->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="CekAntara" role="tabpanel" aria-labelledby="CekAntara-tab">
		<table class="table table-bordered table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocCEK) $n = 0;
				foreach ($DocCEK as $cek) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td>
							<a target="_blank" href="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/CEK_ANTARA/' . $cek->file); ?>#toolbar=0&navpanes=0"><?= $cek->name; ?></a>
						</td>
						<td class="text-center"><?= $cek->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="tab-pane" id="Video" role="tabpanel" aria-labelledby="Video-tab">
		<table class="table table-striped table-hover table-sm">
			<thead>
				<tr class="bg-success text-white">
					<th width="50" class="text-center p-2">No</th>
					<th width="180" class="p-2 text-center">Video</th>
					<th class="p-2">Name File</th>
					<th width="150" class="text-center p-2">Last Update</th>
				</tr>
			</thead>
			<tbody>
				<?php if ($DocVID) $n = 0;
				foreach ($DocVID as $vid) : $n++; ?>
					<tr>
						<td class="text-center"><?= $n; ?></td>
						<td class="text-center cursor-pointer view_video" data-id="<?= $vid->id; ?>">
							<video controlsList="nodownload" oncontextmenu="return false" height="80">
								<source src="<?= base_url('/directory/MASTER_GUIDES/' . $data->company_id . '/VIDEO/' . $vid->file); ?>#t=5" type="video/mp4" />
							</video>
						</td>
						<td><a href="javascript:void(0)" data-id="<?= $vid->id; ?>" class="text-dark view_video">
								<h4><?= $vid->name; ?></h4>
							</a>
						</td>
						<td class="text-center"><?= $vid->created_at; ?></td>
						
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>


<!-- <div class="row">
	<div class="col-lg-3">
		<label class="control-label">Document</label>
		<div class="">
			<?php if ($data->document) : ?>
				<input type="hidden" name="document" id="document" value="<?= $data->document; ?>">
				<input type="hidden" name="company" id="company" value="<?= $data->company_id; ?>">
				<canvas class="border border-2 rounded-lg cursor-pointer pdf-preview" title="<?= $data->guide_detail_data_name; ?>" id="pdf-preview" width="150" height="194" style="display: none;">
				</canvas>
				<img height="120" id="no-file" style="display: inline-block;" class="border border-2 rounded-lg" src="<?= base_url(); ?>directory/no-file.png" alt="">
			<?php else : ?>
				<p>-</p>
			<?php endif; ?>
		</div>

	</div>
	<div class="col-lg-6">
		<div class="row">
			<label class="col-2 control-label">Video</label>
			<div class="col-8">
				<?php if ($data->video) : ?>
					<video controls width="320" height="180" controlsList="nodownload" oncontextmenu="return false">
						<source src="<?= base_url('directory/MASTER_GUIDES/VIDEO/' . $data->company_id . '/') . $data->video; ?>">
					</video>
				<?php else : ?>
					<p>-</p>
				<?php endif; ?>
			</div>
		</div>

	</div>
</div> -->



<script>
	$(document).ready(function() {
		const document = $('#document').val()
		const company = $('#company').val()
		const file = siteurl + 'directory/MASTER_GUIDES/' + company + '/' + document;
		// console.log(document)
		// console.log(company)
		// console.log(file)

		var http = new XMLHttpRequest();
		http.open('HEAD', file, false);
		http.send();
		if (http.status != 404) {
			fetch(file)
				.then((res) => res.blob())
				.then((myBlob) => {
					// console.log(myBlob);
					// logs: Blob { size: 1024, type: "image/jpeg" }
					myBlob.name = document;
					myBlob.lastModified = new Date();
					// console.log(myBlob instanceof File);
					// logs: false
					_OBJECT_URL = URL.createObjectURL(myBlob)
					// console.log(_OBJECT_URL);
					showPDF(_OBJECT_URL);
				});
		}

	})
	$(document).on('click', '.pdf-preview', function() {
		const document = $('#document').val()
		const company = $('#company').val()
		const url = siteurl + 'directory/MASTER_GUIDES/' + company + '/' + document;
		fetch(url)
			.then((res) => res.blob())
			.then((myBlob) => {
				// console.log(myBlob);
				// logs: Blob { size: 1024, type: "image/jpeg" }
				myBlob.name = document;
				myBlob.lastModified = new Date();
				// console.log(myBlob instanceof File);
				// logs: false
				var file = new Blob([myBlob], {
					type: 'application/pdf'
				});
				_OBJECT_URL = URL.createObjectURL(myBlob)
				// console.log(_OBJECT_URL);
				window.open(_OBJECT_URL + '#toolbar=0');
			});
	})

	var _PDF_DOC,
		_CANVAS = document.querySelector('#pdf-preview'),
		_OBJECT_URL;

	function showPDF(pdf_url) {

		PDFJS.getDocument({
			url: pdf_url
		}).then(function(pdf_doc) {
			_PDF_DOC = pdf_doc;

			// Show the first page
			showPage(1);

			// destroy previous object url
			URL.revokeObjectURL(_OBJECT_URL);
		}).catch(function(error) {
			// trigger Cancel on error
			$("#cancel-pdf").click();

			// error reason
			alert(error.message);
		});;
	}

	function showPage(page_no) {
		var _CANVAS = document.querySelector('#pdf-preview');
		// fetch the page
		// console.log(page_no);
		// console.log(_PDF_DOC.getPage(page_no));
		_PDF_DOC.getPage(page_no).then(function(page) {
			// set the scale of viewport
			var scale_required = _CANVAS.width / page.getViewport(1).width;

			// get viewport of the page at required scale
			var viewport = page.getViewport(scale_required);

			// set canvas height
			_CANVAS.height = viewport.height;

			var renderContext = {
				canvasContext: _CANVAS.getContext('2d'),
				viewport: viewport
			};

			// render the page contents in the canvas
			page.render(renderContext).then(function() {
				$("#pdf-preview").css('display', 'inline-block');
				$("#no-file").css('display', 'none');
			});
		});
	}
</script>