<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id}', function ($id) {
    $client = new Client();
    $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
    $client->addScope(Sheets::SPREADSHEETS_READONLY);
    
    $service = new Sheets($client);
    $spreadsheetId = env('GOOGLE_SHEET_ID');
    $range = 'Sheet1!A2:B'; // Asumsi Kolom A adalah ID, Kolom B adalah Nama

    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    $namaTamu = 'Tamu Undangan';
    foreach ($values as $row) {
        if ($row[0] == $id) { // Cari berdasarkan ID di kolom A
            $namaTamu = $row[1]; // Ambil nama di kolom B
            break;
        }
    }

    return view('invitation', ['namaTamu' => $namaTamu]);
});
Route::get('/', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});