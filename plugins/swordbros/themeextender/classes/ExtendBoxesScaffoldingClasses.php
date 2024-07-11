<?php

namespace Swordbros\ThemeExtender\Classes;

use Event;
use OFFLINE\Boxes\Classes\Events;
use OFFLINE\Boxes\Models\Box;
use OFFLINE\Boxes\Classes\Partial\RenderContext;

class ExtendBoxesScaffoldingClasses
{
    public function subscribe(): void
    {
        /**
         * Adds column classes to the box wrapper of 'column' && 'card' types
         * as they need to be on the direct children of uk-grid element
         */
        Event::listen(
            Events::EXTEND_BOX_SCAFFOLDING_CLASSES,
            function (Box $box, RenderContext $context) {

                $data = $box->data;
                if (in_array(
                    $box->partial,
                    ['column', 'card']
                ) && $data['set_column_width'] == 1) {
                    $classes = [];
                    $classes[] = (isset($data['column_default']) && $data['column_default'] != '1-1') ? "uk-width-{$data['column_default']}" : '';
                    $classes[] = isset($data['column_s']) ? "uk-width-{$data['column_s']}@s" : '';
                    $classes[] = (isset($data['column_m']) && $data['column_m']) ? "uk-width-{$data['column_m']}@m" : '';
                    $classes[] = (isset($data['column_l']) && $data['column_l']) ? "uk-width-{$data['column_l']}@l" : '';
                    $classes[] = (isset($data['column_xl']) && $data['column_xl']) ? "uk-width-{$data['column_xl']}@xl" : '';

                    if ($classes) {
                        return array_filter($classes);
                    }
                }

                return [];
            }
        );
    }
}
