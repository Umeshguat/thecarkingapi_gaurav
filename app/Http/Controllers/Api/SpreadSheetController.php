<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Sheets;


class SpreadSheetController extends Controller
{
    //
    public function AddRequest(Request $request)
    {
        try {
            // Get all request data
            $data = $request->all();

            // Convert data to a single row array
            $values = [array_values($data)];

            $client = new Client();
            $client->setAuthConfig(storage_path('app/credentials.json'));
            $client->addScope(Sheets::SPREADSHEETS);

            $service = new Sheets($client);
            $spreadsheetId = '1hCa8R1YS5h7nyR-s4y7WDsimI8scLhAmckvLfGCinok';
            $range = 'Sheet1!A1';

            $body = new Sheets\ValueRange([
                'values' => $values
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            // Append the data to the sheet
            $result = $service->spreadsheets_values->append(
                $spreadsheetId,
                $range,
                $body,
                $params
            );

            return response([
                'status' => 200,
                'message' => 'Sheet updated successfully.',
                'updated_cells' => $result->getUpdates()->getUpdatedCells()
            ]);

        } catch (\Throwable $th) {
            return response([
                'status' => $th->getCode() ?: 500,
                'message' => $th->getMessage()
            ], $th->getCode() ?: 500);
        }
    }
}
