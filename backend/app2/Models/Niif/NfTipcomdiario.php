<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NfTipcomdiario extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    protected $guarded = [];
}
