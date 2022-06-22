<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_inicis.inc.php');

// Code by payment agency include (Script, etc.)
require_once(TB_SHOP_PATH.'/inicis/orderform.1.php');
?>

<!-- Initiate Inicis Payment { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ Product Details to Order <em>Quantity and amount of order</em>Be sure to check if this is wrong.
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
		<th scope="col">Image</th>
		<th scope="col">Product/option information</th>
		<th scope="col">Quantity</th>
		<th scope="col">Product amount</th>
		<th scope="col">Subtitles</th>
		<th scope="col">Point</th>
		<th scope="col">The delivery charge</th>
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

	if($goods_count) $goods .= ' 외 '.$goods_count.'case';

	// Combined taxation treatment
	$comm_tax_mny  = 0; // Tax amount
	$comm_vat_mny  = 0; // Surtax
	$comm_free_mny = 0; // Tax-free amount
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
			(Product amount : <strong><?php echo display_price($stotal['price']); ?></strong> +
			The delivery charge : <strong><?php echo display_price($stotal['baesong']); ?></strong>) -
			(coupon discount : <strong><?php echo display_price($stotal['coupon']); ?></strong> +
			Point payment : <strong><?php echo display_price($stotal['usepoint']); ?></strong>) =
			Total : <strong class="fc_red"><?php echo display_price($stotal['useprice']); ?></strong>
		</td>
	</tr>
	</tfoot>
	</table>
</div>

<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

<?php
// Code by payment agency include (Payment agency information field)
require_once(TB_SHOP_PATH.'/inicis/orderform.2.php');
?>

<section id="sod_fin_orderer">
	<h2 class="anc_tit">Orderer</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Name</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">Phone number</th>
			<td><?php echo $od['telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">cell phone</th>
			<td><?php echo $od['cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
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
	<h2 class="anc_tit">Receiver</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Name</th>
			<td><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">Phone number</th>
			<td><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">cell phone</th>
			<td><?php echo $od['b_cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">Address</th>
			<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
		</tr>
		<?php if($od['memo']) { ?>
		<tr>
			<th scope="row">a message to you</th>
			<td><?php echo conv_content($od['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">Payment information</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Payment method</th>
			<td><?php echo $od['paymethod']; ?></td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td class="fs14 bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		</table>
	</div>
</section>

<div id="display_pay_button" class="btn_confirm">
	<input type="button" value="To pay" onclick="forderform_check(this.form);" class="btn_large wset">
    <a href="<?php echo TB_URL; ?>" class="btn_large bx-white">Cancellation</a>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo TB_IMG_URL; ?>/ajax-loader.gif" alt="">
    <span>Order is being completed. Please wait a moment.</span>
</div>

</form>

<script>
var form_action_url = "<?php echo $order_action_url; ?>";

function forderform_check(f)
{
    // Check amount
    if(!payment_check(f))
        return false;

    if( f.action != form_action_url ){
        f.action = form_action_url;
        f.removeAttribute("target");
        f.removeAttribute("accept-charset");
    }

    switch(f.od_settle_case.value)
    {
        case "account transfer":
            f.gopaymethod.value = "DirectBank";
            break;
        case "virtual account":
            f.gopaymethod.value = "VBank";
            break;
        case "Cell phone":
            f.gopaymethod.value = "HPP";
            break;
        case "Credit card":
            f.gopaymethod.value = "Card";
            f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
            break;
        case "Easy payment":
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

	// Temporary saving of order information
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

// Payment check
function payment_check(f)
{
    var tot_price = parseInt(f.good_mny.value);

	if(f.od_settle_case.value == 'account transfer') {
		if(tot_price < 150) {
			alert("You can pay more than 150 won for account transfer.");
			return false;
		}
	}

    if(f.od_settle_case.value == 'Credit card') {
		if(tot_price < 1000) {
			alert("You can pay more than 1,000 won for your credit card.");
			return false;
		}
    }

	if(f.od_settle_case.value == 'Cell phone') {
		if(tot_price < 350) {
			alert("You can pay more than 350 won for your cell phone.");
			return false;
		}
    }

    return true;
}
</script>
<!-- } End of Inicis Payment -->
