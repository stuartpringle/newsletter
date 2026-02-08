<?php

use Illuminate\Support\Facades\Route;
use StuartPringle\Newsletter\Http\Controllers\NewsletterSignupController;

Route::post('/newsletter/signup', [NewsletterSignupController::class, 'store'])->name('newsletter.signup');
Route::get('/newsletter/confirm/{token}', [NewsletterSignupController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterSignupController::class, 'showUnsubscribe'])->name('newsletter.unsubscribe.show');
Route::post('/newsletter/unsubscribe/{token}', [NewsletterSignupController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
