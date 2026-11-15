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
        <iframe class="w-100" style="height: 450px;" src="<?= base_url("directory/$dir_name/$file->file_name"); ?>?time=<?= time(); ?> #toolbar=0&navpanes=0" frameborder="1"></iframe>
        <hr>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <form id="form">
                    <input type="hidden" name="id" id="id" value="<?= $file->id; ?>">
                    <input type="hidden" name="parent_id" id="parent_id" value="<?= $file->parent_id; ?>">
                    <div class="mb-3">
                        <div class="checkbox-inline">
                            <label class="status checkbox checkbox-outline checkbox-outline-2x checkbox-primary">
                                <input type="checkbox" id="status" name="status" value="PUB" />
                                <span></span>
                                I Aggree, file ready to publish.
                            </label>
                            <p class="pl-8 text-dark-50"><small>Dengan persetujuan ini menyatakan bahwa file yang sudah di approve otomatis akan di publish</small></p>
                            <span class="pl-8 invalid-feedback text-danger">Ceklist terlebuh dahulu.</span>
                        </div>
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
                            foreach ($history as $his) :
                                $hisStatus = isset($his->new_status) ? $his->new_status : (isset($his->status) ? $his->status : '');
                        ?>
                                <div class="timeline-item">
                                    <div class="timeline-media <?= ($hisStatus == 'OPN') ? 'bg-light-success' : 'bg-light-danger'; ?>">
                                        <span class="<?= ($hisStatus == 'OPN') ? 'fa fa-upload text-success' : 'fa fa-circle text-danger'; ?>"></span>
                                    </div>

                                    <div class="timeline-desc timeline-desc-light-danger">
                                        <span class="font-weight-bolder text-danger"> <?= $his->updated_at; ?></span>
                                        <?= isset($sts[$hisStatus]) ? $sts[$hisStatus] : ''; ?>
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