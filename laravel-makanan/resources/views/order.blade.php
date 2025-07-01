@extends('layouts.app')

@section('title', 'Pemesanan Menu')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card p-4">
                <h2 class="h4 card-header">Pesan Menu</h2>
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

                    <form id="orderForm" action="{{ route('order.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
                        </div>
                        <div class="mb-3">
                            <label for="meja_nomor" class="form-label">Nomor Meja (Opsional)</label>
                            <input type="text" class="form-control" id="meja_nomor" name="meja_nomor">
                        </div>

                        <h5 class="mt-4">Pilih Menu:</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Pilih</th>
                                        <th>Nama Menu</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Kuantitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($menus as $menu)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="menu-checkbox" data-menu-id="{{ $menu->id }}" data-menu-name="{{ $menu->nama }}" data-menu-price="{{ $menu->harga }}" onchange="toggleQuantity(this)">
                                            </td>
                                            <td>{{ $menu->nama }}</td>
                                            <td>{{ $menu->kategori->nama ?? 'Tidak Berkategori' }}</td>
                                            <td>Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                                            <td>
                                                <input type="number" class="form-control quantity-input" value="0" min="0" style="width: 80px;" disabled>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada menu tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-primary mt-3 w-100" data-bs-toggle="modal" data-bs-target="#invoiceModal" id="previewOrderBtn">
                            Buat Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Modal -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="invoiceContent">
                <!-- Invoice content will be loaded here by JavaScript -->
                <style>
                    .invoice-container {
                        font-family: 'Inter', sans-serif;
                        padding: 20px;
                        border: 1px solid #eee;
                        box-shadow: 0 0 10px rgba(0,0,0,0.1);
                        max-width: 800px;
                        margin: auto;
                        background: #fff;
                    }
                    .invoice-header {
                        text-align: center;
                        margin-bottom: 30px;
                    }
                    .invoice-header h2 {
                        color: #333;
                        font-weight: bold;
                    }
                    .invoice-details {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 20px;
                    }
                    .invoice-details div {
                        flex: 1;
                    }
                    .invoice-details p {
                        margin: 0;
                    }
                    .invoice-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 20px;
                    }
                    .invoice-table th, .invoice-table td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    .invoice-table th {
                        background-color: #f2f2f2;
                    }
                    .invoice-total {
                        text-align: right;
                        font-size: 1.2em;
                        font-weight: bold;
                    }
                    .invoice-footer {
                        text-align: center;
                        margin-top: 30px;
                        font-size: 0.9em;
                        color: #777;
                    }
                    @media print {
                        body * {
                            visibility: hidden;
                        }
                        #invoiceContent, #invoiceContent * {
                            visibility: visible;
                        }
                        #invoiceContent {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 100%;
                            padding: 0;
                            margin: 0;
                            border: none;
                            box-shadow: none;
                        }
                        .modal-footer, .modal-header .btn-close {
                            display: none;
                        }
                    }
                </style>
                <div class="invoice-container">
                    <div class="invoice-header">
                        <h2>INVOICE PEMESANAN</h2>
                        <p>PT. DWI WIRA USAHA BAKTI</p>
                    </div>
                    <div class="invoice-details">
                        <div>
                            <p><strong>Nama Pemesan:</strong> <span id="invoiceNamaPemesan"></span></p>
                            <p><strong>Nomor Meja:</strong> <span id="invoiceMejaNomor"></span></p>
                        </div>
                        <div>
                            <p><strong>Tanggal:</strong> <span id="invoiceDate"></span></p>
                            <p><strong>Waktu:</strong> <span id="invoiceTime"></span></p>
                        </div>
                    </div>
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Kuantitas</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItems">
                            <!-- Invoice items will be appended here -->
                        </tbody>
                    </table>
                    <div class="invoice-total">
                        Total: Rp<span id="invoiceTotalHarga"></span>
                    </div>
                    <div class="invoice-footer">
                        <p>Terima kasih atas pesanan Anda!</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info" id="printInvoiceBtn"><i class="bi bi-printer"></i> Cetak Invoice</button>
                <button type="button" class="btn btn-primary" id="processOrderBtn">Proses Pesanan</button>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleQuantity(checkbox) {
        const quantityInput = checkbox.closest('tr').querySelector('.quantity-input');
        if (checkbox.checked) {
            quantityInput.disabled = false;
            quantityInput.value = 1; // Set default quantity to 1 when checked
        } else {
            quantityInput.disabled = true;
            quantityInput.value = 0; // Reset quantity to 0 when unchecked
        }
    }

    document.getElementById('previewOrderBtn').addEventListener('click', function() {
        const namaPemesan = document.getElementById('nama_pemesan').value;
        const mejaNomor = document.getElementById('meja_nomor').value;
        const selectedMenus = [];
        let totalHarga = 0;

        document.querySelectorAll('.menu-checkbox:checked').forEach(checkbox => {
            const row = checkbox.closest('tr');
            const menuId = checkbox.dataset.menuId;
            const menuName = checkbox.dataset.menuName;
            const menuPrice = parseFloat(checkbox.dataset.menuPrice);
            const quantity = parseInt(row.querySelector('.quantity-input').value);

            if (quantity > 0) {
                const subtotal = menuPrice * quantity;
                selectedMenus.push({
                    id: menuId,
                    name: menuName,
                    price: menuPrice,
                    quantity: quantity,
                    subtotal: subtotal
                });
                totalHarga += subtotal;
            }
        });

        // Update modal content
        document.getElementById('invoiceNamaPemesan').textContent = namaPemesan;
        document.getElementById('invoiceMejaNomor').textContent = mejaNomor || '-';
        document.getElementById('invoiceDate').textContent = new Date().toLocaleDateString('id-ID');
        document.getElementById('invoiceTime').textContent = new Date().toLocaleTimeString('id-ID');

        const invoiceItemsBody = document.getElementById('invoiceItems');
        invoiceItemsBody.innerHTML = ''; // Clear previous items

        selectedMenus.forEach(item => {
            const row = `
                <tr>
                    <td>${item.name}</td>
                    <td>${item.quantity}</td>
                    <td>Rp${item.price.toLocaleString('id-ID')}</td>
                    <td>Rp${item.subtotal.toLocaleString('id-ID')}</td>
                </tr>
            `;
            invoiceItemsBody.insertAdjacentHTML('beforeend', row);
        });

        document.getElementById('invoiceTotalHarga').textContent = totalHarga.toLocaleString('id-ID');

        // Store data in a temporary variable for submission
        window.orderData = {
            nama_pemesan: namaPemesan,
            meja_nomor: mejaNomor,
            menu_items: selectedMenus,
            total_harga: totalHarga
        };
    });

    document.getElementById('processOrderBtn').addEventListener('click', function() {
        const form = document.getElementById('orderForm');
        const hiddenMenuIds = document.createElement('input');
        hiddenMenuIds.type = 'hidden';
        hiddenMenuIds.name = 'menu_ids';
        hiddenMenuIds.value = JSON.stringify(window.orderData.menu_items.map(item => item.id));
        form.appendChild(hiddenMenuIds);

        const hiddenQuantities = document.createElement('input');
        hiddenQuantities.type = 'hidden';
        hiddenQuantities.name = 'quantities';
        hiddenQuantities.value = JSON.stringify(window.orderData.menu_items.map(item => item.quantity));
        form.appendChild(hiddenQuantities);

        // Set nama_pemesan and meja_nomor from window.orderData
        document.getElementById('nama_pemesan').value = window.orderData.nama_pemesan;
        document.getElementById('meja_nomor').value = window.orderData.meja_nomor;

        form.submit(); // Submit the form
    });

    document.getElementById('printInvoiceBtn').addEventListener('click', function() {
        const printContents = document.getElementById('invoiceContent').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Reload to restore original page content and functionality
    });
</script>
@endsection

