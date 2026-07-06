@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-0">Detail Purchase Order {{ $po->id_po }}</h1>
        <p class="text-muted mb-0">Ringkasan lengkap PO dan list produk yang dibeli.</p>
    </div>
    <div class="btn-group">
        @if(session('pegawai.jabatan') === 'Admin')
            <a href="{{ route('purchase_order.edit', ['id_po' => $po->id_po]) }}" class="btn btn-outline-secondary">Edit PO</a>
            <form method="POST" action="{{ route('purchase_order.destroy',['id_po' => $po->id_po]) }}" class="confirm-delete" data-confirm-title="Hapus Purchase Order" data-confirm-message="Apakah Anda yakin ingin menghapus Purchase Order {{ $po->id_po }}?" data-item-name="Purchase Order {{ $po->id_po }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus PO</button>
            </form>
        @endif
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">PO Info</h6>
                <p class="mb-1"><strong>ID PO</strong><br>{{ $po->id_po }}</p>
                <p class="mb-1"><strong>Tanggal</strong><br>{{ $po->tgl_po }}</p>
                <p class="mb-1"><strong>Status</strong><br><span class="badge bg-warning text-dark">Draft</span></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Customer</h6>
                <p class="mb-1"><strong>{{ $po->customer->nama_customer ?? $po->id_customer }}</strong></p>
                <p class="mb-1">{{ $po->customer->alamat_customer ?? '-' }}</p>
                <p class="mb-1">{{ $po->customer->hp_customer ?? '-' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">Pegawai</h6>
                <p class="mb-1"><strong>{{ $po->pegawai->nama_pegawai ?? $po->id_pegawai }}</strong></p>
                <p class="mb-1">{{ $po->pegawai->jabatan ?? '-' }}</p>
                <p class="mb-1">{{ $po->pegawai->hp_pegawai ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="h5 mb-3">Detail Produk</h3>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr><th>#</th><th>Produk</th><th>Qty</th><th>Size</th><th class="text-end">Harga Satuan</th><th class="text-end">Jumlah</th></tr>
                </thead>
                <tbody>
                    @forelse($po->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $detail->product->description_product ?? $detail->id_product }}</td>
                            <td>{{ $detail->qty }}</td>
                            <td>{{ $detail->size }}</td>
                            <td class="text-end">{{ number_format($detail->product->unit_price ?? 0,2) }}</td>
                            <td class="text-end">{{ number_format($detail->amount ?? 0,2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada detail produk untuk PO ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <div class="border rounded p-3 bg-light">
                    <div class="d-flex justify-content-between"><span>Subtotal</span><strong>{{ number_format($po->subtotal_po,2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>PPN</span><strong>{{ number_format($po->ppn_po,2) }}</strong></div>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Grand Total</span><strong>{{ number_format($po->grand_total_po,2) }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
