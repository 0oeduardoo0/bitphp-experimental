:extends __pages/Main

:block title
   Opps! | Algo salio mal
:endblock

:block main
   :var nerrors count($errors)

   <h2>Ocurrieron {{ $nerrors }} errores!</h2>

   /* for check if error log have permissions */
   :if !$errors[0]['identifier']
      <h4>No se pudieron registrar los errores, verifica qué el servidor tenga permisos de escritura en la carpeta <b>{{ $_ROUTE['base_path'] }}/olimpus</b></h4>
   :endif
   <hr>
   :foreach $errors as $error
      <h4>
         <span class="red"># </span>
         {{ $error.message }} - <i>{{ $error.file }} linea {{ $error.line }}</i>
      </h4>
      :if $error.identifier
         <pre> $ php dummy error --id {{ $error.identifier }}</pre>
      :endif
   :endforeach
:endblock