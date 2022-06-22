<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<form name="fpasswordlost" action="<?php echo $form_action_url; ?>" method="post" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="m_bo_pop">
	<p>
		会員加入の際,登録されたメールアドレスを入力してください。<br>
		該当EメールにてIDとパスワード情報をお送り致します。
	</p>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="text" name="mb_email" id="mb_email" required email itemname="E-mail 住所" placeholder="E-mail 住所">
	</div>
	<p class="btn_confirm">
		<input type="submit" value="확인" class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">キャンセル</a>
	</p>
</div>
</form>
