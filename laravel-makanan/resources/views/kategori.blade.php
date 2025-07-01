@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="row">
    {{-- Kolom Kiri: Form Tambah / Edit Kategori (30%) --}}
    <div class="col-lg-4 mb-4">
        <div class="card p-4 h-100"> {{-- h-100 agar tinggi card sama --}}
            <h2 class="h4 card-header">Tambah / Edit Kategori Makanan</h2>
            <div class="card-body">
                <form action="{{ isset($kategori) ? '/kategori/update/' . $kategori->id : '/kategori' }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="namaKategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="namaKategori" name="nama" placeholder="Nama kategori" value="{{ isset($kategori) ? $kategori->nama : '' }}" required>
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
                <div class="list-group">
                    @forelse($kategoris as $kategori)
                        <div class="list-group-item d-flex justify-content-between align-items-center flex-wrap mb-3 p-3">
                            <span class="text-dark fw-bold">{{ $kategori->nama }}</span>
                            <div class="d-flex mt-2 mt-md-0">
                                <button type="button" class="btn btn-sm btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $kategori->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteKategoriModal{{ $kategori->id }}">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </div>

                        <!-- Modal Edit -->
                        <div class="modal fade" id="editKategoriModal{{ $kategori->id }}" tabindex="-1" aria-labelledby="editKategoriModalLabel{{ $kategori->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editKategoriModalLabel{{ $kategori->id }}">Edit Kategori</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="/kategori/update/{{ $kategori->id }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="editNamaKategori{{ $kategori->id }}" class="form-label">Nama Kategori</label>
                                                <input type="text" class="form-control" id="editNamaKategori{{ $kategori->id }}" name="nama" value="{{ $kategori->nama }}" required>
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
                        <div class="modal fade" id="deleteKategoriModal{{ $kategori->id }}" tabindex="-1" aria-labelledby="deleteKategoriModalLabel{{ $kategori->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteKategoriModalLabel{{ $kategori->id }}">Hapus Kategori?</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah kamu yakin ingin menghapus kategori <strong>{{ $kategori->nama }}</strong>?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="/kategori/delete/{{ $kategori->id }}" method="POST">
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
@endsection