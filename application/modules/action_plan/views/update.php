<form id="form">
  <div class="card border-0 rounded-lg mb-3" style="box-shadow: 2px 2px 6px #eaeaea;">
    <div class="card-body py-3">
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span>Company Name</span>
            <span>:</span>
          </div>
        </div>
        <div class="col-9">
          <input type="hidden" name="id" id="id" value="<?= $opport->id; ?>">
          <label for="" class="font-weight-bolder"><?= $company->nm_perusahaan; ?></label>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 rounded-lg mb-3" style="box-shadow: 2px 2px 6px #eaeaea;">
    <div class="card-body py-3">
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span>Regulation</span>
            <span>:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $regulation->name; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span>Pasal</span>
            <span>:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $pasal->name; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span>Ayat</span>
            <span>:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $prgh->name; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span>Description</span>
            <span>:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $prgh->description; ?></label>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 rounded-lg mb-3" style="box-shadow: 2px 2px 6px #eaeaea;">
    <div class="card-body py-3">
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Pemenuhan</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $detailComp->compliance_desc; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Status</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $status[$detailComp->status]; ?></label>
        </div>
      </div>
    </div>
  </div>

  <div class="card border-0 rounded-lg mb-3" style="box-shadow: 2px 2px 6px #eaeaea;">
    <div class="card-body py-3">
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Kategori</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $cat[$opport->category]; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Deskrpisi</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $opport->description; ?></label>
        </div>
      </div>

      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Rencana</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $opport->action_plan; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">PIC</span>
            <span class:="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $ArrUsers[$opport->pic]; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Due Date</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <label for=""><?= $opport->due_date; ?></label>
        </div>
      </div>
      <div class="row">
        <div class="col-3 font-weight-bold">
          <div class="d-flex justify-content-between">
            <span class="">Status</span>
            <span class="">:</span>
          </div>
        </div>
        <div class="col-9">
          <select name="status" class="form-control select2" data-placeholder="Choose an options" data-allow-clear="true" data-width="100%">
            <option value=""></option>
            <option value="OPN" <?= ($opport->status == 'OPN') ? 'selected' : ''; ?>>OPEN</option>
            <option value="PRO" <?= ($opport->status == 'PRO') ? 'selected' : ''; ?>>PROGRESS</option>
            <option value="CNL" <?= ($opport->status == 'CNL') ? 'selected' : ''; ?>>CANCEL</option>
            <option value="DNE" <?= ($opport->status == 'DNE') ? 'selected' : ''; ?>>DONE</option>
          </select>
        </div>
      </div>
    </div>
  </div>

</form>

<script>
  $(document).ready(function() {
    $('.select2').select2()
  })
</script>