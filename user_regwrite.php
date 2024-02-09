<?php

 $ch = chr(34);
 error_reporting(0);

 header("Content-Type: text/html; charset=utf-8");

 include "config.jsx";

 $f1=(date ("Y")); 
 $f2=(date ("m")); 
 $f3=(date ("d")); 
 $o1=(date ("G")); 
 $o2=(date ("i")); 
 $o3=(date ("s")); 
 $o4=(date ("G"));
 $o5=(date ("i"));
 $o6=(date ("s"));
 $current_date = "$f1/$f2/$f3";
 $regclientip  = $_SERVER['REMOTE_ADDR'];
 $IPADDR       = $regclientip;
 $promo_code_delete = 0;
 $err_txt           = 0;

 $reg_func           = $_POST['reg_func'];
 $u_mail             = $_POST['u_mail'];
 $u_psw1             = $_POST['u_psw1'];
 $u_code1            = $_POST['u_code1'];   
 $u_lang             = $_POST['u_lang'];
 $u_fullname         = $_POST['u_fullname']; 
 $u_address_line     = $_POST['u_address_line']; 
 $u_postal_code      = $_POST['u_postal_code']; 
 $u_country          = $_POST['u_country']; 
 $u_organization     = $_POST['u_organization'];         
 $c_promotional_code = $_POST['c_promotional_code'];
 $u_device_pincode   = $_POST['u_device_pincode'];
 $u_device_pin_passwd1 = $_POST['u_device_pin_passwd1'];
 $fname              = trim("$u_mail.data");
 $promo_del_inf      = "";

 $u_mail             = strtolower($u_mail);  
 $fname              = strtolower($fname);
 $u_psw1             = strtolower($u_psw1);

 $usr_filename       = "database/iptvuser/$fname";
 $fname_oldbox       = "database/oldbox/$fname";
 $fname_oldbox2      = "/usr/local/virtualdata/$domain_name/database/oldbox/$fname";
 $oldbox             = "";

 $usr_pinfile        = "database/pincode/$u_device_pincode";
 $usr_pinfile2       = "/usr/local/virtualdata/$domain_name/database/pincode/$u_device_pincode";

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

 $urli                  = $_SERVER[REQUEST_URI];
 $client_location       = $_POST['client_location'];
 $device_detect         = $_SERVER['HTTP_USER_AGENT'];
 
 include "geomodul.jsx";

 $d_lang = $u_lang;

 include "user_language.jsx";

 $reg_ok          = 1;

if ($reg_func == '1')
{
 $user_func                = "New Registration";
 $expire                   = $current_date;
 $adddaynumber             = 14;

 if (file_exists($fname_oldbox))
 {
   $adddaynumber = 0;
   $oldbox       = " [OBX-nobonus]";
 }

 $expire_date_datetime_now = strtotime($expire);
 $expire_date_new_now      = $expire_date_datetime_now + $adddaynumber * 60 * 60 * 24;
 $expire_date_new_date     = date("Y/m/d",$expire_date_new_now);

 $userstatus      = 'Ok';
 $prevsubday      = "30";
 $prevsubday_save = "30";
 $expire_autooff  = "true";
 $package         = "4";
 $server          = "47";
 $note            = "";
 $mode            = "M3U8";
 $user_id         = implode(':', str_split(str_pad(base_convert(mt_rand(0, 0xffffff), 10, 16) . base_convert(mt_rand(0, 0xffffff), 10, 16), 12), 2));
}

if ($reg_func == '2')
{
 $keyinput             = $_COOKIE["msx9krmew90xgsndc"];
 $encrypt_method       = "AES-256-CBC";
 $secret_key           = 'STGVKMS8394Z3_key';
 $secret_iv            = 'PRQ19UPLSX749_ivy';
 $keyo                 = hash('sha256',$secret_key);
 $cookieloginfkey      = openssl_decrypt(base64_decode($keyinput),$encrypt_method,$keyo,0,$secret_iv);

 if (!($u_mail == $cookieloginfkey)) 
 {
  $reg_ok = 0;
  $err_txt = $unoknow_error;
 }

 $user_func       = "Registration Modify";
 $u_lang_save     = $u_lang;
 $u_psw1_save     = $u_psw1;
 $u_fullname_save = $u_fullname;
 $u_device_pin_passwd1_save = $u_device_pin_passwd1;  
 $u_address_line_save = $u_address_line;
 $u_postal_code_save  = $u_postal_code;
 $u_country_save      = $u_country;
 $u_organization_save = $u_organization;

 include "$usr_filename";
 
 $u_lang          = $u_lang_save;
 $u_psw1          = $u_psw1_save;
 $u_fullname      = $u_fullname_save;
 $u_device_pin_passwd1 = $u_device_pin_passwd1_save;
 $expire_date_new_date = $expire;
 $prevsubday_save      = $prevsubday;
 $u_address_line = $u_address_line_save;
 $u_postal_code  = $u_postal_code_save;
 $u_country      = $u_country_save;
 $u_organization = $u_organization_save;
}

 $pwd             = $u_psw1;

//---------------------------registration-limit-writer--begin----------------------------//
if ($reg_func == '1')
{
 $IPADDR_banfilename = "database/registration_banlist/$IPADDR.data";

 if (file_exists($IPADDR_banfilename)) 
 {
  include "$IPADDR_banfilename";

  if ($registration_number > 3)
  {
   $reg_ok = 0;
   $err_txt = $unoknow_error;
  }
 }
}
//---------------------------registration-limit-writer--end------------------------------//

//-----------begin---Username already exist----------------------------------------------//
if ($reg_func == '1')
{
 if (file_exists($usr_filename)) 
 {
  $reg_ok = 0;
  $err_txt = $Useremailalreexist;
 } 
}
//-----------end---Username already exist----------------------------------------------//

//-----------begin---email empty----------------------------------------------//
 if (trim($u_mail) == '') 
 {
  $reg_ok = 0;
  $err_txt = $Mail_empty;
 } 
//-----------end---email empty----------------------------------------------//

//-----------begin---pincode empty----------------------------------------------//
 if (trim($u_device_pincode) == '') 
 {
  $reg_ok = 0;
  $err_txt = "Pin code empty";
 } 
//-----------end---pincode empty----------------------------------------------//

//-----------begin---Fullname empty----------------------------------------------//
 if (trim($u_fullname) == '') 
 {
  $reg_ok = 0;
  $err_txt = $Full_name_empty;
 } 
//-----------end---Fullname empty----------------------------------------------//

//-----------begin---Bad email address----------------------------------------------//
if ($reg_func == '1')
{
$pos = strpos($u_mail,'@');

if ($pos < 1)
 {
  $reg_ok = 0;
  $err_txt = $Bademailaddr;
 }
}
//-----------end---Bad email address----------------------------------------------//

//-----------begin---Bad email address----------------------------------------------//
if ($reg_func == '1')
{
if (strlen($u_mail) < 4)
 {
  $reg_ok = 0;
  $err_txt = $Bademailaddr;
 }
}
//-----------end---Bad email address----------------------------------------------//

//-----------begin---Bad password----------------------------------------------//
if (strlen($u_psw1) < 6)
 {
  $reg_ok = 0;
  $err_txt = $Bad_password;
 }
//-----------end---Bad password----------------------------------------------//

//-------illegal characters-user-mail-begin------------//
$u_mail2 = "_$u_mail";
$pos  = 0;
$pos1 = strpos($u_mail2,'<');  if ($pos1 > 0)  { $pos = 1; } 
$pos2 = strpos($u_mail2,'>');  if ($pos2 > 0)  { $pos = 1; } 
$pos3 = strpos($u_mail2,'/');  if ($pos3 > 0)  { $pos = 1; } 
$pos4 = strpos($u_mail2,'|');  if ($pos4 > 0)  { $pos = 1; } 
$pos5 = strpos($u_mail2,'(');  if ($pos5 > 0)  { $pos = 1; } 
$pos6 = strpos($u_mail2,')');  if ($pos6 > 0)  { $pos = 1; } 
$pos7 = strpos($u_mail2,'+');  if ($pos7 > 0)  { $pos = 1; }
$pos8 = strpos($u_mail2,'{');  if ($pos8 > 0)  { $pos = 1; } 
$pos9 = strpos($u_mail2,'}');  if ($pos9 > 0)  { $pos = 1; } 
$pos10 = strpos($u_mail2,'['); if ($pos10 > 0) { $pos = 1; } 
$pos11 = strpos($u_mail2,']'); if ($pos11 > 0) { $pos = 1; } 
$pos12 = strpos($u_mail2,'='); if ($pos12 > 0) { $pos = 1; } 
$pos13 = strpos($u_mail2,'"'); if ($pos13 > 0) { $pos = 1; } 
$pos14 = strpos($u_mail2,"'"); if ($pos14 > 0) { $pos = 1; } 
$pos15 = strpos($u_mail2,'&'); if ($pos15 > 0) { $pos = 1; } 
$pos16 = strpos($u_mail2,'#'); if ($pos16 > 0) { $pos = 1; } 
$pos16 = strpos($u_mail2,'!'); if ($pos16 > 0) { $pos = 1; } 
$pos16 = strpos($u_mail2,'%'); if ($pos16 > 0) { $pos = 1; } 

if ($pos == 1)
 {
  $reg_ok = 0;
  $err_txt = $Bad_email_ad;
 }
//-------illegal characters-user-mail-end------------//

//-------illegal characters--begin------------------//
if ($pos == 1)
 {
  $reg_ok = 0;
  $err_txt = $unoknow_error;
 }
//-------illegal characters--end------------------//

//---------------------data-write----begin---------------------------------------//
if ($reg_ok == 1)
{

if ($reg_func == '1')
{
//---------------user code generator---------begin---------------------------------------------//
 $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
 $u_code1 = '';
 for ($i = 0; $i < 5; $i++) 
 {
  $u_code1 = $u_code1 . $characters[rand(0, strlen($characters))];
 }
//---------------user code generator---------end---------------------------------------------//

//------------------begin-szamlalo---------------------------//
$usernumbercount_filename = "database/iptvuser_count/usernumbercount.jsx";

if (file_exists("$usernumbercount_filename"))
 {
  include "$usernumbercount_filename";
 }
 else 
 {
  $numbercount = 0;
 } 

$numbercount++;

$fp = fopen ("$usernumbercount_filename","w");

fwrite($fp,'<?php
');

fwrite($fp,'$numbercount = ' .$ch. $numbercount .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$usernumbercount_filename",0777);
//------------------end-szamlalo-----------------------------//

//------------------begin-szamlalo--2-------------------------//
$usernumbercount_filename2 = "/usr/local/virtualdata/$domain_name/database/iptvuser_count/usernumbercount.jsx";

if (file_exists("$usernumbercount_filename2"))
 {
  include "$usernumbercount_filename2";
 }
 else 
 {
  $numbercount = 0;
 } 

$numbercount++;

$fp = fopen ("$usernumbercount_filename2","w");

fwrite($fp,'<?php
');

fwrite($fp,'$numbercount = ' .$ch. $numbercount .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$usernumbercount_filename2",0777);
//------------------end-szamlalo--2---------------------------//

//------------------begin-index write---------------------------//
if ($numbercount > 1000)
{
 $indexnumber = floor($numbercount/1000);
}
 else
{
 $indexnumber = 1;
}

$fname_userindex = "database/iptvuser_index/$indexnumber/$fname";
mkdir("database/iptvuser_index/$indexnumber");

$fp = fopen ("$fname_userindex","w");
fclose($fp);

chmod("database/iptvuser_index/$indexnumber/$fname",0777);
//------------------end-index write-----------------------------//

//------------------begin-index write--2-------------------------//
if ($numbercount > 1000)
{
 $indexnumber = floor($numbercount/1000);
}
 else
{
 $indexnumber = 1;
}

$fname_userindex = "/usr/local/virtualdata/$domain_name/database/iptvuser_index/$indexnumber/$fname";
mkdir("/usr/local/virtualdata/$domain_name/database/iptvuser_index/$indexnumber");

$fp = fopen ("$fname_userindex","w");
fclose($fp);

chmod("/usr/local/virtualdata/$domain_name/database/iptvuser_index/$indexnumber/$fname",0777);
//------------------end-index write--2---------------------------//

//---------------------------registration-limit-writer--begin---------------------------------//
 $IPADDR_banfilename = "database/registration_banlist/$IPADDR.data";

 if (file_exists($IPADDR_banfilename)) 
 {
  include "$IPADDR_banfilename";
  $registration_number++;
 }
  else
 {
  $registration_number = 1;
 }

$fp = fopen("$IPADDR_banfilename","w");

fwrite($fp,'<?php
');

fwrite($fp,'$registration_number = ' .$ch. $registration_number .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);
//---------------------------registration-limit-writer--end---------------------------------//

}//-----$reg_func=='1'-------//

if ($reg_func == '2')
{
 $u_code1 = 'x';
 $u_code2 = $u_code1;
}

$u_code1_save = $u_code1;

//--------------------primer data begin----------------------------//
$fp = fopen ("$usr_filename","w");

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

fwrite($fp,'$expire = ' .$ch. $expire_date_new_date .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday_save .$ch. ';
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

fwrite($fp,'$c_promotional_code = ' .$ch. $c_promotional_code .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'$user_transaction_code = ' .$ch. $user_transaction_code .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);


chmod("$usr_filename",0777);
//--------------------primer data end-------------------------------//

//-----------------user-pin-code-begin------------------------------//
$fp = fopen ("$usr_pinfile.data","w");

fwrite($fp,'<?php
');

fwrite($fp,'$u_mail = ' .$ch. $u_mail .$ch. ';
');

fwrite($fp,'$u_device_pin_passwd1 = ' .$ch. $u_device_pin_passwd1 .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$usr_pinfile.data",0777);
//-----------------user-pin-code-end-------------------------------//

//-----------------user-pin-code-begin-2-----------------------------//
$fp = fopen ("$usr_pinfile2.data","w");

fwrite($fp,'<?php
');

fwrite($fp,'$u_mail = ' .$ch. $u_mail .$ch. ';
');

fwrite($fp,'$u_device_pin_passwd1 = ' .$ch. $u_device_pin_passwd1 .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$usr_pinfile2.data",0777);
//-----------------user-pin-code-end-2------------------------------//

setcookie("msx9krmew_s_lang","$u_lang",time() + (86400 * 30),"/");

//--------------------secundary data begin----------------------------//
$usr_filename2 = "/usr/local/virtualdata/$domain_name/database/iptvuser/$fname";

$fp = fopen ("$usr_filename2","w");

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

fwrite($fp,'$expire = ' .$ch. $expire_date_new_date .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday_save .$ch. ';
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

fwrite($fp,'$c_promotional_code = ' .$ch. $c_promotional_code .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'$user_transaction_code = ' .$ch. $user_transaction_code .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

chmod("$usr_filename2",0777);
//--------------------secundary data begin----------------------------//

//------------------begin-save-oldbox-data---------------------------//
$fp = fopen ("$fname_oldbox","w");
fclose($fp);
//------------------end-save-oldbox-data-----------------------------//

//------------------begin-save-oldbox-data--2-------------------------//
$fp = fopen ("$fname_oldbox2","w");
fclose($fp);
//------------------end-save-oldbox-data--2---------------------------//

//--------------promo code destroy begin-------------------------//
 $fname_inbox  = "database/promocode_inbox/$c_promotional_code";
 $fname_inbox2 = "/usr/local/virtualdata/$domain_name/database/promocode_inbox/$c_promotional_code";

 if (file_exists($fname_inbox))
  {
   if ($promo_code_delete == 1) { unlink($fname_inbox); unlink($fname_inbox2); }
  }
  else
  {
   $usr_promofilecode = "/usr/local/virtualdata/$domain_name/database/promocode_outbox/$c_promotional_code.data";
   $fp = fopen("$usr_promofilecode","w");
   fclose($fp);

   $usr_promofilecode2 = "database/promocode_outbox/$c_promotional_code.data";
   $fp = fopen("$usr_promofilecode2","w");
   fclose($fp);

   $usr_promocode_action  = "database/invite_code/$c_promotional_code.data";
   $usr_promocode_action2 = "/usr/local/virtualdata/$domain_name/database/invite_code/$c_promotional_code.data";
   if (file_exists($usr_promocode_action))
   {
    include "$usr_promocode_action";
    unlink($usr_promocode_action);   
    unlink($usr_promocode_action2); 

//-------------sender user write begin----------------------------------//
 $sender_usr_filename  = "database/iptvuser/$sender_user_mail.data";
 $sender_usr_filename2 = "/usr/local/virtualdata/$domain_name/database/iptvuser/$sender_user_mail.data";

 $u_fullname_save            = $u_fullname;
 $u_device_pincode_save      = $u_device_pincode;
 $u_device_pin_passwd1_save  = $u_device_pin_passwd1;

 $u_address_line_save        = $u_address_line;
 $u_postal_code_save         = $u_postal_code;
 $u_country_save             = $u_country;
 $u_organization_save        = $u_organization;

 $user_id_save               = $user_id;
 $pwd_save                   = $pwd;
 $regclientip_save           = $regclientip;
 $u_lang_save                = $u_lang;
 $c_promotional_code_save    = $c_promotional_code;
 $indexnumber_save           = $indexnumber;
 $user_transaction_code_save = $user_transaction_code;

 include "$sender_usr_filename";

 //----------------------expired or now date to add bonus--begin-------//
 $current_datetime_now = strtotime($current_date);
 $end_datetime_now     = strtotime($expire);

 $betw_now = $end_datetime_now - $current_datetime_now;
 $betw_day = $betw_now  / 60 / 60 / 24;
 $betw_day = intval($betw_day);

 $subscription_enabled = 1;
 if ($betw_day < 1)
 {
  $subscription_enabled = 0; 
 }
 //----------------------expired or now date to add bonus--end---------//

 $adddaynumber = 3;

  if ($subscription_enabled == 1)
  {  
    $expire_date_datetime_now = strtotime($expire);
  }
   else
  {  
    $expire_date_datetime_now = strtotime($current_date);
  }

 $expire_date_new_now      = $expire_date_datetime_now + $adddaynumber * 60 * 60 * 24;
 $expire_date_new_date     = date("Y/m/d",$expire_date_new_now);

$write_enabled = 1;

if ($write_enabled == 1)
{
$fp = fopen ("$sender_usr_filename","w");

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

fwrite($fp,'$expire = ' .$ch. $expire_date_new_date .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday_save .$ch. ';
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

fwrite($fp,'$c_promotional_code = ' .$ch. $c_promotional_code .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'$user_transaction_code = ' .$ch. $user_transaction_code .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

//--------------------secundary data begin----------------------------//
$fp = fopen ("$sender_usr_filename2","w");

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

fwrite($fp,'$expire = ' .$ch. $expire_date_new_date .$ch. ';
');

fwrite($fp,'$prevsubday = ' .$ch. $prevsubday_save .$ch. ';
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

fwrite($fp,'$c_promotional_code = ' .$ch. $c_promotional_code .$ch. ';
');

fwrite($fp,'$indexnumber = ' .$ch. $indexnumber .$ch. ';
');

fwrite($fp,'$u_code1 = ' .$ch. $u_code1 .$ch. ';
');

fwrite($fp,'$u_code2 = ' .$ch. $u_code2 .$ch. ';
');

fwrite($fp,'$user_transaction_code = ' .$ch. $user_transaction_code .$ch. ';
');

fwrite($fp,'?>
');

fclose($fp);

 $u_fullname            = $u_fullname_save;
 $u_device_pincode      = $u_device_pincode_save;
 $u_device_pin_passwd1  = $u_device_pin_passwd1_save;

 $u_address_line        = $u_address_line_save;
 $u_postal_code         = $u_postal_code_save;
 $u_country             = $u_country_save;
 $u_organization        = $u_organization_save;

 $user_id               = $user_id_save;
 $pwd                   = $pwd_save;
 $regclientip           = $regclientip_save;
 $u_lang                = $u_lang_save;
 $c_promotional_code    = $c_promotional_code_save;
 $indexnumber           = $indexnumber_save;
 $user_transaction_code = $user_transaction_code_save;

//--------------------secundary data begin----------------------------//

$promo_del_inf = " [Promo code action / Sender: $sender_user_mail]";
}
//-------------sender user write end----------------------------------//
   }
   
  }
//--------------promo code destroy end----------------------------//

//----------------------email sender---------begin--------------------//
if ($reg_func == '1')
{
$client_mail = "$Dear $u_fullname!

$Welcome_system

$Activation_code: $u_code1_save

$Enter_Device_pincode: $u_device_pincode
$pin_password_mail: $u_device_pin_passwd1
$Device_pincode_capt


$Pledonatemuspro

$Ifanyprobwriteus: support@$domain_name

$registrationtxt

$Concact: support@$domain_name

$Best_regards
";
}
//----------------------email sender---------end----------------------//

//---------------Client Mail Sender Begin-----------------------------//
if ($reg_func == '1')
{
$cimzett  = "$u_mail";
$targy    = 'HiFi FM Music Platform Registration';
$client_mail   = "$client_mail \r\n ";
$fejlecek = "From: HiFi FM Music Platform <support@hififm.com>" . "\r\n" .
    "Reply-To: support@hififm.com" . "\r\n" .
     'Content-Type: text/plain; charset=UTF-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($cimzett, $targy, $client_mail, $fejlecek);
}
//---------------Client Mail Sender End-----------------------------//


 echo "1|$u_lang";
}

if (!($err_txt == ''))
{
 echo "$err_txt|$u_lang";
}
//---------------------data-write----end-----------------------------------------//

if ($reg_func == '2')
{
 $current_datetime_now = strtotime($current_date);
 $end_datetime_now     = strtotime($expire);

 $betw_now = $end_datetime_now - $current_datetime_now;
 $betw_day = $betw_now  / 60 / 60 / 24;
 $betw_day = intval($betw_day);
}

//---------------------------------------------------system--log-begin-------------------------------------------------//
 $log_filename = "database/log_system/$f1-$f2-$f3.log.html";
 $log_name     = "System";
 //green->#87af61, red->#b96d6d, yellow->#aba230 

 if ($err_txt == '')
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

 if (!($userstatus == "Ok")) { $status_color = "#b96d6d";}

 $datagrid = "$begin_html<tr bgcolor='$status_color'><td>$f1.$f2.$f3. $o4:$o5:$o6</td><td>$IPADDR</td><td>$s_location</td><td>$u_lang</td><td>$u_mail</td><td>$u_fullname</td><td>$expire</td><td>$betw_day</td><td>Func: $user_func | PromoCode: $c_promotional_code$promo_del_inf | Err: $err_txt | Idx: $indexnumber | Rsp: $reg_func </td><td>$urli</td><td>$device_detect</td></tr>";
 $fp = fopen("$log_filename","$write_pfx");
 fwrite($fp,"$datagrid");
 fclose($fp);

 chmod("$log_filename",0777);
//---------------------------------------------------system--log-end-------------------------------------------------//

//---------------------------------------------------user--log-begin-------------------------------------------------//
 $log_filename = "database/log_user/$u_mail/$f1-$f2-$f3.log.html";
 mkdir("database/log_user/$u_mail");
 $log_name     = "User: $u_mail";
 //green->#87af61, red->#e74f4f, yellow->#aba230 

 if ($err_txt == '')
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

 if (!($userstatus == "Ok")) { $status_color = "#b96d6d";}

 $datagrid = "$begin_html<tr bgcolor='$status_color'><td>$f1.$f2.$f3. $o4:$o5:$o6</td><td>$IPADDR</td><td>$s_location</td><td>$u_lang</td><td>$u_mail</td><td>$u_fullname</td><td>$expire</td><td>$betw_day</td><td>Func: $user_func | PromoCode: $c_promotional_code$promo_del_inf | Err: $err_txt | Idx: $indexnumber | Rsp: $reg_func </td><td>$urli</td><td>$device_detect</td></tr>";
 $fp = fopen("$log_filename","$write_pfx");
 fwrite($fp,"$datagrid");
 fclose($fp);

 chmod("$log_filename",0777);
//---------------------------------------------------user--log-end-------------------------------------------------//

?>
