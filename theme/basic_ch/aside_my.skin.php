<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 左侧的开始菜单 { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">MY PAGE</p>
		<p class="kor">我的页面</p>
	</div>
	<div class="aside_name"><?php echo get_text($member['name']); ?></div>
	<ul class="aside_bx">
		<li>点 <span><a href="<?php echo TB_SHOP_URL; ?>/point.php"><?php echo number_format($member['point']); ?></a>P</span></li>
	</ul>
	<dl class="aside_my">
		<dt>订货现状</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">订购/配送查询</a></dd>
		<dt>订购/配送查询</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/point.php">查询积分</a></dd>
		<?php if($config['gift_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/gift.php">优惠券认证</a></dd>
		<?php } ?>
		<?php if($config['coupon_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/coupon.php">优惠券管理</a></dd>
		<?php } ?>
		<dt>关注商品</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/cart.php">菜篮子</a></dd>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/wish.php">我蒸的商品</a></dd>
		<dt>会员信息</dt>
		<dd><a href="<?php echo TB_BBS_URL; ?>/register_mod.php">会员信息修改</a></dd>
		<dd class="marb5"><a href="<?php echo TB_BBS_URL; ?>/leave_form.php">注销会员</a></dd>
	</dl>
</aside>
<!-- } 左菜单结束 -->
