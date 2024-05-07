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


//departments
Route::get('department/{dept_code}', [DepartmentController::class, 'getDepartmentName']);
Route::get('departments', [DepartmentController::class, 'getAllDepartments']);
Route::get('department/{dept_code}/spv', [DepartmentController::class, 'getDepartmentSpv']);


