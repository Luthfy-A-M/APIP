<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\tbm_attendantController; //tbm attendant
use App\Http\Controllers\tbm_instructorController; //tbm instructor

use App\Models\TBM;
use App\Models\User;




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
                'user_id' => 'required', //// Validate prepared_by is required and is a string with maximum length of 255 characters
                'dept_code' => 'required', // Validate dept_code is required and is a string with maximum length of 255 characters
            ],[
                'user_id.required' => 'The user ID is required.',
                'dept_code.required' => 'The department code is required.',
            ]);

            $request->merge(['prepared_by' => $request->user_id]);
            $request->merge(['status' => 'draft']);

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
            //get assigned instructor and person
            // Get assigned instructor and person with their names
            $instructors = (new tbm_instructorController())->getTbmInstructor($id);
            $attendants = (new tbm_attendantController())->getTbmAttendants($id);
            // dd($instructors);
            // Transform user IDs to user names for instructors and attendants
            foreach ($instructors as $instructor) {
                 
                $user = User::findOrFail($instructor->instructor_id);
                $instructor->user_name = $user->name;
            }

            foreach ($attendants as $attendant) {
                $user = User::findOrFail($attendant->attendant_id);
                $attendant->user_name = $user->name;
            }
            // Transform user IDs to user names for prepared_by, checked_by, reviewed_by, and approved_by
            $preparedByUser = User::findOrFail($tbms->prepared_by);
            $checkedByUser = $tbms->checked_by ? User::findOrFail($tbms->checked_by) : null;
            $reviewedByUser = $tbms->reviewed_by ? User::findOrFail($tbms->reviewed_by) : null;
            $approved1ByUser = $tbms->approved1_by ? User::findOrFail($tbms->approved1_by) : null;
            $approved2ByUser = $tbms->approved2_by ? User::findOrFail($tbms->approved2_by) : null;
            // Mengembalikan data dalam bentuk JSON
            return response()->json([
                'tbm' => $tbms ,
                'tbm_instructor' => $instructors,
                'tbm_attendance' => $attendants,
                'prepared_by_name' => $preparedByUser->name,
                'checked_by_name' => $checkedByUser ? $checkedByUser->name : null,
                'reviewed_by_name' => $reviewedByUser ? $reviewedByUser->name : null,
                'approved1_by_name' => $approved1ByUser ? $approved1ByUser->name : null,
                'approved2_by_name' => $approved2ByUser ? $approved2ByUser->name : null,
            ]);
        } catch (\Exception $e) {
            // Mengembalikan pesan kesalahan jika terjadi kesalahan
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            if ($request->getMethod() !== 'PUT') {
                // If the request method is not PUT, return a response indicating that POST method is required
                return response()->json(['error' => 'PUT method is required'], 405);
            }
            // Validasi request
            $request->validate([
                'user_id'=>'required',
                'tbm_id'=>'required'
            ],[
                'user_id.required' => 'The User ID is required',
                'tbm_id.required' => 'The tbm_id is required'
            ]);
            $id = $request->tbm_id;
            // Mengambil data TBMS berdasarkan ID
            $tbms = TBM::findOrFail($id);

            if($request->user_id !== $tbms->prepared_by){
                return response()->json(['error' => 'You Dont have access']);
            }

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

    public function getAssignablePerson(Request $request){
        try{
            //receive json
            //dept_code
            //tbm_id
             // Validate the JSON data
            $validator = Validator::make($request->all(), [
                'tbm_id' => 'required', // Validate 'tbm_id' as required and numeric
                'dept_code' => 'required', // Validate 'dept_code' as required and string
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $jsonData = $request->json()->all();

            $excludedAttendantIds = (new tbm_attendantController())->getTbmAttendants($request->tbm_id)->pluck('attendant_id');

            $excludedInstructorIds = (new tbm_instructorController())->getTbmInstructor($request->tbm_id)->pluck('instructor_id');

            $persons = User::select('id','name')->where('dept_code', $request->dept_code)
            ->whereNotIn('id', $excludedAttendantIds)
            ->whereNotIn('id', $excludedInstructorIds)
            ->get();

            return response()->json(['persons' => $persons]);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function assignAttendant(Request $request){
        try{
            //tbm_id
            //user_id
            $validator = Validator::make($request->all(), [
                'tbm_id' => 'required', // Validate 'tbm_id' as required and numeric
                'attendant_id' => 'required', // Validate 'attendant_id' as required and string
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            TBM::findOrFail($request->tbm_id);

            return (new tbm_attendantController())->Store($request);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unassignAttendant(Request $request){
        try{
            //tbm_id
            //user_id
            $validator = Validator::make($request->all(), [
                'tbm_id' => 'required', // Validate 'tbm_id' as required and numeric
                'attendant_id' => 'required', // Validate 'attendant_id' as required and string
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            TBM::findOrFail($request->tbm_id);

            return (new tbm_attendantController())->Destroy($request);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function assignInstructor(Request $request){
        try{
            //tbm_id
            //user_id
            $validator = Validator::make($request->all(), [
                'tbm_id' => 'required', // Validate 'tbm_id' as required and numeric
                'instructor_id' => 'required', // Validate 'instructor_id' as required and string
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            TBM::findOrFail($request->tbm_id);
            return (new tbm_instructorController())->Store($request);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function unassignInstructor(Request $request){
        try{
            //tbm_id
            //user_id
            $validator = Validator::make($request->all(), [
                'tbm_id' => 'required', // Validate 'tbm_id' as required and numeric
                'instructor_id' => 'required', // Validate 'instructor_id' as required and string
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            TBM::findOrFail($request->tbm_id);
            return (new tbm_instructorController())->destroy($request);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function postReleaseTbm(Request $request){
        try{
            $request->validate([
                'user_id'=>'required',
                'tbm_id'=>'required'
            ],[
                'user_id.required' => 'The user_id is required',
                'tbm_id.required' => 'The tbm_id is required'
            ]);

            $id = $request->tbm_id;
            //here we handle when user click release on tbm
            //first we update the TBM
            $this->update($request);
            //make sure the TBM is ready to released by checking theres no empty or null value on TBM
            $tbms = TBM::findOrFail($id);
            //check ownership of
            if($tbms->prepared_by == $request->user_id){

            }else{
                return response()->json(" Kamu siapaa ");
            }

                    // Define the validation rules
            $rules = [
                'dept_code' => 'required',
                'section' => 'required',
                'shift' => 'required',
                'date' => 'required',
                'time' => 'required',
                'title' => 'required',
                'pot_danger_point' => 'required',
                'most_danger_point' => 'required',
                'countermeasure' => 'required',
                'key_word' => 'required',
                'prepared_by' => 'required',
                'checked_by' => 'required',
                'reviewed_by' => 'required',
                'approved1_by' => 'required',
                'approved2_by' => 'required',
                // Not signed yet
            ];

            // Define custom error messages for validation failures
            $messages = [
                'dept_code.required' => 'The dept code is required.',
                'section.required' => 'The section is required.',
                'shift.required' => 'The shift is required.',
                'date.required' => 'The date is required.',
                'time.required' => 'The time is required.',
                'title.required' => 'The title is required.',
                'pot_danger_point.required' => 'The pot danger point is required.',
                'most_danger_point.required' => 'The most danger point is required.',
                'countermeasure' => 'The countermeasure of most danger point is required',
                'key_word.required' => 'The key word is required.',
                'prepared_by.required' => 'The prepared by is required.',
                'checked_by.required' => 'The checked by is required.',
                'reviewed_by.required' => 'The reviewed by is required.',
                'approved1_by.required' => 'The approved 1 by is required.',
                'approved2_by.required' => 'The approved 2 by is required.',
            ];

            // Perform validation
            $validator = Validator::make($tbms->toArray(), $rules, $messages);

            // Check if validation fails
            if ($validator->fails()) {
                // Return response with validation errors
                return response()->json(['errors' => $validator->errors()], 422);
            }
            //after we check all the tbm release data then we check the assigned and instructor, atleast have 1 of em
            //check attendance & instructor atleast 1
            $attender = (new tbm_attendantController())->getTbmAttendants($id)->count();
            $instructor = (new tbm_instructorController())->getTbmInstructor($id)->count();
            // dd($attender);
            if($attender < 1 || $instructor < 1 ){
                return response()->json(['errors' => 'Make Sure you assign atleast one instructor and attender']);
            }


            $status = 'released';
                if ($tbms->checked_by_sign_date !== null) {
                    $status = 'review';
                    if ($tbms->reviewed_by_sign_date !== null) {
                        $status = 'approve1';
                        if ($tbms->approved1_by_sign_date !== null) {
                            $status = 'approve2';
                        }
                    }
                }


            $tbms->update([
                'prepared_by_sign_date' => now(),
                'status' => $status
            ]);

            return response()->json($tbms);
        }
        catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }





    public function getMyUnassignTBM(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required'
            ]);

            // Initialize an empty collection to store merged results
            $mergedTbms = collect();

            // Query and merge tbms where the user is assigned as different signers
            $tbmsChecker = TBM::select('title', 'id', 'date', 'prepared_by')
                ->where('checked_by', $request->user_id)
                ->whereNotNull('prepared_by_sign_date')
                ->whereNull('checked_by_sign_date')
                ->get();

            $tbmsReviewer = TBM::select('title', 'id', 'date', 'prepared_by')
                ->where('reviewed_by', $request->user_id)
                ->whereNotNull('checked_by_sign_date')
                ->whereNull('reviewed_by_sign_date')
                ->get();

            $tbmsApprove1 = TBM::select('title', 'id', 'date', 'prepared_by')
                ->where('approved1_by', $request->user_id)
                ->whereNotNull('reviewed_by_sign_date')
                ->whereNull('approved1_by_sign_date')
                ->get();

            $tbmsApprove2 = TBM::select('title', 'id', 'date', 'prepared_by')
                ->where('approved2_by', $request->user_id)
                ->whereNotNull('approved1_by_sign_date')
                ->whereNull('approved2_by_sign_date')
                ->get();

            //Get MyTbms as instructor
            $tbm_instructor = (new tbm_instructorController)->getMyUnsignedTbmInstructor($request);
            foreach($tbm_instructor as $tbmI){
                $tbmnya = TBM::findOrFail($tbmI->tbm_id);
                $tbmI->tbm = $tbmnya;
            }
            //as tbm attender
            $tbm_attendant = (new tbm_attendantController)->getMyUnsignedTbmAttendance($request);
            foreach($tbm_instructor as $tbmA){
                $tbmnya = TBM::findOrFail($tbmA->tbm_id);
                $tbmA->tbm = $tbmnya;
            }
            // Merge all collections into a single collection
            $mergedTbms = $mergedTbms->merge($tbmsChecker)
                ->merge($tbmsReviewer)
                ->merge($tbmsApprove1)
                ->merge($tbmsApprove2);


            // Get unique user IDs from merged TBMs
            $userIds = $mergedTbms->pluck('prepared_by')->unique()->toArray();

            // Fetch user names based on user IDs
            $users = User::whereIn('id', $userIds)->get()->keyBy('id');

            // Attach user names to each TBM
            foreach ($mergedTbms as $tbm) {
                $tbm->prepared_by_user = $users[$tbm->prepared_by] ?? null;
            }

            // Check if there are any TBM records found
            if ($mergedTbms->isEmpty() && $tbm_attendant->isEmpty() && $tbm_instructor->isEmpty()) {
                return response()->json('Nothing to be signed');
            }

            if($mergedTbms->isEmpty()){
                $mergedTbms = 'Nothing to be signed';
            }
            if($tbm_attendant->isEmpty()){
                $tbm_attendant = 'Nothing to be signed';
            }
            if($tbm_instructor->isEmpty()){
                $tbm_instructor = 'Nothing to be signed';
            }


            // Return the merged TBM records
            return response()->json(['Important' => $mergedTbms, 'Attendance' => $tbm_attendant , 'Instructor' => $tbm_instructor]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMyTbm(Request $request){
        try{
            $request->validate(
                ['user_id' => 'required']
            );
            $mytbm = TBM::where('prepared_by', $request->user_id)->get();
            return response()->json($mytbm);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function signTbm(Request $request){
        try{
            $request->validate(['user_id' => 'required','tbm_id' => 'required']);
            $tbm = TBM::FindorFail($request->tbm_id);
            if($tbm->checked_by == $request->user_id  && $tbm->status == "released"){
                $tbm->update(['checked_by_sign_date'=>now(),'status' => 'review' ]);
            }
            else if($tbm->reviewed_by == $request->user_id  && $tbm->status == "review"){
                $tbm->update(['reviewed_by_sign_date'=>now(),'status' => 'approve1' ]);
            }
            else if($tbm->approved1_by == $request->user_id  && $tbm->status == "approve1"){
                $tbm->update(['approved1_by_sign_date'=>now(),'status' => 'approve2' ]);
            }
            else if($tbm->approved2_by == $request->user_id  && $tbm->status == "approve2"){
                $tbm->update(['approved2_by_sign_date'=>now(),'status' => 'completed' ]);
            }else{
                return response()->json('Kamu siapaa, kamu siapaa');
            }
            return response()->json($tbm);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function rejectTbm(Request $request){
        try{
            $request->validate(['user_id' => 'required','tbm_id' => 'required']);
            $tbm = TBM::FindorFail($request->tbm_id);
            if($tbm->checked_by == $request->user_id  && $tbm->status == "released"){
                $tbm->update(['status' => 'rejected' ]);
            }
            else if($tbm->reviewed_by == $request->user_id  && $tbm->status == "review"){
                $tbm->update(['status' => 'rejected' ]);
            }
            else if($tbm->approved1_by == $request->user_id  && $tbm->status == "approve1"){
                $tbm->update(['status' => 'rejected' ]);
            }
            else if($tbm->approved2_by == $request->user_id  && $tbm->status == "approve2"){
                $tbm->update(['status' => 'rejected' ]);
            }else{
                return response()->json('Kamu siapaa, kamu siapaa');
            }
            return response()->json($tbm);
        }
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
