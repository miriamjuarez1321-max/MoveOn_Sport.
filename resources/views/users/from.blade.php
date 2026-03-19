<x-layout>
    <div class="container">
        <h1>{{ isset($user) ? 'Editar Usuario' : 'Agregar Usuario' }}</h1>

        <form method="POST"
              action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}"
              enctype="multipart/form-data">

            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <input type="hidden" name="id" value="{{ $user->id ?? '' }}">

            {{-- Nombre --}}
            <div class="col-sm-8 col-md-8 col-lg-5">
                <label class="form-label">Nombre</label>
                <input name="name" type="text" class="form-control" 
                       value="{{ old('name', $user->name ?? '') }}" required maxlength="120">
            </div>

            {{-- Email --}}
            <div class="col-sm-8 col-md-6 col-lg-4 mt-3">
                <label class="form-label">Email</label>
                <input name="email" type="email" class="form-control" 
                       value="{{ old('email', $user->email ?? '') }}" required>
            </div>

            {{-- Contraseña --}}
            <div class="col-sm-6 col-md-6 col-lg-4 mt-3">
                <label class="form-label">
                    Contraseña 
                    @if(isset($user))
                        <small>(dejar vacío para no cambiar)</small>
                    @endif
                </label>
                <input name="password" type="password" class="form-control">
            </div>

            {{-- Confirmación --}}
            <div class="col-sm-6 col-md-6 col-lg-4 mt-3">
                <label class="form-label">Confirmar Contraseña</label>
                <input name="password_confirmation" type="password" class="form-control">
            </div>

            {{-- Foto de perfil --}}
            <div class="col-sm-6 col-md-6 col-lg-4 mt-3">
                <label class="form-label">Foto de Perfil</label>
                <input name="profile_photo" type="file" class="form-control" accept="image/*">

                @if(isset($user) && $user->profile_photo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $user->profile_photo) }}"
                             width="120" class="rounded border">
                    </div>
                @endif
            </div>

            {{-- Botones --}}
            <div class="col-12 mt-4">
                <button class="btn btn-primary">Guardar Usuario</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
            </div>
        </form>
    </div>
</x-layout>
