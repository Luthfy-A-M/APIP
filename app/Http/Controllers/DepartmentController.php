<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function getAllDepartments()
    {
        try {
            $departments = Department::all(['dept_code','dept_name']);
            if($departments->isEmpty()){
                return response()->json(['error' => 'Departments is still empty'], 404);
            }
            return response()->json(['departments' => $departments], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDepartmentName($dept_code)
    {
        try {
            $dept = Department::where('dept_code', $dept_code)->first();

            if (!$dept) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            return response()->json(['dept_name' => $dept->dept_name], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function getDepartmentSpv($dept_code)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDepartmentGl($dept_code)
    {
        try {
            $department = Department::where('dept_code', $dept_code)->first();

            if (!$department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            $glIds = collect([$department->GL1, $department->GL2, $department->GL3])
                ->filter()
                ->unique()
                ->values();

            $gls = User::whereIn('id', $glIds)
                ->select('id', 'name')
                ->get();

            return response()->json(['GL_list' => $gls], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDepartmentMgr($dept_code)
    {
        try {
            $department = Department::where('dept_code', $dept_code)->first();

            if (!$department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            $mgrIds = collect([$department->MGR1, $department->MGR2])
                ->filter()
                ->unique()
                ->values();

            $mgrs = User::whereIn('id', $mgrIds)
                ->select('id', 'name')
                ->get();

            return response()->json(['MGR_list' => $mgrs], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDepartmentDh($dept_code)
    {
        try {
            $department = Department::where('dept_code', $dept_code)->first();

            if (!$department) {
                return response()->json(['error' => 'Department not found'], 404);
            }

            $dhIds = $department->Dept_Head;

            $dh = User::where('id',$dhIds)
                ->select('id', 'name')
                ->first();

            return response()->json(['Dept_Head' => $dh], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
