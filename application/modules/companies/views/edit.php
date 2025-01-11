<form id="form-company">
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Company Name <span class="text-danger">*</span></label>
    <div class="col-9">
      <input type="hidden" name="id_perusahaan" value="<?= $data->id_perusahaan; ?>">
      <input type="text" value="<?= $data->nm_perusahaan; ?>" name="nm_perusahaan" class="form-control required" id="nm_perusahaan" placeholder="Company Name" />
      <span class="invalid-feedback">Company Name can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Address <span class="text-danger">*</span></label>
    <div class="col-9">
      <textarea type="text" name="alamat" id="alamat" class="form-control required" placeholder="Jl. ..."><?= $data->alamat; ?></textarea>
      <span class="invalid-feedback">Address can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">City <span class="text-danger">*</span></label>
    <div class="col-4">
      <input type="text" value="<?= $data->kota; ?>" name="kota" id="kota" class="form-control required" placeholder="Jakarta">
      <span class="invalid-feedback">City can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Inisial <span class="text-danger">*</span></label>
    <div class="col-4">
      <input type="text" value="<?= $data->inisial; ?>" name="inisial" id="inisial" maxlength="3" class="form-control required" placeholder="SSC">
      <span class="invalid-feedback">Inisial can't be empty</span>
    </div>
  </div>

  <hr>
  <div id="branch-list">
    <?php if ($branch) foreach ($branch as $k => $val) : $k++; ?>
      <div class="card branch border-info mb-3">
        <div class="card-header p-3">
          <h4 class="card-title font-weight-bolder mb-0"><i class="fas fa-code-branch text-dark"></i> Branch #<?= $k; ?></h4>
        </div>
        <div class="card-body p-4">
          <input type="hidden" name="branch[<?= $k; ?>][id]" value="<?= $val->id; ?>">
          <div class="mb-3 row flex-nowrap">
            <label for="" class="col-3 col-form-label font-weight-bold">Company Branch Name <span class="text-danger">*</span></label>
            <div class="col">
              <input type="text" name="branch[<?= $k; ?>][branch_name]" id="branch<?= $k; ?>" value="<?= $val->branch_name; ?>" class="form-control required" placeholder="Branch Name">
              <span class="invalid-feedback">Company Branch Name can't be empty</span>
            </div>
          </div>
          <div class="mb-3 row flex-nowrap">
            <label for="" class="col-3 col-form-label font-weight-bold">Address <span class=" text-danger">*</span></label>
            <div class="col">
              <textarea name="branch[<?= $k; ?>][address]" id="branch<?= $k; ?>" class="form-control required" placeholder="Branch Name"><?= $val->branch_address; ?></textarea>
              <span class="invalid-feedback">Address Branch can't be empty</span>
            </div>
          </div>
          <div class="mb-3 row flex-nowrap">
            <label for="" class="col-3 col-form-label font-weight-bold">City <span class=" text-danger">*</span></label>
            <div class="col-4">
              <input type="text" name="branch[<?= $k; ?>][city]" id="branch<?= $k; ?>" class="form-control required" value="<?= $val->branch_city; ?>" placeholder="Branch Name">
              <span class="invalid-feedback">City Branch can't be empty</span>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <button type="button" class="btn btn-sm btn-outline-info" id="add-branch"><i class="fa fa-plus" aria-hidden="true"></i>Add Branch</button>

</form>