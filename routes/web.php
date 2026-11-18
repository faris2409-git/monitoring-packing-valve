<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Supervisor\EngineValveController;
use App\Http\Controllers\Supervisor\PackagingTaskController;
use App\Http\Controllers\Supervisor\OperatorController;
use App\Http\Controllers\Supervisor\ShipmentController;
use App\Http\Controllers\Operator\PackagingTaskController as OperatorPackagingTaskController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:supervisor'])->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/dashboard', function () {
        $totalValves = \App\Models\EngineValve::count();
        $assignedTasksCount = \App\Models\PackagingTask::where('status', 'assigned')->count();
        $inProgressTasksCount = \App\Models\PackagingTask::whereIn('status', ['accepted', 'in_progress'])->count();
        $activeTasks = $assignedTasksCount + $inProgressTasksCount;
        $completedTasks = \App\Models\PackagingTask::where('status', 'completed')->count();
        $totalShipments = \App\Models\Shipment::count();
        $recentTasks = \App\Models\PackagingTask::with(['engineValve', 'operator'])->latest()->take(10)->get();

        return view('supervisor.dashboard', compact(
            'totalValves', 'activeTasks', 'completedTasks', 'totalShipments', 'recentTasks', 'assignedTasksCount', 'inProgressTasksCount'
        ));
    })->name('dashboard');

    Route::resource('engine-valves', EngineValveController::class);
    Route::resource('operators', OperatorController::class);
    Route::resource('packaging-tasks', PackagingTaskController::class)->except(['edit', 'update']);
    Route::get('/monitoring', [PackagingTaskController::class, 'monitoring'])->name('packaging-tasks.monitoring');
    Route::get('/shipments', [ShipmentController::class, 'index'])->name('shipments.index');
    Route::get('/shipments/create', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/shipments', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/shipments/{shipment}', [ShipmentController::class, 'show'])->name('shipments.show');
    Route::get('/shipments/{shipment}/pdf', [ShipmentController::class, 'exportPdf'])->name('shipments.pdf');
});

Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', function () {
        $assignedCount = \App\Models\PackagingTask::where('assigned_to', auth()->id())->where('status', 'assigned')->count();
        $inProgressCount = \App\Models\PackagingTask::where('assigned_to', auth()->id())->whereIn('status', ['accepted', 'in_progress'])->count();
        $completedCount = \App\Models\PackagingTask::where('assigned_to', auth()->id())->where('status', 'completed')->count();
        $myTasks = \App\Models\PackagingTask::with('engineValve')->where('assigned_to', auth()->id())->latest()->take(10)->get();
        $priorityTask = \App\Models\PackagingTask::with('engineValve')->where('assigned_to', auth()->id())->whereIn('status', ['accepted', 'in_progress'])->latest()->first();
        $totalTarget = (int) \App\Models\PackagingTask::where('assigned_to', auth()->id())->sum('quantity_target');
        $totalPacked = (int) \App\Models\PackagingTask::where('assigned_to', auth()->id())->sum('quantity_packed');

        return view('operator.dashboard', compact(
            'assignedCount', 'inProgressCount', 'completedCount', 'myTasks', 'priorityTask', 'totalTarget', 'totalPacked'
        ));
    })->name('dashboard');

    Route::get('/tugas', [OperatorPackagingTaskController::class, 'index'])->name('packaging-tasks.index');
    Route::post('/tugas/{packagingTask}/accept', [OperatorPackagingTaskController::class, 'accept'])->name('packaging-tasks.accept');

    Route::get('/pengerjaan', [OperatorPackagingTaskController::class, 'work'])->name('packaging-tasks.work');
    Route::get('/pengerjaan/{packagingTask}/edit', [OperatorPackagingTaskController::class, 'edit'])->name('packaging-tasks.edit');
    Route::put('/pengerjaan/{packagingTask}', [OperatorPackagingTaskController::class, 'update'])->name('packaging-tasks.update');
    Route::get('/riwayat', [OperatorPackagingTaskController::class, 'history'])->name('packaging-tasks.history');
    Route::delete('/riwayat/{packagingTask}', [OperatorPackagingTaskController::class, 'destroy'])->name('packaging-tasks.destroy');
    Route::get('/riwayat/{packagingTask}/pdf', [OperatorPackagingTaskController::class, 'exportPdf'])->name('packaging-tasks.pdf');
});