<?php
$histories = \Swordbros\Booking\models\BookingHistoryModel::where(['booking_id'=>$model->id])->orderByDesc('id')->get();
if(!$histories->isEmpty()){ ?>
    <div>

            <div>
                <table class="table data">
                    <?php foreach ($histories as $history){?>
                        <tr><td><?=$history->created_at ?></td><td class="text-start"><?= e(__($history->description)) ?></td> </tr>
                    <?php } ?>
                </table>
            </div>

    </div>
<?php } ?>
