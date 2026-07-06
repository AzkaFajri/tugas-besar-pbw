@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Edit Customer {{ $row->id_customer }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('customer.update',$row->id_customer) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="nama_customer" class="form-control" value="{{ $row->nama_customer }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat_customer" class="form-control">{{ $row->alamat_customer }}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">HP</label>
                <input name="hp_customer" class="form-control" value="{{ $row->hp_customer }}">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection
