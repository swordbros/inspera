<?php namespace Swordbros\Booking\Controllers;

use BackendMenu;
use Illuminate\Validation\Rules\In;
use Response;
use Swordbros\Base\Controllers\BaseController;
use Swordbros\Booking\models\BookingModel;
use Swordbros\Base\Controllers\Amele;
use Swordbros\Booking\models\BookingRequestModel;
use Swordbros\Event\Controllers\Event;
use Swordbros\Event\Models\EventModel;


class Api extends BaseController
{
    public static function requestApprove(){
        $otp = \Input::get('otp');
        $message = '';
        if($otp){
            $BookingRequestModel = BookingRequestModel::where(['otp'=>$otp])->first();
            if($BookingRequestModel){
                $result = $BookingRequestModel->approve(null, null, null, null);
                if($result){
                    Amele::addBookingRequestHistory($BookingRequestModel->id, 'Rezervasyon isteği email linki ile onaylandı');
                }
                $message = "İstek onaylandı";
            } else {
                $message = "İstek bulunamadı";
            }
        } else{
            $message = "OTP alınamadı";
        }
        return $message;
    }
    public static function requestDecline(){
        $otp = \Input::get('otp');
        if($otp){
            $item = BookingRequestModel::where(['otp'=>$otp])->first();
            if($item){
                $item->status = 0;
                $item->save();
                Amele::addBookingRequestHistory($item->id, 'Rezervasyon isteği email linki ile reddedildi');
                $message = "İstek reddedildi. #".$item->id;
            } else {
                $message = "İstek bulunamadı";
            }
        } else{
            $message = "OTP alınamadı";
        }
        return $message;
    }
}
