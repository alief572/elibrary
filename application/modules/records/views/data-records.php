<input type="hidden" id="refresh_id" value="<?= ($parent_id) ?: ''; ?>">

<table class="table table-hover" id="records-tree-table">
  <thead>
    <tr>
      <th class="py-0">File or Folder Name</th>
      <th class="py-0 text-right">Last Update</th>
      <th class="py-0 text-center" width="50">Opsi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (($getRecords)) : ?>
      <?php $n = 0; ?>
      <?php foreach ($getRecords as $form) : $n++; ?>
        <tr class="tree-row" data-id="<?= $form->id; ?>" data-parent-id="" data-depth="0" data-loaded="false">
          <td class="py-1">
            <a href="javascript:void(0)" data-id="<?= $form->id; ?>" class="cursor-pointer <?= ($form->flag_type == 'FOLDER') ? 'folder' : 'file'; ?> text-dark">
              <div class="d-flex justify-content-start align-items-center">
                <?php if ($form->flag_type == 'FOLDER') : ?>
                  <i class="fa fa-chevron-right mr-2 toggle-icon" style="transition: transform 0.2s;"></i>
                  <i class="fa fa-folder text-warning fa-2x mr-3"></i>
                <?php elseif (isset($form->link_url) && $form->link_url) : ?>
                  <i class="fa fa-link text-info fa-2x mr-3 ml-4"></i>
                <?php else : 
                  $ext = isset($form->ext) && $form->ext ? strtolower(str_replace('.', '', $form->ext)) : (isset($form->file_name) ? strtolower(pathinfo($form->file_name, PATHINFO_EXTENSION)) : '');
                  if ($ext == 'pdf') { $icon = 'fas fa-file-pdf text-danger'; }
                  elseif (in_array($ext, ['xls', 'xlsx'])) { $icon = 'fas fa-file-excel text-success'; }
                  elseif (in_array($ext, ['doc', 'docx'])) { $icon = 'fas fa-file-word text-primary'; }
                  elseif (in_array($ext, ['ppt', 'pptx'])) { $icon = 'fas fa-file-powerpoint text-warning'; }
                  elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) { $icon = 'fas fa-file-image text-info'; }
                  else { $icon = 'fas fa-file-alt text-success'; }
                ?>
                  <i class="<?= $icon ?> fa-2x mr-3 ml-4"></i>
                <?php endif; ?>
                <span class="text-name mt-3 h5"><?= $form->name; ?></span>
              </div>
            </a>
          </td>
          <td class="py-1 text-right">
            <div class="d-flex justify-content-end align-items-center">
              <h6 class="mt-4 ml-4"><?= $form->created_at; ?></h6>
            </div>
          </td>
          <td class="py-1 text-center">
            <div class="btn-opsi mt-1">
              <div class="dropdown">
                <button class="btn btn-sm btn-icon btn-primary" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="fa fa-cog"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                  <?php if ($form->flag_type == 'FILE') : ?>
                    <?php if (isset($form->link_url) && $form->link_url) : ?>
                      <a class="dropdown-item" href="<?= $form->link_url; ?>" target="_blank" title="Open Link"><i class="fa fa-external-link-alt text-info mr-2"></i> Open Link</a>
                    <?php else : ?>
                      <a class="dropdown-item view-record" href="javascript:void(0)" data-id="<?= $form->id; ?>"><i class="<?= isset($icon) ? $icon : 'fas fa-file-pdf text-primary' ?> mr-2"></i> View Document</a>
                    <?php endif; ?>
                    <a class="dropdown-item edit-record" href="javascript:void(0)" data-id="<?= $form->id; ?>" data-name="<?= $form->name; ?>"><i class="fa fa-edit text-warning mr-2"></i> Edit Document</a>
                  <?php else : ?>
                    <?php if ($this->uri->segment(1) == 'records') : ?>
                      <a class="dropdown-item add-folder-inside" href="javascript:void(0)" data-id="<?= $form->id; ?>"><i class="fa fa-folder-plus text-warning mr-2"></i> Add Folder</a>
                      <a class="dropdown-item add-record-inside" href="javascript:void(0)" data-id="<?= $form->id; ?>"><i class="fa fa-file-medical text-primary mr-2"></i> Add Record</a>
                      <div class="dropdown-divider"></div>
                    <?php endif; ?>
                    <a class="dropdown-item edit-folder" href="javascript:void(0)" data-id="<?= $form->id; ?>" data-name="<?= $form->name; ?>"><i class="fa fa-edit text-warning mr-2"></i> Edit Folder</a>
                  <?php endif; ?>
                  <a class="dropdown-item move-record" href="javascript:void(0)" data-id="<?= $form->id; ?>"><i class="fa fa-random text-success mr-2"></i> Move</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item delete-record text-danger" href="javascript:void(0)" data-id="<?= $form->id; ?>"><i class="fa fa-trash text-danger mr-2"></i> Delete</a>
                </div>
              </div>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr>
        <td colspan="3" class="text-center py-5">
          <i class="fa fa-folder-open text-muted fa-4x mb-3"></i>
          <h5 class="text-muted">~ Folder Kosong ~</h5>
          <button type="button" class="btn btn-warning mt-3 add_folder"><i class="fa fa-folder-plus"></i> Create Folder</button>
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>