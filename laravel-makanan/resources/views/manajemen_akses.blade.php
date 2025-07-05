@extends('layouts.app')

@section('title', 'Manajemen Akses Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                <h2 class="h4 card-header d-flex justify-content-between align-items-center">
                    Manajemen Akses Pengguna
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
                    </button>
                </h2>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Peran Saat Ini</th>
                                    <th>Ubah Peran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge
                                                @if($user->role == 'admin') bg-primary
                                                @elseif($user->role == 'kasir') bg-info text-dark
                                                @elseif($user->role == 'master') bg-success
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('manajemen.akses.update', $user->id) }}" method="POST">
                                                @csrf
                                                <select name="role" class="form-select form-select-sm">
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                    <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                    <option value="master" {{ $user->role == 'master' ? 'selected' : '' }}>Master</option>
                                                </select>
                                        </td>
                                        <td>
                                                <button type="submit" class="btn btn-sm btn-primary me-2">Update</button>
                                            </form>
                                            {{-- Tombol Hapus --}}
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Konfirmasi Hapus User --}}
                                    <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="deleteUserModalLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteUserModalLabel{{ $user->id }}">Konfirmasi Hapus Pengguna</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Apakah Anda yakin ingin menghapus pengguna <strong>{{ $user->name }} ({{ $user->email }})</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('manajemen.akses.destroy', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE') {{-- Penting untuk metode DELETE --}}
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="alert alert-info text-center py-3 rounded-3 shadow-sm" role="alert">
                                                <i class="bi bi-info-circle-fill me-2"></i> Tidak ada pengguna yang dapat dikelola.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Pengguna Baru --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('manajemen.akses.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Tab Error untuk validasi di dalam modal --}}
                    @if ($errors->any() && session('showAddUserModal'))
                        <div class="error-tab" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Peran</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="master" {{ old('role') == 'master' ? 'selected' : '' }}>Master</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styling untuk Tab Error (tambahan di sini agar bisa digunakan di modal) */
    .error-tab {
        background-color: #f8d7da; /* Warna latar belakang merah muda untuk error */
        color: #721c24; /* Warna teks merah gelap */
        border: 1px solid #f5c6cb;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        text-align: left;
        display: flex;
        align-items: flex-start; /* Align items to start for multi-line errors */
        font-size: 0.95rem;
    }

    .error-tab .bi {
        font-size: 1.5rem;
        margin-right: 0.75rem;
        flex-shrink: 0; /* Prevent icon from shrinking */
    }

    .error-tab div {
        flex-grow: 1; /* Allow text to take remaining space */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Logika untuk menampilkan modal tambah user jika ada error validasi
        @if ($errors->any() && session('showAddUserModal'))
            var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
            addUserModal.show();
        @endif
    });
</script>
@endsection