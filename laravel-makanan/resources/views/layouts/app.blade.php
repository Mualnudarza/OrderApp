<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT. DWI WIRA USAHA BAKTI - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #5C6BC0; /* A soft purple/blue for primary elements */
            --secondary-color: #f0f2f5; /* Light background */
            --card-background: #ffffff;
            --text-color-dark: #333;
            --text-color-light: #666;
            --border-radius-lg: 1.5rem; /* Larger border-radius */
            --box-shadow-light: 0 4px 12px rgba(0, 0, 0, 0.05); /* Soft shadow */
            --box-shadow-md: 0 8px 20px rgba(0, 0, 0, 0.08); /* Slightly stronger shadow */
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--secondary-color);
            margin: 0;
            padding: 0;
            display: flex; /* Flex container for sidebar and content */
            min-height: 100vh; /* Full viewport height */
            color: var(--text-color-dark);
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0; /* Prevent sidebar from shrinking */
            background-color: var(--card-background); /* White background for sidebar */
            padding: 2rem 1.5rem;
            border-radius: var(--border-radius-lg);
            margin: 2rem; /* Margin around the sidebar */
            box-shadow: var(--box-shadow-md); /* Apply shadow to sidebar */
            position: sticky;
            top: 2rem; /* Stick to top with margin */
            height: calc(100vh - 4rem); /* Adjust height to fit margins */
            overflow-y: auto; /* Enable scrolling if content overflows */
        }

        #sidebar::-webkit-scrollbar {
            display: none; /* Hide scrollbar for aesthetic */
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
        .sidebar-header .app-logo {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
        }
        .sidebar-header .app-logo i {
            margin-right: 0.5rem;
            font-size: 1.8rem;
        }

        .user-profile {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .user-profile img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 0.75rem;
            box-shadow: var(--box-shadow-light);
        }
        .user-profile h5 {
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }
        .user-profile p {
            font-size: 0.85rem;
            color: var(--text-color-light);
            margin-bottom: 0;
        }

        .sidebar-menu .menu-category {
            font-size: 0.8rem;
            color: var(--text-color-light);
            text-transform: uppercase;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
            padding-left: 1rem; /* Indent menu category */
        }
        .sidebar-menu .list-group-item {
            background-color: transparent;
            border: none;
            color: var(--text-color-dark);
            padding: 0.85rem 1rem;
            border-radius: 0.75rem; /* Rounded menu items */
            margin-bottom: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .sidebar-menu .list-group-item i {
            font-size: 1.25rem;
            margin-right: 1rem;
            color: var(--text-color-light);
        }
        .sidebar-menu .list-group-item:hover {
            background-color: var(--secondary-color); /* Lighter hover state */
            color: var(--primary-color);
        }
        .sidebar-menu .list-group-item:hover i {
            color: var(--primary-color);
        }
        .sidebar-menu .list-group-item.active {
            background-color: var(--primary-color);
            color: #fff;
            box-shadow: var(--box-shadow-light); /* Shadow for active item */
        }
        .sidebar-menu .list-group-item.active i {
            color: #fff;
        }

        /* Main Content Styling */
        #main-content {
            flex-grow: 1; /* Allow content to take remaining space */
            padding: 2rem;
        }

        /* Header (Navbar) Styling */
        .dashboard-header {
            background-color: var(--card-background);
            padding: 1.5rem 2rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-light);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dashboard-header .greeting h1 {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        .dashboard-header .greeting p {
            font-size: 0.9rem;
            color: var(--text-color-light);
            margin-bottom: 0;
        }
        .dashboard-header .header-actions {
            display: flex;
            align-items: center;
        }
        .dashboard-header .header-actions .search-box {
            position: relative;
            margin-right: 1.5rem;
        }
        .dashboard-header .header-actions .search-box input {
            border: none;
            background-color: var(--secondary-color); /* Background for search input */
            border-radius: 0.75rem;
            padding: 0.75rem 1.25rem;
            padding-left: 3rem; /* Space for icon */
            width: 250px;
            font-size: 0.95rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.03); /* Inner shadow */
        }
        .dashboard-header .header-actions .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-color-light);
        }
        .dashboard-header .header-actions .btn-add-project {
            background-color: var(--primary-color);
            color: #fff;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border: none;
            transition: background-color 0.2s ease-in-out;
            display: flex;
            align-items: center;
        }
        .dashboard-header .header-actions .btn-add-project i {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }
        .dashboard-header .header-actions .btn-add-project:hover {
            background-color: #495057; /* Darker on hover */
        }

        .header-icons .icon-button {
            background-color: var(--secondary-color);
            border: none;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-left: 1rem;
            color: var(--text-color-dark);
            font-size: 1.2rem;
            box-shadow: var(--box-shadow-light);
            transition: all 0.2s ease-in-out;
        }
        .header-icons .icon-button:hover {
            background-color: #e2e4e8;
            transform: translateY(-2px);
        }

        /* General Card Styling */
        .card {
            background-color: var(--card-background);
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-light);
            padding: 1.5rem;
            margin-bottom: 1.5rem; /* Consistent spacing between cards */
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color-dark);
        }

        /* Master Grid Layout */
        .master-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        .master-item {
            background-color: var(--card-background);
            border-radius: var(--border-radius-lg);
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            box-shadow: var(--box-shadow-light);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            text-decoration: none; /* Remove underline for links */
            color: inherit; /* Inherit text color */
        }

        .master-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-md);
        }

        .master-item i {
            font-size: 3.5rem; /* Larger icon size */
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .master-item span {
            font-weight: 600;
            font-size: 1.1rem;
            color: var(--text-color-dark);
        }

        /* Form Styling */
        .form-control, .form-select, .btn {
            border-radius: 0.75rem; /* Larger border-radius for form elements */
            padding: 0.75rem 1.25rem;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(92, 107, 192, 0.25); /* Primary color focus glow */
            border-color: var(--primary-color);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #48569a; /* Slightly darker primary on hover */
            border-color: #48569a;
        }
        .btn-success {
            background-color: #66BB6A; /* Green for success/save */
            border-color: #66BB6A;
        }
        .btn-success:hover {
            background-color: #5cb860;
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            border-width: 2px; /* Thicker border for outline buttons */
            border-radius: 0.75rem;
        }
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }
        .btn-outline-danger {
            color: #EF5350; /* Red for danger */
            border-color: #EF5350;
            border-width: 2px;
            border-radius: 0.75rem;
        }
        .btn-outline-danger:hover {
            background-color: #EF5350;
            color: #fff;
        }
        .btn-secondary {
            background-color: #B0BEC5; /* Gray for secondary */
            border-color: #B0BEC5;
        }
        .btn-secondary:hover {
            background-color: #92a4ad;
        }

        /* List Group Items (for menu/category listings) */
        .list-group-item {
            background-color: var(--secondary-color); /* Lighter background for list items */
            border: none;
            border-radius: 0.75rem;
            padding: 1rem 1.5rem;
            margin-bottom: 0.75rem;
            box-shadow: var(--box-shadow-light);
        }

        /* Modal Overrides */
        .modal-content {
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-md);
            padding: 1.5rem; /* Padding inside modal content */
        }
        .modal-header {
            border-bottom: none;
            padding: 0 0 1rem 0; /* Adjust header padding */
            margin-bottom: 1rem;
        }
        .modal-title {
            font-weight: 600;
            color: var(--text-color-dark);
        }
        .modal-body {
            padding: 0; /* Remove default body padding */
        }
        .modal-footer {
            border-top: none;
            padding: 1.5rem 0 0 0; /* Adjust footer padding */
            justify-content: flex-end;
        }

        /* Calendar Card Example (from the design - just for demonstration) */
        .calendar-card {
            background-color: var(--card-background);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-light);
            padding: 1.5rem;
            height: fit-content; /* Adjust height as needed */
        }
        .calendar-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        .calendar-card .card-header h5 {
            font-weight: 600;
            margin-bottom: 0;
        }
        .calendar-card .card-header i {
            font-size: 1.2rem;
            color: var(--text-color-light);
        }
        .event-list .event-date {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-color-dark);
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        .event-list .event-item {
            display: flex;
            margin-bottom: 0.75rem;
        }
        .event-list .event-time {
            font-size: 0.8rem;
            color: var(--text-color-light);
            width: 50px;
            flex-shrink: 0;
        }
        .event-list .event-details {
            margin-left: 0.75rem;
            padding-left: 0.75rem;
            border-left: 2px solid var(--primary-color); /* Event line */
        }
        .event-list .event-details strong {
            font-size: 0.95rem;
            display: block;
            margin-bottom: 0.2rem;
        }
        .event-list .event-details span {
            font-size: 0.8rem;
            color: var(--text-color-light);
        }
        /* Custom card colors for master grid example */
        .card-purple {
            background-color: #7B68EE; /* MediumSlateBlue */
            color: white;
        }
        .card-cyan {
            background-color: #20B2AA; /* LightSeaGreen */
            color: white;
        }
        .card-orange {
            background-color: #FFA500; /* Orange */
            color: white;
        }
        .card-purple i, .card-cyan i, .card-orange i {
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            #sidebar {
                position: fixed;
                left: -var(--sidebar-width);
                margin: 0;
                border-radius: 0;
                transition: left 0.3s ease-in-out;
                z-index: 1030; /* Above navbar */
                height: 100vh;
                top: 0;
                padding: 1.5rem;
                box-shadow: 0 0 20px rgba(0,0,0,0.2);
            }
            #sidebar.toggled {
                left: 0;
            }
            #main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
            .dashboard-header {
                flex-wrap: wrap;
            }
            .dashboard-header .greeting {
                flex-basis: 100%;
                margin-bottom: 1rem;
            }
            .dashboard-header .header-actions {
                flex-basis: 100%;
                justify-content: flex-start;
            }
            .dashboard-header .header-actions .search-box {
                margin-right: 0.5rem;
                flex-grow: 1;
            }
            .dashboard-header .header-actions .search-box input {
                width: 100%;
            }
            .dashboard-header .header-actions .header-icons {
                margin-left: auto;
            }
            .master-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            }
        }
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }
            #sidebar {
                top: 0;
                height: 100vh;
                left: -var(--sidebar-width);
                padding: 1rem;
            }
            #main-content {
                padding: 1rem;
            }
            .dashboard-header {
                padding: 1rem 1.5rem;
                margin-bottom: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div id="sidebar">
        <div class="sidebar-header">
            <span class="app-logo"><i class="bi bi-bar-chart-fill"></i> Chaart</span>
        </div>

        <div class="user-profile">
            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1964&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Sarah Connor">
            <h5>Sarah Connor</h5>
            <p>sarahc@gmail.com</p>
        </div>

        <div class="sidebar-menu">
            <div class="menu-category">MENU</div>
            {{-- Dashboard --}}
            <a href="/" class="list-group-item @if(Request::is('/')) active @endif">
                <i class="bi bi-house-door-fill"></i> Dashboard
            </a>
            {{-- Menu Makanan --}}
            <a href="/menu" class="list-group-item @if(Request::is('menu*')) active @endif">
                <i class="bi bi-egg-fried"></i> Menu
            </a>
            {{-- Kategori --}}
            <a href="/kategori" class="list-group-item @if(Request::is('kategori*')) active @endif">
                <i class="bi bi-bookmark-fill"></i> Kategori
            </a>
            {{-- Tracking (Tidak berubah) --}}
            <a href="/home" class="list-group-item @if(Request::is('home*')) active @endif">
                <i class="bi bi-compass"></i> Tracking
            </a>

            {{-- Menambahkan Menu Pemesanan, History Pesanan, dan Laporan Pemesanan --}}
            <a href="/pemesanan" class="list-group-item @if(Request::is('pemesanan*')) active @endif">
                <i class="bi bi-bag-check"></i> Pemesanan
            </a>
            <a href="/history-pesanan" class="list-group-item @if(Request::is('history-pesanan*')) active @endif">
                <i class="bi bi-clock-history"></i> History Pesanan
            </a>
            <a href="/laporan-pemesanan" class="list-group-item @if(Request::is('laporan-pemesanan*')) active @endif">
                <i class="bi bi-file-earmark-text"></i> Laporan Pemesanan
            </a>
            {{-- Akhir Penambahan Menu --}}

            <div class="menu-category">Setting</div>
            {{-- Setting (Tidak berubah) --}}
            <a href="#" class="list-group-item">
                <i class="bi bi-gear-fill"></i> Setting
            </a>
            {{-- Logout (Tidak berubah) --}}
            <a href="#" class="list-group-item">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
    <div id="main-content">
        <header class="dashboard-header">
            <div class="greeting">
                <h1>Hello, Sarah</h1>
                <p>Today is Monday, 20 October 2021</p>
            </div>
            <div class="header-actions">
                <div class="search-box d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <button class="btn btn-add-project d-none d-md-flex">
                    <i class="bi bi-plus"></i> Add New Project
                </button>
                <div class="header-icons ms-md-3">
                    <button class="icon-button"><i class="bi bi-bell"></i></button>
                    <button class="icon-button ms-2 d-md-none" id="mobileSidebarToggle"><i class="bi bi-list"></i></button>
                </div>
            </div>
        </header>

        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');

            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('toggled');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (sidebar.classList.contains('toggled') && !sidebar.contains(event.target) && !mobileSidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('toggled');
                }
            });
        });
    </script>
</body>
</html>