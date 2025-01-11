<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
  <div class="d-flex flex-column-fluid">
    <div class="container">
      <form id="form-desc">
        <h3 for="" class="font-weight-bolder mb-5">Pasa l</h3>
        <input type="hidden" name="pasal_id" value="<?= $data->id; ?>">
        <input type="hidden" name="regulation_id" value="<?= $data->regulation_id; ?>">
        <table class="table table-bordered pharagraps rounded-lg mb-6">
          <thead>
            <tr class="table-secondary">
              <th class="text-center" width="20"></th>
              <th class="text-center" width="200">Ayat</th>
              <th class="text-center" width="100">Order</th>
              <th class="text-center" colspan="2">Description</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </form>
      <button type="button" class="btn btn-sm btn-success" id="add-desc"><i class="fa fa-plus"></i> Add</button>
    </div>
  </div>
</div>