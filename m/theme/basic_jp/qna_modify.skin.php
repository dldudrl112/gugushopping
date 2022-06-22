<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">

<div class="m_bo_bg">
	<div class="m_bo_wrap">
		<table class="tbl03">
		<colgroup>
			<col style="width:70px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<th>質問タイプ</th>
			<td>
				<select name="catename" class="wfull">
					<option value="">お問い合わせのタイプを選んでください。</option>
					<?php
					$sql = "select * from shop_qa_cate where isuse='Y'";
					$res = sql_query($sql);
					while($row=sql_fetch_array($res)) {
						echo "<option ".get_selected($qa['catename'],$row['catename'])." value='$row[catename]'>$row[catename]</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>題目</th>
			<td><input type="text" name="subject" value="<?php echo $qa['subject']; ?>"></td>
		</tr>
		<tr>
			<th>内容</th>
			<td><textarea name="memo" rows="7"><?php echo $qa['memo']; ?></textarea></td>
		</tr>
		<tr>
			<th>E-mail</th>
			<td>
				<input type="text" name="email" value="<?php echo $qa['email']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">答弁の内容をメールで送ってみます?</span>
					<input type="checkbox" name="email_send_yes" value="1" <?php echo get_checked($qa['email_send_yes'],'1'); ?> id="email_send_yes" class="css-checkbox lrg"> <label for="email_send_yes" class="css-label">はい</label>
				</p>
			</td>
		</tr>
		<tr>
			<th>携帯電話</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $qa['cellphone']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">答弁を文字で受けてみます?</span>
					<input type="checkbox" name="sms_send_yes" value="1" <?php echo get_checked($qa['sms_send_yes'],'1'); ?> id="sms_send_yes" class="css-checkbox lrg"> <label for="sms_send_yes" class="css-label">はい</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">	
		<input type="submit" value="修整" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">キャンセル</a>
	</div>	
</div>
</form>

<script>
function fqaform_submit(f) {
	if(confirm("ご修正しますか?") == false)
		return false;

	return true;
}
</script>
