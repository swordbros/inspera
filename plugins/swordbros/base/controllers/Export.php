<?php
namespace Swordbros\Base\Controllers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class Export implements FromCollection, WithHeadings {
    protected $dataList;
    public function __construct($dataList)
    {
        $this->dataList = $dataList;
        $headings = [];
        if(!$dataList->isEmpty()){
            foreach($dataList as $row){
                $headings = array_keys($row->toArray());
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

