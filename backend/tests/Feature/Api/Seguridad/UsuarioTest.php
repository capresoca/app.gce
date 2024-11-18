<?php

namespace Tests\Feature\Api\Seguridad;



use App\GsRole;
use App\User;
use Tests\Feature\Api\TestCase;

class UsuarioTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
//    public function testSearch()
//    {
//        $this->actingAsAdmin()
//            ->get('api/usuarios/search')
//            ->assertStatus(200);
//    }

//    public function testStore()
//    {
//        $user = factory(User::class)->make()->toArray();
//        $this->actingAsAdmin()
//            ->post('/api/usuarios', $user)
//            ->assertJson(['ok'])
//            ->assertStatus(201);
//    }

    public function testEdit()
    {
        $form = factory(User::class)->make()->toArray();
        $form->roles = GsRole::all();
        $this->actingAsAdmin()
            ->put('/api/usuarios/'.$form->id,$form->toArray())
            ->assertJson(['ok'])
            ->assertStatus(200);
    }
}
