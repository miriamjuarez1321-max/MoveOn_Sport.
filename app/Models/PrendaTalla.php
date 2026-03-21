<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrendaTalla extends Model
{
    protected $fillable = [
        'prenda_id',
        'talla',
        'stock',
    ];

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
