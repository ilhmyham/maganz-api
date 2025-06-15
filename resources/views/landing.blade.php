<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma.Ganz - Rekrut Talenta Magang Terbaik</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            background: linear-gradient(90deg, #4F46E5, #7C3AED);
            /* Warna gradien baru */
            color: white;
            padding: 8rem 0;
        }

        .feature-icon {
            font-size: 3rem;
            color: #4F46E5;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-building-fill"></i>
                Ma.Ganz for Company
            </a>
            <div>
                <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Daftarkan Perusahaan</a>
            </div>
        </div>
    </nav>

    <header class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold">Temukan Talenta Muda Terbaik untuk Perusahaan Anda</h1>
            <p class="lead my-4">Publikasikan lowongan magang Anda dan jangkau ribuan mahasiswa berbakat dari berbagai
                universitas. Kelola semua lamaran dengan efisien di satu dasbor.</p>
            <a href="{{ route('register') }}" class="btn btn-light btn-lg fw-bold px-4">Mulai Merekrut Sekarang</a>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Platform Manajemen Magang</h2>
                <p class="text-muted">Semua yang Anda butuhkan untuk menjalankan program magang yang sukses.</p>
            </div>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-cloud-upload"></i>
                    </div>
                    <h3 class="h5 fw-bold">Publikasi Lowongan Mudah</h3>
                    <p class="text-muted">Buat dan publikasikan detail lowongan magang Anda dalam hitungan menit melalui
                        form yang intuitif dan mudah digunakan.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="h5 fw-bold">Jangkau Pelamar Berkualitas</h3>
                    <p class="text-muted">Akses kumpulan mahasiswa bertalenta dari berbagai universitas yang siap
                        berkontribusi di perusahaan Anda.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-kanban"></i>
                    </div>
                    <h3 class="h5 fw-bold">Manajemen Pelamar Terpusat</h3>
                    <p class="text-muted">Tinjau profil, unduh dokumen, dan kelola status setiap pelamar (terima/tolak)
                        melalui satu dashboard yang terorganisir.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Ma.Ganz. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
