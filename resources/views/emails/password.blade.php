@component('emails.layout')
<br />
Welcome {{ $user->first_name.' '.$user->last_name}},<br /><br />

Thank you for using {{ env('APP_NAME') }} App.<br /><br />

Here is your account username & new password.<br /><br />

Your Username: {{ $user->username}}<br />
Your Password: {{ $user->plain_password}}<br />
<br />

After receiving the password you can login and manage your account by using Looksyummy App.


Yours sincerely<br /><br />

Administrator, {{ env('APP_NAME') }}.
@endcomponent
