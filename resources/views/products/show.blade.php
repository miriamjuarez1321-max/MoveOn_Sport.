@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/collections.css') }}">
<link rel="stylesheet" href="{{ asset('css/products/show.css') }}">
@endpush

@section('content')
<!-- Breadcrumbs Row -->
<div class="breadcrumb-row">
    <div class="container d-flex align-items-center">
        @include('partials.back-button', ['without_container' => true])
        
        <div class="breadcrumb-nav-links">
            <a href="{{ route('collections.hombre') }}" class="btn-cat-nav {{ $prenda->categoria == 'hombre' ? 'active' : '' }}">
                Hombre
            </a>
            <a href="{{ route('collections.mujer') }}" class="btn-cat-nav {{ $prenda->categoria == 'mujer' ? 'active' : '' }}">
                Mujer
            </a>
            <a href="{{ route('collections.accesorios') }}" class="btn-cat-nav {{ $prenda->categoria == 'accesorios' ? 'active' : '' }}">
                Accesorios
            </a>
        </div>
    </div>
</div>

<div class="product-page">
    <div class="container">
        <!-- Main Product Card -->
        <div class="product-main-card">
            <div class="row g-0 row-grid-main">
                <!-- Col 1: Main Image -->
                <div class="col-lg-6 col-md-12 main-img-col">
                    <div class="main-img-container">
                        <img id="mainProductImg" src="{{ asset('storage/' . $prenda->imagen) }}" alt="{{ $prenda->nombre }}">
                    </div>
                </div>

                <!-- Col 3: Product Info -->
                <div class="col-lg-3 col-md-12 info-section">
                    <div class="product-status" style="margin-top: 10px;">Nuevo</div>
                    <h1 class="product-name-title">{{ $prenda->nombre }}</h1>
                    
                    <div class="rating-line">
                        <span>4.9</span>
                        <div class="stars-box">
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <span style="color: var(--ml-text-gray)">(74)</span>
                    </div>

                    <div class="price-value">${{ number_format($prenda->precio_venta, 2) }}</div>
                    <span class="shipping-promo">Envío gratis a todo el país</span>

                    <!-- Color selector -->
                    <div class="variant-box">
                        <span class="variant-title">Color: <strong>{{ $prenda->color }}</strong></span>
                        <div class="color-select-box">
                            <img src="{{ asset('storage/' . $prenda->imagen) }}" alt="color">
                        </div>
                    </div>

                    <!-- Size selector -->
                    @if($prenda->categoria != 'accesorios')
                    <div class="variant-box">
                        <span class="variant-title">Talla: <strong id="selectedSizeText">Elige</strong></span>
                        <div class="size-options">
                            @foreach(explode(',', $prenda->talla) as $talla)
                                @php $talla = trim($talla); @endphp
                                <div class="size-btn" onclick="selectSize('{{ $talla }}', this)">{{ $talla }}</div>
                            @endforeach
                        </div>
                        <a href="#" class="size-guide-btn" data-bs-toggle="modal" data-bs-target="#guideModal">
                            <i class="bi bi-rulers"></i> Guía de tallas
                        </a>
                    </div>
                    @endif

                    <!-- Key points -->
                    <div class="key-points-section">
                        <h4 class="key-points-title">Lo que tienes que saber de este producto</h4>
                        <ul class="key-points-list">
                            @if($prenda->categoria == 'accesorios')
                                <li>Diseño versátil y funcional.</li>
                                <li>Materiales de alta durabilidad.</li>
                                <li>Ideal para complementar tu equipo deportivo.</li>
                                <li>Fácil mantenimiento y limpieza.</li>
                            @else
                                <li>Diseño de alto rendimiento: {{ $prenda->categoria }}.</li>
                                <li>Material transpirable Dry-Fit.</li>
                                <li>Ideal para entrenamiento intensivo.</li>
                                <li>Tecnología de costura plana para evitar roces.</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Col 4: Buy Box -->
                <div class="col-lg-3 col-md-12 buy-box-col">
                    <div class="buy-box-card">
                        @if($prenda->stock > 0)
                            <span class="shipping-badge">Llega gratis el jueves</span>
                            <span class="shipping-subtext">por ser tu primera compra</span>
                            
                            <span class="return-badge">Devolución gratis</span>
                            <span class="return-subtext">Tienes 30 días desde que lo recibes.</span>

                            <span class="stock-available">
                                @if($prenda->stock > 10)
                                    Stock disponible
                                @elseif($prenda->stock <= 5)
                                    <span class="text-danger">¡Pocas unidades disponibles!</span>
                                @else
                                    Stock disponible
                                @endif
                            </span>
                            
                            <div class="qty-select-inline">
                                <span>Cantidad:</span>
                                <select id="buyQty">
                                    @for($i = 1; $i <= min($prenda->stock, 10); $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'unidad' : 'unidades' }}</option>
                                    @endfor
                                </select>
                                <span class="qty-available-hint">({{ $prenda->stock }} disponibles)</span>
                            </div>

                            <button class="btn-buy-now-blue">Comprar ahora</button>
                            
                            <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
                                @csrf
                                <input type="hidden" name="prenda_id" value="{{ $prenda->id }}">
                                <input type="hidden" name="quantity" id="finalQty" value="1">
                                <input type="hidden" name="talla" id="selectedSizeInput" value="">
                                <button type="submit" class="btn-add-cart-light">Agregar al carrito</button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold mt-3">Producto agotado</h4>
                                <p class="text-muted small">Este producto no está disponible por el momento. ¡Vuelve pronto!</p>
                                <button class="btn btn-secondary w-100 mt-3" disabled>Añadir al carrito</button>
                            </div>
                        @endif

                        <div class="official-store-info">
                            <div class="store-logo">
                                <img src="{{ asset('images/logo/logo.png') }}" alt="MoveOn Logo">
                            </div>
                            <div>
                                <span class="store-name-text">Tienda oficial MoveOn Sport <i class="bi bi-patch-check-fill" style="color: var(--ml-blue); font-size: 0.8rem;"></i></span>
                                <div style="font-size: 0.75rem; color: var(--ml-text-gray);">+100mil ventas</div>
                            </div>
                        </div>

                        <div style="margin-top: 20px; font-size: 0.8rem; color: var(--ml-text-gray);">
                            <div class="mb-2"><i class="bi bi-shield-check" style="color: var(--ml-blue);"></i> <strong>Compra Protegida.</strong> Recibe el producto que esperabas o te devolvemos tu dinero.</div>
                            <div><i class="bi bi-award" style="color: var(--ml-blue);"></i> <strong>Garantía.</strong> 12 meses de garantía de fábrica.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description and Specs -->
        <div class="bottom-specs-card">
            <h2 class="bottom-title">Descripción</h2>
            <div style="font-size: 1.1rem; color: var(--ml-text-gray); line-height: 1.6; margin-bottom: 40px; white-space: pre-line;">
                {{ $prenda->descripcion }}
            </div>

            <h2 class="bottom-title">Especificaciones del producto</h2>
            <table class="specs-table-ml">
                <tr><th>Marca</th><td>MoveOn Sport</td></tr>
                <tr><th>Modelo</th><td>Performance Elite 2026</td></tr>
                @if($prenda->categoria == 'accesorios')
                    <tr><th>Material</th><td>Materiales sintéticos de alta resistencia</td></tr>
                    <tr><th>Tipo</th><td>Accesorio Deportivo</td></tr>
                    <tr><th>Uso</th><td>Entrenamiento / Casual</td></tr>
                @else
                    <tr><th>Material</th><td>Poliéster Reciclado / Elastano de alta calidad</td></tr>
                    <tr><th>Tipo de prenda</th><td>{{ ucfirst($prenda->categoria) }} Deportivo</td></tr>
                    <tr><th>Género</th><td>{{ ucfirst($prenda->categoria) }}</td></tr>
                    <tr><th>Usos recomendados</th><td>Running, Crossfit, Gym, Outdoor</td></tr>
                @endif
            </table>
        </div>
    </div>
</div>

<!-- Modal Guía de Tallas -->
<div class="modal fade" id="guideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 4px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Guía de tallas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr><th>Talla</th><th>Pecho</th><th>Cintura</th><th>Cadera</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><strong>CH</strong></td><td>88-94 cm</td><td>70-76 cm</td><td>90-96 cm</td></tr>
                        <tr><td><strong>M</strong></td><td>95-101 cm</td><td>77-83 cm</td><td>97-103 cm</td></tr>
                        <tr><td><strong>G</strong></td><td>102-108 cm</td><td>84-90 cm</td><td>104-110 cm</td></tr>
                        <tr><td><strong>XG</strong></td><td>109-115 cm</td><td>91-97 cm</td><td>111-117 cm</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function selectSize(size, el) {
        // Update hidden input and text
        document.getElementById('selectedSizeInput').value = size;
        document.getElementById('selectedSizeText').innerText = size;
        
        // Update active class
        document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
        el.classList.add('active');
    }

    document.getElementById('buyQty').addEventListener('change', function() {
        document.getElementById('finalQty').value = this.value;
    });

    // Validation before adding to cart
    @if($prenda->categoria != 'accesorios')
    document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
        const selectedSize = document.getElementById('selectedSizeInput').value;
        if (!selectedSize) {
            e.preventDefault();
            alert('Por favor, selecciona una talla antes de agregar al carrito.');
        }
    });
    @endif
</script>
@endsection
