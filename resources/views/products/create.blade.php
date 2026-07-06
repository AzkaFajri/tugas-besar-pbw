@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Buat Product</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ID</label>
                <input name="id_product" class="form-control" value="{{ $nextId }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input name="description_product" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Price</label>
                <input name="unit_price" class="form-control" type="number" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection
