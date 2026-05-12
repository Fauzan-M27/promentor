<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * Registrasi umum hanya untuk mahasiswa.
     * Akun dosen/admin hanya bisa dibuat oleh admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
            'no_wa'                 => ['nullable', 'string', 'max:20'],
            'nim'                   => ['required', 'string', 'max:20', 'unique:users,nim'],
            'prodi'                 => ['required', 'in:PTIK,Teknik Komputer'],
            'semester'              => ['required', 'integer', 'min:1', 'max:8'],
            'ipk'                   => ['nullable', 'numeric', 'min:0', 'max:4'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa',   // Registrasi umum selalu mahasiswa
            'no_wa'    => $request->no_wa,
            'nim'      => $request->nim,
            'prodi'    => $request->prodi,
            'semester' => $request->semester,
            'ipk'      => $request->ipk,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('mahasiswa.beranda');
    }
}
