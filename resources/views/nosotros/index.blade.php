@extends('layouts.app')

@section('content')

<section class="about-hero">
    <div class="hero-bg-overlay"></div>
    <div class="hero-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    <div class="about-text">
        <h1 class="animate-up">Somos <br><span class="gradient-text">MoveOn Sport</span></h1>
        <p class="animate-up-delay">
            Diseño y rendimiento para un estilo de vida activo.
        </p>
    </div>
</section>

<section class="about-story">
    <div class="story-img">
        <img src="/images/nosotros/brand.jpg" alt="MoveOn Brand">
    </div>

    <div class="story-text">
        <h2>Nuestra Historia</h2>
        <p>
            MoveOn Sport nace con el objetivo de ofrecer ropa con identidad y propósito.
            Creamos una marca enfocada en calidad, diseño y funcionalidad.
        </p>
        <p>
            Diseñamos prendas para personas activas que buscan comodidad y estilo en su día a día.
        </p>
    </div>
</section>

<section class="about-values">
    <h2>Lo que nos define</h2>

    <div class="values-grid">
        <div class="value-card">
            <h3>Identidad</h3>
            <p>Vestimos carácter, no tendencias.</p>
        </div>

        <div class="value-card">
            <h3>Sostenibilidad</h3>
            <p>Producción consciente y materiales responsables.</p>
        </div>

        <div class="value-card">
            <h3>Disciplina</h3>
            <p>La constancia construye resultados.</p>
        </div>

        <div class="value-card">
            <h3>Visión</h3>
            <p>Miramos al futuro sin olvidar el origen.</p>
        </div>
    </div>
</section>

<section class="about-vision">
    <div class="vision-text">
        <h2>Nuestra Visión</h2>
        <p>
            Convertir MoveOn en una marca referente
            en moda sostenible, deporte y cultura urbana.
        </p>
        <p>
            Una marca que represente mentalidad fuerte,
            conciencia ecológica y estética elegante.
        </p>
    </div>

    <div class="vision-img">
        <img src="/images/nosotros/imagen.png" alt="Visión MoveOn">
    </div>
</section>

@endsection
