<?php
namespace App\Http\Controllers\Web;

use App\Models\Internship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class InternshipController extends Controller
{
    // Menampilkan daftar lowongan milik perusahaan yang login
    public function index()
{
    // Ambil semua lowongan milik perusahaan yang sedang login
    $internships = Auth::user()->internships()->latest()->get();

    // Hitung total lowongan
    $totalInternships = $internships->count();

    // Hitung total semua pendaftar di semua lowongan milik perusahaan ini
    $companyInternshipIds = $internships->pluck('id');
    $totalApplicants = Application::whereIn('internship_id', $companyInternshipIds)->count();

    // Kirim semua data ke view
    return view('company.dashboard', compact('internships', 'totalInternships', 'totalApplicants'));
}

    // Menampilkan form untuk membuat lowongan baru
    public function create()
    {
    // Ambil divisi milik user yang login saja
        $divisions = Auth::user()->divisions()->orderBy('name')->get();
        return view('company.internship_form', compact('divisions'));
    }


    // Menyimpan lowongan baru
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'required_skills' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        Internship::create($request->all() + ['company_id' => Auth::id()]);
        return redirect()->route('dashboard')->with('success', 'Lowongan berhasil dibuat!');
    }

    // Menampilkan form untuk mengedit lowongan
    public function edit(Internship $internship)
{
    // ...
    // Ambil divisi milik user yang login saja
    $divisions = Auth::user()->divisions()->orderBy('name')->get();
    return view('company.internship_form', compact('internship', 'divisions'));
}

    // Mengupdate lowongan
    public function update(Request $request, Internship $internship)
    {
        if ($internship->company_id !== Auth::id()) abort(403);

        // TAMBAHKAN VALIDASI TANGGAL DI SINI JUGA
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'required_skills' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'division_id' => 'required|exists:divisions,id',
        ]);

        $internship->update($request->all());

        return redirect()->route('dashboard')->with('success', 'Lowongan berhasil diperbarui!');
    }

    // Menghapus lowongan
    public function destroy(Internship $internship)
    {
        // Pastikan perusahaan hanya bisa menghapus lowongannya sendiri
        if ($internship->company_id !== Auth::id()) abort(403);
        $internship->delete();
        return redirect()->route('dashboard')->with('success', 'Lowongan berhasil dihapus!');
    }
}

