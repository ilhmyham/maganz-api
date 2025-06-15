@extends('layouts.app')

@section('title', 'Dashboard Lowongan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Lowongan Magang Anda</h2>
        <a href="{{ route('internships.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Buat Lowongan
            Baru</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- ======================================================= --}}
    {{-- KARTU STATISTIK (BAGIAN BARU) --}}
    {{-- ======================================================= --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-bg-primary shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Lowongan</h5>
                        <p class="card-text display-4 fw-bold">{{ $totalInternships }}</p>
                    </div>
                    <i class="bi bi-briefcase-fill" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-bg-info shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Pendaftar</h5>
                        <p class="card-text display-4 fw-bold">{{ $totalApplicants }}</p>
                    </div>
                    <i class="bi bi-people-fill" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    {{-- ======================================================= --}}

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul Lowongan</th>
                        <th>Lokasi</th>
                        <th>Jumlah Pelamar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($internships as $internship)
                        <tr>
                            <td>{{ $internship->title }}</td>
                            <td>{{ $internship->location }}</td>
                            <td>{{ $internship->applications->count() }}</td>
                            <td>
                                <a href="{{ route('internships.applications', $internship->id) }}"
                                    class="btn btn-info btn-sm" title="Lihat Pelamar"><i class="bi bi-people"></i></a>
                                <a href="{{ route('internships.edit', $internship->id) }}" class="btn btn-warning btn-sm"
                                    title="Edit"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('internships.destroy', $internship->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Yakin ingin menghapus lowongan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus"><i
                                            class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Anda belum membuat lowongan magang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
