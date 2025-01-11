<form id="form">
  <input type="hidden" name="id" value="<?= $data->id; ?>">
  <div class="form-group">
    <label for="" class="h6 font-weight-bold mb-5">Company Name <span class="text-danger">*</span></label>
    <input type="company_name" name="company_name" class="form-control form-control-lg required" aria-describedby="helpId" value="<?= $data->company_name; ?>">
  </div>
</form>
