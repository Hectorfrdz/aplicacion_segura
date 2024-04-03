<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    public function createAuthor(Request $request){
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
            $author = new Author;
            $author->name = $request->name;
            if ($author->save()) {
                $authors = Author::simplePaginate(10);
                return response()->json([
                    'message' => "autor creado con exito",
                    'authors' => $authors
                ], 200);
            } else {
                return response()->json(['error' => "No se creo el autor"], 400);
            }
        }
    }
    public function updateAuthor(Request $request, $id){
        $author = Author::find($id);
        if($author){
            $validator =  Validator::make(
                $request->all(),
                [
                    "name" => "required|max:25",
                ],
                [
                    'name.required' => 'El nombre es requerido.',
                ]
            );
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                $author->name = $request->name;
                if ($author->save()) {
                    $authors = Author::simplePaginate(10);
                    return response()->json([
                        'message' => "autor actualizado con exito",
                        'authors' => $authors
                    ], 200);
                } else {
                    return response()->json(['error' => "No se actualizo el autor"], 400);
                }
            }
        }else{
            return response()->json(['error' => "No se encontro al autor"], 400);
        }
    }
    public function deleteAuthor($id){
        $author = Author::find($id);
        if($author){
            $author->delete();
            $authors = Author::simplePaginate(10);
            return response()->json([
                'message' => "autor eliminado con exito",
                'authors' => $authors
            ], 200);
        }else{
            return response()->json(['error' => "No se encontro al autor"], 400);
        }
    }
    public function readAuthors(){
        $authors = Author::simplePaginate(10);
        return view('authors', compact('authors'));
    }
    public function readAuthor($id){
        $author = Author::find($id);
        if($author){
            return $author;
        }else{
            return response()->json(['error' => "No se encontro al autor"], 400);
        }
    }
}
