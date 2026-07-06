<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\Customer;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InvoiceController extends Controller
{
    /**
     * Check if current user is authorized to modify Invoice
     */
    private function authorizeModify()
    {
        if (session('pegawai.jabatan') === 'Owner') {
            abort(403, 'Owner tidak diizinkan untuk membuat atau mengubah Invoice.');
        }
    }

    public function index()
    {
        $data = Invoice::with('purchaseOrder', 'customer', 'pegawai', 'user')->orderBy('tgl_invoice', 'desc')->get();
        return view('invoice.index', compact('data'));
    }

    public function create()
    {
        $this->authorizeModify();
        $pos = PurchaseOrder::with('customer', 'pegawai')->get();
        $newIdInvoice = $this->generateInvoiceId();
        return view('invoice.create', compact('pos', 'newIdInvoice'));
    }

    public function store(Request $request)
    {
        $this->authorizeModify();
        $request->validate([
            'id_invoice' => 'required|string|unique:invoices,id_invoice',
            'id_po' => 'required|exists:purchase_order,id_po',
            'tgl_invoice' => 'required|date'
        ]);

        $po = PurchaseOrder::findOrFail($request->id_po);

        $invoice = Invoice::create([
            'id_invoice' => $request->id_invoice,
            'id_po' => $request->id_po,
            'tgl_invoice' => $request->tgl_invoice,
            'id_customer' => $po->id_customer,
            'id_pegawai' => $po->id_pegawai,
            'subtotal_invoice' => $po->subtotal_po,
            'ppn_invoice' => $po->ppn_po,
            'grand_total_invoice' => $po->grand_total_po,
            'notes' => $request->notes ?? '',
        ]);

        if ($request->input('action') === 'print' || $request->input('action') === 'Simpan & Cetak') {
            return redirect()->route('invoice.print', ['id_invoice' => $invoice->id_invoice]);
        }

        return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dibuat dengan ID: ' . $invoice->id_invoice);
    }

    public function showPO(Request $request)
    {
        $id_po = $request->query('id_po');
        $po = PurchaseOrder::with('customer','pegawai','details.product','user')->findOrFail($id_po);
        return view('invoice.show', compact('po'));
    }

    public function destroy(Request $request)
    {
        $this->authorizeModify();
        $id_invoice = $request->query('id_invoice');
        $invoice = Invoice::findOrFail($id_invoice);
        try {
            $invoice->delete();
            return redirect()->route('invoice.index')->with('success', 'Invoice berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return redirect()->route('invoice.index')->with('error', 'Invoice "' . $invoice->id_invoice . '" tidak dapat dihapus karena masih terkait dengan data lain.');
            }
            throw $e;
        }
    }

    public function print(Request $request)
    {
        $id_invoice = $request->query('id_invoice');
        $invoice = Invoice::with('purchaseOrder.details.product', 'customer', 'pegawai', 'user')->findOrFail($id_invoice);
        return view('invoice.print', compact('invoice'));
    }

    private function generateInvoiceId()
    {
        $year = now()->year;
        $month = now()->month;
        $monthRoman = $this->getRomanMonth($month);

        $count = Invoice::whereYear('tgl_invoice', $year)
            ->whereMonth('tgl_invoice', $month)
            ->count();

        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        return "{$sequence}/INV/RYR/{$monthRoman}/{$year}";
    }

    private function getRomanMonth($month)
    {
        $romanMonths = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];
        return $romanMonths[$month] ?? 'I';
    }
}
