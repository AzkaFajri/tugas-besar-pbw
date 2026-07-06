@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Buat Customer</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('customer.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ID</label>
                <input name="id_customer" class="form-control" value="{{ $nextId }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="nama_customer" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat_customer" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">HP</label>
                <input name="hp_customer" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection
