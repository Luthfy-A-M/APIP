<?php

namespace App\Http\Controllers;

use App\Models\Tbm_Attendant;
use Illuminate\Http\Request;

class tbm_attendantController extends Controller
{
    //only need store, sign, delete
    public function store(Request $request)
    {
        try {
            $attendant = Tbm_Attendant::create($request->all());
            return response()->json(['attendant' => $attendant], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $attendant = Tbm_Attendant::findOrFail($id);
            return response()->json(['attendant' => $attendant], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attendant = Tbm_Attendant::findOrFail($id);
            $attendant->update($request->all());
            return response()->json(['attendant' => $attendant], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $attendant = Tbm_Attendant::where('attendant_id', $request->attendant_id)
                                       ->where('tbm_id', $request->tbm_id)
                                       ->firstOrFail();
            $attendant->delete();
            return response()->json(['message' => 'Delete Success'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getTbmAttendants($tbm_id)
    {
        try {
            $attendants = Tbm_Attendant::where('tbm_id', $tbm_id)->get();
            return response()->json(['attendants' => $attendants], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
