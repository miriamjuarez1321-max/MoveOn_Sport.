<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserOrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['items.prenda'])
            ->latest()
            ->paginate(10);

        $hasPendingTransfer = Order::where('user_id', $user->id)
            ->where('metodo_pago', 'transferencia')
            ->where('estado_pago', 'pendiente_pago')
            ->exists();

        $hasRejectedTransfer = Order::where('user_id', $user->id)
            ->where('metodo_pago', 'transferencia')
            ->where('estado_pago', 'rechazado')
            ->exists();

        // Obtener IDs de productos ya comentados por el usuario
        $commentedProductIds = \App\Models\Comentario::where('user_id', $user->id)
            ->pluck('prenda_id')
            ->toArray();

        return view('orders.index', compact('orders', 'hasPendingTransfer', 'hasRejectedTransfer', 'commentedProductIds'));
    }

    public function uploadComprobante(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }
        
        $allowedStatuses = ['pendiente_pago', 'rechazado'];
        if ($order->metodo_pago !== 'transferencia' || !in_array($order->estado_pago, $allowedStatuses)) {
            return back()->with('error', 'No se puede subir comprobante para este pedido.');
        }

        $request->validate([
            'comprobante' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        try {
            if ($request->hasFile('comprobante')) {
                $path = $request->file('comprobante')->store('comprobantes', 'public');
                $order->update([
                    'comprobante_pago' => $path,
                    'estado_pago' => 'pendiente_verificacion',
                ]);

                return back()->with('success', '¡Comprobante enviado con éxito! Estamos validando tu pedido.');
            }

            return back()->with('error', 'Error al subir el archivo.');
        } catch (\Exception $e) {
            Log::error('Error subiendo comprobante de pago: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado al procesar tu comprobante.');
        }
    }
}
