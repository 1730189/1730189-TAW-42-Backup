<?php

namespace App\Http\Controllers;

use App\Medicamento;
use Illuminate\Http\Request;

class MedicamentosController extends Controller
{
    public function getAll(){
        //Se obtienen todos los productos de la tabla
        $medicamentos = Medicamento::all();
        return $medicamentos;
    }

    public function getMedicamento($id){
      
        $medicamento = Medicamento::findOrFail($id);

        return $medicamento;
    }

    public function addMedicamento(Request $request){
                //////////// SE AGREGA UN NUEVO PRODUCTO GUARDANDO LOS DATOS DEL REGISTRO
       
        $medicamento = new Medicamento();

     
        $medicamento = $this->guardar($request, $medicamento);

        
        $medicamento->save();
    }

    public function updateMedicamento(Request $request){
        //////////// SE MODIFICA UN PRODUCTO DEPENDIENDO DEL ID SUSTITUYENDO LOS DATOS DEL REGISTRO
        $medicamento = Medicamento::findOrFail($request->id);

        
        $medicamento = $this->guardar($request, $medicamento);

       
        $medicamento->save();

     
        return $medicamento;
    }

    public function deleteMedicamento($id){
            ////////// SE ELIMINA EL PRODUCTO DEPENDIENDO DEL ID
        $medicamentos = Medicamento::destroy($id);
        return $medicamentos;
    }

    public function guardar($request, $medicamento){
        //Se agregan los vlaores a las variables asignadas
        $medicamento->nombre = $request->nombre;
        $medicamento->cantidad = $request->cantidad;
      
        return $medicamento;
    }

}
