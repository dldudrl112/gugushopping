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
        <h3 class="back mob"><a href="javascript:history.back()"><img src="/img/header/back.png"
                                                                      alt="뒤로가기"><span>결제 내역</span></a></h3>
        <div style="border: 1px solid #f9f9f9;"></div>
        <div class="box">
            <h2 class="sub-vis-tit">결제 내역</h2>
            <!-- 구독없을때 -->
        </div>
    </div>
</div>

<div class="sub-visual1">
    <div class="box2">
        <p class="sub-vis-con"><span class="sub-vis-span"><?php echo get_text($member['nickname']); ?></span> 님의 <br>결제
            내역이에요.</p>


    </div>
</div>


<div class="sub-page my-page sub08-01" id="section">
    <div class="container">
        <!-- Mem_info Area Start -->
        <?
        // include_once(TB_THEME_PATH.'/aside_mem_info.skin.php');
        ?>
        <!-- Mem_info Area End -->
        <div class="sub08-01-content-wrap content-wrap">
            <!-- Left_box Area Start -->

            <!-- Left_box Area End -->
            <div class="content-box">
                <h3><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"></a>결제내역
                </h3>
                <ul class="pay-list" style="max-width:580px;">
                    <?php
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {
                        $sql = " select * from shop_cart where od_id = '{$row['od_id']}' ";
                        $sql .= " group by gs_id order by io_type asc, index_no asc ";

                        $res = sql_query($sql);
                        $rowspan = sql_num_rows($res) + 1;
                        for ($k = 0; $ct = sql_fetch_array($res); $k++) {
                            $od = get_order($ct['od_no']);
                            $gs = get_goods($ct['gs_id']);

                            $dlcomp = explode('|', trim($od['delivery']));
                            $href = TB_SHOP_URL . '/view.php?index_no=' . $od['gs_id'];
                            $it_image = get_it_image($gs['index_no'], $gs['simg1'], 135, 135);

                            ?>
                            <li data="<?= $row["od_id"] ?>">
                                <div class="box gugu_<?= $row["od_id"] ?>">
                                    <div class="left"><?= $it_image ?></div>
                                    <div class="right">
                                        <img src="<?php echo TB_IMG_URL; ?>/close-btn.png" alt="close-btn"
                                             class="close-btn">
                                        <h5><?php echo get_text($gs['gname']); ?></h5>
                                        <div class="state-line">
                                            <span class="state"><?php echo $gw_status[$od['dan']]; ?></span>-
                                            <span class="date"><?= str_replace("-", ".", substr($od["od_time"], 0, 10)) ?></span>
                                        </div>
                                        <div class="price-line">
                                            <span class="price"><?php echo display_price($od['use_price']); ?></span>
                                            <?php if (($od["use_point"] + $od["coupon_price"]) > 0) { ?>
                                                <span class="point-discount">(-<?= number_format($od["use_point"] + $od["coupon_price"]) ?>P 사용)</span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <? if ($row["dan"] == "1" || $row["dan"] == "2") { ?>
                                        <!-- <button>주문취소</button>
								<input type="hidden" id="autobill_<?= $row["od_id"] ?>" value="<?= $gs["autobill"]; ?>">
								<input type="hidden" id="gname_<?= $row["od_id"] ?>" value="<?= $gs["gname"]; ?>"> -->
                                    <? } ?>
                                </div>
                            </li>
                            <?php

                        }
                    }

                    if ($i == 0)
                        echo '<li><div class="empty_list">결제내역이 없습니다.</div></li>';
                    ?>
                </ul>
            </div>
            <?php
            echo get_paging2($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?page=');
            ?>

            <div class="pop-up pop_gugu">
                <div class="cancel-box">
                    <p>선택한 결제내역을 정말 삭제하시겠습니까?</p>
                    <div class="pop-up-btn">
                        <label for="subs-no" class="cancel">
                            <input type="radio" name="check" id="subs-no">
                            아니오
                        </label>
                        <label for="subs-yes" class="yes">
                            <input type="radio" name="check" id="subs-yes">
                            예
                        </label>
                    </div>
                </div>
                <div class="payment-cancel-box">
                    <form class="option-form" name="option-form" id="order_cancel" method="post"
                          action="<?php echo TB_SHOP_URL; ?>/orderinquirycancel.php" target="">
                        <input type="hidden" name="od_id" value="<?= $row["od_id"] ?>">
                        <input type="hidden" name="token" value="<?php echo $token; ?>">
                        <input type="hidden" name="autobill" value="">
                        <p>‘<?= $gs["gname"] ?>’을 결제 취소하시겠습니까?</p>
                        <div class="pop-up-btn">
                            <label for="subs-no" class="cancel">
                                <input type="radio" name="check" id="subs-no">
                                아니오
                            </label>
                            <label for="order_cancel_yes" class="order_cancel">
                                <input type="radio" name="check" id="order_cancel_yes">
                                예
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- content-wrap -->
    </div><!-- container -->
</div><!-- section -->
<script>
    $(document).ready(function () {

        //결제내역 삭제
        $('.sub08-01 .content-box .pay-list li .box .right .close-btn').click(function () {
            $('.pop-up > div').hide();
            $('.pop-up .cancel-box').show();
            $('.pop-up').show();

            var od_id = $(this).parents('li').attr("data");

            $("#order_cancel input[name=od_id]").val(od_id);
        });

        $('.pop-up .cancel-box div #subs-yes').click(function () {

            var od_id = $("#order_cancel input[name=od_id]").val();

            $(".pay-list li[data=" + od_id + "]").remove();
            $('.pop-up > div').hide();
            $('.pop-up').hide();

            $.post(
                tb_shop_url + "/ajax.order_update.php",
                {od_id: od_id},
                function (data) {
                    return;
                }
            );
        });

        $('.pop-up .cancel-box div .cancel').click(function () {
            $('.pop-up > div').hide();
            $('.pop-up').hide();
        });


        //내 상품 취소처리
        $('.pay-list  li button ').click(function () {
            $('.pop-up > div').hide();
            $('.pop-up .payment-cancel-box').show();
            $('.pop-up').show();

            var od_id = $(this).parents('li').attr("data");

            $("#order_cancel input[name=od_id]").val(od_id);
            $("#order_cancel input[name=autobill]").val($("#autobill_" + od_id).val());
            $(".pop-up .payment-cancel-box p").text("`" + $("#gname_" + od_id).val() + "`을 결제 취소하시겠습니까?");
        })

        $('.pop-up .payment-cancel-box .cancel').click(function () {
            $('.pop-up > div').hide();
            $('.pop-up').hide();
        })

        $('.pop-up .payment-cancel-box #order_cancel_yes').click(function () {
            $('.pop-up > div').hide();
            $('.pop-up').hide();

            $("#order_cancel").submit();
        })
    });
</script>
