<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 左側メニューのスタート { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">MY PAGE</p>
		<p class="kor">マイページ</p>
	</div>
	<div class="aside_name"><?php echo get_text($member['name']); ?></div>
	<ul class="aside_bx">
		<li>ポイント <span><a href="<?php echo TB_SHOP_URL; ?>/point.php"><?php echo number_format($member['point']); ?></a>P</span></li>
	</ul>
	<dl class="aside_my">
		<dt>注文現況</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">ご注文/配送照会</a></dd>
		<dt>買い物通帳</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/point.php">ポイント照会</a></dd>
		<?php if($config['gift_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/gift.php">クーポン認証</a></dd>
		<?php } ?>
		<?php if($config['coupon_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/coupon.php">クーポン管理</a></dd>
		<?php } ?>
		<dt>関心商品</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/cart.php">カート</a></dd>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/wish.php">私の関心商品</a></dd>
		<dt>会員情報</dt>
		<dd><a href="<?php echo TB_BBS_URL; ?>/register_mod.php">会員情報修正</a></dd>
		<dd class="marb5"><a href="<?php echo TB_BBS_URL; ?>/leave_form.php">会員脱退</a></dd>
	</dl>
</aside>
<!-- } 左側メニューの終わり -->
