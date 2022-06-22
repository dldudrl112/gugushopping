<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 菜篮子开始 { -->
<script src="<?php echo TB_JS_URL; ?>/shop.js"></script>

<p><img src="<?php echo TB_IMG_URL; ?>/tit_cart.gif"></p>

<p class="pg_cnt mart30">
	<em>全体 <?php echo number_format($cart_count); ?>个</em>的物品在篮子里。
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
			<label for="ct_all" class="sound_only">全部商品</label>
			<input type="checkbox" name="ct_all" value="1" id="ct_all" checked="checked">
		</th>
		<th scope="col">形象</th>
		<th scope="col">商品/期权信息</th>
		<th scope="col">数量</th>
		<th scope="col">商品金额</th>
		<th scope="col">合计</th>
		<th scope="col">点</th>
		<th scope="col">配送费</th>
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

		// 合计金额计算
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
					from shop_cart
				   where gs_id = '$row[gs_id]'
					 and ct_direct = '$set_cart_id'
					 and ct_select = '0'";
		$sum = sql_fetch($sql);

		if($i==0) { // 继续购物
			$continue_ca_id = $row['ca_id'];
		}

		unset($it_name);
		unset($mod_options);
		$it_options = print_item_options($row['gs_id'], $set_cart_id);
		if($it_options) {
			$mod_options = '<div class="sod_option_btn"><button type="button" class="btn_small bx-white mod_options">变更选项/追加</button></div>';
			$it_name = '<div class="sod_opt">'.$it_options.'</div>';
		}

		$point = $sum['point'];
		$sell_price = $sum['price'];
		$sell_opt_price = $sum['opt_price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// 配送费
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
			<label for="ct_chk_<?php echo $i; ?>" class="sound_only">商品</label>
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
		echo '<tr><td colspan="8" class="empty_table">没有篮子里装的商品。</td></tr>';
	}

	// 配送费检查
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
			if($condition[$key]['捆']) {
				$com_send_cost += array_sum($condition[$key]['捆']); // 合并发货合计
				$max_send_cost += max($condition[$key]['捆 ']); // 最大的运费合计
				$com_array[] = max(array_keys($condition[$key]['捆 '])); // max key
				$val_array[] = max(array_values($condition[$key]['捆 ']));// max value
			}
			if($condition[$key]['个别']) {
				$sep_send_cost += array_sum($condition[$key]['个别']); // 不可捆绑发货合计
				$com_array[] = array_keys($condition[$key]['个别']); // 全部排列 key
				$val_array[] = array_values($condition[$key]['个别']); // 全部排列 value
			}
		}

		$tune = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // 总配送费合计
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 最终运费
		$tot_final_sum = $send_cost - $tot_send_cost; // 
运费折扣
		$tot_price = $tot_sell_price + $tot_send_cost; // 预定结算金额
	}
	?>
	</tbody>
	</table>
</div>

<?php if($i > 0) { ?>
<div id="sod_bsk_btn">
	<div class="palt"><button type="button" onclick="return form_check('seldelete');" class="btn_lsmall bx-red">选择商品删除</button></div>
	<div class="part"><button type="button" onclick="return form_check('alldelete');" class="btn_lsmall bx-white">空的购物车</button></div>
</div>

<div id="sod_bsk_tot">
	<table class="wfull">
	<tr>
		<td class="w50p">
			<h2 class="anc_tit">菜篮子商品统计</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">点</th>
					<td class="tar">累积积分</td>
					<td class="tar bl"><?php echo display_point($tot_point); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">商品</th>
					<td class="tar">商品金额合计</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_amt); ?></td>
				</tr>
				<tr>
					<td class="tar">期权金额合计</td>
					<td class="tar bl"><?php echo display_price2($tot_opt_price); ?></td>
				</tr>
				<tr>
					<td class="tar">订货数量合计</td>
					<td class="tar bl"><?php echo display_qty($tot_sell_qty); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">目前积分保留余额</td>
					<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="w50p">
			<h2 class="anc_tit">结算预想金额统计</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">咒语</th>
					<td class="tar">(A) 订货金额合计</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_price); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">配送费</th>
					<td class="tar">各商品配送费合计</td>
					<td class="tar bl"><?php echo display_price2($send_cost); ?></td>
				</tr>
				<tr>
					<td class="tar">配送费折扣</td>
					<td class="tar bl">(-) <?php echo display_price2($tot_final_sum); ?></td>
				</tr>
				<tr>
					<td class="tar">(B) 最终运费</td>
					<td class="tar bl"><?php echo display_price2($tot_send_cost); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">预定结算金额 (A+B)</td>
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
	<a href="<?php echo TB_URL; ?>" class="btn_large">继续购物 </a>
	<?php } else { ?>
	<input type="hidden" name="url" value="./orderform.php">
	<input type="hidden" name="records" value="<?php echo $i; ?>">
	<input type="hidden" name="act" value="">
	<button type="button" onclick="return form_check('buy');" class="btn_large wset">选择商品订货</button>
	<a href="<?php echo TB_SHOP_URL; ?>/list.php?ca_id=<?php echo $continue_ca_id; ?>" class="btn_large bx-white">继续购物 </a>
	<?php if($naverpay_button_js) { ?>
	<div class="cart-naverpay"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
	<?php } ?>
	<?php } ?>
</div>
</form>

<script>
$(function() {
	var close_btn_idx;

	// 选择事项修改
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

    // 全部选择
    $("input[name=ct_all]").click(function() {
        if($(this).is(":checked"))
            $("input[name^=ct_chk]").attr("checked", true);
        else
            $("input[name^=ct_chk]").attr("checked", false);
    });

    // 修改选项关闭
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
        alert("请选择一个以上需要购买的商品.");
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
            alert("请选择一个以上需要订购的商品。");
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
            alert("请选择一个以上要删除的商品。");
            return false;
        }

        f.act.value = act;
        f.submit();
    }

    return true;
}
</script>
<!-- } 购物车结束 -->
