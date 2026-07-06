@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Buat Pegawai</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pegawai.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">ID</label>
                <input name="id_pegawai" class="form-control" value="{{ $nextId }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="nama_pegawai" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat_pegawai" class="form-control"></textarea>
            </div>
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label class="form-label">HP</label>
                    <input name="hp_pegawai" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input name="jabatan" class="form-control">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection
