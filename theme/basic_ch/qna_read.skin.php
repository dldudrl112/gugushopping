<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>客户中心<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">题目</th>
			<td><?php echo $qa['subject']; ?></td>
		</tr>
		<tr>
			<th scope="row">内容</th>
			<td style="height:150px;vertical-align:top;"><?php echo nl2br($qa['memo']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<?php if($qa['result_yes']) { ?>
	<h3 class="anc_tit mart30">答辩内容</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">答辩日</th>
			<td><?php echo substr($qa['result_date'],0,10); ?></td>
		</tr>
		<tr>
			<th scope="row">答辩内容</th>
			<td style="height:150px;vertical-align:top;"><?php echo nl2br($qa['reply']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="btn_confirm">
		<a href="<?php echo TB_BBS_URL; ?>/qna_write.php" class="btn_lsmall">联系我们</a>
		<a href="<?php echo TB_BBS_URL; ?>/qna_modify.php?index_no=<?php echo $index_no; ?>" class="btn_lsmall bx-white">修整</a>
		<a href="<?php echo TB_BBS_URL; ?>/qna_list.php" class="btn_lsmall bx-white">目录</a>
		<a href="javascript:del('<?php echo TB_BBS_URL; ?>/qna_read.php?index_no=<?php echo $index_no; ?>&mode=d');" class="btn_lsmall bx-white">删除</a>
	</div>
</div>

<script>
function del(url) {
	answer = confirm("确定要删除吗?");
	if(answer==true) {
		location.href = url;
	}
}
</script>