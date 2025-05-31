<?php

namespace App\Http\Controllers\Api;

use App\Models\Internship;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Container\Attributes\Log;

class InternshipController extends Controller
{
    // Get internships with filters
    public function index(Request $request)
    {
        $query = Internship::where('is_active', true)
            ->with('company.profile');

        // Filter by skill
        if ($request->has('skill')) {
            $query->where('required_skills', 'like', '%'.$request->skill.'%');
        }

        return $query->get();
    }

    // Create internship (for companies only)
    public function store(Request $request)
    {
        if (auth()->user()->role_id != 2) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'required_skills' => 'required|string',
            'location' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $internship = Internship::create([
            'company_id' => auth()->id(),
            ...$validated,
            'is_active' => true
        ]);

        return response()->json($internship, 201);
    }

    // Apply to internship (for students only)
    public function apply(Request $request, Internship $internship)
    {
        if (auth()->user()->role_id != 1) {
            return response()->json(['message' => 'Hanya mahasiswa yang bisa melamar'], 403);
        }

        if ($internship->applications()->where('student_id', auth()->id())->exists()) {
            return response()->json(['message' => 'Anda sudah melamar magang ini'], 409);
        }

        $validated = $request->validate([
            // 'resume' => 'required|file|mimes:pdf,docx|max:2048',
            // 'cover_letter' => 'nullable|string'
        ]);

        // Upload CV
        $path = $request->file('resume')->store('resumes', 'public');

        $application = Application::create([
            'internship_id' => $internship->id,
            'student_id' => auth()->id(),
            'resume_url' => Storage::url($path),
            // 'cover_letter' => $validated['cover_letter'],
            'status' => 'pending'
        ]);

        return response()->json($application, 201);
    }


    public function update(Request $request, Internship $internship)
    {
    // Validasi kepemilikan magang
        if (auth()->id() !== $internship->company_id) {
            return response()->json(['message' => 'Anda bukan pemilik magang ini'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'required_skills' => 'sometimes|string',
            'location' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'is_active' => 'sometimes|boolean'
        ]);

        $internship->update($validated);

        return response()->json([
            'message' => 'Magang berhasil diperbarui',
            'data' => $internship
        ]);

    }

    /**
 * Delete internship (for company owner only)
 */
    public function destroy(Internship $internship)
    {
    // Validasi kepemilikan
        if (auth()->id() !== $internship->company_id) {
            return response()->json(['message' => 'Akses ditolak'], 403);
        }

        // Hapus semua lamaran terkait
        $internship->applications()->delete();

        // Hapus magang
        $internship->delete();

        return response()->json([
            'message' => 'Magang dan semua lamarannya berhasil dihapus'
        ], 202);
    }

}
