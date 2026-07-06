<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice - {{ $invoice->id_invoice }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        *{box-sizing:border-box}
        body{font-family:Inter, Arial, sans-serif;margin:0;color:#2b2b2b;background:#eef4fb}
        .container{max-width:1000px;margin:24px auto;padding:32px;background:#fff;border-radius:24px;box-shadow:0 18px 50px rgba(42, 58, 84, 0.08)}
        .header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px}
        .brand{display:flex;gap:18px;align-items:center}
        .logo{width:140px}
        .company{font-size:14px;color:#5b6770;line-height:1.7}
        .invoice-title{font-family:Poppins, Inter;font-size:48px;color:#1f3f70;letter-spacing:0.02em}
        .subtitle{margin-top:6px;font-size:14px;color:#718096}
        .info-section{display:flex;justify-content:space-between;margin-bottom:24px;gap:20px}
        .customer{width:62%;font-size:14px}
        .meta{width:36%;font-size:14px}
        .label{display:inline-block;width:86px;font-weight:700;color:#323f4b}
        .card{background:#f8fbff;border:1px solid rgba(31, 63, 112, 0.12);border-radius:16px;padding:18px}
        table{width:100%;border-collapse:collapse;margin-top:14px}
        table th{background:#e9efff;padding:14px;border-bottom:1px solid #dfe7f5;text-align:left;font-weight:700;color:#1f3f70}
        table td{padding:14px;border-bottom:1px solid #eef2f7;color:#2b3440}
        table tbody tr:last-child td{border-bottom:none}
        .text-right{text-align:right}
        .text-center{text-align:center}
        .summary{margin-top:24px;width:360px;margin-left:auto}
        .summary table{width:100%}
        .summary td{padding:10px;border:0}
        .summary .label{font-weight:700;color:#323f4b}
        .summary .value{font-weight:600;color:#1f3f70}
        .summary .grand-total{font-size:1.05rem}
        .payment{margin-top:38px;font-size:14px;line-height:1.8;color:#4f5d72;padding:20px;border-top:1px solid #e6ecf5}
        .signature{margin-top:38px;display:flex;justify-content:flex-end}
        .signature-box{text-align:center;width:220px}
        .signature-box img{width:110px;opacity:0.95}
        .signature-name{border-top:1px solid #dde5f1;padding-top:10px;margin-top:8px;font-weight:700;color:#1f3f70}
        .status-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;background:#f1f5ff;color:#1f3f70;font-weight:700;font-size:0.88rem}

        @media print{
            body{margin:0}
            .container{padding:16px;box-shadow:none;border-radius:0}
            .no-print{display:none}
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">
                <img src="{{ asset('images/logo-raynor.png') }}" class="logo" alt="logo">
                <div class="company">
                    <strong>Raynor</strong>
                    <div class="subtitle">Solusi peralatan taekwondo modern dan terpercaya.</div>
                </div>
            </div>
            <div>
                <div class="invoice-title">INVOICE</div>
                <div class="status-pill">Invoice #{{ $invoice->id_invoice }}</div>
            </div>
        </div>

        <div class="info-section">
            <div class="customer">
                <div><span class="label">CUSTOMER</span> : {{ $invoice->customer->nama_customer ?? '-' }}</div>
                <div style="margin-top:6px"><span class="label">ADDRESS</span> : {!! nl2br(e($invoice->customer->alamat_customer ?? '-')) !!}</div>
                @if($invoice->notes)
                    <div style="margin-top:6px"><span class="label">NOTES</span> : {{ $invoice->notes }}</div>
                @endif
            </div>

            <div class="meta">
                <div><span class="label">INVOICE</span> : {{ $invoice->id_invoice }}</div>
                <div style="margin-top:6px"><span class="label">DATE</span> : {{ \Illuminate\Support\Carbon::parse($invoice->tgl_invoice)->format('d F Y') }}</div>
                <div style="margin-top:6px"><span class="label">PO</span> : {{ $invoice->purchaseOrder->id_po ?? '-' }}</div>
            </div>
        </div>

        <table aria-describedby="invoice-items">
            <thead>
                <tr>
                    <th style="width:6%">No.</th>
                    <th>Description</th>
                    <th style="width:12%" class="text-center">Size</th>
                    <th style="width:15%" class="text-right">Unit Price</th>
                    <th style="width:10%" class="text-center">Qty</th>
                    <th style="width:18%" class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
            @php $no=1; @endphp
            @foreach($invoice->purchaseOrder->details ?? [] as $detail)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $detail->product->description_product ?? ($detail->id_product ?? '-') }}</td>
                    <td class="text-center">{{ $detail->size }}</td>
                    <td class="text-right">{{ number_format($detail->product->unit_price ?? $detail->amount,0,',','.') }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-right">{{ number_format($detail->amount,0,',','.') }}</td>
                </tr>
            @endforeach

                <tr><td colspan="6" style="height:30px;border:none"></td></tr>
            </tbody>
        </table>

        <div class="summary">
            <table>
                <tr>
                    <td class="label">Sub Total</td>
                    <td class="text-right">{{ number_format($invoice->subtotal_invoice ?? $invoice->purchaseOrder->subtotal_po ?? 0,0,',','.') }}</td>
                </tr>
                <tr>
                    <td class="label">PPN (12%)</td>
                    <td class="text-right">{{ number_format($invoice->ppn_invoice ?? $invoice->purchaseOrder->ppn_po ?? 0,0,',','.') }}</td>
                </tr>
                <tr>
                    <td class="label"><strong>Grand Total</strong></td>
                    <td class="text-right"><strong>{{ number_format($invoice->grand_total_invoice ?? $invoice->purchaseOrder->grand_total_po ?? 0,0,',','.') }}</strong></td>
                </tr>
            </table>
        </div>

        <div class="payment">
            <b>PAYMENT TO :</b><br>
            MUHAMAD INSAN KAMIL<br>
            BANK MANDIRI<br>
            1660002204097
        </div>

        <div class="signature">
            <div class="signature-box">
                Hormat Kami,
                <br><br>
                <img src="{{ asset('images/logo-raynor.png') }}" alt="logo">
                <div class="signature-name">{{ $invoice->pegawai->nama_pegawai ?? '' }}</div>
            </div>
        </div>

    </div>

    <script>
        window.onload = function(){
            window.print();
            window.onafterprint = function(){
                window.location.href = "{{ route('invoice.index') }}";
            }
            setTimeout(function(){ window.location.href = "{{ route('invoice.index') }}"; }, 4000);
        }
    </script>
</body>
</html>