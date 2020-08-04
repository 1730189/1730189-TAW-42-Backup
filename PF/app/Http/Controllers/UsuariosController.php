<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    function getAll(){
        $usuarios = User::all();

        return $usuarios;
    }

    function crearUsuario(Request $request){
        $usuario = User::create([
            'nombre' => $request->nombre,
            'apellidos' => $request->apellidos,
            'edad' => $request->edad,
            'username' => $request->username,
            'password' => $request->password,
            'id_consultorio' => $request->consultorio,
            'id_tipo_usuario' => $request->tipo_usuario
        ]);

        return $usuario;
    }

    function actualizarUsuario(Request $request){
        $usuario = User::findOrFail($request->id);
        $usuario->nombre = $request->nombre;
        $usuario->apellidos = $request->apellidos;
        $usuario->edad = $request->edad;
        $usuario->username = $request->username;
        $usuario->password = $request->password;
        $usuario->id_consultorio = $request->consultorio;
        $usuario->id_tipo_usuario = $request->tipo_usuario;

        $usuario->save();
    }

    function eliminarUsuario($id){
        $usuario = User::destroy($id);

        return $usuario;
    }

    function getUsuario($id){
        $usuario = User::where('id', '=', $id)->get();

        return $usuario;
    }


    function getDoctores(){
        $usuario = User::where('id_tipo_usuario', '=', 1)->get();

        return $usuario;
    }

    function getDoctoresAsociados(){
        $usuario = User::where('id_tipo_usuario', '=', 2)->get();

        return $usuario;
    }

    function searchDoctorAsociado($nombre){
        $usuario = User::where('id_tipo_usuario', '=', '2')
                    ->where('nombre', 'LIKE', '%'.$nombre.'%')->get();

        return $usuario;
    }

    function searchDoctor($nombre){
        $usuario = Usuario::where('id_tipo_usuario', '=', '1')
            ->where('nombre', 'LIKE', '%'.$nombre.'%')->get();

        return $usuario;
    }

    function searchDoctor2($id){
        $usuario = Usuario::where('id_tipo_usuario', '=', '2')
            ->where('id', '=', $id)->get();

        return $usuario;
    }

    function getSecretarias(){
        $usuario = Usuario::where('id_tipo_usuario', '=', 3)->get();

        return $usuario;
    }

    function getRolUsuario(){
        $usuario = Auth::user()->id_tipo_usuario;

        return $usuario;
    }
}
