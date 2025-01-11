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
                        <a href="<?= base_url('list/guides'); ?>" class="text-muted">MASTER IK</a>
                    </li>
                    <li class="breadcrumb-item text-muted">
                        <a href="" class="text-muted"><?= $guide_details[0]->name; ?></a>
                    </li>
                </ul>
            </div>
            <h1 class="text-white fa-3x mb-12">MASTER IK</h1>

            <!-- <h4 class="text-white fa-2x mb-5"><?= $guide_details[0]->name; ?></h4> -->
            <ul class="nav nav-warning nav-pills nav-bolder" id="myTab2" role="tablist">
                <?php $n = 0;
                $thisCompany = '';
                if ($guide_details) :
                    foreach ($guide_details as $k => $dtl) : $n++; ?>
                        <li class="nav-item mx-0">
                            <a class="rounded-bottom-0 nav-link <?= ($n == '1') ? 'active show' : ''; ?>" id="tab_<?= $dtl->id; ?>" data-toggle="tab" href="#data_<?= $dtl->id; ?>">
                                <span class="nav-icon ">
                                    <i class="fa fa-file-alt"></i>
                                </span>
                                <span class="text-white h5 my-0"><?= $dtl->name; ?>
                                    <small class="">
                                        <div class="badge bg-white rounded-circle text-warning"><?= (isset($ArrDtlData[$dtl->id]) ? count($ArrDtlData[$dtl->id]) : 0); ?></div>
                                    </small>
                                </span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="card rounded-top-0 border-0" style="background-color: zrgba(255,255,255,0.85);">
                <div class="card-body py-3 ">
                    <?php if (!$guide_details) : ?>
                        <div class="justify-content-center flex-column d-flex py-10">
                            <img src="/assets/images/directory/not-found.png" alt="" class="img-cover justify-content-center m-auto" width="200px">
                            <h3 class="text-center text-dark-50">File not found</h3>
                        </div>
                    <?php endif; ?>
                    <div class="tab-content " id="myTabContent2">
                        <a href="<?= base_url($this->uri->segment(1) . '/guides/'); ?>"><i class="fa fa-arrow-left"></i></a>
                        <?php $n = 0;
                        if ($guide_details) :
                            foreach ($guide_details as $k => $dtl) :  $n++; ?>
                                <div class="tab-pane fade <?= ($n == '1') ? 'active show' : ''; ?>" id="data_<?= $dtl->id; ?>" role="tabpanel" aria-labelledby="tab_<?= $dtl->id; ?>">
                                    <table class="table datatable table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Jenis Alat</th>
                                                <th>Kelompok</th>
                                                <th>Rentang Ukur</th>
                                                <th>Metode</th>
                                                <th>Terbit</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($ArrDtlData[$dtl->id])) foreach ($ArrDtlData[$dtl->id] as $list) : ?>
                                                <tr>
                                                    <td style="vertical-align: top;" class="text-center h6 p-1" width="50px">
                                                        <i class="fa fa-file-alt fa-2x text-success"></i>
                                                    </td>
                                                    <td style="vertical-align: top;" class="font-weight-bold pl-0 h6">
                                                        <a href="#"><?= $list->guide_detail_data_name; ?></a>
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-dark">
                                                        <?= $list->group_name; ?>
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-dark">
                                                        <?php if ($list->range_measure) foreach (json_decode($list->range_measure) as $rm) echo "<p class='mb-2'>$rm</p>"; ?>
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-dark">
                                                        <?php if ($list->methode) foreach (json_decode($list->methode) as $mth) echo "<span class='badge badge-success'>$methode[$mth]</span> "; ?>
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-dark">
                                                        <?= $list->publish_date; ?>
                                                    </td>
                                                    <td style="vertical-align: top;" class="text-center">
                                                        <button data-id="<?= $list->id; ?>" type="button" onclick="show(<?= $list->id; ?>)" class="btn btn-sm btn-icon btn-primary view-file"><i class="fa fa-file-alt"></i></button>
                                                        <button data-id="<?= $list->id; ?>" type="button" onclick="show_video(<?= $list->id; ?>)" class="btn btn-sm btn-icon btn-danger view-file"><i class="fa fa-video"></i></button>
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
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content" data-scroll="true">
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
        $('#data-file').load(siteurl + active_controller + 'view_guides/' + id)
    }

    function show_video(id) {
        $('#modelId').modal('show')
        $('#data-file').load(siteurl + active_controller + 'view_video/' + id)
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
            // ordering: false,
            info: false
        });
    })
</script>