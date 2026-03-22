<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RecoveryCodeMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules\Password;

use Illuminate\Support\Facades\Auth;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendRecoveryCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();
        $codigo = rand(100000, 999999);
        
        $user->codigo_recuperacion = $codigo;
        $user->expira_codigo = Carbon::now()->addMinutes(5);
        $user->save();

        Mail::to($user->email)->send(new RecoveryCodeMail($codigo));

        return redirect()->route('password.verify.form', ['email' => $user->email])
            ->with('status', 'Hemos enviado un código de verificación a tu correo.');
    }

    public function showVerifyForm(Request $request)
    {
        return view('auth.passwords.verify', ['email' => $request->email]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'codigo' => 'required|string|size:6'
        ]);

        $user = User::where('email', $request->email)
                    ->where('codigo_recuperacion', $request->codigo)
                    ->where('expira_codigo', '>', Carbon::now())
                    ->first();

        if (!$user) {
            return back()->withErrors(['codigo' => 'El código es incorrecto o ha expirado.']);
        }

        return redirect()->route('password.reset.form', ['email' => $user->email, 'token' => $request->codigo]);
    }

    public function showResetForm(Request $request)
    {
        return view('auth.passwords.reset', ['email' => $request->email, 'token' => $request->token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string|size:6',
            'password' => [
                'required', 
                'string', 
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
            ],
        ]);

        $user = User::where('email', $request->email)
                    ->where('codigo_recuperacion', $request->token)
                    ->where('expira_codigo', '>', Carbon::now())
                    ->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'La sesión de recuperación ha expirado.']);
        }

        $user->password = Hash::make($request->password);
        
        // Limpiar campos de recuperación
        $user->codigo_recuperacion = null;
        $user->expira_codigo = null;

        // Limpiar campos de 2FA para evitar bloqueos
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        
        $user->save();

        // Limpiar cualquier sesión de 2FA pendiente si existe
        if (session()->has('2fa_user_id')) {
            session()->forget('2fa_user_id');
        }

        // Iniciar sesión automáticamente
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'Tu contraseña ha sido actualizada exitosamente y has iniciado sesión automáticamente.');
    }
}
