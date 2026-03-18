@extends('layouts.app')

@section('content')

<!-- HERO / INICIO -->
<section id="inicio" class="hero">
    <div class="hero-text">
        <h1>MoveOn Sport </h1>
        <p>
            Donde el estilo se convierte en identidad.
            Donde la moda deja de seguir tendencias y empieza a crearlas.
        </p>

        <div class="hero-buttons">
            <a href="{{ route('collections') }}" class="btn-primary">Explorar colección</a>
            <a href="#esencia" class="btn-outline">Ver más</a>
        </div>
    </div>

    <div class="hero-imgs">
        <div class="hero-slider">
            <div class="hero-track">
                <img src="{{ asset('images/home/imagen1.jpeg') }}" alt="MoveOn look 1">
                <img src="{{ asset('images/home/imagen2.jpeg') }}" alt="MoveOn look 2">
                <img src="{{ asset('images/collections/hombre.jpeg') }}" alt="Hombre Collection">
                <img src="{{ asset('images/collections/mujer.jpeg') }}" alt="Mujer Collection">
                <!-- Duplicados para el loop infinito suave -->
                <img src="{{ asset('images/home/imagen1.jpeg') }}" alt="MoveOn look 1">
                <img src="{{ asset('images/home/imagen2.jpeg') }}" alt="MoveOn look 2">
                <img src="{{ asset('images/collections/hombre.jpeg') }}" alt="Hombre Collection">
                <img src="{{ asset('images/collections/mujer.jpeg') }}" alt="Mujer Collection">
            </div>
        </div>
    </div>
</section>

<!-- NUESTRA ESENCIA -->
<section id="esencia">
    <h2 class="section-title">Nuestra esencia</h2>

    <div class="cards">
        <div class="card">
            <h3>Identidad</h3>
            <p>Moda que representa quién eres, no lo que te imponen.</p>
        </div>

        <div class="card">
            <h3>Calidad</h3>
            <p>Diseños pensados para durar, no para una temporada.</p>
        </div>

        <div class="card">
            <h3>Estilo</h3>
            <p>Elegancia moderna con esencia urbana.</p>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
window.addEventListener('load', function() {
    const track = document.querySelector('.hero-track');
    if (!track) return;
    const images = track.querySelectorAll('img');
    const totalImages = images.length / 2;
    let index = 0;
    let isPaused = false;
    let interval;

    function moveSlider() {
        if (isPaused) return;
        
        index++;
        const imgWidth = images[0].offsetWidth + 20; 
        track.style.transition = 'transform 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
        track.style.transform = `translateX(-${index * imgWidth}px)`;

        if (index >= totalImages) {
            setTimeout(() => {
                track.style.transition = 'none';
                index = 0;
                track.style.transform = `translateX(0)`;
            }, 800);
        }
    }

    function startSlider() {
        interval = setInterval(moveSlider, 3500);
    }

    track.addEventListener('mouseenter', () => {
        isPaused = true;
    });

    track.addEventListener('mouseleave', () => {
        isPaused = false;
    });

    startSlider();

    window.addEventListener('resize', () => {
        track.style.transition = 'none';
        index = 0;
        track.style.transform = `translateX(0)`;
    });
});
</script>
@endpush