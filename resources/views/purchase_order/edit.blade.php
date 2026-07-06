@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-1">PO {{ $po->id_po }}</h1>
        <p class="text-muted mb-0">Ubah item PO, lihat ringkasan, dan lanjutkan ke invoice.</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('purchase_order.show', ['id_po' => $po->id_po]) }}" class="btn btn-outline-primary">Lihat Detail</a>
        <a href="{{ route('purchase_order.index') }}" class="btn btn-outline-secondary">Selesai</a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="text-uppercase text-muted">PO Info</h6>
                <p class="mb-1"><strong>Tanggal</strong><br>{{ $po->tgl_po }}</p>
                <p class="mb-1"><strong>Customer</strong><br>{{ $po->customer->nama_customer ?? $po->id_customer }}</p>
                <p class="mb-1"><strong>Pegawai</strong><br>{{ $po->pegawai->nama_pegawai ?? $po->id_pegawai }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="h5 mb-1">Tambah Item</h3>
                <p class="text-muted mb-0">Pilih produk dan jumlah untuk ditambahkan ke PO.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('purchase_order.addItem',['id_po' => $po->id_po]) }}">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label">Product</label>
                    <select name="id_product" class="form-select">
                        @foreach($products as $pr)
                            <option value="{{ $pr->id_product }}">{{ $pr->description_product }} - {{ number_format($pr->unit_price,2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Qty</label>
                    <input name="qty" type="number" class="form-control" value="1" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Size</label>
                    <input name="size" class="form-control" placeholder="Contoh: L, XL" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Tambah</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="h5 mb-3">Detail Produk</h3>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Product</th><th>Qty</th><th>Size</th><th class="text-end">Unit Price</th><th class="text-end">Amount</th><th class="text-center">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($details as $d)
                        <tr>
                            <td>{{ $d->product->description_product ?? $d->id_product }}</td>
                            <td>{{ $d->qty }}</td>
                            <td>{{ $d->size }}</td>
                            <td class="text-end">{{ number_format($d->product->unit_price ?? 0,2) }}</td>
                            <td class="text-end">{{ number_format($d->amount ?? 0,2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('purchase_order.editItem', ['id_po' => $po->id_po, 'id_product' => $d->id_product, 'size' => $d->size]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('purchase_order.destroyItem',['id_po' => $po->id_po,'id_product' => $d->id_product,'size' => $d->size]) }}" class="confirm-delete d-inline-block ms-1" data-confirm-title="Hapus Item PO" data-confirm-message="Apakah Anda yakin ingin menghapus item {{ $d->product->description_product ?? $d->id_product }} dari PO ini?" data-item-name="item {{ $d->product->description_product ?? $d->id_product }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada item di PO ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-end">
            <div><strong>Subtotal:</strong> {{ number_format($po->subtotal_po,2) }}</div>
            <div><strong>PPN:</strong> {{ number_format($po->ppn_po,2) }}</div>
            <div class="fs-5"><strong>Grand Total:</strong> {{ number_format($po->grand_total_po,2) }}</strong></div>
        </div>
    </div>
</div>

@endsection
