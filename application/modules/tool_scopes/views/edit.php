<form id="form">
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Name</label>
    <div class="col-9">
      <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
      <input type="text" value="<?= $data->name; ?>" name="name" class="form-control" id="name" placeholder="Name" />
      <span class="invalid-feedback">Name can't be empty</span>
    </div>
  </div>
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Code</label>
    <div class="col-9">
      <input type="text" value="<?= $data->code; ?>" name="code" maxlength="10" class="form-control" id="code" placeholder="Code" />
      <span class="invalid-feedback">Code can't be empty</span>
      <small class="help-text text-muted">max. 10 Characters</small>
    </div>
  </div>
</form>