@extends('layouts.app')

@section('title', isset($internship) ? 'Edit Lowongan' : 'Buat Lowongan Baru')

@section('content')
    <h2>{{ isset($internship) ? 'Edit Lowongan' : 'Buat Lowongan Baru' }}</h2>

    <form action="{{ isset($internship) ? route('internships.update', $internship->id) : route('internships.store') }}"
        method="POST">
        @csrf
        @if (isset($internship))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="title" class="form-label">Judul Lowongan</label>
            <input type="text" class="form-control" id="title" name="title"
                value="{{ old('title', $internship->title ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $internship->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Lokasi</label>
            <input type="text" class="form-control" id="location" name="location"
                value="{{ old('location', $internship->location ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="division_id" class="form-label">Divisi</label>
            <select class="form-select" id="division_id" name="division_id" required>
                <option value="" disabled selected>-- Pilih Divisi --</option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}"
                        {{ old('division_id', $internship->division_id ?? '') == $division->id ? 'selected' : '' }}>
                        {{ $division->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="required_skills" class="form-label">Skill yang Dibutuhkan (pisahkan dengan koma)</label>
            <input type="text" class="form-control" id="required_skills" name="required_skills"
                value="{{ old('required_skills', $internship->required_skills ?? '') }}" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="date" class="form-control" id="start_date" name="start_date"
                    value="{{ old('start_date', isset($internship) ? $internship->start_date : '') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                    value="{{ old('end_date', isset($internship) ? $internship->end_date : '') }}" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
    </form>
@endsection
