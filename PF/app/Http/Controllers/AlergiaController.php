<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use App\Models\Paciente;
use Illuminate\Http\Request;

class AlergiaController extends Controller
{
    //Funcion para obtener Alergias dependiendo de un paciente
    function getAlergiasPaciente($id){
        //Realizamos la consulta dependiendo del id del paciente
        $alergias = Alergia::where('id_paciente', '=', $id)->paginate(10);

        //Realizamos la consulta del paciente
        $paciente = (new PacientesController())->buscarPaciente($id);

        //Regresamos los datos
        return [
            'pagination' => [
                'total' => $alergias->total(),
                'current_page' => $alergias->currentPage(),
                'per_page' => $alergias->perPage(),
                'last_page' => $alergias->lastPage(),
                'from' => $alergias->firstItem(),
                'to' => $alergias->lastPage(),
            ],
            'paciente' => $paciente['paciente'],
            'Alergias' => $alergias
        ];
    }

    //Funcion para crear nueva alergia
    function crearAlergia(Request $request){
        //Realizamos la validacion que se mandaron los datos y no esten vacios
        $this->validate($request, [
            'descripcion' => 'required',
        ],[
            'descripcion.required' => 'La descripción de la alergia es necesaria.',
        ]);

        //Realizamos una nueva alergia con base al modelo
        $alergia = new Alergia();

        //Asignamos los valores
        $alergia->descripcion = $request->descripcion;
        $alergia->id_paciente = $request->paciente;

        //Guardamos la alergia
        $alergia->save();

        //Retornamos el mensaje
        return (['message' => 'Alergia hacia el paciente ha sido creada correctamente']);
    }

    //Funcion para actualizar la alergia
    function updateAlergia(Request $request){
        //Realizamos la validacion que se mandaron los datos y no esten vacios
        $this->validate($request, [
            'descripcion' => 'required',
        ],[
            'descripcion.required' => 'La descripción de la alergia es requerido.',
        ]);

        //Buscamos el dato de la alergia con su id
        $alergia = Alergia::findOrfail($request->id);

        //Asignamos los nuevos valores
        $alergia->descripcion = $request->descripcion;

        //Guardamos la alergia
        $alergia->save();

        //Retornamos mensaje para ver si hay error o no.
        return (['message' => 'Alergia del paciente ha sido actualizado correctamente']);
    }

    //Funcion para actualizar la alergia
    function buscarAlergia($id){
        //Buscamos la alergia
        $alergia = Alergia::findOrFail($id);

        return $alergia;
    }

    //Funcion para eliminar a la alergia
    function deleteAlergia($id){
        //Buscamos la alergia por su id
        $alergia = Alergia::findOrFail($id);

        //Realizamos la eliminacion de la bd
        $alergia->delete();

        return $alergia;
    }

}
