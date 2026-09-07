<div class="modal-body">
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
    <div class="tab-content mt-5">
        <div class="tab-pane position-relative fade show active" id="file" role="tabpanel" aria-labelledby="file-tab">
            <?php 
            $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
            if (isset($file->link_form) && $file->link_form) : 
                $url = $file->link_form;
                if($isMobile){
                    $url = "https://docs.google.com/gview?embedded=true&url=" . $url . "&rm=minimal";
                } else {
                    $url .= "#toolbar=0&navpanes=0";
                }
            ?>
                <iframe src="<?= $url ?>" frameborder="0" width="100%" height="500px"></iframe>
            <?php elseif (isset($file->link_url) && $file->link_url) : ?>
                <div class="text-center py-5">
                    <i class="fa fa-link fa-3x text-info mb-3"></i>
                    <h5>Document Link</h5>
                    <a href="<?= $file->link_url; ?>" target="_blank" class="btn btn-info mt-3">
                        <i class="fa fa-external-link-alt mr-2"></i> Open Link
                    </a>
                    <p class="text-muted mt-2"><?= $file->link_url; ?></p>
                </div>
            <?php else : ?>
                <?php if ($file->status == 'DEL') : ?>
                    <h4>404 Not Found!</h4>
                    <p>File hes been deleted!</p>
                <?php else : ?>
                    <?php if ($file->file_name) : ?>
                        <?php 
                        if ($type == 'form') {
                            $dir = 'FORMS';
                        } else if ($type == 'guide') {
                            $dir = 'GUIDES';
                        } else if ($type == 'record') {
                            $dir = 'RECORDS';
                        }
                        
                        $file_url = base_url("directory/$dir/$file->company_id/$file->file_name");
                        $ext = strtolower($file->ext);
                        ?>
                        <?php if ($ext == '.pdf') : ?>
                            <!-- Mobile View PDF: technical explanation + download button -->
                            <div class="text-center py-4 px-3 d-block d-md-none">
                                <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                <h5 class="mb-2"><?= isset($file->name) ? $file->name : (isset($file->file_name) ? $file->file_name : 'Dokumen PDF'); ?></h5>
                                <div class="alert alert-warning text-left mx-auto mb-4" style="max-width: 550px;">
                                    <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Informasi Preview Mobile:</div>
                                    <ul class="pl-3 mb-2 small text-dark" style="line-height: 1.5;">
                                        <li>Browser di HP/Mobile (seperti Android Chrome) tidak memiliki fitur renderer bawaan untuk merender dokumen PDF di dalam frame browser.</li>
                                        <li>Beberapa perangkat mobile memerlukan aplikasi pembaca PDF eksternal untuk membaca berkas PDF.</li>
                                    </ul>
                                    <div class="border-top pt-2 mt-2 small text-dark">
                                        <i class="fas fa-lightbulb text-warning mr-1"></i> <strong>Saran:</strong> Tekan tombol di bawah untuk membuka / mengunduh PDF secara langsung di tab baru atau aplikasi PDF HP Anda.
                                    </div>
                                </div>
                                <a href="<?= $file_url; ?>" target="_blank" class="btn btn-primary btn-lg">
                                    <i class="fas fa-external-link-alt mr-2"></i> Buka / Download PDF
                                </a>
                            </div>
                            <!-- Desktop View PDF -->
                            <div class="d-none d-md-block">
                                <iframe src="<?= $file_url; ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="500px"></iframe>
                            </div>
                        <?php elseif (in_array($ext, ['.xlsx', '.xls', '.doc', '.docx', '.ppt', '.pptx', '.csv'])) : ?>
                            <div class="d-none d-md-block">
                                <iframe src="<?= $file_url; ?>" frameborder="0" width="100%" height="500px"></iframe>
                                <div class="text-center mt-3">
                                    <a href="<?= $file_url ?>" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i> Download File</a>
                                </div>
                            </div>
                            <div class="text-center py-4 px-3 d-block d-md-none">
                                <?php 
                                $officeIcon = 'fas fa-file-excel';
                                $officeColor = '#217346';
                                if (in_array($ext, ['.doc', '.docx'])) {
                                    $officeIcon = 'fas fa-file-word';
                                    $officeColor = '#2b579a';
                                } elseif (in_array($ext, ['.ppt', '.pptx'])) {
                                    $officeIcon = 'fas fa-file-powerpoint';
                                    $officeColor = '#d24726';
                                }
                                ?>
                                <i class="<?= $officeIcon; ?> fa-3x mb-3" style="color: <?= $officeColor ?>;"></i>
                                <h5 class="mb-2"><?= isset($file->name) ? $file->name : (isset($file->file_name) ? $file->file_name : 'Document'); ?></h5>
                                <div class="alert alert-warning text-left mx-auto mb-4" style="max-width: 550px;">
                                    <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Informasi Preview Mobile:</div>
                                    <ul class="pl-3 mb-2 small text-dark" style="line-height: 1.5;">
                                        <li>Browser di HP/Mobile tidak memiliki fitur renderer bawaan untuk menampilkan file Excel/Word/Office di dalam frame browser.</li>
                                        <li>Browser mobile (Android Chrome / Safari iOS) juga tidak mendukung ekstensi browser seperti <em>Office Editing</em>.</li>
                                        <li>Server ini berada di jaringan lokal (intranet), sehingga cloud viewer pihak ketiga tidak dapat mengakses file ini dari luar.</li>
                                    </ul>
                                    <div class="border-top pt-2 mt-2 small text-dark">
                                        <i class="fas fa-lightbulb text-warning mr-1"></i> <strong>Saran:</strong> Untuk membuka & melihat preview file Excel/Word langsung di browser tanpa mengunduh, disarankan menggunakan browser di <strong>PC Desktop/Laptop</strong> yang terinstall ekstensi <strong>Office Editing for Docs, Sheets & Slides</strong>.
                                    </div>
                                </div>
                                <a href="<?= $file_url; ?>" download class="btn btn-success btn-lg">
                                    <i class="fa fa-download mr-2"></i> Download File
                                </a>
                            </div>
                        <?php else : ?>
                            <iframe src="<?= $file_url ?>" frameborder="0" width="100%" height="500px"></iframe>
                            <div class="text-center mt-3">
                                <a href="<?= $file_url ?>" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i> Download File</a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
            <hr>
        </div>
        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
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
                                foreach ($history as $his) : ?>
                                    <div class="timeline-item">
                                        <div class="timeline-media <?= ($his->new_status == 'OPN') ? 'bg-light-success' : 'bg-light-danger'; ?>">
                                            <span class="<?= ($his->new_status == 'OPN') ? 'fa fa-upload text-success' : 'fa fa-circle text-danger'; ?>"></span>
                                        </div>

                                        <div class="timeline-desc timeline-desc-light-danger">
                                            <span class="font-weight-bolder text-danger"> <?= $his->updated_at; ?></span>
                                            <?php //$sts[$his->status]; 
                                            ?>
                                            <p>
                                                <?= $his->note; ?>
                                            </p>
                                            <p class="font-weight-normal text-dark-50 pt-1">
                                                <span class="badge badge-danger">by <?= $his->full_name; ?></span>
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
    <div class="justify-content-end d-flex">
        <button type="button" class="btn btn-danger" onclick="$('#modelId').modal('hide');setTimeout(function(){$('#content_modal').html('')},500)"><i class="fa fa-times"></i>Close</button>
    </div>
</div>