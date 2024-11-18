<?php

namespace App\Models\Niif;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class NfCiiu extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
