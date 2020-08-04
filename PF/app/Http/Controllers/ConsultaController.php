<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;

class ConsultaController extends Controller{

    //Funcion para registrar consulta
    function crearConsulta(Request $request){
        $consulta = new Consulta();

        $consulta->descripcion = $request->servicio;
        $consulta->id_cita = $request->id_cita;
        $consulta->precio = $request->precio;

        $consulta->save();

        return $consulta;
    }

}
