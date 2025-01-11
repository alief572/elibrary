<form id="form">
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Company Name <span class="text-danger">*</span></label>
    <div class="col-9">
      <select name="company_id" id="company_id" class="form-control select2 required" data-placeholder="Select Company">
        <option></option>
        <?php if ($companies) foreach ($companies as $k => $v) : ?>
          <option value="<?= $v->id; ?>"><?= $v->company_name; ?></option>
        <?php endforeach; ?>
      </select>
      <span class="invalid-feedback">Company Name can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Badan Sertifikasi <span class="text-danger">*</span></label>
    <div class="col-9">
      <select name="badan_sert_id" id="badan_sert_id" class="form-control select2 required" data-placeholder="Select Badan">
        <option></option>
        <?php if ($badan) foreach ($badan as $k => $v) : ?>
          <option value="<?= $v->id; ?>"><?= $v->name; ?></option>
        <?php endforeach; ?>
      </select>
      </select>
      <span class="invalid-feedback">Badan Sertifikasi can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Description</label>
    <div class="col-9">
      <textarea name="notes" rows="5" class="form-control" placeholder="Description"></textarea>
    </div>
  </div>
  <!-- <hr> -->
  <!-- <div class="mb-3">
    <h4 class="font-bold">Standard</h4>
    <table id="tblStandard" class="table rounded table-sm table-bordered table-hover">
      <thead>
        <tr>
          <th width="50" class="text-center">No</th>
          <th>Standard Name</th>
          <th width="80" class="text-center">Remove</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
    <button type="button" id="add-standard" class="btn btn-sm btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Standard</button>
  </div> -->
</form>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      dropdownParent: $('#modalID .modal-body'),
      allowClear: true,
      width: "100%"
    })
  })
</script>