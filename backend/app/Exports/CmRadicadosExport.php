<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CmRadicadosExport implements FromView, WithEvents, WithCharts
{

    use Exportable;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('maatwebsitExcel.reporte_glosas', ['cuentas' => $this->data]);
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $spreadsheet = $event->sheet->getDelegate();
                $this->firstSheet($spreadsheet);
            }
        ];
        // TODO: Implement registerEvents() method.
    }

    public function firstSheet (Worksheet $spreadsheet)
    {
        try {
            $spreadsheet->getDefaultRowDimension()->setRowHeight(20);
            $spreadsheet->getStyle('A1:N2')->getAlignment()
                ->setVertical('center')->setHorizontal('center')->setWrapText(true);

            $styleThinBlackBorderOutline = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    ''
                ],
                'alignment' => [
                    'vertical' => 'center',
                    'horizontal' => 'center',
                    'wrapText' => true
                ],
                'font' => [
                    'size' => 8,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ];
            $styleThinBlackBorderOutlineTwo = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    ''
                ],
                'alignment' => [
                    'vertical' => 'center',
                    'horizontal' => 'right',
                    'wrapText' => true
                ],
                'font' => [
                    'size' => 8,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ];
            $styleThinBlackBorderOutlineThree = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    ''
                ],
                'alignment' => [
                    'vertical' => 'center',
                    'horizontal' => 'left',
                    'wrapText' => true
                ],
                'font' => [
                    'size' => 8,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ];
            foreach ($this->data as $key => $tutela){
                $spreadsheet->getStyle('A'. ($key + 2) .':N'.($key+2))
                    ->applyFromArray($styleThinBlackBorderOutline);
                $spreadsheet->getStyle('E'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineThree);
                $spreadsheet->getStyle('F'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineThree);
                $spreadsheet->getStyle('G'. ($key + 2))->applyFromArray($styleThinBlackBorderOutline);
                $spreadsheet->getStyle('H'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineThree);
                $spreadsheet->getStyle('J'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('K'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('L'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineThree);
                $spreadsheet->getStyle('M'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('N'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineThree);
            }

            $styleNoneBorderOutline = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => 'none' ,
                    ],
                ],
            ];

            $spreadsheet->getStyle('A1')->applyFromArray($styleNoneBorderOutline);

            $spreadsheet->getColumnDimension('A')->setWidth(10);
            $spreadsheet->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getColumnDimension('C')->setWidth(18);
            $spreadsheet->getColumnDimension('D')->setWidth(15);
            $spreadsheet->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getColumnDimension('F')->setWidth(40);
            $spreadsheet->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getColumnDimension('H')->setWidth(80);
            $spreadsheet->getColumnDimension('I')->setWidth(9);
            $spreadsheet->getColumnDimension('J')->setWidth(15);
            $spreadsheet->getColumnDimension('K')->setWidth(15);
            $spreadsheet->getColumnDimension('L')->setWidth(15);
            $spreadsheet->getColumnDimension('M')->setWidth(15);
            $spreadsheet->getColumnDimension('N')->setWidth(90);
            //$spreadsheet->getColumnDimension('N')->setWidth(25);


            $spreadsheet->setAutoFilter($spreadsheet->calculateWorksheetDimension());

            $spreadsheet->getStyle('A1:N1')->applyFromArray([
                'font' => [
                    'size' => 8,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ]);
            $spreadsheet->getStyle('A1:N1')->getFill()
                ->applyFromArray(
                    [ 'fillType' => Fill::FILL_SOLID,
                        'rotation' => 0,
                        'startColor' => [ 'rgb' => '47a4f5' ],
                        'endColor' => [ 'rgb' => '47a4f5' ]
                    ]
                );

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    /**
     * @return Chart|Chart[]
     */
    public function charts()
    {
        return [];
        // TODO: Implement charts() method.
    }

}
