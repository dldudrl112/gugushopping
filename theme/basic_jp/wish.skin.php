<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>マイページ<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">
		<em>全体 <?php echo number_format($wish_count); ?>個</em>の商品が保管されています。
	</p>

	<form name="fwishlist" id="fwishlist" method="post">
	<input type="hidden" name="act" value="multi">
	<input type="hidden" name="sw_direct">

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="50">
			<col width="80">
			<col>
			<col width="100">
			<col width="60">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">選択</th>
			<th scope="col">画像</th>
			<th scope="col">商品情報</th>
			<th scope="col">価格</th>
			<th scope="col">削除</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row = sql_fetch_array($result); $i++) {
			$out_cd = '';
			$sql = " select count(*) as cnt from shop_goods_option where gs_id = '{$row['gs_id']}' and io_type = '0' ";
			$tmp = sql_fetch($sql);
			if($tmp['cnt'])
				$out_cd = 'no';

			if($row['price_msg']) {
				$out_cd = 'price_msg';
			}
		?>
		<tr>
			<td class="tac">
				<?php if(is_soldout($row['gs_id'])) { ?>
				品切れ
				<?php } else { ?>
				<input type="checkbox" name="chk_gs_id[<?php echo $i; ?>]" value="1" onclick="out_cd_check(this, '<?php echo $out_cd; ?>');">
				<?php } ?>
				<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
				<input type="hidden" name="io_type[<?php echo $row['gs_id']; ?>][0]" value="0">
				<input type="hidden" name="io_id[<?php echo $row['gs_id']; ?>][0]" value="">
				<input type="hidden" name="io_value[<?php echo $row['gs_id']; ?>][0]" value="<?php echo $row['gname']; ?>">
				<input type="hidden" name="ct_qty[<?php echo $row['gs_id']; ?>][0]" value="1">
			</td>
			<td class="tac"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>"><?php echo get_it_image($row['gs_id'], $row['simg1'], 70, 70); ?></a></td>
			<td class="td_name">
				<a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>"><?php echo $row['gname']; ?></a>
				<p class="fc_999"><?php echo $row['explan']; ?></p>
			</td>			
			<td class="tar"><?php echo get_price($row['gs_id']); ?></td>
			<td class="tac"><a href="<?php echo TB_SHOP_URL; ?>/wishupdate.php?w=d&wi_id=<?php echo $row['wi_id']; ?>" class="btn_small bx-red">削除</td>
		</tr>
		<?php
		} 
		if($i==0) 
			echo '<tr><td colspan="5" class="empty_list">보관함이 비었습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>
	
	<div class="btn_confirm">		
		<button type="submit" class="btn_large wset" onclick="return fwishlist_check(document.fwishlist,'direct_buy');">オーダ</button>
		<button type="submit" class="btn_large bx-white" onclick="return fwishlist_check(document.fwishlist,'');">カートに入れる</button>
	</div>
	</form>
</div>

<script>
<!--
function out_cd_check(fld, out_cd)
{
	if(out_cd == 'no'){
		alert("オプションがある商品です。\n\n商品をクリックして、商品ページでオプションを選択した後ご注文ください。");
		fld.checked = false;
		return;
	}

	if(out_cd == 'price_msg'){
		alert("この商品はお電話でお問い合わせください。\n\nカートに入れて購入することができません.");
		fld.checked = false;
		return;
	}
}

function fwishlist_check(f, act)
{
	var k = 0;
	var length = f.elements.length;

	for(i=0; i<length; i++) {
		if(f.elements[i].checked) {
			k++;
		}
	}

	if(k == 0)
	{
		alert("商品を1つ以上チェックしてください");
		return false;
	}

	if(act == "direct_buy")
	{
		f.sw_direct.value = 1;
	}
	else
	{
		f.sw_direct.value = 0;
	}

	f.action = "./cartupdate.php";
	f.submit();
}
//-->
</script>
