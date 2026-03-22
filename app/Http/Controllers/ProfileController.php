<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function edit()
    {
        if (!session()->has('url.intended')) {
            $previousUrl = url()->previous();
            if ($previousUrl !== url()->current() && $previousUrl !== route('login')) {
                session(['url.intended' => $previousUrl]);
            }
        }
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // If the user is trying to change their password
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = 'required|current_password';
            $rules['password'] = [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }

        $validated = $request->validate($rules);

        try {
            // Update basic info
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            // Handle profile photo
            if ($request->hasFile('profile_photo')) {
                $path = $request->file('profile_photo')->store('profiles', 'public');
                $user->profile_photo = $path;
            }

            // Update password if requested
            if ($request->filled('password')) {
                $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->intended(route('profile.edit'))->with('success', 'Perfil actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil de usuario: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error inesperado al guardar los cambios en tu perfil.');
        }
    }
}
