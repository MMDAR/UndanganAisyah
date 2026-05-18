<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Google\Client;
use Google\Service\Sheets;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

/**
 * 1. HALAMAN UTAMA
 */
Route::get('/', function () {
    return view('unregistered', ['namaTamu' => 'Tamu Undangan']);
});

/**
 * 2. HALAMAN UNDANGAN (DENGAN ID)
 */
Route::get('/invitation/{id?}', function ($id = null) {
    if (!$id) {
        return view('invitation', ['namaTamu' => 'Tamu Undangan', 'sesiTamu' => 'Sesi 1']);
    }

    try {
        $authJson = config('services.google.service_account') ?? env('GOOGLE_SERVICE_ACCOUNT_JSON');
        $authConfig = json_decode($authJson, true);
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client = new Client();
        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS_READONLY);
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');
        
        // Diubah menjadi A2:D agar Kolom D (Sesi Acara) ikut terbaca
        $range = 'Sheet1!A2:D'; 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $namaTamu = null;
        $sesiTamu = 'Sesi 1'; // Default jika kolom kosong

        if (!empty($values)) {
            foreach ($values as $row) {
                // Kolom A = ID, Kolom B = Nama Lengkap, Kolom D = Sesi Acara
                if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
                    $namaTamu = $row[1] ?? ucwords(trim($row[0])); 
                    // Mengambil nilai Sesi Acara dari Kolom D ($row[3])
                    $sesiTamu = $row[3] ?? 'Sesi 1';
                    break;
                }
            }
        }

        if (!$namaTamu) {
            return response()->view('unregistered', [], 403);
        }

        // Mengirim data namaTamu dan sesiTamu ke view
        return view('invitation', ['namaTamu' => $namaTamu, 'sesiTamu' => $sesiTamu]);

    } catch (\Exception $e) {
        \Log::error("Google Sheets Read Error: " . $e->getMessage());
        return "ERROR: " . $e->getMessage();
    }
});

/**
 * 3. ROUTE UNTUK UPDATE KEHADIRAN (RSVP)
 */
Route::post('/update-attendance', function (Request $request) {
    $idTamu = $request->input('id');
    $status = $request->input('status');

    try {
        $authJson = config('services.google.service_account') ?? env('GOOGLE_SERVICE_ACCOUNT_JSON');
        $authConfig = json_decode($authJson, true);
        
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client = new Client();
        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS); 
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');

        $rangeID = 'Sheet1!A:A';
        $responseID = $service->spreadsheets_values->get($spreadsheetId, $rangeID);
        $valuesID = $responseID->getValues();

        $rowIndex = -1;
        if (!empty($valuesID)) {
            foreach ($valuesID as $index => $row) {
                if (isset($row[0]) && trim($row[0]) == $idTamu) {
                    $rowIndex = $index + 1;
                    break;
                }
            }
        }

        if ($rowIndex == -1) {
            return response()->json(['success' => false, 'message' => 'ID tidak ditemukan di database.'], 404);
        }

        $updateRange = "Sheet1!H{$rowIndex}";
        $body = new Sheets\ValueRange([
            'values' => [[$status]]
        ]);
        $params = ['valueInputOption' => 'RAW'];

        $service->spreadsheets_values->update($spreadsheetId, $updateRange, $body, $params);

        return response()->json(['success' => true, 'status' => $status]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
});