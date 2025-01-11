<style>
	.dataTables_scroll {
		margin: 0px !important;
	}
</style>
<div class="content d-flex flex-column flex-column-fluid p-0">
	<div class="d-flex flex-column-fluid justify-content-between align-items-top">
		<div class="container">
			<div class="d-flex align-items-baseline flex-wrap mr-5 mb-10">
				<a href="<?= base_url('dashboard'); ?>">
					<h4 class="text-dark font-weight-bold my-1 mr-2"><i class="fa fa-home"></i></h4>
				</a>
				<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
					<?php if (isset($Breadcumb)) : foreach ($Breadcumb as $bc) : ?>
							<li class="breadcrumb-item text-muted">
								<a href="" class="text-muted"><?= $bc->name; ?></a>
							</li>
					<?php endforeach;
					endif; ?>
					<li class="breadcrumb-item text-muted">
						<a href="" class="text-muted"><?= $thisData->name; ?></a>
					</li>
				</ul>
			</div>
			<h1 class="text-white fa-3x"><?= $thisData->name; ?></h1>
			<div class="row mb-10">
				<div class="col-md-4">
					<input type="text" name="serarch" id="search" placeholder="Pencarian" class="form-control rounded form-control-sm">
				</div>
			</div>
			<div class="row">
				<div class="col-md-9">

					<?php if (isset($ArrDataLink[$thisData->id])) : ?>
						<div class="row mb-10">
							<?php
							foreach ($ArrDataLink[$thisData->id] as $link) : ?>
								<div class="col-md-6 mb-3">
									<a href="<?= $link->link; ?>" target="_blank" class="h4 btn btn-lg btn-outline-success d-block" title="<?= $link->name; ?>"><i class="fa fa-link"></i><?= $link->name; ?></a>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<ul class="nav nav-warning nav-pills nav-bolder" id="myTab2" role="tablist">
						<?php $n = 0;
						$thisCompany = '';
						if ($Data) :
							foreach ($Data as $dt) : $n++;
								$main = ($dt->parent_id == '0') ? true : false;
								if ($main == false) {
									$thisCompany = ($dt->company_id && $dt->company_id != $company) ? 'd-none' : '';
								} ?>
								<li class="nav-item mx-0">
									<a class="rounded-bottom-0 nav-link  <?= ($n == '1') ? 'active' : ''; ?>" id="tab_<?= $dt->id; ?>" data-toggle="tab" href="#data_<?= $dt->id; ?>">
										<span class="nav-icon ">
											<i class="fa fa-file-alt"></i>
										</span>
										<span class="text-white h5 my-0"><?= $dt->name; ?>
											<small class="">
												<div class="badge bg-white rounded-circle text-warning"><?= (isset($ArrDataFolder[$dt->id]) ? count($ArrDataFolder[$dt->id]) : 0) + (isset($ArrDataFile[$dt->id]) ? count($ArrDataFile[$dt->id]) : 0) + (isset($ArrDataFile[$thisData->id]) ? count($ArrDataFile[$thisData->id]) : 0); ?></div>
											</small>
										</span>
									</a>
								</li>
							<?php endforeach; ?>
						<?php else : ?>
							<li class="nav-item mx-0">
								<a class="rounded-bottom-0 nav-link active" id="tab_<?= $thisData->id; ?>" data-toggle="tab" href="#data_<?= $thisData->id; ?>">
									<span class="nav-icon ">
										<i class="fa fa-file-alt"></i>
									</span>
									<span class="text-white h5 my-0"><?= $thisData->name; ?>
										<small class="">
											<div class="badge bg-white rounded-circle text-warning"><?= isset($ArrDataFile[$thisData->id]) ? count($ArrDataFile[$thisData->id]) : "0" ?></div>
										</small>
									</span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
					<div class="card rounded-top-0 border-0" style="background-color: zrgba(255,255,255,0.85);">
						<div class="card-body py-3 ">
							<?php if (!$Data && !$DataFile) : ?>
								<div class="justify-content-center flex-column d-flex py-10">
									<img src="/assets/images/directory/not-found.png" alt="" class="img-cover justify-content-center m-auto" width="200px">
									<h3 class="text-center text-dark-50">File not found</h3>
								</div>
							<?php endif; ?>
							<div class="tab-content " id="myTabContent2">
								<?php $n = 0;
								$cek_company = '';
								if ($Data) :
									foreach ($Data as $dtl) :  $n++;
										$main = ($dtl->parent_id == '0') ? true : false;

										if ($main == false) :
											$cek_company = ($dtl->company_id && $dtl->company_id != $company) ? 'd-none' : '';
										endif; ?>

										<div class="tab-pane fade <?= ($n == '1') ? 'active show' : ''; ?>" id="data_<?= $dtl->id; ?>" role="tabpanel" aria-labelledby="tab_<?= $dtl->id; ?>">
											<?php if (isset($ArrDataFolder[$dtl->id])) : ?>
												<table class="table datatable table-hover">
													<thead>
														<tr>
															<th></th>
															<th></th>
														</tr>
													</thead>
													<tbody>
														<?php
														foreach ($ArrDataFolder[$dtl->id] as $list) : ?>
															<tr class="cursor-pointer <?= $cek_company; ?>" data-id="<?= $list->id; ?>" ondblclick="window.open(siteurl+active_controller+'manual/<?= $list->id; ?>','_self')">
																<td class="h6 text-right" width="50px"><i class="fa fa-folder fa-2x text-warning"></i></th>
																<td class="h4 font-weight-bolder pt-5 text-dark"><?= $list->name; ?></th>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											<?php endif; ?>

											<?php if (isset($ArrDataFile[$dtl->id])) : ?>
												<table class="table datatable table-condensed table-hover">
													<thead>
														<tr class="<?= $cek_company; ?>">
															<th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
															<th class="h5 border-2 border-bottom-secondary text-center">File Name</th>
															<th class="h5 border-2 border-bottom-secondary text-center" width="50px">View</th>
														</tr>
													</thead>
													<tbody>
														<?php $no = 0;
														foreach ($ArrDataFile[$dtl->id] as $list) : $no++; ?>
															<tr class="cursor-pointer" ondblclick="show('<?= $list->id; ?>')">
																<td class="h6 text-dark"><?= $no; ?></td>
																<td class="h6 text-dark"><?= $list->name; ?></td>
																<td class="h6 text-center"><i class="fa fa-eye text-dark"></i></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											<?php endif; ?>

											<?php if (!isset($ArrDataFolder[$dtl->id]) && !isset($ArrDataFile[$dtl->id])) : ?>
												<?php if (isset($ArrDataFile[$thisData->id])) :; ?>
													<table class="table datatable table-condensed table-hover">
														<thead>
															<tr class="<?= $cek_company; ?>">
																<th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
																<th class="h5 border-2 border-bottom-secondary text-center">File Name</th>
																<th class="h5 border-2 border-bottom-secondary text-center" width="50px">View</th>
															</tr>
														</thead>
														<tbody>
															<?php $no = 0;
															foreach ($ArrDataFile[$thisData->id] as $list) : $no++; ?>
																<tr class="cursor-pointer" ondblclick="show('<?= $list->id; ?>')">
																	<td class="h6 text-dark"><?= $no; ?></td>
																	<td class="h6 text-dark"><?= $list->name; ?></td>
																	<td class="h6 text-center"><i class="fa fa-eye text-dark"></i></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												<?php else : ?>
													<table class="table">
														<tr>
															<td colspan="2" class="text-center h4"><i>No data available</i></td>
														</tr>
													</table>
												<?php endif; ?>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								<?php else : ?>
									<div class="tab-pane fade active show" id="data_<?= $thisData->id; ?>" role="tabpanel" aria-labelledby="tab_<?= $thisData->id; ?>">
										<table class="table table-hover">
											<tbody>
												<?php
												if (isset($ArrDataFolder[$thisData->id])) :
													foreach ($ArrDataFolder[$thisData->id] as $list) : ?>
														<tr class="cursor-pointer <?= $cek_company; ?>" data-id="<?= $list->id; ?>" ondblclick="window.open(siteurl+active_controller+'<?= $list->id; ?>','_self')">
															<th class="h6 text-right" width="50px"><i class="fa fa-folder fa-2x text-warning"></i></th>
															<th class="h4 font-weight-bolder pt-5 text-dark"><?= $list->name; ?></th>
														</tr>
												<?php endforeach;
												endif; ?>
											</tbody>
										</table>

										<?php if (isset($ArrDataFile[$thisData->id])) :; ?>
											<table class="table table-condensed table-hover">
												<thead>
													<tr class="<?= $cek_company; ?>">
														<th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
														<th class="h5 border-2 border-bottom-secondary text-center">File Name</th>
														<th class="h5 border-2 border-bottom-secondary text-center" width="50px">View</th>
													</tr>
												</thead>
												<tbody>
													<?php $no = 0;
													foreach ($ArrDataFile[$thisData->id] as $list) : $no++; ?>
														<tr class="cursor-pointer" ondblclick="show('<?= $list->id; ?>')">
															<td class="h6 text-dark"><?= $no; ?></td>
															<td class="h6 text-dark"><?= $list->name; ?></td>
															<td class="h6 text-center"><i class="fa fa-eye text-dark"></i></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										<?php endif; ?>

										<?php if (!isset($ArrDataFolder[$thisData->id]) && !isset($ArrDataFile[$thisData->id])) : ?>
											<table class="table">
												<tr>
													<td colspan="2" class="text-center h4"><i>No data available</i></td>
												</tr>
											</table>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="card mt-15 border-0 shadow-lg" style="background-color: srgba(255,255,255,0.85);">
						<div class="card-body pt-5 px-5">
							<?php foreach ($MainData as $main) : ?>
								<div class="d-flex flex-center mb-3">
									<span class="align-self-stretch mr-2 my-1 "><i class="fa fa-link text-warning"></i></span>
									<div class="d-flex flex-column flex-grow-1">
										<a href="<?= base_url($this->uri->segment(1) . '/' . $main->id); ?>" class="text-dark text-hover-warning font-weight-bolder font-size-lg mb-1"><?= $main->name; ?></a>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width:90%">
			<div class="modal-content" data-scroll="true" data-height="700">
				<div class="modal-header">
					<h5 class="modal-title">View Document</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body pt-1" id="data-file">
					File not found
				</div>
				<div class="modal-footer py-2">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function show(id) {
			$('#modelId').modal('show')
			$('#data-file').load(siteurl + active_controller + 'show_manual/' + id)
		}

		$(document).ready(function() {
			$('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
				$.fn.dataTable.tables({
					visible: true,
					api: true
				}).columns.adjust();
			});

			$('.datatable').DataTable({
				orderCellsTop: false,
				fixedHeader: true,
				scrollX: true,
				ordering: false,
				info: false
			});
		})
	</script>