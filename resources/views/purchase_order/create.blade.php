@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Buat Purchase Order</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('purchase_order.storeInitial') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nomor Purchase Order</label>
                    <input name="id_po" class="form-control" required placeholder="Misal 001/PO/MMT/VI/2026">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal PO</label>
                    <input name="tgl_po" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pilih Pegawai</label>
                    <select name="id_pegawai" class="form-select">
                        @foreach($pegawais as $p)
                            <option value="{{ $p->id_pegawai }}">{{ $p->nama_pegawai }} ({{ $p->id_pegawai }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pilih Customer</label>
                    <div class="input-group">
                        <select id="id_customer" name="id_customer" class="form-select">
                            @foreach($customers as $c)
                                <option value="{{ $c->id_customer }}">{{ $c->nama_customer }} ({{ $c->id_customer }})</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
                            <i class="bi bi-plus"></i> Tambah
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Lanjutkan isi pesanan</button>
                <a href="{{ route('purchase_order.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Customer -->
<div class="modal fade" id="addCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Customer Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="quickCustomerForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Customer <span class="text-danger">*</span></label>
                        <input type="text" id="nama_customer" name="nama_customer" class="form-control" required>
                        <small class="text-danger" id="error-nama_customer"></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea id="alamat_customer" name="alamat_customer" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. HP</label>
                        <input type="text" id="hp_customer" name="hp_customer" class="form-control">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="submitCustomerBtn">Tambah Customer</button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('submitCustomerBtn').addEventListener('click', function() {
    const form = document.getElementById('quickCustomerForm');
    const formData = new FormData(form);

    fetch('{{ route("customer.storeQuick") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add new customer to select
            const select = document.getElementById('id_customer');
            const option = document.createElement('option');
            option.value = data.customer.id_customer;
            option.textContent = data.customer.nama_customer + ' (' + data.customer.id_customer + ')';
            select.appendChild(option);
            select.value = data.customer.id_customer;

            // Close modal and reset form
            bootstrap.Modal.getInstance(document.getElementById('addCustomerModal')).hide();
            form.reset();
            
            alert('Customer berhasil ditambahkan!');
        } else {
            alert('Gagal menambahkan customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
});
</script>

@endsection
