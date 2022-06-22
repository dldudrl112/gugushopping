<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="find_info" class="new_win">
	<h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<form name="fpasswordlost" action="<?php echo $form_action_url; ?>" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<fieldset id="info_fs">
		<p>
			Please enter the registered e-mail address when you sign up.<br>
			We will send you the ID and password information in this email.
		</p>
		<div class="info_form">
			<label for="mb_email">E-mail Address<strong class="sound_only">Essential</strong></label>
			<input type="text" name="mb_email" id="mb_email" required email itemname="E-mail Address" class="required frm_input" size="30">
		</div>
	</fieldset>

	<div class="win_btn">
		<input type="submit" class="btn_lsmall" value="Check">
		<button type="button" class="btn_lsmall bx-white" onclick="window.close();">Close Window</button>
	</div>	
	</form>
</div>
