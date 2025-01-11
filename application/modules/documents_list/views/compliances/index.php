<div class="content d-flex flex-column flex-column-fluid p-0">
    <div class="d-flex flex-column-fluid justify-content-between align-items-top">
        <div class="container">
            <div class="d-flex align-items-baseline flex-wrap mr-5 mb-10">
                <a href="<?= base_url('dashboard'); ?>">
                    <h4 class="text-dark font-weight-bold my-1 mr-2"><i class="fa fa-home"></i></h4>
                </a>
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="text-muted">COMPLIANCES</a>
                    </li>
                </ul>
            </div>
            <h1 class="text-white fa-3x">COMPLIANCES</h1>
            <div class="row mb-10">
                <div class="col-md-4">
                    <input type="text" name="serarch" id="search" placeholder="Pencarian" class="form-control rounded form-control-sm">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-warning nav-pills nav-bolder" id="myTab2" role="tablist">
                        <li class="nav-item mx-0">
                            <a class="rounded-bottom-0 nav-link active" id="compliances" data-toggle="tab" href="#data_compliances">
                                <span class="nav-icon"><i class="fa fa-file-alt"></i></span>
                                <span class="text-white h5 my-0">Compliances
                                    <small class="">
                                        <div class="badge bg-white rounded-circle text-warning"><?= count($reference); ?></div>
                                    </small>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <div class="card rounded-top-0 border-0" style="background-color: zrgba(255,255,255,0.85);">
                        <div class="card-body py-3 ">
                            <?php if (!$reference) : ?>
                                <div class="justify-content-center flex-column d-flex py-10">
                                    <img src="/assets/images/directory/not-found.png" alt="" class="img-cover justify-content-center m-auto" width="200px">
                                    <h3 class="text-center text-dark-50">File not found</h3>
                                </div>
                            <?php endif; ?>
                            <div class="tab-content " id="myTabContent2">
                                <div class="tab-pane fade active show" id="data_compliances" role="tabpanel" aria-labelledby="tab_compliances">
                                    <table class="table table-condensed table-hover">
                                        <thead>
                                            <tr class="">
                                                <th class="h5 border-2 border-bottom-secondary" width="15px">No.</th>
                                                <th class="h5 border-2 border-bottom-secondary">File Name</th>
                                                <th class="h5 border-2 border-bottom-secondary text-center" width="50px"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $n = 0;
                                            if ($reference) foreach ($reference as $ref) :  $n++; ?>
                                                <tr class="cursor-pointer list-document">
                                                    <td class="h6 text-dark"><?= $n; ?></td>
                                                    <td class="h5 text-dark d-flex align-items-center my-0 py-2">
                                                        <i class="fa fa-file-alt text-primary fa-2x mr-2 pt-0"></i>
                                                        <strong class="mt-1">
                                                            <?= $ref->nm_perusahaan; ?>
                                                        </strong>
                                                    </td>
                                                    <td class="h6 text-center">
                                                        <div class="btn-action">
                                                            <button type="button" data-id="<?= $ref->id; ?>" class="btn btn-icon btn-xs view-compliance"><i class="fas fa-eye text-primary fa-2x"></i></button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-3">
					<div class="card mt-15 border-0 shadow-lg" style="background-color: srgba(255,255,255,0.85);">
						<div class="card-body pt-5 px-5">
							<div class="d-flex flex-center mb-3">
								<span class="align-self-stretch mr-2 my-1 "><i class="fa fa-link text-warning"></i></span>
								<div class="d-flex flex-column flex-grow-1">
									<a href="<?= base_url($this->uri->segment(1) . '/procedures'); ?>" class="text-dark text-hover-warning font-weight-bolder font-size-lg mb-1">PROSEDUR, FORM, IK DAN RECORD</a>
								</div>
							</div>
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
				</div> -->
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 90%;" role="document">
        <div class="modal-content" data-scroll="true" data-height="700">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
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

<style>
    .btn-action {
        display: none;
    }

    .list-document:hover .btn-action {
        display: block;
    }
</style>
<script>
    $(document).on('click', '.view-compliance', function() {
        const id = $(this).data('id')
        $('#modelId').modal('show')
        $('#modelId .modal-title').text('View Compliance')
        $('#modelId .modal-body').load(siteurl + active_controller + 'view_compliance/' + id)
    })
</script>