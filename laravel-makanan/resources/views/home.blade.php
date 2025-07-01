<!DOCTYPE html>
<html>
<head>
    <title>Daftar Menu Makanan</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            /* Gradasi pada background body, dominan biru navy */
            background: linear-gradient(to bottom, var(--primary-bg-start), var(--primary-bg-end));
            padding: 20px;
            color: var(--text-dark); /* Default body text color, akan dioverride oleh elemen di dalam container */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            /* Gradasi pada background container, tetap terang */
            background: linear-gradient(to bottom, var(--card-bg), #FDFDFE);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 30px;
        }

        .header-section {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-light);
        }

        .header-section h1 {
            font-size: 2.8em;
            color: var(--text-dark); /* Tetap biru navy gelap untuk di atas kartu putih */
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .header-section h3 {
            font-size: 1.5em;
            color: var(--accent-color); /* Tetap aksen biru terang untuk di atas kartu putih */
            margin-bottom: 15px;
            font-weight: 600;
        }

        .header-section p {
            font-size: 1.1em;
            color: var(--text-light); /* Tetap abu-abu kebiruan gelap untuk di atas kartu putih */
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
            color: var(--text-dark); /* Teks input navy */
        }

        .controls input[type="text"]::placeholder {
            color: var(--text-light); /* Placeholder biru-abu-abu */
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
            /* Gradasi pada background kartu */
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
            grid-column: 1 / -1;
            padding: 30px;
            background: var(--card-bg);
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(6, 0, 88, 0.05);
            color: var(--text-light);
            font-size: 1.2em;
        }

        /* Media queries */
        @media (min-width: 500px) {
            .menu-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
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
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (min-width: 992px) {
            .menu-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
    </style>
</head>
<body>
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
                <div class="menu-item" data-category="{{ $menu->kategori->nama }}" data-name="{{ $menu->nama }}">
                    {{-- Template untuk Gambar (Anda bisa mengganti URL gambar ini) --}}
                    <img src="https://via.placeholder.com/150/060058/E0EBF5?text={{ urlencode($menu->nama) }}" alt="{{ $menu->nama }}" class="menu-image">
                    <h3>{{ $menu->nama }}</h3>
                    <p class="category">{{ $menu->kategori->nama }}</p>
                    <p class="description">{{ $menu->deskripsi ?? 'Deskripsi belum tersedia.' }}</p>
                    <p class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                </div>
            @empty
                <p class="empty-message" id="noMenuFound">Tidak ada menu tersedia saat ini.</p>
            @endforelse
        </div>
        <p class="empty-message" id="noResults" style="display: none;">Menu tidak ditemukan.</p>
    </div>

    <script>
        function filterMenu() {
            const searchInput = document.getElementById('searchMenu');
            const filterCategory = document.getElementById('filterCategory');
            const menuItems = document.querySelectorAll('.menu-item');
            const noResultsMessage = document.getElementById('noResults');
            const noMenuFoundInitial = document.getElementById('noMenuFound');

            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = filterCategory.value.toLowerCase();

            let foundMenus = 0;

            if (noMenuFoundInitial) {
                noMenuFoundInitial.style.display = 'none';
            }

            menuItems.forEach(item => {
                const menuName = item.querySelector('h3').textContent.toLowerCase();
                const menuNameForSearch = item.getAttribute('data-name') ? item.getAttribute('data-name').toLowerCase() : menuName;
                const menuDescription = item.querySelector('.description').textContent.toLowerCase();
                const menuCategory = item.getAttribute('data-category').toLowerCase();

                const matchesSearch = menuNameForSearch.includes(searchTerm) || menuDescription.includes(searchTerm);
                const matchesCategory = selectedCategory === 'all' || menuCategory === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    item.style.display = 'flex';
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