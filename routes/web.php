<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Sheets;

Route::get('/invitation/{id?}', function ($id = null) {
    // 1. Ambil data dari env secara langsung (sebagai fallback jika config gagal)
    $authJson = config('services.google.service_account') ?? env('GOOGLE_SERVICE_ACCOUNT_JSON');

    if (!$authJson) {
        return "ERROR: Variabel GOOGLE_SERVICE_ACCOUNT_JSON masih kosong di Railway. Pastikan sudah di-Save dan Deploy.";
    }

    try {
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

        $namaTamu = null; // Set null dulu untuk pengecekan akses

        if (!empty($values)) {
            foreach ($values as $row) {
                // Cocokkan ID (Kolom A) dengan {id} di URL
                if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
    // UBAH INI: dari $row[1] (Link) menjadi $row[0] (Nama/ID)
    // Gunakan ucwords() agar "aisyah" otomatis menjadi "Aisyah" (huruf kapital di depan)
    $namaTamu = ucwords(trim($row[0])); 
    break;
            }
        }

        // --- LOGIKA PEMBATASAN AKSES ---
        if (!$namaTamu) {
            // Jika ID tidak ada di spreadsheet, tampilkan halaman "Maaf, akses ditolak"
            // Atau redirect ke halaman lain
            return response("Mohon maaf, nama Anda tidak terdaftar dalam daftar tamu kami. Silakan hubungi mempelai.", 403);
        }

        return view('invitation', ['namaTamu' => $namaTamu]);

    } catch (\Exception $e) {
        return "ERROR GOOGLE API: " . $e->getMessage();
    }
});Route::get('/', function () {
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