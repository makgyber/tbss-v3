<hr style="margin-bottom:14px;" />
<div>
    <table>
        <caption style="margin:8px;font-size:normal;text-align:left"><b>{{$jo->client?->name}} - {{$jo->job_order_type}}</b>
            <span style="float:right"><em>{{$jo->code}}</em></span>
        </caption>
        <tr>
            <td><em>Time: </em><br><b> {{date_format($jo->target_date, 'h:i a')}}</b></td>
            <td><em>Location: </em><br><b>{{$jo->address->fullAddress}}</b></td>
            <td><em>Contacts: </em><br><b>@php
                    if(!is_null($jo->client))
                    foreach($jo->client?->contact_information as $contacts) echo "<div>" . $contacts['type'] . ": " . $contacts['value']. "</div>"
                    @endphp</b></td>
        </tr>
    </table>
</div>

<table style=" margin:4px;font-size:smaller">
    <thead>
        <tr>
            <th>Summary</th>
            <th>Instructions</th>
            <th>Comments</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$jo->summary}}</td>
            <td>
                @foreach($jo->instructions as $instruction)
                <li>{{$instruction->instruction}}</li>
                @endforeach
            </td>
            <td>
                @foreach($jo->comments as $comment)
                <li>{{$comment->body}}</li>
                @endforeach
            </td>
        </tr>
    </tbody>
</table>