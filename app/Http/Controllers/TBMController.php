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
            if ($request->getMethod() !== 'POST') {
                // If the request method is not POST, return a response indicating that POST method is required
                return response()->json(['error' => 'POST method is required'], 405);
            }
            // Validasi request
            $request->validate([
                //if this is a new store then the user that make it will be the one appear on prepared by
                'user_id' => 'required|string|max:255', //// Validate prepared_by is required and is a string with maximum length of 255 characters
                'dept_code' => 'required|string|max:255', // Validate dept_code is required and is a string with maximum length of 255 characters
            ],[
                'user_id.required' => 'The user ID is required.',
                'dept_code.required' => 'The department code is required.',
            ]);

            $request->merge(['prepared_by' => $request->user_id]);
            $request->merge(['status' => $request->user_id]);

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
            if ($request->getMethod() !== 'POST') {
                // If the request method is not POST, return a response indicating that POST method is required
                return response()->json(['error' => 'POST method is required'], 405);
            }
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

    public function searchby($param1, $param2){
        try{
            //Get TBM Data Using Where 'id' = 1
            $tbms = TBM::where($param1, $param2)->Get();
            return response()->json($tbms);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
