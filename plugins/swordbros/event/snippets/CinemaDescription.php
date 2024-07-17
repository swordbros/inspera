<?php
namespace Swordbros\Event\Snippets;
use Cms\Classes\ComponentBase;
use Str;

class CinemaDescription extends ComponentBase{
    public function componentDetails(){
        return [
            'name' => Str::title(str_replace('_', ' ', Str::snake(class_basename(static::class)))),
            'description' => __("No description provided.")
        ];
    }
}
