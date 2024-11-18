<?php

namespace Tests\Feature\Api\AtencionUsuario;

use App\Models\AtencionUsuario\AuTipincapacidade;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class TipincapacidadTest extends TestCase
{



    public function testEdit()
    {
        $tipincapacidad = AuTipincapacidade::find(random_int(1,7));


        $tipincapacidad->tipo = $tipincapacidad->tipo.' EDITADO';

        $tipincapacidad = $tipincapacidad->toArray();

        $this->actingAsAdmin()
            ->put('api/tipincapacidades/'.$tipincapacidad['id'],$tipincapacidad)
            ->assertJson(['ok'])
            ->assertStatus(202);
    }
}
