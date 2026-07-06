<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Customer;
use App\Models\Pegawai;
use App\Models\Product;
use App\Models\DetailProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $data = PurchaseOrder::with('customer','pegawai')->orderBy('tgl_po','desc')->get();
        return view('purchase_order.index', compact('data'));
    }

    public function create()
    {
        $pegawais = Pegawai::all();
        $customers = Customer::all();
        return view('purchase_order.create', compact('pegawais','customers'));
    }

    public function storeInitial(Request $request)
    {
        $request->validate([
            'id_po' => 'required|string|max:30|unique:purchase_order,id_po',
            'id_pegawai' => 'required',
            'id_customer' => 'required',
            'tgl_po' => 'nullable|date'
        ]);

        $po = PurchaseOrder::create([
            'id_po' => $request->id_po,
            'tgl_po' => $request->tgl_po ?: now()->toDateString(),
            'ppn_po' => 0, // Default 0, akan dihitung saat ada detail
            'subtotal_po' => 0,
            'grand_total_po' => 0,
            'id_customer' => $request->id_customer,
            'id_pegawai' => $request->id_pegawai,
        ]);

        // Redirect using query parameter expected by the edit route
        return redirect()->route('purchase_order.edit', ['id_po' => $po->id_po]);
    }

    public function show(Request $request)
    {
        $id_po = $request->query('id_po');
        $po = PurchaseOrder::with('customer','pegawai','details.product')->findOrFail($id_po);
        return view('purchase_order.show', compact('po'));
    }

    public function edit(Request $request)
    {
        $id_po = $request->query('id_po');
        $po = PurchaseOrder::with('customer','pegawai','details.product')->findOrFail($id_po);
        $products = Product::all();
        $details = $po->details;
        return view('purchase_order.edit', compact('po','products','details'));
    }

    public function addItem(Request $request)
    {
        $id_po = $request->query('id_po');
        $request->validate([
            'id_product' => 'required',
            'qty' => 'required|integer|min:1',
            'size' => 'required|string|max:20',
        ]);
        $product = Product::findOrFail($request->id_product);

        // Prevent duplicate product with the same size in the same PO
        $exists = DetailProduct::where('id_po', $id_po)
            ->where('id_product', $product->id_product)
            ->where('size', $request->size)
            ->first();
        if ($exists) {
            return redirect()->route('purchase_order.edit', ['id_po' => $id_po])->with('error', 'Produk dengan ukuran yang sama sudah ada di PO. Silakan gunakan ukuran lain atau edit item tersebut.');
        }

        $amount = $product->unit_price * $request->qty;
        DetailProduct::create([
            'id_po' => $id_po,
            'id_product' => $product->id_product,
            'qty' => $request->qty,
            'size' => $request->size,
            'amount' => $amount,
        ]);
        // update totals dengan PPN 12%
        $subtotal = DetailProduct::where('id_po',$id_po)->sum('amount');
        $ppn = $subtotal * 0.12;
        $grand = $subtotal + $ppn;
        $po = PurchaseOrder::findOrFail($id_po);
        $po->update(['subtotal_po'=>$subtotal,'ppn_po'=>$ppn,'grand_total_po'=>$grand]);
        return redirect()->route('purchase_order.edit', ['id_po' => $id_po]);
    }

    public function updateItem(Request $request)
    {
        $id_po = $request->query('id_po');
        $request->validate([
            'id_product' => 'required',
            'qty' => 'required|integer|min:1',
            'size' => 'required|string|max:20',
            'original_size' => 'required|string|max:20',
        ]);

        $originalSize = $request->input('original_size');
        $detail = DetailProduct::where('id_po', $id_po)
            ->where('id_product', $request->id_product)
            ->where('size', $originalSize)
            ->firstOrFail();

        if ($originalSize !== $request->size) {
            $duplicate = DetailProduct::where('id_po', $id_po)
                ->where('id_product', $request->id_product)
                ->where('size', $request->size)
                ->first();
            if ($duplicate) {
                return redirect()->route('purchase_order.edit', ['id_po' => $id_po])->with('error', 'Produk dengan ukuran yang sama sudah ada di PO. Silakan gunakan ukuran lain.');
            }
        }

        $product = Product::findOrFail($request->id_product);
        $amount = $product->unit_price * $request->qty;
        DetailProduct::where('id_po', $id_po)
            ->where('id_product', $request->id_product)
            ->where('size', $originalSize)
            ->update(['qty' => $request->qty, 'size' => $request->size, 'amount' => $amount]);

        // recalculate totals with 12% PPN
        $subtotal = DetailProduct::where('id_po',$id_po)->sum('amount');
        $ppn = $subtotal * 0.12;
        $grand = $subtotal + $ppn;
        $po = PurchaseOrder::findOrFail($id_po);
        $po->update(['subtotal_po'=>$subtotal,'ppn_po'=>$ppn,'grand_total_po'=>$grand]);

        return redirect()->route('purchase_order.edit', ['id_po' => $id_po])->with('success', 'Item berhasil diperbarui.');
    }

    public function editItem(Request $request)
    {
        $id_po = $request->query('id_po');
        $id_product = $request->query('id_product');
        $size = $request->query('size');
        $po = PurchaseOrder::with('customer','pegawai')->findOrFail($id_po);
        $detail = DetailProduct::where('id_po', $id_po)
            ->where('id_product', $id_product)
            ->where('size', $size)
            ->firstOrFail();
        $product = Product::findOrFail($id_product);
        return view('purchase_order.edit_item', compact('po','detail','product'));
    }

    public function destroyItem(Request $request)
    {
        $id_po = $request->query('id_po');
        $id_product = $request->query('id_product');
        $size = $request->query('size');
        DetailProduct::where('id_po',$id_po)->where('id_product',$id_product)->where('size',$size)->delete();
        $subtotal = DetailProduct::where('id_po',$id_po)->sum('amount');
        $ppn = $subtotal * 0.12;
        $grand = $subtotal + $ppn;
        $po = PurchaseOrder::findOrFail($id_po);
        $po->update(['subtotal_po'=>$subtotal,'ppn_po'=>$ppn,'grand_total_po'=>$grand]);
        return redirect()->route('purchase_order.edit', ['id_po' => $id_po]);
    }

    public function destroy(Request $request)
    {
        $id_po = $request->query('id_po');
        DetailProduct::where('id_po', $id_po)->delete();
        $po = PurchaseOrder::findOrFail($id_po);
        try {
            $po->delete();
            return redirect()->route('purchase_order.index')->with('success', 'Purchase order berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return redirect()->route('purchase_order.index')->with('error', 'Purchase Order "' . $po->id_po . '" tidak dapat dihapus karena masih terkait dengan invoice atau data lain.');
            }
            throw $e;
        }
    }
}
