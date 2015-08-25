:extends Main

:block title
   ({{ $delay }}) Redirection..
:endblock

:block main

   <h4>Redireccionado en <b id="counter"></b> segundos.</h4>

   <script type="text/javascript">

   var url = "{{ $url }}";
   var limit   = {{ $delay }};
   var counter = document.getElementById("counter");

   counter.innerHTML = limit;

   setInterval(function(){
      limit--;

      if(limit <= 0) {
         window.location = url;
      }

      counter.innerHTML = limit;
      document.title = '(' + limit + ') Redirection...';
   }, 1000)

   </script>
:endblock