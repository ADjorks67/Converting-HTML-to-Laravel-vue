<?php

use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::post('/rsvp', [RsvpController::class, 'store'])
    ->name('rsvp.store');