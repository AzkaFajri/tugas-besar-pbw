@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Customer</h1>
    <a href="{{ route('customer.create') }}" class="btn btn-primary">+ Tambah Customer</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Nama</th><th>Alamat</th><th>HP</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->id_customer }}</td>
                        <td>{{ $row->nama_customer }}</td>
                        <td>{{ $row->alamat_customer }}</td>
                        <td>{{ $row->hp_customer }}</td>
                        <td>
                            <a href="{{ route('customer.edit',$row->id_customer) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('customer.destroy',$row->id_customer) }}" class="d-inline-block ms-1 confirm-delete" data-item-name="Customer {{ $row->nama_customer }}">
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
