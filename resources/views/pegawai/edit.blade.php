@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Edit Pegawai {{ $row->id_pegawai }}</h1>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('pegawai.update',$row->id_pegawai) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="nama_pegawai" class="form-control" value="{{ $row->nama_pegawai }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="alamat_pegawai" class="form-control">{{ $row->alamat_pegawai }}</textarea>
            </div>
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label class="form-label">HP</label>
                    <input name="hp_pegawai" class="form-control" value="{{ $row->hp_pegawai }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Jabatan</label>
                    <input name="jabatan" class="form-control" value="{{ $row->jabatan }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pegawai.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@endsection
