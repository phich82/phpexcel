<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelController extends Controller
{
    public function excel()
    {
        $export = new class implements FromCollection {
            public function collection()
            {
                return collect([
                    ['name' => 'Jhp Phich1', 'age' => 30],
                    ['name' => 'Jhp Phich2', 'age' => 31],
                    ['name' => 'Jhp Phich3', 'age' => 32],
                ]);
            }
        };
        return Excel::download($export, 'test.xlsx');
    }

    public function storeExcel()
    {
        //return storage_path('excel');
        $export = new class implements FromCollection {
            public function collection()
            {
                return collect([
                    ['name' => 'Jhp Phich1', 'age' => 30],
                    ['name' => 'Jhp Phich2', 'age' => 31],
                    ['name' => 'Jhp Phich3', 'age' => 32],
                ]);
            }
        };
        return Excel::store($export, storage_path('excel'));
    }
}
