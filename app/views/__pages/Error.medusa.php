:extends __pages/Main

:block title
   Opps! | Algo salio mal
:endblock

:block main
   :var nerrors count($errors)

   <h2>Ocurrieron {{ $nerrors }} errores!</h2>
   <hr>
   :foreach $errors as $error
      <h4>
         <span class="red"># </span>
         {{ $error.message }} - <i>{{ $error.file }} linea {{ $error.line }}</i>
      </h4>
      :if $error.identifier
         <pre> $ php dummy error -id {{ $error.identifier }}</pre>
      :endif
   :endforeach
:endblock