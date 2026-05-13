<?php

use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Sheets;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * Route Utama: Menampilkan undangan dengan nama default
 */
Route::get('/', function () {
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});

/**
 * Route Undangan Spesifik: Mengambil nama tamu dari Google Sheets berdasarkan ID
 */
Route::get('/invitation/{id?}', function ($id = null) {
    // 1. Jika ID tidak ada, tampilkan nama default
    if (!$id) {
        return view('invitation', ['namaTamu' => 'Tamu Undangan']);
    }

    try {
        // 2. Ambil Kredensial dari Config/Env
        $authJson = config('services.google.service_account') ?? env('GOOGLE_SERVICE_ACCOUNT_JSON');

        if (!$authJson) {
            return "ERROR: Variabel GOOGLE_SERVICE_ACCOUNT_JSON kosong. Periksa pengaturan Railway.";
        }

        // 3. Inisialisasi Google Client
        $authConfig = json_decode($authJson, true);
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client = new Client();
        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        $range = 'Sheet1!A2:B'; 

        // 4. Ambil Data dari Google Sheets
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        // 5. Cari Tamu Berdasarkan ID
        $namaTamu = null;

        if (!empty($values)) {
            foreach ($values as $row) {
                // Kolom A ($row[0]) = ID/Nama, Kolom B ($row[1]) = Link
                if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
    // Kita tetap mencocokkan ID di Kolom A ($row[0]) dengan ID di URL
    // Tapi kita mengambil NAMA LENGKAP dari Kolom B ($row[1])
    
    $namaTamu = $row[1] ?? ucwords(trim($row[0])); 
    
    // Penjelasan: 
    // $row[1] mengambil data di kolom B.
    // ?? ucwords(trim($row[0])) adalah cadangan jika kolom B kosong, 
    // maka ia akan menggunakan ID di kolom A sebagai nama.
    
    break;
}
            }
        }

        // 6. Logika Pembatasan Akses
        // Jika ID ada di URL tapi tidak ditemukan di daftar spreadsheet
        if (!$namaTamu) {
            return response("Mohon maaf, nama Anda tidak terdaftar dalam daftar tamu kami. Silakan hubungi mempelai.", 403);
        }

        // 7. Tampilkan View dengan Nama Tamu yang ditemukan
        return view('invitation', ['namaTamu' => $namaTamu]);

    } catch (\Exception $e) {
        // Catat error ke log server dan tampilkan pesan sederhana ke user
        \Log::error("Google Sheets Error: " . $e->getMessage());
        return "ERROR GOOGLE API: Silakan hubungi admin atau coba beberapa saat lagi.";
    }
});

// Route debug-env dihapus untuk keamanan setelah aplikasi berjalan lancar.