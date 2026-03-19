@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile/edit.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')

@include('partials.back-button')

<section class="profile-section">

    <div class="profile-header">
        <div class="profile-picture-container">
            @if(auth()->user()->profile_photo)
                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Foto de perfil" class="profile-picture">
            @else
                <span class="profile-initial">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            @endif
        </div>
        <h1>Mi Perfil</h1>
        <p>Actualiza tu información personal y configuración de seguridad.</p>
    </div>

    @if(session('success'))
        <div class="profile-alert">
            <strong>¡Éxito! </strong> {{ session('success') }}
        </div>
    @endif

    <div class="profile-card">
        <form id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="section-title">Información Básica</div>
            
            <div class="form-group">
                <label for="profile_photo">Foto de Perfil</label>
                <input type="file" id="profile_photo" name="profile_photo" class="form-control-file" accept="image/*">
                @error('profile_photo')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="divider"></div>

            <div class="section-title">Cambiar Contraseña</div>
            <p class="password-hint">Deja estos campos en blanco si no deseas cambiar tu contraseña.</p>

            <div class="form-group">
                <label for="current_password">Contraseña Actual</label>
                <div class="password-field-container">
                    <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Requerida solo si actualizas contraseña">
                    <span class="toggle-password" onclick="togglePasswordVisibility('current_password', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
                @error('current_password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <div class="password-field-container">
                        <input type="password" id="password" name="password" class="form-control">
                        <span class="toggle-password" onclick="togglePasswordVisibility('password', this)">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    <div id="password-requirements" class="mt-2 d-none">
                        <span id="req-length" class="requirement text-danger">• Mínimo 8 caracteres</span>
                        <span id="req-upper" class="requirement text-danger">• Al menos una letra mayúscula</span>
                        <span id="req-lower" class="requirement text-danger">• Al menos una letra minúscula</span>
                        <span id="req-number" class="requirement text-danger">• Al menos un número</span>
                        <span id="req-symbol" class="requirement text-danger">• Al menos un símbolo (!@#$%&*)</span>
                    </div>
                    @error('password')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <div class="password-field-container">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                        <span class="toggle-password" onclick="togglePasswordVisibility('password_confirmation', this)">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                    <span id="confirm-password-error" class="error-text d-none" style="color: #ef4444; font-size: 12px; margin-top: 5px; display: block;">Las contraseñas no coinciden.</span>
                </div>
            </div>

            <div class="mt-40">
                <button type="submit" id="submitBtn" class="btn-submit">Guardar Cambios</button>
            </div>

        </form>
    </div>

</section>

<script>
function togglePasswordVisibility(inputId, iconElement) {
    const passwordInput = document.getElementById(inputId);
    const icon = iconElement.querySelector('i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const requirementsContainer = document.getElementById('password-requirements');

    const requirements = {
        length: document.getElementById('req-length'),
        upper: document.getElementById('req-upper'),
        lower: document.getElementById('req-lower'),
        number: document.getElementById('req-number'),
        symbol: document.getElementById('req-symbol')
    };

    const confirmError = document.getElementById('confirm-password-error');

    function validatePassword() {
        const val = passwordInput.value;
        
        if (val === '') {
            requirementsContainer.classList.add('d-none');
            return true; // No intentando cambiar contraseña
        }
        
        requirementsContainer.classList.remove('d-none');
        
        const checks = {
            length: val.length >= 8,
            upper: /[A-Z]/.test(val),
            lower: /[a-z]/.test(val),
            number: /[0-9]/.test(val),
            symbol: /[!@#$%&*]/.test(val)
        };

        let allValid = true;

        for (const key in checks) {
            if (checks[key]) {
                requirements[key].classList.remove('text-danger');
                requirements[key].classList.add('text-success');
                requirements[key].innerHTML = '✓ ' + requirements[key].innerText.substring(2);
            } else {
                requirements[key].classList.remove('text-success');
                requirements[key].classList.add('text-danger');
                requirements[key].innerHTML = '• ' + requirements[key].innerText.substring(2);
                allValid = false;
            }
        }

        return allValid;
    }

    function validateConfirmPassword() {
        if (passwordInput.value === '' && confirmPasswordInput.value === '') {
            confirmError.classList.add('d-none');
            return true;
        }

        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmError.classList.remove('d-none');
            return false;
        } else {
            confirmError.classList.add('d-none');
            return true;
        }
    }

    passwordInput.addEventListener('input', function() {
        validatePassword();
        validateConfirmPassword();
    });

    confirmPasswordInput.addEventListener('input', validateConfirmPassword);

    form.addEventListener('submit', function(e) {
        if (passwordInput.value !== '') {
            const isPasswordValid = validatePassword();
            const isConfirmValid = validateConfirmPassword();

            if (!isPasswordValid || !isConfirmValid) {
                e.preventDefault();
                alert('Por favor, asegúrese de que la nueva contraseña cumpla con todos los requisitos y coincida con la confirmación.');
            }
        }
    });
});
</script>
@endsection
