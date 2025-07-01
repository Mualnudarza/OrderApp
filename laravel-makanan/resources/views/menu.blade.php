<!DOCTYPE html>
<html>
<head>
    <title>Menu Makanan</title>
</head>
<body style="font-family: sans-serif; max-width: 800px; margin: 30px auto; background: #f9f9f9; padding: 30px; border-radius: 12px;">

    <h2 style="text-align:center; color:#333;">Tambah Menu Makanan</h2>

    <form action="{{ isset($menu) ? '/menu/update/' . $menu->id : '/menu' }}" method="POST" style="margin-top: 20px;">
        @csrf
        <input type="text" name="nama" placeholder="Nama makanan" value="{{ isset($menu) ? $menu->nama : '' }}"
               style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc;">
        <input type="number" name="harga" placeholder="Harga" value="{{ isset($menu) ? $menu->harga : '' }}"
               style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc;">
        <textarea name="deskripsi" placeholder="Deskripsi"
                  style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc;">{{ isset($menu) ? $menu->deskripsi : '' }}</textarea>
        <select name="kategori_id" style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc;">
            @foreach($kategoris as $kategori)
                <option value="{{ $kategori->id }}" {{ isset($menu) && $menu->kategori_id == $kategori->id ? 'selected' : '' }}>
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>
        <button type="submit"
                style="padding: 10px 20px; background-color: #2196F3; color: white; border: none; border-radius: 6px;">
            {{ isset($menu) ? 'Update' : 'Simpan' }}
        </button>
    </form>

    <h3 style="margin-top: 30px;">Filter Berdasarkan Kategori</h3>
    <ul style="display: flex; flex-wrap: wrap; list-style: none; padding-left: 0;">
        @foreach($kategoris as $kategori)
            <li style="margin: 5px;">
                <a href="/menu/kategori/{{ $kategori->id }}"
                   style="display: inline-block; padding: 8px 12px; background: #eee; border-radius: 6px; text-decoration: none; color: #333;">
                    {{ $kategori->nama }}
                </a>
            </li>
        @endforeach
    </ul>

    <h3 style="margin-top: 30px;">Daftar Menu {{ isset($kategoriAktif) ? 'Kategori: ' . $kategoriAktif->nama : '' }}</h3>
    <ul style="list-style: none; padding-left: 0;">
        @forelse($menus as $menu)
            <li style="background: white; padding: 15px; margin-bottom: 10px; border-radius: 8px; box-shadow: 0 0 5px rgba(0,0,0,0.05);">
                <strong style="font-size: 16px;">{{ $menu->nama }}</strong>
                <span style="color: #888;"> - Rp{{ number_format($menu->harga, 0, ',', '.') }}</span><br>
                <em style="color: #666;">Kategori: {{ $menu->kategori->nama }}</em><br>
                <p style="margin-top: 5px;">{{ $menu->deskripsi }}</p>

                <button onclick="openModal('editMenuModal{{ $menu->id }}')" style="color: blue; background: none; border: none; cursor: pointer;">Edit</button>
                <button onclick="openModal('deleteMenuModal{{ $menu->id }}')" style="color: red; background: none; border: none; cursor: pointer;">Hapus</button>
            </li>

            {{-- Modal Edit --}}
            <div id="editMenuModal{{ $menu->id }}" class="modal">
                <div class="modal-content">
                    <h3>Edit Menu</h3>
                    <form action="/menu/update/{{ $menu->id }}" method="POST">
                        @csrf
                        <input type="text" name="nama" value="{{ $menu->nama }}" style="width: 100%; padding: 10px; margin-bottom: 10px;">
                        <input type="number" name="harga" value="{{ $menu->harga }}" style="width: 100%; padding: 10px; margin-bottom: 10px;">
                        <textarea name="deskripsi" style="width: 100%; padding: 10px; margin-bottom: 10px;">{{ $menu->deskripsi }}</textarea>
                        <select name="kategori_id" style="width: 100%; padding: 10px;">
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ $menu->kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        <div style="margin-top: 10px;">
                            <button type="submit" style="background: #2196F3; color: white; padding: 8px 16px;">Simpan</button>
                            <button type="button" onclick="closeModal('editMenuModal{{ $menu->id }}')" style="margin-left: 10px;">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Delete --}}
            <div id="deleteMenuModal{{ $menu->id }}" class="modal">
                <div class="modal-content">
                    <h3>Hapus Menu?</h3>
                    <p>Apakah kamu yakin ingin menghapus menu <strong>{{ $menu->nama }}</strong>?</p>
                    <form action="/menu/delete/{{ $menu->id }}" method="POST">
                        @csrf
                        <button type="submit" style="background: red; color: white; padding: 8px 16px;">Ya, Hapus</button>
                        <button type="button" onclick="closeModal('deleteMenuModal{{ $menu->id }}')" style="margin-left: 10px;">Batal</button>
                    </form>
                </div>
            </div>
        @empty
            <li>Tidak ada menu makanan untuk kategori ini.</li>
        @endforelse
    </ul>

    {{-- Modal CSS & Script --}}
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0; top: 0; width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
            max-width: 450px;
            border-radius: 10px;
        }
    </style>
    <script>
        function openModal(id) {
            document.getElementById(id).style.display = "block";
        }
        function closeModal(id) {
            document.getElementById(id).style.display = "none";
        }
    </script>

</body>
</html>
