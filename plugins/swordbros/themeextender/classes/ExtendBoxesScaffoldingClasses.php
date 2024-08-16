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
                if (
                    in_array(
                        $box->partial,
                        ['column'/*, 'card'*/]
                    ) && isset($data['set_column_width'])
                    && $data['set_column_width'] == 1
                ) {
                    $classes[] = (isset($data['column_default'])) ? "col-{$data['column_default']}" : '';
                    $classes[] = isset($data['column_s']) ? "col-sm-{$data['column_s']}" : '';
                    $classes[] = (isset($data['column_m']) && $data['column_m']) ? "col-md-{$data['column_m']}" : '';
                    $classes[] = (isset($data['column_l']) && $data['column_l']) ? "col-lg-{$data['column_l']}" : '';
                    $classes[] = (isset($data['column_xl']) && $data['column_xl']) ? "col-xl-{$data['column_xl']}" : '';

                    if (empty($classes)) {
                        $classes[] = 'col';
                    }

                    if ($classes) {
                        return array_filter($classes);
                    }
                }

                return [];
            }
        );
    }
}
