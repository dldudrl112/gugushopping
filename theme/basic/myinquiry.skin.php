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


	<div class="sub-page2 sub04 sub04-01" id="section">
		<div class="container" style="width: 100%;">
			<div class="sub-visual">
				<h3 class="back mob" style="margin: 0 10px;"><a href="javascript:history.back()"><img src="/img/header/back.png" alt="뒤로가기"><span>내구독</span></a></h3>
				<div style="border: 1px solid #f9f9f9;"></div>
				<div class="box">

					<h2 class="sub-vis-tit">내구독</h2>

					<!-- 구독없을때 -->
					<?php if ($total_count ==  0) { ?>
						<p class="sub-vis-con"><span class=""><?php echo get_text($member['nickname']); ?></span> 님이 <br>구독 중인 내역이 없습니다.</p>
					<?php } ?>
				</div>
			</div><!-- sub-visual -->

			<div class="sub04-01-content-wrap">
				<!-- 구독 있을 때 -->
				<?php if ($total_count > 0) { ?>
					<!-- <div class="calendar-box" >
				
				 <div id="datepicker"></div> 
				<div id="week" ></div>
				<div class="calendar" >
					<div class="month">
						<img src="<?php echo TB_IMG_URL; ?>/sub04_01_month_btn01.png" alt="" class="pc month-btn-left">
						<img src="<?php echo TB_IMG_URL; ?>/m_sub04_01_month_btn01.png" alt="" class="mob month-btn-left">
						<span class="month-text"><?= date("n") ?>월</span>                            
						<img src="<?php echo TB_IMG_URL; ?>/sub04_01_month_btn02.png" alt="" class="pc month-btn-right">
						<img src="<?php echo TB_IMG_URL; ?>/m_sub04_01_month_btn02.png" alt="" class="mob month-btn-right">
					</div>
				</div>
				<div class="cal-btn mob1024"><img src="<?php echo TB_IMG_URL; ?>/cal-btn.png" alt=""></div>
			</div> -->
					<div class="sub-visual1">
						<div class="box1">
							<p class="sub-vis-con"><span class="sub-vis-span"><?php echo get_text($member['nickname']); ?></span> 님이 <br>구독 중인 내역입니다.</p>

						<?php } ?>
						</div>
					</div>

					<div class="section3 cf">

						<ul class="content-ul">
							<?php
							$list_count	=	0;
							$i = 0;
							$subscriptionData = $Subscription->value ?: [];
							foreach ($subscriptionData as $row) {
								//for ($i = 0; $row = sql_fetch_array($result); $i++) {
								//최근 빌링 데이터 row값 가져오기
								//								$sql	=	"select od_id from shop_order where subscription_id = '{$row['subscription_id']}' and dan not in (0,6,7,8,9)  order by od_time desc limit 0,1";
								//								//echo $sql;
								//								$bill	=	sql_fetch($sql);



								$sql = " select C.* from shop_cart C inner join shop_order O using(od_id) where O.subscription_id = '{$row['id']}' ";
								$sql .= " group by C.gs_id order by O.od_time, C.io_type asc, C.index_no asc ";
								//echo $sql;
								$res = sql_query($sql);
								$rowspan = sql_num_rows($res) + 1;

								for ($k = 0; $ct = sql_fetch_array($res); $k++) {
									$od	=	get_order($ct['od_no']);
									$gs	=	get_goods($ct['gs_id']);

									$option_item	=	get_item_options_gugu($ct['gs_id'], $gs['opt_subject'], $ct["io_id"], $ct["od_id"]);

									//$dlcomp	=	explode('|', trim($od['delivery']));

									$href			=	TB_SHOP_URL . '/view.php?index_no=' . $ct['gs_id'];

									$it_image	=	get_it_image($gs['index_no'], $gs['simg1'], 145, 145);

									//echo $od["od_time"];
									//echo strtotime("+1 months", strtotime($od["od_time"]+1));

									//									if (substr($od["delivery_date"], 0, 10) != "0000-00-00") {
									//										$next_date_day		=	substr($od["delivery_date"], 0, 10);
									//									} else {
									//										$next_date_day		=	date("Y-m-d", strtotime($od["od_time"] . "+1 day"));
									//									}
									//
									//									$next_date				=	date("Y-m-d", strtotime($next_date_day . "+1 month"));
									//
									$next_date_day = substr($row['created_at'], 0, 10);

									$option_flag			=	false;

									//옵션 변경 flag
									if ($ct["io_type"] == 0) {

										if (strpos($ct["ct_option"], "주기") !== false) {
											$arr_ct_option		=	explode("/", $ct["ct_option"]);

											if (count($arr_ct_option) > 1) {
												$option_flag			=	true;
											}
										}
									}


							?>
									<li>
										<div class="left fl">
											<div class="img-box fl"><?= $it_image ?></div>
											<div class="text-box fl">
												<p style="margin-bottom: 8px;"><a class="g_name" href="<?= $href ?>"><?= $gs["gname"] ?></a></p>
												<p style="margin-bottom: 4px;"><span class="gu_sang">구독가</span><span class="date1"> <?= number_format($od["goods_price"]) ?>원</span></p>
												<p><span class="gu_sang">주기</span><span class="date2"> <?= number_format($row["payment_cycle"]) ?>개월</span><span class="date_start"> 시작일 <?= $next_date_day ?></span></p>
												<p style="margin-top: 4px;"><span class="gu_sang">다음결제일</span><span class="date1"><?php echo $row['next_billing_date']; ?></span></p>
												<!-- <?= $next_date_day ?> -->
												<!-- <p class="price"><?= number_format($od["goods_price"]) ?>원</p> -->
											</div>
										</div>
										<div style="display: block;" class="right fl r_gugu_<?= $ct["od_id"] ?>">
											<ul>
												<? if ($od["dan"] == "1" || $od["dan"] == "2" || $od["dan"] == "3") { ?>
													<?php if ($option_flag !== false) { ?>
														<!-- <li class="option-control li-class"><a href="javascript:void(0)">옵션 변경</a></li> -->
													<?php } ?>
													<li style="display:none"><a href="<?php echo TB_SHOP_URL; ?>/my_shipping_day.php?od_id=<?= $ct["od_id"] ?>" onclick="win_open(this,'win_coupon','420','700','yes');return false">배송일 변경</a></li>
												<? } ?>
												<!-- <li class="payment-control"><a href="javascript:void(0)">결제방식 변경</a></li> -->
												<li class="li-class"><a style="background-color: #3f3f3f;" href="<?php echo TB_SHOP_URL; ?>/my_shipping.php?od_id=<?= $ct["od_id"] ?>" onclick="win_open(this,'win_coupon','420','700','yes');return false">변 경</a></li>
												<!-- <li class="option-control li-class"><a href="javascript:void(0)">옵션 변경</a></li> -->
												<?php if ($od["dan"] == "5") { ?>

												<?php } ?>
												<? if (($od["dan"] >= "1" && $od["dan"] <= "5") || $gs["autobill"] == 1) { ?><li class="gray pay-cancel li-class"><a style="background-color: #f1f1f1; color:#ababab;" href="javascript:void(0)">취 소</a></li><? } ?>
												<li style="width:100%;" class="li-class"><a style="background-color: #fff; border:1px solid #d8d8d8; color:#000;" href="<?php echo TB_SHOP_URL; ?>/orderreview.php?od_id=<?= $ct["od_id"] ?>" ">리뷰 쓰기</a></li>
											</ul>
										</div>
										<!-- <div class=" cal-btn mob1024"><img src="<?php echo TB_IMG_URL; ?>/cal-btn-w.png" alt="">
										</div> -->
									</li>

									<div class="pop-up gugu_<?= $ct["od_id"] ?>">
										<div class="option-box">
											<form class="option-form" name="option-form" id="option_chg_<?= $ct["od_id"] ?>" method="post" action="<?php echo TB_SHOP_URL; ?>/option_chg_gugu.php" target="">
												<input type="hidden" name="od_id" value="<?= $ct["od_id"] ?>">
												<h4>옵션변경</h4>
												<? echo $option_item; ?>
												<div class="pop-up-btn pop_gugu_<?= $ct["od_id"] ?>">
													<a href="javascript:void(0)" class="cancel">취소</a>
													<a href="javascript:void(0)" class="option_chg">확인</a>
												</div>
											</form>
										</div>
										<div>
											<div class="cancel-box">
												<form class="option-form" name="option-form" id="order_cancel_<?= $ct["od_id"] ?>" method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquirycancel_gugu.php" target="">
													<input type="hidden" name="od_id" value="<?= $ct["od_id"] ?>">
													<input type="hidden" name="token" value="<?php echo $token; ?>">
													<p>'<?= $gs["gname"] ?>' 구독을 해지하시겠습니까?</p>
													<p class="cancel_u"> (취소 시 기존 구독 내용 삭제 및 혜택 사항이 사라 질 수 있습니다) </p>
													<div class="pop-up-btn pop_gugu_<?= $ct["od_id"] ?>">
														<label for="subs-no" class="cancel">
															<input type="radio" name="check" id="subs-no">
															아니오
														</label>
														<label for="subs-yes" class="order_cancel">
															<input type="radio" name="check" id="subs-yes">
															예
														</label>
													</div>
												</form>
											</div>
										</div>
									</div>

									<script>
										//내구독 옵션변경
										$('.sub04-01 .section3 .r_gugu_<?= $ct["od_id"] ?> ul li.option-control').find('a').click(function() {
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?>').fadeIn()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div').hide()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div:nth-child(1)').show()
										})

										$('.sub04-01 .section3 .r_gugu_<?= $ct["od_id"] ?> ul li.pay-cancel').find('a').click(function() {
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?>').fadeIn()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div').hide()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div:nth-child(2)').show()
										})

										$('.sub04-01 .gugu_<?= $ct["od_id"] ?> .cancel').click(function() {
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?>').hide()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div').hide()
										})

										$('.sub04-01 .gugu_<?= $ct["od_id"] ?> .option_chg').click(function() {
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?>').hide()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div').hide()
											$("#option_chg_<?= $ct["od_id"] ?>").submit();

										})
										$('.sub04-01 .gugu_<?= $ct["od_id"] ?> .order_cancel').click(function() {
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?>').hide()
											$('.sub04-01 .gugu_<?= $ct["od_id"] ?> > div').hide()
											$("#order_cancel_<?= $ct["od_id"] ?>").submit();

										})
									</script>
							<?php
									$list_count++;
								}
								++$i;
							}

							if ($list_count == 0)
								echo '<div class="empty-box"><a href="/index.php#main_category"><img src="\img\my_gu\no_fudog.png" alt="no-subscirbe"></a></div>';
							?>

						</ul>
					</div>
					<?php
					echo get_paging2($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?page=');
					?>

					<div class="btn-box pc">
						<a href="javascript:history.back()" class="btn01">뒤로가기</a>
					</div>

			</div>
		</div>
		<!-- <div class="empty_line">
			<div class="empty-box"><a href="/index.php#main_category"><img src="\img\my_gu\no_fudog.png" alt="no-subscirbe"></a></div>
		</div> -->

	</div>


	<script>
		$(document).ready(function() {
			//캘린더 오늘 날짜 표시
			$(".calendar-box .calendar .calendar__day .<?= date('Ymd') ?>").addClass("on");
			$(".calendar-box #week .calendar__day .<?= date('Ymd') ?>").addClass("on");
		});
	</script>




	<!-- Footer Area Start -->
	<footer class="footer" id="footer">
		<div class="main-container">
			<div class="footer-left fl">
				<a href="/index.php#main_category"><img src="<?php echo TB_IMG_URL; ?>/logo02.png" alt="logo"></a>
			</div>
			<div class="footer-right fl">
				<div class="top">
					<ul>
						<!-- <li><a href="<?php echo TB_BBS_URL; ?>/policy.php">개인정보처리방침</a></li>
		  <li><a href="<?php echo TB_BBS_URL; ?>/provision.php">이용약관</a></li> -->
						<li><a href="javascript:void(0)">개인정보처리방침</a></li>
						<li><a href="javascript:void(0)">이용약관</a></li>
					</ul>
				</div>
				<div class="bottom">
					<ul>
						<li>
							<div><span class="subject">상호명 :</span><span class="con"><?php echo $config['company_name']; ?></span><span class="subject">대표 :</span><span class="con"><?php echo $config['company_owner']; ?></span><span class="subject">주소 :</span><span class="con"><?php echo $config['company_addr']; ?></span></div>
						</li>
						<li>
							<div><span class="subject">전화번호 :</span><span class="con">070-4814-3964</span><span class="subject">문의메일 :</span><span class="con"><?php echo $super['email']; ?></span></div>
						</li>
						<li>
							<div><span class="subject">사업자등록번호 :</span><span class="con"><?php echo $config['company_saupja_no']; ?></span><span class="subject">통신판매업신고번호 : </span><span class="con"><?php echo $config['tongsin_no']; ?></span></div>
						</li>
					</ul>
					<p class="mob">(주) SBJ</p>
				</div>
			</div>
		</div>
	</footer><!-- Footer Area Start -->
	<div class="m-quick-nav">
		<!-- <div class="m-home">
	<?php
	$tnb = array();
	$tnb[] = '<a href="/index.php#main_category"><img src="' . TB_IMG_URL . '/home.png" alt="구구홈"><span>구구홈</span></a>';
	$tnb_str = implode(PHP_EOL, $tnb);
	echo $tnb_str;
	?>          
    </div> -->
		<ul>
			<?php
			$tnb = array();
			$tnb[] = '<li><a href="/index.php#main_category"><img src="/img/footer/home_gugu_off.png" alt="구구홈"><span>구구홈</span></a></li>';
			$tnb[] = '<li><a href="' . TB_SHOP_URL . '/myinquiry.php"><img src="/img/footer/regi_gugu.png" alt="내구독"><span>내구독</span></a></li>';
			$tnb[] = '<li><a href="' . TB_SHOP_URL . '/wish.php"><img src="/img/footer/wish_gugu_off.png" alt="찜"><span>찜</span></a></li>';
			$tnb[] = '<li><a href="' . TB_BBS_URL . '/m_mypagelist.php"><img src="/img/footer/my_gugu_off.png" alt="내정보"><span>내정보</span></a></li>';
			$tnb_str = implode(PHP_EOL, $tnb);
			echo $tnb_str;
			?>
		</ul>
	</div>
	<div id="footer-pop-up">
		<img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
		<h4>이용약관 동의</h4>
		<p><?php echo nl2br($config['shop_provision']); ?></p>
		<a href="javascript:void(0)">확인</a>
	</div>
	<div id="footer-pop-up-private">
		<img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
		<h4>개인정보처리방침</h4>
		<p><?php echo nl2br($config['shop_private']); ?></p>
		<a href="javascript:void(0)">확인</a>
	</div>
	<!-- My JS -->
	<script src="<?php echo TB_JS_URL; ?>/main.js?ver=<?php echo TB_JS_VER; ?>"></script>
	</body>

</html>