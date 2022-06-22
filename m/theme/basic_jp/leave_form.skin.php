<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<div id="smb_my">
	<form name="fleaveform" id="fleaveform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fleaveform_submit(this);" autocomplete="off">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">顧客名(ID)</th>
			<td><b><?php echo $member['name']; ?></b> (<?php echo $member['id']; ?>)</td>
		</tr>
		<tr>
			<th scope="row">保有ポイント</th>
			<td><b><?php echo number_format($member['point']); ?>P</b> <span class="fc_red">(脱退以来、ポイントは消滅します。)</span></td>
		</tr>
		<tr>
			<th scope="row">E-Mail</th>
			<td><?php echo ($member['email'] ? $member['email'] : /'未登録'); ?></td>
		</tr>
		<tr>
			<th scope="row">携帯電話</th>
			<td><?php echo ($member['cellphone'] ? $member['cellphone'] : /'未登録'); ?></td>
		</tr>
		<tr>
			<th scope="row">現在のパスワード</th>
			<td><input type="password" name="mb_password" required itemname="現在のパスワード" class="frm_input required w150" minlength="4" maxlength="20"></td>
		</tr>
		</tbody>
		</table>
	</div>

    <section>
		<h2 class="anc_tit">脱退の理由について、お客様の貴重なご意見を残していただければ、より良いサービスのために努力します。</h2>
		<ul>
			<li>
				<input type="radio" name="memo" id="memo1" value="別のIDに変更">
				<label for="memo1">別のIDに変更</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo2" value="会員登録のメリットが少ない">
				<label for="memo2">会員登録のメリットが少ない</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo3" value="個人情報（通信と信用情報）の露出懸念">
				<label for="memo3">個人情報（通信と信用情報）の露出懸念</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo4" value="システム障害（速度低調、頻繁にエラーなど)">
				<label for="memo4">システム障害（速度低調、頻繁にエラーなど)</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo5" value="サービスの苦情（遅い配送、価格不満、複雑な手続きなど）">
				<label for="memo5">サービスの苦情（遅い配送、価格不満、複雑な手続きなど）</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo6" value="長時間の不在">
				<label for="memo6">長時間の不在</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo7" value="その他 " onclick="showDiv('other');">
				<label for="memo7">その他 </label> <input type="text" class="frm_input marl10" size="60" name="other" style="visibility:hidden">
			</li>
		</ul>
	</section>

	<div class="btn_confirm">
		<input type="submit" value="会員脱退" class="btn_medium wset">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">キャンセル</a>
	</div>

	</form>
</div>

<script>
function fleaveform_submit(f) {
	if(confirm("本当に退会しますか？") == false)
		return false;

    return true;
}

function showDiv( id ) {
    document.all.other.style.visibility = 'hidden';
    document.all.other.value = '';
    document.all[ id ].style.visibility = 'visible';
    document.all[ id ].focus();
}
</script>
