<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if (!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if (!isset($tb['title'])) {
	$tb['title'] = get_head_title('head_title', $pt_id);
	$tb_head_title = $tb['title'];
} else {
	$tb_head_title = $tb['title']; // 상태바에 표시될 제목
	$tb_head_title .= " | " . get_head_title('head_title', $pt_id);
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$tb['lo_location'] = addslashes($tb['title']);
if (!$tb['lo_location'])
	$tb['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$tb['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if (strstr($tb['lo_url'], '/' . TB_ADMIN_DIR . '/') || is_admin()) $tb['lo_url'] = '';

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
	<?php
	include_once(TB_LIB_PATH . '/seometa.lib.php');

	if ($config['add_meta'])
		echo $config['add_meta'] . PHP_EOL;
	?>
	<title><?php echo $tb_head_title; ?></title>
	<link rel="stylesheet" href="<?php echo TB_CSS_URL; ?>/default.css?ver=<?php echo TB_CSS_VER; ?>">
	<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/style.css?ver=<?php echo TB_CSS_VER; ?>">
	<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/resposive.css?ver=<?php echo TB_CSS_VER; ?>">
	<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/slick.css?ver=<?php echo TB_CSS_VER; ?>">
	<link rel="stylesheet" href="<?php echo TB_THEME_URL; ?>/reset.css?ver=<?php echo TB_CSS_VER; ?>">
	<?php if ($ico = display_logo_url('favicon_ico')) { // 파비콘 
	?>
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
	<?php if ($config['mouseblock_yes']) { // 마우스 우클릭 방지 
	?>
		<script>
			$(document).ready(function() {
				$(document).bind("contextmenu", function(e) {
					return false;
				});
			});
			$(document).bind('selectstart', function() {
				return false;
			});
			$(document).bind('dragstart', function() {
				return false;
			});
		</script>
	<?php } ?>
	<?php
	if ($config['head_script']) { // head 내부태그
		echo $config['head_script'] . PHP_EOL;
	}
	?>
</head>
<body<?php echo isset($tb['body_script']) ? $tb['body_script'] : ''; ?>>


	<? if (defined('_INDEX_')) { // index에서만 실행
		include_once(TB_LIB_PATH . '/popup.inc.php'); // 팝업레이어
	}
	?>
	<!-- Header Area Start -->
	<header id="header">
		<div class="gnb">
			<div class="main-container">
				<ul>
					<?php
					$tnb = array();

					if ($is_admin)
						$tnb[] = '<li><a href="' . $is_admin . '" target="_blank" style="padding-right: 20px;">관리자</a></li>';
					if ($member['id']) {
						$tnb[] = '<li><a href="' . TB_BBS_URL . '/logout.php">로그아웃</a></li>';
						$tnb[] = '<li><a href="' . TB_SHOP_URL . '/mypage.php">마이페이지</a></li>';
					} else {
						$tnb[] = '<li><a href="' . TB_BBS_URL . '/login.php?url=' . $urlencode . '">로그인</a></li>';
						$tnb[] = '<li><a href="' . TB_BBS_URL . '/login.php?regi=1">회원가입</a></li>';
					}

					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
		</div>
		<div class="header side_header">
			<div class="main-container">
				<div class="logo">
					<h1><a href="/index.php" class="ir_pm">구구</a></h1>
				</div>
				<form class="search_form" name="fsearch" id="fsearch" method="post" action="/" autocomplete="off">
					<input type="hidden" name="ca_id" value="">
					<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
					<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="구독하고 싶은 상품을 검색해 보세요." value="<?= $_REQUEST["ss_tx"] ?>">
					<button type="submit"><img src="<?php echo TB_IMG_URL; ?>/icon_search.png" alt="search_icon"></button>
				</form>
				<ul class="header-right">
					<?php
					$tnb = array();
					$tnb[] = '<li><a href="' . TB_SHOP_URL . '/myinquiry.php"><img src="' . TB_IMG_URL . '/icon_headerRight01_new.png" alt="내구독"><span>내구독</span></a></li>';
					$tnb[] = '<li><a href="' . TB_SHOP_URL . '/mypage.php"><img src="' . TB_IMG_URL . '/icon_headerRight02_new.png" alt="내정보"><span>내정보</span></a></li>';
					$tnb[] = '<li><a href="/index.php"><img src="' . TB_IMG_URL . '/icon_headerRight03_new.png" alt="구구홈"><span>구구홈</span></a></li>';
					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
			<a href="<?php echo TB_BBS_URL; ?>/alarm.php" class="alarm-btn mob1024"><img src="<?php echo TB_IMG_URL; ?>/m-main-alarm.png" alt="alarm"></a>
		</div>
	</header><!-- Header Area End -->


	<div class="header_mobile">
		<div class="sub_visual login_img">
			<h3 class="back mob"><a href="javascript:history.back()"><img src="/img/header/back.png" alt="뒤로가기"><span>로그인</span></a></h3>
			<div style="border: 1px solid #f9f9f9;"></div>
			<div class="box">
				<h2 class="sub-vis-tit">로그인</h2>
				<!-- 구독없을때 -->
			</div>
		</div>
	</div>

	<?php
	$regi  = $_GET["regi"];
	?>
	<form name="flogin" action="<?php echo $login_action_url; ?>" method="post">
		<input type="hidden" name="url" value="<?php echo $login_url; ?>">
		<div class="sub-page2 sub06-01" id="section">
			<div class="container">
				<div class="sub-visual-login">
					<!-- <h3 class="back mob"><a href="javascript:history.back()"><img src="../img/m-back.png" alt="뒤로가기"><span>로그인</span></a></h3>                           -->
					<div class="box box_login">
						<h2 class="sub-vis-tit-login">아직 로그인을 하지 않으셨네요.</h2>
						<p class="sub-vis-con-login">로그인 후 이용해 주세요.</p>
					</div>
				</div>
				<!-- sub-visual -->

				<div class="sub06-01-content-wrap">
					<ul class="login-tab cf">
						<li class="on">로그인</li>
						<li>회원가입</li>
					</ul>
					<div class="login-box login-box01">
						<ul>
							<li>
								<input type="email" name="mb_id" id="login_id" placeholder="이메일" onkeydown="javascipt:if(event.keyCode == 13) flogin_submit(document.flogin);">
							</li>
							<li>
								<input type="password" name="mb_password" id="login_pw" placeholder="비밀번호" maxlength="20" minlength="10" onkeydown="javascipt:if(event.keyCode == 13) flogin_submit(document.flogin);">
							</li>
						</ul>
						<div class="id-check-box">
							<input type="radio" name="auto_login" id="id-check">
							<label for="id-check">아이디 저장</label>
						</div>
						<div class="btn-box">
							<div class="btn-box-1">
								<a href="javascript:void(0)" onclick="flogin_submit(document.flogin);" class="btn01">로그인</a>
							</div>

						</div>
						<!-- <div class="search-id">
							<p>고객님에게 더 나은 서비스를 제공하기 위해 일시적으로 간편로그인이 중단되었습니다.<br>
								불편을 끼쳐드려 죄송합니다.<br><br>
								관련 문의는 카카오채널 '구구프랜드'로 문의주시면 빠르게 도와드리도록 하겠습니다.
							</p>
						</div> -->

						<div class="sns-login">
							<!-- <span>간편 로그인</span>  -->
							<div class="box">
								<?php if ($default['de_kakao_rest_apikey']) { ?>
									<?php echo get_login_oauth('kakao', 1); 
									?>
								<?php } ?>
								<?php if ($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
									<?php echo get_login_oauth('naver', 1); 
									?>
								<?php } ?>
								<?php if ($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
									<?php //echo get_login_oauth('facebook', 1); 
									?>
								<?php } ?>
								<?php //echo get_login_oauth('google', 1); 
								?>
							</div>
						</div>
						<div class="search-id">
							<span><a href="<?php echo TB_BBS_URL; ?>/find_id.php">아이디 찾기</a></span>
							<span><a href="<?php echo TB_BBS_URL; ?>/find_id.php?pwd=1">비밀번호 찾기</a></span>
						</div>
					</div>

					<div class="login-box login-box02">
						<div class="btn-box email-box">
							<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn01 email-join">이메일로 회원가입</a>
						</div>
						<!-- <div class="search-id">
							<p>고객님에게 더 나은 서비스를 제공하기 위해 일시적으로 간편로그인이 중단되었습니다.<br>
								불편을 끼쳐드려 죄송합니다.<br><br>
								관련 문의는 카카오채널 '구구프랜드'로 문의주시면 빠르게 도와드리도록 하겠습니다.
							</p>
						</div> -->
						<div class="sns-login">
							<!-- <span>간편 로그인</span> -->
							<div class="box">
								<?php if ($default['de_kakao_rest_apikey']) { ?>
									<?php echo get_login_oauth('kakao', 1); 
									?>
								<?php } ?>
								<?php if ($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
									<?php echo get_login_oauth('naver', 1); 
									?>
								<?php } ?>
								<?php if ($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
									<?php //echo get_login_oauth('facebook', 1); 
									?>
								<?php } ?>
								<?php //echo get_login_oauth('google', 1); 
								?>
							</div>
						</div>
					</div>

					<div class="detail-banner">
						<img src="/img/login-bottom-banner.jpg" alt="배너">
					</div>
				</div>
			</div>
		</div>
	</form>
	<script>
		$(function() {
			$("#id-check").click(function() {
				if (this.checked) {
					this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
					if (!this.checked) {
						$("input:radio[name='auto_login']").prop('checked', false);
					}
				}
			});
		});


		function flogin_no_act() {
			$(".sub_title04 h2.no_login").hide();
			$(".sub_title04 h2.mis_type").show();
		}

		function flogin_submit(f) {
			if (!f.mb_id.value) {
				alert('아이디를 입력하세요.');
				f.mb_id.focus();
				return;
			}
			if (!f.mb_password.value) {
				alert('비밀번호를 입력하세요.');
				f.mb_password.focus();
				return;
			}

			$.post(
				tb_bbs_url + "/ajax.login_check.php", {
					mb_id: f.mb_id.value,
					mb_password: f.mb_password.value
				},
				function(data) {

					if (data == "N") {
						flogin_no_act();
						return;
					} else {
						f.submit();
					}
				}
			);

			return true;
		}

		function fguest_submit(f) {
			if (!f.od_id.value) {
				alert('주문번호를 입력하세요.');
				f.od_id.focus();
				return false;
			}
			if (!f.od_pwd.value) {
				alert('비밀번호를 입력해주세요.');
				f.od_pwd.focus();
				return false;
			}

			return true;
		}

		$(document).ready(function() {
			var regi = '<?= $regi ?>';

			if (regi == 1) {
				$('.login-tab li:eq(1)').click();
			}
			$(".login_tab>li:eq(0)").addClass('active');
			$("#login_fld").addClass('active');

			$(".login_tab>li").click(function() {
				var activeTab = $(this).attr('data-tab');
				$(".login_tab>li").removeClass('active');
				$(".login_wrap").removeClass('active');
				$(this).addClass('active');
				$("#" + activeTab).addClass('active');
			});
		});
	</script>