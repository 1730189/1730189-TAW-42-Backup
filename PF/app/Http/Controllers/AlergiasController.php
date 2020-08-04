<?php

namespace App\Http\Controllers;

use App\Alergia;
use Illuminate\Http\Request;

class AlergiasController extends Controller
{
    public function getAll(){
        //Se obtienen todos los Alergias de la tabla
        $alergias = Alergia::all();
        return $alergias;
    }

    public function getAlergia($id){
        
        $alergia = Alergia::findOrFail($id);

        return $alergia;
    }

    public function addAlergia(Request $request){
        //////////// SE AGREGA UN NUEVO alergia GUARDANDO LOS DATOS DEL REGISTRO
        
        $alergia = new Alergia();

        
        $alergia = $this->guardar($request, $alergia);

     
        $alergia->save();
    }

    public function updateAlergia(Request $request){
         //////////// SE MODIFICA UN alergia DEPENDIENDO DEL ID SUSTITUYENDO LOS DATOS DEL REGISTRO
    
        $alergia = Alergia::findOrFail($request->id);

       
        $alergia = $this->guardar($request, $alergia);

        
        $alergia->save();

       
        return $alergia;
    }

    public function deleteAlergia($id){
       ////////// SE ELIMINA EL alergia DEPENDIENDO DEL ID
        $alergias = Alergia::destroy($id);
        return $alergias;
    }

    public function guardar($request, $alergia){
        //Se agregan los vlaores a las variables asignadas
        $alergia->nombre = $request->nombre;
        $alergia->descripcion = $request->descripcion;
        return $alergia;
    }

}
