<div style="margin:20px 0;">
    <table style="border-collapse: collapse;" cellpadding="5" align="center">
        <tr>
            <th colspan="2" style="background:#ccc; border: 1px solid gray;"><strong>Contact Details</strong></th>
        </tr>

        <tr>
            <td style="background:#99B7D4; border: 1px solid gray;"><strong>Name</strong></td>
            <td style="border: 1px solid gray;">{{ $contact['name'] }}</td>
        </tr>

        <tr>
            <td style="background:#99B7D4; border: 1px solid gray;"><strong>Email</strong></td>
            <td style="border: 1px solid gray;">{{ $contact['email'] }}</td>
        </tr>

        <tr>
            <td style="background:#99B7D4; border: 1px solid gray;"><strong>Subject</strong></td>
            <td style="border: 1px solid gray;">{{ $contact['subject'] }}</td>
        </tr>

        <tr>
            <td style="background:#99B7D4; border: 1px solid gray;"><strong>Message</strong></td>
            <td style="border: 1px solid gray;">{!! nl2br(e($contact['message'])) !!}</td>
        </tr>
    </table>
</div>
