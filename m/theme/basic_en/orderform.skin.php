<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- Start filling out an order { -->
<div id="sod_approval_frm">
<?php
ob_start();
?>
    <p>Please check your order.</p>

    <ul class="sod_list">
		<?php
		$tot_point = 0;
		$tot_sell_price = 0;
		$tot_opt_price = 0;
		$tot_sell_qty = 0;
		$tot_sell_amt = 0;
		$seller_id = array();

		$sql = " select * 
				   from shop_cart 
				  where index_no IN ({$ss_cart_id})
					and ct_select = '0'
				  group by gs_id 
				  order by index_no ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs = get_goods($row['gs_id']);

			// Total amount calculation			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
							SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_supply_price) * ct_qty))) as supply_price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as opt_price
					   from shop_cart
					  where gs_id = '$row[gs_id]'
						and ct_direct = '$set_cart_id'
						and ct_select = '0'";
			$sum = sql_fetch($sql);

			$it_name = '<strong>'.stripslashes($gs['gname']).'</strong>';
			$it_options = mobile_print_item_options($row['gs_id'], $set_cart_id);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}

			$point = $sum['point'];
			$supply_price = $sum['supply_price'];
			$sell_price = $sum['price'];		
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// If not a member, initialize points
			if(!$is_member) $point = 0;

			// shipping fee
			if($gs['use_aff'])
				$sr = get_partner($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

			$seller_id[$i] = $gs['mb_id'];

			$href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		?>

        <li class="sod_li">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">		
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
            
			<div class="li_name">
                <?php echo $it_name; ?>
                <div class="li_mod" style="padding-left:100px;"></div>
                <span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span>
            </div>
            <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>
price</span>
				<?php echo number_format($sell_amt); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>Quantity</span>
				<?php echo number_format($sell_qty); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>shipping fee</span>
				<?php echo number_format($info['price']); ?></span>
            </div>
            <div class="li_total">
                <span class="total_price total_span"><span>partial sum</span>
				<strong><?php echo number_format($sell_price); ?></strong></span>
                <span class="total_point total_span"><span>Earning Points</span>
				<strong><?php echo number_format($point); ?></strong></span>
            </div>
        </li>

        <?php
			$tot_point += (int)$point;
			$tot_sell_price += (int)$sell_price;
			$tot_opt_price += (int)$sell_opt_price;
			$tot_sell_qty += (int)$sell_qty;
			$tot_sell_amt += (int)$sell_amt;
        } // for End

		// Check shipping
		$send_cost = 0;
		$com_send_cost = 0;
		$sep_send_cost = 0;
		$max_send_cost = 0;

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
			if($condition[$key]['bundle']) {
				$com_send_cost += array_sum($condition[$key]['bundle']); // Bundled shipping total
				$max_send_cost += max($condition[$key]['bundle']); // Combine the largest postage
				$com_array[] = max(array_keys($condition[$key]['bundle'])); // max key
				$val_array[] = max(array_values($condition[$key]['bundle']));// max value
			}
			if($condition[$key]['
Individual']) {
				$sep_send_cost += array_sum($condition[$key]['Individual']); // Bundle not cumulative
				$com_array[] = array_keys($condition[$key]['bundle']); // All arrays key
				$val_array[] = array_values($condition[$key]['bundle']); // All arrays value
			}
		}

		$baesong_price = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // Total shipping tota
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 
Final shipping cost
		$tot_final_sum = $send_cost - $tot_send_cost; // Shipping discount
		$tot_price = $tot_sell_price + $tot_send_cost; // Amount to be paid

        if($i == 0) {
            alert('
Your shopping cart is empty.', TB_MSHOP_URL.'/cart.php');
        }
        ?>
    </ul>

    <dl id="sod_bsk_tot">
        <dt class="sod_bsk_sell"><span>order</span></dt>
        <dd class="sod_bsk_sell"><strong><?php echo number_format($tot_sell_price); ?> won</strong></dd>
        <dt class="sod_bsk_dvr"><span>shipping fee</span></dt>
        <dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> won</strong></dd>
        <dt class="sod_bsk_cnt"><span>sum</span></dt>
        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?> won</strong></dd>
        <dt class="sod_bsk_point"><span>point</span></dt>
        <dd class="sod_bsk_point"><strong><?php echo number_format($tot_point); ?> P</strong></dd>
    </dl>

<?php
$content = ob_get_contents();
ob_end_clean();
?>
</div>

<div id="sod_frm">
	<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
	<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
	<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
	<input type="hidden" name="pt_id" value="<?php echo $mb_recommend; ?>">
	<input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
	<input type="hidden" name="coupon_total" value="0">
	<input type="hidden" name="coupon_price" value="">
	<input type="hidden" name="coupon_lo_id" value="">
	<input type="hidden" name="coupon_cp_id" value="">	
	<input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
	<input type="hidden" name="baesong_price2" value="0">
	<input type="hidden" name="org_price" value="<?php echo $tot_price; ?>">
	<?php if(!$is_member || !$config['usepoint_yes']) { ?>
	<input type="hidden" name="use_point" value="0">
	<?php } ?>

	<?php echo $content; ?>

	<section id="sod_frm_orderer">
		<h2 class="anc_tit">Ordering person</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<?php if(!$is_member) { // non-membership ?>
			<tr>
				<th scope="row">Password</th>
				<td>
					<input type="password" name="od_pwd" required class="frm_input required" maxlength="20">
					<span class="frm_info">alphanumeric number 3~20 characters (Required when inquiring an order)</span>
				</td>
			</tr>
			<?php } ?>
            <tr>
				<th scope="row">name</th>
                <td><input type="text" name="name" value="<?php echo $member['name']; ?>" required class="frm_input required" maxlength="20"></td>
            </tr>
			<tr>
				<th scope="row">cell phone</th>
				<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required class="frm_input required" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">Phone number</th>
				<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">Address</th>
				<td>
                    <input type="text" name="zip" value="<?php echo $member['zip']; ?>" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">Address search</button><br>
                    <input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required class="frm_input frm_address required"><br>   
                    <input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input frm_address"><br>
                    <input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input frm_address" readonly><br>
                    <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><input type="text" name="email" value="<?php echo $member['email']; ?>" required class="frm_input required wfull"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_frm_taker">
		<h2 class="anc_tit">Recipient</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">Select a shipping location</th>
				<td>
					<input type="radio" name="ad_sel_addr" value="1" id="sel_addr1" class="css-checkbox lrg">
					<label for="sel_addr1" class="css-label padr5">Same as Order</label><br>
					<input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
					<label for="sel_addr2" class="css-label">New Shipping Site</label>
					<?php if($is_member) { ?>
					<br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3" class="css-checkbox lrg">
					<label for="sel_addr3" class="css-label">Shipping list</label>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row">name</th>
				<td><input type="text" name="b_name" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">cell phone</th>
				<td><input type="text" name="b_cellphone" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">Phone number</th>
				<td><input type="text" name="b_telephone" class="frm_input"></td>
			</tr>
			<tr>
				<th scope="row">Adress</th>
				<td>
                    <input type="text" name="b_zip" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">Address search</button><br>
                    <input type="text" name="b_addr1" required class="frm_input frm_address required"><br>   
                    <input type="text" name="b_addr2" class="frm_input frm_address"><br>
                    <input type="text" name="b_addr3" class="frm_input frm_address" readonly><br>
					<input type="hidden" name="b_addr_jibeon" value="">				
				</td>
			</tr>
			<tr>
				<th scope="row">Messages to be forwarded</th>
				<td>
					<select name="sel_memo" class="wfull">
						<option value="">To select a request:</option>
						<option value="If you're not here, I need you to leave it with the security office.">If you're not here, I need you to leave it with the security office</option>
						<option value="I want a quick delivery.">I want a quick delivery.</option>
						<option value="
Please contact us on your mobile phone.">Call me on your cell phone when you're.</option>
						<option value="Please contact me before shipping.">Please contact me before shipping.</option>
					</select>
					<div class="padt5">
						<textarea name="memo" id="memo" class="frm_textbox"></textarea>
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php
	$escrow_title = "";
	if($default['de_escrow_use']) {
		$escrow_title = "Escrow ";
	}

	$multi_settle = '';
	if($is_kakaopay_use)
		$multi_settle .= "<option value='KAKAOPAY'>Kakao Pay</option>\n";
	if($default['de_bank_use'])
		$multi_settle .= "<option value='No passbook'>without bankbook</option>\n";
	if($default['de_card_use'])
		$multi_settle .= "<option value='Credit card'>Credit card</option>\n";
	if($default['de_hp_use'])
		$multi_settle .= "<option value='cell phone'>cell phone</option>\n";
	if($default['de_iche_use'])
		$multi_settle .= "<option value='account transfer'>".$escrow_title."account transfer</option>\n";	
	if($default['de_vbank_use'])
		$multi_settle .= "<option value='account transfer'>".$escrow_title."account transfer</option>\n";
	if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point']))
		$multi_settle .= "<option value='Point'>Point payment</option>\n";

	// PG Easy payment
	if($default['de_easy_pay_use']) {
		switch($default['de_pg_service']) {
			case 'lg':
				$pg_easy_pay_name = 'PAYNOW';
				break;
			case 'inicis':
				$pg_easy_pay_name = 'KPAY';
				break;
			case 'kcp':
				$pg_easy_pay_name = 'PAYCO';
				break;
		}
		$multi_settle .= "<option value='Easy payment'>{$pg_easy_pay_name}</option>\n";
	}

	// Payment of Samsung Pay is possible only when using Inicis
	if($default['de_samsung_pay_use'] && ($default['de_pg_service'] == 'inicis')) {
		$multi_settle .= "<option value='Samsung Pay'>Samsung Pay</option>\n";
	}
	?>

	<section id="sod_frm_pay">
		<h2 class="anc_tit">Payment information input</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">Payment method</th>
				<td>
					<select name="paymethod" onchange="calculate_paymethod(this.value);" class="wfull">
						<option value="">To select</option>
						<?php echo $multi_settle; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">Sum</th>
				<td><strong><?php echo display_price($tot_price); ?></strong></td>
			</tr>
			<tr>
				<th scope="row">extra delivery fee</th>
				<td>
					<strong><span id="send_cost2">0</span>won</strong>
					<span class="fc_999">(Shipping charges added by region)</span>
				</td>
			</tr>
			<?php
			if($is_member && $config['coupon_yes']) { // 
Possession coupon
				$sp_count = get_cp_precompose($member['id']);
			?>
			<tr>
				<th scope="row">discount coupon</th>
				<td>
					<span id="dc_coupon"><a href="javascript:window.open('<?php echo TB_MSHOP_URL; ?>/ordercoupon.php');" class="btn_small bx-red">Available coupons <?php echo $sp_count[3]; ?>chapter</a>&nbsp;</span>(-)&nbsp;&nbsp;<strong><span id="dc_amt">0</strong>won&nbsp;<span id="dc_cancel" style="display:none;"><a href="javascript:coupon_cancel();" class="btn_small grey">delete</a></span></span>
				</td>
			</tr>
			<?php } ?>
			<?php 
			if($is_member && $config['usepoint_yes']) { ?>
			<tr>
				<th scope="row">Point payment</th>
				<td>
					<input type="text" name="use_point" value="0" onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);" class="frm_input w100"> won
					<div>
balance : <b><?php echo display_point($member['point']); ?></b> (<?php echo display_point($config['usepoint']); ?> Available from)</div>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<th scope="row">
Total payment amount</th>
				<td>
					<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input w100" readonly style="background:#f1f1f1;color:red;font-weight:bold;"> won
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="bank_section" style="display:none;">
		<h2 class="anc_tit">Account to deposit</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">
An accountless account</th>
				<td>
					<?php echo mobile_bank_account("bank"); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">Name of depositor</th>
				<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input w100"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="taxsave_section" style="display:none;">
		<h2 class="anc_tit">issuance of documentary evidence</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">Cash receipt</th>
				<td>
					<select name="taxsave_yes" onchange="tax_save(this.value);" class="wfull">
						<option value="N">No issue</option>
						<option value="Y">Individual income tax deduction</option>
						<option value="S">Business Expenditure Evidence</option>
					</select>
					<div id="taxsave_fld_1" style="display:none;">
						<input type="text" name="tax_hp" class="frm_input frm_address" placeholder="cell phone number">
					</div>
					<div id="taxsave_fld_2" style="display:none;">
						<input type="text" name="tax_saupja_no" class="frm_input frm_address" placeholder="Business license number">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">Tax invoice</th>
				<td>
					<select name="taxbill_yes" onchange="tax_bill(this.value);" class="wfull">
						<option value="N">No issue</option>
						<option value="Y">Request for issue</option>
					</select>
					<div id="taxbill_section" style="display:none;">
						<input type="text" name="company_saupja_no" class="frm_input frm_address" placeholder="Business license number."><br>
						<input type="text" name="company_name" class="frm_input frm_address" placeholder="Trade name (corporate name)."><br>
						<input type="text" name="company_owner" class="frm_input frm_address" placeholder="Representative name"><br>
						<input type="text" name="company_addr" class="frm_input frm_address" placeholder="Business establishment address"><br>
						<input type="text" name="company_item" class="frm_input frm_address" placeholder="karma"><br>
						<input type="text" name="company_service" class="frm_input frm_address" placeholder="event">
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php if(!$is_member) { ?>
    <section id="guest_privacy">
		<h2 class="anc_tit">non-member purchase</h2>
		<div class="tbl_head01 tbl_wrap">
			<table>
			<thead>
			<tr>
				<th>purpose</th>
				<th>Item</th>
				<th>
Retention period</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>User identification User identification</td>
				<td>Name, password</td>
				<td>Five years (Act on Consumer Protection, e-Commerce, etc.)</td>
			</tr>
			<tr>
				<td>User identification for shipping and CS response</td>
				<td>Address, contact (e-mail, mobile phone number)</td>
				<td>Five years (Act on Consumer Protection in Electronic Commerce, etc.)</td>
			</tr>
			</tbody>
			</table>
		</div>

		<div id="guest_agree">
			<input type="checkbox" id="agree" value="1" class="css-checkbox lrg">
			<label for="agree" class="css-label">I have read and agree to the collection and use of personal information.</label>
		</div>
	</section>
	<?php } ?>

	<div class="btn_confirm">		
		<input type="submit" value="To order" class="btn_medium wset">
		<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_medium bx-white">Order Cancellation</a>
	</div>
</div>
</form>

<script>
$(function() {
    var zipcode = "";

    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

	// Select destination
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open('./orderaddress.php','win_address');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("textarea[name=memo]").val($(this).val());
    });
});

// island/sand transportation cost check
function calculate_sendcost(code) {
    $.post(
        tb_shop_url+"/ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=baesong_price2]").val(data);
            $("#send_cost2").text(number_format(String(data)));

            calculate_order_price();
        }
    );
}

function calculate_order_price() {
    var sell_price = parseInt($("input[name=org_price]").val()); // Total amount
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // extra delivery fee
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // coupon discount
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //point payment
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var min_point	 = parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point   = parseInt(no_comma(f.use_point.value));
	var sell_price   = parseInt(f.org_price.value);
	var send_cost2   = parseInt(f.baesong_price2.value);
	var mb_coupon    = parseInt(f.coupon_total.value);
	var mb_point     = parseInt(f.mb_point.value);
	var tot_price    = sell_price + send_cost2 - mb_coupon;

	if(f.use_point.value == '') {
		alert('Enter point usage amount. Enter 0 if you do not want to use it.');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert('Point usage amount cannot be greater than the current point held.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert('Point usage amount cannot be greater than the current point held.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < min_point)) {
		alert('Point used amount '+number_format(String(min_point))+'won available from.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(getSelectVal(f["paymethod"]) == ''){
		alert("Please select a payment method.");
		f.paymethod.focus();
		return false;
	}

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "If you are not a member, please enter at least 3 digits of the required password when inquiring the order form.");
    }

	if(getSelectVal(f["paymethod"]) == 'No passbook'){
		check_field(f.bank, "Please select a deposit account.");
		check_field(f.deposit_name, "Please enter the name of the deposit");
	}

	<?php if(!$config['company_type']) { ?>
	if(getSelectVal(f["paymethod"]) == 'No passbook' && getSelectVal(f["taxsave_yes"]) == 'Y') {
		check_field(f.tax_hp, "Please enter your cell phone number");
	}

	if(getSelectVal(f["paymethod"]) == 'No passbook' && getSelectVal(f["taxsave_yes"]) == 'S') {
		check_field(f.tax_saupja_no, "Enter business license number");
	}

	if(getSelectVal(f["paymethod"]) == 'No passbook' && getSelectVal(f["taxbill_yes"]) == 'Y') {
		check_field(f.company_saupja_no, "Enter business license number");
		check_field(f.company_name, "Please enter your name");
		check_field(f.company_owner, "Enter representative name");
		check_field(f.company_addr, "Enter business establishment location");
		check_field(f.company_item, "Enter karma");
		check_field(f.company_service, "Enter event.");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getSelectVal(f["paymethod"]) == 'account transfer') {
		if(tot_price < 150) {
			alert("You can pay more than 150 won for the account transfer.");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == 'Credit card') {
		if(tot_price < 1000) {
			alert("You can pay more than 1,000 won for your credit card.");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == 'Cell phone') {
		if(tot_price < 350) {
			alert("You can pay more than 350 won for your cell phone.");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("Read and consent to the collection and use of personal information.");
			return false;
		}
	}

	if(!confirm("The order details are correct, would you like to order?"))
		return false;

	f.use_point.value = no_comma(f.use_point.value);
	f.tot_price.value = no_comma(f.tot_price.value);

    return true;
}

function calculate_temp_point(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('Point usage must be numeric.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
	}
}

// Payment method
function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // Total amount
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // extra shipping cost
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // coupon discount
	var mb_point   = parseInt($("input[name=mb_point]").val()); // retention point
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// Is the balance of points insufficient?
	if( type == 'point' && mb_point < tot_price ) {
		alert('The point balance is insufficient.');

		$("select[name=paymethod]").val('no passbook');
		$("#bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false); 
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#taxsave_section").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case 'No passbook':
			$("#bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").show();
			<?php } ?>
			break;
		case 'point':
			$("#bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();			
			<?php } ?>
			break;
		default: // Other payment methods
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false); 
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();			
			<?php } ?>
			break;
	}
}

// Cash receipt
function tax_save(val) {
	switch(val) {
		case 'Y': // Individual income tax deduction
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		case 'S': // for expenditure evidence
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		default: //No issue
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
	}
}

// Tax invoice
function tax_bill(val) {
	switch(val) {
		case 'Y':  // Issued
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("select[name=taxsave_yes]").val('N');
			$("#taxbill_section").show();
			break;
		case 'N': //Unissued
			$("#taxbill_section").hide();
			break;
	}
}

// Delete discount coupon
function coupon_cancel() {
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.tot_price.value)); // Final payment amount
	var mb_coupon  = parseInt(f.coupon_total.value); // coupon discount
	var tot_price  = sell_price + mb_coupon;

	$("#dc_amt").text(0);
	$("#dc_cancel").hide();
	$("#dc_coupon").show();

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=coupon_total]").val(0);
	$("input[name=coupon_price]").val("");
	$("input[name=coupon_lo_id]").val("");
	$("input[name=coupon_cp_id]").val("");
}

// Same as buyer information.
function gumae2baesong(checked) {
    var f = document.buyform;

    if(checked == true) {
		f.b_name.value			= f.name.value;
		f.b_cellphone.value		= f.cellphone.value;
		f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
		f.b_telephone.value		= '';
		f.b_zip.value			= '';
		f.b_addr1.value			= '';
		f.b_addr2.value			= '';
		f.b_addr3.value			= '';
		f.b_addr_jibeon.value	= '';

		calculate_sendcost('');
    }
}

gumae2baesong(true);
</script>
<!-- } End of order form -->
