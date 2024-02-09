<?php

 $ch = chr(34);

 error_reporting(0);

 include "config.jsx";

 $IPADDR      = $_SERVER['REMOTE_ADDR'];
 $useripaddr  = $IPADDR;

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

 $promocode_limit_error = 0;

 $IPADDR_promocode_banfilename = "database/promocode_banlist/$IPADDR.data";

 if (file_exists($IPADDR_promocode_banfilename)) 
 {
  include "$IPADDR_promocode_banfilename";
  if ($promocode_number > $promocode_limiter)
  {   
   $promocode_limit_error = 1;
  }
 }

if ($promocode_limit_error == 0)
{
 $f1=(date ("Y")); 
 $f2=(date ("m")); 
 $f3=(date ("d")); 
 $o1=(date ("G")); 
 $o2=(date ("i")); 
 $o3=(date ("s")); 
 $o4=(date ("G"));
 $o5=(date ("i"));
 $o6=(date ("s"));

 $t_promotional_code = $_POST['t_promotional_code'];
 $fname              = "database/promocode_outbox/$t_promotional_code.data";
 $response           = 0; 
 $secret_key         = 'STGVKMS8394Z3_key';
 $secret_iv          = 'PRQ19UPLSX749_ivy';
 $encrypt_method     = "AES-256-CBC";
 $keyo               = hash('sha256',$secret_key);
 $iv                 = substr(hash('sha256',$secret_iv),0,16);
 $keyinput           = $t_promotional_code;
 $codelen            = strlen($keyinput);
 $IPADDR             = $_SERVER['REMOTE_ADDR'];

 $output_time        = openssl_decrypt(base64_decode($keyinput),$encrypt_method,$keyo,0,$secret_iv);
 $timestamp_output   = $output_time;
 $timenow            = time();  

 $client_location    = $_POST['client_location'];
 $urli               = $_SERVER[REQUEST_URI];
 $device_detect      = $_SERVER['HTTP_USER_AGENT'];
 $d_lang             = $_POST['d_lang'];

 include "geomodul.jsx";

 include "user_language.jsx"; 

//----------hash code check begin-------------------------------//
 if (($timestamp_output >= $timenow) & ($codelen == 32))
 {
  if (!(file_exists($fname)))
  {
   $response = 1;
  }

  $chrx = 0;

  if (substr($keyinput,29,3) == 'T09')
  {
   $chrx = 1;
  }

  if (substr($keyinput,29,3) == 'z09')
  {
   $chrx = 1;
  }

  if ($chrx == 0) { $response = 0; }
 }
//----------hash code check end----------------------------------//

//----------inbox code check begin-------------------------------//
 $fname_inbox = "database/promocode_inbox/$t_promotional_code";

  if (file_exists($fname_inbox))
  {
   $response = 1;
  }
//----------inbox code check end---------------------------------//

 if (trim($t_promotional_code) == '')
 {
  $response = 0;
 }
//----------inbox code check begin-------------------------------//
 $fname_promotional_code = "database/invite_code/$t_promotional_code.data";

  if (file_exists($fname_promotional_code))
  {
   $response = 1;
  }
//----------inbox code check end---------------------------------//

 echo $response;

//---------------------------promocode-error-limiter--begin---------------------------------//
if ($response == 0)
{
 if (file_exists($IPADDR_promocode_banfilename)) 
 {
  $promocode_number++;
 }
  else
 {
  $promocode_number = 1;
 }

$fp = fopen("$IPADDR_promocode_banfilename","w");

fwrite($fp,'<?php
');

fwrite($fp,'$promocode_number = ' .$ch. $promocode_number .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);
}
//---------------------------promocode-error-limiter--end-----------------------------------//

//---------------------------------------------------system--log-begin-------------------------------------------------//
 $log_filename = "database/log_system/$f1-$f2-$f3.log.html";
 $log_name     = "System";
 //green->#87af61, red->#b96d6d, yellow->#aba230 

 if ($response == 1)
 {
  $status_color = "#87af61";  
 }
  else
 {
  $status_color = "#b96d6d";
 }

 if (!(file_exists($log_filename)))
 {
  $begin_html = "<html xmlns='http://www.w3.org/1999/xhtml'><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8'><title>$log_name</title></head><body bgcolor='#c2c2c2'><h4><font color='black'>$f1-$f2-$f3 - <font color='black'>$log_name</font></font></h4><table width='100%'>";
  $write_pfx  = "w";
 }
  else
 {
  $begin_html = "";
  $write_pfx  = "a";  
 }

 $datagrid = "$begin_html<tr bgcolor='$status_color'><td>$f1.$f2.$f3. $o4:$o5:$o6</td><td>$IPADDR</td><td>$s_location</td><td>$d_lang</td><td>xxxxxxxx</td><td>xxxxxxxx</td><td>xxxxxxxx</td><td>xxxxxxxx</td><td>Func: Promotion Code | User Entered Code: $keyinput </td><td>$urli</td><td>$device_detect</td></tr>";
 $fp = fopen("$log_filename","$write_pfx");
 fwrite($fp,"$datagrid");
 fclose($fp);

 chmod("$log_filename",0777);
//---------------------------------------------------system--log-end-------------------------------------------------//
}
 else
{
 $response = 2;
 echo $response;
}

?>
