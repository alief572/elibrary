<form id="form">
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold"><?= $label_name; ?></label>
    <div class="col-9">
      <input type="hidden" name="id" id="id" value="<?= $update->id; ?>">
      <select id="data_id" name="data_id" class="form-control form-select select2">
        <option value=""></option>
        <?php if ($data) : ?>
          <?php foreach ($data as $dt) : ?>
            <option value="<?= $dt->id; ?>" <?= ($dt->id == $update->data_id) ? 'selected' : ''; ?>><?= $dt->name; ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
      <span class="invalid-feedback"><?= $label_name; ?> can't be empty</span>

    </div>
  </div>
  <div class="mb-3 row flex-nowrap d-none">
    <label for="" class="col-3 col-form-label font-weight-bold">Name</label>
    <div class="col-9">
      <input type="text" name="name" value="<?= $update->name; ?>" class="form-control" id="name" placeholder="Name" />
      <span class="invalid-feedback">Name can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Year</label>
    <div class="col-3">
      <input type="text" name="year" value="<?= $update->year; ?>" class="form-control form-control-solid" readonly id="year" placeholder="Year" />
      <span class="invalid-feedback">Year can't be empty</span>
    </div>
    <label for="" class="col-2 col-form-label font-weight-bold">Latest Version</label>
    <div class="col-3">
      <input type="text" name="latest_version" value="<?= $update->latest_version; ?>" class="form-control" id="latest_version" placeholder="Latest Version" />
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Source</label>
    <div class="col-9">
      <input type="text" name="source" value="<?= $update->source; ?>" class="form-control" id="source" placeholder="Source" />
      <span class="invalid-feedback">Source can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Chacking Date</label>
    <div class="col-9">
      <input type="date" name="checking_date" value="<?= $update->checking_date; ?>" class="form-control" id="checking_date" placeholder="Date" />
      <span class="invalid-feedback">Date can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Status</label>
    <div class="col-9">
      <select name="status" id="status" class="form-control select2">
        <option value=""></option>
        <option value="NA" <?= ($update->status == 'NA') ? 'selected' : ''; ?>>No Updates</option>
        <option value="PLN" <?= ($update->status == 'PLN') ? 'selected' : ''; ?>>Planning</option>
        <option value="REV" <?= ($update->status == 'REV') ? 'selected' : ''; ?>>Revision</option>
        <option value="REP" <?= ($update->status == 'REP') ? 'selected' : ''; ?>>Invalidation</option>
      </select>
      <span class="invalid-feedback">Status can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="" class="col-3 col-form-label font-weight-bold">Descriptions</label>
    <div class="col-9">
      <textarea name="descriptions" id="descriptions" class="form-control" rows="5" placeholder="Descriptions"><?= $update->descriptions; ?></textarea>
      <span class="invalid-feedback">Descriptions can't be empty</span>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      width: '100%',
      allowClear: true,
      placeholder: 'Choose an options'
    });

  })
</script>