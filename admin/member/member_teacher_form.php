<?php
if(!defined('_TUBEWEB_')) exit;

if($w == 'u') {
	$data = sql_fetch("select * from shop_teacher where index_no = '{$index_no}' ");
	if(!$data['index_no'])
		alert('자료가 존재하지 않습니다.');

	$mb	=	get_member_no($data["mb_code"]);
}

?>

<form name="fregisterform" method="post" action="./member/member_teacher_form_update.php">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">아이디</th>
		<td>
			<input type="text" name="id" id="mb_id" required itemname="아이디" class="frm_input required"  value="<?=$mb["id"]?>" size="20" minlength="3" maxlength="20" readonly>
			<a href="./teacher.php" onclick="win_open(this,'pop_teacher','550','500','no');return false" class="btn_small">선생님 선택</a>
			<span id="msg_mb_id" class="marl5"></span>
		</td>
	</tr>
	<tr>
		<th scope="row">상담 상태</th>
		<td>
			<select name="counseling_type" required>
				<option value="1" <?php if($data["counseling_type"] == 1) echo "selected";?>>상담가능</option>
				<option value="2" <?php if($data["counseling_type"] == 2) echo "selected";?>>상담중</option>
				<option value="3" <?php if($data["counseling_type"] == 3) echo "selected";?>>상담종료</option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">주요 상담분야</th>
		<td><input type="text" name="counseling" itemname="상담분야" style="width:100%;" class="frm_input" size="40" value="<?=$data["counseling"]?>"></td>
	</tr>
	<tr>
		<th scope="row">평균 상담시간</th>
		<td><input type="text" name="counseling_time" itemname="상담시간" class="frm_input" size="40" value="<?=$data["counseling_time"]?>"></td>
	</tr>
	<tr>
		<th scope="row">선생님 이력</th>
		<td>
			<textarea name="teacher_history" class="frm_textbox" style="width:100%;" rows="5"><?=$data["teacher_history"]?></textarea>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>