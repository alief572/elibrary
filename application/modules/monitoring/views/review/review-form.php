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
    <div class="tab-pane fade show active" id="file" role="tabpanel" aria-labelledby="file-tab">
        <iframe class="w-100" style="height: 350px;" src="<?= base_url("procedures/printOut/" . $file->id); ?>#toolbar=0&navpanes=0" frameborder="1"></iframe>
        <hr>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <input type="hidden" name="id" id="id" value="<?= $file->id; ?>">
                <div class="form-group">
                    <label class="col-form-label font-weight-bold">Action Review</label>
                    <div class="div">
                        <span id="invalid-action" class="d-none text-danger">Pilih salah satu Action</span>
                    </div>
                    <div class="col-form-label mb-3">
                        <div class="radio-inline">
                            <label class="radio radio-outline radio-outline-2x radio-primary">
                                <input type="radio" name="status" value="APV" />
                                <span></span>
                                I agree to this file, and continue to the next process
                            </label>
                        </div>
                        <span class="form-text text-muted">Ready to Approval Process</span>
                    </div>
                    <div class="col-form-label mb-3">
                        <div class="radio-inline">
                            <label class="radio radio-outline radio-outline-2x radio-danger">
                                <input type="radio" name="status" value="COR" />
                                <span></span>
                                I don't agree, because some need corrections
                            </label>
                        </div>
                        <span class="form-text text-muted">write down the reason</span>
                        <textarea name="note" id="note" class="form-control" placeholder="Reason"></textarea>
                        <span class="invalid-feedback text-danger">Harus di isi</span>
                    </div>
                    <button type="button" class="btn btn-light-primary" id="save-review"><i class="fab fa-telegram-plane"></i>Submit Review</button>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <label for="">Tracking File</label>
                    <div class="timeline timeline-5">
                        <div class="timeline-items">
                            <!-- <div class="timeline-item">
                                <div class="timeline-media bg-light-primary">
                                    <i class="fa fa-upload text-success"></i>
                                </div>
                                <div class="timeline-desc timeline-desc-light-primary">
                                    <span class="font-weight-bolder text-primary"> <?= date('Y-m-d'); ?> 09:30 AM</span>
                                    <span class="label label-pill label-inline label-light-danger">Upload File</span>
                                    <p class="font-weight-normal text-dark-50 pb-2">
                                        To start a blog, think of a topic about and first brainstorm ways to write details
                                    </p>
                                </div>
                            </div> -->
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