<!-- HEADER -->
<table class="table table-striped table-bordered table-sm">
  <thead>
    <tr>
      <th>No.</th>
      <th>Last Review at</th>
      <th>Compliance (%)</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($review) foreach ($review as $k => $v): $k++; ?>
      <tr>
        <td><?= $k; ?></td>
        <td>
          <a target="_blank" href="<?= base_url("directory/COMPILATIONS/" . $v->document); ?>" title="Download Compilation Review">
            <?= $v->last_review; ?>
          </a>
        </td>
        <td class="text-center"><?= (isset($v->total_compliance) && $v->total_compliance) ? round(($v->total_compliance / ($v->total_compliance + $v->total_not_compliance)) * 100) : '0'; ?>%</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>