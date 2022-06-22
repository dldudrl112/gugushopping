<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<div class="mb_login">
	<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
	<input type="hidden" name="url" value="<?php echo $login_url; ?>">
	<section class="login_fs">
		<p class="mart15">
			<label for="login_id" class="sound_only">Member ID</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="ID">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">Password</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="Password">		
		</p>	
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">Automatic login</label>
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">login</button></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium wfull bx-white">join membership</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">Find ID/Password</a></span></p>
	</section>
	<?php if($default['de_sns_login_use']) { ?>
	<p class="sns_btn">
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</p>
	<?php } ?>
	</form>

	<?php if(preg_match("/orderform.php/", $url)) { ?>
	<section class="mb_login_od">
		<h3>non-member purchase</h3>
		<p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">Purchase by non-member</a></p>
	</section>
	<?php } else if(preg_match("/orderinquiry.php$/", $url)) { ?>
	<form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
	<section class="mb_login_od">
		<h3>Non-member order check</h3>
		<p class="mart15">
			<label for="od_id" class="sound_only">Order number</label>
            <input type="text" name="od_id" id="od_id" placeholder="Order number">			
		</p>
		<p class="mart3">
			<label for="od_pwd" class="sound_only">password</label>
            <input type="password" name="od_pwd" id="od_pwd" placeholder="password">		
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">chek</button></p>
	</section>
	</form>
	<?php } ?>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("If you use automatic login, you do not need to enter your member ID and password from next time..\n\nPersonal information may be leaked in public, so please refrain from using it.\n\nDo you want to use automatic login?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert('Please enter your ID.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert('Please enter your PIN number.');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert('Please enter your order number.');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert('Please enter your PIN number.');
		f.od_pwd.focus();
		return false;
	}

    return true;
}
</script>
