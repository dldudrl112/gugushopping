<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>Customer Service<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
	<input type="hidden" name="mode" value="w">
	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">

	<div class="tbl_frm02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">Question type</th>
			<td>
				<select name="catename" required itemname="Question type">
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
			<th scope="row">Title</th>
			<td><input type="text" name="subject" value="<?php echo $qa['subject']; ?>" required itemname="Title" class="frm_input wfull required"></td>
		</tr>
		<tr>
			<th scope="row">content</th>
			<td><textarea name="memo" required itemname="content" class="frm_textbox wfull required"><?php echo $qa['memo']; ?></textarea></td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td class="td_label">
				<input type="text" name="email" value="<?php echo $qa['email']; ?>" class="frm_input">
				<p class="mart7">
					<span class="marr10">Would you like to text me if I answered your question?</span>
					<label><input type="radio" name="email_send_yes" value="1"<?php echo get_checked($qa['email_send_yes'],'1'); ?>> example</label>
					<label><input type="radio" name="email_send_yes" value="0"<?php echo get_checked($qa['email_send_yes'],'0'); ?>> No</label>
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row">Cell phone</th>
			<td class="td_label">
				<input type="text" name="cellphone" value="<?php echo $qa['cellphone']; ?>" class="frm_input">
				<p class="mart7">
					<span class="marr10">Would you like to text me if I answered your question?</span>
					<label><input type="radio" name="sms_send_yes" value="1"<?php echo get_checked($qa['sms_send_yes'],'1'); ?>> example</label>
					<label><input type="radio" name="sms_send_yes" value="0"<?php echo get_checked($qa['sms_send_yes'],'0'); ?>> No</label>
				</p>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="crystal" class="btn_lsmall">
		<a href="javascript:history.go(-1);" class="btn_lsmall bx-white">Cancellation</a>
	</div>
	</form>
</div>

<script>
function fqaform_submit(f) {
	if(confirm("Would you like to modify it?") == false)
		return false;

	return true;
}
</script>