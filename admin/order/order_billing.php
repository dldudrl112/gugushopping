<?php
if (!defined('_TUBEWEB_')) exit;

$sodrr = admin_order_status_sum("where dan > 0 "); // 총 주문내역
$sodr1 = admin_order_status_sum("where dan = 1 "); // 총 입금대기
$sodr2 = admin_order_status_sum("where dan = 2 "); // 총 입금완료
$sodr3 = admin_order_status_sum("where dan = 3 "); // 총 배송준비
$sodr4 = admin_order_status_sum("where dan = 4 "); // 총 배송중
$sodr5 = admin_order_status_sum("where dan = 5 "); // 총 배송완료
$sodr6 = admin_order_status_sum("where dan = 6 "); // 총 입금전 취소
$sodr7 = admin_order_status_sum("where dan = 7 "); // 총 배송후 반품
$sodr8 = admin_order_status_sum("where dan = 8 "); // 총 배송후 교환
$sodr9 = admin_order_status_sum("where dan = 9 "); // 총 배송전 환불
$final = admin_order_status_sum("where dan = 5 and user_ok = 0 "); // 총 구매미확정


if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sel_field = "created_at";
if (isset($sel_field)) $qstr .= "&sel_field=$sel_field";
if (isset($od_settle_case)) $qstr .= "&od_settle_case=" . urlencode($od_settle_case);
if (isset($od_status)) $qstr .= "&od_status=$od_status";
if (isset($od_final)) $qstr .= "&od_final=$od_final";
if (isset($od_taxbill)) $qstr .= "&od_taxbill=$od_taxbill";
if (isset($od_taxsave)) $qstr .= "&od_taxsave=$od_taxsave";
if (isset($od_memo)) $qstr .= "&od_memo=$od_memo";
if (isset($od_shop_memo)) $qstr .= "&od_shop_memo=$od_shop_memo";
if (isset($od_receipt_point)) $qstr .= "&od_receipt_point=$od_receipt_point";
if (isset($od_coupon)) $qstr .= "&od_coupon=$od_coupon";
if (isset($od_escrow)) $qstr .= "&od_escrow=$od_escrow";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string . "&page=$page";
include_once(TB_PATH . "/classes/Subscription.php");
$sql_common = " from " . Subscription::$tableName;

$where = array();


if ($sfl && $stx) {
    switch ($sfl) {
        case 'id':
            $where[] = " $sfl = '{$stx}' ";
            break;
        case 'gs_id':
            $temp = sql_fetch("select 'all' as x, group_concat(index_no) as idx from shop_goods where gname like '{$stx}%' group by x");
            $where[] = empty($temp['idx']) ? " 0 " : " $sfl in ({$temp['idx']}) ";
            break;
        case 'member_id':
            $temp = sql_fetch("select 'all' as x, group_concat(index_no) as idx from shop_member where id like '{$stx}%' group by x");
            $where[] = empty($temp['idx']) ? " 0 " : " $sfl in ({$temp['idx']}) ";
            break;
        case 'b_cellphone':
            $temp = sql_fetch("select 'all' as x, group_concat(S.id) as idx from shop_order O inner join " . Subscription::$tableName . " as S on O.subscription_id = S.id where O.b_cellphone like '%{$stx}%' group by x");
            $where[] = empty($temp['idx']) ? " 0 " : " id in ({$temp['idx']}) ";
            break;
        case 'b_name':
            $temp = sql_fetch("select 'all' as x, group_concat(S.id) as idx from shop_order O inner join " . Subscription::$tableName . " as S on O.subscription_id = S.id where O.b_name like '{$stx}%' group by x");
            $where[] = empty($temp['idx']) ? " 0 " : " id in ({$temp['idx']}) ";
            break;
        case 'name':
            $temp = sql_fetch("select 'all' as x, group_concat(S.id) as idx from shop_order O inner join " . Subscription::$tableName . " as S on O.subscription_id = S.id where O.name like '{$stx}%' group by x");
            $where[] = empty($temp['idx']) ? " 0 " : " id in ({$temp['idx']}) ";
            break;
        default:
            $where[] = " $sfl like '%$stx%' ";
            break;
    }
}

if (strlen((string)$is_subscribed) > 0) {
    $where[] = "is_subscribed= {$is_subscribed}";
}

if ($fr_date && $to_date)
    $where[] = " {$sel_field} between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if ($fr_date && !$to_date)
    $where[] = " {$sel_field} between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if (!$fr_date && $to_date)
    $where[] = " {$sel_field} between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if ($where) {
    $sql_search = ' where ' . implode(' and ', $where);
}

$sql_group = "";
$sql_order = " order by id desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select * {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

if ($_SESSION['ss_page_rows'])
    $page_rows = $_SESSION['ss_page_rows'];
else
    $page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if ($page == "") {
    $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page - 1) * $rows);

$sql = " select * {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$tot_orderprice = 0; // 총주문액

include_once(TB_PLUGIN_PATH . '/jquery-ui/datepicker.php');


$btn_frmline = '';
?>
<style>
    table tr.warning {
        background-color: #fcb8b8;
    }
</style>
<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">검색어</th>
                <td>
                    <select name="sfl">
                        <?php echo option_selected('id', $sfl, '구독번호'); ?>
                        <?php echo option_selected('gs_id', $sfl, '상품명'); ?>
                        <?php echo option_selected("member_id", $sfl, '회원아이디'); ?>
                        <?php echo option_selected('name', $sfl, '주문자명'); ?>
                        <?php echo option_selected('b_name', $sfl, '받는분'); ?>
                        <?php echo option_selected('b_cellphone', $sfl, '받는분핸드폰'); ?>
                    </select>
                    <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
                </td>
            </tr>
            <tr>
                <th scope="row">기간검색</th>
                <td>
                    <?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
                </td>
            </tr>

            <tr>
                <th scope="row">구독상태</th>
                <td>
                    <?php echo radio_checked('is_subscribed', $is_subscribed, '', '전체'); ?>
                    <?php echo radio_checked('is_subscribed', $is_subscribed, '1', '구독중'); ?>
                    <?php echo radio_checked('is_subscribed', $is_subscribed, '0', '구독취소'); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="btn_confirm">
        <input type="submit" value="검색" class="btn_medium">
        <input type="button" value="초기화" id="frmRest" class="btn_medium grey">
    </div>
</form>

<div class="local_ov mart30">
    전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
    <select id="page_rows"
            onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1"; ?>&page_rows='+this.value;"
            class="marl5">
        <?php echo option_selected('30', $page_rows, '30줄 정렬'); ?>
        <?php echo option_selected('50', $page_rows, '50줄 정렬'); ?>
        <?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
        <?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
    </select>
    <!--    <strong class="ov_a">총주문액 : --><?php //echo number_format($tot_orderprice); ?><!--원</strong>-->
</div>

<form name="forderlist" id="forderlist" method="post">
    <input type="hidden" name="q1" value="<?php echo $q1; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">

    <div class="local_frm01">
        <?php echo $btn_frmline; ?>
    </div>
    <div class="tbl_head01">
        <table id="sodr_list">
            <colgroup>
                <col class="w50">
                <col class="w100">
                <col class="w150">
                <col>

                <col class="w150">
                <col class="w80">
                <col class="w80">
                <col class="w90">
                <col class="w90">
                <col class="w90">
                <col class="w90">
                <col class="w90">
                <col class="w90">
                <col class="w90">
                <col class="w90">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">구독일시</th>
                <th scope="col">구독번호</th>
                <th scope="col">상품명</th>
                <th scope="col">옵션</th>
                <th scope="col">구독금액</th>
                <th scope="col">주기</th>
                <th scope="col">구독상태</th>
                <th scope="col">주문자</th>
                <th scope="col">결제방법</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $rowspan = 1;
            $Subscription = Subscription::getInstance();
            for ($i = 0;
            $row = sql_fetch_array($result);
            $i++) {

            $amount = get_order_spay($row['od_id']);
            $sodr = get_order_list($row, $amount);
            $bg = 'list' . ($i % 2);
            $cart = sql_fetch("select * from shop_cart where od_id = '{$row['od_id']}'");
            $gs = get_goods($row['gs_id']);
            $row = $Subscription->setRow($row)->row;
            if ($Subscription->isTestPg()) {
                $bg = 'warning';
            }
            $orderData = json_decode(htmlspecialchars_decode($row['new_order_data']), true);

            if($orderData['od_id']){
                //$Subscription->update(['order_id'=> $orderData['od_id']]);
            }
            ?>
            <tr class="<?php echo $bg; ?>">
                <?php if ($k == 0) { ?>
                    <td rowspan="<?php echo $rowspan; ?>"><?php echo $num--; ?></td>
                    <td rowspan="<?php echo $rowspan; ?>">
                        <?php echo substr($row['created_at'], 2, 14); ?>
                    </td>
                    <td rowspan="<?php echo $rowspan; ?>">
                        <a href="<?php echo TB_ADMIN_URL; ?>/subscription.php?id=<?php echo $row['id']; ?>"
                           class="fc_197"><?php echo $row['id']; ?></a>
                        <!--                        <input type="hidden" name="id[-->
                        <?php //echo $i; ?><!--]" value="--><?php //echo $row['id']; ?><!--">-->
                        <!--                        <label for="chk_-->
                        <?php //echo $i; ?><!--" class="sound_only">구독번호 --><?php //echo $row['id']; ?><!--</label>-->
                        <!--                        <input type="checkbox" name="chk[]" value="-->
                        <?php //echo $i; ?><!--" id="chk_--><?php //echo $i; ?><!--">-->

                    </td>
                <?php } ?>
                <td class="td_itname"><a
                            href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row['gs_id']; ?>"
                            target="_blank"><?php echo get_text($gs['gname']); ?></a></td>

                <td><?php echo $row['io_id']; ?></td>
                <td rowspan="<?php echo $rowspan; ?>" class="tar"><?php echo number_format($row['price']); ?></td>
                <td rowspan="<?php echo $rowspan; ?>" class="tar"><?php echo $row['payment_cycle']; ?></td>
                <td rowspan="<?php echo $rowspan; ?>">
                    <?php
                    echo $row["is_subscribed"] ? "구독중" : "구독중지";
                    ?>
                </td>
                <td rowspan="<?php echo $rowspan; ?>"><?php echo $orderData['b_name']; ?></td>
                <td rowspan="<?php echo $rowspan; ?>" class="tar"><?php echo $row['pg']; ?></td>

                <?php
                }
                sql_free_result($result);
                if ($i == 0)
                    echo '<tr><td colspan="16" class="empty_table">자료가 없습니다.</td></tr>';
                ?>
            </tbody>
        </table>
    </div>
    <div class="local_frm02">
        <?php echo $btn_frmline; ?>
    </div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'] . '?' . $q1 . '&page=');
?>

<script>
    $(function () {
        $("#fr_date, #to_date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
            yearRange: "c-99:c+99",
            maxDate: "+0d"
        });

        // 주문서출력
        $("#frmOrderPrint, #frmOrderExcel").on("click", function () {
            var type = $(this).attr("id");
            var od_chk = new Array();
            var od_id = "";
            var $el_chk = $("input[name='chk[]']");

            $el_chk.each(function (index) {
                if ($(this).is(":checked")) {
                    od_chk.push($("input[name='od_id[" + index + "]']").val());
                }
            });

            if (od_chk.length > 0) {
                od_id = od_chk.join();
            }

            if (od_id == "") {
                alert("처리할 자료를 하나 이상 선택해 주십시오.");
                return false;
            } else {
                if (type == 'frmOrderPrint') {
                    var url = "./order/order_print.php?od_id=" + od_id;
                    window.open(url, "frmOrderPrint", "left=100, top=100, width=670, height=600, scrollbars=yes");
                    return false;
                } else {
                    this.href = "./order/order_excel2.php?od_id=" + od_id;
                    return true;
                }
            }
        });
    });
</script>
