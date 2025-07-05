@extends('layouts.app') {{-- Pewarisan: Menggunakan layout dasar aplikasi.
Fungsi: Memungkinkan view ini untuk mewarisi struktur HTML, CSS, dan JavaScript dasar dari 'layouts.app'.
Cara kerja: Blade engine akan menyertakan konten dari 'layouts.app' dan mengisi bagian `@yield` dengan konten dari view ini. --}}

@section('title', 'Laporan Pesanan Aktif') {{-- Abstraksi: Mengisi bagian 'title' dari layout dasar.
Fungsi: Menyediakan judul spesifik untuk halaman ini tanpa perlu mengubah file layout utama.
Cara kerja: Konten 'Laporan Pesanan Aktif' akan ditempatkan pada `@yield('title')` di `layouts.app`. --}}

@section('content') {{-- Abstraksi: Mendefinisikan konten utama untuk bagian 'content' dari layout dasar.
Fungsi: Mengisolasi konten spesifik halaman ini dari struktur layout keseluruhan.
Cara kerja: Semua kode HTML di dalam `@section('content')` akan ditempatkan pada `@yield('content')` di `layouts.app`. --}}
<div class="container-fluid">
<div class="row">
   <div class="col-lg-12">
       <div class="card p-4">
           <h2 class="h4 card-header">Daftar Pesanan Aktif (Pending)</h2>
           <div class="card-body">
               @if (session('success'))
                   <div class="alert alert-success" role="alert">
                       {{ session('success') }}
                   </div>
               @endif

               {{-- Menggunakan row-cols untuk tata letak kartu dinamis --}}
               {{-- 1 kartu per baris di layar sangat kecil (xs) --}}
               {{-- 2 kartu per baris di layar kecil (sm) --}}
               {{-- 3 kartu per baris di layar sedang (md) dan lebih besar --}}
               <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                   @forelse($orders as $order)
                       {{-- Enkapsulasi: Setiap div 'col' ini mengemas tampilan satu objek 'Order'.
                            Semua data dan elemen UI yang terkait dengan satu pesanan dienkapsulasi dalam blok ini. --}}
                       <div class="col"> {{-- Setiap pesanan akan menjadi kolom di dalam baris --}}
                           <div class="card h-100 shadow-sm"> {{-- h-100 memastikan tinggi kartu seragam --}}
                               <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3">
                                   <div>
                                       {{-- Abstraksi: Mengakses properti objek $order secara langsung.
                                            Anda tidak perlu tahu bagaimana data ini diambil dari database,
                                            cukup bahwa objek $order menyediakannya. --}}
                                       <strong>Pesanan #{{ $order->id }}</strong>
                                       <span class="ms-2">Pemesan: {{ $order->nama_pemesan }}</span>
                                       @if($order->meja_nomor)
                                           <span class="ms-2">Meja: {{ $order->meja_nomor }}</span>
                                       @endif
                                   </div>
                                   <div>
                                       Status: <span class="badge bg-warning text-dark">{{ ucfirst($order->status) }}</span>
                                   </div>
                               </div>
                               <div class="card-body d-flex flex-column"> {{-- Menggunakan flex-column untuk konten --}}
                                   <ul class="list-group list-group-flush flex-grow-1"> {{-- flex-grow-1 agar daftar item mengambil ruang --}}
                                       @foreach($order->orderItems as $item)
                                           {{-- Abstraksi: Mengakses relasi `orderItems` dan kemudian relasi `menu` dari `orderItems`.
                                                Eloquent ORM mengabstraksi JOIN database yang diperlukan untuk ini. --}}
                                           <li class="list-group-item d-flex justify-content-between align-items-center">
                                               {{ $item->menu->nama }} x {{ $item->quantity }}
                                               <span class="badge bg-info text-dark">Rp{{ number_format($item->harga_per_item * $item->quantity, 0, ',', '.') }}</span>
                                           </li>
                                       @endforeach
                                   </ul>
                                   <div class="mt-3 text-end">
                                       <h5>Total Harga: <span class="text-success">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span></h5>
                                   </div>
                                   <div class="mt-3">
                                       {{-- Enkapsulasi: Form ini mengemas aksi untuk memperbarui status pesanan.
                                            Fungsi: Memungkinkan pengguna mengubah status pesanan (pending, completed, cancelled).
                                            Cara kerja: Ketika form disubmit, request POST dikirim ke rute `order.updateStatus`
                                                       dengan ID pesanan dan status baru. --}}
                                       <form action="{{ route('order.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                           @csrf {{-- Abstraksi: Direktif Blade ini secara otomatis menghasilkan token CSRF
                                                          untuk melindungi dari serangan Cross-Site Request Forgery. --}}
                                           <div class="input-group">
                                               <select name="status" class="form-select form-select-sm">
                                                   {{-- Polymorphism: Opsi-opsi ini merepresentasikan status yang berbeda,
                                                                    dan pilihan yang 'selected' akan bervariasi
                                                                    tergantung pada status objek $order saat ini. --}}
                                                   <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                   <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                                   <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                               </select>
                                               <button type="submit" class="btn btn-sm btn-outline-secondary">Update Status</button>
                                           </div>
                                       </form>
                                   </div>
                               </div>
                           </div>
                       </div>
                   @empty
                       {{-- Enkapsulasi: Blok ini mengemas pesan jika tidak ada pesanan. --}}
                       <div class="col-12"> {{-- Jika tidak ada pesanan, tampilkan pesan di satu kolom penuh --}}
                           <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                               <i class="bi bi-info-circle-fill me-2"></i> Belum ada pesanan yang dibuat.
                           </div>
                       </div>
                   @endforelse
               </div>
           </div>
       </div>
   </div>
</div>
</div>
@endsection
