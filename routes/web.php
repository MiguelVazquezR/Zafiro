<?php

use App\Http\Controllers\BatchController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SubdivisionController;
use App\Http\Controllers\WorkController;
use App\Http\Resources\BatchResource;
use App\Http\Resources\SubdivisionResource;
use App\Models\Batch;
use App\Models\Subdivision;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {

    $subdivisions = SubdivisionResource::collection(Subdivision::with('media')->get());
    $batches = BatchResource::collection(Batch::with(['media', 'subdivision'])->get()->take(2));
    $total_batches = Batch::all()->count();

    return Inertia::render('Lotes', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'subdivisions' => $subdivisions,
        'batches' => $batches,
        'total_batches' => $total_batches,
    ]);
});

Route::get('/otros-servicios', function () {
    return Inertia::render('OtrosServicios', [
        'canLogin' => Route::has('login'),
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

// ** works routes **
Route::resource('works', WorkController::class)->middleware('auth');
Route::get('works/{work_id}/invoice', [WorkController::class, 'showInvoice'])->middleware('auth')->name('works.invoice');
Route::post('works/massive-store', [WorkController::class, 'massiveStore'])->middleware('auth')->name('works.massive-store');
Route::post('works/massive-delete', [WorkController::class, 'massiveDelete'])->name('works.massive-delete');


// ** messages routes **
Route::resource('messages', MessageController::class)->except('show')->middleware('auth');
Route::post('messages/mark-as-dispatched', [MessageController::class, 'MarkAsdispatched'])->name('messages.mark-as-dispatched');
Route::post('messages/massive-delete', [MessageController::class, 'massiveDelete'])->name('messages.massive-delete');


// ** subdivisions routes **
Route::resource('subdivisions', SubdivisionController::class)->except('show')->middleware('auth');
Route::get('/subdivisions/{subdivision}', [SubdivisionController::class, 'show'])->name('subdivisions.show');
Route::post('subdivisions/update-with-media/{subdivision}', [SubdivisionController::class, 'updateWithMedia'])->name('subdivisions.update-with-media');


// ** batches routes **
Route::resource('batches', BatchController::class)->except('show')->middleware('auth');
Route::get('/batches/{batch}', [BatchController::class, 'show'])->name('batches.show');
Route::post('batches/update-with-media/{batch}', [BatchController::class, 'updateWithMedia'])->name('batches.update-with-media');
Route::get('batches-get-by-page', [BatchController::class, 'getItemsByPage'])->name('batches.get-by-page');


// ** quotes routes **
Route::resource('quotes', QuoteController::class)->middleware('auth');
Route::post('quotes/massive-delete', [QuoteController::class, 'massiveDelete'])->name('quotes.massive-delete');
Route::put('quotes-update-status/{quote}', [QuoteController::class, 'updateStatus'])->name('quotes.update-status');


//artisan commands
Route::get('/route-cache', function () {
    Artisan::call('route:cache');
    return 'routes cached!.';
});

Route::get('/storage-link', function () {
    Artisan::call('storage:link');
    return 'media linked!.';
});