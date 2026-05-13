<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id?}', function ($id = null) {
    $namaTamu = 'Tamu Undangan';

    if (!$id) {
        return view('invitation', ['namaTamu' => $namaTamu]);
    }

    try {
        $authJson = env('GOOGLE_SERVICE_ACCOUNT_JSON');
        if (!$authJson) throw new \Exception("Environment GOOGLE_SERVICE_ACCOUNT_JSON kosong di Railway!");

        $authConfig = json_decode($authJson, true);
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client = new \Google\Client();
        $client->setAuthConfig($authConfig);
        $client->addScope(\Google\Service\Sheets::SPREADSHEETS_READONLY);
        
        $service = new \Google\Service\Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = 'Sheet1!A2:B'; 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        // --- MULAI DEBUG ---
        if (empty($values)) {
            throw new \Exception("Data Google Sheets kosong. Cek nama Sheet atau Range!");
        }

        foreach ($values as $row) {
            // Berdasarkan screenshot Anda:
            // $row[0] adalah NAMA TAMU (misal: 'aisyah')
            // $row[1] adalah LINK
            
            if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
                // PERBAIKAN: Kita ambil $row[0] (Nama), bukan $row[1] (Link)
                $namaTamu = $row[0]; 
                break;
            }
        }
        // --- SELESAI DEBUG ---

    } catch (\Exception $e) {
        // Tampilkan error asli agar kita tahu kenapa gagal (Permission/JSON/Sheet Name)
        return "ERROR GOOGLE API: " . $e->getMessage();
    }

    return view('invitation', ['namaTamu' => $namaTamu]);
});
Route::get('/', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});
Route::get('/debug-env', function() {
    $raw = env('GOOGLE_SERVICE_ACCOUNT_JSON');
    return [
        'is_empty' => empty($raw),
        'length' => strlen($raw),
        'starts_with' => substr($raw, 0, 1),
        'ends_with' => substr($raw, -1),
    ];
});