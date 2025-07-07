<?php

use App\Events\TaskCreated;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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

    Route::resource('tarefas', TaskController::class);
});

Route::get('/locale/{lang}', function ($lang) {
    $availableLangs = ['en', 'pt'];
    if (in_array($lang, $availableLangs)) {
        Session::put('locale', $lang);
    }
    return redirect()->back();
})->name('locale.switch');

require __DIR__.'/auth.php';
