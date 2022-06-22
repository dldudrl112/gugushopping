<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">Close window</a>
</h2>

<form name="flist" method="post">
<input type="hidden" name="sum_dc_amt">
<input type="hidden" name="layer_cnt">

<div id="sod_coupon">
	<div class="scope">
		1,Coupons other than double discount can only be applied to individual products.<br>
		2, Shipping costs are not discounted when applying coupons.<br>
		3, Ordering (cancellation/return) will automatically destroy the coupon.<br>
		4, You can check the issued coupon on My Page.
	</div>

	<?php
	$tot_price = 0;
	for($i=0; $row=sql_fetch_array($result2); $i++) {
		$gs = get_goods($row['gs_id']);

		// Total amount calculation
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty
				   from shop_cart
				  where gs_id = '$row[gs_id]'
					and ct_direct = '$set_cart_id'
					and ct_select = '0'";
		$sum = sql_fetch($sql);

		$price = $sum['price'];
		$tot_price += $price;

		// Extract by dividing the membership category into commas
		$ca_list = get_extract($row['gs_id']);
		$cp_tmp[] = $price ."|". $row['gs_id'] ."|". $ca_list ."|". $gs['use_aff'];
	?>
	<div class="sod_cpuse">
		<input type="hidden" name="gd_dc_amt_<?php echo $i; ?>">
		<input type="hidden" name="gd_cp_info_<?php echo $i; ?>">
		<input type="hidden" name="gd_cp_no_<?php echo $i; ?>">
		<input type="hidden" name="gd_cp_idx_<?php echo $i; ?>">
		<table class="us_box">
		<tbody>
		<tr>
			<td class="mi_dt"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 60, 60); ?></td>
			<td class="mi_bt">
				<?php echo get_text($gs['gname']); ?><br>
				<span class="strong"><?php echo display_price($price); ?></span>
			</td>
		</tr>
		<tr id="cp_avail_button_<?php echo $i; ?>">
			<td colspan='2'><button type="button" class="avail_button" onclick="show_coupon('<?php echo $i; ?>');return false;">Applicable coupon selection</button></td>
		</tr>
		</tbody>
		</table>

		<table class="th_box">
		<tbody>
		<tr>
			<td class="tal">Quantity</td>
			<td class="tar"><?php echo display_qty($sum['qty']); ?></td>
		</tr>
		<tr>
			<td class="tal">Discounted amount
</td>
			<td class="tar">
				<span id="dc_amt_<?php echo $i; ?>">0</span>won
				<span id="dc_cancel_bt_<?php echo $i; ?>" style="display:none">&nbsp;<a href="javascript:coupon_cancel('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class='btn_ssmall bx-white'>Discounted amount
</a></span>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="to_wrap to_box">
		<dl class="to_tline">
			<dt>Total amount of goods</dt>
			<dd><?php echo display_price($tot_price); ?></dd>
			<dt class="point_bg">Total discount amount</dt>
			<dd class="point_bg"><span id="to_dc_amt">0</span>won</dd>
		</dl>
	</div>
	<div class="btn_confirm">
		<button type="button" onclick="cp_submit();return false;" class="btn_medium red">Apply coupon</button>
		<button type="button" onclick="window.close();" class="btn_medium bx-white">Close window</button>
	</div>
</div>

<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
	$lo_id = $row['lo_id'];

	//$str = mobile_cp_contents();

	for($j=0; $j<$cart_count; $j++) {

		$is_coupon = false;
		$is_gubun = explode("|", $cp_tmp[$j]);

		switch($row['cp_use_part']) {
			case '0': // Coupons are available for all products
				$is_coupon = true;
				break;
			case '1': // Coupons are only available for some products
				if($row['cp_use_goods']) {
					$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
					if($fields_cnt)
						$is_coupon = true;
				}
				break;
			case '2': // Coupons are only available for all categories
				if($row['cp_use_category']) {
					$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
					if($fields_cnt)
						$is_coupon = true;
				}
				break;
			case '3': // Some products are not allowed to use coupons
				if($row['cp_use_goods']) {
					$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
					if(!$fields_cnt)
						$is_coupon = true;
				}
				break;
			case '4': // Coupons are not available for all categories
				if($row['cp_use_category']) {
					$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
					if(!$fields_cnt)
						$is_coupon = true;
				}
				break;
		}

		// Application field && 
Exempt merchants && Maximum amount <= Item amount
		$seq = array();
		if($is_coupon && !$is_gubun[3] && ($row['cp_low_amt'] <= (int)$is_gubun[0])) {
			// Discount Habitat Inspection
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
}

$result2 = sql_query($sql2);
for($i=0; $row=sql_fetch_array($result2); $i++) {
?>
<div id="cp_list<?php echo $i; ?>" class="mw" style='display:none;'>
	<div class="bg"></div>
	<div class="fg">
		<table class="ty_box">
		<tr>
			<td class="tal">※ Select the coupon to apply</td>
			<td class="tar"><a href="#" onclick="hide_cp_list('<?php echo $i; ?>'); return false;" class="btn_small bx-white">Close</a></td>
		</tr>
		</table>

		<table class="ly_box mart5">
		<tbody>
		<tr>
			<td class="cell">Coupon number</td>
			<td class="cell">Sale</td>
			<td class="cell">Discount price</td>
		</tr>
		<?php
		//5|1|8|0|10%|37496
		// Product Key|Coupon Key |Coupon Key|Concurrent use status|Discount amount|sale prices
		$chk = 0;
		for($j=0; $j<count($is_possible); $j++) {
			$arr = explode("|", $is_possible[$j]);
			if($row['gs_id'] == $arr[0]) {
				$chk++;
		?>
		<tr>
			<td class="tac"><input id="ids_shown<?php echo $j; ?>" type="radio" name="use_cp_<?php echo $row['gs_id']; ?>_<?php echo $row['index_no']?>" value="<?php echo $arr[2]; ?>|<?php echo $arr[5]; ?>|<?php echo $arr[1]; ?>|<?php echo $arr[3]; ?>">
			<label for="ids_shown<?php echo $j; ?>"><?php echo $arr[2]; ?></label></td>
			<td class="tac"><?php echo $arr[4]; ?></td>
			<td class="tac"><?php echo display_price($arr[5]); ?></td>
		</tr>
		<?php
			}
		}

		if(!$chk) {
		?>
		<tr><td colspan="3" class="empty_list">There are no coupons available.</td></tr>
		<?php } ?>
		<tbody>
		</table>

		<div class="btn_confirm">
			<button type="button" onclick="return applycoupon('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class="btn_medium red">Apply coupon</button>
		</div>
	</div>
</div>
<?php } ?>
</form>

<script>
var max_layer = '<?php echo $cart_count; ?>';
document.flist.layer_cnt.value = max_layer;

function applycoupon(gs_id, cart_id, layer_idx) {
	var f = document.flist;

	// If no coupon is selected for individual products
	if(!getRadioValue(f["use_cp_"+gs_id+"_"+cart_id])){
		alert('Please select a coupon for the product.');
		return false;
	}

	// Obtain a coupon number
	var info = getRadioValue(f["use_cp_"+gs_id+"_"+cart_id]).split("|");
	var cp_no = info[0]; // Coupon Number Used
	var gd_dc_amt = info[1]; // coupon discount
	var cp_idx = info[2]; // coupon IDX
	var cp_dups = info[3]; // Duplicate applicability

	// Check if a coupon is already applied
	for(i=0;i<max_layer;i++){
		tmp = f["gd_cp_no_"+i].value; // Coupon Number Used
		if(tmp != ""){
			if(cp_no == tmp){
				// Duplicate not applicable
				if(cp_dups == "1"){
					alert('That coupon is not double-discounted.');
					f["use_cp_"+gs_id+"_"+cart_id].checked = false;
					hide_cp_list(layer_idx);
					return false;
				}
			}
		}
	}

	// Record discount price of coupon by product
	f["gd_dc_amt_"+layer_idx].value = gd_dc_amt;

	// Store applied coupon information by product
	f["gd_cp_info_"+layer_idx].value = gs_id+"|"+cart_id+"|"+cp_no+"|"+cp_idx+"|"+gd_dc_amt;
	f["gd_cp_no_"+layer_idx].value = cp_no;
	f["gd_cp_idx_"+layer_idx].value = cp_idx;

	// Get a full discount
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
	// gross discount record
	f.sum_dc_amt.value = sum_dc_amt;

	// label Change
	document.getElementById("dc_amt_"+layer_idx).innerText = number_format(String(gd_dc_amt));
	document.getElementById("to_dc_amt").innerText = number_format(String(sum_dc_amt));
	document.getElementById("cp_avail_button_"+layer_idx).style.display = "none"; // I don't see what's applied
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "";

	hide_cp_list(layer_idx);
}

function coupon_cancel(gs_id, cart_id, layer_idx){
	var f = document.flist;

	// Record discount price of coupon by product
	f["gd_dc_amt_"+layer_idx].value = 0;

	// Delete applied coupon information by product
	f["gd_cp_info_"+layer_idx].value = "";
	f["gd_cp_no_"+layer_idx].value = "";
	f["gd_cp_idx_"+layer_idx].value = "";

	// Get a full discount
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
	// Record total discount
	f.sum_dc_amt.value = sum_dc_amt;

	// label change document.getElementById("dc_amt_"+layer_idx).innerText = 0;
	document.getElementById("to_dc_amt").innerText = number_format(String(sum_dc_amt));
	document.getElementById("cp_avail_button_"+layer_idx).style.display = ""; // 
Show me again
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "none";
}

function show_coupon(idx) {
	var coupon_layer = document.getElementById("cp_list"+idx);
	var IpopTop = (document.body.clientHeight - coupon_layer.offsetHeight) / 2;
	var IpopLeft = (document.body.clientWidth - coupon_layer.offsetWidth) / 2;

	coupon_layer.style.left=IpopLeft / 2 + document.body.scrollLeft;
	coupon_layer.style.top=IpopTop / 2 + document.body.scrollTop;
 	coupon_layer.style.display = "block";
}

function hide_cp_list(idx) {
	var coupon_layer = document.getElementById("cp_list"+idx);
	coupon_layer.style.display = 'none';
}

function cp_submit() {
	var f = document.flist;
	var tot_price = 0;
	var tot_price = opener.document.buyform.tot_price.value;

	if(f.sum_dc_amt.value == 0 || !f.sum_dc_amt.value) {
		alert("Please select a coupon for the product.");
		return false;
	}

	if(parseInt(no_comma(tot_price)) < f.sum_dc_amt.value) {
		alert("The coupon discount has exceeded the payment amount.");
		return false;
	}

	if(!confirm("Would you like to apply coupon?"))
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

	// Log
	opener.document.buyform.coupon_price.value = tmp_dc_amt;
	opener.document.buyform.coupon_lo_id.value = tmp_lo_id;
	opener.document.buyform.coupon_cp_id.value = tmp_cp_id;

	//Total discount
	opener.document.buyform.coupon_total.value = f.sum_dc_amt.value;
	opener.document.getElementById("dc_amt").innerText = number_format(String(f.sum_dc_amt.value));
	opener.document.getElementById("dc_cancel").style.display = "";
	opener.document.getElementById("dc_coupon").style.display = "none";

	tot_price = parseInt(no_comma(tot_price)) - f.sum_dc_amt.value;

	// Final payment amount
	opener.document.buyform.tot_price.value = number_format(String(tot_price));

	self.close();
}
</script>
