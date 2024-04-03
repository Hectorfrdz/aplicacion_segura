<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';
    
    use HasFactory;

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($genre) {
            // Verificar si el autor tiene libros asociados
            if ($genre->books()->count() > 0) {
                // Obtener el autor "desconocido"
                $unknownGenre = Genre::find(1);

                // Asignar los libros del autor a "desconocido"
                $genre->books()->update(['genre_id' => $unknownGenre->id]);
            }
        });
    }
}
