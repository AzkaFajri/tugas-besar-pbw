@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-0">Purchase Orders</h1>
        <p class="text-muted mb-0">Lihat, edit, dan cetak invoice PO dengan mudah.</p>
    </div>
    @if(session('pegawai.jabatan') === 'Admin')
        <a href="{{ route('purchase_order.create') }}" class="btn btn-primary">+ Tambah PO baru</a>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light"><tr><th>ID PO</th><th>Tanggal</th><th>Customer</th><th>Pegawai</th><th class="text-end">Grand Total</th><th class="text-center">Aksi</th></tr></thead>
            <tbody>
            @foreach($data as $po)
                <tr>
                    <td><strong>{{ $po->id_po }}</strong></td>
                    <td>{{ $po->tgl_po }}</td>
                    <td>{{ $po->customer->nama_customer ?? $po->id_customer }}</td>
                    <td>{{ $po->pegawai->nama_pegawai ?? $po->id_pegawai }}</td>
                    <td class="text-end">{{ number_format($po->grand_total_po ?? 0,2) }}</td>
                    <td class="text-center">
                        <a href="{{ route('purchase_order.show',['id_po'=>$po->id_po]) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    @if(session('pegawai.jabatan') === 'Admin')
                        <a href="{{ route('purchase_order.edit',['id_po'=>$po->id_po]) }}" class="btn btn-sm btn-outline-secondary ms-1">Edit</a>
                        <form method="POST" action="{{ route('purchase_order.destroy',['id_po'=>$po->id_po]) }}" class="d-inline-block ms-1 confirm-delete" data-confirm-title="Hapus Purchase Order" data-confirm-message="Apakah Anda yakin ingin menghapus Purchase Order {{ $po->id_po }}?" data-item-name="Purchase Order {{ $po->id_po }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
