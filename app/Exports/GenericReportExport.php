<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GenericReportExport implements FromCollection, WithHeadings
{
    protected $rows;
    protected $headers;

    public function __construct($rows, array $headers)
    {
        $this->rows = $rows;
        $this->headers = $headers;
    }

    public function collection()
    {
        return collect($this->rows);
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
