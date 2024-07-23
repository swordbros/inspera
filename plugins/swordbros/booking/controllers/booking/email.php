
<?php if (!$this->fatalError): ?>

    <?= Form::open(['class' => 'layout']) ?>

    <div class="layout-row">
        <div class="col-6"><?= $this->formRender() ?></div>
        <div class="col-6"></div>
    </div>

    <div class="form-buttons">
        <div class="loading-indicator-container">
            <button
                type="submit"
                data-request="onSendEmail"
                data-hotkey="ctrl+s, cmd+s"
                data-load-indicator="<?= e(trans('backend::lang.form.send')) ?>"
                class="btn btn-primary">
                <?= e(trans('backend::lang.form.send')) ?>
            </button>
        </div>
    </div>
    <?= Form::close() ?>

<?php else: ?>
    <p class="flash-message static error"><?= e(trans($this->fatalError)) ?></p>
    <p><a href="<?= Backend::url('swordbros/event/eventzone') ?>" class="btn btn-default"><?= e(trans('backend::lang.form.return_to_list')) ?></a></p>
<?php endif ?>
