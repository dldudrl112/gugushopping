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
			<h3 class="back mob"><a href="javascript:history.back()"><img src="/img/header/back.png" alt="뒤로가기"><span>아이디/비밀번호 찾기</span></a></h3>
			<div style="border: 1px solid #f9f9f9;"></div>
			<div class="box">
				<h2 class="sub-vis-tit">아이디/비밀번호 찾기</h2>
				<!-- 구독없을때 -->
			</div>
		</div>
	</div>
	<?php
	$pwd = $_GET["pwd"];
	?>




	<div class="sub-page2 sub06-03" id="section">
		<div class="container">

			<div class="sub-visual-login">
				<div class="box box_login">
					<h2 class="sub-vis-tit-login">아이디/비밀번호를 찾기위해</h2>
					<p class="sub-vis-con-login">이메일 또는 휴대폰 인증을 해주세요.</p>
				</div>
			</div><!-- sub-visual -->

			<div class="sub06-03-content-wrap">
				<ul class="login-tab cf">
					<li class="on">아이디(이메일)찾기</li>
					<li>비밀번호 찾기</li>
				</ul>

				<div class="find-info box01">
					<form name="form_data" action="<?php echo $form_action_url; ?>" method="post" target="popwin">
						<input type="hidden" name="phone_check" value="0">
						<dl>
							<dt>휴대폰 인증</dt>
							<dd>
								<div>
									<select name="agency" id="agency">
										<option value="skt">SKT</option>
										<option value="kt">KT</option>
										<option value="lg">LGU+</option>
									</select>
									<input type="number" name="phone" id="phone2">
								</div>
								<button type="button" onclick="form_submit(document.form_data);">확인</button>
							</dd>
						</dl>
					</form>
				</div>


				<div class="find-info box02">
					<form name="form_pass_data" action="<?php echo $form_pass_action_url; ?>" method="post" target="popwin2">
						<dl class="mb">
							<dt>아이디<span class="sub">(이메일)</span></dt>
							<dd><input type="text" name="id_email" required email id="id-txt"></dd>
						</dl>
						<dl>
							<dt>휴대폰 인증</dt>
							<dd>
								<div>
									<select name="news-agency2" id="news-agency2">
										<option value="skt">SKT</option>
										<option value="kt">KT</option>
										<option value="lg">LGU+</option>
									</select>
									<input type="number" name="phone1" id="phone3">
								</div>
								<button type="button" onclick="form_pass_submit(document.form_pass_data);">확인</button>
							</dd>
						</dl>
					</form>
				</div>

			</div>
		</div>
	</div>
	<script>
		function form_check(check_type) {

			var f = document.fregisterform;
			var msg = "";


			//전화번호 체크
			if (check_type == "phone") {

				if ($.trim($("input[name=phone1]").val()) == "" || $.trim($("input[name=phone2]").val()) == "" || $.trim($("input[name=phone3]").val()) == "") {
					alert("회원가입시 입력하셨던 연락처 정보를 입력해주세요.");
					return;
				}


				var mb_phone = $.trim($("input[name=phone1]").val()) + "-" + $.trim($("input[name=phone2]").val()) + "-" + $.trim($("input[name=phone3]").val());

				$.post(
					tb_bbs_url + "/ajax.mb_phone_check.php", {
						mb_phone: mb_phone
					},
					function(data) {
						if (data == "N") {
							alert("휴대폰 인증 정보를 확인하였습니다.");
							$("input[name=phone_check]").val(1);
							return;
						} else {
							alert("회원가입시 입력하셨던 연락처 정보를 입력해주세요.");
							return;
						}
					}
				);
			}
		}

		function form_submit(f) {

			if ($.trim($("#phone2").val()) == "") {
				alert("회원가입시 입력하셨던 연락처 정보를 입력해주세요.");
				return;
			}

			var mb_phone = $.trim($("#phone2").val());

			$.post(
				tb_bbs_url + "/ajax.mb_phone_check.php", {
					mb_phone: mb_phone
				},
				function(data) {
					if (data == "N") {

						win_open('about:blank', "popwin", "375", "580", "no");
						f.submit();
					} else {
						alert("회원가입시 입력하셨던 연락처 정보를 입력해주세요.");
						return;
					}
				}
			);
		}


		function form_pass_submit(f) {

			if ($.trim($("input[name=id_email]").val()) == "") {
				alert("아이디(이메일)을 입력해주세요.");
				return;
			}

			if ($.trim($("#phone3").val()) == "") {
				alert("회원가입시 입력하셨던 연락처 정보를 입력해주세요.");
				return;
			}

			var mb_phone = $.trim($("#phone3").val());

			$.post(
				tb_bbs_url + "/ajax.mb_phone_check.php", {
					mb_phone: mb_phone
				},
				function(data) {
					if (data == "N") {

						win_open('about:blank', "popwin2", "375", "440", "no");
						f.submit();

					} else {
						alert("회원가입시 입력하셨던 연락처 정보를 입력해주세요.");
						return;
					}
				}
			);
		}


		$(document).ready(function() {
			var pwd = '<?= $pwd ?>';
			if (pwd == 1) {
				$('.login-tab li:eq(1)').click();
			}
		});
	</script>