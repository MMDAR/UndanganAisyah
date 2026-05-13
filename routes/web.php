<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id}', function ($id) {
    try {
        $client = new Client();
        $authConfig = json_decode(env('GOOGLE_SERVICE_ACCOUNT_JSON'), true);

        if (!$authConfig) {
            throw new \Exception("JSON Service Account tidak terbaca. Cek Environment Variable!");
        }

        // Perbaikan format Private Key untuk Railway/Production
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = 'Sheet1!A2:B';

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $namaTamu = 'Tamu Undangan'; // Default

        if (!empty($values)) {
            foreach ($values as $row) {
                // row[0] adalah ID, row[1] adalah Nama
                if (isset($row[0]) && trim($row[0]) == trim($id)) {
                    $namaTamu = $row[1] ?? 'Tamu Undangan';
                    break;
                }
            }
        }

        return view('invitation', ['namaTamu' => $namaTamu]);

    } catch (\Exception $e) {
        // Jika error, kirim pesan error ke log Railway agar bisa Anda cek
        \Log::error("Google Sheets Error: " . $e->getMessage());
        return view('invitation', ['namaTamu' => 'Tamu Undangan']);
    }
});

Route::get('/', function () {
    return view('invitation');
});