<?php

\Illuminate\Support\Facades\Route::post('login', 'YoAuth\Http\Actions\Login')->name('auth.login');
\Illuminate\Support\Facades\Route::middleware(['api', 'auth:api'])->name('auth.')->group(function() {
    \Illuminate\Support\Facades\Route::post('refresh', 'YoAuth\Http\Actions\Refresh')->name('refresh');
    \Illuminate\Support\Facades\Route::post('logout', 'YoAuth\Http\Actions\Logout')->name('logout');
    \Illuminate\Support\Facades\Route::get('users/{user}', 'YoAuth\Http\Actions\ShowUser')->name('user');
});
