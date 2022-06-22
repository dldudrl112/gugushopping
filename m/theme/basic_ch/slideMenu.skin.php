<?php
if(!defined("_TUBEWEB_")) exit; // 个别页面无法访问
?>

<div id="slideMenu">
	<dl class="top_btn">
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/logout.php" class="btn_medium">注销</a>
			<?php } else { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="btn_medium">登录</a>
			<?php } ?>
		</dd>
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/register_form.php?w=u" class="btn_medium bx-white">信息修正</a>
			<?php } else { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white">注册会员</a>
			<?php } ?>
		</dd>
	</dl>
	<ul class="sm_icbt">
		<li><a href="<?php echo TB_MSHOP_URL; ?>/cart.php"><i class="ionicons ion-android-cart"></i> 菜篮子</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php"><i class="ionicons ion-ios-list-outline"></i> 订购/配送</a></li>
		<li><a href="<?php echo TB_MBBS_URL; ?>/review.php"><i class="ionicons ion-android-camera"></i> 购买后期</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/mypage.php"><i class="ionicons ion-android-contact"></i> 我的页面</a></li>
	</ul>
	<ul class="smtab">
		<li data-tab="shop_cate">类别</li>
		<li data-tab="custom">客户中心</li>
		<li data-tab="mypage">我的页面</li>
	</ul>
	<div id="shop_cate" class="sm_body">
		<?php
		$r = sql_query_cgy('all', 'COUNT');
		if($r['cnt'] > 0){
		?>
		<ul>
			<?php
			$res = sql_query_cgy('all');
			while($row = sql_fetch_array($res)) {
				$href = TB_MSHOP_URL.'/list.php?ca_id='.$row['catecode'];
			?>
			<li class="bm"><?php echo $row['catename'];?></li>
			<li class="subm">
				<ul>
					<li><a href="<?php echo $href;?>">全体 </a></li>
					<?php
					$res2 = sql_query_cgy($row['catecode']);
					while($row2 = sql_fetch_array($res2)) {
						$href2 = TB_MSHOP_URL.'/list.php?ca_id='.$row2['catecode'];
					?>
					<li><a href="<?php echo $href2;?>"><?php echo $row2['catename']; ?></a></li>
					<?php } ?>
				</ul>
			</li>
			<?php } ?>
		</ul>
		<?php } else { ?>
		<p class="sct_noitem">没有登记的分类。</p>
		<?php } ?>
	</div>
	<div id="custom" class="sm_body">
		<ul>			
			<?php
			$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
			$res = sql_query($sql);
			for($i=0; $row=sql_fetch_array($res); $i++) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/board_list.php?boardid=<?php echo $row['index_no']; ?>"><?php echo $row['boardname']; ?></a></li>
			<?php } ?>	
			<li><a href="<?php echo TB_MBBS_URL; ?>/review.php">购买后期</a></li>
			<li><a href="<?php echo TB_MBBS_URL; ?>/qna_list.php">1:1 联系我们</a></li>
			<li><a href="<?php echo TB_MBBS_URL; ?>/faq.php">常见问题</a></li>			
		</ul>
	</div>
	<div id="mypage" class="sm_body">
		<ul>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php">订单/运输视图</a></li>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/point.php">点查询</a></li>
			<?php if($config['gift_yes']) { ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/gift.php">优惠券认证</a></li>
			<?php } ?>
			<?php if($config['coupon_yes']) { ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php">优惠券管理</a></li>
			<?php } ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/wish.php">特色产品</a></li>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/today.php">最近看到的商品</a></li>
			<?php if($is_member) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/leave_form.php">注销会员</a></li>
			<?php } ?>
		</ul>
	</div>
	<dl class="sm_cs">
		<dt>客户中心</dt>
		<dd class="cs_tel"><?php echo $config['company_tel']; ?></dd>
		<dd>商谈 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
		<dd>午餐 : <?php echo $config['company_lunch']; ?></dd>
	</dl>
	<dl class="sm_cs">
		<dt>汇款账号通知</dt>
		<?php $bank = unserialize($default['de_bank_account']); ?>
		<dd><?php echo $bank[0]['name']; ?> <b><?php echo $bank[0]['account']; ?></b></dd>
		<dd>存款主名 : <?php echo $bank[0]['holder']; ?></dd>
	</dl>
	<p class="mart20"><a href="tel:<?php echo $config['company_tel']; ?>" class="btn_medium grey wfull">客服中心电话连线</a></p>
</div>
<script>
$(function(){
	// 在左侧幻灯片菜单上 子菜单操作
	$('#slideMenu .subm').hide();
	$('#slideMenu .bm').click(function(){
		if($(this).hasClass('active')){
			$(this).next().slideUp(250);
			$(this).removeClass('active');
		} else {
			$('#slideMenu .bm').removeClass('active');
			$('#slideMenu .subm').slideUp(250);
			$(this).addClass('active');
			$(this).next().slideDown(250);
		}
	});

	// 点击上端菜单按钮时,页面幻灯片
	$(".btn_sidem").click(function () {
		$("#slideMenu, #wrapper, .page_cover, html").addClass("m_open");
		window.location.hash = "#Menu";
		$("#wrapper, html").css({
			height: $(window).height()
		});
	});
	window.onhashchange = function () {
		if(location.hash != "#Menu") {
			$("#slideMenu, #wrapper, .page_cover, html").removeClass("m_open");
			$("#wrapper, html").css({
				height:'100%'
			});
		}
	};

	//踢踏功能
	$(document).ready(function(){
		$(".smtab>li:eq(0)").addClass('active');
		$("#shop_cate").addClass('active');

		$(".smtab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".smtab>li").removeClass('active');
			$(".sm_body").removeClass('active');
			$(this).addClass('active');
			$("#"+activeTab).addClass('active');
		});
	});
});
</script>
