<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkOrderController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::resource('workorders', WorkOrderController::class);
Route::get('workorder/search', [App\Http\Controllers\WorkOrderController::class, 'search'])->name('workorder.search');
Route::post('workorder/add', [App\Http\Controllers\WorkOrderController::class, 'add'])->name('workorder.add');
Route::get('/workorder/details/{id}', [WorkOrderController::class, 'details'])->name('workorder.details');
Route::get('/workorder/pdf/{id}', [WorkOrderController::class, 'pdf'])->name('workorder.pdf');
Route::get('/workorder/{id}/images', [WorkOrderController::class, 'images'])->name('workorder.images');
Route::delete('/workorder/images/{id}', [WorkOrderController::class, 'destroyImage'])->name('workorder.images.delete');
Route::post('workorder/images/update', [WorkOrderController::class, 'updateImage'])->name('workorder.updateImage');
Route::delete('/workorder/delete/destroyWork', [WorkOrderController::class, 'destroyWork'])->name('workorders.destroyWork');
Route::get('/workorder/{id}', [WorkOrderController::class, 'getWorkOrder'])->name('workorder.getWorkOrder');
require __DIR__.'/auth.php';
