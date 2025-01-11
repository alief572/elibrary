<div class="row">
  <div class="col-md-10">
    <div class="row flex-nowrap">
      <label for="" class="col-3 font-weight-bold">Company</label>
      <div class="col-6">:
        <label for="" class="font-weight-bolder"><?= $Data->nm_perusahaan; ?></label>
      </div>
    </div>
    <div class="row flex-nowrap">
      <label for="" class="col-3 font-weight-bold">Start Date</label>
      <div class="col-6">: <label class="font-weight-bolder"><?= $Data->sdate; ?></label></div>
    </div>
    <div class="row flex-nowrap">
      <label class="col-3 font-weight-bold">Descriptions</label>
      <div class="col-6">: <label class="font-weight-bolder"><?= $Data->descriptions; ?></label></div>
    </div>
  </div>
</div>
<hr>

<!-- STANDARD -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="font-weight-bolder"><i class="fa fa-list-alt text-primary" aria-hidden="true"></i> List Standard</h4>
</div>

<table id="tableStandard" class="table table-sm table-condensed table-bordered">
  <thead class="text-center ">
    <tr class="table-light">
      <th class="py-2" width="30">No</th>
      <th class="py-2 text-start">Standard Name</th>
    </tr>
  </thead>
  <tbody>
    <?php if (isset($datStd)) : ?>
      <?php $n = 0;
      foreach ($datStd as $std) : $n++; ?>
        <tr>
          <td class="text-center"><?= $n; ?>
          </td>
          <td class="">
            <span class="dataIdStd d-none"><?= $std->standard_id; ?></span>
            <?= $std->name; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr class="empty">
        <td colspan="2" class="text-center text-muted">~ No data avilable ~</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
<br>

<!-- REGULATIONS -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="font-weight-bolder"><i class="fa fa-list-alt text-primary" aria-hidden="true"></i> List Regulations</h4>
</div>

<table id="tableRegulations" class="table table-sm table-condensed table-bordered">
  <thead class="text-center ">
    <tr class="table-light">
      <th class="py-2" width="30">No</th>
      <th class="py-2">Regulations Name</th>
    </tr>
  </thead>
  <tbody>
    <?php if (isset($dataReg)) : ?>
      <?php $n = 0;
      foreach ($dataReg as $reg) : $n++; ?>
        <tr>
          <td class="text-center"><?= $n; ?></td>
          <td class="">
            <span class="dataIdReg d-none"><?= $reg->regulation_id; ?></span>
            <?= $reg->name; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else : ?>
      <tr class="empty">
        <td colspan="2" class="text-center text-muted">~ No data avilable ~</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>