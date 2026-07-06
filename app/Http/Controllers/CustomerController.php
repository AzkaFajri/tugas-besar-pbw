<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CustomerController extends Controller
{
    public function index()
    {
        $data = Customer::all();
        return view('customer.index', compact('data'));
    }

    public function create()
    {
        $nextId = Customer::generateNextId();
        return view('customer.create', compact('nextId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_customer' => 'nullable|string|max:8',
            'nama_customer' => 'required|string|max:100'
        ]);
        $validated['id_customer'] = $validated['id_customer'] ?? Customer::generateNextId();
        Customer::create($validated + $request->only(['alamat_customer','hp_customer']));
        return redirect()->route('customer.index');
    }

    /**
     * Store customer via AJAX (for quick customer creation in PO)
     */
    public function storeQuick(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:100',
            'alamat_customer' => 'nullable|string|max:255',
            'hp_customer' => 'nullable|string|max:20',
        ]);

        $validated['id_customer'] = Customer::generateNextId();
        $customer = Customer::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Customer berhasil ditambahkan',
            'customer' => $customer
        ]);
    }

    public function edit($id)
    {
        $row = Customer::findOrFail($id);
        return view('customer.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = Customer::findOrFail($id);
        $row->update($request->only(['nama_customer','alamat_customer','hp_customer']));
        return redirect()->route('customer.index');
    }

    public function destroy($id)
    {
        $row = Customer::findOrFail($id);
        try {
            $row->delete();
            return redirect()->route('customer.index')->with('success', 'Data customer berhasil dihapus.');
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return redirect()->route('customer.index')->with('error', 'Customer "' . $row->nama_customer . '" tidak dapat dihapus karena masih digunakan oleh Purchase Order.');
            }
            throw $e;
        }
    }
}
