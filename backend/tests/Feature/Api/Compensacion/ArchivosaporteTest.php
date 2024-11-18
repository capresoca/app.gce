<?php

namespace Tests\Feature\Api\Compensacion;

use App\Models\Compensacion\CpArchivosaporte;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Api\TestCase;

class ArchivosaporteTest extends TestCase
{
    public function testStore()
    {
        $archivos = factory(CpArchivosaporte::class)->make()->toArray();
        $this->actingAsAdmin()
            ->post('/api/archivos_aportes',$archivos)
            ->assertStatus(201);
    }

    public function testIndex()
    {
        $this->actingAsAdmin()
            ->get('/api/archivos_aportes')
            ->assertStatus(200);

    }

//    public function testEdit()
//    {
//        $archivos = factory(CpArchivosaporte::class)->create();
//        $archivos->codigo = 'ed'. random_int(100,999);
//        $this->actingAsAdmin()
//            ->put('/api/cups/'.$archivos->id,$cup->toArray())
//            ->assertStatus(202);
//    }
}
