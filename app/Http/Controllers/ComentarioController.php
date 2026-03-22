<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ComentarioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'prenda_id' => 'required|exists:prendas,id',
            'comentario' => 'required|string|min:10|max:1000',
            'calificacion' => 'required|integer|min:1|max:5',
        ], [
            'comentario.required' => 'El comentario es obligatorio.',
            'comentario.min' => 'El comentario debe tener al menos 10 caracteres.',
            'calificacion.required' => 'Debes seleccionar una calificación.',
        ]);

        $user = Auth::user();
        $prendaId = $request->prenda_id;

        // Validar si el usuario ha comprado el producto
        $haComprado = Order::where('user_id', $user->id)
            ->where('estado_pago', 'pagado')
            ->whereHas('items', function ($query) use ($prendaId) {
                $query->where('prenda_id', $prendaId);
            })->exists();

        if (!$haComprado) {
            return back()->with('error', 'Debes comprar este producto para poder comentar.');
        }

        // Evitar múltiples comentarios del mismo usuario para el mismo producto
        $yaComento = Comentario::where('user_id', $user->id)
            ->where('prenda_id', $prendaId)
            ->exists();

        if ($yaComento) {
            return back()->with('error', 'Ya has dejado una opinión para este producto.');
        }

        try {
            Comentario::create([
                'user_id' => $user->id,
                'prenda_id' => $prendaId,
                'comentario' => $request->comentario,
                'calificacion' => $request->calificacion,
            ]);

            return back()->with('success', '¡Gracias por tu opinión! Se ha publicado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error guardando comentario: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado al publicar tu comentario.');
        }
    }
}
