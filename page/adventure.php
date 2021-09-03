<html>
  <head>
    <title> 一個人的冒險 </title>
    <meta charset='utf-8' />
    <link rel='stylesheet' href='css/main.css' />
    <link href="https://fonts.googleapis.com/css?family=Noto+Serif+TC|Overpass+Mono&display=swap" rel="stylesheet">
  </head>
  <body>
    <nav class='nav'>
      <div> Dice </div>
      <div> 冒險 </div>
    </nav>
      <div class='main'>
      <?php include_once './page/header.html'?>
        <?php include_once './page/footer_adventure.html'?>
      </div>
      <script src='js/main.js'></script>
      <script src='<?php echo $js; ?>'></script>
  </body>
</html>
