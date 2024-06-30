<?php
namespace Swordbros\Base\Controllers;

use Backend\Classes\Controller;

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
}
