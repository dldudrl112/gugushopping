<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">ウィンドウを閉じる</a>
</h2>

<form name="flist" method="post">
<input type="hidden" name="sum_dc_amt">
<input type="hidden" name="layer_cnt">

<div id="sod_coupon">
	<div class="scope">
		1, 重複割引可能以外のクーポンは個別商品のみ適用可能です。<br>
		2, クーポン適用時,配送費は割引されません。<br>
		3, 注文(キャンセル/返品)する場合は,クーポンは自動消滅されます。<br>
		4, 発行されたクーポンはマイページで確認できます。
	</div>

	<?php
	$tot_price = 0;
	for($i=0; $row=sql_fetch_array($result2); $i++) {
		$gs = get_goods($row['gs_id']);

		// 合計金額計算
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty
				   from shop_cart
				  where gs_id = '$row[gs_id]'
					and ct_direct = '$set_cart_id'
					and ct_select = '0'";
		$sum = sql_fetch($sql);

		$price = $sum['price'];
		$tot_price += $price;

		// 所属カテゴリーをコンマに区分して抽出
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
			<td colspan='2'><button type="button" class="avail_button" onclick="show_coupon('<?php echo $i; ?>');return false;">●適用可能クーポン選択</button></td>
		</tr>
		</tbody>
		</table>

		<table class="th_box">
		<tbody>
		<tr>
			<td class="tal">数量</td>
			<td class="tar"><?php echo display_qty($sum['qty']); ?></td>
		</tr>
		<tr>
			<td class="tal">割引金額</td>
			<td class="tar">
				<span id="dc_amt_<?php echo $i; ?>">0</span>ウォン
				<span id="dc_cancel_bt_<?php echo $i; ?>" style="display:none">&nbsp;<a href="javascript:coupon_cancel('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class='btn_ssmall bx-white'>削除</a></span>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="to_wrap to_box">
		<dl class="to_tline">
			<dt>商品金額合計</dt>
			<dd><?php echo display_price($tot_price); ?></dd>
			<dt class="point_bg">割引金額合計</dt>
			<dd class="point_bg"><span id="to_dc_amt">0</span>ウォン</dd>
		</dl>
	</div>
	<div class="btn_confirm">
		<button type="button" onclick="cp_submit();return false;" class="btn_medium red">クーポン適用</button>
		<button type="button" onclick="window.close();" class="btn_medium bx-white">障子</button>
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
			case '0': // 全体商品にクーポン使用可能
				$is_coupon = true;
				break;
			case '1': // 一部の商品のみクーポンが使える
				if($row['cp_use_goods']) {
					$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
					if($fields_cnt)
						$is_coupon = true;
				}
				break;
			case '2': // 一部カテゴリーだけがクーポン使用可能
				if($row['cp_use_category']) {
					$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
					if($fields_cnt)
						$is_coupon = true;
				}
				break;
			case '3': // 一部の商品はクーポンを使用できない
				if($row['cp_use_goods']) {
					$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
					if(!$fields_cnt)
						$is_coupon = true;
				}
				break;
			case '4': // 一部のカテゴリーはクーポン使用禁止
				if($row['cp_use_category']) {
					$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
					if(!$fields_cnt)
						$is_coupon = true;
				}
				break;
		}

		// 適用するかどうか && 加盟店商品除外 && 最大金額 <= 商品金額
		$seq = array();
		if($is_coupon && !$is_gubun[3] && ($row['cp_low_amt'] <= (int)$is_gubun[0])) {
			// ディスカウントチェック
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
			<td class="tal">※ 適用するクーポン選択</td>
			<td class="tar"><a href="#" onclick="hide_cp_list('<?php echo $i; ?>'); return false;" class="btn_small bx-white">閉じる</a></td>
		</tr>
		</table>

		<table class="ly_box mart5">
		<tbody>
		<tr>
			<td class="cell">クーポン番号</td>
			<td class="cell">割引</td>
			<td class="cell">割引価格</td>
		</tr>
		<?php
		//5|1|8|0|10%|37496
		// 商品主キー|クーポンの基本キー|クーポン番号|同時使用するかどうか|割引額（率）|割引価格
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
		<tr><td colspan="3" class="empty_list">使用できるクーポンがありません。</td></tr>
		<?php } ?>
		<tbody>
		</table>

		<div class="btn_confirm">
			<button type="button" onclick="return applycoupon('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class="btn_medium red">クーポン適用</button>
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

	// ●個別商品に適用するクーポンを未選択すれば
	if(!getRadioValue(f["use_cp_"+gs_id+"_"+cart_id])){
		alert(/'商品に適用するクーポンを選んでください。');
		return false;
	}

	// クーポン番号取得
	var info = getRadioValue(f["use_cp_"+gs_id+"_"+cart_id]).split("|");
	var cp_no = info[0]; // 使用されたクーポン番号
	var gd_dc_amt = info[1]; // クーポン割引額
	var cp_idx = info[2]; // クーポン IDX
	var cp_dups = info[3]; //重複適用可否

	// すでに適用されているクーポンか検査
	for(i=0;i<max_layer;i++){
		tmp = f["gd_cp_no_"+i].value; // 使用されたクーポン番号
		if(tmp != ""){
			if(cp_no == tmp){
				// 重複適用不可
				if(cp_dups == "1"){
					alert(/'当該クーポンは重複割引になりません。');
					f["use_cp_"+gs_id+"_"+cart_id].checked = false;
					hide_cp_list(layer_idx);
					return false;
				}
			}
		}
	}

	// クーポンを適用すべきかどうかを商品別に記録
	f["gd_dc_amt_"+layer_idx].value = gd_dc_amt;

	// 適用されたクーポン情報を商品別として保存
	f["gd_cp_info_"+layer_idx].value = gs_id+"|"+cart_id+"|"+cp_no+"|"+cp_idx+"|"+gd_dc_amt;
	f["gd_cp_no_"+layer_idx].value = cp_no;
	f["gd_cp_idx_"+layer_idx].value = cp_idx;

	// 全体の割引価格を得る
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
	// 総割引額の記録
	f.sum_dc_amt.value = sum_dc_amt;

	// label 変更
	document.getElementById("dc_amt_"+layer_idx).innerText = number_format(String(gd_dc_amt));
	document.getElementById("to_dc_amt").innerText = number_format(String(sum_dc_amt));
	document.getElementById("cp_avail_button_"+layer_idx).style.display = "none"; // 適用したのは見えないように
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "";

	hide_cp_list(layer_idx);
}

function coupon_cancel(gs_id, cart_id, layer_idx){
	var f = document.flist;

	// クーポンを適用すべきかどうかを商品別に記録
	f["gd_dc_amt_"+layer_idx].value = 0;

	// 適用されたクーポン情報を商品別に削除
	f["gd_cp_info_"+layer_idx].value = "";
	f["gd_cp_no_"+layer_idx].value = "";
	f["gd_cp_idx_"+layer_idx].value = "";

	// 全体割引価格を得る
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
	// 総割引額の記録
	f.sum_dc_amt.value = sum_dc_amt;

	// label 変更
	document.getElementById("dc_amt_"+layer_idx).innerText = 0;
	document.getElementById("to_dc_amt").innerText = number_format(String(sum_dc_amt));
	document.getElementById("cp_avail_button_"+layer_idx).style.display = ""; // 再び見えるように
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
		alert("商品にクーポンを選んでください。");
		return false;
	}

	if(parseInt(no_comma(tot_price)) < f.sum_dc_amt.value) {
		alert("クーポン割引金額が決済金額を超過しました。");
		return false;
	}

	if(!confirm("クーポン適用をされますか。?"))
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

	// ログ
	opener.document.buyform.coupon_price.value = tmp_dc_amt;
	opener.document.buyform.coupon_lo_id.value = tmp_lo_id;
	opener.document.buyform.coupon_cp_id.value = tmp_cp_id;

	// 総割引額
	opener.document.buyform.coupon_total.value = f.sum_dc_amt.value;
	opener.document.getElementById("dc_amt").innerText = number_format(String(f.sum_dc_amt.value));
	opener.document.getElementById("dc_cancel").style.display = "";
	opener.document.getElementById("dc_coupon").style.display = "none";

	tot_price = parseInt(no_comma(tot_price)) - f.sum_dc_amt.value;

	// 最終的な支払い金額
	opener.document.buyform.tot_price.value = number_format(String(tot_price));

	self.close();
}
</script>
