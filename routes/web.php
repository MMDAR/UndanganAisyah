<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id}', function ($id) {
    try {
        $client = new Client();
        $client->setAuthConfig(json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true));
        $authConfig = json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true);
        // Tambahkan baris ini untuk memperbaiki format Private Key yang rusak
if (isset($authConfig['private_key'])) {
    $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
}       $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = 'Sheet1!A2:B';

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (is_null($values)) {
            dd([
                "Pesan" => "Data mengembalikan NULL. Cek Izin Share dan Nama Sheet!",
                "ID_Sheet" => $spreadsheetId,
                "Range_Dicari" => $range,
                "Email_Service_Account" => $authConfig['client_email'] ?? 'Tidak terbaca'
            ]);
        }

        $namaTamu = 'Tamu Undangan';

        // PERBAIKAN: Pastikan $values adalah array dan tidak kosong
        if (is_array($values)) {
            foreach ($values as $row) {
                // Pastikan kolom A (ID) ada dan cocok dengan URL
                if (isset($row[0]) && $row[0] == $id) {
                    // Ambil nama dari kolom B, jika kosong beri default
                    $namaTamu = $row[1] ?? 'Tamu Undangan';
                    break;
                }
            }
        }

        return view('invitation', ['namaTamu' => $namaTamu]);

    } catch (\Exception $e) {
        // Log error jika diperlukan, tapi jangan biarkan user melihat error 500
        return view('invitation', ['namaTamu' => 'Tamu Undangan']);
    }

    return view('invitation', ['namaTamu' => $namaTamu]);
});
Route::get('/', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});
// Rute tanpa ID (Agar /invitation saja tidak 404)
Route::get('/invitation', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});