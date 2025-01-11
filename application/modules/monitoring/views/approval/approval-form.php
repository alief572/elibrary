<div class="modal-header py-2 px-2">
    <ul class="nav nav-pills nav-light-success py-0" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#filetab">
                <span class="nav-icon">
                    <i class="fa fa-file-alt"></i>
                </span>
                <span class="nav-text">File</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#history">
                <span class="nav-icon">
                    <i class="fa fa-history"></i>
                </span>
                <span class="nav-text">History</span>
            </a>
        </li>
    </ul>
</div>

<div class="tab-content mt-5">
    <div class="tab-pane fade show active" id="filetab" role="tabpanel" aria-labelledby="file-">
        <?php if ($type == 'procedures') : ?>
            <iframe class="w-100" style="height: 350px;" src="<?= base_url("procedures/printOut/" . $file->id); ?>#toolbar=0&navpanes=0" frameborder="1"></iframe>
        <?php endif; ?>
        <hr>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <form id="form">
                    <input type="hidden" name="id" id="id" value="<?= $file->id; ?>">

                    <div class="mb-3">
                        <div class="checkbox-inline">
                            <label class="status checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                <input type="checkbox" class="status" name="status" value="PUB" />
                                <span></span>
                                <strong>Publish Document</strong>
                            </label>
                            <span class="pl-8 invalid-feedback text-danger">Ceklist terlebuh dahulu.</span>
                        </div>
                        <span class="pl-8">I Aggree, file ready to publish.</span>
                    </div>
                    <div class="mb-3">
                        <div class="checkbox-inline">
                            <label class="status checkbox checkbox-outline checkbox-outline-2x checkbox-danger">
                                <input type="checkbox" class="status" name="status" value="COR" />
                                <span></span>
                                <strong>Correction Document</strong>
                            </label>
                            <span class="pl-8 invalid-feedback text-danger">Ceklist terlebuh dahulu.</span>
                        </div>
                        <span class="pl-8">This document still needs to be corrected.</span>
                    </div>
                    <div class="form-group row">
                        <textarea name="note" id="note" class="form-control" placeholder="Reason"></textarea>
                        <span class="invalid-feedback text-danger">Harus di isi</span>
                    </div>
                    <button type="button" class="btn btn-light-info" id="save-approval"><i class="fab fa-telegram-plane"></i>Approve & Publish Document</button>
            </div>
            </form>
        </div>
    </div>

    <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="home-tab">
        <div class="row overflow-auto">
            <div class="col-md-2"></div>
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