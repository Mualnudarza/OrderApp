<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. DWI WIRA USAHA BAKTI - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #5C6BC0; /* Warna ungu/biru lembut untuk elemen utama */
            --secondary-color: #f0f2f5; /* Latar belakang terang */
            --card-background: #ffffff; /* Latar belakang kartu */
            --text-color-dark: #333; /* Warna teks gelap */
            --text-color-light: #666; /* Warna teks terang/sekunder */
            --border-radius-lg: 1.5rem; /* Radius border lebih besar */
            --border-radius-md: 0.75rem; /* Radius border menengah */
            --box-shadow-light: 0 4px 12px rgba(0, 0, 0, 0.05); /* Shadow lembut */
            --box-shadow-md: 0 8px 20px rgba(0, 0, 0, 0.08); /* Shadow sedikit lebih kuat */
            --sidebar-width: 280px; /* Lebar sidebar */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--secondary-color);
            /* margin dan padding dihapus untuk mengandalkan margin sidebar */
            display: flex; /* Kontainer flex untuk sidebar dan konten */
            min-height: 100vh; /* Tinggi viewport penuh */
            color: var(--text-color-dark);
        }

        /* Styling Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0; /* Mencegah sidebar menyusut */
            background-color: var(--card-background); /* Latar belakang putih untuk sidebar */
            padding: 2rem 1.5rem;
            border-radius: var(--border-radius-lg); /* Aktifkan kembali ini */
            margin: 2rem; /* Aktifkan kembali ini */
            box-shadow: var(--box-shadow-md); /* Terapkan shadow ke sidebar */
            position: sticky; /* Membuat sidebar tetap di tempatnya saat scroll */
            top: 2rem; /* Ubah kembali ke 2rem */
            height: calc(100vh - 4rem); /* Ubah kembali ke calc */
            overflow-y: auto; /* Aktifkan scroll jika konten meluap */
            transition: all 0.3s ease; /* Transisi untuk toggle */
            /* border-top-right-radius dan border-bottom-right-radius dihapus */
        }

        #sidebar::-webkit-scrollbar {
            display: none; /* Sembunyikan scrollbar untuk estetika */
        }

        /* Toggle state for sidebar */
        #sidebar.toggled {
            left: -var(--sidebar-width); /* Sembunyikan sidebar di luar layar */
            position: fixed; /* Penting untuk toggle di mobile */
            margin: 0; /* Hapus margin saat toggled */
            border-radius: 0; /* Hapus border-radius saat toggled */
            box-shadow: 0 0 20px rgba(0,0,0,0.2); /* Shadow saat terbuka di mobile */
            z-index: 1030; /* Di atas navbar di mobile */
            height: 100vh; /* Tinggi penuh di mobile */
            top: 0;
            padding: 1.5rem;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .sidebar-header .app-logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        .sidebar-header .app-logo i {
            margin-right: 0.5rem;
            font-size: 1.8rem;
        }

        .user-profile {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .user-profile img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 0.75rem;
            box-shadow: var(--box-shadow-light);
        }
        .user-profile h5 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }
        .user-profile p {
            font-size: 0.85rem;
            color: var(--text-color-light);
            margin-bottom: 0;
        }

        .sidebar-menu .menu-category {
            font-size: 0.8rem;
            color: var(--text-color-light);
            text-transform: uppercase;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
            padding-left: 1rem; /* Indent kategori menu */
        }
        .sidebar-menu .list-group-item {
            background-color: transparent;
            border: none;
            color: var(--text-color-dark);
            padding: 0.85rem 1rem;
            border-radius: 0.75rem; /* Item menu bulat */
            margin-bottom: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .sidebar-menu .list-group-item i {
            font-size: 1.25rem;
            margin-right: 1rem;
            color: var(--text-color-light);
        }
        .sidebar-menu .list-group-item:hover {
            background-color: var(--secondary-color); /* Hover state lebih terang */
            color: var(--primary-color);
        }
        .sidebar-menu .list-group-item:hover i {
            color: var(--primary-color);
        }
        .sidebar-menu .list-group-item.active {
            background-color: var(--primary-color);
            color: #fff;
            box-shadow: var(--box-shadow-light); /* Shadow untuk item aktif */
        }
        .sidebar-menu .list-group-item.active i {
            color: #fff;
        }

        /* Styling Konten Utama */
        #main-content {
            flex-grow: 1; /* Memungkinkan konten mengambil sisa ruang */
            padding: 2rem; /* Padding di sekitar konten utama */
            transition: margin-left 0.3s ease; /* Transisi untuk toggle */
            /* margin-left: var(--sidebar-width); -- Baris ini dihapus */
        }

        /* Toggle state for main content */
        #main-content.toggled {
            margin-left: 0; /* Geser konten ke kiri saat sidebar terbuka di mobile */
        }

        /* Styling Header (Navbar) */
        .dashboard-header {
            background-color: var(--card-background);
            padding: 1.5rem 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-light);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap; /* Izinkan wrapping pada layar kecil */
        }
        .dashboard-header .greeting h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .dashboard-header .greeting p {
            font-size: 0.9rem;
            color: var(--text-color-light);
            margin-bottom: 0;
        }
        .dashboard-header .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .dashboard-header .header-actions .search-box {
            position: relative;
            margin-right: 1.5rem;
        }
        .dashboard-header .header-actions .search-box input {
            border: none;
            background-color: var(--secondary-color); /* Latar belakang untuk input pencarian */
            border-radius: 0.75rem;
            padding: 0.75rem 1.25rem;
            padding-left: 3rem; /* Ruang untuk ikon */
            width: 250px;
            font-size: 0.95rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03); /* Inner shadow */
        }
        .dashboard-header .header-actions .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-color-light);
        }
        .dashboard-header .header-actions .btn-add-project {
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border: none;
            transition: background-color 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .dashboard-header .header-actions .btn-add-project i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }
        .dashboard-header .header-actions .btn-add-project:hover {
            background-color: #495057; /* Lebih gelap saat hover */
        }

        .header-icons .icon-button {
            background-color: var(--secondary-color);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-left: 1rem;
            color: var(--text-color-dark);
            font-size: 1.2rem;
            box-shadow: var(--box-shadow-light);
            transition: all 0.2s ease-in-out;
        }
        .header-icons .icon-button:hover {
            background-color: #e2e4e8;
            transform: translateY(-2px);
        }

        /* Styling Kartu Umum */
        .card {
            background-color: var(--card-background);
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-light);
            padding: 1.5rem;
            margin-bottom: 1.5rem; /* Spasi konsisten antar kartu */
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color-dark);
        }

        /* Tata Letak Grid Master */
        .master-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        .master-item {
            background-color: var(--card-background);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: var(--box-shadow-light);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            text-decoration: none; /* Hapus garis bawah untuk tautan */
            color: inherit; /* Warisi warna teks */
        }

        .master-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-md);
        }

        .master-item i {
            font-size: 3.5rem; /* Ukuran ikon lebih besar */
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .master-item span {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-color-dark);
        }

        /* Styling Form */
        .form-control, .form-select, .btn {
            border-radius: 0.75rem; /* Radius border lebih besar untuk elemen form */
            padding: 0.75rem 1.25rem;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(92, 107, 192, 0.25); /* Glow fokus warna primer */
            border-color: var(--primary-color);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #48569a; /* Warna primer sedikit lebih gelap saat hover */
            border-color: #48569a;
        }
        .btn-success {
            background-color: #66BB6A; /* Hijau untuk sukses/simpan */
            border-color: #66BB6A;
        }
        .btn-success:hover {
            background-color: #5cb860;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-width: 2px; /* Border lebih tebal untuk tombol outline */
            border-radius: 0.75rem;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        .btn-outline-danger {
            color: #EF5350; /* Merah untuk bahaya */
            border-color: #EF5350;
            border-width: 2px;
            border-radius: 0.75rem;
        }
        .btn-outline-danger:hover {
            background-color: #EF5350;
            color: #fff;
        }
        .btn-secondary {
            background-color: #B0BEC5; /* Abu-abu untuk sekunder */
            border-color: #B0BEC5;
        }
        .btn-secondary:hover {
            background-color: #92a4ad;
        }

        /* List Group Items (untuk daftar menu/kategori) */
        .list-group-item {
            background-color: var(--secondary-color); /* Latar belakang lebih terang untuk item daftar */
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 0.75rem;
            box-shadow: var(--box-shadow-light);
        }

        /* Override Modal */
        .modal-content {
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-md);
            padding: 1.5rem; /* Padding di dalam konten modal */
        }
        .modal-header {
            border-bottom: none;
            padding: 0 0 1rem 0; /* Sesuaikan padding header */
            margin-bottom: 1rem;
        }
        .modal-title {
            font-weight: 600;
            color: var(--text-color-dark);
        }
        .modal-body {
            padding: 0; /* Hapus padding body default */
        }
        .modal-footer {
            border-top: none;
            padding: 1.5rem 0 0 0; /* Sesuaikan padding footer */
            justify-content: flex-end;
        }

        /* Contoh Kartu Kalender (dari desain - hanya untuk demonstrasi) */
        .calendar-card {
            background-color: var(--card-background);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-light);
            padding: 1.5rem;
            height: fit-content; /* Sesuaikan tinggi sesuai kebutuhan */
        }
        .calendar-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .calendar-card .card-header h5 {
            font-weight: 600;
            margin-bottom: 0;
        }
        .calendar-card .card-header i {
            font-size: 1.2rem;
            color: var(--text-color-light);
        }
        .event-list .event-date {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-color-dark);
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        .event-list .event-item {
            display: flex;
            margin-bottom: 0.75rem;
        }
        .event-list .event-time {
            font-size: 0.8rem;
            color: var(--text-color-light);
            width: 50px;
            flex-shrink: 0;
        }
        .event-list .event-details {
            margin-left: 0.75rem;
            padding-left: 0.75rem;
            border-left: 2px solid var(--primary-color); /* Garis event */
        }
        .event-list .event-details strong {
            font-size: 0.95rem;
            display: block;
            margin-bottom: 0.2rem;
        }
        .event-list .event-details span {
            font-size: 0.8rem;
            color: var(--text-color-light);
        }
        /* Warna kartu kustom untuk contoh grid master */
        .card-purple {
            background-color: #7B68EE; /* MediumSlateBlue */
            color: white;
        }
        .card-cyan {
            background-color: #20B2AA; /* LightSeaGreen */
            color: white;
        }
        .card-orange {
            background-color: #FFA500; /* Orange */
            color: white;
        }
        .card-purple i, .card-cyan i, .card-orange i {
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            #sidebar {
                position: fixed;
                left: -var(--sidebar-width);
                margin: 0;
                border-radius: 0;
                transition: left 0.3s ease-in-out;
                z-index: 1030; /* Above navbar */
                height: 100vh;
                top: 0;
                padding: 1.5rem;
                box-shadow: 0 0 20px rgba(0,0,0,0.2);
            }
            #sidebar.toggled {
                left: 0;
            }
            #main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
            .dashboard-header {
                flex-wrap: wrap;
            }
            .dashboard-header .greeting {
                flex-basis: 100%;
                margin-bottom: 1rem;
            }
            .dashboard-header .header-actions {
                flex-basis: 100%;
                justify-content: flex-start;
            }
            .dashboard-header .header-actions .search-box {
                margin-right: 0.5rem;
                flex-grow: 1;
            }
            .dashboard-header .header-actions .search-box input {
                width: 100%;
            }
            .dashboard-header .header-actions .header-icons {
                margin-left: auto;
            }
            .master-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
        }
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            #sidebar {
                top: 0;
                height: 100vh;
                left: -var(--sidebar-width);
                padding: 1rem;
            }
            #main-content {
                padding: 1rem;
            }
            .dashboard-header {
                padding: 1rem 1.5rem;
                margin-bottom: 1.5rem;
            }
        }

        /* Styling untuk Modal Akses Ditolak */
        .access-denied-modal .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background-color: var(--card-background);
            text-align: center;
            padding: 1.5rem;
        }

        .access-denied-modal .modal-header {
            border-bottom: none;
            justify-content: center;
            padding-bottom: 0;
        }

        .access-denied-modal .modal-body {
            padding: 1.5rem 1rem 1rem 1rem;
            color: var(--text-color-dark);
        }

        .access-denied-modal .modal-body .bi {
            font-size: 4rem; /* Ukuran ikon lebih besar */
            color: #dc3545; /* Warna merah untuk ikon peringatan */
            margin-bottom: 1rem;
            display: block; /* Agar ikon berada di tengah */
        }

        .access-denied-modal .modal-body h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .access-denied-modal .modal-body p {
            font-size: 1rem;
            color: var(--text-color-light);
            margin-bottom: 1.5rem;
        }

        .access-denied-modal .modal-footer {
            border-top: none;
            justify-content: center;
            padding-top: 0;
        }

        .access-denied-modal .btn-close-modal {
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: background-color 0.2s ease;
        }

        .access-denied-modal .btn-close-modal:hover {
            background-color: #4a5a9e;
        }
    </style>
</head>
<body>
    @auth {{-- Tampilkan sidebar dan header utama hanya jika pengguna sudah login --}}
    <div id="sidebar">
        <div class="sidebar-header">
            <span class="app-logo"><i class="bi bi-bar-chart-fill"></i> Chaart</span>
        </div>

        <div class="user-profile">
            {{-- Gambar profil default atau bisa diganti dengan avatar pengguna jika ada --}}
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="User Avatar">
            <h5>{{ Auth::user()->name }}</h5> {{-- Nama pengguna dari database --}}
            <p>{{ Auth::user()->email }}</p> {{-- Email pengguna dari database --}}
        </div>

        <div class="sidebar-menu">
            <div class="menu-category">MENU</div>
            {{-- Dashboard --}}
            <a href="/dashboard" class="list-group-item @if(Request::is('dashboard')) active @endif">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
            {{-- Menu Makanan --}}
            <a href="/menu" class="list-group-item @if(Request::is('menu*')) active @endif">
                <i class="bi bi-egg-fried"></i> Menu
            </a>
            {{-- Kategori --}}
            <a href="/kategori" class="list-group-item @if(Request::is('kategori*')) active @endif">
                <i class="bi bi-bookmark-fill"></i> Kategori
            </a>
            {{-- Daftar Menu (Publik) - Ini tidak perlu di sidebar admin --}}
            {{-- <a href="/home" class="list-group-item @if(Request::is('home*')) active @endif">
                <i class="bi bi-compass"></i> Daftar Menu
            </a> --}}

            {{-- Menambahkan Menu Pemesanan, History Pesanan, dan Laporan Pemesanan --}}
            <a href="{{ route('order.index') }}" class="list-group-item @if(Request::is('order*')) active @endif">
                <i class="bi bi-bag-check"></i> Pemesanan
            </a>
            <a href="{{ route('historipesanan.list') }}" class="list-group-item @if(Request::is('histori-pesanan*')) active @endif">
                <i class="bi bi-clock-history"></i> History Pesanan
            </a>
            <a href="{{ route('laporanpesanan.list') }}" class="list-group-item @if(Request::is('laporan-pesanan*')) active @endif">
                <i class="bi bi-file-earmark-text"></i> Laporan Pemesanan
            </a>
            {{-- Akhir Penambahan Menu --}}

            <div class="menu-category">Setting</div>
            {{-- Setting --}}
            <a href="{{ route('manajemen.akses') }}" class="list-group-item @if(Request::is('manajemen-akses*')) active @endif">
                <i class="bi bi-person-gear"></i> Manajemen Akses
            </a>
            {{-- Logout --}}
            <li class="sidebar-menu-item mt-auto" style="list-style: none; padding: 0; margin: 0;">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <a href="#" class="list-group-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </div>
    </div>
    @endauth

    <div id="main-content">
        @auth {{-- Tampilkan header utama hanya jika pengguna sudah login --}}
        <header class="dashboard-header">
            <div class="greeting">
                <h1>Hello, {{ Auth::user()->name }}</h1> {{-- Nama pengguna dari database --}}
                <p>Today is {{ date('l, d F Y') }}</p> {{-- Tanggal dinamis --}}
            </div>
            <div class="header-actions">
                <div class="search-box d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <a href="{{ route('order.index') }}" class="btn btn-add-project d-none d-md-flex"> {{-- Ubah dari button menjadi link dan tambahkan rute --}}
                    <i class="bi bi-plus"></i> Buat Pesanan Baru {{-- Mengubah teks tombol --}}
                </a>
                <div class="header-icons ms-md-3">
                    <button class="icon-button"><i class="bi bi-bell"></i></button>
                    <button class="icon-button ms-2 d-md-none" id="mobileSidebarToggle"><i class="bi bi-list"></i></button>
                </div>
            </div>
        </header>
        @endauth

        @yield('content')
    </div>

    {{-- Modal Akses Ditolak --}}
    <div class="modal fade access-denied-modal" id="accessDeniedModal" tabindex="-1" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <i class="bi bi-exclamation-circle-fill"></i> {{-- Ikon peringatan --}}
                    <h5>Akses Ditolak!</h5>
                    <p>Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close-modal" data-bs-dismiss="modal">Oke, Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const mainContent = document.getElementById('main-content'); // ID konten utama Anda

            // Logika toggle sidebar untuk mobile
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function () {
                    if (sidebar) sidebar.classList.toggle('toggled');
                    // mainContent tidak perlu ditoggle class 'toggled' di sini karena
                    // kita akan mengelola margin-left-nya secara terpisah atau dengan media query
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                // Pastikan sidebar ada dan sedang dalam mode toggled (terbuka di mobile)
                // Dan klik terjadi di luar sidebar dan di luar tombol toggle
                if (window.innerWidth <= 992 && sidebar && sidebar.classList.contains('toggled') &&
                    !sidebar.contains(event.target) && !mobileSidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('toggled');
                }
            });

            // Logika untuk menampilkan Modal Akses Ditolak
            @if (session('showAccessDeniedModal'))
                console.log('DEBUG: Session showAccessDeniedModal terdeteksi, mencoba menampilkan modal.');
                var accessDeniedModal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
                accessDeniedModal.show();
            @endif

            // Logika untuk menandai menu sidebar yang aktif
            const currentPath = window.location.pathname;
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a'); // Target semua <a> di dalam .sidebar-menu

            sidebarLinks.forEach(link => {
                // Hapus kelas 'active' dari semua link terlebih dahulu
                link.classList.remove('active');

                // Normalisasi path saat ini (hapus trailing slash kecuali untuk root '/')
                const normalizedCurrentPath = (currentPath.length > 1 && currentPath.endsWith('/'))
                    ? currentPath.slice(0, -1)
                    : currentPath;

                // Dapatkan href dari link dan normalisasi juga
                const linkHref = link.getAttribute('href');
                const normalizedLinkHref = (linkHref && linkHref.length > 1 && linkHref.endsWith('/'))
                    ? linkHref.slice(0, -1)
                    : linkHref;

                // Logika penandaan aktif
                if (normalizedCurrentPath === normalizedLinkHref) {
                    link.classList.add('active');
                }
                // Jika path saat ini dimulai dengan link href dan link href bukan root ("/")
                // Ini untuk menangani rute bersarang seperti /menu/edit/1 mengaktifkan /menu
                else if (normalizedLinkHref !== '/' && normalizedCurrentPath.startsWith(normalizedLinkHref)) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
