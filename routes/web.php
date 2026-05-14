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
    return view('invitation', ['namaTamu' => 'Tamu Undangan']);
});

/**
 * 2. HALAMAN UNDANGAN (DENGAN ID)
 */
Route::get('/invitation/{id?}', function ($id = null) {
    if (!$id) {
        return view('invitation', ['namaTamu' => 'Tamu Undangan']);
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
        $range = 'Sheet1!A2:B'; 

        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $namaTamu = null;
        if (!empty($values)) {
            foreach ($values as $row) {
                // Kolom A = ID, Kolom B = Nama Lengkap
                if (isset($row[0]) && strtolower(trim($row[0])) === strtolower(trim($id))) {
                    $namaTamu = $row[1] ?? ucwords(trim($row[0])); 
                    break;
                }
            }
        }

        if (!$namaTamu) {
            return response("Mohon maaf, nama Anda tidak terdaftar.", 403);
        }

        return view('invitation', ['namaTamu' => $namaTamu]);

    } catch (\Exception $e) {
        \Log::error("Google Sheets Read Error: " . $e->getMessage());
        return "ERROR: " . $e->getMessage();
    }
});

/**
 * 3. ROUTE UNTUK UPDATE KEHADIRAN (RSVP)
 * Jalur inilah yang dicari oleh Javascript (AJAX)
 */
Route::post('/update-attendance', function (Request $request) {
    $idTamu = $request->input('id');
    $status = $request->input('status'); // Akan menerima 'HADIR' atau 'TIDAK HADIR'

    try {
        $authJson = config('services.google.service_account') ?? env('GOOGLE_SERVICE_ACCOUNT_JSON');
        $authConfig = json_decode($authJson, true);
        
        if (isset($authConfig['private_key'])) {
            $authConfig['private_key'] = str_replace("\\n", "\n", $authConfig['private_key']);
        }

        $client = new Client();
        $client->setAuthConfig($authConfig);
        $client->addScope(Sheets::SPREADSHEETS); // Izin Full untuk Menulis
        
        $service = new Sheets($client);
        $spreadsheetId = env('GOOGLE_SHEET_ID');

        // Cari baris berdasarkan ID di kolom A
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

        // Update Kolom H (Kolom ke-8)
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