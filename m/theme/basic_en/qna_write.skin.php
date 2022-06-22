<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="token" value="<?php echo $token; ?>">

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
				<select name="catename" required itemname="Question type" class="wfull">
					<option value="">Please select the type you want to inquire about</option>
					<?php
					$sql = "select * from shop_qa_cate where isuse='Y'";
					$res = sql_query($sql);
					while($row=sql_fetch_array($res)) {	
						echo "<option value='$row[catename]'>$row[catename]</option>\n";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th>title</th>
			<td><input type="text" name="subject" required itemname="title"></td>
		</tr>
		<tr>
			<th>Question details</th>
			<td><textarea name="memo" required rows="7" itemname="Question details"></textarea></td>
		</tr>
		<tr>
			<th>E-mail</th>
			<td>
				<input type="text" name="email" value="<?php echo $member['email'];?>">
				<p class="mart5 fs12">
					<span class="marr10">Would you like to receive your answers by e-mail?</span>
					<input type="checkbox" name="email_send_yes" value="1" id="email_send_yes" class="css-checkbox lrg"> <label for="email_send_yes" class="css-label">example</label>
				</p>
			</td>
		</tr>
		<tr>
			<th>cell phone</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>">
				<p class="mart5 fs12">
					<span class="marr10">Would you like to text me if I answered your question?</span>
					<input type="checkbox" name="sms_send_yes" value="1" id="sms_send_yes" class="css-checkbox lrg"> <label for="sms_send_yes" class="css-label">example</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="Writing" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">cancel</a>
	</div>	
</div>
</form>

<script>
function fqaform_submit(f) {
	if(confirm("Would you like to register?") == false)
		return false;

	return true;
}
</script>
