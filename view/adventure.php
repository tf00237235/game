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
  </head>
  <body>
  <div id="dialog_about_me" style="display:none;overflow:auto;" ></div>
  <input type="hidden" id="role_id" value="<?=$role_id?>">
  <input type="hidden" id="maze_id" value="<?=$maze_id?>">
  <input type="hidden" id="instruction_num" value="0">
  <input type="hidden" id="monster" value="0">
  <form id="form">
    <input type="hidden" name="type" id="type" value="">
    <input type="hidden" name="role_id" value="<?=$role_id?>">
    <input type="hidden" name="difficulty" value="">
  </form>
  <div id="dialog" style="display:none"></div>
    <nav class='nav'>
      <div> Dice </div>
      <div> 冒險 </div>
    </nav>
      <div class='main'>
      <?php include_once './view/header.html'?>
        <?php include_once './view/footer_adventure.html'?>
      </div>
      <script src='js/main.js'></script>
      <script src='<?php echo $js; ?>'></script>
      <script src='js/about_me.js'></script>
      <script src='js/show_talent.js'></script>
      <script src='js/show_status.js'></script>
  </body>
</html>
