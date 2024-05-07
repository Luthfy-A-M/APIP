<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TBM;

class TBMController extends Controller
{
    public function index()
    {
        try {
            // Mengambil semua data TBMS
            $tbms = TBM::all();

            // Mengembalikan data dalam bentuk JSON
            return response()->json($tbms);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi request
            $request->validate([
                // Definisikan validasi sesuai kebutuhan
            ]);

            // Membuat entri baru TBMS
            $tbms = TBM::create($request->all());

            // Mengembalikan data yang baru dibuat dalam bentuk JSON
            return response()->json($tbms, 201);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            // Mengambil data TBMS berdasarkan ID
            $tbms = TBM::findOrFail($id);

            // Mengembalikan data dalam bentuk JSON
            return response()->json($tbms);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validasi request
            $request->validate([
                // Definisikan validasi sesuai kebutuhan
            ]);

            // Mengambil data TBMS berdasarkan ID
            $tbms = TBM::findOrFail($id);

            // Memperbarui data TBMS
            $tbms->update($request->all());

            // Mengembalikan data yang telah diperbarui dalam bentuk JSON
            return response()->json($tbms, 200);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Mengambil data TBMS berdasarkan ID
            $tbms = TBM::findOrFail($id);

            // Menghapus data TBMS
            $tbms->delete();

            // Mengembalikan pesan sukses dalam bentuk JSON
            return response()->json(null, 204);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
