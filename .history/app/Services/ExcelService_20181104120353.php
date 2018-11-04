<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelService
{
    public function export($data)
    {

    }

    private function exportDownloadFromArray(array $data, $filename = null)
    {
        // Create excel file from array
        $export = new class implements FromArray {
            public function array(): array
            {
                return $data;
            }
        };
        return Excel::download($export, 'test-array.xlsx');
    }

    private function exportDownloadFromCollection(Collection $collection, $filename = null)
    {
        // Create excel file from collection
        $export = new class implements FromCollection {
            public function collection()
            {
                return $collection;
            }
        };
        return Excel::download($export, 'test-collection.xlsx');
    }
}
