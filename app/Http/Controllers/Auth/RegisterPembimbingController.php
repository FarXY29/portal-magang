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

class RegisterPembimbingController extends Controller
{
    /**
     * Menampilkan form pendaftaran khusus pembimbing.
     */
    public function create(): View
    {
        // Pastikan file view 'auth.register-pembimbing' sudah dibuat
        return view('auth.register-pembimbing');
    }

    /**
     * Memproses pendaftaran pembimbing.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'asal_instansi' => ['required', 'string', 'max:255'], // Wajib diisi
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'asal_instansi' => $request->asal_instansi, // Simpan nama kampus/sekolah
            'role' => 'pembimbing', // Set role otomatis jadi 'pembimbing'
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect langsung ke dashboard pembimbing
        return redirect()->route('pembimbing.dashboard');
    }
}