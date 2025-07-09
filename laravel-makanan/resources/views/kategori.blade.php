@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="row">
    {{-- Kolom Kiri: Form Tambah / Edit Kategori (30%) --}}
    <div class="col-lg-4 mb-4">
        <div class="card p-4 h-100"> {{-- h-100 agar tinggi card sama --}}
            {{-- Judul form akan berubah tergantung apakah ada $kategori yang sedang diedit --}}
            <h2 class="h4 card-header">{{ isset($kategori) ? 'Edit Kategori Makanan' : 'Tambah Kategori Makanan Baru' }}</h2>
            <div class="card-body">
                {{-- Form untuk Tambah/Edit Kategori --}}
                {{-- Aksi form akan berubah tergantung apakah ada $kategori yang sedang diedit --}}
                <form id="mainKategoriForm" action="{{ isset($kategori) ? '/kategori/update/' . $kategori->id : '/kategori' }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="namaKategori" class="form-label">Nama Kategori</label>
                        {{-- Tambahkan kelas is-invalid jika ada error validasi untuk 'nama' --}}
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="namaKategori" name="nama" placeholder="Nama kategori" value="{{ old('nama', isset($kategori) ? $kategori->nama : '') }}" required>
                        {{-- Tampilkan pesan error validasi untuk 'nama' --}}
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-success mt-3 w-100">
                        {{ isset($kategori) ? 'Update' : 'Simpan' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Daftar Kategori (70%) --}}
    <div class="col-lg-8 mb-4">
        <div class="card p-4 h-100"> {{-- h-100 agar tinggi card sama --}}
            <h3 class="h4 card-header">Daftar Kategori</h3>
            <div class="card-body">
                {{-- Tampilkan pesan sukses/error dari sesi --}}
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="list-group">
                    @forelse($kategoris as $kategoriItem) {{-- Ubah $kategori menjadi $kategoriItem untuk menghindari konflik nama dengan $kategori di atas --}}
                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap mb-3 p-3">
                            <span class="text-dark fw-bold">{{ $kategoriItem->nama }}</span>
                            <div class="d-flex mt-2 mt-md-0">
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $kategoriItem->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteKategoriModal{{ $kategoriItem->id }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editKategoriModal{{ $kategoriItem->id }}" tabindex="-1" aria-labelledby="editKategoriModalLabel{{ $kategoriItem->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editKategoriModalLabel{{ $kategoriItem->id }}">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    {{-- Form Update di dalam Modal --}}
                                    <form action="/kategori/update/{{ $kategoriItem->id }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            {{-- Tampilkan error validasi khusus untuk modal ini --}}
                                            @if ($errors->any() && old('kategori_id_for_modal') == $kategoriItem->id)
                                                <div class="alert alert-danger" role="alert">
                                                    @foreach ($errors->all() as $error)
                                                        <div>{{ $error }}</div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <label for="editNamaKategori{{ $kategoriItem->id }}" class="form-label">Nama Kategori</label>
                                                {{-- Tambahkan kelas is-invalid jika ada error validasi untuk 'nama' pada modal ini --}}
                                                <input type="text" class="form-control @if($errors->any() && old('kategori_id_for_modal') == $kategoriItem->id && $errors->has('nama')) is-invalid @endif" id="editNamaKategori{{ $kategoriItem->id }}" name="nama" value="{{ old('nama', $kategoriItem->nama) }}" required>
                                                {{-- Tampilkan pesan error validasi untuk 'nama' pada modal ini --}}
                                                @if($errors->any() && old('kategori_id_for_modal') == $kategoriItem->id)
                                                    @error('nama')
                                                        <div class="invalid-feedback d-block"> {{-- Gunakan d-block agar selalu terlihat jika ada error --}}
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Delete -->
                        <div class="modal fade" id="deleteKategoriModal{{ $kategoriItem->id }}" tabindex="-1" aria-labelledby="deleteKategoriModalLabel{{ $kategoriItem->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteKategoriModalLabel{{ $kategoriItem->id }}">Hapus Kategori?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah kamu yakin ingin menghapus kategori <strong>{{ $kategoriItem->nama }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="/kategori/delete/{{ $kategoriItem->id }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Tidak ada kategori makanan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk menampilkan modal "Tambah Kategori" jika ada error validasi
        // Ini akan memicu modal jika ada error dan tidak ada $kategori yang sedang diedit (mode tambah)
        @if ($errors->any() && !isset($kategori))
            var addKategoriModal = new bootstrap.Modal(document.getElementById('mainKategoriForm').closest('.card').querySelector('.modal.fade')); // Ini mungkin perlu disesuaikan jika modal tambah bukan di luar
            addKategoriModal.show();
        @endif

        // Logika untuk menampilkan modal "Edit Kategori" jika ada error validasi
        // Ini akan memicu modal edit yang spesifik jika ada error dan ID kategori cocok
        @if ($errors->any() && isset($kategori) && old('kategori_id_for_modal') == $kategori->id)
            var editKategoriModal = new bootstrap.Modal(document.getElementById('editKategoriModal{{ $kategori->id }}'));
            editKategoriModal.show();
        @endif

        // Tambahkan input hidden ke form modal edit saat submit jika ada error
        // Ini diperlukan agar old('kategori_id_for_modal') bisa bekerja
        document.querySelectorAll('.modal.fade[id^="editKategoriModal"] form').forEach(form => {
            form.addEventListener('submit', function() {
                const kategoriId = this.action.split('/').pop(); // Ambil ID dari URL aksi
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'kategori_id_for_modal';
                hiddenInput.value = kategoriId;
                this.appendChild(hiddenInput);
            });
        });

        // Pastikan modal "Tambah" terbuka jika ada error validasi saat submit form utama
        // Ini akan menangani kasus di mana form 'Tambah' gagal validasi dan halaman di-refresh.
        const mainKategoriForm = document.getElementById('mainKategoriForm');
        if (mainKategoriForm && mainKategoriForm.action.endsWith('/kategori') && @json($errors->any())) {
            const addModal = new bootstrap.Modal(document.getElementById('addUserModal')); // Asumsi ini adalah ID modal tambah
            addModal.show();
        }
    });
</script>
@endsection
