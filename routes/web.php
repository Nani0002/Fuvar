<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Models\Job;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $admin = Auth::user() ? Auth::user()->admin : false;
    $jobs = Auth::user() ? ($admin ? Job::with("user.vehicle")->get() : Auth::user()->jobs()->get()) : [];
    $users = $admin ? User::all()->where("admin", false) : [];
    return view('index', ["jobs" => $jobs, "users" => $users, "admin" => $admin]);
})->name('index');

Route::middleware('auth')->group(function () {
    Route::resource('jobs', JobController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::patch('/jobs/distribute/{id}', [JobController::class, "allocate"])->name('jobs.allocate');
    Route::patch('/jobs/progress/{id}', [JobController::class, "progress"])->name('jobs.progress');
});

require __DIR__ . '/auth.php';
