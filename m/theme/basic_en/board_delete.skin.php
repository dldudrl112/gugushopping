<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<form name="frm" action="<?php echo TB_MBBS_URL; ?>/board_delete2.php" method="post" onsubmit="return frm_submit(this);">
<input type="hidden" name="w" value="d">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="boardid" value="<?php echo $boardid;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="m_bo_pop">
	<h2>Delete post</h2>
	<p>Once deleted, the data will not be recovered.<br>To delete, press the Delete button.</p>
	<?php if($is_member) { ?>
	<p class="btn_area">
		<input type='submit' value='To delete' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">back</a>
	</p>
	<?php } else { ?>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="password" name="passwd" placeholder='password'>
	</div>
	<p class="btn_area" style="margin-top:8px;">
		<input type='submit' value='Delete' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">back</a>
	</p>
	<?php } ?>
</div>
</form>

<script>
function frm_submit(f)
{
	<?php if(!$is_member) { ?>
	if(!f.passwd.value)
	{
		alert('
Please enter a password.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	if(answer==true)
	{	return true;  }
	else
	{	return false; }
}
</script>
