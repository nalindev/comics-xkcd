<?php
require 'core/core.php';

$id_err = '';
$msg = '';
$id = '';
$verified = true;
if(isset($_GET["uid"]) && !empty($_GET["uid"])){
    $id = $_GET["uid"];
    $account = $db->query('SELECT * FROM users WHERE shortner = ?', $id)->fetchArray();
    $db->query('UPDATE users SET verified="1" WHERE id = ?', $account['id']);
    $msg = 'your mail has been verified';
}else{
    $id_err = 'something went wrong';
}

if(isset($_GET['op']) && !empty($_GET['op']) && !empty($_GET['uid'])){
    $id = $_GET['uid'];
    if($_GET['op'] == 'unsubscribe'){
        $account = $db->query('SELECT * FROM users WHERE shortner = ?', $id)->fetchArray();
        $db->query('DELETE FROM users WHERE id=?', $account['id']);
        $verified = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="assets/css/main.css">
      <title>XKCD comics</title>
   </head>
   <body style="text-align: center; padding: 10% 0;">
    <?php
    if($verified){
      echo '<div class="cardu">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
      <img src = "assets/img/success.gif" width="100%">
      </div><br>
      <h1>Success</h1> <br>
      <p>Your Email has been verified. Now you<br> will receive our updates every 5 mins.</p><br><br>
      <a href="/"><p class="disclaimer">Go Home</p></a>
      </div>';
    }else{
      echo '<div class="cardu">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
      <img src = "assets/img/sad.gif" width="100%">
      </div><br>
      <h1>Unsubscribed</h1> <br>
      <p>You are unsubscribed from our comic updates. </p><br><br>
      <a href="/"><p class="disclaimer">Go Home</p></a></div>';
    }
    ?>
  </body>
</html>
