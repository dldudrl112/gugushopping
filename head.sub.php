<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if(!isset($tb['title'])) {
    $tb['title'] = get_head_title('head_title', $pt_id);
    $tb_head_title = $tb['title'];
}
else {
    $tb_head_title = $tb['title']; // 상태바에 표시될 제목
    $tb_head_title .= " | ".get_head_title('head_title', $pt_id);
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$tb['lo_location'] = addslashes($tb['title']);
if(!$tb['lo_location'])
    $tb['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$tb['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if(strstr($tb['lo_url'], '/'.TB_ADMIN_DIR.'/') || is_admin()) $tb['lo_url'] = '';

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Hello, I'm GUGU! 구독으로 시작하는 더 편리하고, 늘 새로운 일상">
<?php
include_once(TB_LIB_PATH.'/seometa.lib.php');

if($config['add_meta'])
    echo $config['add_meta'].PHP_EOL;
?>

<meta name="naver-site-verification" content="a79b974ac7474e4337a378648db679146512b7e5" />


<title><?php echo $tb_head_title; ?></title>
<link rel="stylesheet" href="<?php echo TB_CSS_URL; ?>/default.css?ver=<?php echo TB_CSS_VER;?>">
<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/style.css?ver=<?php echo TB_CSS_VER;?>">
<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/resposive.css?ver=<?php echo TB_CSS_VER;?>">
<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/slick.css?ver=<?php echo TB_CSS_VER;?>">
<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/reset.css?ver=<?php echo TB_CSS_VER;?>">
<?php if($ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $ico; ?>" type="image/x-icon">
<?php } ?>
<!-- Font Noto Sans -->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">  
<script>
	var tb_url = "<?php echo TB_URL; ?>";
	var tb_bbs_url = "<?php echo TB_BBS_URL; ?>";
	var tb_shop_url = "<?php echo TB_SHOP_URL; ?>";
	var tb_mobile_url = "<?php echo TB_MURL; ?>";
	var tb_mobile_bbs_url = "<?php echo TB_MBBS_URL; ?>";
	var tb_mobile_shop_url = "<?php echo TB_MSHOP_URL; ?>";
	var tb_is_member = "<?php echo $is_member; ?>";
	var tb_is_mobile = "<?php echo TB_IS_MOBILE; ?>";
	var tb_cookie_domain = "<?php echo TB_COOKIE_DOMAIN; ?>";
</script>
<script src="<?php echo TB_JS_URL; ?>/jquery-3.3.1.js"></script>
<script src="<?php echo TB_JS_URL; ?>/slick.min.js"></script>
<script src="<?php echo TB_JS_URL; ?>/import.js"></script>
<script src="<?php echo TB_JS_URL; ?>/common.js"></script>
<script src="<?php echo TB_JS_URL; ?>/script.js"></script>
<?php if($config['mouseblock_yes']) { // 마우스 우클릭 방지 ?>
<script>
$(document).ready(function(){
	$(document).bind("contextmenu", function(e) {
		return false;
	});
});
$(document).bind('selectstart',function() {return false;});
$(document).bind('dragstart',function(){return false;});
</script>
<?php } ?>
<?php
if($config['head_script']) { // head 내부태그
    echo $config['head_script'].PHP_EOL;
}
?>
</head>
<body<?php echo isset($tb['body_script']) ? $tb['body_script'] : ''; ?>>
