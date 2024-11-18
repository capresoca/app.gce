<?php

namespace Tests\Unit;

use App\Traits\ToolsTrait;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

//class ToolsTraitTest extends TestCase
//{
//    /**
//     * A basic test example.
//     *
//     * @return void
//     */
////    public function testExample()
////    {
////        $array = ['foo', 'bar'];
////
////        $new_array = ToolsTrait::removerElement('foo',$array);
////
////        $this->assertCount(1, $new_array);
////    }
////
////    /**
////     *
////     */
////    public function testSuperpone()
////    {
////        $rangos = [
////            [
////                'valor_inicial' => 0,
////                'valor_final' => 500
////            ],
////            [
////                'valor_inicial' => 501,
////                'valor_final' => 520
////            ],
////            [
////                'valor_inicial' => 521,
////                'valor_final' => 580
////            ]
////        ];
////
////
////        $superpone = ToolsTrait::validarSuperpocisionRangos($rangos);
////
////        $this->assertTrue($superpone);
////
////    }
////
////    public function testNoSuperpone()
////    {
////        $rangos = [
////            [
////                'valor_inicial' => 0,
////                'valor_final' => 500
////            ],
////            [
////                'valor_inicial' => 480,
////                'valor_final' => 520
////            ],
////            [
////                'valor_inicial' => 521,
////                'valor_final' => 580
////            ]
////        ];
////
////        $tool = new ToolsTrait();
////
////        $superpone = $tool->validarSuperpocisionRangos($rangos);
////
////        $this->assertFalse($superpone);
////
////    }
//}
