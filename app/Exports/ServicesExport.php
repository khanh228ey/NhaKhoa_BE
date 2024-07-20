<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ServicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'Mã dịch vụ',
            'Tên dịch vụ',
            'Số lượng bán ra',
            'Tổng thu'
        ];
    }
}
