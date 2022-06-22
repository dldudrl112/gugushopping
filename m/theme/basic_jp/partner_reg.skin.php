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
		<label for="agree11" class="css-label">上記の内容を読み,利用約款に同意します。</label>
	</fieldset>
	<?php if($config['pf_regulation_subj']) { ?>
	<h2 class="mart10"><?php echo $config['pf_regulation_subj']; ?></h2>
	<textarea readonly><?php echo $config['pf_regulation']; ?></textarea>
	<fieldset class="fpartnerform_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21" class="css-checkbox lrg">
		<label for="agree21" class="css-label">上記の内容を読み,規定案内に同意します。</label>
	</fieldset>
	<?php } ?>
</div>

<div class="fp_sign">
	<dl class="info_bx">
		<dt>使用者 “甲”</dt>
		<dd>相互 : <?php echo $config['company_name']; ?></dd>
		<dd>代表番号 : <?php echo $config['company_tel']; ?></dd>
		<dd>事業者登録番号 : <?php echo $config['company_saupja_no']; ?></dd>
		<dd>주소 : <?php echo $config['company_addr']; ?></dd>
		<dd class="mart25">代表者 : <?php echo $config['company_owner']; ?> (署名)
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
		<dt>委嘱者 “乙”</dt>
		<dd>姓名 : <strong><?php echo get_text($member['name']); ?></strong></dd>
		<dd>連絡先 : <?php echo replace_tel($member['cellphone']); ?></dd>
		<dd>E-mail : <?php echo get_text($member['email']); ?></dd>
	</dl>
</div>

<section class="mart30">
	<h3 class="anc_tit">ご入金いただく口座</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="bank_name">銀行名</label></th>
			<td><input type="text" name="bank_name" id="bank_name" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_account">口座番号</label></th>
			<td><input type="text" name="bank_account" id="bank_account" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_holder">預金主名</label></th>
			<td><input type="text" name="bank_holder" id="bank_holder" class="frm_input" size="20"></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="fc_red">入金受けることが口座情報を正確に入力してください。<br>その後マイページでも入力可能です。</div>
</section>

<section class="mart30">
	<h3 class="anc_tit">決済情報</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tr>
			<th scope="row">サービス選択</th>
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
					echo /'設定値がありません。 運営者に知らせてくださればありがたいです。';
				?>
			</td>
		</tr>
		<?php if($multi_settle) { ?>
		<tr>
			<th scope="row">決済方法</th>
			<td>
				<input type="hidden" name="anew_grade" value="">
				<input type="hidden" name="receipt_price" value="0">
				<input type="radio" name="pay_settle_case" value="1" id="pay_settle_case" checked="checked">
				<label for="pay_settle_case">無通帳入金</label>
			</td>
		</tr>
		<tr>
			<th scope="row">決済金額</th>
			<td><span id="reg_tot_price"></span></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_acc">入金口座</label></th>
			<td>
				<?php echo mobile_bank_account("bank_acc"); ?>
				<div class="padt10">
					<input type="text" name="reg_hp" value="<?php echo replace_tel($member['cellphone']); ?>" id="reg_hp" class="frm_input" size="20">
				</div>
				<div class="padt5">
					<button type="button" class="btn_small btn_sms_send">上記振込口座文字受信</button>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="deposit_name">入金者名</label></th>
			<td><input type="text" name="deposit_name" id="deposit_name" class="frm_input" size="20" placeholder="実際の入金者名"></td>
		</tr>
		<tr>
			<th scope="row"><label for="reg_memo">お伝言</label></th>
			<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox h60"></textarea></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
</section>

<?php if($multi_settle) { ?>
<div class="btn_confirm">
	<input type="submit" value="申し込み" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">キャンセル</a>
</div>
<?php } ?>
</form>

<script>
function fpartnerform_submit(f) {
	errmsg = "";
	errfld = "";

	check_field(f.bank_name, "ご入金いただく銀行名を入力してください。");
	check_field(f.bank_account, "入金される口座番号を入力してください。");
	check_field(f.bank_holder, "ご入金いただく預金者の名前を入力してください。");
	check_field(f.bank_acc, "入金される入金口座を選んでください。");
	check_field(f.deposit_name, "入金される入金者名を入力してください。");

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(typeof(f.agree1) != "undefined") {
		if(!f.agree1.checked) {
			alert("利用約款に同意しなければ申請できません。.");
			f.agree1.focus();
			return false;
		}
	}

	if(typeof(f.agree2) != "undefined") {
		if(!f.agree2.checked) {
			alert("規定案内に同意しなければ申請できません.");
			f.agree2.focus();
			return false;
		}
	}

	if(!confirm("申請します?")) {
		return false;
	}

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

<?php if($multi_settle) { ?>
calculate_total_price();
<?php } ?>
</script>
