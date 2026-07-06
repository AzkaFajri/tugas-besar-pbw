<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    public function index()
    {
        $data = Product::all();
        return view('products.index', compact('data'));
    }

    public function create()
    {
        $nextId = Product::generateNextId();
        return view('products.create', compact('nextId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_product' => 'nullable|string|max:8',
            'description_product' => 'nullable|string|max:255',
            'unit_price' => 'nullable|numeric'
        ]);
        $validated['id_product'] = $validated['id_product'] ?? Product::generateNextId();
        Product::create($validated);
        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        $row = Product::findOrFail($id);
        return view('products.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = Product::findOrFail($id);
        $row->update($request->only(['description_product','unit_price']));
        return redirect()->route('products.index');
    }

    public function destroy($id)
    {
        $row = Product::findOrFail($id);
        try {
            $row->delete();
            return redirect()->route('products.index')->with('success', 'Data produk berhasil dihapus.');
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return redirect()->route('products.index')->with('error', 'Produk "' . $row->description_product . '" tidak dapat dihapus karena masih digunakan oleh Purchase Order atau Invoice.');
            }
            throw $e;
        }
    }
}
