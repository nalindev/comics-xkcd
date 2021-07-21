<?php
require 'db/db.php';
require 'config.php';

$db = new comics_db($dbhost, $dbuser, $dbpass, $dbname);

//check if table exists if not then create
$db->query('
CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL auto_increment,
    `verified` int(11) NOT NULL default \'0\',
    `email`  varchar(100) NOT NULL default \'\',
    `shortner`  varchar(100) NOT NULL default \'\',
     PRIMARY KEY  (`id`)
  );
');

$actual_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";

//check email in db
function check_mail_db($email){
    Global $db;
    $accounts = $db->query('SELECT * FROM users')->fetchAll();
    foreach ($accounts as $account) {
        if($account['email'] == $email){
            return true;
        }
    }
    return false;
}

//send verification mail function
function sendmail($id, $to){
    global $from, $actual_url;
    $subject = 'Verify your mail to get XKCD comics updates';
    $message = '
        <h1 style="margin-top:0;margin-left:auto;margin-right:auto;margin-bottom:16px;font-size:18px;line-height:32px;font-weight:bold;letter-spacing: 0.02em;">XKCD Comics Verification</h1>
        <p style="margin:0;">
        Hello '.$to.' ‹<br>
        You registered an Email address on XKCD Comics, before being able to receive updates on every 5 mins you need to verify that this is your email address by clicking here: [ <a href="'.$actual_url.'/verify.php?uid='.$id.'" style="color:#e50d70;text-decoration:underline;">Verify Now</a> ]<br><br>
        Kind Regards, Nalin
    ';
    $headers = "From:" . $from;
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . '\r\n';
    $headers .= 'From: comics Verification <'.$from.'>' . '\r\n';

    mail($to,$subject,$message,$headers);
}
