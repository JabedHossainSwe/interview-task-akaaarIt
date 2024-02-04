<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use Illuminate\Http\Request;

class CsvDataController extends Controller
{
    public function getData(Request $request)
    {
        // Fetch CSV data from the database
        $csvData = CsvData::all();

        // Return the CSV data as JSON
        return response()->json($csvData);
    }
}
