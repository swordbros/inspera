<div data-control="toolbar">

    <?php foreach(\Swordbros\Base\Controllers\Amele::eventTypes() as $service){ ?>
        <a
            href="<?= Backend::url('swordbros/event/event/create') ?>?event_type_id=<?=$service->id?>"
            class="btn btn-primary oc-icon-plus">
            <?=$service->name?> <?= e(trans('backend::lang.form.create')) ?>
        </a>

    <?php } ?>
    <button
        class="btn btn-default oc-icon-trash-o"
        data-request="onDelete"
        data-request-confirm="<?= e(trans('backend::lang.list.delete_selected_confirm')) ?>"
        data-list-checked-trigger
        data-list-checked-request
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')) ?>
    </button>
</div>
