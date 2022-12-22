<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
  <div class="d-flex flex-column-fluid">
    <div class="container">
      <form id="form-desc">
        <h3 for="" class="font-weight-bolder mb-5"><?= $desc->pasal_id; ?></h3>
        <input type="hidden" name="id" value="<?= $desc->id; ?>">
        <table class="table table-bordered pharagraps rounded-lg mb-6">
          <thead>
            <tr class="table-secondary">
              <th class="text-center" width="20"></th>
              <th class="text-center" colspan="2">Description</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($desc) : ?>
              <tr>
                <td><i class="fa fa-pen"></i></td>
                <td>
                  <textarea name="desc" class="form-control"> <?= $desc->description; ?></textarea>
                </td>
                <td></td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>