<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Carrito;
use App\Models\Prenda;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function add(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            return redirect()->guest(route('login'))->with('error', 'Debes iniciar sesión para realizar una compra.');
        }

        $request->validate([
            'prenda_id' => 'required|integer|exists:prendas,id',
            'quantity' => 'nullable|integer|min:1',
            'talla' => 'nullable|string',
        ]);

        try {
            $user_id = auth()->id();
            $prenda_id = $request->prenda_id;
            $cantidad = $request->input('quantity', 1);
            $talla = $request->talla;

            $prenda = Prenda::findOrFail($prenda_id);

            // Validar stock disponible
            if ($talla) {
                $tallaRecord = $prenda->tallas()->where('talla', $talla)->first();
                $stockDisponible = $tallaRecord ? $tallaRecord->stock : 0;
            } else {
                $stockDisponible = $prenda->stock;
            }

            if ($cantidad > $stockDisponible) {
                return back()->with('error', 'La cantidad solicitada supera el stock disponible (Máximo: ' . $stockDisponible . ').');
            }

            $cartItem = Carrito::where('user_id', $user_id)
                               ->where('prenda_id', $prenda_id)
                               ->where('talla', $talla)
                               ->first();

            if ($cartItem) {
                if (($cartItem->cantidad + $cantidad) > $stockDisponible) {
                    return back()->with('error', 'Ya tienes artículos en el carrito y la nueva cantidad supera el stock disponible.');
                }
                $cartItem->increment('cantidad', $cantidad);
            } else {
                Carrito::create([
                    'user_id'   => $user_id,
                    'prenda_id' => $prenda_id,
                    'cantidad'  => $cantidad,
                    'talla'     => $talla,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error en CartController@add: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado al añadir al carrito. Intenta nuevamente.');
        }

        if ($request->has('buy_now')) {
            return redirect()->route('checkout.index');
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => '¡Prenda añadida al carrito!',
                'cart_count' => Carrito::where('user_id', $user_id)->sum('cantidad')
            ]);
        }

        return redirect()->back()->with('success', '¡Prenda añadida al carrito!');
    }

    public function index()
    {
        try {
            $cartItems = auth()->user()->carritos()->with('prenda')->get();
            $total = $cartItems->sum(function($item) {
                return $item->prenda ? $item->prenda->precio_venta * $item->cantidad : 0;
            });

            return view('cart.index', compact('cartItems', 'total'));
        } catch (\Exception $e) {
            Log::error('Error en CartController@index: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'No se pudo cargar el carrito.');
        }
    }

    public function update(Request $request, Carrito $carrito)
    {
        if ($carrito->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }

        $request->validate(['cantidad' => 'required|integer|min:1']);

        try {
            $prenda = $carrito->prenda;
            $stockDisponible = $prenda->stock;
            if ($carrito->talla) {
                $tallaRecord = $prenda->tallas()->where('talla', $carrito->talla)->first();
                if ($tallaRecord) $stockDisponible = $tallaRecord->stock;
            }

            if ($request->cantidad > $stockDisponible) {
                return back()->with('error', 'Stock insuficiente para actualizar el carrito.');
            }

            $carrito->update(['cantidad' => $request->cantidad]);

            return redirect()->route('cart.index')->with('success', 'Cantidad actualizada.');
        } catch (\Exception $e) {
            Log::error('Error en CartController@update: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar el carrito.');
        }
    }

    public function remove(Carrito $carrito)
    {
        if ($carrito->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }

        try {
            $carrito->delete();
            return redirect()->route('cart.index')->with('success', 'Prenda eliminada del carrito.');
        } catch (\Exception $e) {
            Log::error('Error en CartController@remove: ' . $e->getMessage());
            return back()->with('error', 'Error al eliminar la prenda del carrito.');
        }
    }
}
