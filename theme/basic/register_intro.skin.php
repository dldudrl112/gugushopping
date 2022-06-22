<?php
if(!defined('_TUBEWEB_')) exit;
?>
<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
<input type="hidden" name="url" value="<?php echo $login_url; ?>">
<div id="membership" class="pb_12">
	<div class="box">
	  <!-- Text_guide Area Start -->
	  <div class="sub_title04">
		<div class="container1">
		  <h2>
			아직 <span>로그인</span>을 하지 않으셨습니다.<br>
			로그인 후 이용 부탁드립니다.
		  </h2>
		</div>
	  </div><!-- Text_guide Area End -->

	  <!-- Membership_box Area Start -->
	  <div class="membership_box">
		<div class="box_top">
		  <a href="<?php echo TB_BBS_URL; ?>/login.php">로그인</a>
		  <a href="<?php echo TB_BBS_URL; ?>/register_intro.php" class="on">회원가입</a> 
		</div>
		<div class="box_bottom">
		  <a href="<?php echo TB_BBS_URL; ?>/register.php">이메일로 회원가입</a>
		</div>
	  </div><!-- Membership_box Area End -->

	  <!-- SNS_login Area Start -->
	  <div class="sns_login">
		<h3>간편 로그인</h3>
		<ul>
			<?php if($default['de_kakao_rest_apikey']) { ?>
				<li><?php echo get_login_oauth('kakao', 1); ?></li>			
			<?php } ?>
			<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
				<li><?php echo get_login_oauth('naver', 1); ?></li>			
			<?php } ?>
			<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
				<li><?php echo get_login_oauth('facebook', 1); ?></li>			
			<?php } ?>
				<li><?php echo get_login_oauth('google', 1); ?></li>
		</ul>
	  </div><!-- SNS_login Area Start -->
	  <div class="sns_caution">
		<p>
		  SNS 계정 가입 시 포춘 서비스의 <span>이용약관/개인정보취급방침</span> 등에<br>동의하는 것으로 간주 합니다. 
		</p>
	  </div>
	</div>

	<div class="banner">
	  <img src="<?php echo TB_IMG_URL; ?>/mem_banner.png" alt="banner">
	</div>
</div>
</form>
<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert('아이디를 입력하세요.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert('비밀번호를 입력하세요.');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert('주문번호를 입력하세요.');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert('비밀번호를 입력해주세요.');
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
