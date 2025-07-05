@extends('layouts.app') {{-- Pewarisan: Menggunakan layout dasar aplikasi.
Fungsi: Memungkinkan view ini untuk mewarisi struktur HTML, CSS, dan JavaScript dasar dari 'layouts.app'.
Cara kerja: Blade engine akan menyertakan konten dari 'layouts.app' dan mengisi bagian `@yield` dengan konten dari view ini. --}}

@section('title', 'Histori Pesanan') {{-- Abstraksi: Mengisi bagian 'title' dari layout dasar.
Fungsi: Menyediakan judul spesifik untuk halaman ini tanpa perlu mengubah file layout utama.
Cara kerja: Konten 'Histori Pesanan' akan ditempatkan pada `@yield('title')` di `layouts.app`. --}}

@section('content') {{-- Abstraksi: Mendefinisikan konten utama untuk bagian 'content' dari layout dasar.
Fungsi: Mengisolasi konten spesifik halaman ini dari struktur layout keseluruhan.
Cara kerja: Semua kode HTML di dalam `@section('content')` akan ditempatkan pada `@yield('content')` di `layouts.app`. --}}
<div class="container-fluid">
<div class="row">
   <div class="col-lg-12">
       <div class="card p-4">
           <h2 class="h4 card-header">Histori Pesanan (Selesai & Dibatalkan)</h2>
           <div class="card-body">
               @if (session('success'))
                   <div class="alert alert-success" role="alert">
                       {{ session('success') }}
                   </div>
               @endif

               {{-- Filter Section --}}
               {{-- Enkapsulasi: Bagian filter ini mengelompokkan elemen-elemen input (select) dan tombol
                    yang terkait dengan fungsionalitas pemfilteran histori pesanan. --}}
               <div class="row mb-4 g-3 align-items-end">
                   <div class="col-md-3">
                       <label for="filterMonth" class="form-label">Bulan</label>
                       <select class="form-select" id="filterMonth">
                           <option value="all">Semua Bulan</option>
                           @for ($i = 1; $i <= 12; $i++)
                               <option value="{{ sprintf('%02d', $i) }}" {{ request('month') == sprintf('%02d', $i) ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                           @endfor
                       </select>
                   </div>
                   <div class="col-md-3">
                       <label for="filterYear" class="form-label">Tahun</label>
                       <select class="form-select" id="filterYear">
                           <option value="all">Semua Tahun</option>
                           @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                               <option value="{{ $i }}" {{ request('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                           @endfor
                       </select>
                   </div>
                   <div class="col-md-3">
                       <label for="filterStatus" class="form-label">Status</label>
                       <select class="form-select" id="filterStatus">
                           <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                           <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                           <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                       </select>
                   </div>
                   <div class="col-md-3 d-flex justify-content-end">
                       <button id="applyFilterBtn" class="btn btn-primary me-2"><i class="bi bi-funnel-fill"></i> Terapkan Filter</button>
                       <button id="printRekapBtn" class="btn btn-info"><i class="bi bi-printer-fill"></i> Cetak Rekap</button>
                   </div>
               </div>

               <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="historyOrdersContainer">
                   @forelse($historyOrders as $order)
                       {{-- Enkapsulasi: Setiap div 'order-card' mengemas detail satu pesanan histori.
                            Data atribut (`data-month`, `data-year`, `data-status`) adalah contoh enkapsulasi
                            data yang terkait dengan elemen DOM. --}}
                       <div class="col order-card"
                            data-month="{{ $order->updated_at->format('m') }}"
                            data-year="{{ $order->updated_at->format('Y') }}"
                            data-status="{{ $order->status }}">
                           <div class="card h-100 shadow-sm">
                               <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center rounded-top-3">
                                   <div>
                                       <strong>Pesanan #{{ $order->id }}</strong>
                                       <span class="ms-2">Pemesan: {{ $order->nama_pemesan }}</span>
                                       @if($order->meja_nomor)
                                           <span class="ms-2">Meja: {{ $order->meja_nomor }}</span>
                                       @endif
                                   </div>
                                   <div>
                                       Status: <span class="badge {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-dark') }}">{{ ucfirst($order->status) }}</span>
                                   </div>
                               </div>
                               <div class="card-body d-flex flex-column">
                                   <ul class="list-group list-group-flush flex-grow-1">
                                       @foreach($order->orderItems as $item)
                                           <li class="list-group-item d-flex justify-content-between align-items-center">
                                               {{ $item->menu->nama }} x {{ $item->quantity }}
                                               <span class="badge bg-info text-dark">Rp{{ number_format($item->harga_per_item * $item->quantity, 0, ',', '.') }}</span>
                                           </li>
                                       @endforeach
                                   </ul>
                                   <div class="mt-3 text-end">
                                       <h5>Total Harga: <span class="text-success">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span></h5>
                                   </div>
                                   <div class="mt-3 text-muted small">
                                       Diproses pada: {{ $order->updated_at->format('d M Y H:i') }}
                                   </div>
                               </div>
                           </div>
                       </div>
                   @empty
                       <div class="col-12" id="noHistoryMessage">
                           <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                               <i class="bi bi-info-circle-fill me-2"></i> Belum ada pesanan dalam histori yang cocok dengan filter.
                           </div>
                       </div>
                   @endforelse
               </div>
           </div>
       </div>
   </div>
</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
   // Enkapsulasi: Variabel-variabel ini mengacu pada elemen DOM yang relevan, mengelompokkan mereka.
   const filterMonth = document.getElementById('filterMonth');
   const filterYear = document.getElementById('filterYear');
   const filterStatus = document.getElementById('filterStatus');
   const applyFilterBtn = document.getElementById('applyFilterBtn');
   const printRekapBtn = document.getElementById('printRekapBtn');
   const orderCards = document.querySelectorAll('.order-card'); // Ini untuk filter visual di halaman
   const historyOrdersContainer = document.getElementById('historyOrdersContainer');
   const noHistoryMessage = document.getElementById('noHistoryMessage'); // Menggunakan ID ini untuk pesan default


   // Enkapsulasi: Fungsi ini mengemas logika untuk menerapkan filter dengan memodifikasi URL.
   // Fungsi: Mengambil nilai filter dari dropdown dan membangun URL baru untuk memuat ulang halaman
   //        dengan filter yang diterapkan. Ini memastikan filter persisten melalui refresh halaman.
   // Cara kerja: Membangun objek `URL`, menambahkan/menghapus parameter pencarian (`searchParams`),
   //             lalu mengarahkan `window.location.href` ke URL yang baru.
   function applyFilters() {
       const month = filterMonth.value;
       const year = filterYear.value;
       const status = filterStatus.value;

       let url = new URL(window.location.href.split('?')[0]); // Abstraksi: Mengambil base URL.
       if (month !== 'all') url.searchParams.set('month', month); else url.searchParams.delete('month');
       if (year !== 'all') url.searchParams.set('year', year); else url.searchParams.delete('year');
       if (status !== 'all') url.searchParams.set('status', status); else url.searchParams.delete('status');

       window.location.href = url.toString();
   }

   // Enkapsulasi: Event listener untuk tombol "Terapkan Filter".
   applyFilterBtn.addEventListener('click', applyFilters);

   // Enkapsulasi: Event listener ini mengemas logika untuk mencetak rekap laporan.
   // Fungsi: Mengambil nilai filter saat ini dan membangun URL untuk rute pencetakan rekap,
   //        kemudian membuka URL tersebut di jendela baru.
   // Cara kerja: Mirip dengan `applyFilters`, tetapi mengarahkan ke rute cetak (`historipesanan.printRekap`)
   //             dan membuka jendela baru (`window.open`).
   printRekapBtn.addEventListener('click', function() {
       const month = filterMonth.value;
       const year = filterYear.value;
       const status = filterStatus.value;

       // Abstraksi: Construct the URL for the print rekap route with filters.
       // Polymorphism: `route()` helper Laravel akan menghasilkan URL yang berbeda
       //              tergantung pada parameter yang diberikan.
       let printUrl = "{{ route('historipesanan.printRekap') }}";
       const params = [];
       if (month !== 'all') params.push(`month=${month}`);
       if (year !== 'all') params.push(`year=${year}`);
       if (status !== 'all') params.push(`status=${status}`);

       if (params.length > 0) {
           printUrl += '?' + params.join('&');
       }

       // Enkapsulasi: Open the URL in a new window for printing.
       const printWindow = window.open(printUrl, '_blank');
       printWindow.focus();
   });

   // Enkapsulasi: Logika untuk menampilkan/menyembunyikan pesan "Tidak ada histori"
   // Fungsi: Memastikan pesan "Belum ada pesanan dalam histori" hanya muncul jika tidak ada kartu pesanan yang ditampilkan.
   // Cara kerja: Memeriksa jumlah `orderCards` dan menyesuaikan properti `display` dari `noHistoryMessage`.
   if (orderCards.length === 0 && noHistoryMessage) {
        noHistoryMessage.style.display = 'block';
   } else if (noHistoryMessage) {
       noHistoryMessage.style.display = 'none'; // Sembunyikan jika ada kartu
   }
});
</script>
@endsection
