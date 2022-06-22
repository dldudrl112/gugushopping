<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_PATH.'/head.sub.php');
?>

<form name="flogin" action="<?php echo TB_HTTPS_BBS_URL; ?>/login_check.php" onsubmit="return flogin_submit(this);" method="post">
<div id="intro">
	<div id="int_wrap">
		<div class="lcont">
			<h1><?php echo display_logo(); ?></h1>
			<h2 class="tit">MEMBER <b>LOGIN</b></h2>
			<p class="fs13">请输入ID和密码后按下登录按钮。</p>
			<dl class="int_login">
				<dt><input type="submit" value="登录" class="btn_large wset"></dt>
				<dd>
					<label for="login_id" class="sound_only">会员用户名</label>
					<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="用户名">					
				</dd>
				<dd>
					<label for="login_pw" class="sound_only">密码</label>
					<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="密码">		
				</dd>
			</dl>
			<p class="marb20">
				<input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login" class="fs11">自动登录</label>
			</p>
			<div class="int_btn">
				<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn_lsmall grey">注册会员</a>
				<a href="<?php echo TB_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;" class="btn_lsmall bx-white">查找ID /密码</a>
			</div>
			<ul class="int-txt">
				<li>使用部分浏览器提供的自动登录功能时可能会导致个人信息泄露,敬请注意。</li>
				<li>如果您忘记了身份证/密码，请联系身份证/密码查询器或咨询中心。</li>
				<li>咨询中心。 <?php echo $config['company_tel']; ?>(<?php echo $config['company_hours']; ?>)</li>
			</ul>
		</div>
		<div class="rbanner">
			<?php echo display_banner_rows(100, $pt_id); ?>
			<script>
			$(document).ready(function(){
				$('.rbanner ul').slick({
					autoplay: true,
					dots: true,
					arrows: false
				});
			});
			</script>
		</div>
	</div>
	<div class="int_copy">
		<?php echo $config['company_name']; ?> <span class="g_hl"></span> 代表 : <?php echo $config['company_owner']; ?> <span class="g_hl"></span> <?php echo $config['company_addr']; ?><br>
		Email : <?php echo $super['email']; ?> <span class="g_hl"></span> 营业执照号 : <?php echo $config['company_saupja_no']; ?> <a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall bx-white marl5">营业者信息确认</a> <span class="g_hl"></span> 邮政编码 : <?php echo $config['tongsin_no']; ?><br>
		<p class="mart5 fc_137 fs11">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
	</div>
</div>
</form>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("使用自动登录无需输入会员用户名和密码.\n\n公共场所个人信息可能会泄露,请勿使用.\n\n确定要使用自动登录?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value){
		alert('请输入ID。.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value){
		alert('请输入密码。');
		f.mb_password.focus();
		return false;
	}

	return true;
}
</script>

<?php
include_once(TB_PATH."/tail.sub.php");
?>