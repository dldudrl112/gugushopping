<?php
if (!defined('_TUBEWEB_')) exit;

$cp    =    get_cp_precompose($member["id"]);
?>
<img src="/img/my-slidebtn.png" alt="" class="content-button">
<div class="mypage-nav m-menu-open">
    <ul class="dep1">
        <li>
            <a href="<?php echo TB_SHOP_URL; ?>/myinfo_manage.skin.php">내정보</a>
            <ul class="dep2">
                <li>
                    <div class="my_dep"><img src="/img/my_in/Information.png"><a href="<?php echo TB_SHOP_URL; ?>/mypage.php">내정보 관리</a></div>
                </li>
                <li>
                    <div class="my_dep"><img src="/img/my_in/History.png"><a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">결제 내역</a></div>
                </li>
                <li style="display:none">
                    <div class="my_dep"><img src="/img/my_in/History.png"><a href="<?php echo TB_SHOP_URL; ?>/wish.php">찜목록</a></div>
                </li>
                <li style="display:none">
                    <a href="<?php echo TB_SHOP_URL; ?>/coupon.php">쿠폰 |<i><?php echo $cp[3]; ?>장</i>
                    </a>
                </li>
                <li style="display:none">
                    <a href="<?php echo TB_SHOP_URL; ?>/point.php">포인트</a>
                </li>
                <!-- <li>
                                <a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">결제 수단 관리</a>
                            </li> -->
                <li>
                    <div class="my_dep"><img src="/img/my_in/Contact.png"><a href="<?php echo TB_BBS_URL; ?>/qna_list.php">1대1 문의하기</a></div>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">고객센터</a>
            <ul class="dep2 dep21">
                <li>
                    <div class="my_dep"><img src="/img/my_in/FAQ.png"><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">FAQ</a></div>
                </li>
                <li>
                    <div class="my_dep"><img src="/img/my_in/Conditions.png"> <a href="<?php echo TB_BBS_URL; ?>/provision.php">이용약관</a></div>
                </li>
                <li>
                    <div class="my_dep"><img src="/img/my_in/check.png"><a href="<?php echo TB_BBS_URL; ?>/policy.php">개인정보 처리방침</a></div>
                </li>
            </ul>
        </li>
    </ul> <!-- dep1 -->
    </div>
    <div style="border-top: 1px solid #f7f7f7;"></div>
    <div class="mypage-nav2 m-menu-open">
    <ul class="text-box">
        <li class="mob1024">고객센터</li>
        <li><?php echo $config['company_hours']; ?></li>
        <li><?php echo $config['company_lunch']; ?></li>
        <li><?php echo $config['company_close']; ?></li>
    </ul>
</div> <!-- mypage-nav -->