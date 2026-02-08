<?php

use Illuminate\Support\Facades\Route;
use StuartPringle\Newsletter\Http\Controllers\NewsletterController;
use StuartPringle\Newsletter\Http\Controllers\Cp\ListsController;
use StuartPringle\Newsletter\Http\Controllers\Cp\TagsController;
use StuartPringle\Newsletter\Http\Controllers\Cp\SegmentsController;
use StuartPringle\Newsletter\Http\Controllers\Cp\NewsletterUsersController;
use StuartPringle\Newsletter\Http\Controllers\Cp\TemplatesController;
use StuartPringle\Newsletter\Http\Controllers\Cp\CampaignsController;

Route::middleware(['statamic.cp.authenticated'])
    ->prefix('newsletter')
    ->name('newsletter.')
    ->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->name('index');
        Route::post('/resend/{subscriber}', [NewsletterController::class, 'resend'])->name('resend');
        Route::post('/status/{subscriber}', [NewsletterController::class, 'updateStatus'])->name('status');
        Route::delete('/{subscriber}', [NewsletterController::class, 'destroy'])->name('destroy');
        Route::post('/add', [NewsletterController::class, 'store'])->name('store');

        Route::prefix('lists')->name('lists.')->group(function () {
            Route::get('/', [ListsController::class, 'index'])->name('index');
            Route::get('/create', [ListsController::class, 'create'])->name('create');
            Route::post('/', [ListsController::class, 'store'])->name('store');
            Route::get('/{list}/edit', [ListsController::class, 'edit'])->name('edit');
            Route::put('/{list}', [ListsController::class, 'update'])->name('update');
            Route::delete('/{list}', [ListsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('tags')->name('tags.')->group(function () {
            Route::get('/', [TagsController::class, 'index'])->name('index');
            Route::get('/create', [TagsController::class, 'create'])->name('create');
            Route::post('/', [TagsController::class, 'store'])->name('store');
            Route::get('/{tag}/edit', [TagsController::class, 'edit'])->name('edit');
            Route::put('/{tag}', [TagsController::class, 'update'])->name('update');
            Route::delete('/{tag}', [TagsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('segments')->name('segments.')->group(function () {
            Route::get('/', [SegmentsController::class, 'index'])->name('index');
            Route::get('/create', [SegmentsController::class, 'create'])->name('create');
            Route::post('/', [SegmentsController::class, 'store'])->name('store');
            Route::get('/{segment}/edit', [SegmentsController::class, 'edit'])->name('edit');
            Route::put('/{segment}', [SegmentsController::class, 'update'])->name('update');
            Route::delete('/{segment}', [SegmentsController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('newsletter_users')->name('users.')->group(function () {
            Route::get('/', [NewsletterUsersController::class, 'index'])->name('index');
            Route::get('/create', [NewsletterUsersController::class, 'create'])->name('create');
            Route::post('/', [NewsletterUsersController::class, 'store'])->name('store');
            Route::get('/{tenant}/edit', [NewsletterUsersController::class, 'edit'])->name('edit');
            Route::put('/{tenant}', [NewsletterUsersController::class, 'update'])->name('update');
            Route::delete('/{tenant}', [NewsletterUsersController::class, 'destroy'])->name('destroy');

            Route::post('/{tenant}/members', [NewsletterUsersController::class, 'addMember'])->name('members.store');
            Route::put('/{tenant}/members/{member}', [NewsletterUsersController::class, 'updateMember'])->name('members.update');
            Route::delete('/{tenant}/members/{member}', [NewsletterUsersController::class, 'removeMember'])->name('members.destroy');
        });

        Route::prefix('templates')->name('templates.')->group(function () {
            Route::get('/', [TemplatesController::class, 'index'])->name('index');
            Route::get('/create', [TemplatesController::class, 'create'])->name('create');
            Route::post('/', [TemplatesController::class, 'store'])->name('store');
            Route::get('/{template}/edit', [TemplatesController::class, 'edit'])->name('edit');
            Route::put('/{template}', [TemplatesController::class, 'update'])->name('update');
            Route::delete('/{template}', [TemplatesController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('campaigns')->name('campaigns.')->group(function () {
            Route::get('/', [CampaignsController::class, 'index'])->name('index');
            Route::get('/create', [CampaignsController::class, 'create'])->name('create');
            Route::post('/', [CampaignsController::class, 'store'])->name('store');
            Route::get('/{campaign}/edit', [CampaignsController::class, 'edit'])->name('edit');
            Route::put('/{campaign}', [CampaignsController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [CampaignsController::class, 'destroy'])->name('destroy');
        });
    });
