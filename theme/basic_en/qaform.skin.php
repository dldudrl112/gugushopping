<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="sit_qa_write" class="new_win">
    <h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<form name="fitemqa" id="fitemqa" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fitemqa_submit(this);">
	<input type="hidden" name="w" value="<?php echo $w; ?>">
	<input type="hidden" name="mb_id" value="<?php echo $member['id']; ?>">
	<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">	
	<input type="hidden" name="gs_use_aff" value="<?php echo $gs['use_aff']; ?>">
	<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">	
	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<input type="hidden" name="iq_id" value="<?php echo $iq_id; ?>">

	<div class="tbl_frm01 tbl_wrap">
        <table>
        <colgroup>
            <col class="w90">
            <col>
        </colgroup>
		<tbody>
		<tr>
			<th scope="row">Product name</th>
			<td><?php echo $gs['gname']; ?></td>
		</tr>
		<tr>
			<th scope="row">Options</th>
			<td>
				<select name="iq_ty" required itemname="Type of inquiry">
					<option value=""<?php echo get_selected($iq_ty, ""); ?>>Type of inquiry(Choice)</option>
					<option value="product"<?php echo get_selected($iq_ty, "product"); ?>>product</option>
					<option value="Shipping"<?php echo get_selected($iq_ty, "Shipping"); ?>>Shipping</option>
					<option value="Return/Refund/Cancellation"<?php echo get_selected($iq_ty, "Return/Refund/Cancellation"); ?>>Return/Refund/Cancellation</option>
					<option value="Exchange/Change"<?php echo get_selected($iq_ty, "Exchange/Change"); ?>>Exchange/Change</option>
					<option value="Guitar"<?php echo get_selected($iq_ty, "etc"); ?>>etc</option>
				</select>
				<input id="iq_secret" type="checkbox" name="iq_secret" value="1"
				<?php echo get_checked($iq_secret, '1'); ?> class="marl7">
				<label for="iq_secret">Secret writing</label>
			</td>
		</tr>
		<tr>
			<th scope="row">a full name</th>
			<td><input type="text" name="iq_name" value="<?php echo $iq_name; ?>" required itemname="a full name" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><input type="text" name="iq_email" value="<?php echo $iq_email; ?>" required email itemname="E-mail" class="frm_input required" size="30"></td>
		</tr>
		<tr>
			<th scope="row">cell phone</th>
			<td><input type="text" name="iq_hp" value="<?php echo $iq_hp; ?>" required itemname="cell phone" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">Title</th>
			<td><input type="text" name="iq_subject" value="<?php echo $iq_subject; ?>" required itemname="Title" class="frm_input wfull required"></td>
		</tr>
		<tr>
			<th scope="row">question</th>
			<td><textarea name="iq_question" rows="10" required itemname="question" class="frm_textbox wufll required"><?php echo $iq_question; ?></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

    <div class="win_btn">
        <input type="submit" value="Completed" class="btn_lsmall">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">Close Window</a>
    </div>
	</form>
</div>

<script>
function fitemqa_submit(f) {
	if(confirm("Would you like to register?") == false)
		return false;

    return true;
}
</script>
