<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH . "/admin_access.php");
include_once(TB_ADMIN_PATH . "/admin_head.php");


$pg_title = ADMIN_MENU6;
$pg_num = 6;
$snb_icon = "<i class=\"ionicons ion-clipboard\"></i>";

if ($member['id'] != 'admin' && !$member['auth_' . $pg_num]) {
    alert("접근권한이 없습니다.");
}

if ($code == "list") $pg_title2 = ADMIN_MENU6_01;
if ($code == "1") $pg_title2 = ADMIN_MENU6_02;
if ($code == "2") $pg_title2 = ADMIN_MENU6_03;
if ($code == "3") $pg_title2 = ADMIN_MENU6_04;
if ($code == "4") $pg_title2 = ADMIN_MENU6_05;
if ($code == "5") $pg_title2 = ADMIN_MENU6_06;
if ($code == "delivery") $pg_title2 = ADMIN_MENU6_07;
if ($code == "delivery_update") $pg_title2 = ADMIN_MENU6_07;
if ($code == "6") $pg_title2 = ADMIN_MENU6_08;
if ($code == "9") $pg_title2 = ADMIN_MENU6_09;
if ($code == "7") $pg_title2 = ADMIN_MENU6_10;
if ($code == "8") $pg_title2 = ADMIN_MENU6_11;
if ($code == "memo") $pg_title2 = ADMIN_MENU6_12;
if ($code == "billing") $pg_title2 = ADMIN_MENU6_13;

include_once(TB_ADMIN_PATH . "/admin_topmenu.php");

include_once(TB_PATH . "/classes/Subscription.php");
$Subscription = Subscription::getInstance();
$subscription = $Subscription->show($id);
if (empty($subscription)) {
    alert("구독정보가 존재하지 않습니다.");
}
//$subscription['order_data'] = $Subscription->decodeOrderData($subscription['new_order_data']);

$subscription['member_id'] = $subscription['member_id'] ? $subscription['member_id'] : "비회원";

//$amount = get_order_spay($od_id); // 결제정보 합계
//$default = set_partner_value($od['od_settle_pid']); // 가맹점 PG결제 정보

$tb['title'] = "구독정보 수정";
include_once(TB_ADMIN_PATH . "/admin_head.php");

//$pg_anchor = '<ul class="anchor">
//<li><a href="#anc_sodr_detail">구독상세 내용</a></li>
//<li><a href="#anc_sodr_list">해당 구독 주문 리스트</a></li>
//<li><a href="#anc_sodr_memo">관리자메모</a></li>
//</ul>';

//
//$isCreate= Subscription::getInstance()
//    ->setOrderById(21082022181605)
//    ->store()
//    ->syncSubscriptionId();



?>
    <style>
        #anc_sodr_detail {
            margin: 0 20px;
        }
    </style>
    <div id="sodr_pop" class="s_wrap">
        <h1><?php echo $tb['title']; ?></h1>

        <section id="anc_sodr_detail">
            <?php
            //print_r($subscription);
//            $Subscription->update(['pg_id'=> 'TCSUBSCRIP'], $subscription['id']);
//            echo $Subscription->getError();
            if($Subscription->isTestPg()){
            ?>
                <div class="od_test_caution">테스트 결제입니다!!</div>
            <?php
            }
            ?>

            <h4 class="anc_tit">구독상품 목록</h4>
            <form name="frmorderform" method="post" action="./subscription_update.php"
                  onsubmit="return form_submit(this);">
                <input type="hidden" name="id" value="<?php echo $subscription['id']; ?>">
                <input type="hidden" name="mod_type" value="subscription">

                <div class="tbl_head01">
                    <table id="sodr_list">
                        <colgroup>
                            <col class="w120">
                            <col class="w120">
                            <col>
                            <col class="w100">
                            <col class="w100">
                            <col class="w70">
                            <col class="w100">
                            <col class="w50">
                            <col class="w80">
                            <col class="w100">
                            <col class="w80">
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="col">구독일시</th>
                            <th scope="col">구독번호</th>
                            <th scope="col">상품명</th>
                            <th scope="col">다음 결제일</th>
                            <th scope="col">다음 주기일</th>
                            <th scope="col">옵션</th>
                            <th scope="col">구독금액</th>
                            <th scope="col">주기</th>
                            <th scope="col">구독상태</th>
                            <th scope="col">주문자</th>
                            <th scope="col">결제방법</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        <tr class="list0">
                            <td>
                                <input type="hidden" name="id" value="<?php echo $subscription['id']; ?>">
                                <?php echo substr($subscription['created_at'],0, -3); ?>
                            </td>
                            <td>
                                <?php echo $subscription['id']; ?>
                            </td>
                            <td class="tal">
                                <a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $subscription['gs_id']; ?>"
                                   target="_blank"><?php echo get_text($subscription['order_data']['goods']['gname']); ?></a>
                            </td>
                            <td>
                                <input type="date" name="next_billing_date" id="next_billing_date" value="<?php echo $subscription['next_billing_date']; ?>" data-before="<?php echo $subscription['next_billing_date'];?>">
                            </td>
                            <td>
                                <select name="billing_day" id="billing_day" class="frm_input">
                                    <?php for($day=1;$day<=31;$day++){?>
                                        <option value="<?php echo $day;?>" <?php echo (int)$subscription['billing_day']===$day?'selected':'';?>>매월 <?php echo $day;?>일</option>
                                    <?php }?>
                                </select>
                            </td>
                            <td>
                                <select name="io_id" id="io_id" class="frm_input">
                                <?php
                                foreach($Subscription->getGoodsOptions($subscription['gs_id']) as $io){
                                    $selected= $subscription['io_id']===$io['io_id'] ? 'selected':'';
                                    echo "<option value='{$io['io_id']}' {$selected} data-payment_cycle='{$io['payment_cycle']}'>{$io['io_id']}</option>";
                                }
                                ?>
                                </select>
                            </td>
                            <td><?php echo number_format($subscription['price']); ?></td>
                            <td class="tar">
                                <select name="payment_cycle" id="payment_cycle" class="frm_input">
                                    <?php for ($cycle = 0; $cycle <= 12; $cycle++) { ?>
                                        <option value="<?php echo $cycle; ?>" <?php echo $subscription['payment_cycle'] == $cycle ? 'selected' : ''; ?>><?php echo $cycle; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td class="tar">
                                <select name="is_subscribed" class="frm_input">
                                    <option value="0" <?php echo empty($subscription['is_subscribed']) ? 'selected' : ''; ?>>
                                        구독취소
                                    </option>
                                    <option value="1" <?php echo $subscription['is_subscribed'] === '1' ? 'selected' : ''; ?>>
                                        구독중
                                    </option>
                                </select>
                            </td>
                            <td class="tar"><?php echo $subscription['order_data']['name']; ?></td>
                            <td class="td_price"><?php echo $subscription['pg']; ?></td>
                        </tr>
                        <?php
                        $chk_cnt++;
                        //                            if ($row['dan'] == 1) $chk_count1++;
                        //                            if ($row['dan'] == 2) $chk_count2++;
                        //                            if ($row['dan'] == 5) $chk_count5++;

                        //                            // 취소.반품.교환.환불 수
                        //                            if (in_array($row['dan'], array(6, 7, 8, 9))) {
                        //                                $chk_cancel++;
                        //                            }

                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="od_test_caution">'다음 주기일'은 '다음 결제일' 다음 날짜를 지정할때 사용합니다.</div>
                <div class="btn_list marb20">
                    <input type="submit" name="act_button" value="구독상세저장" class="btn_lsmall red"
                           onclick="document.pressed=this.value">
                </div>
            </form>
        </section>


        <section id="anc_sodr_list">
            <h4 class="anc_tit">해당 구독 주문 리스트</h4>

            <div class="tbl_head01">
                <table id="sodr_list">
                    <colgroup>
                        <col class="w150">
                        <col>
                        <col class="w150">
                        <col class="w90">
                        <col class="w90">
                        <col class="w90">
                        <col class="w90">
                        <col class="w120">
                    </colgroup>
                    <thead>
                    <tr>
                        <th scope="col">주문번호</th>
                        <th scope="col">주문상품</th>
                        <th scope="col">판매자</th>
                        <th scope="col">상품금액</th>
                        <th scope="col">배송비</th>
                        <th scope="col">쿠폰할인</th>
                        <th scope="col">포인트결제</th>
                        <th scope="col">실결제액</th>
                    </tr>
                    </thead>
                    <tbody class="list">
                    <?php
                    $chk_cnt = 0; // 전체 배열
                    $chk_count1 = 0; // 입금대기 수
                    $chk_count2 = 0; // 입금완료 수
                    $chk_count5 = 0; // 배송완료 수
                    $chk_cancel = 0; // 클래임 수
                    $sum_point = 0; // 포인트적립

                    $sql = sprintf(" select O.*, S.new_order_data from shop_order as O inner join %s as S on O.subscription_id = S.id where S.id = '$id' order by O.od_time desc, O.index_no asc ", Subscription::$tableName);
                    $result = sql_query($sql);
                    for ($i = 0; $row = sql_fetch_array($result); $i++) {

                        $gs = unserialize($row['od_goods']);
//                        $it_options = print_complete_options($row['gs_id'], $row['od_id']);
//                        if ($it_options) {
//                            $it_options = '<div class="sod_opt">' . $it_options . '</div>';
//                        }
                        //print_r(json_decode(htmlspecialchars_decode($row['new_order_data']), true));
                        // 취소.반품.환불 외
                        if (!in_array($row['dan'], array(6, 7, 9))) {
                            $sum_point += (int)$row['sum_point'];
                        }

                        $bg = 'list' . ($i % 2);
                        ?>
                        <tr class="<?php echo $bg; ?>">
                            <td>
                                <input type="hidden" name="id[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>">
                                <a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a>
                            </td>
                            <td class="tal">
                                <a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row['gs_id']; ?>"
                                   target="_blank"><?php echo get_text($gs['gname']); ?></a>
                                <?php if ($row['od_tax_flag'] && !$gs['notax']) echo '[비과세상품]'; ?>
                                <?php //echo $it_options; ?>
                            </td>
                            <td><?php echo get_order_seller_id($row['seller_id']); ?></td>
                            <td class="tar"><?php echo number_format($row['goods_price']); ?></td>
                            <td class="tar"><?php echo number_format($row['baesong_price']); ?></td>
                            <td class="tar"><?php echo number_format($row['coupon_price']); ?></td>
                            <td class="tar"><?php echo number_format($row['use_point']); ?></td>
                            <td class="td_price"><?php echo number_format($row['use_price']); ?></td>
                        </tr>
                        <?php
                        $chk_cnt++;
                        if ($row['dan'] == 1) $chk_count1++;
                        if ($row['dan'] == 2) $chk_count2++;
                        if ($row['dan'] == 5) $chk_count5++;

                        // 취소.반품.교환.환불 수
                        if (in_array($row['dan'], array(6, 7, 8, 9))) {
                            $chk_cancel++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="anc_sodr_memo">
            <h3 class="anc_tit">관리자메모</h3>
            <div class="local_desc02 local_desc">
                <p>현재 열람 중인 구독에 대한 내용을 메모하는곳입니다.</p>
            </div>

            <form name="frmorderform3" action="./subscription_update.php" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="mod_type" value="memo">

                <label for="shop_memo" class="sound_only">관리자메모</label>
                <textarea name="memo" id="memo" rows="8"
                          class="frm_textbox"><?php echo stripslashes($subscription['memo']); ?></textarea>

                <div class="btn_confirm">
                    <input type="submit" value="관리자메모 수정" class="btn_medium">
                    <a href="./order.php?code=billing" class="btn_medium bx-white">목록</a>
                </div>
            </form>
        </section>

    </div>

    <script>
        $(function () {
            $("#io_id").on("change", function(e){
                var value= parseInt($(this).find('option:selected').data('payment_cycle'));
                var payment_cycle= parseInt($("#payment_cycle").val());
                if(value===payment_cycle) return;

                if(!confirm('주기값도 변경하시겠습니까?')){
                    return;
                }
                $("#payment_cycle").val(value)
            });
            $("#next_billing_date").on("blur", function(e){
                var $this= $(this);
                var date= $this.val();
                var beforeDate= $this.data('before');
                var beforeDay= parseInt(beforeDate.substr(-2));
                var currentDay= parseInt(date.substr(-2));
                var $billingDay= $("#billing_day");
                $this.data('before', date);

                if(currentDay !==beforeDay && currentDay !== parseInt($billingDay.val()) && confirm('다음 주기일도 변경하시겠습니까?')){
                    $billingDay.val(currentDay);
                }
            });
            // 부분취소창
            $(".orderpartcancel").on("click", function () {
                var href = this.href;
                window.open(href, "partcancelwin", "left=100, top=100, width=600, height=350, scrollbars=yes");
                return false;
            });
        });

        function form_submit(f) {
            if (confirm("구독정보를 변경하시겠습니까?")) {
                return true;
            } else {
                return false;
            }
        }
    </script>

<?php
include_once(TB_ADMIN_PATH . "/admin_tail.php");
?>