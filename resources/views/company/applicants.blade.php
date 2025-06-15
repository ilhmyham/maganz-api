@extends('layouts.app')

@section('title', 'Daftar Pelamar - ' . $internship->title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Pelamar untuk "{{ $internship->title }}"</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama Pelamar</th>
                        <th>Email</th>
                        <th>Status Saat Ini</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>{{ $application->student->name ?? 'Nama Belum Diisi' }}</td>
                            <td>{{ $application->student->email }}</td>
                            <td>
                                <span
                                    class="badge
                                    @if ($application->status == 'accepted') bg-success
                                    @elseif($application->status == 'rejected') bg-danger
                                    @else bg-warning @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </td>
                            <td>
                                {{-- Tombol untuk melihat detail pelamar --}}
                                <a href="{{ route('applications.showDetail', $application->id) }}"
                                    class="btn btn-info btn-sm">
                                    Lihat Detail & Aksi
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada pelamar untuk lowongan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
