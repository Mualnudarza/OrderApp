@extends('layouts.app')

@section('title', 'Manajemen Akses Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                <h2 class="h4 card-header d-flex justify-content-between align-items-center">
                    Manajemen Akses Pengguna
                    <div>
                        <button type="button" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="bi bi-person-plus-fill me-1"></i> Tambah Pengguna Baru
                        </button>
                        <button type="button" class="btn btn-info btn-sm" id="toggleHistoryBtn">
                            <i class="bi bi-clock-history me-1"></i> Lihat Semua Histori Peran
                        </button>
                    </div>
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
                    @if (session('info'))
                        <div class="alert alert-info" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif

                    <div class="table-responsive mb-5">
                        <table class="table table-bordered table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->role == 'master' ? 'dark' : ($user->role == 'admin' ? 'primary' : 'secondary') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                {{-- Tombol Edit Role --}}
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-user-email="{{ $user->email }}"
                                                        data-user-role="{{ $user->role }}">
                                                    <i class="bi bi-pencil-square"></i> Edit Role
                                                </button>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('manajemen.akses.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <div class="alert alert-info text-center py-3 rounded-3 shadow-sm" role="alert">
                                                <i class="bi bi-info-circle-fill me-2"></i> Tidak ada pengguna lain yang terdaftar.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Bagian Histori Peran (awalnya tersembunyi) --}}
                    <div id="roleHistorySection" style="display: none;">
                        <h3 class="h5 card-title mb-3">Histori Perubahan Peran Pengguna</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>ID Histori</th>
                                        <th>Pengguna</th>
                                        <th>Peran Lama</th>
                                        <th>Peran Baru</th>
                                        <th>Diubah Oleh</th>
                                        <th>Diubah Pada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($allRoleHistories as $history)
                                        <tr>
                                            <td>{{ $history->id }}</td>
                                            <td>{{ $history->user->name ?? 'Pengguna Tidak Ditemukan' }}</td>
                                            <td>{{ $history->old_role ? ucfirst($history->old_role) : '-' }}</td>
                                            <td>{{ ucfirst($history->new_role) }}</td>
                                            <td>{{ $history->changedBy->name ?? 'Sistem/Tidak Diketahui' }}</td>
                                            <td>{{ $history->changed_at->format('d M Y H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Belum ada histori perubahan peran.</td>
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
                    @if ($errors->any() && session('form_type') == 'add_user')
                        <div class="error-tab alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="addName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="addName" name="name" required value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="addEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" name="email" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label for="addPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="addPassword" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="addPasswordConfirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="addPasswordConfirmation" name="password_confirmation" required>
                    </div>
                    <div class="mb-3">
                        <label for="addRole" class="form-label">Role</label>
                        <select class="form-select" id="addRole" name="role" required>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kasir" {{ old('role') == 'kasir' ? 'selected' : '' }}>Kasir</option>
                            <option value="master" {{ old('role') == 'master' ? 'selected' : '' }}>Master</option>
                        </select>
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

{{-- Modal Edit Pengguna --}}
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit Peran Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any() && session('form_type') == 'edit_user')
                        <div class="error-tab alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="editName" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editName" name="name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role</label>
                        <select class="form-select" id="editRole" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                            <option value="master">Master</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Role</button>
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
        // Logika untuk Modal Edit Pengguna
        const editUserModal = document.getElementById('editUserModal');
        editUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.dataset.userId;
            const userName = button.dataset.userName;
            const userEmail = button.dataset.userEmail;
            const userRole = button.dataset.userRole;

            const modalTitle = editUserModal.querySelector('.modal-title');
            const editUserForm = document.getElementById('editUserForm');
            const editNameInput = document.getElementById('editName');
            const editEmailInput = document.getElementById('editEmail');
            const editRoleSelect = document.getElementById('editRole');

            modalTitle.textContent = `Edit Peran untuk ${userName}`;
            editUserForm.action = `/manajemen-akses/update-role/${userId}`; // Set action URL
            editNameInput.value = userName;
            editEmailInput.value = userEmail;
            editRoleSelect.value = userRole;

            // Tambahkan input hidden untuk form_type saat modal edit dibuka
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'form_type';
            hiddenInput.value = 'edit_user';
            editUserForm.appendChild(hiddenInput);
        });

        // Logika untuk menampilkan/menyembunyikan bagian histori peran
        const toggleHistoryBtn = document.getElementById('toggleHistoryBtn');
        const roleHistorySection = document.getElementById('roleHistorySection');

        toggleHistoryBtn.addEventListener('click', function() {
            if (roleHistorySection.style.display === 'none') {
                roleHistorySection.style.display = 'block';
                this.innerHTML = '<i class="bi bi-eye-slash me-1"></i> Sembunyikan Histori Peran';
                this.classList.remove('btn-info');
                this.classList.add('btn-secondary');
            } else {
                roleHistorySection.style.display = 'none';
                this.innerHTML = '<i class="bi bi-clock-history me-1"></i> Lihat Semua Histori Peran';
                this.classList.remove('btn-secondary');
                this.classList.add('btn-info');
            }
        });

        // Tambahkan input hidden ke form tambah pengguna saat submit
        document.querySelector('#addUserModal form').addEventListener('submit', function() {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'form_type';
            hiddenInput.value = 'add_user';
            this.appendChild(hiddenInput);
        });
    });
</script>
@endsection
