<?php

namespace App\Http\Controllers;

use App\Models\tbm_instructor;
use Illuminate\Http\Request;

class tbm_instructorController extends Controller
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
             return $instructors;
        } catch (\Exception $e) {
            return  $e->getMessage();
        }
    }

    public function getMyUnsignedTbmInstructor(Request $request){
        try {
            $request->validate([
                'user_id' => 'required'
            ],[
                'user_id.required' => 'User ID is required'
            ]);
            $instructors = tbm_instructor::where('instructor_id', $request->user_id)
            ->whereNull('signed_date')
            ->get();
            return  $instructors;
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function signTbmInstructor(Request $request){
        try {
            $request->validate([
                'user_id' => 'required',
                'tbm_instructor_id' => 'required'
            ],[
                'user_id.required' => 'User ID is required',
                'tbm_instructor_id.required' => 'tbm_instructor_id is required'
            ]);
            $instructor = tbm_instructor::find($request->tbm_instructor_id);
            if($instructor->instructor_id == $request->user_id){
                $instructor->signed_date = now();
                $instructor->save();
                return response()->json(['success' => 'tbm is signed successfully','tbm Instructor' => $instructor], 204);
            }
            else{
                return response()->json(['error' => 'You are not authorized to sign this tbm_instructor'], 500);
            }
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
