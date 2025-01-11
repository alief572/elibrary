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
                        <a href="" class="text-muted">MATERI TRAINING</a>
                    </li>
                </ul>
            </div>
            <h1 class="text-white fa-3x">MATERI TRAINING</h1>
            <div class="row mb-10">

            </div>
            <ul class="nav nav-warning nav-pills nav-bolder" id="myTab2" role="tablist">
                <?php $n = 0;
                $thisCompany = '';
                if ($materi) :
                    foreach ($materi as $dt) : $n++; ?>
                        <li class="nav-item mx-0">
                            <a class="rounded-bottom-0 nav-link  <?= ($n == '1') ? 'active' : ''; ?>" id="tab_<?= $dt->id; ?>" data-toggle="tab" href="#data_<?= $dt->id; ?>">
                                <span class="nav-icon ">
                                    <i class="fa fa-file-alt"></i>
                                </span>
                                <span class="text-white h5 my-0"><?= $dt->name; ?>
                                    <small class="">
                                        <div class="badge bg-white rounded-circle text-warning"><?= (isset($ArrDetail[$dt->id]) ? count($ArrDetail[$dt->id]) : 0); ?></div>
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
                        if ($materi) :
                            foreach ($materi as $dtl) :  $n++; ?>
                                <div class="tab-pane fade <?= ($n == '1') ? 'active show' : ''; ?>" id="data_<?= $dtl->id; ?>" role="tabpanel" aria-labelledby="tab_<?= $dtl->id; ?>">
                                    <table class="table datatable table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($ArrDetail[$dtl->id])) foreach ($ArrDetail[$dtl->id] as $list) : ?>
                                                <tr class="cursor-pointer" data-id="<?= $list->id; ?>" onclick="window.open(siteurl+active_controller+'materi/?f=<?= $dtl->id; ?>&sub=<?= $list->id; ?>','_self')">
                                                    <td class="text-center h6 p-1" width="50px"><i class="fa fa-folder fa-2x text-warning"></i></th>
                                                    <td class="font-weight-bolder p-1 h4 text-dark"><?= $list->name; ?></th>
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document" style="width:90%">
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
        $('#data-file').load(siteurl + active_controller + 'show/' + id)
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