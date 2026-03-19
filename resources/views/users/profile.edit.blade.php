<x-layout>
    <div class="container">
        <div class="row justify-content-center mt-4">

            <div class="col-md-8 col-lg-6">

                <h2 class="mb-4 text-center">Editar Perfil</h2>

                {{-- MENSAJES --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">

                        {{-- FOTO DE PERFIL --}}
                        <div class="text-center mb-3">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                     class="rounded-circle shadow-sm"
                                     width="120" height="120"
                                     style="object-fit: cover;"
                                     alt="Foto de perfil">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=120"
                                     class="rounded-circle shadow-sm"
                                     alt="Avatar">
                            @endif
                        </div>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $user->email) }}" required>
                            </div>

                            {{-- 🔐 CONTRASEÑA ACTUAL --}}
                            <div class="mb-3">
                                <label class="form-label">Contraseña actual (obligatoria si quieres cambiarla)</label>
                                <input type="password" name="current_password" class="form-control">
                            </div>

                            {{-- 🔐 NUEVA CONTRASEÑA --}}
                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña (opcional)</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmar nueva contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nueva foto de perfil</label>
                                <input type="file" name="profile_photo" class="form-control" accept="image/*">
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <a href="{{ route('inicio') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>

                                <button type="submit" class="btn btn-primary">
                                    Guardar cambios
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
