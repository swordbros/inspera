<?php

namespace Swordbros\Event\Models;

use ApplicationException;
use Carbon\Carbon;
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
    public $attachOne = [
        'thumb' => 'System\Models\File'
    ];
    public $attachMany = [
        'images' => 'System\Models\File'
    ];
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
    public function zoneIsAvailable()
    {
        if (empty($this->start) || empty($this->end)) {
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

    public function scopePublished($query)
    {
        $query->whereStatus(1);
    }

    public function scopeByMonth($query, string $date)
    {
        if (empty($date)) {
            $year = date('Y');
            $month = date('m');
        } else {
            [$year, $month] = explode('-', $date);
        }
        $firstDayOfMonth = Carbon::create($year, $month, 1);
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();
        $query->whereDate('start', '>=', $firstDayOfMonth->format('Y-m-d'))
            ->whereDate('end', '<=', $lastDayOfMonth->format('Y-m-d'));
    }

    public function scopeFiltered($query, array $params)
    {
        if ($this->isParamSet($params, 'date') && $this->isParamSet($params, 'dateEnd')) {
            $query->whereDate('start', '<=', $params['dateEnd'])
                ->whereDate('end', '>=', $params['date']);
        } elseif ($this->isParamSet($params, 'date')) {
            $query->whereDate('start', '=', $params['date']);
        } elseif ($this->isParamSet($params, 'month')) {
            $query->byMonth($params['month']);
        }

        $query->where(function ($q) use ($params) {
            if (isset($params['types'])) {
                $q->whereIn('event_type_id', $params['types']);
            }
            if (isset($params['venues'])) {
                $q->whereIn('event_zone_id', $params['venues']);
            }
            if (isset($params['categories'])) {
                $q->whereIn('event_category_id', $params['categories']);
            }
            if (isset($params['audiences'])) {
                $q->whereIn('audience', $params['audiences']);
            }
        });
    }

    private function isParamSet(array $params, string $name): bool
    {
        if (isset($params[$name]) && !empty($params[$name])) {
            return true;
        }
        return false;
    }
}
