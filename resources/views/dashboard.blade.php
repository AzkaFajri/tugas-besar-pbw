@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="mb-1">Pegawai</h6>
            <div class="h4">{{ $counts['pegawai'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="mb-1">Customer</h6>
            <div class="h4">{{ $counts['customer'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="mb-1">Products</h6>
            <div class="h4">{{ $counts['products'] }}</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="mb-1">Purchase Orders</h6>
            <div class="h4">{{ $counts['purchase_orders'] }}</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Latest Purchase Orders</h5>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr><th>ID PO</th><th>Tanggal</th><th>Customer</th><th>Pegawai</th><th class="text-end">Grand Total</th></tr>
                </thead>
                <tbody>
                    @forelse($latest_po as $po)
                        <tr>
                            <td><a href="{{ route('purchase_order.edit',$po->id_po) }}">{{ $po->id_po }}</a></td>
                            <td>{{ $po->tgl_po }}</td>
                            <td>{{ $po->customer->nama_customer ?? $po->id_customer }}</td>
                            <td>{{ $po->pegawai->nama_pegawai ?? $po->id_pegawai }}</td>
                            <td class="text-end">{{ number_format($po->grand_total_po ?? 0,2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5">No recent purchase orders</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
