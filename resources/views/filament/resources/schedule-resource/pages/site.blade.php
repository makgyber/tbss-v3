<table>
    <tr>
        <th>Client</th>
        <th>Location</th>
        <th>Contacts</th>
    </tr>
    <tr>
        <td>{{ $client->name }}</td>
        <td>{{ $site->label }}<br>{{ $address->fullAddress }}</td>
        <td>{{ $site->contact_person }}<br>{{ $site->contact_info }}</td>
    </tr>
</table>