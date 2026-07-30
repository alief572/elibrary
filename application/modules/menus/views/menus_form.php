<link rel="stylesheet" href="<?= base_url('assets/css/fontawesome-iconpicker.css')?>">
<script src="<?= base_url('assets/js/fontawesome-iconpicker.js')?>"></script>

<div class="menus-form-container">
    <?= form_open('menus/save_data_Menus', array('id' => 'frm_menus', 'name' => 'frm_menus', 'role' => 'form')) ?>

        <?php if (isset($data->id)) { $type = 'edit'; } ?>
        <input type="hidden" id="type" name="type" value="<?= isset($type) ? $type : 'add' ?>">
        <input type="hidden" id="id" name="id" value="<?= set_value('id', isset($data->id) ? $data->id : ''); ?>">

        <div class="row">
            <!-- Menu Name -->
            <div class="col-md-6 form-group mb-4">
                <label for="title" class="font-weight-bold text-dark">
                    Nama Menu <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light-primary border-0"><i class="fa fa-font text-primary"></i></span>
                    <input type="text" class="form-control" id="title" name="title" maxlength="100" 
                        value="<?= set_value('title', isset($data->title) ? $data->title : ''); ?>" 
                        placeholder="Contoh: Master Documents" required>
                </div>
            </div>

            <!-- Path Menu / Link -->
            <div class="col-md-6 form-group mb-4">
                <label for="link" class="font-weight-bold text-dark">
                    Path / Link Menu <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light-primary border-0"><i class="fa fa-link text-primary"></i></span>
                    <input type="text" class="form-control" id="link" name="link" 
                        value="<?= set_value('link', isset($data->link) ? $data->link : ''); ?>" 
                        placeholder="Contoh: docs atau #" required>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Parent Menu -->
            <div class="col-md-6 form-group mb-4">
                <label for="parent_id" class="font-weight-bold text-dark">
                    Parent Menu <span class="text-danger">*</span>
                </label>
                <select id="parent_id" name="parent_id" class="form-control select2-modal" style="width: 100%;">
                    <?php if (!empty($parent)) : ?>
                        <?php foreach ($parent as $p_id => $p_name) : ?>
                            <option value="<?= $p_id ?>" <?= (isset($data->parent_id) && $data->parent_id == $p_id) ? 'selected' : '' ?>>
                                <?= str_replace('&nbsp;', ' ', strip_tags($p_name)) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Group Menu -->
            <div class="col-md-6 form-group mb-4">
                <label for="group_menu" class="font-weight-bold text-dark">
                    Group Menu <span class="text-danger">*</span>
                </label>
                <select id="group_menu" name="group_menu" class="form-control select2-modal" style="width: 100%;">
                    <?php if (!empty($datgroupmenu)) : ?>
                        <?php foreach ($datgroupmenu as $g_id => $g_name) : ?>
                            <option value="<?= $g_id ?>" <?= (isset($data->group_menu) && $data->group_menu == $g_id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($g_name) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Icon Menu -->
            <div class="col-md-6 form-group mb-4">
                <label for="icon" class="font-weight-bold text-dark">
                    Icon (FontAwesome) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light-primary border-0">
                        <i id="icon-preview" class="<?= !empty($data->icon) ? $data->icon : 'fa fa-bars' ?> text-primary"></i>
                    </span>
                    <input type="text" class="form-control icp-auto" id="icon" name="icon" 
                        value="<?= set_value('icon', isset($data->icon) ? $data->icon : 'fa fa-angle-right'); ?>" 
                        placeholder="fa fa-folder" required>
                </div>
                <small class="form-text text-muted">Pilih icon dari picker atau ketik class FontAwesome.</small>
            </div>

            <!-- Target -->
            <div class="col-md-6 form-group mb-4">
                <label for="target" class="font-weight-bold text-dark">Target Link</label>
                <select id="target" name="target" class="form-control select2-modal" style="width: 100%;">
                    <option value="sametab" <?= (isset($data->target) && $data->target == 'sametab') ? 'selected' : ''; ?>>Same Tab (Default)</option>
                    <option value="_blank" <?= (isset($data->target) && $data->target == '_blank') ? 'selected' : ''; ?>>New Tab (_blank)</option>
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Order Number -->
            <div class="col-md-6 form-group mb-4">
                <label for="order" class="font-weight-bold text-dark">
                    Urutan (Order) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text bg-light-primary border-0"><i class="fa fa-sort-numeric-down text-primary"></i></span>
                    <input type="number" min="1" class="form-control" id="order" name="order" 
                        value="<?= set_value('order', isset($data->order) ? $data->order : '1'); ?>" 
                        placeholder="Urutan tampilan (1, 2, 3...)" required>
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-6 form-group mb-4">
                <label for="status" class="font-weight-bold text-dark">Status Menu</label>
                <select id="status" name="status" class="form-control select2-modal" style="width: 100%;">
                    <option value="1" <?= (!isset($data->status) || $data->status == '1') ? 'selected' : ''; ?>>Active (Aktif)</option>
                    <option value="0" <?= (isset($data->status) && $data->status == '0') ? 'selected' : ''; ?>>Inactive (Non-Aktif)</option>
                </select>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-light-danger font-weight-bold mr-2" data-dismiss="modal">
                <i class="fa fa-times mr-1"></i> Batal
            </button>
            <button type="submit" id="btn-submit" class="btn btn-primary font-weight-bold">
                <i class="fa fa-save mr-1"></i> Simpan Data
            </button>
        </div>

    <?= form_close() ?>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize Select2 with modal context
        if ($.fn.select2) {
            $('.select2-modal').select2({
                dropdownParent: $('#ModalView'),
                width: '100%'
            });
        }

        // Initialize FontAwesome IconPicker if library loaded
        if ($.fn.iconpicker) {
            $('.icp-auto').iconpicker({
                placement: 'bottomRight',
                hideOnSelect: true
            }).on('iconpickerSelected', function(e) {
                $('#icon-preview').attr('class', e.iconpickerValue + ' text-primary');
            });
        }

        // Live icon preview update on manual typing
        $('#icon').on('keyup change', function() {
            var iconClass = $(this).val();
            if (iconClass) {
                $('#icon-preview').attr('class', iconClass + ' text-primary');
            }
        });

        // AJAX Form Submission
        $('#frm_menus').on('submit', function(e) {
            e.preventDefault();
            
            var btnSubmit = $('#btn-submit');
            var originalBtnHtml = btnSubmit.html();
            
            btnSubmit.prop('disabled', true).html('<i class="fa fa-spinner fa-spin mr-1"></i> Menyimpan...');

            var formdata = $(this).serialize();
            
            $.ajax({
                url: siteurl + "menus/save_data_Menus",
                dataType: "json",
                type: 'POST',
                data: formdata,
                success: function(msg) {
                    btnSubmit.prop('disabled', false).html(originalBtnHtml);

                    if (msg.status == 1 || msg.save == 1) {
                        $("#ModalView").modal('hide');
                        
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: msg.msg || 'Data Menu berhasil disimpan.',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(function() {
                                window.location.reload();
                            });
                        } else if (typeof swal !== 'undefined') {
                            swal({
                                title: "Berhasil!",
                                text: msg.msg || "Data Menu berhasil disimpan.",
                                type: "success",
                                timer: 1500,
                                showConfirmButton: false
                            });
                            setTimeout(function() { window.location.reload(); }, 1500);
                        } else {
                            alert(msg.msg || 'Data Menu berhasil disimpan.');
                            window.location.reload();
                        }
                    } else {
                        var errorText = msg.msg || 'Data Gagal Disimpan';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: errorText
                            });
                        } else if (typeof swal !== 'undefined') {
                            swal("Gagal!", errorText, "error");
                        } else {
                            alert(errorText);
                        }
                    }
                },
                error: function() {
                    btnSubmit.prop('disabled', false).html(originalBtnHtml);
                    var errorText = "Terjadi kesalahan saat memproses data ke server.";
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorText
                        });
                    } else if (typeof swal !== 'undefined') {
                        swal("Error!", errorText, "error");
                    } else {
                        alert(errorText);
                    }
                }
            });
        });
    });
</script>
