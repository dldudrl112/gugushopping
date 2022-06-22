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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100;300;400;500;700;900&display=swap"
          rel="stylesheet">
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
            $(document).ready(function () {
                $(document).bind("contextmenu", function (e) {
                    return false;
                });
            });
            $(document).bind('selectstart', function () {
                return false;
            });
            $(document).bind('dragstart', function () {
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
<body <?php echo isset($tb['body_script']) ? $tb['body_script'] : ''; ?>>


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
                <input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="구독하고 싶은 상품을 검색해 보세요."
                       value="<?= $_REQUEST["ss_tx"] ?>">
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
        <a href="<?php echo TB_BBS_URL; ?>/alarm.php" class="alarm-btn mob1024"><img
                    src="<?php echo TB_IMG_URL; ?>/m-main-alarm.png" alt="alarm"></a>
    </div>
</header><!-- Header Area End -->


<div class="header_mobile">
    <div class="sub_visual">
        <h3 class="back mob"><a href="<?php echo TB_SHOP_URL; ?>/myinquiry.php"><img src="/img/header/back.png"
                                                                      alt="뒤로가기"><span>구독 상세</span></a></h3>
        <div style="border: 1px solid #f9f9f9;"></div>
        <div class="box">
            <h2 class="sub-vis-tit">구독 상세</h2>
            <!-- 구독없을때 -->
        </div>
    </div>
</div>


<?php

$arr_b_cellphone = explode("|", $od["b_cellphone"]);
$it_image = get_it_image($gs['index_no'], $gs['simg1'], 145, 145);

?>

<style>
    .img-box {
        max-width: 30%;
    }

    .img-box img {
        width: 100%;
        height: auto
    }

    .l-subscription {
        margin-top: 30px;
    }

    .l-subscription .box {
        padding-left: 20px;
    }

    .l-subscription .box .g_name {
        margin-bottom: 20px;
        display: block;
    }

    .l-subscription .box p {
        margin-bottom: 4px;
    }

    .l-subscription .box p:last-child {
        margin-bottom: 0;
    }

    .d-flex {
        display: flex;
    }


    .l-line {
        background-color: #f7f7f7;
        height: 6px;
    }

    .sub04-04-content-wrap select {
        display: block;
        border: 1px solid #d9d9d9;
        padding: 5px 10px;
        color: #999;
        box-sizing: border-box;
        appearance: none;
    }

    .container h3 {
        margin: 20px 0;
        font-size: 14px;
        font-family: 'AppleSDgothicNeoB';
        font-style: normal;
        font-weight: normal;
        color: #222;
        letter-spacing: -0.01em;
    }

    .container h3 span {
        color: #888888;
        font-size: 13px;
        margin-left: 13px;
    }

    ul.l-info li:first-child .input-box {
        margin-left: 15px;
    }

    ul.l-info li:first-child {
        padding-bottom: 10px;
        border-bottom: 1px solid #f3f3f3;
    }

    .sub04-04-content-wrap ul li .input-box .input-box-box span {
        color: #888888;
        margin-left: 5px;
    }

    label.form-check-label span {
        font-size: 17px;
        color: #3c3c3c;;
    }

    label.form-check-label input[type='checkbox'] + span::before {
        content: '';
        display: inline-block;
        vertical-align: middle;
        width: 25px;
        height: 25px;
        margin-right: 15px;
        background: url(/img/icon_radio.png) no-repeat center / cover;
    }

    label.form-check-label input[type='checkbox']:checked + span::before {
        background: url(/img/icon_radio_on.png) no-repeat center / cover;
    }

    @media screen and (max-width: 1024px) {
        label.form-check-label span {
            font-size: 20px;
        }

        label.form-check-label input[type='checkbox'] + span::before {
            width: 30px;
            height: 30px;
        }
    }

    @media screen and (max-width: 600px) {
        label.form-check-label span {
            font-size: 16px;
        }

        label.form-check-label input[type='checkbox'] + span::before {
            width: 20px;
            height: 20px;
        }

    }
</style>

<div class="sub-page21 sub04-04 sub04" id="section">
    <div class="container" style="width: 90%;">
        <div class="sub-visual21">
            <h3 class="back mob"><span>주문번호</span><span class="number"><?php echo $od['od_no'] ?></span></a></h3>
            <?php
            $subscription = Subscription::getInstance()->row;
            $next_date_day = substr($subscription['created_at'], 0, 10);
            ?>
            <div class="l-subscription d-flex">
                <div class="img-box"><?= $it_image ?></div>
                <div class="box">
                    <a class="g_name"
                       href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $od['gs_id']; ?>"><?php echo get_text($gs['gname']); ?></a>
                    <p><span class="gu_sang">구독가</span><span
                                class="date1"> <?= number_format($od["goods_price"]) ?>원</span>
                    </p>
                    <p><span class="gu_sang">주기</span><span
                                class="date2"> <?= number_format($subscription["payment_cycle"]) ?>개월</span></p>
                    <!--                        <p><span class="gu_sang">다음결제일</span><span-->
                    <!--                                    class="date1">-->
                    <?php //echo $row['next_billing_date']; ?><!--</span></p>-->
                    <!-- <h2 class="sub-vis-tit">내구독</h2> -->
                    <!-- <p class="sub-vis-con">아직 내가 구독한 구구가 없습니다!</p> -->
                </div>
            </div>
        </div><!-- sub-visual -->
    </div>

    <div class="l-line"></div>


    <form id="buyform" name="buyform" method="post" action="<?= $action_url ?>">

        <div class="container">
            <h3>구독정보<span>변경된 구독 정보는 다음 구독부터 적용됩니다.</span></h3>


            <div class="sub04-04-content-wrap">
                <input type="hidden" name="od_id" value="<?= $od_id ?>">
                <ul class="l-info">
                    <li>
                        <span class="input_span" style="width: 50px;">옵션</span>
                        <div class="input-box">
                            <?php
                            $temp = sql_fetch("select payment_cycle from shop_goods_option where gs_id= '{$subscription['gs_id']}' and io_id ='{$subscription['io_id']}'");
                            $paymentCycle = $temp['payment_cycle'];
                            //Subscription::getInstance()->update(['payment_cycle'=> $paymentCycle]);
                            $options = Subscription::getInstance()->getGoodsOptionsByRow();
                            if ($paymentCycle > 0 && count($options) > 0) {
                                ?>
                                <select name="io_id" class="wfull">
                                    <?php foreach ($options as $opt) {
                                        $optNames = explode(chr(30), $opt['io_id']);
                                        array_shift($optNames);
                                        ?>
                                        <option value="<?php echo $opt['io_id']; ?>"><?php echo implode("/", $optNames); ?></option>
                                    <?php } ?>
                                </select>
                            <?php } ?>
                        </div>
                    </li>
                    <li>
                        <span class="input_span" style="width: 50px;">주문자</span>
                        <div class="input-box">
                            <p class="input-box-box1"><?= $order["b_name"] ?></p>
                            <!-- <input type="text" name="b_name" id="b_name" value="<?= $order["b_name"] ?>" placeholder="받는사람" disabled> -->
                        </div>
                    </li>
                    </li>
                    <?php
                    if (count($arr_b_cellphone) > 0) {

                        for ($i = 0; $i < count($arr_b_cellphone); $i++) {

                            if (trim($arr_b_cellphone[$i])) {
                                ?>
                                <li>
                                    <div class="icon-box"></div>
                                    <div class="input-box">
                                        <input type="text" name="cellphone[]" id="del-phone"
                                               value="<?= $arr_b_cellphone[$i] ?>" placeholder="휴대폰 번호" disabled>
                                        <?php if ($i == 0) { ?>
                                            <!-- <a href="javascript:void(0)" class="plus">+</a> -->
                                        <?php } else { ?>
                                            <!-- <a href="javascript:void(0)" class="min">-</a> -->
                                        <?php } ?>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <li>
                            <div class="icon-box"></div>
                            <div class="input-box">
                                <input type="text" name="cellphone[]" id="del-phone" value="" placeholder="휴대폰 번호"
                                       disabled>
                                <!-- <a href="javascript:void(0)" class="plus">+</a> -->
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="">
                        <div class="icon-box"></div>
                        <div class="input-box-wrap">
                            <div class="input-box">
                                <p class="input-box-box1">(<?= $order["b_zip"] ?>
                                    ) <?= $order["b_addr1"] ?> <?= $order["b_addr2"] ?></p>
                            </div>
                        </div>
                </ul>

                <div class="line-gu"></div>

                <ul>
                    <li>
                        <span class="input_span" style="width: 50px;">배송지</span>
                        <div class="input-box">
                            <input type="text" name="b_name" id="b_name" value="<?php echo $order["b_name"]; ?>"
                                   placeholder="<?= $order["b_name"] ?>">
                        </div>
                    </li>
                    </li>
                    <?php
                    if (count($arr_b_cellphone) > 0) {

                        for ($i = 0; $i < count($arr_b_cellphone); $i++) {

                            if (trim($arr_b_cellphone[$i])) {
                                ?>
                                <li>
                                    <div class="icon-box"></div>
                                    <div class="input-box">
                                        <input type="text" name="cellphone[]" id="del-phone"
                                               value="<?php echo $arr_b_cellphone[$i]; ?>"
                                               placeholder="<?= $arr_b_cellphone[$i] ?>">
                                        <?php if ($i == 0) { ?>
                                            <!-- <a href="javascript:void(0)" class="plus">+</a> -->
                                        <?php } else { ?>
                                            <!-- <a href="javascript:void(0)" class="min">-</a> -->
                                        <?php } ?>
                                    </div>
                                </li>
                                <?php
                            }
                        }
                    } else {
                        ?>
                        <li>
                            <div class="icon-box"></div>
                            <div class="input-box">
                                <input type="text" name="cellphone[]" id="del-phone" placeholder="휴대폰 번호">
                                <!-- <a href="javascript:void(0)" class="plus">+</a> -->
                            </div>
                        </li>
                        <?php
                    }
                    ?>
                    <li class="zipcode-line">
                        <div class="icon-box"></div>
                        <div class="input-box-wrap">
                            <div class="input-box">
                                <input type="hidden" name="b_zip" id="b_zip" value="<?= $order["b_zip"] ?>">
                                <input type="text" name="b_addr1" id="b_addr1"
                                       placeholder="(<?= $order["b_zip"] ?>) <?= $order["b_addr1"] ?>" readonly>
                                <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');"
                                   class="zip-search-btn"><img src="<?php echo TB_IMG_URL; ?>/icon-zipcodesearch.png"
                                                               alt="" style="width: 14px;height: auto;"></a>
                            </div>
                            <div class="input-box zipcode2-box">
                                <input type="text" name="b_addr2" id="b_addr2" placeholder=" <?= $order["b_addr2"] ?>">
                                <input type="hidden" name="b_addr3" id="b_addr3">
                                <input type="hidden" name="b_addr_jibeon" id="b_addr_jibeon">
                            </div>
                            <div style="clear:both;padding:5px 15px;">
                                <label for="delivery01" class="form-check-label"><input type="checkbox"
                                                                                        name="delivery"
                                                                                        id="delivery01"
                                                                                        value="1"><span>기본배송지로 저장</span></label>

                            </div>

                        </div>
                    </li>
                </ul>


                <ul>
                    <li>
                        <span class="input_span" style="width: 50px;">요청사항</span>
                        <div class="input-box">
                            <input type="text" name="memo" value="<?php echo $order['memo']; ?>" placeholder="없음">
                            <!-- <a href="javascript:void(0)" class="plus">+</a> -->
                        </div>
                    </li>

                </ul>
                <!-- </div> -->
            </div>
        </div>
        <div class="l-line"></div>
        <div class="container">
            <div>

                <?php if (!empty($subscription)) { ?>
                    <div class="">

                        <div class="sub04-04-content-wrap">

                            <ul>
                                <li>
                                    <span class="input_span">결제 정보</span>
                                    <div class="input-box">
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-box"><span>결제수단</span></div>
                                    <div class="input-box">
                                        <p class="input-box-box"><?php echo $od['paymethod'] ?></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-box"><span>월납부</span></div>
                                    <div class="input-box">
                                        <p class="input-box-box"><?= $subscription["price"] ?>원</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-box"><span>다음결제</span></div>
                                    <div class="input-box">
                                        <p class="input-box-box"><?php echo $subscription['next_billing_date']; ?>결제
                                            예정 <span>(주기-<?php echo $subscription['payment_cycle']; ?>개월)</span></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon-box"><span>시작일</span></div>
                                    <div class="input-box">
                                        <p class="input-box-box"><?php echo substr($subscription['created_at'], 0, 10); ?></p>
                                    </div>
                                </li>
                            </ul>
                            <div class="line-gu"></div>

                        </div>
                    </div>
                <?php } ?>
                <div class="btn-box" style="padding: 20px 0!important; border-bottom: 0px solid #e0e0e0; display:flex;">
                    <a href="javascript:frm_sumbt(document.buyform);" class="btn01_sang">수정하기</a>
                    <!-- <a href="javascript:history.back()" class="btn01_sang">취소</a> -->
                </div>

            </div>
        </div>
    </form>
</div>

<script>

    function frm_sumbt(f) {

        if (f.b_name.value == "") {
            alert("받으실 분 이름을 입력해주세요.");
            f.b_name.focus();
            return;
        }

        if (f.b_addr1.value == "") {
            alert("받으실 분 주소를 입력해주세요.");
            f.b_addr1.focus();
            return;
        }

        if (f.b_addr2.value == "") {
            alert("받으실 분 상세 주소를 입력해주세요.");
            f.b_addr2.focus();
            return;
        }

        if ($("input[name='cellphone[]']").length > 0) {
            var cellphone_cnt = 0;


            for (var i = 0; i < $("input[name='cellphone[]']").length; i++) {

                if ($("input[name='cellphone[]']:eq(" + i + ")").val() != "") {
                    cellphone_cnt++;
                }
            }

            if (cellphone_cnt == 0) {
                alert("받으실 분 연락처를 입력해주세요.");
                return;
            }

        }

        f.submit();

    }
</script>