<?php
    $site = \Site::getSiteFromId($record->site_id);
    if ($site) {
        $indicator = '';
        if($record->is_new){
            $indicator = '<span class="status-indicator" style="background:#ff5e00"></span>'; // Dizi olarak döner
        }
        echo  '<span class="event-translate nav-icon nav-icon-flag" ><i class="'.$site->flagIcon.'"></i> '.$indicator.'</span>'; // Dizi olarak döner
    }

