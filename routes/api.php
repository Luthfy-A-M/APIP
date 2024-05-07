<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartmentController;

//==============AUTH ROUTING============
// Registration
Route::post('register', [AuthController::class, 'register']);

// Login
Route::post('login', [AuthController::class, 'login']);

//===================DEPARTMENTS ROUTING===============
//GET
Route::get('department/{dept_code}', [DepartmentController::class, 'getDepartmentName']);//get department name
Route::get('departments', [DepartmentController::class, 'getAllDepartments']); //get a list of departments
Route::get('department/{dept_code}/spv', [DepartmentController::class, 'getDepartmentSpv']); //get DEPT SPV
Route::get('department/{dept_code}/gl', [DepartmentController::class, 'getDepartmentGl']);  //get DPT GL
Route::get('department/{dept_code}/mgr', [DepartmentController::class, 'getDepartmentMgr']);//GET DEPT MGR
Route::get('department/{dept_code}/dh', [DepartmentController::class, 'getDepartmentDh']);//GET DEPT DEPTHEAD

//====================TBM ROUTING=====================


