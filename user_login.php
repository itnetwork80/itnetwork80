<?php

 $ch = chr(34);

 error_reporting(0);

 $f1=(date ("Y")); 
 $f2=(date ("m")); 
 $f3=(date ("d")); 
 $o1=(date ("G")); 
 $o2=(date ("i")); 
 $o3=(date ("s")); 
 $o4=(date ("G"));
 $o5=(date ("i"));
 $o6=(date ("s"));
 $current_date       = "$f1/$f2/$f3";
 $current_datum_time = "$f1/$f2/$f3 $o4:$o5:$o6";

 $clientip  	     = $_SERVER['REMOTE_ADDR'];
 $IPADDR             = $clientip;
 $device_detect      = $_SERVER['HTTP_USER_AGENT'];
 $str                = $_POST['str'];
 $u_mail             = $_POST['u_mail'];
 $l_mail             = $_POST['u_mail'];
 $u_psw1             = $_POST['u_psw1'];
 $u_code             = $_POST['u_code1'];
 $trans_key          = $_POST['trans_key'];
 $u_mail             = strtolower($u_mail);
 $u_psw1             = strtolower($u_psw1);
 $pincodelogin       = 0;
 $pincodenowlogin    = 0;
 $loginbanned        = 0;
 $client_location    = $_POST['client_location'];
 $urli               = $_SERVER[REQUEST_URI];

 include "config.jsx";

 include "geomodul.jsx";

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

//------------------user-ban-begin--------------------//
$IPADDR_banfilename = "database/loginbanlist/$IPADDR.data";

if (file_exists($IPADDR_banfilename)) 
{
 include "$IPADDR_banfilename";
 if ($loginban_number > $banlogin_number)
 {
  $loginbanned = 1;
 }
}
//------------------user-ban-end---------------------//
 
if ($loginbanned == 0)
{

 $response =  '';

 if ($str == 2)
 {  
  $keyinput           = $_COOKIE["msx9krmew90xgsndc"];
  $encrypt_method     = "AES-256-CBC";
  $secret_key         = 'STGVKMS8394Z3_key';
  $secret_iv          = 'PRQ19UPLSX749_ivy';
  $keyo               = hash('sha256',$secret_key);
  $u_mail             = openssl_decrypt(base64_decode($keyinput),$encrypt_method,$keyo,0,$secret_iv);
 }

//---------------------pincode begin---------------------------------------------//
 $pincode_fname = "database/pincode/$u_mail.data";
 if (file_exists("$pincode_fname")) 
 {
  include "$pincode_fname";
  if ($u_device_pin_passwd1 == $u_psw1)
  {
   $fname           = "database/iptvuser/$u_mail.data";
   include "$fname";
   $u_psw_pin       = $pwd;
   $pincodelogin    = 1;
   $pincodenowlogin = 1;
   setcookie("msxtg189mx","RTC887u21x1",time() + (86400 * 30),"/");
  }
 }
//---------------------pincode end-----------------------------------------------//
  
 $fname = "database/iptvuser/$u_mail.data";
//-----------begin---Username exist----------------------------------------------//
 if (file_exists("$fname")) 
 {
  if ($pincodelogin == 0) 
  {
   include "$fname";
   $pwd = strtolower($pwd);
   if (!($l_mail == '')) { setcookie("msxtg189mx","RTC887u21x0",time() + (86400 * 30),"/"); }
  }
   else
  { 
   $u_psw1 = $u_psw_pin; 
  }  

  if ($pwd == $u_psw1)
  {
    if ($u_code1 == $u_code2)
    {
      $response  = "ok";
      $responsex = "ok";
    }
     else
    {
      $response  = "notvaliduser";
      if ($u_code1 == $u_code)
      {
        $response  = "useractivationok";       
        $u_code2  = $u_code;

//-------------------User Activation Code Write Begin------------------------//        
$fp = fopen ("$fname","w");

fwrite($fp,'<?php
');

fwrite($fp,'$u_fullname = ' .$ch. $u_fullname .$ch. ';
');

fwrite($fp,'$u_address_line = ' .$ch. $u_address_line .$ch. ';
');

fwrite($fp,'$u_postal_code = ' .$ch. $u_postal_code .$ch. ';
');

fwrite($fp,'$u_country = ' .$ch. $u_country .$ch. ';
');

fwrite($fp,'$u_organization = ' .$ch. $u_organization .$ch. ';
');

fwrite($fp,'$user_id = ' .$ch. $user_id .$ch. ';
');

fwrite($fp,'$pwd = ' .$ch. $pwd .$ch. ';
');

fwrite($fp,'$u_device_pincode = ' .$ch. $u_device_pincode .$ch. ';
');

fwrite($fp,'$u_device_pin_passwd1 = ' .$ch. $u_device_pin_passwd1 .$ch. ';
');

fwrite($fp,'$userstatus = ' .$ch. $userstatus .$ch. ';
');

fwrite($fp,'$expire = ' .$ch. $expire .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday .$ch. ';
');

fwrite($fp,'$expire_autooff = ' .$ch. $expire_autooff .$ch. ';
');

fwrite($fp,'$package = ' .$ch. $package .$ch. ';
');

fwrite($fp,'$server = ' .$ch. $server .$ch. ';
');

fwrite($fp,'$note = ' .$ch. $note .$ch. ';
');

fwrite($fp,'$mode = ' .$ch. $mode .$ch. ';
');

fwrite($fp,'$regclientip = ' .$ch. $regclientip .$ch. ';
');

fwrite($fp,'$u_lang = ' .$ch. $u_lang .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$fname",0777);
//-------------------User Activation Code Write End--------------------------//

//-------------------User Activation Code Write Begin--2----------------------// 
$fname2 = "/usr/local/virtualdata/$domain_name/database/iptvuser/$u_mail.data";
       
$fp = fopen ("$fname2","w");

fwrite($fp,'<?php
');

fwrite($fp,'$u_fullname = ' .$ch. $u_fullname .$ch. ';
');

fwrite($fp,'$u_address_line = ' .$ch. $u_address_line .$ch. ';
');

fwrite($fp,'$u_postal_code = ' .$ch. $u_postal_code .$ch. ';
');

fwrite($fp,'$u_country = ' .$ch. $u_country .$ch. ';
');

fwrite($fp,'$u_organization = ' .$ch. $u_organization .$ch. ';
');

fwrite($fp,'$user_id = ' .$ch. $user_id .$ch. ';
');

fwrite($fp,'$pwd = ' .$ch. $pwd .$ch. ';
');

fwrite($fp,'$u_device_pincode = ' .$ch. $u_device_pincode .$ch. ';
');

fwrite($fp,'$u_device_pin_passwd1 = ' .$ch. $u_device_pin_passwd1 .$ch. ';
');

fwrite($fp,'$userstatus = ' .$ch. $userstatus .$ch. ';
');

fwrite($fp,'$expire = ' .$ch. $expire .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday .$ch. ';
');

fwrite($fp,'$expire_autooff = ' .$ch. $expire_autooff .$ch. ';
');

fwrite($fp,'$package = ' .$ch. $package .$ch. ';
');

fwrite($fp,'$server = ' .$ch. $server .$ch. ';
');

fwrite($fp,'$note = ' .$ch. $note .$ch. ';
');

fwrite($fp,'$mode = ' .$ch. $mode .$ch. ';
');

fwrite($fp,'$regclientip = ' .$ch. $regclientip .$ch. ';
');

fwrite($fp,'$u_lang = ' .$ch. $u_lang .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$fname2",0777);
//-------------------User Activation Code Write End--2------------------------//

      }  
    }
  } 
   else
  {
   $response = "errorloginuser";
  }   
 } 
  else
 {
  $response = "usernotfound";
 }
//-----------begin---Username already exist----------------------------------------------//

 if ($str == 2)
 {
  $response  = 'ok';
  $responsex = 'ok';
 }

if (($response == 'ok') || ($response == 'useractivationok'))
{
 $enabled_login = 1;

//--------------unlock---user---begin-----------------------------------------------------------//
 if ($u_code1 == 'unlock')
 {
 //---------------user unlock---------begin---------------------------------------------// 
 $ipuserfilename   = "database/iptvopen/$u_mail.data";
 include "$ipuserfilename";
 $ipfilename       = "database/iptvopen/$client_inc_ip.data";
 unlink($ipfilename);
 unlink($ipuserfilename);
 //---------------user unlock---------end-----------------------------------------------// 

 //---------------user unlock---------begin--2-------------------------------------------// 
 $ipuserfilename   = "/usr/local/virtualdata/$domain_name/database/iptvopen/$u_mail.data";
 include "$ipuserfilename";
 $ipfilename       = "/usr/local/virtualdata/$domain_name/database/iptvopen/$client_inc_ip.data";
 unlink($ipfilename);
 unlink($ipuserfilename);
 //---------------user unlock---------end--2---------------------------------------------// 

//----------------user----write----begin-----------------------------------------------------//
$u_code1 = 'xx';
$u_code2 = 'xx';

$fp = fopen ("$fname","w");

fwrite($fp,'<?php
');

fwrite($fp,'$u_fullname = ' .$ch. $u_fullname .$ch. ';
');

fwrite($fp,'$u_address_line = ' .$ch. $u_address_line .$ch. ';
');

fwrite($fp,'$u_postal_code = ' .$ch. $u_postal_code .$ch. ';
');

fwrite($fp,'$u_country = ' .$ch. $u_country .$ch. ';
');

fwrite($fp,'$u_organization = ' .$ch. $u_organization .$ch. ';
');

fwrite($fp,'$user_id = ' .$ch. $user_id .$ch. ';
');

fwrite($fp,'$pwd = ' .$ch. $pwd .$ch. ';
');

fwrite($fp,'$u_device_pincode = ' .$ch. $u_device_pincode .$ch. ';
');

fwrite($fp,'$u_device_pin_passwd1 = ' .$ch. $u_device_pin_passwd1 .$ch. ';
');

fwrite($fp,'$userstatus = ' .$ch. $userstatus .$ch. ';
');

fwrite($fp,'$expire = ' .$ch. $expire .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday .$ch. ';
');

fwrite($fp,'$expire_autooff = ' .$ch. $expire_autooff .$ch. ';
');

fwrite($fp,'$package = ' .$ch. $package .$ch. ';
');

fwrite($fp,'$server = ' .$ch. $server .$ch. ';
');

fwrite($fp,'$note = ' .$ch. $note .$ch. ';
');

fwrite($fp,'$mode = ' .$ch. $mode .$ch. ';
');

fwrite($fp,'$regclientip = ' .$ch. $regclientip .$ch. ';
');

fwrite($fp,'$u_lang = ' .$ch. $u_lang .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("database/iptvuser/$fname",0777);
//----------------user----write----begin-----------------------------------------------------//

//----------------user----write----begin--2---------------------------------------------------//
$u_code1 = 'xx';
$u_code2 = 'xx';

$fp = fopen ("$fname2","w");

fwrite($fp,'<?php
');

fwrite($fp,'$u_fullname = ' .$ch. $u_fullname .$ch. ';
');

fwrite($fp,'$u_address_line = ' .$ch. $u_address_line .$ch. ';
');

fwrite($fp,'$u_postal_code = ' .$ch. $u_postal_code .$ch. ';
');

fwrite($fp,'$u_country = ' .$ch. $u_country .$ch. ';
');

fwrite($fp,'$u_organization = ' .$ch. $u_organization .$ch. ';
');

fwrite($fp,'$user_id = ' .$ch. $user_id .$ch. ';
');

fwrite($fp,'$pwd = ' .$ch. $pwd .$ch. ';
');

fwrite($fp,'$u_device_pincode = ' .$ch. $u_device_pincode .$ch. ';
');

fwrite($fp,'$u_device_pin_passwd1 = ' .$ch. $u_device_pin_passwd1 .$ch. ';
');

fwrite($fp,'$userstatus = ' .$ch. $userstatus .$ch. ';
');

fwrite($fp,'$expire = ' .$ch. $expire .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday .$ch. ';
');

fwrite($fp,'$expire_autooff = ' .$ch. $expire_autooff .$ch. ';
');

fwrite($fp,'$package = ' .$ch. $package .$ch. ';
');

fwrite($fp,'$server = ' .$ch. $server .$ch. ';
');

fwrite($fp,'$note = ' .$ch. $note .$ch. ';
');

fwrite($fp,'$mode = ' .$ch. $mode .$ch. ';
');

fwrite($fp,'$regclientip = ' .$ch. $regclientip .$ch. ';
');

fwrite($fp,'$u_lang = ' .$ch. $u_lang .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$fname2",0777);
//----------------user----write----begin--2---------------------------------------------------//

//--------------unlock---user---end------------------------------------------------------------//
}

 $ipuserdirname    = "database/iptvopenlist/$u_mail";
 $ipuseripfile_num = 0;
 if (is_dir("$ipuserdirname") == true)
 {
  //--------------files--begin----------------------------------//
  $dir3 = "$ipuserdirname";
  $handle3 = opendir($dir3);
  while (false !== ($file3 = readdir($handle3))) 
  { 
   if ($file3 != "." && $file3 != "..") 
   { 
     $filepath = "$dir3/$file3";
     $ipuseripfile_num++;;
   }
  }
  closedir($handle3); 
  //--------------files--end------------------------------------//
 }
  $myip_ok = 0;

  $useripfile = "database/iptvopenlist/$u_mail/$clientip.data";
  if (file_exists($useripfile))
  {
   $myip_ok = 1;
  }

  if (($ipuseripfile_num > $ip_login_limit-1) & ($myip_ok == 0))
  {
   $response  = "already_user";
   echo "$response";
   exit;   
  }

 if (($str == 1) & ($enabled_login == 1))
 {
  $input_txt       = "$u_mail";
  $secret_key      = 'STGVKMS8394Z3_key';
  $secret_iv       = 'PRQ19UPLSX749_ivy';
  $encrypt_method  = "AES-256-CBC";
  $keyo = hash('sha256',$secret_key); 
  $output_code = base64_encode(openssl_encrypt($input_txt,$encrypt_method,$keyo,0,$secret_iv));

  setcookie("msx9krmew90xgsndc","$output_code",time() + (86400 * 30),"/");
  setcookie("msxtg964mx","RTC852321x",time() + (86400 * 30),"/");
 }

//-------------expire---subscription---begin---------------------//
$subscription_enabled = 1;

if ($expire == 'always')
{
 $subscription_enabled = 1;
}
 else
{
 $current_datetime_now = strtotime($current_date);
 $end_datetime_now     = strtotime($expire);

 $betw_now = $end_datetime_now - $current_datetime_now;
 $betw_day = $betw_now  / 60 / 60 / 24;
 $betw_day = intval($betw_day);

 if ($betw_day < 1)
 {
  $subscription_enabled = 0; 
 } 
 
}
//-------------expire---subscription---end-----------------------//

//---------------icecast-auth-user begin-------------------------//
if (($subscription_enabled == 1) & ($userstatus == 'Ok'))
{ 
 if ($expire == 'always') { $betw_day = 'always'; }
 $response = "ok|$betw_day";
} 
 else 
{ 
 if ($subscription_enabled == 0) { $response = 'expired_subscription|$betw_day'; }
 if (!($userstatus  == 'Ok'))    { $response = 'blocked_user|$betw_day'; }
}
//---------------icecast-auth-user end---------------------------//

//-----------user--false-auto-off to expire---begin---------------//

//-----------ip-open-write-----begin------------//
if ((($subscription_enabled == 1) || ($expire_autooff == 'false')) & ($userstatus == 'Ok'))
{

//---------------------begin--open--web-file--------------//
mkdir("database/iptvopenlist/$u_mail");

$ipfilename = "database/iptvopenlist/$u_mail/$clientip.data"; 
if (!(file_exists($ipfilename)))
{ 
$fp = fopen ("$ipfilename","w");
fwrite($fp,'<?php
');
fwrite($fp,'$client_inc_mail = ' .$ch. $u_mail .$ch. ';
');
fwrite($fp,'$access_datum_time = ' .$ch. $current_datum_time .$ch. ';
');
fwrite($fp,'?>
');
fclose($fp);
chmod("$ipfilename",0777);
}  
//---------------------end--open--web-file----------------//

//---------------------begin--open--player-access-file--------------//
$ipfilename = "database/iptvopen/$clientip.data"; 
if (!(file_exists($ipfilename)))
{ 
$fp = fopen ("$ipfilename","w");
fwrite($fp,'<?php
');
fwrite($fp,'$client_inc_mail = ' .$ch. $u_mail .$ch. ';
');
fwrite($fp,'$access_datum_time = ' .$ch. $current_datum_time .$ch. ';
');
fwrite($fp,'?>
');
fclose($fp);
chmod("$ipfilename",0777);
}  
//---------------------end--open--player-access-file----------------//


  if (($subscription_enabled == 0) & ($expire_autooff == 'false')) { $response = 'expired_subscription2|$betw_day'; }
 }
 //-----------ip-open-write-----end--------------//
//-----------user--false-auto-off to expire---end---------------//
}

//---------------------Premium Code Begin-------------------------------//
if ($premiumcode_autologin == 1)
{
 $cookiegetpremium      = $_COOKIE["msx9krmew90xplxyc"];
 $premium_code_filename = "database/premiumcode_user/$u_mail.data";
 include "$premium_code_filename";

 $premium_func = 0;
 if ($u_code == $cookiegetpremium)
 {  
  include "user_premium_table.jsx";

  $u_premium_code_inf = " [Premium Code: $u_premium_code ($premium_func)]";
 }
  else
 {
  $u_premium_code_inf = " [Inactive Premium Code]"; 
 }
}
//---------------------Premium Code end---------------------------------//

$pinkey = $_COOKIE["msxtg189mx"];

if ($pinkey == 'RTC887u21x0') { $pincodelogin = 0; }
if ($pinkey == 'RTC887u21x1') { $pincodelogin = 1; }

if (!($l_mail == ''))         { $pincodelogin = 0; }
if ($pincodenowlogin == 1)    { $pincodelogin = 1; }

echo "$response|$u_fullname|$premium_func|$pincodelogin|$u_lang";

//---------------login-error-ban-user-begin-------------------//
if (($response == 'errorloginuser') || ($response == 'usernotfound'))
{
 $IPADDR_banfilename = "database/loginbanlist/$IPADDR.data";

 if (file_exists($IPADDR_banfilename)) 
 {
  include "$IPADDR_banfilename";
  $loginban_number++;
 }
  else
 {
  $loginban_number = 1;
 }

$fp = fopen("$IPADDR_banfilename","w");

fwrite($fp,'<?php
');

fwrite($fp,'$loginban_number = ' .$ch. $loginban_number .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);
}
//---------------login-error-ban-user-end---------------------//

}
 else
{
 $response = "unoknow_error";
 echo "$response|$u_fullname|$premium_func|$pincodelogin|$u_lang";
}

if ($u_code2 == '') { $u_code2 = $u_code; }

//---------------------------------------------------system--log-begin-------------------------------------------------//
 $log_filename = "database/log_system/$f1-$f2-$f3.log.html";
 $log_name     = "System";
 //green->#87af61, red->#b96d6d, yellow->#aba230 

 if ($responsex == 'ok')
 {
  $status_color = "#87af61";  
  if (($betw_day < 0) || ($betw_day == 0)) { $status_color = "#b96d6d"; }
 }
  else
 {
  $status_color = "#b96d6d";
 }

 $urli = $_SERVER[REQUEST_URI];

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

 if (!($userstatus == "Ok")) { $status_color = "#b96d6d";}

 $datagrid = "$begin_html<tr bgcolor='$status_color'><td>$f1.$f2.$f3. $o4:$o5:$o6</td><td>$IPADDR</td><td>$s_location</td><td>$u_lang</td><td>$u_mail</td><td>$u_fullname</td><td>$expire</td><td>$betw_day</td><td>Func: Login | Pin: $pincodelogin | [$response] | $premium_func | Ucodes.: [$u_code1][$u_code2] </td><td>$urli</td><td>$device_detect</td></tr>";
 $fp = fopen("$log_filename","$write_pfx");
 fwrite($fp,"$datagrid");
 fclose($fp);

 chmod("$log_filename",0777);
//---------------------------------------------------system--log-end-------------------------------------------------//

//---------------------------------------------------user--log-begin-------------------------------------------------//
 if ($responsex == 'ok')
 {
 $log_filename = "database/log_user/$u_mail/$f1-$f2-$f3.log.html";
 mkdir("database/log_user/$u_mail");
 $log_name     = "User: $u_mail";
 //green->#87af61, red->#b96d6d, yellow->#aba230 

 if ($responsex == 'ok')
 {
  $status_color = "#87af61";  
  if (($betw_day < 0) || ($betw_day == 0)) { $status_color = "#b96d6d"; }
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

 if (!($userstatus == "Ok")) { $status_color = "#b96d6d";}

 $datagrid = "$begin_html<tr bgcolor='$status_color'><td>$f1.$f2.$f3. $o4:$o5:$o6</td><td>$IPADDR</td><td>$s_location</td><td>$u_lang</td><td>$u_mail</td><td>$u_fullname</td><td>$expire</td><td>$betw_day</td><td>Func: Login | Pin: $pincodelogin | [$response] | $premium_func | Ucodes.: [$u_code1][$u_code2] </td><td>$urli</td><td>$device_detect</td></tr>";
 $fp = fopen("$log_filename","$write_pfx");
 fwrite($fp,"$datagrid");
 fclose($fp);

 chmod("$log_filename",0777);
 }
//---------------------------------------------------user--log-end-------------------------------------------------//

?>
