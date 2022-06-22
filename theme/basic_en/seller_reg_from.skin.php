<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TB_IMG_URL; ?>/seller_reg_from.gif"></div>

<form name="fsellerform" id="fsellerform" action="<?php echo $from_action_url; ?>" onsubmit="return fsellerform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fsellerform_term">
	<h2>Terms and Conditions</h2>
	<textarea readonly><?php echo $config['seller_reg_agree']; ?></textarea>
	<fieldset class="fsellerform_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">I have read the above and agree to the terms and conditions.</label>
	</fieldset>
</div>

<h3 class="anc_tit">Enter business information</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_seller_item">Provided goods</label></th>
		<td><input type="text" name="seller_item" id="reg_seller_item" required itemname="Provided goods" class="required frm_input" size="30" placeholder="etc) home appliances"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_name">Company,(corporation)name</label></th>
		<td><input type="text" name="company_name" id="reg_company_name" required itemname="Company(corporation)name" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_owner">Representative name</label></th>
		<td><input type="text" name="company_owner" id="reg_company_owner" required itemname="Representative name" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_saupja_no">Business license number</label></th>
		<td><input type="text" name="company_saupja_no" id="reg_company_saupja_no" required itemname="Business license number" class="required frm_input" size="30" placeholder="etc) 206-23-12552"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_item">karma</label></th>
		<td><input type="text" name="company_item" id="reg_company_item" required itemname="karma" class="required frm_input" size="30" placeholder="etc) Service industry"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_service">event</label></th>
		<td><input type="text" name="company_service" id="reg_company_service" required itemname="event" class="required frm_input" size="30" placeholder="etc) e-commerce business"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_tel">Phone number</label></th>
		<td><input type="text" name="company_tel" id="reg_company_tel" class="frm_input" size="30" placeholder="etc) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_fax">Fax number</label></th>
		<td><input type="text" name="company_fax" id="reg_company_fax" class="frm_input" size="30" placeholder="etc) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row">Business address</th>
		<td>
			<label for="reg_company_zip" class="sound_only">Postal code</label>
			<input type="text" name="company_zip" id="reg_company_zip" required itemname="Postal code" class="required frm_input" size="8" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fsellerform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');">Address search</button><br>

			<label for="reg_company_addr1" class="sound_only">Address</label>
			<input type="text" name="company_addr1" id="reg_company_addr1" required itemname="Base address" class="required frm_input frm_address" size="60" readonly> Base address<br>

			<label for="reg_company_addr2" class="sound_only">Detail address</label>
			<input type="text" name="company_addr2" id="reg_company_addr2" class="frm_input frm_address" size="60"> Detail address<br>

			<label for="reg_company_addr3" class="sound_only">Reference item</label>
			<input type="text" name="company_addr3" id="reg_company_addr3" class="frm_input frm_address" size="60" readonly>Reference item
			<input type="hidden" name="company_addr_jibeon" value="">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_company_hompage">Homepage</label></th>
		<td><input type="text" name="company_hompage" id="reg_company_hompage" class="frm_input" size="30" placeholder="etc) http://homepage.com"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_memo">Forwarding items</label></th>
		<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox wfull h60"></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">Deposit account information input</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_bank_name">Bank name</label></th>
		<td><input type="text" name="bank_name" id="reg_bank_name" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_account">bank account numbe</label></th>
		<td><input type="text" name="bank_account" id="reg_bank_account" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_bank_holder">Deposit owner name</label></th>
		<td><input type="text" name="bank_holder" id="reg_bank_holder" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="anc_tit mart30">Deposit user name</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_info_name">Name of person in charge</label></th>
		<td><input type="text" name="info_name" id="reg_info_name" required itemname="Name of person in charge" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_tel">Name of person in Charge</label></th>
		<td><input type="text" name="info_tel" id="reg_info_tel" required itemname="person in charge cell phone" class="required frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_info_email">Contact Email</label></th>
		<td><input type="text" name="info_email" id="reg_info_email" required email itemname="Contact Email" class="required frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="Apply" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">Cancellation</a>
</div>
</form>

<script>
function fsellerform_submit(f) {
	if(!f.agree.checked) {
		alert("You can apply only if you agree to the terms and conditions.");
		f.agree.focus();
		return false;
	}

	if(confirm("Make sure that the item is correct.\n\nWould you like to apply?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}
</script>
