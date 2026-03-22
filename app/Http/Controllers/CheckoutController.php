<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Carrito;
use App\Models\Prenda;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Services\StripeService;
use App\Services\PayPalService;

class CheckoutController extends Controller
{
    protected $stripe;
    protected $paypal;

    public function __construct(StripeService $stripe, PayPalService $paypal)
    {
        $this->stripe = $stripe;
        $this->paypal = $paypal;
    }

    public function index()
    {
        $user = auth()->user();
        $cartItems = $user->carritos()->with('prenda')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->prenda->precio_venta * $item->cantidad;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:stripe,paypal,transferencia',
        ]);

        $user = auth()->user();
        $cartItems = $user->carritos()->with('prenda')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->prenda->precio_venta * $item->cantidad;
        });

        try {
            // Validación de configuración antes de procesar
            if ($request->metodo_pago === 'stripe' && (empty(config('services.stripe.secret')) || str_contains(config('services.stripe.secret'), 'tu_clave'))) {
                throw new \Exception('La configuración de Stripe no es válida. Por favor, asegúrate de haber configurado las claves reales en el archivo .env');
            }
            if ($request->metodo_pago === 'paypal' && (empty(config('services.paypal.client_id')) || str_contains(config('services.paypal.client_id'), 'tu_client_id'))) {
                throw new \Exception('La configuración de PayPal no es válida. Por favor, asegúrate de haber configurado las claves reales en el archivo .env');
            }

            return DB::transaction(function () use ($request, $user, $cartItems, $total) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total' => $total,
                    'metodo_pago' => $request->metodo_pago,
                    'estado_pago' => 'pendiente_pago',
                ]);

                foreach ($cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'prenda_id' => $item->prenda_id,
                        'cantidad' => $item->cantidad,
                        'precio' => $item->prenda->precio_venta,
                        'talla' => $item->talla,
                    ]);

                    // Descontar stock de la talla específica
                    $tallaStock = $item->prenda->tallas()->where('talla', $item->talla)->first();
                    if ($tallaStock) {
                        $tallaStock->decrement('stock', $item->cantidad);
                    }
                }

                if ($request->metodo_pago === 'transferencia') {
                    $order->referencia_bancaria = 'ORD-' . strtoupper(Str::random(8));
                    $order->save();
                    $user->carritos()->delete();
                    return redirect()->route('checkout.success', $order->id);
                }

                if ($request->metodo_pago === 'stripe') {
                    $session = $this->stripe->createCheckoutSession($order);
                    $order->payment_id = $session->id;
                    $order->save();
                    return redirect($session->url);
                }

                if ($request->metodo_pago === 'paypal') {
                    $response = $this->paypal->createOrder($order);
                    if ($response->statusCode === 201) {
                        $order->payment_id = $response->result->id;
                        $order->save();
                        
                        foreach ($response->result->links as $link) {
                            if ($link->rel === 'approve') {
                                return redirect($link->href);
                            }
                        }
                    }
                    throw new \Exception('No se pudo crear la orden de PayPal.');
                }
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un error al procesar tu pedido: ' . $e->getMessage());
        }
    }

    public function stripeSuccess(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }

        $sessionId = $request->get('session_id');
        if (!$sessionId) return redirect()->route('checkout.index')->with('error', 'Sesión de pago no válida.');

        try {
            $session = $this->stripe->verifySession($sessionId);
            if ($session->payment_status === 'paid') {
                $order->estado_pago = 'pagado';
                $order->save();
                auth()->user()->carritos()->delete();
                return redirect()->route('checkout.success', $order->id);
            }
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', 'Error al verificar pago de Stripe.');
        }
    }

    public function paypalSuccess(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }

        $paypalOrderId = $request->get('token');
        if (!$paypalOrderId) return redirect()->route('checkout.index')->with('error', 'Token de PayPal no válido.');

        try {
            $response = $this->paypal->captureOrder($paypalOrderId);
            if ($response->result->status === 'COMPLETED') {
                $order->estado_pago = 'pagado';
                $order->save();
                auth()->user()->carritos()->delete();
                return redirect()->route('checkout.success', $order->id);
            }
        } catch (\Exception $e) {
            return redirect()->route('checkout.index')->with('error', 'Error al procesar pago de PayPal.');
        }
    }

    public function payment(Order $order, $method)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }
        return view('checkout.payment', compact('order', 'method'));
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return redirect('/')->with('error', 'Lo sentimos, no tienes permiso para acceder a esta sección.');
        }
        return view('checkout.success', compact('order'));
    }
}
