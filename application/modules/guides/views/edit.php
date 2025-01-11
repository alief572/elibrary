<style>
	video::-internal-media-controls-download-button {
		display: none;
	}

	video::-webkit-media-controls-enclosure {
		overflow: hidden;
	}

	video::-webkit-media-controls-panel {
		width: calc(100% + 30px);
		/* Adjust as needed */
	}
</style>

<form id="form-upload" enctype="multipart/form-data">
	<div class="content d-flex flex-column flex-column-fluid p-0">
		<div class="d-flex flex-column-fluid justify-content-between align-items-top">
			<div class="container mt-10">
				<div class="card">
					<div class="card-header py-">
						<h3 class="card-title m-0">Edit</h3>
					</div>
					<div class="card-body">
						<input type="hidden" name="id" value="<?= $data->id; ?>">
						<input type="hidden" name="guide_detail_id" value="<?= $data->guide_detail_id; ?>">
						<div class="row pb-5">
							<div class="col-md-6">
								<div class="row mb-3">
									<label class="col-4 col-form-label">Nomor <span class="text-danger">*</span></label>
									<div class="col-8">
										<input type="text" name="number" id="number" value="<?= $data->number; ?>" placeholder="Nomor" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-4 col-form-label">Kelompok <span class="text-danger">*</span></label>
									<div class="col-8">
										<select name="group_id" id="group_id" disabled class="form-control form-control-solid">
											<option value=""></option>
											<?php if ($group_tools) foreach ($group_tools as $grp) : ?>
												<option value="<?= $grp->id; ?>" <?= ($data->group_id == $grp->id) ? 'selected' : ''; ?>><?= $grp->name; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-4 col-form-label">Jenis Alat <span class="text-danger">*</span></label>
									<div class="col-8">
										<input type="text" name="name" id="name" value="<?= $data->name; ?>" placeholder="Jenis Alat" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-4 col-form-label">Metode <span class="text-danger">*</span></label>
									<div class="col-8">
										<select name="methode[]" id="methode" class="form-control select2" multiple data-placeholder="Choose an options" data-allow-clear="true">
											<option value="INS" <?= (in_array('INS', json_decode($data->methode))) ? 'selected' : ''; ?>>Insitu</option>
											<option value="LAB" <?= (in_array('LAB', json_decode($data->methode))) ? 'selected' : ''; ?>>Inlab</option>
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-4 col-form-label">Reference <span class="text-danger">*</span></label>
									<div class="col-8">
										<select name="reference[]" id="reference" class="form-control select2" multiple data-placeholder="Choose an options" data-allow-clear="true">
											<?php if ($references) foreach ($references as $ref) : ?>
												<option value="<?= $ref->id; ?>" <?= (in_array($ref->id, json_decode($data->reference))) ? 'selected' : ''; ?>><?= $ref->alias; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>

							</div>
							<div class="col-6">
								<div class="row mb-3">
									<label class="col-4 col-form-label">Tanggal Terbit</label>
									<div class="col-8">
										<input type="text" name="publish_date" id="publish_date" value="<?= ($data->publish_date) ? date_format(date_create($data->publish_date), 'd/m/Y') : ''; ?>" placeholder="dd/mm/yyyy" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-4 col-form-label">Tanggal Revisi</label>
									<div class="col-8">
										<input type="text" name="revision_date" id="revision_date" value="<?= ($data->revision_date) ? date_format(date_create($data->revision_date), 'd/m/Y') : ''; ?>" placeholder="dd/mm/yyyy" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-4 col-form-label">Nomor Revisi</label>
									<div class="col-8">
										<input type="text" name="revision_number" id="revision_number" value="<?= ($data->revision_number) ?: ''; ?>" placeholder="Nomor" class="form-control">
									</div>
								</div>
								<div class="row mb-3">
									<table id="list-range" class="table table-bordered table-sm table-condensed">
										<thead class="table-light">
											<tr>
												<th class="text-center py-1">Sub Alat <span class="text-danger">*</span></th>
												<th class="text-center py-1">Rentang Ukur <span class="text-danger">*</span></th>
												<th class="text-center py-1">Ketidakpastian</th>
												<th class="text-center py-1">Opsi</th>
											</tr>
										</thead>
										<tbody>
											<?php if (count($ArrRange) > 0) : ?>
												<?php foreach ($ArrRange as $k => $rm) : ?>
													<tr>
														<td><input type="text" name="sub_tools[]" value="<?= (isset($ArrSubTools[$k]) && $ArrSubTools[$k] ? $ArrSubTools[$k] : ''); ?>" class="form-control border-0 mb-0 p-1"></td>
														<td><input type="text" name="range_measure[]" value="<?= $rm; ?>" placeholder="0mm - 0mm" class="form-control border-0 mb-0 p-1"></td>
														<td><input type="text" name="uncertainty[]" value="<?= ($ArrUncert[$k]); ?>" placeholder="0mm" class="form-control border-0 mb-0 p-1"></td>
														<td class="text-center"><button type="button" class="btn btn-icon btn-xs btn-light-danger remove-range-list"><i class="fa fa-times fa-sm"></i></button></td>
													</tr>
											<?php endforeach;
											endif; ?>
										</tbody>
									</table>
									<button type="button" id="add-range" class="btn btn-info btn-sm px-2 py-1"><i class="fa fa-plus fa-sm"></i> Add Range</button>
								</div>
							</div>
						</div>

						<div class="d-flex justify-content-between align-items-center mb-2">
							<h5>Documents</h5>
							<button type="button" class="btn btn-outline-warning add-file"><i class="fa fa-upload" aria-hidden="true"></i> Upload File</button>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<!-- <button type="button" class="btn btn-xs btn-warning edit-ik btn-icon" data-id="<?= $ik->file; ?>"><i class="fa fa-edit"></i></button> -->
													<button type="button" class="btn btn-xs btn-danger delete-ik btn-icon" data-id="<?= $ik->id; ?>" data-file="<?= $ik->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-cmc btn-icon" data-id="<?= $cmc->id; ?>" data-file="<?= $cmc->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-temp btn-icon" data-id="<?= $temp->id; ?>" data-file="<?= $temp->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-ublk btn-icon" data-id="<?= $ublk->id; ?>" data-file="<?= $ublk->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-sert btn-icon" data-id="<?= $sert->id; ?>" data-file="<?= $sert->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-drift btn-icon" data-id="<?= $drift->id; ?>" data-file="<?= $drift->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-sertcal btn-icon" data-id="<?= $sertcal->id; ?>" data-file="<?= $sertcal->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-cek btn-icon" data-id="<?= $cek->id; ?>" data-file="<?= $cek->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
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
											<th width="100" class="text-center p-2">Opsi</th>
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
												<td class="text-center">
													<button type="button" class="btn btn-xs btn-danger delete-vid btn-icon" data-id="<?= $vid->id; ?>" data-file="<?= $vid->file; ?>"><i class="fa fa-trash"></i></button>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>

						<div class="text-center">
							<button type="button" class="btn btn-success btn-lg px-5 save-files"><i class="fa fa-save"></i>Save</button>
							<a href="<?= base_url($this->uri->segment(1) . '?d=' . $detail->guide_id . '&sub=' . $detail->id); ?>" class="btn btn-danger btn-lg px-5"><i class="fa fa-reply"></i>Back</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header py-3">
					<h5 class="modal-title"><i class="fa fa-upload" aria-hidden="true"></i> Upload File</h5>
					<button type="button" class="btn btn-xs btn-icon btn-default text-secondary" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></span>
					</button>
				</div>
				<div class="modal-body pb-3">
					<!-- Nav tabs -->
					<ul class="nav nav-tabs nav-pills nav-success border-0" id="navId" role="tablist">
						<li class="nav-item">
							<button type="button" data-target="#tab1Id" class="nav-link font-weight-bolder active" data-toggle="tab" aria-current="page">IK</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab2Id" class="nav-link font-weight-bolder" data-toggle="tab">CMC</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab3Id" class="nav-link font-weight-bolder" data-toggle="tab">Template</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab4Id" class="nav-link font-weight-bolder" data-toggle="tab">UBLK</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab5Id" class="nav-link font-weight-bolder" data-toggle="tab">Format Sertifikat</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab6Id" class="nav-link font-weight-bolder" data-toggle="tab">Analisa Drift</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab7Id" class="nav-link font-weight-bolder" data-toggle="tab">Sertifikat Calibrator</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab8Id" class="nav-link font-weight-bolder" data-toggle="tab">Cek Antara</button>
						</li>
						<li class="nav-item" role="presentation">
							<button type="button" data-target="#tab9Id" class="nav-link font-weight-bolder" data-toggle="tab">Video</button>
						</li>
					</ul>

					<!-- Tab panes -->

					<div class="tab-content mt-3 border rounded p-4 mb-3" id="myTabContent">

						<div class="tab-pane fade show active" id="tab1Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="ik-name" name="documents[ik_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-ik">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="ik-file" data-doc="ik" name="ik_file" accept="application/pdf" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-ik d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="ik"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger rounded-circle" data-doc="ik"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="ik-preview" class="d-none" width="150"></canvas>
										<!-- <input type="hidden" name="oldFiles[ik_file]" value=""> -->
										<!-- <input id="remove-document" name="remove-document" type="hidden"> -->
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab2Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="cmc-name" name="documents[cmc_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-cmc">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="cmc-file" name="cmc_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" data-doc="cmc" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-cmc d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="cmc"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="cmc"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="cmc-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab3Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="temp-name" name="documents[temp_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-temp">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="temp-file" name="temp_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroEnabled.12" data-doc="temp" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-temp d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="temp"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="temp"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="temp-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab4Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="ublk-name" name="documents[ublk_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-ublk">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="ublk-file" name="ublk_file" accept="application/pdf" data-doc="ublk" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-ublk d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="ublk"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="ublk"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="ublk-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="tab5Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="sert-name" name="documents[sert_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-sert">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="sert-file" name="sert_file" accept="application/pdf" data-doc="sert" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-sert d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="sert"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="sert"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="sert-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="tab6Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="drift-name" name="documents[drift_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-drift">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="drift-file" name="drift_file" accept="application/pdf" data-doc="drift" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-drift d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="drift"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="drift"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="drift-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="tab7Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="sertcal-name" name="documents[sertcal_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-sertcal">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="sertcal-file" name="sertcal_file" accept="application/pdf" data-doc="sertcal" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-sertcal d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="sertcal"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="sertcal"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="sertcal-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="tab8Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Document Name <span class="text-danger">*</span></label>
								<input type="text" id="cek-name" name="documents[cek_name]" placeholder="Document Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Document <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-cek">
											<i class="fa fa-upload"></i>
											<p>Choose an PDF file or drag it here.</p>
										</div>
										<input type="file" id="cek-file" name="cek_file" accept="application/pdf" data-doc="cek" class="dropzone dropzone-1 drop-file" />
										<div class="for-delete-cek d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle" data-doc="cek"><i class="fa fa-edit"></i></button>
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger remove-file rounded-circle" data-doc="cek"><i class="fa fa-trash"></i></button>
											</div>
										</div>
										<canvas id="cek-preview" class="d-none" width="150"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="tab9Id" role="tabpanel">
							<div class="form-group mb-3">
								<label for="form-label">Video Name <span class="text-danger">*</span></label>
								<input type="text" id="video-name" name="documents[video_name]" placeholder="Video Name" class="form-control">
							</div>
							<div class="form-group mb-0">
								<label class="col-form-label">Upload Video <span class="text-danger">*</span></label>
								<div class="">
									<div class="dropzone-wrapper mr-2 d-flex justify-content-center align-items-center" style="height: 250px;">
										<div class="dropzone-desc dropzone-desc-video">
											<i class="fa fa-upload"></i>
											<p>Choose an Video file or drag it here.</p>
										</div>
										<input type="file" id="video-file" name="video_file" accept="video/mp4" class="dropzone dropzone-1 drop-video" />
										<!-- <input class="remove-video" name="remove-video" type="hidden"> -->
										<video id="video-preview" width="290" class="d-none" controlsList="nodownload" oncontextmenu="return false" height="180">
										</video>
										<div class="for-delete-video d-none">
											<div class="middle d-flex justify-content-center align-items-center">
												<!-- <button type="button" class="btn btn-sm mr-1 btn-icon btn-warning change-image rounded-circle"><i class="fa fa-edit"></i></button> -->
												<button type="button" class="btn btn-sm mr-1 btn-icon btn-danger rounded-circle" id="remove-video"><i class="fa fa-trash"></i></button>
												<!-- <button type="button" class="btn btn-sm btn-icon mb-3 btn-light-danger <?= ($data->video) ? '' : 'd-none'; ?>"><i class="fa fa-times"></i></button> -->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer py-2">
						<button type="button" class="btn btn-primary w-100px" data-dismiss="modal">OK</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<!-- Modal -->
<div class="modal fade" id="modelVideo" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true" style="background-color: rgba(0,0,0,0.9);">
	<div class="modal-dialog align-items-center modal-xl justify-content-center" role="document">
		<div class="modal-content bg-transparent">
			<div class="modal-header bg-transparent py-2 px-0">
				<span>&nbsp;</span>
				<button type="button" onclick="$('.modal-video').html('')" class="btn btn-icon btn-xs" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true"><i class="fa fa-times text-white" aria-hidden="true"></i></span>
				</button>
			</div>
			<div class="modal-video"></div>
		</div>
	</div>
</div>
<style>
	span.selection span.select2-selection.select2-selection--single.is-invalid,
	span.selection span.select2-selection.select2-selection--multiple.is-invalid {
		border-color: #f64e60;
		padding-right: calc(1.5em + 1.3rem);
		background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23F64E60' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23F64E60' stroke='none'/%3e%3c/svg%3e");
		background-repeat: no-repeat;
		background-position: right calc(0.375em + 0.325rem) center;
		background-size: calc(0.75em + 0.65rem) calc(0.75em + 0.65rem);
	}
</style>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			width: '100%',
			placeholder: 'Choose an options'
		})

		/* DATEPICKER */
		$('#publish_date,#revision_date').flatpickr({
			dateFormat: "d/m/Y",
			static: true
		})

	})

	$(document).on('click', '#add-range', function() {
		const element = `
		<tr>
			<td>
				<input type="text" name="sub_tools[]" placeholder="Name" class="form-control border-0 mb-0 p-1">
			</td>
			<td>
				<input type="text" name="range_measure[]" placeholder="0mm - 0mm" class="form-control border-0 mb-0 p-1">
			</td>
			<td>
				<input type="text" name="uncertainty[]" placeholder="0mm" class="form-control border-0 mb-0 p-1">
			</td>
			<td class="text-center"><button type="button" class="btn btn-xs btn-light-danger btn-icon remove-range-list"><i class="fa fa-times fa-sm"></i></button></td>
		</tr>
		`;
		$('table#list-range tbody').append(element);
	})

	$(document).on('click', '.remove-range-list', function() {
		$(this).parents('tr').remove();
	})

	$(document).on('click', '.save-files', function() {
		let name = $('#name').val()
		let group_id = $('#group_id').val()
		let methode = $('#methode').val()
		let reference = $('#reference').val()
		let range_measure = $('input[name="range_measure[]"]');
		let publish_date = $('#publish_date').val()
		let revision_date = $('#revision_date').val()
		let revision_number = $('#revision_number').val()
		let btn = $(this)
		let c = 0;

		$('select#group_id').next().find('span.selection .select2-selection.select2-selection--single').removeClass('is-invalid')
		if (!group_id) {
			$('select#group_id').next().find('span.selection .select2-selection.select2-selection--single').addClass('is-invalid')
			c++
		}

		$('#name').removeClass('is-invalid')
		if (!name) {
			$('#name').addClass('is-invalid')
			c++
		}

		range_measure.each(function() {
			$(this).removeClass('is-invalid')
			if ($(this).val().length == 0) {
				$(this).addClass('is-invalid')
				c++
			}
		})


		$('#publish_date').removeClass('is-invalid')
		if (!publish_date) {
			$('#publish_date').addClass('is-invalid')
			c++;
		}

		$('select#methode').next().find('span.selection .select2-selection.select2-selection--multiple').removeClass('is-invalid')
		if (methode == '') {
			$('select#methode').next().find('span.selection .select2-selection.select2-selection--multiple').addClass('is-invalid')
			c++;
		}

		$('select#reference').next().find('span.selection .select2-selection.select2-selection--multiple').removeClass('is-invalid')
		if (reference == '') {
			$('select#reference').next().find('span.selection .select2-selection.select2-selection--multiple').addClass('is-invalid')
			c++;
		}



		/* IK */
		const ik_doc = $('#ik-file').val()
		const ik_name = $('#ik-name').val()

		$('#ik-name').removeClass('is-invalid')
		if (ik_doc && !ik_name) {
			$('#ik-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name IK can't be empty.", 'warning', 3000)
			c++;
		}

		/* CMC */
		const cmc_doc = $('#cmc-file').val()
		const cmc_name = $('#cmc-name').val()

		$('#cmc-name').removeClass('is-invalid')
		if (cmc_doc && !cmc_name) {
			$('#cmc-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name CMC can't be empty.", 'warning', 3000)
			c++;
		}

		/* Template */
		const temp_doc = $('#temp-file').val()
		const temp_name = $('#temp-name').val()

		$('#temp-name').removeClass('is-invalid')
		if (temp_doc && !temp_name) {
			$('#temp-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name Template can't be empty.", 'warning', 3000)
			c++;
		}

		/* UBLK */
		const ublk_doc = $('#ublk-file').val()
		const ublk_name = $('#ublk-name').val()

		$('#ublk-name').removeClass('is-invalid')
		if (ublk_doc && !ublk_name) {
			$('#ublk-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name UBLK can't be empty.", 'warning', 3000)
			c++;
		}

		/* SERTIFIKAT */
		const sert_doc = $('#sert-file').val()
		const sert_name = $('#sert-name').val()

		$('#sert-name').removeClass('is-invalid')
		if (sert_doc && !sert_name) {
			$('#sert-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name Format Sertifikat can't be empty.", 'warning', 3000)
			c++;
		}

		/* Analisa Drift */
		const drift_doc = $('#drift-file').val()
		const drift_name = $('#drift-name').val()

		$('#drift-name').removeClass('is-invalid')
		if (drift_doc && !drift_name) {
			$('#drift-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name Analisa Drift can't be empty.", 'warning', 3000)
			c++;
		}

		/* Sertifikat Kalibrator */
		const sertcal_doc = $('#sertcal-file').val()
		const sertcal_name = $('#sertcal-name').val()

		$('#sertcal-name').removeClass('is-invalid')
		if (sertcal_doc && !sertcal_name) {
			$('#drift-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name Sertifikat Kalibrator can't be empty.", 'warning', 3000)
			c++;
		}

		/* Cek Antara */
		const cek_doc = $('#cek-file').val()
		const cek_name = $('#cek-name').val()

		$('#cek-name').removeClass('is-invalid')
		if (cek_doc && !cek_name) {
			$('#drift-name').addClass('is-invalid')
			Swal.fire('Warning!', "Document Name Cek Antara can't be empty.", 'warning', 3000)
			c++;
		}

		/* VIDEO */
		const video_file = $('#video-file').val()
		const video_name = $('#video-name').val()

		$('#video-name').removeClass('is-invalid')
		if (video_file && !video_name) {
			$('#video-name').addClass('is-invalid')
			Swal.fire('Warning!', "Video Name can't be empty.", 'warning', 3000)
			c++;
		}
		if (c > 0) {
			return false;
		}

		const formdata = new FormData($('#form-upload')[0])
		$.ajax({
			url: siteurl + active_controller + 'upload_document',
			type: 'POST',
			dataType: 'JSON',
			data: formdata,
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				btn.html('<i class="spinner-border spinner-border" aria-hidden="true"></i>Loading...').prop('disabled', true)
			},
			complete: function() {
				btn.html('<i class="fa fa-save" aria-hidden="true"></i>Save').prop('disabled', false)
			},
			success: function(result) {
				if (result.status == 1) {
					Swal.fire("Success!", result.msg, "success", 3000).then(function() {
						location.reload()
					})
				} else {
					Swal.fire("Warning!", result.msg, "warning", 3000)
				}
			},
			error: function(result) {
				Swal.fire("Error!", "Server time out.", "error", 3000)

			}
		})
	})

	$(document).on('click', '.add-file', function() {
		$('#modelId').modal('show')
	})

	$(document).on('click', '.view_video', function() {
		let id = $(this).data('id')
		$('#modelVideo .modal-video').load(siteurl + active_controller + 'view_video/' + id)
		$('#modelVideo').modal('show')
	})

	/* DROPZONE */
	var _PDF_DOC,
		_OBJECT_URL;
	/* Selected File has changed */
	$(document).on('change', ".drop-file", function() {

		// user selected file
		var file = $(this)[0].files[0];
		var doc = $(this).data('doc')
		// allowed MIME types

		var mime_types = ['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.sheet.macroEnabled.12'];
		// Validate whether PDF
		if (mime_types.indexOf(file.type) == -1) {
			alert('Error : Incorrect file type');
			return;
		}

		// validate file size
		if (file.size > 50 * 1024 * 1024) {
			alert('Error : Exceeded size 50MB');
			return;
		}

		// validation is successful

		// hide upload dialog button
		// $("#upload-dialog").css('display', 'none');

		// set name of the file
		// $("#pdf-name").text(file.name);
		// $("#pdf-name").css('display', 'inline-block');

		// show cancel and upload buttons now
		// $("#cancel-pdf").removeClass('d-none');
		// $("#remove-file").removeClass('d-none');
		// $("#upload-button").css('display', 'inline-block');

		// Show the PDF preview loader
		// $("#pdf-loader").css('display', 'inline-block');

		// object url of PDF 
		// console.log(file);
		_OBJECT_URL = URL.createObjectURL(file)

		// send the object url of the pdf to the PDF preview function
		if (file.type == 'application/pdf') {
			showPDF(_OBJECT_URL, doc);
		} else {
			drawImage(doc);
		}
	});

	$(document).on('click', '.change-image', function() {
		let doc = $(this).data('doc')
		$('#' + doc + '-file').click()
	})

	/* Reset file input */
	$(document).on('click', "#cancel-pdf,.remove-file", function() {
		let doc = $(this).data('doc')
		// $("#remove-document").val('x');
		$("#" + doc + "-preview").addClass('d-none');
		$(".for-delete-" + doc).addClass('d-none');
		$('.dropzone-desc-' + doc).removeClass('d-none')

		// reset to no selection
		$("#" + doc + "-file").val('');

		// show upload dialog button
		// $("#upload-dialog").css('display', 'inline-block');

		// reset to no selection
		// $("#pdf-file").val('');

		// hide elements that are not required
		// $("#pdf-name").css('display', 'none');
		// $("#pdf-preview").css('display', 'none');
		// $("#pdf-loader").css('display', 'none');
		// $("#cancel-pdf").addClass('d-none');
		// $("#upload-button").css('display', 'none');
	});

	$(document).on('change', '.drop-video', function() {
		let file = $(this)[0].files[0];

		// allowed MIME types
		var mime_types = ['video/mp4'];

		// Validate whether PDF
		if (mime_types.indexOf(file.type) == -1) {
			Swal.fire('Warning', 'Error : Incorrect file type', 'warning', 3000)
			return;
		}

		// validate file size
		if (file.size > 50 * 1024 * 1024) {
			Swal.fire('Warning', 'Error : Exceeded size 50MB', 'warning', 3000)
			return;
		}

		let blobURL = URL.createObjectURL(file);
		$("#video-preview").attr('src', blobURL).removeClass('d-none');
		$('.for-delete-video').removeClass('d-none');
		$('.dropzone-desc-video').addClass('d-none')
	})

	$(document).on('click', '#remove-video', function() {
		$('.for-delete-video').addClass('d-none');
		$("#video-preview").attr('src', '').addClass('d-none');
		$("#video-file").val('');
		$('.dropzone-desc-video').removeClass('d-none')
	})

	function showPDF(pdf_url, doc) {
		PDFJS.getDocument({
			url: pdf_url
		}).then(function(pdf_doc) {
			_PDF_DOC = pdf_doc;

			// Show the first page
			showPage(1, doc);

			// destroy previous object url
			URL.revokeObjectURL(_OBJECT_URL);
		}).catch(function(error) {
			// trigger Cancel on error
			// $("#cancel-pdf").click();

			// error reason
			alert(error.message);
		});;
	}


	/* DELETE FILE */

	// IK
	$(document).on('click', '.delete-ik', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-cmc', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-temp', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-ublk', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-sert', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-drift', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-sertcal', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-cek', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	$(document).on('click', '.delete-vid', function() {
		let id = $(this).data('id');
		if (id) {
			Swal.fire({
				title: 'Confirm!',
				text: 'Are you sure you want to delete this document?',
				icon: 'question',
				showCancelButton: true,
			}).then((value) => {
				if (value.isConfirmed) {
					$.ajax({
						url: siteurl + active_controller + 'delete_sub_document',
						dataType: 'JSON',
						type: 'POST',
						data: {
							id
						},
						success: function(result) {
							if (result.status == 1) {
								Swal.fire("Success!", result.msg, "success", 3000).then(function() {
									location.reload()
								})
							} else {
								Swal.fire("Warning!", result.msg, "warning", 3000)
							}
						},
						error: function(result) {
							Swal.fire("Error!", "Server time out.", "error", 3000)

						}
					})
				}
			})
		}
	})

	function showPage(page_no, doc) {
		var _CANVAS = document.querySelector('#' + doc + '-preview')
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
				$('#' + doc + '-preview').removeClass('d-none');
				$('.for-delete-' + doc).removeClass('d-none');
				$('.dropzone-desc-' + doc + '').addClass('d-none')
				// $("#pdf-loader").css('display', 'none');
			});
		});
	}

	function showImage(file, doc) {
		var _CANVAS = document.querySelector('#' + doc + '-preview')
		// _CANVAS.src = src;
		// preview.style.display = "block";


		var scale_required = _CANVAS.width / file.getViewport(1).width;

		// get viewport of the page at required scale
		var viewport = file.getViewport(scale_required);

		// set canvas height
		_CANVAS.height = viewport.height;

		var renderContext = {
			canvasContext: _CANVAS.getContext('2d'),
			viewport: viewport
		};

		// render the page contents in the canvas
		file.render(renderContext).then(function() {
			$('#' + doc + '-preview').removeClass('d-none');
			$('.for-delete-' + doc).removeClass('d-none');
			$('.dropzone-desc-' + doc + '').addClass('d-none')
		});
	}

	function drawImage(doc) {
		var _CANVAS = $('#' + doc + '-preview')[0].getContext("2d"),
			img = new Image();
		img.onload = function() {
			_CANVAS.drawImage(img, -30, 0, 220, 150);
			$('#' + doc + '-preview').removeClass('d-none');
			$('.for-delete-' + doc).removeClass('d-none');
			$('.dropzone-desc-' + doc + '').addClass('d-none')
		};
		img.src = siteurl + "assets/images/excel.png";
	}
</script>