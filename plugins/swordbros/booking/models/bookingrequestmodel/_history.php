<?php
$histories = \Swordbros\Booking\models\BookingRequestHistoryModel::where(['booking_request_id'=>$model->id])->orderByDesc('id')->get();
if(!$histories->isEmpty()){ ?>
    <div>
        <table class="table data">
        <?php foreach ($histories as $history){?>
                <tr><td><?=$history->created_at ?></td><td class="text-start"><?= $history->user ?></td><td class="text-start"><?= $history->description ?></td> </tr>
        <?php } ?>
        </table>
    </div>
<?php } ?>
