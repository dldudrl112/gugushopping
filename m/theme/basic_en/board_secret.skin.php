<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<form name="frm" action="<?php echo TB_MBBS_URL; ?>/board_read.php" method="get" onsubmit="return frm_submit(this);">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="boardid" value="<?php echo $boardid;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="m_bo_pop">
	<h2>Confirm Password</h2>
	<p>
This is a secret.<br>Please enter the password you entered when creating the post.</p>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="password" name="inpasswd" placeholder="
password">
	</div>
	<p class="btn_area" style="margin-top:8px;">
		<input type='submit' value='
Confirm' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">Back</a>
	</p>
</div>
</form>

<script>
function frm_submit(f)
{
	if(!f.inpasswd.value)
	{
		alert('Please enter a password.');
		f.inpasswd.focus();
		return false;
	}

	if(answer==true)
	{	return true;  }
	else
	{	return false; }
}
</script>
