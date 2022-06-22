<?php
if(!defined('_TUBEWEB_')) exit;
?>

<script src="<?php echo TB_MJS_URL; ?>/jquery.partner_form.js"></script>

<form name="fpartnerform" id="fpartnerform" action="<?php echo $from_action_url; ?>" onsubmit="return fpartnerform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="signatureJSON" value="mobile" id="signatureJSON">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fpartnerform_term">
	<h2><?php echo $config['pf_stipulation_subj']; ?></h2>
	<textarea readonly><?php echo $config['pf_stipulation']; ?></textarea>
	<fieldset class="fpartnerform_agree">
		<input type="checkbox" name="agree1" value="1" id="agree11" class="css-checkbox lrg">
		<label for="agree11" class="css-label">I have read the above and agree to the terms and conditions of use.</label>
	</fieldset>
	<?php if($config['pf_regulation_subj']) { ?>
	<h2 class="mart10"><?php echo $config['pf_regulation_subj']; ?></h2>
	<textarea readonly><?php echo $config['pf_regulation']; ?></textarea>
	<fieldset class="fpartnerform_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21" class="css-checkbox lrg">
		<label for="agree21" class="css-label">I have read the above and agree to the regulations.</label>
	</fieldset>
	<?php } ?>
</div>

<div class="fp_sign">
	<dl class="info_bx">
		<dt>User “Licensor”</dt>
		<dd>Mutual : <?php echo $config['company_name']; ?></dd>
		<dd>Representative number : <?php echo $config['company_tel']; ?></dd>
		<dd>Business license number : <?php echo $config['company_saupja_no']; ?></dd>
		<dd>Address : <?php echo $config['company_addr']; ?></dd>
		<dd class="mart25">Representative : <?php echo $config['company_owner']; ?> (
signature)
			<?php
			$file = TB_DATA_PATH.'/common/'.$config['admin_seal'];
			if(is_file($file) && $config['admin_seal']) {
				$seal = rpc($file, TB_PATH, TB_URL);
				echo '<span class="admin_seal"><img src="'.$seal.'"></span>';
			}
			?>
		</dd>
	</dl>
	<dl class="info_bx" style="height:auto;">
		<dt>Consignor “Licensee ”</dt>
		<dd>name : <strong><?php echo get_text($member['name']); ?></strong></dd>
		<dd>contact information : <?php echo replace_tel($member['cellphone']); ?></dd>
		<dd>E-mail : <?php echo get_text($member['email']); ?></dd>
	</dl>
</div>

<section class="mart30">
	<h3 class="anc_tit">Deposit account</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="bank_name">name of bank</label></th>
			<td><input type="text" name="bank_name" id="bank_name" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_account">
Account Number</label></th>
			<td><input type="text" name="bank_account" id="bank_account" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_holder">Deposit owner name</label></th>
			<td><input type="text" name="bank_holder" id="bank_holder" class="frm_input" size="20"></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="fc_red">Please enter the exact account information that you want to.<br>You can enter it on my page after that.</div>
</section>

<section class="mart30">
	<h3 class="anc_tit">Payment information</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tr>
			<th scope="row">Service selection</th>
			<td class="td_label">
				<?php
				$multi_settle = 0;
				$sql = " select *
						   from shop_member_grade
						  where gb_no between 2 and 6
							and gb_name <> ''
						  order by gb_no desc ";
				$res = sql_query($sql);
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$checked = '';
					if($i==0) $checked = ' checked="checked"';

					$reg = $row['gb_no'].'^'.$row['gb_anew_price'];
					echo '<label><input type="radio" name="reg_level" value="'.$reg.'"'.$checked.'> '.get_text($row['gb_name']).'</label>'.PHP_EOL;

					$multi_settle++;
				}
				if(!$multi_settle)
					echo 'No settings found. I'd appreciate it if you could let the operator know.';
				?>
			</td>
		</tr>
		<?php if($multi_settle) { ?>
		<tr>
			<th scope="row">Payment method</th>
			<td>
				<input type="hidden" name="anew_grade" value="">
				<input type="hidden" name="receipt_price" value="0">
				<input type="radio" name="pay_settle_case" value="1" id="pay_settle_case" checked="checked">
				<label for="pay_settle_case">without bankbook</label>
			</td>
		</tr>
		<tr>
			<th scope="row">Payment amount</th>
			<td><span id="reg_tot_price"></span></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_acc">Deposit account</label></th>
			<td>
				<?php echo mobile_bank_account("bank_acc"); ?>
				<div class="padt10">
					<input type="text" name="reg_hp" value="<?php echo replace_tel($member['cellphone']); ?>" id="reg_hp" class="frm_input" size="20">
				</div>
				<div class="padt5">
					<button type="button" class="btn_small btn_sms_send">Receive text message from the above deposit account</button>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="deposit_name">Deposit holder name</label></th>
			<td><input type="text" name="deposit_name" id="deposit_name" class="frm_input" size="20" placeholder="Actual depositor name"></td>
		</tr>
		<tr>
			<th scope="row"><label for="reg_memo">a message to you</label></th>
			<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox h60"></textarea></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
</section>

<?php if($multi_settle) { ?>
<div class="btn_confirm">
	<input type="submit" value="Apply" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">cancel</a>
</div>
<?php } ?>
</form>

<script>
function fpartnerform_submit(f) {
	errmsg = "";
	errfld = "";

	check_field(f.bank_name, "Please enter the name of the bank to be deposited.");
	check_field(f.bank_account, "Please enter the account number to be deposited");
	check_field(f.bank_holder, "Please enter the name of the deposit order to receive the deposit");
	check_field(f.bank_acc, "Select the deposit account to deposit.");
	check_field(f.deposit_name, "Please enter the name of the deposit you want to deposit");

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(typeof(f.agree1) != "undefined") {
		if(!f.agree1.checked) {
			alert("You can apply only if you agree to the terms and conditions of use.");
			f.agree1.focus();
			return false;
		}
	}

	if(typeof(f.agree2) != "undefined") {
		if(!f.agree2.checked) {
			alert("You can apply only if you agree to the regulations.");
			f.agree2.focus();
			return false;
		}
	}

	if(!confirm("Would you like to apply?")) {
		return false;
	}

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

<?php if($multi_settle) { ?>
calculate_total_price();
<?php } ?>
</script>
