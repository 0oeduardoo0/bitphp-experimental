<!DOCTYPE html>
<html>
<head>
   <title>Yeah!</title>
   <meta charset="utf8">
   :css bootstrap
   <style type="text/css">
      .jumbotron {
         color: #fff;
           background-color: #9AA3FF;
      }

      .foo {
         color: #9AA3FF;   
      }

      .sticker {
         max-width: 150px;
      }
   </style>
</head>
<body>
   <div class="jumbotron">
      <div class="container">
         <div class="row">
            <div class="col-sm-2" align="center">
               <img src="{{ :base }}/public/img/unicorn.png" class="sticker">
            </div>
            <div class="col-sm-10" align="left">
               <h1>Fuck Yeah!</h1>
            </div>
         </div>
      </div>
   </div>
   <div class="container">
      <div class="row">
         <h3>
            <span class="foo">#</span> &nbsp;
            Bitphp is running in this magical land, with unicorns and rainbows!
         </h3>
         <h3>
            <span class="foo">#</span> &nbsp;
            Danger! This version is experimental, somethings can to exploit D:
         </h3>
      </div>
   </div>
</body>
</html>