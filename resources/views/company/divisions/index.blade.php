@extends('layouts.app')

@section('title', 'Manajemen Divisi')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Manajemen Divisi</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row">
        {{-- Kolom untuk menambah divisi baru --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Tambah Divisi Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('divisions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Divisi</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Divisi</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Kolom untuk menampilkan daftar divisi yang sudah ada --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Daftar Divisi Anda</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Divisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($divisions as $division)
                                <tr>
                                    <td>{{ $division->name }}</td>
                                    <td>
                                        <form action="{{ route('divisions.destroy', $division->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus divisi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">Anda belum menambahkan divisi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
