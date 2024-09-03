<?php Block::put('breadcrumb') ?>
    <ul>
        <li><a href="<?= Backend::url('swordbros/booking/booking') ?>"><?= e(trans('booking.plugin.bookings')) ?></a></li>
        <li><?= e($this->pageTitle) ?></li>
    </ul>
<?php Block::endPut() ?>

<?php if (!$this->fatalError): ?>

    <div class="form-preview">
        <?= $this->formRenderPreview() ?>
    </div>

<?php else: ?>
    <p class="flash-message static error"><?= e($this->fatalError) ?></p>
<?php endif ?>

<p>
    <a href="<?= Backend::url('') ?>" class="btn btn-default oc-icon-chevron-left">
        <?= e(trans('backend::lang.form.return_to_lswordbros/booking/bookingist')) ?>
    </a>
</p>
