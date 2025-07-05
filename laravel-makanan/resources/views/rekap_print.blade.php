<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reportTitle ?? 'Rekap Laporan Histori Pesanan' }}</title> {{-- Abstraksi: Variabel $reportTitle menyediakan judul laporan secara dinamis. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Abstraksi: Variabel CSS dan kelas Bootstrap mengabstraksi detail styling,
           memungkinkan perubahan tampilan yang konsisten di seluruh laporan. */
        body {
            font-family: 'Inter', sans-serif;
            padding: 20px;
            color: #333;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .report-header h1 {
            color: #333;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-header p {
            margin: 0;
            font-size: 0.9em;
            color: #555;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .summary-box h5 {
            margin-top: 0;
            color: #5C6BC0;
        }
        .summary-box p {
            margin-bottom: 5px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .order-table th, .order-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top; /* Ensure content aligns to top */
        }
        .order-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        /* Styling for the order items list to make it more compact */
        .order-items-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.85em; /* Slightly smaller font for compactness */
        }
        .order-items-list li {
            margin-bottom: 1px; /* Reduce space between list items */
            line-height: 1.3; /* Adjust line height for better readability in compact space */
        }
        .text-status-completed { color: #198754; font-weight: bold; } /* Bootstrap success */
        .text-status-cancelled { color: #dc3545; font-weight: bold; } /* Bootstrap danger */
        .text-status-pending { color: #ffc107; font-weight: bold; } /* Bootstrap warning */

        @media print {
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt; /* Smaller base font size for print */
            }
            .container {
                width: 100%; /* Ensure container takes full width for print */
                max-width: 7.5in; /* Set a max-width suitable for A4/Letter paper */
                margin: 0 auto; /* Center the content */
            }
            .report-header, .summary-box, .order-table {
                box-shadow: none;
                border: none;
            }
            .order-table {
                font-size: 9pt; /* Smaller font for table content */
            }
            .order-table th, .order-table td {
                padding: 4px; /* Reduce padding in table cells */
            }
            .order-items-list {
                font-size: 8pt; /* Even smaller font for item list */
            }
            /* Ensure page breaks for long reports */
            .order-table tbody tr {
                page-break-inside: avoid;
            }
            .order-table thead {
                display: table-header-group;
            }
            .summary-box p {
                font-size: 0.8em; /* Smaller font for summary box text */
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="report-header">
            <h1>{{ $reportTitle }}</h1>
            <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }}</p>
            <p>PT. DWI WIRA USAHA BAKTI</p>
        </div>

        {{-- Enkapsulasi: Bagian ringkasan ini mengelompokkan data statistik laporan.
             Abstraksi: Variabel seperti $filteredOrders->count(), $totalCompletedOrders, dll.,
             menyediakan ringkasan tanpa perlu tahu bagaimana angka-angka ini dihitung di controller. --}}
        <div class="summary-box">
            <h5>Ringkasan Laporan</h5>
            <p><strong>Total Pesanan Ditemukan:</strong> {{ $filteredOrders->count() }}</p>
            <p><strong>Pesanan Selesai:</strong> {{ $totalCompletedOrders }}</p>
            <p><strong>Pesanan Dibatalkan:</strong> {{ $totalCancelledOrders }}</p>
            <p><strong>Total Pendapatan (Selesai):</strong> Rp{{ number_format($grandTotalRevenue, 0, ',', '.') }}</p>
        </div>

        @if($filteredOrders->isEmpty())
            <div class="alert alert-warning text-center">
                Tidak ada data pesanan yang cocok dengan filter yang dipilih.
            </div>
        @else
            {{-- Enkapsulasi: Tabel ini mengelompokkan semua detail pesanan individual. --}}
            <table class="order-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pemesan</th>
                        <th>Meja</th>
                        <th>Status</th>
                        <th>Total Harga</th>
                        <th>Item Pesanan</th>
                        <th>Diproses Pada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($filteredOrders as $order)
                        {{-- Abstraksi: Setiap baris tabel merepresentasikan satu objek 'Order'.
                             Blade mengakses properti objek ($order->id, $order->nama_pemesan, dll.)
                             tanpa perlu tahu bagaimana data tersebut diambil dari database. --}}
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->nama_pemesan }}</td>
                            <td>{{ $order->meja_nomor ?? '-' }}</td>
                            <td>
                                {{-- Polymorphism: Kelas CSS yang diterapkan pada span status (`text-status-{{ $order->status }}`)
                                     berubah secara dinamis berdasarkan nilai properti `status` dari objek `$order`.
                                     Ini adalah bentuk polymorphism dalam presentasi visual. --}}
                                <span class="text-status-{{ $order->status }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                {{-- Abstraksi: Mengakses relasi `orderItems` dan kemudian relasi `menu` dari setiap `orderItems`.
                                     Eloquent ORM mengabstraksi JOIN database yang diperlukan untuk mengambil data ini. --}}
                                <ul class="order-items-list">
                                    @foreach($order->orderItems as $item)
                                        <li>{{ $item->menu->nama }} (x{{ $item->quantity }}) - Rp{{ number_format($item->harga_per_item * $item->quantity, 0, ',', '.') }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $order->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>
