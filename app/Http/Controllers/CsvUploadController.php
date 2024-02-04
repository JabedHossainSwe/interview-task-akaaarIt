<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use Illuminate\Http\Request;

class CsvUploadController extends Controller
{
    public function upload(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10240', // Adjust max file size as needed
        ]);
        
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            $csvData = array_map('str_getcsv', file($file->getRealPath()));
            foreach ($csvData as $row) {
                CsvData::create([
                    'branch_id' => $row[0],
                    'first_name' => $row[1],
                    'last_name' => $row[2],
                    'email' => $row[3],
                    'phone' => $row[4],
                    'gender' => $row[5],
                ]);
            }

            return redirect()->back()->with('success', 'CSV data has been uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Please upload a valid CSV file.');
    }
}
