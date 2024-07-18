<?php Block::put('breadcrumb') ?>
    <ul class="float-start">
        <li><a href="<?= Backend::url('swordbros/event/event') ?>"><?= e(trans('event.plugin.events')) ?></a></li>
        <li><?= e($this->pageTitle) ?></li>
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

    <div class="form-preview">
        <?= $this->formRenderPreview() ?>
    </div>

<?php else: ?>
    <p class="flash-message static error"><?= e($this->fatalError) ?></p>
<?php endif ?>

<p>
    <a href="<?= Backend::url('swordbros/event/event') ?>" class="btn btn-default oc-icon-chevron-left">
        <?= e(trans('backend::lang.form.return_to_list')) ?>
    </a>
</p>
