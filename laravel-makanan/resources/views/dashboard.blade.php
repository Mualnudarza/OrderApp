@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            {{-- Mengikuti struktur card utama dari desain yang mirip image_8f44c9.jpg --}}
            <div class="card p-5 text-center" style="
                background-color: #ffffff; /* Latar putih bersih */
                border-radius: 1.5rem; /* Sudut melengkung */
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08); /* Bayangan lembut */
                min-height: 400px; /* Tinggi minimal agar terlihat substansial */
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                overflow: hidden; /* Penting untuk pseudo-elemen atau background shape */
                position: relative; /* Untuk elemen dekoratif */
            ">
                {{-- Elemen dekoratif abstrak (opsional, bisa diimplementasikan dengan CSS pseudo-elements atau SVG background) --}}
                <div style="
                    position: absolute;
                    top: -50px;
                    left: -50px;
                    width: 200px;
                    height: 200px;
                    background-color: rgba(92, 107, 192, 0.1); /* primary-color dengan opacity */
                    border-radius: 50%;
                    filter: blur(50px);
                    z-index: 0;
                "></div>
                <div style="
                    position: absolute;
                    bottom: -30px;
                    right: -30px;
                    width: 150px;
                    height: 150px;
                    background-color: rgba(121, 134, 203, 0.15); /* primary-color slightly darker with opacity */
                    border-radius: 40% 60% 70% 30% / 40% 50% 50% 60%; /* Bentuk tidak beraturan */
                    filter: blur(40px);
                    z-index: 0;
                "></div>

                <div style="position: relative; z-index: 1;"> {{-- Konten utama di atas elemen dekoratif --}}
                    <i class="bi bi-hand-thumbs-up-fill display-3 text-primary mb-4" style="font-size: 5rem;"></i> {{-- Ikon jempol atau yang lainnya --}}
                    <h1 class="display-4 fw-bold mb-3" style="font-family: 'Inter', sans-serif; color: #333;">
                        Selamat Datang Kembali!
                    </h1>
                    <p class="lead text-muted" style="font-family: 'Inter', sans-serif; font-size: 1.25rem;">
                        Aplikasi Anda siap melayani kebutuhan Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pastikan font Inter sudah diimpor di app.blade.php. Jika tidak, tambahkan di sini --}}
{{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet"> --}}

<style>
    /* Tambahan styling jika diperlukan untuk override atau fine-tuning */
    .card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    .card:hover {
        /* transform: translateY(-5px); */ /* Opsional: efek naik sedikit saat hover */
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12); /* Bayangan lebih kuat saat hover */
    }
</style>
@endsection