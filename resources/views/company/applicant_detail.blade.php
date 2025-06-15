@extends('layouts.app')

@section('title', 'Detail Pelamar')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Detail Pelamar</h2>
            <a href="{{ route('internships.applications', $application->internship_id) }}" class="btn btn-secondary"><i
                    class="bi bi-arrow-left"></i> Kembali ke Daftar Pelamar</a>
        </div>

        <div class="row">
            <div class="col-md-7">
                {{-- Card untuk Informasi Mahasiswa --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Informasi Mahasiswa</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Nama:</strong> {{ $application->student->profile->name ?? $application->student->name }}
                        </p>
                        <p><strong>Email:</strong> {{ $application->student->email }}</p>
                        <p><strong>Universitas:</strong> {{ $application->student->profile->university ?? '-' }}</p>
                        <p><strong>Keahlian:</strong> {{ $application->student->profile->skills ?? '-' }}</p>
                        <p><strong>Alamat:</strong> {{ $application->student->profile->address ?? '-' }}</p>
                        <hr>
                        <p><strong>Melamar untuk Posisi:</strong> {{ $application->internship->title }}</p>
                        <p><strong>Status Lamaran Saat Ini:</strong>
                            <span
                                class="badge
                                @if ($application->status == 'accepted') bg-success
                                @elseif($application->status == 'rejected') bg-danger
                                @else bg-warning @endif">
                                {{ ucfirst($application->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- Card untuk Dokumen Pelamar --}}
                <div class="card">
                    <div class="card-header">
                        <h4>Dokumen Pelamar</h4>
                    </div>
                    <div class="card-body">
                        <p>Tinjau dokumen yang telah diunggah oleh pelamar.</p>
                        <div class="d-grid gap-2">
                            <a href="{{ asset($application->resume_url) }}" target="_blank"
                                class="btn btn-outline-primary"><i class="bi bi-file-earmark-text me-2"></i>Lihat CV</a>
                            <a href="{{ asset($application->transkipNilai_url) }}" target="_blank"
                                class="btn btn-outline-primary"><i class="bi bi-file-earmark-text me-2"></i>Lihat Transkrip
                                Nilai</a>
                            <a href="{{ asset($application->ktp_url) }}" target="_blank" class="btn btn-outline-primary"><i
                                    class="bi bi-file-earmark-person me-2"></i>Lihat KTP</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h4>Aksi Perusahaan</h4>
                    </div>
                    <div class="card-body">
                        {{-- Form untuk Update Status --}}
                        <form action="{{ route('applications.updateStatus', $application->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="statusSelect" class="form-label fw-bold">Ubah Status Lamaran:</label>
                                <select name="status" id="statusSelect" class="form-select">
                                    <option value="pending" @if ($application->status == 'pending') selected @endif>Pending
                                    </option>
                                    <option value="accepted" @if ($application->status == 'accepted') selected @endif>Terima
                                        (Accepted)</option>
                                    <option value="rejected" @if ($application->status == 'rejected') selected @endif>Tolak
                                        (Rejected)</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-4"><i
                                    class="bi bi-check-circle me-2"></i>Update Status</button>
                        </form>

                        <hr>

                        {{-- Form untuk Upload Surat Balasan --}}
                        @if ($application->status === 'accepted')
                            <h5 class="mt-4">Kirim Surat Balasan</h5>

                            @if ($application->surat_balasan_url)
                                <div class="alert alert-success small">
                                    <p class="fw-bold mb-1">Surat balasan sudah dikirim.</p>
                                    <a href="{{ asset($application->surat_balasan_url) }}" target="_blank">Lihat surat yang
                                        terkirim</a>
                                    <p class="mt-2 mb-0">Unggah file baru akan menggantikan surat yang sudah ada.</p>
                                </div>
                            @else
                                <p class="text-muted small">Status lamaran ini sudah **Diterima**. Silakan unggah surat
                                    balasan resmi (format PDF).</p>
                            @endif

                            {{-- Form ini hanya untuk upload surat, status tidak diubah lagi --}}
                            <form action="{{ route('applications.updateStatus', $application->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="accepted"> {{-- Tetap kirim status agar tidak error validasi --}}

                                <div class="mb-3">
                                    <label for="surat_balasan" class="form-label">File Surat Balasan (PDF)</label>
                                    <input class="form-control" type="file" name="surat_balasan" id="surat_balasan"
                                        required accept=".pdf">
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-send me-2"></i>Kirim
                                        Surat Balasan</button>
                                </div>
                            </form>
                        @else
                            <p class="text-muted text-center">Ubah status menjadi "Diterima" untuk dapat mengirim surat
                                balasan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Script ini tidak perlu diubah, sudah benar
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('statusSelect');
            const suratBalasanSection = document.getElementById('suratBalasanSection');

            function toggleSuratBalasan() {
                if (statusSelect.value === 'accepted') {
                    suratBalasanSection.style.display = 'block';
                } else {
                    suratBalasanSection.style.display = 'none';
                }
            }

            toggleSuratBalasan();
            statusSelect.addEventListener('change', toggleSuratBalasan);
        });
    </script>
@endsection
