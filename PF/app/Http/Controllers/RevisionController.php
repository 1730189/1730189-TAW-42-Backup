<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use App\Models\Revision;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevisionController extends Controller
{
    //Funcion para obtener las conversaciones del doctor
    function getMensajesDoctor()
    {
        $doctor = Auth::user();

        $mensajes = DB::table('revision')
            ->join('users', 'users.id', '=', 'revision.id_medico_asociado')
            ->selectRaw('revision.*, users.nombre, users.apellidos')
            ->where('id_medico', '=', $doctor->id)
            ->orderBy('created_at', 'ASC')->paginate(10);

        if ($doctor->id_tipo_usuario == 2) {
            $mensajes = DB::table('revision')
                ->join('users', 'users.id', '=', 'revision.id_medico')
                ->selectRaw('revision.*, users.nombre, users.apellidos')
                ->where('id_medico_asociado', '=', $doctor->id)
                ->orderBy('created_at', 'ASC')->paginate(10);
        }

        return [
            'pagination' => [
                'total' => $mensajes->total(),
                'current_page' => $mensajes->currentPage(),
                'per_page' => $mensajes->perPage(),
                'last_page' => $mensajes->lastPage(),
                'from' => $mensajes->firstItem(),
                'to' => $mensajes->lastPage(),
            ],
            'mensajes' => $mensajes,
        ];
    }

    public function cargarRevision($id)    {
        $mensajes = DB::table('revision')
            ->join('users', 'users.id', '=', 'revision.id_medico_asociado')
            ->selectRaw('revision.*, users.nombre, users.apellidos')
            ->where('revision.id', '=', $id)->get();

        return $mensajes;
    }

    //Funcion crear una nueva conversación DOCTOR -> ASOCIADO
    function crearMensaje(Request $request)
    {
        $doctor = Auth::user();

        //Revisamos que esten correctos los datos
        $this->validate($request, [
            'asunto' => 'required',
            'doctor_asociado' => 'required|notIn:seleccionarAsociado',
            'mensaje' => 'required'
        ], [
            'asunto.required' => 'El asunto es requerido',
            'doctor_asociado.notIn' => 'Por favor seleccione al doctor asociado',
            'doctor_asociado.required' => 'El doctor asociado es requerido',
            'mensaje.required' => 'El mensaje es requerido'
        ]);

        $revision = new Revision();
        $revision->asunto = $request->asunto;
        $revision->id_medico = $doctor->id;
        $revision->id_medico_asociado = $request->doctor_asociado;
        $revision->mensaje = $request->mensaje;
        $revision->respuesta = null;
        $revision->id_paciente_expendiente = null;

        $revision->save();

        //Retornamos que se creó
        return (['message' => 'Mensaje creado correctamente']);
    }

    //Borrar conversacion
    function borrarConversacion($id)
    {
        //Buscamos la conversación de revisiones
        $revision = Revision::where('id', '=', $id)->get();

        $revision->delete();

        return $revision;
    }

    //Funcion para obtener a todos los doctores asociados
    function getAllAsociados()
    {
        $doctores = DB::table('users')
            ->select('users.*')
            ->where('users.id_tipo_usuario', '=', '2')->get();

        return $doctores;
    }

    //Funcion para responder por parte de doctor asociado
    function responderRevision(Request $request){
        //Buscamos la conversación de revisiones
        $revision = Revision::where('id', '=', $request->id_revision)->get();

        $revision->respuesta = $request->respuesta;

        $revision->save();

        return $revision;
    }
}
