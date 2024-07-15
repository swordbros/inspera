<?php

namespace OFFLINE\Boxes\Classes\Traits;

use Backend\Widgets\Form;
use Closure;
use October\Rain\Database\Scopes\MultisiteScope;
use October\Rain\Element\ElementHolder;
use October\Rain\Support\Facades\Site;
use OFFLINE\Boxes\Classes\PatchedTreeCollection;
use OFFLINE\Boxes\Models\Page;
use System\Models\SiteDefinition;

/**
 * Implements October's native multisite support.
 */
trait HasMultisiteSupport
{
    use \October\Rain\Database\Traits\Multisite;

    /**
     * @see \October\Rain\Database\Traits\Multisite
     */
    public $propagatable = [];

    /**
     * Initialize the trait.
     */
    public function initializeHasMultisiteSupport()
    {
        $this->belongsTo['root_page'] = [
            Page::class,
            'key' => 'site_root_id',
        ];

        $this->hasMany['multisite_pages'] = [
            Page::class,
            'key' => 'site_root_id',
            'otherKey' => 'site_root_id',
            'scope' => 'relatedPagesAllSites',
            'replicate' => false,
        ];

        $this->hasOne['current_site_page'] = [
            Page::class,
            'key' => 'site_root_id',
            'otherKey' => 'site_root_id',
            'scope' => 'currentSitePage',
            'replicate' => false,
        ];

        $this->belongsTo['site'] = [
            SiteDefinition::class,
        ];

        $this->bindEvent('model.form.filterFields', function (Form $widget, ElementHolder $holder) {
            if (!$widget->model instanceof Page || $holder->get('site_root_id') === null) {
                return;
            }

            // Hide the root page field if no multisite is configured or the page is its own root id.
            if (!Site::hasMultiSite()) {
                $holder->get('site_root_id')->config['hidden'] = true;
            }
        });
    }

    /**
     * Add the required query parameters to the multisite_pages relation.
     * @param mixed $query
     */
    public function scopeRelatedPagesAllSites($query)
    {
        return $query
            ->withoutGlobalScope(MultisiteScope::class)
            ->current();
    }

    /**
     * Returns the current site's page.
     * @param mixed $query
     */
    public function scopeCurrentSitePage($query)
    {
        return $query
            ->where('site_id', Site::getSiteIdFromContext())
            ->current();
    }

    /**
     * Return all possible root pages for a site. If a page id is passed
     * the site from that page will be used.
     * @param null|mixed $_
     * @param null|mixed $model
     */
    public function getSiteRootIdOptions($_ = null, $model = null)
    {
        $options = [null => '-- ' . trans('offline.boxes::lang.no_root_page')];

        $useSite = Site::getPrimarySite()->id;

        if ($model instanceof Page) {
            $options[$model->id] = '-- ' . trans('offline.boxes::lang.this_page_is_root');
        }

        return $options
            + Page::withoutGlobalScope(MultisiteScope::class)
                ->with('site')
                ->currentDrafts($useSite)
                ->get()
                ->pipe(fn ($pages) => new PatchedTreeCollection($pages))
                ->listsNested('name', 'id');
    }

    /**
     * Run a function without the multisite scope. This is required
     * so that the root page can be queried for new pages by the
     * NestedTree trait.
     */
    public function withoutMultisiteScope(Closure $fn)
    {
        $this->multisiteScopeEnabled = false;

        $result = $fn();

        $this->multisiteScopeEnabled = true;

        return $result;
    }

    public function isMultisiteEnabled()
    {
        return $this->multisiteScopeEnabled && !app()->runningInConsole();
    }
}
