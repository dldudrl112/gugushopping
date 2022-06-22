<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
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
			<th scope="row">Customer name(ID)</th>
			<td><b><?php echo $member['name']; ?></b> (<?php echo $member['id']; ?>)</td>
		</tr>
		<tr>
			<th scope="row">retention point</th>
			<td><b><?php echo number_format($member['point']); ?>P</b> <span class="fc_red">(Points will disappear after withdrawal.)</span></td>
		</tr>
		<tr>
			<th scope="row">E-Mail</th>
			<td><?php echo ($member['email'] ? $member['email'] : 'Unregistered'); ?></td>
		</tr>
		<tr>
			<th scope="row">
cell phone</th>
			<td><?php echo ($member['cellphone'] ? $member['cellphone'] : 'Unregistered'); ?></td>
		</tr>
		<tr>
			<th scope="row">Current password</th>
			<td><input type="password" name="mb_password" required itemname="Current password" class="frm_input required w150" minlength="4" maxlength="20"></td>
		</tr>
		</tbody>
		</table>
	</div>

    <section>
		<h2 class="anc_tit">If you leave us your valuable opinion on the reason for withdrawal, we will strive for better service.</h2>
		<ul>
			<li>
				<input type="radio" name="memo" id="memo1" value="Change to a different ID">
				<label for="memo1">Change to a different ID</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo2" value="Sign - up benefits are low">
				<label for="memo2">Membership benefits are low</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo3" value="
Exposure of personal information (communication and credit information)">
				<label for="memo3">Concerns about personal information (communication and credit information) exposure</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo4" value="System fault (low speed control, cold error, etc.)">
				<label for="memo4">System fault (low speed control, cold error, etc.)</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo5" value="Complaint of service (delayed delivery, price dissatisfaction, complicated procedures, etc.) ">
				<label for="memo5">Complaint of service (delayed delivery, price dissatisfaction, complicated procedures, etc.)</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo6" value="a long absence">
				<label for="memo6">a long absence</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo7" value="etc" onclick="showDiv('other');">
				<label for="memo7">etc</label> <input type="text" class="frm_input marl10" size="60" name="other" style="visibility:hidden">
			</li>
		</ul>
	</section>

	<div class="btn_confirm">
		<input type="submit" value="Membership Withdrawal" class="btn_medium wset">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">Cancellation</a>
	</div>

	</form>
</div>

<script>
function fleaveform_submit(f) {
	if(confirm("Are you sure you want to withdraw from membership?") == false)
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
