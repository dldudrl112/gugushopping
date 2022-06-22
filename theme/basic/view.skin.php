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

// $star_score = ($star_score * 10) * 2;

// $score = $row["score"];

$sum = 0;


for ($a = 0; $a <= 5; $a++) {
	$sum += $star_score[$a];
}
$avg = $star_score / 1 * 0.95;



// $sum = array_sum($number);
// $avg = $sum / sizeof($number);
// echo "total sum:".$sum;
// echo "\n";
// echo "average:".$ave;
// echo "\n";

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
	<script src="<?php echo TB_JS_URL; ?>/lazyload.js"></script>
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
						<a href="/index.php" class="back mob"><img src="/img/header/back.png" alt="뒤로가기"></a>
						<div class="box">
							<div class="share"><img src="<?php echo TB_IMG_URL; ?>/m-icon_share.png" alt="공유하기" style="padding-right: 6px;"></div>
							<div class="wish <?php if ($wish_count > 0) echo "on"; ?>">
								<?php if ($wish_count > 0) { ?>
									<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish.png" alt="찜하기" class="<?= $wish_class ?>" onclick="itemlistwish('<?php echo $index_no; ?>');">
								<?php } else { ?>
									<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish_dim.png" alt="찜하기" onclick="itemlistwish('<?php echo $index_no; ?>');">
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</header><!-- Header Area End -->




	<script src="<?php echo TB_JS_URL; ?>/shop.js"></script>

	<form name="fbuyform" method="post">
		<input type="hidden" name="gs_id[]" value="<?php echo $index_no; ?>">
		<input type="hidden" id="it_price" value="<?php echo get_sale_price($index_no); ?>">
		<input type="hidden" name="ca_id" value="<?php echo $ca['gcate']; ?>">
		<input type="hidden" name="sw_direct">
		<input type="hidden" id="supply_item_gugu" value="<?php echo $supply_item_gugu; ?>">

		<div class="sub-page sub02-01" id="section">
			<div class="goods-content">
				<div class="container sang_video">
					<div class="goods-video">
						<?php echo get_it_goods_image($index_no, $gs['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx']); ?>
					</div>
				</div>
				<div class="container">
					<!-- <div class="share-box">
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
					</div> -->
					<!-- <div class="icon-box_sang">
						<a href="javascript:history.back()" class="back mob"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"></a>
						<div class="box">
							<div class="share"><img src="<?php echo TB_IMG_URL; ?>/m-icon_share.png" alt="공유하기"></div>
							<div class="wish <?php if ($wish_count > 0) echo "on"; ?>">
								<?php if ($wish_count > 0) { ?>
									<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish.png" alt="찜하기" class="<?= $wish_class ?>" onclick="itemlistwish('<?php echo $index_no; ?>');">
								<?php } else { ?>
									<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish_dim.png" alt="찜하기" onclick="itemlistwish('<?php echo $index_no; ?>');">
								<?php } ?>
							</div>
						</div>
					</div> -->


					<!-- goods-video -->
					<div class="goods-info-wrap">
						<div class="goods-info-box">
							<p class="sub-title">
								<span class="goods-cate">[<?= $ca["catename"] ?>]</span>
								<?= $gs["keywords"] ?>
							</p>
							<h2 class="goods-title"><?= $gs["gname"] ?></h2>
							<p class="info01">구구 정기구독은 해지가 자유롭습니다</p>
							<a href="javacript:void(0)" class="aver-star">
								<img src="<?php echo TB_IMG_URL; ?>/m-star.png" alt="별점">

								<span class="average"><?= $gs["avg1"]; ?></span>


								<!-- <span class="average"><?= $avgscore ?></span> -->

								<?php if ($item_use_count > 0) { ?>
									<span class="re-count">(<?= $item_use_count ?>)</span>
								<?php } ?>
							</a>
						</div>
					</div>
				</div>

				<div class="border_line"></div>

				<div class="container">
					<div class="goods-info-wrap">
						<div class="goods-info-box">
							<p class="price-box01 price-box1">
								<span class="period_price1"><?php echo $gs['model']; ?></span><span><?php echo $gs['origin']; ?></span>
							</p>
							<p class="price-box01 price-box">
								<span class="sort" style="font-size: 17px;">구독가</span><span class="price"><?php echo $it_price; ?></span>
							</p>
							<!-- <p class="price-box02 price-box">
								<span class="sort period_price"><? if ($month_price) { ?><? echo "월 구독 " . $month_price; ?><? } ?></span>
							</p> -->


						</div>
						<!-- goods-info-box -->

						<?php if (!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout) { ?>
							<div class="m-quick-nav-sang">

								<div class="page-wrapper">
									<a class="btn trigger" href="#">구독하기</a>

								</div>
								<div class="modal-wrapper">
									<div class="modal">
										<div class="head">
											<a class="btn-close trigger" href="#">
												<img src="/img/sang/down.png">
												<!-- <i class="fa fa-times" aria-hidden="true"></i> -->
											</a>
										</div>
										<div class="content">
											<div class="good-job">
												<div class="good-job-name"> <?php echo $gs['gname'] ?></div>
												<div class="option-box">
													<ul id="option_set_added">
														<li class="amount sit_opt_list vi_txt_li">
															<input type="hidden" name="io_type[<?php echo $index_no; ?>][]" value="0">
															<input type="hidden" name="io_id[<?php echo $index_no; ?>][]" value="">
															<input type="hidden" name="io_value[<?php echo $index_no; ?>][]" value="<?php echo $gs['gname']; ?>">
															<input type="hidden" class="io_price" value="0">
															<input type="hidden" class="io_stock" value="<?php echo $gs['stock_qty']; ?>">
															<span class="option-name sit_opt_subj">수량</span>
															<span class="option-con">
																<span class="calculator">
																	<a href="javascript:void(0)" class="calc-min" data="minus">-</a>
																	<input type="number" name="ct_qty[<?php echo $index_no; ?>][]" value="<?php echo $odr_min; ?>" class="sale-cnt inp_opt">
																	<a href="javascript:void(0)" class="calc-plus" data="plus">+</a>
																</span>
															</span>
														</li>
														<script>
															$(function() {
																price_calculate();
															});
														</script>
														<!-- 선택 옵션 S -->
														<?php if ($option_item || $supply_item) { ?>
															<li>
																<?php if ($option_item) { ?>
																	<div>
																		<span class="option-name">옵션</span>
																		<?php echo $option_item; ?>
																	</div>
																<?php } ?>
																<?php if ($supply_item) { ?>
																	<div>
																		<span class="option-name">주기</span>
																		<?php echo $supply_item; ?>
																	</div>
																<?php } ?>
															</li>
														<?php } ?>
													</ul>
													<div class="good-job-bro">
														<!-- <div class="good-job-sis">
															<p><span style="margin-right:45px;">옵션</span><span class="right"> <?php echo $ct[""]; ?></span></p>
														</div>
														<div class="good-job-sis">
															<p><span style="margin-right:18px;">결제 주기</span><span class="right"><?= date("Y-m-d"); ?></span></p>
														</div> -->
														<div class="good-job-sis">
															<p><span style="margin-right:18px">구독 가격</span><span class="right"><?php echo $it_price; ?></span></p>
														</div>

													</div>
												</div>
												<!-- option-box -->
												<div id="sit_tot_views" class="goods-info-box">
													<p class="price-box01 price-box" style="display:none">
														<span class="sort">총 합계금액</span><span id="sit_tot_price" class="prdc_price"></span>
													</p>
												</div>
											<?php } ?>

											<div class="pay-box">
												<?php echo get_buy_button_unit($script_msg, $index_no, 'buy'); ?>
												<?php if ($naverpay_button_js) { ?>
													<!-- <div class="naverpay-item"><?php echo $naverpay_request_js . $naverpay_button_js; ?></div> -->
												<?php } ?>
											</div>

											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="web-pay-box">
							<div class="pay-box">
								<?php echo get_buy_button_unit($script_msg, $index_no, 'buy'); ?>
								<?php if ($naverpay_button_js) { ?>
									<!-- <div class="naverpay-item"><?php echo $naverpay_request_js . $naverpay_button_js; ?></div> 
								<?php } ?>
							</div>
							</div> -->
					</div>
				</div>






				<!-- <form class="goods-option-form" name="goods-option-form" method="post" action="" target="">
                                <ul>
                                    <li class="amount">
                                        <span class="option-name">수량</span>
                                        <span class="option-con">
                                            <span class="calculator">
                                                <a href="javascript:void(0)" class="calc-min">-</a>
                                                <input type="number" class="sale-cnt" value="1">
                                                <a href="javascript:void(0)" class="calc-plus">+</a>
                                            </span>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="option-name">옵션</span>
                                        <span class="option-con">
                                            <select name="select-box" class="select01">
                                                <option value="">사이즈</option>
                                                <option value="XL 사이즈 1개월">XL 사이즈 1개월</option>
                                                <option value="L 사이즈 1개월">L 사이즈 1개월</option>
                                                <option value="M 사이즈 1개월">M 사이즈 1개월</option>
                                                <option value="S 사이즈 1개월">S 사이즈 1개월</option>
                                            </select>
                                        </span>
                                        <span class="option-con">
                                            <select name="select-box" class="select02">
                                                <option value="">주기</option>
                                                <option value="1달">1달</option>
                                                <option value="2달">2달</option>
                                                <option value="3달">3달</option>
                                                <option value="4달">4달</option>
                                            </select>
                                        </span>
                                    </li>
                                </ul>
                            </form> -->

				<div class="border_line_500"></div>
				<div class="container">
					<div class="goods-info-wrap">
						<div class="goods-info-box">
							<p class="price-box01 price-box2">
								<span>구성</span><span class="period_price1"><?php echo $gs['maker']; ?></span>
							</p>
						</div>
					</div>



					<div class="muryo">
						<div class="muryo_text">
							<p class="price-box1">무료배송</p>
							<p>제주 추가 3,000원, 도서지역 추가 6,000원</p>
						</div>
					</div>
				</div>
				<!-- <div class="border_line_500"></div> -->


				<!-- goods-info-wrap-->
				<div class="detail-info-box cf">
					<div class="detail-tab-box">
						<div class="container">
							<ul class="detail-tab cf">
								<li class="on">
									<a href="javascript:void(0)">상품</a>
								</li>
								<li>
									<a href="javascript:void(0)">리뷰</a>
								</li>
								<li>
									<a href="javascript:void(0)">상품문의</a>
								</li>
							</ul>
							<!-- <div class="detail-banner">
                                <img src="<?php echo TB_IMG_URL; ?>/goods-pagebanner.png" alt="배너" class="pc">
                            </div> -->
						</div>
					</div>

					<div class="goods-detail-info goods-sec" style="display: block;">
						<div class="container sangseimg">
							<?php echo conv_content($gs['memo'], 1); ?>
						</div>
					</div>


					<div class="goods-review goods-sec">
						<div class="container">
							<div class="review-box">
								<div class="review-box-top">
									<h4><?= $gs["gname"] ?></h4>
									<span><img src="<?php echo TB_IMG_URL; ?>/icon_star03.png" alt="star">

										<i><?= $gs["avg1"]; ?></i>


									</span>
								</div>

								<!-- <?php
										$conn = mysqli_connect("211.47.74.10", "gugudev", "gugudev0710!", "dbgugudev");
										$sql = "SELECT * FROM shop_goods_review";
										$result = mysqli_query($conn, $sql);
										if (mysqli_num_rows($result) > 0) {
											while ($row = mysqli_fetch_assoc($result)) {
											}
										} else {
											echo "테이블에 데이터가 없습니다.";
										}
										mysqli_close($conn); // 디비 접속 닫기
										?> -->

								<ul>
									<?php
									$mem_upl_dir		=	TB_DATA_URL . "/member";
									for ($i = 0; $row = sql_fetch_array($review_result); $i++) {

										$mb	=	get_member($row["mb_id"], "name, nickname, mem_img");




									?>



										<li class="content">
											<div class="con-top">
												<a href="javacript:void(0)" class="img-box">
													<?php if ($mb["mem_img"]) { ?>
														<img src="<?php echo $mem_upl_dir; ?>/<?= $mb["mem_img"] ?>" alt="user_img">
													<?php } else { ?>
														<img src="<?php echo TB_IMG_URL; ?>/m-profile-no-img.png" alt="thumb">
													<?php } ?>
												</a>
												<div class="text-box">
													<a href="javascript:void(0)" class="nickname"><?= $mb["nickname"] ?></a>
													<div>
														<span class="date"><?= str_replace("-", ". ", substr($row["reg_time"], 0, 10)) ?></span>
														<span class="star">
															<img src="<?php echo TB_IMG_URL; ?>/icon_star03.png" alt="">
															<span class="aver"><?= $row["score"] ?></span>

														</span>
													</div>
												</div>
											</div>
											<!-- con-top -->
											<div class="con-bottom">
												<?php if ($row["rimg1"]) { ?>
													<ul class="review-img-box">
														<?php if ($row["rimg1"]) { ?>
															<li><img src="/data/review/<?= $row["rimg1"] ?>" alt=""></li>
														<?php } ?>

														<?php if ($row["rimg2"]) { ?>
															<li><img src="/data/review/<?= $row["rimg2"] ?>" alt=""></li>
														<?php } ?>

														<?php if ($row["rimg3"]) { ?>
															<li><img src="/data/review/<?= $row["rimg3"] ?>" alt=""></li>
														<?php } ?>

														<?php if ($row["rimg4"]) { ?>
															<li><img src="/data/review/<?= $row["rimg4"] ?>" alt=""></li>
														<?php } ?>

														<?php if ($row["rimg5"]) { ?>
															<li><img src="/data/review/<?= $row["rimg5"] ?>" alt=""></li>
														<?php } ?>
													</ul>
												<?php } ?>

												<p><?= nl2br($row["memo"]) ?></p>

												<?php if ($row["answer"]) { ?>
													<div class="reply-box">
														<img src="<?php echo TB_IMG_URL; ?>/m-md-profile.png" alt="reply-img">
														<div class="reply-con">
															<p><?= nl2br($row["answer"]) ?></p>
														</div>
													</div>
												<?php } ?>

											</div>
										</li>
									<?php
									}
									?>
								</ul>
							</div>
							<!-- review-box -->
							<?php if ($total_page > 1) { ?>
								<a href="javascript:void(0)" class="more">리뷰 더보기</a>
							<?php } ?>
						</div>
					</div>
					<div class="goods-qna goods-sec">
						<?php
						include_once(TB_THEME_PATH . '/view_qa.skin.php');
						?>
					</div>
				</div>
			</div>
		</div>
	</form>
	<script>
		$(function() {

			/* 리뷰 더보기 */
			$('.review_box .more_btn').click(function() {
				var total_page = parseInt(<?= $total_page ?>);
				var page = parseInt($("input[name=review_page]").val()) + 1;
				var gs_id = $("input[name='gs_id[]']").val();

				$.ajax({
					type: "POST",
					data: "index_no=" + gs_id + "&page=" + page,
					url: tb_shop_url + "/view.php",
					success: function(data) {
						var arr_data = data.split("<ul class=\"review-box-area\">");
						var arr_content = arr_data[1].split("</ul><!--review-box-area End -->");

						$(".review_box .review-box-area").append(arr_content[0]);
						$("input[name=review_page]").val(page);

						if (total_page == page) {
							$('.review_box .more_btn').hide();
						}
					}
				});

			});

		});

		// 상품보관
		function item_wish(f) {
			f.action = "./wishupdate.php";
			f.submit();
		}

		function fsubmit_check(f) {
			// 판매가격이 0 보다 작다면
			if (document.getElementById("it_price").value < 0) {
				alert("전화로 문의해 주시면 감사하겠습니다.");
				return false;
			}


			if ($(".sit_opt_list").length < 1) {
				alert("주문옵션을 선택해주시기 바랍니다.");
				return false;
			}

			var val, io_type, result = true;
			var sum_qty = 0;
			var min_qty = parseInt('<?php echo $odr_min; ?>');
			var max_qty = parseInt('<?php echo $odr_max; ?>');
			var $el_type = $("input[name^=io_type]");

			$("input[name^=ct_qty]").each(function(index) {
				val = $(this).val();

				if (val.length < 1) {
					alert("수량을 입력해 주십시오.");
					result = false;
					return false;
				}

				if (val.replace(/[0-9]/g, "").length > 0) {
					alert("수량은 숫자로 입력해 주십시오.");
					result = false;
					return false;
				}

				if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
					alert("수량은 1이상 입력해 주십시오.");
					result = false;
					return false;
				}

				io_type = $el_type.eq(index).val();
				if (io_type == "0")
					sum_qty += parseInt(val);
			});

			if (!result) {
				return false;
			}

			if (min_qty > 0 && sum_qty < min_qty) {
				alert("주문옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주세요.");
				return false;
			}

			if (max_qty > 0 && sum_qty > max_qty) {
				alert("주문옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주세요.");
				return false;
			}

			return true;
		}

		// 바로구매, 장바구니 폼 전송
		function fbuyform_submit(sw_direct) {



			var f = document.fbuyform;
			f.sw_direct.value = sw_direct;

			if (sw_direct == "cart") {
				f.sw_direct.value = 0;
			} else { // 바로구매
				f.sw_direct.value = 1;
			}

			if ($("#it_option_1").length >= 1) {
				if ($("#it_option_1").val() == "") {
					alert("주문옵션을 선택해주시기 바랍니다.");
					return;
				}
			}

			if ($("#it_option_2").length >= 1) {
				if ($("#it_option_2").val() == "") {
					alert("추가옵션을 선택해주시기 바랍니다.");
					return;
				}
			}

			if ($(".sit_opt_list").length < 1) {
				alert("주문옵션을 선택해주시기 바랍니다.");
				return;
			}


			var val, io_type, result = true;
			var sum_qty = 0;
			var min_qty = parseInt('<?php echo $odr_min; ?>');
			var max_qty = parseInt('<?php echo $odr_max; ?>');
			var $el_type = $("input[name^=io_type]");

			$("input[name^=ct_qty]").each(function(index) {
				val = $(this).val();

				if (val.length < 1) {
					alert("수량을 입력해 주세요.");
					result = false;
					return;
				}

				if (val.replace(/[0-9]/g, "").length > 0) {
					alert("수량은 숫자로 입력해 주세요.");
					result = false;
					return;
				}

				if (parseInt(val.replace(/[^0-9]/g, "")) < 1) {
					alert("수량은 1이상 입력해 주세요.");
					result = false;
					return;
				}

				io_type = $el_type.eq(index).val();
				if (io_type == "0")
					sum_qty += parseInt(val);
			});

			if (!result) {
				return;
			}

			if (min_qty > 0 && sum_qty < min_qty) {
				alert("주문옵션 개수 총합 " + number_format(String(min_qty)) + "개 이상 주문해 주세요.");
				return;
			}

			if (max_qty > 0 && sum_qty > max_qty) {
				alert("주문옵션 개수 총합 " + number_format(String(max_qty)) + "개 이하로 주문해 주세요.");
				return;
			}

			f.action = "./cartupdate.php";
			f.submit();
		}
	</script>

	<!-- Footer Area Start -->
	<footer class="footer" id="footer">
		<div class="main-container">
			<div class="footer-left fl">
				<a href="/index.php#main_category"><img style="width: 150px;" src="/img/footer/tail_logo.png" alt="logo"></a>
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
	<script>
		$(document).ready(function() {
			$('.trigger').on('click', function() {
				$('.modal-wrapper').toggleClass('open');
				$('.page-wrapper').toggleClass('blur-it');
				return false;
			});
		});
	</script>

	<!-- LABBIT GA 전자상거래 변수세팅-->
	<script>
		var labbit_product_name = '<?php echo $gs["gname"]; ?>'; // 상품명
		var labbit_product_id = '<?php echo  $index_no; ?>'; // 상품 고유 번호
		var labbit_product_price = '<?php echo get_sale_price($index_no); ?>'; // 상품 가격
		var labbit_product_brand = 'gugushopping'; // 브랜드
		var labbit_product_category = '<?php echo $ca['gcate']; ?>'; // 상품 카테고리
	</script>
	<!-- LABBIT GA 전자상거래 변수세팅 끝-->

	</body>

</html>