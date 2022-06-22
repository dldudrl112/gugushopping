<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- Start filling out an order { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ Product Details to Order <em>Quantity and amount of order</em>Please make sure you are not wrong.
</p>

<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">

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
		<th scope="col">Product/Option Information</th>
		<th scope="col">Quantity</th>
		<th scope="col">Product amount</th>
		<th scope="col">partial sum</th>
		<th scope="col">Point</th>
		<th scope="col">The delivery charge</th>
	</tr>
	</thead>
	<tbody>
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

		// Total amount calculation
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
		                SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_supply_price) * ct_qty))) as supply_price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
				   from shop_cart
				  where gs_id = '$row[gs_id]'
				    and ct_direct = '$set_cart_id'
				    and ct_select = '0'";
		$sum = sql_fetch($sql);

		$it_name = stripslashes($gs['gname']);
		$it_options = print_item_options($row['gs_id'], $set_cart_id);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}

		if($is_member) {
			$point = $sum['point'];
		}

		$supply_price = $sum['supply_price'];
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

		$seller_id[$i] = $gs['mb_id'];

		$href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="tac">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
			<?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?>
		</td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($sell_qty); ?></td>
		<td class="tar"><?php echo number_format($sell_amt); ?></td>
		<td class="tar"><?php echo number_format($sell_price); ?></td>
		<td class="tar"><?php echo number_format($point); ?></td>
		<td class="tar"><?php echo number_format($info['price']); ?></td>
	</tr>
	<?php
		$tot_point += (int)$point;
		$tot_sell_price += (int)$sell_price;
		$tot_opt_price += (int)$sell_opt_price;
		$tot_sell_qty += (int)$sell_qty;
		$tot_sell_amt += (int)$sell_amt;
	}

	// Delivery cost check
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
		if($condition[$key]['Bags']) {
			$com_send_cost += array_sum($condition[$key]['Bags']); // bundle delivery sum
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

	$baesong_price = get_tune_sendcost($com_array, $val_array);

	$send_cost = $com_send_cost + $sep_send_cost; // Total Delivery Expenses
	$tot_send_cost = $max_send_cost + $sep_send_cost; // final delivery fee
	$tot_final_sum = $send_cost - $tot_send_cost; // Shipping discount
	$tot_price = $tot_sell_price + $tot_send_cost; // Payment scheduled amount
	?>
	</tbody>
	</table>
</div>

<div id="sod_bsk_tot">
	<table class="wfull">
	<tr>
		<td class="w50p">
			<h2 class="anc_tit">Commodity statistics included in the CART</h2>
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
					<th scope="row">Order</th>
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
					<td class="tar">(B) final delivery fee</td>
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

<section id="sod_fin_orderer">
	<h2 class="anc_tit">Ordering person</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<?php if(!$is_member) { // non-member ?>
		<tr>
			<th scope="row">Password</th>
			<td>
				<input type="password" name="od_pwd" required itemname="Password" class="frm_input required" size="20">
				<span class="frm_info">English,number 3~20letter (Required for order lookup)</span>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">Name</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname="Name" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Phone number</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">cell phone</th>
			<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname="cell phone" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Address</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>" required itemname="Postal code" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">Address search</a>
				</div>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required itemname="Address" class="frm_input required" size="60" readonly> Base address
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> Detail address
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60" readonly> Reference item
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><input type="text" name="email" value="<?php echo $member['email']; ?>" required email itemname="E-mail" class="frm_input required" size="30"></td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_receiver">
	<h2 class="anc_tit">Recipient</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Select Shipping Location</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> Same as order</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> New Shipping Site</label>
				<?php if($is_member) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> Shipping list</label>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">Name</th>
			<td><input type="text" name="b_name" required itemname="Name" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Phone number</th>
			<td><input type="text" name="b_telephone" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">cell phone</th>
			<td><input type="text" name="b_cellphone" required itemname="cell phone" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Address</th>
			<td>
				<div>
					<input type="text" name="b_zip" required itemname="Postal code" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">Address search</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" required itemname="Address" class="frm_input required" size="60" readonly> Base address
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" class="frm_input" size="60"> Detail address
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" class="frm_input" size="60" readonly> Reference item
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">Your message.</th>
			<td>
				<select name="sel_memo">
					<option value="">Select Request</option>
					<option value="If you're not here, we have to leave it to the security office.">If you're not here, we have to leave it to the security office.</option>
					<option value="I want a quick delivery.">I want a quick delivery.</option>
					<option value="Please contact me on your cell phone when you are away.">Please contact me on your cell phone when you are away.</option>
					<option value="Please contact me before shipping.">Please contact me before shipping.</option>
				</select>
				<textarea name="memo" class="frm_textbox h60 mart5" rows="3"></textarea>
				<span class="frm_info"><strong class="fc_red">"a courier"</strong>Write a message.~!<br>C/SComplete the relevant questions in the customer center.You can't check if you're here.</span>
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">Payment information input</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Payment method</th>
			<td class="td_label">
				<?php
				$escrow_title = "";
				if($default['de_escrow_use']) {
					$escrow_title = "Escrow ";
				}

				if($is_kakaopay_use) {
					echo '<input type="radio" name="paymethod" id="paymethod_kakaopay" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"> <label for="paymethod_kakaopay" class="kakaopay_icon">Kakao Pay</label>'.PHP_EOL;
				}
				if($default['de_bank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_bank" value="without passbook" onclick="calculate_paymethod(this.value);"> <label for="paymethod_bank">without bankbook</label>'.PHP_EOL;
				}
				if($default['de_card_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_card" value="Credit card" onclick="calculate_paymethod(this.value);"> <label for="paymethod_card">Credit card</label>'.PHP_EOL;
				}
				if($default['de_hp_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_hp" value="Cell phone" onclick="calculate_paymethod(this.value);"> <label for="paymethod_hp">Cell phone</label>'.PHP_EOL;
				}
				if($default['de_iche_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_iche" value="account transfer" onclick="calculate_paymethod(this.value);"> <label for="paymethod_iche">'.$escrow_title.'account transfer</label>'.PHP_EOL;
				}
				if($default['de_vbank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_vbank" value="virtual account" onclick="calculate_paymethod(this.value);"> <label for="paymethod_vbank">'.$escrow_title.'virtual account</label>'.PHP_EOL;
				}
				if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point'])) {
					echo '<input type="radio" name="paymethod" id="paymethod_point" value="Point" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point">Point payment</label>'.PHP_EOL;
				}

				//PG Simple settlement
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

					echo '<input type="radio" name="paymethod" id="paymethod_easy_pay" value="Easy payment" onclick="calculate_paymethod(this.value);"><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'">'.$pg_easy_pay_name.'</label>'.PHP_EOL;
				}
				?>
			</td>
		</tr>
		<tr>
			<th scope="row">Total</th>
			<td class="bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		<tr>
			<th scope="row">extra delivery fee</th>
			<td>
				<strong><span id="send_cost2">0</span>WON</strong>
				<span class="fc_999">(Shipping charges, such as docks, are added by region.)</span>
			</td>
		</tr>
		<?php
		if($is_member && $config['coupon_yes']) { // retained coupon
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th scope="row">Discount coupon</th>
			<td>(-) <strong><span id="dc_amt">0</span>WON <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></strong>
			<span id="dc_coupon"><a href="<?php echo TB_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false"><span class='fc_197 tu'>Available coupon <?php echo $cp_count[3]; ?>chapter</a> </span></span></td>
		</tr>
		<?php } ?>
		<?php
		if($is_member && $config['usepoint_yes']) { ?>
		<tr>
			<th scope="row">Point payment</th>
			<td>
				<input type="text" name="use_point" value="0" class="frm_input" size="12" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> WON holding point : <?php echo display_point($member['point']); ?>
				<?php if($config['usepoint']) { ?>
				(<strong><?php echo display_point($config['usepoint']); ?></strong> Available from)
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">Total payment amount</th>
			<td>
				<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input" size="12" readonly style="font-weight:bold;color:#ec0e03;"> WON
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="bank_section" style="display:none;">
	<h2 class="anc_tit">an account to be deposited with</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Deposit account selection</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr>
			<th scope="row">Deposit holder name</th>
			<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input" size="12"></td>
		</tr>
		</table>
	</div>
</section>

<?php if(!$config['company_type']) { ?>
<section id="tax_section" style="display:none;">
	<h2 class="anc_tit">Document evidence issue request</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Cash receipt issue</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">Personal income deduction</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">Business Expenditure Evidence</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">Unissued</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">Cell phone number</th>
			<td>
				<input type="text" name="tax_hp" class="frm_input" size="20">
				<span class="frm_info">
					Cash receipts can be issued for purchases over 1 won.<br>
					Cash receipt is issued one day after the purchase payment deposit confirmation date.<br>
					Cash receipt homepage :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">Business license number</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Issuing tax invoice</th>
			<td class="td_label">
				<input type="radio" id="taxbill_1" name="taxbill_yes" value="Y" onclick="tax_bill(4);">
				<label for="taxbill_1">Request for issue</label>
				<input type="radio" id="taxbill_2" name="taxbill_yes" value="N" onclick="tax_bill(5);" checked>
				<label for="taxbill_2">Unissued</label>
			</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">Business license number</td>
			<td><input type="text" name="company_saupja_no" size="20" class="frm_input"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">Mutual(Corporate name)</th>
			<td><input type="text" name="company_name" class="frm_input" size="20"> example : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">Representative</th>
			<td><input type="text" name="company_owner" class="frm_input" size="20"> example : Hong Gil-Dong</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">Business address</th>
			<td><input type="text" name="company_addr" class="frm_input" size="60"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">business</th>
			<td><input type="text" name="company_item" class="frm_input" size="20"> example : wholesale and retail</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">
event</th>
			<td><input type="text" name="company_service" class="frm_input" size="20"> example : Electronic parts</td>
		</tr>
		</table>
	</div>
</section>
<?php } ?>

<?php if(!$is_member) { ?>
<section id="guest_privacy">
	<h3 class="anc_tit">Collection and utilization of personal information</h3>
	<p>Non-members are not eligible for point placement and additional benefits.</p>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th scope="col">
purpose</th>
			<th scope="col">category</th>
			<th scope="col">retention period</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>User identification and identification</td>
			<td>Name, Password</td>
			<td>5 years(the law on consumer protection in e-commerce, etc.)</td>
		</tr>
		<tr>
			<td>User identification for shipping and CS response</td>
			<td>Address, Contacts(Email, mobile phone number)</td>
			<td>5 years(the law on consumer protection in e-commerce, etc.)</td>
		</tr>
		</tbody>
		</table>
	</div>

	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">I have read and agree to the collection and use of personal information.</label>
	</fieldset>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="To order" class="btn_large wset">
	<a href="<?php echo TB_SHOP_URL; ?>/cart.php" class="btn_large bx-white">Cancellation</a>
</div>

</form>

<script>
$(function() {
    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);
        calculate_sendcost(code);
    });

	// Select a shipping location
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open(tb_shop_url+'/orderaddress.php','win_address', 600, 600, 'yes');
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
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //Point payment
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var min_point	= parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point	= parseInt(no_comma(f.use_point.value));
	var sell_price	= parseInt(f.org_price.value);
	var send_cost2	= parseInt(f.baesong_price2.value);
	var mb_coupon	= parseInt(f.coupon_total.value);
	var mb_point	= parseInt(f.mb_point.value);
	var tot_price	= sell_price + send_cost2 - mb_coupon;

	if(f.use_point.value == '') {
		alert('Enter point usage amount. If you don't want to use it, enter zero.');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert('Point usage cannot be greater than the current retention point.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert('Point usage amount cannot be greater than final payment amount.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < min_point)) {
		alert('Point used amount '+number_format(String(min_point))+'It can be used from WON.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	var paymethod_check = false;
	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "paymethod" && f.elements[i].checked==true){
			paymethod_check = true;
		}
	}

    if(!paymethod_check) {
        alert("Please select a payment method.");
        return false;
    }

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "If you are not a member, enter at least 3 digits of the required password when inquiring the order form.");
    }

	if(getRadioVal(f.paymethod) == 'without bankbook
') {
		check_field(f.bank, "Please select a deposit account.");
		check_field(f.deposit_name, "Please enter the name of the deposit");
	}

	<?php if(!$config['company_type']) { ?>
	if(getRadioVal(f.paymethod) == 'without bankbook' && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "Please enter your cell phone number");
	}

	if(getRadioVal(f.paymethod) == 'without bankbook' && getRadioVal(f.taxsave_yes) == 'S') {
		check_field(f.tax_saupja_no, "Enter business license number");
	}

	if(getRadioVal(f.paymethod) == 'without bankbook' && getRadioVal(f.taxbill_yes) == 'Y') {
		check_field(f.company_saupja_no, "Enter business license number.");
		check_field(f.company_name, "Please enter your name");
		check_field(f.company_owner, "Enter representative name");
		check_field(f.company_addr, "Enter business establishment location");
		check_field(f.company_item, "Enter karma");
		check_field(f.company_service, "Enter event");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getRadioVal(f.paymethod) == 'account transfer') {
		if(tot_price < 150) {
			alert("You can pay more than 150 won for account transfer.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == 'Credit card') {
		if(tot_price < 1000) {
			alert("You can pay more than 1,000 won for your credit card.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == 'Cell phone') {
		if(tot_price < 350) {
			alert("You can pay more than 350 won for your cell phone.");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("You must read and agree to the collection and use of personal information.");
			return false;
		}
	}

	if(!confirm("The order details are correct. Would you like to order it?"))
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

function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // Total amount
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // extra delivery fee
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // coupon discount
	var mb_point   = parseInt($("input[name=mb_point]").val()); // retention point
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// Is the balance of points insufficient??
	if( type == 'Point' && mb_point < tot_price ) {
		alert('The point balance is insufficient.');

		$("#paymethod_bank").attr("checked", true);
		$("#bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#tax_section").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case 'without bankbook':
			$("#bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").show();
			<?php } ?>
			break;
		case 'Point':
			$("#bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		default: // Other payment methods
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}

function tax_bill(val) {
	switch(val) {
		case 1:
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 2:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 3:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
		case 4:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").show();
			$("#taxsave_3").attr("checked", true);
			break;
		case 5:
			$(".taxbill_fld").hide();
			break;
	}
}

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
