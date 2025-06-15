<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile; // Pastikan model Profile di-import

class ProfileController extends Controller
{
    /**
     * Menampilkan form untuk melengkapi profil.
     */

     public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Pastikan profil ada sebelum menampilkannya
        if (!$profile) {
            return redirect()->route('profile.create')->with('warning', 'Harap lengkapi profil perusahaan Anda.');
        }

        return view('company.profile_show', compact('profile'));
    }

    public function create()
    {
        return view('company.profile_create');
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->profile;

        // Jika profil belum ada, arahkan untuk membuat baru
        if (!$profile) {
            return redirect()->route('profile.create');
        }

        return view('company.profile_edit', compact('profile'));
    }

    /**
     * Menyimpan data profil yang baru diisi.
     */
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date', // 'birthdate' untuk tanggal berdiri
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Foto opsional, maks 2MB
        ]);

        $user = Auth::user();
        $photoUrl = null;

        // 2. Proses upload foto jika ada
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada (opsional, bagus untuk edit profil nanti)
            // if ($user->profile && $user->profile->photo_url) {
            //     Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile->photo_url));
            // }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $photoUrl = Storage::url($path);
        }

        // 3. Simpan atau Update data ke tabel profiles
        // Menggunakan updateOrCreate agar bisa dipakai untuk membuat baru atau mengupdate jika sudah ada
        Profile::updateOrCreate(
            ['user_id' => $user->id], // Kondisi pencarian: cari profile dengan user_id ini
            [ // Data yang akan di-update atau dibuat baru
                'company_name' => $validated['company_name'],
                'company_description' => $validated['company_description'],
                'address' => $validated['address'],
                'birthdate' => $validated['birthdate'],
                'photo_url' => $photoUrl ?? $user->profile?->photo_url, // Gunakan foto baru jika ada, jika tidak, pertahankan foto lama
            ]
        );

        // 4. Arahkan ke dashboard utama setelah profil disimpan
        return redirect()->route('dashboard')->with('success', 'Profil perusahaan berhasil disimpan!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        $profile = Profile::where('user_id', $user->id)->firstOrFail(); // Pastikan profil ada

        $photoUrl = $profile->photo_url; // Default ke foto yang sudah ada

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($photoUrl) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $photoUrl));
            }
            $path = $request->file('photo')->store('profile-photos', 'public');
            $photoUrl = Storage::url($path);
        }

        $profile->update([
            'company_name' => $validated['company_name'],
            'company_description' => $validated['company_description'],
            'address' => $validated['address'],
            'birthdate' => $validated['birthdate'],
            'photo_url' => $photoUrl,
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil perusahaan berhasil diperbarui!');
    }
}
