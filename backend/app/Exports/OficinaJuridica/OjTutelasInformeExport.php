<?php

namespace App\Exports\OficinaJuridica;

use App\Models\OficinaJuridica\OjTutela;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OjTutelasInformeExport implements FromView, WithEvents, WithCharts
{

    use Exportable;

    public function __construct($data, $detail)
    {
        $this->data = $data;
        $this->detail = $detail;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        return view('maatwebsitExcel.reporteTutela', ['tutelas' => $this->data, 'detalles' => $this->detail]);
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
            $spreadsheet->getDefaultRowDimension()->setRowHeight(64);
            $spreadsheet->getStyle('A1:AL2')->getAlignment()
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
                    'vertical' => 'justify',
                    'horizontal' => 'justify',
                    'wrapText' => true
                ]
            ];
            foreach ($this->data as $key => $tutela){
                $spreadsheet->getStyle('A'. ($key + 2) .':AL'.($key+2))
                    ->applyFromArray($styleThinBlackBorderOutline);
                $spreadsheet->getStyle('N'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('O'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('P'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('R'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
                $spreadsheet->getStyle('Y'. ($key + 2))->applyFromArray($styleThinBlackBorderOutlineTwo);
            }

            $styleNoneBorderOutline = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => 'none' ,
                    ],
                ],
            ];

            $spreadsheet->getStyle('A1')->applyFromArray($styleNoneBorderOutline);

            $spreadsheet->getColumnDimension('A')->setWidth(20);
            $spreadsheet->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getColumnDimension('C')->setWidth(20);
            $spreadsheet->getColumnDimension('D')->setWidth(22);
            $spreadsheet->getColumnDimension('E')->setWidth(30);
            $spreadsheet->getColumnDimension('F')->setWidth(30);
            $spreadsheet->getColumnDimension('G')->setWidth(30);
            $spreadsheet->getColumnDimension('H')->setWidth(30);
            $spreadsheet->getColumnDimension('I')->setWidth(30);
            $spreadsheet->getColumnDimension('J')->setWidth(22);
            $spreadsheet->getColumnDimension('K')->setWidth(14);
            $spreadsheet->getColumnDimension('L')->setWidth(22);
            $spreadsheet->getColumnDimension('M')->setWidth(22);
            $spreadsheet->getColumnDimension('N')->setWidth(22);
            $spreadsheet->getColumnDimension('O')->setWidth(40);
            $spreadsheet->getColumnDimension('P')->setWidth(40);
            $spreadsheet->getColumnDimension('Q')->setWidth(12);
            $spreadsheet->getColumnDimension('R')->setWidth(22);
            $spreadsheet->getColumnDimension('S')->setWidth(20);
            $spreadsheet->getColumnDimension('T')->setWidth(20);
            $spreadsheet->getColumnDimension('U')->setWidth(15);
            $spreadsheet->getColumnDimension('V')->setWidth(12);
            $spreadsheet->getColumnDimension('W')->setWidth(12);
            $spreadsheet->getColumnDimension('X')->setWidth(22);
            $spreadsheet->getColumnDimension('Y')->setWidth(30);
            $spreadsheet->getColumnDimension('Z')->setWidth(35);
            $spreadsheet->getColumnDimension('AA')->setWidth(15);
            $spreadsheet->getColumnDimension('AB')->setWidth(15);
            $spreadsheet->getColumnDimension('AC')->setWidth(22);
            $spreadsheet->getColumnDimension('AD')->setWidth(15);
            $spreadsheet->getColumnDimension('AE')->setWidth(15);
            $spreadsheet->getColumnDimension('AF')->setWidth(15);
            $spreadsheet->getColumnDimension('AG')->setWidth(15);
            $spreadsheet->getColumnDimension('AH')->setWidth(15);
            $spreadsheet->getColumnDimension('AI')->setWidth(22);
            $spreadsheet->getColumnDimension('AJ')->setWidth(22);
            $spreadsheet->getColumnDimension('AK')->setWidth(33);
            $spreadsheet->getColumnDimension('AL')->setWidth(12);


            $spreadsheet->setAutoFilter($spreadsheet->calculateWorksheetDimension());

            $spreadsheet->getStyle('A1:AL1')->applyFromArray([
                'font' => [
                    'size' => 9,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ]);
            $spreadsheet->getStyle('A1:AL1')->getFill()
                ->applyFromArray(
                    [ 'fillType' => Fill::FILL_SOLID,
                        'rotation' => 0,
                        'startColor' => [ 'rgb' => '92D050' ],
                        'endColor' => [ 'rgb' => '92D050' ]
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