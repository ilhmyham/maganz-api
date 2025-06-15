@extends('layouts.app')

@section('title', 'Profil Perusahaan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Profil Perusahaan</h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($profile->photo_url)
                        <div class="mb-3 text-center">
                            <img src="{{ asset($profile->photo_url) }}" alt="Logo Perusahaan"
                                class="img-thumbnail rounded-circle" style="max-width: 150px;">
                        </div>
                    @endif

                    <p><strong>Nama Perusahaan:</strong> {{ $profile->company_name }}</p>
                    <p><strong>Deskripsi:</strong> {{ $profile->company_description }}</p>
                    <p><strong>Alamat:</strong> {{ $profile->address }}</p>
                    <p><strong>Tanggal Berdiri:</strong> {{ $profile->birthdate }}</p>

                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
