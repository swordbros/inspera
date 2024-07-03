<?php namespace Swordbros\Base\Controllers;


use Site;
use Backend\Classes\Controller;
use Swordbros\Booking\Models\BookingHistoryModel;
use Swordbros\Booking\Models\BookingRequestHistoryModel;
use Swordbros\Booking\Models\BookingTranslateModel;
use Swordbros\Event\Models\EventTranslateModel;
use Swordbros\Event\Models\EventTypeModel;
use Swordbros\Event\Models\EventZoneModel;

class Amele extends Controller
{
    public static function test(){
        echo 'Test Language: '.__('Test Language');
    }

    public static function localize_row($row){
        if(!property_exists($row, 'translateClass')){
            return;
        }
        if($row->isTranslated()){
            return ;
        }

        $plugin = $row->table;
        $record_id = $row->id;
        foreach($row->attributes as $translate_key=>$attribute_value){
            $text = "$plugin.$translate_key.$record_id";
            $translated = self::translate($text, $row->translateClass);
            if($translated!==null){
                $row->$translate_key = $translated;
            }
        }
        $row->setTranslated();
        return $row;
    }
    public static function create_localize_row($row){
        self::save_localize_row($row);
    }
    public static function update_localize_row($row){
        self::save_localize_row($row);
    }
    private static function save_localize_row($row){

        if(!property_exists($row, 'translateClass')){
            return;
        }
        $translateClass =  $row->translateClass;
        $translateModel = new $translateClass();
        $plugin = $row->table;
        $record_id = $row->id;
        $site_id = Site::getSiteIdFromContext();

        foreach($row->attributes as $translate_key=>$attribute_value){
            if(self::is_translatable($translate_key)){
                $query = $translateModel::where([['plugin','=', $plugin], ['translate_key','=', $translate_key], ['record_id','=', $record_id], ['site_id','=', $site_id]]);
                $translate = $query->first();
                if(empty($translate)){
                    $translate = new $translateClass();
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
    private static function restoreDefaultSiteFields($row){
        echo  "Restored";
        return;
        $translateClass =  $row->translateClass;
        $translateModel = new $translateClass();
        dd($translateModel);
        $plugin = $row->table;
        $site_id = self::getDefaultSiteId();
        foreach($row->attributes as $translate_key=>$attribute_value){
            $query = $translateModel::where([['plugin', '=', $plugin], ['translate_key', '=', $translate_key], ['record_id', '=', $row->id], ['site_id','=', $site_id]]);
            $translate = $query->first();
            if($translate){
                $row->$translate_key = $translate->translate_value;
            }
        }
        $row->save();
        dd("restored");
    }

    private static function is_translatable($translate_key){
        if(is_string($translate_key)){
            $translatable = ['name', 'title', 'description', 'short'];
            if(in_array($translate_key, $translatable)){
                return true;
            }
        }
        return false;
    }

   private static function translate($key, $translateClass){
       $site_id = Site::getSiteIdFromContext();
       $translateModel = new $translateClass();
       $parts = explode('.', $key);
       $plugin = isset($parts[0])?$parts[0]:'';
       $translate_key   = isset($parts[1])?$parts[1]:'';
       $record_id  = isset($parts[2])?(int)$parts[2]:0;
       if(!self::is_translatable($translate_key)){
           return null;
       }
       $query = $translateModel::where([['plugin','=', $plugin], ['translate_key','=', $translate_key], ['record_id','=', $record_id], ['site_id','=', $site_id]]);
       $row = $query->first();
       if($row){
           return $row->translate_value.$site_id;
       }
       return '';

    }
    public static function services(){
        $result = [];
        foreach (EventTypeModel::all() as $item) {
            $result[$item->id] = $item;
        }
        return $result;
    }
    public static function places(){
        $result = [];
        foreach (EventZoneModel::all() as $item) {
            $result[$item->id] = $item;
        }
        return $result;
    }

    public function dummyData(){
       foreach (Amele::demoEventTypes() as $code=>$name){
            $item = new EventTypeModel();
            $item->name = $name;
            $item->description = $name.' Description';
            $item->save();
        }
        foreach (Amele::demoEventZones() as $code=>$name){
            $item = new EventZoneModel();
            $item->name = $name;
            $item->description = $name.' Description';
            $item->save();
        }
        foreach (Amele::demoEventCategories() as $code=>$name){
            $item = new EventCategoryModel();
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
    public static function demoEventTypes(){
        return [
            'concert' =>  __('Concerts'),
            'theater' => __('Theater'),
            'cinema' =>  __('Cinema'),
            'workshop' => __('Workshop'),
            'exhibition' => __('Exhibition'),
        ];
    }
    public static function demoEventZones(){
        return [
            'zone001' =>  __('Küçük Sahne'),
            'zone002' => __('Büyük Sahne'),
            'zone003' =>  __('Atolye'),
        ];
    }
    public static function demoEventCategories(){
        return [
            'caz' =>  __('Caz'),
            'drama' => __('Drama'),
            'action' =>  __('Aksiyon'),
        ];
    }
    public static function getBookingStatusOptions(){
        $items = [];
        $rows = self::getBookingStatuses();
        foreach ($rows as $item){
            $items[$item['code']] = $item['title'];
        }
        return $items;
    }
    public static function getBookingStatuses(){
        $items = [
            'pending'=>['code'=>'pending', 'color'=>'#86cb43', 'title'=>'Pending', 'icon'=>''],
            'approved'=>['code'=>'approved', 'color'=>'#e91e63', 'title'=>'Approved', 'icon'=>''],
            'denied'=>['code'=>'denied', 'color'=>'#ff9800', 'title'=>'Denied', 'icon'=>''],
            'canceled'=>['code'=>'canceled', 'color'=>'#2196f3', 'title'=>'Canceled', 'icon'=>''],
        ];

        return $items;
    }
    public static function getBookingStatus($code){
        $items = self::getBookingStatuses();
        if(isset($items[$code])){
            return $items[$code];
        }
        return [];

    }

    public static function getPaymentMethodOptions(){
        $items = [];
        $rows = self::getPaymentMethods();
        foreach ($rows as $item){
            $items[$item['code']] = $item['title'];
        }
        return $items;
    }

    public static function getPaymentMethods(){

        $items = [
            'free'=>['code'=>'free', 'color'=>'#86cb43', 'title'=>'Free', 'icon'=>''],
            'banktransfer'=>['code'=>'banktransfer', 'color'=>'#e91e63', 'title'=>'Bank Transfer', 'icon'=>''],
            'creditcard'=>['code'=>'creditcard', 'color'=>'#ff9800', 'title'=>'Credit Card', 'icon'=>''],
            'onzone'=>['code'=>'onzone', 'color'=>'#2196f3', 'title'=>'Etkinlik Alanında Ödeme', 'icon'=>''],
        ];

        return $items;
    }
    public static function getPaymentMethodOption($code){
        $items = self::getPaymentMethods();
        if(isset($items[$code])){
            return $items[$code];
        }
        return [];

    }
    public static function getPaymentStatusOptions(){
        $items = [];
        $rows = self::getPaymentStatuses();
        foreach ($rows as $item){
            $items[$item['code']] = $item['title'];
        }
        return $items;
    }

    public static function getPaymentStatuses(){
        $items = [
            'pending'=>['code'=>'pending', 'color'=>'#2196f3', 'title'=>'Pending', 'icon'=>''],
            'completed'=>['code'=>'pending', 'color'=>'#e91e63', 'title'=>'Completed', 'icon'=>''],
            'refund'=>['code'=>'refund', 'color'=>'#86cb43', 'title'=>'İade', 'icon'=>''],
        ];
        return $items;
    }
    public static function getPaymentStatusOption($code){
        $items = self::getPaymentStatuses();
        if(isset($items[$code])){
            return $items[$code];
        }
        return [];
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

    public static function getDefaultSiteId(){
        if(defined('DEFAULT_SITE_ID')){
            return DEFAULT_SITE_ID;
        }
        $defaultSiteId = 0;
        $defaultSite = Site::where('is_default', true)->first();
        if ($defaultSite) {
            $defaultSiteId = $defaultSite->id;
        }
        define('DEFAULT_SITE_ID', $defaultSiteId);
    }
}
