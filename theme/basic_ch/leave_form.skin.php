<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>我的页面 <i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fleaveform" id="fleaveform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fleaveform_submit(this);" autocomplete="off">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">顾客名(ID)</th>
			<td><b><?php echo $member['name']; ?></b> (<?php echo $member['id']; ?>)</td>
		</tr>
		<tr>
			<th scope="row">持仓点</th>
			<td><b><?php echo number_format($member['point']); ?>P</b> <span class="fc_red marl10">※ 退出后积分将全部消失。</span></td>
		</tr>
		<tr>
			<th scope="row">E-Mail</th>
			<td><?php echo ($member['email'] ? $member['email'] : '未登记'); ?></td>
		</tr>
		<tr>
			<th scope="row">手机</th>
			<td><?php echo ($member['cellphone'] ? $member['cellphone'] : '未登记'); ?></td>
		</tr>
		<tr>
			<th scope="row">现有密码</th>
			<td><input type="password" name="mb_password" required itemname="现有密码" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<section>
		<h2 class="anc_tit">就退出理由,如果能给顾客留下宝贵的意见,我会努力提供更好的服务。</h2>
		<ul>
			<li>
				<input type="radio" name="memo" id="memo1" value="变更为其他ID">
				<label for="memo1">变更为其他ID</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo2" value="注册会员优惠少">
				<label for="memo2">注册会员优惠少</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo3" value="个人信息(通讯及信用信息)暴露的忧虑">
				<label for="memo3">个人信息(通讯及信用信息)暴露的忧虑</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo4" value="系统障碍(速度低下,频繁错误等)">
				<label for="memo4">系统障碍(速度低下,频繁错误等)</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo5" value="对服务的不满(迟配送,价格不满意,复杂的程序等)">
				<label for="memo5">서비스의 불만 对服务的不满(迟配送,价格不满意,复杂的程序等)</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo6" value="旷日持久">
				<label for="memo6">旷日持久</label>
			</li>
			<li>
				<input type="radio" name="memo" id="memo7" value="等等" onclick="showDiv('other');">
				<label for="memo7">等等</label> <input type="text" class="frm_input marl10" size="60" name="other" style="visibility:hidden">
			</li>
		</ul>
	</section>

	<div class="btn_confirm">
		<input type="submit" value="注销会员" class="btn_large wset">
		<a href="javascript:history.go(-1);" class="btn_large bx-white">取消</a>
	</div>

	</form>
</div>

<script>
function fleaveform_submit(f) {
	if(confirm("真的要退出会员吗?") == false)
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
