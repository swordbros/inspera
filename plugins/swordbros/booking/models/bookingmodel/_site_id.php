<?php
if($record->is_new){
    echo  '<span class="status-indicator" style="background:#ff5e00"></span>';
} else{
    echo '<span class="status-indicator" style="background:#ffffff"></span>';
}
$site = \Site::getSiteFromId($record->site_id);
if ($site) {
    echo  '<span class="event-translate nav-icon nav-icon-flag" ><i class="'.$site->flagIcon.'"></i></span>';
