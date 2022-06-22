<?php
define('_PURENESS_', true);
include_once("./_common.php");

$ca_id = trim($_POST['ca_id']);
$search_txt = trim($_POST['search_txt']);


$len = strlen($ca_id);
$sql_search = " and left(b.gcate,$len)='$ca_id' ";

if ($search_txt != "") {
    $sql_search .= " and a.gname like '%" . $search_txt . "%' or a.keywords like '%" . $search_txt . "%' ";
}

$sql_common = get_sql_precompose($sql_search);
$sql_order = " group by a.index_no ";
$sql_order .= " order by a.rank desc, a.index_no desc ";

$sql = " select a.*, c.catename $sql_common $sql_order ";

$result = sql_query($sql);
$isAdultUser = isAdultUser($member['checked_birthday']);
for ($i = 0; $row = sql_fetch_array($result); $i++) {

    $it_href = TB_SHOP_URL . '/view.php?index_no=' . $row['index_no'];
    $linkClass = "goodsLink ";
    if (($row['is_adult'] && !$isAdultUser)){
        $linkClass .= $is_member ? "confirmAdult" : "confirmMember";
        if($row['is_adult_img']){
            if (is_mobile()) {
                $it_image = get_it_image($row['index_no'], $row['adult_img'], 1024, 605);
            } else {
                $it_image = get_it_image($row['index_no'], $row['adult_img'], 700, 320);
            }
        } else {
            if (is_mobile() == true) {
                $it_image = get_it_image($row['index_no'], $row['simg3'], 1024, 605);
            } else {
                $it_image = get_it_image($row['index_no'], $row['simg1'], 700, 320);
            }
        }
//        if (is_mobile()) {
//            $it_image = "<img src='/theme/basic/img/mobile/check_adult.jpg' width='1024' height='605'>";
//        } else {
//            $it_image = "<img src='/theme/basic/img/check_adult.jpg' width='700' height='320'>";
//        }

    } else {
        if (is_mobile() == true) {
            $it_image = get_it_image($row['index_no'], $row['simg3'], 1024, 605);
        } else {
            $it_image = get_it_image($row['index_no'], $row['simg1'], 700, 320);
        }
    }

    $it_price = get_price($row['index_no']);
    $it_amount = get_sale_price($row['index_no']);
    $it_point = display_point($row['gpoint']);

    // (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
    $it_sprice = $sale = '';

    if ($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
        $sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
        $sale = '<p class="sale">' . number_format($sett, 0) . '<span>%</span></p>';
        $it_sprice = display_price2($row['normal_price']);
    }

    $br = get_brand_arr($row["brand_uid"]);

    // 고객선호도 별점수
    $star_score = get_star_image($row['index_no']);

    // 고객선호도 평점
    $aver_score = ($star_score * 10) * 2;


    $supply_item_gugu = get_item_supply_gugu($row['index_no'], $row['spl_subject']);

    // 주기 가격이 있으면 가격 * 주기
    if ($supply_item_gugu) {
        $month_price = str_replace("원", "", $it_price);
        $month_price = str_replace(",", "", $it_price);
        $month_price = number_format($month_price / $supply_item_gugu) . "원";
    }

    $mod = $i % 2;

    if ($i % 2 == 1) {
        $content_class = "content02";
        $img_box_class = "fr";
        $text_box_class = "fl";
    } else {
        $content_class = "content01";
        $img_box_class = "fl";
        $text_box_class = "fr";
    }

    if ($i === 0) {?>
        <script src="<?php echo TB_JS_URL; ?>/shop.js"></script>
        <script>
            $(function () {
                $(".content.cf .goodsLink").on("click", function () {
                    var $this = $(this);
                    if ($this.hasClass('confirmAdult') || $this.hasClass('confirmMember')) {
                        $('.pop-adult').show()
                        return;
                    }
                    location.href = $this.data('href');
                });
                $(".pop-close").on("click", function () {
                    $('.pop-adult').hide();
                });
            });

            function itemlistwish(gs_id) {
                $.post(
                    tb_shop_url + "/ajax.wishupdate.php", {
                        gs_id: gs_id
                    },
                    function (data) {

                        var iconWish = $('.wish');

                        if (data == 'INSERT') {
                            //$("."+gs_id).attr('class',gs_id+' zzim on');


                            iconWish.addClass('on');
                            iconWish.find('img').attr("src", iconWish.find('img').attr("src").replace("m-icon_wish_dim.png", "m-icon_wish.png"));

                            alert("해당 상품을 찜 하셨습니다.");

                        } else if (data == 'DELETE') {
                            //$("."+gs_id).attr('class',gs_id+' zzim');
                            iconWish.addClass();
                            iconWish.find('img').attr("src", iconWish.find('img').attr("src").replace("m-icon_wish.png", "m-icon_wish_dim.png"));

                            alert("해당 상품을 찜 목록에서 삭제하였습니다.");
                        } else {
                            alert(data);
                        }
                    }
                );
            }
        </script>
            <div class="pop-up pop-adult" style="z-index:99;">
                <div>
                    <?php if (!$is_member) { ?>
                        <div class="cancel-box">
                            <form class="option-form" name="option-form" id="order_cancel_<?= $ct["od_id"] ?>" method="post"
                                  action="<?php echo TB_SHOP_URL; ?>/orderinquirycancel_gugu.php" target="">
                                <p style="text-align: center;line-height:22px;margin-bottom:10px;font-size:1.2rem;">로그인 및 성인인증이 필요한 화면입니다.<br>로그인 하시겠습니까?</p>
                                <div class="pop-up-btn">
                                    <label for="subs-no" class="cancel pop-close">
                                        아니오
                                    </label>
                                    <a href="<?php echo TB_BBS_URL; ?>/login.php" id="subs-yes"><label for="subs-yes" class="order_cancel">
                                              예       
                                    </label> </a>
                                </div>
                            </form>
                        </div>
                    <?php } else { ?>
                        <div class="cancel-box">
                            <form class="option-form" name="option-form" id="order_cancel_<?= $ct["od_id"] ?>" method="post"
                                  action="<?php echo TB_SHOP_URL; ?>/orderinquirycancel_gugu.php" target="">
                                <p style="text-align: center;line-height:22px;margin-bottom:10px;font-size:1.2rem;">성인인증이 필요한 화면입니다.<br>성인인증을 하시겠습니까?</p>
                                <div class="pop-up-btn">
                                    <label for="subs-no" class="cancel pop-close">
                                        아니오
                                    </label>
                                    <label for="subs-yes" class="order_cancel" id="checkAdult">
                                        예
                                    </label>
                                </div>
                            </form>
                        </div>
                    <?php } ?>
                </div>
            </div>
    <?php } ?>

    <div class="<?= $content_class ?> content cf">
        <div class="img-box <?= $img_box_class ?> <?php echo $linkClass; ?>" data-href="<?= $it_href ?>">
            <div class="bottom">
                <?= $it_image ?>
                <div class="thum_sub_text">
                    <div class="thum_box">
                        <div class="left">구독특가</div>
                        <div class="right">무료배송</div>
                    </div>
                </div>
                <div class="goods-logo">
                    <!-- <img src="<?= "/data/brand/" . $br["br_logo"] ?>" alt="<?= $row["brand_nm"] ?>"> -->
                </div>
            </div>
        </div>
        <!-- <div class="sub02-01">
			<div class="icon-box">
				<div class="box">
					<div class="wish <?php if ($wish_count > 0) echo "on"; ?>">
						<?php if ($wish_count > 0) { ?>
							<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish.png" alt="찜하기" class="<?= $wish_class ?>" onclick="itemlistwish('<?php echo $index_no; ?>');">
						<?php } else { ?>
							<img src="<?php echo TB_IMG_URL; ?>/m-icon_wish_dim.png" alt="찜하기" onclick="itemlistwish('<?php echo $index_no; ?>');">
						<?php } ?>
					</div>
				</div>
			</div>
		</div> -->
        <div class="text-box <?= $text_box_class ?>">
            <div class="top">
                <h3 class="<?php echo $linkClass; ?>" data-href="<?= $it_href ?>"><?= $row["gname"] ?></h3>

                <div class="price-box1">
                    <p class="price1"><?= $row["maker"] ?></p>
                    <span class="price_s">구독가</span>
                    <p class="price"><?= $it_price ?></p>
                    <!-- <span class="star"><img src="/img/icon_star01.png" alt="평점"><span class="aver"><?= $star_score ?></span></span>  별입니다.-->
                </div>
            </div>
            <div class="text-con">
                <p><?= stripslashes($row["explan"]) ?></p>
            </div>
        </div>
    </div>
    <?
}
?>