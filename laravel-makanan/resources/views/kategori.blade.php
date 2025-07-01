<!DOCTYPE html>
<html>
<head>
    <title>Kategori Makanan</title>
</head>
<body style="font-family: sans-serif; max-width: 700px; margin: 30px auto; background: #f5f5f5; padding: 30px; border-radius: 12px;">

    <h2 style="text-align:center; color:#333;">Kategori Makanan</h2>

    {{-- FORM TAMBAH / EDIT --}}
    <form action="{{ isset($kategori) ? '/kategori/update/' . $kategori->id : '/kategori' }}" method="POST" style="margin-top: 20px;">
        @csrf
        <input type="text" name="nama" placeholder="Nama kategori"
               value="{{ isset($kategori) ? $kategori->nama : '' }}"
               style="width: 100%; padding: 10px; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ccc;">
        <button type="submit"
                style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; border-radius: 6px;">
            {{ isset($kategori) ? 'Update' : 'Simpan' }}
        </button>
    </form>

    {{-- LIST KATEGORI --}}
    <h3 style="margin-top: 30px;">Daftar Kategori</h3>
    <ul style="list-style: none; padding-left: 0;">
        @foreach($kategoris as $kategori)
            <li style="background: white; padding: 10px 15px; border-radius: 6px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                {{ $kategori->nama }}

                <div>
                    <button onclick="openModal('editKategoriModal{{ $kategori->id }}')"
                            style="margin-right: 10px; color: blue; background: none; border: none; cursor: pointer;">Edit</button>

                    <button onclick="openModal('deleteKategoriModal{{ $kategori->id }}')"
                            style="color: red; background: none; border: none; cursor: pointer;">Hapus</button>
                </div>
            </li>

            {{-- Modal Edit --}}
            <div id="editKategoriModal{{ $kategori->id }}" class="modal">
                <div class="modal-content">
                    <h3>Edit Kategori</h3>
                    <form action="/kategori/update/{{ $kategori->id }}" method="POST">
                        @csrf
                        <input type="text" name="nama" value="{{ $kategori->nama }}" style="width: 100%; padding: 10px;">
                        <div style="margin-top: 10px;">
                            <button type="submit" style="background: #2196F3; color: white; padding: 8px 16px;">Simpan</button>
                            <button type="button" onclick="closeModal('editKategoriModal{{ $kategori->id }}')" style="margin-left: 10px;">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Modal Delete --}}
            <div id="deleteKategoriModal{{ $kategori->id }}" class="modal">
                <div class="modal-content">
                    <h3>Hapus Kategori?</h3>
                    <p>Apakah kamu yakin ingin menghapus kategori <strong>{{ $kategori->nama }}</strong>?</p>
                    <form action="/kategori/delete/{{ $kategori->id }}" method="POST">
                        @csrf
                        <button type="submit" style="background: red; color: white; padding: 8px 16px;">Ya, Hapus</button>
                        <button type="button" onclick="closeModal('deleteKategoriModal{{ $kategori->id }}')" style="margin-left: 10px;">Batal</button>
                    </form>
                </div>
            </div>
        @endforeach
    </ul>

    {{-- CSS Modal & Script --}}
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
            max-width: 400px;
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
