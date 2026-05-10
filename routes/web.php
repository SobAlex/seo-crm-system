<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\WebsiteTypeController;
use App\Http\Controllers\BusinessProcessController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\PlanningController;
use App\Http\Controllers\ReportController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

// Клиенты
Route::resource('clients', ClientController::class);

// Проекты
Route::resource('projects', ProjectController::class);

// Сайты
Route::resource('websites', WebsiteController::class);

// Типы сайтов (справочник)
Route::resource('website-types', WebsiteTypeController::class)->except(['show']);

// Бизнес-процессы
Route::resource('business-processes', BusinessProcessController::class);

// Треки
Route::resource('tracks', TrackController::class);

// Задачи
Route::resource('tasks', TaskController::class);

// Планирование
Route::resource('plannings', PlanningController::class);

// Отчёты
Route::resource('reports', ReportController::class);

require __DIR__.'/settings.php';
