<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Raynor Admin</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root { color-scheme: light; }
        body {
            min-height: 100vh;
            font-family: Inter, Arial, sans-serif;
            background: linear-gradient(180deg, #eef3fb 0%, #f8fbff 100%);
            color: #1e2b4d;
        }
        h1,h2,h3,h4,h5,h6 { font-family: Poppins, Inter, sans-serif; }
        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(180deg, #0f1f3f 0%, #1a2e52 100%);
            color: #f3f6ff;
            box-shadow: 4px 0 32px rgba(15,31,63,.15);
            padding: 1.5rem 1.25rem;
            position: relative;
            overflow-y: auto;
        }
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.1), transparent);
        }
        .sidebar h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            letter-spacing: -0.5px;
        }
        .sidebar .nav-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: rgba(217, 230, 255, 0.5);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
            padding: 0 0.5rem;
        }
        .sidebar .nav-section:first-child .nav-label {
            margin-top: 0;
        }
        .sidebar .nav-link {
            color: #c5d4f1;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #3b6dd8 0%, #3060c0 100%);
            color: #fff;
            box-shadow: 0 8px 24px rgba(59, 109, 216, 0.25);
        }
        .sidebar .nav-link.active i {
            transform: scale(1.1);
        }
        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.12);
            transform: translateX(4px);
        }
        .sidebar .nav-link:hover i {
            transform: scale(1.15);
        }
        .content-area {
            padding: 30px;
            min-height: 100vh;
            background: #eff5fc;
        }
        .content-area .container-fluid { max-width: 1300px; }
        .alert {
            border-radius: 18px;
            box-shadow: 0 6px 24px rgba(15,23,42,.08);
        }
        .page-title {
            font-family: Poppins, Inter, sans-serif;
            font-size: 1.9rem;
            font-weight: 700;
            color: #1f3f70;
            margin-bottom: 16px;
        }
        .card {
            border: 0;
            border-radius: 18px;
            box-shadow: 0 18px 40px rgba(15,23,42,.06);
        }
        .card .card-header {
            background: #f4f8ff;
            border-bottom: 0;
        }
        .modal-content { border-radius: 22px; }
        .modal-header { border-radius: 22px 22px 0 0; }
        .modal-footer { border-radius: 0 0 22px 22px; }
        .modal-body p { font-size: 0.95rem; color: #374151; line-height: 1.7; }
        .form-control, .form-select {
            border-radius: 12px;
            box-shadow: none;
            border: 1px solid #d8e0ed;
        }
        .form-label { font-weight: 600; color: #344054; }
        .btn-primary {
            background: #315aa2;
            border-color: #315aa2;
            box-shadow: 0 10px 20px rgba(49,90,162,.18);
        }
        .btn-primary:hover {
            background: #274d8c;
            border-color: #274d8c;
        }
        .btn-success {
            background: #1d8a54;
            border-color: #1d8a54;
            box-shadow: 0 10px 20px rgba(29,138,84,.15);
        }
        .btn-success:hover {
            background: #166b42;
            border-color: #166b42;
        }
        .btn-outline-secondary {
            color: #4d5f80;
            border-color: #c4d0e5;
        }
        .btn-outline-secondary:hover {
            background: #edf2f7;
            border-color: #b8c6de;
        }
        .table thead th { border-bottom: 2px solid #edf2f7; }
        .table tbody tr:hover { background: #f7fbff; }
        .badge { border-radius: 12px; }
    </style>
    @stack('head')
</head>
<body>
    <div class="d-flex">
        <aside class="sidebar">
            <div class="mb-4" style="text-align: center;">
                <img src="{{ asset('images/logo-raynor.png') }}" alt="Raynor Logo" style="height: 48px; width: auto; margin-bottom: 0.5rem;">
                <small style="color: rgba(217, 230, 255, 0.6); font-size: 0.8rem; display: block;">Admin Panel</small>
            </div>
            
            <nav class="nav flex-column">
                <div class="nav-section">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class="nav-label">Master Data</div>
                <div class="nav-section">
                    @if(session('pegawai.jabatan') === 'Owner')
                        <a class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}" href="{{ route('pegawai.index') }}">
                            <i class="bi bi-people-fill"></i>
                            <span>Pegawai</span>
                        </a>
                    @endif
                    <a class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}" href="{{ route('customer.index') }}">
                        <i class="bi bi-person-badge-fill"></i>
                        <span>Customer</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>Products</span>
                    </a>
                </div>

                <div class="nav-label">Transaksi</div>
                <div class="nav-section">
                    <a class="nav-link {{ request()->routeIs('purchase_order.*') ? 'active' : '' }}" href="{{ route('purchase_order.index') }}">
                        <i class="bi bi-file-earmark-text-fill"></i>
                        <span>Purchase Order</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}" href="{{ route('invoice.index') }}">
                        <i class="bi bi-receipt-cutoff"></i>
                        <span>Invoice</span>
                    </a>
                </div>
            </nav>
        </aside>

        <div class="flex-grow-1 content-area bg-light">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm">
                <div class="modal-header bg-warning text-dark d-flex align-items-center gap-3">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white" style="width:44px;height:44px;">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4V6" stroke="#1f3f70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 18V20" stroke="#1f3f70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.93 4.93L6.34 6.34" stroke="#1f3f70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.66 17.66L19.07 19.07" stroke="#1f3f70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M4.93 19.07L6.34 17.66" stroke="#1f3f70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.66 6.34L19.07 4.93" stroke="#1f3f70" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="12" cy="12" r="5" stroke="#1f3f70" stroke-width="2"/>
                        </svg>
                    </span>
                    <div>
                        <h5 class="modal-title mb-0" id="confirmDeleteModalLabel">Konfirmasi</h5>
                        <small class="text-muted">Tindakan ini tidak dapat dibatalkan.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmDeleteMessage" class="mb-0">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmDeleteButton" class="btn btn-danger rounded-pill">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-sm">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorModalLabel">Gagal Menghapus Data</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ session('error') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var confirmModalEl = document.getElementById('confirmDeleteModal');
            var confirmDeleteModal = new bootstrap.Modal(confirmModalEl);
            var confirmDeleteMessage = document.getElementById('confirmDeleteMessage');
            var confirmDeleteButton = document.getElementById('confirmDeleteButton');
            var activeDeleteForm = null;

            document.querySelectorAll('form.confirm-delete').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    activeDeleteForm = form;
                    var title = form.dataset.confirmTitle || 'Konfirmasi Hapus';
                    var message = form.dataset.confirmMessage || 'Apakah Anda yakin ingin menghapus ' + (form.dataset.itemName || 'data ini') + '?';
                    document.getElementById('confirmDeleteModalLabel').textContent = title;
                    confirmDeleteMessage.textContent = message;
                    confirmDeleteModal.show();
                });
            });

            confirmDeleteButton.addEventListener('click', function () {
                if (activeDeleteForm) {
                    activeDeleteForm.submit();
                }
            });

            @if(session('error'))
                var errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
                errorModal.show();
            @endif
        });
    </script>
    @stack('scripts')
</body>
</html>
