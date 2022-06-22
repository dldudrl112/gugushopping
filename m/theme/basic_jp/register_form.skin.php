<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_name">会員名</label></th>
		<td>
			<input type="text" name="name" value="<?php echo $member['name']; ?>" id="reg_mb_name" required itemname="会員名" class="frm_input required"<?php if($w=='u' || $default['de_certify_use']) echo $readonly; ?>>			
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_id">ユーザ名</label></th>
		<td>
			<input type="text" name="id" value="<?php echo $member['id']; ?>" id="reg_mb_id" required memberid itemname="ユーザ名" class="frm_input required" onkeyup="reg_mb_id_ajax();" minlength="3" maxlength="20"<?php if($w=='u') echo $readonly; ?>>
			<span class="frm_info" id="msg_mb_id">少なくとも3者以上の英文字、数字、_だけ入力</span>
		</td>
	</tr>
	<?php if($w=='') { ?>
	<tr>
		<th scope="row"><label for="reg_mb_password">パスワード</label></th>
		<td>
			<input type="password" name="passwd" id="reg_mb_password" required itemname="パスワード" class="frm_input required" minlength="4" maxlength="20">
			<span class="frm_info">4者以上の英文及び数字</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">パスワードの確認</label></th>
		<td><input type="password" name="repasswd" id="reg_mb_password_re" required itemname="パスワードの確認" class="frm_input required" minlength="4" maxlength="20"></td>
	</tr>
	<?php } else if($w=='u') { ?>
	<tr>
		<th scope="row"><label for="reg_mb_password_db">現在のパスワード</label></th>
		<td><input type="password" name="dbpasswd" id="reg_mb_password_db" required itemname="現在のパスワード" class="frm_input required" minlength="4" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password">新しいパスワード</label></th>
		<td>
			<input type="password" name="passwd" id="reg_mb_password" class="frm_input" minlength="4" maxlength="20">
			<span class="frm_info">4文字以上の英数字</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">新しいパスワードの確認</label></th>
		<td><input type="password" name="repasswd" id="reg_mb_password_re" class="frm_input" minlength="4" maxlength="20"></td>
	</tr>
	<?php } ?>	
	<tr>
		<th scope="row">生年月日</th>
		<td>	
			<label for="reg_mb_birth_year" class="sound_only"></label>
			<input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" id="reg_mb_birth_year" required itemname="生年月日" class="frm_input required" maxlength="4" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>年
			
			<label for="reg_mb_birth_month" class="sound_only"></label>
			<input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" id="reg_mb_birth_month" required itemname="生年月日" class="frm_input required" maxlength="2" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>月
			
			<label for="reg_mb_birth_day" class="sound_only"></label>
			<input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" id="reg_mb_birth_day" required itemname="生年月日" class="frm_input required" maxlength="2" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>日
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_birth_type">揚力/旧暦</label></th>
		<td>
			<select name="birth_type" id="reg_mb_birth_type">
				<?php echo option_selected('S', $member['birth_type'], /'揚力'); ?>
				<?php echo option_selected('L', $member['birth_type'], /'旧暦'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_gender">性別</label></th>
		<td>
			<select name="gender" id="reg_mb_gender">
				<?php echo option_selected('',  $member['gender'], /'性別'); ?>
				<?php echo option_selected('M', $member['gender'], /'男'); ?>
				<?php echo option_selected('F', $member['gender'], /'女'); ?>
			</select>
		</td>
	</tr>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row"><label for="reg_telephone">電話番号</label></th>
		<td>
			<input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" id="reg_telephone"<?php echo $config['register_req_tel']?' required':''; ?> itemname="電話番号" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>">
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp']) { ?>
	<tr>
		<th scope="row"><label for="reg_cellphone">携帯電話</label></th>
		<td>
			<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" id="reg_cellphone"<?php echo $config['register_req_hp']?' required':''; ?> itemname="携帯電話" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<div class="padt5">
				<input type="checkbox" name="smsser" value="Y" id="smsser_yes"<?php echo get_checked('Y', $member['smsser']); ?> class="css-checkbox lrg"><label for="smsser_yes" class="css-label">SMS を受信します。</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_email">E-mail</label></th>
		<td>
			<input type="email" name="email" value="<?php echo $member['email']; ?>" id="reg_mb_email"<?php echo $config['register_req_email']?' required':''; ?> email itemname="E-mail" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>">
			<div class="padt5">
				<input type="checkbox" name="mailser" value="Y" id="mailser_yes"<?php echo get_checked('Y', $member['mailser']); ?> class="css-checkbox lrg"><label for="mailser_yes" class="css-label">E-mailを受信します。</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">住所</th>
		<td>
			<label for="reg_mb_zip" class="sound_only">郵便番号</label>
			<input type="text" name="zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="郵便番号" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="5" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">住所検索</button><br>

			<label for="reg_mb_addr1" class="sound_only">住所</label>
			<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="住所" class="frm_input frm_address<?php echo $config['register_req_addr']?' required':''; ?>" readonly><br>
			
			<label for="reg_mb_addr2" class="sound_only">詳細住所</label>
			<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" id="reg_mb_addr2" class="frm_input frm_address"><br>
			
			<label for="reg_mb_addr3" class="sound_only">参考項目</label>
			<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" id="reg_mb_addr3" class="frm_input frm_address" readonly>
			<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="<?php echo $w==''?/'会員加入':/'情報修正'; ?>" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">キャンセル</a>
</div>
</form>

<script>
function fregisterform_submit(f)
{
	var str;
	<?php if($w=='') { ?>
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'は使用することができないIDです.");
		f.id.focus();
		return false;
	}
	<?php } ?>

    // 会員ID検査
	if(f.id.value.length < 3) {
		alert(/'ハンドルネームを3文字以上入力してください。');
		f.id.focus();
		return false;
	}

	<?php if($w=='') { ?>
    // パスワード検査
	if(f.passwd.value.length < 4) {
		alert(/'パスワードを4文字以上入力してください.');
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert(/'パスワードが同じではありません。');
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert(/'パスワードを4文字以上入力してください。');
            f.repasswd.focus();
            return false;
        }
    }

	str = "会員加入";
	<?php } else if($w=='u') { ?>
	if(f.passwd.value) {
		// パスワード検査
		if(f.passwd.value.length < 4) {
			alert(/'パスワードを4文字以上入力してください。');
			f.passwd.focus();
			return false;
		}

		if(f.passwd.value != f.repasswd.value) {
			alert(/'パスワードが同じでありません。');
			f.repasswd.focus();
			return false;
		}

		if(f.passwd.value.length > 0) {
			if(f.repasswd.value.length < 4) {
				alert(/'パスワードを4文字以上入力してください。');
				f.repasswd.focus();
				return false;
			}
		}
	}

	str = "情報修正";
	<?php } ?>

	<?php if($config['register_use_email']) { ?>
	// 使用できないE-mailドメイン
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'は使用することができないメールです。");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm(str+" よろしいですか？") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// 会員ID検査
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

// 禁止メールドメイン検査
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // メールのドメインだけが手に入る

    for(i=0; i<s.length; i++) {
        if(s[i] == domain)
            return domain;
    }
    return "";
}

function reg_mb_id_ajax() {
	var mb_id = $.trim($("#reg_mb_id").val());
	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
</script>
