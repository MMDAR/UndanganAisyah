<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id?}', function ($id = null) {
    // 1. Tentukan default di awal agar variabel SELALU ada untuk dikirim ke view
    $namaTamu = 'Tamu Undangan';

    // Jika tidak ada ID di URL, langsung tampilkan view
    if (!$id) {
        return view('invitation', ['namaTamu' => $namaTamu]);
    }

    try {
        $client = new Client();
        $authJson = env('GOOGLE_SERVICE_ACCOUNT_JSON');
        
        if (!$authJson) {
            throw new \Exception("Environment GOOGLE_SERVICE_ACCOUNT_JSON tidak ditemukan.");
        }

        $authConfig = json_decode($authJson, true);
        
        // Memastikan private key terbaca dengan benar (khusus untuk Railway)
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID'); 
        
        // CATATAN: Pastikan nama tab di Google Sheets Anda benar-benar "Sheet1"
        // Jika nama tabnya berbeda (misal: "Daftar"), ganti teks di bawah ini.
        $range = 'Sheet1!A2:B'; 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        dd($values);

        if (!empty($values)) {
            foreach ($values as $row) {
                // Bandingkan ID di kolom A (index 0) dengan ID dari URL
                // Gunakan trim dan strtolower agar perbandingan lebih akurat
                if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
                    // Ambil nama dari kolom B (index 1), jika kosong pakai ID-nya
                    $namaTamu = $row[1] ?? $row[0];
                    break;
                }
            }
        }

    } catch (\Exception $e) {
        // Catat error ke log agar bisa dicek di dashboard Railway (Tab Logs)
        \Log::error("Google Sheets Error: " . $e->getMessage());
    }

    return view('invitation', ['namaTamu' => $namaTamu]);
});

Route::get('/', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});