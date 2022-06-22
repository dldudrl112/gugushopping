<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">家 <i class="ionicons ion-ios-arrow-right"></i> 登录</p>
<h2 class="stit">LOGIN</h2>
<ul class="login_tab">
	<li data-tab="login_fld"><span>会员登录</span></li>
	<li data-tab="guest_fld"><span>非会员订单查询</span></li>
</ul>

<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
<input type="hidden" name="url" value="<?php echo $login_url; ?>">

<div class="login_wrap" id="login_fld">		
	<dl class="log_inner">
		<dt>会员登录</dt>
		<dd class="stxt">登录可获得多种服务和优惠。</dd>
		<dd>
			<label for="login_id" class="sound_only">会员用户名</label>
			<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="用户名">	
		</dd>
		<dd>
			<label for="login_pw" class="sound_only">密码</label>
			<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="密码">		
		</dd>
		<dd><button type="submit" class="btn_large">登录</button></dd>
		<?php if(preg_match("/orderform.php/", $url)) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderform.php" class="btn_large red wfull">非会员购买</a></dd>
		<?php } ?>
		<dd class="log_op">
			<span><input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login">自动登录</label></span>	
			<span class="fr"><a href="<?php echo TB_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;">查找ID /密码</a></span>
		</dd>
	</dl>
	<?php if($default['de_sns_login_use']) { ?>
	<div class="sns_btn">
		<h3>SNS 账户登录</h3>
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
		<dt>非会员订单查询</dt>
		<dd class="stxt">
			结算结束后请输入介绍的订单编号和订购结算时填写的密码。
		</dd>
		<dd>
			<label for="od_id" class="sound_only">订单号码</label>
            <input type="text" name="od_id" id="od_id" class="frm_input" placeholder="订单号码">		
		</dd>
		<dd>
			<label for="od_pwd" class="sound_only">密码</label>
            <input type="password" name="od_pwd" id="od_pwd" class="frm_input" placeholder="密码">		
		</dd>
		<dd><button type="submit" class="btn_large">确认</button></dd>
	</dl>	
</div>
</form>

<div class="log_bt_box">
	请注册会员,享受丰厚的福利。
	<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn_lsmall bx-white marl15">注册会员</a>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("使用自动登录后无需输入会员账号和密码。\n\n公共场所个人信息可能会泄露,请勿使用。\n\n确定要使用自动登录?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert('请输入ID。');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert('请输入密码。');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert('请输入订单编号。');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert('请输入密码。');
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
