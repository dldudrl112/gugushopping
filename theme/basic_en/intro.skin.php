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
			<p class="fs13">Please enter your ID and password and press the login button.</p>
			<dl class="int_login">
				<dt><input type="submit" value="로그인" class="btn_large wset"></dt>
				<dd>
					<label for="login_id" class="sound_only">Member ID</label>
					<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="아이디">					
				</dd>
				<dd>
					<label for="login_pw" class="sound_only">Password</label>
					<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="Password">		
				</dd>
			</dl>
			<p class="marb20">
				<input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login" class="fs11">Automatic login</label>
			</p>
			<div class="int_btn">
				<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn_lsmall grey">join membership</a>
				<a href="<?php echo TB_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;" class="btn_lsmall bx-white">Find ID/Password</a>
			</div>
			<ul class="int-txt">
				<li>Please be aware that using the automatic login feature provided by some browsers may result in personal information leakage.</li>
				<li>If you have lost your ID/password, please find your ID/password or contact the counseling center.</li>
				<li>a drop-in centre <?php echo $config['company_tel']; ?>(<?php echo $config['company_hours']; ?>)</li>
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
		<?php echo $config['company_name']; ?> <span class="g_hl"></span> Representative : <?php echo $config['company_owner']; ?> <span class="g_hl"></span> <?php echo $config['company_addr']; ?><br>
		Email : <?php echo $super['email']; ?> <span class="g_hl"></span> Business license number : <?php echo $config['company_saupja_no']; ?> <a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall bx-white marl5">Business license information check</a> <span class="g_hl"></span> Communication sales number : <?php echo $config['tongsin_no']; ?><br>
		<p class="mart5 fc_137 fs11">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
	</div>
</div>
</form>

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
include_once(TB_PATH."/tail.sub.php");
?>