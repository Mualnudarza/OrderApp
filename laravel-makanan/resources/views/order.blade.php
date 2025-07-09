@extends('layouts.app') {{-- Pewarisan: Menggunakan layout dasar aplikasi.
Fungsi: Memungkinkan view ini untuk mewarisi struktur HTML, CSS, dan JavaScript dasar dari 'layouts.app'.
Cara kerja: Blade engine akan menyertakan konten dari 'layouts.app' dan mengisi bagian `@yield` dengan konten dari view ini. --}}

@section('title', 'Pemesanan Menu') {{-- Abstraksi: Mengisi bagian 'title' dari layout dasar.
Fungsi: Menyediakan judul spesifik untuk halaman ini tanpa perlu mengubah file layout utama.
Cara kerja: Konten 'Pemesanan Menu' akan ditempatkan pada `@yield('title')` di `layouts.app`. --}}

@section('content') {{-- Abstraksi: Mendefinisikan konten utama untuk bagian 'content' dari layout dasar.
Fungsi: Mengisolasi konten spesifik halaman ini dari struktur layout keseluruhan.
Cara kerja: Semua kode HTML di dalam `@section('content')` akan ditempatkan pada `@yield('content')` di `layouts.app`. --}}
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

               {{-- Enkapsulasi: Form ini mengelompokkan semua input dan aksi terkait pemesanan.
                    Fungsi: Mengumpulkan informasi pemesan dan menu yang dipilih untuk diproses. --}}
               <form id="orderForm" action="{{ route('order.store') }}" method="POST">
                   @csrf {{-- Abstraksi: Direktif Blade ini secara otomatis menghasilkan token CSRF
                           untuk melindungi dari serangan Cross-Site Request Forgery. --}}
                   <div class="row mb-4">
                       <div class="col-md-6 mb-3 mb-md-0">
                           <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                           <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
                       </div>
                       <div class="col-md-6">
                           <label for="meja_nomor" class="form-label">Nomor Meja (Opsional)</label>
                           <input type="text" class="form-control" id="meja_nomor" name="meja_nomor">
                       </div>
                   </div>

                   <h5 class="mt-4">Daftar Menu Tersedia:</h5>
                   {{-- Enkapsulasi: Bagian filter dan pencarian menu. --}}
                   <div class="row mb-4 g-3">
                       <div class="col-md-6">
                           <input type="text" class="form-control" id="searchMenu" placeholder="Cari nama menu...">
                       </div>
                       <div class="col-md-6">
                           <select class="form-select" id="filterCategory">
                               <option value="all">Semua Kategori</option>
                               {{-- Abstraksi: Iterasi melalui objek $kategoris yang disediakan dari controller. --}}
                               @foreach($kategoris as $kategori)
                                   <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>

                   {{-- Enkapsulasi: Container untuk daftar item menu yang dapat dipilih. --}}
                   <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3 mb-4" id="menuListContainer">
                       @forelse($menus as $menu)
                           {{-- Enkapsulasi: Setiap div 'col menu-item' mengemas tampilan satu objek 'Menu'.
                                Data atribut (`data-menu-id`, `data-menu-name`, dll.) adalah bentuk enkapsulasi data
                                yang akan digunakan oleh JavaScript. --}}
                           <div class="col menu-item"
                                data-menu-id="{{ $menu->id }}"
                                data-menu-name="{{ $menu->nama }}"
                                data-menu-price="{{ $menu->harga }}"
                                data-category-id="{{ $menu->kategori->id ?? 'uncategorized' }}"
                                data-category-name="{{ $menu->kategori->nama ?? 'Tidak Berkategori' }}">
                               <div class="card h-100 shadow-sm">
                                   <div class="card-body d-flex flex-column">
                                       {{-- Abstraksi: Mengakses properti objek $menu secara langsung. --}}
                                       <h5 class="card-title mb-1">{{ $menu->nama }}</h5>
                                       <p class="card-text text-muted small mb-2">{{ $menu->kategori->nama ?? 'Tidak Berkategori' }}</p>
                                       <p class="card-text fw-bold text-primary fs-5 mt-auto">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                                       <div class="d-flex align-items-center mt-2">
                                           <input type="number" class="form-control quantity-input-item me-2" value="1" min="1" style="width: 80px;">
                                           <button type="button" class="btn btn-sm btn-success add-to-cart-btn w-100">
                                               <i class="bi bi-plus-circle"></i> Tambah
                                           </button>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       @empty
                           <div class="col-12">
                               <div class="alert alert-info text-center py-4 rounded-3 shadow-sm" role="alert">
                                   <i class="bi bi-info-circle-fill me-2"></i> Tidak ada menu tersedia.
                               </div>
                           </div>
                       @endforelse
                   </div>

                   <h5 class="mt-4">Keranjang Pesanan:</h5>
                   {{-- Enkapsulasi: Tabel keranjang pesanan. --}}
                   <div class="table-responsive mb-4">
                       <table class="table table-bordered table-striped">
                           <thead>
                               <tr>
                                   <th>Menu</th>
                                   <th>Kuantitas</th>
                                   <th>Harga Satuan</th>
                                   <th>Subtotal</th>
                                   <th>Aksi</th>
                               </tr>
                           </thead>
                           <tbody id="cartItemsBody">
                               <!-- Cart items will be dynamically added here -->
                               <tr id="emptyCartMessage">
                                   <td colspan="5" class="text-center text-muted">Keranjang kosong.</td>
                               </tr>
                           </tbody>
                           <tfoot>
                               <tr>
                                   <th colspan="3" class="text-end">Total Keseluruhan:</th>
                                   <th id="cartGrandTotal" class="text-end">Rp0</th>
                                   <th></th>
                               </tr>
                           </tfoot>
                       </table>
                   </div>

                   <button type="button" class="btn btn-primary mt-3 w-100" id="previewOrderBtn">
                       Buat Pesanan
                   </button>
               </form>
           </div>
       </div>
   </div>
</div>
</div>

<!-- Invoice Modal -->
{{-- Enkapsulasi: Modal invoice mengelompokkan tampilan detail pesanan sebelum dikirim. --}}
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
               /* Inline CSS for Invoice, consistent with previous design */
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

<!-- Validation Modal -->
{{-- Enkapsulasi: Modal validasi untuk pesan kesalahan. --}}
<div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
<div class="modal-dialog modal-sm">
   <div class="modal-content">
       <div class="modal-header">
           <h5 class="modal-title" id="validationModalLabel">Peringatan!</h5>
           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
       </div>
       <div class="modal-body">
           <p id="validationMessage"></p>
       </div>
       <div class="modal-footer">
           <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
       </div>
   </div>
</div>
</div>

<script>
// Objek untuk menyimpan item yang dipilih di keranjang
let cart = {}; {{-- Enkapsulasi Data: Objek 'cart' mengelompokkan semua item yang dipilih
                    dan kuantitasnya, menjaga state keranjang. --}}

document.addEventListener('DOMContentLoaded', function() {
   const menuItems = document.querySelectorAll('.menu-item');
   const searchInput = document.getElementById('searchMenu');
   const filterCategory = document.getElementById('filterCategory');
   const cartItemsBody = document.getElementById('cartItemsBody');
   const cartGrandTotal = document.getElementById('cartGrandTotal');
   const emptyCartMessage = document.getElementById('emptyCartMessage');
   const previewOrderBtn = document.getElementById('previewOrderBtn');
   const processOrderBtn = document.getElementById('processOrderBtn');
   const printInvoiceBtn = document.getElementById('printInvoiceBtn');
   const orderForm = document.getElementById('orderForm');
   const invoiceModalInstance = new bootstrap.Modal(document.getElementById('invoiceModal'));


   // Get validation modal elements
   const validationModal = new bootstrap.Modal(document.getElementById('validationModal'));
   const validationMessage = document.getElementById('validationMessage');


   // Fungsi untuk memfilter menu berdasarkan pencarian dan kategori
   function filterMenus() { {{-- Abstraksi Perilaku: Fungsi ini mengabstraksi logika filtering. --}}
       const searchTerm = searchInput.value.toLowerCase();
       const selectedCategoryId = filterCategory.value;

       menuItems.forEach(item => {
           const menuName = item.dataset.menuName.toLowerCase();
           const categoryId = item.dataset.categoryId;

           const matchesSearch = menuName.includes(searchTerm);
           const matchesCategory = selectedCategoryId === 'all' || categoryId === selectedCategoryId;

           // Polymorphism: Tampilan item berubah (display block/none) berdasarkan kondisi filter.
           if (matchesSearch && matchesCategory) {
               item.style.display = 'block'; // Tampilkan item
           } else {
               item.style.display = 'none'; // Sembunyikan item
           }
       });
   }

   // Fungsi untuk memperbarui tampilan keranjang pesanan
   function renderCart() { {{-- Abstraksi Perilaku: Fungsi ini mengabstraksi logika rendering keranjang. --}}
       cartItemsBody.innerHTML = ''; // Bersihkan isi keranjang
       let currentGrandTotal = 0;
       let hasItems = false;

       for (const menuId in cart) {
           const item = cart[menuId];
           if (item.quantity > 0) {
               hasItems = true;
               const row = `
                   <tr>
                       <td>${item.name}</td>
                       <td>
                           <input type="number" class="form-control form-control-sm cart-quantity-input"
                                  data-menu-id="${item.id}" value="${item.quantity}" min="1" style="width: 70px;">
                       </td>
                       <td>Rp${item.price.toLocaleString('id-ID')}</td>
                       <td>Rp${(item.price * item.quantity).toLocaleString('id-ID')}</td>
                       <td>
                           <button type="button" class="btn btn-danger btn-sm remove-from-cart-btn" data-menu-id="${item.id}">
                               <i class="bi bi-trash"></i>
                           </button>
                       </td>
                   </tr>
               `;
               cartItemsBody.insertAdjacentHTML('beforeend', row);
               currentGrandTotal += (item.price * item.quantity);
           }
       }

       cartGrandTotal.textContent = `Rp${currentGrandTotal.toLocaleString('id-ID')}`;

       // Polymorphism: Pesan keranjang kosong ditampilkan atau disembunyikan berdasarkan keberadaan item.
       if (hasItems) {
           emptyCartMessage.style.display = 'none';
       } else {
           emptyCartMessage.style.display = 'table-row'; // Display as table-row for correct layout
       }

       // Re-attach event listeners for dynamically added elements
       attachCartEventListeners(); {{-- Abstraksi Perilaku: Memanggil fungsi lain untuk melampirkan event listener. --}}
   }

   // Fungsi untuk melampirkan event listener ke tombol hapus dan input kuantitas di keranjang
   function attachCartEventListeners() { {{-- Abstraksi Perilaku: Mengelompokkan logika untuk event listener keranjang. --}}
       document.querySelectorAll('.remove-from-cart-btn').forEach(button => {
           button.onclick = function() {
               const menuId = this.dataset.menuId;
               delete cart[menuId]; // Hapus item dari objek keranjang
               renderCart(); // Perbarui tampilan
           };
       });

       document.querySelectorAll('.cart-quantity-input').forEach(input => {
           input.onchange = function() {
               const menuId = this.dataset.menuId;
               const newQuantity = parseInt(this.value);
               if (newQuantity > 0) {
                   cart[menuId].quantity = newQuantity;
                   cart[menuId].subtotal = cart[menuId].price * newQuantity;
               } else {
                   delete cart[menuId]; // Hapus jika kuantitas 0 atau kurang
               }
               renderCart(); // Perbarui tampilan
           };
       });
   }

   // Event listener untuk tombol "Tambah" pada setiap item menu
   document.querySelectorAll('.add-to-cart-btn').forEach(button => {
       button.addEventListener('click', function() {
           const menuItemDiv = this.closest('.menu-item');
           const menuId = menuItemDiv.dataset.menuId;
           const menuName = menuItemDiv.dataset.menuName;
           const menuPrice = parseFloat(menuItemDiv.dataset.menuPrice);
           const quantityInput = menuItemDiv.querySelector('.quantity-input-item');
           let quantity = parseInt(quantityInput.value);

           if (isNaN(quantity) || quantity <= 0) {
               quantity = 1; // Default ke 1 jika input tidak valid
               quantityInput.value = 1;
           }

           // Polymorphism: Objek 'cart' dapat menambahkan item baru atau memperbarui item yang sudah ada.
           if (cart[menuId]) {
               // Jika item sudah ada di keranjang, tambahkan kuantitasnya
               cart[menuId].quantity += quantity;
               cart[menuId].subtotal = cart[menuId].price * cart[menuId].quantity;
           } else {
               // Jika item belum ada, tambahkan ke keranjang
               cart[menuId] = {
                   id: menuId,
                   name: menuName,
                   price: menuPrice,
                   quantity: quantity,
                   subtotal: menuPrice * quantity
               };
           }
           renderCart(); // Perbarui tampilan keranjang
       });
   });

   // Event listener untuk pencarian dan filter
   searchInput.addEventListener('input', filterMenus);
   filterCategory.addEventListener('change', filterMenus);

   // Event listener untuk tombol "Buat Pesanan" (preview invoice)
   previewOrderBtn.addEventListener('click', function() {
       const namaPemesan = document.getElementById('nama_pemesan').value;
       const mejaNomor = document.getElementById('meja_nomor').value;

       // Enkapsulasi Validasi: Logika validasi dikelompokkan di sini.
       if (!namaPemesan.trim()) {
           validationMessage.textContent = 'Nama Pemesan harus diisi.';
           validationModal.show();
           return;
       }
       if (Object.keys(cart).length === 0) {
           validationMessage.textContent = 'Keranjang pesanan kosong. Silakan pilih menu terlebih dahulu.';
           validationModal.show();
           return;
       }

       // Update modal content
       document.getElementById('invoiceNamaPemesan').textContent = namaPemesan;
       document.getElementById('invoiceMejaNomor').textContent = mejaNomor || '-';
       document.getElementById('invoiceDate').textContent = new Date().toLocaleDateString('id-ID');
       document.getElementById('invoiceTime').textContent = new Date().toLocaleTimeString('id-ID');

       const invoiceItemsBody = document.getElementById('invoiceItems');
       invoiceItemsBody.innerHTML = ''; // Clear previous items

       let invoiceTotalHarga = 0;
       const menuItemsForSubmission = []; // Untuk dikirim ke server

       for (const menuId in cart) {
           const item = cart[menuId];
           const row = `
               <tr>
                   <td>${item.name}</td>
                   <td>${item.quantity}</td>
                   <td>Rp${item.price.toLocaleString('id-ID')}</td>
                   <td>Rp${item.subtotal.toLocaleString('id-ID')}</td>
               </tr>
           `;
           invoiceItemsBody.insertAdjacentHTML('beforeend', row);
           invoiceTotalHarga += item.subtotal;

           // Siapkan data untuk pengiriman ke server
           menuItemsForSubmission.push({
               id: item.id,
               quantity: item.quantity
           });
       }

       document.getElementById('invoiceTotalHarga').textContent = invoiceTotalHarga.toLocaleString('id-ID');

       // Simpan data untuk proses pengiriman form
       window.orderDataForSubmission = {
           nama_pemesan: namaPemesan,
           meja_nomor: mejaNomor,
           menu_ids: menuItemsForSubmission.map(item => item.id),
           quantities: menuItemsForSubmission.map(item => item.quantity)
       };

       // Show the invoice modal
       invoiceModalInstance.show();
   });

   // Event listener untuk tombol "Proses Pesanan" di modal
   processOrderBtn.addEventListener('click', function() {
       if (window.orderDataForSubmission) {
           // Sembunyikan modal invoice sebelum submit form
           invoiceModalInstance.hide();

           // Buat input hidden untuk menu_ids dan quantities
           const hiddenMenuIds = document.createElement('input');
           hiddenMenuIds.type = 'hidden';
           hiddenMenuIds.name = 'menu_ids';
           hiddenMenuIds.value = JSON.stringify(window.orderDataForSubmission.menu_ids);
           orderForm.appendChild(hiddenMenuIds);

           const hiddenQuantities = document.createElement('input');
           hiddenQuantities.type = 'hidden';
           hiddenQuantities.name = 'quantities';
           hiddenQuantities.value = JSON.stringify(window.orderDataForSubmission.quantities);
           orderForm.appendChild(hiddenQuantities);

           // Pastikan nama_pemesan dan meja_nomor terisi di form utama
           document.getElementById('nama_pemesan').value = window.orderDataForSubmission.nama_pemesan;
           document.getElementById('meja_nomor').value = window.orderDataForSubmission.meja_nomor;

           orderForm.submit(); // Kirim form
       } else {
           validationMessage.textContent = 'Tidak ada data pesanan untuk diproses. Silakan buat pesanan terlebih dahulu.';
           validationModal.show();
       }
   });

   // Event listener untuk tombol "Cetak Invoice" di modal
   printInvoiceBtn.addEventListener('click', function() {
       const printContents = document.getElementById('invoiceContent').innerHTML;

       // Buat jendela baru untuk pencetakan
       const printWindow = window.open('', '_blank');
       printWindow.document.write('<html><head><title>Invoice</title>');
       // Sertakan CSS yang relevan untuk pencetakan
       printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
       printWindow.document.write('<style>');
       printWindow.document.write(`
           body { font-family: 'Inter', sans-serif; margin: 0; padding: 20px; }
           .invoice-container {
               font-family: 'Inter', sans-serif;
               padding: 20px;
               border: 1px solid #eee;
               box-shadow: 0 0 10px rgba(0,0,0,0.1);
               max-width: 800px;
               margin: auto;
               background: #fff;
           }
           .invoice-header { text-align: center; margin-bottom: 30px; }
           .invoice-header h2 { color: #333; font-weight: bold; }
           .invoice-details { display: flex; justify-content: space-between; margin-bottom: 20px; }
           .invoice-details div { flex: 1; }
           .invoice-details p { margin: 0; }
           .invoice-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
           .invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
           .invoice-table th { background-color: #f2f2f2; }
           .invoice-total { text-align: right; font-size: 1.2em; font-weight: bold; }
           .invoice-footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #777; }
           @media print {
               .invoice-container { border: none; box-shadow: none; }
           }
       `);
       printWindow.document.write('</style>');
       printWindow.document.write('</head><body>');
       printWindow.document.write(printContents); // Masukkan konten invoice
       printWindow.document.write('</body></html>');
       printWindow.document.close(); // Penting untuk menutup dokumen
       printWindow.focus(); // Fokus ke jendela baru
       printWindow.print(); // Panggil fungsi cetak
       // printWindow.close(); // Opsional: tutup jendela setelah cetak (beberapa browser mungkin tidak mengizinkan ini secara otomatis)
   });

   // Render keranjang saat halaman dimuat (jika ada data yang tersimpan dari refresh)
   renderCart();
});
</script>
@endsection
