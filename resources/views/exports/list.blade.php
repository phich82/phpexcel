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
            <td>
                <select>
                    <option value="{{ $row['age'] }}"{{ $row['age'] == 29 ? ' selected' : '' }}>29</option>
                    <option value="{{ $row['age'] }}"{{ $row['age'] == 30 ? ' selected' : '' }}>30</option>
                    <option value="{{ $row['age'] }}"{{ $row['age'] == 31 ? ' selected' : '' }}>31</option>
                    <option value="{{ $row['age'] }}"{{ $row['age'] == 32 ? ' selected' : '' }}>32</option>
                    <option value="{{ $row['age'] }}"{{ $row['age'] == 33 ? ' selected' : '' }}>33</option>
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
