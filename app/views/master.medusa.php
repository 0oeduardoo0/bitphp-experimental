<html>
<head>
	<title>Foo</title>
	:css bootstrap
</head>
<body>
	:require navbar :args
	<div class="container">
		<h1>I'm the father</h1>
		<hr>
		:block foo
			:for $i = 0; $i < 10; $i++
				{{ $i }}
			:endfor
			<h2>I'm from father {{ $name }}</h2>
		:endblock
		<hr>
		:block bar
			<h3>I'm father</h3>
		:endblock
	</div>
</body>
</html>