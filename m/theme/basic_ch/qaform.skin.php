<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
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
            <col class="w70">
            <col>
        </colgroup>
        <tbody>
		<tr>
			<th scope="row"><label for="iq_ty">期权</label></th>
			<td>
				<select name="iq_ty" required class="marr5">
					<option value=""<?php echo get_selected($iq_ty, ""); ?>>咨询类型(选择)</option>
					<option value="商品"<?php echo get_selected($iq_ty, "商品"); ?>>商品</option>
					<option value="配送"<?php echo get_selected($iq_ty, "配送"); ?>>配送</option>
					<option value="退货/退款/取消"<?php echo get_selected($iq_ty, "退货/退款/取消"); ?>>退货/退款/取消 </option>
					<option value="交换/变更"<?php echo get_selected($iq_ty, "交换/变更"); ?>>交换/变更</option>
					<option value="吉他"<?php echo get_selected($iq_ty, "吉他"); ?>>吉他</option>
				</select>
				<input id="iq_secret" type="checkbox" name="iq_secret" value='1' class="css-checkbox lrg"
				<?php echo get_checked($iq_secret, '1'); ?>><label for="iq_secret" class="css-label">秘密文章</label>

			</td>
		</tr>
        <tr>
            <th scope="row"><label for="iq_name">姓名</label></th>
            <td><input type="text" name="iq_name" value="<?php echo get_text($iq_name); ?>" id="iq_name" required class="required frm_input" size="30"></td>
        </tr>
        <tr>
            <th scope="row"><label for="iq_email">电子邮件</label></th>
            <td><input type="text" name="iq_email" value="<?php echo get_text($iq_email); ?>" id="iq_email" class="frm_input" size="30"></td>
        </tr>
        <tr>
            <th scope="row"><label for="iq_hp">手机</label></th>
            <td><input type="text" name="iq_hp" value="<?php echo get_text($iq_hp); ?>" id="iq_hp" class="frm_input" size="20"></td>
        </tr>
        <tr>
            <th scope="row"><label for="iq_subject">题目</label></th>
            <td><input type="text" name="iq_subject" value="<?php echo get_text($iq_subject); ?>" id="iq_subject" required class="required frm_input" minlength="2" maxlength="250"></td>
        </tr>
        <tr>
            <th scope="row"><label for="iq_question">提问</label></th>
            <td><textarea name="iq_question" required class="required frm_textbox"><?php echo $iq_question; ?></textarea></td>
        </tr>
        </tbody>
        </table>
    </div>

    <div class="win_btn">
        <input type="submit" value="制作完毕" class="btn_medium">
		<a href="javascript:window.close();" class="btn_medium bx-white">关闭窗口</a>
    </div>
    </form>
</div>

<script>
function fitemqa_submit(f)
{
    return true;
}
</script>
