<?php
    $site = \Site::getSiteFromId($record->site_id);
    if ($site) {
        echo  '<span class="event-translate nav-icon nav-icon-flag" ><i class="'.$site->flagIcon.'"></i></span>'; // Dizi olarak döner
    }

