<?php

namespace App\Http\Controllers;

use App\Models\tbm_instructor;
use Illuminate\Http\Request;

class tbm_InstructorController extends Controller
{
    //only need store, signing, and delete
    public function store(Request $request)
    {
        try {
            $instructor = tbm_Instructor::create($request->all());
            return response()->json(['instructor' => $instructor], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $instructor = tbm_Instructor::findOrFail($id);
            return response()->json(['instructor' => $instructor], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $instructor = tbm_Instructor::findOrFail($id);
            $instructor->update($request->all());
            return response()->json(['instructor' => $instructor], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $instructor = tbm_Instructor::where('instructor_id', $request->instructor_id)
                                        ->where('tbm_id', $request->tbm_id)
                                        ->firstOrFail();
            $instructor->delete();
            return response()->json(['message' => 'Delete Success'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getTbmInstructor($tbm_id)
    {
        try {
            $instructors = tbm_Instructor::where('tbm_id', $tbm_id)->get();
            return response()->json(['instructors' => $instructors], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
