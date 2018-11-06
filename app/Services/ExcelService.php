<?php

namespace App\Services;

use Maatwebsite\Excel\Sheet;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class ExcelService
{
    public $headings;

    public function exportDownload() //$data, $params, $filename = null, $extension = 'xlsx'
    {
        $args = func_get_args();
        $data = $args[0];
        if (is_array($data)) {
            return $this->exportDownloadFromArray($data, $args[1], $args[2]);
        } elseif ($data instanceof Collection) {
            return $this->exportDownloadFromCollection($data, $args[1], $args[2]);
        } elseif (is_string($data)) {
            return $this->exportDownloadFromView($data, $args[1], $args[2], $args[3]);
        }
        throw new Exception('Input data should be an array or a collection.');
    }

    private function exportDownloadFromArray(array $data, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from array
        $export = new class implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {
            public $data = [];
            public $headings = [];
            public function array(): array
            {
                return $this->data;
            }
            public function headings(): array
            {
                return $this->headings;
            }
            public function registerEvents(): array
            {
                Sheet::macro('styleCells', function (Sheet $sheet, string $cellRange, array $style) {
                    $sheet->getDelegate()->getStyle($cellRange)->applyFromArray($style);
                });

                return [
                    AfterSheet::class => function (AfterSheet $event) {
                        $cellRange = 'A1:C1'; // All headers
                        $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
                        $event->sheet->styleCells(
                            'A2:C2',
                            [
                                'borders' => [
                                    'outline' => [
                                        'borderStyle' => Border::BORDER_THICK,
                                        'color' => ['argb' => 'FFFF0000'],
                                    ],
                                ]
                            ]
                        );
                        $event->sheet->getStyle('A3:C3')->getAlignment()->setWrapText(true);
                        $event->sheet->setCellValue('D2', '=IF(C2=1, B2+1, B2-1)');

                        $validation = $event->sheet->getCell('B5')->setValue(30)->getDataValidation();
                        $validation->setType(DataValidation::TYPE_LIST);
                        $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                        $validation->setAllowBlank(false);
                        $validation->setShowInputMessage(true);
                        $validation->setShowErrorMessage(true);
                        $validation->setShowDropDown(true);
                        $validation->setErrorTitle('Input error');
                        $validation->setError('Value is not in list.');
                        $validation->setPromptTitle('Pick from list');
                        $validation->setPrompt('Please pick a value from the drop-down list.');
                        //$validation->setFormula1('"Item A,Item B,Item C"');
                        $validation->setFormula1('$B$2:$B$4');

                        $event->sheet->getCell('B6')->setValue(31)->setDataValidation(clone $validation);

                        $styleArray = [
                            'font' => [
                                'bold' => true,
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                            ],
                            'borders' => [
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                            ],
                            'fill' => [
                                'fillType' => Fill::FILL_GRADIENT_LINEAR,
                                'rotation' => 90,
                                'startColor' => [
                                    'argb' => 'FFA0A0A0',
                                ],
                                'endColor' => [
                                    'argb' => 'FFFFFFFF',
                                ],
                            ],
                        ];

                        $conditional1 = new Conditional();
                        $conditional1->setConditionType(Conditional::CONDITION_EXPRESSION);
                        $conditional1->addCondition('=$B5=30');
                        $conditional1->getStyle()->applyFromArray($styleArray);

                        // $conditional2 = new Conditional();
                        // $conditional2->setConditionType(Conditional::CONDITION_CELLIS);
                        // $conditional2->setOperatorType(Conditional::OPERATOR_GREATERTHANOREQUAL);
                        // $conditional2->addCondition('0');
                        // $conditional2->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_GREEN);
                        // $conditional2->getStyle()->getFont()->setBold(true);

                        $conditionalStyles = $event->sheet->getStyle('B5')->getConditionalStyles();
                        $conditionalStyles[] = $conditional1;
                        //$conditionalStyles[] = $conditional2;

                        $event->sheet->getStyle('B5')->setConditionalStyles($conditionalStyles);
                    },
                ];
            }
        };
        $export->data = $data;
        $export->headings = $this->headings;
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
        // Create excel file from view
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

    private function exportDownloadFromQueue($query, $filename = null, $extension = 'xlsx')
    {
        // Create excel file from query
        $export = new class implements FromQuery {
            use Exportable;
            public $query;
            public function query(): array
            {
                return $this->query;
            }
        };
        $export->query = $query;
        return $export->queue($this->getFileName($filename, $extension));
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
