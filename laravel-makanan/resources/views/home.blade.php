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
            --accent-orange: #FF8C00;
            --category-color: #555;
            --input-border: #ccc;
            --input-focus-border: #00796b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            max-width: 1200px;
            margin: 30px auto;
            background: var(--dark-blue);
            padding: 30px;
            border-radius: 12px;
            color: var(--text-dark);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            overflow-x: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .intro-section {
            background: var(--dark-blue);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            position: relative;
            z-index: 1;
        }

        .intro-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 3.5em;
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
            background: var(--light-cream);
            padding: 40px;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .menu-section h2.section-title {
            text-align: center;
            margin-bottom: 30px;
            color: var(--dark-blue);
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
            background-color: var(--dark-blue);
            border-radius: 2px;
        }

        /* Styling untuk Filter dan Pencarian */
        .controls {
            display: flex;
            flex-wrap: wrap; /* Untuk responsivitas di layar kecil */
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center; /* Pusatkan kontrol */
            align-items: center;
        }

        .controls input[type="text"],
        .controls select {
            padding: 12px 18px;
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s ease;
            width: 100%; /* Default 100% width for smaller screens */
            max-width: 300px; /* Max width for inputs */
            box-sizing: border-box;
        }

        .controls input[type="text"]:focus,
        .controls select:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 3px rgba(0, 121, 107, 0.2);
        }

        .controls select {
            background-color: white;
            cursor: pointer;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23000%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-6.5%200-13%205.7-13%2013%200%206.5%205.7%2013%2013%2013h255.2c6.5%200%2013-5.7%2013-13%200-4.5-1.7-8.8-5.4-12.8z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 15px top 50%, 0 0;
            background-size: 12px auto, 100%;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        .menu-item {
            background: #ffffff;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
        }

        .menu-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
        }

        .menu-item h3 {
            margin: 0 0 8px 0;
            color: var(--dark-blue);
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
            flex-grow: 1;
            margin-bottom: 15px;
        }

        .menu-item .price {
            color: var(--accent-orange);
            font-size: 1.5em;
            font-weight: 700;
            text-align: right;
            margin-top: auto;
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
                flex-direction: row;
                border-radius: 12px;
                overflow: hidden;
            }

            .intro-section {
                flex: 1;
                border-radius: 0;
                padding: 60px 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            .menu-section {
                flex: 2;
                border-radius: 0;
                padding: 60px 50px;
            }
            body {
                padding: 0;
            }

            .controls input[type="text"],
            .controls select {
                width: auto;
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
                    <div class="menu-item" data-category="{{ $menu->kategori->nama }}">
                        <h3>{{ $menu->nama }}</h3>
                        <p class="category"><strong>Kategori:</strong> {{ $menu->kategori->nama }}</p>
                        <p class="description">{{ $menu->deskripsi }}</p>
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
            const noMenuFoundInitial = document.getElementById('noMenuFound'); // Pesan awal jika DB kosong

            const searchTerm = searchInput.value.toLowerCase();
            const selectedCategory = filterCategory.value.toLowerCase();

            let foundMenus = 0;

            // Sembunyikan pesan awal jika ada filter/pencarian
            if (noMenuFoundInitial) {
                noMenuFoundInitial.style.display = 'none';
            }

            menuItems.forEach(item => {
                const menuName = item.querySelector('h3').textContent.toLowerCase();
                const menuDescription = item.querySelector('.description').textContent.toLowerCase();
                const menuCategory = item.getAttribute('data-category').toLowerCase();

                const matchesSearch = menuName.includes(searchTerm) || menuDescription.includes(searchTerm);
                const matchesCategory = selectedCategory === 'all' || menuCategory === selectedCategory;

                if (matchesSearch && matchesCategory) {
                    item.style.display = 'flex'; // Tampilkan item
                    foundMenus++;
                } else {
                    item.style.display = 'none'; // Sembunyikan item
                }
            });

            // Tampilkan atau sembunyikan pesan "Menu tidak ditemukan"
            if (foundMenus === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        }

        // Panggil filterMenu saat halaman dimuat untuk memastikan tampilan awal
        document.addEventListener('DOMContentLoaded', filterMenu);
    </script>
</body>
</html>