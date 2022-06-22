<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form  name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<div><img src="<?php echo TB_IMG_URL; ?>/register_1.gif"></div>

<?php if($default['de_certify_use']) { // When using real name authentication ?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
<?php } ?>

<?php if($default['de_sns_login_use']) { ?>
<div class="sns_box mart20">
	<h3>Subscribe to your SNS account</h3>
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
	<h2>Membership Terms and Conditions</h2>
	<textarea readonly><?php echo $config['shop_provision']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">I agree to the terms and conditions of the membership.</label>
	</fieldset>
</section>

<section id="fregister_private">
	<h2>Collection and utilization of personal information</h2>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th>
purpose</th>
			<th>category</th>
			<th>retention period</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>User identification and identification</td>
			<td>ID, name, password</td>
			<td>by the time of membership withdrawal</td>
		</tr>
		<tr>
			<td>Notify customer service use and identify users for CS response</td>
			<td>contact information (Email, mobile phone number)</td>
			<td>by the time of withdrawal of membership</td>
		</tr>
		</tbody>
		</table>
	</div>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21">
		<label for="agree21">I agree with the collection and utilization of personal information.</label>
	</fieldset>
</section>

<?php if($default['de_certify_use']) { // When using real name authentication ?>
<section>
	<div class="agree_txt">
		<i class="fa fa-exclamation-circle"></i> Under the revised Information and Communication Act, resident registration numbers are not collected when a member is registered.
		<span class="bold marl20">
			<input type="radio" value="1" name="chkplus" id="chkplus11">
			<label for="chkplus11" class="marr10">Mobile phone authentication</label>
			<input type="radio" value="0" name="chkplus" id="chkplus10">
			<label for="chkplus10">Ipin authentication</label>
		</span>
	</div>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="Check" class="btn_large wset">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">Cancellation</a>
</div>
</form>

<script>
window.name ="Parent_window";
function fnPopup(val){
	switch(val){
		case 1: //Mobile phone authentication
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // Ipin authentication
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
		alert("You can join only if you agree to the terms of the membership.");
		f.agree.focus();
		return false;
	}

	if(!f.agree2.checked) {
		alert("You can join only if you agree to the terms of the members.");
		f.agree2.focus();
		return false;
	}

	<?php if($default['de_certify_use']) { ?>
    var chkplus = document.getElementsByName("chkplus");
    if(!chkplus[0].checked && !chkplus[1].checked) {
        alert("You can sign up for membership after authentication of mobile phone and IPin.");
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
