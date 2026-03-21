<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $table = 'comentarios';
    
    protected $fillable = [
        'user_id',
        'prenda_id',
        'comentario',
        'calificacion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prenda()
    {
        return $this->belongsTo(Prenda::class);
    }
}
