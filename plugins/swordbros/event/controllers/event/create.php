<?php Block::put('breadcrumb') ?>
    <ul class="float-start">
        <li><a href="<?= Backend::url('swordbros/event/event') ?>"><?= e(trans('event.plugin.events')) ?></a></li>
        <li><?= e($this->pageTitle) ?></li>
        <li>Tercüme Et</li>
    </ul>
<!--<ul class="float-end">-->
<!--    --><?php //if($sites = \Swordbros\Base\Controllers\Amele::getEnableSites()){
//        foreach($sites as $site){?>
<!--            <li>-->
<!--                <a class="event-translate nav-icon nav-icon-flag --><?php //if($site['active']) echo 'active';?><!--"-->
<!--                   href="?--><?php //= http_build_query(array_merge(get(), ['_site_id' => e($site['id'])])) ?><!--"><i class="--><?php //= e($site['flagIcon']) ?><!--"></i></a>-->
<!--                </span>-->
<!--            </li>-->
<!--        --><?php //}
//    } ?>
<!--</ul>-->
<?php Block::endPut() ?>

<?php if (!$this->fatalError): ?>

    <?= Form::open(['class' => 'layout', 'data-required'=>'']) ?>

        <div class="layout-row">
            <?= $this->formRender() ?>
        </div>

        <div class="form-buttons">
            <div class="loading-indicator-container">
                <button
                    type="submit"
                    data-request="onSave"
                    data-hotkey="ctrl+s, cmd+s"
                    data-load-indicator="<?= e(trans('backend::lang.form.saving')) ?>"
                    class="btn btn-primary">
                    <?= e(trans('backend::lang.form.create')) ?>
                </button>
                <button
                    type="button"
                    data-request="onSave"
                    data-request-data="close:1"
                    data-hotkey="ctrl+enter, cmd+enter"
                    data-load-indicator="<?= e(trans('backend::lang.form.saving')) ?>"
                    class="btn btn-default">
                    <?= e(trans('backend::lang.form.create_and_close')) ?>
                </button>
                <span class="btn-text">
                    <?= e(trans('backend::lang.form.or')) ?> <a href="<?= Backend::url('swordbros/event/event') ?>"><?= e(trans('backend::lang.form.cancel')) ?></a>
                </span>
            </div>
        </div>

    <?= Form::close() ?>

<?php else: ?>
    <p class="flash-message static error"><?= e(trans($this->fatalError)) ?></p>
    <p><a href="<?= Backend::url('swordbros/event/event') ?>" class="btn btn-default"><?= e(trans('backend::lang.form.return_to_list')) ?></a></p>
<?php endif ?>
