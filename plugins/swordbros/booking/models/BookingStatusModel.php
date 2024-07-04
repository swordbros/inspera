<?php namespace Swordbros\Booking\Models;

use Backend\Models\User;
use Model;

/**
 * Model
 */
class BookingStatusModel extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_booking_statuses';
    public $translateClass = BookingTranslateModel::class;
    public $rules = [ ];
    public $belongsTo = [];
    protected $casts = [];

}
