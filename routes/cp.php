<?php

use Illuminate\Support\Facades\Route;
use StuartPringle\Newsletter\Http\Controllers\NewsletterController;

Route::middleware(['statamic.cp.authenticated'])
    ->prefix('newsletter')
    ->name('newsletter.')
    ->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->name('index');
        Route::post('/resend/{subscriber}', [NewsletterController::class, 'resend'])->name('resend');
        Route::post('/status/{subscriber}', [NewsletterController::class, 'updateStatus'])->name('status');
        Route::delete('/{subscriber}', [NewsletterController::class, 'destroy'])->name('destroy');
        Route::post('/add', [NewsletterController::class, 'store'])->name('store');
    });
