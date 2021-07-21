<?php
require 'core/core.php';

$data = get_comics();
temp_img($data['img']);

$accounts = $db->query('SELECT * FROM users')->fetchAll();

foreach ($accounts as $account) {
    if($account['verified']){
        send_mail($account['email'], $data, $account['shortner']);
    }
}

function get_comics()
{
    $id = get_id();
    $url = 'https://xkcd.com/$id/info.0.json';
    $json_data = json_decode(file_get_contents($url), true);
    return $json_data;
}

function get_id()
{
    $temp = explode('/', get_url());
    return $temp[3];
}

function get_url()
{
    $url='https://c.xkcd.com/random/comic/';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $a = curl_exec($ch);
    $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

    return $url;
}

function temp_img($url)
{
    $img = 'assets/img/temp.jpg';
    file_put_contents($img, file_get_contents($url));
}


function send_mail($to, $datas, $id)
{
    global $from, $actual_url;
    $fromName = 'Comics - '.$datas['safe_title'];

    $subject = 'Comics - '.$datas['safe_title'];

    $file = "assets/img/temp.jpg";

    $htmlContent = '
    <h3>'.$datas["alt"].'</h3>
    <img src="'.$datas["img"].'" width="20%" />
    <br>
    <p>year - '.$datas["year"].'</p>
    <p>Comic ID - '.$datas["num"].' </p><br><br>
    <p>Unsubscribe XKCD comic by clicking here: [ <a href="'.$actual_url.'/verify.php?uid='.$id.'&op=unsubscribe"> unsubscribe Now </a> ]</p>
    ';

     $headers = "From: $fromName"." <".$from.">";

     $semi_rand = md5(time());
     $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

     $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

     $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
     "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";

if(!empty($file) > 0){
    if(is_file($file)){
        $message .= "--{$mime_boundary}\n";
        $fp =    @fopen($file,"rb");
        $data =  @fread($fp,filesize($file));

        @fclose($fp);
        $data = chunk_split(base64_encode($data));
        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .
        "Content-Description: ".basename($file)."\n" .
        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
    }
}
$message .= "--{$mime_boundary}--";
$returnpath = "-f" . $from;

$mail = @mail($to, $subject, $message, $headers, $returnpath);

echo $mail?"<p>Email Sent - $to</p>":'<p>Email sending failed.</p>';
}
