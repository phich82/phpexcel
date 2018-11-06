<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ExcelService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelController extends Controller
{
    public function downloadExcel()
    {
        return view('excel');
    }

    public function excel(ExcelService $excelService)
    {
        $data = [
            ['name' => 'Jhp Phich1', 'age' => 30, 'sex' => 1],
            ['name' => 'Jhp Phich2', 'age' => 31, 'sex' => 0],
            ['name' => 'Jhp Phich3', 'age' => 32, 'sex' => 1],
        ];
        $excelService->headings = ['Name', 'Age', 'Sex'];
        return $excelService->exportDownload($data, 'testing', 'xlsx');
    }

    public function excelView(ExcelService $excelService)
    {
        $data = [
            ['name' => 'Jhp Phich1', 'age' => 30],
            ['name' => 'Jhp Phich2', 'age' => 31],
            ['name' => 'Jhp Phich3', 'age' => 32],
        ];
        return $excelService->exportDownload('exports.list', compact('data'), 'testing', 'xlsx');
    }

    public function exportExcelFromArray()
    {
        // Create excel file from collection
        $export = new class implements FromArray {
            public function array(): array
            {
                return [
                    ['name' => 'Jhp Phich1', 'age' => 30],
                    ['name' => 'Jhp Phich2', 'age' => 31],
                    ['name' => 'Jhp Phich3', 'age' => 32],
                ];
            }
        };
        return Excel::download($export, 'test-array.xlsx');
    }

    public function exportExcelFromCollection()
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
        return Excel::download($export, 'test-collection.xlsx');
    }

    public function storeExcelFromCollection()
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
        return Excel::store($export, 'excel/test-store.xlsx') ? 'Excel file saved successfully!' : 'Could not create excel file.';
    }

    public function downloadExcelCollection()
    {
        // download collection as excel
        return (new Collection([[1, 2, 3], [4, 5, 6]]))->downloadExcel('dl-test-collection.xlsx', null, false);
    }
}
