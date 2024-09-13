<?php
namespace Swordbros\Base\Controllers;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class Export implements FromCollection, WithHeadings {
    protected $dataList;
    public function __construct($dataList, $cols=[], $translate_suffix='')
    {
        if($cols){
            $rows = new Collection();
            foreach ($dataList->items() as $col) {
                foreach($col->attributes as $key => $value){
                    if(!in_array($key, $cols)){
                        unset($col->attributes[$key]);
                    }
                }
                $col->setAppends([]);
                unset($col->attachOne);
                $rows->push($col);
            }
        } else {
            $rows = $dataList;
        }

        $this->dataList = $rows;
        $headings = [];
        if(!$rows->isEmpty()){
            foreach($rows as $row){
                $headings = array_keys($row->toArray());
                foreach ($headings as $key => $heading) {
                    if($translate_suffix){
                        $headings[$key] = trans($translate_suffix.$heading);
                    }
                }
                break;
            }
        }
        $this->headings = $headings;
    }
    public function collection()
    {
        return $this->dataList;
    }
    public function headings(): array
    {
        return $this->headings;
    }
}

