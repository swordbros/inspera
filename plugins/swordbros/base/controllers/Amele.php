<?php namespace Swordbros\Base\Controllers;

use App;
use Backend\Classes\Controller;
use Backend\Controllers\Index\DashboardHandler;
use BackendMenu;
use Config;
use Locale;
use October\Rain\Support\Facades\Schema;
use Site;
use Swordbros\Booking\Models\BookingHistoryModel;
use Swordbros\Booking\Models\BookingRequestHistoryModel;
use Swordbros\Booking\models\EventCategoryModel;
use Swordbros\Booking\Models\EventModel;
use Swordbros\Booking\models\EventTypeModel;
use Swordbros\Booking\models\EventZoneModel;
use Swordbros\Booking\Models\Message;
use Swordbros\Event\Models\EventTranslateModel;
use Swordbros\Event\Models\TypeModel;
use Swordbros\Event\Models\ZoneModel;

class Amele extends Controller
{
    public static function test(){
        echo 'Test Language: '.__('Test Language');
    }
    public static function localize_row($row){
        $plugin = $row->table;
        $record_id = $row->id;
        foreach($row->attributes as $translate_key=>$attribute_value){
            $text = "$plugin.$translate_key.$record_id";
            $translated = self::translate($text);
            if($translated){
                $row->$translate_key = $translated;
            }
        }
        return $row;
    }
    public static function save_localize_row($row){
        $plugin = $row->table;
        $record_id = $row->id;
        $site_id = Site::getSiteIdFromContext();
        foreach($row->attributes as $translate_key=>$attribute_value){
            if(self::is_translatable($translate_key)){
                $query = EventTranslateModel::where([['plugin','=', $plugin], ['translate_key','=', $translate_key], ['record_id','=', $record_id], ['site_id','=', $site_id]]);
                $translate = $query->first();
                if(empty($translate)){
                    $translate = new EventTranslateModel();
                    $translate->plugin = $plugin;
                    $translate->translate_key = $translate_key;
                    $translate->record_id = $record_id;
                    $translate->site_id = $site_id;
                }
                $translate->translate_value = $attribute_value;
                $translate->save();
            }
        }
    }
    private static function is_translatable($translate_key){
        /*if(is_object($value)){
            return false;
        }
        if(is_array($value)){
            return false;
        }
        if(is_numeric($value)){
            return false;
        }
        if (strtotime($value)) {
            return false;
        }
        if (\DateTime::createFromFormat('Y-m-d H:i:s', $value) !== false) {
            return false;
        }*/
        if(is_string($translate_key)){
            $translatable = ['name', 'title', 'description', 'short'];
            if(in_array($translate_key, $translatable)){
                return true;
            }
        }
        return false;
    }

    public static function _e($key){
        echo self::translate($key);
    }
    public static function translate($key){
        $parts = explode('.', $key);
        $plugin = isset($parts[0])?$parts[0]:'';
        $translate_key   = isset($parts[1])?$parts[1]:'';
        $record_id  = isset($parts[2])?(int)$parts[2]:0;
        $site_id = Site::getSiteIdFromContext();
        $query = EventTranslateModel::where([['plugin','=', $plugin], ['translate_key','=', $translate_key], ['record_id','=', $record_id], ['site_id','=', $site_id]]);
        $row = $query->first();
        if($row){
            return $row->translate_value;
        }
        return '';

    }
    public static function services(){
        $result = [];
        foreach (TypeModel::all() as $item) {
            $result[$item->id] = $item;
        }
        return $result;
    }
    public static function places(){
        $result = [];
        foreach (ZoneModel::all() as $item) {
            $result[$item->id] = $item;
        }
        return $result;
    }

    public function dummyData(){
       foreach (Amele::eventTypes() as $code=>$name){
            $item = new TypeModel();
            $item->name = $name;
            $item->description = $name.' Description';
            $item->save();
        }
        foreach (Amele::eventZones() as $code=>$name){
            $item = new ZoneModel();
            $item->name = $name;
            $item->description = $name.' Description';
            $item->save();
        }
        foreach (Amele::eventCategories() as $code=>$name){
            $item = new CategoryModel();
            $item->name = $name;
            $item->description = $name.' Description';
            $item->save();
        }
        $item = new EventModel();
        $item->event_zone_id = 1;
        $item->event_category_id = 1;
        $item->event_type_id = 1;
        $item->save();

    }
    public static function eventTypes(){
        return [
            'concert' =>  __('Concerts'),
            'theater' => __('Theater'),
            'cinema' =>  __('Cinema'),
            'workshop' => __('Workshop'),
            'exhibition' => __('Exhibition'),
        ];
    }
    public static function eventZones(){
        return [
            'zone001' =>  __('Küçük Sahne'),
            'zone002' => __('Büyük Sahne'),
            'zone003' =>  __('Atolye'),
        ];
    }
    public static function eventCategories(){
        return [
            'caz' =>  __('Caz'),
            'drama' => __('Drama'),
            'action' =>  __('Aksiyon'),
        ];
    }
    public static function getBookingStatusOptions(){
        $items = [
            'pending'=>'Pending',
            'approved'=>'Approved',
            'denied'=>'Denied',
            'canceled'=>'Canceled',
        ];
        return $items;
    }
    public static function getPaymentMethodOptions(){
        $items = [
            'free'=>'Free',
            'banktransfer'=>'Bank Transfer',
            'creditcard'=>'Credit Card',
            'onzone'=>'Etkinlik Alanında Ödeme',
        ];
        return $items;
    }
    public static function getPaymentMethodOptionName($code){
        $items = self::getPaymentMethodOptions();
        if(isset($items[$code])){
            return $items[$code];
        }
        return $code;

    }
    public static function getPaymentStatusOptions(){
        $items = [
            'pending'=>'Pending',
            'complated'=>'Complated',
            'refund'=>'İade',
        ];
        return $items;
    }
    public static function getPaymentStatusOptionsName($code){
        $items = self::getPaymentStatusOptions();
        if(isset($items[$code])){
            return $items[$code];
        }
        return $code;
    }
    public static function addBookingRequestHistory($booking_request_id, $description){
        $item = new BookingRequestHistoryModel();
        $item->booking_request_id = $booking_request_id;
        $item->description = $description;
        $item->save();
    }
    public static function addBookingHistory($booking_id, $description){
        $item = new BookingHistoryModel();
        $item->booking_id = $booking_id;
        $item->description = $description;
        $item->save();
    }
    public static function event_url($event){
        return self::localize_url('/event',['id'=>$event->id]);
    }
    public static function localize_url($url, $params=[]){
        $ActiveSite = Site::getActiveSite();
        if($ActiveSite){
            $prefix = $ActiveSite->route_prefix;
        } else {
            $prefix = '';
        }
        return url(trim($prefix, '/').'/'.ltrim($url, '/'),  $params);
    }
}
