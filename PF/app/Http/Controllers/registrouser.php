<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;


class registrouser extends Controller
{
    //

    public function guardar($request, $usuario){
        //Se agregan los vlaores a las variables asignadas
        $usuario->nombre = $request->nombre;
        $usuario->cantidad = $request->cantidad;
      
        return $usuario;
    }
}
