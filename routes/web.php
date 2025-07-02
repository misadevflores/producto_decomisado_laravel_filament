<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\PdfController;
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
use Filament\Facades\Filament;

Route::get('/login', fn () => redirect(route('filament.admin.auth.login')))->name('login');

Route::get('/', function () {
    return redirect(route('filament.admin.auth.login'));
});


// Route::get('/pdf/generate/{seizures}', function () {
//     $pdf = Pdf::loadView('pdf.example');
//     return $pdf->download('example.pdf');
// })->name('pdf.example');

Route::get('/pdf/generate/decomiso/{id}', [PdfController::class, 'SeizuresRecords'])->name('pdf.example');

Route::get('/debug-client', function () {
    return App\Models\Client::count();
});

