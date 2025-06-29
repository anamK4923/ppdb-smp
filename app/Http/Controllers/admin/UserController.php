<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan nama
        if ($request->filled('nama')) {
            $query->where('name', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan email
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan tanggal registrasi
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $users = $query->latest()->paginate(10);

        // Statistik untuk cards
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'student' => User::where('role', 'student')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,student'],
            'image' => ['nullable', 'image', 'mimetypes:image/jpeg,image/png,image/gif', 'max:2048'],
        ]);

        $imagePath = null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store new image
            $imagePath = $request->file('image')->store('dokumen', 'public');
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(), // Auto verify untuk admin
            'profile_image' => $imagePath, // simpan path image
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::with(['pendaftaran', 'berkasPendaftaran', 'pengumuman'])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,student'],
            'image' => ['nullable', 'image', 'mimetypes:image/jpeg,image/png,image/gif', 'max:2048'],
        ]);

        $imagePath = null;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Store new image
            $imagePath = $request->file('image')->store('dokumen', 'public');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'profile_image' => $imagePath,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $nama = $user->name;

        // Cegah admin menghapus dirinya sendiri
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus data terkait jika user adalah student
        if ($user->role === 'student') {
            // Hapus pengumuman
            $user->pengumuman()->delete();

            // Hapus berkas pendaftaran
            if ($user->berkasPendaftaran) {
                // Hapus file dari storage jika ada
                // Storage::disk('public')->delete([
                //     $user->berkasPendaftaran->kk_file,
                //     $user->berkasPendaftaran->akta_file,
                //     $user->berkasPendaftaran->piagam_file,
                //     $user->berkasPendaftaran->foto,
                // ]);
                $user->berkasPendaftaran->delete();
            }

            // Hapus pendaftaran
            if ($user->pendaftaran) {
                $user->pendaftaran->delete();
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User "' . $nama . '" berhasil dihapus.');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            $user->update(['email_verified_at' => null]);
            $status = 'dinonaktifkan';
        } else {
            $user->update(['email_verified_at' => now()]);
            $status = 'diaktifkan';
        }

        return redirect()->back()
            ->with('success', "User {$user->name} berhasil {$status}.");
    }
}
