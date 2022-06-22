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
	<link rel="stylesheet" href="./test.scss">
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
			<!-- <a href="<?php echo TB_BBS_URL; ?>/alarm.php" class="alarm-btn mob1024"><img src="<?php echo TB_IMG_URL; ?>/m-main-alarm.png" alt="alarm"></a> -->
		</div>


		<div class="sub-page_sang sub02-01 webi" id="section">
			<div class="goods-content line">
				<div class="container">
					<div class="share-box">
						<h5>공유하기</h5>
						<div class="sns-box">
							<ul>
								<li><?= get_sns_share_link('kakao', $sns_url, $sns_title, TB_IMG_URL . '/share-sns01.png'); ?></li>
								<li><?= get_sns_share_link('naverline', $sns_url, $sns_title, TB_IMG_URL . '/share-sns02.png'); ?></li>
								<li><?= get_sns_share_link('facebook', $sns_url, $sns_title, TB_IMG_URL . '/share-sns03.png'); ?></li>
								<li><?= get_sns_share_link('naverband', $sns_url, $sns_title, TB_IMG_URL . '/share-sns04.png'); ?></li>
								<li>
									<a href="sms:">
										<img src="<?php echo TB_IMG_URL; ?>/share-sns05.png" alt="SMS">
										<span>SMS</span>
									</a>
								</li>
							</ul>
						</div>
						<div class="share-input-box">
							<input type="text" name="url" value="http://<?= $_SERVER["HTTP_HOST"]; ?><?= $_SERVER["REQUEST_URI"]; ?>" readonly>
							<a href="javascript:void(0);" onclick="copy('http://<?= $_SERVER["HTTP_HOST"]; ?><?= $_SERVER["REQUEST_URI"]; ?>');">url 복사</a>
						</div>
						<a href="javascript:void(0)">취소</a>
					</div>
					<div class="icon-box">
						<a href="javascript:history.back()" class="back mob"><img src="/img/header/back.png" alt="뒤로가기"></a>
						<div class="box">
							<div class="share"><img src="<?php echo TB_IMG_URL; ?>/m-icon_share.png" alt="공유하기"></div>
							<!-- <div class="wish <?php if ($wish_count > 0) echo "on"; ?>">
								<?php if ($wish_count > 0) { ?>
									<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish.png" alt="찜하기" class="<?= $wish_class ?>" onclick="itemlistwish('<?php echo $index_no; ?>');">
								<?php } else { ?>
									<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish_dim.png" alt="찜하기" onclick="itemlistwish('<?php echo $index_no; ?>');">
								<?php } ?>
							</div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</header><!-- Header Area End -->

<div class="sub-page sub03-03" id="section">
	<div class="container">                
		<!-- <span class="back mob"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"></span>                           -->
		<p class="tit">구독 신청이 완료 되었습니다. 감사합니다.</p>
		<img src="<?php echo TB_IMG_URL; ?>/icon_complete.png" alt="구독신청완료">
		<?php

        $st_count1 = $st_count2 = $st_cancel_price = 0;
        $custom_cancel = false;

		$sql = " select * from shop_order where od_id = '$od_id' group by od_id order by index_no desc ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
//print_r($row);
			$sql = " select * from shop_cart where od_id = '$od_id' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			$rowspan = sql_num_rows($res) + 1;

			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$rw	=	get_order($ct['od_no']);
				$gs	=	get_goods($ct['gs_id']);

				$dlcomp = explode('|', trim($rw['delivery']));

				$href = TB_SHOP_URL.'/view.php?index_no='.$rw['gs_id'];

				$it_name = stripslashes($gs['gname']);

				//찜하기 상태 
				$wish_count	=	0;

				if($member["id"]){

					$sql = "select count(wi_id) as cnt from shop_wish where gs_id='".$rw['gs_id']."' and mb_id = '".$member["id"]."'";
					$wi = sql_fetch($sql);
					
					$wish_count	=	$wi["cnt"];
				}

				$it_options = print_complete_options($ct['gs_id'], $ct['od_id']);

				if($it_options){
					$it_options = '<div class="sod_opt">'.$it_options.'</div>';
				}

		?>
		<div class="complete-box">
			<ul>
				<li>
					<span class="subject">상 품</span>:
					<span class="con"><?=$it_name?></span>
				</li>
				<?php if($it_options){ ?>
				<li>
					<span class="subject">옵션</span>:
					<span class="con"><?=$it_options?></span>
				</li>
				<?php } ?>
				<li>
					<span class="subject">금 액</span>:
					<span class="con"><?php echo get_price($gs['index_no']); ?></span>
				</li>
				<li>
					<span class="subject">결제일</span>:
					<span class="con">매월 <?=substr(date("Y-m-d", strtotime($od["od_time"]."+1 day")),8,2)?>일</span>
				</li>
			</ul>
			<div class="share-box">
				<h5>공유하기</h5>
				<div class="sns-box">
					<ul>
						<li><?=get_sns_share_link('kakao', $sns_url, $sns_title, TB_IMG_URL.'/share-sns01.png');?></li>
						<li><?=get_sns_share_link('naverline', $sns_url, $sns_title, TB_IMG_URL.'/share-sns02.png');?></li>
						<li><?=get_sns_share_link('facebook', $sns_url, $sns_title, TB_IMG_URL.'/share-sns03.png');?></li>
						<li><?=get_sns_share_link('naverband', $sns_url, $sns_title, TB_IMG_URL.'/share-sns04.png');?></li>                               
						<li>
							<a href="sms:">
							<img src="<?php echo TB_IMG_URL; ?>/share-sns05.png" alt="SMS">
							<span>SMS</span>
							</a>
						</li>                                
					</ul>
				</div>
				<div class="share-input-box">
					<input type="text" name="url" value="<?=$_SERVER["HTTP_ORIGIN"];?><?=$_SERVER["REQUEST_URI"];?>" readonly>
					<a href="javascript:void(0);" onclick="copy('<?=$_SERVER["HTTP_ORIGIN"];?><?=$_SERVER["REQUEST_URI"];?>');">url 복사</a>
				</div>
				<a href="javascript:void(0)">취소</a>
			</div>
			<div class="icon-box" style="display:none;">
				<div class="share"><img src="<?php echo TB_IMG_URL; ?>/m-icon_share.png" alt="공유하기"></div>
				<div class="wish <?php if($wish_count > 0) echo "on"; ?>" >
					<?php if($wish_count > 0){ ?>
						<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish.png" alt="찜하기" class="<?=$wish_class?>" onclick="itemlistwish('<?php echo $index_no; ?>');">
					<?php }else{ ?>
						<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish_dim.png" alt="찜하기" onclick="itemlistwish('<?php echo $index_no; ?>');">
					<?php }?>	
				</div>
			</div>
		</div>
		<?php
				$st_count1++;
				if(in_array($rw['dan'], array('1','2','3')))
					$st_count2++;

				$st_cancel_price += $rw['cancel_price'];
			}
		}

		// 주문상태가 배송중 이전 단계이면 고객 취소 가능
		if($st_count1 > 0 && $st_count1 == $st_count2)
			$custom_cancel = true;
		?>
		<p class="refer">*구구 정기구독은 언제든 자유롭게 취소가 가능합니다. 내구독 화면을 통해 자유롭게 구독 내용을 변경 가능합니다.</p>
		<a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" class="order-confirm"><span>결제 내역 확인하기</span><img src="<?php echo TB_IMG_URL; ?>/next01.png" alt="next"></a>
		<div class="btn-box">
			<a href="/" class="btn01">홈으로 이동</a>
		</div>
	</div>
</div>

<script>
    (function() {
        var products = JSON.parse(window.localStorage.getItem('labbit_ga_products'));
        var total_price = '<?php echo get_price($gs['index_no']); ?>'.replace(/[^0-9]/g, '');
        var order_id = '<?php echo $od_id?>';
      
        if (!products) {
            return;
        }
      
        // products = window.products;
      
        if (products.length > 0) {
            window.dataLayer.push({
                'event': 'ga4_purchase',
                'ecommerce': {
                    'purchase': {
                        'transaction_id': order_id,
                        'affiliation': 'gugushopping',
                        'value': total_price,
                        'currency': 'KRW',
                        'items': products        
                    }
                }
            });
          
          window.localStorage.removeItem('labbit_ga_products');
          window.localStorage.removeItem('labbit_ga_total_price');
        }
    })();
</script>