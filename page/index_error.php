<html>
  <head>
    <title> 一個人的冒險 </title>
    <meta charset='utf-8' />
    <link rel='stylesheet' href='css/main.css' />
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif+TC|Overpass+Mono&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/dialog.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src='js/about_me.js'></script>
  </head>
  <body>
  <div id="dialog_about_me" style="display:none"></div>
    <nav class='nav'>
      <div>  </div>
      <div> 你是怎麼操作的... </div>
    </nav>
      <div class='main'>
      <?php include_once './page/header.html'?>
      </div>
      <script src='js/main.js'></script>
      <script src='<?php echo $js; ?>'></script>
  </body>
</html>
