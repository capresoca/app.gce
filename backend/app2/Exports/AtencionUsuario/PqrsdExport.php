<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/12/2018
 * Time: 9:54 AM
 */

namespace App\Exports\AtencionUsuario;


use Illuminate\Contracts\View\View;
use Illuminate\Support\Traits\Macroable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Writer;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class PqrsdExport implements FromView, WithEvents, WithCharts
{
    protected $pqrsds;
    protected $estadisticas;
    protected $chart;
    protected $estadisticasWorksheet;

    public function __construct($pqrsds,$estadisticas)
    {
        $this->pqrsds = $pqrsds;
        $this->estadisticas = $estadisticas;

    }


    /**
     * @return View
     */
    public function view(): View
    {
        return view('maatwebsitExcel.reportePqrs', ['pqrsds' => $this->pqrsds]);
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

            },

            BeforeSheet::class => function (BeforeSheet $event) {
                $spreadsheet = $event->sheet->getDelegate();
                $this->graficos($spreadsheet);
            },


            BeforeWriting::class => function (BeforeWriting $event) {
                $spreadsheet = $event->writer->getDelegate();

                $worksheet = $spreadsheet->createSheet();


                $worksheet->setTitle('Estadisticas');


                $worksheet->fromArray(
                    $this->estadisticas['by_macromotivo']
                );

                $worksheet->addChart($this->chart);

                $spreadsheet->getProperties()
                    ->setCreator('Capresoca EPS')
                    ->setTitle('Reporte de PQRSDS');

                $this->createMacromotivosSheets($spreadsheet);
                $spreadsheet->setActiveSheetIndex(0);


            }


        ];
    }


    public function graficos(Worksheet $worksheet){
        try{
            $registros = count($this->estadisticas['by_macromotivo']);

            $dataSeriesLabels1 = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Estadisticas!$B$1', null, 1), // 2011
            ];


            $xAxisTickValues1 = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Estadisticas!$A$1:$A$'.$registros, null, $registros), // Q1 to Q4
            ];

            $dataSeriesValues1 = [
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Estadisticas!$B$1:$B$'.$registros, null, $registros),
            ];


            $series1 = new DataSeries(
                DataSeries::TYPE_PIECHART, // plotType
                null, // plotGrouping (Pie charts don't have any grouping)
                range(0, count($dataSeriesValues1) - 1), // plotOrder
                $dataSeriesLabels1, // plotLabel
                $xAxisTickValues1, // plotCategory
                $dataSeriesValues1          // plotValues
            );

            $layout1 = new Layout();
            $layout1->setShowVal(true);
            $layout1->setShowPercent(true);


// Set the series in the plot area
            $plotArea1 = new PlotArea($layout1, [$series1]);
// Set the chart legend
            $legend1 = new Legend(Legend::POSITION_RIGHT, null, false);

            $title1 = new Title('Distribución por Macromotivo');

// Create the chart
            $chart1 = new Chart(
                'chart1', // name
                $title1, // title
                $legend1, // legend
                $plotArea1, // plotArea
                true, // plotVisibleOnly
                0, // displayBlanksAs
                null, // xAxisLabel
                null   // yAxisLabel - Pie charts don't have a Y-Axis
            );
// Set the position where the chart should appear in the worksheet
            $chart1->setTopLeftPosition('A1');
            $chart1->setBottomRightPosition('M28');
            $chart1->setWorksheet();
// Add the chart to the worksheet
            $this->chart = $chart1;

        }catch (\Exception $exception)
        {
            throw new $exception;
        }

    }

    public function firstSheet(Worksheet $spreadsheet)
    {
        try{
            $spreadsheet->getDefaultRowDimension()->setRowHeight(64);
            $spreadsheet->getStyle('A1:W1')
                ->getAlignment()->setVertical('center')
                ->setHorizontal('center')->setWrapText(true);



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
            foreach ($this->pqrsds as $key=>$pqrsd){
                $spreadsheet->getStyle('A'. ($key + 2) .':W'.($key+2))
                    ->applyFromArray($styleThinBlackBorderOutline);
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
            $spreadsheet->getColumnDimension('B')->setWidth(10);
            $spreadsheet->getColumnDimension('C')->setWidth(10);
            $spreadsheet->getColumnDimension('D')->setWidth(10);
            $spreadsheet->getColumnDimension('E')->setWidth(13);
            $spreadsheet->getColumnDimension('F')->setWidth(14);
            $spreadsheet->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getColumnDimension('H')->setWidth(9);
            $spreadsheet->getColumnDimension('I')->setWidth(13);
            $spreadsheet->getColumnDimension('J')->setWidth(18);
            $spreadsheet->getColumnDimension('K')->setWidth(22);
            $spreadsheet->getColumnDimension('L')->setWidth(14);
            $spreadsheet->getColumnDimension('M')->setWidth(22);
            $spreadsheet->getColumnDimension('N')->setWidth(22);
            $spreadsheet->getColumnDimension('O')->setWidth(22);
            $spreadsheet->getColumnDimension('P')->setWidth(22);
            $spreadsheet->getColumnDimension('Q')->setWidth(45);
            $spreadsheet->getColumnDimension('R')->setWidth(12);
            $spreadsheet->getColumnDimension('S')->setWidth(12);
            $spreadsheet->getColumnDimension('T')->setWidth(12);
            $spreadsheet->getColumnDimension('U')->setWidth(12);
            $spreadsheet->getColumnDimension('V')->setWidth(45);
            $spreadsheet->getColumnDimension('W')->setWidth(40);


            $spreadsheet->setAutoFilter($spreadsheet->calculateWorksheetDimension());

            $spreadsheet->getStyle('A1:W1')->applyFromArray([
                'font' => [
                    'size' => 9,
                    'bold' => true,
                    'name' => 'Calibri'
                ],
            ]);
            $spreadsheet->getStyle('A1:W1')->getFill()
                ->applyFromArray(
                    [ 'fillType' => Fill::FILL_SOLID,
                        'rotation' => 0,
                        'startColor' => [ 'rgb' => '92D050' ],
                        'endColor' => [ 'rgb' => '92D050' ]
                    ]
                );
        }catch (\Exception $exception){
            throw $exception;
        }


    }


    public function createMacromotivosSheets(Spreadsheet $spreadsheet)
    {
        foreach ($this->estadisticas['macromotivo_by_entidad'] as $macromotivo){
            try{
                $worksheet_name = 'Macromotivo'.$macromotivo['codigo'];
                $worksheet = $spreadsheet->createSheet();

                $worksheet->setTitle($worksheet_name);
                $worksheet->mergeCells('A1:B1');
                $worksheet->setCellValue('A1',$macromotivo['descripcion']);

                $worksheet->setCellValue('A2','Entidad');
                $worksheet->setCellValue('B2','PQRS Recibidas');

                $worksheet->fromArray($macromotivo['entidades'],null,'A3');

                $worksheet->getColumnDimension('A')->setWidth(50);
                $worksheet->getRowDimension(1)->setRowHeight(24);
                $worksheet->getRowDimension(2)->setRowHeight(24);

                $worksheet->getStyle('A1:B2')->applyFromArray([
                    'font' => [
                        'size' => 9,
                        'bold' => true,
                        'name' => 'Calibri'
                    ],
                ]);

                $worksheet->getStyle('A1:B2')->getFill()
                    ->applyFromArray(
                        [ 'fillType' => Fill::FILL_SOLID,
                            'rotation' => 0,
                            'startColor' => [ 'rgb' => '92D050' ],
                            'endColor' => [ 'rgb' => '92D050' ]
                        ]
                    );

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
                $registros = count($macromotivo['entidades']);
                $worksheet->getStyle('A1:B'.($registros+2))->applyFromArray($styleThinBlackBorderOutline);

                $chart = $this->makeColumnsChart($worksheet_name,$registros,$macromotivo['descripcion']);


                $worksheet->addChart($chart);


            }catch (\Exception $exception){
                throw $exception;
            }
        }

    }

    public function makeColumnsChart($worksheet_name,$registros,$macromotivo_descripcion){

        $registros = $registros + 2;
        $dataSeriesLabels1 = [];


        $xAxisTickValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $worksheet_name.'!$A$3:$A$'.$registros, null, $registros), // Q1 to Q4
        ];

        $dataSeriesValues1 = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $worksheet_name.'!$B$3:$B$'.$registros, null, $registros),
        ];


        $series1 = new DataSeries(
            DataSeries::TYPE_BARCHART, // plotType
            null, // plotGrouping (Pie charts don't have any grouping)
            range(0, count($dataSeriesValues1) - 1), // plotOrder
            $dataSeriesLabels1, // plotLabel
            $xAxisTickValues1, // plotCategory
            $dataSeriesValues1          // plotValues
        );

        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);


// Set the series in the plot area
        $plotArea1 = new PlotArea($layout1, [$series1]);
// Set the chart legend
        $legend1 = new Legend(Legend::POSITION_RIGHT, null, false);

        $title1 = new Title('PQRS x Entidad Macromotivo:'.$macromotivo_descripcion);
        $yAxisLabel = new Title('PQRS Recibidas');
// Create the chart
        $chart1 = new Chart(
            'chart1', // name
            $title1, // title
            $legend1, // legend
            $plotArea1, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel   // yAxisLabel - Pie charts don't have a Y-Axis
        );
// Set the position where the chart should appear in the worksheet
        $chart1->setTopLeftPosition('C1');
        $chart1->setBottomRightPosition('Q25');
        $chart1->setWorksheet();


        return $chart1;
    }

    /**
     * @return Chart|Chart[]
     */
    //No quitar es necesario para que se agreguen los graficos
    public function charts()
    {
        return [];
    }
}

