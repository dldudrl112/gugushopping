<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages

include_once("./_head.php");
?>

<form name="flogin" action="<?php echo TB_HTTPS_MBBS_URL; ?>/login_check.php" onsubmit="return flogin_submit(this);" method="post">
<div class="mb_login">
	<section class="login_fs">
		<h2 class="log_tit">MEMBER <strong>LOGIN</strong></h2>
		<p class="mart15">
			<label for="login_id" class="sound_only">Member ID</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="ID">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">Password</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="password">
		</p>
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">Automatic login</label>
		</p>
		<p class="mart10"><input type="submit" value="login" class="btn_large wset wfull"></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white wfull">join membership</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">Find ID/Password</a></span></p>
	</section>
</div>
</form>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("If you use automatic login, you do not need to enter your member ID and password from next time..\n\nPersonal information may be leaked in public places, so please refrain from using it.\n\nDo you want to use automatic login?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value){
		alert('Please enter your ID.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value){
		alert('Please enter your PIN number.');
		f.mb_password.focus();
		return false;
	}

	return true;
}
</script>

<?php
include_once("./_tail.php");
?>