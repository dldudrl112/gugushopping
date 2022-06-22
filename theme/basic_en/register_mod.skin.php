<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>My Page<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<h3>Enter site usage information</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">Name of member</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" <?php echo $readonly; ?> class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">ID</th>
			<td><input type="text" name="id" value="<?php echo $member['id']; ?>" <?php echo $readonly; ?> class="frm_input" size="20" minlength="3" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">Current password</th>
			<td><input type="password" name="dbpasswd" required itemname="Current password" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">New password</th>
			<td><input type="password" name="passwd" class="frm_input" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">Confirm new password</th>
			<td><input type="password" name="repasswd" class="frm_input" size="20" minlength="4" maxlength="20"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="mart30">Enter personal information</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">the date of one’s birth</th>
			<td>
				<div class="ini_wrap">
				<table>
				<tr>
					<td><input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" required itemname="the date of one’s birth" class="frm_input required" size="8" maxlength="4"> year</td>
					<td class="padl5"><input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" required itemname="the date of one’s birth" class="frm_input required" size="4" maxlength="2"> month</td>
					<td class="padl5"><input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" required itemname="the date of one’s birth" class="frm_input required" size="4" maxlength="2"> day</td>
					<td class="padl5">
						<select name="gender">
						<option value="">Gender</option>
						<option value="M"<?php echo get_selected($member['gender'],"M"); ?>>Man</option>
						<option value="F"<?php echo get_selected($member['gender'],"F"); ?>>WOMan</option>
						</select>
					</td>
					<td class="padl5">
						<select name="birth_type">
						<option value="S"<?php echo get_selected($member['birth_type'],"S"); ?>>the solar calendar</option>
						<option value="L"<?php echo get_selected($member['birth_type'],"L"); ?>>Lunar calendar</option>
						</select>
					</td>
				</tr>
				</table>
				</div>
			</td>
		</tr>
		<?php if($config['register_use_tel']) { ?>
		<tr>
			<th scope="row">Phone number</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>"<?php echo $config['register_req_tel']?' required':''; ?> itemname="Phone number" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20"></td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_hp']) { ?>
		<tr>
			<th scope="row">Cell phone</th>
			<td>
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>"<?php echo $config['register_req_hp']?' required':''; ?> itemname="Cell phone" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20">
				<input type="checkbox" value="Y" name="smsser" class="marl7"<?php echo $member['smsser'] == 'Y'?' checked':''; ?>> Receive SMS.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_email']) { ?>
		<tr>
			<th scope="row">E-mail</th>
			<td>
				<input type="text" name="email" value="<?php echo $member['email']; ?>"<?php echo $config['register_req_email']?' required':''; ?> email itemname="E-mail" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
				<input type="checkbox" value="Y" name="mailser" class="marl7"<?php echo $member['mailser'] == 'Y'?' checked':''; ?>> Receive E-Mail.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_addr']) { ?>
		<tr>
			<th scope="row">Address</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="Postal code" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
					<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">Address search</a>
				</div>
				<div class="mart5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="Address" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> Base address
				</div>
				<div class="mart5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> Detail address
				</div>
				<div class="mart5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60"> Reference item
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="Modifying information" id="btn_submit" class="btn_large wset" accesskey="s">
		<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">Cancellation</a>
	</div>
	</form>
</div>

<script>
function fregisterform_submit(f)
{
	if(f.passwd.value) {
		// password check
		if(f.passwd.value.length < 4) {
			alert("Please enter a password with at least 4 characters.");
			f.passwd.focus();
			return false;
		}

		if(f.passwd.value != f.repasswd.value) {
			alert("The password is not the same.");
			f.repasswd.focus();
			return false;
		}

		if(f.passwd.value.length > 0) {
			if(f.repasswd.value.length < 4) {
				alert("Please enter a password with at least 4 characters.");
				f.repasswd.focus();
				return false;
			}
		}
	}

	<?php if($config['register_use_email']) { ?>
	// Unavailable E-mail Domains
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'This mail cannot be used.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

// Scan Prohibited Mail Domain
function prohibit_email_check(email)
{
	email = email.toLowerCase();

	var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
	var s = prohibit_email.split(",");
	var tmp = email.split("@");
	var domain = tmp[tmp.length - 1]; // I only get the mail domain

	for(i=0; i<s.length; i++) {
		if(s[i] == domain)
			return domain;
	}
	return "";
}
</script>
