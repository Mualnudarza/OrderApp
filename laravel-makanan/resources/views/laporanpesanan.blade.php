@extends('layouts.app')

@section('title', 'Laporan Pesanan Aktif')

@section('content')
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
                            <div class="col"> {{-- Setiap pesanan akan menjadi kolom di dalam baris --}}
                                <div class="card h-100 shadow-sm"> {{-- h-100 memastikan tinggi kartu seragam --}}
                                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-3">
                                        <div>
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
                                            <form action="{{ route('order.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <div class="input-group">
                                                    <select name="status" class="form-select form-select-sm">
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
