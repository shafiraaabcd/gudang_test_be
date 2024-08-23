<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\User;
use Illuminate\Http\Request;

class MutasiController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        $mutasi = Mutasi::with('barang', 'user')->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $mutasi
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'jenis_mutasi' => 'required|string',
            'jumlah' => 'required|numeric',
            'barang_id' => 'required|exists:barangs,id',
            'user_id' => 'required|exists:users,id',
        ], [
            'tanggal.required' => 'Tanggal mutasi diperlukan.',
            'jenis_mutasi.required' => 'Jenis mutasi diperlukan.',
            'jumlah.required' => 'Jumlah mutasi diperlukan.',
            'barang_id.required' => 'ID Barang diperlukan.',
            'user_id.required' => 'ID User diperlukan.',
            'barang_id.exists' => 'ID Barang tidak ditemukan.',
            'user_id.exists' => 'ID User tidak ditemukan.',
        ]);

        try {
            $mutasi = Mutasi::create($validatedData);
            return response()->json([
                'success' => true,
                'data' => $mutasi,
                'message' => 'Mutasi berhasil dibuat.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat mutasi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $mutasi = Mutasi::with('barang', 'user')->find($id);

        if (!$mutasi) {
            return response()->json([
                'success' => false,
                'message' => 'Mutasi tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $mutasi
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $mutasi = Mutasi::find($id);

        if (!$mutasi) {
            return response()->json([
                'success' => false,
                'message' => 'Mutasi tidak ditemukan.'
            ], 404);
        }

        $validatedData = $request->validate([
            'tanggal' => 'required|date',
            'jenis_mutasi' => 'required|string',
            'jumlah' => 'required|numeric',
            'barang_id' => 'required|exists:barangs,id',
            'user_id' => 'required|exists:users,id',
        ], [
            'tanggal.required' => 'Tanggal mutasi diperlukan.',
            'jenis_mutasi.required' => 'Jenis mutasi diperlukan.',
            'jumlah.required' => 'Jumlah mutasi diperlukan.',
            'barang_id.required' => 'ID Barang diperlukan.',
            'user_id.required' => 'ID User diperlukan.',
            'barang_id.exists' => 'ID Barang tidak ditemukan.',
            'user_id.exists' => 'ID User tidak ditemukan.',
        ]);

        $mutasi->update($validatedData);

        return response()->json([
            'success' => true,
            'data' => $mutasi,
            'message' => 'Mutasi berhasil diperbarui.'
        ], 200);
    }

    public function destroy($id)
    {
        $mutasi = Mutasi::find($id);

        if (!$mutasi) {
            return response()->json([
                'success' => false,
                'message' => 'Mutasi tidak ditemukan.'
            ], 404);
        }

        $mutasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mutasi berhasil dihapus.'
        ], 200);
    }

    public function historyByBarang($barangId)
    {
        $barang = Barang::find($barangId);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak ditemukan.'
            ], 404);
        }

        $mutasi = Mutasi::with('barang', 'user')
            ->where('barang_id', $barangId)
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $mutasi
        ], 200);
    }

    public function historyByUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        $mutasi = Mutasi::with('barang', 'user')
            ->where('user_id', $userId)
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $mutasi
        ], 200);
    }

    public function filter(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $page = $request->input('page', 1);

        $query = Mutasi::with('barang', 'user');

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }
        if ($request->has('jenis_mutasi')) {
            $query->where('jenis_mutasi', $request->input('jenis_mutasi'));
        }
        if ($request->has('min_jumlah')) {
            $query->where('jumlah', '>=', $request->input('min_jumlah'));
        }
        if ($request->has('max_jumlah')) {
            $query->where('jumlah', '<=', $request->input('max_jumlah'));
        }
        if ($request->has('barang_id')) {
            $query->where('barang_id', $request->input('barang_id'));
        }
        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        $mutasi = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $mutasi
        ], 200);
    }
}
