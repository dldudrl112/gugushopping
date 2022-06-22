<?php
if(!defined('_TUBEWEB_')) exit;
?>

<a name="it_comment"></a>
<div class="bx-danger">
	全体 <b><?php echo $item_use_count; ?></b>件のレビューがあります。レビューに加えて、他の目的や不健全な内容をのばし場合は、削除処理されることがあります。
</div>

<table class="wfull">
<?php
$sql = "select * from shop_goods_review where gs_id = '$index_no' ";
if($default['de_review_wr_use']) { 
	$sql .= " and pt_id = '$pt_id' ";
}
$sql .= " order by index_no desc ";
$res = sql_query($sql);
while($row = sql_fetch_array($res)) {
?>
<tr>
	<td class="tal padl10 padt15 padb10 lh6"><?php if(is_admin() || ($member['id'] == $row['mb_id'])) { ?><a href="javascript:tdel('<?php echo TB_SHOP_URL; ?>/view_user_update.php?index_no=<?php echo $index_no; ?>&it_mid=<?php echo $row['index_no']; ?>&mode=d');"><img src="<?php echo TB_IMG_URL; ?>/icon/icon_x.gif" width="15" height="15" align="absmiddle"></a>&nbsp;&nbsp;<?php } ?><?php echo $row['memo']; ?></td>
	<td width="130" class="tac"><?php echo cut_str($row['mb_id'], 10); ?></td>
	<td width="80" class="tac"><?php echo substr($row['reg_time'],0,10); ?></td>
	<td width="80" class="tac"><?php for($i=0;$i<(int)$row['score'];$i++) { ?><img src="<?php echo TB_IMG_URL; ?>/sub/comment_start.jpg" align="absmiddle"><?php } ?></td>
</tr>
<tr><td height="1" bgcolor="#eeeeee" colspan="4"></td></tr>
<?php } ?>
</table>

<form name="fuserform" id="fuserform" action="<?php echo TB_SHOP_URL; ?>/view_user_update.php" method="post" onsubmit="return fuserform_submit(this);" class="mart20">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<table class="wfull">
<tr>
	<td class="tal">Name : <?php if($is_member) { echo $member['name']; } else { echo "ログイン後作成してください。"; } ?></td>
	<td class="tar">
		<?php
		for($i=1; $i<=5; $i++) {
			$checked = "";
			if($i == 1) $checked = "checked";
		?>
		<input type="radio" name="score" value="<?php echo $i; ?>" <?php echo $checked; ?>>
		<img src="<?php echo TB_IMG_URL; ?>/sub/score_<?php echo $i; ?>.gif" align="absmiddle">
		<?php } ?>
	</td>
</tr>
</table>

<table class="wfull mart10">
<tr>
	<td><textarea name="memo" class="letter_bx" <?php if(!$is_member) { echo "disabled"; } ?>></textarea></td>
	<td width="10"></td>
	<td width="78">
		<?php if($is_member) { ?>
		<button type="submit" name="formimage1" class="btn_letter">使用後記<br>登録する</button>
		<?php } else { ?>
		<a href="javascript:tguest();" class="btn_letter">使用後記<br>登録する</a>
		<?php } ?>
	</td>
</tr>
</table>
</form>

<script>
function fuserform_submit(f){
	if(!f.memo.value){
		alert(/'内容を入力してください。');
		f.memo.focus();
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;
}

function tdel(url){
	if(confirm(/'削除しますか？')){
		location.href = url;
	}
}

function tguest(){
	answer = confirm(/'ログインが必要レビューの作成が可能です。ログインしますか?');
	if(answer==true) {	
		location.href = tb_bbs_url+"/login.php?url=<?php echo $urlencode; ?>";
	}
}
</script>
