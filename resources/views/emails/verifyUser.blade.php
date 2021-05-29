<!DOCTYPE html>
<html>
	<head>
		<title>Welcome Email</title>
	</head>
	<body>
		<h2>Welcome to the site {{$user['user_name']}}</h2>
		<br/>
		Your registered email-id is {{$user['email']}} , Please click on the below link to verify your email account
		<br/>
		<a href="{{url('email/verify', $user['user_name'])}}">Verify Email</a>
	</body>
</html>
