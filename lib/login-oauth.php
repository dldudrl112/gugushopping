<?php
if(!defined('_TUBEWEB_')) exit;

function get_login_oauth($type, $img='') {

	if(!$type) return;

	if(!defined('APMS_SNS_LOGIN_OAUTH')) {
		define('APMS_SNS_LOGIN_OAUTH', true);

		global $url, $urlencode;

		$return_url = (isset($url) && $url) ? $url : $urlencode;

		echo '<script>'.PHP_EOL;
		echo 'function login_oauth(type,ww,wh) {'.PHP_EOL;
		echo 'var url = "'.TB_PLUGIN_URL.'/login-oauth/login_with_" + type + ".php";'.PHP_EOL;
		echo 'var opt = "width=" + ww + ",height=" + wh + ",left=0,top=0,scrollbars=1,toolbars=no,resizable=yes";'.PHP_EOL;
		echo 'window.open(url,type,opt);'.PHP_EOL;
		echo '}'.PHP_EOL;
		echo '</script>'.PHP_EOL;
		echo '<input type="hidden" name="slr_url" value="'.$return_url.'">'.PHP_EOL;
	}

	// Size
	switch($type) {
		case 'facebook'	: $ww = 1024; $wh = 640; break;
		case 'twitter'	: $ww = 600; $wh = 600; break;
		case 'google'	: $ww = 460; $wh = 640; break;
		case 'naver'	: $ww = 460; $wh = 517; break;
		case 'kakao'	: $ww = 480; $wh = 680;	break;
		default			: $ww = 600; $wh = 600; break;
	}

	$str = "login_oauth('".$type."','".$ww."','".$wh."');";
	if($img) {
		if($img == '1') { // Link
			switch($type) {
				case 'google':
					$str = '<a href="javascript:'.$str.'" class="bt_google"><img src="'.TB_IMG_URL.'/m-sns-login04.png" alt="Sign in with '.$type.'"></a>'.PHP_EOL;
					break;
				case 'facebook':
					$str = '<a href="javascript:'.$str.'" class="bt_face"><img src="'.TB_IMG_URL.'/m-sns-login03.png" alt="Sign in with '.$type.'"></a>'.PHP_EOL;
					break;
				case 'naver':
					$str = '<a href="javascript:'.$str.'" class="bt_naver"><img src="/img/sns/naver_login_m.png" alt="Sign in with '.$type.'"></a>'.PHP_EOL;
					break;
				case 'kakao':
					$str = '<a href="javascript:'.$str.'"  target="_self" class="bt_kakao"><img src="/img/sns/kakao_login_m.png" alt="Sign in with '.$type.'"></a>'.PHP_EOL;
					break;
			}
		} else {
			$str = '<a href="javascript:'.$str.'"><img src="'.$img.'" alt="Sign in with '.$type.'"></a>'.PHP_EOL;
		}
	} else {
		$img = TB_PLUGIN_URL.'/login-oauth/img/'.$type.'.png';
		$str = '<a href="javascript:'.$str.'"><img src="'.$img.'" alt="Sign in with '.$type.'"></a>'.PHP_EOL;
	}

    return $str;
}
?>
