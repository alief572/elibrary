<form id="form-company">
  <div class="row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Company Name</label>
    <div class="col-9">
      <input type="hidden" name="id_perusahaan" id="" value="">
      <span class="">: <?= $data->nm_perusahaan; ?></span></span>
    </div>
  </div>
  <div class="row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Address</label>
    <div class="col-9">
      <span class="">: <?= $data->alamat; ?></span></span>
    </div>
  </div>
  <div class="row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">City</label>
    <div class="col-4">
      <span class="">: <?= $data->kota; ?></span></span>
    </div>
  </div>
  <div class="row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Inisial</label>
    <div class="col-4">
      <span class="">: <?= $data->inisial; ?></span></span>
    </div>
  </div>

  <!-- Branch -->
  <?php if ($branch) foreach ($branch as $k => $val) : $k++; ?>
    <div class="card branch border-info">
      <div class="card-header p-3">
        <h4 class="card-title font-weight-bolder mb-0"><i class="fas fa-code-branch text-dark"></i> Branch #<?= $k; ?></h4>
      </div>
      <div class="card-body p-3">
        <div class="row flex-nowrap">
          <label for="" class="col-3 col-form-label font-weight-bold">Company Branch Name</label>
          <div class="col">: <?= $val->branch_name; ?></div>
        </div>
        <div class="row flex-nowrap">
          <label for="" class="col-3 col-form-label font-weight-bold">Address</label>
          <div class="col">: <?= $val->branch_address; ?></div>
        </div>
        <div class="row flex-nowrap">
          <label for="" class="col-3 col-form-label font-weight-bold">City</label>
          <div class="col-4">: <?= $val->branch_city; ?></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</form>