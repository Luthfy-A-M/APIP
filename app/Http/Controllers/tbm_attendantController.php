<?php
namespace App\Http\Controllers;

use App\Models\Tbm_Attendant;
use Illuminate\Http\Request;

class tbm_attendantController extends Controller
{
    //only need store, sign, delete
    public function index()
    {
        $attendants = tbm_attendant::all();
        return response()->json(['attendants' => $attendants], 200);
    }

    public function store(Request $request)
    {
        $attendant = tbm_attendant::create($request->all());
        return response()->json(['attendant' => $attendant], 201);
    }

    public function show($id)
    {
        $attendant = tbm_attendant::findOrFail($id);
        return response()->json(['attendant' => $attendant], 200);
    }

    public function update(Request $request, $id)
    {
        $attendant = tbm_attendant::findOrFail($id);
        $attendant->update($request->all());
        return response()->json(['attendant' => $attendant], 200);
    }

    public function destroy($id)
    {
        $attendant = tbm_attendant::findOrFail($id);
        $attendant->delete();
        return response()->json(null, 204);
    }

    public function getTbmAttendants($tbm_id){
        $attendant = tbm_attendant::where('tbm_id' , $tbm_id)->get();

        return $attendant;
    }
}
