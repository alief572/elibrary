<style>
    .dataTables_scroll {
        margin: 0px !important;
    }

    .materi-file-row:focus {
        outline: 2px solid #f3b200;
        outline-offset: -2px;
    }

    #materiViewerModal {
        padding: 0 !important;
    }

    #materiViewerModal .modal-dialog {
        width: 100vw;
        max-width: 100vw;
        height: 100vh;
        margin: 0;
    }

    #materiViewerModal .modal-content {
        height: 100vh;
        border: 0;
        border-radius: 0;
        background: #202124;
    }

    #materiViewerModal .materi-viewer-close {
        width: 40px;
        height: 40px;
        position: absolute;
        z-index: 1060;
        top: 10px;
        right: 12px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .92);
        color: #3c4043;
        font-size: 28px;
        line-height: 1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
    }

    #materiViewerModal .materi-viewer-close:hover,
    #materiViewerModal .materi-viewer-close:focus {
        background: #fff;
        color: #111;
        outline: none;
    }

    #materiViewerModal .modal-body {
        height: 100vh;
        min-height: 0;
        padding: 0;
        overflow: hidden;
    }

    .materi-viewer-loading {
        height: 100%;
        color: #fff;
    }
</style>
<div class="content d-flex flex-column flex-column-fluid p-0">
    <div class="d-flex flex-column-fluid justify-content-between align-items-top">
        <div class="container">
            <div class="d-flex align-items-baseline flex-wrap mr-5 mb-10">
                <a href="<?= base_url(); ?>">
                    <h4 class="text-dark font-weight-bold my-1 mr-2"><i class="fa fa-home"></i></h4>
                </a>
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item text-muted">
                        <a href="<?= base_url('dashboard'); ?>" class="text-muted">Dashboard</a>
                    </li>

                    <li class="breadcrumb-item text-muted">
                        <a href="<?= base_url('list/materi'); ?>" class="text-muted">MATERI TRAINING</a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="text-muted"><?= $materi[0]->name; ?></a>
                    </li>
                </ul>
            </div>
            <h1 class="text-white fa-3x">MATERI TRAINING</h1>

            <h4 class="text-white fa-2x mb-5"><?= $materi[0]->name; ?></h4>
            <ul class="nav nav-warning nav-pills nav-bolder" id="myTab2" role="tablist">
                <?php $n = 0;
                $thisCompany = '';
                if ($materi) :
                    foreach ($category as $k => $dt) : $n++; ?>
                        <li class="nav-item mx-0">
                            <a class="rounded-bottom-0 nav-link <?= ($n == '1') ? 'active show' : ''; ?>" id="tab_<?= $k; ?>" data-toggle="tab" href="#data_<?= $k; ?>">
                                <span class="nav-icon ">
                                    <i class="fa fa-file-alt"></i>
                                </span>
                                <span class="text-white h5 my-0"><?= $dt; ?>
                                    <small class="">
                                        <div class="badge bg-white rounded-circle text-warning"><?= (isset($ArrDtlData[$k]) ? count($ArrDtlData[$k]) : 0); ?></div>
                                    </small>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="card rounded-top-0 border-0" style="background-color: zrgba(255,255,255,0.85);">
                <div class="card-body py-3 ">
                    <?php if (!$materi) : ?>
                        <div class="justify-content-center flex-column d-flex py-10">
                            <img src="/assets/images/directory/not-found.png" alt="" class="img-cover justify-content-center m-auto" width="200px">
                            <h3 class="text-center text-dark-50">File not found</h3>
                        </div>
                    <?php endif; ?>
                    <div class="tab-content " id="myTabContent2">
                        <?php $n = 0;
                        if ($category) :
                            foreach ($category as $k => $cat) :  $n++; ?>
                                <div class="tab-pane fade <?= ($n == '1') ? 'active show' : ''; ?>" id="data_<?= $k; ?>" role="tabpanel" aria-labelledby="tab_<?= $k; ?>">
                                    <div class="table-responsive">
                                    <table class="table datatable table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($ArrDtlData[$k])) foreach ($ArrDtlData[$k] as $list) : ?>
                                                <tr class="cursor-pointer materi-file-row" data-id="<?= $list->id; ?>" data-title="<?= html_escape($list->name); ?>" tabindex="0" role="button" aria-label="Buka <?= html_escape($list->name); ?>">
                                                    <td class="text-center h6 p-1" width="50px">
                                                        <i class="fa fa-file-alt fa-2x text-success"></i>
                                                    </td>
                                                    <td class="font-weight-bold p-1 h4 text-dark">
                                                        <span><?= html_escape($list->name); ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="materiViewerModal" tabindex="-1" role="dialog" aria-label="Pratinjau dokumen" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="materi-viewer-close" data-dismiss="modal" aria-label="Tutup pratinjau">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body" id="data-file">
                <div class="materi-viewer-loading d-flex align-items-center justify-content-center">Memuat dokumen...</div>
            </div>
        </div>
    </div>
</div>

<script>
    function showMateri(id) {
        var $modal = $('#materiViewerModal');
        $('#data-file').html('<div class="materi-viewer-loading d-flex align-items-center justify-content-center">Memuat dokumen...</div>');
        $modal.modal('show');

        $('#data-file').load(siteurl + active_controller + 'show_materi/' + id, function(response, status) {
            if (status === 'error') {
                $(this).html('<div class="materi-viewer-loading d-flex align-items-center justify-content-center">Dokumen gagal dimuat. Silakan tutup lalu coba kembali.</div>');
            }
        });
    }

    $(document).ready(function() {
        $('.materi-file-row').on('click', function() {
            showMateri($(this).data('id'));
        }).on('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                showMateri($(this).data('id'));
            }
        });

        $('#materiViewerModal').on('hidden.bs.modal', function() {
            $('#data-file').empty();
        });

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
