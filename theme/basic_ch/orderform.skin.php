<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- 주문서작성 시작 { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ 想订购的商品明细上 <em>数量及订货金额</em>一定要确认一下这个没有错
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
		<th scope="col">形象</th>
		<th scope="col">商品/期权信息</th>
		<th scope="col">数量</th>
		<th scope="col">商品金额</th>
		<th scope="col">小计</th>
		<th scope="col">点</th>
		<th scope="col">配送费</th>
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

		// 合计金额计算
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

		// 配送费
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

	// 配送费检查
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
		if($condition[$key][/'捆']) {
			$com_send_cost += array_sum($condition[$key][/'捆']); // 合并发货合计
			$max_send_cost += max($condition[$key][/'捆']); // 最大的运费合计
			$com_array[] = max(array_keys($condition[$key][/'捆'])); // max key
			$val_array[] = max(array_values($condition[$key][/'捆']));// max value
		}
		if($condition[$key][/'个别']) {
			$sep_send_cost += array_sum($condition[$key][/'个别']); // 不可捆绑发货合计
			$com_array[] = array_keys($condition[$key][/'个别']); // 全部排列 key
			$val_array[] = array_values($condition[$key][/'个别']); // 全部排列 value
		}
	}

	$baesong_price = get_tune_sendcost($com_array, $val_array);

	$send_cost = $com_send_cost + $sep_send_cost; // 总配送费合计
	$tot_send_cost = $max_send_cost + $sep_send_cost; // 最终运费
	$tot_final_sum = $send_cost - $tot_send_cost; // 配送费折扣
	$tot_price = $tot_sell_price + $tot_send_cost; // 预定结算金额
	?>
	</tbody>
	</table>
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
					<td class="list2 tac bold" colspan="2">目前持有百分点</td>
					<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="w50p">
			<h2 class="anc_tit">预计结算金额统计</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">咒语</th>
					<td class="tar">(A) 订单金额合计</td>
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
	<h2 class="anc_tit">订购者</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<?php if(!$is_member) { // 非会员的话 ?>
		<tr>
			<th scope="row">密码</th>
			<td>
				<input type="password" name="od_pwd" required itemname="密码" class="frm_input required" size="20">
				<span class="frm_info">零,数字 3~20자 (查询订单时需要)</span>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">名</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname="名" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">电话号码</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">手机</th>
			<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname="핸드폰" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">住址</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>" required itemname="우편번호" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">地址检索</a>
				</div>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required itemname="주소" class="frm_input required" size="60" readonly> 基本地址
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 详细地址
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60" readonly> 参考项目
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
	<h2 class="anc_tit">收货人</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">选择配送地</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> 与订购者相同</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> 新配送地</label>
				<?php if($is_member) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> 配送地目录</label>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">名</th>
			<td><input type="text" name="b_name" required itemname="이름" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">电话号码</th>
			<td><input type="text" name="b_telephone" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">手机</th>
			<td><input type="text" name="b_cellphone" required itemname="핸드폰" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">住址</th>
			<td>
				<div>
					<input type="text" name="b_zip" required itemname="邮政编码" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">地址检索</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" required itemname="주소" class="frm_input required" size="60" readonly> 基本地址
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" class="frm_input" size="60"> 详细地址
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" class="frm_input" size="60" readonly> 参考项目
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">要传达的话</th>
			<td>
				<select name="sel_memo">
					<option value="">选择要求事项</option>
					<option value="不在时请交给警卫室。.">不在时请交给警卫室。</option>
					<option value="请尽快配送。.">请尽快配送。.</option>
					<option value="不在时请用手机联系。.">不在时请用手机联系。</option>
					<option value="配送前请联系。">配送前请联系。</option>
				</select>
				<textarea name="memo" class="frm_textbox h60 mart5" rows="3"></textarea>
				<span class="frm_info"><strong class="fc_red">"快递员"</strong>请写下要传达的话~!<br>C/S有关咨询请在客服中心填写。 留在这里的话无法确认。</span>
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">输入结算信息</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">结算方法</th>
			<td class="td_label">
				<?php
				$escrow_title = "";
				if($default['de_escrow_use']) {
					$escrow_title = "埃斯克罗 ";
				}

				if($is_kakaopay_use) {
					echo '<input type="radio" name="paymethod" id="paymethod_kakaopay" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"> <label for="paymethod_kakaopay" class="kakaopay_icon">可可豆</label>'.PHP_EOL;
				}
				if($default['de_bank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_bank" value="无折存款" onclick="calculate_paymethod(this.value);"> <label for="paymethod_bank">无折存款</label>'.PHP_EOL;
				}
				if($default['de_card_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_card" value="信用卡" onclick="calculate_paymethod(this.value);"> <label for="paymethod_card">信用卡</label>'.PHP_EOL;
				}
				if($default['de_hp_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_hp" value="手机" onclick="calculate_paymethod(this.value);"> <label for="paymethod_hp">手机</label>'.PHP_EOL;
				}
				if($default['de_iche_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_iche" value="转账" onclick="calculate_paymethod(this.value);"> <label for="paymethod_iche">'.$escrow_title.'转账</label>'.PHP_EOL;
				}
				if($default['de_vbank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_vbank" value="虚拟账户" onclick="calculate_paymethod(this.value);"> <label for="paymethod_vbank">'.$escrow_title.'虚拟账户</label>'.PHP_EOL;
				}
				if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point'])) {
					echo '<input type="radio" name="paymethod" id="paymethod_point" value="点" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point">积分结算</label>'.PHP_EOL;
				}

				// PG 简便结算
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

					echo '<input type="radio" name="paymethod" id="paymethod_easy_pay" value="简便结算" onclick="calculate_paymethod(this.value);"><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'">'.$pg_easy_pay_name.'</label>'.PHP_EOL;
				}
				?>
			</td>
		</tr>
		<tr>
			<th scope="row">合计</th>
			<td class="bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		<tr>
			<th scope="row">追加运费</th>
			<td>
				<strong><span id="send_cost2">0</span>원</strong>
				<span class="fc_999">(根据地区追加的导线费等配送费。.)</span>
			</td>
		</tr>
		<?php
		if($is_member && $config['coupon_yes']) { // 保有券
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th scope="row">优惠券</th>
			<td>(-) <strong><span id="dc_amt">0</span>韩元 <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></strong>
			<span id="dc_coupon"><a href="<?php echo TB_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false"><span class='fc_197 tu'>可使用优惠券 <?php echo $cp_count[3]; ?>张</a> </span></span></td>
		</tr>
		<?php } ?>
		<?php
		if($is_member && $config['usepoint_yes']) { ?>
		<tr>
			<th scope="row">积分结算</th>
			<td>
				<input type="text" name="use_point" value="0" class="frm_input" size="12" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> 韩元 保持点 : <?php echo display_point($member['point']); ?>
				<?php if($config['usepoint']) { ?>
				(<strong><?php echo display_point($config['usepoint']); ?></strong> 可开始使用)
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">总结算金额</th>
			<td>
				<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input" size="12" readonly style="font-weight:bold;color:#ec0e03;"> 韩元
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="bank_section" style="display:none;">
	<h2 class="anc_tit">要汇款的账户</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">选择存款账户</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr>
			<th scope="row">汇款人名</th>
			<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input" size="12"></td>
		</tr>
		</table>
	</div>
</section>

<?php if(!$config['company_type']) { ?>
<section id="tax_section" style="display:none;">
	<h2 class="anc_tit">要求提供文件证明</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">现金收据发行</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">个人所得抵扣用</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">营业执照</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">未发行</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">手机号码</th>
			<td>
				<input type="text" name="tax_hp" class="frm_input" size="20">
				<span class="frm_info">
					现金收据在1韩元以上现金购买时可发放。<br>
					现金收据在购买款汇款确认日第二天发放。<br>
					现金收据网站 :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">营业执照号</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">开具税单</th>
			<td class="td_label">
				<input type="radio" id="taxbill_1" name="taxbill_yes" value="Y" onclick="tax_bill(4);">
				<label for="taxbill_1">发行要求</label>
				<input type="radio" id="taxbill_2" name="taxbill_yes" value="N" onclick="tax_bill(5);" checked>
				<label for="taxbill_2">未发行</label>
			</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">营业执照号</td>
			<td><input type="text" name="company_saupja_no" size="20" class="frm_input"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">商号(法人名)</th>
			<td><input type="text" name="company_name" class="frm_input" size="20"> 例子 : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">代表</th>
			<td><input type="text" name="company_owner" class="frm_input" size="20"> 例子 : 洪吉童</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">营业场所地址</th>
			<td><input type="text" name="company_addr" class="frm_input" size="60"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">业态</th>
			<td><input type="text" name="company_item" class="frm_input" size="20"> 例子 : 批发零售</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">项目</th>
			<td><input type="text" name="company_service" class="frm_input" size="20"> 例子 : 电子元件</td>
		</tr>
		</table>
	</div>
</section>
<?php } ?>

<?php if(!$is_member) { ?>
<section id="guest_privacy">
	<h3 class="anc_tit">个人信息收集及使用</h3>
	<p>非会员订购时不得积分累计及追加优惠。</p>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th scope="col">目的</th>
			<th scope="col">项目</th>
			<th scope="col">保有期</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>使用者识别及本人确认인</td>
			<td>姓名,密码</td>
			<td>5年(关于电子商务等消费者保护的法律)</td>
		</tr>
		<tr>
			<td>为配送及CS对应的用户识别</td>
			<td>地址,联系方式(电子邮件,手机号码)</td>
			<td>5年(关于电子商务等消费者保护的法律)</td>
		</tr>
		</tbody>
		</table>
	</div>

	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">阅读了个人信息收集及使用内容,同意。</label>
	</fieldset>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="订购" class="btn_large wset">
	<a href="<?php echo TB_SHOP_URL; ?>/cart.php" class="btn_large bx-white">取消</a>
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

	// 选择配送地
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

// 图书/产间配送费检查
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
    var sell_price = parseInt($("input[name=org_price]").val()); // 合计金额
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 追加运费
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 优惠券折扣
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //积分结算
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
		alert('请输入积分使用金额。 不想使用时请输入0。');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert(/'积分使用金额不得大于现有积分。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert(/'积分使用金额不得大于最终结算金额。');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < min_point)) {
		alert(/'使用积分的金额是 '+number_format(String(min_point))+/'可从一元开始使用。');
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
        alert("请选择结算方法。");
        return false;
    }

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "非会员查询订单时请输入3位数以上的密码。");
    }

	if(getRadioVal(f.paymethod) == /'无折存款') {
		check_field(f.bank, "请选择汇款账号。");
		check_field(f.deposit_name, "请输入汇款者名。");
	}

	<?php if(!$config['company_type']) { ?>
	if(getRadioVal(f.paymethod) == /'无折存款' && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "请输入手机号。");
	}

	if(getRadioVal(f.paymethod) == /'无折存款' && getRadioVal(f.taxsave_yes) == 'S') {
		check_field(f.tax_saupja_no, "请输入营业执照号。");
	}

	if(getRadioVal(f.paymethod) == /'无折存款' && getRadioVal(f.taxbill_yes) == 'Y') {
		check_field(f.company_saupja_no, "请输入营业执照号。");
		check_field(f.company_name, "请输入互名。");
		check_field(f.company_owner, "请输入代表人名。");
		check_field(f.company_addr, "请输入营业场所材料地。");
		check_field(f.company_item, "请输入业态。");
		check_field(f.company_service, "请输入项目");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getRadioVal(f.paymethod) == '转账') {
		if(tot_price < 150) {
			alert("150万韩元以上转账结算。");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == /'信用卡') {
		if(tot_price < 1000) {
			alert("信用卡可以结算1000韩元以上。");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == /'手机') {
		if(tot_price < 350) {
			alert("手机可以结算350韩元以上。");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("请阅读个人信息收集及使用内容后同意。");
			return false;
		}
	}

	if(!confirm("订购明细准确,是否订购?"))
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
		alert(/'特点是必须使用数字');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
	}
}

function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // 合计金额
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 追加运费
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 优惠券折扣
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 持仓点
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 积分余额是否不足?
	if( type == /'点' && mb_point < tot_price ) {
		alert(/'积分余额不足。');

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
		case /'无折存款':
			$("#bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").show();
			<?php } ?>
			break;
		case /'点':
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
		default: // 其他结算方式
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
	var sell_price = parseInt(no_comma(f.tot_price.value)); // 最终结算金额
	var mb_coupon  = parseInt(f.coupon_total.value); // 优惠券折扣
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

// 与购买者信息相同。
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
<!-- } 订单完成完毕 -->
