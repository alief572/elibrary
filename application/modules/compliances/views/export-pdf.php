<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemenuhan Regulasi</title>
  <style>
    *,
    body {
      margin: 0px;
      padding: 0px;
    }

    table.table-data {
      /* width: 100%; */
      font-size: 10px;
    }

    table.table-data-no-border {
      width: 100%;
      font-size: 10px;
    }

    table.table-data td,
    table.table-data th {
      padding: 2px;
      word-wrap: break-word;
      border: 1px solid #444
    }

    table.table-data {
      border-collapse: collapse;
    }

    table td {
      vertical-align: top;
    }

    /* Horizontal */
    .text-center {
      text-align: center;
    }

    .text-left {
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    /* Vertical */
    .text-middle {
      vertical-align: middle;
    }

    .text-top {
      vertical-align: top;
    }

    .text-bottom {
      vertical-align: bottom;
    }

    /* Padding */
    .p-1 {
      padding: 1px;
    }

    .p-2 {
      padding: 2px;
    }

    .p-3 {
      padding: 3px;
    }

    .p-4 {
      padding: 4px;
    }

    .p-5 {
      padding: 5px;
    }

    .bg-red {
      background-color: red;
    }
  </style>

</head>

<body>
  <div class="text-center">
    <h2><strong>IDENTIFIKASI DAN EVALUASI KEWAJIBAN PENAATAN</strong></h2>
  </div>
  <!-- HEADER -->
  <table class="table-data-no-border">
    <tr>
      <th width="100" class="text-left">Company</th>
      <td>: <?= $reference->nm_perusahaan; ?></td>
      <th width="100" class="text-left"></th>
      <td></td>
      <th width="100" class="text-left">Created On</th>
      <td width="100">: <?= $reference->sdate; ?></td>
    </tr>
    <tr>
      <th class="text-left">Last Review</th>
      <td>: <?= date('Y-m-d H:i:s'); ?></td>
      <th class="text-left">Count Review</th>
      <td>: <?= ($reference->counter_review) ? $reference->counter_review + 1 : 1; ?></td>
      <th class="text-left">Review By</th>
      <td>: <?= $ArrUsers[$this->auth->user_id()]; ?></td>
    </tr>
  </table>
  <hr>

  <table class="table-data-no-border">
    <tbody>
      <tr>
        <th width="100" class="text-left">Compliance</th>
        <td class="text-left">: <?= $summary['TC']; ?></td>
      </tr>
      <tr>
        <th class="text-left">Not Compliance</th>
        <td class="text-left">: <?= $summary['TNC']; ?></td>
      </tr>
      <tr>
        <th class="text-left">Not Applicable</th>
        <td class="text-left">: <?= $summary['TNA']; ?></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th class="text-left">% Compliance</th>
        <th class="text-left">:
          <?=
          ($summary['TC'] != 0 || $summary['TC'] != '') ? round(($summary['TC'] / ($summary['TC'] + $summary['TNC'])) * 100) : 0; ?>%</th>
      </tr>
    </tfoot>
  </table>
  <br>
  <table class="table-data" style="font-size: 10px;">
    <thead>
      <tr class="">
        <th class="text-center" width="30">No.</th>
        <th class="text-center">JENIS PERATURAN</th>
        <th class="text-center">PASAL/TOPIK</th>
        <th class="text-center">KONDISI AKTUAL</th>
        <th class="text-center">STATUS</th>
        <th class="text-center">PELUANG/RESIKO</th>
        <th class="text-center">TINDAKAN</th>
        <th class="text-center">PIC</th>
        <th class="text-center">DUE DATE</th>
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
          foreach ($rg as $j => $dt) : ?>
            <tr>
              <td class="text-center">
                <?php if ($j == '0') : ?>
                  <strong><?= $n; ?></strong>
                <?php endif; ?>
              </td>
              <td width="200">
                <?php if ($j == '0') : ?>
                  <p><strong><?= $dt->category_name; ?></strong></p>
                  <p><?= $dt->regulation_name; ?></p>
                <?php endif; ?>
              </td>
              <td width="300">
                <strong><?= $dt->pasal_name; ?></strong>
                <br>
                <?= ($dt->ayat) ? "(" . $dt->ayat . ")" : '' ?> <?= ($dt->description_ayat); ?>
              </td>
              <td><?= $dt->compliance_desc; ?></td>
              <td class="text-center <?= ($dt->status == 'NCM') ? 'bg-red' : ''; ?>"><?= $status[$dt->status]; ?></td>
              <td style=" padding:0px;margin:0px;" width="100">
                <?php $no = 0;
                if (isset($ArrOpports[$dt->prgh_id])) :
                  foreach ($ArrOpports[$dt->prgh_id] as $opr) : $no++;
                ?>
                    <p><?= $no . ". " . $cat[$opr->category]; ?></p>
                  <?php endforeach; ?>
                <?php endif; ?>
              </td>
              <td style="padding:0px;margin:0px;" width="100">
                <?php $no = 0;
                if (isset($ArrOpports[$dt->prgh_id])) :
                  foreach ($ArrOpports[$dt->prgh_id] as $opr) :  $no++;
                ?>
                    <p><?= $no . ". " . $opr->description_opports; ?></p>
                  <?php endforeach; ?>
                <?php endif; ?>
              </td>
              <td style="padding:0px;margin:0px;" width="100">
                <?php $no = 0;
                if (isset($ArrOpports[$dt->prgh_id])) :
                  foreach ($ArrOpports[$dt->prgh_id] as $opr) : $no++;
                ?>
                    <p><?= $no . ". " . $ArrUsers[$opr->pic]; ?></p>
                  <?php endforeach; ?>
                <?php endif; ?>
              </td>
              <td style="padding:0px;margin:0px;" width="100">
                <?php $no = 0;
                if (isset($ArrOpports[$dt->prgh_id])) :
                  foreach ($ArrOpports[$dt->prgh_id] as $opr) :  $no++;
                ?>
                    <p><?= $no . ". " . $opr->due_date; ?></p>
                  <?php endforeach; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</body>

</html>