@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal/legal.css') }}">
@endpush

@section('content')
<div class="legal-section">
    <div class="container">
        <div class="legal-card">
            <h1>Términos y Condiciones</h1>
            <p>Última actualización: {{ date('d/m/Y') }}</p>

            <h2>1. Generalidades</h2>
            <p>Al acceder y utilizar el sitio web de MoveOn Sport, aceptas cumplir con los siguientes términos y condiciones. Nos reservamos el derecho de modificar estos términos en cualquier momento.</p>

            <h2>2. Uso del Sitio</h2>
            <p>Este sitio web está destinado a la venta de ropa deportiva. Queda prohibido el uso del contenido para fines comerciales no autorizados o actividades ilícitas.</p>

            <h2>3. Precios y Pagos</h2>
            <p>Todos los precios se muestran en Pesos Mexicanos (MXN). Nos esforzamos por mantener la exactitud en los precios, pero nos reservamos el derecho de corregir errores tipográficos. Aceptamos pagos vía Stripe, PayPal y Transferencia Bancaria.</p>

            <h2>4. Propiedad Intelectual</h2>
            <p>El logotipo, diseño, textos e imágenes de MoveOn Sport son propiedad exclusiva de la marca. Queda prohibida su reproducción total o parcial sin consentimiento previo por escrito.</p>

            <h2>5. Limitación de Responsabilidad</h2>
            <p>MoveOn Sport no se hace responsable por daños indirectos derivados del uso de este sitio web o la imposibilidad de acceder al mismo.</p>

            <h2>6. Jurisdicción</h2>
            <p>Cualquier controversia relacionada con estos términos se regirá bajo las leyes vigentes en Chiapas, México.</p>
        </div>
    </div>
</div>
@endsection
