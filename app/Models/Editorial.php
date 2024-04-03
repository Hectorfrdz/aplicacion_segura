<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editorial extends Model
{
    protected $table = 'editorials';

    use HasFactory;

    public function books()
    {
        return $this->hasMany(Book::class, 'editorial');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($editorial) {
            // Verificar si el autor tiene libros asociados
            if ($editorial->books()->count() > 0) {
                // Obtener el autor "desconocido"
                $unknownEditorial = Editorial::find(1);

                // Asignar los libros del autor a "desconocido"
                $editorial->books()->update(['editorial_id' => $unknownEditorial->id]);
            }
        });
    }
}
