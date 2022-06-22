<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<form name="fpasswordlost" action="<?php echo $form_action_url; ?>" method="post" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="m_bo_pop">
	<p>
		注册会员时请输入注册的邮箱地址。<br>
		用该邮箱发送用户名和密码信息。
	</p>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="text" name="mb_email" id="mb_email" required email itemname="E-mail 住址" placeholder="E-mail 住址">
	</div>
	<p class="btn_confirm">
		<input type="submit" value="确认" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">取消</a>
	</p>
</div>
</form>
