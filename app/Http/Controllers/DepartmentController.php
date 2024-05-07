<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function getAllDepartments()
    {
        $departments = Department::all();
        if($departments){
            return response()->json(['departments' => $departments], 200);
        }
        else{
            return response()->json(['error' => 'Departments is still empty'], 404);
        }
    }

    public function getDepartmentName($dept_code)
    {
        $dept = Department::where('dept_code', $dept_code)->first();

        if ($dept) {
            return response()->json(['dept_name' => $dept->dept_name], 200);
        } else {
            return response()->json(['error' => 'Department not found'], 404);
        }
    }


    public function getDepartmentSpv($dept_code)
        {
            $department = Department::where('dept_code', $dept_code)->first();

            if (!$department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            $spvIds = collect([$department->SPV1, $department->SPV2, $department->SPV3])
                ->filter()
                ->unique()
                ->values();

            $spvs = User::whereIn('id', $spvIds)
                ->select('id', 'name')
                ->get();

            return response()->json(['spv_list' => $spvs], 200);
        }

}
