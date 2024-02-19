<p>Hello {{ $user->name }},</p>

<p>Your manager {{$vendor->name}} has created an account for you as {{$role->name}}. Here are your access details:</p>

<p>Username: {{ $user['email'] }}</p>
<p>Password: {{ $password }}</p>
<p>You can log in using the following link: <a href="{{ $url }}">Login</a> </p>

<p>Thank you</p>


