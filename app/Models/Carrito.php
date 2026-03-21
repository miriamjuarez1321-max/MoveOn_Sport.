<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $fillable = [
        'user_id',
        'prenda_id',
        'cantidad',
        'talla',
    ];

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
