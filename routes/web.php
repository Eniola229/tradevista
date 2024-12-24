<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

//ADMIN
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\CategoryController;

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


Route::get('admin/login', [AdminAuthController::class, 'index'])->name('admin-login');
Route::post('post/login', [AdminAuthController::class, 'postLogin'])->name('admin-login.post'); 

Route::get('admin/logout', [AdminAuthController::class, 'logout'])->name('admin-logout');
//Remeber to fix this
// Route::middleware('admins')->group(function () {
//     Route::get('admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin-dashboard'); 
// });
 Route::get('admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin-dashboard'); 
 Route::get('admin/team', [TeamController::class, 'team'])->name('admin-team'); 
 Route::get('admin/edit/{id}', [TeamController::class, 'edit'])->name('admin-team-edit'); 
 Route::get('admin/delete/{id}', [TeamController::class, 'delete'])->name('admin-team-edit'); 
 Route::post('admin/add', [AdminAuthController::class, 'postRegistration'])->name('admin-team-add'); 
 Route::get('admin/add', [TeamController::class, 'add'])->name('team-add'); 
 Route::get('admin/categories', [CategoryController::class, 'index'])->name('categories-view'); 
 Route::get('admin/add/catagory', [CategoryController::class, 'add'])->name('categories-add'); 
 Route::post('admin/add/catagory', [CategoryController::class, 'addCategory'])->name('admin-add-category'); 
 Route::get('admin/category/delete/{id}', [CategoryController::class, 'delete'])->name('admin-delete-category');
 Route::get('admin/category/edit/{id}', [CategoryController::class, 'edit'])->name('admin-category-edit');
 
require __DIR__.'/auth.php';
