<?php
    $ENABLE_ADD     = true;
    $ENABLE_MANAGE  = true;
    $ENABLE_VIEW    = true;
    $ENABLE_DELETE  = true;
?>

<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>

<div class="content d-flex flex-column flex-column-fluid p-0">
    <div class="container mt-3">
        <div class="card card-custom shadow-sm">
            <div class="card-header py-4 d-flex justify-content-between align-items-center">
                <div class="card-title m-0">
                    <h3 class="card-label font-weight-bolder text-dark m-0">
                        <i class="fa fa-bars text-primary mr-2"></i>Manage Data Menus
                    </h3>
                </div>
                <div class="card-toolbar">
                    <?php if ($ENABLE_ADD) : ?>
                        <button type="button" class="btn btn-primary font-weight-bold" onclick="add_data()">
                            <i class="fa fa-plus-circle mr-1"></i> New Menu
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card-body pt-3">
                <div class="table-responsive">
                    <table id="example1" class="table table-head-custom table-head-bg table-borderless table-vertical-center table-hover w-100">
                        <thead>
                            <tr class="text-uppercase text-dark font-weight-bolder">
                                <th width="40" class="text-center">#</th>
                                <th>Nama Menu</th>
                                <th>Path / Link</th>
                                <th>Parent Menu</th>
                                <th>Target</th>
                                <th>Status</th>
                                <?php if ($ENABLE_MANAGE || $ENABLE_DELETE) : ?>
                                    <th width="90" class="text-center">Action</th>
                                <?php endif; ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($results)) : ?>
                                <?php $numb = 0; foreach ($results as $record) : $numb++; ?>
                                    <?php $level = isset($record->level) ? (int)$record->level : 0; ?>
                                    <tr class="<?= $level == 0 ? 'bg-light-primary-subtle' : '' ?>">
                                        <td class="text-center align-middle font-weight-bold text-muted"><?= $numb; ?></td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center" style="padding-left: <?= $level * 25 ?>px;">
                                                <?php if ($level > 0) : ?>
                                                    <span class="text-muted mr-2 font-weight-bold" style="font-size: 13px;">└─</span>
                                                <?php endif; ?>

                                                <div class="symbol symbol-30 <?= $level == 0 ? 'symbol-light-primary' : 'symbol-light-info' ?> mr-3 d-flex align-items-center justify-content-center" style="width:28px; height:28px; border-radius:6px; background-color: <?= $level == 0 ? '#e1f0ff' : '#f3f6f9' ?>;">
                                                    <i class="<?= !empty($record->icon) ? $record->icon : 'fa fa-angle-right' ?> <?= $level == 0 ? 'text-primary' : 'text-info' ?>"></i>
                                                </div>
                                                
                                                <span class="<?= $level == 0 ? 'font-weight-bolder text-dark h6 mb-0' : 'font-weight-bold text-dark-75 mb-0' ?>">
                                                    <?= htmlspecialchars($record->title) ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <code class="px-2 py-1 bg-light text-danger rounded font-weight-bold" style="font-size: 11px;"><?= htmlspecialchars($record->link) ?></code>
                                        </td>
                                        <td class="align-middle">
                                            <?php if ($record->parent_id == 0 || empty($record->parent_name)) : ?>
                                                <span class="badge badge-light-dark font-weight-bold"><i class="fa fa-home text-muted mr-1"></i> ROOT</span>
                                            <?php else : ?>
                                                <span class="badge badge-light-info font-weight-bold"><i class="fa fa-level-up-alt text-info mr-1"></i> <?= htmlspecialchars($record->parent_name) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php if ($record->target == '_blank') : ?>
                                                <span class="badge badge-light-warning text-warning font-weight-bold" title="Open in New Tab"><i class="fa fa-external-link-alt text-warning mr-1"></i> New Tab</span>
                                            <?php else : ?>
                                                <span class="badge badge-light text-dark font-weight-bold" title="Open in Same Tab">Same Tab</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="align-middle">
                                            <?php if ($record->status == '1') : ?>
                                                <span class="badge badge-success font-weight-bold">Active</span>
                                            <?php else : ?>
                                                <span class="badge badge-danger font-weight-bold">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <?php if ($ENABLE_MANAGE || $ENABLE_DELETE) : ?>
                                            <td class="text-center align-middle">
                                                <?php if ($ENABLE_MANAGE) : ?>
                                                    <button type="button" class="btn btn-xs btn-icon btn-light-primary shadow-sm mr-1" title="Edit Menu" onclick="edit_data('<?= $record->id ?>')">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($ENABLE_DELETE) : ?>
                                                    <button type="button" class="btn btn-xs btn-icon btn-light-danger shadow-sm" title="Delete Menu" onclick="delete_data('<?= $record->id ?>')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Modal View -->
<div class="modal fade" id="ModalView" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="head_title" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bolder text-white" id="head_title"><i class="fa fa-bars text-white mr-2"></i>Form Menu</h5>
                <button type="button" class="close text-white opacity-90" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4" id="modalDataView">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data form...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page Scripts -->
<script type="text/javascript">
    $(document).ready(function () {
        if (!$.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable({
                "responsive": true,
                "autoWidth": false,
                "ordering": false,
                "pageLength": 100
            });
        }
    });

    function add_data() {
        var url = 'menus/create';
        $("#head_title").html("<i class='fa fa-plus-circle text-white mr-2'></i>Tambah Master Menu");
        $("#modalDataView").html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2 text-muted">Memuat data form...</p></div>');
        $("#ModalView").modal('show');
        $("#modalDataView").load(siteurl + url);
    }

    function edit_data(id) {
        if (id != "") {
            var url = 'menus/edit/' + id;
            $("#head_title").html("<i class='fa fa-edit text-white mr-2'></i>Edit Master Menu (ID: " + id + ")");
            $("#modalDataView").html('<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="mt-2 text-muted">Memuat data form...</p></div>');
            $("#ModalView").modal('show');
            $("#modalDataView").load(siteurl + url);
        }
    }

    function delete_data(id) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Data menu ini akan dihapus dari sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    execute_delete(id);
                }
            });
        } else if (typeof swal !== 'undefined') {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Data menu ini akan dihapus!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal",
                closeOnConfirm: false
            }, function (isConfirm) {
                if (isConfirm) {
                    execute_delete(id);
                }
            });
        } else {
            if (confirm("Apakah Anda yakin ingin menghapus menu ini?")) {
                execute_delete(id);
            }
        }
    }

    function execute_delete(id) {
        $.ajax({
            url: siteurl + 'menus/hapus_menus/' + id,
            dataType: "json",
            type: 'POST',
            success: function (msg) {
                if (msg.status == 1 || msg.delete == 1) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: msg.msg || 'Data berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(function() {
                            window.location.reload();
                        });
                    } else if (typeof swal !== 'undefined') {
                        swal({
                            title: "Berhasil!",
                            text: msg.msg || "Data berhasil dihapus.",
                            type: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(function(){ window.location.reload(); }, 1500);
                    } else {
                        alert(msg.msg || "Data berhasil dihapus.");
                        window.location.reload();
                    }
                } else {
                    var errorMsg = msg.msg || "Data gagal dihapus.";
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMsg
                        });
                    } else if (typeof swal !== 'undefined') {
                        swal("Gagal!", errorMsg, "error");
                    } else {
                        alert(errorMsg);
                    }
                }
            },
            error: function () {
                var errorMsg = "Gagal eksekusi request hapus data.";
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: errorMsg
                    });
                } else {
                    alert(errorMsg);
                }
            }
        });
    }
</script>