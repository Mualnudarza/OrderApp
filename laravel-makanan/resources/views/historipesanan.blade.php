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

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        @forelse($historyOrders as $order) {{-- Menggunakan $historyOrders --}}
                            <div class="col">
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
                            <div class="col-12">
                                <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                                    <i class="bi bi-info-circle-fill me-2"></i> Belum ada pesanan dalam histori.
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
