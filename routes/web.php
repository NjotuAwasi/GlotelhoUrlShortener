<?php

use App\Http\Controllers\UrlManager;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// this route is created to post the request and create the short url
Route::post("/api/shorten", [UrlManager::class, 'createShortUrl'])->name('url.short');

//this route is created to redirect a short url to the original url
Route::get("/{short_code}", [UrlManager::class, 'redirectToOriginalUrl'])->name('url.redirect');

// this route is ceated to display the stats of the url
Route::get("/api/stats/{short_code}", [UrlManager::class, 'stats'])->name('url.stats');



