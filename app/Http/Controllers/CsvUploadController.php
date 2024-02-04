<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class CsvUploadController extends Controller
{

    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10240',
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            // Open the CSV file for reading
            $handle = fopen($file->getRealPath(), "r");

            // Skip the first row (header)
            $header = fgetcsv($handle);

            // Iterate over the remaining rows
            while (($data = fgetcsv($handle)) !== false) {
                try {
                    // Create a new CsvData record
                    CsvData::create([
                        'branch_id' => $data[0],
                        'first_name' => $data[1],
                        'last_name' => $data[2],
                        'email' => $data[3],
                        'phone' => $data[4],
                        'gender' => $data[5],
                    ]);
                } catch (QueryException $e) {
                    // Handle duplicate entry error
                    if ($e->errorInfo[1] == 1062) { // MySQL error code for duplicate entry
                        // Log or display an error message
                        Log::error('Duplicate entry found for email: ' . $data[3]);
                    } else {
                        // Log or display a general database error message
                        Log::error('Database error: ' . $e->getMessage());
                    }
                }
            }

            fclose($handle);

            return redirect()->back()->with('success', 'CSV data has been uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Please upload a valid CSV file.');
    }
}
