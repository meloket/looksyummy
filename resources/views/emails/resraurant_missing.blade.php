@component('emails.layout')
<br />
Hello Admin,<br /><br />

Following location was searched by a user using the {{ env('APP_NAME') }} App.<br /><br />

Here are the details of the location.<br /><br />

<table width="75%">
    <tr><td width="30%">Name</td><td>{{ $restaurant['name'] }}</td></tr>
    <tr><td>Address</td><td>{{ $restaurant['address'] }}</td></tr>
    <tr><td>City</td><td>{{ $restaurant['city'] }}</td></tr>
    <tr><td>State</td><td>{{ $restaurant['state'] }}</td></tr>
    <tr><td>Country</td><td>{{ $restaurant['country'] }}</td></tr>
    <tr><td>Postal Code</td><td>{{ $restaurant['postalCode'] }}</td></tr>
    <tr><td>Phone</td><td>{{ $restaurant['phone'] }}</td></tr>
</table>

<br />


Yours sincerely<br /><br />

Administrator, {{ env('APP_NAME') }}.
@endcomponent
