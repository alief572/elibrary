<form id="form">
  <div class="mb-3 row flex-nowrap">
    <label for="Name" class="col-3 col-form-label font-weight-bold">Category Name</label>
    <div class="col-9">
      <input type="hidden" name="id" id="id" value="<?= $data->id; ?>">
      <input type="text" value="<?= $data->name; ?>" name="name" class="form-control" id="name" placeholder="Category Name" />
      <span class="invalid-feedback">Category Name can't be empty</span>
    </div>
  </div>
</form>