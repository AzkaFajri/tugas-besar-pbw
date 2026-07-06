@extends('layouts.app')

@section('content')
<div class="mb-3">
    <a href="{{ route('invoice.index') }}" class="btn btn-secondary">← Kembali</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0">Buat Invoice Baru</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('invoice.store') }}" method="POST">
            @csrf
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_invoice" class="form-label">ID Invoice (Auto Generate)</label>
                    <input type="text" class="form-control @error('id_invoice') is-invalid @enderror" id="id_invoice" name="id_invoice" value="{{ $newIdInvoice }}" readonly>
                    @error('id_invoice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="tgl_invoice" class="form-label">Tanggal Invoice</label>
                    <input type="date" class="form-control @error('tgl_invoice') is-invalid @enderror" id="tgl_invoice" name="tgl_invoice" value="{{ now()->toDateString() }}" required>
                    @error('tgl_invoice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="id_po" class="form-label">Pilih Purchase Order</label>
                    <select class="form-select @error('id_po') is-invalid @enderror" id="id_po" name="id_po" required onchange="updateInvoiceDetails()">
                        <option value="">-- Pilih PO --</option>
                        @foreach($pos as $po)
                            <option value="{{ $po->id_po }}" data-customer="{{ $po->customer->nama_customer ?? '' }}" data-pegawai="{{ $po->pegawai->nama_pegawai ?? '' }}" data-subtotal="{{ $po->subtotal_po }}" data-ppn="{{ $po->ppn_po }}" data-grand="{{ $po->grand_total_po }}">
                                {{ $po->id_po }} - {{ $po->customer->nama_customer ?? '' }} ({{ number_format($po->grand_total_po, 2) }})
                            </option>
                        @endforeach
                    </select>
                    @error('id_po')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="customer" class="form-label">Customer</label>
                    <input type="text" class="form-control" id="customer" readonly>
                </div>
                <div class="col-md-6">
                    <label for="pegawai" class="form-label">Pegawai</label>
                    <input type="text" class="form-control" id="pegawai" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="subtotal" class="form-label">Subtotal</label>
                    <input type="text" class="form-control" id="subtotal" readonly>
                </div>
                <div class="col-md-4">
                    <label for="ppn" class="form-label">PPN</label>
                    <input type="text" class="form-control" id="ppn" readonly>
                </div>
                <div class="col-md-4">
                    <label for="grand" class="form-label">Grand Total</label>
                    <input type="text" class="form-control" id="grand" readonly>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="notes" class="form-label">Catatan (Opsional)</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" name="action" value="save" class="btn btn-primary">Simpan Invoice</button>
                <button type="submit" name="action" value="print" class="btn btn-success">Simpan & Cetak</button>
                <a href="{{ route('invoice.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function updateInvoiceDetails() {
    const select = document.getElementById('id_po');
    const option = select.options[select.selectedIndex];
    
    document.getElementById('customer').value = option.dataset.customer || '';
    document.getElementById('pegawai').value = option.dataset.pegawai || '';
    document.getElementById('subtotal').value = parseFloat(option.dataset.subtotal || 0).toFixed(2);
    document.getElementById('ppn').value = parseFloat(option.dataset.ppn || 0).toFixed(2);
    document.getElementById('grand').value = parseFloat(option.dataset.grand || 0).toFixed(2);
}
</script>
@endpush

@endsection
