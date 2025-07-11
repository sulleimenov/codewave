<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('app'); // или 'welcome', если ты не создавал отдельный Blade-шаблон
})->where('any', '.*');

