<?php namespace Swordbros\Setting\Models;


use ApplicationException;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use Exception;
use RainLab\User\Models\User;
use Site;
use Swordbros\Event\Models\EventModel;
use System;
use System\Models\MailTemplate;
use System\Models\SettingModel;

/**
 * Model
 */
class SwordbrosSettingModel extends SettingModel
{
    use \October\Rain\Database\Traits\Multisite;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string settingsCode is a unique code
     */
    public $settingsCode = 'swordbros_settings';

    /**
     * @var mixed settingsFields definitions
     */
    public $settingsFields = 'fields.yaml';

    /**
     * @var array rules for validation
     */
    public $rules = [];

    /**
     * @var array propagatable fields
     */
    protected $propagatable = [];
    public $belongsTo = [
        'booking_email_created' => MailTemplate::class,
        'booking_email_pending' => MailTemplate::class,
        'booking_email_approved' => MailTemplate::class,
        'booking_email_rejected' => MailTemplate::class,
        'booking_email_canceled' => MailTemplate::class
    ];

    /**
     * initSettingsData initializes the seed data for this model. This only executes when the
     * model is first created or reset to default.
     * @return void
     */
    public function initSettingsData()
    {
        $this->is_enabled = true;
    }

    /**
     * isEnabled returns true if maintenance mode should be used
     */
    public static function isEnabled(): bool
    {
        if (!System::hasDatabase()) {
            return false;
        }

        if (BackendAuth::userHasAccess('general.view_offline')) {
            return false;
        }

        return self::get('is_enabled', false);
    }

    /**
     * isEnabledForBackend
     */
    public static function isEnabledForBackend(): bool
    {
        if (!System::hasDatabase()) {
            return false;
        }

        if (BackendAuth::userHasAccess('general.backend.view_offline')) {
            return false;
        }

        return self::get('is_enabled', false);
    }

    public function isMultisiteEnabled()
    {
        return true;
    }
     public static function swordbros_setting($setting_key){
       $value = self::get($setting_key, '');
       if(is_string($value)){
           return html_entity_decode($value);
       }
       return $value;
     }
}
