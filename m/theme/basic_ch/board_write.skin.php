<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<form name="fwrite" id="fwrite" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fwrite_submit(this);" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w;?>">
<input type="hidden" name="boardid" value="<?php echo $boardid;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="m_bo_bg mart10">
	<div class="m_bo_wrap">
		<table class="tbl03">
		<colgroup>
			<col style="width:70px">
			<col style="width:auto">
		</colgroup>
		<tbody>
		<tr>
			<th>名</th>
			<td>
				<?php
				if($is_member) {
					echo $write['writer_s'];
					echo "<input type='hidden' name='writer_s' value='$write[writer_s]'>";
				} else {
					echo "<input type='text' name='writer_s' value='$write[writer_s]' style='width:150px;'>";
				}
				?>
			</td>
		</tr>
		<?php if(!$is_member && $w != 'r') { ?>
		<tr>
			<th>密码</th>
			<td><input type="password" name="passwd" style="width:150px;"></td>
		</tr>
		<?php } ?>
		<?php if($board['use_category'] == '1') { ?>
		<tr>
			<th>분류</th>
			<td>
				<select name="ca_name" style="width:100%;">
				<option value="">选择吧。</option>
				<?php echo get_category_option($board['usecate']);?>
				</select>
				<?php if($w == 'u' || $w == 'r') { ?>
				<script>document.fwrite.ca_name.value='<?php echo $write[ca_name];?>';</script>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<?php
		$option = "";
		$option_hidden = "";
		if(is_admin()) {
			$checked = '';
			if($write['btype']=='1') { $checked = ' checked'; }
			$option .= '<input type="checkbox" name="btype" value="1" id="ids_btype" class="css-checkbox lrg"'.$checked.'><label for="ids_btype" class="css-label padr10">公告事项</label>';

			$checked = '';
			if($write['issecret']=='Y') { $checked = ' checked'; }
			$option .= '<input type="checkbox" name="issecret" value="Y" id="ids_issecret" class="css-checkbox lrg"'.$checked.'><label for="ids_issecret" class="css-label padr10">秘密文章</label>';
		} else {
			switch($board['use_secret']) {
				case '0':
					$option_hidden .= '<input type="hidden" name="issecret" value="N">';
					break;
				case '1':
					$checked = '';
					if($write['issecret']=='Y') { $checked = ' checked'; }
					$option .= '<input type="checkbox" name="issecret" value="Y" id="ids_issecret" class="css-checkbox lrg"'.$checked.'> <label for="ids_issecret" class="css-label padr10">秘密文章</label>';
					break;
				case '2':
					$option_hidden .= '<input type="hidden" name="issecret" value="Y">';
					break;
			}
		}

		echo $option_hidden;
		if($option) {
		?>
		<tr>
			<th>期权</th>
			<td><?php echo $option;?></td>
		</tr>
		<?php } ?>
		<tr>
			<th>题目</th>
			<td><input type="text" name="subject" value="<?php echo $write['subject'];?>" class="wfull"></td>
		</tr>
		<tr style="border-bottom:none">
			<th>内容</th>
			<td><textarea name="memo" rows="10" class="wfull"><?php echo $write['memo'];?></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="确认" class="btn_medium">
		<?php if($w == 'u' || $w == 'r') { ?>
		<a href="<?php echo TB_MBBS_URL; ?>/board_list.php?<?php echo $qstr1;?>" class="btn_medium bx-white">目录</a>
		<?php } else { ?>
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">취소</a>
		<?php } ?>
	</div>
</div>
</form>

<script>
function fwrite_submit(f) {
	<?php if(!$is_member) { ?>
	if(!f.writer_s.value) {
		alert(/'请输入姓名。');
		f.writer_s.focus();
		return false;
	}
	<?php } ?>
	<?php if(!$is_member && $w != 'r') { ?>
	if(!f.passwd.value) {
		alert(/'请输入密码。');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	<?php if($board['use_category'] == '1') { ?>
	if(!f.ca_name.value) {
		alert(/'请选择分类。');
		f.ca_name.focus();
		return false;
	}
	<?php } ?>

	if(!f.subject.value) {
		alert(/'请输入题目。');
		f.subject.focus();
		return false;
	}

    return true;
}
</script>
