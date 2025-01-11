<div class="modal-header py-2 px-2">
    <ul class="nav nav-pills nav-light-success py-0" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#file">
                <span class="nav-icon">
                    <i class="fa fa-file-alt"></i>
                </span>
                <span class="nav-text">File</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#home">
                <span class="nav-icon">
                    <i class="fa fa-history"></i>
                </span>
                <span class="nav-text">History</span>
            </a>
        </li>
    </ul>
</div>
<div class="tab-content mt-5">
    <?php
    $height = '350px';
    if ($view_data == true) :
        $height = '450px';
    endif; ?>
    <div class="tab-pane fade show active" id="file" role="tabpanel" aria-labelledby="file-tab">
        <iframe class="w-100 mb-5" style="min-height: <?= ($height) ?>;" src="<?= base_url("procedures/printOut/" . $file->id); ?>#toolbar=0&navpanes=0" frameborder="1"></iframe>
        <?php if ($view_data == false) : ?>
            <button type="button" class="btn btn-default revision" data-id="<?= $file->id; ?>" data-type="procedure"><i class="fa fa-info-circle text-"></i>Submit this document for Revision</button>
            <button type="button" class="btn btn-light-danger deletion" data-id="<?= $file->id; ?>" data-type="procedure"><i class="fa fa-info-circle text-"></i>Submit this document for Deletion</button>
        <?php endif; ?>
    </div>
    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <label for="">Tracking File</label>
                    <div class="timeline timeline-5">
                        <div class="timeline-items">
                            <?php if (isset($history)) :
                                foreach ($history as $his) : ?>
                                    <div class="timeline-item">
                                        <div class="timeline-media <?= ($his->new_status == 'OPN') ? 'bg-light-success' : 'bg-light-danger'; ?>">
                                            <span class="<?= ($his->new_status == 'OPN') ? 'fa fa-upload text-success' : 'fa fa-circle text-danger'; ?>"></span>
                                        </div>

                                        <div class="timeline-desc timeline-desc-light-danger">
                                            <span class="font-weight-bolder text-danger"> <?= $his->updated_at; ?></span>
                                            <?= $sts[$his->new_status]; ?>
                                            <p class="font-weight-normal text-dark-50 pt-1">
                                                <strong for="">Processed by <?= $his->updated_by; ?></strong>
                                            </p>
                                            <p>
                                                <?= $his->note; ?>
                                            </p>
                                        </div>
                                    </div>
                            <?php endforeach;
                            endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>