@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="h4 mb-0">Invoice</h1>
        <p class="text-muted mb-0">Kelola invoice dari purchase order.</p>
    </div>
    @if(session('pegawai.jabatan') === 'Admin')
        <a href="{{ route('invoice.create') }}" class="btn btn-primary">+ Tambah Invoice</a>
    @endif
</div>

<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Invoice</th>
                    <th>Tanggal</th>
                    <th>ID PO</th>
                    <th>Customer</th>
                    <th>Pegawai</th>
                    <th class="text-end">Grand Total</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->id_invoice }}</strong></td>
                        <td>{{ $invoice->tgl_invoice }}</td>
                        <td>{{ $invoice->id_po }}</td>
                        <td>{{ $invoice->customer->nama_customer ?? $invoice->id_customer }}</td>
                        <td>{{ $invoice->pegawai->nama_pegawai ?? $invoice->id_pegawai }}</td>
                        <td class="text-end">{{ number_format($invoice->grand_total_invoice ?? 0, 2) }}</td>
                        <td class="text-center">
                            @if(session('pegawai.jabatan') === 'Admin')
                            <a href="{{ route('purchase_order.edit', ['id_po' => $invoice->id_po]) }}" class="btn btn-sm btn-outline-secondary">Edit PO</a>
                        @endif
                        <a href="{{ route('invoice.print', ['id_invoice' => $invoice->id_invoice]) }}" target="_blank" class="btn btn-sm btn-success ms-1">Cetak</a>
                        @if(session('pegawai.jabatan') === 'Admin')
                            <form method="POST" action="{{ route('invoice.destroy', ['id_invoice' => $invoice->id_invoice]) }}" class="d-inline-block ms-1 confirm-delete" data-confirm-title="Hapus Invoice" data-confirm-message="Apakah Anda yakin ingin menghapus Invoice {{ $invoice->id_invoice }}?" data-item-name="Invoice {{ $invoice->id_invoice }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Tidak ada data invoice</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
