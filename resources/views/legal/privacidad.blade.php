@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/legal/legal.css') }}">
@endpush

@section('content')
<div class="legal-section">
    <div class="container">
        <div class="legal-card">
            <h1>Políticas y Privacidad</h1>
            <p>Última actualización: {{ date('d/m/Y') }}</p>

            <h2>1. Información que recopilamos</h2>
            <p>En MoveOn Sport, nos tomamos muy en serio tu privacidad. Recopilamos información personal necesaria para procesar tus pedidos y mejorar tu experiencia de compra, como:</p>
            <ul>
                <li>Nombre completo</li>
                <li>Dirección de correo electrónico</li>
                <li>Dirección de envío</li>
                <li>Información de contacto (teléfono)</li>
            </ul>

            <h2>2. Uso de la información</h2>
            <p>Tu información se utiliza exclusivamente para:</p>
            <ul>
                <li>Gestionar y enviar tus pedidos.</li>
                <li>Procesar tus pagos de forma segura.</li>
                <li>Enviarte actualizaciones sobre el estado de tu compra.</li>
                <li>Mejorar nuestros servicios y productos.</li>
            </ul>

            <h2>3. Protección de datos</h2>
            <p>Implementamos medidas de seguridad robustas, incluyendo encriptación SSL, para proteger tus datos personales contra el acceso no autorizado o la divulgación.</p>

            <h2>4. Cookies</h2>
            <p>Nuestro sitio utiliza cookies para recordar tus preferencias y los productos en tu carrito, facilitando una navegación más fluida.</p>

            <h2>5. Contacto</h2>
            <p>Si tienes dudas sobre nuestras políticas de privacidad, puedes contactarnos en: <strong>Moveonsport720@gmail.com</strong></p>
        </div>
    </div>
</div>
@endsection
