<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(){
        /*$user = Auth::user();

        dd($user);*/
    }

    public function prueba() {
        /*$con = mysql_connect('198.136.51.130','ojo','qazwsx12A.');
        mysql_select_db('desarrollo', $con);*/
        //$enlace = mysqli_connect("198.136.51.130", "ojo", "qazwsx12A.", "desarrollo");
        
        $array = array('BIEN');
       /* if ($resultado = $enlace->query("select * from as_afiliados")) {
            $array[] = ("La selección devolvió %d filas.\n".$resultado->num_rows);
            
            $resultado->close();
        }*/
        
        
        
        return implode(", ", $array);
    }
}
