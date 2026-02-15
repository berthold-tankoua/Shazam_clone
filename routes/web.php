<?php

use App\Http\Controllers\Api\ShazamController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search/result', [HomeController::class, 'viewResult'])->name('view.result');


Route::post('/n8n-webhook', [ShazamController::class, 'handleWebhook']);
