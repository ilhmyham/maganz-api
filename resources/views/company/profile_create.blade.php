@extends('layouts.app') {{-- Menggunakan layout utama yang sudah ada --}}

@section('title', 'Lengkapi Profil Perusahaan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Lengkapi Profil Perusahaan Anda</h3>
                    <p class="text-muted">Harap isi informasi berikut untuk melanjutkan.</p>
                </div>
                <div class="card-body">
                    {{-- PENTING: tambahkan enctype untuk upload file --}}
                    <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Menampilkan error validasi --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                value="{{ old('company_name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="company_description" class="form-label">Deskripsi Perusahaan</label>
                            <textarea class="form-control" id="company_description" name="company_description" rows="4" required>{{ old('company_description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Tanggal Berdiri Perusahaan</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate"
                                value="{{ old('birthdate') }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="photo" class="form-label">Logo Perusahaan (Opsional)</label>
                            <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
