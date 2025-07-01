@extends('layouts.app')

@section('title', 'Laporan Pesanan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                <h2 class="h4 card-header">Daftar Semua Pesanan</h2>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse($orders as $order)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Pesanan #{{ $order->id }}</strong>
                                    <span class="ms-3">Pemesan: {{ $order->nama_pemesan }}</span>
                                    @if($order->meja_nomor)
                                        <span class="ms-3">Meja: {{ $order->meja_nomor }}</span>
                                    @endif
                                </div>
                                <div>
                                    Status: <span class="badge {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }}">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
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
                    @empty
                        <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                            <i class="bi bi-info-circle-fill me-2"></i> Belum ada pesanan yang dibuat.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
