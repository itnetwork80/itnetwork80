<?php

 $ch = chr(34);

 error_reporting(0);

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
 $current_date          = "$f1/$f2/$f3";
 $current_datum_time    = "$f1/$f2/$f3 $o4:$o5:$o6";
 $clientip  	        = $_SERVER['REMOTE_ADDR'];
 $str                   = $_POST['str'];
 $strx                  = $_POST['strx'];
 $u_capt1               = $_POST['u_capt1'];  
 $get_promotional_code  = $_POST['get_promotional_code']; 
 $c_promotional_code    = $_POST['c_promotional_code'];
 $client_location       = $_POST['client_location']; 
 $d_lang                = $_POST['d_lang'];

 if (trim($get_promotional_code == ''))
 {
  $get_promotional_code = $default_promo_code;
 }

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

 if (($str == '0') || ($str == '1'))
 {
  include "user_language.jsx";
 }


 if ($strx == '0')
 {
  $response = "
    <label for='uname'><b>$Entertheprcode: </b></label>
    <input type='text' placeholder='$Entertheprcode' id='u_promotional_code' value='$get_promotional_code' required>
    <button type='button' onclick='javascript:promotional_code_user(1)'>$Submit</button>      
  ";
 }

//-----------------------Begin--Registration Or Modify-------------------------------------------------//
 if ( (($str == '1') & ($strx == '1')) || ($str == '2'))
 {

 if ($str == '1')
 {

//------------------------pincode generator----begin----------------------------------------------------//
while (true)
{ 
  while (true)
  { 
   $characters   = '0123456789';
   $u_device_pincode = '';
   for ($i = 0; $i < 8; $i++) 
   {
    $u_device_pincode = $u_device_pincode . $characters[rand(0,strlen($characters))];
   }
   if (strlen($u_device_pincode) > 7) { break; }
  }
if (!(file_exists("database/pincode/$u_device_pincode.data"))) { break; }
}
//------------------------pincode generator----end------------------------------------------------------//

 }

 if ($str == '2')
 {
  $keyinput           = $_COOKIE["msx9krmew90xgsndc"];
  $encrypt_method     = "AES-256-CBC";
  $secret_key         = 'STGVKMS8394Z3_key';
  $secret_iv          = 'PRQ19UPLSX749_ivy';
  $keyo               = hash('sha256',$secret_key);
  $u_mail             = openssl_decrypt(base64_decode($keyinput),$encrypt_method,$keyo,0,$secret_iv);

  $fname  = "database/iptvuser/$u_mail.data";
  include "$fname";

  $d_lang = $u_lang;
  include "user_language.jsx";

  $u_mailaddr = $u_mail;
  $readonly_email_text    = 'readonly';
  $readonly_email_capt    = "<font color=#A4A4A4>($not_modify)</font>";
  $selected_u_lang = "<option value='$u_lang'>$u_lang</option>";
  $u_passwd1 = $pwd;
  $u_passwd2 = $pwd;
  $u_capt2   = $u_capt1;
  $u_device_pin_passwd2 = $u_device_pin_passwd1;
  $picode_passwd_view = "<br>[<A href='javascript:pinpassview($u_device_pin_passwd1)' style='text-decoration:none'> >>Pin Code Password view<< </A>]</label>";
 }

 $fname_promocode  = "database/invite_code/$c_promotional_code.data";
 if (($str == '1') & (file_exists($fname_promocode)))
 {
  include "$fname_promocode";
  $u_fullname             =  $u_invite_name;
  $u_mailaddr             =  $u_invite_mail;
  $selected_u_lang        =  $u_lang;
  $selected_u_lang = "<option value='$u_lang'>$u_lang</option>";
  $readonly_email_text    = 'readonly';
  $readonly_email_capt    = "<font color=#A4A4A4>($not_modify)</font>";
  $u_passwd1 = '';
  $u_passwd2 = '';
     
  $cookiegetpremium = $_COOKIE["msx9krmew90xplxyc"];
  if ($cookiegetpremium == '')
  {
   $u_code    = '222';
   setcookie("msx9krmew90xplxyc","$u_code",time() + (86400 * 30),"/");
  }

 }

 if (trim($u_lang) == '') { $u_lang = $d_lang; }
 if (trim($u_lang) == '') { $u_lang = 'English'; }

if ($u_lang == 'English') { $iconcode = '🇺🇸'; }
if ($u_lang == 'German') { $iconcode = '🇩🇪'; }
if ($u_lang == 'French') { $iconcode = '🇫🇷'; }
if ($u_lang == 'Georgian') { $iconcode = '🇬🇪'; }
if ($u_lang == 'Italian') { $iconcode = '🇮🇹'; }
if ($u_lang == 'Japanese') { $iconcode = '🇯🇵'; }
if ($u_lang == 'Bulgarian') { $iconcode = '🇧🇬'; }
if ($u_lang == 'Hungarian') { $iconcode = '🇭🇺'; }
if ($u_lang == 'Romanian') { $iconcode = '🇷🇴'; }
if ($u_lang == 'Spanish') { $iconcode = '🇪🇸'; }
if ($u_lang == 'Dutch') { $iconcode = '🇳🇱'; }
if ($u_lang == 'Finnish') { $iconcode = '🇫🇮'; }
if ($u_lang == 'Russian') { $iconcode = '🇷🇺'; }
if ($u_lang == 'Ukranian') { $iconcode = '🇺🇦'; }
if ($u_lang == 'Serbian') { $iconcode = '🇷🇸'; }
if ($u_lang == 'Slovak') { $iconcode = '🇸🇰'; }
if ($u_lang == 'Slovenian') { $iconcode = '🇸🇮'; }
if ($u_lang == 'Polish') { $iconcode = '🇵🇱'; }
if ($u_lang == 'Portuguese') { $iconcode = '🇵🇹'; }
if ($u_lang == 'Pashto') { $iconcode = '🇵🇰'; }
if ($u_lang == 'Esperanto') { $iconcode = '🇮🇨'; }
if ($u_lang == 'Korean') { $iconcode = '🇰🇷'; }
if ($u_lang == 'Turkish') { $iconcode = '🇹🇷'; }
if ($u_lang == 'Arabic') { $iconcode = '🇦🇪'; }
if ($u_lang == 'Albanian') { $iconcode = '🇦🇱'; }
if ($u_lang == 'Basque') { $iconcode = '🇦🇶'; }
if ($u_lang == 'Catalan') { $iconcode = '🇳🇨'; }
if ($u_lang == 'Chinese') { $iconcode = '🇨🇳'; }
if ($u_lang == 'Hrvatski') { $iconcode = '🇭🇷'; }
if ($u_lang == 'Czech') { $iconcode = '🇨🇿'; }
if ($u_lang == 'Danish') { $iconcode = '🇩🇰'; }
if ($u_lang == 'Estonian') { $iconcode = '🇪🇪'; }
if ($u_lang == 'Greek') { $iconcode = '🇬🇷'; }
if ($u_lang == 'Gujarati') { $iconcode = '🇬🇹'; }
if ($u_lang == 'Hebrew') { $iconcode = '🇮🇱'; }
if ($u_lang == 'Hindi') { $iconcode = '🇮🇳'; }
if ($u_lang == 'Azerbaijani') { $iconcode = '🇦🇿'; }
if ($u_lang == 'Bengali') { $iconcode = '🇧🇯'; }
if ($u_lang == 'Marathi') { $iconcode = '🇮🇳'; }
if ($u_lang == 'Sundanese') { $iconcode = '🇸🇩'; }
if ($u_lang == 'Vietnamese') { $iconcode = '🇻🇳'; }
if ($u_lang == 'Icelandic') { $iconcode = '🇮🇸'; }
if ($u_lang == 'Indonesian') { $iconcode = '🇮🇩'; }
if ($u_lang == 'Irish') { $iconcode = '🇮🇪'; }
if ($u_lang == 'Javanese') { $iconcode = '🇮🇩'; }
if ($u_lang == 'Latin') { $iconcode = '🇻🇦'; }
if ($u_lang == 'Latvian') { $iconcode = '🇱🇻'; }
if ($u_lang == 'Lithuanian') { $iconcode = '🇱🇹'; }
if ($u_lang == 'Macedonian') { $iconcode = '🇲🇰'; }
if ($u_lang == 'Malay') { $iconcode = '🇲🇾'; }
if ($u_lang == 'Norwegian') { $iconcode = '🇳🇴'; }
if ($u_lang == 'Persian') { $iconcode = '🇮🇷'; }
if ($u_lang == 'Punjabi') { $iconcode = '🇮🇳'; }
if ($u_lang == 'Swedish') { $iconcode = '🇸🇪'; }
if ($u_lang == 'Tamil') { $iconcode = '🇮🇳'; }
if ($u_lang == 'Telugu') { $iconcode = '🇮🇳'; }
if ($u_lang == 'Thai') { $iconcode = '🇹🇭'; }
if ($u_lang == 'Urdu') { $iconcode = '🇮🇳'; }
if ($u_lang == 'Afrikanns') { $iconcode = '🇨🇫'; }
if ($u_lang == 'Xhosa') { $iconcode = '🇿🇼'; }

if ($user_address_data_enabled == 1)
{
  include "geodata.jsx";
  include "geomodul.jsx";

  if ($u_country == '')
  {
   $u_country = $s_location;
  }

     $user_address_data = "
      <label for='fullname'><b>$Organization_n:</b></label>
      <input type='text' placeholder='Organization/Company name' id='u_organization' value='$u_organization' maxlength='80'>

      <label for='u_country_n'><b><div id='lang_div_caption'>$Select_Country: </div> </b></label>
      <select id='u_country' name='u_country'>

                <option value='$u_country'>$u_country</option>
                <option value='$s_location'>$s_location</option>
                <option value='Afghanistan'>Afghanistan</option>
                <option value='Åland Islands'>Åland Islands</option>
                <option value='Albania'>Albania</option>
                <option value='Algeria'>Algeria</option>
                <option value='American Samoa'>American Samoa</option>
                <option value='Andorra'>Andorra</option>
                <option value='Angola'>Angola</option>
                <option value='Anguilla'>Anguilla</option>
                <option value='Antarctica'>Antarctica</option>
                <option value='Antigua and Barbuda'>Antigua and Barbuda</option>
                <option value='Argentina'>Argentina</option>
                <option value='Armenia'>Armenia</option>
                <option value='Aruba'>Aruba</option>
                <option value='Australia'>Australia</option>
                <option value='Austria'>Austria</option>
                <option value='Azerbaijan'>Azerbaijan</option>
                <option value='Bahamas'>Bahamas</option>
                <option value='Bahrain'>Bahrain</option>
                <option value='Bangladesh'>Bangladesh</option>
                <option value='Barbados'>Barbados</option>
                <option value='Belarus'>Belarus</option>
                <option value='Belgium'>Belgium</option>
                <option value='Belize'>Belize</option>
                <option value='Benin'>Benin</option>
                <option value='Bermuda'>Bermuda</option>
                <option value='Bhutan'>Bhutan</option>
                <option value='Bolivia'>Bolivia</option>
                <option value='Bosnia and Herzegovina'>Bosnia and Herzegovina</option>
                <option value='Botswana'>Botswana</option>
                <option value='Bouvet Island'>Bouvet Island</option>
                <option value='Brazil'>Brazil</option>
                <option value='British Indian Ocean Territory'>British Indian Ocean Territory</option>
                <option value='Brunei Darussalam'>Brunei Darussalam</option>
                <option value='Bulgaria'>Bulgaria</option>
                <option value='Burkina Faso'>Burkina Faso</option>
                <option value='Burundi'>Burundi</option>
                <option value='Cambodia'>Cambodia</option>
                <option value='Cameroon'>Cameroon</option>
                <option value='Canada'>Canada</option>
                <option value='Cape Verde'>Cape Verde</option>
                <option value='Cayman Islands'>Cayman Islands</option>
                <option value='Central African Republic'>Central African Republic</option>
                <option value='Chad'>Chad</option>
                <option value='Chile'>Chile</option>
                <option value='China'>China</option>
                <option value='Christmas Island'>Christmas Island</option>
                <option value='Cocos (Keeling) Islands'>Cocos (Keeling) Islands</option>
                <option value='Colombia'>Colombia</option>
                <option value='Comoros'>Comoros</option>
                <option value='Congo'>Congo</option>
                <option value='Congo, The Democratic Republic of The'>Congo, The Democratic Republic of The</option>
                <option value='Cook Islands'>Cook Islands</option>
                <option value='Costa Rica'>Costa Rica</option>
                <option value='Cote D'ivoire'>Cote D'ivoire</option>
                <option value='Croatia'>Croatia</option>
                <option value='Cuba'>Cuba</option>
                <option value='Cyprus'>Cyprus</option>
                <option value='Czech Republic'>Czech Republic</option>
                <option value='Denmark'>Denmark</option>
                <option value='Djibouti'>Djibouti</option>
                <option value='Dominica'>Dominica</option>
                <option value='Dominican Republic'>Dominican Republic</option>
                <option value='Ecuador'>Ecuador</option>
                <option value='Egypt'>Egypt</option>
                <option value='El Salvador'>El Salvador</option>
                <option value='Equatorial Guinea'>Equatorial Guinea</option>
                <option value='Eritrea'>Eritrea</option>
                <option value='Estonia'>Estonia</option>
                <option value='Ethiopia'>Ethiopia</option>
                <option value='Falkland Islands (Malvinas)'>Falkland Islands (Malvinas)</option>
                <option value='Faroe Islands'>Faroe Islands</option>
                <option value='Fiji'>Fiji</option>
                <option value='Finland'>Finland</option>
                <option value='France'>France</option>
                <option value='French Guiana'>French Guiana</option>
                <option value='French Polynesia'>French Polynesia</option>
                <option value='French Southern Territories'>French Southern Territories</option>
                <option value='Gabon'>Gabon</option>
                <option value='Gambia'>Gambia</option>
                <option value='Georgia'>Georgia</option>
                <option value='Germany'>Germany</option>
                <option value='Ghana'>Ghana</option>
                <option value='Gibraltar'>Gibraltar</option>
                <option value='Greece'>Greece</option>
                <option value='Greenland'>Greenland</option>
                <option value='Grenada'>Grenada</option>
                <option value='Guadeloupe'>Guadeloupe</option>
                <option value='Guam'>Guam</option>
                <option value='Guatemala'>Guatemala</option>
                <option value='Guernsey'>Guernsey</option>
                <option value='Guinea'>Guinea</option>
                <option value='Guinea-bissau'>Guinea-bissau</option>
                <option value='Guyana'>Guyana</option>
                <option value='Haiti'>Haiti</option>
                <option value='Heard Island and Mcdonald Islands'>Heard Island and Mcdonald Islands</option>
                <option value='Holy See (Vatican City State)'>Holy See (Vatican City State)</option>
                <option value='Honduras'>Honduras</option>
                <option value='Hong Kong'>Hong Kong</option>
                <option value='Hungary'>Hungary</option>
                <option value='Iceland'>Iceland</option>
                <option value='India'>India</option>
                <option value='Indonesia'>Indonesia</option>
                <option value='Iran, Islamic Republic of'>Iran, Islamic Republic of</option>
                <option value='Iraq'>Iraq</option>
                <option value='Ireland'>Ireland</option>
                <option value='Isle of Man'>Isle of Man</option>
                <option value='Israel'>Israel</option>
                <option value='Italy'>Italy</option>
                <option value='Jamaica'>Jamaica</option>
                <option value='Japan'>Japan</option>
                <option value='Jersey'>Jersey</option>
                <option value='Jordan'>Jordan</option>
                <option value='Kazakhstan'>Kazakhstan</option>
                <option value='Kenya'>Kenya</option>
                <option value='Kiribati'>Kiribati</option>
                <option value='Korea, Democratic People's Republic of'>Korea, Democratic People's Republic of</option>
                <option value='Korea, Republic of'>Korea, Republic of</option>
                <option value='Kuwait'>Kuwait</option>
                <option value='Kyrgyzstan'>Kyrgyzstan</option>
                <option value='Lao People's Democratic Republic'>Lao People's Democratic Republic</option>
                <option value='Latvia'>Latvia</option>
                <option value='Lebanon'>Lebanon</option>
                <option value='Lesotho'>Lesotho</option>
                <option value='Liberia'>Liberia</option>
                <option value='Libyan Arab Jamahiriya'>Libyan Arab Jamahiriya</option>
                <option value='Liechtenstein'>Liechtenstein</option>
                <option value='Lithuania'>Lithuania</option>
                <option value='Luxembourg'>Luxembourg</option>
                <option value='Macao'>Macao</option>
                <option value='Macedonia, The Former Yugoslav Republic of'>Macedonia, The Former Yugoslav Republic of</option>
                <option value='Madagascar'>Madagascar</option>
                <option value='Malawi'>Malawi</option>
                <option value='Malaysia'>Malaysia</option>
                <option value='Maldives'>Maldives</option>
                <option value='Mali'>Mali</option>
                <option value='Malta'>Malta</option>
                <option value='Marshall Islands'>Marshall Islands</option>
                <option value='Martinique'>Martinique</option>
                <option value='Mauritania'>Mauritania</option>
                <option value='Mauritius'>Mauritius</option>
                <option value='Mayotte'>Mayotte</option>
                <option value='Mexico'>Mexico</option>
                <option value='Micronesia, Federated States of'>Micronesia, Federated States of</option>
                <option value='Moldova, Republic of'>Moldova, Republic of</option>
                <option value='Monaco'>Monaco</option>
                <option value='Mongolia'>Mongolia</option>
                <option value='Montenegro'>Montenegro</option>
                <option value='Montserrat'>Montserrat</option>
                <option value='Morocco'>Morocco</option>
                <option value='Mozambique'>Mozambique</option>
                <option value='Myanmar'>Myanmar</option>
                <option value='Namibia'>Namibia</option>
                <option value='Nauru'>Nauru</option>
                <option value='Nepal'>Nepal</option>
                <option value='Netherlands'>Netherlands</option>
                <option value='Netherlands Antilles'>Netherlands Antilles</option>
                <option value='New Caledonia'>New Caledonia</option>
                <option value='New Zealand'>New Zealand</option>
                <option value='Nicaragua'>Nicaragua</option>
                <option value='Niger'>Niger</option>
                <option value='Nigeria'>Nigeria</option>
                <option value='Niue'>Niue</option>
                <option value='Norfolk Island'>Norfolk Island</option>
                <option value='Northern Mariana Islands'>Northern Mariana Islands</option>
                <option value='Norway'>Norway</option>
                <option value='Oman'>Oman</option>
                <option value='Pakistan'>Pakistan</option>
                <option value='Palau'>Palau</option>
                <option value='Palestinian Territory, Occupied'>Palestinian Territory, Occupied</option>
                <option value='Panama'>Panama</option>
                <option value='Papua New Guinea'>Papua New Guinea</option>
                <option value='Paraguay'>Paraguay</option>
                <option value='Peru'>Peru</option>
                <option value='Philippines'>Philippines</option>
                <option value='Pitcairn'>Pitcairn</option>
                <option value='Poland'>Poland</option>
                <option value='Portugal'>Portugal</option>
                <option value='Puerto Rico'>Puerto Rico</option>
                <option value='Qatar'>Qatar</option>
                <option value='Reunion'>Reunion</option>
                <option value='Romania'>Romania</option>
                <option value='Russian Federation'>Russian Federation</option>
                <option value='Rwanda'>Rwanda</option>
                <option value='Saint Helena'>Saint Helena</option>
                <option value='Saint Kitts and Nevis'>Saint Kitts and Nevis</option>
                <option value='Saint Lucia'>Saint Lucia</option>
                <option value='Saint Pierre and Miquelon'>Saint Pierre and Miquelon</option>
                <option value='Saint Vincent and The Grenadines'>Saint Vincent and The Grenadines</option>
                <option value='Samoa'>Samoa</option>
                <option value='San Marino'>San Marino</option>
                <option value='Sao Tome and Principe'>Sao Tome and Principe</option>
                <option value='Saudi Arabia'>Saudi Arabia</option>
                <option value='Senegal'>Senegal</option>
                <option value='Serbia'>Serbia</option>
                <option value='Seychelles'>Seychelles</option>
                <option value='Sierra Leone'>Sierra Leone</option>
                <option value='Singapore'>Singapore</option>
                <option value='Slovakia'>Slovakia</option>
                <option value='Slovenia'>Slovenia</option>
                <option value='Solomon Islands'>Solomon Islands</option>
                <option value='Somalia'>Somalia</option>
                <option value='South Africa'>South Africa</option>
                <option value='South Georgia and The South Sandwich Islands'>South Georgia and The South Sandwich Islands</option>
                <option value='Spain'>Spain</option>
                <option value='Sri Lanka'>Sri Lanka</option>
                <option value='Sudan'>Sudan</option>
                <option value='Suriname'>Suriname</option>
                <option value='Svalbard and Jan Mayen'>Svalbard and Jan Mayen</option>
                <option value='Swaziland'>Swaziland</option>
                <option value='Sweden'>Sweden</option>
                <option value='Switzerland'>Switzerland</option>
                <option value='Syrian Arab Republic'>Syrian Arab Republic</option>
                <option value='Taiwan, Province of China'>Taiwan, Province of China</option>
                <option value='Tajikistan'>Tajikistan</option>
                <option value='Tanzania, United Republic of'>Tanzania, United Republic of</option>
                <option value='Thailand'>Thailand</option>
                <option value='Timor-leste'>Timor-leste</option>
                <option value='Togo'>Togo</option>
                <option value='Tokelau'>Tokelau</option>
                <option value='Tonga'>Tonga</option>
                <option value='Trinidad and Tobago'>Trinidad and Tobago</option>
                <option value='Tunisia'>Tunisia</option>
                <option value='Turkey'>Turkey</option>
                <option value='Turkmenistan'>Turkmenistan</option>
                <option value='Turks and Caicos Islands'>Turks and Caicos Islands</option>
                <option value='Tuvalu'>Tuvalu</option>
                <option value='Uganda'>Uganda</option>
                <option value='Ukraine'>Ukraine</option>
                <option value='United Arab Emirates'>United Arab Emirates</option>
                <option value='United Kingdom'>United Kingdom</option>
                <option value='United States'>United States</option>
                <option value='United States Minor Outlying Islands'>United States Minor Outlying Islands</option>
                <option value='Uruguay'>Uruguay</option>
                <option value='Uzbekistan'>Uzbekistan</option>
                <option value='Vanuatu'>Vanuatu</option>
                <option value='Venezuela'>Venezuela</option>
                <option value='Viet Nam'>Viet Nam</option>
                <option value='Virgin Islands, British'>Virgin Islands, British</option>
                <option value='Virgin Islands, U.S.'>Virgin Islands, U.S.</option>
                <option value='Wallis and Futuna'>Wallis and Futuna</option>
                <option value='Western Sahara'>Western Sahara</option>
                <option value='Yemen'>Yemen</option>
                <option value='Zambia'>Zambia</option>
                <option value='Zimbabwe'>Zimbabwe</option>
            </select>

      <label for='u_postal_code_n'><b><div id='lang_div_caption'>$Postal_Code: </div> </b></label>
      <input type='text' placeholder='Postal Code' id='u_postal_code' value='$u_postal_code' maxlength='16' required>

      <label for='fullname'><b>$Address_Line:</b></label>
      <input type='text' placeholder='Please Enter City and addres line' id='u_address_line' value='$u_address_line' maxlength='80' required>";
}

 $response = "
      <label for='fullname'><b>$Full_name:</b>$readonly_fullname_capt</label>
      <input type='text' placeholder='$Enter_full_name' id='u_fullname' value='$u_fullname' $readonly_fullname_text maxlength='40' required>

      $user_address_data

      <label for='uname'><b>$Mail_addressf: </b>$readonly_email_capt</label>
      <input type='text' placeholder='$Enter_mail_addressf' id='ur_mail' value='$u_mailaddr' $readonly_email_text maxlength='32' required>

      <label for='psw'><b>$password_send_mail:</b> [$Emilpassword_text] </label>
      <input type='password' placeholder='$password_send_mail' id='ur_psw1' value='$u_passwd1' maxlength='28' required>

      <label for='psw'><b>$Password_agin:</b></label>
      <input type='password' placeholder='$EnterPasswordagain' id='u_psw2' value='$u_passwd2' maxlength='28' required>

      <label for='uname'><b>$Device_pincode: </b>$Device_pincode_capt <br> $What_pin_code </label>
      <input type='text' placeholder='$Enter_Device_pincode' id='u_device_pincode' value='$u_device_pincode' readonly>

      <label for='psw'><b>$Device_pin_password_n</b>[$Pinpassword_text]$picode_passwd_view
      <input type='password' placeholder='$EnterPinPassword' id='u_device_pin_passwd1' value='$u_device_pin_passwd1' maxlength='8' required>

      <label for='psw'><b>$Device_pin_password_agin:</b></label>
      <input type='password' placeholder='$EnterPinPasswordagain' id='u_device_pin_passwd2' value='$u_device_pin_passwd2' maxlength='8' required>

      <label for='ulang'><b><div id='lang_div_caption'>$Select_language: </div> </b></label>
       <select id='u_lang'>
  <option value='$u_lang'>$iconcode $u_lang</option>
  <option value='English'>🇺🇸 English</option>
  <option value='German'>🇩🇪 German</option>
  <option value='French'>🇫🇷 French</option>
  <option value='Georgian'>🇬🇪 Georgian</option>
  <option value='Italian'>🇮🇹 Italian</option>
  <option value='Japanese'>🇯🇵 Japanese</option>
  <option value='Bulgarian'>🇧🇬 Bulgarian</option>
  <option value='Hungarian'>🇭🇺 Hungarian</option>
  <option value='Romanian'>🇷🇴 Romanian</option>
  <option value='Spanish'>🇪🇸 Spanish</option>
  <option value='Dutch'>🇳🇱 Dutch</option>
  <option value='Finnish'>🇫🇮 Finnish</option>
  <option value='Russian'>🇷🇺 Russian</option>
  <option value='Ukranian'>🇺🇦 Ukranian</option>
  <option value='Serbian'>🇷🇸 Serbian</option>
  <option value='Slovak'>🇸🇰 Slovak</option>
  <option value='Slovenian'>🇸🇮 Slovenian</option>
  <option value='Polish'>🇵🇱 Polish</option>
  <option value='Portuguese'>🇵🇹 Portuguese</option>
  <option value='Pashto'>🇵🇰 Pashto</option>
  <option value='Esperanto'>🇮🇨 Esperanto</option>
  <option value='Korean'>🇰🇷 Korean</option>
  <option value='Turkish'>🇹🇷 Turkish</option>
  <option value='Arabic'>🇦🇪 Arabic</option>
  <option value='Albanian'>🇦🇱 Albanian</option>
  <option value='Basque'>🇦🇶 Basque</option>
  <option value='Catalan'>🇳🇨 Catalan</option>
  <option value='Chinese'>🇨🇳 Chinese</option>
  <option value='Hrvatski'>🇭🇷 Hrvatski</option>
  <option value='Czech'>🇨🇿 Czech</option>
  <option value='Danish'>🇩🇰 Danish</option>
  <option value='Estonian'>🇪🇪 Estonian</option>
  <option value='Greek'>🇬🇷 Greek</option>
  <option value='Gujarati'>🇬🇹 Gujarati</option>
  <option value='Hebrew'>🇮🇱 Hebrew</option>
  <option value='Hindi'>🇮🇳 Hindi</option>
  <option value='Azerbaijani'>🇦🇿 Azerbaijani</option>
  <option value='Bengali'>🇧🇯 Bengali</option>
  <option value='Marathi'>🇮🇳 Marathi</option>
  <option value='Sundanese'>🇸🇩 Sundanese</option>
  <option value='Vietnamese'>🇻🇳 Vietnamese</option>
  <option value='Icelandic'>🇮🇸 Icelandic</option>
  <option value='Indonesian'>🇮🇩 Indonesian</option>
  <option value='Irish'>🇮🇪 Irish</option>
  <option value='Javanese'>🇮🇩 Javanese</option>
  <option value='Latin'>🇻🇦 Latin</option>
  <option value='Latvian'>🇱🇻 Latvian</option>
  <option value='Lithuanian'>🇱🇹 Lithuanian</option>
  <option value='Macedonian'>🇲🇰 Macedonian</option>
  <option value='Malay'>🇲🇾 Malay</option>
  <option value='Norwegian'>🇳🇴 Norwegian</option>
  <option value='Persian'>🇮🇷 Persian</option>
  <option value='Punjabi'>🇮🇳 Punjabi</option>
  <option value='Swedish'>🇸🇪 Swedish</option>
  <option value='Tamil'>🇮🇳 Tamil</option>
  <option value='Telugu'>🇮🇳 Telugu</option>
  <option value='Thai'>🇹🇭 Thai</option>
  <option value='Urdu'>🇮🇳 Urdu</option>
  <option value='Afrikanns'>🇨🇫 Afrikanns</option>
  <option value='Xhosa'>🇿🇼 Xhosa</option>
       </select> 

      <label for='uname'><b>$Captcha_Code: <font color=teal>$u_capt1</font></b></label>
      <input type='text' placeholder='$PlEntCaptcha_Code' id='u_capt2' value='$u_capt2' maxlength='8' required>
";

if ($str == '1')
{
$response = "$response <label for='uname'><b>$User_Agreement_caption:</b></label>
<textarea id='w3mission' rows='8' cols='50' readonly>
$User_Agreement
</textarea>

<select id='argselect' onchange='agreement_func()'>
  <option value='PleaseSelect'>$Please_Select</option>
  <option value='notagree'>$I_do_not_agree</option>
  <option value='iagree'>$I_agree</option>
</select>

     <button type='button' name='regbutton' id='regbutton' onclick='javascript:registry_user(1)' disabled><div id='reg_button_div'>$Registration</div></button>
";
}
 else
{     
 $response = "$response <button type='button' name='regbutton' id='regbutton' onclick='javascript:registry_user(1)'><div id='reg_button_div'>$Registration</div></button>";
}

 $response = "$response <div id='delete_user_button_div'></div>";
 }
//-----------------------End--Registration Or Modify----------------------------------------------------//
 
 echo "$response";

?>
