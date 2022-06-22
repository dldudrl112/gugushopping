<?php
if(!defined("_TUBEWEB_")) exit; // 個々のページアクセス不可
?>

<div class="mb_login">
	<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
	<input type="hidden" name="url" value="<?php echo $login_url; ?>">
	<section class="login_fs">
		<p class="mart15">
			<label for="login_id" class="sound_only">会員IDを</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="아이디">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">パスワード</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="パスワード">		
		</p>	
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">自動ログイン</label>
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">ログイン</button></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium wfull bx-white">会員加入</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">ID/パスワード探し</a></span></p>
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
		<h3>非会員購買</h3>
		<p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">非会員として購買すること</a></p>
	</section>
	<?php } else if(preg_match("/orderinquiry.php$/", $url)) { ?>
	<form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
	<section class="mb_login_od">
		<h3>非会員注文照会</h3>
		<p class="mart15">
			<label for="od_id" class="sound_only">注文番号</label>
            <input type="text" name="od_id" id="od_id" placeholder="注文番号">			
		</p>
		<p class="mart3">
			<label for="od_pwd" class="sound_only">パスワード</label>
            <input type="password" name="od_pwd" id="od_pwd" placeholder="パスワード">		
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">確認</button></p>
	</section>
	</form>
	<?php } ?>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("自動ログインを使用すると,次から会員IDとパスワードを入力する必要はありません。\n\n公共の場所では個人情報が流出することがありますので,ご使用はご遠慮ください。\n\n自動ログインをお使いになりますか?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert(/'IDを入力してください。');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert(/'暗証番号を入力してください。');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert(/'注文番号を入力してください。');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert(/'暗証番号をご入力ください。');
		f.od_pwd.focus();
		return false;
	}

    return true;
}
</script>
