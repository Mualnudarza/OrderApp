@extends('layouts.app')

@section('title', 'Manajemen Menu Makanan')

@section('content')
<div class="row">
    {{-- Kolom Kiri: Filter dan Tampil Data (70%) --}}
    <div class="col-lg-8 mb-4">
        {{-- Filter Berdasarkan Kategori --}}
        <div class="card p-4 mb-4">
            <h3 class="h4 card-header">Filter Berdasarkan Kategori</h3>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    @foreach($kategoris as $kategori)
                        <a href="/menu/kategori/{{ $kategori->id }}" class="btn btn-outline-secondary">
                            {{ $kategori->nama }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Daftar Menu --}}
        <div class="card p-4">
            <h3 class="h4 card-header">Daftar Menu {{ isset($kategoriAktif) ? 'Kategori: ' . $kategoriAktif->nama : '' }}</h3>
            <div class="card-body">
                <div class="list-group">
                    @forelse($menus as $menu)
                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap mb-3 p-3">
                            <div class="flex-grow-1 me-3">
                                <h5 class="mb-1 text-primary">{{ $menu->nama }} <small class="text-muted"> - Rp{{ number_format($menu->harga, 0, ',', '.') }}</small></h5>
                                <small class="d-block text-secondary mb-2">Kategori: {{ $menu->kategori->nama }}</small>
                                <p class="mb-0 text-dark">{{ $menu->deskripsi }}</p>
                            </div>
                            <div class="d-flex mt-2 mt-md-0">
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteMenuModal{{ $menu->id }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1" aria-labelledby="editMenuModalLabel{{ $menu->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editMenuModalLabel{{ $menu->id }}">Edit Menu</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/menu/update/{{ $menu->id }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="editNama{{ $menu->id }}" class="form-label">Nama Makanan</label>
                                                <input type="text" class="form-control" id="editNama{{ $menu->id }}" name="nama" value="{{ $menu->nama }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editHarga{{ $menu->id }}" class="form-label">Harga</label>
                                                <input type="number" class="form-control" id="editHarga{{ $menu->id }}" name="harga" value="{{ $menu->harga }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editDeskripsi{{ $menu->id }}" class="form-label">Deskripsi</label>
                                                <textarea class="form-control" id="editDeskripsi{{ $menu->id }}" name="deskripsi" rows="3">{{ $menu->deskripsi }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="editKategori{{ $menu->id }}" class="form-label">Kategori</label>
                                                <select class="form-select" id="editKategori{{ $menu->id }}" name="kategori_id" required>
                                                    @foreach($kategoris as $kategori)
                                                        <option value="{{ $kategori->id }}" {{ $menu->kategori_id == $kategori->id ? 'selected' : '' }}>
                                                            {{ $kategori->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
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
                        <div class="modal fade" id="deleteMenuModal{{ $menu->id }}" tabindex="-1" aria-labelledby="deleteMenuModalLabel{{ $menu->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteMenuModalLabel{{ $menu->id }}">Hapus Menu?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah kamu yakin ingin menghapus menu <strong>{{ $menu->nama }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="/menu/delete/{{ $menu->id }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Tidak ada menu makanan untuk kategori ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Form Tambah/Edit Menu Makanan (30%) --}}
    <div class="col-lg-4 mb-4">
        <div class="card p-4 h-100"> {{-- h-100 agar tinggi card sama --}}
            <h2 class="h4 card-header">Tambah / Edit Menu Makanan</h2>
            <div class="card-body">
                <form action="{{ isset($menu) ? '/menu/update/' . $menu->id : '/menu' }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Makanan</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama makanan" value="{{ isset($menu) ? $menu->nama : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" placeholder="Harga" value="{{ isset($menu) ? $menu->harga : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi" rows="3">{{ isset($menu) ? $menu->deskripsi : '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id" required>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ isset($menu) && $menu->kategori_id == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3 w-100">
                        {{ isset($menu) ? 'Update' : 'Simpan' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection