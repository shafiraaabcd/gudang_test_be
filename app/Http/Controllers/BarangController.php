<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15); // Jumlah item per halaman, default 15
        $page = $request->input('page', 1); // Halaman saat ini, default 1

        $barangs = Barang::paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $barangs
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_barang' => 'required|string',
            'kode' => 'required|string',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
        ], [
            'nama_barang.required' => 'Nama barang diperlukan.',
            'kode.required' => 'Kode barang diperlukan.',
            'kategori.required' => 'Kategori barang diperlukan.',
            'lokasi.required' => 'Lokasi barang diperlukan.',
        ]);

        try {
            $barang = Barang::create($validatedData);
            return response()->json([
                'success' => true,
                'data' => $barang,
                'message' => 'Barang successfully created.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating barang.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $barang
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang not found.'
            ], 404);
        }

        // Validasi input
        $validatedData = $request->validate([
            'nama_barang' => 'required|string',
            'kode' => 'required|string',
            'kategori' => 'required|string',
            'lokasi' => 'required|string',
        ], [
            'nama_barang.required' => 'Nama barang diperlukan.',
            'kode.required' => 'Kode barang diperlukan.',
            'kategori.required' => 'Kategori barang diperlukan.',
            'lokasi.required' => 'Lokasi barang diperlukan.',
        ]);

        // Update barang
        $barang->update($validatedData);

        return response()->json([
            'success' => true,
            'data' => $barang,
            'message' => 'Barang successfully updated.'
        ], 200);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang not found.'
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Barang successfully deleted.'
        ], 200);
    }
}
