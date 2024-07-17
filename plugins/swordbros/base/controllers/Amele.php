<?php namespace Swordbros\Base\Controllers;


use Site;
use Backend\Classes\Controller;
use Swordbros\Booking\Models\BookingHistoryModel;
use Swordbros\Booking\Models\BookingRequestHistoryModel;
use Swordbros\Booking\Models\BookingTranslateModel;
use Swordbros\Event\Models\EventModel;
use Swordbros\Event\Models\EventTagModel;
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
    public static function save_localize_row($row){

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
           return $row->translate_value;
       }
       return '';

    }
    public static function eventTypes(){
        $result = [];
        foreach (EventTypeModel::all() as $item) {
            $result[$item->id] = $item;
        }
        return $result;
    }
    public static function eventZones(){
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

    public static function getAudienceOptions(){
        $items = [];
        $rows = self::getAudiences();
        foreach ($rows as $item){
            $items[$item['code']] = trans('event.audience.'.$item['code']);
        }
        return $items;
    }
    public static function getAudiences(){
        $items = [
            'male'=>['code'=>'male', 'color'=>'#86cb43', 'title'=>'Male', 'icon'=>''],
            'female'=>['code'=>'female', 'color'=>'#e91e63', 'title'=>'Female', 'icon'=>''],
            'children'=>['code'=>'children', 'color'=>'#ff9800', 'title'=>'Children', 'icon'=>''],
            'adult'=>['code'=>'adult', 'color'=>'#2196f3', 'title'=>'Adult', 'icon'=>''],
            'family'=>['code'=>'family', 'color'=>'#2196f3', 'title'=>'Family', 'icon'=>''],
        ];
        return $items;

    }
    public static function getAudience($code){
        $items = self::getAudiences();
        if(isset($items[$code])){
            return $items[$code];
        }
        return [];

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

    /*public static function set_swordbros_metas(string $id, string $module, array $values){
        $table = 'swordbros_'.$module.'_metas';
        \Db::table($table);
    }*/
    public static function get_swordbros_meta(string $id, string $key){
        $params = self::getMetaModuelAndTable( $key);
        $site_id = Site::getSiteIdFromContext();

        $DB = \Db::table($params['table']);
        $meta = $DB->where([
            ['site_id','=', $site_id],
            ['owner_id', '=', $id],
            ['module', '=', $params['module']],
            ['meta_key', '=', $params['meta_key']]
        ])->first();
        dd($meta);
    }
    public static function set_swordbros_meta(string $id, string $key, $value){
        if(empty($id) || empty($key)){
            return false;
        }
        $params = self::getMetaModuelAndTable( $key);
        $site_id = Site::getSiteIdFromContext();
        $DB = \Db::table($params['table']);
        $values = is_array($value)?$value:[$key=>$value];
        foreach($values as  $meta_key=> $meta_value){
            $is_json = is_array($meta_value);
            if($is_json){
                $meta_value = json_encode($meta_value, JSON_UNESCAPED_UNICODE);
            }
            $meta = $DB->where([
                ['site_id','=', $site_id],
                ['owner_id', '=', $id],
                ['module', '=', $params['module']],
                ['meta_key', '=', $meta_key]
            ])->first();
            if($meta){
                $DB->where(['id'=>$meta->id])->update(['meta_value'=>$meta_value]);
            } else {
                $DB->insert(['site_id'=>$site_id, 'owner_id'=>$id, 'module'=>$params['module'], 'meta_key'=>$meta_key, 'meta_value'=>$meta_value]);
            }
        }
        return true;
    }

    private static function getMetaModuelAndTable(string $meta_key){
        if(empty($meta_key)){
            return [
                'module'=>null,
                'table'=>null,
                'meta_key'=>null
            ];
        }
        $parts = explode('.', $meta_key);
        if(count($parts)){
            $module = array_shift($parts);
        } else {
            $module = '';
        }
        $key = implode('.', $parts);
        if($module){
            $table = 'swordbros_'.$module.'_metas';
        } else {
            $table = 'swordbros_metas';
        }
        $key = implode('.', $parts);
        return [
            'module'=>$module,
            'table'=>$table,
            'meta_key'=>$key
        ];
    }
    public static function getEnableSites(){

        $sites = [];
        $editSite = Site::getEditSite();
        if (Site::hasMultiSite()) {
            foreach (Site::listEnabled() as $site) {
                $sites[] = [
                    'id' => $site->id,
                    'code' => $site->code,
                    'locale' => $site->hard_locale,
                    'flagIcon' => $site->flag_icon,
                    'active'=>$editSite->id==$site->id
                ];
            }
        }
        return  $sites;
    }
    public static function stars($value){
        $value = intval($value);
        $html = '<div>';
        for ($i=0; $i<$value; $i++){
            $html .= '<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="18px" width="18px" version="1.1" id="Capa_1" viewBox="0 0 47.94 47.94" xml:space="preserve">
<path style="fill:#ED8A19;" d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757  c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042  c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685  c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528  c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956  C22.602,0.567,25.338,0.567,26.285,2.486z"/>
</svg></span> ';
        }
        for ($i=$value; $i<5; $i++){
            $html .= '<span><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="18px" width="18px" version="1.1" id="Capa_1" viewBox="0 0 47.94 47.94" xml:space="preserve">
<path style="fill:#d8d8d8;" d="M26.285,2.486l5.407,10.956c0.376,0.762,1.103,1.29,1.944,1.412l12.091,1.757  c2.118,0.308,2.963,2.91,1.431,4.403l-8.749,8.528c-0.608,0.593-0.886,1.448-0.742,2.285l2.065,12.042  c0.362,2.109-1.852,3.717-3.746,2.722l-10.814-5.685c-0.752-0.395-1.651-0.395-2.403,0l-10.814,5.685  c-1.894,0.996-4.108-0.613-3.746-2.722l2.065-12.042c0.144-0.837-0.134-1.692-0.742-2.285l-8.749-8.528  c-1.532-1.494-0.687-4.096,1.431-4.403l12.091-1.757c0.841-0.122,1.568-0.65,1.944-1.412l5.407-10.956  C22.602,0.567,25.338,0.567,26.285,2.486z"/>
</svg></span> ';
        }
        $html .= '</div>';
        return $html;
    }
    public static function get_tagged_events($tag){
        $result = [];
        $taggedEvents = EventTagModel::where(['tag'=>$tag])->get();
        if(!$taggedEvents->isEmpty()){
            foreach($taggedEvents as $taggedEvent){
                $event = EventModel::find($taggedEvent->event_id);
                if($event){
                    $result[] = $event;
                }
            }
        }
        return $result;
    }
}
