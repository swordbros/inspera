<?php

namespace Swordbros\Event\Models;

use ApplicationException;
use Db;
use Model;
use Swordbros\Base\models\BaseModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\Models\BookingModel;

/**
 * Model
 */
class EventModel extends BaseModel
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_event_events';

    public $translateClass = EventTranslateModel::class;

    /**
     * @var array rules for validation.
     */
    public $rules = [
        'title' => ['required'],
        'event_zone_id' => ['required'],
        'event_category_id' => ['required'],
        'event_type_id' => ['required'],
        'start' => ['required'],
        'end' => ['required'],
    ];

    public $belongsTo = [
        'event_zone' => EventZoneModel::class,
        'event_category' => EventCategoryModel::class,
        'event_type' => EventTypeModel::class,
    ];
    protected static function boot()
    {
        parent::boot();
        static::fetched(function ($row) {
            $row->images = json_decode($row->images);
        });
    }
    public function getAudienceOptions()
    {
        $result = [];
        foreach (Amele::getAudiences() as $item) {
            $result[$item['code']] = [trans('event.audience.' . $item['code'])];
        }
        return $result;
    }
    public function getEventCategoryIdOptions($value, $formData)
    {
        $result = [];
        $event_type_id = isset($formData['event_type_id']) ? $formData['event_type_id'] : false;
        if ($event_type_id) {
            $eventCategories = EventCategoryModel::where(['event_type_id' => $event_type_id])->get();
        } else {
            $eventCategories = [];
        }
        foreach ($eventCategories as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function getEventTypeIdOptions()
    {
        $result = [];
        foreach (EventTypeModel::all() as $item) {
            Amele::localize_row($item);
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function getEventZoneIdOptions()
    {
        $result = [];
        foreach (EventZoneModel::all() as $item) {
            $result[$item->id] = [$item->name, $item->description];
        }
        return $result;
    }
    public function isValid()
    {
        $errors = [];

        /*if(!$this->zoneIsAvailable()){
            $errors[] = 'Seçilen tarihlerde seçtiğiniz alan kullanılamaz';
        }*/
        return  $errors;
    }
    public $customMessages = [];
    public function beforeValidate()
    {
        $this->rules['end'] = ['required', 'date', 'after_or_equal:start'];
        $this->customMessages['end.after_or_equal'] = e(trans('swordbros.event::validate.after_or_equal:start')); //'Bitiş tarihi başlangıç tarihinden sonra veya eşit olmalıdır.';
        if (!$this->zoneIsAvailable()) {
            $this->rules['event_zone_id'] = ['numeric', 'not_in:' . $this->event_zone_id];
            $this->customMessages['event_zone_id.not_in'] = 'Lütfen geçerli bir etkinlik alanı seçiniz.';
        }
        if (!$this->hasCapasity()) {
            $eventBookingCount = $this->getEventBookingCount();
            $this->rules['capacity'] = ['numeric', 'min:' . $eventBookingCount];
            $this->customMessages['capacity.min'] = 'Geçerli rezervasyon sayısından daha az kapasite tanımladınız. Kapasite Minimum ' . $eventBookingCount . ' olmalıdır';
        }
    }
    public function zoneIsAvailable(){
        if(empty($this->start) || empty($this->end)){
            return true;
        }
        $eventModel = Db::table('swordbros_event_events');
        $eventModel->where([
            ['id', '!=', $this->id],
            ['event_zone_id', '=', $this->event_zone_id],
            ['start', '<', $this->end],
            ['end', '>', $this->start]
        ]);
        return empty($eventModel->count());
    }
    public function hasCapasity()
    {
        if (empty($this->capacity)) {
            return true;
        }
        if ($this->capacity > $this->getEventBookingCount()) {
            return true;
        }
        return false;
    }
    public function getEventBookingCount()
    {
        return intval(BookingModel::where(['event_id' => $this->id])->count());
    }

    public function getUrlAttribute(): string
    {
        return Amele::event_url($this);
    }
}
