<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    function obtenerTodos($id){
        $rol = Rol::all();

        return $rol;
    }

    function buscarRol($id){
        $rol = Rol::firstOrFail($id);

        return $rol;
    }

    function datos($rol, $request){
        $rol->descripcion = $request->rol;

        return $rol;
    }
}
