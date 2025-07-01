@extends('layouts.app')

@section('title', 'Histori Pesanan')

@section('content')
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
        const filterMonth = document.getElementById('filterMonth');
        const filterYear = document.getElementById('filterYear');
        const filterStatus = document.getElementById('filterStatus');
        const applyFilterBtn = document.getElementById('applyFilterBtn');
        const printRekapBtn = document.getElementById('printRekapBtn');
        const orderCards = document.querySelectorAll('.order-card'); // Ini untuk filter visual di halaman
        const historyOrdersContainer = document.getElementById('historyOrdersContainer');
        const noHistoryMessage = document.getElementById('noHistoryMessage'); // Menggunakan ID ini untuk pesan default


        // Fungsi untuk menerapkan filter (mengubah URL dan memuat ulang halaman)
        function applyFilters() {
            const month = filterMonth.value;
            const year = filterYear.value;
            const status = filterStatus.value;

            let url = new URL(window.location.href.split('?')[0]); // Ambil base URL
            if (month !== 'all') url.searchParams.set('month', month); else url.searchParams.delete('month');
            if (year !== 'all') url.searchParams.set('year', year); else url.searchParams.delete('year');
            if (status !== 'all') url.searchParams.set('status', status); else url.searchParams.delete('status');

            window.location.href = url.toString();
        }

        applyFilterBtn.addEventListener('click', applyFilters);

        printRekapBtn.addEventListener('click', function() {
            const month = filterMonth.value;
            const year = filterYear.value;
            const status = filterStatus.value;

            // Construct the URL for the print rekap route with filters
            let printUrl = "{{ route('historipesanan.printRekap') }}";
            const params = [];
            if (month !== 'all') params.push(`month=${month}`);
            if (year !== 'all') params.push(`year=${year}`);
            if (status !== 'all') params.push(`status=${status}`);

            if (params.length > 0) {
                printUrl += '?' + params.join('&');
            }

            // Open the URL in a new window for printing
            const printWindow = window.open(printUrl, '_blank');
            printWindow.focus();
        });

        // Pada saat halaman dimuat, jika tidak ada order sama sekali, tampilkan pesan default
        if (orderCards.length === 0 && noHistoryMessage) {
             noHistoryMessage.style.display = 'block';
        } else if (noHistoryMessage) {
            noHistoryMessage.style.display = 'none'; // Sembunyikan jika ada kartu
        }
    });
</script>
@endsection
