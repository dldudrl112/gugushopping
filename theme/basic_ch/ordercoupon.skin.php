<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="flist" method="post" class="new_win">
<input type="hidden" name="sum_dc_amt">
<input type="hidden" name="layer_cnt">

<h1 id="win_title"><?php echo $tb['title']; ?></h1>

<div class="win_desc marb10">
	<p class="bx-danger">
		* 优惠券仅适用于个人（订单/产品）（重复优惠券除外）<br>
		* 优惠券使用时配送费不打折。 (打折预示 : 商品销售价 x 数量}+{和左边一样}+{和左边一样}仅适用于)<br>
		* 如果您取消或退回订单，优惠券将自动取消.
	</p>
</div>

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w70">
		<col>
		<col class="w90">
		<col class="w80">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">形象</th>
		<th scope="col">商品名称</th>
		<th scope="col">售价</th>
		<th scope="col">选择优惠券</th>
		<th scope="col">折扣金额</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result2); $i++) {
		$gs = get_goods($row['gs_id']);

		// 合计金额计算
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty
					from shop_cart
				   where gs_id = '$row[gs_id]'
					 and ct_direct = '$set_cart_id'
					 and ct_select = '0'";
		$sum = sql_fetch($sql);

		$price = $sum['price'];

		// 用逗号提取类别
		$ca_list = get_extract($row['gs_id']);
		$cp_tmp[] = $price ."|". $row['gs_id'] ."|". $ca_list ."|". $gs['use_aff'];

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="tac">
			<input type="hidden" name="gd_dc_amt_<?php echo $i; ?>">
			<input type="hidden" name="gd_cp_info_<?php echo $i; ?>">
			<input type="hidden" name="gd_cp_no_<?php echo $i; ?>">
			<input type="hidden" name="gd_cp_idx_<?php echo $i; ?>">
			<?php echo get_it_image($row['gs_id'], $gs['simg1'], 60, 60); ?>
		</td>
		<td class="td_name"><?php echo get_text($gs['gname']); ?></td>
		<td class="tac">
			<div><?php echo display_price2($price); ?></div>
			<div class='padt5 fc_197'>(数量:<?php echo $sum['qty']; ?>)</div>
		</td>
		<td class="tac">
			<span id="cp_avail_button_<?php echo $i; ?>">
			<a href="#" onclick="show_coupon('<?php echo $i; ?>');return false;" class="btn_small">选择优惠券</a>
			</span>
		</td>
		<td class="tar">
			<span id="dc_amt_<?php echo $i; ?>">0</span>韩元
			<span id="dc_cancel_bt_<?php echo $i; ?>" style="display:none"><a href="javascript:coupon_cancel('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');">X</a></span>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	<tfoot>
	<tr>
		<td class="tar" colspan="5">
			<strong>
优惠券 : <span id="to_dc_amt" class="fc_red">0</span>韩元</strong>
		</td>
	</tr>
	</tfoot>
	</table>
</div>

<div class="btn_confirm marb30">
	<a href="#" onclick="cp_submit();return false;" onfocus="this.blur();" class="btn_medium red">适用优惠券</a>
</div>

<div class="win_desc">
	<p class="pg_cnt">
		<em>全体 <?php echo number_format($total_count); ?>个</em> 早会
		(<b><?php echo $member['name']; ?></b>您可以使用的优惠券上税明细。)
	</p>
</div>

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w60">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">优惠券编号</th>
		<th scope="col">优惠券名</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {			
		$lo_id = $row['lo_id'];

		$str = get_cp_contents();

		for($j=0; $j<$cart_count; $j++) {

			$is_coupon = false;
			$is_gubun = explode("|", $cp_tmp[$j]);

			switch($row['cp_use_part']) {
				case '0': // 全部商品均可使用优惠券
					$is_coupon = true;
					break;
				case '1': // 只有部分商品可以使用优惠券
					if($row['cp_use_goods']) {
						$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
						if($fields_cnt)
							$is_coupon = true;
					}
					break;
				case '2': // 仅限部分类别可使用优惠券
					if($row['cp_use_category']) {
						$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
						if($fields_cnt)
							$is_coupon = true;
					}
					break;
				case '3': // 部分商品不能使用优惠券
					if($row['cp_use_goods']) {
						$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
						if(!$fields_cnt)
							$is_coupon = true;
					}
					break;
				case '4': //部分类别不能使用优惠券
					if($row['cp_use_category']) {
						$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
						if(!$fields_cnt)
							$is_coupon = true;
					}
					break;
			}

			// 适用与否&&加盟店商品除外 && 最大金额 <= 商品金额
			$seq = array();
			if($is_coupon && !$is_gubun[3] && ($row['cp_low_amt'] <= (int)$is_gubun[0])) {
				// 折扣优惠检查
				$amt =  get_cp_sale_amt((int)$is_gubun[0]);
				$seq[] = $is_gubun[1];
				$seq[] = $lo_id;
				$seq[] = $row['cp_id'];
				$seq[] = $row['cp_dups'];
				$seq[] = $amt[1];
				$seq[] = $amt[0];
				$is_possible[] = implode("|", $seq);
			}
		}

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="tac"><?php echo $row['cp_id']; ?></td>
		<td><?php echo $str; ?></td>
	</tr>
	<?php
	}
	if(!$total_count)
		echo '<tr><td colspan="2" class="empty_list">没有资料。</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
?>

<?php
$result2 = sql_query($sql2);
for($i=0; $row=sql_fetch_array($result2); $i++) {
?>
<div id="cp_list<?php echo $i; ?>" class="apply_cmd" style="display:none;">
	<table width="306">
	<tr>
		<td>
			<img src="<?php echo TB_IMG_URL; ?>/coupon_apply_title.gif" usemap="#coupon_apply<?php echo $i; ?>">
			<map name="coupon_apply<?php echo $i; ?>">
			<area shape="rect" coords="286,0,304,14" href="#" onclick="hide_cp_list('<?php echo $i; ?>'); return false;">
			</map>
		</td>
	</tr>
	</table>

	<div class="tbl_head02">
		<table width="306">
		<thead>
		<tr>
			<th scope="col">优惠券编号</th>
			<th scope="col">折扣金额</th>
			<th scope="col">折扣价格</th>
		</tr>
		</thead>
		<tbody>
		<?php
		//5|1|8|0|10%|37496
		// 商品钥匙|优惠券钥匙|优惠券编号|是否同时使用|折扣金额|折价
		$chk = 0;
		for($j=0; $j<count($is_possible); $j++) {
			$arr = explode("|", $is_possible[$j]);
			if($row['gs_id'] == $arr[0]) {
				$chk++;
		?>
		<tr>
			<td class="tac"><input type="radio" name="use_cp_<?php echo $row['gs_id']; ?>_<?php echo $row['index_no']; ?>" value="<?php echo $arr[2]; ?>|<?php echo $arr[5]; ?>|<?php echo $arr[1]; ?>|<?php echo $arr[3]; ?>"> <b><?php echo $arr[2]; ?></b></td>
			<td class="tac"><?php echo $arr[4]; ?></td>
			<td class="tac"><?php echo display_price2($arr[5]); ?></td>
		</tr>
		<?php
			}
		}

		if(!$chk) {
			echo '<tr><td colspan="3" class="empty_table">没有可以使用的优惠券。</td></tr>';
		}
		?>
		</tbody>
		</table>
	</div>

	<div class="btn_confirm mart10">
		<button type="button" onclick="return applycoupon('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class="btn_small">优惠券适用</button>
	</div>
</div>
<?php } ?>
<div class="win_btn">
	<a href="javascript:window.close();" class="btn_lsmall bx-white">
关闭窗口</a>
</div>
</form>

<script language="javascript">
var max_layer = '<?php echo $cart_count; ?>';
document.flist.layer_cnt.value = max_layer;

function applycoupon(gs_id, cart_id, layer_idx) {
	var f = document.flist;

	// 单个商品的未选择优惠券
	if(!getRadioValue(f["use_cp_"+gs_id+"_"+cart_id])){
		alert('请选择商品适用的优惠券.');
		return false;
	}

	// 获得优惠券编号
	var info = getRadioValue(f["use_cp_"+gs_id+"_"+cart_id]).split("|");
	var cp_no = info[0]; // 使用的优惠券编号
	var gd_dc_amt = info[1]; // 优惠券折扣额
	var cp_idx = info[2]; // 优惠券 IDX
	var cp_dups = info[3]; // 重复适用与否

	// 检查是否已使用的优惠券
	for(i=0;i<max_layer;i++){
		tmp = f["gd_cp_no_"+i].value; //使用的优惠券编号
		if(tmp != ""){
			if(cp_no == tmp){
				// 不可重复适用
				if(cp_dups == "1"){
					alert('相关优惠券不能重复打折。');
					f["use_cp_"+gs_id+"_"+cart_id].checked = false;
					hide_cp_list(layer_idx);
					return false;
				}
			}
		}
	}

	// 按商品分类记录优惠券适用折扣价
	f["gd_dc_amt_"+layer_idx].value = gd_dc_amt;

	// 按商品分类保存适用的优惠券信息
	f["gd_cp_info_"+layer_idx].value = gs_id+"|"+cart_id+"|"+cp_no+"|"+cp_idx+"|"+gd_dc_amt;
	f["gd_cp_no_"+layer_idx].value = cp_no;
	f["gd_cp_idx_"+layer_idx].value = cp_idx;

	// 获得全额折扣
	var sum_dc_amt = 0;
	var tmp = 0;
	for(i = 0; i < max_layer; i++){
		if(f["gd_dc_amt_"+i].value == ""){
			tmp = 0;
		} else {
			tmp = parseInt(f["gd_dc_amt_"+i].value);
		}
		sum_dc_amt += tmp;
	}
	// 记录总折扣
	f.sum_dc_amt.value = sum_dc_amt;

	// label 变化
	document.getElementById("dc_amt_"+layer_idx).innerText = formatComma(gd_dc_amt);
	document.getElementById("to_dc_amt").innerText = formatComma(sum_dc_amt);
	document.getElementById("cp_avail_button_"+layer_idx).style.display = "none"; // 我看不到应用程序
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "";

	hide_cp_list(layer_idx);
}

function coupon_cancel(gs_id, cart_id, layer_idx){
	var f = document.flist;

	// 按产品记录优惠券适用价格
	f["gd_dc_amt_"+layer_idx].value = 0;

	// 按产品删除应用的优惠券信息
	f["gd_cp_info_"+layer_idx].value = "";
	f["gd_cp_no_"+layer_idx].value = "";
	f["gd_cp_idx_"+layer_idx].value = "";

	//
获得全额折扣
	var sum_dc_amt = 0;
	var tmp = 0;
	for(i = 0; i < max_layer; i++){
		if(f["gd_dc_amt_"+i].value == ""){
			tmp = 0;
		}else{
			tmp = parseInt(f["gd_dc_amt_"+i].value);
		}
		sum_dc_amt += tmp;
	}
	// 记录总折扣
	f.sum_dc_amt.value = sum_dc_amt;

	// label 变化
	document.getElementById("dc_amt_"+layer_idx).innerText = formatComma(0);
	document.getElementById("to_dc_amt").innerText = formatComma(sum_dc_amt);
	document.getElementById("cp_avail_button_"+layer_idx).style.display = ""; // 让我再次看到
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "none";
}

function show_coupon(idx)
{
	var cp_list = $("#cp_list"+idx);
	var wt = Math.max(0, (($(window).height() - $(cp_list).outerHeight()) / 2) + $(window).scrollTop()) + "px";
	var wl = Math.max(0, (($(window).width() - $(cp_list).outerWidth()) / 2) + $(window).scrollLeft()) + "px";
	$(cp_list).css("top", wt);
	$(cp_list).css("left", wl);
	$(cp_list).show();
}

function hide_cp_list(idx) {
	var coupon_layer = document.getElementById("cp_list"+idx);
	coupon_layer.style.display = 'none';
}

function cp_submit() {
	var f = document.flist;
	var tot_price = opener.document.buyform.tot_price.value;

	if(f.sum_dc_amt.value == 0 || !f.sum_dc_amt.value) {
		alert("请在商品上选择优惠券。");
		return false;
	}

	if(parseInt(stripComma(tot_price)) < f.sum_dc_amt.value) {
		alert("优惠券折扣金额超过结算金额。");
		return false;
	}

	if(!confirm("确定要使用优惠券吗?"))
		return false;

	var tmp_dc_amt	= '';
	var tmp_lo_id	= '';
	var tmp_cp_id	= '';
	var chk_dc_amt	= '';
	var chk_lo_id	= '';
	var chk_cp_id	= '';
	var comma		= '';
	for(i = 0; i < max_layer; i++) {
		chk_dc_amt	= eval("f.gd_dc_amt_"+i).value ? eval("f.gd_dc_amt_"+i).value : 0;
		chk_lo_id   = eval("f.gd_cp_idx_"+i).value ? eval("f.gd_cp_idx_"+i).value : 0;
		chk_cp_id	= eval("f.gd_cp_no_"+i).value ? eval("f.gd_cp_no_"+i).value : 0;

		tmp_dc_amt += comma + chk_dc_amt;
		tmp_lo_id  += comma + chk_lo_id;
		tmp_cp_id  += comma + chk_cp_id;
		comma = '|';
	}

	// 日志
	opener.document.buyform.coupon_price.value = tmp_dc_amt;
	opener.document.buyform.coupon_lo_id.value = tmp_lo_id;
	opener.document.buyform.coupon_cp_id.value = tmp_cp_id;

	// 总折扣
	opener.document.buyform.coupon_total.value = f.sum_dc_amt.value;
	opener.document.getElementById("dc_amt").innerText = formatComma(f.sum_dc_amt.value);
	opener.document.getElementById("dc_cancel").style.display = "";
	opener.document.getElementById("dc_coupon").style.display = "none";

	// 最终付款金额
	opener.document.buyform.tot_price.value = formatComma(parseInt(stripComma(tot_price)) - f.sum_dc_amt.value);

	self.close();
}
</script>
