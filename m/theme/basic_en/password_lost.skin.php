<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<form name="fpasswordlost" action="<?php echo $form_action_url; ?>" method="post" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="m_bo_pop">
	<p>
		Please enter the registered e-mail address when you sign up.<br>
		We will send you an ID and password information to that email.
	</p>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="text" name="mb_email" id="mb_email" required email itemname="E-mail Address" placeholder="E-mail Address">
	</div>
	<p class="btn_confirm">
		<input type="submit" value="check" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">cancel</a>
	</p>
</div>
</form>
