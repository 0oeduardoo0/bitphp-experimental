:extends Main

:block title
   Opps! | Algo salio mal
:endblock

:block main
   :var nerrors count($errors)

   <div class="jumbotron">
      <h1>Ocurrieron {{ $nerrors }} errores!</h1>
      /* for check if error log have permissions */
      :if !$errors[0]['identifier']
         <h4>No se pudieron registrar los errores, verifica qué el servidor tenga permisos de escritura en la carpeta <b>{{ $_ROUTE['base_path'] }}/olimpus</b></h4>
      :endif
      <br>
      :foreach $errors as $error
         <h4>
            <span class="red"># </span>
            {{ $error.message }} - <i>{{ $error.file }} linea {{ $error.line }}</i>
         </h4>
         :if $error.identifier
            <div class="alert alert-info"> $ php dummy error --id {{ $error.identifier }}</div>
         :endif
      :endforeach
      <br>
   </div>
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3>Entorno</h3>
      </div>
      <div class="panel-body">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th>Base path</th>
                  <th>Base URL</th>
                  <th>Request URI</th>
                  <th>App path</th>
                  <th>URI params</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <td>{{ $_ROUTE['base_path'] }}</td>
                  <td>{{ $_ROUTE['base_url'] }}</td>
                  <td>/{{ $_ROUTE['request_uri'] }}</td>
                  <td>{{ $_ROUTE['app_path'] }}</td>
                  <td>
                     :foreach $_ROUTE['uri_params'] as $param
                        {{ $param }}<br>
                     :endforeach
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3>Entrada</h3>
      </div>
      <div class="panel-body">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th>Método</th>
                  <th>Valor</th>
               </tr>
            </thead>
            <tbody>
               <tr>
                  <th>POST</th>
                  <td>
                     <pre>{{ filter_var(var_export($_POST, true), FILTER_SANITIZE_FULL_SPECIAL_CHARS) }}</pre>
                  </td>
               </tr>
               <tr>
                  <th>GET</th>
                  <td>
                     <pre>{{ filter_var(var_export($_GET, true), FILTER_SANITIZE_FULL_SPECIAL_CHARS) }}</pre>
                  </td>
               </tr>
               <tr>
                  <th>COOKIE</th>
                  <td>
                     <pre>{{ filter_var(var_export($_COOKIE, true), FILTER_SANITIZE_FULL_SPECIAL_CHARS) }}</pre>
                  </td>
               </tr>
               <tr>
                  <th>STANDARD</th>
                  <td>
                     <pre>{{ filter_var(var_export(file_get_contents('php://input'), true), FILTER_SANITIZE_FULL_SPECIAL_CHARS) }}</pre>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3>Cabeceras HTTP</h3>
      </div>
      <div class="panel-body">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th>Nombre</th>
                  <th>Valor</th>
               </tr>
            </thead>
            <tbody>
               :foreach getallheaders() as $name => $value
                  <tr>
                     <th>{{ filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS) }}</th>
                     <td>{{ filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS) }}</td>
                  </tr>
               :endforeach
            </tbody>
         </table>
      </div>
   </div>
   <div class="panel panel-default">
      <div class="panel-heading">
         <h3>Configuración</h3>
      </div>
      <div class="panel-body">
         <table class="table table-hover">
            <thead>
               <tr>
                  <th>Parametro</th>
                  <th>Valor</th>
               </tr>
            </thead>
            <tbody>
               :foreach \Bitphp\Core\Config::all() as $param => $value
                  <tr>
                     <th>{{ $param }}</th>
                     <td>
                        <pre><?php var_dump($value) ?></pre>
                     </td>
                  </tr>
               :endforeach
            </tbody>
         </table>
      </div>
   </div>
   <br><br>
:endblock