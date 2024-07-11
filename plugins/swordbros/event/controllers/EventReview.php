<?php namespace Swordbros\Event\Controllers;

use Backend;
use BackendMenu;
use Backend\Classes\Controller;

class EventReview extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Swordbros.Event', 'main-menu-item', 'side-menu-item7');
    }
    public static function eventReviewTable(){
        $eventReview = new EventReview();
        $eventReview->asExtension('ListController')->index();
        return $eventReview->listRender();
    }
}
