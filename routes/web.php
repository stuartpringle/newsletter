<?php

use Illuminate\Support\Facades\Route;
use StuartPringle\Newsletter\Http\Controllers\NewsletterSignupController;
use StuartPringle\Newsletter\Http\Controllers\TrackingController;
use StuartPringle\Newsletter\Http\Controllers\WebhookController;
use StuartPringle\Newsletter\Http\Controllers\NewsletterPreferencesController;

Route::post('/newsletter/signup', [NewsletterSignupController::class, 'store'])->name('newsletter.signup');
Route::get('/newsletter/confirm/{token}', [NewsletterSignupController::class, 'confirm'])->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterSignupController::class, 'showUnsubscribe'])->name('newsletter.unsubscribe.show');
Route::post('/newsletter/unsubscribe/{token}', [NewsletterSignupController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/newsletter/track/open/{send}', [TrackingController::class, 'open'])->name('newsletter.track.open');
Route::get('/newsletter/track/click/{send}', [TrackingController::class, 'click'])->name('newsletter.track.click');
Route::post('/newsletter/webhooks/postmark', [WebhookController::class, 'postmark'])->name('newsletter.webhooks.postmark');
Route::get('/newsletter/preferences/{token}', [NewsletterPreferencesController::class, 'show'])->name('newsletter.preferences.show');
Route::post('/newsletter/preferences/{token}', [NewsletterPreferencesController::class, 'update'])->name('newsletter.preferences.update');
