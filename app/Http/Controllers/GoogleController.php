<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            // FIX: Bypass SSL verification for local development (cURL error 60)
            $googleUser = Socialite::driver('google')->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))->user();

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($user) {
                // Si el usuario existe, actualizamos su google_id y avatar si no lo tiene
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
                
                Auth::login($user);
                
                if ($user->rol === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('home');
            } else {
                // Si no existe, lo creamos
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => Hash::make(Str::random(16)), // Contraseña aleatoria segura
                    'rol' => 'usuario', // Rol por defecto
                ]);

                Auth::login($newUser);

                return redirect()->route('home');
            }

        } catch (\Throwable $th) {
            // Log::error('Error en autenticación con Google: ' . $th->getMessage());
            return redirect()->route('login')->with('error', 'Hubo un problema al iniciar sesión con Google.');
        }
    }
}
