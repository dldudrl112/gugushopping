<?php
if (!defined('_TUBEWEB_')) exit;

if (defined('_INDEX_')) { // index에서만 실행
	include_once(TB_LIB_PATH . '/popup.inc.php'); // 팝업레이어
}
?>
<script>
	function callNative() {
		try {
			window.webkit.messageHandlers.invokeAction.postMessage("run_vimeo"); // ios 새로운 동영상 기능 요청
			alert( "클릭되었습니다");
		} catch (e) {
			// alert(e);
		}
	}
</script>
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
	<div class="header">
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
		<!-- <h1>ios</h1> -->
		<!-- <input type="button" onclick="callNative()" value="네이티브 호출" /> -->
		<a href="javascript:(0);" onclick="callNative()" class="alarm-btn mob1024" style="right: 47px;"><img src="<?php echo TB_IMG_URL; ?>/m-main-video.png" alt="video"></a>
		<a href="<?php echo TB_BBS_URL; ?>/alarm.php" class="alarm-btn mob1024"><img src="<?php echo TB_IMG_URL; ?>/m-main-alarm.png" alt="alarm"></a>
	</div>
</header><!-- Header Area End -->