   <title>Oops!</title>
   <meta charset="utf8">
   <link rel="stylesheet" type="text/css" href="<?php echo \Bitphp\Core\Globals::get('base_url') ?>/public/css/bootstrap.css">
   <style type="text/css">
      .jumbotron {
         color: #fff;
         background-color: #9AA3FF;
      }

      .foo {
         color: #9AA3FF;   
      }

      .bit-err {
         position: absolute;
         width: 100%;
         height: 100%;
         top: 0;
         left: 0;
         background-color: #fff;
      }
   </style>
<div class="bit-err">
   <div class="jumbotron">
      <div class="container">
         <div class="row">
            <h1>Error!</h1>
         </div>
      </div>
   </div>
   <div class="container">
      <div class="row">
         <h4>
            <span class="foo">#</span>
            Hubo <?php echo count($errors) ?> errores en la aplicación.
         </h4>
         <?php foreach($errors as $error): ?>
            <h4>
               <span class="foo">#</span>
               <?php echo $error['message']  ?>.
            </h4>
            <pre>Lanzado en el archivo <?php echo $error['file'] ?> linea <?php echo $error['line'] ?></pre>
            <?php if($error['identifier']): ?>
               <pre>~$ php dummy error --id <?php echo $error['identifier'] ?><br></pre>
            <?php else: ?>
               <pre>El error no fue registrado en el historial de errores, revisa la configuración o qué la carpeta '/olimpus/log' tenga permisos de escritura</pre>
            <?php endif; ?>
         <?php endforeach; ?>
      </div>
   </div>
</div>