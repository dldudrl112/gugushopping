<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div><img src="<?php echo TB_IMG_URL; ?>/register_2.gif"></div>

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
		<td><input type="text" name="name" value="<?php echo $cert_name; ?>" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="Name of member" class="frm_input required" size="20"></td>
	</tr>
	<tr>
		<th scope="row">ID</th>
		<td>
			<input type="text" name="id" id="mb_id" required memberid itemname="ID" class="frm_input required" onkeyup="reg_mb_id_ajax();" size="20" minlength="3" maxlength="20">
			<span id="msg_mb_id" class="marl5"></span>
			<span class="frm_info">※ Only alphanumeric characters, numbers, and _ can be entered. Please enter a minimum of 3 characters.</span>
		</td>
	</tr>
	<tr>
		<th scope="row">Password</th>
		<td>
			<input type="password" name="passwd" required itemname="Password" class="frm_input required" size="20" minlength="4" maxlength="20">
			<span class="frm_info">※ 4+ characters in English and Numeri</span>
		</td>
	</tr>
	<tr>
		<th scope="row">Password verification</th>
		<td><input type="password" name="repasswd" required itemname="Password verification" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
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
		<th scope="row">the day of one’s birth</th>
		<td>
			<div class="ini_wrap">
			<table>
			<tr>
				<td><input type="text" name="birth_year" value="<?php echo $cert_year; ?>" required itemname="the date of one’s birth" <?php if($default['de_certify_use']){echo $readonly;}?> class="frm_input required" size="8" maxlength="4"> year</td>
				<td class="padl5"><input type="text" name="birth_month" value="<?php echo $cert_month; ?>" required itemname="the date of one’s birth" <?php if($default['de_certify_use']){echo $readonly;}?> class="frm_input required" size="4" maxlength="2"> month</td>
				<td class="padl5"><input type="text" name="birth_day" value="<?php echo $cert_day; ?>" required itemname="the date of one’s birth" <?php if($default['de_certify_use']){echo $readonly;}?> class="frm_input required" size="4" maxlength="2"> day</td>
				<td class="padl5">
					<select name="gender">
						<option value="">Gender</option>
						<option value="M"<?php if($cert['j_sex']=='1'){echo " selected";}?>>Man</option>
						<option value="F"<?php if($cert['j_sex']=='0'){echo " selected";}?>>Woman</option>
					</select>
				</td>
				<td class="padl5">
					<select name="birth_type">
						<option value="S">the solar calendar</option>
						<option value="L">Lunar calendar</option>
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
		<td><input type="text" name="telephone" size="20"<?php echo $config['register_req_tel']?' required':''; ?> itemname="Phone number" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>"></td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp']) { ?>
	<tr>
		<th scope="row">Phone number</th>
		<td>
			<input type="text" name="cellphone" value="<?php echo $cert['cell']; ?>" <?php if($default['de_certify_use'] && $cert['cell']){echo $readonly;}?> size="20"<?php echo $config['register_req_hp']?' required':''; ?> itemname="Cell phone" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<input type="checkbox" checked value="Y" name="smsser" class="marl7"> SMS reception.
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row">E-mail</th>
		<td>
			<input type="text" name="email"<?php echo $config['register_req_email']?' required':''; ?>
			email itemname="E-mail" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
			<input type="checkbox" checked value="Y" name="mailser" class="marl7"> Receive E-Mail.
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">Address</th>
		<td>
			<div>
				<input type="text" name="zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="Address" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
				<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">Address search</a>
			</div>
			<div class="mart5">
				<input type="text" name="addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="Address" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> Base address
			</div>
			<div class="mart5">
				<input type="text" name="addr2" class="frm_input" size="60"> Detail address
			</div>
			<div class="mart5">
				<input type="text" name="addr3" class="frm_input" size="60" readonly> Reference item
				<input type="hidden" name="addr_jibeon" value="">
			</div>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="join membership" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">Cancellation</a>
</div>
</form>

<script>
function fregisterform_submit(f)
{
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"' This ID cannot be used.");
		f.id.focus();
		return false;
	}

    // Member ID check
	if(f.id.value.length < 3) {
		alert("Please enter at least 3 characters of ID.");
		f.id.focus();
		return false;
	}

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

	<?php if($config['register_use_email']) { ?>
	// Unavailable E-mail Domains
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'This mail is not available.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm("Would you like to sign up?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// Member ID check
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config['prohibit_id'])); ?>";
    var s = prohibit_mb_id.split(",");

    for(i=0; i<s.length; i++) {
        if(s[i] == mb_id)
            return mb_id;
    }
    return "";
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

function reg_mb_id_ajax() {
	var mb_id = $.trim($("#mb_id").val());
	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
</script>