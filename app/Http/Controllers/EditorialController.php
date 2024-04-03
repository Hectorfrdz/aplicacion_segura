<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Editorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EditorialController extends Controller
{
    public function createEditorial(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|max:25",
                "direction" => "required|max:50",
                "phone" => "required|max:10|unique:editorials,phone",
                "email" => "required|max:50|unique:editorials,email|email",
            ],
            [
                'name.required' => 'El nombre es requerido.',
                'name.max' => 'El nombre debe ser mas corto.',
                'direction.required' => 'La direccion es requerida.',
                'direction.max' => 'La direccion debe ser mas corta.',
                'phone.required' => 'El telefono es requerido.',
                'phone.max' => 'El telefono debe ser mas corto.',
                'phone.unique' => 'El telefono debe ser unico.',
                'email.required' => 'El correo es requerido.',
                'email.max' => 'El correo debe ser mas corto.',
                'email.unique' => 'El correo debe ser unico.',
                'email.email' => 'El correo debe ser un correo valido.',
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $editorial = new Editorial;
            $editorial->name = $request->name;
            $editorial->direction = $request->direction;
            $editorial->phone = $request->phone;
            $editorial->email = $request->email;
            if ($editorial->save()) {
                return response()->json(['message' => "editorial creada con exito"], 200);
            } else {
                return response()->json(['error' => "No se creo la editorial"], 400);
            }
        }
    }
    public function updateEditorial(Request $request, $id){
        $editorial = Editorial::find($id);
        if($editorial){
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => "required|max:25",
                    "direction" => "required|max:50",
                    "phone" => "required|max:10|unique:editorials,phone,".$editorial->id,
                    "email" => "required|max:50|unique:editorials,email,".$editorial->id,
                ],
                [
                    'name.required' => 'El nombre es requerido.',
                    'name.max' => 'El nombre debe ser mas corto.',
                    'direction.required' => 'La direccion es requerida.',
                    'direction.max' => 'La direccion debe ser mas corta.',
                    'phone.required' => 'El telefono es requerido.',
                    'phone.max' => 'El telefono debe ser mas corto.',
                    'phone.unique' => 'El telefono debe ser unico.',
                    'email.required' => 'El correo es requerido.',
                    'email.max' => 'El correo debe ser mas corto.',
                    'email.unique' => 'El correo debe ser unico.',
                ]
            );

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                $editorial->name = $request->name;
                $editorial->direction = $request->direction;
                $editorial->phone = $request->phone;
                $editorial->email = $request->email;
                if ($editorial->save()) {
                    return response()->json(['message' => "editorial actualizada con exito"], 200);
                } else {
                    return response()->json(['error' => "No se actualizo la editorial"], 400);
                }
            }
        }else{
            return response()->json(['error' => "No se encontro la editorial"], 400);
        }
    }
    public function deleteEditorial($id){
        $editorial = Editorial::find($id);
        if($editorial){
            $editorial->delete();
            return response()->json(['message' => "editorial eliminada con exito"], 200);
        }else{
            return response()->json(['error' => "No se encontro la editorial"], 400);
        }
    }
    public function readEditorials(){
        $editorials = Editorial::simplePaginate(10);
        return view('editorials', compact('editorials'));
    }
    public function readEditorial($id){
        $editorial = Editorial::find($id);
        if($editorial){
            return $editorial;
        }else{
            return response()->json(['error' => "No se encontro la editorial"], 400);
        }
    }
}
