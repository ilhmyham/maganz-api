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
        // Pastikan hanya perusahaan yang benar yang bisa melihat detail lamaran ini
        if (Auth::id() !== $application->internship->company_id) {
            abort(403, 'AKSES DITOLAK');
        }

        // Kita lempar objek $application yang lengkap (sudah berisi data student dan internship) ke view
        return view('company.applicant_detail', compact('application'));
    }

    // Menampilkan daftar pelamar untuk satu lowongan
    public function index(Internship $internship)
    {
        if ($internship->company_id !== Auth::id()) abort(403);
        $applications = $internship->applications()->with('student.profile')->get();
        return view('company.applicants', compact('internship', 'applications'));
    }

    // Mengupdate status
    public function updateStatus(Request $request, Application $application)
{
    // 1. Otorisasi: Pastikan hanya pemilik magang yang bisa update
    if (auth()->id() !== $application->internship->company_id) {
        abort(403, 'AKSES DITOLAK');
    }

    // 2. Validasi: Validasi status dan file surat balasan
    $validated = $request->validate([
        'status' => ['required', Rule::in(['pending', 'accepted', 'rejected'])],
        'surat_balasan' => ['nullable', 'file', 'mimes:pdf', 'max:2048'], // Opsional, maks 2MB
    ]);

    // 3. Siapkan data yang akan diupdate
    $updateData = [
        'status' => $validated['status'],
    ];

    // 4. Proses file jika ada yang diunggah
    if ($request->hasFile('surat_balasan')) {

        // Hapus file surat balasan lama jika ada, untuk digantikan yang baru
        if ($application->surat_balasan_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $application->surat_balasan_url));
        }

        // Simpan file baru dan dapatkan path-nya
        $path = $request->file('surat_balasan')->store('surat-balasan', 'public');

        // Tambahkan URL file dan waktu upload ke data yang akan di-update
        $updateData['surat_balasan_url'] = Storage::url($path);
        $updateData['surat_balasan_at'] = Carbon::now();
    }

    // 5. Update data aplikasi di database
    $application->update($updateData);

    // 6. Redirect kembali ke halaman sebelumnya dengan pesan sukses
    return back()->with('success', 'Status lamaran berhasil diperbarui!');
}
}
