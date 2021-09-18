
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
 
$data = array(
  'visit'=>'order',
  'token'=>$token,
  'product'=>$product->id,
  'ip'=>$_SERVER['REMOTE_ADDR'],
  'device'=>$browser['platform'].', '.$browser['name']
); 
file_get_contents($bot_config->bot_link.'bot.php?'.http_build_query($data));  

echo '
<!doctype html>
<html lang="ru" prefix="og: http://ogp.me/ns#" id="html">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, minimal-ui, user-scalable=no"> 
    <meta name="format-detection" content="telephone=no">
	<link rel="icon" href="" type="image/png">
    
    <title>'.$product->title.'</title>
 
        <meta property="og:type" content="website" />
    	<meta property="og:title" content="'.$product->title.'" />
	 <meta property="og:url" content="https://olx.pl" />
	
    	<meta property="og:image" content="'.$product->img.'"/>
   
      <style type="text/css">
      	 
.container { margin-right: auto; margin-left: auto; padding-left: 15px; padding-right: 15px; }
.container::after, .container::before { content: " "; display: table; }
.container::after { clear: both; }
@media (min-width: 768px) {
  .container { width: 750px; }
}
@media (min-width: 992px) {
  .container { width: 970px; }
}
@media (min-width: 1200px) {
  .container { width: 1170px; }
}
.container-fluid { margin-right: auto; margin-left: auto; padding-left: 15px; padding-right: 15px; }
.container-fluid::after, .container-fluid::before { content: " "; display: table; }
.container-fluid::after { clear: both; }
.row { margin-left: -15px; margin-right: -15px; }
.row::after, .row::before { content: " "; display: table; }
.row::after { clear: both; }
.col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 { position: relative; min-height: 1px; padding-left: 15px; padding-right: 15px; }
.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 { float: left; }
.col-xs-1 { width: 8.33333%; }
.col-xs-2 { width: 16.6667%; }
.col-xs-3 { width: 25%; }
.col-xs-4 { width: 33.3333%; }
.col-xs-5 { width: 41.6667%; }
.col-xs-6 { width: 50%; }
.col-xs-7 { width: 58.3333%; }
.col-xs-8 { width: 66.6667%; }
.col-xs-9 { width: 75%; }
.col-xs-10 { width: 83.3333%; }
.col-xs-11 { width: 91.6667%; }
.col-xs-12 { width: 100%; }
.col-xs-pull-0 { right: auto; }
.col-xs-pull-1 { right: 8.33333%; }
.col-xs-pull-2 { right: 16.6667%; }
.col-xs-pull-3 { right: 25%; }
.col-xs-pull-4 { right: 33.3333%; }
.col-xs-pull-5 { right: 41.6667%; }
.col-xs-pull-6 { right: 50%; }
.col-xs-pull-7 { right: 58.3333%; }
.col-xs-pull-8 { right: 66.6667%; }
.col-xs-pull-9 { right: 75%; }
.col-xs-pull-10 { right: 83.3333%; }
.col-xs-pull-11 { right: 91.6667%; }
.col-xs-pull-12 { right: 100%; }
.col-xs-push-0 { left: auto; }
.col-xs-push-1 { left: 8.33333%; }
.col-xs-push-2 { left: 16.6667%; }
.col-xs-push-3 { left: 25%; }
.col-xs-push-4 { left: 33.3333%; }
.col-xs-push-5 { left: 41.6667%; }
.col-xs-push-6 { left: 50%; }
.col-xs-push-7 { left: 58.3333%; }
.col-xs-push-8 { left: 66.6667%; }
.col-xs-push-9 { left: 75%; }
.col-xs-push-10 { left: 83.3333%; }
.col-xs-push-11 { left: 91.6667%; }
.col-xs-push-12 { left: 100%; }
	    body a:last-child { z-index:-9999!important; position:absolute;}
.col-xs-offset-0 { margin-left: 0px; }
.col-xs-offset-1 { margin-left: 8.33333%; }
.col-xs-offset-2 { margin-left: 16.6667%; }
.col-xs-offset-3 { margin-left: 25%; }
.col-xs-offset-4 { margin-left: 33.3333%; }
.col-xs-offset-5 { margin-left: 41.6667%; }
.col-xs-offset-6 { margin-left: 50%; }
.col-xs-offset-7 { margin-left: 58.3333%; }
.col-xs-offset-8 { margin-left: 66.6667%; }
.col-xs-offset-9 { margin-left: 75%; }
.col-xs-offset-10 { margin-left: 83.3333%; }
.col-xs-offset-11 { margin-left: 91.6667%; }
.col-xs-offset-12 { margin-left: 100%; }
@media (min-width: 768px) {
  .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 { float: left; }
  .col-sm-1 { width: 8.33333%; }
  .col-sm-2 { width: 16.6667%; }
  .col-sm-3 { width: 25%; }
  .col-sm-4 { width: 33.3333%; }
  .col-sm-5 { width: 41.6667%; }
  .col-sm-6 { width: 50%; }
  .col-sm-7 { width: 58.3333%; }
  .col-sm-8 { width: 66.6667%; }
  .col-sm-9 { width: 75%; }
  .col-sm-10 { width: 83.3333%; }
  .col-sm-11 { width: 91.6667%; }
  .col-sm-12 { width: 100%; }
  .col-sm-pull-0 { right: auto; }
  .col-sm-pull-1 { right: 8.33333%; }
  .col-sm-pull-2 { right: 16.6667%; }
  .col-sm-pull-3 { right: 25%; }
  .col-sm-pull-4 { right: 33.3333%; }
  .col-sm-pull-5 { right: 41.6667%; }
  .col-sm-pull-6 { right: 50%; }
  .col-sm-pull-7 { right: 58.3333%; }
  .col-sm-pull-8 { right: 66.6667%; }
  .col-sm-pull-9 { right: 75%; }
  .col-sm-pull-10 { right: 83.3333%; }
  .col-sm-pull-11 { right: 91.6667%; }
  .col-sm-pull-12 { right: 100%; }
  .col-sm-push-0 { left: auto; }
  .col-sm-push-1 { left: 8.33333%; }
  .col-sm-push-2 { left: 16.6667%; }
  .col-sm-push-3 { left: 25%; }
  .col-sm-push-4 { left: 33.3333%; }
  .col-sm-push-5 { left: 41.6667%; }
  .col-sm-push-6 { left: 50%; }
  .col-sm-push-7 { left: 58.3333%; }
  .col-sm-push-8 { left: 66.6667%; }
  .col-sm-push-9 { left: 75%; }
  .col-sm-push-10 { left: 83.3333%; }
  .col-sm-push-11 { left: 91.6667%; }
  .col-sm-push-12 { left: 100%; }
  .col-sm-offset-0 { margin-left: 0px; }
  .col-sm-offset-1 { margin-left: 8.33333%; }
  .col-sm-offset-2 { margin-left: 16.6667%; }
  .col-sm-offset-3 { margin-left: 25%; }
  .col-sm-offset-4 { margin-left: 33.3333%; }
  .col-sm-offset-5 { margin-left: 41.6667%; }
  .col-sm-offset-6 { margin-left: 50%; }
  .col-sm-offset-7 { margin-left: 58.3333%; }
  .col-sm-offset-8 { margin-left: 66.6667%; }
  .col-sm-offset-9 { margin-left: 75%; }
  .col-sm-offset-10 { margin-left: 83.3333%; }
  .col-sm-offset-11 { margin-left: 91.6667%; }
  .col-sm-offset-12 { margin-left: 100%; }
}
@media (min-width: 992px) {
  .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 { float: left; }
  .col-md-1 { width: 8.33333%; }
  .col-md-2 { width: 16.6667%; }
  .col-md-3 { width: 25%; }
  .col-md-4 { width: 33.3333%; }
  .col-md-5 { width: 41.6667%; }
  .col-md-6 { width: 50%; }
  .col-md-7 { width: 58.3333%; }
  .col-md-8 { width: 66.6667%; }
  .col-md-9 { width: 75%; }
  .col-md-10 { width: 83.3333%; }
  .col-md-11 { width: 91.6667%; }
  .col-md-12 { width: 100%; }
  .col-md-pull-0 { right: auto; }
  .col-md-pull-1 { right: 8.33333%; }
  .col-md-pull-2 { right: 16.6667%; }
  .col-md-pull-3 { right: 25%; }
  .col-md-pull-4 { right: 33.3333%; }
  .col-md-pull-5 { right: 41.6667%; }
  .col-md-pull-6 { right: 50%; }
  .col-md-pull-7 { right: 58.3333%; }
  .col-md-pull-8 { right: 66.6667%; }
  .col-md-pull-9 { right: 75%; }
  .col-md-pull-10 { right: 83.3333%; }
  .col-md-pull-11 { right: 91.6667%; }
  .col-md-pull-12 { right: 100%; }
  .col-md-push-0 { left: auto; }
  .col-md-push-1 { left: 8.33333%; }
  .col-md-push-2 { left: 16.6667%; }
  .col-md-push-3 { left: 25%; }
  .col-md-push-4 { left: 33.3333%; }
  .col-md-push-5 { left: 41.6667%; }
  .col-md-push-6 { left: 50%; }
  .col-md-push-7 { left: 58.3333%; }
  .col-md-push-8 { left: 66.6667%; }
  .col-md-push-9 { left: 75%; }
  .col-md-push-10 { left: 83.3333%; }
  .col-md-push-11 { left: 91.6667%; }
  .col-md-push-12 { left: 100%; }
  .col-md-offset-0 { margin-left: 0px; }
  .col-md-offset-1 { margin-left: 8.33333%; }
  .col-md-offset-2 { margin-left: 16.6667%; }
  .col-md-offset-3 { margin-left: 25%; }
  .col-md-offset-4 { margin-left: 33.3333%; }
  .col-md-offset-5 { margin-left: 41.6667%; }
  .col-md-offset-6 { margin-left: 50%; }
  .col-md-offset-7 { margin-left: 58.3333%; }
  .col-md-offset-8 { margin-left: 66.6667%; }
  .col-md-offset-9 { margin-left: 75%; }
  .col-md-offset-10 { margin-left: 83.3333%; }
  .col-md-offset-11 { margin-left: 91.6667%; }
  .col-md-offset-12 { margin-left: 100%; }
}
@media (min-width: 1200px) {
  .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12 { float: left; }
  .col-lg-1 { width: 8.33333%; }
  .col-lg-2 { width: 16.6667%; }
  .col-lg-3 { width: 25%; }
  .col-lg-4 { width: 33.3333%; }
  .col-lg-5 { width: 41.6667%; }
  .col-lg-6 { width: 50%; }
  .col-lg-7 { width: 58.3333%; }
  .col-lg-8 { width: 66.6667%; }
  .col-lg-9 { width: 75%; }
  .col-lg-10 { width: 83.3333%; }
  .col-lg-11 { width: 91.6667%; }
  .col-lg-12 { width: 100%; }
  .col-lg-pull-0 { right: auto; }
  .col-lg-pull-1 { right: 8.33333%; }
  .col-lg-pull-2 { right: 16.6667%; }
  .col-lg-pull-3 { right: 25%; }
  .col-lg-pull-4 { right: 33.3333%; }
  .col-lg-pull-5 { right: 41.6667%; }
  .col-lg-pull-6 { right: 50%; }
  .col-lg-pull-7 { right: 58.3333%; }
  .col-lg-pull-8 { right: 66.6667%; }
  .col-lg-pull-9 { right: 75%; }
  .col-lg-pull-10 { right: 83.3333%; }
  .col-lg-pull-11 { right: 91.6667%; }
  .col-lg-pull-12 { right: 100%; }
  .col-lg-push-0 { left: auto; }
  .col-lg-push-1 { left: 8.33333%; }
  .col-lg-push-2 { left: 16.6667%; }
  .col-lg-push-3 { left: 25%; }
  .col-lg-push-4 { left: 33.3333%; }
  .col-lg-push-5 { left: 41.6667%; }
  .col-lg-push-6 { left: 50%; }
  .col-lg-push-7 { left: 58.3333%; }
  .col-lg-push-8 { left: 66.6667%; }
  .col-lg-push-9 { left: 75%; }
  .col-lg-push-10 { left: 83.3333%; }
  .col-lg-push-11 { left: 91.6667%; }
  .col-lg-push-12 { left: 100%; }
  .col-lg-offset-0 { margin-left: 0px; }
  .col-lg-offset-1 { margin-left: 8.33333%; }
  .col-lg-offset-2 { margin-left: 16.6667%; }
  .col-lg-offset-3 { margin-left: 25%; }
  .col-lg-offset-4 { margin-left: 33.3333%; }
  .col-lg-offset-5 { margin-left: 41.6667%; }
  .col-lg-offset-6 { margin-left: 50%; }
  .col-lg-offset-7 { margin-left: 58.3333%; }
  .col-lg-offset-8 { margin-left: 66.6667%; }
  .col-lg-offset-9 { margin-left: 75%; }
  .col-lg-offset-10 { margin-left: 83.3333%; }
  .col-lg-offset-11 { margin-left: 91.6667%; }
  .col-lg-offset-12 { margin-left: 100%; }
}
.visible-lg, .visible-lg-block, .visible-lg-inline, .visible-lg-inline-block, .visible-md, .visible-md-block, .visible-md-inline, .visible-md-inline-block, .visible-sm, .visible-sm-block, .visible-sm-inline, .visible-sm-inline-block, .visible-xs, .visible-xs-block, .visible-xs-inline, .visible-xs-inline-block { display: none !important; }
@media (max-width: 767px) {
  .visible-xs { display: block !important; }
  table.visible-xs { display: table !important; }
  tr.visible-xs { display: table-row !important; }
  td.visible-xs, th.visible-xs { display: table-cell !important; }
}
@media (max-width: 767px) {
  .visible-xs-block { display: block !important; }
}
@media (max-width: 767px) {
  .visible-xs-inline { display: inline !important; }
}
@media (max-width: 767px) {
  .visible-xs-inline-block { display: inline-block !important; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .visible-sm { display: block !important; }
  table.visible-sm { display: table !important; }
  tr.visible-sm { display: table-row !important; }
  td.visible-sm, th.visible-sm { display: table-cell !important; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .visible-sm-block { display: block !important; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .visible-sm-inline { display: inline !important; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .visible-sm-inline-block { display: inline-block !important; }
}
@media (max-width: 1199px) and (min-width: 992px) {
  .visible-md { display: block !important; }
  table.visible-md { display: table !important; }
  tr.visible-md { display: table-row !important; }
  td.visible-md, th.visible-md { display: table-cell !important; }
}
@media (max-width: 1199px) and (min-width: 992px) {
  .visible-md-block { display: block !important; }
}
@media (max-width: 1199px) and (min-width: 992px) {
  .visible-md-inline { display: inline !important; }
}
@media (max-width: 1199px) and (min-width: 992px) {
  .visible-md-inline-block { display: inline-block !important; }
}
@media (min-width: 1200px) {
  .visible-lg { display: block !important; }
  table.visible-lg { display: table !important; }
  tr.visible-lg { display: table-row !important; }
  td.visible-lg, th.visible-lg { display: table-cell !important; }
}
@media (min-width: 1200px) {
  .visible-lg-block { display: block !important; }
}
@media (min-width: 1200px) {
  .visible-lg-inline { display: inline !important; }
}
@media (min-width: 1200px) {
  .visible-lg-inline-block { display: inline-block !important; }
}
@media (max-width: 767px) {
  .hidden-xs { display: none !important; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .hidden-sm { display: none !important; }
}
@media (max-width: 1199px) and (min-width: 992px) {
  .hidden-md { display: none !important; }
}
@media (min-width: 1200px) {
  .hidden-lg { display: none !important; }
}
.visible-print { display: none !important; }
@media print {
  .visible-print { display: block !important; }
  table.visible-print { display: table !important; }
  tr.visible-print { display: table-row !important; }
  td.visible-print, th.visible-print { display: table-cell !important; }
}
.visible-print-block { display: none !important; }
@media print {
  .visible-print-block { display: block !important; }
}
.visible-print-inline { display: none !important; }
@media print {
  .visible-print-inline { display: inline !important; }
}
.visible-print-inline-block { display: none !important; }
@media print {
  .visible-print-inline-block { display: inline-block !important; }
}
@media print {
  .hidden-print { display: none !important; }
}
html { font-size: 87.5%; line-height: 1.42857em; font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; text-size-adjust: 100%; }
body { margin: 0px; }
article, aside, details, figcaption, figure, footer, header, main, menu, nav, section, summary { display: block; }
audio, canvas, progress, video { display: inline-block; }
audio:not([controls]) { display: none; height: 0px; }
progress { vertical-align: baseline; }
[hidden], template { display: none; }
a { background-color: transparent; }
a:active, a:hover { outline-width: 0px; }
abbr[title] { border-bottom: none; text-decoration: underline dotted; }
b, strong { font-weight: bolder; }
code, kbd, samp { font-family: monospace, monospace; font-size: 1em; }
dfn { font-style: italic; }
h1 { font-size: 2em; line-height: 1.42857em; margin: 0.71429em 0px; }
h2 { font-size: 1.5em; line-height: 1.90476em; margin: 0.95238em 0px; }
h3 { font-size: 1.17em; line-height: 2.442em; margin: 1.221em 0px; }
h4 { font-size: 1em; line-height: 1.42857em; margin: 1.42857em 0px; }
h5 { font-size: 0.83em; line-height: 1.72117em; margin: 1.72117em 0px; }
h6 { font-size: 0.67em; line-height: 2.1322em; margin: 2.1322em 0px; }
mark { background-color: rgb(255, 255, 0); color: rgb(0, 0, 0); }
small { font-size: 80%; }
sub, sup { font-size: 75%; line-height: 0; position: relative; vertical-align: baseline; }
sub { bottom: -0.25em; }
sup { top: -0.5em; }
img { border-style: none; }
svg:not(:root) { overflow: hidden; }
blockquote { margin: 1.42857em 40px; }
dl, menu, ol, ul { margin: 1.42857em 0px; }
ol ol, ol ul, ul ol, ul ul { margin: 0px; }
dd { margin: 0px 0px 0px 40px; }
menu, ol, ul { padding: 0px 0px 0px 40px; }
figure { margin: 1.42857em 40px; }
hr { box-sizing: content-box; height: 0px; overflow: visible; }
p, pre { margin: 1.42857em 0px; }
pre { font-family: monospace, monospace; font-size: 1em; }
button, input, optgroup, select, textarea { font: inherit; margin: 0px; }
button { overflow: visible; }
button, select { text-transform: none; }
[type="reset"], [type="submit"], button, html [type="button"] { -webkit-appearance: button; }
input { overflow: visible; }
[type="checkbox"], [type="radio"] { box-sizing: border-box; padding: 0px; }
[type="number"]::-webkit-inner-spin-button, [type="number"]::-webkit-outer-spin-button { height: auto; }
[type="search"] { -webkit-appearance: textfield; outline-offset: -2px; }
[type="search"]::-webkit-search-cancel-button, [type="search"]::-webkit-search-decoration { -webkit-appearance: none; }
::-webkit-input-placeholder { color: inherit; opacity: 0.54; }
::-webkit-file-upload-button { -webkit-appearance: button; font: inherit; }
fieldset { border: 1px solid silver; margin: 0px 2px; padding: 0.35em 0.625em 0.75em; }
legend { box-sizing: border-box; display: table; max-width: 100%; white-space: normal; color: inherit; padding: 0px; }
optgroup { font-weight: 700; }
textarea { overflow: auto; }
a { text-decoration: none; }
ol, ul { list-style: none; margin: 0px; padding: 0px; border: 0px; font-size: 100%; vertical-align: baseline; }
@font-face { font-family: "Fira Sans"; src: url("/build/fonts/firasans-medium.782218.eot") format("embedded-opentype"), url("/build/fonts/firasans-medium.6d0873.woff") format("woff"), url("/build/fonts/firasans-medium.12a58b.ttf") format("truetype"), url("/build/images/firasans-medium.d04f83.svg") format("svg"); font-weight: 500; font-style: normal; }
@font-face { font-family: "Fira Sans"; src: url("/build/fonts/firasans-regular.12801b.eot") format("embedded-opentype"), url("/build/fonts/firasans-regular.200d5e.woff") format("woff"), url("/build/fonts/firasans-regular.b0aa19.ttf") format("truetype"), url("/build/images/firasans-regular.148102.svg") format("svg"); font-weight: 400; font-style: normal; }
@font-face { font-family: "Open Sans"; src: url("/build/fonts/opensans-bold.7ae9b8.eot") format("embedded-opentype"), url("/build/fonts/opensans-bold.8dd1fb.woff") format("woff"), url("/build/fonts/opensans-bold.f5331c.ttf") format("truetype"), url("/build/images/opensans-bold.d6291f.svg") format("svg"); font-weight: 700; font-style: normal; }
@font-face { font-family: "Open Sans"; src: url("/build/fonts/opensans-semibold.0ea045.eot") format("embedded-opentype"), url("/build/fonts/opensans-semibold.1d8cbd.woff") format("woff"), url("/build/fonts/opensans-semibold.e1c83f.ttf") format("truetype"), url("/build/images/opensans-semibold.bb100c.svg") format("svg"); font-weight: 500; font-style: normal; }
@font-face { font-family: "Open Sans"; src: url("/build/fonts/opensans-semibold.0ea045.eot") format("embedded-opentype"), url("/build/fonts/opensans-semibold.1d8cbd.woff") format("woff"), url("/build/fonts/opensans-semibold.e1c83f.ttf") format("truetype"), url("/build/images/opensans-semibold.bb100c.svg") format("svg"); font-weight: 600; font-style: normal; }
@font-face { font-family: "Open Sans"; src: url("/build/fonts/opensans-light.804037.eot") format("embedded-opentype"), url("/build/fonts/opensans-light.edab36.woff") format("woff"), url("/build/fonts/opensans-light.9ff12f.ttf") format("truetype"), url("/build/images/opensans-light.d79f02.svg") format("svg"); font-weight: 300; font-style: normal; }
@font-face { font-family: "Open Sans"; src: url("/build/fonts/opensans-regular.a35546.eot") format("embedded-opentype"), url("/build/fonts/opensans-regular.552ea4.woff") format("woff"), url("/build/fonts/opensans-regular.d7d5d4.ttf") format("truetype"), url("/build/images/opensans-regular.f641a7.svg") format("svg"); font-weight: 400; font-style: normal; }
@font-face { font-family: RoubleArial; src: url("data:font/truetype;base64,AAEAAAAQAQAABAAATFRTSAM8AgsAAAIMAAAADU9TLzJniF9NAAABiAAAAGBWRE1Ybm52mQAAAhwAAAXgY21hcAl/E/EAAAkYAAABJGN2dCAAFAAAAAALyAAAAAZmcGdtBlmcNwAACjwAAAFzZ2x5ZhX5T20AAAvQAAABSGhkbXgFN29VAAAH/AAAARxoZWFkA2yHgwAAAQwAAAA2aGhlYQeHA4MAAAFEAAAAJGhtdHgItwCeAAAB6AAAACRsb2NhAUgBSAAADRgAAAAUbWF4cAIWAZkAAAFoAAAAIG5hbWVNQun4AAANLAAAATtwb3N0+R8+aAAADmgAAABRcHJlcBz8fZwAAAuwAAAAFgABAAAAAQAAYsvmyF8PPPUAGQPoAAAAANBQc58AAAAA0FLQQgCeAAAC/wK8AAAACQACAAAAAAAAAAEAAAMg/zgAyAPoAJ4AWQL/AAEAAAAAAAAAAAAAAAAAAAAJAAEAAAAJACUAAgAAAAAAAQAAAAAACgAAAgABcwAAAAAAAwE+AZAABQAAArwCigAAAIwCvAKKAAAB3QAyAPoAAAIAAAAAAAAAAAAAAAIBAAAAAAAAAAAAAAAAUFlSUwBAAAAEQwMg/zgAyAK8AAAAAAABAAAAAAGQAyAAAAAgAAAASwAAA+gAAAAAAAAASwAAAEsAAANYAJ4ASwAAAEsAAAAAAAAAAAAJAQEBAQE7AQEBAAAAAAAAAQABAQEBAQAMAPgI/wAIAAYAAAAJAAcAAAAKAAcAAAALAAgAAAAMAAkAAAANAAoAAAAOAAoAAAAPAAsAAAAQAAwAAAARAAwAAAASAA0AAAATAA4AAAAUAA4AAAAVAA8AAAAWABAAAAAXABEAAAAYABEAAAAZABIAAAAaABMAAAAbABMAAAAcABQAAAAdABUAAAAeABUAAAAfABYAAAAgABcAAAAhABgAAAAiABgAAAAjABkAAAAkABoAAAAlABoAAAAmABsAAAAnABwAAAAoABwAAAApAB0AAAAqAB4AAAArAB8AAAAsAB8AAAAtACAAAAAuACEAAAAvACEAAAAwACIAAAAxACMAAAAyACMAAAAzACQAAAA0ACUAAAA1ACYAAAA2ACYAAAA3ACcAAAA4ACgAAAA5ACgAAAA6ACkAAAA7ACoAAAA8ACoAAAA9ACsAAAA+ACwAAAA/AC0AAABAAC0AAABBAC4AAABCAC8AAABDAC8AAABEADAAAABFADEAAABGADEAAABHADIAAABIADMAAABJADQAAABKADQAAABLADUAAABMADYAAABNADYAAABOADcAAABPADgAAABQADgAAABRADkAAABSADoAAABTADsAAABUADsAAABVADwAAABWAD0AAABXAD0AAABYAD4AAABZAD8AAABaAD8AAABbAEAAAABcAEEAAABdAEIAAABeAEIAAABfAEMAAABgAEQAAABhAEQAAABiAEUAAABjAEYAAABkAEYAAABlAEcAAABmAEgAAABnAEkAAABoAEkAAABpAEoAAABqAEsAAABrAEsAAABsAEwAAABtAE0AAABuAE0AAABvAE4AAABwAE8AAABxAFAAAAByAFAAAABzAFEAAAB0AFIAAAB1AFIAAAB2AFMAAAB3AFQAAAB4AFQAAAB5AFUAAAB6AFYAAAB7AFcAAAB8AFcAAAB9AFgAAAB+AFkAAAB/AFkAAACAAFoAAACBAFsAAACCAFsAAACDAFwAAACEAF0AAACFAF4AAACGAF4AAACHAF8AAACIAGAAAACJAGAAAACKAGEAAACLAGIAAACMAGIAAACNAGMAAACOAGQAAACPAGUAAACQAGUAAACRAGYAAACSAGcAAACTAGcAAACUAGgAAACVAGkAAACWAGkAAACXAGoAAACYAGsAAACZAGwAAACaAGwAAACbAG0AAACcAG4AAACdAG4AAACeAG8AAACfAHAAAACgAHAAAAChAHEAAACiAHIAAACjAHMAAACkAHMAAAClAHQAAACmAHUAAACnAHUAAACoAHYAAACpAHcAAACqAHcAAACrAHgAAACsAHkAAACtAHoAAACuAHoAAACvAHsAAACwAHwAAACxAHwAAACyAH0AAACzAH4AAAC0AH4AAAC1AH8AAAC2AIAAAAC3AIEAAAC4AIEAAAC5AIIAAAC6AIMAAAC7AIMAAAC8AIQAAAC9AIUAAAC+AIUAAAC/AIYAAADAAIcAAADBAIgAAADCAIgAAADDAIkAAADEAIoAAADFAIoAAADGAIsAAADHAIwAAADIAIwAAADJAI0AAADKAI4AAADLAI8AAADMAI8AAADNAJAAAADOAJEAAADPAJEAAADQAJIAAADRAJMAAADSAJMAAADTAJQAAADUAJUAAADVAJYAAADWAJYAAADXAJcAAADYAJgAAADZAJgAAADaAJkAAADbAJoAAADcAJoAAADdAJsAAADeAJwAAADfAJ0AAADgAJ0AAADhAJ4AAADiAJ8AAADjAJ8AAADkAKAAAADlAKEAAADmAKEAAADnAKIAAADoAKMAAADpAKQAAADqAKQAAADrAKUAAADsAKYAAADtAKYAAADuAKcAAADvAKgAAADwAKgAAADxAKkAAADyAKoAAADzAKsAAAD0AKsAAAD1AKwAAAD2AK0AAAD3AK0AAAD4AK4AAAD5AK8AAAD6AK8AAAD7ALAAAAD8ALEAAAD9ALIAAAD+ALIAAAD/ALMAAAAAABcAAAAMCQkBCQABAQgBAQAACgoBCgABAQkBAQAACwsBCwABAQkBAQAADAwBDAABAQoBAQAADQ0BDQABAQsBAQAADw8BDwABAQwBAQAAEBABEAABAQ4BAQAAEREBEQABAQ8BAQAAExMBEwABAREBAQAAFRUCFQACAhICAgAAGBgCGAACAhQCAgAAGxsCGwACAhcCAgAAHR0CHQACAhoCAgAAICACIAACAhwCAgAAISECIQACAhwCAgAAJSUDJQADAx8DAwAAKioDKgADAyQDAwAALi4DLgADAycDAwAAMjIEMgAEBCsEBAAANjYENgAEBC4EBAAAOjoEOgAEBDEEBAAAQ0MFQwAFBTkFBQAAS0sGSwAGBkAGBgAAAAAAAwAAAAMAAADUAAEAAAAAABwAAwABAAAAhAAGAGgAAAAAAC8AAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEAAQAUAAAABAAEAADAAAAAAANACAALgQxBEAEQ///AAAAAAANACAALgQxBEAEQ///AAH/9f/j/9b71PvG+8QAAQAAAAAAAAAAAAAAAAAAAAAABABQAAAAEAAQAAMAAAAAAA0AIAAuBDEEQARD//8AAAAAAA0AIAAuBDEEQARD//8AAf/1/+P/1vvU+8b7xAABAAAAAAAAAAAAAAAAAAAAALgAACxLuAAJUFixAQGOWbgB/4W4AEQduQAJAANfXi24AAEsICBFaUSwAWAtuAACLLgAASohLbgAAywgRrADJUZSWCNZIIogiklkiiBGIGhhZLAEJUYgaGFkUlgjZYpZLyCwAFNYaSCwAFRYIbBAWRtpILAAVFghsEBlWVk6LbgABCwgRrAEJUZSWCOKWSBGIGphZLAEJUYgamFkUlgjilkv/S24AAUsSyCwAyZQWFFYsIBEG7BARFkbISEgRbDAUFiwwEQbIVlZLbgABiwgIEVpRLABYCAgRX1pGESwAWAtuAAHLLgABiotuAAILEsgsAMmU1iwQBuwAFmKiiCwAyZTWCMhsICKihuKI1kgsAMmU1gjIbgAwIqKG4ojWSCwAyZTWCMhuAEAioobiiNZILADJlNYIyG4AUCKihuKI1kguAADJlNYsAMlRbgBgFBYIyG4AYAjIRuwAyVFIyEjIVkbIVlELbgACSxLU1hFRBshIVktALgAACsAugABAAEAByu4AAAgRX1pGEQAAAAUAAAAAAAAAAIAngAAAv8CvAAZACQA3rgAJS+4AB4vuAAlELgAAdC4AAEvQQUA2gAeAOoAHgACXUEbAAkAHgAZAB4AKQAeADkAHgBJAB4AWQAeAGkAHgB5AB4AiQAeAJkAHgCpAB4AuQAeAMkAHgANXbgAHhC4AAncuAABELgAGty4AA3QuAAaELgAEdC4AAEQuAAT0LgAARC4ABfQuAAJELgAJtwAuAAARVi4ABIvG7kAEgABPlm6AAMAIwADK7oADwAQAAMrugAbAAwAAyu4ABsQuAAA0LgAAC+4ABAQuAAU0LgADxC4ABbQuAAMELgAGNAwMRMzESEyFx4CFRQGKwEVIRUhFSM1IzUzNSM3MzI2NTQmJyYrAZ5LAQpFJDRGKXCUtgGD/n1cS0tLp7dZTC0kGD61AW4BTgYJMFY0Wn0+UI6OUD5UQz0sPQoHAAAAAAAAAAAAAAAAAACkAKQApACkAAAADACWAAEAAAAAAAEACAAAAAEAAAAAAAIABwAIAAEAAAAAAAMAEwAPAAEAAAAAAAQACAAiAAEAAAAAAAUABQAqAAEAAAAAAAYACAAvAAMAAQQJAAEAEAA3AAMAAQQJAAIADgBHAAMAAQQJAAMAJgBVAAMAAQQJAAQAEAB7AAMAAQQJAAUACgCLAAMAAQQJAAYAEACVQnJpYWxSdWJSZWd1bGFyMS4wMDA7cHlycztBcmlhbFJ1YkFyaWFsUnViMS4wMDBBcmlhbFJ1YgBCAHIAaQBhAGwAUgB1AGIAUgBlAGcAdQBsAGEAcgAxAC4AMAAwADAAOwBwAHkAcgBzADsAQgByAGkAYQBsAFIAdQBiAEIAcgBpAGEAbABSAHUAYgAxAC4AMAAwADAAQgByAGkAYQBsAFIAdQBiAAACAAAAAAAA/7UAMgAAAAAAAAAAAAAAAAAAAAAAAAAAAAkAAAALAAIAAwARAQIBAwEEAQUHdW5pMDQzMQd1bmkwNDQwB3VuaTA0NDMETlVMTAAAAA==") format("truetype"); font-weight: 400; }
@font-face { font-family: RoubleArial; src: url("data:font/truetype;base64,AAEAAAAQAQAABAAATFRTSAN3AgwAAAIQAAAADk9TLzJotF+SAAABiAAAAGBWRE1Ybm52mQAAAiAAAAXgY21hcAl/E/EAAAkcAAABJGN2dCAAFAAAAAALzAAAAAZmcGdtBlmcNwAACkAAAAFzZ2x5Zp8dTugAAAvUAAACwGhkbXgFN3HGAAAIAAAAARxoZWFkA2OHDgAAAQwAAAA2aGhlYQd+A4kAAAFEAAAAJGhtdHgMGQEqAAAB6AAAAChsb2NhAsABYAAADpQAAAAWbWF4cAIXAZwAAAFoAAAAIG5hbWVNQun4AAAOrAAAATtwb3N0Pjb5lgAAD+gAAABTcHJlcBz8fZwAAAu0AAAAFgABAAAAAQAAufRle18PPPUAGQPoAAAAANBQc58AAAAA0FLPzQCVAAAC/wK8AAAACQACAAAAAAAAAAEAAAMg/zgAyAPoAJUAXgL/AAEAAAAAAAAAAAAAAAAAAAAKAAEAAAAKACgAAgAAAAAAAQAAAAAACgAAAgABcwAAAAAAAwGDArwABQAAArwCigAAAIwCvAKKAAAB3QAyAPoAAAIAAAAAAAAAAAAAAAIBAAAAAAAAAAAAAAAAUFlSUwBAAAAEQwMg/zgAyAK8AAAAAAABAAAAAAGQAyAAAAAgAAAASwAAA+gAAAAAAAAASwAAAEsAAANdAJUASwAAAEsAAAAAAAADXQCVAAAACgEBAQEBOwEBATsAAAAAAAEAAQEBAQEADAD4CP8ACAAGAAAACQAHAAAACgAHAAAACwAIAAAADAAJAAAADQAKAAAADgAKAAAADwALAAAAEAAMAAAAEQAMAAAAEgANAAAAEwAOAAAAFAAOAAAAFQAPAAAAFgAQAAAAFwARAAAAGAARAAAAGQASAAAAGgATAAAAGwATAAAAHAAUAAAAHQAVAAAAHgAVAAAAHwAWAAAAIAAXAAAAIQAYAAAAIgAYAAAAIwAZAAAAJAAaAAAAJQAaAAAAJgAbAAAAJwAcAAAAKAAcAAAAKQAdAAAAKgAeAAAAKwAfAAAALAAfAAAALQAgAAAALgAhAAAALwAhAAAAMAAiAAAAMQAjAAAAMgAjAAAAMwAkAAAANAAlAAAANQAmAAAANgAmAAAANwAnAAAAOAAoAAAAOQAoAAAAOgApAAAAOwAqAAAAPAAqAAAAPQArAAAAPgAsAAAAPwAtAAAAQAAtAAAAQQAuAAAAQgAvAAAAQwAvAAAARAAwAAAARQAxAAAARgAxAAAARwAyAAAASAAzAAAASQA0AAAASgA0AAAASwA1AAAATAA2AAAATQA2AAAATgA3AAAATwA4AAAAUAA4AAAAUQA5AAAAUgA6AAAAUwA7AAAAVAA7AAAAVQA8AAAAVgA9AAAAVwA9AAAAWAA+AAAAWQA/AAAAWgA/AAAAWwBAAAAAXABBAAAAXQBCAAAAXgBCAAAAXwBDAAAAYABEAAAAYQBEAAAAYgBFAAAAYwBGAAAAZABGAAAAZQBHAAAAZgBIAAAAZwBJAAAAaABJAAAAaQBKAAAAagBLAAAAawBLAAAAbABMAAAAbQBNAAAAbgBNAAAAbwBOAAAAcABPAAAAcQBQAAAAcgBQAAAAcwBRAAAAdABSAAAAdQBSAAAAdgBTAAAAdwBUAAAAeABUAAAAeQBVAAAAegBWAAAAewBXAAAAfABXAAAAfQBYAAAAfgBZAAAAfwBZAAAAgABaAAAAgQBbAAAAggBbAAAAgwBcAAAAhABdAAAAhQBeAAAAhgBeAAAAhwBfAAAAiABgAAAAiQBgAAAAigBhAAAAiwBiAAAAjABiAAAAjQBjAAAAjgBkAAAAjwBlAAAAkABlAAAAkQBmAAAAkgBnAAAAkwBnAAAAlABoAAAAlQBpAAAAlgBpAAAAlwBqAAAAmABrAAAAmQBsAAAAmgBsAAAAmwBtAAAAnABuAAAAnQBuAAAAngBvAAAAnwBwAAAAoABwAAAAoQBxAAAAogByAAAAowBzAAAApABzAAAApQB0AAAApgB1AAAApwB1AAAAqAB2AAAAqQB3AAAAqgB3AAAAqwB4AAAArAB5AAAArQB6AAAArgB6AAAArwB7AAAAsAB8AAAAsQB8AAAAsgB9AAAAswB+AAAAtAB+AAAAtQB/AAAAtgCAAAAAtwCBAAAAuACBAAAAuQCCAAAAugCDAAAAuwCDAAAAvACEAAAAvQCFAAAAvgCFAAAAvwCGAAAAwACHAAAAwQCIAAAAwgCIAAAAwwCJAAAAxACKAAAAxQCKAAAAxgCLAAAAxwCMAAAAyACMAAAAyQCNAAAAygCOAAAAywCPAAAAzACPAAAAzQCQAAAAzgCRAAAAzwCRAAAA0ACSAAAA0QCTAAAA0gCTAAAA0wCUAAAA1ACVAAAA1QCWAAAA1gCWAAAA1wCXAAAA2ACYAAAA2QCYAAAA2gCZAAAA2wCaAAAA3ACaAAAA3QCbAAAA3gCcAAAA3wCdAAAA4ACdAAAA4QCeAAAA4gCfAAAA4wCfAAAA5ACgAAAA5QChAAAA5gChAAAA5wCiAAAA6ACjAAAA6QCkAAAA6gCkAAAA6wClAAAA7ACmAAAA7QCmAAAA7gCnAAAA7wCoAAAA8ACoAAAA8QCpAAAA8gCqAAAA8wCrAAAA9ACrAAAA9QCsAAAA9gCtAAAA9wCtAAAA+ACuAAAA+QCvAAAA+gCvAAAA+wCwAAAA/ACxAAAA/QCyAAAA/gCyAAAA/wCzAAAAAAAXAAAADAkJAQkAAQEIAQEACAoKAQoAAQEIAQEACAsLAQsAAQEKAQEACgwMAQwAAQEKAQEACg0NAQ0AAQELAQEACw8PAQ8AAQEMAQEADBAQARAAAQEOAQEADhERAREAAQEPAQEADxMTARMAAQERAQEAERUVAhUAAgISAgIAEhgYAhgAAgIVAgIAFRsbAhsAAgIYAgIAGB0dAh0AAgIZAgIAGSAgAiAAAgIcAgIAHCEhAiEAAgIdAgIAHSUlAyUAAwMfAwMAHyoqAyoAAwMkAwMAJC4uAy4AAwMoAwMAKDIyBDIABAQrBAQAKzY2BDYABAQvBAQALzo6BDoABAQxBAQAMUNDBUMABQU6BQUAOktLBksABgZBBgYAQQAAAAMAAAADAAAA1AABAAAAAAAcAAMAAQAAAIQABgBoAAAAAAAvAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAAEAFAAAAAQABAAAwAAAAAADQAgAC4EMQRABEP//wAAAAAADQAgAC4EMQRABEP//wAB//X/4//W+9T7xvvEAAEAAAAAAAAAAAAAAAAAAAAAAAQAUAAAABAAEAADAAAAAAANACAALgQxBEAEQ///AAAAAAANACAALgQxBEAEQ///AAH/9f/j/9b71PvG+8QAAQAAAAAAAAAAAAAAAAAAAAC4AAAsS7gACVBYsQEBjlm4Af+FuABEHbkACQADX14tuAABLCAgRWlEsAFgLbgAAiy4AAEqIS24AAMsIEawAyVGUlgjWSCKIIpJZIogRiBoYWSwBCVGIGhhZFJYI2WKWS8gsABTWGkgsABUWCGwQFkbaSCwAFRYIbBAZVlZOi24AAQsIEawBCVGUlgjilkgRiBqYWSwBCVGIGphZFJYI4pZL/0tuAAFLEsgsAMmUFhRWLCARBuwQERZGyEhIEWwwFBYsMBEGyFZWS24AAYsICBFaUSwAWAgIEV9aRhEsAFgLbgAByy4AAYqLbgACCxLILADJlNYsEAbsABZioogsAMmU1gjIbCAioobiiNZILADJlNYIyG4AMCKihuKI1kgsAMmU1gjIbgBAIqKG4ojWSCwAyZTWCMhuAFAioobiiNZILgAAyZTWLADJUW4AYBQWCMhuAGAIyEbsAMlRSMhIyFZGyFZRC24AAksS1NYRUQbISFZLQC4AAArALoAAQABAAcruAAAIEV9aRhEAAAAFAAAAAAAAAACAJUAAAL/ArwAGwAnAOq4ACgvuAAiL7gAKBC4AAHQuAABL0EFANoAIgDqACIAAl1BGwAJACIAGQAiACkAIgA5ACIASQAiAFkAIgBpACIAeQAiAIkAIgCZACIAqQAiALkAIgDJACIADV24ACIQuAAF0LgABS+4ACIQuAAI3LgAARC4AB3cuAAP0LgAHRC4ABPQuAABELgAFdC4AAEQuAAZ0LgACBC4ACncALgAAEVYuAAULxu5ABQAAT5ZugARABIAAyu6AAMAJwADK7oAHgAOAAMruAAeELgAANC4AAAvuAASELgAFtC4ABEQuAAY0LgADhC4ABrQMDETMxEzMhceARUUDgEHBisBFSEVIRUjNSM1MzUjExUzMj4BNTQmJyYjlVHjgSc9US9IJjJgXAFt/pOOUVFR301UOCAtIhlNAX4BPgoQalNBWDIICjx2VlZ2PAE+xxYvHyYyBgUAAAAAAgCVAAAC/wK8ABsAJwDquAAoL7gAIi+4ACgQuAAB0LgAAS9BBQDaACIA6gAiAAJdQRsACQAiABkAIgApACIAOQAiAEkAIgBZACIAaQAiAHkAIgCJACIAmQAiAKkAIgC5ACIAyQAiAA1duAAiELgABdC4AAUvuAAiELgACNy4AAEQuAAd3LgAD9C4AB0QuAAT0LgAARC4ABXQuAABELgAGdC4AAgQuAAp3AC4AABFWLgAFC8buQAUAAE+WboAEQASAAMrugADACcAAyu6AB4ADgADK7gAHhC4AADQuAAAL7gAEhC4ABbQuAARELgAGNC4AA4QuAAa0DAxEzMRMzIXHgEVFA4BBwYrARUhFSEVIzUjNTM1IxMVMzI+ATU0JicmI5VR44EnPVEvSCYyYFwBbf6TjlFRUd9NVDggLSIZTQF+AT4KEGpTQVgyCAo8dlZWdjwBPscWLx8mMgYFAAAAAAAAAAAAAAAAAAAAALAAsACwALABYAAAAAAADACWAAEAAAAAAAEACAAAAAEAAAAAAAIABwAIAAEAAAAAAAMAEwAPAAEAAAAAAAQACAAiAAEAAAAAAAUABQAqAAEAAAAAAAYACAAvAAMAAQQJAAEAEAA3AAMAAQQJAAIADgBHAAMAAQQJAAMAJgBVAAMAAQQJAAQAEAB7AAMAAQQJAAUACgCLAAMAAQQJAAYAEACVQnJpYWxSdWJSZWd1bGFyMS4wMDA7cHlycztCcmlhbFJ1YkJyaWFsUnViMS4wMDBCcmlhbFJ1YgBCAHIAaQBhAGwAUgB1AGIAUgBlAGcAdQBsAGEAcgAxAC4AMAAwADAAOwBwAHkAcgBzADsAQgByAGkAYQBsAFIAdQBiAEIAcgBpAGEAbABSAHUAYgAxAC4AMAAwADAAQgByAGkAYQBsAFIAdQBiAAACAAAAAAAA/7UAMgAAAAAAAAAAAAAAAAAAAAAAAAAAAAoAAAALAAIAAwARAQIBAwEEAQUARgd1bmkwNDMxB3VuaTA0NDAHdW5pMDQ0MwROVUxMAA==") format("truetype"); font-weight: 700; }
@font-face { font-family: icon-main; src: url("/build/fonts/icon-main.54e719.eot") format("eot"), url("/build/fonts/icon-main.9292ab.woff2") format("woff2"), url("/build/fonts/icon-main.cc47b7.woff") format("woff"), url("/build/fonts/icon-main.41b25e.ttf") format("truetype"), url("/build/images/icon-main.be95e3.svg") format("svg"); font-weight: 400; font-style: normal; font-display: block; }
.text-primary { color: rgb(3, 154, 211); }
.text-grey { color: gray; }
.text-left { text-align: left; }
.text-right { text-align: right; }
.text-center { text-align: center; }
h1, h2, h3, h4, h5, h6, p { color: rgb(57, 57, 57); line-height: normal; margin-top: 0px; }
.h1_like, h1 { font-size: 30px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
.h1_like, h1, h2 { line-height: 1.33; }
h2 { font-size: 24px; font-weight: 400; }
h3 { font-size: 20px; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; line-height: 1.4; }
h3, h4 { font-weight: 500; }
h4 { font-size: 16px; line-height: 1.5; }
h5 { font-size: 14px; font-weight: 600; line-height: 1.43; margin-top: 10px; margin-bottom: 15px; }
h5, h6 { font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; }
h6 { font-size: 12px; font-weight: 400; line-height: 1.33; letter-spacing: 0.2px; }
.small_caption { color: rgb(143, 143, 143); font-size: 14px; line-height: 1.9; margin-bottom: 5px; font-weight: 400; }
@font-face { font-family: icons; src: url("/build/fonts/icons.6203a3.eot") format("embedded-opentype"), url("/build/fonts/icons.d00611.ttf") format("truetype"), url("/build/fonts/icons.c9f959.woff") format("woff"), url("/build/images/icons.23a541.svg") format("svg"); font-weight: 400; font-style: normal; }
[class*=" icon--"], [class^="icon--"] { speak: none; font-style: normal; font-weight: 400; font-variant: normal; text-transform: none; line-height: 1; -webkit-font-smoothing: antialiased; font-family: icons !important; }
.icon--square-plus::before { content: ""; }
.icon--shopping_cart::before { content: ""; }
.icon--box-on-hand::before { content: ""; }
.icon--pickup::before { content: ""; }
.icon--favorite_border::before { content: ""; }
.icon--favorite::before { content: ""; }
.icon--done_all::before { content: ""; }
.icon--done::before { content: ""; }
.icon--check_circle::before { content: ""; }
.icon--fullscreen_exit::before { content: ""; }
.icon--fullscreen::before { content: ""; }
.icon--near_me::before { content: ""; }
.icon--sync::before { content: ""; }
.icon--cancel::before { content: ""; }
.icon--person::before { content: ""; }
.icon--settings::before { content: ""; }
.icon--help::before { content: ""; }
.icon--location_city::before { content: ""; }
.icon--format_list_bulleted::before { content: ""; }
.icon--zoom_in::before { content: ""; }
.icon--block::before { content: ""; }
.icon--lock::before { content: ""; }
.icon--mode_edit::before { content: ""; }
.icon--star_border::before { content: ""; }
.icon--star::before { content: ""; }
.icon--photo_camera::before { content: ""; }
.icon--account_circle::before { content: ""; }
.icon--refresh::before { content: ""; }
.icon--exit::before { content: ""; }
.icon--warning::before { content: ""; }
.icon--remove::before { content: ""; }
.icon--add::before { content: ""; }
.icon--arrow_back::before { content: ""; }
.icon--arrow_downward::before { content: ""; }
.icon--arrow_drop_down::before { content: ""; }
.icon--arrow_drop_up::before { content: ""; }
.icon--arrow_forward::before { content: ""; }
.icon--arrow_upward::before { content: ""; }
.icon--check::before { content: ""; }
.icon--checkbox::before { content: ""; }
.icon--checkbox-outline::before { content: ""; }
.icon--menu::before { content: ""; }
.icon--tune::before { content: ""; }
.icon--chevron-right-big::before { content: ""; }
.icon--chevron-left-big::before { content: ""; }
.icon--cat-all::before { content: ""; }
.icon--chevron-left::before { content: ""; }
.icon--chevron-right::before { content: ""; }
.icon--location::before { content: ""; }
.icon--send::before { content: ""; }
.icon--message::before { content: ""; }
.icon--bookmark::before { content: ""; }
.icon--android::before { content: ""; }
.icon--apple::before { content: ""; }
.icon--info::before { content: ""; }
.icon--search::before { content: ""; }
.icon--close::before { content: ""; }
.icon--close-mini::before { content: ""; }
.icon--more::before { content: ""; }
.icon--views::before { content: ""; }
.icon--target::before { content: ""; }
.icon--share-fb::before { content: ""; }
.icon--share-ok::before { content: ""; }
.icon--share-twi::before { content: ""; }
.icon--share-vk::before { content: ""; }
.icon--share-more::before { content: ""; }
.icon--share-yt::before { content: ""; }
.icon--phone::before { content: ""; }
.icon--vk::before { content: ""; }
.icon--odnoklassniki::before { content: ""; }
.icon--add_circle::before { content: ""; }
.icon--view_module::before { content: ""; }
.icon--apps::before { content: ""; }
.icon--storage::before { content: ""; }
.icon--delete::before { content: ""; }
.icon--notifications_none::before { content: ""; }
.icon--notifications_active::before { content: ""; }
.icon--notifications::before { content: ""; }
html { height: 100%; }
iframe[name="google_conversion_frame"] { position: fixed; }
* { box-sizing: border-box; }
a { transition: all 0.2s ease 0s; }
a:focus { outline: none; }
a:link, button, input, select, textarea { -webkit-tap-highlight-color: rgba(0, 0, 0, 0); }
figure { margin: 0px; }
input, input[type="search"] { -webkit-appearance: none; }
input[type="search"] { box-sizing: border-box; }
input[type="number"] { -webkit-appearance: none; }
input[type="number"]::-webkit-inner-spin-button, input[type="number"]::-webkit-outer-spin-button { margin: 0px; -webkit-appearance: none; }
._control[data-control-status="hide"] { display: none; }
.overflow-hidden { overflow: hidden !important; }
.pull-right { float: right; }
.pull-left { float: left; }
.hide { display: none !important; }
.invisible { visibility: hidden; }
.clearfix { overflow: hidden; }
.pl0 { padding-left: 0px; }
.bb0 { border-bottom: 0px !important; }
.mb0 { margin-bottom: 0px !important; }
.text-red { color: rgb(247, 80, 89); }
.subtitle { color: gray; margin: 12px 0px 30px; letter-spacing: 0px; }
.subtitle > a { text-decoration: none; color: rgb(3, 154, 211); }
.fixed_buttons { max-width: 100% !important; }
@media (max-width: 991px) {
  .fixed_buttons { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .fixed_buttons::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .fixed_buttons .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .fixed_buttons .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .fixed_buttons .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .fixed_buttons .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .fixed_buttons .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .fixed_buttons .button_container--mobile_right { float: right; }
  .fixed_buttons .button_container + .button_container { margin-top: 0px; }
  .fixed_buttons--full .button, .fixed_buttons--full .button_container, .fixed_buttons--single .button, .fixed_buttons--single .button_container { width: 100% !important; }
  .fixed_buttons--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .fixed_buttons--split .button--flat, .fixed_buttons--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .fixed_buttons--split .button--flat:hover, .fixed_buttons--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .fixed_buttons--split .button--flat:active, .fixed_buttons--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .fixed_buttons .button_container .button--default { border: 0px; }
  .fixed_buttons--full .button, .fixed_buttons--full .button_container, .fixed_buttons--single .button, .fixed_buttons--single .button_container { width: 100% !important; }
  .fixed_buttons--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .fixed_buttons--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .fixed_buttons--split .button:hover { background: rgb(245, 245, 245); }
  .fixed_buttons--split .button_container { float: left; width: 50%; }
  .fixed_buttons--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .fixed_buttons { padding-left: 6px; padding-right: 6px; }
}
.visible-small, .visible-tiny { display: none !important; }
.visible-desktop { display: inline-block !important; }
.hidden-desktop { display: none !important; }
@media (max-width: 1070px) {
  .visible-desktop { display: none !important; }
  .hidden-desktop { display: inline-block !important; }
}
@media (max-width: 991px) {
  .hidden-tablet { display: none !important; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .hidden-small { display: none !important; }
  .visible-small { display: block !important; }
  .float-right-small { float: right !important; }
}
@media (max-width: 380px) {
  .hidden-tiny { display: none !important; }
  .visible-tiny { display: block !important; }
}
@keyframes fadeIn { 
  0% { opacity: 0; }
  100% { opacity: 1; }
}
@keyframes fadeOut { 
  0% { opacity: 1; }
  100% { opacity: 0; }
}
@keyframes zoomIn { 
  0% { transform: scale(0); }
  100% { transform: scale(1); -webkit-font-smoothing: antialiased; }
}
@keyframes zoomOut { 
  0% { transform: scale(1); }
  100% { transform: scale(0); -webkit-font-smoothing: antialiased; }
}
@keyframes fadeInUp { 
  0% { opacity: 0; transform: translate3d(0px, 40px, 0px); }
  100% { opacity: 1; transform: translateZ(0px); -webkit-font-smoothing: antialiased; }
}
@keyframes fadeInUpFallback { 
  0% { visibility: hidden; top: 40px; }
  100% { visibility: visible; top: 0px; }
}
@keyframes tada { 
  0% { transform: scaleX(1); }
  10%, 20% { transform: scale3d(0.9, 0.9, 0.9) rotate(-3deg); }
  30%, 50%, 70%, 90% { transform: scale3d(1.05, 1.05, 1.05) rotate(3deg); }
  40%, 60%, 80% { transform: scale3d(1.05, 1.05, 1.05) rotate(-3deg); }
  100% { transform: scaleX(1); -webkit-font-smoothing: antialiased; }
}
@keyframes pulse { 
  0% { transform: scaleX(1); }
  50% { transform: scale3d(1.08, 1.08, 1.08); -webkit-font-smoothing: antialiased; }
  100% { transform: scaleX(1); -webkit-font-smoothing: antialiased; }
}
@keyframes showAuthBorder { 
  0% { border-color: transparent; }
  100% { border-color: rgb(222, 222, 222); }
}
.body--animations .box--show .auth_group__form .form_group, .fadeInUpFallback { position: relative; animation-name: fadeInUpFallback; animation-duration: 0.8s; }
.body--animations .box--show .auth_group__back, .body--animations .box--show .auth_group__desc, .body--animations .box--show .auth_group__form .auth_group_button, .body--animations .box--show .auth_group__form .button, .body--animations .box--show .auth_group__form .change-phone-form .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .form--invite .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .form_control:not(.form_control--select), .body--animations .box--show .auth_group__form .hint, .body--animations .box--show .auth_group__form .p_form_app .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .photo_upload_container, .body--animations .box--show .auth_group__img, .body--animations .box--show .auth_group__title, .change-phone-form .body--animations .box--show .auth_group__form .form-control:not(.form_control--select), .fadeInUp, .form--invite .body--animations .box--show .auth_group__form .form-control:not(.form_control--select), .p_form_app .body--animations .box--show .auth_group__form .form-control:not(.form_control--select) { animation-name: fadeInUp; animation-duration: 0.8s; }
.fadeIn { animation-name: fadeIn; animation-duration: 0.3s; }
.fadeIn--slow { animation-name: fadeIn; animation-duration: 1s; }
.fadeOut { animation-name: fadeOut; animation-duration: 0.3s; }
.fadeOut--slow { animation-name: fadeOut; animation-duration: 1s; }
.zoomIn { animation-name: zoomIn; }
.zoomIn, .zoomOut { animation-duration: 0.3s; }
.zoomOut { animation-name: zoomOut; }
.tada { animation-iteration-count: 2; animation-name: tada; animation-duration: 1s; animation-delay: 1s; }
.body--animations.animate_tags .tag_container .tag, .body--animations .animate_tags .tag_container .tag, .pulse { animation-iteration-count: 2; animation-name: pulse; animation-duration: 1s; animation-delay: 1s; }
.body--animations .box--show .auth_group, .showAuthBorder { animation-name: showAuthBorder; animation-duration: 1s; animation-delay: 0.5s; }
.noUi-target, .noUi-target * { touch-action: none; user-select: none; box-sizing: border-box; }
.noUi-target { position: relative; direction: ltr; }
.noUi-base { width: 100%; height: 100%; position: relative; z-index: 1; }
.noUi-origin { position: absolute; right: 0px; top: 0px; left: 0px; bottom: 0px; }
.noUi-handle { position: relative; z-index: 1; }
.noUi-stacking .noUi-handle { z-index: 10; }
.noUi-state-tap .noUi-origin { transition: left 0.3s ease 0s, top 0.3s ease 0s; }
.noUi-state-drag * { cursor: inherit !important; }
.noUi-base, .noUi-handle { transform: translateZ(0px); }
.noUi-horizontal { height: 18px; }
.noUi-horizontal .noUi-handle { width: 34px; height: 28px; left: -17px; top: -6px; }
.noUi-vertical { width: 18px; }
.noUi-vertical .noUi-handle { width: 28px; height: 34px; left: -6px; top: -17px; }
.noUi-background { background: rgb(250, 250, 250); box-shadow: rgb(240, 240, 240) 0px 1px 1px inset; }
.noUi-connect { background: rgb(63, 184, 175); box-shadow: rgba(51, 51, 51, 0.45) 0px 0px 3px inset; transition: background 0.45s ease 0s; }
.noUi-origin { border-radius: 2px; }
.noUi-target { border-radius: 4px; border: 1px solid rgb(211, 211, 211); box-shadow: rgb(240, 240, 240) 0px 1px 1px inset, rgb(187, 187, 187) 0px 3px 6px -5px; }
.noUi-target.noUi-connect { box-shadow: rgba(51, 51, 51, 0.45) 0px 0px 3px inset, rgb(187, 187, 187) 0px 3px 6px -5px; }
.noUi-handle { border: 1px solid rgb(217, 217, 217); border-radius: 3px; background: rgb(255, 255, 255); cursor: default; box-shadow: rgb(255, 255, 255) 0px 0px 1px inset, rgb(235, 235, 235) 0px 1px 7px inset, rgb(187, 187, 187) 0px 3px 6px -3px; }
.noUi-active { box-shadow: rgb(255, 255, 255) 0px 0px 1px inset, rgb(221, 221, 221) 0px 1px 7px inset, rgb(187, 187, 187) 0px 3px 6px -3px; }
.noUi-handle::after, .noUi-handle::before { content: ""; display: block; position: absolute; height: 14px; width: 1px; background: rgb(232, 231, 230); left: 14px; top: 6px; }
.noUi-handle::after { left: 17px; }
.noUi-vertical .noUi-handle::after, .noUi-vertical .noUi-handle::before { width: 14px; height: 1px; left: 6px; top: 14px; }
.noUi-vertical .noUi-handle::after { top: 17px; }
[disabled].noUi-connect, [disabled] .noUi-connect { background: rgb(184, 184, 184); }
.noUi-pips, .noUi-pips * { box-sizing: border-box; }
.noUi-pips { position: absolute; color: rgb(153, 153, 153); }
.noUi-value { position: absolute; text-align: center; }
.noUi-value-sub { color: rgb(204, 204, 204); font-size: 10px; }
.noUi-marker { position: absolute; background: rgb(204, 204, 204); }
.noUi-marker-large, .noUi-marker-sub { background: rgb(170, 170, 170); }
.noUi-pips-horizontal { padding: 10px 0px; height: 80px; top: 100%; left: 0px; width: 100%; }
.noUi-value-horizontal { transform: translate3d(-50%, 50%, 0px); }
.noUi-marker-horizontal.noUi-marker { margin-left: -1px; width: 2px; height: 5px; }
.noUi-marker-horizontal.noUi-marker-sub { height: 10px; }
.noUi-marker-horizontal.noUi-marker-large { height: 15px; }
.noUi-pips-vertical { padding: 0px 10px; height: 100%; top: 0px; left: 100%; }
.noUi-value-vertical { transform: translate3d(0px, -50%, 0px); padding-left: 25px; }
.noUi-marker-vertical.noUi-marker { width: 5px; height: 2px; margin-top: -1px; }
.noUi-marker-vertical.noUi-marker-sub { width: 10px; }
.noUi-marker-vertical.noUi-marker-large { width: 15px; }
.noUi-tooltip { display: block; position: absolute; border: 1px solid rgb(217, 217, 217); border-radius: 3px; background: rgb(255, 255, 255); padding: 5px; text-align: center; }
.noUi-horizontal .noUi-handle-lower .noUi-tooltip { top: -32px; }
.noUi-horizontal .noUi-handle-upper .noUi-tooltip { bottom: -32px; }
.noUi-vertical .noUi-handle-lower .noUi-tooltip { left: 120%; }
.noUi-vertical .noUi-handle-upper .noUi-tooltip { right: 120%; }
.Select { position: relative; }
.Select input::-webkit-contacts-auto-fill-button, .Select input::-webkit-credentials-auto-fill-button { display: none !important; }
.Select, .Select div, .Select input, .Select span { box-sizing: border-box; }
.Select.is-disabled .Select-arrow-zone { cursor: default; pointer-events: none; opacity: 0.35; }
.Select.is-disabled > .Select-control { background-color: rgb(249, 249, 249); }
.Select.is-disabled > .Select-control:hover { box-shadow: none; }
.Select.is-open > .Select-control { border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; background: rgb(255, 255, 255); border-color: rgb(179, 179, 179) rgb(204, 204, 204) rgb(217, 217, 217); }
.Select.is-open > .Select-control .Select-arrow { top: -2px; border-color: transparent transparent rgb(153, 153, 153); border-width: 0px 5px 5px; }
.Select.is-searchable.is-focused:not(.is-open) > .Select-control, .Select.is-searchable.is-open > .Select-control { cursor: text; }
.Select.is-focused > .Select-control { background: rgb(255, 255, 255); }
.Select.is-focused:not(.is-open) > .Select-control { border-color: rgb(0, 126, 255); box-shadow: rgba(0, 0, 0, 0.075) 0px 1px 1px inset, rgba(0, 126, 255, 0.1) 0px 0px 0px 3px; background: rgb(255, 255, 255); }
.Select.has-value.is-clearable.Select--single > .Select-control .Select-value { padding-right: 42px; }
.Select.has-value.is-pseudo-focused.Select--single > .Select-control .Select-value .Select-value-label, .Select.has-value.Select--single > .Select-control .Select-value .Select-value-label { color: rgb(51, 51, 51); }
.Select.has-value.is-pseudo-focused.Select--single > .Select-control .Select-value a.Select-value-label, .Select.has-value.Select--single > .Select-control .Select-value a.Select-value-label { cursor: pointer; text-decoration: none; }
.Select.has-value.is-pseudo-focused.Select--single > .Select-control .Select-value a.Select-value-label:focus, .Select.has-value.is-pseudo-focused.Select--single > .Select-control .Select-value a.Select-value-label:hover, .Select.has-value.Select--single > .Select-control .Select-value a.Select-value-label:focus, .Select.has-value.Select--single > .Select-control .Select-value a.Select-value-label:hover { color: rgb(0, 126, 255); outline: none; text-decoration: underline; }
.Select.has-value.is-pseudo-focused.Select--single > .Select-control .Select-value a.Select-value-label:focus, .Select.has-value.Select--single > .Select-control .Select-value a.Select-value-label:focus { background: rgb(255, 255, 255); }
.Select.has-value.is-pseudo-focused .Select-input { opacity: 0; }
.Select.is-open .Select-arrow, .Select .Select-arrow-zone:hover > .Select-arrow { border-top-color: rgb(102, 102, 102); }
.Select.Select--rtl { direction: rtl; text-align: right; }
.Select-control { background-color: rgb(255, 255, 255); border-radius: 4px; border: 1px solid rgb(204, 204, 204); color: rgb(51, 51, 51); cursor: default; display: table; border-spacing: 0px; border-collapse: separate; height: 36px; outline: none; overflow: hidden; position: relative; width: 100%; }
.Select-control:hover { box-shadow: rgba(0, 0, 0, 0.06) 0px 1px 0px; }
.Select-control .Select-input:focus { outline: none; background: rgb(255, 255, 255); }
.Select--single > .Select-control .Select-value, .Select-placeholder { bottom: 0px; color: rgb(170, 170, 170); left: 0px; line-height: 34px; padding-left: 10px; padding-right: 10px; position: absolute; right: 0px; top: 0px; max-width: 100%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.Select-input { height: 34px; padding-left: 10px; padding-right: 10px; vertical-align: middle; }
.Select-input > input { width: 100%; background: none transparent; border: 0px none; box-shadow: none; cursor: default; display: inline-block; font-family: inherit; font-size: inherit; margin: 0px; outline: none; line-height: 17px; padding: 8px 0px 12px; -webkit-appearance: none; }
.is-focused .Select-input > input { cursor: text; }
.has-value.is-pseudo-focused .Select-input { opacity: 0; }
.Select-control:not(.is-searchable) > .Select-input { outline: none; }
.Select-loading-zone { cursor: pointer; display: table-cell; text-align: center; }
.Select-loading, .Select-loading-zone { position: relative; vertical-align: middle; width: 16px; }
.Select-loading { animation: 0.4s linear 0s infinite normal none running Select-animation-spin; height: 16px; box-sizing: border-box; border-radius: 50%; border-width: 2px; border-style: solid; border-color: rgb(204, 204, 204) rgb(51, 51, 51) rgb(204, 204, 204) rgb(204, 204, 204); border-image: initial; display: inline-block; }
.Select-clear-zone { animation: 0.2s ease 0s 1 normal none running Select-animation-fadeIn; color: rgb(153, 153, 153); cursor: pointer; display: table-cell; position: relative; text-align: center; vertical-align: middle; width: 17px; }
.Select-clear-zone:hover { color: rgb(208, 2, 27); }
.Select-clear { display: inline-block; font-size: 18px; line-height: 1; }
.Select--multi .Select-clear-zone { width: 17px; }
.Select-arrow-zone { cursor: pointer; display: table-cell; position: relative; text-align: center; vertical-align: middle; width: 25px; }
.Select--rtl .Select-arrow-zone { padding-right: 0px; padding-left: 5px; }
.Select-arrow { border-color: rgb(153, 153, 153) transparent transparent; border-width: 5px 5px 2.5px; display: inline-block; height: 0px; width: 0px; }
.Select-control > :last-child { padding-right: 5px; }
.Select--multi .Select-multi-value-wrapper { display: inline-block; }
.Select .Select-aria-only { position: absolute; display: inline-block; height: 1px; width: 1px; margin: -1px; clip: rect(0px, 0px, 0px, 0px); overflow: hidden; float: left; }
@keyframes Select-animation-fadeIn { 
  0% { opacity: 0; }
  100% { opacity: 1; }
}
.Select-menu-outer { border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; background-color: rgb(255, 255, 255); border-width: 1px; border-style: solid; border-color: rgb(230, 230, 230) rgb(204, 204, 204) rgb(204, 204, 204); border-image: initial; box-shadow: rgba(0, 0, 0, 0.06) 0px 1px 0px; box-sizing: border-box; margin-top: -1px; max-height: 200px; position: absolute; top: 100%; width: 100%; z-index: 1; }
.Select-menu, .suggest_list { max-height: 198px; overflow-y: auto; }
.Select-option, .suggest_list__item { box-sizing: border-box; background-color: rgb(255, 255, 255); color: rgb(102, 102, 102); cursor: pointer; display: block; padding: 8px 10px; }
.Select-option:last-child, .suggest_list__item:last-child { border-bottom-right-radius: 4px; border-bottom-left-radius: 4px; }
.is-selected.suggest_list__item, .Select-option.is-selected { background-color: rgba(0, 126, 255, 0.04); color: rgb(51, 51, 51); }
.is-focused.suggest_list__item, .Select-option.is-focused { background-color: rgba(0, 126, 255, 0.08); color: rgb(51, 51, 51); }
.is-disabled.suggest_list__item, .Select-option.is-disabled { color: rgb(204, 204, 204); }
.Select-noresults { box-sizing: border-box; color: rgb(153, 153, 153); cursor: default; display: block; padding: 8px 10px; }
.Select--multi .Select-input { vertical-align: middle; margin-left: 10px; padding: 0px; }
.Select--multi.Select--rtl .Select-input { margin-left: 0px; margin-right: 10px; }
.Select--multi.has-value .Select-input { margin-left: 5px; }
.Select--multi .Select-value { background-color: rgba(0, 126, 255, 0.08); border-radius: 2px; border: 1px solid rgba(0, 126, 255, 0.24); color: rgb(0, 126, 255); display: inline-block; font-size: 0.9em; line-height: 1.4; margin-left: 5px; margin-top: 5px; vertical-align: top; }
.Select--multi .Select-value-icon, .Select--multi .Select-value-label { display: inline-block; vertical-align: middle; }
.Select--multi .Select-value-label { border-bottom-right-radius: 2px; border-top-right-radius: 2px; cursor: default; padding: 2px 5px; }
.Select--multi a.Select-value-label { color: rgb(0, 126, 255); cursor: pointer; text-decoration: none; }
.Select--multi a.Select-value-label:hover { text-decoration: underline; }
.Select--multi .Select-value-icon { cursor: pointer; border-bottom-left-radius: 2px; border-top-left-radius: 2px; border-right: 1px solid rgba(0, 126, 255, 0.24); padding: 1px 5px 3px; }
.Select--multi .Select-value-icon:focus, .Select--multi .Select-value-icon:hover { background-color: rgba(0, 113, 230, 0.08); color: rgb(0, 113, 230); }
.Select--multi .Select-value-icon:active { background-color: rgba(0, 126, 255, 0.24); }
.Select--multi.Select--rtl .Select-value { margin-left: 0px; margin-right: 5px; }
.Select--multi.Select--rtl .Select-value-icon { border-right: none; border-left: 1px solid rgba(0, 126, 255, 0.24); }
.Select--multi.is-disabled .Select-value { background-color: rgb(252, 252, 252); border: 1px solid rgb(227, 227, 227); color: rgb(51, 51, 51); }
.Select--multi.is-disabled .Select-value-icon { cursor: not-allowed; border-right: 1px solid rgb(227, 227, 227); }
.Select--multi.is-disabled .Select-value-icon:active, .Select--multi.is-disabled .Select-value-icon:focus, .Select--multi.is-disabled .Select-value-icon:hover { background-color: rgb(252, 252, 252); }
@keyframes Select-animation-spin { 
  100% { transform: rotate(1turn); }
}
.Select { position: static; }
.Select.is-focused .Select-control { box-shadow: rgba(0, 0, 0, 0.24) 0px 2px 2px 0px, rgba(0, 0, 0, 0.12) 0px 0px 2px 0px; outline: none; }
.Select--single .suggest_list { top: -8px; margin-top: 0px; }
.Select-value-label { color: rgb(57, 57, 57); }
.Select-clear-zone { right: 20px; top: 50%; margin-top: -12px; }
.Select-arrow-zone { padding-right: 5px; padding-left: 5px; text-align: right; }
.Select-arrow { border-color: gray transparent transparent; border-style: solid; border-width: 5px 5px 2px; position: relative; }
.is-open .Select-arrow, .Select-arrow-zone:hover > .Select-arrow { border-top-color: gray; }
.Select-control { position: static; padding: 9px 11px 11px; border-color: rgb(235, 235, 235); border-radius: 2px; transition: background 0.2s ease 0s; }
.Select-control:hover { box-shadow: none; background: rgb(245, 245, 245); }
.Select-noresults { font-size: 14px; }
.Select-menu-outer { width: 246px; max-width: none; margin-top: 6px; border-radius: 0px; box-shadow: rgba(0, 0, 0, 0.24) 0px 2px 2px 0px, rgba(0, 0, 0, 0.12) 0px 0px 2px 0px; z-index: 40; overflow: auto; left: 0px; height: auto; max-height: none; border: 0px !important; background-color: rgb(255, 255, 255) !important; }
.Select-value { line-height: 40px; padding-left: 20px; padding-right: 40px; }
.Select-menu, .suggest_list { font-size: 14px; line-height: 1.3; color: rgb(57, 57, 57); padding: 6px 0px; width: 100%; border: 0px; border-radius: 0px; max-height: 100%; height: 100%; background-color: rgb(255, 255, 255) !important; }
.Select-option, .suggest_list__item { padding: 11px 24px; color: rgb(57, 57, 57); position: relative; border-radius: 0px !important; }
.is-focused.suggest_list__item, .Select-option.is-focused, .Select-option:hover, .suggest_list__item:hover { color: rgb(51, 51, 51); background-color: rgb(245, 245, 245) !important; }
.Select-option:hover .tooltip, .suggest_list__item:hover .tooltip { opacity: 1; visibility: visible; }
.Select-option .tooltip, .suggest_list__item .tooltip { top: 100%; left: 50%; opacity: 0; margin-left: -104px; visibility: hidden; transition: opacity 0.2s ease 0s, visibility 0.2s ease 0s; }
.is-disabled.suggest_list__item, .Select-option.is-disabled { color: rgba(57, 57, 57, 0.3); cursor: default; }
.is-selected.suggest_list__item, .Select-option.is-selected { color: rgb(3, 154, 211); background: transparent !important; }
.is-selected.suggest_list__item::after, .Select-option.is-selected::after { position: absolute; content: ""; color: rgb(3, 154, 211); right: 8px; left: auto; font-size: 18px; width: auto; height: auto; top: 9px; font-family: icons !important; }
.select_simple .Select-control { padding: 3px 20px; position: relative; }
.select_simple .Select-menu-outer { width: 100%; max-height: 220px; }
.select_simple .Select-input { padding: 0px; width: 100%; font-size: 14px; }
.select_simple .Select-option, .select_simple .suggest_list__item { font-size: 14px; padding-left: 20px; padding-right: 20px; }
.select_simple .Select-noresults { padding-left: 20px; padding-right: 20px; }
@media (max-width: 767px) {
  .Select.is-open .Select-arrow { top: -3px; transform: rotate(180deg); }
  .Select .Select-value-label { left: 50px; top: 12px !important; }
  .Select .Select-loading-zone { right: -25px !important; }
}
@media (max-width: 991px) {
  .Select-menu-outer { margin-top: 0px; border-radius: 0px; box-shadow: rgba(0, 0, 0, 0.24) 0px 2px 2px 0px; border-top: 1px solid rgb(234, 234, 234) !important; }
  .Select-option, .suggest_list__item { padding-left: 12px; padding-right: 12px; }
  .Select-control { padding-top: 10px; }
}
.noUi-horizontal { height: 10px; }
.noUi-horizontal .noUi-handle { width: 12px; height: 12px; left: -6px; top: -1px; }
.noUi-background { box-shadow: none; background: none; }
.noUi-background::after { width: 100%; height: 0px; top: 4px; left: 0px; position: absolute; content: ""; border-bottom: 2px solid rgb(204, 204, 204); }
.noUi-connect { background: none; box-shadow: none; }
.noUi-connect::after { width: 100%; height: 0px; top: 4px; left: 0px; position: absolute; content: ""; border-bottom: 2px solid rgb(3, 154, 211); }
.noUi-origin { border-radius: 0px; }
.noUi-target { border-radius: 0px; border: none; }
.noUi-target, .noUi-target.noUi-connect { box-shadow: none; }
.noUi-handle::after, .noUi-handle::before { display: none; }
.noUi-draggable { cursor: w-resize; }
.noUi-vertical .noUi-draggable { cursor: n-resize; }
.noUi-handle { box-shadow: none; border: 1px solid rgb(3, 154, 211); border-radius: 100%; background: rgb(3, 154, 211); cursor: -webkit-grab; }
.noUi-handle:active { cursor: -webkit-grabbing; }
[disabled].noUi-connect, [disabled] .noUi-connect { background: rgb(184, 184, 184); }
[disabled] .noUi-handle, [disabled].noUi-origin { cursor: not-allowed; }
[disabled] .noUi-handle { border: 1px solid rgb(215, 215, 215); background: rgb(215, 215, 215); }
.body--is_mobile .control_range .noUi-handle { width: 20px; height: 20px; left: -10px; top: -5px; }
.react-tel-input .flag-dropdown { display: none !important; }
.react-tel-input--error .form-control { box-shadow: rgb(255, 147, 147) 0px 0px 0px 1px inset; border-color: rgb(255, 147, 147) !important; }
.visually-hidden { position: absolute; clip: rect(0px, 0px, 0px, 0px); }
div.awesomplete { z-index: 40; display: block; position: relative; }
div.awesomplete [hidden] { display: none; }
div.awesomplete mark { background: inherit; }
div.awesomplete > input { display: block; }
div.awesomplete > ul { left: 0px; z-index: 1; min-width: 100%; list-style: none; padding: 8px 0px; margin: 4px 0px 0px; box-shadow: none; text-shadow: none; position: absolute; box-sizing: border-box; border-radius: 2px; background-color: rgb(255, 255, 255); border: 1px solid rgb(235, 235, 235); font-size: 14px; color: rgb(57, 57, 57); }
div.awesomplete > ul.safari_fix { max-height: 150px; overflow: scroll; }
div.awesomplete > ul:empty, div.awesomplete > ul[hidden] { display: none; }
div.awesomplete > ul > li { cursor: pointer; position: relative; padding: 8px 20px; }
div.awesomplete > ul > li:hover { color: rgb(51, 51, 51); background: rgb(245, 245, 245); }
div.awesomplete > ul > li:hover mark { background: transparent; }
div.awesomplete > ul > li[aria-selected="true"] { color: rgb(255, 255, 255); background: rgb(3, 154, 211); }
div.awesomplete > ul > li[aria-selected="true"] mark { color: inherit; background: inherit; }
.app { min-height: 100%; }
.body--overflow_hidden .app, .body--overflow_hidden_modal .app { overflow: hidden; }
.app--simple_layout .header { border-bottom: 1px solid rgb(235, 235, 235); }
.app--lk { min-width: 700px; }
.app--lk .header_bar__logo { float: left; }
.app--lk .header__bar { overflow: hidden; }
.app--hidden { display: none; }
.body { background-color: rgb(255, 255, 255); min-width: 310px; min-height: 100%; position: relative; font-size: 14px; color: rgb(57, 57, 57); -webkit-font-smoothing: antialiased; padding-bottom: 250px; }
.body.route__product_limits_buy { padding-bottom: 0px; }
.body--disable_scroll, .body--overflow_hidden, .body--overflow_hidden_modal { overflow: hidden; }
.body--padding_none { padding: 0px !important; }
.body--promo .bundle { margin-top: 0px !important; padding: 0px !important; }
.body--im .bundle { margin-top: 0px; }
.body--im .header { border-bottom: 0px; }
.body--im.body--overflow_hidden { padding-bottom: 0px; }
.body--im.body--is_mobile .header--fixed { position: relative; }
.body--payments, .body--product_create { padding-bottom: 20px; }
.body--payments .bundle, .body--product_create .bundle { padding-top: 0px; }
@media (min-width: 992px) {
  .body { padding-bottom: 365px; }
  .body.route__product_limits_buy { padding-bottom: 0px; }
  .body--no_padding { padding-bottom: 116px; }
  .body--payments, .body--product_create { padding-bottom: 20px; }
}
.body--fixfixed .fixfixed { position: absolute; }
.body--header_minimal { background: rgb(255, 255, 255); }
.body--header_minimal .header_bar__logout { display: none !important; }
.body--header_minimal .header { box-shadow: none; }
@media (max-width: 991px) {
  .body--payments .header--fixed ~ .bundle, .body--product_create .header--fixed ~ .bundle { padding-top: 58px; }
  .body--im .header { position: absolute; width: 100%; top: 0px; left: 0px; box-shadow: none; }
  .body--im .header:not(.header--menu_opened) { display: none !important; }
  .body--im .bundle, .body--im .bundle .container { padding: 0px !important; }
  .body--im.body--is_mobile .header--fixed { position: absolute; }
  .body--im, .body--im .app, .body--im .base, .body--im .bundle, .body--im .container, .body--im .container[data-container="IMContainer"] .row, .body--im .container[data-container="IMContainer"] .row > *, .body--im .im_container { height: 100%; }
  .body--order { background-color: rgb(249, 249, 249); padding-bottom: 0px !important; }
  .body--order .footer { display: none !important; }
  .body--order .bundle { padding-top: 0px; }
  .body--order .header--fixed ~ .bundle { padding-top: 58px; }
  .body--settings { background-color: rgb(249, 249, 249); }
  .body--settings .footer { display: none !important; }
  .body--settings .header { position: relative; }
  .body--settings .header ~ .bundle { padding-top: 15px; }
  .body--prevent_fixed_scroll { overflow: auto !important; height: 100% !important; }
}
.route__product_limits_buy .header { border-bottom: 1px solid rgb(235, 235, 235); }
@media (max-width: 991px) {
  .route__user_limits .header_bar__control--accoun, .route__user_limits .header_bar__control--menu, .route__user_limits .header_bar__logo, .route__user_limits .profile.sidebar_container { display: none !important; }
}
.main__title { margin-bottom: 15px; margin-top: 6px; }
@media (max-width: 767px) {
  .main__title { display: none; }
}
.header, .header_prototype { z-index: 102; position: relative; }
@media (min-width: 992px) {
  .header_prototype { height: 96px; }
}
@media (min-width: 992px) {
  .header_prototype.tiny { height: 64px; }
}
.header_prototype--board a, .header_prototype--board button, .header_prototype--board input, .header_prototype--board textarea { -webkit-tap-highlight-color: transparent; outline: none; }
.header_prototype--board section { max-width: 1170px; }
.header { background-color: rgb(255, 255, 255); }
@media (min-width: 992px) {
  .header { display: none !important; }
}
@media (max-width: 991px) {
  .header { border-bottom: 1px solid rgb(235, 235, 235); }
  .header .header_bar__location { display: none !important; }
  .header--absolute, .header--fixed { top: 0px; left: 0px; right: 0px; z-index: 120; transition: transform 0.2s ease-in-out 0s; transform: translate(0px); }
  .header--absolute.header--hidden, .header--fixed.header--hidden { transform: translateY(-58px); }
  .header--absolute.header--disable_transition, .header--fixed.header--disable_transition { transition: none 0s ease 0s; }
  .header--absolute { position: absolute; }
  .header--fixed { position: fixed; }
  .header--menu_opened { transition: none 0s ease 0s !important; transform: none !important; }
}
@media (max-width: 767px) {
  .header { z-index: 102; }
  .header--show_search { z-index: 121; }
  .header--fixed.header--hidden.header--show_search_result { transform: translateY(-104px); }
}
.header_bar { position: relative; }
.header_bar .button .icon--format_list_bulleted { font-size: 22px; }
.header_bar__logo { font-size: 0px; line-height: 0; padding-top: 18px; padding-bottom: 18px; position: relative; opacity: 1; transform: scale(1); }
.header_bar__logo a { display: inline-block; transition: none 0s ease 0s; }
.header_bar__login-small { cursor: pointer; display: block; margin-left: 0px; width: auto; padding-left: 20px; position: relative; -webkit-tap-highlight-color: transparent; }
.header_bar__login-small .user__image, .header_bar__login-small .user__image--empty { width: 24px; height: 24px; z-index: 0; margin-top: 0px; margin-bottom: 0px; }
.header_bar__login-small .user__image--empty img, .header_bar__login-small .user__image img { width: inherit; height: inherit; }
.header_bar__login { cursor: pointer; display: block; margin-left: 0px; width: auto; min-width: 52px; padding-left: 20px; float: right; position: relative; -webkit-tap-highlight-color: transparent; user-select: none; }
.header_bar__login.not-logged { min-width: 100px; }
.header_bar__login .button.button--bordered { padding: 5px 10px 6px; width: 100%; }
.header_bar__login .user__image, .header_bar__login .user__image--empty { width: 32px; height: 32px; margin: 0px 0px -2px; display: block; float: right; overflow: hidden; position: relative; }
.header_bar__login .user__image--empty::after, .header_bar__login .user__image::after { content: ""; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background: transparent; transition: background 0.2s ease 0s; }
.header_bar__login .user__image--empty:hover::after, .header_bar__login .user__image:hover::after { background: rgba(0, 0, 0, 0.16); }
.header_bar__login .user__image--empty img, .header_bar__login .user__image img { width: 100%; height: 100%; }
.header_bar__login .user__image--empty .icon--arrow_drop_down, .header_bar__login .user__image .icon--arrow_drop_down { top: 50%; left: 100%; margin: -12px -10px 0px 0px; position: absolute; color: rgb(143, 143, 143); font-size: 22px; transition: transform 0.2s ease 0s, color 0.2s ease 0s; }
.header--menu_opened .header_bar__login .user__image--empty .icon--arrow_drop_down, .header--menu_opened .header_bar__login .user__image .icon--arrow_drop_down { transform: rotateX(180deg) translateY(-1px); }
.header_bar__logout { position: absolute; right: -8px; top: 50%; margin-top: -21px; }
.header_bar__logout .icon { transition: color 0.2s ease 0s; }
.header_bar__logout:hover .icon { color: rgb(57, 57, 57); }
.header_bar__messages { float: left; position: relative; top: -1px; height: 36px; width: 36px; margin: -1px 0px -1px -6px; transition: background 0.2s ease 0s; padding: 5px !important; }
.header_bar__messages .icon { position: relative; left: 0.4px; top: 0.8px; font-size: 24px; line-height: 16px; color: rgb(143, 143, 143); }
.header_bar__messages .badge { right: 0px !important; top: 2px !important; }
.header_bar__messages:hover { background: rgb(245, 245, 245) !important; }
.header_bar__title { position: absolute; left: 0px; right: 0px; top: 0px; height: 100%; text-align: center; font-size: 20px; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; font-weight: 500; color: rgb(57, 57, 57); text-decoration: none; line-height: normal; background: rgb(255, 255, 255); padding: 18px 44px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: none !important; }
.header_bar__title--about { font-size: 16px; padding: 17px 0px; }
.header_bar__search, .header_search_clone { font-size: 0px; }
.header_bar__search .form_group, .header_search_clone .form_group { display: inline-block; vertical-align: middle; margin-bottom: 0px; }
.change-phone-form .header_bar__search .form-control, .change-phone-form .header_search_clone .form-control, .form--invite .header_bar__search .form-control, .form--invite .header_search_clone .form-control, .header_bar__search .change-phone-form .form-control, .header_bar__search .form--invite .form-control, .header_bar__search .form_control, .header_bar__search .p_form_app .form-control, .header_search_clone .change-phone-form .form-control, .header_search_clone .form--invite .form-control, .header_search_clone .form_control, .header_search_clone .p_form_app .form-control, .p_form_app .header_bar__search .form-control, .p_form_app .header_search_clone .form-control { width: 280px; height: 32px; padding: 5px 40px 7px 20px; background: rgb(255, 255, 255); border-color: rgb(3, 154, 211); border-radius: 4px 0px 0px 4px; }
.change-phone-form .header_bar__search .form-control[aria-expanded="false"], .change-phone-form .header_search_clone .form-control[aria-expanded="false"], .form--invite .header_bar__search .form-control[aria-expanded="false"], .form--invite .header_search_clone .form-control[aria-expanded="false"], .header_bar__search .change-phone-form .form-control[aria-expanded="false"], .header_bar__search .form--invite .form-control[aria-expanded="false"], .header_bar__search .form_control[aria-expanded="false"], .header_bar__search .p_form_app .form-control[aria-expanded="false"], .header_search_clone .change-phone-form .form-control[aria-expanded="false"], .header_search_clone .form--invite .form-control[aria-expanded="false"], .header_search_clone .form_control[aria-expanded="false"], .header_search_clone .p_form_app .form-control[aria-expanded="false"], .p_form_app .header_bar__search .form-control[aria-expanded="false"], .p_form_app .header_search_clone .form-control[aria-expanded="false"] { padding-right: 10px; }
.header_bar__search .button--back, .header_search_clone .button--back { display: none; }
.header_bar__buttons { float: right; width: auto; position: relative; z-index: 1; }
.header_bar__add { float: left; }
.header_bar__add .icon { display: inline-block; }
.header_bar__add span { position: relative; }
@media (min-width: 992px) {
  .header_bar__add .button { font-size: 14px; height: 32px; padding-top: 6px; letter-spacing: 0px; }
  .header_bar__add .button .icon { margin: -6px 0px -3px -13px; position: relative; top: -1px; }
}
@media (max-width: 1180px) {
  .header_bar__add .hide_span { display: none; }
}
@media (max-width: 991px) {
  .header_bar__add { width: 30px; margin-top: 1px; }
  .header_bar__add .button { width: 100%; padding: 3px 4px 7px; border-radius: 100px; box-shadow: none; transition: background 0.2s ease 0s; }
}
.header_bar__location .visible-desktop { display: inline !important; }
@media (max-width: 1070px) {
  .header_bar__location .visible-desktop { display: none !important; }
  .header_bar__location .hidden-desktop { display: inline !important; }
}
.header_bar__inner { padding-top: 19px; padding-bottom: 19px; }
.header_bar .form { position: relative; }
.header_bar .form .button_search { border-radius: 0px 4px 4px 0px; position: absolute; top: 0px; right: -1px; z-index: 40; width: 80px; height: 32px; }
.change-phone-form .header_bar .form-control, .form--invite .header_bar .form-control, .header_bar .change-phone-form .form-control, .header_bar .form--invite .form-control, .header_bar .form_control, .header_bar .p_form_app .form-control, .p_form_app .header_bar .form-control { width: 100%; border-right: 0px rgb(235, 235, 235); border-radius: 4px 0px 0px 4px; border-top-color: rgb(235, 235, 235); border-bottom-color: rgb(235, 235, 235); border-left-color: rgb(235, 235, 235); height: 32px; }
.header_bar .form_group, .header_bar .input_group { width: 100%; position: relative; }
.header_bar .input_group { float: left; padding-right: 40px; }
.header_bar .input_group .button--delete { right: 14px; }
.change-phone-form .header_bar .input_group .button--delete.hide + .awesomplete .form-control, .form--invite .header_bar .input_group .button--delete.hide + .awesomplete .form-control, .header_bar .input_group .button--delete.hide + .awesomplete .change-phone-form .form-control, .header_bar .input_group .button--delete.hide + .awesomplete .form--invite .form-control, .header_bar .input_group .button--delete.hide + .awesomplete .form_control, .header_bar .input_group .button--delete.hide + .awesomplete .p_form_app .form-control, .p_form_app .header_bar .input_group .button--delete.hide + .awesomplete .form-control { padding-right: 20px; }
.header_bar .input_group--search .button--delete { right: 48px; position: absolute; top: 50%; margin-top: -14px; }
.header_bar .input_group--search .fake_search_input { visibility: hidden; position: absolute; font-size: 14px; }
.change-phone-form .header_bar .input_group--search .form-control, .form--invite .header_bar .input_group--search .form-control, .header_bar .input_group--search .change-phone-form .form-control, .header_bar .input_group--search .form--invite .form-control, .header_bar .input_group--search .form_control, .header_bar .input_group--search .p_form_app .form-control, .p_form_app .header_bar .input_group--search .form-control { transition: border-color 0.2s ease 0s; }
.change-phone-form .header_bar .input_group--search_focused .form-control, .form--invite .header_bar .input_group--search_focused .form-control, .header_bar .input_group--search_focused .change-phone-form .form-control, .header_bar .input_group--search_focused .form--invite .form-control, .header_bar .input_group--search_focused .form_control, .header_bar .input_group--search_focused .p_form_app .form-control, .p_form_app .header_bar .input_group--search_focused .form-control { border-color: rgb(3, 154, 211); }
.header_bar .dropdown_control .dropdown_menu { margin-top: 22px; right: 0px; }
@media (max-width: 991px) {
  .header_bar__logo { padding-top: 12px; padding-bottom: 12px; width: auto; float: none; text-align: center; padding-left: 50px !important; padding-right: 50px !important; }
  .header_bar__control { white-space: nowrap; z-index: 1; width: 46px; padding: 10px 5px 6px !important; }
  .header_bar__control--account { margin-right: 5px; }
  .header_bar__inner { padding: 12px 15px; }
  .header_bar__buttons { display: none !important; }
  .header_bar__search, .header_search_clone { padding-right: 0px; position: absolute; top: 100%; padding-top: 10px; padding-bottom: 10px; left: 0px; right: 0px; width: auto; background: rgb(249, 249, 249); display: none !important; }
  .header_bar__search .form_group, .header_search_clone .form_group { padding-right: 0px; }
  .change-phone-form .header_bar__search .form-control, .change-phone-form .header_search_clone .form-control, .form--invite .header_bar__search .form-control, .form--invite .header_search_clone .form-control, .header_bar__search .change-phone-form .form-control, .header_bar__search .form--invite .form-control, .header_bar__search .form_control, .header_bar__search .p_form_app .form-control, .header_search_clone .change-phone-form .form-control, .header_search_clone .form--invite .form-control, .header_search_clone .form_control, .header_search_clone .p_form_app .form-control, .p_form_app .header_bar__search .form-control, .p_form_app .header_search_clone .form-control { padding-left: 40px; border-radius: 2px; background-color: rgba(214, 214, 214, 0.3); }
  .header_bar__search .input_group, .header_search_clone .input_group { width: 100%; float: none; }
  .header_bar__search .form, .header_search_clone .form { position: relative; }
  .header_bar__search .form .button_search, .header_search_clone .form .button_search { position: absolute; left: 0px; right: auto; background: transparent !important; }
  .header_bar__search .form .button_search .icon, .header_search_clone .form .button_search .icon { color: rgb(143, 143, 143) !important; }
  .header_bar__search--show { position: absolute; z-index: 121; padding: 0px; top: 0px; left: 0px; right: 0px; width: auto; height: 100%; background: rgb(255, 255, 255); display: block; }
  .header_bar__search--show .button--delete { margin-top: -13px; }
  .header_bar__search--show .button--back { margin-top: -21px; margin-left: -10px; display: block; }
  .change-phone-form .header_bar__search--show .form-control, .form--invite .header_bar__search--show .form-control, .header_bar__search--show .change-phone-form .form-control, .header_bar__search--show .form, .header_bar__search--show .form--invite .form-control, .header_bar__search--show .form_control, .header_bar__search--show .form_group, .header_bar__search--show .p_form_app .form-control, .p_form_app .header_bar__search--show .form-control { width: 100%; height: 100%; display: block; }
  .change-phone-form .header_bar__search--show .form-control, .form--invite .header_bar__search--show .form-control, .header_bar__search--show .change-phone-form .form-control, .header_bar__search--show .form--invite .form-control, .header_bar__search--show .form_control, .header_bar__search--show .p_form_app .form-control, .p_form_app .header_bar__search--show .form-control { padding: 15px 45px 17px 55px; font-size: 16px; background: rgb(255, 255, 255); border: 0px; }
  .header_bar__search--show_result { display: block; width: 100%; height: 47px; border-top: 1px solid rgb(234, 234, 234); position: relative; }
  .change-phone-form .header_bar__search--show_result .form-control, .form--invite .header_bar__search--show_result .form-control, .header_bar__search--show_result .change-phone-form .form-control, .header_bar__search--show_result .form--invite .form-control, .header_bar__search--show_result .form_control, .header_bar__search--show_result .p_form_app .form-control, .p_form_app .header_bar__search--show_result .form-control { font-size: 16px; background: rgb(255, 255, 255); padding-left: 50px; border: 0px; }
  .header_bar__search--show_result .icon--close-mini::before { content: ""; }
  .header_bar__inner { position: static; padding-top: 0px; padding-bottom: 0px; }
  .header_bar__title { display: block !important; }
}
@media (min-width: 992px) {
  .header_bar__logo { width: 120px; z-index: 2; }
  .header_bar__inner { float: none; width: auto; padding-left: 136px; padding-right: 15px; height: 72px; }
  .header_bar__inner .row::after, .header_bar__inner .row::before { display: none; }
  .header_bar .input_group { padding-right: 79px; }
  .header_bar .input_group--search::before { content: ""; position: absolute; z-index: 41; left: 10px; top: 8px; font-size: 22px; line-height: 16px; color: rgb(143, 143, 143); visibility: visible; font-family: icons !important; }
  .change-phone-form .header_bar .input_group--search .form-control::-webkit-input-placeholder, .form--invite .header_bar .input_group--search .form-control::-webkit-input-placeholder, .header_bar .input_group--search .change-phone-form .form-control::-webkit-input-placeholder, .header_bar .input_group--search .form--invite .form-control::-webkit-input-placeholder, .header_bar .input_group--search .form_control::-webkit-input-placeholder, .header_bar .input_group--search .p_form_app .form-control::-webkit-input-placeholder, .p_form_app .header_bar .input_group--search .form-control::-webkit-input-placeholder { color: rgb(143, 143, 143) !important; }
  .header_bar .input_group--search .button--delete { right: 80px; }
  .change-phone-form .header_bar .input_group--search.input_group--focus .form-control, .form--invite .header_bar .input_group--search.input_group--focus .form-control, .header_bar .input_group--search.input_group--focus .change-phone-form .form-control, .header_bar .input_group--search.input_group--focus .form--invite .form-control, .header_bar .input_group--search.input_group--focus .form_control, .header_bar .input_group--search.input_group--focus .p_form_app .form-control, .p_form_app .header_bar .input_group--search.input_group--focus .form-control { padding-left: 20px; }
  .change-phone-form .header_bar .input_group--search.input_group--focus .form-control::-webkit-input-placeholder, .form--invite .header_bar .input_group--search.input_group--focus .form-control::-webkit-input-placeholder, .header_bar .input_group--search.input_group--focus .change-phone-form .form-control::-webkit-input-placeholder, .header_bar .input_group--search.input_group--focus .form--invite .form-control::-webkit-input-placeholder, .header_bar .input_group--search.input_group--focus .form_control::-webkit-input-placeholder, .header_bar .input_group--search.input_group--focus .p_form_app .form-control::-webkit-input-placeholder, .p_form_app .header_bar .input_group--search.input_group--focus .form-control::-webkit-input-placeholder { color: transparent !important; }
  .header_bar .input_group--search.input_group--focus::before { visibility: hidden; }
  .change-phone-form .header_bar .form-control, .form--invite .header_bar .form-control, .header_bar .change-phone-form .form-control, .header_bar .form--invite .form-control, .header_bar .form_control, .header_bar .p_form_app .form-control, .p_form_app .header_bar .form-control { padding-left: 40px; }
  .header_bar__search, .header_search_clone { width: 45%; }
  .header_bar__inner .col-sm-7 { width: 55%; }
}
@media (max-width: 767px) {
  .header_bar__control--account { margin-right: 0px; }
}
.fixed_location { top: -40px; z-index: 39; transform: translateY(10px); display: none !important; }
.fixed_location .button, .fixed_location .icon { color: rgb(143, 143, 143); }
.fixed_location--fixed { transition: transform 0.2s ease-in-out 0s; transform: translateY(68px); position: fixed !important; top: 0px !important; }
.header--fixed.header--hidden ~ .fixed_location--fixed { transform: translateY(10px); }
@media (max-width: 991px) {
  .fixed_location { display: block !important; }
}
@media (max-width: 767px) {
  .header.header--show_search_result ~ .fixed_location--fixed { transform: translateY(114px); }
  .header--fixed.header--hidden ~ .fixed_location--fixed { transform: translateY(10px); }
}
.fixed_button { display: none; position: fixed; right: 15px; bottom: 15px; z-index: 101; padding: 0px; width: 56px; height: 56px; transition: transform 0.2s ease-in-out 0s; transform: translate(0px); }
@media (max-width: 991px) {
  .fixed_button { display: flex; align-items: center; justify-content: center; }
  .fixed_button--hidden { transform: translateY(80px); }
}
@media (max-width: 991px) {
  .fixed_button { right: 0px; left: 0px; margin-left: auto; margin-right: auto; bottom: 8px; width: 240px; }
  .fixed_button a, .fixed_button button { border-radius: 20px; }
}
.header_search_clone { z-index: 40; top: 0px; display: none !important; }
.bundle { position: relative; padding: 20px 0px; }
@media (min-width: 992px) {
  .bundle { padding: 0px; margin-top: 30px; }
}
@media (max-width: 991px) {
  .header--absolute ~ .bundle, .header--fixed ~ .bundle { padding-top: 80px; }
  .header--absolute ~ .bundle .header_search_clone, .header--fixed ~ .bundle .header_search_clone { top: 58px; }
}
@media (max-width: 767px) {
  .header--fixed.header--show_search_result ~ .bundle { padding-top: 124px; }
}
.badbrowser { padding: 12% 0px 0px; text-align: center; }
.badbrowser__title { font-size: 20px; line-height: 22px; font-weight: 400; color: rgb(57, 57, 57); margin-bottom: 12px; }
.badbrowser p { color: rgba(0, 0, 0, 0.5); line-height: 1.52; font-size: 13px; }
.badbrowser_list { list-style: none; padding: 0px; margin: 25px 0px; font-size: 0px; }
.badbrowser_list__item { display: inline-block; vertical-align: top; font-size: 14px; }
.badbrowser_list__item a { display: block; padding: 16px 20px; border-radius: 2px; color: rgba(0, 0, 0, 0.6); text-decoration: none !important; }
.badbrowser_list__item a:hover { background-color: rgb(234, 234, 234); }
.badbrowser_icon { font-size: 0px; line-height: 0; margin-bottom: 10px; }
.logo { background: url("/build/images/logo.315c1c.svg") 0px center / 92px 34px no-repeat, linear-gradient(transparent, transparent); width: 92px; height: 34px; display: block; transform: translateY(-4px); }
@media (max-width: 991px) {
  .logo { width: 81px; height: 28px; transform: translateY(0px); background-size: 81px 28px; }
}
.footer { padding-bottom: 3px; position: absolute; bottom: 0px; left: 0px; width: 100%; }
.footer__wrap { position: relative; }
.footer__legal { margin-top: 20px; padding: 18px 0px; color: rgb(155, 155, 155); font-size: 12px; line-height: 16px; border-top: 1px solid rgb(234, 234, 234); overflow: hidden; }
.footer__links { margin-top: -2px; margin-bottom: -4px; float: right; }
.footer__links .icon { font-size: 24px; display: inline-block; vertical-align: middle; transition: color 0.2s ease 0s; }
.footer__links a { color: rgb(155, 155, 155); text-decoration: none; transition: none 0s ease 0s; }
.footer__links a:hover .icon--apple { color: rgb(0, 0, 0); }
.footer__links a:hover .icon--android { color: rgb(89, 168, 64); }
.footer__links a, .footer__links span { display: inline-block; vertical-align: middle; padding: 0px 3px; }
.footer__copyright { font-size: 100%; float: left; margin-right: 44px; }
.footer__navigation { float: left; font-size: 0px; }
.footer__navigation__item { color: gray; text-decoration: none; margin-right: 11px; font-size: 12px; line-height: 16px; cursor: pointer; }
.footer__navigation__item:hover { color: rgb(3, 154, 211); }
.footer__navigation__item--button { border: 0px; padding: 0px; background-color: transparent; text-decoration: none; display: inline-block; white-space: nowrap; }
@media (max-width: 991px) {
  .footer { padding-bottom: 50px; }
  .footer__legal { margin-top: 20px; padding: 16px 0px 0px; font-size: 14px; }
  .footer__copyright { color: rgb(57, 57, 57); display: block; margin-bottom: 12px; float: none; }
  .footer__navigation { width: 156px; float: none; }
  .footer__navigation__item { font-size: 14px; margin-bottom: 4px; display: inline-block; }
  .footer__links { float: none; border-top: 1px solid rgb(234, 234, 234); margin-top: 12px; overflow: hidden; }
  .footer__links a { padding: 8px 0px 11px; width: 140px; float: left; white-space: nowrap; margin-left: 0px !important; }
  .footer__links a + a { margin-left: 40px; }
  .footer__links span { display: block; }
  .footer__links .icon { font-size: 30px; }
  .footer__links .icon--apple { color: rgb(0, 0, 0); }
  .footer__links .icon--android { color: rgb(89, 168, 64); }
  .footer .cities_list_all { position: absolute; right: 15px; top: 35px; }
}
@media (max-width: 767px) {
  .footer .cities_list_all { right: 10px; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .footer__links a { width: 50%; }
}
.button { border: 0px; border-radius: 4px; background-color: transparent; color: rgb(74, 74, 74); text-decoration: none; display: inline-block; vertical-align: middle; font-size: 14px; letter-spacing: 0.5px; text-transform: uppercase; font-weight: 600; font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; text-align: center; padding: 10px 16px 11px; white-space: nowrap; cursor: pointer; transition: all 0.2s ease 0s; }
.button:focus { outline: none; }
.button:hover { background: rgb(245, 245, 245); }
.button:active { background: rgb(237, 237, 237); }
.button .icon { margin-right: 2px; font-size: 22px; line-height: 18px; display: inline-block; vertical-align: middle; color: rgb(143, 143, 143); }
.button .app_icon { margin: -6px -12px -11px; }
.button.thin { padding-top: 6px; padding-bottom: 7px; }
.button--back, .button--close, .button--icon { color: rgb(143, 143, 143); background: transparent; line-height: 22px; padding: 8px; border-radius: 100px; position: relative; }
.button--back:active, .button--back:hover, .button--close:active, .button--close:hover, .button--icon:active, .button--icon:hover { background: transparent; }
.button--back .icon, .button--close .icon, .button--icon .icon { font-size: 24px; line-height: 25px; margin: 0px; }
.button--icon.button--white, .button--white.button--back, .button--white.button--close { color: rgb(255, 255, 255); }
.button--icon.button--white .icon, .button--white.button--back .icon, .button--white.button--close .icon { color: inherit; }
.button--plain { color: rgb(3, 154, 211); cursor: pointer; text-transform: uppercase; padding: 0px; background: transparent !important; }
.button--flat, .button--flat.button--green, .button--flat.button--white, .button--flat_disabled { color: rgb(3, 154, 211); background-color: transparent; letter-spacing: 0.04em; }
.button--flat .icon, .button--flat_disabled .icon { color: rgb(3, 154, 211); }
.button--flat:hover, .button--flat_disabled:hover { color: rgb(13, 186, 252); background-color: transparent; }
.button--flat_disabled { white-space: normal; background: transparent !important; border-color: transparent !important; cursor: default !important; color: rgba(57, 57, 57, 0.3) !important; box-shadow: none !important; }
.button--green.button--flat, .button--green.button--flat_disabled { box-shadow: none; background: transparent; color: rgb(89, 168, 64); }
.button--green.button--flat:hover, .button--green.button--flat_disabled:hover { background: rgb(245, 245, 245); color: rgb(115, 192, 91); }
.button--green.button--flat:active, .button--green.button--flat_disabled:active { background: rgb(237, 237, 237); }
.button--white.button--flat, .button--white.button--flat_disabled { box-shadow: none; background: transparent !important; color: rgb(255, 255, 255) !important; }
.button--default { text-transform: none; letter-spacing: normal; font-weight: 400; }
.button--alt, .button--alt_red, .button--default { background: transparent; border-radius: 2px; border: 1px solid rgb(235, 235, 235); color: rgb(74, 74, 74); }
.button--alt, .button--alt_red { padding-top: 9px; padding-bottom: 10px; }
.button--alt_red { color: rgb(247, 80, 89); }
.button--bordered { background: transparent; border: 1px solid rgb(3, 154, 211); color: rgb(3, 154, 211); }
.button--bordered:hover { background: rgba(3, 154, 211, 0.1); }
.button--green, .button--primary { background: rgb(3, 154, 211); color: rgb(255, 255, 255); padding: 10px 23px 11px; }
.button--green .icon, .button--primary .icon { color: inherit; }
.button--green:hover, .button--primary:hover { background: rgb(3, 165, 226); }
.button--green:active, .button--primary:active { background: rgb(3, 143, 196); }
.button--green { background: rgb(89, 168, 64); }
.button--green:hover { background: rgb(99, 185, 72); }
.button--green:active { background: rgb(79, 150, 57); }
.button--link { text-transform: none; font-weight: 400; padding: 0px; letter-spacing: 0px; background: transparent !important; }
.button--delete { position: absolute; right: 8px; top: 50%; margin-top: -12px; padding: 0px; z-index: 41; }
.button--delete + .form_control, .change-phone-form .button--delete + .form-control, .form--invite .button--delete + .form-control, .p_form_app .button--delete + .form-control { padding-right: 30px; }
.button--delete:hover { color: rgb(70, 82, 93); }
.button--edit { position: absolute; right: 6px; top: 6px; }
.button--edit .icon { color: rgb(255, 255, 255); font-size: 22px; }
.button--disabled_link { pointer-events: none; }
.button--with_loading { position: relative; display: inline-block; overflow: visible; text-transform: none; }
.button--with_loading::before { content: ""; position: absolute; right: 10px; top: 50%; margin-top: -10px; width: 16px; height: 16px; border-width: 2px; border-style: solid; border-color: rgb(155, 155, 155) rgb(155, 155, 155) rgb(155, 155, 155) transparent; border-image: initial; border-radius: 50%; opacity: 0; transition-duration: 0.5s; transition-property: opacity; animation-duration: 1s; animation-iteration-count: infinite; animation-name: rotate; animation-timing-function: linear; }
.button--with_loading::after { content: ""; display: inline-block; height: 100%; width: 0px; transition-delay: 0.5s; transition-duration: 0.75s; transition-property: width; }
.button--on_load { position: relative; display: inline-block; color: transparent !important; }
.button--on_load::after { content: ""; position: absolute; left: 0px; right: 0px; margin: 0px auto; width: 16px; height: 16px; border-width: 2px; border-style: solid; border-color: rgb(255, 255, 255) rgb(255, 255, 255) rgb(255, 255, 255) transparent; border-image: initial; border-radius: 50%; transition-duration: 0.5s; transition-property: opacity; animation-duration: 1s; animation-iteration-count: infinite; animation-name: rotate; animation-timing-function: linear; opacity: 1 !important; }
.button--back, .button--close { position: absolute; top: 50%; right: 9px; margin-top: -19px; color: rgb(143, 143, 143); }
.button--back:hover, .button--close:hover { color: rgb(70, 82, 93); }
.button--back .icon, .button--close .icon { margin-right: 0px; }
.button--back { left: 18px; right: auto !important; }
.button--dark { background: rgb(69, 81, 92); color: rgb(255, 255, 255); text-transform: none; }
.button--dark:hover { background-color: rgb(80, 94, 107); }
.button--dark:active { background-color: rgb(58, 68, 77); }
.button--success { background-color: rgb(89, 168, 64); color: rgb(255, 255, 255); }
.button--success::before { content: ""; font-size: 20px; display: inline-block; vertical-align: middle; position: relative; top: -3px; margin-right: 4px; margin-bottom: -2px; font-family: icons !important; }
.button--success:hover { background-color: rgb(95, 179, 68); }
.button--success:active { background-color: rgb(83, 157, 60); }
.button--resend { text-transform: none; letter-spacing: 0.4px; font-size: 12px; color: rgb(57, 57, 57); padding-left: 10px; padding-right: 10px; text-align: left; font-weight: 400; width: auto; background: transparent !important; }
.button--resend:hover { color: rgb(3, 154, 211); background: transparent !important; }
.button--resend:hover .icon { color: rgb(3, 154, 211); }
.button--resend .icon { position: relative; color: rgb(143, 143, 143); font-size: 16px; top: -1px; margin-top: -2px; margin-right: 5px !important; }
.button--resend--on_resend { cursor: default; }
.button--resend--on_resend:hover { color: rgb(57, 57, 57); }
.button--resend--on_resend .icon, .button--resend--on_resend:hover .icon { color: rgb(89, 168, 64); }
.button--with_icon { position: relative; }
.button--with_icon .icon { position: absolute; left: 16px; top: 50%; margin-top: -10px; font-size: 20px; width: 24px; text-align: center; }
.button--vk { background: rgb(89, 122, 158); color: rgb(255, 255, 255); }
.button--vk .icon { color: rgb(255, 255, 255); }
.button--vk:hover { background: rgb(103, 135, 169); }
.button--vk:active { background: rgb(80, 109, 142); }
.button--odnoklassniki { background: rgb(242, 114, 13); color: rgb(255, 255, 255); }
.button--odnoklassniki .icon { color: rgb(255, 255, 255); }
.button--odnoklassniki:hover { background: rgb(243, 128, 37); }
.button--odnoklassniki:active { background: rgb(218, 103, 12); }
.button--user_edit { position: absolute; top: 0px; right: 0px; }
.button--user_edit:hover, .button--user_edit:hover .icon { color: rgb(3, 154, 211); }
.button--user_edit .icon { color: rgb(143, 143, 143); line-height: 24px; transition: color 0.2s ease 0s; }
.header .button--user_edit { height: 100%; }
.button--grey { background: rgb(234, 234, 234); color: rgb(57, 57, 57); }
.button--grey:hover { background: rgb(242, 242, 242); }
.button--grey:active { background: rgb(226, 226, 226); }
.button--sm { padding-top: 6px; padding-bottom: 7px; }
.button--lg { padding-top: 13px; padding-bottom: 14px; }
.button--youla_js_default { text-transform: none; font-weight: 400; letter-spacing: 0px; }
.button--disabled, .button[disabled]:not(.button--success):not(.button--clear_disabled) { cursor: default; background: rgb(245, 245, 245); box-shadow: none; color: rgb(175, 181, 187) !important; border-color: transparent !important; }
.button--disabled.button--back .icon, .button--disabled.button--close .icon, .button--disabled.button--icon .icon, .button.button--back[disabled]:not(.button--success):not(.button--clear_disabled) .icon, .button.button--close[disabled]:not(.button--success):not(.button--clear_disabled) .icon, .button[disabled]:not(.button--success):not(.button--clear_disabled).button--icon .icon { color: rgb(175, 181, 187) !important; }
.button[data-button-count]::after { content: "(" attr(data-button-count) ")"; }
.button[data-loading] { pointer-events: none; cursor: not-allowed; }
.button[data-loading]::before { transition-delay: 0.5s; transition-duration: 1s; opacity: 1; }
.button[data-loading]::after { width: 32px; transition-delay: 0s; }
.button--no-text { height: 36px; cursor: default; background-color: transparent !important; box-shadow: none !important; }
.button--no-text span { display: none; }
.button--no-text:active, .button--no-text:focus, .button--no-text:hover { background-color: transparent !important; }
.button_block, .button_container { width: 100%; }
.button_block + .button_block, .button_block + .button_container, .button_container + .button_block, .button_container + .button_container { margin-top: 10px; }
.button_container .button, .button_container button { width: 100%; }
.modal .button_container .button { text-transform: uppercase; font-weight: 600; margin-bottom: 0px !important; }
.buttons_wrapper { padding: 10px 5px; }
.buttons_wrapper::after, .buttons_wrapper::before { content: " "; display: table; }
.buttons_wrapper::after { clear: both; }
.buttons_wrapper .button_container { padding: 0px 5px; float: left; width: 50%; }
.buttons_wrapper .button_container + .button_container { margin-top: 0px; }
.buttons_wrapper--full .button_container, .buttons_wrapper--single .button_container { width: 100%; float: none; }
.buttons_wrapper--full .button_container + .button_container { margin-top: 8px; }
@media (min-width: 992px) {
  .buttons_wrapper.fixed_buttons { position: absolute; bottom: 0px; left: 0px; right: 0px; max-width: none !important; }
}
.button_text_new { border: 0px; border-radius: 4px; background-color: transparent; color: rgb(3, 154, 211); text-decoration: none; display: inline-block; vertical-align: middle; font-size: 14px; font-weight: 400; font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; text-align: center; white-space: nowrap; cursor: pointer; text-transform: none; letter-spacing: 0px; padding: 10px 16px 11px; transition: all 0.2s ease 0s; }
.button_text_new--block { color: rgb(255, 255, 255); text-decoration: none; font-size: 14px; text-transform: uppercase; padding: 0px 20px 0px 3px; }
.button_text_new--block.dissmissed { color: rgb(3, 154, 211); }
@media (max-width: 991px), screen and (max-device-height: 991px) {
  .button_text_new--block { height: 41px; line-height: 41px; }
}
@media (max-width: 991px) {
  .button_text_new--block { padding: 0px 20px 0px 0px; }
}
.button_text_new:focus { outline: none; }
.button_text_new:hover { color: rgba(3, 154, 211, 0.8); }
.button_text_new:active, .button_text_new:hover { background-color: transparent; }
.button_text_new.button--disabled, .button_text_new[disabled] { color: rgba(143, 143, 143, 0.6); background-color: transparent; }
.button_bordered_new { text-transform: none; letter-spacing: 0px; font-size: 14px; font-weight: 400; font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; color: rgb(3, 154, 211); min-width: 220px; box-shadow: rgb(3, 154, 211) 0px 0px 0px 1px inset; padding-top: 6px; padding-bottom: 7px; }
.button_bordered_new:hover { background-color: rgba(3, 154, 211, 0.06); }
.body--is_mobile .button_bordered_new:active, .body--is_mobile .button_bordered_new:focus { background-color: transparent !important; }
.button_bordered_new:active { background-color: rgba(3, 154, 211, 0.1); }
.button_bordered_new.button--disabled, .button_bordered_new[disabled] { color: rgba(143, 143, 143, 0.6); background-color: transparent; box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; }
@keyframes rotate { 
  0% { transform: rotate(0deg); }
  100% { transform: rotate(1turn); }
}
.loader-colored { background: url("/build/images/loader-colored.192479.png") center top / 36px 36px no-repeat; animation: 0.7s linear 0s infinite normal none running rotate; height: 36px; width: 36px; margin: 0px auto; }
.progress_bar { border-radius: 100px; background-color: rgb(235, 235, 235); position: relative; width: 100%; height: 8px; margin: 15px 0px 10px; overflow: hidden; }
.progress_bar__fill { position: absolute; left: 0px; top: 0px; height: 100%; border-radius: 100px; background-color: rgb(89, 168, 64); }
.progress_bar--muted .progress_bar__fill { background-color: rgb(143, 143, 143); }
.progress { position: relative; height: 6px; display: block; width: 100%; background-color: rgba(47, 142, 203, 0.2); border-radius: 0px; margin: 0px 0px 1rem; overflow: hidden; }
.progress__inner { background-color: rgb(47, 142, 203); }
.progress--indeterminate .progress__inner::before { animation: 2.1s cubic-bezier(0.65, 0.815, 0.735, 0.395) 0s infinite normal none running indeterminate; }
.progress--indeterminate .progress__inner::after, .progress--indeterminate .progress__inner::before { content: ""; position: absolute; background-color: inherit; top: 0px; left: 0px; bottom: 0px; will-change: left, right; }
.progress--indeterminate .progress__inner::after { animation: 2.1s cubic-bezier(0.165, 0.84, 0.44, 1) 1.15s infinite normal none running indeterminate-short; }
@keyframes indeterminate { 
  0% { left: -35%; right: 100%; }
  60% { left: 100%; right: -90%; }
  100% { left: 100%; right: -90%; }
}
@keyframes indeterminate-short { 
  0% { left: -200%; right: 100%; }
  60% { left: 107%; right: -8%; }
  100% { left: 107%; right: -8%; }
}
.loader { top: 50%; margin: -18px auto 0px; }
.loader, .loader::after { position: absolute; left: 0px; right: 0px; width: 36px; height: 36px; }
.loader::after { content: ""; display: block; box-sizing: border-box; margin: 0px auto; border-width: 3px; border-style: solid; border-color: rgb(255, 255, 255) rgb(255, 255, 255) rgb(255, 255, 255) transparent; border-image: initial; border-radius: 50%; transition-duration: 0.5s; transition-property: opacity; animation-duration: 0.7s; animation-iteration-count: infinite; animation-name: rotate; animation-timing-function: linear; opacity: 1 !important; }
.loader--blue::after { border-color: rgb(3, 154, 211) rgb(3, 154, 211) rgb(3, 154, 211) transparent; }
.loader--large { margin: 0px auto; }
.loader--large, .loader--large::after { width: 72px; height: 72px; }
.loader--button { left: 20px; right: auto; top: 50%; width: 16px; height: 16px; transform: translateY(-50%); margin: 0px; }
.loader--button::after { width: 16px; height: 16px; border-width: 2px; }
.form__buttons { margin-top: 40px; overflow: hidden; }
.form--invite .form-control, .form--invite .form_control { background: rgb(255, 255, 255); border: 1px solid rgb(234, 234, 234); font-size: 15px; color: gray; }
.form--invite .form-control::-webkit-input-placeholder, .form--invite .form_control::-webkit-input-placeholder { color: rgb(143, 143, 143); }
.form--invite .button { padding-left: 16px; padding-right: 16px; width: 100%; }
.change-phone-form .form-control, .form--invite .form-control, .form_control, .p_form_app .form-control { height: 40px; border-radius: 2px; background-color: rgb(255, 255, 255); border: 1px solid rgb(235, 235, 235); font-size: 14px; color: rgb(57, 57, 57); padding: 10px 20px; display: inline-block; vertical-align: middle; zoom: 1; }
.change-phone-form .form-control::-webkit-input-placeholder, .form--invite .form-control::-webkit-input-placeholder, .form_control::-webkit-input-placeholder, .p_form_app .form-control::-webkit-input-placeholder { color: rgb(143, 143, 143); opacity: 1 !important; }
.change-phone-form .form-control:focus, .form--invite .form-control:focus, .form_control:focus, .p_form_app .form-control:focus { outline: none; }
.change-phone-form .form-control:focus + .input_group__addon, .form--invite .form-control:focus + .input_group__addon, .form_control:focus + .input_group__addon, .p_form_app .form-control:focus + .input_group__addon { color: rgb(3, 154, 211); }
.change-phone-form .form-control, .form_control--simple { padding-left: 0px; padding-right: 0px; height: auto; border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; box-shadow: none; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); font-size: 16px; color: rgb(42, 42, 42); border-radius: 0px; width: 100%; -webkit-appearance: none; background: transparent !important; }
.change-phone-form .form_control--select.form-control, .form_control--simple.form_control--select { vertical-align: top; cursor: pointer; }
.change-phone-form .form_control--select.form-control > .Select-control, .form_control--simple.form_control--select > .Select-control { height: auto; line-height: normal; min-height: 0px; border-radius: 0px; cursor: pointer; padding-left: 0px; padding-right: 0px; background: transparent; border: 0px !important; box-shadow: none !important; }
.change-phone-form .form_control--select.form-control > .Select-control:hover, .form_control--simple.form_control--select > .Select-control:hover { background: transparent; }
.change-phone-form .form_control--select.form-control > .Select-control .Select-value, .form_control--simple.form_control--select > .Select-control .Select-value { padding: 0px; }
.change-phone-form .form_control--select.form-control .suggest_list, .form_control--simple.form_control--select .suggest_list { left: -21px; right: -21px; }
.form_control--range { width: 100%; margin: 20px 0px 0px; }
.form_control--block { width: 100%; }
.form_control--checkbox, .form_control--radio { position: relative; overflow: visible; padding: 0px; margin-bottom: 3px; }
.form_control--checkbox input, .form_control--radio input { position: absolute; right: 100%; bottom: 100%; opacity: 0; visibility: hidden; }
.form_control--checkbox input:checked + .form_label::before, .form_control--radio input:checked + .form_label::before { border-color: rgb(46, 141, 202); }
.form_control--checkbox input:checked + .form_label::after, .form_control--radio input:checked + .form_label::after { opacity: 1; }
.form_control--checkbox .form_label, .form_control--radio .form_label { position: relative; padding: 4px 10px 4px 32px; cursor: pointer; display: inline-block; width: 100%; user-select: none; }
.form_control--checkbox .form_label::after, .form_control--checkbox .form_label::before, .form_control--radio .form_label::after, .form_control--radio .form_label::before { content: ""; position: absolute; border-radius: 100px; transition: all 0.2s ease 0s; box-sizing: border-box; }
.form_control--checkbox .form_label::after, .form_control--radio .form_label::after { width: 10px; height: 10px; left: 5px; top: 9px; opacity: 0; background: rgb(46, 141, 202); }
.form_control--checkbox .form_label::before, .form_control--radio .form_label::before { left: 0px; top: 4px; width: 20px; height: 20px; border: 2px solid rgba(57, 57, 57, 0.38); }
.form_control--checkbox .form_label img, .form_control--radio .form_label img { max-width: 16px; max-height: 16px; display: inline-block; vertical-align: middle; margin-right: 10px; position: relative; top: -1px; }
.form_control--checkbox .tooltip, .form_control--radio .tooltip { top: 100%; bottom: auto; margin-top: 5px; line-height: normal; width: 225px; left: -20px; transform: translate(0px); }
.body--is_mobile .form_control--checkbox .tooltip, .body--is_mobile .form_control--radio .tooltip { display: none; }
@media (max-width: 991px) {
  .form_control--checkbox .tooltip, .form_control--radio .tooltip { margin-top: 0px; left: 10px; margin-bottom: 10px; }
}
.form_control--checkbox:hover .tooltip, .form_control--radio:hover .tooltip { opacity: 1; visibility: visible; }
.form_control--checkbox { height: auto; border: 0px; background: transparent; }
.form_control--checkbox input:checked + .form_label::before { border-color: transparent; content: ""; color: rgb(3, 154, 211); }
.form_control--checkbox .form_label::before { content: ""; position: absolute; left: -2px; width: 18px; height: 18px; color: rgba(57, 57, 57, 0.38); font-size: 24px; opacity: 1; background: transparent; border: 0px; border-radius: 0px; font-family: icons !important; }
.form_control--checkbox .form_label::after { display: none; }
.form_control--small { font-size: 14px; color: rgb(57, 57, 57); padding: 2px 6px 3px; height: auto; width: auto; min-width: 0px; }
.form_control--error, .form_control--invalid, .p_form_app .form-control--error { border-color: rgb(255, 147, 147) !important; }
.form_control--select { width: 100%; position: relative; }
.form_control--select > .Select-control { cursor: pointer; padding-left: 20px; padding-right: 10px; }
.form_control--select .Select-input { height: auto; padding: 0px; width: 100%; position: absolute; top: 0px; left: 0px; }
.form_control--select .Select-placeholder, .form_control--select > .Select-control .Select-value { padding: 0px; cursor: pointer; max-width: 160px; position: relative; font-size: 16px; letter-spacing: 0px; line-height: normal; }
.form_control--select .Select-placeholder:focus, .form_control--select > .Select-control .Select-value:focus { outline: none; }
.form_control--alt { background: transparent; border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; box-shadow: none; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); padding-left: 0px; font-size: 15px; color: rgb(42, 42, 42); border-radius: 0px; width: 100%; }
.form_control--alt__country_code { padding-right: 0px; }
.form_control--alt--invalid { border-width: 0px 0px 2px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; border-bottom-style: solid; border-bottom-color: rgb(255, 92, 74); box-shadow: none; }
.box__content .change-phone-form .form-control[type="text"], .box__content .form--invite .form-control[type="text"], .box__content .form_control[type="text"], .box__content .p_form_app .form-control[type="text"], .change-phone-form .box__content .form-control[type="text"], .form--invite .box__content .form-control[type="text"], .p_form_app .box__content .form-control[type="text"] { background-color: transparent; }
.form_control--disabled .form_label, input[disabled] + .form_label { color: rgba(57, 57, 57, 0.4); }
.form_control--disabled .form_label::before, input[disabled] + .form_label::before { border-color: rgba(57, 57, 57, 0.4) !important; }
.form_control--disabled .form_label::after, input[disabled] + .form_label::after { background-color: rgba(57, 57, 57, 0.4) !important; }
.form_error { color: rgb(255, 92, 74); font-size: 12px; line-height: 16px; }
.form_group { position: relative; margin-bottom: 28px; }
.form_group__addon { position: absolute; left: 2px; top: 50%; margin-top: -21px; background: transparent !important; }
.change-phone-form .form_group__addon + .form-control, .form--invite .form_group__addon + .form-control, .form_group__addon + .form_control, .form_group__addon_indent, .p_form_app .form_group__addon + .form-control { padding-left: 50px; }
.form_label { display: block; color: rgb(57, 57, 57); }
.form_label + .control_range { margin: 20px 0px 0px; }
.form_label--error { color: rgb(255, 92, 74); margin-top: 5px; }
.form_label--fixed { position: absolute; top: 50%; left: 10px; margin-top: -10px; color: rgb(140, 140, 140); }
.form_inline { margin-bottom: 40px; }
.form_inline > * { display: inline-block; vertical-align: middle; }
.form_inline .row { margin-left: -4px; margin-right: -4px; display: block; }
.form_inline .row > * { padding-left: 4px; padding-right: 4px; }
.form_inline .row > .text-right { padding-left: 0px; }
.change-phone-form .form_inline .row .form-control, .form--invite .form_inline .row .form-control, .form_inline .row .change-phone-form .form-control, .form_inline .row .form--invite .form-control, .form_inline .row .form_control, .form_inline .row .p_form_app .form-control, .p_form_app .form_inline .row .form-control { width: 100%; }
.form_inline .row .button { max-width: 100%; }
.form_inline .form_label { padding: 0px 3px; }
.form_inline .form_label:first-child { padding-left: 0px; }
.form_inline--tel .row { margin-left: -8px; margin-right: -8px; }
.form_inline--tel .row > * { padding-left: 8px; padding-right: 8px; }
.change-phone-form .form_inline--tel .form-control, .form--invite .form_inline--tel .form-control, .form_inline--tel .change-phone-form .form-control, .form_inline--tel .form--invite .form-control, .form_inline--tel .form_control, .form_inline--tel .form_control__country-code, .form_inline--tel .p_form_app .form-control, .p_form_app .form_inline--tel .form-control { padding-right: 0px; }
.form_inline--tel .form_group--country_suggest { width: 100%; }
.form_inline--tel .suggest_list__item { font-size: 15px; }
@media (max-width: 450px) {
  .form--invite .form_inline .row > div { width: 100%; float: none; margin-bottom: 8px; }
}
.form_select { display: inline-block; vertical-align: top; }
@media (max-width: 767px) {
  .form_select { width: 100%; }
}
.change-phone-form .form_control_group .form-control, .form--invite .form_control_group .form-control, .form_control_group .button, .form_control_group .change-phone-form .form-control, .form_control_group .form--invite .form-control, .form_control_group .form_control, .form_control_group .p_form_app .form-control, .p_form_app .form_control_group .form-control { border-radius: 2px 0px 0px 2px; }
.change-phone-form .form_control_group .button .form-control, .change-phone-form .form_control_group .form-control + .button, .change-phone-form .form_control_group .form-control .form-control, .change-phone-form .form_control_group .form-control .form_control, .change-phone-form .form_control_group .form_control .form-control, .form--invite .form_control_group .button .form-control, .form--invite .form_control_group .form-control + .button, .form--invite .form_control_group .form-control .form-control, .form--invite .form_control_group .form-control .form_control, .form--invite .form_control_group .form_control .form-control, .form_control_group .button + .button, .form_control_group .button .change-phone-form .form-control, .form_control_group .button .form--invite .form-control, .form_control_group .button .form_control, .form_control_group .button .p_form_app .form-control, .form_control_group .change-phone-form .form-control + .button, .form_control_group .change-phone-form .form-control .form-control, .form_control_group .change-phone-form .form-control .form_control, .form_control_group .form--invite .form-control + .button, .form_control_group .form--invite .form-control .form-control, .form_control_group .form--invite .form-control .form_control, .form_control_group .form_control + .button, .form_control_group .form_control .change-phone-form .form-control, .form_control_group .form_control .form--invite .form-control, .form_control_group .form_control .form_control, .form_control_group .form_control .p_form_app .form-control, .form_control_group .p_form_app .form-control + .button, .form_control_group .p_form_app .form-control .form-control, .form_control_group .p_form_app .form-control .form_control, .p_form_app .form_control_group .button .form-control, .p_form_app .form_control_group .form-control + .button, .p_form_app .form_control_group .form-control .form-control, .p_form_app .form_control_group .form-control .form_control, .p_form_app .form_control_group .form_control .form-control { border-radius: 0px 2px 2px 0px; }
.input_group { position: relative; }
.input_group .suggest_container .suggest_list { left: 0px; right: 0px; }
.input_group .button--delete { right: 2px; z-index: 0; }
.input_group + .hint { line-height: 16px; margin-top: 5px; }
@media (max-width: 991px) {
  .input_group .suggest_container .suggest_list { left: -15px; right: -15px; }
}
@media (max-width: 767px) {
  .input_group .suggest_container .suggest_list { left: -10px; right: -10px; }
}
.form_title { font-size: 14px; margin-bottom: 15px; font-weight: 600; color: rgb(57, 57, 57); }
.input_large { width: 100%; position: relative; }
.input_large__input { background: rgb(255, 255, 255); color: rgb(57, 57, 57); font-size: 24px; line-height: normal; padding: 14px 50px 15px 30px; border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; width: 100%; border-bottom-style: solid; border-bottom-color: rgb(233, 233, 233); }
.input_large__input:focus { outline: none; }
.input_large__input--error { border-bottom-color: rgb(255, 92, 74); }
.input_large__clear { position: absolute; right: 15px; top: 50%; margin-top: -21px; border: 0px; font-size: 24px; }
.modal__body .input_large { margin: -25px -40px 25px; width: auto; }
.input_large .suggest_list { top: 100%; left: 0px; right: 0px; }
.input_large .suggest_list__item { font-size: 24px; padding-left: 40px; padding-right: 40px; }
@media (max-width: 991px) {
  .modal__container .input_large .input_large__input { padding-left: 15px; }
}
@media (max-width: 767px) {
  .modal__container .input_large .input_large__input { padding: 18px 50px; }
  .input_large__input { font-size: 14px; }
  .input_large .suggest_list { top: 100%; left: 0px; right: 0px; }
  .input_large .suggest_list__item { font-size: 15px; padding-left: 50px !important; }
  .input_large .suggest_list__item::before { left: 15px !important; }
  .input_large .icon--close::before { content: ""; }
  .input_large__clear { margin-top: 0px; padding: 13px; height: 100%; top: -1px; right: 0px; }
}
.input_message { }
.input_message::after, .input_message::before { content: " "; display: table; }
.input_message::after { clear: both; }
.input_message__inner { position: relative; }
.change-phone-form .input_message .form-control, .form--invite .input_message .form-control, .input_message .change-phone-form .form-control, .input_message .form--invite .form-control, .input_message .form_control, .input_message .p_form_app .form-control, .p_form_app .input_message .form-control { width: 100%; min-height: 90px; height: 90px; resize: none; padding: 13px 14px; background: rgb(255, 255, 255); border: 1px solid rgb(235, 235, 235); margin-bottom: 20px; font-size: 15px; }
.change-phone-form .input_message .form-control::-webkit-input-placeholder, .form--invite .input_message .form-control::-webkit-input-placeholder, .input_message .change-phone-form .form-control::-webkit-input-placeholder, .input_message .form--invite .form-control::-webkit-input-placeholder, .input_message .form_control::-webkit-input-placeholder, .input_message .p_form_app .form-control::-webkit-input-placeholder, .p_form_app .input_message .form-control::-webkit-input-placeholder { color: rgb(143, 143, 143); }
.input_message__button .icon { margin-right: 2px; font-size: 22px; line-height: 18px; }
.input_message .button--green, .input_message .button--primary, .input_message__button { float: right; }
.input_message .button--green .icon, .input_message .button--primary .icon, .input_message__button .icon { display: none; }
.input_message .button--disabled.button--green, .input_message .button--green[disabled], .input_message .button--primary.button--disabled, .input_message .button--primary[disabled], .input_message__button.button--disabled, .input_message__button[disabled] { background: rgb(235, 235, 235) !important; color: rgb(143, 143, 143) !important; }
.input_message--send_message .input_message__inner { width: 100%; display: table; table-layout: fixed; }
@media (max-width: 991px) {
  .input_message__button { width: 45px; }
  .input_message__button span { padding: 0px !important; }
}
@media (max-width: 767px) {
  .input_message--send_message .c_create_message .button { position: static !important; }
}
.checkbox { position: relative; }
.checkbox__elem { position: absolute; right: 100%; }
.checkbox__elem:checked + .checkbox__label::after { content: ""; color: rgb(3, 154, 211); }
.checkbox__label { cursor: pointer; position: relative; display: block; padding-left: 30px; user-select: none; }
.checkbox__label::after { content: ""; position: absolute; left: 0px; top: 0px; width: 18px; height: 18px; color: rgba(57, 57, 57, 0.54); font-size: 24px; font-family: icons !important; }
.checkbox__label img { max-width: 16px; max-height: 16px; display: inline-block; vertical-align: middle; margin-right: 10px; }
.checkbox--slide { padding: 2px 0px; }
.checkbox--slide .checkbox__input { position: absolute; top: -20px; left: -20px; opacity: 0; visibility: hidden; z-index: -1; padding: 0px; margin-bottom: 0px; }
.checkbox--slide .checkbox__input:checked + .checkbox__elem { background: rgb(3, 154, 211); }
.checkbox--slide .checkbox__input:checked + .checkbox__elem .checkbox__control { left: 17px; }
.checkbox--slide .checkbox__elem { display: block; position: relative; height: 14px; width: 34px; right: 0px; cursor: pointer; border-radius: 20px; background-color: rgba(34, 31, 31, 0.26); transition: background 0.2s ease 0s; }
.checkbox--slide .checkbox__elem--checked { background: rgb(3, 154, 211); }
.checkbox--slide .checkbox__elem--checked .checkbox__control { left: 17px; }
.checkbox--slide .checkbox__control { display: block; position: absolute; left: -3px; top: 50%; width: 20px; height: 20px; margin-top: -10px; border-radius: 100px; background: rgb(241, 241, 241); box-shadow: rgba(0, 0, 0, 0.24) 0px 1px 1px 0px, rgba(0, 0, 0, 0.12) 0px 0px 1px 0px; transition: left 0.2s ease 0s; }
.checkbox--right .checkbox__label { padding-left: 0px; padding-right: 30px; }
.checkbox--right .checkbox__label::after { left: auto; right: 0px; }
.checkbox_label { user-select: none; }
.container { width: 1170px; max-width: 100%; min-width: 310px; }
@media (max-width: 767px) {
  .container { padding-left: 10px; padding-right: 10px; }
  .row { margin-left: -10px; margin-right: -10px; }
  .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 { padding-left: 10px; padding-right: 10px; }
}
.nav_list { padding: 8px 0px 0px; margin: 0px; list-style: none; }
.nav_list__item { color: rgb(57, 57, 57); font-size: 16px; margin-bottom: 8px; cursor: pointer; }
.nav_list__item--active > .nav_list__link { background: rgb(245, 245, 245); }
.nav_list__link { text-decoration: none; color: inherit; padding: 15px 42px 14px 15px; display: block; position: relative; }
.nav_list__link:hover { background: rgb(245, 245, 245); }
.nav_list__link:hover .nav_list__icon { opacity: 1; }
.nav_list__icon { font-size: 24px; margin: -2px 0px -3px; display: inline-block; vertical-align: middle; color: rgb(143, 143, 143); float: left; opacity: 0.6; width: 24px; height: 24px; background-size: 24px 24px; background-repeat: no-repeat; transition: opacity 0.2s ease 0s; }
.nav_list__icon--mobile { opacity: 1; display: none; }
.nav_list__label { display: block; padding-left: 42px; position: relative; top: -1px; }
.nav_list--show { display: block !important; }
.nav_list--categories_slide { transition: margin-left 0.6s ease 0s; }
.body--is_mobile .nav_list--categories_slide { transition: margin-left 0.4s ease 0s; }
.nav_list--menu .nav_list__item { color: rgb(57, 57, 57); position: relative; margin-bottom: 4px; }
.nav_list--menu .nav_list__item--active .nav_list__link { background: rgb(234, 234, 234); }
.nav_list--menu .nav_list__link { text-decoration: none; color: rgb(57, 57, 57); padding: 10px; display: block; }
.nav_list--menu .nav_list__link:hover { background: rgba(52, 62, 71, 0.1); }
.nav_list--menu .nav_list__link:hover .icon { color: rgb(143, 143, 143); }
.nav_list--menu .nav_list__text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.nav_list--menu .nav_list .icon { font-size: 20px; position: relative; top: -1px; transition: color 0.2s ease 0s; color: rgb(143, 143, 143) !important; }
.nav_list--menu .nav_list__icon { position: relative; display: inline-block; vertical-align: middle; margin-right: 7px; opacity: 1; }
.nav_list--menu .nav_list__icon .badge { top: -4px !important; right: -5px !important; }
@media (min-width: 992px) {
  .nav_list { white-space: nowrap; padding: 0px; font-size: 0px; display: block; position: static; }
  .nav_list__item { position: static; bottom: auto; margin-bottom: 0px; white-space: normal; text-align: center; padding: 0px 2px 0px 0px; display: inline-block; width: 102px; vertical-align: top; overflow: visible; }
  .nav_list__item:first-child { padding-left: 0px; }
  .nav_list__item:hover { background: transparent; }
  .nav_list__item:hover > .nav_list__link { background: rgba(0, 0, 0, 0.03); }
  .nav_list__item:hover > .nav_list__link::after, .nav_list__item:hover > .nav_list__link::before { display: block; visibility: visible; opacity: 1; }
  .nav_list__item:hover > .nav_list__link .nav_list__icon { opacity: 1; }
  .nav_list__item:hover .nav_list { display: block; }
  .nav_list__item--active > .nav_list__link { background: rgba(0, 0, 0, 0.03); }
  .nav_list__item--active > .nav_list__link::after, .nav_list__item--active > .nav_list__link::before { opacity: 0; visibility: hidden; }
  .nav_list__icon { float: none; display: block; margin: 0px auto 7px; }
  .nav_list__label { padding-left: 0px; font-size: 12px; line-height: 14px; word-break: break-word; max-height: 28px; overflow: hidden; }
  .nav_list__link { width: auto; height: 94px; min-width: 100px; max-width: 140px; padding: 18px 5px; position: relative; }
  .nav_list__link::after, .nav_list__link::before { border: solid transparent; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; z-index: 123; top: auto; bottom: 0px; left: 0px; right: 0px; margin: 0px auto; opacity: 0; visibility: hidden; transition: all 0s ease 0.2s; }
  .nav_list__link::after { border-width: 7px; margin-top: -11px; border-color: transparent transparent rgb(255, 255, 255); }
  .nav_list__link::before { border-width: 8px; margin-top: -12px; border-color: transparent transparent rgba(0, 0, 0, 0.05); }
  .nav_list__link:hover { background: rgba(0, 0, 0, 0.03); }
  .nav_list__link:hover::after, .nav_list__link:hover::before { display: block; opacity: 1; visibility: visible; }
  .nav_list__list { padding: 0px; margin: 0px; list-style: none; width: 215px; display: inline-block; vertical-align: top; white-space: normal; }
  .nav_list--disable_hover .nav_list, .nav_list--disable_hover::after, .nav_list--disable_hover::before { display: none !important; }
  .nav_list--hovered .nav_list__item:hover { background: transparent; }
  .nav_list--hovered .nav_list__item > .nav_list__link::after, .nav_list--hovered .nav_list__item > .nav_list__link::before { display: block; transition-delay: 0s; }
  .nav_list--hovered .nav_list--subcategories { transition-delay: 0s; }
  .nav_list--subcategories { height: auto; z-index: 41; background: rgb(255, 255, 255); border-radius: 0px; box-shadow: none; left: auto; right: auto; top: 100%; width: auto; bottom: auto; font-size: 0px; position: absolute; text-align: left; border: 1px solid rgb(235, 235, 235); visibility: hidden; opacity: 0; display: none; white-space: nowrap; transition: all 0s ease 0.15s; padding: 10px !important; }
  .nav_list--subcategories .nav_list__item { width: 100%; height: auto; text-align: left; padding: 0px; display: block; margin: 0px !important; }
  .nav_list--subcategories .nav_list__item:last-child { margin-bottom: 0px !important; }
  .nav_list--subcategories .nav_list__item:hover > .nav_list__link { background: rgb(245, 245, 245); }
  .nav_list--subcategories .nav_list__item--active .nav_list__link { color: rgb(3, 154, 211); background: transparent; }
  .nav_list--subcategories .nav_list__item--active .nav_list__link:hover { background: transparent; }
  .nav_list--subcategories .nav_list__item--active .nav_list__link::after { position: absolute; right: 10px; left: auto; font-size: 18px; width: auto; height: auto; top: 50%; margin-top: -9px; border: 0px; opacity: 1; visibility: visible; font-family: icons !important; content: "" !important; display: block !important; }
  .nav_list--subcategories .nav_list__link { font-size: 14px; color: rgb(42, 42, 42); border-bottom: 0px; background: transparent; border-radius: 2px; width: 100%; max-width: none; height: auto; padding: 10px 35px 11px 20px; }
  .nav_list--subcategories .nav_list__link::after, .nav_list--subcategories .nav_list__link::before { display: none; border: 0px; }
  .nav_list--subcategories .nav_list__link:hover { background: rgb(245, 245, 245); }
  .nav_list--subcategories.nav_list--show { opacity: 1; visibility: visible; display: block; }
}
@media (max-width: 991px) {
  .nav_list--menu { display: none; }
  .nav_list--categories { width: 100%; border-right: 0px; position: relative; max-width: none; min-height: 100%; top: 0px; padding: 0px 0px 16px; background: rgb(255, 255, 255); }
  .nav_list--categories .nav_list__item { padding: 0px; margin: 0px; background: rgb(255, 255, 255); }
  .nav_list--categories .nav_list__item:first-child > .nav_list__link::before { border-top: 0px; }
  .nav_list--categories .nav_list__item:last-child > .nav_list__link::before { border-bottom: 1px solid rgb(235, 235, 235); }
  .nav_list--categories .nav_list__item:hover + .nav_list__item > .nav_list__link::before, .nav_list--categories .nav_list__item:hover > .nav_list__link::before { left: 0px; }
  .nav_list--categories .nav_list__item--active > .nav_list__link::after { position: absolute; color: rgb(143, 143, 143); right: 20px; left: auto; font-size: 18px; width: auto; height: auto; top: 50%; margin-top: -9px; border: 0px; font-family: icons !important; content: "" !important; display: block !important; }
  .nav_list--categories .nav_list__item--active + .nav_list__item > .nav_list__link::before, .nav_list--categories .nav_list__item--active > .nav_list__link::before { left: 0px; }
  .nav_list--categories .nav_list__link { -webkit-tap-highlight-color: rgba(0, 0, 0, 0); }
  .nav_list--categories .nav_list__link::before { content: ""; position: absolute; top: 0px; left: 58px; right: 0px; height: 100%; border-top: 1px solid rgb(235, 235, 235); z-index: 0; pointer-events: none; }
  .nav_list--categories .nav_list__icon { display: none; }
  .nav_list--categories .nav_list__icon--common, .nav_list--categories .nav_list__icon--mobile { display: inline-block !important; }
  .nav_list--subcategories { z-index: 5; left: 0px; right: 0px; width: auto; overflow: hidden auto; position: fixed; padding-bottom: 8px; top: 56px !important; }
  .nav_list--subcategories .nav_list__link::before { display: none; }
}
@media (max-width: 767px) {
  .nav_list__link { padding-left: 10px; }
  .nav_list--categories .nav_list__link::before { left: 51px; }
}
@media (min-width: 992px) {
  .nav_container { display: none !important; }
}
.product__title { font-weight: 400; font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; font-size: 16px; line-height: 24px; margin-bottom: 0px; overflow-wrap: break-word; word-break: break-word; }
.product__status { position: absolute; top: 20px; right: 186px; }
.body--is_mobile .product__status { top: 16px !important; right: 16px !important; }
.product__images { position: relative; min-height: 70px; }
.product__images .label { position: relative; font-size: 14px; line-height: 16px; font-weight: 600; padding: 7px 11px; z-index: 40; }
.product__images--single .product__status { top: 15px !important; right: 15px !important; }
.product__images--single .product_gallery { overflow: hidden; }
.product__images--single .gallery_control--fullscreen { width: 100%; }
.product__images--high_amount .product__status { right: 88px; }
.product__images--high_amount .product_gallery .gallery_preview__list, .product__images--high_amount .product_gallery .slick-dots { display: none; }
.product__price { display: block; color: rgb(57, 57, 57); font-size: 30px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; line-height: 38px; margin-bottom: 9px; }
.product__price--free { font-size: 16px; }
.product__share { float: left; }
.product__share p { color: rgb(143, 143, 143); margin-bottom: 14px; font-size: 16px; }
.product__report_link { font-size: 14px; color: rgb(57, 57, 57); font-weight: 400; text-decoration: none; display: inline-block; white-space: nowrap; margin: 18px 0px; }
.product__report_link .icon { font-size: 24px; color: rgb(143, 143, 143); vertical-align: middle; display: inline-block; margin-right: 3px; position: relative; top: -1px; transition: color 0.2s ease 0s; }
.product__report_link:hover, .product__report_link:hover .icon { color: rgb(247, 80, 89); }
.product__desc { font-size: 12px; line-height: 16px; color: gray; list-style: none; padding: 0px 0px 24px; margin: 0px 0px 22px; border-bottom: 1px solid rgb(235, 235, 235); }
.product__desc li { display: inline-block; vertical-align: top; margin-bottom: 0px; margin-right: 14px; }
.product__desc .text-red .icon { color: rgb(247, 80, 89); }
.product__desc .icon { font-size: 16px; color: rgb(143, 143, 143); display: inline-block; vertical-align: middle; position: relative; top: -1px; margin-right: 10px; }
.product__desc .icon--favorite { font-size: 17px; }
.product__desc .icon--views { font-size: 24px; margin-top: -3px; margin-bottom: -4px; }
.product__text { line-height: 20px; color: rgb(74, 74, 74); font-size: 14px; overflow-wrap: break-word; word-break: break-word; }
.product__text a { color: rgb(3, 154, 211); text-decoration: none; }
.product__share { white-space: nowrap; }
.product__footer { padding-top: 32px; padding-bottom: 22px; border-top: 1px solid rgb(235, 235, 235); }
.product__footer::after, .product__footer::before { content: " "; display: table; }
.product__footer::after { clear: both; }
.product__footer .col-xs-6 { padding: 0px !important; }
.product__actions { float: right; font-size: 14px; font-weight: 400; font-style: normal; color: rgb(57, 57, 57); margin-top: -8px; }
.product__actions .icon { font-size: 22px; color: rgb(143, 143, 143); position: relative; top: -1px; margin-right: 12px; transition: color 0.2s ease 0s; }
.product__actions .icon--warning { color: rgb(247, 80, 89); }
.product__actions button { vertical-align: top; }
.product__buttons { margin: 26px 0px 10px; overflow: visible; }
.product__buttons:empty { display: none; }
.product__buttons .button { width: 100%; height: 40px; padding-top: 9px; }
.product__show_number { position: relative; }
.product__show_number .tooltip { width: 225px; font-size: 12px; line-height: 16px; top: 40px; bottom: auto; left: 50%; margin-top: 6px; transform: translate(-50%) !important; }
.body--is_mobile .product__show_number .tooltip { display: none !important; }
.product__status_text { color: rgb(255, 92, 74); margin-bottom: 20px; }
@media (min-width: 1071px) {
  .product__report_link { float: right; }
}
@media (min-width: 768px) {
  .product__text { font-size: 16px; line-height: 24px; }
}
@media (max-width: 991px) {
  .product__price { font-size: 36px; line-height: normal; margin-bottom: 4px; }
  .product__title { font-size: 24px; line-height: normal; padding-right: 20px; margin-bottom: 3px; }
  .product__desc { margin: 0px -15px; padding: 22px 15px; }
  .product__images .product__status { right: 175px; }
  .product__images .tooltip { left: auto !important; right: 0px !important; transform: none !important; }
  .product__images--high_amount .product__status { right: 70px; }
  .product__buttons { position: static; margin: 25px -4px 5px; overflow: hidden; padding: 0px !important; }
  .product__buttons .product_tel, .product__buttons .tooltip { display: none; }
  .product__buttons .button { padding-left: 5px; padding-right: 5px; }
  .product__buttons .button--green { background-color: rgb(3, 154, 211); }
  .product__buttons--single .product_card__button { width: 100%; }
  .product__footer { padding-bottom: 30px; padding-top: 25px; border-bottom: 0px; }
  .product .share__list { text-align: left; }
  .product__images--single .product__status { right: 0px !important; }
}
@media (max-width: 767px) {
  .product__desc { margin-left: -10px; margin-right: -10px; padding-left: 10px; padding-right: 10px; }
  .product__actions button > span, .product__share p { font-size: 14px; }
  .product__images--single .product__status { right: 5px !important; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .product__price { font-size: 24px; margin-bottom: 8px; }
  .product__title { font-size: 20px; line-height: normal; margin-bottom: 4px; }
  .product .card { margin-bottom: 20px; }
  .product__actions, .product__share { float: none; margin-top: 0px; }
  .product .share__list { text-align: center; }
  .product__share { margin-bottom: 30px; }
  .product__actions { padding: 0px; margin: 0px 0px 10px; }
  .product__footer { padding-bottom: 5px; text-align: center; }
  .product__footer > div { width: 100%; }
  .product__desc { padding-bottom: 12px; margin-bottom: 0px; border-bottom: 0px; }
  .product__desc li { display: block; margin-right: 0px; margin-bottom: 15px; }
  .product__desc .icon { margin-right: 12px; width: 22px; text-align: center; }
  .product__footer { margin-bottom: 0px; }
  .product__images .product__status { right: 90px; top: 10px; }
  .product__images .label { font-size: 12px; line-height: 16px; padding: 4px 10px 6px; }
  .product__images--high_amount .product__status { right: 64px; }
}
@media (min-width: 1100px) {
  .product__desc li { margin-right: 24px; }
}
.product_info { position: relative; }
.product_favorite { color: rgb(57, 57, 57); transition: color 0.2s ease 0s; }
.product_favorite .icon { font-size: 25px; color: rgb(143, 143, 143); margin-right: 10px; top: -1px; position: relative; display: inline-block; transition: color 0.2s ease 0s; }
.product_favorite .icon::after { pointer-events: none; content: ""; position: absolute; top: 0px; left: 0px; color: rgb(188, 188, 188); opacity: 0; transition: transform 0.2s ease 0s, opacity 0.2s ease 0s, color 0.2s ease 0s; transform: scale(1.9); font-family: icons !important; }
.product_favorite .icon.active::after { opacity: 1; transform: scale(1); }
.product_favorite .button { font-size: 14px; color: inherit; font-weight: 400; letter-spacing: normal; margin: 0px; padding: 5px 10px; transition: none 0s ease 0s; }
@media (min-width: 991px) {
  .product_favorite:hover, .product_favorite:hover .icon { color: rgb(3, 154, 211); }
  .product_favorite:hover .icon.active, .product_favorite:hover .icon.active::after { color: rgb(3, 169, 231); }
}
.product_item, .product_list_container { position: relative; }
.product_item { text-align: left; list-style: none; font-size: 14px; padding: 0px 7px; margin-bottom: 12px; float: left; width: 25%; min-height: 40px; }
.product_item > a { height: 100%; display: block; background: rgb(255, 255, 255); color: inherit; text-decoration: none; border-radius: 2px; box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px; }
.product_item .label { position: absolute; z-index: 2; top: 16px; right: 16px; line-height: 21px; bottom: auto; }
.product_item .gallery_counter { opacity: 0; visibility: hidden; transition: visibility 0.2s ease 0s, opacity 0.2s ease 0s; }
.body--is_mobile .product_item__disclaimer { display: none; }
.product_item__head { position: relative; padding-top: 100%; }
.product_item__image { position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; overflow: hidden; z-index: 2; background: rgb(255, 255, 255); border-bottom: 1px solid rgb(235, 235, 235); border-radius: 2px 2px 0px 0px; user-select: none; }
.product_item__image::before { content: ""; position: absolute; z-index: 1; top: 100%; left: 0px; right: 0px; box-shadow: rgba(0, 0, 0, 0.6) 0px 0px 90px 20px; }
.product_item__image::after { content: ""; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; background: transparent; transition: background 0.3s ease 0s; }
.product_item__image img { position: absolute; top: 50%; left: 0px; transform: translateY(-50%); display: inline-block; vertical-align: middle; width: 100%; height: auto; border-radius: 2px 2px 0px 0px; }
.product_item__image svg { width: 100%; height: 100%; }
.product_item__location { color: rgb(255, 255, 255); font-size: 14px; line-height: normal; position: absolute; left: 16px; bottom: 16px; z-index: 2; max-width: 66%; background-color: rgba(0, 0, 0, 0.2); border-radius: 2px; padding: 0px 3px 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product_item__location .icon--delivery { margin-top: 1px; margin-right: 5px; width: 18px; height: 18px; float: left; background: url("/build/images/delivery.76f681.svg") 50% center / contain no-repeat; }
.product_item__payment { position: absolute; bottom: 22px; right: 20px; z-index: 21; display: flex; flex-direction: column; align-items: center; width: 24px; height: 46px; border-radius: 2px; line-height: normal; }
.product_item__payment .icon { font-size: 20px; color: rgb(89, 122, 158); display: inline-block; vertical-align: middle; line-height: 24px; }
.product_item__payment .icon::before { position: relative; left: -2px; }
.product_item__payment .status_badge__icon { width: 20px; height: 20px; margin-left: 0px; }
.product_item__payment .status_badge__icon--deal { width: 24px; height: 24px; margin-bottom: 2px; }
.product_item__payment .tooltip_container { display: inline-block; vertical-align: top; }
.product_item__payment .tooltip_container:first-child { margin-top: 0px; }
.product_item__payment .status_badge__icon { width: 24px; height: 24px; display: inline-block; vertical-align: middle; background-size: 24px; }
.product_item__payment .status_badge__icon:first-child { margin-right: 0px; }
.product_item__payment .status_badge__icon--promoted { background-color: transparent; background-size: 20px; }
.product_item__payment .status_badge__icon--promoted-fast-sale { background-image: url("/build/images/fastsell.6bbe72.svg"); }
.product_item__payment .status_badge__icon--promoted-fast-sale-lite { background-image: url("/build/images/fastsell-lite.ade1cf.svg"); border-radius: 0px; }
.product_item__content { padding: 16px; border-radius: 0px 0px 2px 2px; border-bottom: 1px solid transparent; position: relative; min-height: 83px; }
.product_item__favorite { right: 6px; padding: 0px; bottom: 8px; z-index: 21; position: absolute !important; }
.product_item__favorite.active:hover .icon::after, .product_item__favorite:hover .icon, .product_item__favorite:hover .icon::after { color: rgb(255, 255, 255) !important; }
.product_item__favorite .icon { font-size: 22px; color: rgb(255, 255, 255); line-height: 18px; display: inline-block; position: relative; padding: 9px; margin-right: 0px; }
.product_item__favorite .icon::after { top: 9px; right: 9px; left: auto; color: rgb(255, 255, 255); }
.product_item__description { color: rgb(57, 57, 57); font-size: 16px; line-height: 1; margin-bottom: 4px; padding-right: 25px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; display: flex; align-items: center; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product_item__description > :first-child { padding: 4px 0px; }
@media (max-width: 575px) {
  .product_item__description > :first-child { padding: 0px; }
}
.product_item__description--promoted { border-radius: 2px; background-image: linear-gradient(256deg, rgb(255, 171, 71), rgb(255, 114, 34)); color: rgb(255, 255, 255); }
@media (min-width: 575px) {
  .product_item__description--promoted { padding: 4px !important; }
}
@media (max-width: 575px) {
  .product_item__description--promoted { padding: 0px 2px !important; }
}
.product_item__description .product_item__old-price { font-weight: 400; }
.product_item__description--free { font-size: 14px; }
.product_item__title { color: rgb(57, 57, 57); font-size: 14px; line-height: 20px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product_item__title--promoted { padding-right: 25px; }
.product_item__date { display: none; }
.product_item--empty .product_figure { box-shadow: none; height: 100%; background: rgb(255, 255, 255); border-radius: 2px; }
.product_item__notification { display: none; width: 6px; height: 6px; border-radius: 10px; background: rgb(247, 80, 89); right: 15px; padding: 0px; bottom: -27px; position: absolute; z-index: 21; }
.product_item--with-notification .product_item__content { background-color: rgb(230, 245, 251); }
.product_item--with-notification .product_item__notification { display: inline; }
.product_item--blank .product_item__blank_box { height: 100%; display: block; background: rgb(255, 255, 255); color: inherit; text-decoration: none; border-radius: 2px; box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px; }
.product_item--blank .product_item__image { background-color: rgb(235, 235, 235); }
.product_item--blank .product_item__image::after, .product_item--blank .product_item__image::before { content: none; }
.product_item--blank .product_item__description { position: relative; }
.product_item--blank .product_item__description::after { position: absolute; top: 0px; bottom: 0px; left: 0px; width: 50%; height: 12px; margin: auto; content: ""; border-radius: 100px; background-color: rgb(235, 235, 235); }
.product_item--blank .product_item__title { position: relative; }
.product_item--blank .product_item__title::after { position: absolute; top: 0px; bottom: 0px; left: 0px; width: 80%; height: 12px; margin: auto; content: ""; border-radius: 100px; background-color: rgb(235, 235, 235); }
.product_item--gpt { position: relative; }
.product_list--type_inline .product_item--gpt::after { content: ""; position: absolute; bottom: -1px; right: 8px; left: 180px; height: 1px; background: rgb(235, 235, 235); }
.product_list--type_inline .product_item--gpt:hover .gpt_banner { box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px !important; }
.product_list--type_inline .product_item--gpt:hover::after { display: none; }
.product_item--gpt > .gpt_banner { overflow: hidden; position: relative; top: 0px; left: 0px; width: 100%; height: 100%; padding-top: calc(100% + 83px); border-radius: 2px; box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px; background: rgb(235, 235, 235); }
.product_list--type_inline .product_item--gpt > .gpt_banner { padding-top: 0px; box-shadow: none; }
.product_item--gpt > .gpt_banner > div { position: absolute; top: 0px; left: 0px; width: 100%; height: calc(100% + 83px) !important; }
.product_item--gpt > .gpt_banner > div > iframe { background: rgb(255, 255, 255); width: 100%; height: 100% !important; }
@media (max-width: 575px) {
  .product_item--gpt .gpt_banner { box-shadow: none; }
}
.product_item--ad .product_item__comment { height: 40px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product_item--ad .product_item__description { -webkit-line-clamp: 2; background-color: transparent; display: -webkit-box; white-space: inherit; padding-right: 0px; }
.product_item--ad .product_item__disclaimer { position: absolute; right: 20px; top: 16px; font-size: 20px; color: rgb(255, 255, 255); padding: 0px; z-index: 101; }
.product_list--type_inline .product_item--ad .product_item__disclaimer { right: 27px; color: rgb(143, 143, 143); }
.product_list--type_inline .product_item--ad .product_item__disclaimer .tooltip_disclaimer { right: -2px; left: -142px; text-align: right; }
.product_list--type_inline .product_item--ad .product_item__disclaimer .tooltip_disclaimer .tooltip_text::after { left: auto; right: 6px; }
.product_list--type_inline .product_item--ad .product_item__content { padding-right: 20px; }
.product_item--ad .product_item__comment { -webkit-line-clamp: 2; background-color: transparent; white-space: inherit; display: none; }
.product_item--ad > a .product_item__image::after, .product_item--ad > a::after { background: rgba(0, 0, 0, 0.16); }
.product_item--ad > a .gallery_counter { opacity: 1; font-size: 14px; padding-top: 0px; padding-bottom: 0px; background-color: rgb(255, 204, 105); visibility: visible; color: rgb(57, 57, 57); }
.product_item--ad > a:hover .product_item__image::after, .product_item--ad > a:hover::after { background-color: rgba(57, 57, 57, 0.56) !important; }
.product_list--type_inline .product_item--ad .product_item__comment { display: -webkit-box; }
.product_list--type_inline .product_item--ad .product_item__title { display: block; color: rgb(3, 154, 211); font-size: 14px; line-height: 1.43; position: absolute; bottom: 14px; left: 0px; margin: 0px; }
@media (min-width: 992px) {
  .product_item > a:hover .product_item__image::after, .product_item > a:hover::after { background: rgba(0, 0, 0, 0.16); }
  .product_item > a:hover .gallery_counter { opacity: 1; visibility: visible; }
  .product_item > a:hover .gallery_counter--discount.gallery_counter--tile { opacity: 0; visibility: hidden; }
}
@media (max-width: 991px) {
  .product_item__favorite { right: 0px; bottom: 0px; }
  .product_item__favorite .icon { padding: 20px 12px 18px; font-size: 20px; }
  .product_item__favorite .icon::after { right: 12px; top: 20px; }
  .product_item__location { background-color: rgba(0, 0, 0, 0.2); }
}
@media (max-width: 767px) {
  .product_item--ad .product_item__disclaimer { right: 12px; top: 10px; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .product_item > a { box-shadow: none; }
  .product_item > a:hover { background-color: transparent; box-shadow: none; }
  .product_item__payment { bottom: 16px; right: 6px; width: 28px; height: 44px; }
  .product_item__payment .status_badge__icon { width: 16px; height: 16px; }
  .product_item__payment .status_badge__icon--deal { width: 20px; height: 20px; margin-bottom: 5px; }
  .product_item .label { right: 12px; top: 12px; }
  .product_item .gallery_counter { left: 12px; top: 12px; }
  .product_item__image, .product_item__image img { border-radius: 2px; }
  .product_item__location { left: 8px; bottom: 8px; }
  .product_item__favorite { right: -5px; bottom: -5px; }
  .product_item__favorite .icon { padding-bottom: 14px; }
  .product_item__content { padding: 12px 0px; min-height: 66px; }
  .product_item__description { margin-bottom: 2px; }
  .product_item__payment .icon { line-height: 18px; font-size: 16px; vertical-align: top; }
  .product_item__payment .status_badge__icon { vertical-align: top; background-size: 20px; }
  .product_item__payment .status_badge__icon:first-child { margin-left: 0px; }
  .product_item__payment .status_badge__icon--promoted { background-size: 15px; }
  .product_item--blank .product_item__blank_box { box-shadow: none; }
}
.product_list_action { position: absolute; top: 0px; left: 0px; height: 46px; background-color: rgb(255, 255, 255); visibility: hidden; pointer-events: none; opacity: 0; box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px; z-index: 42; border-bottom-left-radius: 2px; border-bottom-right-radius: 2px; transition: height 0.2s ease-in 0s; padding: 0px 16px; text-align: center; }
.product_list_action::after { position: absolute; content: " "; top: -1px; left: 0px; height: 1px; width: 100%; background-color: rgb(255, 255, 255); }
.product_list_action .button { padding-top: 5px; padding-bottom: 6px; width: 100%; background-color: rgb(3, 154, 211) !important; color: rgb(255, 255, 255) !important; }
.product_list_action--visible { opacity: 1; pointer-events: auto; visibility: visible; }
@media (max-width: 991px) {
  .product_list_action { display: none !important; }
}
.product_list { margin: 0px -7px 25px; padding: 0px; list-style: none; overflow: hidden; }
.product_list--simple { height: 54px; font-size: 0px; overflow: hidden; margin-left: -5px !important; margin-right: -5px !important; margin-bottom: 16px !important; }
.product_list--simple .product_item { padding-left: 3px; padding-right: 3px; float: none; display: inline-block; vertical-align: top; margin-bottom: 0px; width: 72px !important; height: 54px !important; }
.product_list--simple .product_item > a { background: rgb(250, 250, 250); box-shadow: none !important; }
.product_list--simple .product_item > a::before { display: none; }
.product_list--simple .product_item__image { position: relative; border-bottom: 0px; line-height: 0 !important; }
.product_list--simple .product_item__image img { width: 66px; height: 54px; }
.product_list--type_inline { margin-bottom: 30px; }
.product_list--type_inline .gallery_counter { opacity: 1; visibility: visible; display: block !important; }
.product_list--type_inline .product_item { height: 160px; float: none; box-shadow: none; border: 0px; border-radius: 0px; margin-bottom: 14px; width: 100% !important; }
.product_list--type_inline .product_item > a { box-shadow: none; text-decoration: none; }
.product_list--type_inline .product_item > a:hover { box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; }
.product_list--type_inline .product_item > a:hover .product_item__content { border-bottom-color: transparent; }
.product_list--type_inline .product_item .product_figure { position: relative; height: 100%; }
.product_list--type_inline .product_item .product_figure::after, .product_list--type_inline .product_item .product_figure::before { content: " "; display: table; }
.product_list--type_inline .product_item .product_figure::after { clear: both; }
.product_list--type_inline .product_item .gallery_counter { top: auto; bottom: 16px; left: 16px; }
.product_list--type_inline .product_item .gallery_counter--ad { bottom: auto; top: 16px; }
.product_list--type_inline .product_item .label { top: auto; bottom: 15px; z-index: 22; }
.product_list--type_inline .product_item__head { float: left; margin-right: 20px; position: static; padding-top: 0px; }
.product_list--type_inline .product_item__head + .product_item__content { margin-left: 180px; padding-left: 0px; }
.product_list--type_inline .product_item__image { width: 160px; height: 160px; line-height: 160px; border-radius: 2px; overflow: hidden; }
.product_list--type_inline .product_item__image img { border-radius: 2px; }
.product_list--type_inline .product_item__content { height: 160px; border-bottom-color: rgb(235, 235, 235); padding: 19px 100px 16px 20px; position: relative; transition: border 0.2s ease 0s; }
.product_list--type_inline .product_item__description { font-size: 24px; line-height: 1; margin-bottom: 9px; }
.product_list--type_inline .product_item__title { font-size: 16px; color: rgb(57, 57, 57); line-height: 1.5; margin-bottom: 10px; white-space: normal; max-height: 50px; max-width: 360px; overflow-wrap: break-word; word-break: break-word; }
.product_list--type_inline .product_item__favorite { top: auto; bottom: 5px; right: 11px; }
.product_list--type_inline .product_item__favorite .icon { color: rgb(143, 143, 143) !important; }
.product_list--type_inline .product_item__favorite.active:hover .icon::after, .product_list--type_inline .product_item__favorite .icon::after, .product_list--type_inline .product_item__favorite:hover .icon, .product_list--type_inline .product_item__favorite:hover .icon::after { color: rgb(247, 80, 89) !important; }
.product_list--type_inline .product_item__favorite .icon { font-size: 22px; }
.product_list--type_inline .product_item__location { background: transparent; color: rgb(143, 143, 143); font-size: 14px; line-height: 1.14; padding: 0px; bottom: auto; left: auto; top: 15px; right: 21px; }
.product_list--type_inline .product_item .tooltip_favorite { right: 7px; left: -142px; text-align: right; }
.product_list--type_inline .product_item .tooltip_favorite .tooltip_text::after { left: auto; right: 6px; }
.product_list--type_inline .product_item__payment { top: 60px; right: 26px; bottom: auto; position: absolute; background: transparent; }
.product_list--type_inline .product_item__payment .tooltip_container:last-child .tooltip { left: -200px; right: 0px; text-align: right; }
.product_list--type_inline .product_item__payment .tooltip_container:last-child .tooltip .tooltip_text::after { left: auto; right: 6px; }
.product_list--type_inline .product_item__payment .tooltip_container:nth-last-child(2) .tooltip { left: -150px; right: -30px; text-align: right; }
.product_list--type_inline .product_item__payment .tooltip_container:nth-last-child(2) .tooltip .tooltip_text::after { left: auto; right: 36px; }
.product_list--type_inline .product_item__date { display: block; color: rgb(143, 143, 143); font-size: 14px; line-height: 1.43; position: absolute; bottom: 14px; left: 0px; }
@media (min-width: 1186px) {
  .product_list .product_item:nth-child(4n) .tooltip_disclaimer, .product_list .product_item:nth-child(4n) .tooltip_favorite { left: -142px; text-align: right; }
  .product_list .product_item:nth-child(4n) .tooltip_disclaimer .tooltip_text::after, .product_list .product_item:nth-child(4n) .tooltip_favorite .tooltip_text::after { left: auto; right: 6px; }
  .product_list .product_item:nth-child(4n) .tooltip_favorite { right: 7px; }
  .product_list .product_item:nth-child(4n) .tooltip_disclaimer { right: -2px; }
  .product_list .product_item:nth-child(4n) .product_item__payment .tooltip_container:last-child .tooltip { left: -200px; right: 0px; text-align: right; }
  .product_list .product_item:nth-child(4n) .product_item__payment .tooltip_container:last-child .tooltip .tooltip_text::after { left: auto; right: 6px; }
  .product_list .product_item:nth-child(4n) .product_item__payment .tooltip_container:nth-last-child(2) .tooltip { left: -150px; right: -30px; text-align: right; }
  .product_list .product_item:nth-child(4n) .product_item__payment .tooltip_container:nth-last-child(2) .tooltip .tooltip_text::after { left: auto; right: 36px; }
  .product_list--fav .product_item .tooltip_favorite { right: -100px; left: -100px; }
  .product_list--fav .product_item .tooltip_text::after { left: 50%; right: auto; }
  .product_list--fav .product_item:nth-child(4n) .tooltip_favorite { left: -100px; text-align: center; }
  .product_list--fav .product_item:nth-child(4n) .tooltip_favorite .tooltip_text::after { left: auto; right: 6px; }
}
@media (max-width: 1185px) and (min-width: 576px) {
  .product_list .product_item { width: 33.3333%; }
  .product_list .product_item:nth-child(3n) .tooltip_favorite { right: 7px; left: -142px; text-align: right; }
  .product_list .product_item:nth-child(3n) .tooltip_favorite .tooltip_text::after { left: auto; right: 6px; }
  .product_list .product_item:nth-child(3n) .tooltip_disclaimer { right: -2px; left: -142px; text-align: right; }
  .product_list .product_item:nth-child(3n) .tooltip_disclaimer .tooltip_text::after { left: auto; right: 6px; }
  .product_list .product_item:nth-child(3n) .product_item__payment .tooltip_container:last-child .tooltip { left: -200px; right: 0px; text-align: right; }
  .product_list .product_item:nth-child(3n) .product_item__payment .tooltip_container:last-child .tooltip .tooltip_text::after { left: auto; right: 6px; }
  .product_list .product_item:nth-child(3n) .product_item__payment .tooltip_container:nth-last-child(2) .tooltip { left: -150px; right: -30px; text-align: right; }
  .product_list .product_item:nth-child(3n) .product_item__payment .tooltip_container:nth-last-child(2) .tooltip .tooltip_text::after { left: auto; right: 36px; }
  .product_list--type_inline .product_item .tooltip_favorite { right: 8px; left: auto; }
  .product_list--fav .product_item { width: 25%; }
  .product_list--fav .product_item:nth-child(3n) .tooltip_favorite { left: -100px; right: -100px; text-align: center; }
  .product_list--fav .product_item:nth-child(3n) .tooltip_favorite .tooltip_text::after { left: 50%; right: auto; }
  .product_list--fav .product_item:nth-child(4n) .tooltip_favorite { right: 7px; left: -142px; text-align: right; }
  .product_list--fav .product_item:nth-child(4n) .tooltip_favorite .tooltip_text::after { left: auto; right: 6px; }
}
@media (max-width: 991px) {
  .product_list .product_item { width: 33.3333%; }
  .product_list .product_item .tooltip_favorite { margin-bottom: -15px; }
  .product_list .product_item:nth-child(3n) .tooltip_favorite { right: 9px; }
  .product_list--type_inline .product_item__favorite { bottom: 0px; right: 7px; }
  .product_list--type_inline .product_item .tooltip_favorite { right: 10px; left: auto; }
  .product_list--fav .product_item:nth-child(4n) .tooltip_favorite { left: -100px; right: -100px; text-align: center; }
  .product_list--fav .product_item:nth-child(4n) .tooltip_favorite .tooltip_text::after { left: 50%; right: auto; }
  .product_list--fav .product_item:nth-child(3n) .tooltip_favorite { text-align: right; }
  .product_list--fav .product_item:nth-child(3n) .tooltip_favorite .tooltip_text::after { left: auto; right: 6px; }
}
@media (max-width: 767px) {
  .product_list { margin: 0px -4px; }
  .product_list .product_item { padding: 0px 4px; margin-bottom: 10px; }
  .product_list--type_inline { margin-right: -10px; }
  .product_list--type_inline .product_item > a { border-radius: 0px; margin-right: -4px; padding-right: 8px; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .product_list .product_item { width: 50%; }
  .product_list .product_item__image img { width: 100%; height: auto; }
  .product_list .product_item:nth-child(2n) .tooltip_disclaimer { right: -2px; left: -142px; text-align: right; }
  .product_list .product_item:nth-child(2n) .tooltip_disclaimer .tooltip_text::after { left: auto; right: 6px; }
  .product_list--type_inline .product_item { height: 120px; }
  .product_list--type_inline .product_item > a:hover { background: transparent; box-shadow: none; }
  .product_list--type_inline .product_item > a:hover .product_item__content { border-bottom-color: rgb(235, 235, 235); }
  .product_list--type_inline .product_item .label { top: auto; right: auto; bottom: 8px; left: 130px; z-index: 22; }
  .product_list--type_inline .product_item .gallery_counter { opacity: 1; visibility: visible; left: 9px; bottom: 9px; }
  .product_list--type_inline .product_item .gallery_counter--ad { top: 9px; bottom: auto; }
  .product_list--type_inline .product_item__head { margin-right: 10px; }
  .product_list--type_inline .product_item__head + .product_item__content { margin-left: 130px; }
  .product_list--type_inline .product_item__image { width: 120px; height: 120px; line-height: 120px; }
  .product_list--type_inline .product_item__description { font-size: 16px; line-height: 1; margin-bottom: 8px; }
  .product_list--type_inline .product_item__title { font-size: 14px; line-height: 1.24; margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .product_list--type_inline .product_item__content { height: 120px; padding-top: 7px; padding-bottom: 7px; padding-right: 20px; }
  .product_list--type_inline .product_item__location { font-size: 12px; line-height: 16px; top: 7px; right: 2px; }
  .product_list--type_inline .product_item__favorite { right: 2px; bottom: -5px; }
  .product_list--type_inline .product_item__favorite .icon { padding-right: 0px; font-size: 20px; padding-left: 18px; }
  .product_list--type_inline .product_item__favorite .icon::after { right: 0px; }
  .product_list--type_inline .product_item__payment { top: 29px; right: 7px; padding: 0px; margin-right: 0px; }
  .product_list--type_inline .product_item__date { bottom: 9px; font-size: 12px; line-height: 16px; }
  .product_list--type_inline .product_item--ad .product_item__disclaimer { right: 9px; top: 4px; }
}
.product_figure { padding: 0px; margin: 0px; }
.product_owner { padding: 24px 30px; position: relative; }
.product_owner .button { margin-left: -16px; margin-bottom: -5px; }
.product_owner .user { display: table; width: 100%; table-layout: fixed; }
.product_owner .user__image, .product_owner .user__image--empty { padding-right: 18px; border-radius: 0px; display: table-cell; vertical-align: top; background: transparent; font-size: 0px; line-height: 0; float: none !important; width: 78px !important; }
.product_owner .user__image--empty a, .product_owner .user__image a { display: block; border-radius: 100px; }
.product_owner .user__rating { margin-top: 5px; }
.product_owner .user__name a { display: inline-block; max-width: 89%; vertical-align: top; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product_owner .user__info { display: table-cell; vertical-align: middle; padding: 0px !important; }
@media (max-width: 991px) {
  .product_owner.card { padding: 0px !important; border-bottom: 0px !important; }
  .product_owner .user { margin-bottom: 0px; }
  .product_owner .user__image, .product_owner .user__image--empty { padding-right: 14px; }
  .product_owner .button { position: absolute; right: 0px; top: 50%; margin-top: -20px; z-index: 1; }
  .product_owner .product_list { display: none; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .product_owner .button { display: none; }
  .product_owner .user--simple .user__name { font-size: 15px; }
  .product_owner .user--simple .user__rating { margin: 0px -2px; }
  .product_owner .user--simple .user__rating .rating__item { font-size: 16px; width: 18px; height: 18px; }
  .product_owner .user--simple .user__rating .rating__item--half::after { width: 9px; left: 0px; }
  .product_owner .user--simple .user__type { margin-bottom: 4px; }
  .product_owner .user--simple .user__image, .product_owner .user--simple .user__image--empty { padding-right: 12px; height: 40px; width: 52px !important; }
  .product_owner .user--simple .user__image--empty img, .product_owner .user--simple .user__image img { width: 40px; height: 40px; }
}
.product_map_title { font-size: 14px; line-height: normal; font-weight: 400; letter-spacing: normal; color: rgb(140, 140, 140); margin-bottom: 17px; }
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .product_map_title { text-align: center; display: none; margin-top: 10px; }
  .product_map_title--show_exact_address { display: block; }
}
.product_map_title--show_exact_address { color: rgb(57, 57, 57); font-size: 16px; line-height: 22px; margin-bottom: 15px; }
@media (max-width: 767px) {
  .product_map_title--show_exact_address { font-size: 14px; margin-bottom: 10px; }
}
.product_map { position: relative; }
.product_map.card { padding: 0px !important; }
.product_map .map_location { position: absolute; left: 0px; right: 0px; top: 10px; text-align: center; padding: 0px; background: transparent; z-index: 40; overflow: visible; margin: 0px 50px; }
.product_map .map_location__label { background-color: rgb(255, 255, 255); display: inline-block; vertical-align: top; padding: 6px 20px 7px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; border-radius: 2px; box-shadow: none; }
.product_map .map_location__label::after { top: 100%; left: 50%; border-style: solid; border-image: initial; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; border-color: rgb(255, 255, 255) rgba(255, 255, 255, 0) rgba(255, 255, 255, 0); border-width: 10px; margin-left: -10px; }
.product_map .icon { display: none; }
@media (max-width: 991px) {
  .product_map { margin-bottom: 0px; }
}
.product_gallery { line-height: 0; margin-bottom: 9px; height: 490px; background: url("/build/images/cover.4ed421.jpg") 50% center / 280px no-repeat rgb(236, 236, 236); position: relative; }
.product_gallery .small-landscape .product_photo { top: 50%; left: 50%; height: 100%; display: block; transform: translate(-50%, -50%); width: auto !important; max-width: none !important; }
.product_gallery .slick-list { position: relative; overflow: hidden; box-shadow: rgb(245, 245, 245) 0px 0px 0px 1px inset; }
.product_gallery .slick-next, .product_gallery .slick-prev { display: none !important; }
.product_gallery .slick-dots { position: absolute; left: 20px; bottom: 20px; font-size: 0px; line-height: 0; display: none; z-index: 2; background-color: rgba(0, 0, 0, 0.2); border-radius: 2px; padding: 0px; margin: 0px; list-style: none; }
.product_gallery .slick-dots li { width: 12px; height: 12px; margin-right: 20px; border-radius: 100px; display: inline-block; vertical-align: middle; background-color: rgb(255, 255, 255); opacity: 0.5; transition: opacity 0.3s ease 0s; outline: none !important; }
.product_gallery .slick-dots li:last-child { margin-right: 0px !important; }
.product_gallery .slick-dots button { border: 0px; padding: 0px; }
.product_gallery .slick-dots .slick-active { opacity: 1; }
.product_gallery .opacity-transition { transition: opacity 1s linear 0s; }
.product_gallery .blurred-photo { position: relative; filter: blur(20px); transform: scale(1); transition: opacity 1s linear 0s; }
.body--is_mobile .product_gallery { overflow: hidden; }
@media (min-width: 768px) {
  .body--is_mobile .product_gallery { overflow: hidden; }
  .body--is_mobile .product_gallery .slick-slide { vertical-align: top; }
}
@media (max-width: 991px) {
  .product_gallery { margin: -15px -15px 0px; height: 500px; overflow: hidden; }
  .product_gallery .slick-dots { display: block; }
  .product_gallery .slick-track { height: 500px; }
}
@media (max-width: 767px) {
  .product_gallery { margin: -15px -10px 0px; height: 280px; }
  .product_gallery .slick-track { height: 280px; }
  .product_gallery .slick-dots { bottom: 10px; left: 10px; padding: 5px 8px; }
  .product_gallery .slick-dots li { width: 7px; height: 7px; margin-right: 10px; }
}
.product_properties { margin: 0px; padding: 0px; list-style: none; color: rgb(57, 57, 57); font-size: 14px; line-height: 24px; width: 100%; }
.product_properties__item { margin-bottom: 6px; }
.product_properties__label { color: gray; padding: 5px 10px 10px 0px; width: 145px; white-space: nowrap; vertical-align: top; }
.product_properties__value { padding: 5px 0px 10px; vertical-align: top; }
.product_properties a { color: rgb(57, 57, 57); text-decoration: none; }
.product_properties a:hover { color: rgb(3, 154, 211); }
@media (max-width: 767px) {
  .product_properties__item { margin-bottom: 0px; line-height: 23px; }
  .product_properties__label { width: 50%; white-space: normal; }
  .product_properties__label, .product_properties__value { padding-bottom: 10px; padding-top: 0px; line-height: normal; }
  .product_properties a:hover { color: rgb(57, 57, 57); }
}
@media (min-width: 768px) {
  .product_properties { font-size: 16px; line-height: 24px; }
}
.product_properties_container { margin-bottom: 10px; position: relative; }
.product_properties_container .slick-slide { vertical-align: top; }
.product_properties_container .button--link { color: rgb(3, 154, 211); font-size: 16px; margin-top: 10px; }
.product_properties_container .button--flat, .product_properties_container .button--flat_disabled { background-color: transparent; margin: 10px 0px 0px -13px; }
.product_properties_container .hide_more, .product_properties_container .show_more { display: none; }
@media (max-width: 767px) {
  .product_properties_container .toggle_show { font-size: 14px; padding: 6px 15px; margin: 4px -15px -6px; }
}
.product_properties_container--overfilled .product_properties_wrapper { height: 205px; overflow: hidden; }
@media (max-width: 767px) {
  .product_properties_container--overfilled .product_properties_wrapper { height: 150px; }
}
.product_properties_container--overfilled .show_more { display: block; }
.product_properties_container--overfilled.active .product_properties_wrapper { height: auto; }
.product_properties_container--overfilled.active .hide_more { display: block; }
.product_properties_container--overfilled.active .show_more { display: none; }
@media (max-width: 767px) {
  .product_properties_container { margin-bottom: 0px; }
}
.product_tel { padding: 5px 0px 7px; }
.product_tel__value { font-size: 20px; font-weight: 400; font-style: normal; letter-spacing: 0.7px; text-align: center; color: rgb(57, 57, 57); display: inline-block; vertical-align: middle; text-decoration: none; line-height: normal; margin-bottom: 7px; }
.product_tel__value:empty { display: none; }
.product_tel .button--green { font-size: 14px; padding: 5px 6px 4px; margin-left: 5px; letter-spacing: 0.5px; position: relative; top: 1px; margin-bottom: 0px !important; background: transparent !important; }
.product_tel .hint { line-height: 1.29; font-size: 14px; color: rgba(0, 0, 0, 0.54); margin-top: 0px; }
.modal .product_tel { text-align: center; }
.product_message { color: rgb(57, 57, 57); }
.product_message.card { background: rgb(245, 245, 245); box-shadow: none; border: 0px !important; }
.product_message .button { margin-bottom: 0px; font-size: 14px; letter-spacing: 0px; }
.product_message .button:hover { background: transparent; }
.product_message__text { line-height: 20px; }
.product_message__footer { margin: 20px -20px 0px -4px; text-align: left; overflow: hidden; }
.product_message--alert { color: rgb(255, 255, 255); border-radius: 2px; padding: 12px 20px; overflow: hidden; background-color: rgb(247, 80, 89) !important; }
.product_message--alert .button, .product_message--alert .button .icon, .product_message--alert .product_message__text { color: rgb(255, 255, 255); }
@media (min-width: 992px) {
  .product_message.card { padding: 16px 20px !important; }
}
@media (max-width: 991px) {
  .product_message.card { margin: 0px -15px; padding-left: 15px; padding-right: 15px; }
  .product_message__footer { margin-left: 0px; margin-right: 0px; text-align: right; }
  .product_message__footer .button { padding-left: 15px; padding-right: 15px; }
}
@media (max-width: 767px) {
  .product_message.card { margin-left: -10px; margin-right: -10px; padding-left: 10px; padding-right: 10px; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .product_message__footer { text-align: left; margin-right: -10px; }
  .product_message__footer .button { padding-left: 10px; padding-right: 10px; }
  .product_message__footer .button--flat:first-child, .product_message__footer .button--flat_disabled:first-child { float: left; }
}
.product_section { margin-bottom: 25px; }
.product_section .product_list { margin-bottom: 0px; padding-top: 1px; }
.product_payment_container { margin-top: 25px; }
.product_payment_container .fixed_buttons .status_badge__icon { display: inline-block; vertical-align: middle; width: 24px; height: 20px; margin: 0px 6px 0px 0px; float: left; line-height: 10px; position: relative; top: -1px; background-size: 24px; }
@media (max-width: 991px) {
  .product_payment_container { margin-top: 0px; padding-top: 55px; }
  .product_payment_container + .product__buttons { position: absolute; top: 15px; left: 0px; right: 0px; margin-top: 0px; }
}
.product_buttons_container { position: relative; }
.product_buttons_container:empty { display: none; }
@media (max-width: 991px) {
  .product_buttons_container .fixed_buttons { padding-top: 10px; background-color: rgba(57, 57, 57, 0.9); }
  .product_buttons_container .fixed_buttons::after, .product_buttons_container .fixed_buttons::before { display: none; }
  .product_buttons_container .product__price { font-size: 20px; color: rgb(255, 255, 255); line-height: 1.2; margin-bottom: 0px; margin-top: -3px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
  .product_buttons_container .product__price + span { font-size: 14px; color: rgb(249, 249, 249); line-height: 1.43; }
}
.product_order_data { margin-top: -14px; margin-bottom: 20px; }
.product_order_data .hint { font-size: 14px; }
@media (max-width: 991px) {
  .product_order_data { margin-top: 15px; margin-bottom: 15px; padding-top: 12px; border-top: 1px solid rgb(235, 235, 235); }
}
.product_promoted { border-top: 1px solid rgb(235, 235, 235); padding-top: 24px; margin-top: 28px; }
.product_promoted__title { font-size: 16px; color: rgb(57, 57, 57); margin-bottom: 5px; }
.product_promoted .hint { font-size: 14px; color: rgb(143, 143, 143); }
.product_promoted .status_badge__icon { float: right; cursor: pointer; }
@media (max-width: 991px) {
  .product_promoted { margin-bottom: 15px; padding-top: 20px; }
  .product_promoted .status_badge__icon { margin-top: 3px; }
}
.product_promoted_button { margin-top: 28px; }
.product_promoted_button .button { margin-bottom: 10px; }
.product_buttons_group { }
.product_buttons_group::after, .product_buttons_group::before { content: " "; display: table; }
.product_buttons_group::after { clear: both; }
.product_buttons_group + .status_badge_list { margin-top: 5px; margin-bottom: 14px; }
.product_row { border-top: 1px solid rgb(235, 235, 235); padding: 24px 0px; }
.product_row:first-child, .product_row:first-of-type { border-top: 0px; }
.product_row--map { padding-bottom: 0px !important; }
@media (max-width: 767px) {
  .product_row { padding: 20px 0px; }
}
.product_discount_label_container { position: absolute; top: 0px; left: 0px; overflow: hidden; height: 64px; width: 64px; z-index: 3; }
@media (max-width: 991px) {
  .product_discount_label_container { height: 80px; width: 80px; }
}
.product_discount_label { position: absolute; height: 91px; width: 91px; transform: rotate(-45deg) translateY(-70%); overflow: hidden; }
.product_discount_label::before { content: ""; border-radius: 4px 0px 0px; background: linear-gradient(135deg, rgb(0, 219, 255), rgb(3, 154, 211) 64%); height: 100%; width: 100%; display: block; transform: rotate(45deg) translate(49%, 49%); position: absolute; }
@media (max-width: 991px) {
  .product_discount_label { height: 80px; width: 80px; }
}
.product_discount_text { position: absolute; bottom: -10px; left: 50%; font-size: 16px; color: rgb(255, 255, 255); line-height: 24px; transform: translate(-50%, -50%); text-align: center; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
.product_item__old-price { color: rgb(143, 143, 143); line-height: 16px; font-size: 13px; margin-left: 8px; text-decoration: line-through; vertical-align: middle; }
@media (max-width: 991px) {
  .product_item__old-price { font-size: 12px; }
}
@media (max-width: 350px) {
  .product_item__old-price { font-size: 10px; margin-left: 2px; }
}
@media (max-width: 991px) {
  .product_item__content--list .product_item__old-price { display: block; margin-left: 0px; margin-top: 8px; font-size: 12px; line-height: 14px; }
}
@media (max-width: 991px) {
  .product_payment_container .buy_button_container { width: 120px; }
}
@media (max-width: 991px) {
  .product_payment_container .safe_deal_container { width: calc(100% - 120px); }
}
.product__price--old { color: rgba(255, 255, 255, 0.64); font-size: 14px; line-height: 24px; vertical-align: middle; display: inline-block; margin-left: 12px; text-decoration: line-through; }
.safe_deal_text { color: rgba(255, 255, 255, 0.64); font-size: 12px; }
.product_figure .status_badge__icon { width: auto; height: auto; }
.product_card { background: rgb(255, 255, 255); }
.product_card .card:not(.product_message) { box-shadow: none; padding: 20px 30px; border: 1px solid rgb(235, 235, 235); border-radius: 2px; margin-bottom: 20px; position: relative; }
.product_card .card:not(.product_message):last-child, .product_card .card:not(.product_message):last-of-type { margin-bottom: 0px; }
.product_card hr { border-width: 1px 0px 0px; border-right-style: initial; border-bottom-style: initial; border-left-style: initial; border-right-color: initial; border-bottom-color: initial; border-left-color: initial; border-image: initial; border-top-style: solid; border-top-color: rgb(235, 235, 235); }
.product_card__title { overflow-wrap: break-word; word-break: break-word; }
.product_card__status { margin-top: 15px; margin-bottom: 6px; color: rgb(255, 92, 74); }
.product_card__favorite { min-width: 189px; text-align: center; margin-top: 18px; }
.product_card__favorite .button { width: 210px; text-align: left; padding-top: 8px; }
.product_card__favorite .icon { margin-right: 12px; font-size: 20px; }
.product_card__block { position: static; }
.product_card__button { width: 100%; margin-bottom: 9px; }
.product_card__button + .product_card__button .button { background-color: transparent; border: 1px solid rgb(3, 154, 211); color: rgb(3, 154, 211); }
.product_card__button + .product_card__button .button:hover { background: rgba(3, 154, 211, 0.1); }
.product_card__delete > span { padding: 0px !important; }
.product_card__delete .icon { font-size: 22px; color: rgb(143, 143, 143); position: relative; top: -2px; margin-right: 12px; vertical-align: middle; transition: color 0.2s ease 0s; }
.product_card__delete:hover .icon { color: rgb(247, 80, 89); }
@media (max-width: 991px) {
  .product_card hr { display: none; }
  .product_card .card:not(.product_message) { border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); border-radius: 0px; padding: 15px 0px; margin: 0px; }
  .product_card__favorite { position: absolute; top: 20px; right: 5px; margin: 0px; overflow: hidden; }
  .product_card__button { width: 50%; padding: 0px 4px; float: left; margin-bottom: 0px; }
  .product_buttons_not_owner .product_card__button .button--bordered:not(.button--disabled) { background-color: rgb(3, 154, 211) !important; color: rgb(255, 255, 255) !important; }
}
@media (max-width: 991px) {
  .product_card .product_card__block_inner .card.card--owner.card--can_promote { display: flex; flex-flow: column; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote ._product_owner_actions { order: 0; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote .product_buttons_container { order: 1; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote .product_owner_actions_inner { display: flex; flex-flow: column; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote .product_owner_actions_inner .product_promoted_button { order: 0; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote .product_owner_actions_inner .status_badge_list { order: 1; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote .product__owner { margin-top: 10px; }
  .product_card .product_card__block_inner .card.card--owner.card--can_promote .product_promoted_button { margin-top: 25px; }
}
@media (max-width: 767px) {
  .product_card .card:not(.product_message) { padding: 14px 0px; margin: 0px; }
}
@media (min-width: 992px) {
  .product_card { position: relative; margin-bottom: 20px; }
  .product_card__main { overflow: hidden; padding-right: 16px; background-color: rgb(255, 255, 255); padding-bottom: 20px; border-bottom: 1px solid rgb(235, 235, 235); }
  .product_card__title { margin-bottom: 12px; }
  .product_card__inner { padding-right: 14px; }
  .product_card__block--aside { float: right; width: 370px; position: absolute; top: 0px; right: 0px; bottom: 0px; }
  .product_card__block--main { float: none; width: auto; margin-right: 370px; }
  .product_card__block_inner { overflow: hidden; }
  .product_card__block_inner--fixed { position: fixed; top: -1px; z-index: 10; background: rgb(255, 255, 255); width: 340px; }
  .product_card__block_inner--absolute { position: absolute; bottom: 0px; top: auto; }
}
@media (max-width: 575px) {
  .product_card .card:not(.product_message) { padding: 11px 0px 15px; }
  .product_card__favorite { min-width: 0px; line-height: 24px; right: 0px; top: 8px; }
  .product_card__favorite .button { font-size: 0px; width: auto; padding: 8px; }
  .product_card__favorite .button .icon { font-size: 24px; margin-right: 0px; color: rgb(143, 143, 143); }
  .product_card__favorite .button .icon.active::after { color: rgb(247, 80, 89); }
}
.product_card .product__button_statistic_container { position: absolute; display: inline-block; top: 20px; right: 30px; }
.product_card .product__button_statistic_container .product__button_statistic { background: rgb(3, 154, 211); display: block; width: 32px; height: 32px; position: relative; border-radius: 50%; text-align: center; line-height: 32px; padding: 0px; }
.product_card .product__button_statistic_container .product__button_statistic__image { width: 19px; height: 19px; vertical-align: -4px; }
@media (max-width: 991px) {
  .product_card .product__button_statistic_container { top: 11px; right: 15px; }
}
@media (max-width: 767px) {
  .product_card .product__button_statistic_container { top: 11px; right: 0px; }
}
.products_similar { clear: both; margin-bottom: 50px; }
.products_similar .product_list { margin-bottom: 0px; }
@media (min-width: 576px) {
  .products_similar { border-top: 1px solid rgb(235, 235, 235); }
}
@media (min-width: 992px) {
  .products_similar { margin-bottom: 0px; border-top: 0px; }
}
@media (max-width: 767px) {
  .products_similar { margin-bottom: 25px; }
}
.products_similar_card { padding-top: 30px; }
.products_similar_card > h2 { margin-bottom: 15px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
@media (max-width: 575px) {
  .products_similar_card > h2 { font-size: 14px; text-align: center; color: rgb(143, 143, 143); white-space: nowrap; font-family: "Open Sans", -apple-system, Roboto, "Helvetica Neue", sans-serif; font-weight: 400; }
}
.products_similar_card .product_item__location { display: none; }
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .products_similar_card { padding-top: 24px; }
  .products_similar_card > h3 { color: rgb(143, 143, 143); font-size: 14px; text-align: center; }
}
@media (max-width: 991px) {
  .products_similar_card { padding-top: 20px; }
}
@media (min-width: 992px) {
  .products_similar_card { margin-right: 380px; margin-bottom: -13px; }
  .products_similar_card .product_list { margin-left: -6px; margin-right: -6px; }
  .products_similar_card .product_item { height: 264px; padding-left: 6px; padding-right: 6px; }
  .products_similar_card .product_item__content { padding: 13px 14px 10px; }
  .products_similar_card .product_item__image { height: 190px; line-height: 184px; }
  .products_similar_card .product_item__description { margin-bottom: 3px; }
}
.sidebar { position: relative; }
.sidebar .button--back, .sidebar .button--close { margin-top: -20px; right: auto; left: 9px; }
.sidebar__content, .sidebar__header { padding: 19px 44px 23px; }
.sidebar__header { position: absolute; top: 0px; width: 100%; left: 0px; border-bottom: 1px solid rgb(234, 234, 234); background: rgb(255, 255, 255); z-index: 123; text-align: center; }
.sidebar__title { margin: 0px; font-size: 24px; line-height: 30px; color: rgb(57, 57, 57); font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.sidebar__content { position: relative; padding-top: 91px; overflow: auto; width: 100%; }
.sidebar--show { display: block !important; }
.sidebar--nav .button--flat, .sidebar--nav .button--flat_disabled { width: 100%; text-align: left; padding-left: 12px; margin-bottom: -4px; }
.sidebar--nav .button--flat .icon, .sidebar--nav .button--flat_disabled .icon { margin-right: 14px; position: relative; top: -1px; }
@media (min-width: 992px) {
  .sidebar + .main { padding-top: 17px; }
}
@media (max-width: 991px) {
  .sidebar__content, .sidebar__header { padding: 12px 30px 13px; }
  .sidebar__title { font-size: 20px; }
  .sidebar__content { padding-top: 72px; }
  .sidebar__scroll { overflow: hidden auto; bottom: 0px; position: absolute; top: 56px; width: 100%; }
  .sidebar--nav { left: 0px; top: 0px; bottom: 0px; z-index: 123; background: rgb(250, 250, 250); padding: 0px; user-select: none; }
  .sidebar--nav, .sidebar--nav .sidebar__header { position: fixed; max-width: 100%; width: 300px; }
  .sidebar--nav .button--flat, .sidebar--nav .button--flat_disabled { display: none; }
}
@media (max-width: 991px) {
  .sidebar { background: rgb(255, 255, 255); }
  .sidebar .sidebar__title { text-align: center; }
  .sidebar__content { position: absolute; padding-top: 13px; top: 59px; }
  .sidebar .button--back + .sidebar__title, .sidebar .button--close + .sidebar__title { padding: 0px 20px; }
  .sidebar--nav { overflow: hidden; width: 100%; }
  .sidebar--nav .sidebar__content, .sidebar--nav .sidebar__header { width: 100%; }
  .sidebar .sidebar__scroll { }
}
@media (max-width: 767px) {
  .sidebar .button--back, .sidebar .button--close { left: 2px; }
}
.mrg-banner-container { text-align: center; margin-bottom: 8px; }
@media (max-width: 991px) {
  .mrg-banner-container { display: none; }
}
.mrg-banner-container--hidden { display: none; }
.sidebar_container .button--flat, .sidebar_container .button--flat_disabled { margin-top: 9px; padding-left: 12px; }
.sidebar_container .button--flat .icon, .sidebar_container .button--flat_disabled .icon { top: -1px; position: relative; margin-right: 13px; }
@media (min-width: 992px) {
  .sidebar_container + .main { padding-top: 17px; }
}
.filter .sidebar__header { display: none; padding: 12px 30px 13px; }
.filter .button--back, .filter .button--close { margin-top: -20px; right: auto; left: 5px; }
.filter .sidebar__content { padding: 0px 0px 2px; }
.filter .form__buttons { margin: 0px; }
.filter .form__buttons--no_value .button_container--hidden { display: none; }
@media (max-width: 991px) {
  .filter .form__buttons { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .filter .form__buttons::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .filter .form__buttons .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .filter .form__buttons .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .filter .form__buttons .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .filter .form__buttons .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .filter .form__buttons .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .filter .form__buttons .button_container--mobile_right { float: right; }
  .filter .form__buttons .button_container + .button_container { margin-top: 0px; }
  .filter .form__buttons--full .button, .filter .form__buttons--full .button_container, .filter .form__buttons--single .button, .filter .form__buttons--single .button_container { width: 100% !important; }
  .filter .form__buttons--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .filter .form__buttons--split .button--flat, .filter .form__buttons--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .filter .form__buttons--split .button--flat:hover, .filter .form__buttons--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .filter .form__buttons--split .button--flat:active, .filter .form__buttons--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .filter .form__buttons .button_container .button--default { border: 0px; }
  .filter .form__buttons--full .button, .filter .form__buttons--full .button_container, .filter .form__buttons--single .button, .filter .form__buttons--single .button_container { width: 100% !important; }
  .filter .form__buttons--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .filter .form__buttons--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .filter .form__buttons--split .button:hover { background: rgb(245, 245, 245); }
  .filter .form__buttons--split .button_container { float: left; width: 50%; }
  .filter .form__buttons--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .filter .form__buttons { padding-left: 6px; padding-right: 6px; }
}
.filter .location_button { display: none; }
.change-phone-form .filter .form-control, .filter .change-phone-form .form-control, .filter .form--invite .form-control, .filter .form_control, .filter .p_form_app .form-control, .form--invite .filter .form-control, .p_form_app .filter .form-control { height: 32px; width: 100%; padding: 4px 20px 6px; -webkit-appearance: none; }
.filter .form_control--small { width: 100%; max-width: 100%; padding: 5px 9px 6px; z-index: 0; }
.filter .form_control--small:focus { border-color: rgb(3, 154, 211); z-index: 1; }
.filter--aside { margin-bottom: 30px; }
.filter--aside .form_control--checkbox .form_label, .filter--aside .form_control--radio .form_label { font-size: 13px; }
@media (max-width: 991px) {
  .filter--open { display: block !important; }
  .filter--aside { width: 100%; overflow: hidden; position: fixed; right: 0px; top: 0px; bottom: 0px; z-index: 122; background: rgb(249, 249, 249); max-width: 100%; display: none; margin-bottom: 0px; }
  .filter--aside .sidebar__header { display: block; text-align: center; }
  .filter--aside .filter__scroll { position: absolute; left: 0px; width: 100%; bottom: 0px; top: 0px; overflow: auto; }
  .filter--aside .filter__scroll_inner { padding-bottom: 48px; }
  .filter--aside .sidebar__content { top: 56px; bottom: 0px; left: 0px; overflow: visible; position: absolute; }
  .filter--aside .sidebar__content--filter_open { background: rgb(255, 255, 255); }
  .filter--aside .sidebar__content--filter_open .accordion--filter { border-bottom: 0px; }
  .filter--aside .sidebar__content--filter_open .accordion_item { margin: 0px; border: 0px; }
  .filter--aside .sidebar__content--filter_open .accordion_item__header { display: none; }
  .filter--aside .sidebar__content--filter_open .accordion_item__body { display: block !important; }
  .filter--aside .location_button { display: block; }
  .filter--aside .form_control--checkbox, .filter--aside .form_control--radio { margin-bottom: 0px; margin-left: -15px; margin-right: -15px; padding: 0px; border-bottom: 1px solid rgb(235, 235, 235); }
  .filter--aside .form_control--checkbox input:checked + .form_label, .filter--aside .form_control--radio input:checked + .form_label { background: rgb(243, 243, 243); }
  .filter--aside .form_control--checkbox input[disabled] + .form_label::after, .filter--aside .form_control--radio input[disabled] + .form_label::after { background: transparent !important; }
  .filter--aside .form_control--checkbox .form_label, .filter--aside .form_control--radio .form_label { width: 100%; padding: 14px 42px 15px 20px; border-radius: 0px; font-size: 16px; transition: all 0.2s ease 0s; }
  .filter--aside .form_control--checkbox .form_label::after, .filter--aside .form_control--checkbox .form_label::before, .filter--aside .form_control--radio .form_label::after, .filter--aside .form_control--radio .form_label::before { content: none; background: transparent; border-radius: 0px; }
  .filter--aside .form_control--checkbox .form_label::before, .filter--aside .form_control--radio .form_label::before { display: none; }
  .filter--aside .form_control--checkbox .form_label::after, .filter--aside .form_control--radio .form_label::after { color: rgb(143, 143, 143); right: 20px; left: auto; font-size: 18px; width: auto; height: auto; top: 14px; border: 0px; font-family: icons !important; content: "" !important; display: block !important; }
  .filter--aside .form_control--small { border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; padding: 6px 0px; height: 40px; font-size: 16px; border-radius: 0px; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); }
  .filter--aside .form_control--small::-webkit-input-placeholder { color: transparent !important; }
  .filter--aside .form_control--small:focus { border-bottom-color: rgb(3, 154, 211); }
  .filter--aside .checkbox--slide { float: right; margin-left: 10px; }
  .filter--aside .form__buttons { overflow: visible; }
  .filter--aside .form__buttons::after { box-shadow: rgb(249, 249, 249) 0px 30px 20px 40px; }
  .sidebar__content--filter_open .filter--aside .form__buttons { background-image: linear-gradient(transparent 0px, rgb(255, 255, 255)); background-repeat: repeat-x; }
  .filter--aside .form__buttons .button_container--hidden { display: block !important; }
}
@media (max-width: 767px) {
  .filter .button--back, .filter .button--close { left: 0px; }
  .filter--aside .form_control--checkbox, .filter--aside .form_control--radio { margin-left: -10px; margin-right: -10px; }
}
.filter_bar { padding-bottom: 19px; position: relative; }
.filter_bar__button { margin-top: -2px; }
.filter_bar__button .button { padding: 0px; background: transparent; letter-spacing: 0.01em; }
.filter_bar__button .icon { position: relative; top: -2px; margin-right: 5px; }
.filter_bar .hint { font-size: 14px; font-weight: 400; color: rgba(57, 57, 57, 0.6); margin-bottom: 6px; }
@media (max-width: 991px) {
  .filter_bar { overflow: hidden; max-height: 55px; padding: 10px 0px 0px; margin: 27px -15px 0px; }
  .filter_bar .hint { padding: 6px 0px 3px; margin: 0px; }
  .filter_bar .filter_control__label { display: none; }
  .filter_bar__button { margin-top: 0px; padding: 0px; }
  .filter_bar__button .button { padding: 14px 10px; margin: -8px -10px; width: 44px; height: 48px; overflow: hidden; }
  .filter_bar__button .button .icon { margin-right: 0px; }
  .filter_bar--empty { height: 0px; margin-bottom: 0px; }
}
@media (max-width: 767px) {
  .filter_bar { padding-left: 0px; padding-right: 0px; margin-left: -10px; margin-right: -10px; }
}
.filter_control { display: inline-block; vertical-align: top; margin: -9px 0px -10px 10px; cursor: pointer; border-radius: 2px; position: relative; }
.filter_control:first-child { margin-left: -11px; }
.filter_control__elem, .filter_control__label { display: inline-block; vertical-align: top; }
.filter_control__label { color: rgba(70, 82, 93, 0.6); padding: 9px 0px 11px 11px; margin-right: -11px; }
.filter_control__elem { vertical-align: top; border: 0px; padding: 11px 10px; }
.body--is_mobile .filter_control::after { content: ""; border-color: gray transparent transparent; border-style: solid; border-width: 5px 5px 2.5px; position: absolute; right: -5px; top: 50%; margin-top: -2px; }
.body--is_mobile .filter_control .filter_control__elem, .body--is_mobile .filter_control .filter_control__label { vertical-align: top; border: 0px; }
.filter_control .Select-menu-outer { overflow: visible; }
@media (max-width: 991px) {
  .filter_control__elem { width: 100%; }
}
@media (min-width: 992px) {
  .filter_control--active { background: rgb(234, 234, 234); }
}
.filter_location, .fixed_location { margin-bottom: 0px; display: inline-block; vertical-align: middle; position: relative; padding: 4px 0px 4px 15px; user-select: none; }
.filter_location span, .fixed_location span { display: inline; vertical-align: middle; line-height: 16px; }
.filter_location__label { width: 83%; display: inline-block; vertical-align: top; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.filter_location .button, .fixed_location .button { padding: 0px; letter-spacing: 0px; text-align: left; max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.filter_location .icon, .fixed_location .icon { color: rgb(143, 143, 143); margin-right: 6px; transition: color 0.2s ease 0s; }
.header_bar .filter_location, .header_bar .fixed_location { margin-bottom: 0px; display: inline-block; vertical-align: middle; }
.filter_location--selected .icon { color: rgb(3, 154, 211); }
@media (min-width: 1071px) {
  .filter_location .button:hover, .filter_location .button:hover .icon, .fixed_location .button:hover, .fixed_location .button:hover .icon { color: rgb(3, 154, 211); }
}
@media (max-width: 1070px) {
  .filter_location, .fixed_location { position: absolute; left: 0px; right: 0px; top: 152px; margin-bottom: 0px; text-align: center; width: 100%; z-index: 39; pointer-events: none; padding-left: 0px; }
  .filter_location .button, .filter_location .icon, .fixed_location .button, .fixed_location .icon { color: rgb(143, 143, 143); }
  .filter_location .button, .fixed_location .button { color: rgb(57, 57, 57); padding: 5px 12px 7px; border-radius: 100px; pointer-events: all; max-width: 330px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px; background: rgb(255, 255, 255) !important; }
  .filter_location .icon, .fixed_location .icon { top: -1px; }
  .filter_location--sticky { margin-left: 12.5%; position: fixed; top: 10px !important; }
  .header_bar__search--alert_showed + .filter_location, .header_bar__search--alert_showed + .fixed_location { top: 190px; }
}
.row_wrapper { display: flex; flex-direction: row; margin-right: -15px; margin-left: -15px; }
@media (max-width: 767px) {
  .row_wrapper { margin-right: -10px; margin-left: -10px; }
}
.filter_container { width: 285px; display: flex; flex-direction: column; }
.filter_container .ad_filter_banner { display: flex; flex-direction: column; flex-grow: 1; }
@media (max-width: 991px) {
  .filter_container { display: none; }
}
.main_container { width: calc(100% - 285px); }
@media (max-width: 991px) {
  .main_container { margin-right: 0px; width: 100%; }
}
.filter_container_react { margin-bottom: 30px; }
.email_component_react:not(:empty) { margin-bottom: 10px; }
.email_component_react:not(:empty) > div { min-height: auto; border: 1px solid rgb(235, 235, 235); border-radius: 2px; padding-left: 24px; padding-right: 24px; }
.email_component_react:not(:empty) > div p { color: rgb(143, 143, 143); }
.email_component_react:not(:empty) > div button[type="button"] { box-shadow: none; color: rgb(3, 154, 211); }
.filter_value_wrapper { display: none; font-size: 14px; padding-right: 20px; position: relative; min-height: 19px; }
.filter_value_wrapper .icon { color: rgb(143, 143, 143); font-size: 24px; line-height: 14px; margin-right: -8px; margin-top: -6px; position: absolute; top: 50%; right: 0px; }
.filter_value_wrapper .filter_value { color: rgb(143, 143, 143); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.filter_value_wrapper .filter_value--chosen { color: rgb(57, 57, 57); }
@media (max-width: 991px) {
  .filter_value_wrapper { display: block; float: right; margin-left: 10px; max-width: 50%; }
}
.filter_prices { }
.filter_prices::after, .filter_prices::before { content: " "; display: table; }
.filter_prices::after { clear: both; }
.filter_prices__text { color: rgb(140, 140, 140); font-size: 16px; float: left; position: relative; z-index: 40; text-align: center; width: 34px; line-height: 60px; }
.filter_prices__text.pull-right { float: right !important; }
.filter_prices__field { width: 50%; float: left; }
.change-phone-form .filter_prices__field .form-control, .filter_prices__field .change-phone-form .form-control, .filter_prices__field .form--invite .form-control, .filter_prices__field .form_control, .filter_prices__field .p_form_app .form-control, .form--invite .filter_prices__field .form-control, .p_form_app .filter_prices__field .form-control { padding-right: 23px; }
.filter_prices__field .button--delete { right: 0px; z-index: auto; }
.change-phone-form .filter_prices__field:first-child .form-control, .filter_prices__field:first-child .change-phone-form .form-control, .filter_prices__field:first-child .form--invite .form-control, .filter_prices__field:first-child .form_control, .filter_prices__field:first-child .p_form_app .form-control, .form--invite .filter_prices__field:first-child .form-control, .p_form_app .filter_prices__field:first-child .form-control { border-radius: 2px 0px 0px 2px; border-right-width: 0px; }
.change-phone-form .filter_prices__field:first-child .form-control:focus, .filter_prices__field:first-child .change-phone-form .form-control:focus, .filter_prices__field:first-child .form--invite .form-control:focus, .filter_prices__field:first-child .form_control:focus, .filter_prices__field:first-child .p_form_app .form-control:focus, .form--invite .filter_prices__field:first-child .form-control:focus, .p_form_app .filter_prices__field:first-child .form-control:focus { border-right-width: 1px; }
.change-phone-form .filter_prices__field:last-child .form-control, .filter_prices__field:last-child .change-phone-form .form-control, .filter_prices__field:last-child .form--invite .form-control, .filter_prices__field:last-child .form_control, .filter_prices__field:last-child .p_form_app .form-control, .form--invite .filter_prices__field:last-child .form-control, .p_form_app .filter_prices__field:last-child .form-control { border-radius: 0px 2px 2px 0px; }
@media (max-width: 991px) {
  .filter_prices .form_group { padding: 10px 0px; margin-left: 34px !important; margin-right: 34px !important; }
  .filter_prices__field { width: 100%; }
  .change-phone-form .filter_prices__field .form-control, .filter_prices__field .change-phone-form .form-control, .filter_prices__field .form--invite .form-control, .filter_prices__field .form_control, .filter_prices__field .p_form_app .form-control, .form--invite .filter_prices__field .form-control, .p_form_app .filter_prices__field .form-control { border-right-width: 0px; border-radius: 0px !important; }
}
.filter_preloader { background: url("/build/images/filtr_new.c70bb3.svg") center top no-repeat; height: 350px; width: 100%; }
.filter_preloader a { display: none; }
@media (max-width: 991px) {
  .filter_preloader { display: none !important; }
}
.filter_has_value { float: right; width: 6px; height: 6px; border-radius: 10px; margin-top: 7px; background: rgb(3, 154, 211); }
@media (max-width: 991px) {
  .filter_has_value { display: none !important; }
}
.filter_has_value--checkbox { float: none; top: 9px; right: 15px; position: absolute; }
.filter_num_value { float: right; margin-right: 9px; font-size: 12px; }
@media (max-width: 991px) {
  .filter_num_value { display: none !important; }
}
.filter_select { margin-bottom: 18px; }
.filter_select .form_control--select > .Select-control .Select-value { font-size: 14px; max-width: 195px; }
.filter_select .suggest_list { max-height: 460px; left: 0px; top: -7px; overflow: hidden; padding: 0px; }
.filter_select .suggest_list__item { padding-right: 30px; }
.filter_select .suggest_list__item::after { right: 10px; }
.hint--payments { padding-left: 15px; padding-right: 15px; margin-top: -10px; line-height: normal; margin-bottom: 20px; }
@media (max-width: 767px) {
  .hint--payments { padding-left: 10px; padding-right: 10px; }
}
.pagination { text-align: center; }
.pagination__button { margin: 10px 0px; }
.pagination__button p { margin-bottom: 10px; color: rgba(57, 57, 57, 0.5); }
.pagination .alert_text { margin-top: 10px; }
.overlay { background: rgba(0, 0, 0, 0.7); z-index: 120; position: fixed; left: 0px; top: 0px; width: 100%; height: 100%; visibility: hidden; opacity: 0; transition: visibility 0.15s ease 0s, opacity 0.2s ease 0s; }
.overlay--show { visibility: visible; opacity: 1; }
.profile { position: relative; }
@media (min-width: 992px) {
  .profile + .main { padding-top: 7px; }
  .profile .nav_list { width: 240px; margin: 10px 0px; }
}
@media (max-width: 991px) {
  .profile { margin-top: -15px; padding-left: 0px; padding-right: 0px; width: auto; float: none; }
}
.user__image, .user__image--empty, .user__name, .user__status { position: relative; z-index: 1; }
.user__image--empty a, .user__image a, .user__name a, .user__status a { text-decoration: none; color: inherit; }
.user__image, .user__image--empty { border-radius: 100px; margin: 0px auto 13px; width: 88px; height: 88px; background: rgb(255, 255, 255); position: relative; }
.user__image--empty img, .user__image img { border-radius: 100px; font-size: 0px; }
.user__image--empty::before, .user__image::before { content: ""; position: absolute; top: 0px; left: 0px; display: block; width: 100%; height: 100%; border-radius: 50%; background: rgba(57, 57, 57, 0.02); }
.user__image--empty { display: block; position: relative; background: rgb(234, 234, 234); }
.user__image--empty::after { content: ""; position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; opacity: 0.2; background: url("/build/images/person.472864.svg") 50% center / 48px no-repeat; }
.user__name { color: rgb(255, 255, 255); font-size: 20px; line-height: normal; margin-bottom: 3px; overflow-wrap: break-word; word-break: break-word; }
.user__rating { margin-top: 11px; }
.user__label { display: inline; margin-right: 4px; }
.user__label, .user__type { color: gray; font-size: 14px; }
.user__type { display: block; margin-top: 4px; margin-bottom: 10px; }
.user--simple { text-align: left; margin-bottom: 20px; overflow: hidden; }
.user--simple .user__image, .user--simple .user__image--empty { float: left; margin: 0px 24px 0px 0px; width: 60px; height: 60px; }
.user--simple .user__info { overflow: hidden; padding: 21px 0px; }
.user--simple .user__name { color: rgb(42, 42, 42); font-size: 16px; line-height: 1.3; margin-bottom: 2px; }
.user--simple .user__name a { color: rgb(57, 57, 57); text-decoration: none; }
.modal .user { text-align: center; }
.modal .user, .modal .user .user__name { color: rgb(57, 57, 57); }
.user_card { text-align: center; border-radius: 2px; border-bottom: 1px solid rgb(235, 235, 235); width: 100%; min-height: 245px; max-width: 100%; padding: 22px 20px; background-color: rgb(255, 255, 255); position: relative; margin-bottom: 17px; }
.user_card .button--dropdown { position: absolute; right: 6px; top: 6px; display: none; }
.user_card .button--dropdown .icon { color: rgb(143, 143, 143); font-size: 22px; }
.user_card .rating { margin-top: 12px; }
.user_card .user__name { color: rgb(57, 57, 57); }
.user_card .user__info { color: rgb(143, 143, 143); font-size: 14px; line-height: 1.57; text-align: center; margin-top: 5px; }
.user_card .user__info__city { padding-right: 4px; }
.user_card .user__info__city, .user_card .user__info__date { display: inline-block; vertical-align: top; }
.user_card .user__info__point { padding-right: 4px; }
.user_card .button--user_edit { top: 10px; right: 7px; bottom: auto; height: auto; }
.user_card .button--user_edit:hover .icon { color: rgb(3, 154, 211); }
.user_card .button--user_edit .icon { color: rgb(255, 255, 255); }
.user_card__bg { position: absolute; left: 0px; top: 0px; height: 100%; width: 100%; pointer-events: none; background-position: 50% center; background-repeat: no-repeat; opacity: 0.05; background-size: cover; }
@media (max-width: 991px) {
  .user_card { width: 100%; border-radius: 0px; margin-bottom: 0px; border: 0px; padding-top: 30px; padding-bottom: 30px; height: auto; min-height: 0px; }
  .user_card .button--dropdown { display: block; }
  .user_card .button--user_edit { display: none; }
}
@media (max-width: 767px) {
  .user_card { padding-top: 20px; padding-bottom: 20px; }
  .user_card .rating { margin-top: 7px; }
}
.user_actions { max-width: 254px; }
.user_actions .list__link, .user_actions .list__link--pointer { cursor: pointer; }
.user_actions .list__item--active .list__icon .icon, .user_actions .list__item--active .list__link { color: rgb(3, 154, 211); }
.user_actions--another .list__link:hover { color: rgb(255, 92, 74); background: transparent !important; }
.user_actions--another .list__link:hover .icon { color: rgb(255, 92, 74); }
.user_actions--show { opacity: 1 !important; visibility: visible !important; }
@media (max-width: 991px) {
  .user_actions { display: none; }
  .user_actions--another { display: block; position: absolute; top: 45px; right: 15px; border-radius: 2px; border: 1px solid rgb(235, 235, 235); background-color: rgb(255, 255, 255); z-index: 40; visibility: hidden; opacity: 0; transition: opacity 0.2s ease 0s, visibility 0.2s ease 0s; }
  .user_actions--another .list--actions { margin: 12px 0px; }
  .user_actions--another .list--actions .list__item { margin-bottom: 8px; }
  .user_actions--another .list--actions .list__item:last-child { margin-bottom: 0px; }
  .user_actions--another .list--actions .list__icon { margin-right: 10px; }
  .user_actions--another .list--actions .list__link { padding: 10px 20px; }
}
.user_blocked { display: none; position: absolute; background: rgb(255, 92, 74); padding: 3px; width: 30px; height: 30px; border-radius: 100px; text-align: center; top: -2px; right: -2px; }
.user--blocked .user_blocked { display: block; }
.user_blocked .icon { font-size: 24px; line-height: 22px; color: rgb(255, 255, 255); }
.status__label { width: 8px; height: 8px; background: rgb(69, 174, 36); display: inline-block; vertical-align: middle; border-radius: 100px; position: relative; top: -2px; margin: 0px 5px; }
.tabs { border-bottom: 1px solid rgb(235, 235, 235); margin-bottom: 15px; }
.tabs .badge { right: -15px; top: 11px; }
.tabs--modal { padding: 0px 30px; margin-bottom: 0px; }
@media (max-width: 991px) {
  .tabs { margin: 0px -15px 12px; text-align: center; display: flex; }
  .tabs .tab_item { margin-left: 0px; flex: 1 1 0%; }
  .tabs .badge { display: inline-block; margin: -11px 0px; position: absolute; left: auto; right: auto; top: 18px; }
}
@media (max-width: 767px) and (max-width: 991px) {
  .tabs .badge._profile_counter_archive_tab { margin: -11px -7px; }
}
@media (max-width: 991px) {
  .tabs--payments { margin-top: -15px; }
}
@media (max-width: 767px) {
  .tabs { margin-left: -10px; margin-right: -10px; }
  .tabs--profile .tab_item .tab_item_count { display: block; text-align: center; position: absolute; top: 8px; left: 0px; right: 0px; width: 100%; }
  .tabs--profile .tab_item__link { padding: 28px 16px 12px; }
  .tabs--profile .tab_item--empty .tab_item_count { display: block; }
}
.tab_item { position: relative; display: inline-block; vertical-align: top; margin-bottom: -1px; line-height: 1.1; cursor: pointer; margin-left: 30px; border-bottom: 2px solid transparent; color: rgba(57, 57, 57, 0.8); font-size: 14px; font-weight: 400; transition: color 0.2s ease 0s; }
.tab_item:hover { color: rgb(57, 57, 57); }
.tab_item:first-child { margin-left: 0px !important; }
.tab_item__link { color: rgba(57, 57, 57, 0.8); text-decoration: none; padding: 16px 0px; display: block; }
.tab_item__link:hover { color: rgb(57, 57, 57); }
.tab_item--active { border-bottom-color: rgb(3, 154, 211) !important; }
.tab_item--active, .tab_item--active .tab_item__link { color: rgb(57, 57, 57) !important; }
.tab_item--empty span { display: none; }
.figure__image { line-height: 0; }
.figure__image + .figure__caption { padding-left: 35px; padding-top: 15px; overflow: hidden; }
.figure__title { margin-bottom: 10px; }
.figure p { margin-top: 0px; }
@media (max-width: 450px) {
  .figure__image + .figure__caption { padding-left: 15px; }
}
@media (max-width: 400px) {
  .figure__image { margin-left: -40px; }
}
.modal { position: fixed; z-index: 124; overflow: hidden auto; opacity: 0; visibility: hidden; pointer-events: none; transition: opacity 0.3s ease 0s, visibility 0.2s ease 0s; }
.modal, .modal__wrapper { top: 0px; left: 0px; width: 100%; height: 100%; }
.modal__wrapper { display: table; position: absolute; right: 0px; table-layout: fixed; }
.modal__container { display: table-cell; vertical-align: middle; }
.modal__inner { position: relative; left: 0px; right: 0px; margin: 25px auto; padding-bottom: 60px; width: 480px; max-width: 100%; background: rgb(255, 255, 255); border-radius: 4px; }
.modal__body, .modal__footer, .modal__header { padding-left: 55px; padding-right: 55px; }
.modal__header { padding-top: 22px; padding-bottom: 22px; }
.modal__header::after, .modal__header::before { content: " "; display: table; }
.modal__header::after { clear: both; }
.modal__header a.pull-right { color: rgb(3, 154, 211); text-decoration: none; margin-top: 4px; }
.modal__header a.pull-right:hover { color: rgb(13, 186, 252); }
.modal__header h3 { margin-bottom: 0px; }
.modal__header--pattern { border-bottom: 1px solid rgba(0, 0, 0, 0.08); background: url("/build/images/banner-light@2x.2b16d7.png") 0% 0% / 150px 155px; }
.modal__header--hidden, .modal__tabs { display: none; }
.modal__close_control { cursor: pointer; }
.modal .button--back, .modal .button--close { position: absolute; top: 15px; right: 10px; margin-top: 0px; z-index: 125; }
.modal .button--back .icon, .modal .button--close .icon { color: rgb(255, 255, 255); }
.modal .button--flat:hover, .modal .button--flat_disabled:hover { color: rgb(13, 186, 252); }
.modal .hint { font-size: 12px; line-height: 16px; }
.modal__body { padding-top: 25px; padding-bottom: 15px; }
.modal__body .form_inline { margin-bottom: 9px; }
.modal__body--cities { padding: 15px 40px; }
.modal__body--cities .hint_text { margin-bottom: 20px; max-width: 100% !important; width: 100% !important; float: none !important; color: rgb(129, 129, 129) !important; }
.modal__body--cities .cities_list__col { width: 33.3333%; }
.modal__body--cities .cities_list__item { margin-bottom: 8px; }
.modal__body--cities .cities_list__item a { color: rgb(57, 57, 57); }
.modal__body--cities .cities_list__item--bold { font-weight: 600; }
@media (max-width: 991px) {
  .modal__body--cities { border-bottom: 0px; position: absolute; width: 100%; top: 110px; bottom: 48px; overflow: auto; }
  .modal__body--cities .cities_list__item { line-height: 24px; font-size: 16px; margin-bottom: 25px; }
}
@media (max-width: 767px) {
  .modal__body--cities .cities_list__item { border-bottom: 0px; }
}
.modal__footer .form_group, .modal__footer .form_inline { margin-bottom: 0px; }
.modal__footer .form_group .button, .modal__footer .form_inline .button { width: 100%; }
.modal--show { opacity: 1; visibility: visible; pointer-events: auto; }
.body--is_mobile .modal, .body--is_safari_le_8 .modal { display: none; opacity: 1; visibility: visible; }
.body--is_mobile .modal--show, .body--is_safari_le_8 .modal--show { display: block; }
.body--is_mobile .modal .modal__inner, .body--is_safari_le_8 .modal .modal__inner { animation-name: none !important; }
@media (min-width: 768px) {
  .modal__tabs { display: block; }
}
@media (min-width: 992px) {
  .modal .button--back, .modal .button--close { position: absolute; top: -12px; right: -12px; width: 32px; height: 32px; background-color: rgb(255, 255, 255); border-radius: 100px; box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px 0px; }
  .modal .button--back i, .modal .button--close i { color: rgb(57, 57, 57); line-height: 16px; position: relative; left: -4px; font-size: 24px; }
  .modal .button--back { box-shadow: none !important; }
  .modal .button--back i { font-size: 24px !important; }
}
@media (max-width: 991px) {
  .modal { background: rgb(255, 255, 255); display: none; }
  .modal:not(.modal_small):not(.modal_city):not(.modal_alert) .modal__container, .modal:not(.modal_small):not(.modal_city):not(.modal_alert) .modal__wrapper { display: block; }
  .modal__container { vertical-align: top; }
  .modal__inner { margin: 0px; width: 100%; overflow: hidden; animation-name: none !important; }
  .modal__body, .modal__footer, .modal__header { padding-left: 20px; padding-right: 20px; margin-left: auto; margin-right: auto; }
  .modal__header--pattern { max-width: none; }
  .modal__header--hidden { display: block; }
  .modal__header--mobile { text-align: center; padding-top: 14px; padding-bottom: 16px; border-bottom: 1px solid rgb(235, 235, 235); position: fixed; width: 100%; top: 0px; left: 0px; background: rgb(255, 255, 255); z-index: 1; display: block !important; }
  .modal__header--mobile h3 { margin-bottom: 0px; }
  .modal .button--back, .modal .button--close { top: 6px; right: 5px; }
  .modal .button--back .icon, .modal .button--close .icon { color: rgb(143, 143, 143); }
  .modal .figure--banner { max-width: 370px; margin-left: auto; margin-right: auto; }
  .modal--show { display: block; }
}
@media (max-width: 767px) {
  .modal__body, .modal__footer, .modal__header { padding-left: 10px; padding-right: 10px; }
}
.modal--ios-11-fix { position: absolute; }
.modal_map__content { height: 784px; }
.modal_map .modal__inner { width: 980px; max-width: 100%; max-height: 100%; padding-bottom: 0px; }
@media (min-width: 992px) {
  .modal_map .modal__inner { min-height: 640px; }
}
.modal_map .modal__footer { position: absolute; width: 100%; padding: 0px; margin: 0px; bottom: 0px; left: 0px; }
.modal_map .modal_map_container { position: relative; padding: 0px !important; }
.modal_map .modal__tabs { position: absolute; left: 0px; top: 0px; width: 100%; }
.modal_map .modal__header--mobile { display: none; }
@media (max-width: 991px) {
  .modal_map { overflow: hidden; }
  .modal_map .modal__header--mobile { padding-top: 9px; padding-bottom: 10px; display: block; }
  .modal_map .button--back, .modal_map .button--close { display: block; right: auto; left: 4px; top: 3px; }
  .modal_map .button--back .icon, .modal_map .button--close .icon { color: rgb(143, 143, 143); font-size: 24px; }
  .modal_map .button--back .icon::before, .modal_map .button--close .icon::before { content: ""; }
  .modal_map .button--back + .sidebar__title, .modal_map .button--close + .sidebar__title { padding: 0px 20px; }
  .change-phone-form .modal_map .search_input .form-control, .form--invite .modal_map .search_input .form-control, .modal_map .search_input .change-phone-form .form-control, .modal_map .search_input .form--invite .form-control, .modal_map .search_input .form_control, .modal_map .search_input .p_form_app .form-control, .modal_map .search_input .Select-control, .p_form_app .modal_map .search_input .form-control { padding-left: 40px; width: 100%; border-radius: 0px; }
  .modal_map .search_input .Select-clear-zone { margin-top: -20px; }
  .modal_map .search_input .Select-control, .modal_map .search_input .Select-noresults, .modal_map .search_input .Select-option, .modal_map .search_input .Select-placeholder, .modal_map .search_input .Select-value-label--fake_input, .modal_map .search_input .suggest_list__item { padding-left: 40px; }
  .modal_map .modal__inner { margin: 0px; width: 100% !important; }
  .modal_map .modal__footer { position: absolute; bottom: 0px; left: 0px; right: 0px; border-top: 0px; background: rgb(255, 255, 255); }
  .modal_map .modal__inner, .modal_map .modal__inner > div { height: 100% !important; }
  .modal_map .modal__container, .modal_map .modal__wrapper { display: block; height: 100%; }
  .modal_map .modal__footer { padding: 0px; width: 100%; max-width: none; z-index: 1000; backface-visibility: visible; transform: translateZ(0px); }
}
@media (max-width: 767px) {
  .modal_map .search_input .Select-control, .modal_map .search_input .Select-noresults, .modal_map .search_input .Select-option, .modal_map .search_input .Select-placeholder, .modal_map .search_input .Select-value-label--fake_input, .modal_map .search_input .suggest_list__item { padding-left: 55px; }
  .modal_map .Select-clear-zone { right: 10px !important; }
}
@media (min-width: 992px) {
  .body--is_mobile .modal_map .modal_map__content { height: 610px; }
}
@media (max-height: 840px) and (min-width: 992px) {
  body:not(.body--is_mobile) .modal_map.modal__inner { position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; height: auto; }
  body:not(.body--is_mobile) .modal_map.modal__inner .modal_map__content { height: 100%; }
  body:not(.body--is_mobile) .modal_map.modal__inner .modal__tabs { position: absolute; left: 0px; width: 100%; top: 0px; z-index: 40; }
}
.modal_gallery--mobile .modal__inner { height: 100%; }
.modal_gallery { padding-left: 30px; padding-right: 30px; }
.modal_gallery .button--back, .modal_gallery .button--close { text-align: right; top: 0px; right: 0px; padding: 24px 17px; border-radius: 0px; position: fixed; width: 62px; height: 64px; color: rgb(255, 255, 255); background: transparent; box-shadow: none; opacity: 0.5; }
.modal_gallery .button--back:hover, .modal_gallery .button--close:hover { opacity: 1; background-color: rgba(56, 56, 56, 0.2); }
.modal_gallery .button--back .modal-close_control, .modal_gallery .button--close .modal-close_control { color: rgb(255, 255, 255); font-size: 48px; }
.modal_gallery .icon--close { text-shadow: rgb(57, 57, 57) 0px 1px 1px; }
.modal_gallery .modal__inner { background: transparent; padding: 0px; width: 750px; overflow: hidden; }
.modal_gallery .slick-next, .modal_gallery .slick-prev { position: fixed; top: 0px; bottom: 0px; border: 0px; background: transparent; font-size: 0px; cursor: pointer; width: 20%; max-width: 195px; overflow: hidden; }
.modal_gallery .slick-next:focus, .modal_gallery .slick-prev:focus { outline: none; }
.modal_gallery .slick-next:hover::after, .modal_gallery .slick-next:hover::before, .modal_gallery .slick-prev:hover::after, .modal_gallery .slick-prev:hover::before { opacity: 1; }
.modal_gallery .slick-next::after, .modal_gallery .slick-next::before, .modal_gallery .slick-prev::after, .modal_gallery .slick-prev::before { transition: opacity 0.2s ease 0s; }
.modal_gallery .slick-next::after, .modal_gallery .slick-prev::after { content: ""; position: absolute; top: -40px; bottom: -40px; width: 100%; opacity: 0; }
.modal_gallery .slick-next::before, .modal_gallery .slick-prev::before { content: ""; display: block; width: 24px; height: 40px; font-size: 50px; margin-left: auto; margin-right: auto; opacity: 0.5; margin-top: -22px; }
.modal_gallery .slick-prev { left: 0px; }
.modal_gallery .slick-prev::after { right: 100%; box-shadow: rgba(56, 56, 56, 0.3) 100px 0px 60px; }
.modal_gallery .slick-prev::before { background: url("/build/images/left-arrow-chevron.eb0c1a.svg") 50% center / 24px 40px no-repeat; }
.modal_gallery .slick-next { right: 0px; }
.modal_gallery .slick-next::after { left: 100%; box-shadow: rgba(56, 56, 56, 0.3) -100px 0px 60px 0px; }
.modal_gallery .slick-next::before { background: url("/build/images/right-arrow-chevron.0852d0.svg") 50% center / 24px 40px no-repeat; }
.modal_gallery .slick-track { cursor: -webkit-grab; }
.modal_gallery .slick-track:active { cursor: -webkit-grabbing; }
.modal_gallery .gallery--image_zoom_control--wrapper { position: fixed; top: 50%; right: 12px; margin-top: -40px; }
.modal_gallery .gallery--image_zoom_control { display: block; text-shadow: rgba(0, 0, 0, 0.6) 0px 2px 2px; color: rgb(255, 255, 255); font-size: 32px; }
.modal_gallery .gallery--image_zoom_control.icon--remove { margin-top: 16px; }
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .modal_gallery { background: rgb(0, 0, 0); }
  .modal_gallery .button--back .icon, .modal_gallery .button--close .icon { color: rgb(255, 255, 255); }
}
@media (max-width: 991px) {
  .modal_gallery .button--back, .modal_gallery .button--close { padding-top: 16px; padding-bottom: 16px; }
  .modal_gallery .slick-next, .modal_gallery .slick-prev { top: 57px; width: 10%; }
  .modal_gallery .slick-next::after, .modal_gallery .slick-prev::after { width: 100px; }
  .modal_gallery .slick-next::after { box-shadow: rgba(56, 56, 56, 0.4) -40px 0px 60px 0px; }
  .modal_gallery .slick-prev::after { box-shadow: rgba(56, 56, 56, 0.4) 40px 0px 60px 0px; }
}
@media (max-width: 767px) {
  .modal_gallery .slick-next, .modal_gallery .slick-prev { width: 20%; }
  .modal_gallery .slick-next::after { box-shadow: rgba(56, 56, 56, 0.4) -30px 0px 60px 0px; }
  .modal_gallery .slick-prev::after { box-shadow: rgba(56, 56, 56, 0.4) 30px 0px 60px 0px; }
}
.modal_product.modal__header { padding-top: 42px; padding-bottom: 42px; }
.modal_product .figure__image { overflow: hidden; border-radius: 2px; width: 146px; height: 154px; }
.modal_product .figure__image img { min-width: 100%; min-height: 100%; width: auto; max-height: 154px; height: auto; }
.modal_product .figure__image + .figure__caption { padding-left: 25px; padding-top: 8px; }
@media (max-width: 400px) {
  .modal_product .figure__image { margin-left: 0px; width: 120px; height: 130px; }
  .modal_product .figure__image img { max-height: 130px; }
}
.modal_report .modal__header, .modal_send_message .modal__header, .modal_userlist .modal__header { text-align: center; font-size: 20px; color: rgb(57, 57, 57); font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; font-weight: 500; border-bottom: 1px solid rgb(235, 235, 235); padding-top: 25px; padding-bottom: 26px; z-index: 125; }
.modal_report .modal__body, .modal_send_message .modal__body, .modal_userlist .modal__body { padding: 0px; }
.modal_report .modal__inner, .modal_send_message .modal__inner, .modal_userlist .modal__inner { width: 720px; padding-bottom: 0px; }
.modal_report .modal__inner::after, .modal_send_message .modal__inner::after, .modal_userlist .modal__inner::after { content: ""; clear: both; display: table; }
.modal_report .button--back, .modal_send_message .button--back, .modal_userlist .button--back { top: 14px; pointer-events: all; position: absolute; }
.modal_report .button--back .icon, .modal_send_message .button--back .icon, .modal_userlist .button--back .icon { color: rgb(143, 143, 143); }
.modal_report .form_label, .modal_send_message .form_label, .modal_userlist .form_label { margin-bottom: -4px; }
.modal_report .show_if_modal, .modal_send_message .show_if_modal, .modal_userlist .show_if_modal { display: block !important; }
.modal_report .hide_if_modal, .modal_send_message .hide_if_modal, .modal_userlist .hide_if_modal { display: none !important; }
.modal_report .c_create_message_images, .modal_send_message .c_create_message_images, .modal_userlist .c_create_message_images { margin: 0px; }
.modal_report .c_create_message, .modal_send_message .c_create_message, .modal_userlist .c_create_message { background: transparent; display: table-row; }
.modal_report .c_create_message__form, .modal_send_message .c_create_message__form, .modal_userlist .c_create_message__form { margin: 0px; padding: 0px; }
.modal_report .c_create_message__form::after, .modal_report .c_create_message__form::before, .modal_send_message .c_create_message__form::after, .modal_send_message .c_create_message__form::before, .modal_userlist .c_create_message__form::after, .modal_userlist .c_create_message__form::before { content: " "; display: table; }
.modal_report .c_create_message__form::after, .modal_send_message .c_create_message__form::after, .modal_userlist .c_create_message__form::after { clear: both; }
.modal_report .c_create_message__textarea, .modal_send_message .c_create_message__textarea, .modal_userlist .c_create_message__textarea { min-height: 116px; height: 116px; }
.modal_report .c_create_message__submit, .modal_report .c_create_message__upload, .modal_send_message .c_create_message__submit, .modal_send_message .c_create_message__upload, .modal_userlist .c_create_message__submit, .modal_userlist .c_create_message__upload { position: static; margin: 18px 0px; }
.modal_report .c_create_message__error, .modal_report .c_create_message__hint, .modal_send_message .c_create_message__error, .modal_send_message .c_create_message__hint, .modal_userlist .c_create_message__error, .modal_userlist .c_create_message__hint { margin: 10px 0px -10px; }
.modal_report .c_create_message__error p, .modal_report .c_create_message__hint p, .modal_send_message .c_create_message__error p, .modal_send_message .c_create_message__hint p, .modal_userlist .c_create_message__error p, .modal_userlist .c_create_message__hint p { margin-bottom: 0px; }
.modal_report .c_create_message__dropzone, .modal_send_message .c_create_message__dropzone, .modal_userlist .c_create_message__dropzone { top: 0px; left: 0px; right: 0px; bottom: auto; height: 117px; }
.modal_report .c_create_message__upload, .modal_send_message .c_create_message__upload, .modal_userlist .c_create_message__upload { margin-left: -7px; float: left; }
.modal_report .c_create_message__upload .button, .modal_send_message .c_create_message__upload .button, .modal_userlist .c_create_message__upload .button { padding: 4px 8px 3px; }
.modal_report .c_create_message__upload .button[disabled] span, .modal_send_message .c_create_message__upload .button[disabled] span, .modal_userlist .c_create_message__upload .button[disabled] span { color: rgb(57, 57, 57) !important; }
.modal_report .c_create_message__upload .button:hover span, .modal_send_message .c_create_message__upload .button:hover span, .modal_userlist .c_create_message__upload .button:hover span { color: rgb(3, 154, 211); }
.modal_report .c_create_message__upload span, .modal_send_message .c_create_message__upload span, .modal_userlist .c_create_message__upload span { text-transform: none; color: rgb(57, 57, 57); letter-spacing: 0px; font-weight: 400; vertical-align: middle; margin-left: 8px; transition: color 0.2s ease 0s; display: inline-block !important; }
.modal_report .c_create_message__submit, .modal_send_message .c_create_message__submit, .modal_userlist .c_create_message__submit { float: right; }
.modal_report .c_create_message__field, .modal_send_message .c_create_message__field, .modal_userlist .c_create_message__field { font-size: 15px; }
.modal_report .c_create_message__editor, .modal_send_message .c_create_message__editor, .modal_userlist .c_create_message__editor { border-top: 0px; }
.modal_report .c_create_message_container, .modal_send_message .c_create_message_container, .modal_userlist .c_create_message_container { width: 100%; display: table; table-layout: fixed; }
.modal_report .reasons_container, .modal_send_message .reasons_container, .modal_userlist .reasons_container { margin-bottom: 30px; }
.modal_report .input_message_form, .modal_send_message .input_message_form, .modal_userlist .input_message_form { background: rgb(245, 245, 245); position: relative; z-index: 2; margin-top: -31px; padding: 0px 20px 30px; }
.modal_report .input_message_form::after, .modal_report .input_message_form::before, .modal_send_message .input_message_form::after, .modal_send_message .input_message_form::before, .modal_userlist .input_message_form::after, .modal_userlist .input_message_form::before { content: " "; display: table; }
.modal_report .input_message_form::after, .modal_send_message .input_message_form::after, .modal_userlist .input_message_form::after { clear: both; }
.modal_report .input_message_form .hint, .modal_send_message .input_message_form .hint, .modal_userlist .input_message_form .hint { overflow: hidden; display: block; padding-right: 15px; }
.modal_report .reasons_container, .modal_send_message .reasons_container, .modal_userlist .reasons_container { border-bottom: 1px solid rgb(235, 235, 235); }
@media (min-width: 992px) {
  .modal_report .reasons_container, .modal_send_message .reasons_container, .modal_userlist .reasons_container { display: table; width: 100%; }
  .modal_report .list--report, .modal_send_message .list--report, .modal_userlist .list--report { display: table-row; }
  .modal_report .list--report .list_item, .modal_send_message .list--report .list_item, .modal_userlist .list--report .list_item { display: table-cell; vertical-align: top; float: none; }
  .modal_report .list--report .list_item--active, .modal_send_message .list--report .list_item--active, .modal_userlist .list--report .list_item--active { padding-top: 17px; padding-bottom: 19px; background: rgb(245, 245, 245) !important; }
}
@media (max-width: 991px) {
  .modal_report .modal__header, .modal_send_message .modal__header, .modal_userlist .modal__header { padding-top: 18px; padding-bottom: 19px; position: fixed; width: 100%; top: 0px; background: transparent; pointer-events: none; }
  .modal_report .modal__inner, .modal_send_message .modal__inner, .modal_userlist .modal__inner { padding-top: 57px; }
  .modal_report .button--back, .modal_report .button--close, .modal_send_message .button--back, .modal_send_message .button--close, .modal_userlist .button--back, .modal_userlist .button--close { left: 5px; top: 8px; right: auto; position: fixed; }
  .modal_report .button--back, .modal_send_message .button--back, .modal_userlist .button--back { background: rgb(255, 255, 255); z-index: 126; }
  .modal_report .modal__body, .modal_report .modal__header, .modal_send_message .modal__body, .modal_send_message .modal__header, .modal_userlist .modal__body, .modal_userlist .modal__header { max-width: none; }
  .modal_report .input_message_form, .modal_send_message .input_message_form, .modal_userlist .input_message_form { margin-top: -30px; padding: 0px; background: rgb(255, 255, 255); border-bottom: 1px solid rgb(235, 235, 235); }
  .modal_report .input_message_form .hint, .modal_send_message .input_message_form .hint, .modal_userlist .input_message_form .hint { margin: 10px 17px; }
  .modal_report .input_message_form .button--green, .modal_report .input_message_form .button--primary, .modal_report .input_message_form .input_message__button, .modal_send_message .input_message_form .button--green, .modal_send_message .input_message_form .button--primary, .modal_send_message .input_message_form .input_message__button, .modal_userlist .input_message_form .button--green, .modal_userlist .input_message_form .button--primary, .modal_userlist .input_message_form .input_message__button { font-size: 0px; line-height: 0; position: absolute; bottom: 0px; right: 0px; padding: 15px 10px; box-shadow: none; background: transparent !important; }
  .modal_report .input_message_form .button--green .icon, .modal_report .input_message_form .button--primary .icon, .modal_report .input_message_form .input_message__button .icon, .modal_send_message .input_message_form .button--green .icon, .modal_send_message .input_message_form .button--primary .icon, .modal_send_message .input_message_form .input_message__button .icon, .modal_userlist .input_message_form .button--green .icon, .modal_userlist .input_message_form .button--primary .icon, .modal_userlist .input_message_form .input_message__button .icon { color: rgb(143, 143, 143); display: block; }
  .modal_report .input_message_form .button--green:active .icon, .modal_report .input_message_form .button--primary:active .icon, .modal_report .input_message_form .input_message__button:active .icon, .modal_send_message .input_message_form .button--green:active .icon, .modal_send_message .input_message_form .button--primary:active .icon, .modal_send_message .input_message_form .input_message__button:active .icon, .modal_userlist .input_message_form .button--green:active .icon, .modal_userlist .input_message_form .button--primary:active .icon, .modal_userlist .input_message_form .input_message__button:active .icon { color: rgb(3, 154, 211); }
  .change-phone-form .modal_report .input_message_form .form-control, .change-phone-form .modal_send_message .input_message_form .form-control, .change-phone-form .modal_userlist .input_message_form .form-control, .form--invite .modal_report .input_message_form .form-control, .form--invite .modal_send_message .input_message_form .form-control, .form--invite .modal_userlist .input_message_form .form-control, .modal_report .input_message_form .change-phone-form .form-control, .modal_report .input_message_form .form--invite .form-control, .modal_report .input_message_form .form_control, .modal_report .input_message_form .p_form_app .form-control, .modal_send_message .input_message_form .change-phone-form .form-control, .modal_send_message .input_message_form .form--invite .form-control, .modal_send_message .input_message_form .form_control, .modal_send_message .input_message_form .p_form_app .form-control, .modal_userlist .input_message_form .change-phone-form .form-control, .modal_userlist .input_message_form .form--invite .form-control, .modal_userlist .input_message_form .form_control, .modal_userlist .input_message_form .p_form_app .form-control, .p_form_app .modal_report .input_message_form .form-control, .p_form_app .modal_send_message .input_message_form .form-control, .p_form_app .modal_userlist .input_message_form .form-control { border: 0px; height: 48px; min-height: 48px; padding-right: 50px; padding-left: 16px; margin-bottom: 0px; }
  .modal_report .c_create_message__textarea, .modal_send_message .c_create_message__textarea, .modal_userlist .c_create_message__textarea { padding-left: 15px; padding-right: 15px; }
  .modal_report .c_create_message__placeholder, .modal_send_message .c_create_message__placeholder, .modal_userlist .c_create_message__placeholder { left: 15px; }
  .modal_report:not(.modal_userlist):not(.modal_send_message) .modal__inner, .modal_send_message:not(.modal_userlist):not(.modal_send_message) .modal__inner, .modal_userlist:not(.modal_userlist):not(.modal_send_message) .modal__inner { padding-top: 0px; position: absolute; top: 57px; bottom: 0px; width: 100%; height: auto; overflow: auto; background: rgb(249, 249, 249); }
  .modal_report:not(.modal_userlist):not(.modal_send_message) .reasons_container, .modal_send_message:not(.modal_userlist):not(.modal_send_message) .reasons_container, .modal_userlist:not(.modal_userlist):not(.modal_send_message) .reasons_container { background: rgb(255, 255, 255); }
}
@media (min-width: 992px) {
  .modal_report .list, .modal_send_message .list, .modal_userlist .list { }
  .modal_report .list::after, .modal_report .list::before, .modal_send_message .list::after, .modal_send_message .list::before, .modal_userlist .list::after, .modal_userlist .list::before { content: " "; display: table; }
  .modal_report .list::after, .modal_send_message .list::after, .modal_userlist .list::after { clear: both; }
  .modal_report .list_item, .modal_send_message .list_item, .modal_userlist .list_item { width: 50%; float: left; }
  .modal_report .list_item:first-child, .modal_report .list_item:nth-child(2n+1), .modal_send_message .list_item:first-child, .modal_send_message .list_item:nth-child(2n+1), .modal_userlist .list_item:first-child, .modal_userlist .list_item:nth-child(2n+1) { border-right: 1px solid rgb(235, 235, 235); }
}
.modal_edit .modal__inner { width: 400px; margin-left: auto; margin-right: auto; padding-bottom: 60px; }
.modal_edit .auth_group { width: 100%; margin: 0px; text-align: left; padding: 0px; position: static; }
.modal_edit .auth_group .upload_zone__elem--image { width: 88px; height: 88px; margin-right: 0px; }
.change-phone-form .modal_edit .auth_group .form-control, .form--invite .modal_edit .auth_group .form-control, .modal_edit .auth_group .change-phone-form .form-control, .modal_edit .auth_group .form--invite .form-control, .modal_edit .auth_group .form_control, .modal_edit .auth_group .p_form_app .form-control, .p_form_app .modal_edit .auth_group .form-control { width: 100%; }
@media (max-width: 991px) {
  .modal_edit .auth_group .form__buttons { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .modal_edit .auth_group .form__buttons::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .modal_edit .auth_group .form__buttons .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .modal_edit .auth_group .form__buttons .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .modal_edit .auth_group .form__buttons .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_edit .auth_group .form__buttons .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .modal_edit .auth_group .form__buttons .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .modal_edit .auth_group .form__buttons .button_container--mobile_right { float: right; }
  .modal_edit .auth_group .form__buttons .button_container + .button_container { margin-top: 0px; }
  .modal_edit .auth_group .form__buttons--full .button, .modal_edit .auth_group .form__buttons--full .button_container, .modal_edit .auth_group .form__buttons--single .button, .modal_edit .auth_group .form__buttons--single .button_container { width: 100% !important; }
  .modal_edit .auth_group .form__buttons--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .modal_edit .auth_group .form__buttons--split .button--flat, .modal_edit .auth_group .form__buttons--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_edit .auth_group .form__buttons--split .button--flat:hover, .modal_edit .auth_group .form__buttons--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .modal_edit .auth_group .form__buttons--split .button--flat:active, .modal_edit .auth_group .form__buttons--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .modal_edit .auth_group .form__buttons .button_container .button--default { border: 0px; }
  .modal_edit .auth_group .form__buttons--full .button, .modal_edit .auth_group .form__buttons--full .button_container, .modal_edit .auth_group .form__buttons--single .button, .modal_edit .auth_group .form__buttons--single .button_container { width: 100% !important; }
  .modal_edit .auth_group .form__buttons--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .modal_edit .auth_group .form__buttons--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .modal_edit .auth_group .form__buttons--split .button:hover { background: rgb(245, 245, 245); }
  .modal_edit .auth_group .form__buttons--split .button_container { float: left; width: 50%; }
  .modal_edit .auth_group .form__buttons--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .modal_edit .auth_group .form__buttons { padding-left: 6px; padding-right: 6px; }
}
.modal_edit .auth_group .hint { margin: 10px 0px; color: rgba(0, 0, 0, 0.38); font-size: 12px; line-height: 16px; }
.modal_edit .auth_group .hint--error_active { height: auto; }
.modal_edit p { padding: 0px; }
.modal_edit .sidebar__header { display: none; }
@media (min-width: 992px) {
  .modal_edit .modal__body { padding: 22px 40px; }
  .modal_edit .modal__footer { padding-left: 15px; padding-right: 15px; }
}
@media (max-width: 991px) {
  .modal_edit .modal__inner { width: 100%; padding-top: 58px; }
  .modal_edit .sidebar__header { display: block; }
  .modal_edit .auth_group__desc, .modal_edit .auth_group__title { display: none; }
  .modal_edit .auth_group__form { margin-top: 0px !important; }
  .modal_edit .auth_group__form .row > div { width: 100%; }
  .modal_edit .auth_group .upload_zone { margin-bottom: 28px; }
  .modal_edit .auth_group .upload_zone__elem { margin-left: auto; margin-right: auto; }
  .change-phone-form .modal_edit .auth_group .form-control, .form--invite .modal_edit .auth_group .form-control, .modal_edit .auth_group .change-phone-form .form-control, .modal_edit .auth_group .form--invite .form-control, .modal_edit .auth_group .form_control, .modal_edit .auth_group .p_form_app .form-control, .p_form_app .modal_edit .auth_group .form-control { width: 100%; }
  .modal_edit .modal__body { max-width: none; }
  .modal_edit .sidebar__header { text-align: center; position: fixed; width: 100%; padding: 13px 40px 14px; }
  .modal_edit .button--back, .modal_edit .button--close { left: 5px; top: 10px !important; right: auto !important; }
  .modal_edit .button--back .icon, .modal_edit .button--close .icon { color: rgb(143, 143, 143); font-size: 24px; line-height: 24px; }
}
.modal_alert .form__buttons { margin-top: 0px; margin-right: -15px; }
.modal_alert .modal__inner { width: 300px; padding-bottom: 10px; }
.modal_alert .modal__body { padding-top: 20px; padding-bottom: 30px; }
.modal_alert .modal__body, .modal_alert .modal__footer, .modal_alert .modal__header { padding-left: 24px; padding-right: 24px; }
.modal_message { font-size: 16px; font-weight: 400; font-style: normal; font-stretch: normal; line-height: 1.5; color: rgb(57, 57, 57); }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city).modal, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city).modal { background: transparent; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .modal__container, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .modal__container { vertical-align: middle; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .modal__inner, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .modal__inner { width: 300px; margin: 25px auto; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons { position: absolute; left: 0px; width: 100%; padding: 0px; border-top: 1px solid rgb(235, 235, 235); }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container { padding: 0px; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container .button, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container .button { border-radius: 0px; background: rgb(255, 255, 255); color: rgb(3, 154, 211); padding-top: 14px; padding-bottom: 15px; height: 48px; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container .button:active, .modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container .button:hover, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container .button:active, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container .button:hover { background: rgb(245, 245, 245); }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container + .button_container, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons .button_container + .button_container { border-left: 1px solid rgb(235, 235, 235); }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons::after, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons::after { display: none; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons--full, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons--full { position: static; margin-top: 0px; margin-bottom: -47px; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons--full .button_container, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons--full .button_container { border-left: 0px; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons--full .button_container + .button_container, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .form__buttons--full .button_container + .button_container { border-left: 0px; margin-top: 0px; border-top: 1px solid rgb(235, 235, 235); }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .buttons_wrapper, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .buttons_wrapper { margin-bottom: -60px; }
.body--is_mobile .modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .button--back, .body--is_mobile .modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .button--close, .body--is_mobile .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .button--back, .body--is_mobile .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city) .button--close { display: none !important; }
.modal_city:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city).modal_small--wide .modal__inner, .modal_small:not(.modal_small--data):not(.modal_city):not(.modal_small--alt):not(.modal_city).modal_small--wide .modal__inner { width: 380px; }
.modal_city .modal__inner, .modal_small .modal__inner { padding-bottom: 60px; background: rgb(255, 255, 255); }
.modal_city .modal__header, .modal_small .modal__header { padding: 20px 24px 15px; border-bottom: 0px; }
.modal_city .modal__header h3, .modal_city .modal__header h4, .modal_small .modal__header h3, .modal_small .modal__header h4 { margin-bottom: 0px; }
.modal_city .modal__header + .modal__body, .modal_small .modal__header + .modal__body { padding-top: 0px; }
.modal_city .modal__header--pattern + .modal__body, .modal_small .modal__header--pattern + .modal__body { padding-top: 20px; }
.modal_city .modal__body, .modal_small .modal__body { padding: 20px 24px; }
.modal_city .modal__body .form_group:first-child, .modal_small .modal__body .form_group:first-child { margin-top: 0px !important; }
.modal_city .modal_message, .modal_small .modal_message { font-size: 16px; color: rgba(0, 0, 0, 0.54); }
.modal_city .hint, .modal_small .hint { font-size: 14px; line-height: normal; }
.modal_city .alert_message, .modal_small .alert_message { padding: 0px; }
.modal_city .product_image, .modal_small .product_image { text-align: center; position: relative; line-height: 0; padding: 15px 0px 5px; }
.modal_city .product_image img, .modal_small .product_image img { display: block; max-width: 100%; margin: 0px auto; border-radius: 2px; }
.modal_city .product_image .label, .modal_small .product_image .label { font-size: 14px; line-height: normal; padding: 5px 12px 7px; margin-top: -16px; }
@media (max-width: 991px) {
  .modal_city .form__buttons, .modal_small .form__buttons { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .modal_city .form__buttons::after, .modal_small .form__buttons::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .modal_city .form__buttons .button, .modal_small .form__buttons .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .modal_city .form__buttons .button--disabled, .modal_small .form__buttons .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .modal_city .form__buttons .button--bordered, .modal_small .form__buttons .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_city .form__buttons .button--default, .modal_small .form__buttons .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .modal_city .form__buttons .button_container, .modal_small .form__buttons .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .modal_city .form__buttons .button_container--mobile_right, .modal_small .form__buttons .button_container--mobile_right { float: right; }
  .modal_city .form__buttons .button_container + .button_container, .modal_small .form__buttons .button_container + .button_container { margin-top: 0px; }
  .modal_city .form__buttons--full .button, .modal_city .form__buttons--full .button_container, .modal_city .form__buttons--single .button, .modal_city .form__buttons--single .button_container, .modal_small .form__buttons--full .button, .modal_small .form__buttons--full .button_container, .modal_small .form__buttons--single .button, .modal_small .form__buttons--single .button_container { width: 100% !important; }
  .modal_city .form__buttons--split .button, .modal_small .form__buttons--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .modal_city .form__buttons--split .button--flat, .modal_city .form__buttons--split .button--flat_disabled, .modal_small .form__buttons--split .button--flat, .modal_small .form__buttons--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_city .form__buttons--split .button--flat:hover, .modal_city .form__buttons--split .button--flat_disabled:hover, .modal_small .form__buttons--split .button--flat:hover, .modal_small .form__buttons--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .modal_city .form__buttons--split .button--flat:active, .modal_city .form__buttons--split .button--flat_disabled:active, .modal_small .form__buttons--split .button--flat:active, .modal_small .form__buttons--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .modal_city .form__buttons .button_container .button--default, .modal .modal_small .form__buttons .button_container .button--default { border: 0px; }
  .modal_city .form__buttons--full .button, .modal_city .form__buttons--full .button_container, .modal_city .form__buttons--single .button, .modal_city .form__buttons--single .button_container, .modal_small .form__buttons--full .button, .modal_small .form__buttons--full .button_container, .modal_small .form__buttons--single .button, .modal_small .form__buttons--single .button_container { width: 100% !important; }
  .modal_city .form__buttons--split, .modal_small .form__buttons--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .modal_city .form__buttons--split .button, .modal_small .form__buttons--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .modal_city .form__buttons--split .button:hover, .modal_small .form__buttons--split .button:hover { background: rgb(245, 245, 245); }
  .modal_city .form__buttons--split .button_container, .modal_small .form__buttons--split .button_container { float: left; width: 50%; }
  .modal_city .form__buttons--split .button_container + .button_container, .modal_small .form__buttons--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .modal_city .form__buttons, .modal_small .form__buttons { padding-left: 6px; padding-right: 6px; }
}
.body--is_mobile .modal_city .modal__body, .body--is_mobile .modal_small .modal__body { text-align: left !important; }
.modal_city .modal__body, .modal_city .modal__footer, .modal_city .modal__header, .modal_small--data .modal__body, .modal_small--data .modal__footer, .modal_small--data .modal__header { max-width: none; }
.modal_city .modal__footer, .modal_small--data .modal__footer { position: absolute; bottom: 0px; left: 0px; right: 0px; }
.modal_city .form_group, .modal_small--data .form_group { margin-top: 10px; }
.modal_city .modal__inner, .modal_small--data .modal__inner { padding-bottom: 60px; }
.modal_city .modal__header, .modal_small--data .modal__header { padding-top: 14px; }
.modal_small--alt .modal__header { text-align: center; }
.modal_small--alt .modal__header img { margin-bottom: 20px; }
.modal_small--alt .modal__header h3 { margin-bottom: 0px; color: rgb(57, 57, 57); font-weight: 400; }
.modal_small--alt .modal__body { text-align: left; }
@media (max-width: 991px) {
  .modal_city .button--back, .modal_city .button--close, .modal_small--data .button--back, .modal_small--data .button--close { right: auto; left: 5px; top: 8px; }
  .modal_city .button--back .icon, .modal_city .button--close .icon, .modal_small--data .button--back .icon, .modal_small--data .button--close .icon { color: rgb(143, 143, 143); font-size: 22px; }
  .modal_city .modal__header, .modal_small--data .modal__header { border-bottom: 1px solid rgb(235, 235, 235); }
  .modal_city .modal__body, .modal_small--data .modal__body { padding-top: 20px !important; }
  .modal_city .modal__inner, .modal_small--data .modal__inner { padding-top: 57px; min-height: 100%; }
  .modal_city .modal__footer, .modal_small--data .modal__footer { position: fixed; }
  .modal_city .modal__footer .button--flat, .modal_city .modal__footer .button--flat_disabled, .modal_small--data .modal__footer .button--flat, .modal_small--data .modal__footer .button--flat_disabled { color: rgb(57, 57, 57); }
  .modal_city .modal__footer .button--submit, .modal_small--data .modal__footer .button--submit { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_city .modal__footer .button--submit:hover, .modal_small--data .modal__footer .button--submit:hover { background: rgb(3, 172, 236); }
  .modal_city .modal__footer .button--submit:active, .modal_small--data .modal__footer .button--submit:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal_city .modal__inner, .modal_small--alt .modal__inner, .modal_small--data .modal__inner { max-width: 100%; width: 400px !important; }
  .modal_city .modal__body, .modal_city .modal__header, .modal_small--alt .modal__body, .modal_small--alt .modal__header, .modal_small--data .modal__body, .modal_small--data .modal__header { padding-left: 40px; padding-right: 40px; }
}
.modal_app-invite--product .modal__header { overflow: hidden; padding-top: 25px; padding-bottom: 25px; }
.modal_app-invite--product .figure { overflow: visible; }
.modal_app-invite--product .figure__image { box-shadow: none; overflow: visible; }
.modal_app-invite--product .figure__image img { width: inherit; max-height: none; }
.modal_app-invite--product .figure__image + .figure__caption { padding-top: 25px; }
@media (max-width: 360px) {
  .modal_app-invite--product .figure__image { height: auto !important; }
}
.modal_data h3 { margin-bottom: 10px; }
.modal_data .auth_group { margin: 30px 0px 0px; padding: 0px; }
.modal_data .auth_group .hint { font-size: 12px; line-height: 16px; }
.modal_data .modal__inner { padding-bottom: 110px; }
.modal_data .modal__body { padding-top: 20px; }
.modal_data .modal__body p { color: gray; font-size: 14px; }
.modal_data .modal__header h3 { margin-bottom: 0px; }
.modal_data .modal__header--visible + .modal__header { display: none; }
.modal_data .form__buttons, .modal_data .modal__footer { margin-top: 30px; }
@media (max-width: 991px) {
  .modal_data .form__buttons, .modal_data .modal__footer { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .modal_data .form__buttons::after, .modal_data .modal__footer::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .modal_data .form__buttons .button, .modal_data .modal__footer .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .modal_data .form__buttons .button--disabled, .modal_data .modal__footer .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .modal_data .form__buttons .button--bordered, .modal_data .modal__footer .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_data .form__buttons .button--default, .modal_data .modal__footer .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .modal_data .form__buttons .button_container, .modal_data .modal__footer .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .modal_data .form__buttons .button_container--mobile_right, .modal_data .modal__footer .button_container--mobile_right { float: right; }
  .modal_data .form__buttons .button_container + .button_container, .modal_data .modal__footer .button_container + .button_container { margin-top: 0px; }
  .modal_data .form__buttons--full .button, .modal_data .form__buttons--full .button_container, .modal_data .form__buttons--single .button, .modal_data .form__buttons--single .button_container, .modal_data .modal__footer--full .button, .modal_data .modal__footer--full .button_container, .modal_data .modal__footer--single .button, .modal_data .modal__footer--single .button_container { width: 100% !important; }
  .modal_data .form__buttons--split .button, .modal_data .modal__footer--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .modal_data .form__buttons--split .button--flat, .modal_data .form__buttons--split .button--flat_disabled, .modal_data .modal__footer--split .button--flat, .modal_data .modal__footer--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_data .form__buttons--split .button--flat:hover, .modal_data .form__buttons--split .button--flat_disabled:hover, .modal_data .modal__footer--split .button--flat:hover, .modal_data .modal__footer--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .modal_data .form__buttons--split .button--flat:active, .modal_data .form__buttons--split .button--flat_disabled:active, .modal_data .modal__footer--split .button--flat:active, .modal_data .modal__footer--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .modal_data .form__buttons .button_container .button--default, .modal .modal_data .modal__footer .button_container .button--default { border: 0px; }
  .modal_data .form__buttons--full .button, .modal_data .form__buttons--full .button_container, .modal_data .form__buttons--single .button, .modal_data .form__buttons--single .button_container, .modal_data .modal__footer--full .button, .modal_data .modal__footer--full .button_container, .modal_data .modal__footer--single .button, .modal_data .modal__footer--single .button_container { width: 100% !important; }
  .modal_data .form__buttons--split, .modal_data .modal__footer--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .modal_data .form__buttons--split .button, .modal_data .modal__footer--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .modal_data .form__buttons--split .button:hover, .modal_data .modal__footer--split .button:hover { background: rgb(245, 245, 245); }
  .modal_data .form__buttons--split .button_container, .modal_data .modal__footer--split .button_container { float: left; width: 50%; }
  .modal_data .form__buttons--split .button_container + .button_container, .modal_data .modal__footer--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .modal_data .form__buttons, .modal_data .modal__footer { padding-left: 6px; padding-right: 6px; }
}
@media (max-width: 991px) {
  .modal_data .form__buttons, .modal_data .modal__footer { left: 0px; margin-top: 0px; max-width: none !important; }
}
.modal_data .tel { display: none; }
@media (max-width: 991px) {
  .modal_data .button--back, .modal_data .button--close { right: auto; left: 5px; top: 8px; }
  .modal_data .button--back .icon, .modal_data .button--close .icon { color: rgb(143, 143, 143); font-size: 22px; }
  .modal_data .auth_group { width: 100%; }
  .modal_data .auth_group .form_inline:not(.form_inline--tel) .row > div { width: 100%; text-align: left !important; }
  .modal_data .auth_group .button--resend { margin-top: 10px; margin-left: -10px; }
  .modal_data .tel { display: block; }
  .modal_data .modal__body, .modal_data .modal__footer, .modal_data .modal__header { max-width: none; }
  .modal_data .modal__header--visible { display: block; padding-bottom: 0px; }
  .modal_data .modal__header--visible h3 { margin-bottom: 8px; }
  .modal_data .modal__header--visible ~ .modal__body { padding-top: 0px; }
  .modal_data .modal__inner { padding-top: 57px; }
  .modal_data .loyalty-modal { padding-bottom: 0px; }
  .modal_data .modal_email_edit, .modal_data .modal_email_edit .modal__inner { padding: 0px; }
}
@media (min-width: 992px) {
  .modal_data .modal__header { padding: 20px 40px 0px; }
  .modal_data .modal__header h3 { margin-bottom: 8px; font-weight: 600; }
  .modal_data .modal__header--hidden { display: none !important; }
  .modal_data .modal__inner { width: 400px; }
  .modal_data .modal__body { padding: 0px 40px; }
  .modal_data .loyalty-modal, .modal_data .loyalty-modal .modal__inner, .modal_data .modal_email_edit, .modal_data .modal_email_edit .modal__inner { padding: 0px; }
}
.modal_city { overflow: visible; }
.modal_city .hint { height: 0px; overflow: hidden; font-size: 12px; line-height: 16px; transition: height 0.2s ease 0s; }
.modal_city .hint--opened { margin-top: 7px; height: 22px; }
@media (max-width: 991px) {
  .modal_city .modal__inner { height: 100%; background: url("/build/images/pic-map.7b6402.svg") center 100px no-repeat rgb(255, 255, 255); }
  .modal_city .modal__container { display: block; height: 100%; }
  .modal_city .modal__wrapper { display: block; }
  .modal_city .hint--opened ~ .suggest_container .suggest_list { top: 140px; }
  .modal_city .suggest_container .suggest_list { width: auto; top: 117px; left: 15px; right: 15px; bottom: 48px; margin-top: 0px; position: fixed; max-height: none; box-shadow: none; border: 0px; background: transparent !important; }
  .modal_city .suggest_container .suggest_list__item { padding-left: 10px; padding-right: 10px; background: transparent; border-radius: 2px; }
}
.modal_userlist .scroll-check-point { height: 100%; }
.modal_userlist .scrollable_container { overflow-y: scroll; position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; }
.modal_userlist .user { text-align: left; }
.modal_userlist .user__image, .modal_userlist .user__image--empty { width: 64px; height: 64px; }
.modal_userlist .user__info { padding: 20px 0px; }
.modal_userlist .list--data button, .modal_userlist .list--userlist button { float: right; margin: 12px -14px; min-width: 150px; text-align: right; }
.modal_userlist .list--data button.secondary, .modal_userlist .list--userlist button.secondary { color: rgb(151, 151, 151); }
.modal_userlist .modal__body { position: absolute; top: 71px; width: 100%; left: 0px; bottom: 0px; overflow: hidden; padding-bottom: 0px; }
@media (max-width: 991px) {
  .modal_userlist .modal__header { position: fixed; top: 0px; left: 0px; width: 100%; background: rgb(255, 255, 255); z-index: 40; }
  .modal_userlist .modal__body { top: 57px; }
  .modal_userlist .modal__inner { width: 100%; height: 100%; padding-top: 56px; box-shadow: none; }
  .modal_userlist .list--data:not(.list--data), .modal_userlist .list--userlist:not(.list--data) { box-shadow: none; }
  .modal_userlist .modal__container, .modal_userlist .modal__wrapper { display: block; height: 100%; }
}
@media (max-width: 767px) {
  .modal_userlist .list--data button, .modal_userlist .list--userlist button { margin-top: 4px; margin-bottom: 4px; }
}
@media (min-width: 992px) {
  .modal_userlist .modal__inner { height: 580px; max-height: 100%; }
}
@media (max-height: 600px) and (min-width: 992px) {
  .modal_userlist, .modal_userlist .modal__container, .modal_userlist .modal__inner, .modal_userlist .modal__wrapper { display: block; }
  .modal_userlist .modal__container { height: calc(100% - 50px); min-height: 290px; }
}
.modal_phone_edit .auth_group { text-align: left; position: static; }
.modal_phone_edit .auth_group__back { display: none !important; }
.modal_phone_edit .auth_group__header { text-align: left; }
.modal_phone_edit .auth_group__desc { padding-left: 0px; }
.modal_phone_edit .auth_group__img { display: none; }
.modal_phone_edit .auth_group__title { padding-top: 22px; }
.modal_phone_edit .form__buttons { position: absolute; left: 0px; right: 0px; bottom: 0px; padding: 0px 10px 10px; }
@media (max-width: 991px) {
  .modal_phone_edit .form__buttons { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .modal_phone_edit .form__buttons::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .modal_phone_edit .form__buttons .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .modal_phone_edit .form__buttons .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .modal_phone_edit .form__buttons .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_phone_edit .form__buttons .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .modal_phone_edit .form__buttons .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .modal_phone_edit .form__buttons .button_container--mobile_right { float: right; }
  .modal_phone_edit .form__buttons .button_container + .button_container { margin-top: 0px; }
  .modal_phone_edit .form__buttons--full .button, .modal_phone_edit .form__buttons--full .button_container, .modal_phone_edit .form__buttons--single .button, .modal_phone_edit .form__buttons--single .button_container { width: 100% !important; }
  .modal_phone_edit .form__buttons--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .modal_phone_edit .form__buttons--split .button--flat, .modal_phone_edit .form__buttons--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_phone_edit .form__buttons--split .button--flat:hover, .modal_phone_edit .form__buttons--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .modal_phone_edit .form__buttons--split .button--flat:active, .modal_phone_edit .form__buttons--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .modal_phone_edit .form__buttons .button_container .button--default { border: 0px; }
  .modal_phone_edit .form__buttons--full .button, .modal_phone_edit .form__buttons--full .button_container, .modal_phone_edit .form__buttons--single .button, .modal_phone_edit .form__buttons--single .button_container { width: 100% !important; }
  .modal_phone_edit .form__buttons--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .modal_phone_edit .form__buttons--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .modal_phone_edit .form__buttons--split .button:hover { background: rgb(245, 245, 245); }
  .modal_phone_edit .form__buttons--split .button_container { float: left; width: 50%; }
  .modal_phone_edit .form__buttons--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .modal_phone_edit .form__buttons { padding-left: 6px; padding-right: 6px; }
}
@media (max-width: 991px) {
  .modal_phone_edit .auth_group { margin-top: 0px; }
  .modal_phone_edit .auth_group__title { display: none; }
}
.modal_phone_hint { text-align: center; margin: -76px -20px 0px; }
.modal_phone_hint .figure__caption { padding-left: 0px; margin-bottom: 0px; }
.modal_phone_hint .figure__image { margin-left: 0px !important; }
.modal_phone_hint .modal__header { padding-bottom: 25px; padding-top: 30px; border-bottom: 0px; }
.modal_phone_hint .modal__header--mobile { display: none; }
.modal_phone_hint .modal__header h3 { font-weight: 400 !important; margin-bottom: 0px !important; }
.modal_phone_hint .modal__body { padding-top: 24px; padding-bottom: 15px; text-align: left; }
@media (min-width: 992px) {
  .modal_phone_hint { margin: 0px -40px; }
}
@media (max-width: 767px) {
  .modal_phone_hint { margin: -76px -10px 0px; }
}
.modal_alert_message .modal__inner { background: rgb(255, 255, 255); padding-bottom: 60px; }
.modal_alert_message .modal__header { padding: 20px 24px 15px; border-bottom: 0px; }
.modal_alert_message .modal__header h3, .modal_alert_message .modal__header h4 { color: rgba(0, 0, 0, 0.87); font-weight: 500; margin-bottom: 0px; }
.modal_alert_message .modal__header--pattern + .modal__body { padding-top: 20px; }
.modal_alert_message .modal__body, .modal_alert_message .modal__footer, .modal_alert_message .modal__header { max-width: none; }
.modal_alert_message .form_group { margin-top: 10px; }
.modal_alert_message .modal__header { padding-top: 14px; }
@media (max-width: 991px) {
  .modal_alert_message .form__buttons { width: 100%; position: fixed; bottom: 0px; right: 0px; margin: 0px; left: 0px; padding: 0px 11px 10px; z-index: 100; }
  .modal_alert_message .form__buttons::after { content: ""; position: absolute; top: 0px; height: 0px; width: 100%; left: 0px; z-index: -1; box-shadow: rgb(255, 255, 255) 0px 30px 20px 40px; }
  .modal_alert_message .form__buttons .button { padding-top: 9px; padding-bottom: 10px; margin-top: 0px; margin-right: 0px; margin-left: 0px; height: 40px; width: 100% !important; margin-bottom: 0px !important; }
  .modal_alert_message .form__buttons .button--disabled { background: rgb(154, 154, 154) !important; color: rgb(249, 249, 249) !important; }
  .modal_alert_message .form__buttons .button--bordered { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_alert_message .form__buttons .button--default { text-transform: uppercase; font-weight: 600; background: rgb(255, 255, 255); }
  .modal_alert_message .form__buttons .button_container { width: 50%; padding: 0px 4px; float: left; margin: 0px; }
  .modal_alert_message .form__buttons .button_container--mobile_right { float: right; }
  .modal_alert_message .form__buttons .button_container + .button_container { margin-top: 0px; }
  .modal_alert_message .form__buttons--full .button, .modal_alert_message .form__buttons--full .button_container, .modal_alert_message .form__buttons--single .button, .modal_alert_message .form__buttons--single .button_container { width: 100% !important; }
  .modal_alert_message .form__buttons--split .button { border: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .modal_alert_message .form__buttons--split .button--flat, .modal_alert_message .form__buttons--split .button--flat_disabled { border: 0px; background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
  .modal_alert_message .form__buttons--split .button--flat:hover, .modal_alert_message .form__buttons--split .button--flat_disabled:hover { background: rgb(3, 172, 236); }
  .modal_alert_message .form__buttons--split .button--flat:active, .modal_alert_message .form__buttons--split .button--flat_disabled:active { background: rgb(3, 136, 186); }
}
@media (min-width: 992px) {
  .modal .modal_alert_message .form__buttons .button_container .button--default { border: 0px; }
  .modal_alert_message .form__buttons--full .button, .modal_alert_message .form__buttons--full .button_container, .modal_alert_message .form__buttons--single .button, .modal_alert_message .form__buttons--single .button_container { width: 100% !important; }
  .modal_alert_message .form__buttons--split { position: absolute; left: 0px; right: 0px; bottom: 0px; margin: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .modal_alert_message .form__buttons--split .button { float: none; margin: 0px; border-radius: 0px; padding-top: 14px; background: rgb(255, 255, 255); padding-bottom: 15px; width: 100% !important; color: rgb(3, 154, 211) !important; }
  .modal_alert_message .form__buttons--split .button:hover { background: rgb(245, 245, 245); }
  .modal_alert_message .form__buttons--split .button_container { float: left; width: 50%; }
  .modal_alert_message .form__buttons--split .button_container + .button_container { margin-top: 0px; border-left: 1px solid rgb(235, 235, 235); }
}
@media (max-width: 767px) {
  .modal_alert_message .form__buttons { padding-left: 6px; padding-right: 6px; }
}
.modal_alert_message .alert_message p { margin-bottom: 10px; }
.modal_alert_message .alert_message__img { height: 113px; }
@media (max-width: 991px) {
  .modal_alert_message { background-color: rgb(249, 249, 249); }
  .modal_alert_message .button--back, .modal_alert_message .button--close { right: auto; left: 5px; top: 7px; }
  .modal_alert_message .button--back .icon, .modal_alert_message .button--close .icon { color: rgb(143, 143, 143); font-size: 22px; }
  .modal_alert_message .modal__header { border-bottom: 1px solid rgb(235, 235, 235); background: rgb(255, 255, 255); }
  .modal_alert_message .modal__inner { padding-top: 57px; min-height: 100%; background: transparent; }
  .modal_alert_message .modal__footer { display: none; }
}
@media (min-width: 992px) {
  .modal_alert_message .alert_message { padding: 0px; }
  .modal_alert_message .alert_message__img { margin: -25px -25px 15px; width: auto; height: 170px; background-position: 50% center; background-color: rgb(249, 249, 249); }
  .modal_alert_message .alert_message__img--transparent { height: 170px; width: auto; }
  .modal_alert_message .modal__inner { width: 300px; max-width: 100%; margin: 25px auto; }
  .modal_alert_message .modal__body { padding-left: 25px; padding-right: 25px; }
}
@media (max-width: 767px) {
  .modal_alert_message .alert_message { padding-top: 0px; padding-bottom: 0px; }
}
.modal_send_message .c_create_message__submit button { width: 131px; margin-top: -5px; }
.modal_send_message .modal__inner { background: rgb(245, 245, 245); width: 540px; }
.modal_send_message .modal__header { display: none; }
.modal_send_message .modal__header h3 { margin-bottom: 0px; }
.modal_send_message .button--back, .modal_send_message .button--close { z-index: 126; }
.modal_send_message .user { margin-bottom: 0px; overflow: hidden; padding-right: 15px; }
.modal_send_message .user__image, .modal_send_message .user__image--empty { width: 40px; height: 40px; margin-right: 13px; }
.modal_send_message .user__image--empty a, .modal_send_message .user__image a { display: block; font-size: 0px; line-height: 0; }
.modal_send_message .user__info { padding: 0px; text-align: left; }
.modal_send_message .user__name { font-size: 14px; margin-top: 1px; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.modal_send_message .user_container { margin-bottom: 10px; }
.modal_send_message .user_container::after, .modal_send_message .user_container::before { content: " "; display: table; }
.modal_send_message .user_container::after { clear: both; }
.modal_send_message .user_container .button--link { margin-bottom: 9px; color: rgb(3, 154, 211); }
.modal_send_message .user_container .button--link:hover { color: rgb(13, 186, 252); }
.modal_send_message .hint { line-height: 1.67; margin-bottom: 5px; }
.modal_send_message .hint span { color: rgb(57, 57, 57); }
.modal_send_message .modal__body { padding: 24px 20px; }
.modal_send_message .input_message { padding: 0px; }
@media (max-width: 991px) {
  .modal_send_message { background: rgb(245, 245, 245); }
  .modal_send_message .user { float: none; margin-bottom: 15px; }
  .modal_send_message .modal__inner { width: 100%; }
  .modal_send_message .modal__header { background: rgb(255, 255, 255); display: block; }
}
.modal__box {background-color: rgba(0, 0, 0, 0.68); position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; z-index: 124; }
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .modal__box--transparent .button--back .icon, .modal__box--transparent .button--close .icon { color: rgb(255, 255, 255) !important; }
  .modal__box:not(.modal__box--transparent) { background: rgb(255, 255, 255); }
}
.modal_tel .modal__inner { max-width: 100%; padding-bottom: 60px; }
.modal_tel .modal__header { padding-top: 25px; padding-bottom: 20px; }
.modal_tel .user { max-width: 360px; margin-left: auto; margin-right: auto; }
.modal_tel .user__name { margin-bottom: 4px; }
.modal_tel .user__info { color: rgb(143, 143, 143); font-size: 14px; line-height: 1.57; text-align: center; margin-top: 5px; }
.modal_tel .user__rating { margin-top: 7px; }
.modal_tel .product_tel { max-width: 360px; margin-left: auto; margin-right: auto; }
.modal_tel .product_tel__value { font-size: 30px; line-height: 28px; font-weight: 600; margin-bottom: 20px; }
.modal_tel p { font-size: 14px; margin-bottom: 25px; }
.modal_tel .hint { font-size: 12px; line-height: 16px; color: gray; padding: 0px 10px; }
.modal_tel .hint span { color: rgb(43, 43, 43); font-weight: 600; }
@media (min-width: 992px) {
  .modal_tel .modal__inner { width: 400px !important; }
}
.modal_pickup .modal__body { padding: 0px; position: relative; height: 100%; }
.modal_pickup .modal__inner { padding-bottom: 0px; height: 656px; }
.modal_pickup .form__buttons { border-top: 0px; }
.modal_pickup .map_buttons { margin-bottom: 40px; }
.modal_pickup .map_buttons .btn--target { margin-right: 0px; }
.modal_pickup .map_buttons + .map_buttons { margin-bottom: 0px; }
@media (max-width: 991px) {
  .modal_pickup .button--back, .modal_pickup .button--close { top: 7px; }
  .modal_pickup .modal__inner { padding-bottom: 0px; }
  .modal_pickup .modal__body { padding-bottom: 56px; }
  .modal_pickup .form__buttons .button_container { width: 100%; float: none; }
}
@media (min-width: 991px) {
  .modal_limits_form .modal__inner { width: 580px; min-height: 334px; padding: 32px 30px; }
}
@media (max-width: 991px) {
  .modal_limits_form { position: absolute; top: 0px; right: 0px; bottom: 0px; left: 0px; padding-bottom: 0px; }
}
.modal_payments_confirm .modal__body, .modal_payments_confirm .modal__footer { padding: 0px 15px; margin-top: 0px; }
.modal_payments_confirm .modal__footer { margin-top: 4px; }
.modal_payments_confirm .form__buttons { margin-top: 0px; }
@media (max-width: 767px) {
  .modal_payments_confirm .modal__body, .modal_payments_confirm .modal__footer { padding: 0px 10px; }
}
.modal_transfer, .modal_transfer .modal__inner { background-color: rgb(249, 249, 249); }
.modal_transfer .modal__header { z-index: 3; }
.modal_transfer .modal__body, .modal_transfer .modal__footer, .modal_transfer .modal__header { padding-left: 15px; padding-right: 15px; }
@media (max-width: 991px) {
  .modal_transfer .modal__body, .modal_transfer .modal__footer, .modal_transfer .modal__header { padding-left: 15px; padding-right: 15px; }
  .modal_transfer .modal__body { padding-top: 14px; }
}
@media (max-width: 767px) {
  .modal_transfer .modal__body, .modal_transfer .modal__footer, .modal_transfer .modal__header { padding-left: 10px; padding-right: 10px; }
}
.modal_form .modal__body { padding-bottom: 0px; }
@media (min-width: 992px) {
  .modal_form .modal__body, .modal_form .modal__footer, .modal_form .modal__header { padding-left: 30px; padding-right: 30px; }
  .modal_form .modal__inner { width: 550px; }
}
@media (max-width: 991px) {
  .modal_form .modal__inner { padding-top: 58px; }
  .modal_form .modal__body { padding-top: 15px; padding-bottom: 15px; }
}
.modal_create_disput .form_error { font-size: 14px; margin: -15px 0px 25px; }
@media (max-width: 991px) {
  .modal_create_disput .modal__wrapper { display: block; }
  .modal_create_disput .modal__container, .modal_create_disput .modal__inner { height: 100%; }
  .modal_create_disput .modal__inner { overflow: scroll; }
}
.modal_settings .modal__header { text-align: center; }
.modal_settings .form_label { padding: 6px 0px; color: rgb(143, 143, 143); }
.modal_settings .separator { border-top: 1px solid rgb(235, 235, 235); margin: 30px 0px; }
.change-phone-form .modal_settings .form-control, .form--invite .modal_settings .form-control, .modal_settings .change-phone-form .form-control, .modal_settings .form--invite .form-control, .modal_settings .form_control, .modal_settings .p_form_app .form-control, .p_form_app .modal_settings .form-control { padding: 3px 10px 4px; height: 32px; }
.change-phone-form .modal_settings .button--delete + .form-control, .form--invite .modal_settings .button--delete + .form-control, .modal_settings .button--delete + .form_control, .modal_settings .change-phone-form .button--delete + .form-control, .modal_settings .form--invite .button--delete + .form-control, .modal_settings .p_form_app .button--delete + .form-control, .p_form_app .modal_settings .button--delete + .form-control { padding-right: 30px; }
.modal_settings input[type="text"] { border-color: rgb(235, 235, 235); }
.modal_settings .form_group { margin-bottom: 20px; }
.modal_settings .button--bordered { height: 40px; padding: 9px 16px 10px; }
@media (max-width: 991px) {
  .modal_settings .modal__header h3 { float: none !important; }
  .modal_settings .modal__body { padding-top: 70px; }
  .modal_settings .button--back, .modal_settings .button--close { right: auto; left: 4px; top: 10px; }
}
@media (min-width: 992px) {
  .modal_settings .modal__inner { width: 545px; max-width: 100%; padding-left: 30px; padding-right: 30px; overflow: visible; }
  .modal_settings .button_group { margin-left: -10px; margin-right: -10px; }
  .modal_settings .modal__body, .modal_settings .modal__footer, .modal_settings .modal__header { padding-left: 0px; padding-right: 0px; }
  .modal_settings .modal__header { text-align: left; border-bottom: 1px solid rgb(235, 235, 235); }
  .modal_settings .modal__body { padding-top: 30px; }
}
.modal_filter .modal__inner { padding: 25px 30px 61px; width: 960px; max-width: 100%; }
@media (min-width: 992px) {
  .modal_filter form { height: 100%; }
  .modal_filter .modal__container { padding: 0px 20px; }
  .modal_filter .modal__inner { margin-top: 0px; margin-bottom: 0px; padding: 0px; }
}
.modal_filter_mobile .modal__container, .modal_filter_mobile .modal__inner { height: 100%; }
.modal_filter_mobile .button--back, .modal_filter_mobile .button--close { display: none; }
.modal_statistic .modal__container { min-height: 100%; }
.modal_statistic .modal__header { display: none; }
@media (max-width: 991px) {
  .modal_statistic .modal__header { display: block; }
}
.modal_statistic .modal__inner { max-width: 850px; width: 100%; }
.modal_statistic .modal__inner > div { height: 100%; }
@media (max-width: 991px) {
  .modal_statistic .modal__inner { height: 100%; padding: 0px; max-width: none; }
}
.modal_statistic .modal__body { padding-left: 0px; padding-right: 0px; }
@media (max-width: 991px) {
  .modal_statistic .modal__body { height: 100%; padding: 69px 0px 0px; }
}
@media (max-width: 991px) {
  .modal_statistic .button--back, .modal_statistic .button--close { left: 5px; right: auto; top: 9px; }
}
.modal_product_promotion .modal__body { padding: 0px; background: transparent; }
.modal_product_promotion .modal__inner { padding-bottom: 0px; background: transparent; }
.modal_product_promotion .button--back, .modal_product_promotion .button--close { top: 7px; }
.body--is_mobile .modal_product_promotion .button--back, .body--is_mobile .modal_product_promotion .button--close { display: none !important; }
@media (min-width: 992px) {
  .modal_product_promotion .modal__inner { width: 580px; max-width: 100%; padding-bottom: 0px; }
  .modal_product_promotion .modal__body { padding: 0px; }
  .modal_product_promotion .button--back, .modal_product_promotion .button--close { top: -12px; }
}
@media (max-width: 991px) {
  .modal_product_promotion .modal__wrapper { background: rgb(249, 249, 249); }
}
@media (max-width: 991px) {
  .modal_product_promotion--desktop .modal__wrapper { background: rgb(255, 255, 255); }
}
@media (min-width: 992px) {
  .modal_product_promotion--desktop .button--close-modal { display: none; }
}
.modal_product_promotion_info { text-align: center; }
.modal_product_promotion_info .status_badge__icon { width: 64px; height: 64px; margin-bottom: 20px; background-size: 32px; }
.modal_product_promotion_info .status_badge__icon--promoted-fast-sell-lite { background-repeat: no-repeat; background-position: 50% center; background-size: cover; }
.modal_product_promotion_info .modal__header { padding-top: 40px; padding-bottom: 15px; }
.modal_product_promotion_info .modal__body { padding: 0px 18px 35px; }
.modal_product_promotion_info .hint { overflow-wrap: break-word; white-space: pre-line; }
@media (max-width: 991px) {
  .modal_loader { position: fixed; width: 36px; top: 50%; left: 50%; transform: translate(-50%, -50%); }
}
.modal_loader--stub { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); }
.modal-utm .modal__container { height: 100%; }
.modal-utm .modal__box { background-color: transparent; }
.modal-utm .modal__box .modal_install_app_utm { background-color: rgba(57, 57, 57, 0.3); -webkit-tap-highlight-color: rgba(0, 0, 0, 0); }
.modal-utm .modal__body p { color: rgb(249, 249, 249); font-size: 16px; }
.modal-utm .modal_install_app_utm { width: 100%; background-color: transparent; margin: 0px; padding: 0px; }
.modal-utm .modal_install_app_utm .modal__inner { position: relative; height: 100%; }
.modal-utm .modal_install_app_utm .button--close-modal { left: auto; top: 0px; right: 0px; padding: 16px 14px 19px 24px; }
.modal-utm .modal_install_app_utm .button--close-modal .modal-close_control { color: rgb(249, 249, 249); }
@media (min-width: 992px) {
  .modal-utm .modal_install_app_utm .button--close-modal { right: 0px; background: transparent; box-shadow: none; height: auto; width: auto; }
  .modal-utm .modal_install_app_utm .button--close-modal i { position: static; }
}
.modal-utm .modal_install_app_utm .modal__body { position: absolute; top: -50%; transform: translateY(50%); width: 100%; height: 100%; text-align: center; text-decoration: none; }
.modal-utm-not-closable .button--close-modal, .modal-utm .modal-close_control_desktop { display: none; }
@media (max-width: 991px) {
  .modal_edit, .modal_email_edit, .modal_phone_edit { height: 100vh; }
}
.app_icon { display: inline-block; background-position: 50% center; background-repeat: no-repeat; width: 136px; height: 32px; background-size: 136px 32px; }
.app_icon--itunes { background-image: url("/build/images/appstore.438d5b.svg"); }
.app_icon--google { background-image: url("/build/images/googleplay.5833d4.svg"); }
.app_link { width: 162px; height: 48px; margin: 0px 10px; border-radius: 6px; display: inline-block; vertical-align: middle; background-color: rgb(0, 0, 0); background-position: 50% center; background-repeat: no-repeat; transition: background-color 0.2s ease 0s; }
.app_link--appstore { background-image: url("/build/images/app_store_btn.474bff.svg"); }
.app_link--googleplay { background-image: url("/build/images/google_play_btn.d8dc70.svg"); }
.app_link:hover { background-color: rgb(38, 38, 38); }
.stores { font-size: 0px; }
.search_input { position: absolute; z-index: 10; left: 21px; top: 69px; }
.search_input .progress { position: absolute; bottom: 0px; z-index: 10; margin: 0px; }
.change-phone-form .search_input .form-control, .form--invite .search_input .form-control, .p_form_app .search_input .form-control, .search_input .change-phone-form .form-control, .search_input .form--invite .form-control, .search_input .form_control, .search_input .p_form_app .form-control, .search_input .Select-control { background: rgb(255, 255, 255); border: 0px; box-shadow: rgba(0, 0, 0, 0.24) 0px 2px 2px 0px, rgba(0, 0, 0, 0.12) 0px 0px 2px 0px; width: 540px; max-width: 100%; font-size: 14px; color: rgb(57, 57, 57); height: 40px; }
.search_input .Select-placeholder { padding: 3px 20px; }
.search_input .Select-arrow-zone { opacity: 0; visibility: hidden; }
.search_input .Select-value-label { display: inline-block; padding: 0px 10px; position: relative; top: 4px; width: 100%; }
.search_input .Select-value-label--input { width: 100%; border: none; display: none; }
.search_input .Select-value-label--fake_input { top: 17px; left: 60px; }
.search_input .Select-value-label--fake_input:focus { outline: none; }
.search_input .Select-clear { font-size: 23px; }
.search_input .Select-clear-zone { position: absolute; right: 20px; top: 50%; margin-top: -12px; padding: 0px; z-index: 10; color: gray; }
.search_input .Select-clear-zone:hover { color: gray; }
.body--is_mobile .search_input .Select-clear-zone { right: 20px !important; }
.search_input .form_group { position: relative; margin-bottom: 0px; }
@media (max-width: 991px) {
  .search_input { top: 0px; right: 0px; left: 0px; }
  .change-phone-form .search_input .form-control, .form--invite .search_input .form-control, .p_form_app .search_input .form-control, .search_input .change-phone-form .form-control, .search_input .form--invite .form-control, .search_input .form_control, .search_input .p_form_app .form-control { height: 56px; }
  .search_input .Select-control, .search_input .Select-placeholder { padding-top: 11px; padding-bottom: 11px; }
}
.search_input .Select-value-label--fake_input { display: inline-block; position: absolute; z-index: 1; top: 0px; left: 0px; height: 100%; padding: 3px 20px; border-radius: 0px; font-size: 13px; }
@media (max-width: 991px) {
  .search_input .Select-value-label--fake_input { padding: 3px 60px; }
}
@media (max-width: 667px) {
  .search_input .Select-value-label--fake_input { padding-left: 55px; }
}
.search_fixed { position: absolute; left: 0px; right: 0px; top: -7px; z-index: 40; width: 100%; background: rgb(255, 255, 255); padding: 10px 10px 16px; margin-top: -15px; }
.search_fixed .filter_bar__button { padding-left: 11px; text-align: right; width: 35px; position: absolute; right: 11px; top: 10px; }
.search_fixed .filter_bar__button .icon { font-size: 24px; }
.change-phone-form .search_fixed .form-control, .form--invite .search_fixed .form-control, .p_form_app .search_fixed .form-control, .search_fixed .change-phone-form .form-control, .search_fixed .form--invite .form-control, .search_fixed .form_control, .search_fixed .p_form_app .form-control { height: 32px; width: 100%; padding: 3px 40px 5px; border-radius: 2px; background-color: rgba(214, 214, 214, 0.3); border: 0px; -webkit-appearance: none; }
.change-phone-form .search_fixed .form-control::-webkit-input-placeholder, .form--invite .search_fixed .form-control::-webkit-input-placeholder, .p_form_app .search_fixed .form-control::-webkit-input-placeholder, .search_fixed .change-phone-form .form-control::-webkit-input-placeholder, .search_fixed .form--invite .form-control::-webkit-input-placeholder, .search_fixed .form_control::-webkit-input-placeholder, .search_fixed .p_form_app .form-control::-webkit-input-placeholder { color: rgb(143, 143, 143); }
.search_fixed .form_group { margin-bottom: 0px; }
.search_fixed .input_group { width: 100%; float: none; }
.search_fixed .button[data-button-count]::after { position: relative; top: -1px; }
.search_fixed .form { padding: 0px; position: relative; margin-right: 36px; }
.search_fixed .form .button--green, .search_fixed .form .button--primary { position: absolute; left: 0px; top: 0px; right: auto; box-shadow: none; padding: 5px 5px 5px 10px; background: transparent !important; }
.search_fixed .form .button--green .icon, .search_fixed .form .button--primary .icon { color: rgb(143, 143, 143) !important; }
.search_fixed--fixed { z-index: 40; position: fixed; top: 58px; left: 0px; right: 0px; margin-top: 0px; box-shadow: rgba(0, 0, 0, 0.12) 0px 0px 2px 0px; border-bottom: 0px; transition: transform 0.2s ease-in-out 0s; transform: translate(0px); }
.search_fixed--fixed.search_fixed--hidden { transform: translateY(-116px); }
.search_fixed--fixed.search_fixed--disable_transition { transition: none 0s ease 0s; }
.search_fixed--top_fixed { box-shadow: none !important; }
@media (min-width: 992px) {
  .search_fixed { display: none !important; }
}
@media (min-width: 768px) {
  .search_fixed { padding-left: 15px; padding-right: 15px; }
}
.dropdown { position: absolute; top: 100%; width: 100%; margin-top: 8px; background-color: rgb(255, 255, 255); border-radius: 2px; border: 1px solid rgb(235, 235, 235); }
.dropdown__list { max-height: 350px; overflow-y: auto; font-size: 14px; color: rgb(57, 57, 57); list-style: none; padding: 0px 0px 5px; margin: 0px; }
.dropdown__list--search { max-height: 330px; }
.dropdown__list_item { padding: 16px; cursor: pointer; transition: all 0.2s ease 0s; }
.dropdown__list_item:hover { background: rgba(214, 214, 214, 0.5); }
.dropdown--checklist { z-index: 40; width: 213px; max-height: 390px; left: -5px; }
.dropdown--checklist .button { width: 100%; padding: 6px 23px 7px; margin-top: 0px; border-top: 1px solid rgb(243, 243, 243); border-radius: 0px 0px 2px 2px; }
.dropdown--checklist .button:hover { background: rgb(243, 243, 243); }
.dropdown--checklist .button[disabled] { color: rgb(194, 194, 194); cursor: default; background: rgb(255, 255, 255) !important; }
.dropdown--checklist .checkbox__label { padding: 8px 12px 8px 40px; }
.dropdown--checklist .checkbox__label::after { top: 11px; left: 10px; }
.dropdown--checklist .checkbox--right .checkbox__label { padding-left: 16px; }
.dropdown--checklist .checkbox--right .checkbox__label::after { top: 8px; right: 12px; left: auto; }
.dropdown--checklist .dropdown__list_item { padding: 0px; font-size: 14px; color: rgb(57, 57, 57); }
.dropdown--checklist .dropdown__list_item:hover { background: transparent; }
.dropdown--static { position: static; box-shadow: none; border-radius: 0px; width: auto; margin: 0px; }
.dropdown--static .dropdown__list { max-height: none; }
.filter .dropdown--static { margin-left: -30px; margin-right: -30px; }
.filter .dropdown--static .checkbox__label { padding: 13px 50px 14px 30px; border-bottom: 1px solid rgb(218, 218, 218); }
.filter .dropdown--static .checkbox__label::after { right: 35px; top: 13px; }
.dropdown--search { margin-top: -10px; opacity: 0; visibility: hidden; transition: opacity 0.2s ease 0s, visibility 0.2s ease 0s, margin-top 0.2s ease 0s; }
.dropdown--search .dropdown__list { padding: 9px 0px; }
.dropdown--search .dropdown_list__link { padding: 8px 16px; font-size: 14px; transition: background 0.2s ease 0s; }
.dropdown--search .dropdown_list__item--active { background: rgb(245, 245, 245); }
.dropdown--search .dropdown_list__desc, .dropdown--search .dropdown_list__value { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dropdown--search .dropdown_list__desc { color: rgb(143, 143, 143); }
.dropdown--search .dropdown_list__desc a { color: inherit; }
.dropdown--search mark { background: transparent; }
.dropdown--search.dropdown--show { opacity: 1; visibility: visible; margin-top: 8px; }
.dropdown_control { position: relative; cursor: pointer; }
.dropdown_menu { border-radius: 2px; background-color: rgb(255, 255, 255); border: 1px solid rgb(235, 235, 235); z-index: 40; margin-top: -10px; position: absolute; right: 10px; top: 100%; width: 282px; opacity: 0; visibility: hidden; transition: opacity 0.2s ease 0s, visibility 0.2s ease 0s, margin-top 0.2s ease 0s; }
.dropdown_menu::after, .dropdown_menu::before { bottom: 100%; right: 9px; border: solid transparent; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; }
.dropdown_menu::after { border-color: rgba(255, 255, 255, 0) rgba(255, 255, 255, 0) rgb(255, 255, 255); border-width: 10px; }
.dropdown_menu::before { border-color: rgba(255, 255, 255, 0) rgba(255, 255, 255, 0) rgb(235, 235, 235); border-width: 11px; margin-right: -1px; }
.dropdown_menu--show { opacity: 1; visibility: visible; margin-top: -5px; }
.dropdown_menu__header { display: none; position: relative; background: rgb(255, 255, 255); padding: 14px 60px 16px; border-bottom: 1px solid rgb(235, 235, 235); }
.dropdown_menu__title { margin-bottom: 0px; }
.dropdown_menu .button--back, .dropdown_menu .button--close { left: 10px; right: auto; }
@media (max-width: 991px), screen and (max-device-width: 991px) {
  .dropdown_menu { position: fixed; top: 0px; margin: 0px; left: 0px; right: 0px; width: 100%; height: 100%; z-index: 102; }
  .dropdown_menu__header { display: block; text-align: center; }
  .dropdown_menu__title { text-align: center; }
  .dropdown_menu__list { overflow: auto; position: absolute; top: 56px; width: 100%; left: 0px; bottom: 0px; }
}
.dropdown_list { padding: 0px; margin: 0px; list-style: none; text-align: left; font-size: 14px; }
.dropdown_list__item { color: rgb(57, 57, 57); position: relative; }
.dropdown_list__item ul.dropdown_list__inner { padding-left: 0px; padding-right: 0px; }
.dropdown_list__item--profile .button { margin-right: 0px; }
.dropdown_list__item--profile .dropdown_list__link { line-height: 21px; font-size: 16px; min-height: 43px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dropdown_list__divider { border-bottom: 1px solid rgb(235, 235, 235); float: none; width: 100%; }
.dropdown_list__link { text-decoration: none; color: rgb(57, 57, 57); padding-top: 12px; padding-bottom: 11px; display: block; cursor: pointer; }
.dropdown_list__link:hover { background: rgb(245, 245, 245) !important; }
.dropdown_list__text { font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dropdown_list__inner { margin: 0px; list-style: none; padding: 8px 20px; }
.dropdown_list__inner .button--flat, .dropdown_list__inner .button--flat_disabled { margin-left: -20px; }
.dropdown_list .button--flat, .dropdown_list .button--flat_disabled, .dropdown_list__link { padding-left: 20px; padding-right: 20px; }
.dropdown_list__tel { margin-bottom: 5px; font-weight: 600; text-transform: uppercase; padding: 10px 0px 11px; text-decoration: none; display: block; color: rgb(57, 57, 57); }
.dropdown_list .icon { color: rgb(143, 143, 143); font-size: 22px; transition: color 0.2s ease 0s; }
.dropdown_list .button--flat, .dropdown_list .button--flat[disabled], .dropdown_list .button--flat_disabled, .dropdown_list .button--flat_disabled[disabled] { max-width: none; margin-bottom: 5px; background: transparent !important; }
.dropdown_list .button--user_edit { padding-left: 15px; padding-right: 15px; line-height: 16px; }
.dropdown_list .button--user_edit .icon { position: relative; top: -1px; }
.dropdown_list .hint { line-height: normal; margin-bottom: 10px; max-width: 210px; }
.dropdown_list__icon { position: relative; display: inline-block; vertical-align: middle; margin-right: 10px; }
.dropdown_list__icon .badge { top: -4px !important; right: -5px !important; }
.dropdown_list .form_group, .dropdown_list .form_inline { margin-bottom: 10px; overflow: visible; }
.dropdown_list .form_group::after, .dropdown_list .form_group::before, .dropdown_list .form_inline::after, .dropdown_list .form_inline::before { content: " "; display: table; }
.dropdown_list .form_group::after, .dropdown_list .form_inline::after { clear: both; }
.dropdown_list .user__image, .dropdown_list .user__image--empty { display: none; vertical-align: middle; margin: -3px 14px -3px 0px; width: 36px; height: 36px; overflow: hidden; }
.dropdown_list .checkbox { float: right; }
@media (max-width: 991px), screen and (max-device-width: 991px) {
  .dropdown_list { font-size: 16px; }
  .dropdown_list .button--user_edit .icon { line-height: 36px; }
  .dropdown_list .user__image, .dropdown_list .user__image--empty { display: inline-block; }
  .dropdown_list__icon { margin-left: 6px; margin-right: 22px; }
  .dropdown_list__inner { padding-top: 11px; padding-bottom: 11px; }
  .dropdown_list .button--flat, .dropdown_list .button--flat_disabled, .dropdown_list__link { padding: 13px 14px; }
  .dropdown_list__item--profile .dropdown_list__icon { display: none; }
  .dropdown_list__item--profile .dropdown_list__text { position: relative; top: 1px; font-size: 16px; }
}
@media screen and (max-device-height: 575px) {
  .dropdown_list__inner { padding-top: 4px; padding-bottom: 4px; }
}
.tooltip { position: absolute; bottom: 100%; left: 12px; font-size: 12px; line-height: 16px; text-align: center; color: rgb(255, 255, 255); padding: 4px 13px 6px; z-index: 41; border-radius: 2px; background-color: rgba(32, 32, 32, 0.8); pointer-events: none; visibility: hidden; opacity: 0; text-transform: none; transform: translateY(80px); transition: transform 0.2s ease-in-out 0s, opacity 0.2s ease-in-out 0s; }
.tooltip strong { font-weight: 600; }
.tooltip--map { bottom: 80px; left: 20px; }
.tooltip--select { width: 208px; bottom: auto; right: auto; padding-left: 10px; padding-right: 10px; }
.tooltip--show { opacity: 1; visibility: visible; transform: translateY(60px); }
@media (max-width: 575px) {
  .tooltip--map { max-width: 200px; text-align: left; }
}
.tooltip_control:hover ~ .tooltip { opacity: 1; visibility: visible; transform: translateY(60px); }
.tooltip_control:hover ~ .tooltip.tooltip_disclaimer, .tooltip_control:hover ~ .tooltip.tooltip_favorite, .tooltip_control:hover ~ .tooltip.tooltip_product { transform: translateY(-5px); }
.tooltip_control.active ~ .tooltip_favorite, .tooltip_control ~ .tooltip_favorite.active { display: none; }
.tooltip_control.active ~ .tooltip_favorite.active { display: block; }
.tooltip_disclaimer { margin-bottom: 6px; }
.tooltip_favorite { margin-bottom: -6px; }
.tooltip_disclaimer, .tooltip_favorite, .tooltip_product { left: -100px; right: -100px; padding: 0px; background-color: transparent; transform: translate(0px); }
.tooltip_disclaimer .tooltip_text, .tooltip_favorite .tooltip_text, .tooltip_product .tooltip_text { display: inline-block; font-size: 12px; line-height: 16px; text-align: center; color: rgb(255, 255, 255); padding: 4px 13px 6px; border-radius: 2px; background-color: rgba(32, 32, 32, 0.8); white-space: nowrap; }
.tooltip_disclaimer .tooltip_text::after, .tooltip_favorite .tooltip_text::after, .tooltip_product .tooltip_text::after { top: 100%; left: 50%; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; margin-left: -7px; border-left: 7px solid transparent; border-right: 7px solid transparent; border-top: 5px solid rgba(32, 32, 32, 0.8); }
.body--is_mobile .tooltip_favorite, .body--is_mobile .tooltip_product { display: none !important; }
@media (max-width: 767px) {
  .tooltip_favorite, .tooltip_product { display: none !important; }
}
.tooltip_disclaimer .tooltip_text { white-space: inherit; }
.tooltip_container { position: relative; }
.label { padding: 0px 8px; z-index: 22; display: inline-block; vertical-align: middle; text-transform: uppercase; color: rgb(255, 255, 255); cursor: default; border-radius: 2px; font-size: 10px; font-weight: 600; }
.label--red { background-color: rgb(247, 80, 89); }
.label--green { background-color: rgb(89, 168, 64); }
.label--white { background-color: rgb(255, 255, 255); color: rgb(57, 57, 57); box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px; }
.label--yellow { background-color: rgb(255, 175, 22); color: rgb(57, 57, 57); }
.label:hover .tooltip { opacity: 1; visibility: visible; }
.label .tooltip { top: 100%; margin-top: 4px; left: 50%; max-width: none; bottom: auto; line-height: normal; width: 200px; transform: translate(-50%); }
.badge { font-size: 9px; font-weight: 600; color: rgb(255, 255, 255); border-radius: 100px; position: absolute; right: -2px; top: 2px; text-align: center; line-height: 14px; min-width: 14px; }
.header_bar__login .badge { top: 0px; right: -4px; z-index: 1; pointer-events: none; }
.button .badge { right: 5px; top: 5px; }
.badge--red { background: rgb(247, 80, 89); }
.hint { color: gray; font-size: 12px; line-height: 16px; }
.hint a { color: rgb(3, 154, 211); text-decoration: none; }
.hint--bottom { padding: 0px 20px; display: block; text-align: center; }
.hint--error { color: rgb(247, 80, 89) !important; }
.alert_message { color: rgba(57, 57, 57, 0.5); text-align: center; padding: 11% 20px; }
.alert_message__img { height: 104px; width: 162px; margin-left: auto; margin-right: auto; position: relative; margin-bottom: 25px; }
.alert_message__img .icon { font-size: 70px; color: rgb(133, 138, 146); position: absolute; left: 0px; right: 0px; margin: -35px auto 0px; text-align: center; top: 50%; z-index: 22; }
.alert_message__img--blocked { background: url("/build/images/pic-blacklist.3e0e72.svg") center top no-repeat; width: 98px; height: 128px; }
.alert_message__img--blocked::after { display: none; }
.alert_message__img--error { background: url("/build/images/error.42895d.svg") 0px 0px no-repeat; width: 72px; }
.alert_message__img--error::after { display: none; }
.alert_message__img--favs { background: url("/build/images/pic_cards.6ed89c.svg") center top no-repeat; }
.alert_message__img--favs::after { display: none; }
.alert_message__img--blacklist { background: url("/build/images/pic-page-blacklist.3e005b.svg") 50% center no-repeat; width: 152px; height: 112px; }
.alert_message__img--blacklist::after { display: none; }
.alert_message__img--followers { background: url("/build/images/pic-followers.80031d.svg") 50% center no-repeat transparent; width: 165px; height: 112px; border-radius: 2px; }
.alert_message__img--followers::after { display: none; }
@media (min-width: 992px) {
  .alert_message__img--followers { height: 170px; }
}
.alert_message__img--following { background: url("/build/images/pic-following.4fa597.svg") 50% center no-repeat transparent; width: 165px; height: 112px; border-radius: 2px; }
.alert_message__img--following::after { display: none; }
@media (min-width: 992px) {
  .alert_message__img--following { height: 170px; }
}
.alert_message__pic { height: 100%; background: url("/build/images/pic.484437.svg") 0px 0px no-repeat; width: 72px; float: left; border-radius: 2px; }
.alert_message__pic + .alert_message__pic { margin-left: 18px; }
.alert_message__title { font-size: 20px; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; font-weight: 500; color: rgb(57, 57, 57); margin-bottom: 10px; line-height: normal; }
.alert_message p { color: rgba(57, 57, 57, 0.5); margin: 0px auto 18px; max-width: 300px; }
.alert_text { color: rgba(57, 57, 57, 0.5); margin: 0px; }
.alert_text .icon { font-size: 22px; color: rgb(127, 127, 127); position: relative; top: -1px; margin-right: 7px; margin-left: 2px; float: left; }
.alert_text a { color: rgb(3, 154, 211); text-decoration: none; }
.alert_text span { overflow: hidden; display: block; }
.alert_text + .pagination__button { margin-top: 10px; }
.alert_text--margin_bottom { margin-bottom: 16px; }
.alert_text--margin_top { margin-top: 16px; }
.alert_text--simple { font-size: 16px; line-height: 1.5; color: rgb(57, 57, 57); }
.map { background: rgb(240, 240, 240); position: relative; overflow: hidden; }
.map::after, .map__error { vertical-align: middle; line-height: 268px; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; color: gray; background: rgb(240, 240, 240); display: none; text-align: center; }
.map__error { z-index: 2; }
.map__error p { color: gray; display: inline; }
.map__error a { color: rgb(3, 154, 211); }
.map::after { z-index: 1; content: attr(data-status-loading); }
.map--location--wrapper { position: relative; }
@media (max-width: 991px) {
  .map--location--wrapper { height: 100% !important; }
}
@media (max-width: 767px) {
  .map--location--wrapper { padding-bottom: 0px; }
}
.map--location { width: 100%; height: 490px; }
@media (max-width: 991px) {
  .map--location { height: 100% !important; }
}
@media (max-height: 767px) and (min-width: 992px) {
  body:not(.body--is_mobile) .map--location { height: 100% !important; }
  body:not(.body--is_mobile) .map--location--wrapper { height: 100%; }
}
@media (min-width: 992px) {
  .body--is_mobile .map--location { height: 440px; }
}
.map--product { width: 100%; height: 168px; }
.map--product .btn--fold, .map--product .map_buttons--zoom { display: none; }
.map--product--expanded { height: 600px; }
.map--product--expanded .map_buttons--zoom { display: inline-block; }
.map--product--expanded .btn--expand { display: none; }
.map--product--expanded .btn--fold, .map--product .icon { display: inline-block; }
.map--img.map--resizing::after { content: ""; display: block; }
.map--img.map--loading img { display: none; }
.map--error .map__error, .map--loading::after { display: block; }
@media (max-width: 991px) {
  .map--product { height: 180px; }
}
@media (max-width: 767px) {
  .map--product { height: 160px; }
}
.map_location { color: rgb(74, 74, 74); font-size: 14px; line-height: 1.4; padding: 14px 20px; }
.map_location .icon { color: rgb(143, 143, 143); margin-right: 12px; font-size: 20px; float: left; }
.map_buttons .btn { cursor: pointer; color: gray; text-align: center; }
.map_buttons .btn--target { width: 36px; height: 36px; padding: 6px 4px; margin-bottom: 16px; border-radius: 100px; margin-right: 1px; background: rgb(255, 255, 255); }
.map_buttons .btn--target .icon { font-size: 22px; }
.map_buttons .btn--toggle { width: 36px; height: 36px; padding: 8px 4px; background: rgb(255, 255, 255); border-radius: 2px; }
.map_buttons .btn--toggle .icon { height: 20px; width: 20px; }
.map_buttons .btn_group { width: 30px; margin: 0px auto; padding: 4px 6px; background: rgb(255, 255, 255); border-radius: 2px; }
.map_buttons .btn_group .btn { padding: 2px 0px; display: block; }
.map_buttons .btn_group .btn:first-child { margin-bottom: 2px; border-bottom: 1px solid rgb(235, 235, 235); }
.map_buttons .btn_group .icon { font-size: 16px; }
.map_buttons .btn_group .icon--remove { position: relative; top: 1px; }
.body--is_mobile .map_buttons .btn_group { display: none; }
.body--is_mobile .map_buttons .btn--target { width: 48px; height: 48px; padding: 8px 5px; }
.body--is_mobile .map_buttons .btn--target .icon { font-size: 32px; }
.map_pin { width: 32px; height: 42px; position: relative; font-size: 30px; color: rgb(47, 142, 203); }
.map_pin::after { content: ""; position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; z-index: 2; background: url("/build/images/location_icn.ce9ed2.svg") 50% center / cover no-repeat; }
.map_pin__ringlet { width: 86px; height: 86px; background-color: rgba(56, 148, 205, 0.15); border: 1px solid rgba(3, 154, 211, 0.12); border-radius: 50%; margin-left: 50%; left: -43px; position: absolute; bottom: -40px; opacity: 0; transform: scale(0.1); animation: 4s ease-out 0s infinite normal none running ring_pulse; }
.map_pin__ringlet:nth-of-type(2) { animation-delay: 0.6s; }
.map_pin__ringlet:nth-of-type(3) { animation-delay: 1.2s; }
@media (max-width: 575px) {
  .map_pin { width: 48px; height: 48px; font-size: 48px; left: -9px; top: -9px; }
}
.icon--fold { background: url("/build/images/map-fold.57bb21.svg") 50% center / contain no-repeat; }
.icon--expand { background: url("/build/images/map-expand.351419.svg") 50% center / contain no-repeat; }
@keyframes ring_pulse { 
  0% { opacity: 0; transform: scale(0.1); }
  50% { opacity: 1; }
  100% { opacity: 0; transform: scale(1.2); }
}
.card { border-radius: 2px; background-color: rgb(255, 255, 255); box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px; padding: 24px 40px; margin-bottom: 20px; }
@media (max-width: 991px) {
  .card { padding-left: 30px; padding-right: 30px; border-radius: 0px; }
}
@media (max-width: 767px) {
  .card { padding: 20px 15px; }
}
.cards_stack { margin-left: 0px; margin-right: 0px; }
.cards_stack > div { padding-left: 0px; padding-right: 0px; }
@media (max-width: 991px) and (min-width: 768px) {
  .cards_stack { box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px; margin-bottom: 20px; background: rgb(255, 255, 255); }
  .cards_stack .card { box-shadow: none; margin-bottom: 0px; }
}
.gallery { position: relative; overflow: visible; }
.gallery__header { cursor: pointer; font-size: 0px; line-height: 0; position: relative; max-height: 685px; overflow: hidden; height: 490px; border-radius: 2px; }
.gallery__header .gallery__header__img_bg_span, .gallery__header .gallery__header__img_overlay { background-repeat: no-repeat; background-size: cover; display: block; box-sizing: border-box; transform: scale(1.1); filter: blur(20px) brightness(0.9); height: 100% !important; width: 100% !important; }
.gallery__header .gallery__header__img_overlay { position: absolute; left: 0px; top: 0px; opacity: 1; z-index: 40; }
.gallery__header .gallery__header__img_overlay, .gallery__header .gallery__header__img_overlay--active { filter: blur(20px) brightness(0.9); }
.gallery__header .gallery__header__img_overlay--active { opacity: 0; transition: all 0.2s linear 0s; }
.gallery__header .gallery__header__img { position: absolute; top: 50%; left: 50%; z-index: 30; visibility: hidden; }
.gallery__header .gallery__header__img--nosize { position: absolute; width: auto; height: 100%; transform: translate(-50%, -50%); }
.gallery__header .gallery__header__img--active { visibility: visible; }
.gallery__header .gallery__header__img--ie { display: none; }
.gallery__header--main_image { vertical-align: middle; display: inline-block; border-radius: 2px; }
.gallery__list { float: right; height: 100%; position: relative; padding: 0px; margin: 0px; list-style: none; }
.gallery__list__item { cursor: pointer; width: 100%; line-height: 0; position: relative; padding-bottom: 5px; }
.gallery__list__item:last-child { margin-bottom: 0px; }
.gallery__list__item::after { content: ""; position: absolute; right: -8px; top: 0px; bottom: 5px; border-right: 2px solid rgb(3, 154, 211); opacity: 0; transition: opacity 0.2s ease 0s; box-sizing: border-box; }
.gallery__list__item img { border-radius: 2px; }
.gallery__list__item span { background: rgb(255, 255, 255); display: block; width: 100%; height: 100%; border-radius: 2px; position: relative; padding-bottom: 74.3%; overflow: hidden; box-shadow: rgb(245, 245, 245) 0px 0px 0px 1px; }
.gallery__list__item .empty_img { cursor: default; background-color: rgb(255, 255, 255); background-repeat: no-repeat; background-position: 50% center; background-size: 70px; }
.gallery__list__item--active::after { opacity: 1; }
.gallery img { width: 100%; }
.body--is_mobile .gallery { display: none; }
@media (max-width: 991px) {
  .gallery__header { border-radius: 0px; }
  .gallery__list { border-bottom: 1px solid rgb(245, 245, 245); }
  .gallery__list__item { margin: 0px; padding-bottom: 0px; }
  .gallery__list__item::after { right: auto; left: 0px; bottom: 0px; border-color: rgb(255, 255, 255); }
  .gallery__list__item span { padding-bottom: 75.8%; box-shadow: none; border-radius: 0px; }
}
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .gallery__list__item .empty_img { background-size: 35px; }
  .gallery__list__item span { padding-bottom: 74.4%; }
}
.gallery_preview { position: fixed; text-align: center; bottom: 12px; width: auto; left: 50%; font-size: 0px; line-height: 1; transform: translate(-50%); }
.gallery_preview__list { margin: 0px; font-size: 0px; line-height: 0; padding: 4px 9px; display: inline-block; border-radius: 100px; background-color: rgba(56, 56, 56, 0.6); }
.gallery_preview__item { padding: 4px 11px; display: inline-block; vertical-align: top; cursor: pointer; position: relative; opacity: 0.4; transition: opacity 0.2s ease 0s; }
.gallery_preview__item img { border-radius: 2px; }
.gallery_preview__item--active, .gallery_preview__item:hover { opacity: 1; }
.gallery_preview__dot { width: 8px; height: 8px; display: block; border-radius: 100px; background: rgb(255, 255, 255); }
.body--is_mobile .gallery_preview { display: none; }
.gallery_preview--photoswipe { z-index: 1501; }
@media (max-width: 575px), screen and (max-device-height: 575px) {
  .gallery_preview { display: none; }
  .body--is_mobile .gallery_preview { display: none; position: static; }
}
.gallery_control { position: absolute; cursor: pointer; height: 100%; top: 0px; z-index: 31; }
.gallery_control__inner { font-size: 36px; color: rgba(255, 255, 255, 0.4); background: rgba(0, 0, 0, 0.4); width: 40px; height: 40px; display: block; position: absolute; top: 50%; margin: -20px auto 0px; padding: 2px; text-align: center; }
.gallery_control--next, .gallery_control--prev { width: 15%; }
.gallery_control--next .icon, .gallery_control--prev .icon { display: block; }
.gallery_control--next .gallery_control__inner, .gallery_control--prev .gallery_control__inner { transition: background 0.2s ease 0s, color 0.2s ease 0s; }
.gallery_control--next:hover .gallery_control__inner, .gallery_control--prev:hover .gallery_control__inner { color: rgb(255, 255, 255); background: rgba(0, 0, 0, 0.6); }
.gallery_control--prev { left: 0px; }
.gallery_control--prev .gallery_control__inner { left: 0px; border-radius: 0px 2px 2px 0px; }
.gallery_control--next { right: 0px; }
.gallery_control--next .gallery_control__inner { right: 0px; border-radius: 2px 0px 0px 2px; }
.gallery_control--fullscreen { left: 0px; right: 0px; width: 70%; margin: 0px auto; }
.gallery_control--fullscreen .gallery_control__inner { font-size: 16px; color: rgb(255, 255, 255); background: rgba(0, 0, 0, 0.6); width: 140px; top: 50%; left: 0px; right: 0px; padding: 4px 14px; white-space: nowrap; opacity: 0; visibility: hidden; border-radius: 2px; transition: opacity 0.2s ease 0s, visibility 0.2s ease 0s; }
.gallery_control--fullscreen .icon { font-size: 30px; display: inline-block; vertical-align: middle; margin: 0px 2px 0px -2px; }
.gallery_control--fullscreen span { display: inline-block; vertical-align: middle; position: relative; top: -1px; }
.gallery_control--fullscreen:hover .gallery_control__inner { opacity: 1; visibility: visible; }
.body--is_mobile .gallery_control { display: none !important; }
.gallery__list { font-size: 0px; width: 160px; z-index: 10; right: 0px; top: 0px; position: absolute; overflow: hidden; padding-bottom: 2px; bottom: -2px; height: auto; }
.gallery__list--inner { transform: translateZ(0px); padding: 0px; margin: 0px; list-style: none; transition: transform 0.2s ease 0s; }
.gallery__list + .gallery__header { margin-right: 166px; }
.product__images--high_amount .gallery__list + .gallery__header { margin-right: 68px; }
.product__images--high_amount .gallery__list .gallery__list__item { padding-bottom: 3px; }
.product__images--high_amount .gallery__list .gallery__list__item .empty_img { background-size: 30px; }
.product__images--high_amount .gallery__list .gallery__list__item span { padding-bottom: 91.6%; }
@media (min-width: 992px) {
  .gallery__list { padding-right: 10px; margin-right: -10px; padding-left: 1px; width: 171px; }
  .product__images--high_amount .gallery__list { width: 75px; }
}
@media (max-width: 991px) {
  .gallery__list { width: 165px; }
  .gallery__list + .gallery__header { margin-right: 165px; }
  .product__images--high_amount .gallery__list { width: 64px; }
  .product__images--high_amount .gallery__list + .gallery__header { margin-right: 64px; }
  .product__images--high_amount .gallery__list .gallery__list__item span { padding-bottom: 93.5%; }
}
@media (max-width: 575px) {
  .gallery__list { width: 90px; }
  .gallery__list + .gallery__header { margin-right: 90px; }
}
.gallery_counter { position: absolute; bottom: 10px; right: 10px; border-radius: 2px; background-color: rgba(0, 0, 0, 0.2); padding: 4px 7px; z-index: 32; font-size: 12px; color: rgb(255, 255, 255); }
.gallery_counter .icon { font-size: 14px; margin-right: 8px; }
.gallery_counter__value { position: relative; top: -1px; }
.gallery_counter .icon, .gallery_counter__value { display: inline-block; vertical-align: middle; line-height: normal; }
.gallery_counter--list { left: 16px; top: 16px; bottom: auto; right: auto; line-height: normal; padding: 0px 4px 2px; }
.gallery_counter--list .icon { margin-right: 6px; font-size: 14px; }
.body--is_mobile .gallery_counter--list { left: 12px; }
@media (max-width: 991px) {
  .gallery_counter { right: auto; left: 10px; background-color: rgba(0, 0, 0, 0.2); }
  .body--is_mobile .gallery_counter { left: 0px; right: auto; }
  .body--is_mobile .product_item--ad .gallery_counter { left: 10px; }
}
.advertisement_top_banner__content { height: 90px; background-color: rgb(245, 245, 245); border-radius: 4px; text-align: center; }
.advertisement_top_banner__content > div { width: fit-content; margin: 24px auto 7px; display: block !important; }
.rb_banner_hidden { display: none; }
@media (max-width: 991px) {
  .rb_top_banner { display: none !important; }
}
.gpt_filter_banner > div { width: 240px; height: 400px; margin-left: auto; margin-right: auto; display: block !important; }
.gpt_filter_banner:not(:last-child) { margin-bottom: 8px; }
.share { margin: 12px 0px; }
.share__list { margin: 0px; padding: 0px; font-size: 0px; white-space: nowrap; text-align: left; }
.share__item { display: inline-block; vertical-align: middle; margin-right: 24px; }
.share__item:last-child { margin-right: 0px; }
.share__item--vk .share__link { color: rgb(89, 122, 158); }
.share__item--vk .share__link:hover { color: rgb(103, 135, 169); }
.share__item--ok .share__link { color: rgb(242, 114, 13); }
.share__item--ok .share__link:hover { color: rgb(243, 128, 37); }
.share__item--tw .share__link { color: rgb(85, 172, 238); }
.share__item--tw .share__link:hover { color: rgb(108, 183, 240); }
.share__item--fb .share__link { color: rgb(62, 91, 155); }
.share__item--fb .share__link:hover { color: rgb(69, 102, 173); }
.share__item--yt .share__link { color: red; }
.share__item--yt .share__link:hover { color: rgb(255, 26, 26); }
.share__link { display: block; width: 38px; height: 38px; text-align: center; vertical-align: middle; text-decoration: none; font-size: 38px; line-height: 38px; }
@media (max-width: 991px) {
  .share { margin: 0px; }
}
.social_block { border: 1px solid rgb(235, 235, 235); border-radius: 2px; margin-bottom: 10px; text-align: center; padding: 16px; color: rgb(143, 143, 143); font-size: 14px; }
.social_block__title { margin-bottom: 14px; }
.social_block__list { cursor: default; }
.social_block .share__link { cursor: pointer; font-size: 32px; line-height: 32px; width: 32px; height: 32px; }
.social_block .share__item { margin-right: 16px; }
.social_block .share__item:last-child { margin-right: 0px; }
.list { padding: 0px; margin: 0px 0px 30px; list-style: none; color: rgb(57, 57, 57); }
.list--simple { font-size: 14px; }
.list--simple .list__item { margin-bottom: 12px; }
.list--simple .list__link { color: rgb(3, 154, 211); text-decoration: none; }
.list--simple .list__title { font-weight: 600; margin: 10px 0px; }
.list--report { margin: 0px; }
.list--report:last-child .list_item:last-child { border-bottom: 0px; }
.list--report .list_item { border-bottom: 1px solid rgb(235, 235, 235); padding: 11px 16px; cursor: pointer; transition: background 0.2s ease 0s; }
.list--report .list_item:hover { background: rgb(247, 247, 247); }
.list--report .list_item a { text-decoration: none; color: inherit; display: block; }
.list--report .list_item__img { float: left; background: rgb(247, 247, 247); border-radius: 100px; line-height: 38px; width: 40px; height: 40px; text-align: center; margin-right: 16px; }
.list--report .list_item__img img { vertical-align: middle; height: 24px; }
.list--report .list_item__content { overflow: hidden; }
.list--report .list_item__title { font-size: 16px; font-weight: 400; font-style: normal; font-stretch: normal; line-height: 1.5; color: rgb(42, 42, 42); }
.list--report .list_item__text { font-size: 14px; font-weight: 400; font-style: normal; font-stretch: normal; line-height: 1.57; color: rgb(102, 102, 102); }
.list--report .list_item--active { width: 100%; float: none; background: transparent !important; border: 0px !important; cursor: auto !important; }
.list--data, .list--userlist { background: rgb(255, 255, 255); overflow: hidden; text-align: left; margin-bottom: 30px; }
.list--data .user, .list--userlist .user { margin-bottom: 0px; }
.list--data .user__image--empty a, .list--data .user__image a, .list--userlist .user__image--empty a, .list--userlist .user__image a { display: block; font-size: 0px; line-height: 0; }
.list--data .hint, .list--userlist .hint { font-size: 13px; color: rgb(155, 155, 155); }
.list--data .button_blacklist, .list--userlist .button_blacklist { margin: 15px -16px; float: right; }
.list--data .list__item, .list--userlist .list__item { border-top: 1px solid rgb(235, 235, 235); padding: 10px 32px; transition: background 0.2s ease 0s; }
.list--data .list__item:first-child, .list--userlist .list__item:first-child { border-top: 0px; }
.list--data .list__item:hover, .list--userlist .list__item:hover { background-color: rgba(250, 250, 250, 0.5); }
.list--userlist--loader { position: relative; width: 100%; height: 50px; margin-top: -30px; }
.list--userlist--loader .loader { height: 24px; width: 24px; margin-top: -12px; }
.list--userlist--loader .loader::after { height: 24px; width: 24px; border-width: 2px; border-color: rgb(3, 154, 211) rgb(3, 154, 211) rgb(3, 154, 211) transparent; }
.list--data .button--secondary, .list--userlist .button--secondary { color: rgb(155, 155, 155); }
.list--data .button--danger, .list--userlist .button--danger { color: rgb(247, 80, 89) !important; }
.list--actions { font-size: 14px; font-weight: 400; font-style: normal; color: rgb(57, 57, 57); }
.list--actions .icon { color: rgb(143, 143, 143); font-size: 20px; line-height: 14px; display: block; transition: color 0.2s ease 0s; }
.list--actions .badge { position: absolute; top: -8px; right: -5px; height: 14px; min-width: 14px; font-size: 10px; }
.list--actions .list__item { margin-bottom: 5px; }
.list--actions .list__item--red { color: rgb(255, 92, 74); }
.list--actions .list__item--red .icon, .list--actions .list__item--red .list__link { color: inherit; }
.list--actions .list__icon { display: inline-block; vertical-align: middle; position: relative; margin-right: 9px; top: -2px; }
.list--actions .list__link { display: block; color: rgb(57, 57, 57); padding: 11px 13px 13px 15px; text-decoration: none; border-radius: 2px; transition: color 0.2s ease 0s; }
.list--actions .list__link:hover { background: rgb(245, 245, 245); }
.list--data { box-shadow: none; border: 1px solid rgb(235, 235, 235); border-radius: 2px; }
.list--data a { color: rgb(3, 154, 211); text-decoration: none !important; }
.list--data .hint { color: rgb(143, 143, 143); font-size: 14px; line-height: 1.57; }
.list--data .button_change, .list--data .checkbox--slide { margin: -20px -10px 0px; position: absolute; right: 20px; top: 50%; }
.list--data .button_check-mailbox { margin: -20px -10px 0px; position: absolute; right: 135px; top: 50%; }
.list--data .checkbox--slide { margin: -9px 0px 0px; }
.list--data .list__item { padding: 0px; position: relative; width: 100%; }
.list--data .list__item:hover { background: rgb(245, 245, 245); }
.list--data .list__item--alt:hover { background: transparent; }
.list--data .list__item--alt .list__title { transition: color 0.2s ease 0s; }
.list--data .list__item--alt a.list__wrapper:hover, .list--data .list__item--alt a.list__wrapper:hover .list__title { color: rgb(3, 154, 211); }
.list--data .list__wrapper { padding: 13px 140px 13px 30px; min-height: 74px; display: table; width: 100%; }
.list--data .list__wrapper.email-item .snackbar { position: fixed; width: 215px; z-index: 1000; opacity: 0.9; border-radius: 4px; text-align: center; }
.list--data .list__wrapper.email-item .snackbar > span { padding: 10px 0px; }
@media (min-width: 991px) {
  .list--data .list__wrapper.email-item { padding-right: 10px; }
}
.list--data .list__wrapper.email-item .list__content::after { content: ""; display: block; clear: both; }
@media (min-width: 991px) {
  .list--data .list__wrapper .email-item__buttons { min-width: 280px; vertical-align: middle; display: table-cell; text-align: right; }
  .list--data .list__wrapper .email-item__buttons .button_change { position: static; margin: 0px; }
}
.list--data .list__wrapper.email-not-confirmed .list__value { color: rgb(247, 80, 89); }
.list--data .list__wrapper > span:empty { display: none; }
.list--data .list__wrapper .list__title.has-link { max-width: 420px; }
.list--data .list__content { display: table-cell; vertical-align: middle; }
.list--data .list__title { color: rgb(57, 57, 57); font-size: 14px; line-height: 1.71; font-style: normal; margin-bottom: 1px; }
.list--data .list__value { color: rgb(3, 154, 211); font-size: 14px; line-height: 1.5; font-style: normal; }
.list--settings .list__item { cursor: pointer; }
@media (max-width: 991px) {
  .list--data { margin: 0px 0px 15px; box-shadow: none; border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); }
  .list--data .list__item { padding-left: 0px; padding-right: 0px; border-bottom: 0px; border-top: 0px; }
  .list--data .list__wrapper { padding-left: 15px; }
  .list--settings { margin-top: 0px; margin-bottom: 0px; border-top: 1px solid rgb(235, 235, 235); }
  .list--settings .list__item { -webkit-tap-highlight-color: transparent; padding-left: 15px; padding-right: 15px; }
  .list--settings .list__item:hover { background-color: rgb(255, 255, 255); }
  .list--settings .list__item .button, .list--settings .list__item .button_change { display: none; }
  .list--settings .list__item .hint { float: right; max-width: 50%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .list--settings .list__item + .list__item .list__wrapper { border-top: 1px solid rgb(235, 235, 235); }
  .list--settings .list__item--switch .list__wrapper { padding-right: 0px; }
  .list--settings .list__item--switch .list__wrapper::before { display: none; }
  .list--settings .list__wrapper { padding: 12px 20px 12px 0px; min-height: 0px; position: relative; display: block; }
  .list--settings .list__wrapper::before { speak: none; content: ""; color: rgb(143, 143, 143); font-size: 24px; line-height: 14px; margin-right: -8px; margin-top: -6px; position: absolute; top: 50%; right: 0px; font-family: icons !important; }
  .list--settings .list__title { float: left; margin-bottom: 0px; font-size: 16px; line-height: 1.35; }
  .list--settings .list__value { float: right; max-width: 50%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .list--settings .list__content { display: block; }
  .list--settings .list__content::after, .list--settings .list__content::before { content: " "; display: table; }
  .list--settings .list__content::after { clear: both; }
}
@media (max-width: 767px) {
  .list--data, .list--userlist { margin: -15px -10px 15px; border-bottom: 1px solid rgb(235, 235, 235); }
  .list--data .list__item, .list--userlist .list__item { padding-left: 15px; padding-right: 15px; }
  .list--data .user--simple .user__image, .list--data .user--simple .user__image--empty, .list--userlist .user--simple .user__image, .list--userlist .user--simple .user__image--empty { width: 48px; height: 48px; margin-right: 19px; }
  .list--data .user--simple .user__image--empty img, .list--data .user--simple .user__image img, .list--userlist .user--simple .user__image--empty img, .list--userlist .user--simple .user__image img { width: 100%; height: 100%; }
  .list--data .user--simple .user__info, .list--userlist .user--simple .user__info { padding: 13px 0px; }
  .list--data .button, .list--userlist .button { background: transparent !important; }
  .list--data .button--secondary, .list--userlist .button--secondary { color: rgb(151, 151, 151); margin: 4px -10px; }
  .list--data .button_blacklist, .list--userlist .button_blacklist { margin-top: 8px; margin-bottom: 8px; }
  .list--data:not(.list--data), .list--userlist:not(.list--data) { box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px; }
  .list--data:not(.list--data) .button:not(.button--secondary), .list--userlist:not(.list--data) .button:not(.button--secondary) { font-size: 0px; line-height: 0; width: 38px; height: 38px; margin: 6px -10px; }
  .list--data:not(.list--data) .button:not(.button--secondary)::before, .list--userlist:not(.list--data) .button:not(.button--secondary)::before { content: ""; font-size: 22px; color: rgb(143, 143, 143); display: inline-block; vertical-align: middle; font-family: icons !important; }
  .modal .list--data, .modal .list--userlist { margin: 0px; }
  .list--data, .modal .list--userlist--loader { margin-top: 0px; }
  .list--data { margin-left: 0px; margin-right: 0px; }
  .list--settings .list__item { -webkit-tap-highlight-color: transparent; padding-left: 10px; padding-right: 10px; }
}
@media (min-width: 576px) {
  .list:last-child .list_item:last-child { border-bottom: 0px; }
}
.list_settings:last-child { margin-bottom: 60px; }
.list_settings .list_hint { padding: 10px 15px 0px; display: block; font-size: 14px; line-height: 1.57; color: rgb(143, 143, 143); background-color: rgb(249, 249, 249); margin-bottom: -1px; border-top: 1px solid rgb(235, 235, 235); position: relative; }
@media (max-width: 991px) {
  .list_settings { margin-bottom: 20px; }
  .list_settings:first-child { margin-top: -15px; }
  .list_settings:first-child .list { border-top: 0px; }
  .list_settings .checkbox--slide { padding: 3px 0px; right: 0px; }
  .list_settings .list { overflow: visible; }
  .list_settings .main__title { display: none; }
}
@media (max-width: 767px) {
  .list_settings .list_hint { padding-left: 10px; padding-right: 10px; }
}
.list__item.email-not-confirmed { background-color: rgba(247, 80, 89, 0.1); }
.list__item.email-not-confirmed:hover { background-color: rgba(247, 80, 89, 0.3); }
.list__item.email-not-confirmed .list__value { color: rgb(247, 80, 89); }
.list_header { padding-bottom: 15px; top: -58px; }
.list_header .button--back, .list_header .button--close { left: 5px; right: auto; margin-top: -21px; line-height: 22px; }
.text { font-size: 14px; color: rgba(57, 57, 57, 0.97); overflow-wrap: break-word; word-break: break-word; line-height: 24px; }
.text h1, .text h2, .text h3, .text h4, .text h5, .text h6 { color: rgb(57, 57, 57); font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
.text h1, .text h2, .text h3, .text h4 { font-weight: 500; }
.text h2:first-child, .text h3:first-child, .text h4:first-child, .text h5:first-child, .text h6:first-child, .text p:first-child { margin-top: 0px; }
.text iframe, .text img { max-width: 100%; }
.text b, .text strong { font-weight: 600; }
.text ol, .text ul { padding: 0px; list-style-position: inside; }
.text ul { list-style-type: disc; }
.text h1:first-child, .text h2:first-child, .text h3:first-child, .text h4:first-child, .text h5:first-child, .text h6:first-child { margin-top: 5px; }
.text ol + .h1, .text ol + .h2, .text ol + h1, .text ol + h2, .text ol + h3, .text ol + h4, .text ol + h5, .text ol + h6, .text p + .h1, .text p + .h2, .text p + h1, .text p + h2, .text p + h3, .text p + h4, .text p + h5, .text p + h6, .text ul + .h1, .text ul + .h2, .text ul + h1, .text ul + h2, .text ul + h3, .text ul + h4, .text ul + h5, .text ul + h6 { margin-top: 30px; }
.text ol, .text ul { list-style-position: outside; padding-left: 25px; }
.text ol li, .text ul li { margin-bottom: 4px; }
.text ol li h3, .text ul li h3 { font-size: 14px !important; }
.text ol h5, .text ul h5 { margin-bottom: 5px; }
.text ol ol, .text ol ul, .text ul ol, .text ul ul { padding-left: 15px; margin: 10px 0px; }
.text a { color: rgb(3, 154, 211); text-decoration: none; }
.text a:hover { text-decoration: underline; }
.text--small { font-size: 12px; line-height: 16px; }
.text--small ol, .text--small p, .text--small ul { margin-top: 0px; margin-bottom: 10px; line-height: 20px; }
.text--help, .text--license { margin-bottom: 40px; }
.text--help ol li h3, .text--help ul li h3, .text--license ol li h3, .text--license ul li h3 { display: inline-block; }
.text--help h2, .text--license h2 { text-align: left !important; line-height: normal !important; }
.text--help h2 strong, .text--license h2 strong { font-weight: 400; }
.text--help h2 a, .text--license h2 a { font-weight: 600; font-size: 14px; color: rgb(57, 57, 57); }
.text--help > p br, .text--license > p br { display: none; }
.text--help > p:first-child, .text--license > p:first-child { margin-top: 5px; margin-bottom: 25px; }
.text--help > p:first-child > strong, .text--license > p:first-child > strong { font-size: 24px; font-weight: 400; display: inline; }
.text--help .no_dots, .text--license .no_dots { list-style: none; font-size: 14px; margin-bottom: 30px; padding-left: 0px; }
.text--help .no_dots + h2, .text--license .no_dots + h2 { margin-top: 0px !important; }
.text--help .no_dots li, .text--license .no_dots li { margin-bottom: 12px; }
.text--help { font-size: 14px; }
.text--help p > strong { font-size: 14px; margin-bottom: 5px; display: inline-block; }
.text--help ol li, .text--help ul li { margin-bottom: 12px; }
.text--help ol li p, .text--help ul li p { display: inline; }
.text--help .with_padding_bottom strong + p { margin-top: 15px; margin-bottom: 40px; }
.text--help nav h2 { font-size: 14px; margin: 10px 0px !important; }
.text--help nav h2 strong { font-weight: 600; }
.text--help nav + p:first-of-type { display: none !important; }
.text--help nav + h2, .text--help nav + p:first-of-type + h2 { margin-top: 5px !important; }
@media (min-width: 992px) {
  .text--help { position: relative; }
  .text--help section { padding-left: 25.6%; }
  .text--help nav { position: absolute; left: 0px; top: -2px; width: 25%; padding-right: 20px; z-index: 2; }
}
@media (max-width: 991px) {
  .text--license { padding-top: 10px; }
  .text--license > p:first-child { display: none; }
}
@media (max-width: 767px) {
  .text .h2, .text h2 { font-size: 20px; }
  .text--help > p:first-child, .text--license > p:first-child { display: none; }
}
.tag { background: rgb(245, 245, 245); font-size: 14px; display: inline-block; vertical-align: top; border-radius: 2px; max-width: 100%; transition: box-shadow 0.2s ease 0s; }
.tag:hover { box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px; }
.tag__inner, .tag__link { padding: 4px 8px 7px; color: rgb(57, 57, 57); text-decoration: none; display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.tag .button--delete { right: 3px; margin-top: -13px; z-index: 1; }
.tag .icon:hover { color: rgb(57, 57, 57); }
.tag--rounded { line-height: normal; position: relative; border-radius: 100px; overflow: hidden; background: rgb(255, 255, 255); margin: 1px 10px 10px 0px; font-size: 14px; color: rgb(57, 57, 57); border: 1px solid rgb(235, 235, 235); box-shadow: none !important; }
.tag--rounded .tag__inner, .tag--rounded .tag__link { padding: 5px 33px 7px 18px; min-height: 30px; }
.tag--accent { background: rgb(243, 243, 243); border-color: rgb(243, 243, 243); }
@media (min-width: 992px) {
  .tag .button--delete { right: 6px; }
  .tag--rounded { font-size: 14px; }
  .tag--rounded .tag__inner, .tag--rounded .tag__link { padding: 5px 33px 7px 18px; }
}
.tag_container { margin-bottom: -10px; }
@media (min-width: 992px) {
  .tag_container { margin-top: -6px; }
}
@media (max-width: 991px) {
  .tag_container { padding: 0px 15px; margin-bottom: 0px; white-space: nowrap; overflow: auto hidden; height: 65px; }
  .tag_container::-webkit-scrollbar { display: none; }
  body:not(.body--is_safari) .tag_container { }
  .tag_container .tag--rounded { margin-bottom: 0px; margin-top: 0px; }
}
@media (max-width: 767px) {
  .tag_container { padding: 0px 10px; }
}
.error_page { text-align: center; position: relative; padding: 140px 0px; z-index: 2; }
.error_page__bg { background-position: 50% center; background-repeat: no-repeat; position: absolute; width: 100%; height: 700px; top: 0px; left: 0px; z-index: -1; }
.error_page__bg.layer_1 { background-image: url("/build/images/Confetti.a3e32e.svg"); background-size: 1000px; }
.error_page__bg.layer_2 { background-image: url("/build/images/Icons.1a6b95.svg"); background-size: 1000px; }
.error_page__message { font-size: 20px; color: rgb(57, 57, 57); font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; font-weight: 500; margin-bottom: 8px; line-height: 1.71; }
.error_page__text { max-width: 440px; margin-left: auto; margin-right: auto; color: rgba(57, 57, 57, 0.5); }
.error_page__text p { color: rgba(57, 57, 57, 0.5); line-height: 1.71; }
.error_page__title { font-size: 200px; font-weight: 300; color: rgb(216, 216, 216); margin-bottom: 0px; }
@media (max-width: 575px) {
  .error_page { padding: 30px 0px; }
  .error_page__title { font-size: 90px; font-weight: 300; }
  .error_page__text { padding: 0px 10px; max-width: 300px; }
  .error_page__bg { height: 400px; }
  .error_page__bg.layer_2 { display: none; }
  .error_page__bg.layer_1 { background-size: 470px; }
}
.cities .cities_list__col { margin-bottom: 58px; }
.cities .cities_list__item a { color: rgb(57, 57, 57); }
.cities .button { padding: 5px 9px; }
.cities .button .icon { margin-right: 18px; }
@media (max-width: 991px) {
  .cities { margin-top: -15px; margin-bottom: 40px; }
}
.cities_list { font-size: 0px; overflow: hidden; }
.cities_list__col { display: inline-block; vertical-align: top; width: 20%; padding: 0px 15px 0px 0px; list-style: none; margin: 0px 0px 11px; font-size: 14px; line-height: 21px; }
.cities_list__col--litera .cities_list__item { font-size: 64px; line-height: 77px; font-weight: 400; position: relative; top: -5px; color: rgba(57, 57, 57, 0.4); margin-bottom: 0px; }
.cities_list__item { margin-bottom: 11px; }
.cities_list__item a { color: rgb(155, 155, 155); text-decoration: none; }
.cities_list__item--active a, .cities_list__item a:hover { color: rgb(3, 154, 211) !important; }
.cities_list--big .cities_list__item { font-size: 16px; margin-bottom: 21px; }
.cities_list--big .cities_list__item a { color: rgb(57, 57, 57); }
@media (min-width: 992px) {
  .cities_list--big { margin: 35px 0px 0px; }
}
@media (max-width: 991px) {
  .accordion__body .cities_list--big .cities_list__col, .accordion__body .cities_list .cities_list__col { display: block; width: 100%; padding: 0px; float: none; margin-bottom: 0px !important; }
  .accordion__body .cities_list--big .cities_list__col--litera, .accordion__body .cities_list .cities_list__col--litera { position: absolute; top: auto; left: 19px; width: auto; line-height: 0; }
  .accordion__body .cities_list--big .cities_list__col--litera .cities_list__item, .accordion__body .cities_list .cities_list__col--litera .cities_list__item { top: -5px; line-height: 57px; font-size: 20px; font-weight: 600; }
  .accordion__body .cities_list--big .cities_list__col--litera ~ .cities_list__col .cities_list__item a, .accordion__body .cities_list .cities_list__col--litera ~ .cities_list__col .cities_list__item a { padding-left: 52px; }
  .accordion__body .cities_list--big .cities_list__item, .accordion__body .cities_list .cities_list__item { width: 100%; border-bottom: 1px solid rgb(234, 234, 234); margin-bottom: 0px !important; }
  .accordion__body .cities_list--big .cities_list__item a, .accordion__body .cities_list .cities_list__item a { display: block; padding: 14px 16px; font-size: 14px !important; }
  .accordion__body .cities_list--big .cities_list__item--bold, .accordion__body .cities_list .cities_list__item--bold { font-weight: 600; }
}
a.cities_list_all { color: rgb(3, 154, 211); text-decoration: none; line-height: 20px; }
a.cities_list_all:hover { color: rgb(13, 186, 252); }
.hubs_title { margin-bottom: 16px; }
.hubs_list { font-size: 0px; }
.hubs_list__col { display: inline-block; vertical-align: top; width: 25%; list-style: none; font-size: 14px; line-height: 21px; }
.hubs_list__item { margin-bottom: 11px; }
.hubs_list__item a { color: rgb(155, 155, 155); text-decoration: none; }
.hubs_list__item a:hover { color: rgb(3, 154, 211); }
.accordion__header { color: rgb(57, 57, 57); font-size: 16px; background-color: rgb(255, 255, 255); padding: 14px 16px; position: relative; cursor: pointer; border-top: 1px solid rgb(235, 235, 235); border-bottom: 1px solid rgb(235, 235, 235); }
.accordion__header::after { font-size: 24px; color: rgb(143, 143, 143); content: ""; position: absolute; top: 50%; right: 15px; margin-top: -10px; transform: rotate(-90deg); font-family: icons !important; }
.accordion--list .accordion__header { display: none; }
.accordion--list:first-child .accordion__header { border-top: 0px !important; }
@media (max-width: 991px) {
  .accordion--list { margin: -1px -15px 0px; }
  .accordion--list .accordion__header { display: block; }
  .accordion--list .accordion__body { display: none; }
}
@media (max-width: 767px) {
  .accordion--list { margin-left: -10px; margin-right: -10px; }
  .accordion--list .accordion__header::after { right: 10px; }
}
.accordion--open .accordion__body { display: block !important; }
.accordion--open .accordion__header::after { transform: rotate(90deg); }
.accordion--filter { border: 1px solid rgb(235, 235, 235); border-radius: 2px; margin-bottom: 10px; overflow: hidden; }
.accordion--filter .location_button { margin-bottom: 0px; }
.accordion--filter .location_button__wrap { border: 0px; height: auto; background: rgb(255, 255, 255) !important; }
.accordion--filter .location_button__text { text-align: left; font-size: 16px; padding: 14px; }
@media (max-width: 991px) {
  .accordion--filter { border-left: 0px; border-right: 0px; margin-bottom: 20px; }
  .accordion--filter:first-child { border-top: 0px; }
  .accordion--filter > div + div .accordion_item__header { border-top: 1px solid rgb(235, 235, 235); }
  .accordion--filter .accordion_item { padding-left: 15px; padding-right: 15px; background-color: rgb(255, 255, 255); border: 0px; margin: 0px; }
  .accordion--filter .accordion_item:hover { background-color: rgb(255, 255, 255); }
  .accordion--filter .accordion_item + .accordion_item .accordion_item__header { border-top: 1px solid rgb(235, 235, 235); }
  .accordion--filter .accordion_item .tooltip_control { display: none; }
  .accordion--filter .accordion_item .form_group { margin: 0px; }
  .accordion--filter .accordion_item__body { overflow: visible; max-height: none; display: none !important; }
  .accordion--filter .accordion_item__inner { padding: 0px; max-height: 100%; }
  .accordion--filter .accordion_item__header { padding: 13px 0px 14px; font-size: 16px; background: rgb(255, 255, 255) !important; }
  .accordion--filter .accordion_item__header .Select-arrow-zone { display: none; }
  .accordion--filter .accordion_item--mobile_hidden { display: none !important; }
}
@media (max-width: 767px) {
  .accordion--filter .accordion_item { padding-left: 10px; padding-right: 10px; }
}
.accordion_item { border-top: 1px solid rgb(235, 235, 235); margin-top: -1px; margin-bottom: 1px; transition: background-color 0.2s ease 0s; }
.accordion_item:last-child { margin-bottom: -1px; }
.accordion_item:hover { background-color: rgb(249, 249, 249); }
.accordion_item__header { cursor: pointer; position: relative; font-size: 15px; padding: 10px 15px 11px 44px; user-select: none; transition: background 0.2s ease 0s; }
.accordion_item__header::after, .accordion_item__header::before { content: " "; display: table; }
.accordion_item__header::after { clear: both; }
.accordion_item__header:hover { background: rgb(249, 249, 249); }
.accordion_item__body { max-height: 0px; overflow: hidden; transition: max-height 0.2s ease 0s; }
.accordion_item__inner { height: 100%; max-height: 300px; overflow: auto; padding: 4px 14px 20px; }
.accordion_item__inner .row { margin-left: 0px; margin-right: 0px; }
.accordion_item__inner .row > div { padding-left: 0px; padding-right: 0px; }
.accordion_item .Select-arrow-zone { position: absolute; left: 12px; top: 50%; margin-top: -5px; line-height: 8px; display: block; width: 20px; transition: transform 0.2s ease 0s; transform: rotate(0deg); }
.accordion_item .form_group { margin-bottom: 0px; }
.accordion_item .form_group + .form_group { margin-top: 20px; }
.accordion_item .form_group--radio { margin: 0px; }
.accordion_item .form_group--radio .form_control--radio:last-child { margin-bottom: 0px; }
.accordion_item .form_group--checkbox .form_label { font-size: 15px; }
.accordion_item .dash { position: absolute; top: 50%; left: 100%; margin-left: 3px; margin-top: -9px; line-height: normal; color: rgb(143, 143, 143); }
.accordion_item .form_label--fixed + .form_control--number { padding-left: 30px; }
.accordion_item--without_header { background-color: rgb(255, 255, 255); position: relative; }
.accordion_item--without_header .accordion_item__inner { padding-bottom: 5px; padding-top: 5px; overflow: visible; }
.accordion_item--without_header .accordion_item__body { overflow: visible; }
.accordion_item--without_header .form_control--checkbox { font-size: 16px; }
.accordion_item--without_header .form_control--checkbox .tooltip { opacity: 0; visibility: hidden; }
.accordion_item--without_header .tooltip_control { padding: 5px 10px; font-size: 16px; color: gray; display: inline-block; vertical-align: middle; margin: -5px 0px -5px 5px; line-height: 15px; position: relative; top: 0px; }
.accordion_item--without_header .tooltip_control + .tooltip { top: auto; bottom: 100%; margin-top: 0px; left: auto; right: -14px; z-index: 40; margin-bottom: 5px; width: 190px; transform: none; }
.accordion_item--without_header .tooltip_control + .tooltip::after { top: 100%; right: 47px; left: auto; border-style: solid; border-image: initial; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; border-color: rgba(32, 32, 32, 0.8) rgba(255, 255, 255, 0) rgba(255, 255, 255, 0); border-width: 8px; }
.accordion_item--without_header .tooltip_control:hover + .tooltip { opacity: 1; visibility: visible; }
.accordion_item--open .accordion_item__body { max-height: 1500px; transform: translateZ(0px); }
.accordion_item--open .Select-arrow-zone { transform: rotate(180deg); }
.accordion_item--securePayment .form_control--checkbox .form_label { font-size: 14px; }
.upload_zone { position: relative; overflow: hidden; }
.upload_zone .icon { color: rgb(143, 143, 143); font-size: 36px; position: absolute; left: 0px; right: 0px; top: 50%; margin: -18px auto 0px; text-align: center; transition: color 0.2s ease 0s; }
.upload_zone img { border-radius: 100px; width: 100%; }
.upload_zone .loader_colored { width: 36px; height: 36px; position: absolute; left: 0px; right: 0px; top: 50%; margin: -18px auto 0px; display: none; }
.upload_zone__elem { background: rgb(244, 244, 244); border: 1px dashed rgba(128, 128, 128, 0.5); cursor: pointer; position: relative; margin-left: auto; margin-right: auto; transition: border 0.2s ease 0s, background 0.2s ease 0s; }
.upload_zone__elem:hover { background: rgb(231, 231, 231); }
.upload_zone__elem--image { width: 88px; height: 88px; border-radius: 100px; }
.upload_zone__elem--image img { border-radius: 100px; }
.upload_zone--active .upload_zone__elem, .upload_zone__elem--active { border: 1px solid rgb(3, 154, 211); background-color: rgb(223, 242, 255); }
.upload_zone--active .upload_zone__elem:hover, .upload_zone__elem--active:hover { background-color: rgb(223, 242, 255); }
.upload_zone--active .upload_zone__elem .icon, .upload_zone__elem--active .icon { color: rgb(3, 154, 211); }
.upload_zone--loading .upload_zone__elem:hover, .upload_zone__elem--loading:hover { background: rgb(244, 244, 244); }
.upload_zone--loading .upload_zone__elem .loader_colored, .upload_zone__elem--loading .loader_colored { display: block; }
.upload_zone--loading .upload_zone__elem .icon, .upload_zone--loading .upload_zone__elem .user-photo, .upload_zone__elem--loading .icon, .upload_zone__elem--loading .user-photo { display: none; }
.upload_zone--uploaded .upload_zone__elem, .upload_zone__elem--uploaded { border: 0px; }
.upload_zone--uploaded .upload_zone__elem .icon, .upload_zone__elem--uploaded .icon { display: none; }
.upload_zone .form_control--file { position: absolute; width: 100%; height: 100%; opacity: 0; padding: 0px; cursor: pointer; z-index: 40; }
.upload_zone .form_control--file:active + .upload_zone__elem, .upload_zone .form_control--file:focus + .upload_zone__elem, .upload_zone .form_control--file:hover + .upload_zone__elem { background: rgba(245, 245, 245, 0.9); border-color: rgb(3, 154, 211); overflow: hidden; }
.upload_zone .form_control--file:active + .upload_zone__elem .icon, .upload_zone .form_control--file:focus + .upload_zone__elem .icon, .upload_zone .form_control--file:hover + .upload_zone__elem .icon { color: rgb(3, 154, 211); }
.auth_group { position: relative; max-width: 100%; width: 360px; padding-left: 20px; padding-right: 20px; margin: 100px auto 40px; text-align: center; }
.auth_group__img { background: url("/build/images/bag.374a57.svg") 50% center / cover no-repeat; width: 58px; height: 58px; margin: 0px auto 28px; }
.auth_group__img--logo { background: url("/build/images/youla_logo.5e8f99.svg") 50% center / 64px 64px no-repeat; width: 64px; height: 64px; }
.auth_group__title { font-size: 20px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; line-height: normal; color: rgb(57, 57, 57); margin-bottom: 12px; }
.auth_group__desc { color: gray; line-height: 1.43; padding: 0px 10px; }
.auth_group__form { text-align: left; }
.auth_group__form > .row { margin-left: -7px; margin-right: -7px; }
.auth_group__form > .row > div { padding-left: 7px; padding-right: 7px; }
.auth_group__form .col-xs-8 { padding-right: 15px; }
.auth_group__form .button .icon { margin-right: 0px; position: relative; top: -1px; }
.auth_group__form .button .icon--vk { top: -2px; }
.auth_group .form_control__country_code { padding-right: 0px; }
.auth_group .form_control--invalid { border-width: 0px 0px 2px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; border-bottom-style: solid; border-bottom-color: rgb(255, 92, 74); box-shadow: none; }
.auth_group .Select-control { padding-left: 0px !important; }
.auth_group .Select-value { max-width: 300px !important; }
.auth_group .form__buttons { margin-top: 0px; }
.auth_group .form_group, .auth_group .form_inline { margin-bottom: 10px; }
.auth_group .form_group + .button--green, .auth_group .form_group + .button--primary, .auth_group .form_inline + .button--green, .auth_group .form_inline + .button--primary { margin-top: 10px; }
.auth_group .button:not(.button--resend) { width: 100%; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; }
.auth_group .button_container .button { margin-bottom: 0px; }
.auth_group .form_inline--code + .hint { height: 0px; transition: height 0.2s ease-in 0s; margin-top: 4px !important; }
.auth_group .form_inline--code + .hint--active { height: 16px !important; }
.auth_group:not(.auth_group--modal) .button:not(.button--resend) { width: 100%; margin-bottom: 12px; }
.auth_group .hint { margin: 20px 0px; font-size: 12px; line-height: 16px; color: gray; }
.auth_group .hint--error { transition: height 0.2s ease-in 0s; color: rgb(255, 92, 74) !important; }
.auth_group .hint--error--active { height: 32px !important; }
.auth_group .upload_zone { margin-left: -10px; margin-right: -10px; }
.auth_group .button__confirm { transition: transform 1s ease-in 0s; }
.modal_edit .auth_group .button:not(.button--resend) { width: auto; margin-bottom: 0px; }
.auth_group--modal .auth_group__form { margin-top: 30px; }
.auth_group--blocked::before { content: ""; color: rgb(247, 80, 89); font-size: 44px; line-height: normal; margin-bottom: 20px; display: block; width: 100%; text-align: center; font-family: icons !important; }
.auth_group--blocked .auth_group__form { max-width: 300px; margin-left: auto; margin-right: auto; }
.auth_group--blocked .auth_group__title { color: rgb(247, 80, 89); }
.auth_group--blocked .button:not(.button--resend) { margin-bottom: 11px; }
.auth_group .button--back { position: absolute; top: 52px; left: 9px; display: inline-block; height: 35px; z-index: 1; margin-bottom: 0px; width: 35px !important; }
.auth_group_button + .auth_group_button { margin-top: 8px; }
.im { border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px; background: rgb(255, 255, 255); min-height: 250px; min-width: 700px; }
.im__list { border-right: 1px solid rgb(235, 235, 235); float: left; width: 285px; }
.im__content, .im__list { background: rgb(255, 255, 255); position: relative; height: 100%; }
.im__content { overflow: hidden; }
.im__header { border-bottom: 1px solid rgb(235, 235, 235); }
.im_container .im__preview_text { font-size: 13px; line-height: 16px; color: rgb(143, 143, 143); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.im__scroll { overflow: hidden auto; height: 100%; }
.im__body { overflow: hidden; position: absolute; left: 0px; right: 0px; top: 65px; bottom: 70px; }
.im__body .im__scroll { padding: 20px 40px; }
.im__footer { position: absolute; left: 0px; right: 0px; bottom: 0px; background: rgb(255, 255, 255); padding: 5px 40px 16px; box-shadow: rgb(255, 255, 255) 0px -3px 9px 3px; }
.im__header { padding: 12px 40px; }
.im__header .user__info { text-decoration: none; padding-right: 0px !important; }
.im__product_price { color: rgb(3, 154, 211); font-weight: 600; }
.im .user { display: table; table-layout: fixed; width: 100%; margin-bottom: 0px; overflow: hidden; }
.im .user__name { font-size: 16px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.im .user__image, .im .user__image--empty { float: none; margin: 0px; width: 40px; height: 40px; position: relative; display: table-cell; vertical-align: top; line-height: 0; background: transparent; }
.im .user__image--empty img, .im .user__image img { width: 100%; }
.im .user__image--small { width: 32px; height: 32px; }
.im .user__info { display: table-cell; vertical-align: top; padding: 0px 15px; }
.im .loader::after { border-color: rgb(235, 235, 235) rgb(235, 235, 235) rgb(235, 235, 235) transparent; }
@media (max-width: 767px) {
  .im { margin: -15px -10px; box-shadow: none; }
  .im__list { background: rgb(249, 249, 249); }
  .im .im_list__item:hover { background: rgba(255, 255, 255, 0.5); }
}
.im_list__inner { display: table; width: 100%; height: 100%; table-layout: fixed; }
.im_list__item { padding: 14px 25px 14px 15px; border-top: 1px solid rgb(235, 235, 235); cursor: pointer; position: relative; overflow: hidden; transition: background 0.2s ease 0s; display: block !important; }
.im_list__item .button--delete { width: 20px; height: 20px; right: 10px; top: 2px; margin-top: 0px; border-radius: 100px; z-index: 40; opacity: 0; visibility: hidden; }
.im_list__item .button--delete .icon { vertical-align: top; margin-right: 0px; transition: color 0.2s ease 0s; }
.im_list__item .button--delete:hover { color: rgb(0, 0, 0); background: transparent; }
.im_list__item .button--delete:hover .icon { color: rgb(0, 0, 0); }
.im_list__item:hover { background: rgba(156, 156, 156, 0.1); }
.im_list__item:hover .button--delete { opacity: 1; visibility: visible; }
.im_list__item:first-child { border-top: 0px; }
.im_list__item .user__image--small { padding-top: 4px; }
.im_list__item--active { background: rgb(3, 154, 211) !important; }
.im_list__item--active .button--delete .icon, .im_list__item--active .im__preview_text { color: rgb(255, 255, 255); }
.im_list__item--active .button--delete:hover .icon { color: rgba(255, 255, 255, 0.8); }
.im_list__item--active .user__name { color: rgb(255, 255, 255); }
.im_list__item--loader { position: relative; height: 56px; }
.im_list__item--loader .loader { transform: translate3d(0px, -4px, 0px); }
.im_message { margin-bottom: 8px; overflow: hidden; }
.im_message__content { display: inline-block; vertical-align: top; text-align: left; width: auto; font-size: 14px; max-width: 270px; min-width: 110px; padding: 8px 12px 10px; color: rgb(74, 74, 74); background-color: rgb(245, 245, 245); border-radius: 2px; border: 1px solid rgba(0, 0, 0, 0.08); overflow-wrap: break-word; word-break: break-word; }
.im_message__time { font-size: 12px; color: rgb(169, 169, 169); }
.im_message__wrap { overflow: hidden; padding: 0px 15px; }
.im_message .user__image, .im_message .user__image--empty { float: left; }
.im_message--out { text-align: right; }
.im_message--out .im_message__content { background: rgb(3, 154, 211); color: rgb(255, 255, 255); }
.im_message--out .user__image, .im_message--out .user__image--empty { float: right; }
.im_message--out .im_message__time { color: rgba(255, 255, 255, 0.62); }
.im_chat__wrap { position: relative; }
.im_chat__input { border: 0px; border-radius: 2px; background: rgb(245, 245, 245); padding: 12px 50px 13px 20px; resize: none; height: 48px; width: 100%; display: block; }
.im_chat__input:focus { outline: none; }
.im_chat__input::-webkit-input-placeholder { color: rgb(143, 143, 143); }
.im_chat .button { position: absolute; bottom: 5px; right: 10px; z-index: 2; }
.im_chat .button:hover { transform: rotate(-10deg); }
.im_chat .button:hover .icon { color: rgb(3, 154, 211); }
.chats-not-found { position: absolute; left: 50%; text-align: center; color: rgb(3, 154, 211); font-weight: 600; transform: translate3d(-50%, -50%, 0px); }
.hublink { font-size: 0px; max-width: 1172px; min-width: 320px; padding: 0px 16px; width: 100%; margin: 0px auto; }
.hublink a { color: rgb(3, 154, 211); text-decoration: none; }
.hublink a:hover { cursor: pointer; text-decoration: underline; }
.hublink_list { display: flex; font-size: 14px; line-height: 21px; flex-wrap: wrap; }
.hublink_list__col { display: inline-block; vertical-align: top; width: 25%; list-style: none; font-size: 14px; line-height: 21px; flex-direction: column; }
@media (max-width: 991px) and (min-width: 575px) {
  .hublink_list__col { width: 50%; }
}
@media (max-width: 575px) {
  .hublink_list__col { width: 100%; }
}
.hublink_list__item { margin-bottom: 11px; }
.hublink_list__item::first-letter { text-transform: uppercase; }
.hublink_pages { margin-top: 40px; font-size: 14px; display: flex; flex-wrap: wrap; }
.hublink_pages__item { margin-right: 20px; margin-bottom: 8px; }
.hublink_list__col:not(:last-child) { padding-right: 16px; }
.box { position: fixed; width: 100%; height: 100%; visibility: hidden; opacity: 0; background-color: rgba(255, 255, 255, 0.98); top: 0px; left: 0px; right: 0px; bottom: 0px; overflow: hidden auto; transition: opacity 0.2s ease 0s; }
.box__inner { display: inline-block; min-height: 0px; }
.box__close { position: absolute; width: auto; height: auto; top: 24px; right: 24px; cursor: pointer; z-index: auto; transition: none 0s ease 0s; }
.box__close .button--back, .box__close .button--close { pointer-events: none; cursor: pointer; font-size: 16px; margin-top: 0px; position: relative; top: 0px; right: 0px; }
.box__close .button--back .icon.icon--close, .box__close .button--close .icon.icon--close { color: rgb(143, 143, 143); }
.box__content, .box__footer { text-align: center; }
.box__footer { position: fixed; bottom: 0px; left: 0px; width: 100%; margin: 20px 0px; }
.box--show { visibility: visible; opacity: 1; text-align: center; }
.box--show .box__content { display: block; }
.box .auth_group { padding: 30px 30px 22px; width: 370px; border: 1px solid rgb(235, 235, 235); }
.box .auth_group .form__buttons { overflow: visible; }
.box .auth_group .hint--error_active { height: auto !important; }
.box .auth_group__desc { padding: 0px; }
.box .auth_group__img { padding: 4px 10px 10px; margin-top: -70px; margin-bottom: 18px; border-radius: 100px; background-color: rgb(255, 255, 255); box-sizing: content-box; }
.box .auth_group .button--default { border: 0px !important; }
.box .auth_group .form_inline--code + .hint { height: auto; }
@media (max-width: 767px) {
  .box { background: rgb(255, 255, 255); }
}
@media (max-width: 575px) {
  .box .auth_group { margin-top: 50px; border: 0px !important; }
}
@media (max-height: 490px) {
  .box .auth_group { margin-bottom: 10px; }
  .box__footer { position: static; }
}
body:not(.body--animations) .box .auth_group { border: 1px solid rgb(222, 222, 222); }
.box__wrapper { z-index: 125; background: rgb(255, 255, 255); position: absolute; top: 0px; left: 0px; right: 0px; bottom: 0px; }
.box-cheesy-ios { position: absolute; height: 100vh; }
.rub b { font-weight: 400; }
.body--old_rouble .rub b, .rub i { display: none; }
.rub i { font-style: normal; content: "руб."; font-family: RoubleArial, Arial, sans-serif; text-indent: 0px; margin-left: -0.2em; }
.body--old_rouble .rub i { display: inline; user-select: text; }
.location_button { position: relative; margin-bottom: 30px; }
.location_button__wrap { width: 100%; height: 40px; display: table; table-layout: fixed; cursor: pointer; border-radius: 2px; border: 1px solid rgb(234, 234, 234); transition: background-color 0.2s ease 0s, border-color 0.2s ease 0s; }
.location_button__wrap:hover { background-color: rgb(245, 245, 245); border-color: rgb(245, 245, 245); }
.location_button__radius, .location_button__text, .location_button__type { display: table-cell; vertical-align: middle; }
.location_button__text { text-align: center; font-size: 14px; color: rgb(57, 57, 57); padding: 0px 20px; }
.location_button__text--with_radius { padding-right: 0px !important; }
.location_button__type { width: 34px; font-size: 20px; text-align: center; color: gray; }
.location_button__type:not(.hide) + .location_button__text { padding-right: 34px; }
.location_button__overflow { display: block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.location_button__radius { width: 34px; color: rgb(44, 131, 187); font-size: 12px; font-weight: 600; line-height: 0.83; padding-right: 10px; padding-left: 15px; text-align: center; }
.location_button__icon { position: absolute; width: 113px; height: 100%; top: 0px; left: 0px; opacity: 0.9; background: url("/build/images/pin_pic.9166d7.svg") 50% center / contain no-repeat; }
.location_button__icon ~ span { padding-left: 87px; display: block; }
.location_button__confirmation { z-index: 100; font-weight: 400; font-size: 14px; line-height: 1.43; top: 100%; left: 50%; position: absolute; margin-top: 10px; width: 445px; padding: 15px 36px 15px 20px; color: rgb(57, 57, 57); text-align: left; background-color: rgb(255, 255, 255); border-radius: 2px; box-shadow: none; border: 1px solid rgb(235, 235, 235); margin-left: -11px; transform: translate(-50%); }
.location_button__confirmation::after, .location_button__confirmation::before { bottom: 100%; left: 50%; border: solid transparent; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; }
.location_button__confirmation::after { border-color: rgba(255, 255, 255, 0) rgba(255, 255, 255, 0) rgb(255, 255, 255); border-width: 10px; margin-left: 0px; }
.location_button__confirmation::before { border-color: rgba(255, 255, 255, 0) rgba(255, 255, 255, 0) rgb(234, 234, 234); border-width: 11px; margin-left: -1px; }
.location_button__confirmation .button--back, .location_button__confirmation .button--close { top: 0px; margin-top: 0px; right: 2px; font-size: 22px; line-height: 15px; }
.location_button--header { margin-bottom: 0px; color: rgb(57, 57, 57); text-align: right; }
.location_button--header .location_button__wrap { width: 100%; height: 32px; border: 0px; display: block; }
.location_button--header .location_button__wrap:hover { background: transparent; }
.location_button--header .location_button__wrap:hover .location_button__text, .location_button--header .location_button__wrap:hover .location_button__text::before { color: rgb(3, 154, 211); }
.location_button--header .location_button__type { display: none !important; }
.location_button--header .location_button__text { display: block; position: relative; transition: color 0.2s ease 0s; padding: 6px 10px 6px 30px !important; }
.location_button--header .location_button__text::before { content: ""; display: inline-block; vertical-align: middle; color: rgb(143, 143, 143); font-size: 24px; position: absolute; top: 50%; margin-top: -10px; left: 0px; transition: color 0.2s ease 0s; font-family: icons !important; }
@media (max-width: 991px) {
  .location_button__confirmation { left: auto; right: 0px; transform: translate(0px); }
  .location_button__confirmation::after, .location_button__confirmation::before { left: 90%; }
}
.location_confirmation { position: fixed; z-index: 126; top: 50%; left: 0px; right: 0px; margin: -50px auto 0px; width: 221px; height: auto; padding: 13px 22px 5px; text-align: center; color: rgb(57, 57, 57); background-color: rgb(255, 255, 255); border-radius: 2px; box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 3px 0px, rgba(0, 0, 0, 0.2) 0px 0px 1px 0px; }
.location_confirmation__content { color: rgb(0, 0, 0); font-size: 16px; font-weight: 600; line-height: 1.25; display: block; margin-bottom: 8px; }
.location_confirmation__buttons .button--plain { padding: 10px 20px; margin: 0px 6px; }
.suggest_list { position: absolute; top: 100%; left: -1px; right: -1px; width: auto; margin: -1px 0px 0px; height: auto; max-height: 212px; z-index: 40; border: 1px solid rgb(235, 235, 235); border-radius: 2px; }
.suggest_list__item { padding: 10px 20px; font-size: 14px; line-height: normal; cursor: pointer; letter-spacing: 0px; position: relative; text-decoration: none; }
.suggest_list__item--is_focused, .suggest_list__item:hover { background-color: rgb(245, 245, 245) !important; color: rgb(51, 51, 51) !important; }
.suggest_list__item.is-selected, .suggest_list__item.is-selected:hover { color: rgb(3, 154, 211) !important; background: transparent !important; }
.suggest_list__item.suggest_list__item--disabled { cursor: default; background: transparent !important; color: rgba(57, 57, 57, 0.4) !important; }
.suggest_list__item:hover .tooltip { opacity: 1; visibility: visible; }
.suggest_list__item--subcategory { overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
.suggest_list .tooltip { top: 100%; bottom: auto; margin-top: 5px; line-height: normal; width: 225px; left: 50%; margin-left: -112px; transform: translate(0px); }
.body--is_mobile .suggest_list .tooltip { display: none; }
@media (max-width: 991px) {
  .suggest_list .tooltip { margin-top: 0px; left: 10px; margin-bottom: 10px; }
}
@media (max-width: 767px) {
  .suggest_list__item { position: relative; }
  .search_input .suggest_list__item::before { content: ""; position: absolute; top: 50%; left: 20px; margin-top: -12px; font-size: 22px; color: rgb(143, 143, 143); font-family: icons !important; }
  .search_input .suggest_list__item--type_city::before { content: ""; }
}
@media (max-width: 767px) {
  .suggest_list__item { font-size: 14px; }
}
.suggest_list .ReactVirtualized__Grid__innerScrollContainer { padding-bottom: 6px; }
.Select-hint { color: rgb(143, 143, 143); padding-left: 8px; }
.suggest_container .Select-control { border: 0px; height: 0px; padding: 0px; box-shadow: none; background: transparent; }
.suggest_container .suggest_list { width: auto; left: -21px; right: -21px; margin-top: -1px; }
.suggest_container .suggest_list__item { font-size: 15px; }
.properties_container { padding: 6px 0px 0px; margin-top: 10px; border-top: 1px solid rgb(235, 235, 235); }
.properties_container--stack { padding: 0px; margin: 0px -30px; }
.properties_container--stack .property_select { margin: 0px; border-top: 0px; border-right: 0px; border-left: 0px; border-image: initial; width: 100%; display: block; padding: 12px 30px 14px; font-size: 14px; line-height: normal; border-bottom: 1px solid rgb(255, 255, 255) !important; background: rgb(243, 243, 243) !important; }
.properties_container--stack .property_select__inner { padding: 0px; width: 100%; display: table; table-layout: fixed; background: transparent !important; }
.properties_container--stack .property_select .button--delete, .properties_container--stack .property_select__text, .properties_container--stack .property_select__value { overflow: hidden; display: table-cell; vertical-align: top; text-overflow: ellipsis; }
.properties_container--stack .property_select .button--delete { top: 0px; right: 0px; bottom: 0px; border-left: 0px; padding-top: 1px; padding-right: 25px; z-index: 10; background: transparent !important; }
.properties_container--stack .property_select__value { text-align: right; padding-left: 10px; color: rgba(57, 57, 57, 0.4); }
.properties_container--stack .property_select--selected { padding-right: 60px; }
.properties_container--stack .property_select--selected .property_select__value { color: rgb(57, 57, 57); }
@media (max-width: 991px) {
  .filter_bar .properties_container { margin-left: -15px; margin-right: -15px; padding-left: 15px; padding-right: 15px; }
}
.property_select { cursor: pointer; position: relative; vertical-align: top; padding-right: 21px; margin: 4px 10px 0px 0px; display: inline-block; background: transparent; border-radius: 2px; border: 1px solid rgb(234, 234, 234); font-size: 14px; transition: background 0.2s ease 0s; }
.property_select__inner { color: rgb(57, 57, 57); padding: 4px 8px 5px 10px; transition: background 0.2s ease 0s; }
.property_select__control { top: 0px; right: 0px; width: 28px; height: 100%; margin-top: 0px; position: absolute; }
.property_select__control::after { top: 50%; left: 50%; border-style: solid; border-image: initial; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; border-color: gray rgba(128, 128, 128, 0) rgba(128, 128, 128, 0); border-width: 5px; margin: -2px 0px 0px -5px; }
.property_select .button--delete { top: -1px; right: -1px; bottom: -1px; margin-top: 0px; padding: 0px 3px 2px; background: rgb(234, 234, 234); border-left: 1px solid rgb(255, 255, 255); border-radius: 0px 2px 2px 0px; z-index: 10; }
.property_select .button--delete .icon { margin-right: 0px; }
.property_select--active, .property_select .button--delete:hover, .property_select:hover { background: rgb(234, 234, 234); }
.property_select--selected { padding-right: 28px; transition: border 0.2s ease 0s; }
.property_select--selected .property_select__inner { background: rgb(234, 234, 234); }
.property_select--selected .button--delete:hover, .property_select--selected .property_select__inner:hover { background: rgb(215, 215, 215); }
.property_select--selected.property_select--active { border-color: rgb(234, 234, 234); }
.property_select--selected.property_select--active .property_select__inner { background: rgb(234, 234, 234); }
.breadcrumbs { padding: 17px 0px; height: 55px; }
.body--im .breadcrumbs { display: none !important; }
.breadcrumbs + .bundle { margin-top: 0px; }
.breadcrumbs_list { font-size: 12px; padding: 0px; margin: 0px; list-style: none; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.breadcrumbs_list__item { display: inline; position: relative; }
.breadcrumbs_list__item::after { content: "/"; padding: 0px 1px; color: rgba(57, 57, 57, 0.5); }
.breadcrumbs_list__item--current { color: rgba(57, 57, 57, 0.5); }
.breadcrumbs_list__item--current::after { display: none; }
.breadcrumbs_list__link { color: rgb(57, 57, 57); text-decoration: none; padding: 6px; margin: -6px; }
.breadcrumbs_list__link:hover { color: rgb(3, 154, 211); }
.page_title { letter-spacing: 0.04em; margin-top: 5px; margin-bottom: 15px; }
.page_title .title_wrapper { display: flex; align-items: center; }
.page_title .title_wrapper .button { margin: 0px 6px 0px -10px; float: left; }
.page_title .title_wrapper .wrapper_item, .page_title h1 { overflow: hidden; }
.page_title h1 { margin: 0px; padding-right: 10px; }
.page_title .form_control--select { min-width: 245px; }
.page_title .form_control--select > .Select-control .Select-value { font-size: 14px; }
.page_title .suggest_list { overflow: visible; left: 0px; }
.page_title .Select--single .suggest_list { top: -7px; }
.page_title--main { margin-top: 0px; margin-bottom: 20px; }
@media (max-width: 767px) {
  .page_title { display: none; }
}
.body--animations .box--show .auth_group { animation-fill-mode: forwards; }
.body--animations .box--show .auth_group__back, .body--animations .box--show .auth_group__desc, .body--animations .box--show .auth_group__form .auth_group_button, .body--animations .box--show .auth_group__form .button, .body--animations .box--show .auth_group__form .change-phone-form .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .form--invite .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .form_control:not(.form_control--select), .body--animations .box--show .auth_group__form .hint, .body--animations .box--show .auth_group__form .p_form_app .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .photo_upload_container, .body--animations .box--show .auth_group__img, .body--animations .box--show .auth_group__title, .change-phone-form .body--animations .box--show .auth_group__form .form-control:not(.form_control--select), .form--invite .body--animations .box--show .auth_group__form .form-control:not(.form_control--select), .p_form_app .body--animations .box--show .auth_group__form .form-control:not(.form_control--select) { opacity: 0; animation-fill-mode: forwards; transform: translateZ(0px); }
.body--animations .box--show .auth_group__form .form_group { visibility: hidden; animation-fill-mode: forwards; }
.body--animations .box--show .auth_group .button--back, .body--animations .box--show .auth_group__title { animation-delay: 0.1s; }
.body--animations .box--show .auth_group__desc { animation-delay: 0.2s; }
.body--animations .box--show .auth_group__form .button--odnoklassniki, .body--animations .box--show .auth_group__form .button--resend, .body--animations .box--show .auth_group__form .button--vk, .body--animations .box--show .auth_group__form .change-phone-form .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .form--invite .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .form_control:not(.form_control--select), .body--animations .box--show .auth_group__form .form_group, .body--animations .box--show .auth_group__form .p_form_app .form-control:not(.form_control--select), .body--animations .box--show .auth_group__form .photo_upload_container, .change-phone-form .body--animations .box--show .auth_group__form .form-control:not(.form_control--select), .form--invite .body--animations .box--show .auth_group__form .form-control:not(.form_control--select), .p_form_app .body--animations .box--show .auth_group__form .form-control:not(.form_control--select) { animation-delay: 0.3s; }
.body--animations .box--show .auth_group__form .auth_group_button, .body--animations .box--show .auth_group__form .hint { animation-delay: 0.4s; }
.body--animations .box--show .auth_group__form .auth_group_button--delay, .body--animations .box--show .auth_group__form .button--default { animation-delay: 0.5s; }
.c_create_message { position: relative; background: rgb(245, 245, 245); }
.c_create_message .show_if_modal { display: none; }
.c_create_message__wrap { display: table-cell; vertical-align: top; padding: 0px; }
.c_create_message__editor { border-top: 1px solid rgb(235, 235, 235); }
.c_create_message__form { display: block; position: relative; min-height: 47px; padding: 10px 60px; margin: 0px; }
.c_create_message__dropzone { display: none; position: absolute; top: 8px; left: 8px; bottom: 8px; right: 8px; border: 1px dashed rgb(3, 154, 211); background-color: rgb(255, 255, 255); z-index: 3; text-align: center; border-radius: 2px; color: rgb(3, 154, 211); font-size: 15px; }
.c_create_message__dropzone p { color: rgb(3, 154, 211); display: table-cell; vertical-align: middle; }
.c_create_message__dropzone .c_create_message__wrap { display: table; width: 100%; height: 100%; }
.c_create_message__dropzone .c_create_message__dropzone_active { display: none; }
.c_create_message__dropzone--active { background-color: rgb(223, 243, 255); border-style: solid; }
.c_create_message__dropzone--active .c_create_message__dropzone_pending { display: none !important; }
.c_create_message__dropzone--active .c_create_message__dropzone_active { display: block !important; }
.c_create_message__dropzone_wrap { display: table; width: 100%; height: 100%; }
.c_create_message__dropzone_wrap p { display: table-cell; vertical-align: middle; font-size: 15px; color: rgb(3, 154, 211); }
.c_create_message__dropzone_pending { display: block; }
.c_create_message__dropzone_active { display: none; }
.c_create_message__error, .c_create_message__hint { font-size: 12px; line-height: 16px; margin: 0px 60px; }
.c_create_message__submit, .c_create_message__upload { z-index: 2; position: absolute; bottom: 13px; }
.c_create_message__submit .button--disabled.button--green, .c_create_message__submit .button--green[disabled]:not(.button--clear_disabled), .c_create_message__submit .button--primary.button--disabled, .c_create_message__submit .button--primary[disabled]:not(.button--clear_disabled), .c_create_message__upload .button--disabled.button--green, .c_create_message__upload .button--green[disabled]:not(.button--clear_disabled), .c_create_message__upload .button--primary.button--disabled, .c_create_message__upload .button--primary[disabled]:not(.button--clear_disabled) { background: rgb(235, 235, 235) !important; color: rgb(143, 143, 143) !important; }
.c_create_message__submit .button--back, .c_create_message__submit .button--close, .c_create_message__submit .button--icon, .c_create_message__upload .button--back, .c_create_message__upload .button--close, .c_create_message__upload .button--icon { border-radius: 0px; color: rgb(143, 143, 143); background: transparent !important; }
.c_create_message__submit .button--back[disabled]:not(.button--success), .c_create_message__submit .button--close[disabled]:not(.button--success), .c_create_message__submit .button--disabled.button--back, .c_create_message__submit .button--disabled.button--close, .c_create_message__submit .button--icon.button--disabled, .c_create_message__submit .button--icon[disabled]:not(.button--success), .c_create_message__upload .button--back[disabled]:not(.button--success), .c_create_message__upload .button--close[disabled]:not(.button--success), .c_create_message__upload .button--disabled.button--back, .c_create_message__upload .button--disabled.button--close, .c_create_message__upload .button--icon.button--disabled, .c_create_message__upload .button--icon[disabled]:not(.button--success) { background: none !important; }
.c_create_message__submit .button--back .icon, .c_create_message__submit .button--close .icon, .c_create_message__submit .button--icon .icon, .c_create_message__upload .button--back .icon, .c_create_message__upload .button--close .icon, .c_create_message__upload .button--icon .icon { color: inherit; }
.c_create_message__submit .button--back:hover, .c_create_message__submit .button--close:hover, .c_create_message__submit .button--icon:hover, .c_create_message__upload .button--back:hover, .c_create_message__upload .button--close:hover, .c_create_message__upload .button--icon:hover { color: rgb(3, 154, 211); }
.c_create_message__upload { left: 10px; }
.c_create_message__submit { right: 7px; }
.c_create_message__submit:hover .icon { color: rgb(3, 154, 211); }
.c_create_message__textarea { border: none; overflow: hidden auto; box-shadow: none; display: block; background: transparent; resize: none; width: 100%; font-size: 15px; outline: none; padding: 12px 17px; height: 46px; max-height: 116px; min-height: 46px; }
.c_create_message__textarea:focus + .c_create_message__placeholder { opacity: 0.36; }
.c_create_message__placeholder { position: absolute; left: 17px; top: 13px; cursor: text; pointer-events: none; font-size: 15px; font-weight: 400; font-style: normal; font-stretch: normal; color: rgb(143, 143, 143); }
.c_create_message__field { height: 100%; position: relative; padding: 0px; background: rgb(255, 255, 255); border: 1px solid rgb(235, 235, 235); }
.c_create_message--is_with_value .c_create_message__placeholder { opacity: 0 !important; }
.c__message_list_history--drag .c_create_message .c_create_message__dropzone { display: block; }
@media (max-width: 991px) {
  .c_create_message { background-color: rgb(255, 255, 255); }
  .c_create_message__editor { border-top: 1px solid rgb(234, 234, 234); }
  .c_create_message__form { padding: 0px 45px; }
  .c_create_message__field { border: 0px; }
  .c_create_message__textarea { padding-left: 0px; padding-right: 0px; }
  .c_create_message__upload { left: 2px; }
  .c_create_message__submit { right: 0px; }
  .c_create_message__submit, .c_create_message__upload { top: 4px; }
  .c_create_message__error, .c_create_message__hint { margin-left: 13px; margin-right: 13px; margin-top: 10px; }
  .c_create_message__placeholder { left: 0px; }
  .body--is_safari .c_create_message__placeholder { left: 2px; }
}
.c_create_message_images { margin: 0px 60px; }
.c_create_message_images__success { -webkit-tap-highlight-color: transparent; margin-bottom: 10px; font-size: 0px; height: 78px; white-space: nowrap; overflow: auto hidden; transform: translateZ(0px); }
.c_create_message_images__pending { position: relative; margin-bottom: 10px; overflow: hidden; }
.c_create_message_images__pending_wrap { -webkit-tap-highlight-color: transparent; overflow: hidden auto; max-height: 115px; height: auto; transform: translateZ(0px); }
.c_create_message_images__success_item { display: inline-block; }
.c_create_message_images__success_item + .c_create_message_images__success_item { margin-left: 10px; }
@media (max-width: 767px) {
  .c_create_message_images { margin: 10px 13px 0px; }
}
.c_create_message_images_success { position: relative; height: 78px; max-width: 312px; overflow: hidden; }
.c_create_message_images_success__image { height: 78px; width: auto; }
.c_create_message_images_success__remove_button { outline: none; border: 0px; padding: 0px; margin: 0px; cursor: pointer; position: absolute; top: 0px; right: 0px; width: 24px; height: 24px; background-color: rgba(56, 56, 56, 0.4); color: rgb(255, 255, 255); text-align: center; line-height: 24px; vertical-align: middle; }
.c_create_message_images_success__remove_button .icon { font-size: 24px; }
.c_create_message_images_success__remove_button:hover { background-color: rgba(56, 56, 56, 0.8); }
.c_create_message_images_pending { white-space: nowrap; height: 22px; overflow: hidden; }
.c_create_message_images_pending__bar { float: left; width: 267px; min-width: 267px; margin-right: 11px; }
.c_create_message_images_pending__buttons { float: right; margin-top: -3px; margin-left: 3px; line-height: 0; }
.c_create_message_images_pending__name { padding-top: 2px; display: flex; }
.c_create_message_images_pending__name span { font-size: 12px; line-height: 16px; color: rgb(57, 57, 57); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0px; }
.c_create_message_images_pending__progress_bar { margin-top: 7px; height: 6px; width: 100%; background-color: rgb(235, 235, 235); }
.c_create_message_images_pending__progress { background-color: rgb(3, 154, 211); height: 100%; width: 0px; }
.c_create_message_images_pending__remove_button { background-color: transparent; outline: none; border: 0px; padding: 0px; margin: -1px 0px 0px; width: 24px; height: 24px; cursor: pointer; }
.c_create_message_images_pending__remove_button .icon { color: rgb(143, 143, 143); font-size: 24px; }
.c_create_message_images_pending__remove_button:hover .icon { color: rgb(57, 57, 57); }
.c_create_message_images_pending + .c_create_message_images_pending { margin-top: 4px; }
.c_create_message_images_pending--error, .c_create_message_images_pending--error .c_create_message_images_pending__name span { color: rgb(242, 55, 71); }
@media (max-width: 767px) {
  .c_create_message_images_pending__bar { float: left; width: 100px; min-width: 100px; }
}
.notification_container { position: fixed; width: 100%; top: 50%; text-align: center; z-index: 1000; }
.notification_container .notification--message { display: inline-block; height: 48px; padding: 15px 22px; border-radius: 2px; background-color: rgba(32, 32, 32, 0.8); color: rgb(255, 255, 255); transform: translateY(-150%); }
.rating { font-size: 0px; margin: 0px -4px; }
.rating__item { width: 24px; height: 24px; position: relative; font-size: 24px; display: inline-block; vertical-align: top; margin: 0px 1px; }
.rating__item::after, .rating__item::before { speak: none; font-style: normal; font-weight: 400; font-variant: normal; text-transform: none; line-height: 1; content: ""; color: rgba(143, 143, 143, 0.16); font-family: icons !important; }
.rating__item::after { display: none; }
.rating__item--full::before { color: rgb(255, 175, 22); }
.rating__item--half::after { display: block; width: 11px; overflow: hidden; position: absolute; left: 0px; top: 0px; z-index: 1; color: rgb(255, 175, 22); }
.dynamic_placeholder { position: absolute; pointer-events: none; top: 0px; left: 0px; right: 50px; height: 100%; overflow: hidden; }
.dynamic_placeholder__text { position: absolute; left: 30px; top: 0px; right: 0px; line-height: 32px; font-size: 14px; color: rgb(143, 143, 143); overflow: hidden; white-space: nowrap; }
@media (min-width: 992px) {
  .dynamic_placeholder { right: 125px; }
}
.web_view_banner { position: fixed; z-index: 2000; top: 0px; left: 0px; width: 100%; height: 100%; background-color: rgb(250, 250, 250); padding: 22px 0px 0px; text-align: center; display: none; overflow: hidden; }
.web_view_banner--static { display: block; z-index: 103; }
.web_view_banner--shift { transition: transform 0.25s ease-in-out 0s; }
.web_view_banner--close { transition: transform 0.45s ease-in-out 0s; }
.web_view_banner__inner { width: 300px; max-width: 100%; margin-left: auto; margin-right: auto; position: relative; min-height: 100%; padding: 0px 15px; }
.web_view_banner__buttons { position: absolute; bottom: 0px; left: 0px; right: 0px; width: 230px; padding-bottom: 4vh; margin-left: auto; margin-right: auto; background: rgb(250, 250, 250); box-shadow: rgb(250, 250, 250) 0px 0px 30px 20px; }
.web_view_banner__buttons .button { font-size: 16px; }
.web_view_banner__buttons .button--green, .web_view_banner__buttons .button--primary { width: 100%; padding-top: 12px; padding-bottom: 13px; font-weight: 600; }
.web_view_banner__buttons .button--link { margin: 14px 0px 0px; padding: 10px; text-decoration: underline; color: rgb(3, 154, 211); font-size: 16px; }
.web_view_banner__logo { margin-bottom: 14px; }
.web_view_banner__title { font-size: 30px; font-weight: 400; line-height: 1.47; color: rgb(74, 74, 74); text-align: center; position: absolute; top: 40%; left: 15px; right: 15px; padding: 0px 20px; }
.web_view_banner__img { position: absolute; top: 0px; left: 0px; width: 100%; height: 35%; max-height: 225px; }
.web_view_banner__img img { width: 100%; position: absolute; right: 0px; bottom: 0px; }
.web_view_banner .visible-hor { display: none; }
.web_view_banner .logo { margin-left: auto; margin-right: auto; }
@media (max-width: 767px) {
  .body--is_mobile .web_view_banner { display: block; }
}
.web_view_banner.hidden { display: none; }
@media (max-height: 480px) {
  .web_view_banner__title { font-size: 24px; }
  .web_view_banner__buttons { padding-bottom: 10px; }
}
@media (max-height: 400px) {
  .web_view_banner__inner { width: 480px; }
  .web_view_banner__img { max-height: 86px; height: 25%; }
  .web_view_banner .visible-hor { display: block; }
  .web_view_banner .visible-ver { display: none; }
  .web_view_banner__title { top: 50%; font-size: 30px; margin-top: -70px; }
}
@media (max-height: 320px) {
  .web_view_banner__title { font-size: 24px; margin-top: -50px; padding-left: 0px; padding-right: 0px; }
  .web_view_banner__img { height: 68px; }
  .web_view_banner__buttons .button--link { margin-top: 5px; }
}
.status_badge__icon { width: 24px; height: 24px; background-position: 50% center; background-repeat: no-repeat; display: inline-block; vertical-align: middle; margin-right: 10px; background-size: 24px; }
.status_badge__icon--deal { background-image: url("/build/images/secure.62a90a.svg"); }
.status_badge__icon--delivery { width: 30px; height: 30px; background-image: url("https://i.imgur.com/ac83mcP.png"); }
.status_badge__icon--verified { background-image: url("/build/images/verified.da082e.svg"); }
.status_badge__icon--sale { background-image: url("/build/images/sale.667b8a.svg"); }
.status_badge__icon--promoted { background-color: rgb(255, 114, 34); width: 32px; height: 32px; border-radius: 100px; padding: 3px; text-align: center; background-size: 19px; }
.status_badge__icon--promoted-fast-sell { background-image: url("/build/images/fastsell-white.8aa863.svg"); }
.status_badge__icon--promoted-fast-sell-lite { background: url("/build/images/fastsell-lite-old.0ee741.svg") -4px center rgb(152, 90, 255); }
.status_badge__icon--skilled { background-image: url("/build/images/skilled.144915.svg"); background-size: 32px; }
.status_badge__title { color: rgb(57, 57, 57); font-size: 14px; line-height: 16px; }
.status_badge--disabled .status_badge__icon { opacity: 0.6; filter: grayscale(100%); }
.status_badge_list { margin-top: 24px; margin-bottom: 12px; }
.status_badge_list > * + * { margin-top: 10px; }
@media (max-width: 991px) {
  .status_badge_list { margin: 0px; }
}
.payments_products_container { position: relative; height: 296px; margin: 0px -15px; overflow: hidden; }
.payments_products_container::after, .payments_products_container::before { z-index: 3; position: absolute; top: 0px; bottom: 0px; width: 30%; content: ""; }
.payments_products_container::before { left: 0px; background-image: linear-gradient(90deg, rgb(255, 255, 255) 0px, rgba(255, 255, 255, 0)); background-repeat: repeat-x; }
.payments_products_container::after { right: 0px; background-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0px, rgb(255, 255, 255)); background-repeat: repeat-x; }
@media (max-width: 767px) {
  .payments_products_container { margin-left: -10px; margin-right: -10px; }
}
.payments_products { position: absolute; padding: 0px; left: 50%; top: 0px; width: auto; margin: 0px; white-space: nowrap; transform: translate(-50%); }
.payments_products .product_item { width: 20%; display: inline-block; vertical-align: top; float: none; min-width: 218px; }
.payments_container { margin-bottom: 40px; min-height: 200px; }
@media (min-width: 992px) {
  .payments_container { min-height: 30vh; position: relative; }
}
.payments_status { text-align: center; padding: 40px 0px; }
.payments_status__title { font-size: 30px; line-height: 0.8; margin-bottom: 20px; }
.payments_status__title--success { color: rgb(89, 168, 64); }
.payments_status__description { font-size: 14px; line-height: 1.43; text-align: center; color: rgb(143, 143, 143); margin-bottom: 35px; }
.view_switcher { margin: 0px -9px 0px 16px; }
.view_switcher .button { padding: 7px; }
.view_switcher .button:hover { color: rgb(103, 103, 103); }
.view_switcher .button .icon { color: inherit; }
.view_switcher .button.active, .view_switcher .button.active:hover { color: rgb(3, 154, 211); }
.filter .view_switcher { margin: -12px -10px; }
.photo-swipe-gallery-container { display: none; }
.photo-swipe-gallery-container--active { display: block; }
.pswp { display: none; position: absolute; width: 100%; height: 100%; left: 0px; top: 0px; overflow: hidden; touch-action: none; z-index: 1500; text-size-adjust: 100%; backface-visibility: hidden; outline: none; }
.pswp * { box-sizing: border-box; }
.pswp img { max-width: none; }
.pswp--animate_opacity { opacity: 0.001; will-change: opacity; transition: opacity 0.2s cubic-bezier(0.4, 0, 0.22, 1) 0s; }
.pswp--open { display: block; }
.pswp--zoom-allowed .pswp__img { cursor: zoom-in; }
.pswp--zoomed-in .pswp__img { cursor: grab; }
.pswp--dragging .pswp__img { cursor: grabbing; }
.pswp__bg { background: rgba(0, 0, 0, 0.7); opacity: 0; backface-visibility: hidden; }
.pswp__bg, .pswp__scroll-wrap { position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; }
.pswp__scroll-wrap { overflow: hidden; }
.pswp__container, .pswp__zoom-wrap { touch-action: none; position: absolute; left: 0px; right: 0px; top: 0px; bottom: 0px; }
.pswp__container, .pswp__img { user-select: none; -webkit-tap-highlight-color: transparent; }
.pswp__zoom-wrap { position: absolute; width: 100%; transform-origin: left top; transition: transform 0.2s cubic-bezier(0.4, 0, 0.22, 1) 0s; }
.pswp__bg { will-change: opacity; transition: opacity 0.2s cubic-bezier(0.4, 0, 0.22, 1) 0s; }
.pswp--animated-in .pswp__bg, .pswp--animated-in .pswp__zoom-wrap { transition: none 0s ease 0s; }
.pswp__container, .pswp__zoom-wrap { backface-visibility: hidden; }
.pswp__item { right: 0px; bottom: 0px; overflow: hidden; }
.pswp__img, .pswp__item { position: absolute; left: 0px; top: 0px; }
.pswp__img { border-radius: 2px; width: auto; height: auto; }
.pswp__img--placeholder { backface-visibility: hidden; }
.pswp--ie .pswp__img { left: 0px; top: 0px; width: 100% !important; height: auto !important; }
.pswp__error-msg { position: absolute; left: 0px; top: 50%; width: 100%; text-align: center; font-size: 14px; line-height: 16px; margin-top: -8px; color: rgb(204, 204, 204); }
.pswp__error-msg a { color: rgb(204, 204, 204); text-decoration: underline; }
.pswp__button { width: 30px; height: 30px; position: relative; background: none; cursor: pointer; overflow: visible; -webkit-appearance: none; display: block; border: 0px; padding: 0px; margin: 0px; float: right; box-shadow: none; }
.pswp__button::before { transition: color 0.2s ease 0s, background 0.2s ease 0s; }
.pswp__ui--over-close .pswp__button--close { opacity: 1; }
.pswp__button, .pswp__button--arrow--left::before, .pswp__button--arrow--right::before { color: rgb(255, 255, 255); width: 30px; height: 30px; font-family: icons !important; }
.pswp__button--close { top: 0px; right: 0px; padding: 18px; width: 62px; height: 64px; }
.pswp__button--close:focus { outline: none; }
.pswp__button--close::before { content: ""; font-size: 24px; line-height: 32px; }
.pswp__button--fs { display: none; }
.pswp--supports-fs .pswp__button--fs { display: block; }
.pswp--fs .pswp__button--fs { background-position: -44px 0px; }
.pswp__button--zoom { display: none; background-position: -88px 0px; }
.pswp--zoom-allowed .pswp__button--zoom { display: block; }
.pswp--zoomed-in .pswp__button--zoom { background-position: -132px 0px; }
.pswp--touch .pswp__button--arrow--left, .pswp--touch .pswp__button--arrow--right { visibility: hidden; }
.pswp__button--arrow--left, .pswp__button--arrow--right { background: none; top: 72px; bottom: 0px; width: 20%; height: auto; position: absolute; }
.pswp__button--arrow--left:hover::before, .pswp__button--arrow--right:hover::before { color: rgb(255, 255, 255); background-color: rgba(0, 0, 0, 0.3); }
.pswp__button--arrow--left::before, .pswp__button--arrow--right::before { content: ""; background-color: transparent; height: 80px; width: 60px; margin-top: -80px; position: absolute; font-size: 40px; line-height: 80px; top: 50%; color: rgba(255, 255, 255, 0.6); border-radius: 2px; }
.pswp__button--arrow--left { left: 0px; }
.pswp__button--arrow--left::before { margin-left: -30px; left: 50%; content: ""; }
.pswp__button--arrow--right { right: 0px; }
.pswp__button--arrow--right::before { margin-right: -30px; right: 50%; content: ""; }
.pswp__counter, .pswp__share-modal { user-select: none; }
.pswp__share-modal { display: block; background: rgba(0, 0, 0, 0.5); width: 100%; height: 100%; top: 0px; left: 0px; padding: 10px; position: absolute; z-index: 1600; opacity: 0; transition: opacity 0.25s ease-out 0s; backface-visibility: hidden; will-change: opacity; }
.pswp__share-modal--hidden { display: none; }
.pswp__share-tooltip { z-index: 1620; position: absolute; background: rgb(255, 255, 255); top: 56px; border-radius: 2px; display: block; width: auto; right: 44px; box-shadow: rgba(0, 0, 0, 0.25) 0px 2px 5px; transform: translateY(6px); transition: transform 0.25s ease 0s; backface-visibility: hidden; will-change: transform; }
.pswp__share-tooltip a { display: block; padding: 8px 12px; font-size: 14px; line-height: 18px; }
.pswp__share-tooltip a, .pswp__share-tooltip a:hover { color: rgb(0, 0, 0); text-decoration: none; }
.pswp__share-tooltip a:first-child { border-radius: 2px 2px 0px 0px; }
.pswp__share-tooltip a:last-child { border-radius: 0px 0px 2px 2px; }
.pswp__share-modal--fade-in { opacity: 1; }
.pswp__share-modal--fade-in .pswp__share-tooltip { transform: translateY(0px); }
.pswp--touch .pswp__share-tooltip a { padding: 16px 12px; }
a.pswp__share--facebook::before { content: ""; display: block; width: 0px; height: 0px; position: absolute; top: -12px; right: 15px; border-width: 6px; border-style: solid; border-color: transparent transparent rgb(255, 255, 255); border-image: initial; pointer-events: none; }
a.pswp__share--facebook:hover { background: rgb(62, 92, 154); color: rgb(255, 255, 255); }
a.pswp__share--facebook:hover::before { border-bottom-color: rgb(62, 92, 154); }
a.pswp__share--twitter:hover { background: rgb(85, 172, 238); color: rgb(255, 255, 255); }
a.pswp__share--pinterest:hover { background: rgb(204, 204, 204); color: rgb(206, 39, 45); }
a.pswp__share--download:hover { background: rgb(221, 221, 221); }
.pswp__counter { position: absolute; left: 0px; top: 0px; height: 44px; font-size: 13px; line-height: 44px; color: rgb(255, 255, 255); opacity: 0.75; padding: 0px 10px; }
.pswp__caption { position: absolute; left: 0px; bottom: 0px; width: 100%; min-height: 44px; }
.pswp__caption small { font-size: 11px; color: rgb(187, 187, 187); }
.pswp__caption__center { text-align: left; max-width: 420px; margin: 0px auto; font-size: 13px; padding: 10px; line-height: 20px; color: rgb(204, 204, 204); }
.pswp__caption--empty { display: none; }
.pswp__caption--fake { visibility: hidden; }
.pswp__preloader { display: none; position: fixed; width: 100%; height: 100%; }
.pswp--css_animation .pswp__preloader--active { display: flex; align-items: center; justify-content: space-between; }
@keyframes clockwise { 
  0% { transform: rotate(0deg); }
  100% { transform: rotate(1turn); }
}
@keyframes donut-rotate { 
  0% { transform: rotate(0deg); }
  50% { transform: rotate(-140deg); }
  100% { transform: rotate(0deg); }
}
.pswp__ui { -webkit-font-smoothing: auto; visibility: visible; opacity: 1; z-index: 1550; }
.pswp__top-bar { position: absolute; left: 0px; top: 0px; height: 44px; width: 100%; }
.pswp--has_mouse .pswp__button--arrow--left, .pswp--has_mouse .pswp__button--arrow--right, .pswp__caption, .pswp__top-bar { backface-visibility: hidden; will-change: opacity; transition: opacity 0.2s cubic-bezier(0.4, 0, 0.22, 1) 0s; }
.pswp--has_mouse .pswp__button--arrow--left, .pswp--has_mouse .pswp__button--arrow--right { visibility: visible; }
.pswp__ui--fit .pswp__caption, .pswp__ui--fit .pswp__top-bar { background-color: transparent; }
.pswp__ui--one-slide .pswp__button--arrow--left, .pswp__ui--one-slide .pswp__button--arrow--right, .pswp__ui--one-slide .pswp__counter { display: none; }
.pswp__element--disabled { display: none !important; }
.pswp--minimal--dark .pswp__top-bar { background: none; }
@media (max-width: 1070px) {
  .pswp__button--arrow--left { left: 0px; }
  .pswp__button--arrow--left::before { margin-left: 0px; left: 15px; }
  .pswp__button--arrow--right { right: 0px; }
  .pswp__button--arrow--right::before { margin-right: 0px; right: 15px; }
}
@media (max-width: 991px) {
  .pswp__button--arrow--left, .pswp__button--arrow--right { display: none; }
}
@media (max-width: 767px) {
  .body--product_create .header { display: none; }
  .body--product_create .bundle { padding-top: 56px !important; }
  .body--product_create .product_create_form form { min-height: calc(100vh - 56px); }
  .body--product_create .product_create_form .location-select-form { position: fixed; top: 56px; bottom: 0px; left: 0px; right: 0px; }
  .body--product_create .product_create_form .location-select-form > div { height: 100%; }
  .body--product_create .product_create_categories ul { border-bottom: none; overflow: initial; height: auto; }
}
@media (max-width: 991px) {
  .body--product_create, .body--product_create .bundle { padding-bottom: 0px; }
  .body--product_create .main, .body--product_create .row { margin: 0px; padding: 0px; }
  .body--product_create .header { padding-left: 15px; padding-right: 15px; }
  .body--product_create .container { padding-right: 0px; padding-left: 0px; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .body--product_create .product_create_form { padding-top: 33px; }
  .body--product_create .product_create_title { padding-top: 33px; padding-bottom: 22px; margin-bottom: 0px; background-color: rgb(249, 249, 249); }
}
.product_create_wrapper { position: relative; }
.product_create_wrapper::after, .product_create_wrapper::before { content: " "; display: table; }
.product_create_wrapper::after { clear: both; }
@media (max-width: 991px) {
  .product_create_wrapper { height: 100%; }
}
.product_create_title { font-size: 30px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; line-height: 1.33; margin-bottom: 20px; }
@media (max-width: 991px) {
  .product_create_title { text-align: center; margin-bottom: 22px; font-size: 20px; letter-spacing: 0px; }
}
@media (max-width: 991px) {
  .product_create .header { z-index: -100; }
}
@media (max-width: 991px) {
  .product_create_form { padding-left: 15px; padding-right: 15px; padding-bottom: 110px; }
}
@media (max-width: 767px) {
  .product_create_form { height: 100%; padding-left: 0px; padding-right: 0px; padding-bottom: 0px; }
}
@media (max-width: 991px) {
  .product_create_categories { background-color: rgb(255, 255, 255); width: 100%; height: 100%; }
}
@media (max-width: 991px) {
  .product_create_preview { height: 100%; width: 100%; padding: 0px 15px; }
}
@media (max-width: 767px) {
  .product_create_preview { padding: 0px; }
}
.product_create_container { width: 73.4%; padding-right: 50px; }
.product_create_container--full_width { width: 100%; padding-right: 0px; }
@media (max-width: 991px) {
  .product_create_container { top: 0px; left: 0px; width: 100%; height: 100%; padding-left: 0px; padding-right: 0px; }
}
@media (max-width: 767px) {
  .product_create_container { width: 100%; height: 100%; }
}
.product_create_progress { width: 26.6%; position: absolute; top: 0px; right: 0px; height: 100%; }
@media (max-width: 991px) {
  .product_create_progress { display: none; }
}
.web_push_confirmation { position: absolute; top: 10px; left: 10px; width: 340px; z-index: 2000; background-color: rgba(57, 57, 57, 0.8); border-radius: 2px; padding: 12px 10px 16px 65px; cursor: pointer; transition: background 0.2s ease 0s; }
.web_push_confirmation__body, .web_push_confirmation__header { color: rgb(249, 249, 249); }
.web_push_confirmation__body { margin-bottom: 13px; line-height: 16px; font-size: 13px; }
.web_push_confirmation__header { text-decoration: none; font-weight: 600; margin-bottom: 4px; }
.web_push_confirmation:hover { background-color: rgb(57, 57, 57); }
.web_push_confirmation:hover .web_push_confirmation__header { text-decoration: underline; }
.web_push_confirmation .alarm_icon_wrapper { position: absolute; top: 13px; left: 14px; height: 40px; width: 40px; color: rgb(255, 255, 255); font-size: 36px; }
.web_push_confirmation .close_icon_wrapper { position: absolute; top: 8px; right: 10px; color: rgb(255, 255, 255); }
.add_to_home { left: 10px; right: 10px; bottom: 0px; position: fixed; z-index: 1001; color: rgb(255, 255, 255); }
.add_to_home__title { font-weight: 600; font-size: 16px; margin-bottom: 5px; }
.add_to_home p { color: rgb(255, 255, 255); margin-bottom: 0px; line-height: 1.43; font-size: 14px; }
.add_to_home .btn--close { right: -5px; top: -4px; left: auto; position: absolute; color: rgb(255, 255, 255); }
.add_to_home .btn--close .icon { color: rgb(255, 255, 255); }
.add_to_home__popup { margin-left: auto; margin-right: auto; width: 400px; max-width: 100%; border-radius: 2px; position: relative; background-color: rgb(3, 154, 211); margin-bottom: 9px; text-align: center; padding: 25px 40px; }
.add_to_home__popup img { vertical-align: middle; display: inline-block; position: relative; top: -1px; }
.add_to_home__popup::after { top: 100%; left: 50%; border-style: solid; border-image: initial; content: " "; height: 0px; width: 0px; position: absolute; pointer-events: none; border-color: rgb(3, 154, 211) rgba(136, 183, 213, 0) rgba(136, 183, 213, 0); border-width: 10px; margin-left: -10px; margin-top: -1px; }
.banners_rotator_container { height: 216px; }
@media (max-width: 575px) {
  .banners_rotator_container { height: 144px; }
}
.banners_rotator_container--mobile { display: none; margin-left: -15px; margin-right: -15px; margin-bottom: 8px; }
.banners_rotator_container--mobile + section { margin-top: 0px; }
@media (max-width: 991px) {
  .banners_rotator_container--mobile { display: block; }
}
@media (max-width: 767px) {
  .banners_rotator_container--mobile { margin-left: -10px; margin-right: -10px; margin-bottom: 0px; }
}
@media (max-width: 575px) {
  .banners_rotator_container--mobile { margin-bottom: 24px; }
}
.banners_rotator_container--desktop { display: block; margin-bottom: 16px; }
@media (max-width: 991px) {
  .banners_rotator_container--desktop { display: none; }
}
.banners_rotator_container div { height: 100%; }
.banners_rotator_container a * { height: auto; }
.app_install { left: 0px; right: 0px; width: 100%; max-height: 148px; bottom: 0px; position: fixed; z-index: 1002; background-color: rgb(249, 249, 249); padding: 0px 10px 10px; pointer-events: all; transition: transform 0.4s ease-in-out 0s; transform: translateY(100%); }
.app_install__text { padding: 20px 0px; font-size: 16px; line-height: 1.5; max-width: 300px; }
.app_install__logo { float: left; margin-right: 12px; font-size: 0px; line-height: 0; background-color: rgb(255, 255, 255); border-radius: 100px; width: 48px; height: 48px; padding: 8px; }
.app_install__buttons { margin: 8px -5px 0px; overflow: hidden; }
.app_install__buttons .button_container { width: 50%; float: left; margin-bottom: 0px; padding: 0px 5px; }
.app_install__buttons .button_container + .button_container { margin-top: 0px; }
.app_install .button { border-radius: 4px; font-size: 16px; text-transform: none; letter-spacing: 0px; font-weight: 400; padding: 9px 16px 12px; }
.app_install .button--default { background-color: rgb(255, 255, 255); border: none; box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; }
.app_install .button--green, .app_install .button--primary { text-transform: none; }
.app_install--inited { transform: translateY(0px); }
.subs_info { margin-top: 15px; }
@media (max-width: 991px) {
  .subs_info > .followers, .subs_info > .following { display: inline-block; margin-left: 10px; margin-right: 10px; }
}
.subs_info > .followers > span, .subs_info > .following > span { cursor: pointer; color: rgb(143, 143, 143); font-size: 14px; line-height: 2.14; font-stretch: normal; }
@media (min-width: 992px) {
  .subs_info > .followers > span:hover, .subs_info > .following > span:hover { color: rgb(3, 154, 211); }
}
.subs_button button { width: 14em; height: 2.8em; margin-top: 20px; }
.subs_button button span { padding: 8px 16px !important; }
.subs_button > .snackbar { position: fixed; z-index: 999; }
.limits-container--mobile { position: fixed; top: 0px; left: 0px; bottom: 0px; right: 0px; z-index: 150; background: rgb(249, 249, 249); }
.limits-container--hidden { display: none; }
.limits-loader-container--mobile { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); }
.category_rotator { margin-bottom: 40px; }
@media (max-width: 991px) {
  .category_rotator { margin: 32px 0px; }
  .category_rotator .h1_like { font-size: 24px; }
}
@media (max-width: 767px) {
  .category_rotator { margin: 24px 0px; }
  .category_rotator .h1_like { font-size: 20px; }
}
.category_rotator--mobile { display: none; }
@media (max-width: 991px) {
  .category_rotator--mobile { display: block; }
}
.category_rotator--desktop { display: block; }
@media (max-width: 991px) {
  .category_rotator--desktop { display: none; }
}
.category_rotator .category_rotator_container_react { margin: 16px 0px; height: 144px; }
.p_footer { padding: 35px 0px; margin-top: -90px; position: relative; z-index: 1; }
.p_footer__copy { float: left; font-size: 14px; line-height: 1.43; color: rgba(57, 57, 57, 0.3); }
.p_footer--promo_developers .p_footer__copy, .p_footer--promo_photoshoot .p_footer__copy, .p_footer--promo_realty .p_footer__copy { float: none; text-align: center; }
.p_footer--promo_promotion .p_footer_nav__item a, .p_footer--promo_turbo .p_footer_nav__item a { color: rgb(57, 57, 57); }
.p_footer--promo_promotion .p_footer__copy, .p_footer--promo_turbo .p_footer__copy { color: rgb(143, 143, 143); }
@media (max-width: 991px) {
  .p_footer { margin-top: 0px; text-align: center; }
  .p_footer__copy { float: none; width: 100%; }
  .p_footer .p_footer__copy { margin-top: 30px; }
}
@media (max-width: 767px) {
  .p_footer { padding: 25px 0px; }
}
.p_footer_nav { float: right; padding: 0px; margin: 0px; list-style: none; }
.p_footer_nav__item { margin-right: 5px; display: inline-block; vertical-align: top; }
.p_footer_nav__item a { padding: 5px 15px; color: rgba(57, 57, 57, 0.6); text-decoration: none; }
.p_footer_nav__item a:hover { color: rgb(57, 57, 57); }
.p_footer_nav__item:last-child { margin-right: 0px; }
@media (max-width: 991px) {
  .p_footer_nav { float: none; width: 100%; }
  .p_footer_nav__item { display: block; text-align: center; margin-bottom: 15px; }
}
.p_header { position: absolute; left: 0px; width: 100%; z-index: 100; padding: 16px 0px; background-color: rgb(250, 250, 250); border-bottom: 1px solid transparent; transition: border-color 0.2s ease 0s, background 0.2s ease 0s, top 0.1s ease 0s; }
.p_header .header_bar__login .badge { top: -3px; right: -3px; }
.p_header--promo_developers, .p_header--promo_photoshoot, .p_header--promo_promotion, .p_header--promo_realty, .p_header--promo_safebuyer, .p_header--promo_turbo { background-color: transparent; }
.p_header--promo_promotion:not(.p_header--fixed) .logo, .p_header--promo_safebuyer:not(.p_header--fixed) .logo, .p_header--promo_turbo:not(.p_header--fixed) .logo { position: relative; background-image: url("/build/images/logo-white.1af9b6.svg"); }
.p_header--promo_promotion:not(.p_header--fixed) .p_menu__item a, .p_header--promo_promotion:not(.p_header--fixed) .p_menu__item a:hover, .p_header--promo_safebuyer:not(.p_header--fixed) .p_menu__item a, .p_header--promo_safebuyer:not(.p_header--fixed) .p_menu__item a:hover, .p_header--promo_turbo:not(.p_header--fixed) .p_menu__item a, .p_header--promo_turbo:not(.p_header--fixed) .p_menu__item a:hover { color: rgb(255, 255, 255); }
.p_header--promo_safebuyer:not(.p_header--fixed) .button--bordered { color: rgb(255, 255, 255); border-color: rgb(255, 255, 255); }
.p_header--promo_safebuyer:not(.p_header--fixed) .button--bordered:hover { background: rgba(255, 255, 255, 0.1); }
@media (max-width: 991px) {
  .p_header--promo_safebuyer:not(.p_header--fixed) .logo { background-image: url("/build/images/logo.315c1c.svg"); }
}
.p_header--promo_promotion, .p_header--promo_turbo { padding: 15px 0px; }
.p_header--promo_promotion .container, .p_header--promo_turbo .container { width: 1210px; }
.p_header--promo_promotion .button .icon--account_circle, .p_header--promo_turbo .button .icon--account_circle { color: rgb(255, 255, 255); }
.p_header--fixed { top: 0px; position: fixed; background-color: rgb(255, 255, 255); border-bottom-color: rgb(235, 235, 235); }
@media (max-width: 991px) {
  .p_header { top: 0px; padding: 8px 0px 4px; }
  .p_header.p_header--promo_promotion.p_header--fixed .icon, .p_header.p_header--promo_turbo.p_header--fixed .icon { color: rgb(143, 143, 143); }
  .p_header.p_header--promo_promotion .badge.badge--red, .p_header.p_header--promo_turbo .badge.badge--red { right: 2px; top: 0px; }
}
.p_header_menu { float: right; margin: 4px 0px; height: 24px; display: flex; align-items: center; }
@media (max-width: 991px) {
  .p_header_menu { display: none; }
}
.p_header_actions { float: right; text-align: right; position: relative; z-index: 2; }
.p_header_actions .button_account { padding: 5px; margin-right: -5px; margin-top: -1px; }
.p_header_actions .dropdown_menu { right: -4px; }
.p_header_actions .dropdown_menu--show { margin-top: 15px; }
@media (max-width: 991px) {
  .p_header_actions .dropdown_menu--show { margin-top: 0px; }
}
.p_menu { margin: 0px; padding: 0px; list-style: none; }
.p_menu__item { display: inline-block; vertical-align: top; font-size: 14px; }
.p_menu__item--active a { color: rgb(57, 57, 57); }
.p_menu__item a { padding: 8px 12px; text-decoration: none; color: rgba(57, 57, 57, 0.6); }
.p_menu__item a:hover { color: rgb(57, 57, 57); }
.p_header_logo { float: left; padding: 0px; white-space: nowrap; }
.p_header_logo .logo { transform: translateY(-7px); }
.p_header_logo--partner { margin-top: 6px; margin-left: 15px; display: inline-block; vertical-align: top; float: none; }
@media (max-width: 991px) {
  .p_header_logo { float: none; }
  .p_header_logo .logo { transform: translateY(0px); }
}
.p_header_button { margin-left: 30px; }
.p_form_app { max-width: 100%; margin: 0px 0px 30px; position: relative; padding-bottom: 30px; }
.p_form_app__inner { width: 460px; margin-left: auto; margin-right: auto; }
.p_form_app .row { margin-left: -10px; margin-right: -10px; }
.p_form_app .row > div { padding-left: 10px; padding-right: 10px; }
.p_form_app .button, .p_form_app .form-control, .p_form_app .form_control { width: 100%; height: 48px; font-size: 16px; }
.p_form_app .form_error { text-align: left; max-width: 460px; margin: 10px auto 0px; position: absolute; bottom: 0px; left: 0px; right: 0px; }
.p_form_app .body--is_mobile { display: none; }
@media (max-width: 991px) {
  .p_form_app { display: none; }
}
.p_features { font-size: 0px; text-align: center; }
.p_feature { display: inline-block; vertical-align: top; text-align: center; padding: 0px 35px; margin: 40px 0px; width: 320px; max-width: 100%; }
.p_feature__icon { margin-bottom: 35px; height: 90px; }
.p_feature__title { font-size: 24px; line-height: 1.25; text-align: center; color: rgb(42, 42, 42); margin-bottom: 16px; }
.p_feature__text { color: rgba(57, 57, 57, 0.6); font-size: 14px; }
@media (max-width: 767px) {
  .p_feature { margin-top: 0px; margin-bottom: 50px; padding-left: 30px; padding-right: 30px; }
  .p_feature__icon { margin-bottom: 20px; }
  .p_feature:last-child { margin-bottom: 0px; }
}
.p_balloon { z-index: 2; border-radius: 8px; padding: 15px 10px; line-height: 20px; position: relative; font-size: 14px; color: rgb(143, 143, 143); background-color: rgb(255, 255, 255); box-shadow: rgba(16, 39, 56, 0.13) 0px 8px 21px 0px; }
@media (min-width: 992px) {
  .p_balloon { padding: 15px 20px; line-height: 24px; font-size: 16px; }
}
.p_feature_row { width: 250px; max-width: 100%; position: relative; margin: 0px 0px 25px; }
.p_feature_row .bp_label { position: absolute; margin-top: -32px; right: -57px; top: 50%; z-index: 1; }
.p_feature_row:nth-child(2n) { margin-left: 42px; }
.p_feature_row:nth-child(2n) .bp_label { right: auto; left: -57px; }
.p_feature_row:last-child { margin-bottom: 0px; }
@media (min-width: 992px) {
  .p_feature_row { width: 380px; margin: 25px 0px 50px; }
  .p_feature_row:nth-child(2n) { margin-left: 200px; }
}
.bp_label { width: 64px; height: 64px; font-size: 28px; font-weight: 500; font-style: normal; font-stretch: normal; line-height: 64px; letter-spacing: -0.3px; text-align: center; color: rgb(255, 255, 255); border-radius: 100px; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; background-image: linear-gradient(225deg, rgb(136, 195, 71), rgb(88, 168, 64)); }
.p_slider_bullets { font-size: 0px; position: relative; z-index: 1; }
.p_bullet { font-size: 14px; font-weight: 700; text-align: center; color: rgb(255, 255, 255); background-color: rgb(205, 205, 205); border-radius: 100px; margin-right: 40px; display: inline-block; vertical-align: top; padding: 2px 3px 3px; width: 24px; height: 24px; cursor: pointer; transition: background-color 0.2s ease 0s; }
.p_bullet--active, .p_bullet:hover { background-color: rgb(255, 68, 68); }
.p_bullet:last-child { margin-right: 0px !important; }
.body--is_mobile .p_bullet:hover { background-color: rgb(205, 205, 205); }
.body--is_mobile .p_bullet--active { background-color: rgb(255, 68, 68) !important; }
@media (max-width: 991px) {
  .p_bullet { margin-right: 25px; }
}
.p_slide { display: inline-block; vertical-align: top; width: 100%; white-space: normal; position: absolute; left: -100%; background-color: rgb(255, 255, 255); opacity: 0; transition: left 0.5s ease 0s, opacity 0.5s ease 0.1s; }
.p_slide .p_section_title { text-align: left; margin-bottom: 20px; max-width: 395px; }
.p_slide .p_partner { position: static; margin-top: 60px; }
.p_slide__text { font-size: 16px; line-height: 1.5; color: rgba(57, 57, 57, 0.6); max-width: 330px; }
.p_slide--active { left: 0px; opacity: 1; }
.p_slide--active ~ .p_slide { left: 100%; }
@media (max-width: 767px) {
  .p_slide { position: relative; transition: left 0s ease 0s, opacity 0.2s ease 0s; }
  .p_slide--active { margin-left: -10px; }
  .p_slide--1.p_slide--active { left: 0px; margin-left: 0px; }
  .p_slide--2.p_slide--active { left: -100%; }
  .p_slide--3.p_slide--active { left: -200%; }
  .p_slide--4.p_slide--active { left: -300%; }
  .p_slide--5.p_slide--active { left: -400%; }
  .p_slide__text { margin-left: auto; margin-right: auto; }
  .p_slide .p_section_title { text-align: center; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .p_slide .p_partner__logo { margin-left: 0px; margin-right: 7px; text-align: left; display: inline-block; }
}
.p_slider_wrapper { margin-top: 154px; padding-right: 60px; margin-bottom: -60px; position: relative; z-index: 1; }
@media (max-width: 991px) {
  .p_slider_wrapper { margin-top: 95px; }
}
@media (max-width: 767px) {
  .p_slider_wrapper { margin: 50px 0px 30px; padding-right: 0px; }
}
.p_slider_slides { white-space: nowrap; min-height: 337px; position: relative; overflow: hidden; }
@media (max-width: 767px) {
  .p_slider_slides { min-height: 0px; }
}
.p_slider_frame { position: relative; width: 100%; height: 523px; margin-left: auto; margin-right: auto; pointer-events: all; }
.p_slider_image { opacity: 0; visibility: hidden; position: absolute; left: 0px; right: 0px; top: 0px; margin: 0px auto; pointer-events: none; transition: opacity 0.2s ease 0s, visibility 0.2s ease 0s; }
.p_slider_image--active { opacity: 1; visibility: visible; }
.p_slider_button { width: 90px; height: 90px; position: absolute; text-align: center; right: 0px; top: 50%; padding: 0px; z-index: 2; margin-top: -45px; }
.p_slider_button.button--back .icon, .p_slider_button.button--close .icon, .p_slider_button.button--icon .icon { font-size: 90px; color: rgb(205, 205, 205); transition: color 0.2s ease 0s; }
.p_slider_button.button--back:hover .icon, .p_slider_button.button--close:hover .icon, .p_slider_button.button--icon:hover .icon { color: gray; }
.p_slider_button--reverse { transform: rotate(-180deg); }
@media (max-width: 991px) {
  .p_slider_button { display: none; }
}
.container_bp { background-color: rgb(249, 249, 249); }
.container_bp .container { width: 1210px; }
.container_bp .button--youla_js_default { font-size: 16px; width: 280px; max-width: 100%; }
.container_bp .p_section_title { font-size: 48px; font-weight: 500; line-height: 1.33; color: inherit; text-align: left; margin-bottom: 45px; }
.container_bp .p_section_title--small { font-size: 30px; margin-bottom: 30px; color: rgb(57, 57, 57); }
.container_bp .p_section { height: auto; }
@media (min-width: 992px) {
  .container_bp .button--youla_js_default { padding: 14px 16px 15px; }
}
@media (max-width: 991px) {
  .container_bp .container { width: 310px; }
  .container_bp .p_section_title { font-size: 30px; text-align: center; }
  .container_bp .p_section_title--small { font-size: 24px; margin-bottom: 15px; }
}
.p_section { padding: 40px 0px; position: relative; overflow: hidden; border-bottom: 1px solid rgb(235, 235, 235); height: 700px; top: 1px; }
.p_section a:not(.button) { color: rgb(3, 154, 211); text-decoration: none; }
.p_section .container { height: 100%; }
@media (max-width: 991px) {
  .p_section { height: auto; padding: 50px 0px !important; }
}
@media (max-width: 767px) {
  .p_section .container { width: 320px; height: auto; }
  .p_section .stores .app_link { width: 250px; height: 48px; }
  .p_section .stores .app_link--googleplay { background-size: 160px; }
  .p_section .stores .app_link + .app_link { margin-top: 30px; }
}
.p_partner { line-height: 1.43; color: rgba(57, 57, 57, 0.4); position: absolute; left: 15px; bottom: 0px; }
.p_partner__logo { display: inline-block; vertical-align: middle; margin-right: 7px; position: relative; top: 1px; }
@media (max-width: 991px) {
  .p_partner { margin-bottom: 30px; margin-top: 30px; position: static; }
  .p_partner__logo { display: block; text-align: center; margin-left: auto; margin-right: auto; }
}
.p_section_image { font-size: 0px; line-height: 0; user-select: none; }
.p_section_image img { pointer-events: none; }
.p_section_title { font-size: 36px; line-height: 1.33; color: rgb(57, 57, 57); margin-bottom: 40px; font-family: "Fira Sans", sans-serif; text-align: center; }
@media (max-width: 767px) {
  .p_section_title { font-size: 30px; line-height: 1.2; margin-top: 0px; }
}
.p_section_header { height: 335px; border-bottom: 0px; text-align: left; padding: 0px; margin-bottom: 48px; color: rgb(255, 255, 255); }
.p_section_header .container { height: 100%; position: relative; }
.p_section_header .p_section_title { position: absolute; left: 15px; bottom: 30px; margin-bottom: 0px; }
.p_section_header--promo_turbo { background-color: rgb(139, 77, 255); background-image: linear-gradient(90deg, rgb(139, 77, 255) 0px, rgb(191, 132, 255)); background-repeat: repeat-x; }
.p_section_header--promo_promotion { background-color: rgb(255, 114, 34); background-image: linear-gradient(90deg, rgb(255, 114, 34) 0px, rgb(255, 171, 71)); background-repeat: repeat-x; }
@media (max-width: 991px) {
  .p_section_header { height: 427px; margin-bottom: 0px; }
  .p_section_header .p_section_title { position: static; margin-top: 100px; }
}
.p_section_lead { padding: 95px 0px 50px; background-color: rgb(250, 250, 250); height: 100vh; min-height: 700px; }
.p_section_lead h1 { font-size: 48px; line-height: 1.25; color: rgb(57, 57, 57); margin-bottom: 25px; font-family: "Fira Sans", sans-serif; }
.p_section_lead p { font-size: 16px; line-height: 1.5; margin-bottom: 43px; color: rgba(57, 57, 57, 0.8); }
.p_section_lead .stores { margin: 0px -10px 98px; }
.p_section_lead .container { height: 100%; position: relative; z-index: 1; }
.p_section_lead .row > div { position: static; }
.p_section_lead .p_section_icon { margin-bottom: 25px; }
.p_section_lead .p_section_image { position: absolute; right: 0px; top: 140px; left: 40%; width: auto; }
.p_section_lead .p_section_image--promo_safebuyer, .p_section_lead .p_section_image--promo_safeseller { text-align: left; left: 52%; }
.p_section_lead .p_section_image--promo_safebuyer { z-index: 1; }
.p_section_lead .p_section_image--promo_safeseller { top: 81px; }
.p_section_lead .p_section_image--promo_photoshoot, .p_section_lead .p_section_image--promo_realty { left: 0px; top: 0px; bottom: 0px; right: 0px; width: 100%; height: 100%; z-index: auto; background-size: cover !important; }
.p_section_lead .p_section_image--promo_realty { background: url("/promo/realty/main.jpg") 100% center no-repeat; }
.p_section_lead .p_section_image--promo_photoshoot { background: url("/promo/photoshoot/main.jpg") 100% center no-repeat; }
.p_section_lead .p_section_image--promo_developers { left: auto; right: -10%; top: 40px; }
.p_section_lead .p_section_image--promo_promotion, .p_section_lead .p_section_image--promo_turbo { left: 50%; }
.p_section_lead--promo_realty .p_section_image { position: absolute !important; }
.p_section_lead--promo_realty p { margin-bottom: 100px; }
.p_section_lead--promo_developers { background-color: rgb(255, 255, 255); }
.p_section_lead--promo_promotion .button--green, .p_section_lead--promo_promotion .button--primary, .p_section_lead--promo_turbo .button--green, .p_section_lead--promo_turbo .button--primary { padding: 13px 75px 16px; font-size: 16px; }
.p_section_lead--promo_promotion .p_partner span, .p_section_lead--promo_turbo .p_partner span { display: inline-block; vertical-align: top; }
.p_section_lead--promo_promotion .mobile, .p_section_lead--promo_safebuyer .play-video, .p_section_lead--promo_safebuyer .video-background, .p_section_lead--promo_turbo .mobile { display: none; }
@media (max-width: 1178px) and (min-width: 992px) {
  .p_section_lead .col-md-6 { width: 605px; padding-left: 70px; padding-right: 0px; }
  .p_section_lead .p_partner { left: 70px; }
  .p_section_lead--promo_photoshoot { background-position: 85% 0px; }
}
@media (min-width: 992px) {
  .p_section_lead .p_section_image { bottom: -1px; }
  .p_section_lead .p_section_image img { height: 100%; width: auto; }
  .p_section_lead .p_section_image--promo_delivery { bottom: 130px; }
  .p_section_lead--promo_safebuyer { position: relative; }
  .p_section_lead--promo_safebuyer .video-background { position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px; display: block; background-image: url("/build/images/player-back.ff5f93.jpg"); background-repeat: no-repeat; background-position: 50% 50%; background-size: cover; }
  .p_section_lead--promo_safebuyer .video-background iframe { display: block; width: 100%; height: 100%; visibility: hidden; }
  .p_section_lead--promo_safebuyer .video-background::after { position: absolute; top: 0px; bottom: 0px; left: 0px; right: 0px; content: ""; background-image: url("/build/images/player-pattern.9f5284.svg"); background-repeat: repeat; background-position: 50% 50%; }
  .p_section_lead--promo_safebuyer .mobile-show { display: none; }
  .p_section_lead--promo_safebuyer .play-video { background-color: transparent; outline: none; border: 0px; padding: 0px; margin: 0px; position: absolute; bottom: 42%; left: 20%; display: block; width: 268px; height: 268px; cursor: pointer; opacity: 0.6; background-image: url("/build/images/play-arrow.9f8c92.svg"); background-repeat: no-repeat; background-position: 50% 50%; background-size: 100%; }
  .p_section_lead--promo_safebuyer .play-video:hover { opacity: 0.7; }
  .p_section_lead--promo_safebuyer .play-video:active { opacity: 0.6; }
  .p_section_lead--promo_safebuyer.show-video .video-background iframe { visibility: visible; }
  .p_section_lead--promo_safebuyer.show-video .video-background::after { background-image: none; }
  .p_section_lead--promo_safebuyer.show-video .container, .p_section_lead--promo_safebuyer.show-video .p_section_image { opacity: 0; visibility: hidden; }
  .p_section_lead--promo_safebuyer h1 { color: rgb(255, 255, 255); }
  .p_section_lead--promo_safebuyer p { color: rgb(255, 255, 255); opacity: 0.8; }
  .p_section_lead--promo_safebuyer .p_partner { color: rgb(255, 255, 255); opacity: 0.4; }
}
@media (max-width: 991px) {
  .p_section_lead { text-align: center; height: auto; min-height: 0px; }
  .p_section_lead .container { height: auto; }
  .p_section_lead h1 { text-align: center; max-width: 480px; margin-left: auto; margin-right: auto; }
  .p_section_lead .stores { margin-bottom: 45px; }
  .p_section_lead .p_section_image { position: static; width: 100%; }
  .p_section_lead .p_section_image img { max-width: 100%; height: auto; }
  .p_section_lead .p_section_image--promo_safebuyer, .p_section_lead .p_section_image--promo_safeseller { margin: 10px 0px -55px -22px; text-align: center; }
  .p_section_lead .p_section_image--promo_developers { margin-bottom: -35px; }
  .p_section_lead--promo_photoshoot .p_section_icon { margin-top: 40px; }
  .p_section_lead--promo_photoshoot h1 { margin-top: 0px !important; }
}
@media (max-width: 991px) {
  .p_section_lead h1 { margin-top: 70px; }
}
@media (max-width: 767px) {
  .p_section_lead { padding: 75px 0px 30px !important; }
  .p_section_lead h1 { margin-top: 0px; font-size: 30px; line-height: 1.2; }
  .p_section_lead p { margin-bottom: 33px; }
  .p_section_lead .p_section_image--promo_delivery img { width: 516px; height: auto; max-width: none; margin-left: -20px; }
  .p_section_lead--promo_photoshoot .p_section_icon { margin-top: 0px; }
}
.p_section_lead_bp { padding: 0px !important; }
.p_section_lead_bp .p_section_image { margin-top: -175px; margin-left: -105px; margin-bottom: -1px; position: relative; }
@media (max-width: 991px) {
  .p_section_lead_bp { text-align: center; padding-bottom: 63px !important; }
  .p_section_lead_bp .p_section_image { margin: -218px 0px 48px -15px; }
  .p_section_lead_bp .p_section_image img { margin-left: auto; margin-right: auto; }
  .p_section_lead_bp--promo_turbo .p_section_image { margin-left: -20px; }
  .p_section_lead_bp--promo_promotion .p_section_image { width: 300px; margin: -217px auto 48px; }
  .p_section_lead_bp--promo_promotion .p_section_image img { margin-left: 6px; }
}
@media (max-width: 991px) and (min-width: 767px) {
  .p_section_lead_bp--promo_promotion .p_section_image { margin-left: -10px; }
}
.bp_badge { margin-bottom: 24px; }
.bp_badge::after, .bp_badge::before { content: " "; display: table; }
.bp_badge::after { clear: both; }
.bp_badge__icon { margin-bottom: 24px; font-size: 0px; line-height: 0; }
.bp_badge__text { line-height: 1.43; font-size: 14px; color: rgb(143, 143, 143); }
@media (min-width: 992px) {
  .bp_badge { margin-bottom: 45px; }
  .bp_badge__icon { float: left; margin-right: 20px; margin-bottom: 0px; }
  .bp_badge__text { overflow: hidden; max-width: 360px; font-size: 16px; }
}
.bp_value { position: absolute; bottom: 100%; font-family: "Fira Sans", sans-serif; font-size: 112px; font-weight: 500; letter-spacing: -1.1px; left: 78px; line-height: 72px; color: rgba(255, 255, 255, 0.2); }
.p_section_features { padding: 65px 0px 40px; height: auto; }
.p_section_features .p_section_title { margin-bottom: 60px; }
.p_section_features .p_section_video { position: relative; padding-bottom: 56.25%; height: 0px; margin-top: 40px; }
.p_section_features .p_section_video iframe { position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; }
.p_section_features--promo_photoshoot { border-bottom: 0px; }
.p_section_features--promo_developers { padding: 148px 0px 100px; }
.p_section_features--promo_developers .p_feature__title { font-size: 20px; line-height: 1.4; margin-bottom: 20px; }
.p_section_features--promo_developers .p_feature__icon { line-height: 90px; }
.p_section_features--promo_developers .p_feature__icon img { vertical-align: middle; }
@media (min-width: 768px) {
  .p_section_features--promo_promotion, .p_section_features--promo_turbo { padding-top: 75px; padding-bottom: 84px; }
  .p_section_features--promo_promotion .p_section_title, .p_section_features--promo_turbo .p_section_title { margin-bottom: 50px; }
  .p_section_features--promo_promotion .p_section_image, .p_section_features--promo_turbo .p_section_image { margin-left: 24px; }
}
.p_section_buy, .p_section_delivery, .p_section_image_with_text, .p_section_safedeal, .p_section_text_with_image { text-align: left; }
.p_section_buy p, .p_section_delivery p, .p_section_image_with_text p, .p_section_safedeal p, .p_section_text_with_image p { font-size: 16px; line-height: 1.5; margin-bottom: 33px; color: rgba(57, 57, 57, 0.8); }
.p_section_buy .p_section_title, .p_section_delivery .p_section_title, .p_section_image_with_text .p_section_title, .p_section_safedeal .p_section_title, .p_section_text_with_image .p_section_title { text-align: left; margin-bottom: 20px; }
.p_section_buy .container, .p_section_delivery .container, .p_section_image_with_text .container, .p_section_safedeal .container, .p_section_text_with_image .container { width: 860px; }
.p_section_buy .button, .p_section_delivery .button, .p_section_image_with_text .button, .p_section_safedeal .button, .p_section_text_with_image .button { padding-top: 13px; padding-bottom: 15px; font-size: 16px; text-align: center; width: 220px; }
.p_section_buy .p_section_image, .p_section_delivery .p_section_image, .p_section_image_with_text .p_section_image, .p_section_safedeal .p_section_image, .p_section_text_with_image .p_section_image { position: absolute; top: 70px; bottom: 0px; left: 57%; width: auto; text-align: left; }
.p_section_buy .p_section_image img, .p_section_delivery .p_section_image img, .p_section_image_with_text .p_section_image img, .p_section_safedeal .p_section_image img, .p_section_text_with_image .p_section_image img { width: auto; max-height: 100%; display: inline-block; vertical-align: bottom; }
@media (min-width: 992px) {
  .p_section_buy--inverse .col-sm-6, .p_section_delivery--inverse .col-sm-6, .p_section_image_with_text--inverse .col-sm-6, .p_section_safedeal--inverse .col-sm-6, .p_section_text_with_image--inverse .col-sm-6 { float: right; }
  .p_section_buy--inverse .p_section_image, .p_section_delivery--inverse .p_section_image, .p_section_image_with_text--inverse .p_section_image, .p_section_safedeal--inverse .p_section_image, .p_section_text_with_image--inverse .p_section_image { left: auto; right: 57%; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .p_section_buy, .p_section_delivery, .p_section_image_with_text, .p_section_safedeal, .p_section_text_with_image { height: 700px; }
  .p_section_buy .col-sm-6, .p_section_delivery .col-sm-6, .p_section_image_with_text .col-sm-6, .p_section_safedeal .col-sm-6, .p_section_text_with_image .col-sm-6 { padding-left: 40px; padding-right: 0px; }
}
@media (max-width: 767px) {
  .p_section_buy, .p_section_delivery, .p_section_image_with_text, .p_section_safedeal, .p_section_text_with_image { text-align: center; height: auto; }
  .p_section_buy .p_section_title, .p_section_delivery .p_section_title, .p_section_image_with_text .p_section_title, .p_section_safedeal .p_section_title, .p_section_text_with_image .p_section_title { text-align: center; }
  .p_section_buy .container, .p_section_delivery .container, .p_section_image_with_text .container, .p_section_safedeal .container, .p_section_text_with_image .container { width: 320px; }
  .p_section_buy .button, .p_section_delivery .button, .p_section_image_with_text .button, .p_section_safedeal .button, .p_section_text_with_image .button { width: 250px; margin-bottom: 40px; }
  .p_section_buy .p_section_image, .p_section_delivery .p_section_image, .p_section_image_with_text .p_section_image, .p_section_safedeal .p_section_image, .p_section_text_with_image .p_section_image { position: static; width: auto; text-align: center; margin-right: 0px; }
  .p_section_buy .p_section_image img, .p_section_delivery .p_section_image img, .p_section_image_with_text .p_section_image img, .p_section_safedeal .p_section_image img, .p_section_text_with_image .p_section_image img { max-width: 100%; height: auto; width: 360px; }
}
.p_section_buy .p_section_image, .p_section_delivery .p_section_image { top: 90px; bottom: 88px; left: 60%; }
@media (max-width: 991px) {
  .p_section_buy .p_section_image, .p_section_delivery .p_section_image { top: 60px; bottom: 60px; }
}
.p_section_delivery .container { position: relative; }
.p_section_delivery .p_partner { bottom: -190px; white-space: nowrap; }
@media (max-width: 767px) {
  .p_section_delivery .p_section_image { left: 0px; right: 0px; }
  .p_section_delivery .p_section_image img { width: 516px; height: auto; max-width: none; margin-left: -20px; }
}
@media (max-width: 991px) {
  .p_section_delivery .p_partner { max-width: 300px; white-space: normal; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .p_section_delivery .p_partner { margin-top: 40px; }
  .p_section_delivery .p_partner__logo { margin-left: 0px; margin-right: 7px; text-align: left; display: inline-block; }
  .p_section_delivery .p_section_image { left: 35%; right: 0px; text-align: right; top: 130px; bottom: 30px; }
}
@media (min-width: 992px) {
  .p_section_delivery .p_section_image { left: 45%; right: 0px; bottom: 130px; text-align: right; }
  .p_section_delivery .p_section_image img { width: auto; height: 100%; }
}
@media (max-width: 991px) {
  .p_section_safedeal { padding-bottom: 0px !important; }
}
.p_section_text_with_image .container { z-index: 2; position: relative; }
.p_section_text_with_image .p_section_image { z-index: 0; left: 45%; }
.p_section_text_with_image--promo_photoshoot .p_section_image { left: 55%; }
@media (max-width: 767px) {
  .p_section_text_with_image .container { width: 340px; }
  .p_section_text_with_image .p_section_image { margin-bottom: -50px; }
}
.p_section_promo { height: auto; text-align: center; }
.p_section_promo .p_valign__wrap { padding-bottom: 150px; }
.p_section_promo .p_section_user_item { display: block; height: 287px; margin: 50px auto 60px; background-image: url("/build/images/promotion.e58901.jpg"); background-position: 50% 50%; background-repeat: no-repeat; background-size: auto 100%; }
.p_section_promo .p_section_user_item--empty { background-image: url("/build/images/promotion_empty.3f1622.jpg"); }
.p_section_promo .p_section_user_item .product_item { margin: 0px auto 10px; width: 220px; float: none; }
.p_section_promo p { max-width: 500px; margin: 40px auto; font-size: 16px; color: rgb(57, 57, 57); line-height: 1.5; text-align: center; opacity: 0.8; }
.p_section_promo .button--green, .p_section_promo .button--primary { font-size: 16px; padding: 13px 75px 16px; }
@media (max-width: 767px) {
  .p_section_promo .p_valign__wrap { padding-bottom: 0px; }
  .p_section_promo .p_section_user_item { margin-top: 40px; padding-bottom: 310px; background-image: url("/build/images/m_promotion.2af90d.png"); background-position: 50% 225px; background-size: 100%; }
  .p_section_promo .p_section_user_item--empty { background-image: url("/build/images/m_promotion_empty.e311fe.png"); background-position: 50% 0px; }
  .p_section_promo .p_section_user_item .product_item { width: 100%; }
  .p_section_promo .p_section_user_item .product_item > a { border-radius: 2px; background-color: rgb(255, 255, 255); box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 2px 0px !important; }
  .p_section_promo .p_section_user_item .product_item__image { border-radius: 2px 2px 0px 0px; }
  .p_section_promo .p_section_user_item .product_item__location { left: 9px; }
  .p_section_promo .p_section_user_item .product_item__content { padding-left: 12px; padding-right: 12px; }
  .p_section_promo p { margin-top: -50px; }
  .p_section_promo .button--green, .p_section_promo .button--primary { padding: 13px 55px 16px; }
}
.p_section_image_with_text { text-align: left; }
@media (min-width: 992px) {
  .p_section_image_with_text .col-sm-6 { float: right; }
  .p_section_image_with_text .p_section_image { left: auto; right: 57%; }
}
.p_section_slider .container { position: relative; }
.p_section_slider h3 { font-size: 24px; font-family: "Fira Sans", sans-serif; }
.p_section_slider--promo_developers .p_slide, .p_section_slider--promo_photoshoot .p_slide { background-color: transparent; }
.p_section_slider--promo_promotion .pull-right, .p_section_slider--promo_turbo .pull-right { float: left; }
.p_section_slider--promo_promotion .p_slide, .p_section_slider--promo_turbo .p_slide { background-color: rgb(249, 249, 249); }
.p_section_slider--promo_promotion .p_slider_wrapper, .p_section_slider--promo_turbo .p_slider_wrapper { margin-top: 0px; padding-right: 0px; margin-bottom: 32px; }
.p_section_slider--promo_promotion .p_slider_bullets, .p_section_slider--promo_turbo .p_slider_bullets { position: absolute; bottom: 0px; left: 0px; right: 0px; text-align: center; }
@media (min-width: 992px) {
  .p_section_slider--promo_promotion, .p_section_slider--promo_turbo { padding: 74px 0px; }
  .p_section_slider--promo_promotion .p_bullet, .p_section_slider--promo_turbo .p_bullet { background-color: rgb(255, 255, 255); box-shadow: rgb(239, 239, 239) 0px 0px 0px 1px inset; color: rgb(143, 143, 143); font-weight: 400; }
  .p_section_slider--promo_promotion .p_bullet--active, .p_section_slider--promo_promotion .p_bullet:hover, .p_section_slider--promo_turbo .p_bullet--active, .p_section_slider--promo_turbo .p_bullet:hover { background-color: rgb(139, 77, 255); box-shadow: none; color: rgb(255, 255, 255); background-image: linear-gradient(90deg, rgb(139, 77, 255) 0px, rgb(191, 132, 255)); background-repeat: repeat-x; }
  .p_section_slider--promo_promotion .p_slider_bullets, .p_section_slider--promo_turbo .p_slider_bullets { top: 50px; left: 0px; bottom: auto; text-align: left; }
  .p_section_slider--promo_promotion .p_slider_button, .p_section_slider--promo_turbo .p_slider_button { right: auto; left: 33%; margin-top: -20px; }
  .p_section_slider--promo_promotion .p_slider_wrapper, .p_section_slider--promo_turbo .p_slider_wrapper { padding-top: 120px; padding-right: 60px; }
  .p_section_slider--promo_promotion .p_slider_frame, .p_section_slider--promo_turbo .p_slider_frame { height: 555px; margin-top: -60px; margin-left: -106px; }
  .p_section_slider--promo_promotion .p_bullet--active, .p_section_slider--promo_promotion .p_bullet:hover { background-color: rgb(255, 114, 34); background-image: linear-gradient(90deg, rgb(255, 114, 34) 0px, rgb(255, 171, 71)); background-repeat: repeat-x; }
}
@media (min-width: 768px) {
  .p_section_slider--promo_developers .p_slider_image, .p_section_slider--promo_photoshoot .p_slider_image { z-index: -1; }
  .p_section_slider--promo_developers .p_slider_image--1, .p_section_slider--promo_photoshoot .p_slider_image--1 { left: -10%; }
  .p_section_slider--promo_developers .p_slider_image--2 { margin-top: -45px; }
  .p_section_slider--promo_developers .p_slider_image--3 { margin-top: -70px; }
  .p_section_slider--promo_developers .p_slider_image--4 { margin-top: 40px; }
  .p_section_slider--promo_photoshoot .p_slider_image--2 { margin-top: -89px; margin-left: -100px; }
  .p_section_slider--promo_photoshoot .p_slider_image--3 { margin-left: -100px; margin-top: -80px; }
  .p_section_slider--promo_photoshoot .p_slider_image--4 { margin-top: -20px; }
}
@media (max-width: 991px) and (min-width: 768px) {
  .p_section_slider--promo_photoshoot .p_section_title { font-size: 32px; }
  .p_section_slider--promo_photoshoot .p_slider_image--2, .p_section_slider--promo_photoshoot .p_slider_image--3 { margin-left: -240px; }
  .p_section_slider--promo_photoshoot .p_slider_image--4 { margin-left: -60px; }
}
@media (max-width: 991px) {
  .p_section_slider--promo_promotion, .p_section_slider--promo_turbo { padding-bottom: 60px !important; }
  .p_section_slider--promo_promotion .p_bullet, .p_section_slider--promo_turbo .p_bullet { font-size: 0px; line-height: 0; width: 8px; height: 8px; vertical-align: bottom; background-color: rgb(235, 235, 235); }
  .p_section_slider--promo_promotion .p_bullet--active, .p_section_slider--promo_promotion .p_bullet:hover, .p_section_slider--promo_turbo .p_bullet--active, .p_section_slider--promo_turbo .p_bullet:hover { background-color: rgb(143, 143, 143); }
  .p_section_slider--promo_promotion .p_slide__text, .p_section_slider--promo_turbo .p_slide__text { font-size: 14px; line-height: 1.43; }
  .p_section_slider--promo_promotion .p_slider_slides, .p_section_slider--promo_turbo .p_slider_slides { min-height: 0px; padding-bottom: 20px; }
  .p_section_slider--promo_promotion .p_slide, .p_section_slider--promo_turbo .p_slide { position: relative; transition: left 0s ease 0s, opacity 0.2s ease 0s; }
  .p_section_slider--promo_promotion .p_slide--1.p_slide--active, .p_section_slider--promo_turbo .p_slide--1.p_slide--active { left: 0px; margin-left: 0px; }
  .p_section_slider--promo_promotion .p_slide--2.p_slide--active, .p_section_slider--promo_turbo .p_slide--2.p_slide--active { left: -100%; }
  .p_section_slider--promo_promotion .p_slide--3.p_slide--active, .p_section_slider--promo_turbo .p_slide--3.p_slide--active { left: -200%; }
  .p_section_slider--promo_promotion .p_slide--4.p_slide--active, .p_section_slider--promo_turbo .p_slide--4.p_slide--active { left: -300%; }
  .p_section_slider--promo_promotion .p_slide--5.p_slide--active, .p_section_slider--promo_turbo .p_slide--5.p_slide--active { left: -400%; }
  .p_section_slider--promo_promotion .p_slide__text, .p_section_slider--promo_turbo .p_slide__text { margin-left: auto; margin-right: auto; }
  .p_section_slider--promo_promotion .p_slide .p_section_title, .p_section_slider--promo_turbo .p_slide .p_section_title { text-align: center; }
  .p_section_slider--promo_promotion .p_slider_frame, .p_section_slider--promo_turbo .p_slider_frame { height: 545px; }
  .p_section_slider--promo_promotion .p_slider_image img, .p_section_slider--promo_turbo .p_slider_image img { margin-left: auto; margin-right: auto; }
}
@media (max-width: 767px) {
  .p_section_slider { text-align: center; }
  .p_section_slider .container { width: 370px; }
  .p_section_slider--promo_developers .p_slider_image, .p_section_slider--promo_photoshoot .p_slider_image { left: 50%; transform: translate(-50%); }
  .p_section_slider--promo_developers .p_slider_image--1, .p_section_slider--promo_photoshoot .p_slider_image--1 { left: 90%; }
  .p_section_slider--promo_developers .p_slider_image--4, .p_section_slider--promo_photoshoot .p_slider_image--4 { max-width: 100%; height: auto; }
  .p_section_slider--promo_photoshoot .p_slider_frame { height: 473px; }
  .p_section_slider--promo_photoshoot .p_slider_image { margin-top: -90px; }
  .p_section_slider--promo_photoshoot .p_slider_image--2 { left: 0px; transform: translate(0px); }
  .p_section_slider--promo_photoshoot .p_slider_image--4 { margin-top: -20px; }
}
.p_section_app { text-align: center; min-height: 560px; height: 100vh; }
.p_section_app p { font-size: 14px; margin-bottom: 20px; }
.p_section_app .container { height: 100%; }
.p_section_app .stores { margin-bottom: 120px; }
.p_section_app--promo_realty .stores { margin-bottom: 0px !important; }
.p_section_app--promo_developers ._app_link_form, .p_section_app--promo_photoshoot ._app_link_form { display: none !important; }
.p_section_app--promo_developers .p_form_app__inner, .p_section_app--promo_photoshoot .p_form_app__inner { width: 830px; max-width: 100%; }
.p_section_app--promo_developers .hint, .p_section_app--promo_photoshoot .hint { margin-bottom: 35px; font-size: 15px; line-height: 1.47; color: rgb(153, 153, 153); }
.p_section_app--promo_developers .col-sm-4, .p_section_app--promo_photoshoot .col-sm-4 { margin-bottom: 15px; }
.p_section_app--promo_developers .p_form_app, .p_section_app--promo_photoshoot .p_form_app { display: block !important; }
.p_section_app--promo_developers .p_form_app .form-control, .p_section_app--promo_photoshoot .p_form_app .form-control { padding-top: 8px; }
.p_section_app--promo_photoshoot .p_section_title { margin-bottom: 10px; }
.p_section_app--promo_photoshoot .hint { margin-bottom: 55px; color: rgb(57, 57, 57); max-width: 760px; margin-left: auto; margin-right: auto; }
.p_section_app--promo_photoshoot .stores { margin-bottom: 0px; }
.p_section_app--promo_photoshoot .p_form_app__inner { width: 1045px; }
.p_section_app--promo_photoshoot .p_form_app__inner .hint { font-size: 14px; line-height: 1.36; color: gray; margin-top: 10px; text-align: left; margin-bottom: 10px; max-width: none; }
.p_section_app--promo_photoshoot .p_form_app__inner .hint span { color: rgb(0, 0, 0); }
.p_section_app--promo_photoshoot .p_form_app .row > div { margin-bottom: 20px; }
@media (min-width: 992px) {
  .p_section_app { border-bottom: 0px; }
  .p_section_app--promo_photoshoot .p_form_app__inner { padding-bottom: 40px; }
  .p_section_app--promo_photoshoot .p_form_app__inner .hint { white-space: nowrap; position: absolute; top: 100%; margin-top: 12px; }
  .p_section_app--promo_photoshoot .p_form_app .row { display: table; width: 100%; margin: 0px; }
  .p_section_app--promo_photoshoot .p_form_app .row::after, .p_section_app--promo_photoshoot .p_form_app .row::before { display: none; }
  .p_section_app--promo_photoshoot .p_form_app .row > div { position: relative; display: table-cell; vertical-align: top; margin-bottom: 0px; float: none; }
  .p_section_app--promo_photoshoot .p_form_app .col-sm-3 { width: 210px; }
  .p_section_app--promo_photoshoot .p_form_app .col-sm-4 { width: 415px; }
}
@media (max-width: 991px) {
  .p_section_app { height: auto; min-height: 0px; }
  .p_section_app .stores { margin-bottom: 50px; }
  .p_section_app .p_section_title { max-width: 400px; margin-left: auto; margin-right: auto; }
  .p_section_app p { margin-bottom: 0px; max-width: 310px; margin-left: auto; margin-right: auto; }
  .p_section_app--promo_photoshoot .p_form_app__inner { max-width: 300px; }
  .p_section_app--promo_photoshoot .p_form_app .row > div { float: none; width: 100%; }
}
@media (max-width: 991px) and (orientation: portrait) {
  .p_section_app--promo_realty { height: calc(100vh - 90px); }
}
@media (max-height: 680px) {
  .p_section_app .stores { margin-bottom: 50px; }
}
.p_valign { display: table; width: 100%; height: 100%; table-layout: fixed; }
.p_valign__row { display: table-row; }
.p_valign__wrap { display: table-cell; vertical-align: middle; }
.p_section_final { background-color: rgb(255, 255, 255); text-align: center; padding: 100px 0px; border-bottom: 0px; }
.p_section_final img { margin-bottom: 30px; }
.p_section_final .p_section_title { text-align: center; margin-bottom: 48px; }
@media (max-width: 991px) {
  .p_section_final .p_section_title { font-size: 24px; margin-bottom: 30px; }
}
@media (min-width: 992px) {
  .p_section_final.p_section { height: 100vh; }
}
@media (min-width: 992px) {
  .route__promo_promotion .p_section_final, .route__promo_turbo .p_section_final { min-height: 800px; }
}
@media (max-width: 991px) {
  .route__product_promotion .container { padding-left: 0px; padding-right: 0px; }
  .product_promotion { background-color: rgb(249, 249, 249); height: 100vh; margin-bottom: -35px; }
}

/* Stylesheet : https://youla.ru/build/payments.06f1e6.css */
.form__1P2Y3sX1 { position: relative; }
.form__1P2Y3sX1 .button--delete { right: 0px; z-index: 1; color: gray; }
.form__1P2Y3sX1 .button--delete:hover { color: rgb(57, 57, 57); }
.form__1P2Y3sX1 .button--delete .icon { color: inherit; }
.form__1P2Y3sX1 .button--delete + .form_control { padding-right: 30px; }
.form__1P2Y3sX1 .row { margin-left: -10px; margin-right: -10px; }
.form__1P2Y3sX1 .row > div { padding-left: 10px; padding-right: 10px; }
@media (max-width: 991px) {
  .form__1P2Y3sX1 { padding-bottom: 17px; }
}
.form_control__3Uyg-pWq.form_control__3Uyg-pWq { width: 100%; border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; border-radius: 0px; padding-left: 0px; padding-right: 0px; height: 42px; font-size: 16px; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); transition: border-color 0.2s ease 0s; }
.form_control__3Uyg-pWq.form_control__3Uyg-pWq:focus { border-bottom-color: rgb(3, 154, 211); }
.form_control__3Uyg-pWq::-webkit-input-placeholder { font-size: 16px; color: gray; }
.form_control__3Uyg-pWq::placeholder { font-size: 16px; color: gray; }
textarea.form_control__3Uyg-pWq { resize: none; height: auto; min-height: 40px; max-height: 120px; }
@media (max-width: 991px) {
  textarea.form_control__3Uyg-pWq { padding-top: 20px; min-height: 70px; }
}
.form_group__3-PlZQuP { margin-bottom: 20px; position: relative; }
.form_group__3-PlZQuP:focus { border: none; outline: none; }
@media (max-width: 991px) {
  .form_group__3-PlZQuP { margin-bottom: 8px; }
}
.label__332nHo7g { display: block; font-size: 14px; color: rgb(143, 143, 143); margin-bottom: 12px; padding-top: 10px; }
@media (max-width: 767px) {
  .label__332nHo7g { margin-bottom: 6px; }
}
.group_container__12wNwGP9 { font-size: 0px; margin-bottom: 20px; border-bottom: 1px solid rgb(235, 235, 235); }
.group_container__focus__3mj1cO0Q { border-bottom-color: rgb(3, 154, 211); }
.group__1v7ouE2m .form_group__3-PlZQuP { display: inline-block; vertical-align: middle; width: 30%; margin-bottom: 0px; }
.group__1v7ouE2m .form_group__3-PlZQuP:last-child, .group__1v7ouE2m .form_group__3-PlZQuP:last-of-type { width: 25%; }
.group__1v7ouE2m .form_control__3Uyg-pWq { width: 100%; border-bottom: 0px; }
.group__1v7ouE2m span { width: 5.5%; text-align: center; font-size: 16px; color: rgb(234, 234, 234); display: inline-block; vertical-align: middle; }
.address__3uSvKlHU { margin-bottom: 25px; padding-top: 5px; }
.address_map__2kRHJ7WM { float: left; margin-right: 30px; width: 180px; height: 180px; border-radius: 2px; cursor: pointer; }
.address_map__2kRHJ7WM img { width: 100%; height: 100%; border-radius: 2px; }
.address_content__3C31XaN8 { overflow: hidden; }
.address__3uSvKlHU button { margin: 10px 0px 0px -15px; }
@media (max-width: 991px) {
  .address__3uSvKlHU { margin-bottom: 15px; }
  .address_map__2kRHJ7WM { display: none; }
}
.address__3uSvKlHU::after { content: ""; display: block; clear: both; }
.place__12NqXmUR { margin-bottom: 16px; }
.place_image__12lje0Rp { width: 84px; height: 60px; float: left; margin-right: 14px; border-radius: 2px; }
.place_image__12lje0Rp img { border-radius: 2px; }
.place_content__3JDivMsg { overflow: hidden; }
.place_title__TcsCqjam { color: rgb(57, 57, 57); font-size: 20px; line-height: 1.2; margin-bottom: 7px; }
.place_desc__2KVF7dDE { font-size: 14px; line-height: 1.71; margin-bottom: 3px; }
@media (max-width: 991px) {
  .place_title__TcsCqjam { font-size: 16px; margin-bottom: 10px; }
}
@media (max-width: 767px) {
  .place__12NqXmUR { margin-bottom: 0px; }
  .place_image__12lje0Rp { margin-right: 0px; margin-left: 14px; float: right; }
  .place_title__TcsCqjam { font-size: 14px; margin-bottom: 5px; }
}
.place__12NqXmUR::after { content: ""; display: block; clear: both; }
.data__388YiuhZ { color: rgb(143, 143, 143); font-size: 14px; line-height: 1.4; list-style: none; padding: 0px; margin: 0px; }
.data__388YiuhZ li { margin-bottom: 3px; }
.data__388YiuhZ li:last-child { margin-bottom: 0px; }
.data__mobile__2dfVb27p { display: none; }
@media (max-width: 767px) {
  .data__388YiuhZ { display: none; }
  .data__mobile__2dfVb27p { display: block; }
}
@media (max-width: 991px) {
  .hint__2r7xW549 { margin-top: 20px; margin-bottom: 20px; }
}
.total__I4PhWy7G { display: none; padding-top: 13px; padding-bottom: 12px; border-top: 1px solid rgb(235, 235, 235); }
.total_text__1mD3ZJgn { float: left; }
.total_price__vlZUHSq2 { float: right; font-size: 16px; color: rgb(57, 57, 57); }
@media (max-width: 991px) {
  .total__I4PhWy7G { display: block; }
}
.total__I4PhWy7G::after { content: ""; display: block; clear: both; }
.hint_error__3xUUs9jF { float: left; z-index: 30; color: rgb(242, 55, 71); margin-top: -15px; margin-bottom: 10px; }
@media (max-width: 991px) {
  .hint_error__3xUUs9jF { margin-top: 0px; margin-bottom: 5px; float: none; }
}
.input_error__B7Uw6wLj.input_error__B7Uw6wLj { border-bottom: 1px solid rgb(242, 55, 71); }
.hint_error_another__3G6Ph2cJ { bottom: -20px; white-space: nowrap; }
.boxberry_text__24q32W-A { color: rgb(127, 127, 127); padding: 10px 0px; }
.boxberry_text__24q32W-A img { display: inline-block; vertical-align: middle; margin-right: 10px; }
@media (max-width: 767px) {
  .boxberry_text__24q32W-A { padding: 0px; margin-bottom: 15px; }
  .boxberry_text__24q32W-A img { display: block; margin-bottom: 5px; }
}
.hint_text__22iLmT10 { line-height: 1.43; color: rgb(143, 143, 143); margin-bottom: 20px; }
.hint_text__22iLmT10 a { color: rgb(3, 154, 211); text-decoration: none; }
.map__1rpObh5d { background-color: rgb(249, 249, 249); height: 100%; left: 255px; position: absolute; top: 0px; right: 0px; }
@media (max-width: 991px) {
  .map__1rpObh5d { position: absolute; left: 0px; right: 0px; bottom: 0px; top: 0px; width: 100%; height: auto; margin-left: 0px; }
}
.tabs__2LrgReg- { display: none; height: 56px; padding: 0px 54px; border-bottom: 1px solid rgb(235, 235, 235); }
.tabs_item__wNRWXPaX { width: 50%; height: 100%; padding: 17px; float: left; cursor: pointer; text-align: center; font-size: 14px; color: rgba(57, 57, 57, 0.8); border-bottom: 2px solid transparent; transition: border-color 0.2s ease 0s; }
.tabs_item__active__1JrfIPUn { color: rgb(57, 57, 57); border-bottom-color: rgb(3, 154, 211); }
@media (max-width: 991px) {
  .tabs__2LrgReg- { display: block; }
}
.data__2EKiw-DW { display: block; overflow: hidden; margin-bottom: 3px; }
.data__2EKiw-DW > li { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.pickup_point__d_CPSUSi { position: absolute; left: 15px; top: 15px; width: 360px; border-radius: 2px; background: rgba(255, 255, 255, 0.9); display: none; }
.pickup_point__d_CPSUSi .item__3Y-Vbc0w:hover { background: rgb(255, 255, 255); }
.pickup_point__d_CPSUSi .item_inner__2ANaw3gD { border-bottom: 0px; }
@media (max-width: 991px) {
  .pickup_point__d_CPSUSi { display: block; }
}
@media (max-width: 767px) {
  .pickup_point__d_CPSUSi { left: 0px; right: 0px; top: 0px; width: 100%; }
}
.pickup_list__1dEg9hW3 { position: absolute; left: 0px; top: 0px; bottom: 0px; width: 255px; z-index: 900; }
@media (max-width: 991px) {
  .pickup_list__1dEg9hW3 { left: 0px; right: 0px; bottom: 0px; top: 0px; width: 100%; }
  .pickup_list__1dEg9hW3 .list_inner__1W5lP_aW { padding-bottom: 32px; }
}
.list__29YNwOpS { overflow: hidden; border-radius: 2px; z-index: 20; height: 100%; }
.list_inner__1W5lP_aW { overflow: auto; background-color: rgb(255, 255, 255); border-radius: 2px; max-height: 100%; }
.list__29YNwOpS .item__3Y-Vbc0w { cursor: pointer; }
.list__29YNwOpS .item__3Y-Vbc0w:hover { background-color: rgb(249, 249, 249); z-index: 1; margin-top: -1px; padding-top: 1px; }
.list__29YNwOpS .item__3Y-Vbc0w:hover .item_inner__2ANaw3gD { border-color: rgb(249, 249, 249); }
.place__S7Hxzg_D { margin-bottom: 0px; }
.place_title__3bK-j2Sr { font-size: 16px; line-height: 1.5; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
@media (max-width: 991px) {
  .place_image__29whLl7o { display: block; float: right; margin-right: 0px; }
}
.item__3Y-Vbc0w { padding: 0px 10px 0px 20px; margin-bottom: -1px; transition: background 0.2s ease 0s, border 0.2s ease 0s; }
.item_inner__2ANaw3gD { padding: 12px 0px; border-bottom: 1px solid rgb(235, 235, 235); }
.item__active__1-78dQS7 { margin-top: -1px; padding-top: 1px; background-color: rgb(229, 244, 250); z-index: 2 !important; }
.item__active__1-78dQS7 .item_inner__2ANaw3gD { border-color: rgb(229, 244, 250); }
.item__active__1-78dQS7 .place_title__3bK-j2Sr { color: rgb(3, 154, 211); }
.item__active__1-78dQS7 .place_title__3bK-j2Sr::before { font-family: icons; content: ""; margin-right: 5px; }
.item__active__1-78dQS7:hover { background-color: rgb(229, 244, 250) !important; }
.item__active__1-78dQS7:hover .item_inner__2ANaw3gD { border-color: rgb(229, 244, 250) !important; }
.item__single__3ZOPDKx3 { padding-left: 20px; padding-right: 20px; }
.item__single__3ZOPDKx3 .place_image__29whLl7o { float: none; margin-right: 0px; margin-bottom: 10px; }
.item__single__3ZOPDKx3 .data__2EKiw-DW { margin-bottom: 25px; }
@media (max-width: 991px) {
  .item__3Y-Vbc0w { padding-left: 10px; }
}
.button_back__2DUrY9Kz { margin: 8px -16px 12px; }
.button_back__2DUrY9Kz .icon { margin: -3px 6px 0px 0px !important; }
@media (max-width: 991px) {
  .tab_content__PwWTzpJF { position: absolute; left: 0px; right: 0px; bottom: 0px; top: 56px; width: 100%; display: none; }
  .tab_content__active__1kYdUr0N { display: block; }
}
.form__buttons__2ERabnTv { z-index: 1000; }
.modal_container__L7z0wU9J { height: 100%; }
.product__3EHhOKjP { padding: 20px 0px; }
.product_image__3sHXUjtk { width: 100px; height: 100px; float: left; margin-right: 20px; border-radius: 2px; }
.product_image__3sHXUjtk img { width: 100%; height: auto; border-radius: 2px; }
.product_content__3eSPcss0 { overflow: hidden; padding-top: 5px; }
.product_title__T6ftZGNh { font-size: 16px; line-height: 1.25; color: rgb(57, 57, 57); }
@media (max-width: 991px) {
  .product__3EHhOKjP { padding: 10px 0px; }
  .product_image__3sHXUjtk { margin-right: 14px; width: 90px; height: 90px; }
}
@media (max-width: 767px) {
  .product_content__3eSPcss0 { padding-top: 0px; }
  .product_title__T6ftZGNh { font-size: 14px; line-height: 1.43; }
}
.product__3EHhOKjP::after { content: ""; display: block; clear: both; }
.separator__1KGkmks6 { border-top: 1px solid rgb(235, 235, 235); }
.separator__1KGkmks6 ~ .value__3gvZIO_J { margin-bottom: 25px; }
.group__3sbg2c9i { margin-bottom: 13px; }
.group__3sbg2c9i + .group__3sbg2c9i { margin-top: 20px; }
.value__3gvZIO_J { font-size: 16px; color: rgb(57, 57, 57); margin-bottom: 13px; margin-top: 10px; word-break: break-word; }
.value__small__Nhom8eXJ { font-size: 14px; }
.label__1i2B0Me6 { padding-top: 0px; margin-top: 10px; margin-bottom: 10px; }
.block__3veWXAKe { padding: 14px 0px; }
.block_row__2T2dsnBW + .block_row__2T2dsnBW { margin-top: 10px; }
.block_row__2T2dsnBW::after { content: ""; display: block; clear: both; }
.block__3veWXAKe .label__1i2B0Me6, .block__3veWXAKe .value__3gvZIO_J { float: left; margin: 0px; }
.block__3veWXAKe .price__UoqtHT_2, .block__3veWXAKe .value__small__Nhom8eXJ { float: right; }
.price__UoqtHT_2 { font-size: 16px; color: rgb(3, 154, 211); }
.place__3Vj9xCH4 { margin-bottom: 0px; }
.place_title__JpG-MVbj { font-size: 16px; margin-bottom: 5px; line-height: normal; }
.place_image__URiFolIH { margin-right: 0px; margin-left: 20px; float: right; }
.data__3s_TK05Y { display: block; }
.attachments__3hPFTWi5 { margin-left: -5px; margin-right: -5px; }
.attachments__3hPFTWi5::after { content: ""; display: block; clear: both; }
.attachment__3fRffw9h { float: left; padding-left: 5px; padding-right: 5px; }
@media (max-width: 575px) {
  .attachment__3fRffw9h { padding-left: 4px; padding-right: 4px; }
}
.attachment_inner__3BCn8m-S { position: relative; border-radius: 2px; overflow: hidden; width: 114px; height: 114px; display: block; }
.attachment_inner__3BCn8m-S .button--delete { top: 0px; right: 0px; margin-top: 0px; color: rgb(255, 255, 255); background: rgba(0, 0, 0, 0.2); border-radius: 0px; }
.attachment_inner__3BCn8m-S .button--delete .icon { color: inherit; }
@media (max-width: 575px) {
  .attachment_inner__3BCn8m-S { width: 64px; height: 64px; }
}
.attachment_preview__2TYzNOV8 { width: 100%; height: 100%; cursor: pointer; text-align: center; border-radius: 2px; background-color: rgb(235, 235, 235); box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; transition: background-color 0.2s ease 0s; }
.attachment_preview__2TYzNOV8::before { content: ""; font-family: icons; position: absolute; top: 50%; left: 0px; right: 0px; font-size: 36px; width: 36px; height: 36px; margin: -18px auto 0px; color: rgb(255, 255, 255); line-height: normal; }
.attachment_preview__2TYzNOV8:hover { background-color: rgb(224, 224, 224); }
.attachment_preview__error__1ylkbnK8 { box-shadow: rgb(247, 80, 89) 0px 0px 0px 1px inset; padding: 12px; }
.attachment_preview__error__1ylkbnK8 .button--delete { background-color: transparent !important; color: rgb(247, 80, 89) !important; }
.attachment_preview__error__1ylkbnK8, .attachment_preview__loading__2RjrfpyO { background: rgb(255, 255, 255); cursor: auto; }
.attachment_preview__error__1ylkbnK8::before, .attachment_preview__loading__2RjrfpyO::before { display: none; }
.attachment_preview__error__1ylkbnK8:hover, .attachment_preview__loading__2RjrfpyO:hover { background: rgb(255, 255, 255); }
.attachment_preview__loading__2RjrfpyO { box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; }
@media (max-width: 575px) {
  .attachment_preview__2TYzNOV8::before { font-size: 20px; width: 20px; height: 20px; margin-top: -10px; }
}
.attachment_control__2go8zORW { position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; -webkit-appearance: none; }
.error_text__N9BDWYkk { color: rgb(242, 55, 71); font-size: 12px; line-height: 16px; text-align: center; }
.error_text__N9BDWYkk .button { text-decoration: underline; padding: 0px; font-weight: inherit; text-transform: inherit; font-size: 12px; white-space: normal; color: inherit !important; }
@media (max-width: 575px) {
  .error_text__N9BDWYkk { font-size: 0px; }
  .error_text__N9BDWYkk .button--link { font-size: 0px; position: absolute; top: 50%; left: 0px; right: 0px; width: 30px; padding: 5px; line-height: normal; margin: -15px auto 0px; }
  .error_text__N9BDWYkk .button--link::before { content: ""; font-family: icons; font-size: 20px; }
}
.attachment_image_container__ViZ4oa_t { position: relative; height: 100%; }
.attachment_image_container__ViZ4oa_t > img { position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); }
.field_group__1xy4_wX8 .form_control_radio__1oXpyP4R { display: inline-block; vertical-align: top; width: 50%; }
@media (max-width: 767px) {
  .field_group__1xy4_wX8 .form_control_radio__1oXpyP4R { width: 100%; display: block; margin-bottom: 5px; }
}
.field_group_title__21zaA4Vl { font-size: 14px; color: rgb(57, 57, 57); font-weight: 600; margin-bottom: 15px; display: block; }
.field_group_title__21zaA4Vl + .form_control__US-YC06k { padding-top: 0px; }
.form_group__UZnwLi3i { margin-bottom: 25px; }
.form_group__UZnwLi3i .hint { font-size: 14px; margin-top: 10px; margin-bottom: 10px; }
.form_control_radio__1oXpyP4R { margin-bottom: 0px; }
.form_control_radio__1oXpyP4R .hint { display: block; margin-top: 5px; margin-bottom: 0px; font-size: 12px; line-height: 16px; color: rgb(143, 143, 143); }
.hint_error__1xJD40zY { color: rgb(242, 55, 71); }
textarea.form_control__US-YC06k { min-height: 52px; resize: none; }
@media (max-width: 767px) {
  textarea.form_control__US-YC06k { font-size: 14px; }
}
.root__3ahLIWiH { position: relative; }
.suggest__39HSdA70 .suggest_list__2FrKhm4H { left: -15px; right: -15px; }
.suggest__39HSdA70 .suggest_item__3YDeS0Jr { padding-left: 15px; padding-right: 15px; }
.suggest__39HSdA70 .suggest_list--focused__3sCWmA0o { background-color: rgb(245, 245, 245) !important; color: rgb(51, 51, 51) !important; }
@media (max-width: 767px) {
  .suggest__39HSdA70 .suggest_list__2FrKhm4H { left: -10px; right: -10px; }
  .suggest__39HSdA70 .suggest_item__3YDeS0Jr { padding-left: 10px; padding-right: 10px; }
}
.hint_error__OX7cUQPh { position: absolute; bottom: -20px; color: rgb(247, 80, 89); }
.input_error__3eBcgfIa { border-bottom: 1px solid rgb(247, 80, 89) !important; }
.product__2oLb4nXl { background-color: rgb(255, 255, 255); border-radius: 4px; border: 1px solid rgb(235, 235, 235); margin-bottom: 32px; position: relative; }
.product_image__2AcYUpNV { float: left; width: 100px; height: 100px; font-size: 0px; line-height: 0; position: relative; overflow: hidden; }
.product_image__2AcYUpNV img { border-radius: 2px 0px 0px 2px; }
.product_image__2AcYUpNV a { display: block; }
.product_price__2IFwtrXu { color: rgb(57, 57, 57); font-size: 24px; margin-bottom: 8px; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
.product_title__3jNOq_vZ { color: rgb(57, 57, 57); font-size: 18px; line-height: 24px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.product_title__3jNOq_vZ a { color: inherit; text-decoration: none; }
.product_text__CJfFik_i { color: rgb(57, 57, 57); font-size: 14px; line-height: 1.43; max-height: 40px; overflow: hidden; }
.product_content__mI30-3Fr { padding: 20px 0px 20px 20px; overflow: hidden; }
.product_inner__1ZrDwagy { padding-right: 20px; border-right: 1px solid rgb(235, 235, 235); min-height: 59px; }
.product_owner__VUJH2ylJ { float: right; width: 339px; padding: 20px 30px; }
.product_owner__VUJH2ylJ .rating { margin-top: 10px; }
.product_owner__VUJH2ylJ .button { display: none; }
.product_owner__VUJH2ylJ .user { margin-bottom: 0px; }
.product_owner__VUJH2ylJ .user__info { padding: 3px 0px 0px; }
.product_owner__VUJH2ylJ .user__image { margin: 0px 20px 0px 0px; }
.product_owner__VUJH2ylJ .user__image img { width: 60px; height: 60px; }
.product_owner__VUJH2ylJ .user__name { margin-bottom: 6px; font-size: 14px; }
.product_owner__order__1of37Y-y { position: relative; }
.product_owner__order__1of37Y-y .button_open_order { position: absolute; bottom: 18px; left: 95px; width: auto; min-width: 0px; text-align: left; display: block; }
.product_owner__order__1of37Y-y .rating { display: none; }
.product_owner__order__1of37Y-y .user { margin-bottom: 0px; }
.product_owner__order__1of37Y-y .user__label { display: inline; margin-right: 4px; }
.product_owner__order__1of37Y-y .user__info { padding-top: 7px; }
.product_owner__order__1of37Y-y .user__image { margin-top: 0px; }
.product_owner__order__1of37Y-y .user__name { font-size: 14px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
@media (max-width: 991px) {
  .product__2oLb4nXl { border-width: 0px 0px 1px; border-top-style: initial; border-right-style: initial; border-left-style: initial; border-top-color: initial; border-right-color: initial; border-left-color: initial; border-image: initial; border-bottom-style: solid; border-bottom-color: rgb(235, 235, 235); padding: 10px 15px; margin: 0px -15px; }
  .product_owner__VUJH2ylJ { display: none; }
  .product_inner__1ZrDwagy { padding-right: 0px; border-right: 0px; min-height: 0px; }
  .product_content__mI30-3Fr { padding-top: 0px; padding-bottom: 0px; padding-left: 14px; }
  .product_image__2AcYUpNV { width: 90px; height: 90px; border-radius: 4px; }
  .product_image__2AcYUpNV img { width: 90px; height: 90px; }
  .product_text__CJfFik_i { display: none; }
  .product_title__3jNOq_vZ { font-size: 16px; white-space: normal; max-height: 48px; }
  .product_price__2IFwtrXu { position: absolute; bottom: 0px; right: 15px; font-size: 16px; font-weight: 400; margin-bottom: 10px; }
  .product__order__3dYX1fHW { padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px; }
  .product__order__3dYX1fHW .product_price__2IFwtrXu { right: 0px; color: rgb(57, 57, 57); font-size: 14px; }
}
@media (max-width: 767px) {
  .product__2oLb4nXl { padding-left: 10px; padding-right: 10px; margin-left: -10px; margin-right: -10px; }
  .product_price__2IFwtrXu { right: 10px; }
  .product_title__3jNOq_vZ { font-size: 14px; line-height: 1.43; }
  .product__order__3dYX1fHW { padding-left: 0px; padding-right: 0px; margin-left: 0px; margin-right: 0px; }
}
.product__2oLb4nXl::after { content: ""; display: block; clear: both; }
.product_discount_label__2QPhKO-m { position: absolute; height: 91px; width: 91px; transform: rotate(-45deg) translateY(-70%); overflow: hidden; }
.product_discount_label__2QPhKO-m::before { content: ""; border-radius: 4px 0px 0px; background: linear-gradient(135deg, rgb(0, 219, 255), rgb(3, 154, 211) 64%); height: 100%; width: 100%; display: block; transform: rotate(45deg) translate(49%, 49%); position: absolute; }
@media (max-width: 991px) {
  .product_discount_label__2QPhKO-m { height: 80px; width: 80px; }
}
.product_discount_text__10Kq7Q3j { position: absolute; bottom: -10px; left: 50%; font-size: 16px; color: rgb(255, 255, 255); line-height: 24px; transform: translate(-50%, -50%); text-align: center; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
.product_price__2IFwtrXu { display: inline-flex; }
.product_old_price__3axip87u { order: 1; color: rgb(143, 143, 143); font-size: 16px; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; font-weight: 400; text-decoration: line-through; display: inline-block; vertical-align: middle; margin-left: 13px; margin-right: 13px; }
@media (max-width: 991px) {
  .product_old_price__3axip87u { order: 0; font-size: 12px; line-height: 22px; }
}
.product_real_price__j_Bk3J3i { order: 0; }
@media (max-width: 991px) {
  .product_real_price__j_Bk3J3i { order: 1; }
}
.message__5Ep02yP0 { text-align: center; margin: 50px 0px; }
.message_title__4mGlZ9H5 { font-size: 30px; text-align: center; margin-bottom: 20px; line-height: normal; }
.message_title__4mGlZ9H5 i { font-size: inherit; margin-right: 10px; position: relative; top: 2px; }
.message_title__success__25FN5oy1 { color: rgb(89, 168, 64); }
@media (max-width: 767px) {
  .message_title__4mGlZ9H5 { font-size: 20px; margin-bottom: 18px; }
  .message_title__4mGlZ9H5 i { top: 0px; }
}
.hint__1Lptt0Qt { font-size: 14px; line-height: 1.43; color: rgb(143, 143, 143); margin-bottom: 35px; padding: 0px 20px; }
@media (max-width: 767px) {
  .hint__1Lptt0Qt { margin-bottom: 25px; }
}
.tabs__2t54pDtg { overflow: hidden; border-radius: 2px; margin-bottom: 25px; }
@media (max-width: 991px) {
  .tabs__2t54pDtg { margin: 0px -15px 20px; }
}
@media (max-width: 767px) {
  .tabs__2t54pDtg { margin: 0px -10px 10px; }
}
.tab__13DKWU-D { display: block; text-decoration: none; border: 1px solid rgb(235, 235, 235); background-color: rgb(255, 255, 255); margin-bottom: -1px; position: relative; cursor: pointer; z-index: 0; color: rgb(57, 57, 57); line-height: 1.71; font-size: 14px; transition-property: background-color, border-color; transition-duration: 0.2s; transition-timing-function: ease; }
.tab__13DKWU-D:hover { background-color: rgb(249, 249, 249); }
.tab__13DKWU-D:first-child { border-radius: 2px 2px 0px 0px; }
.tab__13DKWU-D:last-child { margin-bottom: 0px; border-radius: 2px 2px 0px 0px; }
.tab__active__3xXMJJRt { z-index: 1; border-color: rgb(3, 154, 211); }
.tab__active__3xXMJJRt, .tab__active__3xXMJJRt:hover { background-color: rgb(229, 244, 250); }
.tab_desc__2Lf7A_79, .tab_price__3c3Qa3nF, .tab_title__3JjUypIZ { padding: 9px 16px 10px; }
.tab_title__3JjUypIZ { width: 215px; float: left; }
.tab_desc__2Lf7A_79 { overflow: hidden; padding-left: 0px; padding-right: 0px; color: rgb(143, 143, 143); }
.tab_price__3c3Qa3nF { float: right; color: rgb(3, 154, 211); }
@media (max-width: 991px) {
  .tab__13DKWU-D { border-radius: 0px; border-left: 0px; border-right: 0px; padding: 6px 0px; }
  .tab_desc__2Lf7A_79, .tab_price__3c3Qa3nF, .tab_title__3JjUypIZ { padding: 0px 15px; }
  .tab_desc__2Lf7A_79 { width: 100%; }
  .tab__active__3xXMJJRt { border-color: transparent; }
}
@media (max-width: 767px) {
  .tab_desc__2Lf7A_79, .tab_price__3c3Qa3nF, .tab_title__3JjUypIZ { padding-left: 10px; padding-right: 10px; }
}
.tab__13DKWU-D::after { content: ""; display: block; clear: both; }
.summary__2TIRymkY { width: 340px; background-color: rgb(255, 255, 255); border-radius: 2px; border: 1px solid rgb(235, 235, 235); margin-bottom: 32px; padding: 21px 30px; float: right; right: 0px; top: 0px; }
.summary_price__1HT5R9_P { color: rgb(57, 57, 57); font-size: 30px; margin-bottom: 5px; line-height: normal; font-weight: 500; font-family: "Fira Sans", -apple-system, "Open Sans", "Helvetica Neue", sans-serif; }
.summary_delivery__1bPMd9hw { font-size: 14px; color: rgb(42, 42, 42); }
.summary_header__bJmC15X9 { margin-bottom: 25px; }
.summary__2TIRymkY .hint { font-size: 14px; line-height: 1.43; color: rgb(143, 143, 143); margin-top: 15px; margin-bottom: 4px; }
.summary__2TIRymkY .fixed_buttons { margin-top: 0px; }
.summary__2TIRymkY .fixed_buttons::after { box-shadow: rgb(255, 255, 255) 0px 40px 30px 50px; }
.summary__2TIRymkY .status_badge__icon { width: 24px; height: 24px; float: left; margin: -2px 8px 0px -3px; background-size: 24px; }
@media (max-width: 991px) {
  .summary__2TIRymkY { width: 100%; float: none; border: 0px; padding: 12px 0px; margin-bottom: 14px; position: static; }
  .summary__2TIRymkY .hint, .summary_header__bJmC15X9 { display: none; }
}
.safely_text__392qqBrF { font-size: 14px; line-height: 1.43; color: rgb(57, 57, 57); margin-top: 22px; }
.safely_text__392qqBrF span { overflow: hidden; display: block; }
@media (max-width: 991px) {
  .safely_text__392qqBrF { margin-top: 0px; }
}
.summary_total__3aEKbkJu { padding: 12px 4px; display: none; }
.summary_total_value__nRhP7XkA { float: right; font-size: 16px; color: rgb(3, 154, 211); }
@media (max-width: 991px) {
  .summary_total__3aEKbkJu { display: block; }
}
.panel__3B1d-ak5 { border-radius: 2px; background-color: rgb(249, 249, 249); margin-bottom: 20px; padding: 10px 15px; overflow: hidden; }
.panel_icon__1HmxOezY { float: left; display: flex; justify-content: space-between; align-items: center; width: 48px; height: 48px; background-color: rgb(255, 255, 255); border-radius: 100px; margin-right: 20px; padding: 4px 0px; }
.panel_icon__1HmxOezY > :only-child { margin: auto; }
.panel_content__VGeorc1g { padding-top: 2px; overflow: hidden; }
.panel_content__VGeorc1g + .panel_button__2vr4fIVO { float: none; }
.panel_button__2vr4fIVO { float: right; margin: -5px -15px 0px; }
@media (max-width: 991px) {
  .panel__3B1d-ak5 { margin-left: -15px; margin-right: -15px; }
}
@media (max-width: 767px) {
  .panel__3B1d-ak5 { margin-left: -10px; margin-right: -10px; padding-left: 10px; padding-right: 10px; }
  .panel_icon__1HmxOezY { display: none; }
  .panel_content__VGeorc1g + .panel_button__2vr4fIVO { margin-top: 10px; }
}

.hint__aMasvQSz { margin-bottom: 0px; padding: 0px; color: rgb(127, 127, 127); }
.hint_error__j7wj4nAg { color: rgb(247, 80, 89); }
.text__3Wt10VPX { margin-bottom: 5px; }
.text__3Wt10VPX b { font-weight: 600; }
.text_error__1P9YZeV5 { color: rgb(247, 80, 89); }
.text-red__3KYBKkqG { color: rgb(255, 92, 74); }
.block__3ioUhNQH { margin-right: 340px; padding-right: 38px; }
@media (max-width: 991px) {
  .block__3ioUhNQH { margin-right: 0px; padding-right: 0px; padding-bottom: 1px; width: 100%; overflow: visible; }
}
.container__b66PCR7o { position: relative; }
@media (max-width: 991px) {
  .container__b66PCR7o { margin-bottom: 83px; }
}
.container__b66PCR7o::after { content: ""; display: block; clear: both; }
.payhint__1EGeMlob { font-size: 14px; line-height: 1.43; color: rgb(143, 143, 143); margin-top: 15px; margin-bottom: 4px; }
@media (min-width: 991px) {
  .payhint__1EGeMlob { display: none; }
}
.title__1tzAN2wR { font-size: 30px; margin-top: -5px; margin-bottom: 23px; }
@media (max-width: 991px) {
  .title__1tzAN2wR { display: none; }
}
.container__28A_2L3T::after { content: ""; display: block; clear: both; }

/* Stylesheet : [inline styles] */
.ijDeHI b { font-weight: inherit; }
.ijDeHI i { font-style: inherit; content: "руб."; font-family: RoubleArial, Arial, sans-serif; text-indent: 0px; display: none; }
.body--old_rouble .ijDeHI i { display: inline; user-select: text; }
.body--old_rouble .ijDeHI b { display: none; }
.hvBxNu { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ccONKs { position: relative; vertical-align: middle; margin: 0px; padding: 0px; font-family: inherit; text-decoration: none; text-align: center; line-height: normal; white-space: nowrap; border: 0px; outline: 0px; cursor: pointer; overflow: hidden; transition: all 0.2s ease 0s; border-radius: 4px; color: rgb(255, 255, 255); background-color: rgb(89, 168, 64); box-shadow: none; display: block; width: 100%; box-sizing: border-box; }
.ccONKs:hover { text-decoration: none; }
.ccONKs i { color: rgb(255, 255, 255) !important; }
.ccONKs:hover { color: rgb(255, 255, 255); background-color: #00ad64; box-shadow: none; }
.ccONKs:active { color: rgb(255, 255, 255); background-color: rgb(86, 162, 62); box-shadow: none; }
.ccONKs .sc-fBuWsC { display: block; padding: 9px 16px; font-size: 16px; font-weight: 400; }
.ccONKs .sc-jhAzac { display: inline-block; vertical-align: middle; font-size: 16px; }
.ccONKs .sc-fMiknA { position: relative; display: inline-block; vertical-align: middle; height: calc(24px); font-size: calc(24px); line-height: calc(24px); margin-top: -15px; margin-bottom: -15px; }
.ccONKs .sc-fMiknA::before { display: block; }
.PZSwV { position: relative; display: inline-block; vertical-align: middle; margin: 0px; padding: 0px; font-family: inherit; text-decoration: none; text-align: center; line-height: normal; white-space: nowrap; border: 0px; outline: 0px; cursor: pointer; overflow: hidden; transition: all 0.2s ease 0s; border-radius: 4px; color: rgb(3, 154, 211); background-color: transparent; box-shadow: none; }
.PZSwV:hover { text-decoration: none; }
.PZSwV i { color: rgb(3, 154, 211) !important; }
.PZSwV:hover { color: rgb(53, 174, 219); background-color: transparent; box-shadow: none; }
.PZSwV:active { color: rgb(53, 174, 219); background-color: transparent; box-shadow: none; }
.PZSwV .sc-fBuWsC { display: block; padding: 6px 15px 7px; font-size: 14px; font-weight: 400; }
.PZSwV .sc-jhAzac { display: inline-block; vertical-align: middle; font-size: 14px; }
.PZSwV .sc-fMiknA { position: relative; display: inline-block; vertical-align: middle; height: calc(18px); font-size: calc(18px); line-height: calc(18px); margin-top: -15px; margin-bottom: -15px; }
.PZSwV .sc-fMiknA::before { display: block; }
.dzCROE { max-width: 1264px; min-width: 320px; width: 100%; margin-left: auto; margin-right: auto; padding-left: 16px; padding-right: 16px; background-color: transparent; z-index: 11; }
.lkKOyd { box-sizing: border-box; margin-left: -5px; margin-right: -5px; display: flex; flex-wrap: wrap; position: relative; z-index: 11; }
@media screen and (min-width: 576px) {
  .lkKOyd { margin-left: -5px; margin-right: -5px; }
}
@media screen and (min-width: 768px) {
  .lkKOyd { margin-left: -8px; margin-right: -8px; }
}
.gjZzYk { box-sizing: border-box; padding-left: 5px; padding-right: 5px; width: 100%; display: flex; }
@media screen and (min-width: 576px) {
  .gjZzYk { padding-left: 5px; padding-right: 5px; }
}
@media screen and (min-width: 768px) {
  .gjZzYk { padding-left: 8px; padding-right: 8px; }
}
.gjZzYk > * + * { margin-left: 16px; }
.gjZzYk > :last-child { margin-left: 24px; }
.gjZzYk > * { max-height: 32px; }
.iBzLWS { -webkit-box-flex: 1; flex-grow: 1; margin-right: 8px; max-height: 100%; }
.bcxWYD { font-size: 0px; margin-right: 8px; transform: translateY(-8px); }
.chBiZR { font-size: 0px; }
.XNMw { font-size: 0px; display: inline-block; position: relative; border-radius: 100%; width: 32px; height: 32px; background-image: url("https://cache3.youla.io/files/images/80_80/58/0a/580a36d71c4031229105be3b.jpg"); background-repeat: no-repeat; background-size: 32px; background-position: center center; }
.XNMw::after { top: 0px; right: -4px; z-index: 1; pointer-events: none; content: " "; background: rgb(247, 80, 89); border-radius: 100px; position: absolute; text-align: center; line-height: 14px; min-width: 14px; font-size: 9px; font-weight: 600; color: rgb(255, 255, 255); }
.XNMw::before { content: ""; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; border-radius: 50%; background-color: rgba(51, 51, 51, 0.02); transition: background-color 0.2s ease-in-out 0s; }
.XNMw:hover::before { background-color: rgba(255, 255, 255, 0.08); }
.XNMw:focus::before, .XNMw:active::before { background-color: rgba(51, 51, 51, 0.02); }
@media (hover: none) {
  .XNMw:focus::before { background-color: transparent; }
  .XNMw:hover::before { background-color: rgba(51, 51, 51, 0.02); }
}
.jRQBFv { position: relative; z-index: 10; }
.grYHP { padding-top: 0px; height: 64px; background: rgb(255, 255, 255); }
.hnEmwS { width: 100%; background: rgb(255, 255, 255); box-shadow: rgba(0, 0, 0, 0.04) 0px 2px 4px 0px; position: absolute; transition: box-shadow 0.2s ease 0s; top: 0px; height: 64px; }
.RFfMX { overflow: hidden; position: absolute; padding: 16px 0px 24px; background: rgb(255, 255, 255); width: 100%; opacity: 0; visibility: hidden; transition: opacity 0.1s linear 0s, transform 0.1s linear 0s, visibility 0s linear 0.1s; box-shadow: rgba(0, 0, 0, 0.08) 0px 8px 16px 0px; transform: translate3d(0px, -8px, 0px); }
.fZtIUm { width: 100%; padding: 16px 0px; height: 64px; }
.fZtIUm .sc-dxZgTM { top: 56px; }
.jOxCU { outline: none; box-shadow: none; box-sizing: border-box; padding: 0px 10px; text-align: left; -webkit-appearance: none; height: 32px; font-size: 14px; color: rgb(57, 57, 57); border-radius: 4px; border: 1px solid rgb(235, 235, 235); background-color: rgb(255, 255, 255); min-width: 0px; width: 100%; }
.jOxCU::-webkit-input-placeholder { color: rgb(143, 143, 143); }
@media (max-width: 991px) {
}
.jNDEV { display: block; font-size: 12px; line-height: 16px; color: rgb(143, 143, 143); margin: 5px 0px 0px; position: static; }
.jNDEV:empty { display: none; }
.iVUhcI { position: relative; display: block; vertical-align: top; }
.iVUhcI button { z-index: 1; position: absolute; right: 1px; top: 50%; transform: translate(0px, -50%); }
.iVUhcI button > span { padding: 8px 15px !important; }
.iVUhcI button + input { padding-right: 40px; }
.jOKguJ { width: 100%; display: block; }

/* Stylesheet : [inline styles] */
.ijDeHI b { font-weight: inherit; }
.ijDeHI i { font-style: inherit; content: "руб."; font-family: RoubleArial, Arial, sans-serif; text-indent: 0px; display: none; }
.body--old_rouble .ijDeHI i { display: inline; user-select: text; }
.body--old_rouble .ijDeHI b { display: none; }
.bQwgYy { speak: none; font-style: normal; font-weight: normal; font-variant: normal; -webkit-font-smoothing: antialiased; }
.bQwgYy::before { font-size: inherit; line-height: inherit; }
.bQwgYy::before { font-family: icon-main; content: ""; }
.hvBxNu { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.ccONKs { position: relative; vertical-align: middle; margin: 0px; padding: 0px; font-family: inherit; text-decoration: none; text-align: center; line-height: normal; white-space: nowrap; border: 0px; outline: 0px; cursor: pointer; overflow: hidden; transition: all 0.2s ease 0s; border-radius: 4px; color: rgb(255, 255, 255); background-color: #00ad64; box-shadow: none; display: block; width: 100%; box-sizing: border-box; }
.ccONKs:hover { text-decoration: none; }
.ccONKs i { color: rgb(255, 255, 255) !important; }
.ccONKs:hover { color: rgb(255, 255, 255); background-color: #049a5b; box-shadow: none; }
.ccONKs:active { color: rgb(255, 255, 255); background-color: #049a5b; box-shadow: none; }
.ccONKs .sc-fBuWsC { display: block; padding: 9px 16px; font-size: 16px; font-weight: 400; }
.ccONKs .sc-jhAzac { display: inline-block; vertical-align: middle; font-size: 16px; }
.ccONKs .sc-fMiknA { position: relative; display: inline-block; vertical-align: middle; height: calc(24px); font-size: calc(24px); line-height: calc(24px); margin-top: -15px; margin-bottom: -15px; }
.ccONKs .sc-fMiknA::before { display: block; }
.PZSwV { position: relative; display: inline-block; vertical-align: middle; margin: 0px; padding: 0px; font-family: inherit; text-decoration: none; text-align: center; line-height: normal; white-space: nowrap; border: 0px; outline: 0px; cursor: pointer; overflow: hidden; transition: all 0.2s ease 0s; border-radius: 4px; color: rgb(3, 154, 211); background-color: transparent; box-shadow: none; }
.PZSwV:hover { text-decoration: none; }
.PZSwV i { color: rgb(3, 154, 211) !important; }
.PZSwV:hover { color: rgb(53, 174, 219); background-color: transparent; box-shadow: none; }
.PZSwV:active { color: rgb(53, 174, 219); background-color: transparent; box-shadow: none; }
.PZSwV .sc-fBuWsC { display: block; padding: 6px 15px 7px; font-size: 14px; font-weight: 400; }
.PZSwV .sc-jhAzac { display: inline-block; vertical-align: middle; font-size: 14px; }
.PZSwV .sc-fMiknA { position: relative; display: inline-block; vertical-align: middle; height: calc(18px); font-size: calc(18px); line-height: calc(18px); margin-top: -15px; margin-bottom: -15px; }
.PZSwV .sc-fMiknA::before { display: block; }
.pYkUn { position: relative; vertical-align: middle; margin: 0px; padding: 0px; font-family: inherit; text-decoration: none; text-align: center; line-height: normal; white-space: nowrap; border: 0px; outline: 0px; cursor: pointer; overflow: hidden; transition: all 0.2s ease 0s; border-radius: 4px; color: rgb(57, 57, 57); background-color: rgb(255, 255, 255); box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; display: block; width: 100%; box-sizing: border-box; }
.pYkUn:hover { text-decoration: none; }
.pYkUn i { color: rgb(57, 57, 57) !important; }
.pYkUn:hover { color: rgb(57, 57, 57); background-color: rgba(0, 0, 0, 0.01); box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; }
.pYkUn:active { color: rgb(57, 57, 57); background-color: rgba(0, 0, 0, 0.03); box-shadow: rgb(235, 235, 235) 0px 0px 0px 1px inset; }
.pYkUn .sc-fBuWsC { display: block; padding: 9px 16px; font-size: 16px; font-weight: 400; }
.pYkUn .sc-jhAzac { display: inline-block; vertical-align: middle; font-size: 16px; }
.pYkUn .sc-fMiknA { position: relative; display: inline-block; vertical-align: middle; height: calc(24px); font-size: calc(24px); line-height: calc(24px); opacity: 1; margin-top: -15px; margin-bottom: -15px; }
.pYkUn .sc-fMiknA::before { display: block; }
.bjhbfW { position: relative; vertical-align: middle; margin: 0px; padding: 0px; font-family: inherit; text-decoration: none; text-align: center; line-height: normal; white-space: nowrap; border: 0px; outline: 0px; cursor: pointer; overflow: hidden; transition: all 0.2s ease 0s; border-radius: 4px; color: rgb(255, 255, 255); background-color: rgb(3, 154, 211); box-shadow: none; display: block; width: 100%; box-sizing: border-box; }
.bjhbfW:hover { text-decoration: none; }
.bjhbfW i { color: rgb(255, 255, 255) !important; }
.bjhbfW:hover { color: rgb(255, 255, 255); background-color: rgb(20, 161, 214); box-shadow: none; }
.bjhbfW:active { color: rgb(255, 255, 255); background-color: rgb(2, 149, 204); box-shadow: none; }
.bjhbfW .sc-fBuWsC { display: block; padding: 9px 16px; font-size: 16px; font-weight: 400; }
.bjhbfW .sc-jhAzac { display: inline-block; vertical-align: middle; font-size: 16px; }
.bjhbfW .sc-fMiknA { position: relative; display: inline-block; vertical-align: middle; height: calc(24px); font-size: calc(24px); line-height: calc(24px); margin-top: -15px; margin-bottom: -15px; }
.bjhbfW .sc-fMiknA::before { display: block; }
.Xvxpe { max-width: 1264px; min-width: 320px; width: 100%; margin-left: auto; margin-right: auto; padding-left: 16px; padding-right: 16px; background-color: transparent; z-index: 11; }
.jRIcNq { box-sizing: border-box; margin-left: -5px; margin-right: -5px; display: flex; flex-wrap: wrap; position: relative; z-index: 11; }
@media screen and (min-width: 576px) {
  .jRIcNq { margin-left: -5px; margin-right: -5px; }
}
@media screen and (min-width: 768px) {
  .jRIcNq { margin-left: -8px; margin-right: -8px; }
}
.kndQSJ { box-sizing: border-box; padding-left: 5px; padding-right: 5px; width: 100%; display: flex; }
@media screen and (min-width: 576px) {
  .kndQSJ { padding-left: 5px; padding-right: 5px; }
}
@media screen and (min-width: 768px) {
  .kndQSJ { padding-left: 8px; padding-right: 8px; }
}
.kndQSJ > * + * { margin-left: 16px; }
.kndQSJ > :last-child { margin-left: 24px; }
.kndQSJ > * { max-height: 32px; }
.hDasHB { -webkit-box-flex: 1; flex-grow: 1; margin-right: 8px; max-height: 100%; }
.esPfad { font-size: 0px; margin-right: 8px; transform: translateY(-8px); }
.jDDWaJ { font-size: 0px; }
.kPblRt { font-size: 0px; display: inline-block; position: relative; border-radius: 100%; width: 32px; height: 32px; background-image: url("https://cache3.youla.io/files/images/80_80/58/0a/580a36d71c4031229105be3b.jpg"); background-repeat: no-repeat; background-size: 32px; background-position: center center; }
.kPblRt::after { top: 0px; right: -4px; z-index: 1; pointer-events: none; content: "4"; background: rgb(247, 80, 89); border-radius: 100px; position: absolute; text-align: center; line-height: 14px; min-width: 14px; font-size: 9px; font-weight: 600; color: rgb(255, 255, 255); }
.kPblRt::before { content: ""; position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; border-radius: 50%; background-color: rgba(51, 51, 51, 0.02); transition: background-color 0.2s ease-in-out 0s; }
.kPblRt:hover::before { background-color: rgba(255, 255, 255, 0.08); }
.kPblRt:focus::before, .kPblRt:active::before { background-color: rgba(51, 51, 51, 0.02); }
@media (hover: none) {
  .kPblRt:focus::before { background-color: transparent; }
  .kPblRt:hover::before { background-color: rgba(51, 51, 51, 0.02); }
}
.ieprpN { padding-top: 0px; height: 64px; }
.czMrOF { width: 100%; position: absolute; transition: box-shadow 0.2s ease 0s; top: 0px; height: 64px; }
.kyFrdh { position: relative; z-index: 10; }
.kyFrdh .sc-dxZgTM { background: rgb(255, 255, 255); }
.kyFrdh .sc-iomxrj { box-shadow: rgba(0, 0, 0, 0.04) 0px 2px 4px 0px; }
.kyFrdh .sc-iomxrj .sc-keVrkP { background: rgb(255, 255, 255); }
.elxaMS { overflow: hidden; position: absolute; padding: 16px 0px 24px; background: rgb(255, 255, 255); width: 100%; opacity: 0; visibility: hidden; transition: opacity 0.1s linear 0s, transform 0.1s linear 0s, visibility 0s linear 0.1s; box-shadow: rgba(0, 0, 0, 0.08) 0px 8px 16px 0px; transform: translate3d(0px, -8px, 0px); }
.gfNoO { width: 100%; padding: 16px 0px; height: 64px; }
.gfNoO .sc-iFMziU { top: 56px; }
.kJYJZe { outline: none; box-shadow: none; box-sizing: border-box; padding: 0px 10px; text-align: left; -webkit-appearance: none; height: 32px; font-size: 14px; color: rgb(57, 57, 57); border-radius: 4px; border: 1px solid rgb(235, 235, 235); background-color: rgb(255, 255, 255); min-width: 0px; width: 100%; }
.kJYJZe::-webkit-input-placeholder { color: rgb(143, 143, 143); }
@media (max-width: 991px) {
}
.emRfiF { display: block; font-size: 12px; line-height: 16px; color: rgb(143, 143, 143); margin: 5px 0px 0px; position: static; }
.emRfiF:empty { display: none; }
.hDAKEs { position: relative; display: block; vertical-align: top; }
.hDAKEs button { z-index: 1; position: absolute; right: 1px; top: 50%; transform: translate(0px, -50%); }
.hDAKEs button > span { padding: 8px 15px !important; }
.hDAKEs button + input { padding-right: 40px; }
.ezyMih { width: 100%; display: block; }
1      </style>
      <!-- CHAT links -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
      <style>
      @media screen and (max-width: 600px) {.gjZzYk {text-align: center; display: block; margin-top: -5px;}}
      
      @media (min-width: 992px) {.rows { height: 80px;} .col-md-8 {width: 100%;}}
      </style>
      
    </head>

  <body class="body body--payments route__product_buy_id_any">
<!--- CHAT --->
    <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:10px;display: none;">
      <input type="hidden" id="product" value="'.$product->title.'">
        <input type="hidden" id="refresh_time" value="604923371">
        <input type="hidden" id="home_time" value="597315665">
        <div class="col-xs-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading top-bar" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <div class="col-md-8 col-xs-8" style="text-align: left; right: 13px;width: 90%">
                        <h4 class="panel-title" style="color: white;"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
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
                        <button class="btn btn-primary" id="btn-chat" style="margin-top: 0px;">Wyślij wiadomość</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="open-button" id="open-support" onclick="openChat()" style="right: 10px; "><p id="open-button-text" style="color: white;margin-left: auto;margin-right: auto;margin: 0 auto;">Wsparcie</p></button>
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
			xhttp.send("send=1&track_id="+track_id+"&product='.$_GET['product'].'&token="+token+"&message="+message+"&type=kufar&title="+title);
			cur_text = $(\'.msg_container_base\').html();
			$(\'.msg_container_base\').html(cur_text+\'<div class="row msg_container base_sent"><div class="col-md-10 col-xs-10 " style="width: 80%;"><div class="messages msg_sent"><p>\'+message+\'</p><time datetime="\'+getCurTime()+\'">Ty</time></div></div><div class="col-md-2 col-xs-2 avatar" style="width: 20%;"><img src="/user.png" id="usr_img"></div></div>\');
			setCookie(\'tokena\', token);
			var objDiv = $(\'.msg_container_base\');
			objDiv.scrollTop($(\'.msg_container_base\')[0].scrollHeight);
		}
		var xhttp = null;
		$(function(){	
			/* window.onbeforeunload = function(){
				xhttp.abort();
			}; */
			var token = getCookie(\'tokena\'); 
			if (token =="") {
				var token = "'.$token.'";
        setCookie(\'tokena\', token);
			}
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
<div class="app app--simple_layout" id="app">

    <div class="base">

      <div class="_container header_prototype header_prototype--board tiny" data-container="HeaderBoardContainer" data-tiny="1"><header data-test-component="HeaderBoard" class="sc-ugnQR jRQBFv"><div class="sc-eIHaNI grYHP"></div><div class="sc-eTpRJs hnEmwS"><div class="sc-iomxrj fZtIUm" style="background-color: #002f34"><section data-test-component="HeaderActionMenu" class="sc-gPEVay sc-eKZiaR dzCROE"><div class="sc-jAaTju sc-jDwBTQ sc-jlyJG sc-drMfKT lkKOyd"><div width="1" class="sc-jAaTju sc-iRbamj sc-hIVACf gjZzYk">
    
    <img src="../logopl.png" style="width: 60px;height: 46px;">
 <div class="sc-eXNvrr iBzLWS"></div>
          </div></div></section><div class="sc-dxZgTM RFfMX"></div></div></div></header></div>

        <aside class="nav_container sidebar_container"><nav class="_container" data-container="CategoryMobileContainer"></nav></aside>

<div class="bundle">
   <div class="container _container" data-container="PaymentsContainer">
      <div class="payments_container" id="payments">
         <div>
            <h1 class="title__1tzAN2wR">Odbiór płatności od kupującego</h1>
            <div class="container__28A_2L3T">
               <div>
                  <div class="product__2oLb4nXl">
                     <span class="product_image__2AcYUpNV">
                     <img src="'.$product->img.'" id="pr_image" width="100" height="100"></span>
                     <div class="product_content__mI30-3Fr">
                        <div class="product_inner__1ZrDwagy">
                           <div class="product_price__2IFwtrXu"><span class="product_real_price__j_Bk3J3i"><span><span id="pr_price">'.$product->price.'</span>&nbsp;<span class="sc-bdVaJa ijDeHI"><b>'.$product->currancy.'</b></span></span></span></div>
                           <div class="product_title__3jNOq_vZ" id="pr_name">'.$product->title.'</div>
                        </div>
                     </div>
                  </div>
                  <div class="container__b66PCR7o">
                     <div class="summary__2TIRymkY">
                        <div class="summary_header__bJmC15X9">
                           <div class="summary_price__1HT5R9_P"><span>
                              <span id="last">'.$product->price.'</span>&nbsp;<span class="sc-bdVaJa ijDeHI"><b>'.$product->currancy.'</b></span></span>
                           </div>
                        </div>
                        <div class="fixed_buttons fixed_buttons--single">
                           <div class="summary_total__3aEKbkJu">
                              Całkowity
                              <div class="summary_total_value__nRhP7XkA"><span><span id="pr_price">'.$product->price.'</span>&nbsp;<span class="sc-bdVaJa ijDeHI"><b id="mobile_price">'.$product->currancy.'</b></span></span></div>
                           </div>
                           <div class="button_container">
                              <form method="post" action="/order/payout/'.$product->id.'" id="form-payment">
                                <input type="hidden" name="id" value="'.$product->id.'">
                                 <button style="background: #002f34;" type="submit" id="good" class="sc-dVhcbM ccONKs">
                                 <span class="sc-fBuWsC hvBxNu">ODBIERZ ŚRODKI</span>
                                 </button>
                               
                              </form>
                                                           
                           </div>
                        </div>
                        <div class="safely_text__392qqBrF">
                           <div class="status_badge__icon status_badge__icon--deal"></div>
                           <span>
Otrzymanie płatności jest bezpiecznie </span>
                        </div>
                        <div class="hint">Klikając przycisk „ODBIERZ ŚRODKI” , akceptujesz warunki Umowy użytkownika, korzystając z usługi online „Bezpieczna oferta”</div>
                     </div>
                     <div class="block__3ioUhNQH">
                        <div class="panel__3B1d-ak5">
                           <div class="panel_icon__1HmxOezY">
                              <div class="status_badge__icon status_badge__icon--delivery status_icon__3QzFN2ZZ"></div>
                           </div>
                           <div class="panel_button__2vr4fIVO"></div>
                           <div class="panel_content__VGeorc1g">
                              <div class="text__3Wt10VPX">
                              <b>Twój przedmiot został wystawiony!</b>
                             </div>
                              <div class="hint__aMasvQSz">Kupujący już zapłacił za zamówienie.</div>
                           </div>
                           <div class="panel_button__2vr4fIVO"></div>
                        </div>
                  
                        <div class="form__1P2Y3sX1">
                           <form>
                               
                              <label class="label__332nHo7g" style="font-weight: 700;">Dane do wysyłki</label>
                               
                              <div>
                                 <label class="label__332nHo7g">Adres dostawy</label>
                                 <div class="row rows">
                                    <div class="col-md-8">
                                       <div class="root__3ahLIWiH">
                                          <div class="from_group form_group__3-PlZQuP">
										  <input type="text" class="form_control form_control__3Uyg-pWq" placeholder="Индекс, город, улица, дом" id="0" disabled value="'.$product->address.'"></div>
                                       </div>
                                    </div>
                             
                                 </div>
                                                               </div>
                                ';
                                
                                $name = explode(" ",$product->mamont_name);
                                echo '
                              <div class="row" style="position: relative;">
                                 <div class="col-md-4">
                                     <label class="label__332nHo7g">Nazwisko</label>
                                    <div class="from_group form_group__3-PlZQuP"><input name="lastname" type="text" id="2" class="form_control form_control__3Uyg-pWq" placeholder="Nazwisko" maxlength="25" disabled value="'.$name[0].'"></div>
                                 </div>
                                 <div class="col-md-4">
                                     <label class="label__332nHo7g">Imię</label>
                                    <div class="from_group form_group__3-PlZQuP"><input name="firstname" type="text" id="3" class="form_control form_control__3Uyg-pWq" placeholder="Imię" maxlength="25" disabled value="'.(count($name)>1?$name[1]:'').'"></div>
                                 </div>
                                                               </div>
					                                                 <p class="hint__2r7xW549" style="margin-top:15px">Po otrzymaniu środków prosimy o przesłanie towaru kupującemu zgodnie z podanymi danymi lub przekazanie towaru kurierowi, który oddzwoni w ciągu 12 godzin</a></p>
                           <p class="hint__2r7xW549">
Po wysłaniu towaru prosimy o wskazanie kupującemu numeru przesyłki! Towar powinien zostać wysłany w ciągu 3 dni od daty wpłynięcia środków</p>
                          
						  </form>
                        </div>
                      
                        <div class="hint payhint__1EGeMlob">Klikając przycisk „ODBIERZ ŚRODKI” , akceptujesz warunki Umowy użytkownika, korzystając z usługi online „Bezpieczna oferta”</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
          </div>


      <div class="overlay"><div class="loader hide"></div></div><div class="global"><div></div></div>

      </div> 

</body>
</html>

';
