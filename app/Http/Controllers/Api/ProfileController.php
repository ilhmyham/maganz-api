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
    Log::info('Request Data:', $request->all());
    Log::info('Files:', $request->file());

    $user = auth()->user();
    $isCompany = $user->role_id == 2; // Asumsi role_id 2 = company
    $isStudent = $user->role_id == 1;

    // Validasi dinamis tergantung role
    $validated = $request->validate([
        'address' => 'nullable|string',
        'photo' => 'nullable|image|max:2048',
        'birthdate' => 'nullable|date',
        'gender' => 'required|in:pria,wanita',

        // Field untuk mahasiswa
        'skills' => $isStudent ? 'required|string' : 'nullable|string',
        'university' => $isStudent ? 'required|string' : 'nullable|string',

        // Field untuk perusahaan
        'company_name' => $isCompany ? 'required|string' : 'nullable|string',
        'company_description' => $isCompany ? 'required|string' : 'nullable|string',
    ]);

    // Proses upload foto jika ada
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('profile-photos', 'public');
        $validated['photo_url'] = Storage::url($path);
    }

    // Tambahkan user_id jika belum ada
    $validated['user_id'] = $user->id;

    // Update profil
    $profile = $user->profile;
    if (!$profile) {
        $profile = new \App\Models\Profile();
        $profile->user_id = $user->id;
    }

    $profile->fill($validated)->save();

    return response()->json([
        'message' => 'Profile updated!',
        'data' => $profile
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
