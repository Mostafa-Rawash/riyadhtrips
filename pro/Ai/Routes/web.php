<?php

use Illuminate\Support\Facades\Route;
use Pro\Ai\Controllers\TextController;

Route::prefix('ai/')->name('ai.')->middleware(['web', 'auth'])->group(function () {
    Route::post('/text/generate', [TextController::class, 'generate'])->name('text.generate');
});
