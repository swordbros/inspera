<style>
    .event-translate.active {
        opacity: 1;
    }
    .event-translate {
        opacity: 0.1;
    }
    .event-translate:hover {
        opacity: 1;
    }
</style>
<nav class="control-breadcrumb">
<ul class="list-unstyled float-end">
    <?php if($sites = \Swordbros\Base\Controllers\Amele::getEnableSites()){
        foreach($sites as $site){?>
            <li>
                <a class="event-translate nav-icon nav-icon-flag <?php if($site['active']) echo 'active';?>"
                   href="?<?= http_build_query(array_merge(get(), ['_site_id' => e($site['id'])])) ?>"><i class="<?= e($site['flagIcon']) ?>"></i></a>
                </span>
            </li>
        <?php }
    } ?>
</ul>
</nav>
