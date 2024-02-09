<?php

 $ch = chr(34);

 error_reporting(0);

 include "config.jsx";

//-----------------domain access----begin-------------//
$mpost   = 0;
$srv_url = $_SERVER["HTTP_REFERER"];
$pos     = strpos($srv_url,"$domain_name");  

if (($pos == 8) || ($pos == 12)) { $mpost = 1; }

//echo "<br><br>* $srv_url - $pos *";

if ($mpost == 1)
{
// echo "Allow REQUEST!";
}
 else
{
// echo "Bad REQUEST!";
 exit;
}
//-----------------domain access----end-------------//

 $str                = $_POST['str']; 
 $keyinput           = $_COOKIE["msx9krmew90xgsndc"];
 $encrypt_method     = "AES-256-CBC";
 $secret_key         = 'STGVKMS8394Z3_key';
 $secret_iv          = 'PRQ19UPLSX749_ivy';
 $keyo               = hash('sha256',$secret_key);
 $u_mail             = openssl_decrypt(base64_decode($keyinput),$encrypt_method,$keyo,0,$secret_iv);

 $fname_number  = "database/invite_number/$u_mail.data";
 $response      = 1;

 if (file_exists($fname_number)) 
 {
  include "$fname_number";
 } 
  else
 {
  $sender_number = 1;
 }

 if ($sender_number > 9) { $response = 0; }

 $fname_user = "database/iptvuser/$u_mail.data";
 if ((file_exists($fname_user)) & (!(trim($u_mail) == '')))
 {
  include "$fname_user";
  if ($user_id == '') { $response = 0; }
 } 
  else
 {
  $response = 0;
 }

 echo $response;

?>
