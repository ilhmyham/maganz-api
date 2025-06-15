<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DivisionController extends Controller
{
    // Menampilkan halaman manajemen divisi
    public function index()
    {
        $divisions = Auth::user()->divisions()->orderBy('name')->get();
        return view('company.divisions.index', compact('divisions'));
    }

    // Menyimpan divisi baru
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Auth::user()->divisions()->create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Divisi baru berhasil ditambahkan.');
    }

    // Menghapus divisi
    public function destroy(Division $division)
    {
        // Otorisasi: pastikan divisi ini milik user yang sedang login
        if ($division->company_id !== Auth::id()) {
            abort(403);
        }
        $division->delete();
        return back()->with('success', 'Divisi berhasil dihapus.');
    }
}
