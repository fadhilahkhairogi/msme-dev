<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%')
                ->orWhere('kode', 'like', '%' . $request->search . '%');
        }

        $daftarSupplier = $query->latest()->paginate(10)->withQueryString();

        return view('produk.supplier.index', compact('daftarSupplier'));
    }

    public function create()
    {
        return view('produk.supplier.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'telepon' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('produk.supplier.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('produk.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'nama'    => 'required|string|max:255',
            'telepon' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'alamat' => 'required|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('produk.supplier.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('produk.supplier.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
