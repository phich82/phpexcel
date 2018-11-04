<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelService
{
    public function exportDownload($data)
    {

    }

    private function exportDownloadFromArray(array $data, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from array
        $export = new class implements FromArray {
            public function array(): array
            {
                return $data;
            }
        };
        return Excel::download($export, 'data.xlsx');
    }

    private function exportDownloadFromCollection(Collection $collection, $filename = null, $extension = 'xlsx')
    {
        $filename = !empty($filename) ? $filename : 'data';

        // Create excel file from collection
        $export = new class implements FromCollection {
            public function collection()
            {
                return $collection;
            }
        };
        return Excel::download($export, 'data.xlsx');
    }

    private function getExtension($filename)
    {
        $parts = explode('.', $filename);
        if (count($parts) < 2) {
            return false;
        }
        return array_pop($parts);
    }

    private function checkSupportedExtension($extension)
    {
        $extensionsAllowed = ['xlsx', 'xls', 'csv'];
        return in_array($extension, $extensionsAllowed);
    }
}
