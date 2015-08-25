
<title>
   :block title
   :endblock
</title>
:css bootstrap
<style type="text/css">
   .jumbotron.blue {
      color: #fff;
      background-color: #556370;
   }

   .red {
      color: #9AA3FF;   
   }

   .bitphp-error-main {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      background-color: #fff;
   }

   .sticker {
      max-width: 100px;
   }
</style>
<div class="bitphp-error-main">
   <div class="jumbotron blue">
      <div class="container">
         <div class="row" align="center">
            <img src="{{ :base }}/public/img/unicorn.png" class="sticker">
         </div>
      </div>
   </div>
   <div class="container">
      <div class="row">
         :block main
         :endblock
      </div>
   </div>
</div>