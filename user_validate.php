<?php

 $ch = chr(34);
 error_reporting(0);

 $IPADDR = $_SERVER['REMOTE_ADDR'];

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

 $f1=(date ("Y")); 
 $f2=(date ("m")); 
 $f3=(date ("d")); 
 $o1=(date ("G")); 
 $o2=(date ("i")); 
 $o3=(date ("s")); 
 $o4=(date ("G"));
 $o5=(date ("i"));
 $o6=(date ("s"));

 $str    = $_POST['str'];
 $u_mail = $_POST['u_mail'];
 $fname  = "$u_mail.data";

//-----------begin---Username already exist----------------------------------------------//
 if ((file_exists("database/iptvuser/$fname")) & (!(trim($u_mail) == '')))
 {
  $response = "<font color=red>Mail already exist!</font>";
  echo "$response";
 } 
//-----------begin---Username already exist----------------------------------------------//

//-----------begin---Username already exist----------------------------------------------//
 if ((file_exists("database/invite_email/$fname")) & (!(trim($u_mail) == '')) & ($str == '2'))
 {
  $response = "<font color=red>Mail already exist!</font>";
  echo "$response";
 } 
//-----------begin---Username already exist----------------------------------------------//

?>
