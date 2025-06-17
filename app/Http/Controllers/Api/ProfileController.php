<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Get profile by user ID (untuk student/company)
    public function show($userId)
    {
        $profile = Profile::with('user') // Eager load relasi user
        ->where('user_id', $userId)
        ->firstOrFail();

        return response()->json([
            'data' => $profile
        ]);
    }


    // Update profile (digunakan oleh kedua role)
    public function update(Request $request)
{
    Log::info('Update Profile Request Data:', $request->all());
    Log::info('Update Profile Files:', $request->file());

    $user = auth()->user();

    // 1. Validasi untuk data di tabel users
    // Email harus unik, tapi abaikan user saat ini
    $userValidated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
        'phone' => 'sometimes|string|max:20',
    ]);

    // 2. Validasi untuk data di tabel profiles
    $profileValidated = $request->validate([
        'address' => 'nullable|string',
        'photo' => 'nullable|image|max:2048',
        'birthdate' => 'nullable|date',
        'gender' => 'nullable|in:pria,wanita',
        'skills' => 'nullable|string',
        'university' => 'nullable|string',
        // Anda bisa menambahkan 'company_name' dan 'company_description' di sini jika diperlukan
    ]);

    // 3. Update data di tabel users jika ada datanya
    // array_filter() akan menghapus field yang kosong dari request
    if (!empty(array_filter($userValidated))) {
        $user->update($userValidated);
    }

    // 4. Proses upload foto (logika ini sudah benar)
    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada
        if ($user->profile && $user->profile->photo_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $user->profile->photo_url));
        }
        $path = $request->file('photo')->store('profile-photos', 'public');
        $profileValidated['photo_url'] = Storage::url($path);
    }

    // 5. Update atau buat data di tabel profiles
    $user->profile()->updateOrCreate(
        ['user_id' => $user->id], // Cari berdasarkan user_id
        array_filter($profileValidated) // Simpan data yang tidak kosong
    );

    // 6. Kembalikan response sukses
    return response()->json([
        'message' => 'Profil berhasil diperbarui!',
        'data' => $user->load('profile') // Kirim kembali data user yang sudah di-refresh
    ]);
}


    // Hapus foto profil
    public function deletePhoto()
    {
        $profile = auth()->user()->profile;

        if ($profile->photo_url) {
            Storage::delete('public/' . str_replace('/storage/', '', $profile->photo_url));
            $profile->update(['photo_url' => null]);
        }

        return response()->json([
            'message' => 'Photo deleted!'
        ]);
    }
}
