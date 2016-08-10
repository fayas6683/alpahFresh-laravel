<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset</h2>
<!--        http://noblecrowd.com/#/forgotPassword/-->
		<div>
			To reset your password, complete this form: {{ $domain_name.'#/forgotPassword/'.$code }}.<br/>
			This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.
		</div>
	</body>
</html>
