<?php
$item =  \Swordbros\Base\Controllers\Amele::getPaymentStatusOption($record->payment_status);
?>
<?php if($item){ ?>
    <span class="badge" style="background-color: <?=$item['color']?>"><?=$item['title']?></span>
<?php } ?>

