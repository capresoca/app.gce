<?php

namespace App\Exports;

use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Excel;

class ReporteExport implements FromCollection, WithHeadings, WithCustomCsvSettings
{
    protected $collection;
    protected $headings;
    protected $fileName;

    public function __construct($collection, $headings, $fileName)
    {
        $this->collection = $collection;
        $this->headings = $headings;
        $this->fileName = $fileName;
    }

    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return $this->headings;
    }

    /**
     * @inheritDoc
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => '│',
            'enclosure' => ''
        ];
    }

    public function downloadStoreFile()
    {
        return Storage::download(public_path('storage/tmp/') . $this->fileName)->deleteAfterSend();
    }
}
