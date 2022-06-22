<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
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
		<th scope="row"><label for="reg_mb_name">会员名</label></th>
		<td>
			<input type="text" name="name" value="<?php echo $member['name']; ?>" id="reg_mb_name" required itemname="会员名" class="frm_input required"<?php if($w=='u' || $default['de_certify_use']) echo $readonly; ?>>			
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_id">用户名</label></th>
		<td>
			<input type="text" name="id" value="<?php echo $member['id']; ?>" id="reg_mb_id" required memberid itemname="用户名" class="frm_input required" onkeyup="reg_mb_id_ajax();" minlength="3" maxlength="20"<?php if($w=='u') echo $readonly; ?>>
			<span class="frm_info" id="msg_mb_id">至少输入3个字以上的英文字母,数字,_</span>
		</td>
	</tr>
	<?php if($w=='') { ?>
	<tr>
		<th scope="row"><label for="reg_mb_password">密码</label></th>
		<td>
			<input type="password" name="passwd" id="reg_mb_password" required itemname="密码" class="frm_input required" minlength="4" maxlength="20">
			<span class="frm_info">四字以上的英文和数字</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">密码确认</label></th>
		<td><input type="password" name="repasswd" id="reg_mb_password_re" required itemname="密码确认" class="frm_input required" minlength="4" maxlength="20"></td>
	</tr>
	<?php } else if($w=='u') { ?>
	<tr>
		<th scope="row"><label for="reg_mb_password_db">当前密码</label></th>
		<td><input type="password" name="dbpasswd" id="reg_mb_password_db" required itemname="当前密码" class="frm_input required" minlength="4" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password">新密码</label></th>
		<td>
			<input type="password" name="passwd" id="reg_mb_password" class="frm_input" minlength="4" maxlength="20">
			<span class="frm_info">四字以上的英文和数字</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">确认新密码</label></th>
		<td><input type="password" name="repasswd" id="reg_mb_password_re" class="frm_input" minlength="4" maxlength="20"></td>
	</tr>
	<?php } ?>	
	<tr>
		<th scope="row">出生日期</th>
		<td>	
			<label for="reg_mb_birth_year" class="sound_only"></label>
			<input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" id="reg_mb_birth_year" required itemname="出生日期" class="frm_input required" maxlength="4" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>年
			
			<label for="reg_mb_birth_month" class="sound_only"></label>
			<input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" id="reg_mb_birth_month" required itemname="出生日期" class="frm_input required" maxlength="2" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>月
			
			<label for="reg_mb_birth_day" class="sound_only"></label>
			<input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" id="reg_mb_birth_day" required itemname="出生日期" class="frm_input required" maxlength="2" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>日
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_birth_type">提升/月球力量</label></th>
		<td>
			<select name="birth_type" id="reg_mb_birth_type">
				<?php echo option_selected('S', $member['birth_type'], /'提升'); ?>
				<?php echo option_selected('L', $member['birth_type'], /'月球力量'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_gender">性别</label></th>
		<td>
			<select name="gender" id="reg_mb_gender">
				<?php echo option_selected('',  $member['gender'], /'性别'); ?>
				<?php echo option_selected('M', $member['gender'], /'男人'); ?>
				<?php echo option_selected('F', $member['gender'], /'女子'); ?>
			</select>
		</td>
	</tr>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row"><label for="reg_telephone">电话号码</label></th>
		<td>
			<input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" id="reg_telephone"<?php echo $config['register_req_tel']?' required':''; ?> itemname="电话号码" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>">
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp']) { ?>
	<tr>
		<th scope="row"><label for="reg_cellphone">手机</label></th>
		<td>
			<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" id="reg_cellphone"<?php echo $config['register_req_hp']?' required':''; ?> itemname="手机" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<div class="padt5">
				<input type="checkbox" name="smsser" value="Y" id="smsser_yes"<?php echo get_checked('Y', $member['smsser']); ?> class="css-checkbox lrg"><label for="smsser_yes" class="css-label">接收SMS。</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_email">电子邮件</label></th>
		<td>
			<input type="email" name="email" value="<?php echo $member['email']; ?>" id="reg_mb_email"<?php echo $config['register_req_email']?' required':''; ?> email itemname="电子邮件" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>">
			<div class="padt5">
				<input type="checkbox" name="mailser" value="Y" id="mailser_yes"<?php echo get_checked('Y', $member['mailser']); ?> class="css-checkbox lrg"><label for="mailser_yes" class="css-label">收到电子邮件.</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">地址</th>
		<td>
			<label for="reg_mb_zip" class="sound_only">邮政编码</label>
			<input type="text" name="zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="邮政编码" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="5" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">地址检索</button><br>

			<label for="reg_mb_addr1" class="sound_only">地址</label>
			<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="地址" class="frm_input frm_address<?php echo $config['register_req_addr']?' required':''; ?>" readonly><br>
			
			<label for="reg_mb_addr2" class="sound_only">详细地址</label>
			<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" id="reg_mb_addr2" class="frm_input frm_address"><br>
			
			<label for="reg_mb_addr3" class="sound_only">参考项目</label>
			<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" id="reg_mb_addr3" class="frm_input frm_address" readonly>
			<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="<?php echo $w==''?'注册会员':'信息修正'; ?>" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">取消</a>
</div>
</form>

<script>
function fregisterform_submit(f)
{
	var str;
	<?php if($w=='') { ?>
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"/'不能使用银的ID。");
		f.id.focus();
		return false;
	}
	<?php } ?>

    // 会员ID检查
	if(f.id.value.length < 3) {
		alert(/'请输入至少3个字符。');
		f.id.focus();
		return false;
	}

	<?php if($w=='') { ?>
    // 密码检查
	if(f.passwd.value.length < 4) {
		alert(/'请输入4个以上密码。');
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert(/'密码不一样。');
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert(/'密码 请输入至少4个字符。');
            f.repasswd.focus();
            return false;
        }
    }

	str = "注册会员";
	<?php } else if($w=='u') { ?>
	if(f.passwd.value) {
		// 密码检查
		if(f.passwd.value.length < 4) {
			alert(/'密码 请输入至少4个字符。');
			f.passwd.focus();
			return false;
		}

		if(f.passwd.value != f.repasswd.value) {
			alert(/'密码不一样。');
			f.repasswd.focus();
			return false;
		}

		if(f.passwd.value.length > 0) {
			if(f.repasswd.value.length < 4) {
				alert(/'密码 请输入4个字以上。');
				f.repasswd.focus();
				return false;
			}
		}
	}

	str = "信息修正";
	<?php } ?>

	<?php if($config['register_use_email']) { ?>
	// 不能使用的E-mail域名
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"/'银是不能使用的邮件。");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm(str+" 您要吗？") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// 会员ID检查
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

// 检查禁止的邮件域
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // 只获取邮件域名

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
