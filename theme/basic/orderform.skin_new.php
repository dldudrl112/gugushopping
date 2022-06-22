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
	<?php

	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {   //https 통신
		echo '<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>'.PHP_EOL;
	} else {  //http 통신
		echo '<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>'.PHP_EOL;
	}
	echo '<script src="/js/zip_frame.js"></script>'.PHP_EOL;
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
                            <a href="javascript:void(0);" onclick="copy('http://<?= $_SERVER["HTTP_HOST"]; ?><?= $_SERVER["REQUEST_URI"]; ?>');">url
                                복사</a>
                        </div>
                        <a href="javascript:void(0)">취소</a>
                    </div>
                    <div class="icon-box">
                        <a href="javascript:history.back()" class="back mob"><img src="/img/header/back.png" alt="뒤로가기"></a>
                        <div class="box">
                            <!-- <div class="share"><img src="<?php echo TB_IMG_URL; ?>/m-icon_share.png" alt="공유하기"></div> -->
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


    <?php
    require_once(TB_SHOP_PATH . '/settle_kakaopay.inc.php');
    ?>


    <form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
        <div class="sub-page-sang sub03-02" id="section">
            <div class="container">
                <!-- Sub_title Area Start -->
                <div class="sub_title">
                    <h2><img src="<?php echo TB_IMG_URL; ?>/sub03-02-title.png" alt="title" style="width: 30%;"></h2>
                </div><!-- Sub_title Area End -->

                <!-- Section1 Area Start -->
                <div class="section1 sec">
                    <!-- Txt Area Start -->
                    <div class="txt">
                        <h3>주문내역을 확인해 주세요.</h3>
                    </div>
                    <!-- Box Area Start -->
                    <?php
                    $tot_point = 0;
                    $tot_sell_price = 0;
                    $tot_opt_price = 0;
                    $tot_sell_qty = 0;
                    $tot_sell_amt = 0;
                    $seller_id = array();

                    $sql = "	select * from shop_cart  where index_no IN ({$ss_cart_id}) and ct_select = '0'  
						group by gs_id
						order by index_no ";
                    $result = sql_query($sql);
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $gs = get_goods($row['gs_id']);

                        // 합계금액 계산
                        $sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
								SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_supply_price) * ct_qty))) as supply_price,
								SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
								SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
								SUM(io_price * ct_qty) as opt_price
						   from shop_cart
						  where gs_id = '$row[gs_id]'
							and ct_direct = '$set_cart_id'
							and ct_select = '0'";
                        $sum = sql_fetch($sql);

                        $it_name = stripslashes($gs['gname']);
                        $it_options = print_item_options($row['gs_id'], $set_cart_id);


                        if ($is_member) {
                            $point = $sum['point'];
                        }

                        $supply_price = $sum['supply_price'];
                        $sell_price = $sum['price'];
                        $sell_opt_price = $sum['opt_price'];
                        $sell_qty = $sum['qty'];
                        $sell_amt = $sum['price'] - $sum['opt_price'];

                        // 배송비
                        if ($gs['use_aff'])
                            $sr = get_partner($gs['mb_id']);
                        else
                            $sr = get_seller_cd($gs['mb_id']);

                        $info = get_item_sendcost($sell_price);
                        $item_sendcost[] = $info['pattern'];

                        $seller_id[$i] = $gs['mb_id'];

                        $href = TB_SHOP_URL . '/view.php?index_no=' . $row['gs_id'];

                        //상품 구분 오토빌링
                        if ($gs["autobill"] > 0) {
                            $goods_item_count++;
                        }


                        $it_image = get_it_image($gs['index_no'], $gs['simg1'], 258, 258);


                    ?>
                        <!-- LABBIT GA 전자상거래 관련 변수 세팅-->
                        <script>
                            var labbit_products = window.labbit_products || [];
                            labbit_products.push({
                                "item_name": "<?php echo $it_name ?>",
                                'item_id': "<?php echo $row['gs_id']; ?>",
                                'price': "<?php echo $sell_price; ?>",
                                'quantity': "<?php echo $sell_qty; ?>",
                            })
                        </script>
                        <!-- LABBIT GA 전자상거래 관련 변수 세팅-->

                        <div class="txt">
                            <div class="img-box"><?= $it_image ?></div>
                        </div>
                        <div class="box">
                            <input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
                            <input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
                            <input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
                            <input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
                            <input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
                            <input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
                            <input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
                            <input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
                            <dl class="product">
                                <dt>상품</dt>
                                <dd><?= $it_name ?></dd>
                            </dl>
                            <?php if ($it_options) { ?>
                                <dl class="product">
                                    <dt>옵션</dt>
                                    <dd><?= $it_options ?></dd>
                                </dl>
                            <?php } ?>
                            <dl class="price">
                                <dt>금액</dt>
                                <dd><span><?php echo number_format($sell_price); ?></span>원</dd>
                            </dl>
                            <?php if ($gs["autobill"] > 0) {    //상품 구분 오토빌링
                            ?>
                                <dl class="date">
                                    <dt>결제일</dt>
                                    <dd>매월 <?= date("d"); ?>일</dd>
                                </dl>
                            <?php } else { ?>
                                <dl class="date">
                                    <dt>결제일</dt>
                                    <dd><?= date("Y-m-d"); ?></dd>
                                </dl>
                            <?php } ?>
                        </div>
                    <?php
                        $tot_point += (int)$point;
                        $tot_sell_price += (int)$sell_price;
                        $tot_opt_price += (int)$sell_opt_price;
                        $tot_sell_qty += (int)$sell_qty;
                        $tot_sell_amt += (int)$sell_amt;
                    }

                    // 배송비 검사
                    $send_cost = 0;
                    $com_send_cost = 0;
                    $sep_send_cost = 0;
                    $max_send_cost = 0;

                    $k = 0;
                    $condition = array();
                    foreach ($item_sendcost as $key) {
                        list($userid, $bundle, $price) = explode('|', $key);
                        $condition[$userid][$bundle][$k] = $price;
                        $k++;
                    }

                    $com_array = array();
                    $val_array = array();
                    foreach ($condition as $key => $value) {
                        if ($condition[$key]['묶음']) {
                            $com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
                            $max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
                            $com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
                            $val_array[] = max(array_values($condition[$key]['묶음'])); // max value
                        }
                        if ($condition[$key]['개별']) {
                            $sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
                            $com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
                            $val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
                        }
                    }

                    $baesong_price = get_tune_sendcost($com_array, $val_array);

                    $send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
                    $tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
                    $tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
                    $tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액
                    ?>
                </div><!-- Section1 Area End -->

                <!-- Section2 Area Start -->
                <div class="section2 sec">
                    <input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
                    <input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
                    <input type="hidden" name="pt_id" value="<?php echo $mb_recommend; ?>">
                    <input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
                    <input type="hidden" name="coupon_total" value="0">
                    <input type="hidden" name="coupon_price" value="">
                    <input type="hidden" name="coupon_lo_id" value="">
                    <input type="hidden" name="coupon_cp_id" value="">
                    <input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
                    <input type="hidden" name="baesong_price2" value="0">
                    <input type="hidden" name="org_price" value="<?php echo $tot_price; ?>">
                    <?php if (!$is_member || !$config['usepoint_yes']) { ?>
                        <input type="hidden" name="use_point" value="0">
                    <?php } ?>

                    <input type="hidden" name="name" value="<?php echo $member['name']; ?>">
                    <input type="hidden" name="telephone" value="<?php echo $member['telephone']; ?>">
                    <input type="hidden" name="cellphone" value="<?php echo $member['cellphone']; ?>">
                    <input type="hidden" name="zip" value="<?php echo $member['zip']; ?>">
                    <input type="hidden" name="addr1" value="<?php echo $member['addr1']; ?>">
                    <input type="hidden" name="addr2" value="<?php echo $member['addr2']; ?>">
                    <input type="hidden" name="addr3" value="<?php echo $member['addr3']; ?>">
                    <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
                    <input type="hidden" name="email" value="<?php echo $member['id']; ?>">
                    <input type="hidden" name="tot_price" value="<?php echo number_format($tot_price); ?>" readonly>
                    <!-- Txt Area Start -->
                    <div class="txt">
                        <h3>배송정보를 입력해 주세요.</h3>
                    </div>

                    <!-- Pay_info Area Start -->
                    <div class="list_box">
                        <dl>
                            <dt>수령인</dt>
                            <dd><input type="text" name="b_name" id="recipient" value="<?php echo $member['name']; ?>" required></dd>
                        </dl>
                        <dl>
                            <dt>연락처</dt>
                            <dd><input type="text" name="b_cellphone" id="tell" value="<?php echo $member['cellphone']; ?>" required></dd>
                        </dl>
                        <dl>
                            <dt>주소</dt>
                            <dd style="position: relative;">
                                <div class="button-line"><input type="text" name="b_zip" id="zipcode" itemname="주소" required maxLength="5" value="<?php echo $member['zip']; ?>" readonly><a href="javascript:sample3_execDaumPostcode();" class="sub03-02-btn zip-search-btn">주소찾기</a></div>
                                <div class="post_addr2"><input type="text" name="b_addr1" id="addr1" placeholder="기본주소" itemname="기본주소" value="<?php echo $member['addr1']; ?>" required readonly></div>
								<div id="order_zip_wrap" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
									<img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
								</div>
                            </dd>
                        </dl>
                        <dl>
                            <dt>상세주소</dt>
                            <dd>
                                <input type="text" name="b_addr2" id="addr2" value="<?php echo $member['addr2']; ?>" placeholder="상세주소">
                                <input type="hidden" name="b_addr3" value="<?php echo $member['addr3']; ?>">
                                <input type="hidden" name="b_addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
                            </dd>
                        </dl>
                        <dl>
                            <dt>배송 메모 <span>(선택)</span></dt>
                            <dd><input type="text" name="memo" id="meno"></dd>
                        </dl>
                        <div><?php echo $gs['memo_test']; ?></div> 
                        
                    </div>
                </div><!-- Section2 Area End -->

            

                <div class="section3 sec" style="display:none">
                    <div class="txt">
                        <h3>쿠폰 및 포인트 사용</h3>
                    </div>
                    <div class="list_box">
                        <?php
                        if ($is_member && $config['coupon_yes']) { // 보유쿠폰
                            $cp_count = get_cp_precompose($member['id']);
                        ?>
                            <dl>
                                <dt>쿠폰 (사용 가능 쿠폰 <?php echo $cp_count[3]; ?>장</dt>
                                <dd>
                                    <div class="button-line">
                                        <input type="text" name="point" id="dc_amt"><a href="<?php echo TB_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false" class="sub03-02-btn">쿠폰사용</a>
                                    </div>
                                </dd>
                            </dl>
                        <?php } ?>
                        <dl>
                            <dt>포인트</dt>
                            <dd>
                                <div class="point">
                                    <span class="subject">보유포인트</span>
                                    <span class="con"><?php echo display_point($member['point']); ?></span>
                                </div>
                                <div class="point">
                                    <span class="subject">사용포인트:</span>
                                    <input type="text" name="use_point" value="0" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" id="use-point"><a href="javascript:void(0)" onclick="point_all_use();" class="sub03-02-btn">전액사용</a>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- Section3 Area Start -->
                <div class="section4 sec">
                    <!-- Txt Area Start -->
                    <div class="txt">
                        <div class="img-box"><img src="<?php echo TB_IMG_URL; ?>/sub03-02-img2.png" alt="img2"></div>
                        <h3>결제 수단을 선택해주세요.</h3>
                    </div>
                    <!-- Product_pay Area Start -->
                    <ul>
                        <?php
                        $escrow_title = "";
                        if ($default['de_escrow_use']) {
                            $escrow_title = "에스크로 ";
                        }

                        if ($goods_item_count > 0) {

                            //신용카드
                            if ($default['de_card_use']) {
                                if ($member['id'] === 'fromkt@daum.net') {
                                    echo '<li><label for="paymethod_KAKAOPAY"><input type="radio" name="paymethod" id="paymethod_KAKAOPAY" class="screen-hidden" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"><span>KAKAOPAY</span></label></li>' . PHP_EOL;
                                }
                                echo '<li><label for="paymethod_INICIS"><input type="radio" name="paymethod" id="paymethod_INICIS" class="screen-hidden" value="신용카드" onclick="calculate_paymethod(this.value);"><span>신용/체크카드</span></label></li>' . PHP_EOL;
                                //                                    echo '<li><label for="paymethod_card"><input type="radio" name="paymethod" id="paymethod_card" class="screen-hidden" value="신용카드" onclick="calculate_paymethod(this.value);"><span>신용/체크카드( 정기결제)</span></label></li>' . PHP_EOL;
                            }

                            if ($default['de_hp_use']) {
                                echo '<li><label for="paymethod_hp"><input type="radio" name="paymethod" id="paymethod_hp" class="screen-hidden" value="휴대폰" onclick="calculate_paymethod(this.value);"><span>휴대폰(정기결제)</span></label></li>' . PHP_EOL;
                            }
                        } else {


                            if ($is_kakaopay_use) {
                                echo ' <li style="display:none"><label for="kakao_pay" ><input type="radio" name="paymethod" id="kakao_pay" class="screen-hidden"  value="KAKAOPAY" onclick="calculate_paymethod(this.value);"><span>카카오페이</span></label></li>' . PHP_EOL;
                            }
                            if ($default['de_bank_use']) {
                                echo '<li><label for="paymethod_bank"><input type="radio" name="paymethod" id="paymethod_bank" class="screen-hidden" value="무통장" onclick="calculate_paymethod(this.value);"><span>무통장입금</span></label></li>' . PHP_EOL;
                            }

                            if ($default['de_card_use']) {
                                echo '<li><label for="paymethod_card"><input type="radio" name="paymethod" id="paymethod_card" class="screen-hidden" value="신용카드" onclick="calculate_paymethod(this.value);"><span>신용/체크카드</span></label></li>' . PHP_EOL;
                            }

                            if ($default['de_hp_use']) {
                                echo '<li><label for="paymethod_hp"><input type="radio" name="paymethod" id="paymethod_hp" class="screen-hidden" value="휴대폰" onclick="calculate_paymethod(this.value);"><span>휴대폰</span></label></li>' . PHP_EOL;
                            }

                            if ($default['de_iche_use']) {
                                echo '<li><label for="paymethod_iche"><input type="radio" name="paymethod" id="paymethod_iche" class="screen-hidden" value="계좌이체" onclick="calculate_paymethod(this.value);"><span>' . $escrow_title . '계좌이체</span></label></li>' . PHP_EOL;
                            }
                            if ($default['de_vbank_use']) {
                                echo '<li><label for="paymethod_vbank"><input type="radio" name="paymethod" id="paymethod_vbank" class="screen-hidden" value="가상계좌" onclick="calculate_paymethod(this.value);"><span>' . $escrow_title . '가상계좌</span></label></li>' . PHP_EOL;
                            }
                            if ($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point'])) {
                                echo '<li><label for="paymethod_point"><input type="radio" name="paymethod" id="paymethod_point" class="screen-hidden" value="포인트" onclick="calculate_paymethod(this.value);"><span>포인트결제</span></label></li>' . PHP_EOL;
                            }

                            // PG 간편결제
                            if ($default['de_easy_pay_use']) {
                                switch ($default['de_pg_service']) {
                                    case 'lg':
                                        $pg_easy_pay_name = 'PAYNOW';
                                        break;
                                    case 'inicis':
                                        $pg_easy_pay_name = 'KPAY';
                                        break;
                                    case 'kcp':
                                        $pg_easy_pay_name = 'PAYCO';
                                        break;
                                }

                                echo '<li><label for="paymethod_easy_pay" class="' . $pg_easy_pay_name . '"><input type="radio" name="paymethod" id="paymethod_easy_pay" class="screen-hidden" value="간편결제" onclick="calculate_paymethod(this.value);"><span>' . $pg_easy_pay_name . '</span></label></li>' . PHP_EOL;
                            }
                        }
                        ?>
                        <li>
                            <input type="submit" value="다음">
                        </li>
                    </ul>
                </div><!-- Section3 Area End -->
            </div>
        </div>
    </form>
    <script>
        $(function() {
            $("input[name=b_addr2]").focus(function() {
                var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
                if (zip == "")
                    return false;

                var code = String(zip);
                calculate_sendcost(code);
            });

            // 배송지선택
            $("input[name=ad_sel_addr]").on("click", function() {
                var addr = $(this).val();

                if (addr == "1") {
                    gumae2baesong(true);
                } else if (addr == "2") {
                    gumae2baesong(false);
                } else {
                    win_open(tb_shop_url + '/orderaddress.php', 'win_address', 600, 600, 'yes');
                }
            });

            $("select[name=sel_memo]").change(function() {
                $("textarea[name=memo]").val($(this).val());
            });
        });

        // 도서/산간 배송비 검사
        function calculate_sendcost(code) {
            $.post(
                tb_shop_url + "/ordersendcost.php", {
                    zipcode: code
                },
                function(data) {
                    $("input[name=baesong_price2]").val(data);
                    $("#send_cost2").text(number_format(String(data)));

                    calculate_order_price();
                }
            );
        }

        //포인트 전액 사용
        function point_all_use() {

            var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
            var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
            var mb_coupon = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
            var mb_point = parseInt(<?= $member['point'] ?>); //포인트결제
            var tot_price = sell_price + send_cost2 - (mb_coupon);

            if (mb_point > tot_price) {
                $("input[name=use_point]").val(tot_price);
                calculate_temp_point(tot_price);
            } else {
                $("input[name=use_point]").val(mb_point);
                calculate_temp_point(mb_point);
            }
        }

        function calculate_order_price() {
            var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
            var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
            var mb_coupon = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
            var mb_point = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //포인트결제
            var tot_price = sell_price + send_cost2 - (mb_coupon + mb_point);

            $("input[name=tot_price]").val(number_format(String(tot_price)));
        }

        function fbuyform_submit(f) {

            errmsg = "";
            errfld = "";

            var min_point = parseInt("<?php echo $config['usepoint']; ?>");
            var temp_point = parseInt(no_comma(f.use_point.value));
            var sell_price = parseInt(f.org_price.value);
            var send_cost2 = parseInt(f.baesong_price2.value);
            var mb_coupon = parseInt(f.coupon_total.value);
            var mb_point = parseInt(f.mb_point.value);
            var tot_price = sell_price + send_cost2 - mb_coupon;

            if (f.use_point.value == '') {
                alert('포인트사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
                f.use_point.value = 0;
                f.use_point.focus();
                return false;
            }

            if (temp_point > mb_point) {
                alert('포인트사용 금액은 현재 보유포인트 보다 클수 없습니다.');
                f.tot_price.value = number_format(String(tot_price));
                f.use_point.value = 0;
                f.use_point.focus();
                return false;
            }


            if (temp_point > 0 && (mb_point < min_point)) {
                alert('포인트사용 금액은 ' + number_format(String(min_point)) + '원 부터 사용가능 합니다.');
                f.tot_price.value = number_format(String(tot_price));
                f.use_point.value = 0;
                f.use_point.focus();
                return false;
            }

            var paymethod_check = false;
            for (var i = 0; i < f.elements.length; i++) {
                if (f.elements[i].name == "paymethod" && f.elements[i].checked == true) {
                    paymethod_check = true;
                }
            }

            if (!paymethod_check) {
                alert("결제방법을 선택하세요.");
                return false;
            }

            if (typeof(f.od_pwd) != 'undefined') {
                clear_field(f.od_pwd);
                if ((f.od_pwd.value.length < 3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/) != -1))
                    error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
            }

            if (getRadioVal(f.paymethod) == '무통장') {
                check_field(f.bank, "입금계좌를 선택하세요");
                check_field(f.deposit_name, "입금자명을 입력하세요");
            }

            <?php if (!$config['company_type']) { ?>
                if (getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxsave_yes) == 'Y') {
                    check_field(f.tax_hp, "핸드폰번호를 입력하세요");
                }

                if (getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxsave_yes) == 'S') {
                    check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
                }

                if (getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxbill_yes) == 'Y') {
                    check_field(f.company_saupja_no, "사업자번호를 입력하세요");
                    check_field(f.company_name, "상호명을 입력하세요");
                    check_field(f.company_owner, "대표자명을 입력하세요");
                    check_field(f.company_addr, "사업장소재지를 입력하세요");
                    check_field(f.company_item, "업태를 입력하세요");
                    check_field(f.company_service, "종목을 입력하세요");
                }
            <?php } ?>

            if (errmsg) {
                alert(errmsg);
                errfld.focus();
                return false;
            }

            if (getRadioVal(f.paymethod) == '계좌이체') {
                if (tot_price < 150) {
                    alert("계좌이체는 150원 이상 결제가 가능합니다.");
                    return false;
                }
            }

            if (getRadioVal(f.paymethod) == '신용카드') {
                if (tot_price < 1000) {
                    alert("신용카드는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }

            if (getRadioVal(f.paymethod) == '휴대폰') {
                if (tot_price < 350) {
                    alert("휴대폰은 350원 이상 결제가 가능합니다.");
                    return false;
                }
            }

            if (document.getElementById('agree')) {
                if (!document.getElementById('agree').checked) {
                    alert("개인정보 수집 및 이용 내용을 읽고 이에 동의하셔야 합니다.");
                    return false;
                }
            }

            if (!confirm("주문내역이 정확하며, 주문 하시겠습니까?"))
                return false;

            f.use_point.value = no_comma(f.use_point.value);
            f.tot_price.value = no_comma(f.tot_price.value);

            return true;
        }

        function calculate_temp_point(val) {
            var f = document.buyform;
            var temp_point = parseInt(no_comma(f.use_point.value));
            var sell_price = parseInt(f.org_price.value);
            var send_cost2 = parseInt(f.baesong_price2.value);
            var mb_coupon = parseInt(f.coupon_total.value);
            var tot_price = sell_price + send_cost2 - mb_coupon;

            if (val == '' || !checkNum(no_comma(val))) {
                alert('포인트 사용액은 숫자이어야 합니다.');
                f.tot_price.value = number_format(String(tot_price));
                f.use_point.value = 0;
                f.use_point.focus();
                return;
            } else {
                f.tot_price.value = number_format(String(tot_price - temp_point));
            }
        }

        function calculate_paymethod(type) {

            var min_point = parseInt("<?php echo $config['usepoint']; ?>");
            var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
            var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
            var mb_coupon = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
            var temp_point = parseInt(no_comma($("input[name=use_point]").val()));
            var mb_point = parseInt($("input[name=mb_point]").val()); // 보유포인트
            var tot_price = sell_price + send_cost2 - mb_coupon;

            // 포인트잔액이 부족한가?
            if (type == '포인트' && mb_point < tot_price) {
                alert('포인트 잔액이 부족합니다.');

                $("#paymethod_bank").attr("checked", true);
                $("#bank_section").show();
                $("input[name=use_point]").val(0);
                $("input[name=use_point]").attr("readonly", false);
                calculate_order_price();
                <?php if (!$config['company_type']) { ?>
                    $("#tax_section").show();
                <?php } ?>

                return;
            } else {

                if (temp_point > tot_price) {
                    alert('사용가능 포인트가 부족합니다.');
                    $("input[name=use_point]").val(0);
                    $("input[name=use_point]").focus();
                    return;
                }

            }

            if (temp_point > mb_point) {
                alert('포인트사용 금액은 현재 보유포인트 보다 클수 없습니다.');
                $("input[name=use_point]").val(0);
                $("input[name=use_point]").focus();
                return;
            }


            if (temp_point > 0 && (mb_point < min_point)) {
                alert('포인트사용 금액은 ' + number_format(String(min_point)) + '원 부터 사용가능 합니다.');
                $("input[name=use_point]").val(0);
                $("input[name=use_point]").focus();
                return;
            }

            switch (type) {
                case '무통장':
                    $("#bank_section").show();
                    $("input[name=use_point]").val(0);
                    $("input[name=use_point]").attr("readonly", false);
                    calculate_order_price();
                    <?php if (!$config['company_type']) { ?>
                        $("#tax_section").show();
                    <?php } ?>
                    break;
                case '포인트':
                    $("#bank_section").hide();
                    $("input[name=use_point]").val(number_format(String(tot_price)));
                    $("input[name=use_point]").attr("readonly", true);
                    calculate_order_price();
                    <?php if (!$config['company_type']) { ?>
                        $("#tax_section").hide();
                        $(".taxbill_fld").hide();
                        $("#taxsave_3").attr("checked", true);
                        $("#taxbill_2").attr("checked", true);
                    <?php } ?>
                    break;
                default: // 그외 결제수단
                    $("#bank_section").hide();
                    //$("input[name=use_point]").val(0);
                    //$("input[name=use_point]").attr("readonly", false);
                    calculate_order_price();
                    <?php if (!$config['company_type']) { ?>
                        $("#tax_section").hide();
                        $(".taxbill_fld").hide();
                        $("#taxsave_3").attr("checked", true);
                        $("#taxbill_2").attr("checked", true);
                    <?php } ?>
                    break;
            }
        }

        function tax_bill(val) {
            switch (val) {
                case 1:
                    $("#taxsave_fld_1").show();
                    $("#taxsave_fld_2").hide();
                    $(".taxbill_fld").hide();
                    $("#taxbill_2").attr("checked", true);
                    break;
                case 2:
                    $("#taxsave_fld_1").hide();
                    $("#taxsave_fld_2").show();
                    $(".taxbill_fld").hide();
                    $("#taxbill_2").attr("checked", true);
                    break;
                case 3:
                    $("#taxsave_fld_1").hide();
                    $("#taxsave_fld_2").hide();
                    break;
                case 4:
                    $("#taxsave_fld_1").hide();
                    $("#taxsave_fld_2").hide();
                    $(".taxbill_fld").show();
                    $("#taxsave_3").attr("checked", true);
                    break;
                case 5:
                    $(".taxbill_fld").hide();
                    break;
            }
        }

        function coupon_cancel() {
            var f = document.buyform;
            var sell_price = parseInt(no_comma(f.tot_price.value)); // 최종 결제금액
            var mb_coupon = parseInt(f.coupon_total.value); // 쿠폰할인
            var tot_price = sell_price + mb_coupon;

            $("#dc_amt").val(0);
            $("#dc_cancel").hide();
            $("#dc_coupon").show();

            $("input[name=tot_price]").val(number_format(String(tot_price)));
            $("input[name=coupon_total]").val(0);
            $("input[name=coupon_price]").val("");
            $("input[name=coupon_lo_id]").val("");
            $("input[name=coupon_cp_id]").val("");
        }

        // 구매자 정보와 동일합니다.
        function gumae2baesong(checked) {
            var f = document.buyform;

            if (checked == true) {
                f.b_name.value = f.name.value;
                f.b_cellphone.value = f.cellphone.value;
                f.b_telephone.value = f.telephone.value;
                f.b_zip.value = f.zip.value;
                f.b_addr1.value = f.addr1.value;
                f.b_addr2.value = f.addr2.value;
                f.b_addr3.value = f.addr3.value;
                f.b_addr_jibeon.value = f.addr_jibeon.value;

                calculate_sendcost(String(f.b_zip.value));
            } else {
                f.b_name.value = '';
                f.b_cellphone.value = '';
                f.b_telephone.value = '';
                f.b_zip.value = '';
                f.b_addr1.value = '';
                f.b_addr2.value = '';
                f.b_addr3.value = '';
                f.b_addr_jibeon.value = '';

                calculate_sendcost('');
            }
        }

        //gumae2baesong(true);
    </script>
    <!-- } 주문서작성 끝 -->