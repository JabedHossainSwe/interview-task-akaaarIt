<?php

namespace App\Http\Controllers;

use App\Models\CsvData;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CsvUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:10240',
        ]);

        $csvData = [];

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');

            $handle = fopen($file->getRealPath(), "r");

            $header = fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                try {
                    CsvData::create([
                        'branch_id' => $data[0],
                        'first_name' => $data[1],
                        'last_name' => $data[2],
                        'email' => $data[3],
                        'phone' => $data[4],
                        'gender' => $data[5],
                    ]);
                } catch (QueryException $e) {
                    if ($e->errorInfo[1] == 1062) {
                        Log::error('Duplicate entry found for email: ' . $data[3]);
                    } else {
                        Log::error('Database error: ' . $e->getMessage());
                    }
                }
            }

            fclose($handle);

            $csvData = CsvData::all();

            return view('welcome', ['csvData' => $csvData]);
        }

        return redirect()->back()->with('error', 'Please upload a valid CSV file.');
    }

    public function getData(Request $request)
    {
        $columns = ['id', 'branch_id', 'first_name', 'last_name', 'email', 'phone', 'gender'];

        $query = DB::table('csv_data');

        if ($request->has('search') && !empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $query->where(function ($q) use ($columns, $searchValue) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', '%' . $searchValue . '%');
                }
            });
        }

        // Get the total count of records without pagination
        $totalRecords = $query->count();

        // Apply pagination
        $perPage = $request->input('length', 10); // Number of records per page
        $page = $request->input('start', 0) / $perPage + 1; // Current page
        $records = $query->paginate($perPage, ['*'], 'page', $page);

        // Transform records for DataTables
        $data = [];
        foreach ($records as $record) {
            $data[] = [
                $record->id,
                $record->branch_id,
                $record->first_name,
                $record->last_name,
                $record->email,
                $record->phone,
                $record->gender
            ];
        }

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $records->total(),
            'data' => $data,
        ]);
    }
}
