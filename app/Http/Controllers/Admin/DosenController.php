<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class DosenController extends Controller
{
    /**
     * Daftar semua dosen.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'dosen');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('nidn', 'like', "%{$q}%");
            });
        }

        $dosens = $query->latest()->paginate(15)->withQueryString();

        return view('admin.dosen.index', compact('dosens'));
    }

    /**
     * Tampilkan form tambah dosen.
     */
    public function create()
    {
        return view('admin.dosen.create');
    }

    /**
     * Simpan dosen baru (dibuat oleh admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Rules\Password::defaults()],
            'nidn'     => ['nullable', 'string', 'max:20', 'unique:users,nidn'],
            'prodi'    => ['nullable', 'string', 'max:100'],
            'no_wa'    => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'dosen',
            'nidn'     => $request->nidn,
            'prodi'    => $request->prodi,
            'no_wa'    => $request->no_wa,
        ]);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Akun dosen berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit dosen.
     */
    public function edit(User $dosen)
    {
        abort_if($dosen->role !== 'dosen', 404);
        return view('admin.dosen.edit', compact('dosen'));
    }

    /**
     * Update data dosen.
     */
    public function update(Request $request, User $dosen)
    {
        abort_if($dosen->role !== 'dosen', 404);

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $dosen->id],
            'password' => ['nullable', Rules\Password::defaults()],
            'nidn'     => ['nullable', 'string', 'max:20', 'unique:users,nidn,' . $dosen->id],
            'prodi'    => ['nullable', 'string', 'max:100'],
            'no_wa'    => ['nullable', 'string', 'max:20'],
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'nidn'  => $request->nidn,
            'prodi' => $request->prodi,
            'no_wa' => $request->no_wa,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $dosen->update($data);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    /**
     * Hapus dosen.
     */
    public function destroy(User $dosen)
    {
        abort_if($dosen->role !== 'dosen', 404);

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Akun dosen berhasil dihapus.');
    }
}
