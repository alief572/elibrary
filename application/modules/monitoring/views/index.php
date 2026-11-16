<div class="content d-flex flex-column flex-column-fluid p-0">
    <div class="container mt-5">
        <h1 class="text-white pt-0 font-weight-bolder bg-white-o-50 d-inline-block text-center rounded-lg px-5 py-1">
            MONITORING DOCUMENTS</h1>

        <!-- PROCEDURES -->
        <div class="pt-3 mt-4">
            <h3 class="text-white pt-0 font-weight-bolder bg-white-o-0 rounded-lg px-0 py-1">Procedures</h3>
            <div class="row">
                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-success monitoring-count" style="font-size: 48px;"><?= $dtProcedureRev; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/review"); ?>" class="text-hover-primary" title="REVIEW DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">REVIEW DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-warning monitoring-count" style="font-size: 48px;"><?= $dtProcedureCor; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/correction"); ?>" class="text-hover-primary" title="CORRECTION DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">CORRECTION DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-info monitoring-count" style="font-size: 48px;"><?= $dtProcedureApv; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/approval"); ?>" class="text-hover-primary" title="APPROVAL DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">APPROVAL DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-danger monitoring-count" style="font-size: 48px;"><?= $dtProcedureRvi; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/revision"); ?>" class="text-hover-primary" title="REVISION DOCUMENT">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">REVISION DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-primary monitoring-count" style="font-size: 48px;"><?= $dtProcedurePub; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/publised"); ?>" class="text-hover-primary" title="PUBLISH DOCUMENT">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">PUBLISH DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OTHER DOCUMENT (d-none) -->
        <div class="pt-3 d-none">
            <h3 class="text-white mt-0 pt-0 font-weight-bolder bg-white-o-0 rounded-lg px-0 py-1">Other Document</h3>
            <div class="row">
                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-success monitoring-count" style="font-size: 48px;"><?= $dtGuidesApv; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/review"); ?>" class="text-hover-primary" title="REVIEW DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">REVIEW DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-warning monitoring-count" style="font-size: 48px;"><?= $dtGuidesRev; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/correction"); ?>" class="text-hover-primary" title="CORRECTION DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">CORRECTION DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-info monitoring-count" style="font-size: 48px;"><?= $dtGuidesCor; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/approval"); ?>" class="text-hover-primary" title="APPROVAL DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">APPROVAL DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-danger monitoring-count" style="font-size: 48px;"><?= $dtGuidesRvi; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/revision"); ?>" class="text-hover-primary" title="REVISION DOCUMENT">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">REVISION DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2-4 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-primary monitoring-count" style="font-size: 48px;"><?= $dtGuidesPub; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/publised"); ?>" class="text-hover-primary" title="PUBLISH DOCUMENT">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">PUBLISH DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DELETION DOCUMENTS -->
        <h1 class="text-white pt-0 font-weight-bolder bg-white-o-50 d-inline-block text-center rounded-lg px-5 py-1 mt-4">DELETION DOCUMENTS</h1>

        <!-- DELETION PROCEDURES -->
        <div class="pt-3 mt-4 mb-5">
            <h3 class="text-white pt-0 font-weight-bolder bg-white-o-0 rounded-lg px-0 py-1">Procedures</h3>
            <div class="row">
                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-warning monitoring-count" style="font-size: 48px;"><?= $hld; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/review_deletion"); ?>" class="text-hover-primary" title="REVIEW DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">REVIEW DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-danger monitoring-count" style="font-size: 48px;"><?= $revDel; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/approval_deletion"); ?>" class="text-hover-primary" title="APPROVAL DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">APPROVAL DELETION DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-muted monitoring-count" style="font-size: 48px;"><?= $apvDel; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/deletion_document"); ?>" class="text-hover-primary" title="NEED ACTION TO DELETE DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">NEED ACTION TO DELETE DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-lg-3 mb-4">
                    <div class="card border-0 shadow-lg rounded-lg h-100" style="background-color: rgba(255, 255, 255, 100);">
                        <div class="card-body p-3 text-center d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="font-weight-bolder text-success monitoring-count" style="font-size: 48px;"><?= $rejDel; ?></h5>
                                <p class="text-muted font-size-sm mb-2">Documents</p>
                            </div>
                            <h6 class="card-title text-center px-1 mb-0">
                                <a href="<?= base_url($this->uri->segment(1) . "/rejected_document"); ?>" class="text-hover-primary" title="REJECTED DELETION DOCUMENTS">
                                    <span class="card-label text-dark text-center font-weight-bolder monitoring-title">REJECTED DELETION DOCUMENTS</span>
                                </a>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    p {
        margin-bottom: 0;
    }
    @media (min-width: 992px) {
        .col-lg-2-4 {
            flex: 0 0 20%;
            max-width: 20%;
        }
    }
    @media (max-width: 575.98px) {
        .monitoring-count {
            font-size: 32px !important;
        }
        .monitoring-title {
            font-size: 11px !important;
        }
        .container {
            padding-left: 12px !important;
            padding-right: 12px !important;
        }
        .row {
            margin-left: -6px !important;
            margin-right: -6px !important;
        }
        .col-6 {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
    }
</style>
