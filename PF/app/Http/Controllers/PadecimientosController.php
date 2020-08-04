<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Padecimiento;
use Illuminate\Http\Request;

class PadecimientosController extends Controller
{
    //Funcion para obtener los Padecimientos que sean de un paciente en especifico
    function getPadecimientosPaciente(Request $request, $id){
        //Obtenemos los Padecimientos que tenga el paciente con id tal
        $padecimientos = Padecimiento::where('id_paciente', '=', $id)->paginate(8);

        //Realizamos la consulta del paciente
        $paciente = (new PacientesController())->buscarPaciente($id);

        //Regresamos los datos
        return [
            'pagination' => [
                'total' => $padecimientos->total(),
                'current_page' => $padecimientos->currentPage(),
                'per_page' => $padecimientos->perPage(),
                'last_page' => $padecimientos->lastPage(),
                'from' => $padecimientos->firstItem(),
                'to' => $padecimientos->lastPage(),
            ],
            'paciente' => $paciente['paciente'],
            'Padecimientos' => $padecimientos
        ];
    }

    //Funcion para crear un nuevo padecimiento
    function createPadecimiento(Request $request){
        //Realizamos la validacion que se mandaron los datos y no esten vacios
        $this->validate($request, [
            'descripcion' => 'required',
        ],[
            'descripcion.required' => 'La descripción del padecimiento es requerido.',
        ]);

        //Realizamos una nuevo padecimiento con base al modelo
        $padecimiento = new Padecimiento();

        //Asignamos los valores
        $padecimiento->descripcion = $request->descripcion;
        $padecimiento->id_paciente = $request->paciente;

        //Guardamos la el padecimiento
        $padecimiento->save();

        //Retornamos el mensaje
        return (['message' => 'Padecimiento del paciente ha sido creado correctamente']);
    }

    //Funcion para actualizar un padecimiento
    function updatePadecimiento(Request $request){
        //Realizamos la validacion que se mandaron los datos y no esten vacios
        $this->validate($request, [
            'descripcion' => 'required',
        ],[
            'descripcion.required' => 'La descripción del padecimiento es requerido.',
        ]);

        //Buscamos un padecimiento por su id
        $padecimiento = Padecimiento::findOrfail($request->id);

        //Actualizamos descripcion de padecimiento, siempre será para el mismo paciente
        $padecimiento->descripcion = $request->descripcion;

        //Guardamos en la BD
        $padecimiento->save();

        //Retornamos mensaje para ver si hay error o no.
        return (['message' => 'Padecimiento del paciente ha sido actualizado correctamente']);
    }

    //Funcion para obtener la informacion del padecimiento para actualizar
    function buscarPadecimiento($id){
        //Buscamos el padecimiento con el id unico
        $padecimiento = Padecimiento::findOrFail($id);

        return $padecimiento;
    }

    //Funcion para eliminar el padecimiento
    function deletePadecimiento($id){
        //Buscamos el padecimiento por su id
        $padecimiento = Padecimiento::findOrFail($id);

        //Realizamos la eliminacion de la bd
        $padecimiento->delete();

        return $padecimiento;
    }
}
