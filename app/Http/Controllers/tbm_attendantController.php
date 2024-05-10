<?php

namespace App\Http\Controllers;

use App\Models\tbm_attendant;
use Illuminate\Http\Request;

class tbm_attendantController extends Controller
{
    //only need store, sign, delete
    public function store(Request $request)
    {
        try {
            $attendant = tbm_attendant::create($request->all());
            return response()->json(['attendant' => $attendant], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $attendant = tbm_attendant::findOrFail($id);
            return response()->json(['attendant' => $attendant], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $attendant = tbm_attendant::findOrFail($id);
            $attendant->update($request->all());
            return response()->json(['attendant' => $attendant], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $attendant = tbm_attendant::where('attendant_id', $request->attendant_id)
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
            $attendants = tbm_attendant::where('tbm_id', $tbm_id)->get();
            return $attendants;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getMyTbmAttendance(Request $request){
        try {
            $tbm_id = $request->tbm_id;
            $user_id = $request->user_id;
            $attendance = tbm_attendant::where('tbm_id',$tbm_id)->where('attendant_id',$user_id)->firstOrFail();
            return response()->json(['attendants' => $attendance], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMyUnsignedTbmAttendance(Request $request){
        try {
            $request->validate([
                'user_id'=>'required',
            ],[
                'user_id.required' => 'The User ID is required',
            ]);
            $attendances = tbm_attendant::where('attendant_id', $request->user_id)
            ->whereNull('signed_date')
            ->get();
            return $attendances;

        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function signTbmAttendants(Request $request){
        try {
            //here we try to check if he really is the one who need to sign in
            $tbm_attendance_id  = $request->tbm_attendance_id;
            $tbm_attandance_user_id = $request->user_id;
            $attendant = tbm_attendant::findOrFail($tbm_attendance_id);
            if($attendant->attendant_id == $tbm_attandance_user_id){
                $attendant->update(['signed_date',now()]); //just update the signed date, nothing else
            }
            return response()->json(['tbm attendance' => $attendant], 200); //return updated tbm_attendant
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
