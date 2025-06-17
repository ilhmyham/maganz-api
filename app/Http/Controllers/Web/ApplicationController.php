<?php

// app/Http/Controllers/Web/ApplicationController.php
namespace App\Http\Controllers\Web;

use App\Models\Internship;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{

    public function showDetail(Application $application)
    {
        if (Auth::id() !== $application->internship->company_id) {
            abort(403, 'AKSES DITOLAK');
        }

        return view('company.applicant_detail', compact('application'));
    }

    public function index(Internship $internship)
    {
        if ($internship->company_id !== Auth::id()) abort(403);
        $applications = $internship->applications()->with('student.profile')->get();
        return view('company.applicants', compact('internship', 'applications'));
    }

    public function updateStatus(Request $request, Application $application)
{

    if (auth()->id() !== $application->internship->company_id) {
        abort(403, 'AKSES DITOLAK');
    }

    $validated = $request->validate([
        'status' => ['required', Rule::in(['pending', 'accepted', 'rejected'])],
        'surat_balasan' => ['nullable', 'file', 'mimes:pdf', 'max:2048'], // Opsional, maks 2MB
    ]);

    $updateData = [
        'status' => $validated['status'],
    ];

    if ($request->hasFile('surat_balasan')) {


        if ($application->surat_balasan_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $application->surat_balasan_url));
        }


        $path = $request->file('surat_balasan')->store('surat-balasan', 'public');


        $updateData['surat_balasan_url'] = Storage::url($path);
        $updateData['surat_balasan_at'] = Carbon::now();
    }

    $application->update($updateData);

    return back()->with('success', 'Status lamaran berhasil diperbarui!');
}
}
