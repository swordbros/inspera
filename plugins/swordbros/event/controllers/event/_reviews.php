<?php
$reviews = \Swordbros\Event\Models\EventReviewModel::where(['event_id'=>$model->id])->orderByDesc('id')->get();
if(!$reviews->isEmpty()){ ?>
    <div>
        <div>
            <table class="table data">
                <?php foreach ($reviews as $review){?>
                    <tr>
                        <td><?=$review->user->username ?></td>
                        <td><?= \Swordbros\Base\Controllers\Amele::stars($review->stars)?></td>
                        <td class="text-start"><?= e(__($review->review)) ?></td>
                        <td id="status-icons-<?= $review->id ?>" class="text-end">
                            <?php if($review->status){ ?>
                            <button type="button" class="oc-icon-ban btn-icon" data-request-data="{status:0,review_id:<?= $review->id ?>}" data-request="onEventReviewStatusChange"></button>
                            <?php } else { ?>
                            <button type="button" class="oc-icon-check btn-icon" data-request-data="{status:1,review_id:<?= $review->id ?>}" data-request="onEventReviewStatusChange"></button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>

    </div>
<?php } ?>
