<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Illuminate\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelService
{
    public function exportDownload($data, $filename = null, $extension = 'xlsx')
    {
        if (is_array($data)) {
            return $this->exportDownloadFromArray($data, $filename, $extension);
        } elseif ($data instanceof Collection) {
            return $this->exportDownloadFromCollection($data, $filename, $extension);
        }
        throw new Exception('Input data should be an array or a collection.');
    }

    private function exportDownloadFromArray(array $data, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from array
        $export = new class implements FromArray {
            public $data = [];
            public function array(): array
            {
                return $this->data;
            }
        };
        $export->data = $data;
        return Excel::download($export, $this->getFileName($filename, $extension), ucfirst($extension));
    }

    private function exportDownloadFromCollection(Collection $collection, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from collection
        $export = new class implements FromCollection {
            public $collection;
            public function collection()
            {
                return $this->collection;
            }
        };
        $export->colletion = $collection;
        return Excel::download($export, $this->getFileName($filename, $extension), ucfirst($extension));
    }

    private function exportDownloadFromQuery($query, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from query
        $export = new class implements FromQuery {
            public $query;
            public function query(): array
            {
                return $this->query;
            }
        };
        $export->query = $query;
        return Excel::download($export, $this->getFileName($filename, $extension), ucfirst($extension));
    }

    private function exportDownloadFromView($viewPath, $data = [], $filename = null, $extension = 'xlsx')
    {
        // Create excel file from array
        $export = new class implements FromView {
            public $viewPath;
            public $data = [];
            public function view(): View
            {
                return view($this->viewPath, $this->data);
            }
        };
        $export->viewPath = $viewPath;
        $export->data = $data;
        return Excel::download($export, $this->getFileName($filename, $extension), ucfirst($extension));
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
            } elseif (!$this->checkSupportedExtension($ext)) {
                throw new Exception('Extension is not supported.');
            } else {
                $filename .= '.'.$ext;
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
