<!-- HEADER -->
<div class="row mb-3">
  <div class="col-md-6">
    <h2 class="mb-4"><i class="fa fa-desktop"></i> Current Compliance Overview</h2>
    <table class="table table-striped table-bordered table-sm">
      <tr>
        <th width="150">Company</th>
        <td><?= $reference->nm_perusahaan; ?></td>
      </tr>
      <tr>
        <th>State</th>
        <td><?= $reference->status; ?></td>
      </tr>
      <tr>
        <th>Created On</th>
        <td><?= $reference->created_at; ?></td>
      </tr>
      <tr>
        <th>Last Review</th>
        <td><?= $reference->last_review; ?></td>
      </tr>
      <tr>
        <th>Count Review</th>
        <td><?= $reference->counter_review; ?></td>
      </tr>
      <tr>
        <th>Review By</th>
        <td><?= isset($ArrUsers[$reference->review_by]) ? $ArrUsers[$reference->review_by] : ''; ?></td>
      </tr>
    </table>
  </div>
  <div class="col-md-6">
    <h2 class="mb-4"><i class="fa fa-stream"></i> Summary Overview</h2>
    <table class="table table-bordered table-sm">
      <tbody>
        <tr>
          <th>Compliance</th>
          <td class="text-center"><?= ($summary) ? $summary->total_compliance : '0'; ?></td>
        </tr>
        <tr>
          <th>Not Compliance</th>
          <td class="text-center"><?= ($summary) ? $summary->total_not_compliance : '0'; ?></td>
        </tr>
        <tr>
          <th>Not Applicable</th>
          <td class="text-center"><?= ($summary) ? $summary->total_not_applicable : '0'; ?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <th>% Compliance</th>
          <th class="text-center"><?= ($summary) ? round(($summary->total_compliance / ($summary->total_compliance + $summary->total_not_compliance)) * 100) : '0'; ?>%</th>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<!-- <hr> -->
<div class="row">
  <div class="col-md-12">
    <div class="mb-3">
      <div class="row">
        <label for="" class="col-1 form-control-label">Sort by :</label>
        <div class="w-25">
          <select name="sort_status" id="sort_status" data-id="<?= $reference->id; ?>" class="select2 form-control" data-width="100%">
            <option value="">Show All</option>
            <option value="CMP">Compliance</option>
            <option value="NCM">Not Compliance</option>
            <option value="NAP">Not Applicable</option>
          </select>
        </div>
        <!-- <button type="button" id="show" data-id="<?= $reference->id; ?>" class="btn btn-sm btn-primary mx-2">Show</button> -->
        <button type="button" id="export_pdf" data-id="<?= $reference->id; ?>" class="btn btn-sm btn-light"><i class="fa fa-file-pdf"></i> Export PDF</button>
      </div>
    </div>

    <div id="show-data">
      <table class="table table-sm table-bordered" style="font-size:12px">
        <thead>
          <tr class="">
            <th class="text-center" width="30">No.</th>
            <th class="text-center" width="200">REGULATION</th>
            <th class="text-center" width="350">PASAL</th>
            <th class="text-center" width="350">COMPLIANCE DESC.</th>
            <th class="text-center" width="100">STATUS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $compl = $not_compl = $not_apl = $total = $percent = $allReg = 0;
          if ($ArrReg) : ?>
            <?php $n = 0;
            foreach ($ArrReg as $key => $rg) : $n++; ?>
              <?php
              $allReg = count($rg);
              foreach ($rg as $j => $dt) :
                if ($dt->status == 'CMP') :
                  $compl++;
                endif;
                if ($dt->status == 'NCM') :
                  $not_compl++;
                endif;
                if ($dt->status == 'NAP') :
                  $not_apl++;
                endif;
              ?>
                <tr>
                  <?php if ($j == '0') : ?>
                    <th rowspan="<?= count($rg); ?>" class="text-center">
                      <?= $n; ?>
                    </th>
                  <?php endif; ?>
                  <?php if ($j == '0') : ?>
                    <td rowspan="<?= count($rg); ?>" class="">
                      <strong>
                        <p><?= $dt->category_name; ?></p>
                      </strong>
                      <p><?= $dt->regulation_name; ?></p>
                    </td>
                  <?php endif; ?>
                  <td>
                    <strong><?= $dt->pasal_name; ?></strong>
                    <br>
                    (<?= $dt->ayat; ?>) <?= $dt->description_ayat; ?>
                  </td>
                  <td><?= $dt->compliance_desc; ?></td>
                  <td class="text-center"><?= ($dt->status) ? $status[$dt->status] : ''; ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $('.select2').select2({
    allowClear: true,
    placeholder: "Show All",
    dropdownParent: $('#modalView')
  })
</script>