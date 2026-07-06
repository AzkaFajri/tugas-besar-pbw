<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice {{ $po->id_po }}</title>
    <style>
        body{font-family:Arial,Helvetica,sans-serif;padding:20px}
        .invoice-box{max-width:800px;margin:0 auto;border:1px solid #eee;padding:20px}
        h1{margin:0 0 12px}
        table{width:100%;border-collapse:collapse}
        table th, table td{padding:8px;border:1px solid #eee}
        .right{text-align:right}
        .no-print{margin:12px 0}
        @media print{.no-print{display:none}}
    </style>
    <script>
        window.addEventListener('load', function(){
            // auto print
            window.print();
        });
    </script>
</head>
<body>
    <div class="invoice-box">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <h1>Invoice</h1>
                <div>{{ $po->id_po }}</div>
                <div>{{ $po->tgl_po }}</div>
            </div>
            <div style="text-align:right">
                <strong>Raynor</strong>
                <div>Alamat perusahaan</div>
            </div>
        </div>

        <hr>
        <div style="display:flex;justify-content:space-between;margin-bottom:12px">
            <div>
                <strong>Customer</strong>
                <div>{{ $po->customer->nama_customer ?? $po->id_customer }}</div>
                <div>{{ $po->customer->alamat_customer ?? '' }}</div>
            </div>
            <div>
                <strong>Pegawai</strong>
                <div>{{ $po->pegawai->nama_pegawai ?? $po->id_pegawai }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr><th>Produk</th><th>Qty</th><th>Size</th><th>Harga Satuan</th><th>Jumlah</th></tr>
            </thead>
            <tbody>
                @foreach($po->details as $d)
                    <tr>
                        <td>{{ $d->product->description_product ?? $d->id_product }}</td>
                        <td class="right">{{ $d->qty }}</td>
                        <td class="right">{{ $d->size }}</td>
                        <td class="right">{{ number_format($d->product->unit_price ?? 0,2) }}</td>
                        <td class="right">{{ number_format($d->amount ?? 0,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="right"><strong>Subtotal</strong></td>
                    <td class="right">{{ number_format($po->subtotal_po ?? 0,2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="right"><strong>PPN</strong></td>
                    <td class="right">{{ number_format($po->ppn_po ?? 0,2) }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="right"><strong>Grand Total</strong></td>
                    <td class="right">{{ number_format($po->grand_total_po ?? 0,2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="no-print">
            <button onclick="window.print()">Print</button>
            <a href="{{ route('purchase_order.edit',$po->id_po) }}">Kembali</a>
        </div>
    </div>
</body>
</html>
