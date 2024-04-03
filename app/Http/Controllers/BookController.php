<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Editorial;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function createBook(Request $request){
        $validator =  Validator::make(
            $request->all(),
            [
                "name" => "required|max:50",
                "ISBN" => "required|max:13|unique:books,ISBN",
                "author" => "required|exists:authors,id",
                "genre" => "required|exists:genres,id",
                "editorial" => "required|exists:editorials,id",
            ],
            [
                'name.required' => 'El nombre es requerido.',
                'name.max' => 'El nombre debe ser más corto.',
                'ISBN.required' => 'El ISBN es requerido.',
                'ISBN.max' => 'El ISBN debe ser de 13 dígitos.',
                'ISBN.unique' => 'El ISBN debe ser único.',
                'author.required' => 'El autor es requerido.',
                'author.exists' => 'El autor seleccionado no es válido.',
                'genre.required' => 'El género es requerido.',
                'genre.exists' => 'El género seleccionado no es válido.',
                'editorial.required' => 'La editorial es requerida.',
                'editorial.exists' => 'La editorial seleccionada no es válida.',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $book = new Book;
            $book->name = $request->name;
            $book->ISBN = $request->ISBN;
            $book->author_id = $request->author;
            $book->genre_id = $request->genre;
            $book->editorial_id = $request->editorial;
            if ($book->save()) {
                return response()->json(['message' => "Libro creado con exito"], 200);
            } else {
                return response()->json(['error' => "No se creo el libro"], 400);
            }
        }
    }
    public function updateBook(Request $request, $id){
        $book = Book::find($id);
        if($book){
            $validator =  Validator::make(
                $request->all(),
                [
                    "name" => "required|max:50",
                    "ISBN" => "required|max:13|unique:books,ISBN,".$book->id,
                    "author" => "required|exists:authors,id",
                    "genre" => "required|exists:genres,id",
                    "editorial" => "required|exists:editorials,id",
                ],
                [
                    'name.required' => 'El nombre es requerido.',
                    'name.max' => 'El nombre debe ser más corto.',
                    'ISBN.required' => 'El ISBN es requerido.',
                    'ISBN.max' => 'El ISBN debe ser de 13 dígitos.',
                    'ISBN.unique' => 'El ISBN debe ser único.',
                    'author.required' => 'El autor es requerido.',
                    'author.exists' => 'El autor seleccionado no es válido.',
                    'genre.required' => 'El género es requerido.',
                    'genre.exists' => 'El género seleccionado no es válido.',
                    'editorial.required' => 'La editorial es requerida.',
                    'editorial.exists' => 'La editorial seleccionada no es válida.',
                ]
            );
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            } else {
                $book->name = $request->name;
                $book->ISBN = $request->ISBN;
                $book->author_id = $request->author;
                $book->genre_id = $request->genre;
                $book->editorial_id = $request->editorial;
                if ($book->save()) {
                    return response()->json(['message' => "Libro actualizado con exito"], 200);
                } else {
                    return response()->json(['error' => "No se actualizo el libro"], 400);
                }
            }
        }else{
            return response()->json(['error' => "No se encontro el libro"], 400);
        }
    }
    public function deleteBook($id){
        $book = Book::find($id);
        if($book){
            $book->delete();
            return response()->json(['message' => "Libro eliminado con exito"], 200);
        }else{
            return response()->json(['error' => "No se encontro el libro"], 400);
        }
    }
    public function readBooks()
    {
        $books = Book::with('genre', 'editorial', 'author')->simplePaginate(10);
        return $books;
    }
    public function readBook($id)
    {
        $book = Book::with('genre','editorial','author')->find($id);
        if($book){
            return $book;
        } else {
            return response()->json(['error' => "No se encontró el libro"], 400);
        }
    }
}
