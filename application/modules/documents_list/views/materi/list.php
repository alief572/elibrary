<style>
    .dataTables_scroll {
        margin: 0px !important;
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
                                    <table class="table datatable table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($ArrDtlData[$k])) foreach ($ArrDtlData[$k] as $list) : ?>
                                                <tr class="cursor-pointer" data-id="<?= $list->id; ?>">
                                                    <td class="text-center h6 p-1" width="50px">
                                                        <i class="fa fa-file-alt fa-2x text-success"></i>
                                                    </td>
                                                    <td class="font-weight-bold p-1 h4 text-dark">
                                                        <span onclick="show(<?= $list->id; ?>)"><?= $list->name; ?></span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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
<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" style="max-width:90%">
        <div class="modal-content" data-scroll="true" data-height="700">
            <div class="modal-header">
                <h5 class="modal-title">View Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1 m-1" id="data-file">
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
        $('#data-file').load(siteurl + active_controller + 'show_materi/' + id)
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