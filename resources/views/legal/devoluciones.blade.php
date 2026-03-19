@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal/legal.css') }}">
@endpush

@section('content')
<div class="legal-section">
    <div class="container">
        <div class="legal-card">
            <h1>Cambios y Devoluciones</h1>
            <p>Última actualización: {{ date('d/m/Y') }}</p>

            <h2>1. Condiciones para Cambios</h2>
            <p>Aceptamos cambios de prendas en un plazo de <strong>30 días naturales</strong> después de la compra, siempre que cumplan los siguientes requisitos:</p>
            <ul>
                <li>La prenda debe estar en su estado original, sin usar y con las etiquetas puestas.</li>
                <li>Debes presentar tu comprobante de compra o número de orden.</li>
                <li>Los cambios están sujetos a disponibilidad de stock.</li>
            </ul>

            <h2>2. Proceso de Devolución</h2>
            <p>Si deseas devolver un producto para obtener un reembolso:</p>
            <ul>
                <li>Cuentas con <strong>15 días naturales</strong> desde la recepción del pedido.</li>
                <li>El reembolso se procesará a través del mismo método de pago utilizado.</li>
                <li>Los gastos de envío por devolución corren por cuenta del cliente, a menos que el producto presente defectos de fabricación.</li>
            </ul>

            <h2>3. Productos No Retornables</h2>
            <p>Por razones de higiene, no se aceptan cambios ni devoluciones en ropa interior ni calcetines, a menos que el empaque esté sellado y el producto defectuoso.</p>

            <h2>4. Garantía</h2>
            <p>Todos nuestros productos cuentan con garantía contra defectos de fábrica por un periodo de 60 días.</p>

            <h2>5. Contacto</h2>
            <p>Para iniciar un proceso de cambio o devolución, escríbenos a: <strong>Moveonsport720@gmail.com</strong></p>
        </div>
    </div>
</div>
@endsection
