<!-- <div class="tab-content mt-5">
    <div style="width:92%;height:500px;background-color: red;position: absolute;opacity: 0;"></div>

    <?php if ($ext == 'pdf' || $ext == 'PDF') : ?>
        <iframe src="<?= base_url("directory/MATERI/$file->company_id/$file->document"); ?>#toolbar=0&navpanes=0" frameborder="0" width="100%" height="500px"></iframe>
    <?php else : ?>
        <iframe src="https://docs.google.com/gview?embedded=true&url=<?= base_url("directory/MATERI/$file->company_id/$file->document"); ?>&rm=minimal#toolbar=0&navpanes=0" frameborder="0" width="100%" height="450px"></iframe>
    <?php endif; ?>
    <hr>
</div> -->
<?php
$has_document = !empty($file->document);
$has_external_link = !empty($file->url_link);
$has_video = !empty($file->video_link);
$active_tab = $has_document ? 'document' : ($has_external_link ? 'external' : ($has_video ? 'video' : ''));
?>
<style>
    .materi-viewer-content,
    .materi-viewer-content > .tab-content,
    .materi-viewer-content > .tab-content > .tab-pane {
        height: 100%;
        min-height: 0;
    }

    .materi-viewer-content {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #202124;
    }

    .materi-tabs-drawer {
        position: absolute;
        z-index: 5;
        top: 10px;
        left: 0;
        display: flex;
        align-items: stretch;
        padding: 4px;
        padding-right: 32px;
        transform: translateX(calc(-100% + 28px));
        transition: transform .2s ease;
        border-radius: 0 8px 8px 0;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .16);
    }

    .materi-tabs-drawer:hover,
    .materi-tabs-drawer:focus-within,
    .materi-tabs-drawer.is-open {
        transform: translateX(0);
    }

    .materi-tabs-drawer .nav-tabs {
        display: flex;
        flex-wrap: nowrap;
        padding: 4px;
        border: 0;
        background: transparent;
    }

    .materi-tabs-drawer .nav-link {
        margin-right: 3px;
        padding: 6px 10px;
        border: 0;
        border-radius: 5px;
        color: #5f6368;
        background: transparent;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
    }

    .materi-tabs-drawer .nav-item:last-child .nav-link {
        margin-right: 0;
    }

    .materi-tabs-drawer .nav-link.active {
        color: #202124;
        background: #f1f3f4;
    }

    .materi-tabs-handle {
        position: absolute;
        top: 0;
        right: 0;
        width: 28px;
        height: 100%;
        padding: 0;
        border: 0;
        border-left: 1px solid #e8eaed;
        border-radius: 0 8px 8px 0;
        background: transparent;
        color: #5f6368;
        cursor: pointer;
    }

    .materi-tabs-handle:focus {
        outline: 2px solid #f3b200;
        outline-offset: -2px;
    }

    .materi-viewer-content > .tab-content {
        flex: 1 1 auto;
        overflow: hidden;
    }

    .materi-document-frame {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
        background: #525659;
    }

    .materi-external-pane {
        position: relative;
        height: 100%;
        overflow: hidden;
    }

    .materi-external-frame {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
        background: #fff;
    }

    .materi-external-action {
        position: absolute;
        z-index: 4;
        top: 58px;
        left: 12px;
        padding: 7px 12px;
        border-radius: 6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .18);
        font-size: 12px;
    }

    .materi-empty-state {
        height: 100%;
        color: #fff;
    }
</style>

<div class="materi-viewer-content">
<?php if ($has_document || $has_external_link || $has_video) : ?>
<div class="materi-tabs-drawer" id="materiTabsDrawer">
<ul class="nav nav-tabs" id="tab-upload" role="tablist">
    <?php if ($has_document) : ?>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'document' ? 'active' : ''; ?>" id="upload-document-tab" data-toggle="tab" data-target="#upload-document" type="button" role="tab" aria-controls="upload-document" aria-selected="<?= $active_tab === 'document' ? 'true' : 'false'; ?>">Document</button>
    </li>
    <?php endif; ?>
    <?php if ($has_external_link) : ?>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'external' ? 'active' : ''; ?>" id="from-external-url-tab" data-toggle="tab" data-target="#from-external-url" type="button" role="tab" aria-controls="from-external-url" aria-selected="<?= $active_tab === 'external' ? 'true' : 'false'; ?>">External Link <i class="ml-1 fa fa-link text-primary" aria-hidden="true"></i></button>
    </li>
    <?php endif; ?>
    <?php if ($has_video) : ?>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= $active_tab === 'video' ? 'active' : ''; ?>" id="from-url-tab" data-toggle="tab" data-target="#from-url" type="button" role="tab" aria-controls="from-url" aria-selected="<?= $active_tab === 'video' ? 'true' : 'false'; ?>">YouTube <i class="fab fa-youtube ml-1 text-danger"></i></button>
    </li>
    <?php endif; ?>
</ul>
<button type="button" class="materi-tabs-handle" aria-label="Tampilkan pilihan konten" aria-controls="tab-upload" aria-expanded="false">&#8250;</button>
</div>
<?php endif; ?>

<div class="tab-content">
    <?php if ($has_document) : ?>
    <div class="tab-pane <?= $active_tab === 'document' ? 'active show' : ''; ?> p-0" id="upload-document" role="tabpanel" aria-labelledby="upload-document-tab">
        <iframe class="materi-document-frame" src="<?= base_url('directory/MATERI/' . rawurlencode($file->company_id) . '/' . rawurlencode($file->document)); ?>#toolbar=0&navpanes=0&scrollbar=1&view=FitH" title="<?= html_escape($file->name); ?>" allowfullscreen></iframe>
    </div>
    <?php endif; ?>
    <?php if ($has_external_link) : ?>
    <div class="tab-pane materi-external-pane <?= $active_tab === 'external' ? 'active show' : ''; ?> p-0" id="from-external-url" role="tabpanel" aria-labelledby="from-external-url-tab">
        <iframe class="materi-external-frame" src="<?= html_escape($file->url_link); ?>" title="External Link - <?= html_escape($file->name); ?>"></iframe>
        <a href="<?= html_escape($file->url_link); ?>" target="_blank" rel="noopener noreferrer" class="materi-external-action btn btn-success">
            Buka di tab baru <i class="fa fa-external-link-alt ml-1" aria-hidden="true"></i>
        </a>
    </div>
    <?php endif; ?>
    <?php if ($has_video) : ?>
    <div class="tab-pane <?= $active_tab === 'video' ? 'active show' : ''; ?> p-0 border border-top-0 rounded-bottom" id="from-url" role="tabpanel" aria-labelledby="from-url-tab">
            <iframe class="materi-document-frame" src="https://youtube.com/embed/<?= html_escape($file->video_link); ?>" title="YouTube - <?= html_escape($file->name); ?>" allowfullscreen></iframe>
    </div>
    <?php endif; ?>
    <?php if (!$has_document && !$has_external_link && !$has_video) : ?>
        <div class="materi-empty-state d-flex align-items-center justify-content-center">Konten belum tersedia</div>
    <?php endif; ?>
</div>
</div>

<script>
    (function() {
        var $drawer = $('#materiTabsDrawer');
        var $handle = $drawer.find('.materi-tabs-handle');

        $handle.on('click', function() {
            var isOpen = $drawer.toggleClass('is-open').hasClass('is-open');
            $handle.attr('aria-expanded', isOpen ? 'true' : 'false').html(isOpen ? '&#8249;' : '&#8250;');
        });

        $drawer.find('[data-toggle="tab"]').on('click', function() {
            $drawer.removeClass('is-open');
            $handle.attr('aria-expanded', 'false').html('&#8250;');
        });
    })();
</script>
