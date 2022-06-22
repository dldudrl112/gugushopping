<?php
if(!defined('_TUBEWEB_')) exit;

$gr	=	sql_fetch("select * from shop_goods_review where index_no = '$gr_id'");
$gs	=	sql_fetch("select * from shop_goods where index_no = '$gr[gs_id]'");

if(!$gr['index_no'])
	alert("자료가 존재하지 않습니다.");
?>

<form name="fregform" method="post" action="./goods/goods_review_form_update.php" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="gr_id" value="<?php echo $gr_id; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">상품명</th>
		<td><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $iq['gs_id']; ?>" target="_blank"><?php echo $gs['gname']; ?></a></td>
	</tr>
	<tr>
		<th scope="row">주문코드</th>
		<td><?php echo $gr['od_id']; ?></td>
	</tr>
	<tr>
		<th scope="row">회원 아이디</th>
		<td><?php echo $gr['mb_id']; ?></td>
	</tr>
	<tr>
		<th scope="row">별점</th>
		<td><img src="<?php echo TB_IMG_URL; ?>/sub/score_<?php echo $gr['score']; ?>.gif"></td>
	</tr>
	<tr>
		<th scope="row">제목</th>
		<td><?php echo $gr['title']; ?></td>
	</tr>
	<tr>
		<th scope="row">내용</th>
		<td><?php echo nl2br($gr['memo']); ?></td>
	</tr>
	<tr>
		<th scope="row">답변</th>
		<td><textarea name="answer" class="frm_textbox"><?php echo $gr['answer']; ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./goods.php?code=review<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>
