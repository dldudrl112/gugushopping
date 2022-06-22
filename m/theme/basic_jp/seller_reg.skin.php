<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

<div id="ctt">
	<?php echo get_image_resize($config['seller_reg_mobile_guide']); ?>

	<div class="btn_confirm">
		<a href="<?php echo TB_MBBS_URL; ?>/seller_reg_from.php" class="btn_medium wset">確認</a>
		<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">キャンセル</a>
	</div>
</div>