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
    public function index(Internship $internship)
    {
        if (auth()->id() !== $internship->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $applications = $internship->applications()
        ->with(['student.profile', 'internship.division'])
        ->get();


        return response()->json(['data' => $applications]);
    }

    public function store(Request $request, Internship $internship)
    {

        $validated = $request->validate([
            'resume_url' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'ktp_url' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'transkipNilai_url' => 'required|file|mimes:pdf,doc,docx|max:2048',

        ]);

        if ($internship->applications()->where('student_id', auth()->id())->exists()) {
            return response()->json(['message' => 'Anda sudah melamar magang ini'], 409);
        }


        $resumePath = $request->file('resume_url')->store('resumes', 'public');
        $resumePathktp = $request->file('ktp_url')->store('ktps', 'public');
        $resumePathtranskip = $request->file('transkipNilai_url')->store('transkips', 'public');

        $application = Application::create([
            'internship_id' => $internship->id,
            'student_id' => auth()->id(),
            'resume_url' => Storage::url($resumePath),
            'ktp_url' => Storage::url($resumePathktp),
            'transkipNilai_url' => Storage::url($resumePathtranskip),
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Lamaran berhasil dikirim',
            'data' => $application
        ], 201);
    }


    public function updateStatus(Request $request, Application $application)
{
    if (auth()->id() !== $application->internship->company_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'status' => ['required', Rule::in(['pending', 'accepted', 'rejected'])],
        'surat_balasan' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
    ]);

    $updateData = [
        'status' => $validated['status'],
    ];

    if ($validated['status'] === 'accepted' && $request->hasFile('surat_balasan')) {
        $file = $request->file('surat_balasan');

        $path = $file->storeAs(
            'public/surat_balasan',
            Str::uuid() . '.' . $file->getClientOriginalExtension()
        );

        $updateData['surat_balasan_url'] = Storage::url($path);
        $updateData['surat_balasan_at'] = Carbon::now();
    }

    $application->update($updateData);

    return response()->json([
        'message' => 'Status lamaran dan surat balasan diperbarui',
        'data' => $application
    ]);
}

    public function userApplications()
    {
        $applications = Application::where('student_id', auth()->id())
        ->with(['internship.company.profile', 'internship.division'])
        ->get();

        return response()->json(['data' => $applications]);
    }

    public function kirimSuratBalasan(Request $request, Application $application)
    {
        if (auth()->id() !== $application->internship->company_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'surat_balasan_file' => 'required_without:surat_balasan_text|file|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('surat_balasan_file')) {
            $path = $request->file('surat_balasan_file')->store('surat-balasan', 'public');
            $validated['surat_balasan_url'] = Storage::url($path);
        }

        $application->update([
            'surat_balasan_url'  => $validated['surat_balasan_url'] ?? null,
            'surat_balasan_at'   => now(),
        ]);

        return response()->json([
            'message' => 'Surat balasan berhasil dikirim',
            'data' => $application
        ]);
    }

    public function destroy(Application $application)
    {
        if (auth()->id() !== $application->student_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($application->resume_url) {
            $filePath = str_replace('/storage/', '', $application->resume_url);
            Storage::disk('public')->delete($filePath);
        }

        $application->delete();

        return response()->json(['message' => 'Lamaran dihapus']);
    }
}
