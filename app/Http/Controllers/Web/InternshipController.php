<?php
namespace App\Http\Controllers\Web;

use App\Models\Internship;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;

class InternshipController extends Controller
{
    public function index()
{
    $internships = Auth::user()->internships()->latest()->get();

    $totalInternships = $internships->count();

    $companyInternshipIds = $internships->pluck('id');
    $totalApplicants = Application::whereIn('internship_id', $companyInternshipIds)->count();

    return view('company.dashboard', compact('internships', 'totalInternships', 'totalApplicants'));
}

    public function create()
    {
        $divisions = Auth::user()->divisions()->orderBy('name')->get();
        return view('company.internship_form', compact('divisions'));
    }

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

    public function edit(Internship $internship)
{
    $divisions = Auth::user()->divisions()->orderBy('name')->get();
    return view('company.internship_form', compact('internship', 'divisions'));
}

    public function update(Request $request, Internship $internship)
    {
        if ($internship->company_id !== Auth::id()) abort(403);

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

    public function destroy(Internship $internship)
    {
        if ($internship->company_id !== Auth::id()) abort(403);
        $internship->delete();
        return redirect()->route('dashboard')->with('success', 'Lowongan berhasil dihapus!');
    }
}

