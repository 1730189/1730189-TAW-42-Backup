<?php

namespace App\Http\Controllers;

use App\Models\Alergia;
use App\Models\Paciente;
use App\Models\Padecimiento;
use App\Models\Sangre;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PacientesController extends Controller
{
    //Funcion para obetener a todos los Pacientes
    function getPacientesConsultorio(){
        //Obtenemos el usuario para sabe que consultorio es
        $usuario = Auth::user();

        //Funcion para traer a los Pacientes dependiendo del consultorio paginado
        $pacientes = Paciente::where('id_consultorio', '=', $usuario->id_consultorio)->paginate(10);

        return [
            'pagination' => [
                'total' => $pacientes->total(),
                'current_page' => $pacientes->currentPage(),
                'per_page' => $pacientes->perPage(),
                'last_page' => $pacientes->lastPage(),
                'from' => $pacientes->firstItem(),
                'to' => $pacientes->lastPage(),
            ],
            'pacientes' => $pacientes
        ];
    }

    //Funcion para crear paciente nuevo
    function crearPaciente(Request $request){
        $usuario = Auth::user();

        //Validamos los valores
        $this->validate($request, [
            'nombre' => 'required',
            'apellidos' => 'required',
            'edad' => 'required|max:2',
            'sexo' => 'required|notIn:seleccionarSexo',
            'peso' => 'required',
            'altura' => 'required',
        ],[
            'nombre.required' => 'El nombre es requerido',
            'apellidos.required' => 'Los apellidos son requerido',
            'edad.required' => 'La edad es requerida',
            'sexo.required' => 'El sexo es requerido',
            'sexo.notIn' => 'Por favor seleccione el sexo',
            'peso.required' => 'El peso es requerido',
            'altura.required' => 'La altura es requerida',
        ]);

        //Creamos un nuevo paciente a travez de modelo
        $paciente = new Paciente();

        //Asignamos los valores
        $paciente->nombre = $request->nombre;
        $paciente->apellidos = $request->apellidos;
        $paciente->edad = $request->edad;
        $paciente->sexo = $request->sexo;
        $paciente->altura = $request->altura;
        $paciente->peso = $request->peso;
        $paciente->id_consultorio = $usuario->id_consultorio;

        //Guardamos el paciente
        $paciente->save();

        //Retornamos a la lista
        return (['message' => 'Paciente creado']);
    }

    //Funcion para crear paciente nuevo
    function buscarPaciente($id){
        //Funcion para buscar el paciente por el id
        $paciente = Paciente::findOrFail($id);

        //Obtenemos las cantidades de Alergias del paciente
        $alergia = count(Alergia::where('id_paciente', '=', $id)->get());
        $padecimiento = count(Padecimiento::where('id_paciente', '=', $id)->get());

        //Regresamos al paciente
        return [
            'paciente' => $paciente,
            'Alergias' => $alergia,
            'padecimiento' => $padecimiento
        ];
    }

    //Funcion para actualizar un nuevo paciente
    function actualizarPaciente(Request $request){
        $usuario = Auth::user();

        //Validamos los valores
        $this->validate($request, [
            'nombre' => 'required',
            'apellidos' => 'required',
            'edad' => 'required|max:2',
            'sexo' => 'required|notIn:seleccionarSexo',
            'peso' => 'required',
            'altura' => 'required',
        ],[
            'nombre.required' => 'El nombre es requerido',
            'apellidos.required' => 'Los apellidos son requerido',
            'edad.required' => 'La edad es requerida',
            'sexo.required' => 'El sexo es requerido',
            'sexo.notIn' => 'Por favor seleccione el sexo',
            'peso.required' => 'El peso es requerido',
            'altura.required' => 'La altura es requerida',
        ]);

        //Funcion para buscar el paciente por el id
        $paciente = Paciente::findOrFail($request->id);

        //Asignamos los valores
        $paciente->nombre = $request->nombre;
        $paciente->apellidos = $request->apellidos;
        $paciente->edad = $request->edad;
        $paciente->sexo = $request->sexo;
        $paciente->altura = $request->altura;
        $paciente->peso = $request->peso;
        $paciente->id_consultorio = $usuario->id_consultorio;

        //Guardamos el paciente
        $paciente->save();

        //Retornamos a la lista
        return (['message' => 'Paciente actualizado']);
    }

    //Funcion para eliminar paciente
    function eliminarPaciente($id){
        //Buscamos el paciente
        $paciente = Paciente::findOrFail($id);

        //Eliminamos el paciente
        $paciente->delete();

        //Retornamos a la lista
        return $paciente;
    }

    function pacienteConsultorio(){
        $usuario = Auth::user();

        $pacientes = Paciente::where('id_consultorio', '=', $usuario->id_consultorio)->get();

        return ['pacientes' => $pacientes,
            'cantPacientes' => count($pacientes)];
    }

    function cantidadPacientesAll(){
        $pacientes = count(Paciente::all());

        return $pacientes;
    }
}
