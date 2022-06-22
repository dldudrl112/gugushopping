<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
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
			<th>Question type</th>
			<td>
				<select name="catename" class="wfull">
					<option value="">Please select the type you want to inquire about</option>
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
			<th>Title</th>
			<td><input type="text" name="subject" value="<?php echo $qa['subject']; ?>"></td>
		</tr>
		<tr>
			<th>content</th>
			<td><textarea name="memo" rows="7"><?php echo $qa['memo']; ?></textarea></td>
		</tr>
		<tr>
			<th>E-mail</th>
			<td>
				<input type="text" name="email" value="<?php echo $qa['email']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">Would you like to receive your answers by e-mail?</span>
					<input type="checkbox" name="email_send_yes" value="1" <?php echo get_checked($qa['email_send_yes'],'1'); ?> id="email_send_yes" class="css-checkbox lrg"> <label for="email_send_yes" class="css-label">example</label>
				</p>
			</td>
		</tr>
		<tr>
			<th>cell phone</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $qa['cellphone']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">Would you like to text me if I answered your question?</span>
					<input type="checkbox" name="sms_send_yes" value="1" <?php echo get_checked($qa['sms_send_yes'],'1'); ?> id="sms_send_yes" class="css-checkbox lrg"> <label for="sms_send_yes" class="css-label">example</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">	
		<input type="submit" value="Modified" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">cancel</a>
	</div>	
</div>
</form>

<script>
function fqaform_submit(f) {
	if(confirm("Would you like to modify it?") == false)
		return false;

	return true;
}
</script>
