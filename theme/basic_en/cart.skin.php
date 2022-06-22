<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- Start Cart { -->
<script src="<?php echo TB_JS_URL; ?>/shop.js"></script>

<p><img src="<?php echo TB_IMG_URL; ?>/tit_cart.gif"></p>

<p class="pg_cnt mart30">
	<em>Total <?php echo number_format($cart_count); ?>the number</em>There's a prize in the cart.
</p>

<form name="frmcartlist" id="sod_bsk_list" method="post" action="<?php echo $cart_action_url; ?>">
<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w50">
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
		<th scope="col">
			<label for="ct_all" class="sound_only">Total Products</label>
			<input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked">
		</th>
		<th scope="col">Image</th>
		<th scope="col">product/Option information</th>
		<th scope="col">Quantity</th>
		<th scope="col">Product amount</th>
		<th scope="col">Subtitles</th>
		<th scope="col">Point</th>
		<th scope="col">The delivery charge</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$tot_point		= 0;
	$tot_sell_price = 0;
	$tot_opt_price	= 0;
	$tot_sell_qty	= 0;
	$tot_sell_amt	= 0;

	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = get_goods($row['gs_id']);

		// Total amount calculationv
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
					from shop_cart
				   where gs_id = '$row[gs_id]'
					 and ct_direct = '$set_cart_id'
					 and ct_select = '0'";
		$sum = sql_fetch($sql);

		if($i==0) { // Continuing Shopping
			$continue_ca_id = $row['ca_id'];
		}

		unset($it_name);
		unset($mod_options);
		$it_options = print_item_options($row['gs_id'], $set_cart_id);
		if($it_options) {
			$mod_options = '<div class="sod_option_btn"><button type="button" class="btn_small bx-white mod_options">옵션변경/추가</button></div>';
			$it_name = '<div class="sod_opt">'.$it_options.'</div>';
		}

		$point = $sum['point'];
		$sell_price = $sum['price'];
		$sell_opt_price = $sum['opt_price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// The delivery charge
		if($gs['use_aff'])
			$sr = get_partner($gs['mb_id']);
		else
			$sr = get_seller_cd($gs['mb_id']);

		$info = get_item_sendcost($sell_price);
		$item_sendcost[] = $info['pattern'];

		$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="tac">
			<label for="ct_chk_<?php echo $i; ?>" class="sound_only">Product</label>
			<input type="checkbox" name="ct_chk[<?php echo $i; ?>]" value="1" id="ct_chk_<?php echo $i; ?>" checked="checked">
		</td>
		<td class="tac"><a href="<?php echo $it_href; ?>"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 100, 100); ?></a></td>
		<td class="td_name">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<a href="<?php echo $it_href; ?>"><?php echo $gs['gname']; ?></a>
			<?php echo $it_name.$mod_options; ?>
		</td>
		<td class="tac"><?php echo number_format($sell_qty); ?></td>
		<td class="tar"><?php echo number_format($sell_amt); ?></td>			
		<td class="tar"><?php echo number_format($sell_price); ?></td>
		<td class="tar"><?php echo number_format($point); ?></td>	
		<td class="tar"><?php echo number_format($info['price']); ?></td>
	</tr>
	<?php
		$tot_point		+= $point;
		$tot_sell_price += $sell_price;
		$tot_opt_price	+= $sell_opt_price;
		$tot_sell_qty	+= $sell_qty;
		$tot_sell_amt	+= $sell_amt;

		if(!$is_member) {
			$tot_point = 0;
		}
	} // for

	if($i == 0) {
		echo '<tr><td colspan="8" class="empty_table">I don't have any items in my shopping cart.</td></tr>';
	}

	//shipping cost check
	$send_cost = 0;
	$com_send_cost = 0;
	$sep_send_cost = 0;
	$max_send_cost = 0;

	if($i > 0) {
		$k = 0;
		$condition = array();
		foreach($item_sendcost as $key) {
			list($userid, $bundle, $price) = explode('|', $key);
			$condition[$userid][$bundle][$k] = $price;
			$k++;
		}

		$com_array = array();
		$val_array = array();
		foreach($condition as $key=>$value) {
			if($condition[$key]['Bags']) {
				$com_send_cost += array_sum($condition[$key]['Bags']); // bundle delivery fee
				$max_send_cost += max($condition[$key]['Bags']); // the sum of the largest shipping charges
				$com_array[] = max(array_keys($condition[$key]['Bags'])); // max key
				$val_array[] = max(array_values($condition[$key]['Bags']));// max value
			}
			if($condition[$key]['Individual']) {
				$sep_send_cost += array_sum($condition[$key]['Individual']); // Total amount of bundle delivery non-compliance
				$com_array[] = array_keys($condition[$key]['Individual']); // All arrays key
				$val_array[] = array_values($condition[$key]['Individual']); // All arrays value
			}
		}

		$tune = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // Total shipping cost
		$tot_send_cost = $max_send_cost + $sep_send_cost; // final delivery fee
		$tot_final_sum = $send_cost - $tot_send_cost; // Shipping discount
		$tot_price = $tot_sell_price + $tot_send_cost; // Payment scheduled amount
	}
	?>
	</tbody>
	</table>
</div>

<?php if($i > 0) { ?>
<div id="sod_bsk_btn">
	<div class="palt"><button type="button" onclick="return form_check('seldelete');" class="btn_lsmall bx-red">Delete Selection</button></div>
	<div class="part"><button type="button" onclick="return form_check('alldelete');" class="btn_lsmall bx-white">Empty Cart</button></div>
</div>

<div id="sod_bsk_tot">
	<table class="wfull">
	<tr>
		<td class="w50p">
			<h2 class="anc_tit">shopping cart statistics</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">Point</th>
					<td class="tar">reserve point</td>
					<td class="tar bl"><?php echo display_point($tot_point); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">product</th>
					<td class="tar">Total amount of goods</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_amt); ?></td>
				</tr>
				<tr>
					<td class="tar">Total amount of option amount</td>
					<td class="tar bl"><?php echo display_price2($tot_opt_price); ?></td>
				</tr>
				<tr>
					<td class="tar">Total order quantity</td>
					<td class="tar bl"><?php echo display_qty($tot_sell_qty); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">Current point holding balance</td>
					<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="w50p">
			<h2 class="anc_tit">Estimated Payment Amount Statistics</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">order</th>
					<td class="tar">(A) Total order amount</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_price); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">The delivery charge</th>
					<td class="tar">Total delivery cost by product</td>
					<td class="tar bl"><?php echo display_price2($send_cost); ?></td>
				</tr>
				<tr>
					<td class="tar">Shipping discount</td>
					<td class="tar bl">(-) <?php echo display_price2($tot_final_sum); ?></td>
				</tr>
				<tr>
					<td class="tar">(B)final delivery fee</td>
					<td class="tar bl"><?php echo display_price2($tot_send_cost); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">Payment scheduled amount (A+B)</td>
					<td class="list2 tar bold fc_red"><?php echo display_price2($tot_price); ?></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
</div>
<?php } ?>

<div class="btn_confirm">
	<?php if($i == 0) { ?>
	<a href="<?php echo TB_URL; ?>" class="btn_large">Continue shopping</a>
	<?php } else { ?>
	<input type="hidden" name="url" value="./orderform.php">
	<input type="hidden" name="records" value="<?php echo $i; ?>">
	<input type="hidden" name="act" value="">
	<button type="button" onclick="return form_check('buy');" class="btn_large wset">Selective product order</button>
	<a href="<?php echo TB_SHOP_URL; ?>/list.php?ca_id=<?php echo $continue_ca_id; ?>" class="btn_large bx-white">Continue shopping</a>
	<?php if($naverpay_button_js) { ?>
	<div class="cart-naverpay"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
	<?php } ?>
	<?php } ?>
</div>
</form>

<script>
$(function() {
	var close_btn_idx;

	// Modify Options
	$(".mod_options").click(function() {
		var gs_id = $(this).closest("tr").find("input[name^=gs_id]").val();
		var $this = $(this);
		close_btn_idx = $(".mod_options").index($(this));

		$.post(
			tb_shop_url+"/cartoption.php",
			{ gs_id: gs_id },
			function(data) {
				$("#mod_option_frm").remove();
				$this.after("<div id=\"mod_option_frm\"></div>");
				$("#mod_option_frm").html(data);
				price_calculate();
			}
		);
	});

    // Select All
    $("input[name=ct_all]").click(function() {
        if($(this).is(":checked"))
            $("input[name^=ct_chk]").attr("checked", true);
        else
            $("input[name^=ct_chk]").attr("checked", false);
    });

    // Close Option Modification
    $(document).on("click", "#mod_option_close", function() {
        $("#mod_option_frm").remove();
        $(".mod_options").eq(close_btn_idx).focus();
    });

    $("#win_mask").click(function () {
        $("#mod_option_frm").remove();
        $(".mod_options").eq(close_btn_idx).focus();
    });
});

function fsubmit_check(f) {
    if($("input[name^=ct_chk]:checked").size() < 1) {
        alert("Please select at least one item to order.");
        return false;
    }

    return true;
}

function form_check(act) {
    var f = document.frmcartlist;
    var cnt = f.records.value;

    if(act == "buy")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("Please select at least one item to order.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }
    else if(act == "alldelete")
    {
        f.act.value = act;
        f.submit();
    }
    else if(act == "seldelete")
    {
        if($("input[name^=ct_chk]:checked").size() < 1) {
            alert("삭제하실 상품을 하나이상 선택해 주십시오.");
            return false;
        }

        f.act.value = act;
        f.submit();
    }

    return true;
}
</script>
<!-- } the end of a shopping -->
