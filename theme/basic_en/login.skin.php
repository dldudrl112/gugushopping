<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">Home <i class="ionicons ion-ios-arrow-right"></i> Login</p>
<h2 class="stit">LOGIN</h2>
<ul class="login_tab">
	<li data-tab="login_fld"><span>Member Login</span></li>
	<li data-tab="guest_fld"><span>Non-member order check</span></li>
</ul>

<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
<input type="hidden" name="url" value="<?php echo $login_url; ?>">

<div class="login_wrap" id="login_fld">		
	<dl class="log_inner">
		<dt>Member Login</dt>
		<dd class="stxt">Sign in and you'll get a wide range of services and benefits.</dd>
		<dd>
			<label for="login_id" class="sound_only">Member ID</label>
			<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="ID">	
		</dd>
		<dd>
			<label for="login_pw" class="sound_only">Password</label>
			<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="Password">		
		</dd>
		<dd><button type="submit" class="btn_large">Login</button></dd>
		<?php if(preg_match("/orderform.php/", $url)) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderform.php" class="btn_large red wfull">To purchase as a non-member</a></dd>
		<?php } ?>
		<dd class="log_op">
			<span><input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login">Automatic login</label></span>	
			<span class="fr"><a href="<?php echo TB_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;">ID/ Find Password</a></span>
		</dd>
	</dl>
	<?php if($default['de_sns_login_use']) { ?>
	<div class="sns_btn">
		<h3>SNS Log in to your account</h3>
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</div>
	<?php } ?>	
</div>
</form>

<form name="forderinquiry" method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" autocomplete="off">
<div class="login_wrap" id="guest_fld">	
	<dl class="log_inner">
		<dt>Non-member order check</dt>
		<dd class="stxt">
			Please enter the order number and password that was provided at the time of order payment.
		</dd>
		<dd>
			<label for="od_id" class="sound_only">Order number</label>
            <input type="text" name="od_id" id="od_id" class="frm_input" placeholder="Order number">		
		</dd>
		<dd>
			<label for="od_pwd" class="sound_only">Password</label>
            <input type="password" name="od_pwd" id="od_pwd" class="frm_input" placeholder="Password">		
		</dd>
		<dd><button type="submit" class="btn_large">Check</button></dd>
	</dl>	
</div>
</form>

<div class="log_bt_box">
	Sign up for membership and enjoy a wealth of benefits.
	<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn_lsmall bx-white marl15">join membership</a>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("If you use automatic login, you do not need to enter your member ID and password from next time.\n\nPersonal information may be leaked in public places, so please refrain from using it.\n\nDo you want to use automatic login?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert('Please enter your PIN number.');
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
		alert('Please enter your password.');
		f.od_pwd.focus();
		return false;
	}

    return true;
}

$(document).ready(function(){
	$(".login_tab>li:eq(0)").addClass('active');
	$("#login_fld").addClass('active');

	$(".login_tab>li").click(function() {
		var activeTab = $(this).attr('data-tab');
		$(".login_tab>li").removeClass('active');
		$(".login_wrap").removeClass('active');
		$(this).addClass('active');
		$("#"+activeTab).addClass('active');
	});
});
</script>
