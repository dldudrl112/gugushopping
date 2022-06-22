<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<div class="m_bo_bg">
	<ul>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$bo_date = $row['mb_id']."<span class='padl10'>".substr($row['wdate'],0,10);
		?>
		<li class="list">
			<a href="<?php echo TB_MBBS_URL; ?>/qna_read.php?index_no=<?php echo $row['index_no']; ?>">
			<p class="subj"><b class="cate">[ <?php echo $row['catename']; ?> ]</b> <?php echo cut_str($row['subject'],60); ?></p>
			<p class="date"><?php echo $bo_date; ?></p>
			</a>
		</li>
		<?php
		}
		if($i==0) echo '<li class="empty_list">資料がありません。</li>';
		?>
	</ul>

	<?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page='); 
	?>

	<div class="btn_confirm">
		<p><a href="<?php echo TB_MBBS_URL; ?>/qna_write.php" class="btn_medium wset">相談お問い合わせ</a></p>
	</div>

</div>