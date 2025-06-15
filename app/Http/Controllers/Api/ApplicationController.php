<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Internship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Mendapatkan semua aplikasi untuk magang tertentu (perusahaan)
     */
    public function index(Internship $internship)
    {
        // Pastikan hanya pemilik magang yang bisa melihat aplikasi
        if (auth()->id() !== $internship->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $applications = $internship->applications()
        ->with(['student.profile', 'internship.division']) // <-- UBAH DI SINI
        ->get();


        return response()->json(['data' => $applications]);
    }

    /**
     * Membuat aplikasi baru (mahasiswa)
     */
    public function store(Request $request, Internship $internship)
    {
        // Validasi
        $validated = $request->validate([
            'resume_url' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'ktp_url' => 'required|file|mimes:pdf,doc,docx|max:2048', // Max 2MB
            'transkipNilai_url' => 'required|file|mimes:pdf,doc,docx|max:2048', // Max 2MB

             // Max 2MB
            // 'cover_letter' => 'nullable|string|max:1000',
        ]);

        // Cek apakah sudah pernah melamar
        if ($internship->applications()->where('student_id', auth()->id())->exists()) {
            return response()->json(['message' => 'Anda sudah melamar magang ini'], 409);
        }


        // Upload file resume
        $resumePath = $request->file('resume_url')->store('resumes', 'public');
        $resumePathktp = $request->file('ktp_url')->store('ktps', 'public');
        $resumePathtranskip = $request->file('transkipNilai_url')->store('transkips', 'public');

        // $resumePathSb = $request->file('surat_balasan')->store('surat_balasan', 'public');

        // Buat aplikasi
        $application = Application::create([
            'internship_id' => $internship->id,
            'student_id' => auth()->id(),
            'resume_url' => Storage::url($resumePath),
            'ktp_url' => Storage::url($resumePathktp),
            'transkipNilai_url' => Storage::url($resumePathtranskip),
            // 'cover_letter' => $validated['cover_letter'],
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Lamaran berhasil dikirim',
            'data' => $application
        ], 201);
    }

    /**
     * Mengupdate status aplikasi (perusahaan)
     */
    public function updateStatus(Request $request, Application $application)
{
    // Validasi hanya pemilik magang yang bisa update
    if (auth()->id() !== $application->internship->company_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'status' => ['required', Rule::in(['pending', 'accepted', 'rejected'])],
        'surat_balasan' => ['nullable', 'file', 'mimes:pdf', 'max:2048'], // maksimal 2MB PDF
    ]);

    $updateData = [
        'status' => $validated['status'],
    ];

    if ($validated['status'] === 'accepted' && $request->hasFile('surat_balasan')) {
        $file = $request->file('surat_balasan');

        // Simpan file di storage/app/public/surat_balasan/
        $path = $file->storeAs(
            'public/surat_balasan',
            Str::uuid() . '.' . $file->getClientOriginalExtension()
        );

        // Simpan URL dan waktu upload
        $updateData['surat_balasan_url'] = Storage::url($path); // jika ingin URL publik
        $updateData['surat_balasan_at'] = Carbon::now();
    }

    $application->update($updateData);

    return response()->json([
        'message' => 'Status lamaran dan surat balasan diperbarui',
        'data' => $application
    ]);
}

    /**
     * Mendapatkan semua lamaran user (mahasiswa)
     */
    public function userApplications()
    {
        $applications = Application::where('student_id', auth()->id())
        ->with(['internship.company.profile', 'internship.division']) // <-- UBAH DI SINI
        ->get();

        return response()->json(['data' => $applications]);
    }

    public function kirimSuratBalasan(Request $request, Application $application)
    {
        // Pastikan hanya perusahaan pemilik lowongan yang bisa kirim surat balasan
        if (auth()->id() !== $application->internship->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'surat_balasan_file' => 'required_without:surat_balasan_text|file|mimes:pdf|max:2048',
        ]);

        // Upload file PDF jika ada
        if ($request->hasFile('surat_balasan_file')) {
            $path = $request->file('surat_balasan_file')->store('surat-balasan', 'public');
            $validated['surat_balasan_url'] = Storage::url($path);
        }

        // Update data aplikasi
        $application->update([
            'surat_balasan_url'  => $validated['surat_balasan_url'] ?? null,
            'surat_balasan_at'   => now(),
        ]);

        return response()->json([
            'message' => 'Surat balasan berhasil dikirim',
            'data' => $application
        ]);
    }

    /**
     * Menghapus lamaran (mahasiswa)
     */
    public function destroy(Application $application)
    {
        // Hanya pembuat lamaran yang bisa menghapus
        if (auth()->id() !== $application->student_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Hapus file resume
        if ($application->resume_url) {
            $filePath = str_replace('/storage/', '', $application->resume_url);
            Storage::disk('public')->delete($filePath);
        }

        $application->delete();

        return response()->json(['message' => 'Lamaran dihapus']);
    }
}
