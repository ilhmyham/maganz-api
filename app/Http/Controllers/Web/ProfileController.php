<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile; // Pastikan model Profile di-import

class ProfileController extends Controller
{
     public function show()
    {
        $user = Auth::user();
        $profile = $user->profile;

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

        if (!$profile) {
            return redirect()->route('profile.create');
        }

        return view('company.profile_edit', compact('profile'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'required|string',
            'address' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        $photoUrl = null;

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile-photos', 'public');
            $photoUrl = Storage::url($path);
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $validated['company_name'],
                'company_description' => $validated['company_description'],
                'address' => $validated['address'],
                'birthdate' => $validated['birthdate'],
                'photo_url' => $photoUrl ?? $user->profile?->photo_url,
            ]
        );

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
        $profile = Profile::where('user_id', $user->id)->firstOrFail();

        $photoUrl = $profile->photo_url;

        if ($request->hasFile('photo')) {
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
