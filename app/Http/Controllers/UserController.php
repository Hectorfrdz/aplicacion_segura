<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function readUsers(){
        $users = User::all();
        return view('users', compact('users'));
    }
    public function readUser($id){
        $user = User::find($id);
        if($user){
            return $user;
        }else{
            return response()->json(['error' => "No se encontro al usuario"], 400);
        }
    }
}
