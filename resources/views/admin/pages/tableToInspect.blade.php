<table class="table table-striped">
    <thead class="thead-dark">
        <tr style="text-align: center;">
            <th scope="col">ID</th>
            <th scope="col">Account Number</th>
            <th scope="col">Member's Name</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($client_to_inspect as $client_to_inspect_data)
            <tr style="text-align: center;">
                <td>{{ $client_to_inspect_data->id }}</td>
                <td>{{ $client_to_inspect_data->account_number }}</td>
                <td name="fullname">{{ $client_to_inspect_data->client_firstname }} {{ $client_to_inspect_data->client_lastname }}</td>
                <td><a href="{{ url('admin/specClient/'.$client_to_inspect_data->id) }}">Inspect</a></td>
            </tr>
        @endforeach
    </tbody>
</table>