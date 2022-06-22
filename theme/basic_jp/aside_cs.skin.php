<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 左側のメニューを開始 { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">CS CENTER</p>
		<p class="kor">顧客センター</p>
	</div>
	<dl class="aside_cs">	
		<?php
		$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			$bo_href = TB_BBS_URL.'/list.php?boardid='.$row['index_no'];
			echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
		}
		?>	
		<dt><a href="<?php echo TB_BBS_URL; ?>/review.php">顧客商品評</a></dt>
		<dt><a href="<?php echo TB_BBS_URL; ?>/qna_list.php">1:1 相談お問い合わせ</a></dt>		
		<dt><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">よく聞く質問</a></dt>		
		<?php
		// FAQ MASTER
		$fm_sql = "select * from shop_faq_cate order by index_no asc";
		$fm_result = sql_query($fm_sql);
		for($i=0;$row=sql_fetch_array($fm_result);$i++){
			if($i==0) echo "<dd>\n<ul>\n";
			$fm_href = TB_BBS_URL.'/faq.php?faqcate='.$row['index_no'];
			echo '<li><a href="'.$fm_href.'">'.$row['catename'].'</a></li>'.PHP_EOL;
		}
		if($i > 0) echo "</ul>\n</dd>\n";
		?>
	</dl>
</aside>
<!-- } 左側メニューの終わり -->
