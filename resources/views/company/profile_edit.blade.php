@extends('layouts.app')

@section('title', 'Edit Profil Perusahaan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Profil Perusahaan</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3 text-center">
                            @if ($profile->photo_url)
                                <img src="{{ asset($profile->photo_url) }}" alt="Logo Perusahaan"
                                    class="img-thumbnail rounded-circle" style="max-width: 150px;">
                            @else
                                <p class="text-muted">Belum ada logo.</p>
                            @endif
                            <label for="photo" class="form-label mt-2">Ubah Logo (Opsional)</label>
                            <input class="form-control" type="file" id="photo" name="photo" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nama Perusahaan</label>
                            <input type="text" class="form-control" id="company_name" name="company_name"
                                value="{{ old('company_name', $profile->company_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="company_description" class="form-label">Deskripsi Perusahaan</label>
                            <textarea class="form-control" id="company_description" name="company_description" rows="4" required>{{ old('company_description', $profile->company_description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address', $profile->address) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="birthdate" class="form-label">Tanggal Berdiri Perusahaan</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate"
                                value="{{ old('birthdate', $profile->birthdate ? $profile->birthdate->format('Y-m-d') : '') }}"
                                required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Profil</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary mt-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
