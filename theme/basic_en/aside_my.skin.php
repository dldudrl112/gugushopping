<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- Start left menu { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">MY PAGE</p>
		<p class="kor">MY PAGE</p>
	</div>
	<div class="aside_name"><?php echo get_text($member['name']); ?></div>
	<ul class="aside_bx">
		<li>point <span><a href="<?php echo TB_SHOP_URL; ?>/point.php"><?php echo number_format($member['point']); ?></a>P</span></li>
	</ul>
	<dl class="aside_my">
		<dt>Order status</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">Order/delivery check</a></dd>
		<dt>a shopping account</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/point.php">Point check</a></dd>
		<?php if($config['gift_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/gift.php">coupon authentication</a></dd>
		<?php } ?>
		<?php if($config['coupon_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/coupon.php">Coupon management</a></dd>
		<?php } ?>
		<dt>wish</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/cart.php">Cart</a></dd>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/wish.php">wish</a></dd>
		<dt>Member information</dt>
		<dd><a href="<?php echo TB_BBS_URL; ?>/register_mod.php">Modify profile</a></dd>
		<dd class="marb5"><a href="<?php echo TB_BBS_URL; ?>/leave_form.php">Membership Withdrawal</a></dd>
	</dl>
</aside>
<!-- } the end of the left menu -->
