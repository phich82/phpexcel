<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function excel()
    {
        Excel::create('test', function ($excel) {
            $excel->setTitle('Test');
            $excel->setCreator('Jhp Phich')->setCompany('SonHaCo');
            $excel->setDescription('Export Excel Test');

            $data = [
                ['name' => 'Jhp Phich1', 'age' => 30],
                ['name' => 'Jhp Phich2', 'age' => 31],
                ['name' => 'Jhp Phich3', 'age' => 32],
            ];
            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1', false, false);
            });
        })->download('xslx');
    }
}
