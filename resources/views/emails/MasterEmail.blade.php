<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>MY CMS-@yield('title')</title>
	<link rel="stylesheet" href="">
</head>
<body style="margin: 0px; padding: 0px; background-color: #f3f3f3">
	<div style="
	display: block;
	max-width: 728px;
	margin: 0px auto;
	width: 60%;	
	">
		<img src="{{url('/static/images/emails.png')}}" style="width: 100%; display: block;">
		
		<div style="
		background-color: #fff;
		padding: 24px;
		">
		 @yield('content')

	</div>
</body>
</html>