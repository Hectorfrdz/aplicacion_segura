<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'authors';
    
    use HasFactory;

    public function books()
    {
        return $this->hasMany(Book::class, 'author');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($author) {
            // Verificar si el autor tiene libros asociados
            if ($author->books()->count() > 0) {
                // Obtener el autor "desconocido"
                $unknownAuthor = Author::find(1);

                // Asignar los libros del autor a "desconocido"
                $author->books()->update(['author' => $unknownAuthor->id]);
            }
        });
    }
}
