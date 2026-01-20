<?php

Route::middleware('auth')->get('/admin', function () {
    return response()->json(['message' => 'welcome admin']);
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api|storage|sanctum).*$');


