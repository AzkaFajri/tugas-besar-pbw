@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0">Daftar Pegawai</h1>
    <a href="{{ route('pegawai.create') }}" class="btn btn-primary">+ Tambah Pegawai</a>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Nama</th><th>Jabatan</th><th>Alamat</th><th>HP</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->id_pegawai }}</td>
                        <td>{{ $row->nama_pegawai }}</td>
                        <td>{{ $row->jabatan }}</td>
                        <td>{{ $row->alamat_pegawai }}</td>
                        <td>{{ $row->hp_pegawai }}</td>
                        <td>
                            <a href="{{ route('pegawai.edit',$row->id_pegawai) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('pegawai.destroy',$row->id_pegawai) }}" class="d-inline-block ms-1 confirm-delete" data-item-name="Pegawai {{ $row->nama_pegawai }}">
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
