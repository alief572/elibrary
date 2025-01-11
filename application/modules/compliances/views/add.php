<form id="new-complience">
  <div class="mb-3 row">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Regulation Name</label>
    <div class="col-9">
      <input type="hidden" name="reference_id" value="<?= (isset($regulations) && $regulations) ? $regulations[0]->reference_id : ''; ?>">
      <select name="regulation_id" id="regulation_id" class="form-control select2" data-allow-clear="true" data-placeholder="Choose an options">
        <option value=""></option>
        <?php if (isset($regulations)) : ?>
          <?php foreach ($regulations as $reg) : ?>
            <option value="<?= $reg->regulation_id; ?>" <?= (in_array($reg->regulation_id, $ArrCompl)) ? 'disabled' : ''; ?>><?= $reg->name; ?></option>
          <?php endforeach; ?>
        <?php endif; ?>
      </select>
      <span class="invalid-feedback">Regulation can't be empty</span>
    </div>
  </div>
</form>

<script>
  $(document).ready(function() {
    $('.select2').select2({
      width: '100%'
    })
  })
</script>