<!-- VIEW PROCEDURE PDF -->
<div class="text-center mb-3">
    <h4 class="font-weight-bold"><?= $docs->name; ?></h4>
</div>
<iframe src="<?= base_url('procedures/viewPdf/' . $docs->id); ?>#toolbar=0&navpanes=0" style="width:100%; height:75vh; border:none;" frameborder="0"></iframe>
