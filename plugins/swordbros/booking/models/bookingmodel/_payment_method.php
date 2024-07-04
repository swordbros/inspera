<?php
$item =  \Swordbros\Base\Controllers\Amele::getPaymentMethodOption($record->payment_method);

?>
<?php if($item){ ?>
    <span class="badge" style="background-color: <?=$item['color']?>"><?=$item['title']?></span>
<?php } ?>
