:extends master

:block foo
	:parent
	<h2>I'm from child >:D</h2>
:endblock

/* Sobre escribe el bloque padre */
:block bar
	<h3>I'm child hola {{ $name }}</h3>
:endblock