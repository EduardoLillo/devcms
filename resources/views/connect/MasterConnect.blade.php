<!DOCTYPE html>
<html>
<head>

	<title>MY CMS-@yield('title')</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<link rel="stylesheet" href="{{url('/static/css/connect.css?v='.time())}}">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/7f4260e83c.js" crossorigin="anonymous"></script>

</head>

<body>
	
	

	@section('content')
	hola mundo
	@show

</body>
</html>
