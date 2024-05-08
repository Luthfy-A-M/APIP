<?php
namespace App\Http\Controllers;

use App\Models\tbm_instructor;
use Illuminate\Http\Request;

class tbm_InstructorController extends Controller
{
    //only need store, signing, and delete
    public function index() //we dont need get ALL
    {
        $instructors = tbm_Instructor::all();
        return response()->json(['instructors' => $instructors], 200);
    }

    public function store(Request $request)
    {
        $instructor = tbm_Instructor::create($request->all());
        return response()->json(['instructor' => $instructor], 201);
    }

    public function show($id)
    {
        $instructor = tbm_Instructor::findOrFail($id);
        return response()->json(['instructor' => $instructor], 200);
    }

    public function update(Request $request, $id)
    {
        $instructor = tbm_Instructor::findOrFail($id);
        $instructor->update($request->all());
        return response()->json(['instructor' => $instructor], 200);
    }

    public function destroy($id)
    {
        $instructor = tbm_Instructor::findOrFail($id);
        $instructor->delete();
        return response()->json(null, 204);
    }

    public function getTbmInstructor($tbm_id){
        $instructors = tbm_Instructor::where('tbm_id' , $tbm_id)->get();

        return $instructors;
    }

}
