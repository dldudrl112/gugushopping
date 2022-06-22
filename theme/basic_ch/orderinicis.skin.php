<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_inicis.inc.php');

// 各结算代理公司代码 include (脚本等)
require_once(TB_SHOP_PATH.'/inicis/orderform.1.php');
?>

<!-- Enisis开始结算 { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ 想要订购的商品明细上 <em>数量及订货金额</em>一定要确认一下这个没有错。
</p>

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w120">
		<col>
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">形象</th>
		<th scope="col">商品/期权信息</th>
		<th scope="col">数量</th>
		<th scope="col">商品金额</th>
		<th scope="col">小计</th>
		<th scope="col">小计</th>
		<th scope="col">配送费</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$goods = '';
	$goods_count = -1;

	$sql = " select *
			   from shop_cart
			  where od_id = '$od_id'
				and ct_select = '0'
			  group by gs_id
			  order by index_no ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$rw = get_order($row['od_no']);
		$gs = get_goods($row['gs_id'], 'gname,simg1');

		if(!$goods)
			$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

		$goods_count++;

		$it_name = stripslashes($gs['gname']);
		$it_options = print_complete_options($row['gs_id'], $od_id);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}
	?>
	<tr>
		<td class="tac"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($rw['sum_qty']); ?></td>
		<td class="tar"><?php echo number_format($rw['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['use_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['sum_point']); ?></td>
		<td class="tar"><?php echo number_format($rw['baesong_price']); ?></td>
	</tr>
	<?php
	}

	if($goods_count) $goods .= /' 除了 '.$goods_count.'件';

	// 复合征税处理
	$comm_tax_mny  = 0; // 征税金额
	$comm_vat_mny  = 0; // 附加税
	$comm_free_mny = 0; // 免税金额
	if($default['de_tax_flag_use']) {
		$info = comm_tax_flag($od_id);
		$comm_tax_mny  = $info['comm_tax_mny'];
		$comm_vat_mny  = $info['comm_vat_mny'];
		$comm_free_mny = $info['comm_free_mny'];
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td class="tar" colspan="7">
			(商品金额 : <strong><?php echo display_price($stotal['price']); ?></strong> +
			配送费 : <strong><?php echo display_price($stotal['baesong']); ?></strong>) -
			(优惠券折扣 : <strong><?php echo display_price($stotal['coupon']); ?></strong> +
			积分结算 : <strong><?php echo display_price($stotal['usepoint']); ?></strong>) =
			总计 : <strong class="fc_red"><?php echo display_price($stotal['useprice']); ?></strong>
		</td>
	</tr>
	</tfoot>
	</table>
</div>

<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

<?php
// 各结算代理公司代码 include (结算代理公司的信息字段)
require_once(TB_SHOP_PATH.'/inicis/orderform.2.php');
?>

<section id="sod_fin_orderer">
	<h2 class="anc_tit">订购的人</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">名字</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">电话号码</th>
			<td><?php echo $od['telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">手机</th>
			<td><?php echo $od['cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">地址</th>
			<td><?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?></td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><?php echo $od['email']; ?></td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_receiver">
	<h2 class="anc_tit">收件人的</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">名</th>
			<td><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">电话号码</th>
			<td><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">手机</th>
			<td><?php echo $od['b_cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">住址</th>
			<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
		</tr>
		<?php if($od['memo']) { ?>
		<tr>
			<th scope="row">最</th>
			<td><?php echo conv_content($od['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">结算信息</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">结算方法</th>
			<td><?php echo $od['paymethod']; ?></td>
		</tr>
		<tr>
			<th scope="row">结算金额</th>
			<td class="fs14 bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		</table>
	</div>
</section>

<div id="display_pay_button" class="btn_confirm">
	<input type="button" value="结算" onclick="forderform_check(this.form);" class="btn_large wset">
    <a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo TB_IMG_URL; ?>/ajax-loader.gif" alt="">
    <span>完成订购中。 稍微等一下。.</span>
</div>

</form>

<script>
var form_action_url = "<?php echo $order_action_url; ?>";

function forderform_check(f)
{
    // 金额格子
    if(!payment_check(f))
        return false;

    if( f.action != form_action_url ){
        f.action = form_action_url;
        f.removeAttribute("target");
        f.removeAttribute("accept-charset");
    }

    switch(f.od_settle_case.value)
    {
        case "转账":
            f.gopaymethod.value = "DirectBank";
            break;
        case "我虚拟账号":
            f.gopaymethod.value = "VBank";
            break;
        case "手机":
            f.gopaymethod.value = "HPP";
            break;
        case "信用卡":
            f.gopaymethod.value = "Card";
            f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
            break;
        case "简便结算":
            f.gopaymethod.value = "Kpay";
            break;
    }

    f.price.value       = f.good_mny.value;
    <?php if($default['de_tax_flag_use']) { ?>
    f.tax.value         = f.comm_vat_mny.value;
    f.taxfree.value     = f.comm_free_mny.value;
    <?php } ?>
    f.buyername.value   = f.od_name.value;
    f.buyeremail.value  = f.od_email.value;
    f.buyertel.value    = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
    f.recvname.value    = f.od_b_name.value;
    f.recvtel.value     = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
    f.recvpostnum.value = f.od_b_zip.value;
    f.recvaddr.value    = f.od_b_addr1.value + " " +f.od_b_addr2.value;

	// 订单信息临时存储
	var order_data = $(f).serialize();
	var save_result = "";
	$.ajax({
		type: "POST",
		data: order_data,
		url: tb_url+"/shop/ajax.orderdatasave.php",
		cache: false,
		async: false,
		success: function(data) {
			save_result = data;
		}
	});

	if(save_result) {
		alert(save_result);
		return false;
	}

	if(!make_signature(f))
		return false;

	paybtn(f);
}

// 结算格子
function payment_check(f)
{
    var tot_price = parseInt(f.good_mny.value);

	if(f.od_settle_case.value == '转账') {
		if(tot_price < 150) {
			alert("转账可以结算150韩元以上。");
			return false;
		}
	}

    if(f.od_settle_case.value == /'信用卡') {
		if(tot_price < 1000) {
			alert("信用卡可以结算1000韩元以上。");
			return false;
		}
    }

	if(f.od_settle_case.value == /'手机') {
		if(tot_price < 350) {
			alert("手机可以结算350韩元以上。");
			return false;
		}
    }

    return true;
}
</script>
<!-- } Enisis结算结束 -->
