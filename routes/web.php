<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/invitation', function (Request $request) {
    // Mengambil nama dari parameter 'to', jika kosong defaultnya 'Tamu Undangan'
    $namaTamu = $request->query('to', 'Tamu Undangan');

    return view('invitation', compact('Tamu Undangan'));
});

Route::get('/', function () {
    return view('invitation');
});

Route::get('/invitation', function () {
    return view('invitation');
});