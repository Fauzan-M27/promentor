<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Tampilkan daftar semua user dengan filter & search.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nim', 'like', "%{$q}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru (termasuk dosen/admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'role'       => ['required', 'in:admin,mahasiswa'],  // Dosen dikelola di DosenController
            'password'   => ['required', Rules\Password::defaults()],
            'nim'        => ['nullable', 'string', 'max:20', 'unique:users,nim'],
            'prodi'      => ['nullable', 'string', 'max:100'],
            'semester'   => ['nullable', 'integer', 'min:1', 'max:8'],
            'ipk'        => ['nullable', 'numeric', 'min:0', 'max:4'],
            'no_wa'      => ['nullable', 'string', 'max:20'],
            'no_wa_mhs'  => ['nullable', 'string', 'max:20'],
        ]);

        $isMahasiswa = $request->role === 'mahasiswa';

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'nim'      => $isMahasiswa ? $request->nim : null,
            'prodi'    => ($isMahasiswa || $isDosen) ? $request->prodi : null,
            'semester' => $isMahasiswa ? $request->semester : null,
            'ipk'      => $isMahasiswa ? $request->ipk : null,
            // Gunakan no_wa dari section mahasiswa jika ada, fallback ke no_wa umum
            'no_wa'    => $isMahasiswa ? ($request->no_wa_mhs ?: $request->no_wa) : $request->no_wa,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail / form edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'      => ['required', 'in:admin,mahasiswa'],  // Dosen dikelola di DosenController
            'password'  => ['nullable', Rules\Password::defaults()],
            'nim'       => ['nullable', 'string', 'max:20', 'unique:users,nim,' . $user->id],
            'prodi'     => ['nullable', 'string', 'max:100'],
            'semester'  => ['nullable', 'integer', 'min:1', 'max:8'],
            'ipk'       => ['nullable', 'numeric', 'min:0', 'max:4'],
            'no_wa'     => ['nullable', 'string', 'max:20'],
        ]);

        $isMahasiswa = $request->role === 'mahasiswa';

        $data = [
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'nim'      => $isMahasiswa ? $request->nim : null,
            'prodi'    => $isMahasiswa ? $request->prodi : null,
            'semester' => $isMahasiswa ? $request->semester : null,
            'ipk'      => $isMahasiswa ? $request->ipk : null,
            'no_wa'    => $request->no_wa,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user.
     */
    public function destroy(User $user)
    {
        // Cegah admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
