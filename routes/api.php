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

use App\Http\Controllers\TBMController;
//dont use sanctum yet due time limit

// Get all TBM data
Route::get('/tbms', [TBMController::class, 'index']);

// Create a new TBM entry
Route::post('/tbms', [TBMController::class, 'store']);

// Get a specific TBM entry by ID
Route::get('/tbms/{id}', [TBMController::class, 'show']);

// Update a specific TBM entry by ID
Route::put('/tbms/{id}', [TBMController::class, 'update']);

// Delete a specific TBM entry by ID
Route::delete('/tbms/{id}', [TBMController::class, 'destroy']);

// Search TBM data by parameters
Route::get('/tbms/search/{param1}/{param2}', [TBMController::class, 'searchby']);
