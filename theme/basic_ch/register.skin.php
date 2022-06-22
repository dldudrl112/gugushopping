<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form  name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<div><img src="<?php echo TB_IMG_URL; ?>/register_1.gif"></div>

<?php if($default['de_certify_use']) { // 使用实名认证时 ?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
<?php } ?>

<?php if($default['de_sns_login_use']) { ?>
<div class="sns_box mart20">
	<h3>SNS 注册一个帐户</h3>
	<p>
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
</div>
<?php } ?>

<section id="fregister_term">
	<h2>会员加入条款</h2>
	<textarea readonly><?php echo $config['shop_provision']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">同意会员加入条款内容.</label>
	</fieldset>
</section>

<section id="fregister_private">
	<h2>个人信息收集及使用</h2>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th>目的</th>
			<th>项目</th>
			<th>保有期</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>用户识别及本人身份确认</td>
			<td>用户名、密码、姓名</td>
			<td>直到会员退出为止</td>
		</tr>
		<tr>
			<td>关于使用客户服务的通知,为CS对应的用户识别</td>
			<td>联系方式(电子邮件,手机号码)</td>
			<td>直到会员退出为止</td>
		</tr>
		</tbody>
		</table>
	</div>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21">
		<label for="agree21">同意个人信息收集及使用内容.</label>
	</fieldset>
</section>

<?php if($default['de_certify_use']) { // 使用实名认证时 ?>
<section>
	<div class="agree_txt">
		<i class="fa fa-exclamation-circle"></i> 根据修改的信息通信法第23条,注册会员时不收集身份证号码.
		<span class="bold marl20">
			<input type="radio" value="1" name="chkplus" id="chkplus11">
			<label for="chkplus11" class="marr10">手机认证</label>
			<input type="radio" value="0" name="chkplus" id="chkplus10">
			<label for="chkplus10">i-pin认证</label>
		</span>
	</div>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="确认" class="btn_large wset">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">取消</a>
</div>
</form>

<script>
window.name ="Parent_window";
function fnPopup(val){
	switch(val){
		case 1: //手机认证
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // 机认证
			window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.target = "popupIPIN2";
			document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
			document.fregister.submit();
			break;
	}
}

function fregister_submit(f)
{
	if(!f.agree.checked) {
		alert("只有同意会员加入条款内容才能加入会员");
		f.agree.focus();
		return false;
	}

	if(!f.agree2.checked) {
		alert("只有同意个人信息收集及使用内容才能注册会员.");
		f.agree2.focus();
		return false;
	}

	<?php if($default['de_certify_use']) { ?>
    var chkplus = document.getElementsByName("chkplus");
    if(!chkplus[0].checked && !chkplus[1].checked) {
        alert("手机认证与i-pin认证后可注册会员。");
        return false;
    }
	if(chkplus[0].checked) {
        fnPopup(1);
		return false;
    }
	if(chkplus[1].checked) {
        fnPopup(0);
		return false;
    }
	<?php } else { ?>
	return true;
	<?php } ?>
}
</script>
