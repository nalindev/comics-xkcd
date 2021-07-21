<?php
require 'core/core.php';

$model = false;
$email_err = '';
$err = '';
$email = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(empty(trim($_POST['email']))){
        $email_err = 'Please enter email address.';
    } else{
        $email = trim($_POST['email']);
    }
}

if(!empty($email) && check_mail_db($email)){
    $err = 'Email already exists';
}

if(!empty($email) && !check_mail_db($email)){
  $uid = uniqid('user_');
  $db->insert($email, $uid);
  $model = true;
  sendmail($uid, $email);
}
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="icon" href="assets/img/icon.jpg">
      <link rel="stylesheet" href="assets/css/main.css">
      <title>XKCD comics updates</title>
   </head>
   <body>
      <div class="container">
      <div class="right-section">
         <div class="right-section-wrapper">
            <h1 class="title">
               Get XKCD Comics Every Five Minutes
            </h1>
            <h3 class="description">
               We will send you a random comics details on your email on every 5 minutes to keep you updated with latest comics news and updates.
            </h3>
         </div>
      </div>
      <div class="left-section">
      <div class="left-section-wrapper">
      <div class="disclaimer"> <span class="disclaimer-highlight">
         Add your email addres below & get
         </span> <span class="disclaimer-text">
         XKCD comics updates.
         </span>
      </div>
        <?php
        if(!empty($err)){
          echo '<div class="disclaimer">'.$err.'</div><br>';
         }
        if(!$model){
          echo '<div class="form"> <img class="comic" src="assets/img/comic.png" /><br>
          <form autocomplete="off" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
          <input type="email" class="email" name="email" placeholder="Email Address" />
          <button class="form-btn"> <span class="form-btn-text">SUBMIT YOUR DETAILS</span> </button>
          <p class="terms"> By clicking the above button, you will get access to our <span class="terms-highlight">
          comics notifier
          </span></p></form></div>';
        }else{
          echo '<div class="form">
          <center><img width="40%" src="assets/img/verify.gif" /><br>
          <h4>We have sent a mail to your address. Please verify it now<br> to get access to our updates every 5 minutes.</h4><br>
          <small>Note - <br>
          1. do not forget to check mail in spam or important folder.<br>
          2. gmail keeps forwarding our mail to important folder of gmail <br>
          </small><br>
          <p class="terms"> To get access to our <span class="terms-highlight"> comics notifier </span> you need to verify your email address.</p>
          </center></div>';
        } ?>
        </div>
      </div>
    </div>
  </body>
</html>
