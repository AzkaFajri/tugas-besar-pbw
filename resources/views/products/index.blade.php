@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Product</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Description</th><th>Unit Price</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->id_product }}</td>
                        <td>{{ $row->description_product }}</td>
                        <td>{{ number_format($row->unit_price,2) }}</td>
                        <td>
                            <a href="{{ route('products.edit',$row->id_product) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('products.destroy',$row->id_product) }}" class="d-inline-block ms-1 confirm-delete" data-item-name="Produk {{ $row->description_product }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
