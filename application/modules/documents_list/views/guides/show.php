<div class="tab-content mt-5">
    <div style="width:92%;height:500px;background-color: red;position: absolute;opacity: 0;"></div>
    <?php if ($ext == 'pdf' || $ext == 'PDF') : ?>
        <iframe src="<?= base_url("directory/MASTER_GUIDES/$file->company_id/$file->document"); ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="500px"></iframe>
    <?php else : ?>
        <iframe src="https://docs.google.com/gview?embedded=true&url=<?= base_url("directory/MASTER_GUIDES/$file->company_id/$file->document"); ?>&rm=minimal#toolbar=0&navpanes=0" frameborder="0" width="100%" height="450px"></iframe>
    <?php endif; ?>
    <hr>
</div>