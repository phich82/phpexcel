<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelController extends Controller
{
    public function excel()
    {
        // Create excel file from collection
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
        // store the excel file from collection
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
        return Excel::store($export, 'excel/test.xlsx') ? 'Excel file saved successfully!' : 'Could not create excel file.';
    }

    public function downloadExcel()
    {
        // download collection as excel
        return (new Collection([[1, 2, 3], [4, 5, 6]]))->downloadExcel('test.xlsx', null, false);
    }
}
