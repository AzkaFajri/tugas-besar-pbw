@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-0">Ubah Item PO {{ $po->id_po }}</h1>
        <p class="text-muted mb-0">Edit jumlah dan ukuran produk di purchase order ini.</p>
    </div>
    <a href="{{ route('purchase_order.edit', ['id_po' => $po->id_po]) }}" class="btn btn-secondary">Kembali ke PO</a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-4">
            <h5 class="mb-2">Informasi PO</h5>
            <div class="row gy-2">
                <div class="col-md-4"><strong>ID PO</strong><div>{{ $po->id_po }}</div></div>
                <div class="col-md-4"><strong>Tanggal</strong><div>{{ $po->tgl_po }}</div></div>
                <div class="col-md-4"><strong>Customer</strong><div>{{ $po->customer->nama_customer ?? $po->id_customer }}</div></div>
            </div>
        </div>

        <div class="mb-4">
            <h5 class="mb-2">Produk</h5>
            <div class="border rounded p-3 bg-light">
                <div><strong>{{ $product->description_product }}</strong></div>
                <div class="text-muted">Harga Satuan: {{ number_format($product->unit_price,2) }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('purchase_order.updateItem', ['id_po' => $po->id_po]) }}">
            @csrf
            <input type="hidden" name="id_product" value="{{ $detail->id_product }}">
            <input type="hidden" name="original_size" value="{{ $detail->size }}">

            <div class="mb-3">
                <label class="form-label">Qty</label>
                <input type="number" name="qty" value="{{ old('qty', $detail->qty) }}" min="1" class="form-control @error('qty') is-invalid @enderror" required>
                @error('qty')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Size</label>
                <input type="text" name="size" value="{{ old('size', $detail->size) }}" class="form-control @error('size') is-invalid @enderror" placeholder="Contoh: M, L, XL">
                @error('size')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('purchase_order.edit', ['id_po' => $po->id_po]) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
