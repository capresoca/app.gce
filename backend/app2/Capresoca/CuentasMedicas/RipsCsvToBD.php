<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 18/01/2019
 * Time: 4:58 PM
 */

namespace App\Capresoca\CuentasMedicas;


use App\Capresoca\LeerCsv;
use App\Models\Riips\RsRipsRadicado;

class RipsCsvToBD
{
    use LeerCsv;

    protected $rips;
    protected $data;
    protected $numRows;
    protected $rowsProcessed;

    public function __construct(RsRipsRadicado $rips,$tipo)
    {
        $this->rips = $rips;
        $this->tipo = $tipo;
        $this->setData();

    }

    protected function setData(){
        $reader = $this->leerFromUrlS3($this->rips->getFileUrl($this->tipo));
        $this->data = $reader->getRecords();
        $this->numRows = count($reader);
    }

}