<?php

namespace App\Exports\AtencionUsuario;

use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCharts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

class estatPqrsExport implements WithCharts,WithEvents, FromView
{
    protected $estadisticas;
    protected $pqrs;

    public function __construct($estadisticas,$pqrs)
    {
        $this->estadisticas = $estadisticas;
        $this->pqrs = $pqrs;
    }

    public function view(): View
    {
        return view('maatwebsitExcel.reportePqrs', ['pqrsds' => $this->pqrs]);
    }
    /**
     * @return array
     */
    public function array(): array
    {
        return $this->estadisticas['by_macromotivo'];
    }

    public function charts()
    {

        $label      = [new DataSeriesValues('String', 'Worksheet!$A$1', null, 1)];
        $categories = [new DataSeriesValues('String', 'Worksheet!$A$1:$A$5', null, 5)];
        $values     = [new DataSeriesValues('Number', 'Worksheet!$B$1:$B$5', null, 5)];



        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, count($values) - 1),
            $label, $categories, $values);

        $layout1 = new Layout();
        $layout1->setShowVal(true);
        $layout1->setShowPercent(true);

        $plot   = new PlotArea($layout1, [$series]);
        $legend = new Legend(Legend::POSITION_RIGHT, null, false);
        $title = new Title('Pqrs por Macromotivo');

        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plot, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            null   // yAxisLabel - Pie charts don't have a Y-Axis
        );
        $chart->setTopLeftPosition('A8');
        $chart->setBottomRightPosition('H20');
        return $chart;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [




        ];
    }

}
