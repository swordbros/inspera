<?php
namespace Swordbros\Base\Controllers;

use Backend\Classes\Controller;
use Backend\Models\User;
use Input;

class BaseController extends Controller
{
    public $requiredCss = [];
    public $requiredJs = [];
    public function beforeDisplay()
    {
        $this->loadAssets();
    }
    private function loadAssets(){
        $this->addJs('/plugins/swordbros/assets/js/swordbros.main.js');

        foreach($this->requiredJs as $requiredJs){
            $this->addJs($requiredJs);
        }
        $this->addCss('/plugins/swordbros/assets/css/swordbros.main.css');
        foreach($this->requiredCss as $requiredCss){
            $this->addCss($requiredCss);
        }
    }
    public function onUserDropDownChange(){
        $result = [];
        $BookingModel = Input::get('BookingModel');
        $user_id = isset($BookingModel['user_id'])?$BookingModel['user_id']:0;
        $row = User::find($user_id);
        $user = [];
        if(empty($row)){
            $row = new User();
            foreach($row->getFillable() as $field){
                $user[$field] = '';
            }
        } else {
            foreach($row->getAttributes() as $field=>$value){
                $user[$field] = $value;
            }
        }
        foreach($user as $key=>$value){
            $result['#Form-field-BookingModel-'.$key] =  $value;
        }
        return ['fields' => $result];
    }

}
