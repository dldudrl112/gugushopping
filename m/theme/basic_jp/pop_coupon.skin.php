<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">창닫기</a>
</h2>

<div id="sit_coupon">
	<table class="tbl_cp">
	<colgroup>
		<col width="60px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<td class="scope" colspan='2'>
			この商品購買時,使用できる割引クーポンです。.<br>
			ダウンロードした後,注文の時にご使用ください。!<br>
			発行されたクーポンはマイページで確認できます。
		</td>
	</tr>
	<tr>
		<td class="image"><?php echo get_it_image($gs['index_no'], $gs['simg1'], 60, 60); ?></td>
		<td class="gname">
			<?php echo get_text($gs['gname']); ?>
			<p class="bold mart5"><?php echo mobile_price($gs['index_no']); ?></p>
		</td>
	</tr>
	</tbody>
	</table>

	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">使用可能なクーポンがありません。</p>";
	} else {
	?>
		<table class="tbl_cp mart10">
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$cp_id = $row['cp_id'];

			$str  = "";
			$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";

			// クーポン発行期間
			$str .= "<div class='padt5'>&#183; ダウンロード期間 : ";
			if($row['cp_type'] != '3') {
				if($row['cp_pub_sdate'] == '9999999999') $cp_pub_sdate = '';
				else $cp_pub_sdate = $row['cp_pub_sdate'];

				if($row['cp_pub_edate'] == '9999999999') $cp_pub_edate = '';
				else $cp_pub_edate = $row['cp_pub_edate'];

				if($row['cp_pub_sdate'] == '9999999999' && $row['cp_pub_edate'] == '9999999999')
					$str .= "制限なし";
				else
					$str .= $cp_pub_sdate." ~ ".$cp_pub_edate;

				// クーポンの発行曜日
				if($row['cp_type'] == '1') {
					$str .= "&nbsp;-&nbsp;毎週 (".$row['cp_week_day'].")";
				}
			} else {
				$str .= "誕生日 (".$row['cp_pub_sday']."日前 ~ ".$row['cp_pub_eday']."日以降まで)";
			}
			$str .= "</div>";

			// 恵沢
			$str .= "<div class='padt5'>&#183; ";
			if(!$row['cp_sale_type']) {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(最大 ".display_price($row['cp_sale_amt_max'])."まで割引)";
				else
					$cp_sale_amt_max = "";

				$str .= $row['cp_sale_percent']. '% 割引' . $cp_sale_amt_max;
			} else {
				$str .= display_price($row['cp_sale_amt']). /' 割引';
			}
			$str .= "</div>";

			// 最大金額
			if($row['cp_low_amt'] > 0) {
				$str .= "<div class='padt5'>&#183; ".display_price($row['cp_low_amt'])." 異常購入時</div>";
			}

			// 使用可能対象
			$str .= "<div class='padt5'>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

			$s_upd = "<button type=\"button\" onclick=\"post_update('".TB_MSHOP_URL."/pop_coupon_update.php', '$cp_id');\" class=\"btn_small\">現在クーポンダウンロード</button>";
		?>
		<tr>
			<td class="cbtn">
				<?php echo $str; ?>
				<p class="padt10"><?php echo $s_upd; ?></p>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>		
	<?php 
	}
	?>

	<?php echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?gs_id='.$gs_id.'&page='); ?>

	<div class="btn_confirm">
		<button type="button" onclick="window.close();" class="btn_medium bx-white">ウィンドウを閉じる</button>
	</div>
</div>

<script>
function post_update(action_url, val) {
	var f = document.fpost;
	f.cp_id.value = val;
	f.action = action_url;
	f.submit();
}
</script>

<form name="fpost" method="post">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="cp_id">
</form>
