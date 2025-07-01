<!DOCTYPE html>
<html>
<head>
    <title>🍜 "Opo Enak? - Makanan Khas Malang"</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Pastikan link Bootstrap CSS sudah benar dan termuat -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Variabel CSS dengan tema Biru Navy Dominan dan Gradasi */
        :root {
            --dominant-navy: #060058; /* Warna biru navy dominan yang gelap */
            --primary-bg-start: #1A324C; /* Warna awal gradasi latar belakang (biru gelap sedikit lebih terang) */
            --primary-bg-end: #060058; /* Warna akhir gradasi latar belakang (biru navy dominan) */
            --card-bg: #ffffff; /* Latar belakang kartu: Putih bersih */
            --text-dark: var(--dominant-navy); /* Teks gelap/utama (untuk di atas kartu): Biru Navy Dominan */
            --text-light: #546E7A; /* Teks terang/sekunder (untuk di atas kartu): Abu-abu kebiruan gelap */
            --accent-color: #AEC6CF; /* Warna aksen untuk harga/penting: Biru sangat terang (lebih kontras) */
            --border-light: #DAE1E8; /* Warna border terang: Biru muda pucat */
            --shadow-subtle: rgba(6, 0, 88, 0.08); /* Shadow dengan nuansa navy dominan */
            --input-border: #C8D4E2; /* Border input: Biru keabu-abuan terang */
            --input-focus: #7FB3D5; /* Border focus input: Biru kalem sedikit lebih terang */

            /* Warna teks untuk elemen yang berada langsung di atas background gelap (misal: jika ada teks di luar container) */
            --text-on-dark-bg: #E0EBF5; /* Sangat terang, untuk kontras dengan background gelap */

            /* Button specific variables */
            --button-primary: #5C6BC0; /* Warna tombol utama (biru keunguan) */
            --button-primary-hover: #4a5a9e; /* Hover tombol utama */
            --button-secondary: #6c757d; /* Warna tombol sekunder */
            --button-secondary-hover: #5a6268; /* Hover tombol sekunder */
        }

        html, body {
            height: 100%; /* Memastikan html dan body mengambil tinggi penuh viewport */
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(to bottom, var(--primary-bg-start), var(--primary-bg-end));
            background-attachment: fixed; /* Menjaga background tetap saat scroll */
            background-size: cover; /* Memastikan background mencakup seluruh area */
            margin: 0;
            padding: 0;
            color: var(--text-dark); /* Default body text color */
            display: flex;
            flex-direction: column; /* Mengatur tata letak vertikal untuk header dan konten */
            box-sizing: border-box;
        }

        /* Header Utama Aplikasi (untuk judul dan tombol auth) */
        .app-main-header {
            background-color: var(--card-bg);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px var(--shadow-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap; /* Memungkinkan wrapping pada layar kecil */
        }

        .app-main-header .app-title {
            font-size: 1.8em;
            font-weight: 700;
            color: var(--dominant-navy);
            margin: 0;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-auth {
            padding: 10px 20px;
            background-color: var(--button-primary); /* Menggunakan variabel button-primary */
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95em;
            transition: background-color 0.3s ease;
            border: 1px solid var(--button-primary); /* Tambahkan border untuk konsistensi */
        }

        .btn-auth:hover {
            background-color: var(--button-primary-hover); /* Menggunakan variabel hover */
        }

        .btn-auth.register {
            background-color: transparent; /* Transparan untuk register */
            color: var(--button-primary); /* Warna teks dari primary button */
            border: 1px solid var(--button-primary);
        }

        .btn-auth.register:hover {
            background-color: var(--button-primary); /* Background jadi primary saat hover */
            color: #fff;
        }


        /* Container utama untuk konten (selain header aplikasi) */
        .main-content-container {
            flex-grow: 1; /* Memungkinkan konten mengambil sisa ruang yang tersedia */
            padding: 20px; /* Padding dari body yang asli */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Konten dimulai dari atas */
            box-sizing: border-box;
        }

        .container { /* Ini adalah container untuk konten di dalam main-content-container */
            width: 100%;
            max-width: 1100px;
            background: linear-gradient(to bottom, var(--card-bg), #FDFDFE);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 30px;
            box-sizing: border-box; /* Penting untuk padding */
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .header-section h1 {
            font-size: 2.8em;
            color: var(--text-dark);
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header-section h3 {
            font-size: 1.5em;
            color: var(--accent-color);
            margin-bottom: 15px;
            font-weight: 600;
        }

        .header-section p {
            font-size: 1.1em;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Styling untuk Filter dan Pencarian */
        .controls {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
            align-items: center;
        }

        .controls input[type="text"],
        .controls select {
            padding: 12px 18px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            max-width: 280px;
            box-sizing: border-box;
            background-color: var(--card-bg);
            color: var(--text-dark);
        }

        .controls input[type="text"]::placeholder {
            color: var(--text-light);
        }

        .controls input[type="text"]:focus,
        .controls select:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(127, 179, 213, 0.3);
        }

        .controls select {
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23060058%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-6.5%200-13%205.7-13%2013%200%206.5%205.7%2013%2013%2013h255.2c6.5%200%2013-5.7%2013-13%200-4.5-1.7-8.8-5.4-12.8z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px auto;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 25px;
        }

        .menu-item {
            background: linear-gradient(to bottom, var(--card-bg), #FBFBFB);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px var(--shadow-subtle);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(6, 0, 88, 0.12);
        }

        .menu-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(6, 0, 88, 0.1);
        }

        .menu-item h3 {
            margin: 0 0 8px 0;
            color: var(--text-dark);
            font-size: 1.3em;
            font-weight: 600;
            line-height: 1.3;
        }

        .menu-item .category {
            color: var(--text-light);
            font-size: 0.85em;
            margin-bottom: 10px;
            font-style: italic;
        }

        .menu-item .description {
            color: var(--text-light);
            font-size: 0.9em;
            line-height: 1.5;
            margin-bottom: 15px;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-item .price {
            color: var(--accent-color);
            font-size: 1.6em;
            font-weight: 700;
            margin-top: auto;
        }

        .empty-message {
            text-align: center;
            grid-column: 1 / -1; /* Memastikan pesan mengambil seluruh lebar grid */
            padding: 30px;
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(6, 0, 88, 0.05);
            color: var(--text-light);
            font-size: 1.2em;
        }

        /* Media queries */
        @media (max-width: 767px) { /* Untuk ukuran mobile */
            .app-main-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .app-main-header .app-title {
                margin-bottom: 1rem;
            }
            .auth-buttons {
                width: 100%;
                justify-content: stretch;
            }
            .btn-auth {
                flex: 1; /* Tombol akan mengisi lebar penuh */
            }
            .container {
                padding: 20px; /* Kurangi padding untuk mobile */
            }
            .controls {
                flex-direction: column;
                align-items: stretch;
            }
            .controls input[type="text"],
            .controls select {
                max-width: 100%; /* Gunakan lebar penuh untuk input/select */
            }
            .menu-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Sesuaikan ukuran kartu untuk mobile */
            }
        }

        @media (min-width: 768px) {
            .controls {
                justify-content: flex-end;
            }
            .controls input[type="text"],
            .controls select {
                max-width: 250px;
            }
            .menu-grid {
                grid-template-columns: repeat(3, 1fr); /* 3 kolom untuk tablet/desktop kecil */
            }
        }

        @media (min-width: 992px) {
            .menu-grid {
                grid-template-columns: repeat(4, 1fr); /* 4 kolom untuk desktop besar */
            }
        }
    </style>
</head>
<body>
    <header class="app-main-header">
        <h1 class="app-title">Daftar Menu Makanan</h1>
        <div class="auth-buttons">
            <a href="/login" class="btn-auth">Login</a>
            <a href="/register" class="btn-auth register">Register</a>
        </div>
    </header>

    <div class="main-content-container">
        <div class="container">
            <div class="header-section">
                <h1>🍜 "Opo Enak? - Makanan Khas Malang"</h1>
                <h3>Kowe Makan, Kowe Bahagia!</h3>
                <p>"Opo Enak?" adalah tempat makan online yang menyajikan makanan khas Malang langsung dari dapur rumahan ke lidah pelanggan. Semua menu dibuat dengan resep tradisional khas Malang dan rasa rumahan yang ngangenin. Cocok buat kamu yang kangen kampung halaman, pengen jajan enak, murah, dan cepet.</p>
            </div>

            <div class="controls">
                <input type="text" id="searchMenu" onkeyup="filterMenu()" placeholder="Cari menu...">
                <select id="filterCategory" onchange="filterMenu()">
                    <option value="all">Semua Kategori</option>
                    {{-- Pastikan variabel $kategoris dikirim dari controller --}}
                    @foreach($kategoris as $category)
                        <option value="{{ $category->nama }}">{{ $category->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="menu-grid" id="menuList">
                @forelse($menus as $menu)
                    <div class="menu-item" data-category="{{ $menu->kategori->nama ?? 'Tidak Berkategori' }}" data-name="{{ $menu->nama }}">
                        {{-- Template untuk Gambar (Anda bisa mengganti URL gambar ini) --}}
                        <img src="https://via.placeholder.com/150/060058/E0EBF5?text={{ urlencode($menu->nama) }}" alt="{{ $menu->nama }}" class="menu-image">
                        <h3>{{ $menu->nama }}</h3>
                        <p class="category">{{ $menu->kategori->nama ?? 'Tidak Berkategori' }}</p>
                        <p class="description">{{ $menu->deskripsi ?? 'Deskripsi belum tersedia.' }}</p>
                        <p class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <p class="empty-message" id="noMenuFound">Tidak ada menu tersedia saat ini.</p>
                @endforelse
            </div>
            <p class="empty-message" id="noResults" style="display: none;">Menu tidak ditemukan.</p>
        </div>
    </div>

    <script>
        function filterMenu() {
            const searchInput = document.getElementById('searchMenu');
            const filterCategory = document.getElementById('filterCategory');
            const menuItems = document.querySelectorAll('.menu-item');
            const noResultsMessage = document.getElementById('noResults');
            const noMenuFoundInitial = document.getElementById('noMenuFound'); // Pesan awal jika tidak ada menu sama sekali

            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = filterCategory.value.toLowerCase();

            let foundMenus = 0;

            // Sembunyikan pesan awal "Tidak ada menu tersedia" jika ada menu yang dimuat
            if (noMenuFoundInitial && menuItems.length > 0) {
                noMenuFoundInitial.style.display = 'none';
            }

            menuItems.forEach(item => {
                const menuName = item.getAttribute('data-name').toLowerCase();
                const menuDescription = item.querySelector('.description') ? item.querySelector('.description').textContent.toLowerCase() : ''; // Handle if description is missing
                const menuCategory = item.getAttribute('data-category').toLowerCase();

                const matchesSearch = menuName.includes(searchTerm) || menuDescription.includes(searchTerm);
                const matchesCategory = selectedCategory === 'all' || menuCategory === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    item.style.display = 'flex'; // Gunakan flex untuk tata letak kolom
                    foundMenus++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (foundMenus === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', filterMenu);
    </script>
</body>
</html>
