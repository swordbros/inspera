<?php namespace Swordbros\Booking\Models;

use Backend\Models\User;
use Model;

/**
 * Model
 */
class BookingPaymentStatusModel extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table in the database used by the model.
     */
    public $table = 'swordbros_booking_payment_statuses';
    public $translateClass = BookingTranslateModel::class;
    public $rules = [ ];
    public $belongsTo = [];
    protected $casts = [];

}
