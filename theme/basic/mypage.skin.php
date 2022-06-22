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
		<div class="sub_visual">
			<h3 class="back mob"><a href="javascript:history.back()"><img src="/img/header/back.png" alt="뒤로가기"><span>내정보 관리</span></a></h3>
			<div style="border: 1px solid #f9f9f9;"></div>
			<div class="box">
				<h2 class="sub-vis-tit">내정보 관리</h2>
				<!-- 구독없을때 -->
			</div>
		</div>
	</div>

	<?php $od	=	get_order($_GET['od_id']); ?>
	<? $arr_b_cellphone		=	explode("|", $od["b_cellphone"]); ?>
	<div class="sub-page mypage1 my-page sub08-05" id="section">
		<div class="container">
			<!-- Mem_info Area Start -->
			<?
			//  include_once(TB_THEME_PATH.'/aside_mem_info.skin.php');
			?>
			<!-- Mem_info Area End -->
			<div class="sub08-05-content-wrap content-wrap">
				<!-- Left_box Area Start -->
				<? include_once(TB_THEME_PATH . '/aside_my.skin.php'); ?>
				<!-- Left_box Area End -->

                <?php if($default['de_certify_use'] || isset($_GET['isDev'])) { // 실명인증 사용시 ?>
                <form name="fregister" id="fregister" method="post">
                        <input type="hidden" name="m" value="checkplusSerivce">
                        <input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
                        <input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
                        <input type="hidden" name="param_r1" value="">
                        <input type="hidden" name="param_r2" value="">
                        <input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
                </form>
                    <?php

                    //sql_query("update shop_member set checked_birthday = '' where id= 'fromkt@daum.net' ");
                    ?>
                <?php } ?>
				<!---------------------내정보관리--------------------->
				<div class="content-box">
					<h3><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기">내정보 관리</a></h3>
					<form name="fmemberform" id="fmemberform" method="post" action="<?= $form_action_url ?>" enctype="MULTIPART/FORM-DATA">
						<input type="hidden" name="token" value="<?php echo $token; ?>">
						<input type="hidden" name="id" value="<?= $member['id'] ?>">
						<input type="hidden" name="phone_flag" value="0">
						<input type="hidden" name="check_number" value="">

                        <?php if($default['de_certify_use'] || isset($_GET['isDev'])) { // 실명인증 사용시 ?>
                            <input type="hidden" name="m" value="checkplusSerivce">
                            <input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
                            <input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
                            <input type="hidden" name="param_r1" value="">
                            <input type="hidden" name="param_r2" value="">
                            <input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
                        <?php } ?>

						<div class="top">
							<div class="img-box">
								<?php if ($member['mem_img']) { ?>
									<img src="<?php echo $upl_dir; ?>/<?= $member['mem_img'] ?>" alt="img">
								<?php } else { ?>
									<span class="mem_no_img"><img src="<?php echo TB_IMG_URL; ?>/no_img2.jpg" alt="img"></span>
								<?php } ?>
								<input type="file" name="mem_img" id="thumb-upload" onchange="checkImg(this);">
								<label for="thumb-upload"><img src="<?php echo TB_IMG_URL; ?>/mypage-setting.png" alt="설정"></label>
							</div>
							<div class="nick-box">
								<input type="text" name="nickname" value="<?= $member['nickname'] ?>">
								<a href="javascript:void(0)" class="input-delte-btn"><img src="<?php echo TB_IMG_URL; ?>/input-delete.png" alt="삭제"></a>
							</div>
						</div>
						<div class="mid">
							<ul>
								<li>
									<div class="subject">닉네임</div>
									<div class="con">
										<P><?= $member['name'] ?></P>
									</div>
								</li>
								<li class="email-line">
									<div class="subject">이메일</div>
									<div class="con">
										<!-- <div class="sns-icon"> -->
										<!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns01.png" alt="kakao"> -->
										<!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns02.png" alt="facebook"> -->
										<!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns03.png" alt="google"> -->
										<!-- <img src="<?php echo TB_IMG_URL; ?>/mypage_sns04.png" alt="naver"> -->
										<!-- </div> -->
										<p><?= $member['id'] ?></p>
									</div>
								</li>
								<li class="password-line">
									<div class="subject">비밀번호</div>
									<div class="con">
										<a href="javascript:void(0)" class="modify">변경</a>
									</div>
									<div class="fix-box">
										<input type="password" name="passwd" id="password" placeholder="10~20자 이내">
										<span class="subject">비밀번호 확인<span class="refer">(영문 숫자 조합 8자 이상)</span></span>
										<div>
											<input type="password" name="repasswd" id="password-confirm" placeholder="10~20자 이내">
											<a href="javascript:;" onclick="pass_change();" class="password-re">변경</a>
										</div>

									</div>
								</li>
								<li class="phone-line">
									<div class="subject">휴대폰 인증</div>
									<div class="con" style="padding-left: 14px;">
										<a href="javascript:void(0)" onclick="sms_send_check();" class="modify">변경</a>
									</div>
									<div class="fix-box">
										<span>
											<input type="number" name="phone1" id="phone1" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=3 value="<?= $phone_01 ?>" readonly class="input-col3">
											<input type="number" name="phone2" id="phone2" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?= $phone_02 ?>" readonly class="input-col3">
											<input type="number" name="phone3" id="phone3" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?= $phone_03 ?>" readonly class="input-col3">
											<a href="javascript:void(0)" onclick="sms_send_check();" class="re">재인증</a>
										</span>

										<span>
											<input type="number" name="certification" id="certification">
											<a href="javascript:void(0)" onclick="sms_check_confirm();" class="phone-confirm">인증</a>
										</span>
									</div>
								</li>
								<li class="phone-line">
									<div class="subject">기본 배송지</div>
									<div class="con" style="padding-left: 19px;">
										<p><?= $member["addr1"] ?></p>
										<!-- <a href="javascript:void(0)" onclick="sms_send_check();" class="modify">변경</a> -->
									</div>
									<!-- <div class="fix-box">
										<span>
											<input type="number" name="phone1" id="phone1" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=3 value="<?= $phone_01 ?>" readonly class="input-col3">
											<input type="number" name="phone2" id="phone2" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?= $phone_02 ?>" readonly class="input-col3">
											<input type="number" name="phone3" id="phone3" maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=4 value="<?= $phone_03 ?>" readonly class="input-col3">
											<a href="javascript:void(0)" onclick="sms_send_check();" class="re">재인증</a>
										</span>

										<span>
											<input type="number" name="certification" id="certification">
											<a href="javascript:void(0)" onclick="sms_check_confirm();" class="phone-confirm">인증</a>
										</span>
									</div> -->
								</li>
								<li class="birth-line" style="display: none;">
									<div class="subject">생년월일<i>(년/월/일)</i></div>
									<div class="con">
										<input type="number" name="birth_year" id="year" placeholder="년도" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?= $member["birth_year"] ?>" class="input-col3">
										<input type="number" name="birth_month" id="month" placeholder="월" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?= $member["birth_month"] ?>" class="input-col3">
										<input type="number" name="birth_day" id="day" placeholder="일" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" maxlength=2 value="<?= $member["birth_day"] ?>" class="input-col3">
									</div>
								</li>
                                <li class="adult">
                                    <div class="subject">성인인증</div>
                                    <div class="con">
                                        <!--<?php echo $member['checked_birthday'];?> <?php echo $member['confirmed_at'];?>--->
                                        <?php
                                            echo isAdultUser($member['checked_birthday']) ? "인증완료" : "<a href='#' id='checkAdult'>인증</a>";
                                        ?>
                                    </div>
                                </li>
							</ul>
                            <script>
                                var $checkAdult= $("#checkAdult");
                                if($checkAdult.length >0){
                                    $checkAdult.on("click", function(e){
                                        e.preventDefault();
                                        window.name ="Parent_window";
                                        var mode = 1;
                                        var f = document.fregister;

                                        switch(mode){
                                            case 1: //Mobile phone authentication
                                                window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
                                                f.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
                                                f.target = "popupChk";
                                                f.submit();
                                                break;
                                            case 0: //I-PIN authentication
                                                window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
                                                f.target = "popupIPIN2";
                                                f.action = "https://cert.vno.co.kr/ipin.cb";
                                                f.submit();
                                                break;
                                        }
                                    });
                                }

                            </script>
						</div>
						<div style="margin:0 -20px; box-sizing: border-box;border-bottom: 1px solid #f7f7f7;"></div>
						<div class="bottom">
							<div class="bottom-top">
								<div class="subject">마케팅 정보 수신동의</div>
								<div class="con">* 이벤트 및 혜택에 대한 정보를 받으실 수 있어요.</div>
							</div>

							<div class="bottom-mid">
								<div class="sms-agree01">
									<input type="checkbox" name="mailser" value="Y" <?php if ($member["mailser"] == "Y") echo "checked"; ?> id="sms-agree-btn01">
									<label for="sms-agree-btn01" class="">
										<span>메일 수신동의</span>
										<div class="sms-agree-btn <?php if ($member["mailser"] == "Y") echo "on"; ?>">
											<div class="circle"></div>
										</div>
									</label>
								</div>
								<div class="sms-agree02">
									<input type="checkbox" name="smsser" value="Y" <?php if ($member["smsser"] == "Y") echo "checked"; ?> id="sms-agree-btn02">
									<label for="sms-agree-btn02" class="">
										<span>SMS 수신동의</span>
										<div class="sms-agree-btn <?php if ($member["smsser"] == "Y") echo "on"; ?>">
											<div class="circle"></div>
										</div>
									</label>
								</div>
							</div>
							<div class="btn-box">
								<a href="javascript:member_edit_submit();" class="btn01">수정하기</a>
							</div>
							<div class="bottom-bot">
								<a href="<?php echo TB_BBS_URL; ?>/logout.php">로그아웃</a>
								<a href="<?php echo TB_BBS_URL; ?>/leave_form.php">회원탈퇴</a>
							</div>
						</div>
					</form>
				</div>
			</div><!-- content-wrap -->
		</div><!-- container -->
	</div><!-- section -->
	<script>
		function pass_change() {


			var chk_num = $("input[name=passwd]").val().search(/[0-9]/g);
			var chk_eng = $("input[name=passwd]").val().search(/[a-z]/ig);


			if (chk_num < 0 || chk_eng < 0) {
				alert("비밀번호는 숫자와 영문자 조합으로 8글자 이상 사용해야 합니다.");
				return false;
			}

			if ($("input[name=passwd]").val().length < 8) {
				alert("비밀번호를 8자 이상 입력해주세요.");
				return;
			}

			if ($("input[name=passwd]").val() == "") {
				alert("변경하실 비밀번호를 입력해주세요.");
				return;
			}

			if ($("input[name=repasswd]").val() == "") {
				alert("변경하실 비밀번호 확인을 위해 비밀번호를 재입력해주세요.");
				return;
			}

			if ($("input[name=passwd]").val() != $("input[name=repasswd]").val()) {
				alert("비밀번호가 일치하지 않습니다.");
				return;
			}

			// if ($("input[name=phone_flag]").val() != 1) {
			// 	alert("휴대폰 인증을 하셔야 합니다.");
			// 	return;
			// }

			var pass = $("input[name=repasswd]").val();

			$.post(
				tb_bbs_url + "/ajax_mb_pass_change.php", {
					passwd: pass
				},
				function(data) {

					if (data == "Y") {
						$("input[name=phone_flag]").val(0);
						$("input[name=check_number]").val("");
						alert("패스워드 변경이 정상적으로 처리되었습니다.");
						return;
					} else {
						alert("정보가 잘못되었거나 등록되지 않은 회원입니다.");
						return;
					}
				}
			);
		}

		function sms_send_check() {

			var recv_phone = $("input[name=phone1]").val() + "-" + $("input[name=phone2]").val() + "-" + $("input[name=phone3]").val();

			$.post(
				tb_bbs_url + "/ajax_sms_check.php", {
					check_type: 'send',
					recv_phone: recv_phone
				},
				function(data) {
					if (data == "N") {
						alert("연락처 정보가 잘못되었거나 등록되지 않은 회원입니다.");
						return;
					} else {
						if (data.length == 6) {
							$("input[name=check_number]").val(data);
						} else {
							alert("인증코드 발급에 실패하였습니다. 다시 시도해주세요.");
							return;
						}
					}
				}
			);
		}


		function sms_check_confirm() {

			var cert_code = $("input[name=certification]").val();

			if (cert_code == "") {
				alert("전송받은 인증번호를 입력해 주세요.");
				$("input[name=certification]").focus();
				return;
			}

			$.post(
				tb_bbs_url + "/ajax_sms_check.php", {
					check_type: 'cert',
					cert_code: cert_code
				},
				function(data) {
					if (data == "Y") {
						alert("휴대폰 인증이 성공하였습니다.\n비밀번호 변경이 가능하십니다.");
						$("input[name=phone_flag]").val(1);
						return;
					} else {
						alert("인증에 실패하였습니다.\n올바른 인증코드를 입력해주세요.");
						return;
					}
				}
			);
		}

		function checkImg(obj) {

			if ($(obj).val() != "") {

				var ext = $(obj).val().split('.').pop().toLowerCase();

				if ($.inArray(ext, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
					alert('gif,png,jpg,jpeg 파일만 업로드 할수 있습니다.');
					return;
				}
			}
		}

		function member_edit_submit() {

			var f = document.fmemberform;

			if (f.nickname.value == "") {
				f.nickname.focus();
				alert("닉네임을 입력해주세요.");
				return;
			}

			f.submit();
		}
	</script>