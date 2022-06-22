<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<!--[if IE]>
<script src="<?php echo TB_JS_URL; ?>/excanvas.js"></script>
<![endif]-->
<script src="<?php echo TB_JS_URL; ?>/jquery.signature.js"></script>
<script src="<?php echo TB_JS_URL; ?>/jquery.partner_form.js"></script>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>Customer Service<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fpartnerform" id="fpartnerform" action="<?php echo $from_action_url; ?>" onsubmit="return fpartnerform_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

	<div class="fpartnerform_term">
		<h2><?php echo $config['pf_stipulation_subj']; ?></h2>
		<textarea readonly><?php echo $config['pf_stipulation']; ?></textarea>
		<fieldset class="fpartnerform_agree">
			<input type="checkbox" name="agree1" value="1" id="agree11">
			<label for="agree11">I have read the above and agree to the terms and conditions of use.</label>
		</fieldset>
		<?php if($config['pf_regulation_subj']) { ?>
		<h2 class="mart20"><?php echo $config['pf_regulation_subj']; ?></h2>
		<textarea readonly><?php echo $config['pf_regulation']; ?></textarea>
		<fieldset class="fpartnerform_agree">
			<input type="checkbox" name="agree2" value="1" id="agree21">
			<label for="agree21">I have read the above and agree to the regulations.</label>
		</fieldset>
		<?php } ?>
	</div>

	<div class="fp_sign">
		<dl class="info_bx fl">
			<dt>User “Licensor”</dt>
			<dd>Mutual : <?php echo $config['company_name']; ?></dd>
			<dd>Representative number : <?php echo $config['company_tel']; ?></dd>
			<dd>Business license number : <?php echo $config['company_saupja_no']; ?></dd>
			<dd>Address : <?php echo $config['company_addr']; ?></dd>
			<dd class="mart25">Representative : <?php echo $config['company_owner']; ?> (signature)
			<?php
			$file = TB_DATA_PATH.'/common/'.$config['admin_seal'];
			if(is_file($file) && $config['admin_seal']) {
				$seal = rpc($file, TB_PATH, TB_URL);
				echo '<span class="admin_seal"><img src="'.$seal.'"></span>';
			}
			?>
			</dd>
		</dl>
		<dl class="info_bx fr">
			<dt>The person in charge “Licensee ”</dt>
			<dd>a full name : <strong><?php echo get_text($member['name']); ?></strong></dd>
			<dd>contact information : <?php echo replace_tel($member['cellphone']); ?></dd>
			<dd>E-mail : <?php echo get_text($member['email']); ?></dd>
			<dd>
				<div id="signature-pad" class="m-signature-pad">
					<p class="marb3"><strong class="blink">↓↓ Sign at the bottom(wrapped with a mouse)Please do it for me.</strong><button type="button" id="clear" class="btn_ssmall bx-white">Initialize Signature</button></p>
					<div id="sign"></div>
					<textarea name="signatureJSON" id="signatureJSON" class="dn" readonly=""></textarea>
				</div>
			</dd>
		</dl>
	</div>

	<section class="mart30">
		<h3 class="anc_tit">an account to be deposited</h3>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row"><label for="bank_name">Bank name</label></th>
				<td><input type="text" name="bank_name" id="bank_name" class="frm_input" size="20"></td>
			</tr>
			<tr>
				<th scope="row"><label for="bank_account">bank account number</label></th>
				<td><input type="text" name="bank_account" id="bank_account" class="frm_input" size="30"></td>
			</tr>
			<tr>
				<th scope="row"><label for="bank_holder">Deposit owner name</label></th>
				<td><input type="text" name="bank_holder" id="bank_holder" class="frm_input" size="20"></td>
			</tr>
			</tbody>
			</table>
		</div>
		<p class="padt5 fc_red">※Please enter the exact account information that you want to.(You can enter it on my page after that.)</p>
	</section>

	<section class="mart30">
		<h3 class="anc_tit">Payment information</h3>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
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
					<?php echo get_bank_account("bank_acc"); ?>
					<div class="padt10">
						<input type="text" name="reg_hp" value="<?php echo replace_tel($member['cellphone']); ?>" id="reg_hp" class="frm_input" size="20">
						<button type="button" class="btn_small btn_sms_send">Receive text message from the above deposit account</button>
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="deposit_name">Deposit holder name</label></th>
				<td><input type="text" name="deposit_name" id="deposit_name" class="frm_input" size="20" placeholder="Actual depositor name"></td>
			</tr>
			<tr>
				<th scope="row"><label for="reg_memo">Your message.</label></th>
				<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox wfull h60"></textarea></td>
			</tr>
			<?php } ?>
			</tbody>
			</table>
		</div>
	</section>

	<?php if($multi_settle) { ?>
	<div class="btn_confirm">
		<input type="submit" value="Apply" id="btn_submit" class="btn_large wset" accesskey="s">
		<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">Cancellation</a>
	</div>
	<?php } ?>
	</form>
</div>

<script>
function fpartnerform_submit(f) {
	errmsg = "";
	errfld = "";

	check_field(f.bank_name, "Please enter the name of the bank to be deposited.");
	check_field(f.bank_account, "Please enter the account number to be deposited.");
	check_field(f.bank_holder, "Please enter the account number to be deposited.");
	check_field(f.bank_acc, "Select the deposit account to deposit.");
	check_field(f.deposit_name, "Please enter the name of the deposit you want to deposit.");

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

	if($("#sign").signature("isEmpty")) {
		alert("signature(signature)You can apply after you do.");
		$("#clear").focus();
		return false;
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
