<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';
    
    use HasFactory;
    
    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
    public function editorial()
    {
        return $this->belongsTo(Editorial::class);
    }
    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
