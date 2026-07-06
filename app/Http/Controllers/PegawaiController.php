<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PegawaiController extends Controller
{
    public function index()
    {
        $data = Pegawai::all();
        return view('pegawai.index', compact('data'));
    }

    public function create()
    {
        $nextId = Pegawai::generateNextId();
        return view('pegawai.create', compact('nextId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pegawai' => 'nullable|string|max:8',
            'nama_pegawai' => 'required|string|max:100'
        ]);
        $validated['id_pegawai'] = $validated['id_pegawai'] ?? Pegawai::generateNextId();
        Pegawai::create($validated + $request->only(['alamat_pegawai','hp_pegawai','jabatan']));
        return redirect()->route('pegawai.index');
    }

    public function edit($id)
    {
        $row = Pegawai::findOrFail($id);
        return view('pegawai.edit', compact('row'));
    }

    public function update(Request $request, $id)
    {
        $row = Pegawai::findOrFail($id);
        $row->update($request->only(['nama_pegawai','alamat_pegawai','hp_pegawai','jabatan']));
        return redirect()->route('pegawai.index');
    }

    public function destroy($id)
    {
        $row = Pegawai::findOrFail($id);
        try {
            $row->delete();
            return redirect()->route('pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
        } catch (QueryException $e) {
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1451) {
                return redirect()->route('pegawai.index')->with('error', 'Pegawai "' . $row->nama_pegawai . '" tidak dapat dihapus karena masih digunakan oleh Purchase Order atau Invoice.');
            }
            throw $e;
        }
    }
}
