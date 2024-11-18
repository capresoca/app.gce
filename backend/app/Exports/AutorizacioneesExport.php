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

class AutorizacioneesExport implements FromView, WithEvents, WithCharts
{

    use Exportable;

    public function __construct($data)
    {
        //dd($data);
        $this->data = $data;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('maatwebsitExcel.informe_autorizador', ['autorizadores' => $this->data]);
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
            $spreadsheet->getDefaultRowDimension()->setRowHeight(30);
            $spreadsheet->getStyle('A1:C2')->getAlignment()
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
                    'horizontal' => 'left',
                    'wrapText' => true
                ]
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
                    'horizontal' => 'center',
                    'wrapText' => true
                ]
            ];
            foreach ($this->data as $key => $tutela){
                $spreadsheet->getStyle('A'. ($key + 2) .':C'.($key+2))
                    ->applyFromArray($styleThinBlackBorderOutline);
                $spreadsheet->getStyle('C'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
            }

            $styleNoneBorderOutline = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => 'none' ,
                    ],
                ],
            ];

            $spreadsheet->getStyle('A1')->applyFromArray($styleNoneBorderOutline);

            $spreadsheet->getColumnDimension('A')->setWidth(5);
            $spreadsheet->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getColumnDimension('C')->setWidth(20);


            $spreadsheet->setAutoFilter($spreadsheet->calculateWorksheetDimension());

            $spreadsheet->getStyle('A1:C1')->applyFromArray([
                'font' => [
                    'size' => 9,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ]);
            $spreadsheet->getStyle('A1:C1')->getFill()
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
