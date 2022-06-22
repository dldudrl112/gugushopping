<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages
?>

<div id="slideMenu">
	<dl class="top_btn">
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/logout.php" class="btn_medium">Log out</a>
			<?php } else { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/login.php?url=<?php echo $urlencode; ?>" class="btn_medium">login</a>
			<?php } ?>
		</dd>
		<dd>
			<?php if($is_member) { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/register_form.php?w=u" class="btn_medium bx-white">Changing information</a>
			<?php } else { ?>
			<a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium bx-white">join membership</a>
			<?php } ?>
		</dd>
	</dl>
	<ul class="sm_icbt">
		<li><a href="<?php echo TB_MSHOP_URL; ?>/cart.php"><i class="ionicons ion-android-cart"></i> cart</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php"><i class="ionicons ion-ios-list-outline"></i> Order/delivery</a></li>
		<li><a href="<?php echo TB_MBBS_URL; ?>/review.php"><i class="ionicons ion-android-camera"></i> Reviews</a></li>
		<li><a href="<?php echo TB_MSHOP_URL; ?>/mypage.php"><i class="ionicons ion-android-contact"></i> my page</a></li>
	</ul>
	<ul class="smtab">
		<li data-tab="shop_cate">category</li>
		<li data-tab="custom">Customer Service</li>
		<li data-tab="mypage">my page</li>
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
					<li><a href="<?php echo $href;?>">all</a></li>
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
		<p class="sct_noitem">There is no registered classification.</p>
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
			<li><a href="<?php echo TB_MBBS_URL; ?>/review.php">Reviews</a></li>
			<li><a href="<?php echo TB_MBBS_URL; ?>/qna_list.php">1:1 consultation</a></li>
			<li><a href="<?php echo TB_MBBS_URL; ?>/faq.php">Frequently Asked Questions</a></li>			
		</ul>
	</div>
	<div id="mypage" class="sm_body">
		<ul>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php">Order/delivery check</a></li>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/point.php">Point check</a></li>
			<?php if($config['gift_yes']) { ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/gift.php">coupon authentication</a></li>
			<?php } ?>
			<?php if($config['coupon_yes']) { ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php">Coupon management</a></li>
			<?php } ?>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/wish.php">wish</a></li>
			<li><a href="<?php echo TB_MSHOP_URL; ?>/today.php">
Recently viewed products</a></li>
			<?php if($is_member) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/leave_form.php">Withdrawal</a></li>
			<?php } ?>
		</ul>
	</div>
	<dl class="sm_cs">
		<dt>Customer Service</dt>
		<dd class="cs_tel"><?php echo $config['company_tel']; ?></dd>
		<dd>consulting : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
		<dd>Lunch : <?php echo $config['company_lunch']; ?></dd>
	</dl>
	<dl class="sm_cs">
		<dt>Deposit account information</dt>
		<?php $bank = unserialize($default['de_bank_account']); ?>
		<dd><?php echo $bank[0]['name']; ?> <b><?php echo $bank[0]['account']; ?></b></dd>
		<dd>Deposit owner name : <?php echo $bank[0]['holder']; ?></dd>
	</dl>
	<p class="mart20"><a href="tel:<?php echo $config['company_tel']; ?>" class="btn_medium grey wfull">Customer Center Phone Connection</a></p>
</div>
<script>
$(function(){
	// Submenu action on the left slide menu
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

	// Top menu button click Menu page slide
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

	//tap function
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
