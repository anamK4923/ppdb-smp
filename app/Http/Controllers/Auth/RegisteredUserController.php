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
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password'      => ['required', 'confirmed', Rules\Password::defaults()],
            'role'          => ['required', 'in:admin,student'],
            'nisn'          => ['nullable', 'string', 'max:20'],
            'no_hp'         => ['nullable', 'string', 'max:20'],
            'asal_sekolah'  => ['nullable', 'string', 'max:255'],
            'alamat'        => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'nisn'          => $request->nisn,
            'no_hp'         => $request->no_hp,
            'asal_sekolah'  => $request->asal_sekolah,
            'alamat'        => $request->alamat,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect sesuai role
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin');
        } else {
            return redirect()->route('dashboard.student');
        }
    }
}
