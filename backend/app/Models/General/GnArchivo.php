<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use OwenIt\Auditing\Contracts\Auditable;

class GnArchivo extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $guarded = [];

    protected $appends = ['url_signed'];

    public function getUrlSignedAttribute(){
        return URL::temporarySignedRoute('archivo',now()->addMinute(30),[$this->id]);
    }

}
