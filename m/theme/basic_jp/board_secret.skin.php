<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<form name="frm" action="<?php echo TB_MBBS_URL; ?>/board_read.php" method="get" onsubmit="return frm_submit(this);">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="boardid" value="<?php echo $boardid;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="m_bo_pop">
	<h2>パスワード確認</h2>
	<p>この文章は秘密です。<br>文を作成するとき,入力した暗証番号を入力してください。</p>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="password" name="inpasswd" placeholder="パスワード">
	</div>
	<p class="btn_area" style="margin-top:8px;">
		<input type='submit' value=/'確認' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">後ろへ行く</a>
	</p>
</div>
</form>

<script>
function frm_submit(f)
{
	if(!f.inpasswd.value)
	{
		alert(/'暗証番号を入力してください。');
		f.inpasswd.focus();
		return false;
	}

	if(answer==true)
	{	return true;  }
	else
	{	return false; }
}
</script>
