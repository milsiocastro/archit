<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MultipleSheetsExport;

class ExcelExportController extends Controller
{
    public function export()
    {
        return Excel::download(new MultipleSheetsExport, 'informe.xlsx');
    }
}
