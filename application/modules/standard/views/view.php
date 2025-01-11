<h1 class="text-center px-10 mb-10"><?= $Data->name; ?></h1>
<hr>
<div class="row">
  <div class="col-md-6">
    <div class="mb-3 row flex-nowrap">
      <label for="" class="col-4 font-weight-bold">Standard Category</label>
      <div class="col-8"> :
        <?= $Data->category_name; ?>
      </div>
    </div>

    <div class="mb-3 row flex-nowrap">
      <label for="scope" class="col-4 font-weight-bold">Scope</label>
      <div class="col-8"> :
        <?= $Data->scope_name; ?>
      </div>
    </div>

    <div class="mb-3 row flex-nowrap">
      <label class="col-4 font-weight-bold">Number</label>
      <div class="col-8"> :
        <?= $Data->number; ?>
      </div>
    </div>
    <div class="mb-3 row flex-nowrap">
      <label for="Name" class="col-4 font-weight-bold">Subject</label>
      <div class="col-8"> :
        <?= $Data->subject; ?>
      </div>
    </div>

  </div>

  <div class="col-md-6">
    <div class="mb-3 row flex-nowrap">
      <label for="" class="col-4 font-weight-bold">Year</label>
      <div class="col-4"> :
        <?= $Data->year; ?>
      </div>
    </div>

    <div class="mb-3 row flex-nowrap">
      <label for="source" class="col-4 font-weight-bold">Source</label>
      <div class="col-8"> :
        <?= $Data->source; ?>
      </div>
    </div>

    <div class="mb-3 row flex-nowrap">
      <label for="" class="col-4 font-weight-bold">Frequence Check</label>
      <div class="col-8"> :
        <?= $Data->frequence_check . " Tahun"; ?>
      </div>
    </div>

    <div class="mb-3 row flex-nowrap">
      <label for="" class="col-4 font-weight-bold">Status</label>
      <div class="col-8"> :
        <?php if ($Data->status == 'PUB') : ?>
          <span class="badge badge-primary">Active</span>
        <?php elseif ($Data->status == 'PUB') : ?>
          <span class="badge badge-secodnary">Draft</span>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <!--  -->
  <div class="col-md-12">
    <hr>
    <div class="mb-3 row flex-nowrap">
      <label for="Name" class="col-2 font-weight-bold">Standard Name</label>
      <div class="col-10"> :
        <?= $Data->name; ?>
      </div>
    </div>
    <hr>
    <div class="mb-3 row flex-nowrap">
      <label for="revision_desc" class="col-2 font-weight-bold">Revision Description</label>
      <div class="col-10"> :
        <?= $Data->revision_desc; ?>
      </div>
    </div>
    <hr>
    <div class="mb-3 row flex-nowrap">
      <label for="document" class="col-2 font-weight-bold">Document</label>
      <div class="col-10">

        <?php if ($Data->document && file_exists($exists_file)) : ?>
          <div class="d-flex justify-content-between align-items-center">
            <a target="_blank" href="<?= base_url($exists_file); ?>">
              <div class="d-flex align-items-center">
                <i class="fa fa-file-alt text-success fa-3x mr-3"></i><?= $Data->name; ?>
              </div>
            </a>
          </div>
        <?php else : ?>
          <div class="d-flex justify-content-between align-items-center">
            <a href="#not-found">
              <div class="d-flex align-items-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/Blank-document-broken.svg/1024px-Blank-document-broken.svg.png" alt="file-not-found" class="img-fluid d-inline" width="50">
                <?= $Data->name; ?>
              </div>
            </a>
          </div>
        <?php endif; ?>

      </div>
    </div>
    <hr>
  </div>
</div>