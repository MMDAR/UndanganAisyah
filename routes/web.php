<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id?}', function ($id = null) {
    // 1. Tentukan default di awal agar variabel SELALU ada
    $namaTamu = 'Tamu Undangan';

    // Jika tidak ada ID di URL, langsung tampilkan view default
    if (!$id) {
        return view('invitation', ['namaTamu' => $namaTamu]);
    }

    try {
        $authJson = env('GOOGLE_SERVICE_ACCOUNT_JSON');
        if (!$authJson) throw new \Exception("Env JSON Kosong");

        $authConfig = json_decode($authJson, true);
        
        // Perbaikan format Private Key untuk Railway
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client = new Client();
        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = 'Sheet1!A2:B'; // Pastikan nama Sheet sesuai

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        if (!empty($values)) {
            foreach ($values as $row) {
                // Gunakan trim() untuk menghindari spasi tak terlihat di Spreadsheet
                // dan strtolower() agar tidak masalah jika huruf besar/kecil berbeda
                if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
                    $namaTamu = $row[1] ?? 'Tamu Undangan';
                    break;
                }
            }
        }
    } catch (\Exception $e) {
        // Log error ke Railway agar bisa dicek di tab 'Logs'
        \Log::error("Gagal ambil data Sheets: " . $e->getMessage());
    }

    // Pastikan variabel dikirim ke view
    return view('invitation', ['namaTamu' => $namaTamu]);
});

// Redirect root ke invitation default
Route::get('/', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});