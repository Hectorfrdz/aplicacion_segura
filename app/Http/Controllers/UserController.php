<?php

namespace App\Http\Controllers;

use App\Jobs\Verificar_Correo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function readUsers(){
        $users = User::all();
        return $users;
    }
    public function readUser($id){
        $user = User::find($id);
        if($user){
            return $user;
        }else{
            return response()->json(['error' => "No se encontro al usuario"], 400);
        }
    }

    public function createUser(Request $request)
    {
        //Validamos los datos de entrada
        $validator =  Validator::make(
            $request->all(),
            [
                "name" => "required|min:3|max:50",
                "email" => "required|email|unique:users,email|max:50",
                "password" => "required|min:8|max:50",
                "role" => "required",
            ],
            [
                'name.required' => 'El nombre es requerido.',
                'name.min' => 'El nombre debe tener al menos 3 caracteres.',
                'name.max' => 'El nombre debe tener máximo 50 caracteres',
                'email.required' => 'El email es requerido.',
                'email.email' => 'El email debe ser válido.',
                'email.unique' => 'El email ya existe.',
                'password.required' => 'La contraseña es requerida.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.max' => 'La contraseña debe tener máximo 25 caracteres.',
                'role.required' => 'El rol es requerido.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 422);
        } else {
            // Crear usuario
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->status = 1;
            // Guardar al usuario 
            if($user->save()){
                return response()->json([
                    'message' => ' Usuario creado con éxito'
                ],201);
            } else {
                return response()->json([
                    'error' => 'Error al crear el usuario, intentelo mas tarde'
                ], 400);
            }
        }
    }

    public function updateUser(Request $request, $id){
        $user = User::find($id);
        if($user){
            $validator =  Validator::make(
                $request->all(),
                [
                    "name" => "required|min:3|max:50",
                    "email" => "required|max:50|email|unique:users,email,".$user->id,
                    "role" => "required",
                ],
                [
                    'name.required' => 'El nombre es requerido.',
                    'name.min' => 'El nombre debe tener al menos 3 caracteres.',
                    'name.max' => 'El nombre debe tener máximo 50 caracteres',
                    'email.required' => 'El email es requerido.',
                    'email.email' => 'El email debe ser válido.',
                    'email.unique' => 'El email ya existe.',
                    'role.required' => 'El rol es requerido.',
                ]
            );
            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ], 422);
            } else {
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role;
                $user->status = 1;
                // Guardar al usuario 
                if($user->save()){
                    return response()->json([
                        'message' => ' Usuario creado con éxito'
                    ],201);
                } else {
                    return response()->json([
                        'error' => 'Error al crear el usuario, intentelo mas tarde'
                    ], 400);
                }
            }
        } else {
            return response()->json([
                'error' => 'Error al actualizar el usuario, Usuario no encontrado'
            ], 400);
        }
        
    }

    public function deleteUser($id){
        $user = User::find($id);
        if($user){
            $user->delete();
            return response()->json(['message' => "genero eliminado con exito"], 200);
        }else{
            return response()->json(['error' => "No se encontro el genero"], 400);
        }
    }
}
