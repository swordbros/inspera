<?php
$item =  \Swordbros\Base\Controllers\Amele::getBookingStatus($record->booking_status);
?>
<?php if($item){ ?>
    <span class="badge" style="background-color: <?=$item['color']?>"><?=$item['title']?></span>
<?php } ?>

