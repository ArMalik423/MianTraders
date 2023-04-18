<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Route\Http\Admin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Admin::register();

Route::get('/', function () {
    $view = (Auth::check()) ? 'dashboard' : 'login';
    return redirect()->route($view);
    // return view('welcome');
});

Route::get('/dashboard', [DashboardController::class,'dashboard'])->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
