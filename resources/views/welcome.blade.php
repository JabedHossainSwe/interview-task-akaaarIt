<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task@Akaar IT</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">CSV Table</div>

                    <div class="card-body">
                        <table id="csvDataTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Branch ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Gender</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($csvData) && $csvData->isNotEmpty())
                                    @foreach ($csvData as $data)
                                        <tr>
                                            <td>{{ $data->branch_id }}</td>
                                            <td>{{ $data->first_name }}</td>
                                            <td>{{ $data->last_name }}</td>
                                            <td>{{ $data->email }}</td>
                                            <td>{{ $data->phone }}</td>
                                            <td>{{ $data->gender }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">No data available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#csvDataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('data.get') }}",
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "branch_id"
                    },
                    {
                        "data": "first_name"
                    },
                    {
                        "data": "last_name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "phone"
                    },
                    {
                        "data": "gender"
                    }
                ]
            });
        });
    </script>
</body>

</html>
