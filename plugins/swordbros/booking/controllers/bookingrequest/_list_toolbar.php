<div data-control="toolbar">

<!--    <a-->
<!--        href="--><?php //= Backend::url('swordbros/booking/booking/create') ?><!--"-->
<!--        class="btn btn-primary oc-icon-plus">-->
<!--        --><?php //= e(trans('backend::lang.form.create')) ?>
<!--    </a>-->

    <button
        class="btn btn-default oc-icon-trash-o"
        data-request="onDelete"
        data-request-confirm="<?= e(trans('backend::lang.list.delete_selected_confirm')) ?>"
        data-list-checked-trigger
        data-list-checked-request
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')) ?>
    </button>
    <a
        class="btn btn-default oc-icon-file-excel"
        target="_blank"
        href="<?= Backend::url('swordbros/booking/bookingrequest/toexcel') ?>"
    >
        Excel
    </a>
</div>
