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

class AuthController extends Controller
{
    //Registro
    public function register(Request $request)
    {
        
        $time = now();
        //Validacion del token de reCaptcha
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
            "secret" => '6LdOYV8pAAAAAL4MQQ1IaFhb-PYvkNuIOjeMsdbd',
            "response"=> $request->input('g-recaptcha-response'),
        ])->object();
        if($response->success && $response->score >= 0.7) {
            //Validamos los datos de entrada
            $validator =  Validator::make(
                $request->all(),
                [
                    "name" => "required|min:3|max:50",
                    "email" => "required|email|unique:users,email|max:50",
                    "password" => "required|min:8|max:50",
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
                ]
            );
            if ($validator->fails()) {
                Log::channel('errors')->info('Informacion: Intento de registro Error: validaciones' . ' Fecha:('.$time.')');
                return response()->json([
                    'error' => $validator->errors()
                ], 422);
            } else {
                // Crear usuario
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                // Checa si hay usuarios en la tabla
                $users = User::count();
                if ($users == 0) {
                    // Si no hay le da el rol de admin
                    $user->role = 1;
                } else {
                    // Si hay le da el rol de usuario comun
                    $user->role = 2;
                }
                // Guardar al usuario 
                if($user->save()){
                    Log::channel('infos')->info('Informacion: Un usuario se registro' . ' Usuario: '. $user . ' Fecha:('.$time.')');
                    // Se envia el email
                    $url = URL::temporarySignedRoute('verificar-email',now()->addDays(1),
                    ['id'=>$user->id]);
                    // Mail::to($user->email)->send(new ActivarUsuario($user,$url));
                    Verificar_Correo::dispatch($user,$url)
                    ->onQueue('email')
                    ->onConnection('database');
                    return response()->json([
                        'message' => ' Usuario registrado con éxito'
                    ],201);
                } else {
                    Log::channel('errors')->info('Informacion: Intento de registro Error: error al guardar el usuario' . ' Fecha:('.$time.')');
                    return response()->json([
                        'error' => 'Error al crear el usuario, intentelo mas tarde'
                    ], 400);
                }
            }
        } else {
            Log::channel('errors')->info('Informacion: Un robot a aparecido' . ' Fecha:('.$time.')');
            return response()->json([
                'error' => 'No eres humano'
            ], 400);
        }
    }

    //Login
    public function login(Request $request)
    {
        $time = now();
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
            "secret" => '6LdOYV8pAAAAAL4MQQ1IaFhb-PYvkNuIOjeMsdbd',
            "response"=> $request->input('g-recaptcha-response'),
        ])->object();
        if($response->success && $response->score >= 0.7) {
            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email|max:50',
                    'password' => 'required|min:8|max:50'
                ],
                [
                    'email.required' => 'El email es requerido',
                    'email.email' => 'El email debe ser válido',
                    'password.required' => 'La contraseña es requerida',
                    'password.min' => 'La contraseña debe tener al menos 8 caracteres',
                    'password.max' => 'La contraseña debe tener máximo 25 caracteres',
                ]
            );
            if($validator->fails()) {
                Log::channel('errors')->info('Informacion: Alguien intento iniciar sesion Error: validaciones' . ' Fecha:('.$time.')');
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                $user = User::where('email','=',$request->email)->first();
                if($user && Hash::check($request->password,$user->password)){
                    if($user->status != 0){
                        $verificationCode = mt_rand(100000, 999999);
                        $user->second_factory_token = Hash::make($verificationCode); 
                        $user->save();
                        $url = URL::temporarySignedRoute('verificarCodigo', now()->addMinutes(10), ['user' => $user->id]);
                        Verificacion_Dos_Pasos::dispatch($user,$verificationCode)
                        ->onQueue('email')
                        ->onConnection('database')
                        ->delay(now()->addSeconds(10));
                        // if($user->role != 1){
                        //     Log::channel('infos')->info('Informacion: Un usuario esta intentando iniciar sesion' . ' Usuario: '. $user . ' Fecha:('.$time.')');
                        //     $verificationCode = mt_rand(100000, 999999);
                        //     $user->second_factory_token = Hash::make($verificationCode); 
                        //     $user->save();
                        //     $url = URL::temporarySignedRoute('verificarCodigo', now()->addMinutes(10), ['user' => $user->id]);
                        //     Verificacion_Dos_Pasos::dispatch($user,$verificationCode)
                        //     ->onQueue('email')
                        //     ->onConnection('database')
                        //     ->delay(now()->addSeconds(10));
                        // } else {
                        //     Log::channel('infos')->info('Informacion: Un usuario administrador esta intentando iniciar sesion' . ' Usuario: '. $user . ' Fecha:('.$time.')');
                        //     $verificationCode = mt_rand(100000, 999999);
                        //     $user->second_factory_token_admin = Hash::make($verificationCode); 
                        //     $user->save();
                        //     $url = URL::temporarySignedRoute('verificarCodigo', now()->addMinutes(10), ['user' => $user->id]);
                        //     verificacion_dos_pasos_admin::dispatch($user,$verificationCode)
                        //     ->onQueue('email')
                        //     ->onConnection('database')
                        //     ->delay(now()->addSeconds(10));
                        // }
                        return response()->json([
                            'message' => ' Usuario logeado con éxito',
                            'url' => $url
                        ],201);
                    } else {
                        return response()->json([
                            'error' => 'Cuenta desactivada'
                        ], 400);
                    }
                } else {
                    return response()->json([
                        'error' => 'Credenciales incorrectas'
                    ], 400);
                }
            }
        } else {
            Log::channel('errors')->info('Informacion: Un robot a aparecido' . ' Fecha:('.$time.')');
            return response()->json([
                'error' => 'No eres humano'
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    public function verificarEmail(Request $request)
    {
        $time = now();
        $user = User::find($request->id);
        Log::channel('infos')->info('Informacion: Un usuario verifico su correo electronico' . ' Usuario: '. $user . ' Fecha:('.$time.')');
        $user->status = 1;
        $user->save();
        
        return View('verificado');
    }

    public function reenviarCorreo(Request $request,$id)
    {
        $time = now();
        $user = User::find($id);
        $url = URL::temporarySignedRoute('verificar-email',now()->addMinutes(5),
        ['id'=>$user->id]);
        Verificar_Correo::dispatch($user,$url)
        ->onQueue('email')
        ->onConnection('database')
        ->delay(now()->addSeconds(30));

        
        return View('welcome');
    }

    public function verificarUsuario(Request $request)
    {
        $time = now();
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify',[
            "secret" => '6LdOYV8pAAAAAL4MQQ1IaFhb-PYvkNuIOjeMsdbd',
            "response"=> $request->input('g-recaptcha-response'),
        ])->object();
        if($response->success && $response->score >= 0.7) {
            $validator = Validator::make(
                $request->all(),
                [
                    'code' => 'required|max:6',
                ],
                [
                    'code.required' => 'El codigo es requerido',
                    'code.max' => 'Codigo incorrecto',
                ]
            );
            if($validator->fails()) {
                Log::channel('errors')->info('Informacion: Alguien intento iniciar como administrador Error: validaciones' . ' Fecha:('.$time.')');
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                $user = User::find($request->id_user);
                if($user){
                    if(Hash::check($request->code,$user->second_factory_token)){
                        Log::channel('infos')->info('Informacion: Un usuario administrador inicio sesion' . ' Usuario: '. $user . ' Fecha:('.$time.')');
                        $user->second_factory_token = null;
                        $user->save();
                        Auth::login($user);
                        return response()->json([
                            'message' => ' Usuario verificado'
                        ], 201);    
                    } else {
                        Log::channel('errors')->info('Informacion: Alguien intento iniciar como administrador Error: Codigo equivocado' . ' Fecha:('.$time.')');
                        return response()->json([
                            'error' => 'Codigo incorrecto'
                        ], 400);    
                    }
                } else {
                    Log::channel('errors')->info('Informacion: Alguien intento iniciar como administrador Error: Usuario sin permiso' . ' Fecha:('.$time.')');
                    return response()->json([
                        'error' => 'No tienes permiso'
                    ], 400);
                }
            }
        } else {
            Log::channel('errors')->info('Informacion: Un robot a aparecido' . ' Fecha:('.$time.')');
            return response()->json([
                'error' => 'No eres humano'
            ], 400);
        }
    }

    public function reenviarCodigo($id){
        $user = User::find($id);
        if($user){
            $verificationCode = mt_rand(100000, 999999);
            $user->second_factory_token = $verificationCode; 
            $user->save();
            Verificacion_Dos_Pasos::dispatch($user)
            ->onQueue('email')
            ->onConnection('database')
            ->delay(now()->addSeconds(10));
            return response()->json([
                'message' => 'Codigo enviado correctamente'
            ],201);
        } else {
            return response()->json([
                'error' => 'Usuario no encontrado'
            ],400);
        }
    }

    public function codigoAdmin(Request $request){
        $time = now();
        $validator = Validator::make(
            $request->all(),
            [
                'code' => 'required|max:6',
            ],
            [
                'code.required' => 'El codigo es requerido',
                'code.max' => 'Codigo incorrecto',
            ]
        );
        if($validator->fails()) {
            Log::channel('errors')->info('Informacion: Alguien intento iniciar como administrador Error: validaciones' . ' Fecha:('.$time.')');
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            $user = User::find(1);
            if($user){
                if(Hash::check($request->code,$user->second_factory_token_admin)){
                    Log::channel('infos')->info('Informacion: Un usuario administrador inicio sesion' . ' Usuario: '. $user . ' Fecha:('.$time.')');
                    $user->second_factory_token_admin = null;
                    $verificationCode = mt_rand(100000, 999999);
                    $user->second_factory_token = Hash::make($verificationCode); 
                    $user->save();
                    Verificacion_Dos_Pasos::dispatch($user,$verificationCode)
                    ->onQueue('email')
                    ->onConnection('database')
                    ->delay(now()->addSeconds(10));
                    return response()->json([
                        'message' => ' Usuario administrador verficado 1'
                    ], 201);    
                } else {
                    Log::channel('errors')->info('Informacion: Alguien intento iniciar como administrador Error: Codigo equivocado' . ' Fecha:('.$time.')');
                    return response()->json([
                        'error' => 'Codigo incorrecto'
                    ], 400);    
                }
            } else {
                Log::channel('errors')->info('Informacion: Alguien intento iniciar como administrador Error: Usuario sin permiso' . ' Fecha:('.$time.')');
                return response()->json([
                    'error' => 'No tienes permiso'
                ], 400);
            }
        }
    }
}
