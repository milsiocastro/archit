<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class MultipleSheetsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new FirstSheetExport(),
            new SecondSheetExport(),
            new ThirdSheetExport(),
            new FourthSheetExport(),
        ];
    }
}