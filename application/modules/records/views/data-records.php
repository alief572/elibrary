<button type="button" class="btn btn-warning mb-3" id="add_folder"><i class="fa fa-folder-plus"></i> Add Folder</button>
<button type="button" class="btn btn-primary mb-3" <?= ($EOF) ? 'disabled' : ''; ?> id="add_record"><i class="fa fa-plus"></i> Add Record</button>
<button type="button" class="btn btn-success btn-icon mb-3" id="refresh" title="Refresh"><i class="fa fa-sync-alt"></i></button>
<hr>

<input type="hidden" id="refresh_id" value="<?= ($parent_id) ?: ''; ?>">

<table class="table table-hover">
  <thead>
    <tr>
      <th class="py-0">File or Folder Name</th>
      <th class="py-0 text-right">Last Update</th>
      <th class="py-0 text-center">Opsi</th>
    </tr>
  </thead>
  <tbody>
    <?php if (($getRecords)) : ?>
      <?php if (!$EOF) : ?>
        <tr>
          <td colspan="3" class="py-1">
            <a href="javascript:void(0)" title="Up Folder" data-id="<?= ($parent_id) ?: ''; ?>" class="cursor-pointer up_folder text-dark">
              <div class="d-flex justify-content-start align-items-center">
                <i class="fa fa-level-up-alt text-success mr-3"></i>
                <span class="text-name mt-3 h5"><i class="fa fa-ellipsis-h"></i></span>
              </div>
            </a>
          </td>
        </tr>
      <?php endif; ?>
      <?php $n = 0; ?>
      <?php foreach ($getRecords as $form) : $n++; ?>
        <tr class="">
          <td class="py-1">
            <a href="javascript:void(0)" data-id="<?= $form->id; ?>" class="cursor-pointer <?= ($form->flag_type == 'FOLDER') ? 'folder' : 'file'; ?> text-dark">
              <div class="d-flex justify-content-start align-items-center">
                <?php if ($form->flag_type == 'FOLDER') : ?>
                  <i class="fa fa-folder text-warning fa-3x mr-3"></i>
                <?php else : ?>
                  <i class="fa fa-file-alt text-success fa-3x mr-3"></i>
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
          <td class="py-1 text-right" width="135">
            <div class="btn-opsi mt-1">
              <?php if ($form->flag_type == 'FILE') : ?>
                <button type="button" class="btn btn-sm btn-icon btn-default view-record" title="View Document" data-id="<?= $form->id; ?>"><i class="fas fa-file-pdf text-primary"></i></button>
                <button type="button" class="btn btn-sm btn-icon btn-white edit-record" title="Edit Document" data-id="<?= $form->id; ?>"><i class="fa fa-edit text-warning"></i></button>
              <?php else : ?>
                <button type="button" class="btn btn-sm btn-icon btn-white edit-folder" title="Edit Folder" data-id="<?= $form->id; ?>"><i class="fa fa-edit text-warning"></i></button>
              <?php endif; ?>
              <button type="button" class="btn btn-sm btn-icon btn-white delete-record" title="Delete" data-id="<?= $form->id; ?>"><i class="fa fa-trash text-danger"></i></button>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <?php if (!$EOF) : ?>
        <tr>
          <td colspan="3" class="py-1">
            <a href="javascript:void(0)" title="Up Folder" data-id="<?= ($parent_id) ?: ''; ?>" class="cursor-pointer up_folder text-dark">
              <div class="d-flex justify-content-start align-items-center">
                <i class="fa fa-level-up-alt text-success mr-3"></i>
                <span class="text-name mt-3 h5"><i class="fa fa-ellipsis-h"></i></span>
              </div>
            </a>
          </td>
        </tr>
      <?php endif; ?>
      <tr>
        <td colspan="3" class="text-center py-3">
          <h5 class="text-gray">~ No data available~ </h5>
        </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>