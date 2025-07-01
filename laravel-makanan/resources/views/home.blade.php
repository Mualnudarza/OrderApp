<!DOCTYPE html>
<html>
<head>
    <title>Daftar Menu Makanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Variabel CSS untuk warna agar mudah diubah */
        :root {
            --dark-blue: #1A2E4E; /* Mengambil warna biru gelap dari gambar */
            --light-cream: #F8F6F1; /* Warna putih / krem terang dari latar belakang menu */
            --text-dark: #333;
            --text-light: #666;
            --accent-orange: #FF8C00; /* Contoh warna aksen jika diperlukan, tidak ada di gambar menu tapi bisa ditambahkan */
            --category-color: #555;
        }

        body {
            font-family: 'Poppins', sans-serif; /* Menggunakan Poppins untuk teks umum */
            max-width: 1200px; /* Lebar lebih besar untuk menampung dua bagian jika diperlukan */
            margin: 30px auto;
            background: var(--dark-blue); /* Latar belakang body sesuai bagian kiri gambar */
            padding: 30px;
            border-radius: 12px;
            color: var(--text-dark);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            overflow-x: hidden; /* Mencegah scroll horizontal yang tidak diinginkan */
        }

        .container {
            display: flex;
            flex-direction: column; /* Default column for mobile */
            gap: 0; /* No gap for this specific layout */
        }

        .intro-section {
            background: var(--dark-blue);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            position: relative;
            z-index: 1; /* Ensure it's above other elements if needed */
        }

        .intro-section h2 {
            font-family: 'Playfair Display', serif; /* Font bergaya untuk judul utama */
            font-size: 3.5em; /* Ukuran font lebih besar */
            margin-bottom: 10px;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            line-height: 1.1;
        }

        .intro-section p {
            font-size: 1.1em;
            opacity: 0.9;
        }

        .menu-section {
            background: var(--light-cream); /* Latar belakang putih/krem untuk bagian menu */
            padding: 40px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .menu-section h2.section-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--dark-blue); /* Warna judul section sesuai warna body awal */
            font-size: 2em;
            letter-spacing: 1px;
            position: relative;
            padding-bottom: 10px;
        }

        .menu-section h2.section-title::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: var(--dark-blue); /* Garis bawah pada judul */
            border-radius: 2px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Mengatur kolom yang lebih fleksibel */
            gap: 25px;
        }

        .menu-item {
            background: #ffffff; /* Item menu tetap putih bersih */
            border-radius: 10px; /* Sudut sedikit membulat */
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); /* Bayangan yang lebih lembut */
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: 1px solid #eee; /* Border sangat tipis */
            display: flex; /* Menggunakan flexbox untuk layout di dalam item */
            flex-direction: column;
        }

        .menu-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        .menu-item h3 {
            margin: 0 0 8px 0;
            color: var(--dark-blue); /* Judul item menu */
            font-size: 1.4em;
            font-weight: 600;
        }

        .menu-item .category {
            color: var(--category-color);
            font-size: 0.9em;
            margin-bottom: 10px;
            font-style: italic;
        }

        .menu-item .description {
            color: var(--text-light);
            font-size: 0.95em;
            line-height: 1.6;
            flex-grow: 1; /* Deskripsi mengisi ruang yang tersisa */
            margin-bottom: 15px; /* Jarak sebelum harga */
        }

        .menu-item .price {
            color: var(--accent-orange); /* Harga dengan warna aksen untuk penekanan */
            font-size: 1.5em;
            font-weight: 700;
            text-align: right;
            margin-top: auto; /* Mendorong harga ke bawah */
        }

        .empty-message {
            text-align: center;
            grid-column: 1 / -1;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            color: var(--text-light);
            font-size: 1.2em;
        }

        /* Media queries untuk tampilan dua kolom pada layar besar */
        @media (min-width: 768px) {
            .container {
                flex-direction: row; /* Tampilan dua kolom pada desktop */
                border-radius: 12px; /* Radius pada container utama */
                overflow: hidden; /* Memastikan radius terlihat */
            }

            .intro-section {
                flex: 1; /* Mengambil setengah lebar */
                border-radius: 0; /* Hilangkan radius individu karena sudah di container */
                padding: 60px 40px; /* Padding lebih besar */
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .menu-section {
                flex: 2; /* Mengambil dua pertiga lebar (bisa disesuaikan) */
                border-radius: 0; /* Hilangkan radius individu */
                padding: 60px 50px;
            }
            body {
                padding: 0; /* Hapus padding body jika container sudah mengurus padding */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="intro-section">
            <h2>Indonesian Menu</h2>
            <p>Indonesian foods when you need a taste at home</p>
            </div>

        <div class="menu-section">
            <h2 class="section-title">🍽️ Daftar Menu Makanan</h2>

            <div class="menu-grid">
                @forelse($menus as $menu)
                    <div class="menu-item">
                        <h3>{{ $menu->nama }}</h3>
                        <p class="category"><strong>Kategori:</strong> {{ $menu->kategori->nama }}</p>
                        <p class="description">{{ $menu->deskripsi }}</p>
                        <p class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="empty-message">Tidak ada menu tersedia saat ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>