<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $table = 'books';
    
    use HasFactory;
    
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
    public function editorial(): BelongsTo
    {
        return $this->belongsTo(Editorial::class);
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
