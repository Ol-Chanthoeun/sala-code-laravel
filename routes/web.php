<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Admin\DashboardController;

Route::view('/', 'frontend.home')->name('home');
Route::view('/about', 'frontend.about')->name('about');
Route::view('/courses', 'frontend.courses')->name('courses');
Route::view('/videos', 'frontend.videos')->name('videos');
Route::view('/quiz', 'frontend.quiz')->name('quiz');
Route::view('/test', 'frontend.test')->name('test');
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');
Route::post('/contact/store', [ContactController::class, 'store'])
    ->name('contact.store');
Route::view('/c-programming', 'frontend.c_programming')->name('c_programming');
Route::get('/admin', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

Route::get('/admin/contacts', [DashboardController::class, 'contacts'])
    ->name('admin.contacts');
