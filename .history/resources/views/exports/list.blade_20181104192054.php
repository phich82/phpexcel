<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Age</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td style="border:1px solid red;">{{ $row['name'] }}</td>
            <td>{{ $row['age'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
