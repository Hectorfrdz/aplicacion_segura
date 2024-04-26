<?php

namespace App\Http\Controllers;

use App\Jobs\Verificacion_Dos_Pasos;
use App\Jobs\verificacion_dos_pasos_admin;
use App\Jobs\Verificar_Correo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    

    public function login(Request $request)
    {
        //Validacion 
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'code' => 'required'
        ]);

        //retornar errores
        if ($validator->fails()) {
            return response()->json([
                'mensaje' => $validator->errors(),
                'success' => false
            ], 401);
        }

        //Validar credenciales
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'mensaje' => 'Credenciales invalidas',
                'success' => false
            ], 401);
        }

        //Regresar si el usuario no es admin    
        if ($user->role != 1) {
            
            return response()->json([
                'mensaje' => 'Acceso denegado',
                'success' => false
            ], 401);
        }

        if(!Hash::check($request->code,$user->second_factory_token_admin)){
            return response()->json([
                'mensaje' => 'Codigo invalido',
                'success' => false
            ], 401);
        }

        $random = sprintf("%04d", rand(0, 9999));
        $codigoMovil = strval($random); //convertir a string
        $codigo_hash = Hash::make($codigoMovil); 
        //Guardarlo en BD 
        $user->second_factory_token = $codigo_hash;
        $user->save();


        return response()->json([
            'mensaje' => 'logeado',
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
            'codigoMovil' => $codigoMovil,
            'success' => true
        ], 200);
    }

    public function generateNewCode(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        $random = sprintf("%04d", rand(0, 9999));
        $codigoMovil = strval($random); 
        $codigo_hash = Hash::make($codigoMovil); 
        //Guardarlo en BD 
        $user->second_factory_token = $codigo_hash;
        $user->save();

        return response()->json([
            'mensaje' => 'Codigo generado',
            'codigoMovil' => $codigoMovil,
            'success' => true
        ], 200);



    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'mensaje' => 'Log out',
            'success' => true
        ]);
    }

    public function verificarCodigo(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'codigo' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'success' => false
            ], 401);
        }

        //Buscar al usuario
        $user = $request->user();

        //Verificar si el codigo que se mando por correo y se introdujo a la app es correcto
        if (Hash::check($request->codigo,$user->second_factory_token_admin)) {
            // Generar Codigo Movil, que introducira en la app web
            // Generar numero aleatorio, convertirlo a string y hashear
            $random = sprintf("%04d", rand(0, 9999));
            $codigoMovil = strval($random); //convertir a string
            $codigo_hash = Hash::make($codigoMovil); 
            //Guardarlo en BD 
            $user->second_factory_token = $codigo_hash;
            $user->save();
            return response()->json([
                'mensaje' => 'Codigo verificado',
                'codigoMovil' => $codigoMovil,
                'success' => true
            ], 200);
        }

        return response()->json([
            'mensaje' => 'Codigo invalido',
            'success' => false
        ], 401);
    }
}
