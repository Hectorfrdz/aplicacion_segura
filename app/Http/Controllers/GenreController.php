<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    public function createGenre(Request $request){
       $validator = Validator::make(
            $request->all(),
            [
                "name" => "required|max:25",
            ],
            [
                'name.required' => 'El nombre es requerido.',
                'name.max' => 'El nombre debe ser más corto.',
            ]
        );
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $genre = new Genre;
            $genre->name = $request->name;
            if ($genre->save()) {
                return response()->json(['message' => "genero creado con exito"], 200);
            } else {
                return response()->json(['error' => "No se creo el genero"], 400);
            }
        }
    }
    public function updateGenre(Request $request, $id){
        $genre = Genre::find($id);
        if($genre){
            $validator = Validator::make(
                $request->all(),
                [
                    "name" => "required|max:25",
                ],
                [
                    'name.required' => 'El nombre es requerido.',
                    'name.max' => 'El nombre debe ser más corto.',
                ]
            );
        
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                $genre->name = $request->name;
                if ($genre->save()) {
                    return response()->json(['message' => "genero creado con exito"], 200);
                } else {
                    return response()->json(['error' => "No se actualizo el genero"], 400);
                }
            }
        }else{
            return response()->json(['error' => "No se encontro el genero"], 400);
        }
    }
    public function deleteGenre($id){
        $genre = Genre::find($id);
        if($genre){
            $genre->delete();
            return response()->json(['message' => "genero eliminado con exito"], 200);
        }else{
            return response()->json(['error' => "No se encontro el genero"], 400);
        }
    }
    public function readGenres(){
        $genres = Genre::simplePaginate(10);
        return view('genres', compact('genres'));
    }
    public function readGenre($id){
        $genre = Genre::find($id);
        if($genre){
            return $genre;
        }else{
            return response()->json(['error' => "No se encontro el genero"], 400);
        }
    }
}
