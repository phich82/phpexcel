<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelService
{
    public function exportDownload($data, $filename = null, $extension = 'xlsx')
    {
        if (is_array($data)) {
            return $this->exportDownloadFromArray($data, $filename, $extension);
        } elseif ($data instanceof Collection) {
            return $this->exportDownloadFromCollection($data, $filename, $extension);
        }
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
        return Excel::download($export, $this->getFileName($filename, $extension));
    }

    private function exportDownloadFromCollection(Collection $collection, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from collection
        $export = new class implements FromCollection {
            public function collection()
            {
                return $collection;
            }
        };
        return Excel::download($export, $this->getFileName($filename, $extension));
    }

    private function getFileName($filename, $extension)
    {
        if (!$this->checkSupportedExtension($extension)) {
            throw new Exception('Extension is not supported.');
        }
        if (!empty($filename)) {
            $ext = $this->getExtension($filename);
            if ($ext === false) {
                $filename .= '.'.$extension;
            } elseif ($this->checkSupportedExtension($ext)) {
                $filename .= '.'.$ext;
            } else {
                throw new Exception('Extension is not supported.');
            }
        } else {
            $filename = 'data.'.$extension;
        }
        return $filename;
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
