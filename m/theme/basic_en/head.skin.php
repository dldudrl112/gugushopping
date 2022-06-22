<?php
if(!defined("_TUBEWEB_")) exit; // No access to individual pages

include_once(TB_MTHEME_PATH.'/slideMenu.skin.php');
?>

<div id="wrapper">
	<div onclick="history.go(-1);" class="page_cover"><span class="sm_close"></span></div>
	<?php if($banner1 = mobile_banner(1, $pt_id)) { // Top Large Banner ?>
	<div class="top_ad"><?php echo $banner1; ?></div>
	<?php } ?>
	<header id="header">
		<div id="m_gnb">
			<h1 class="logo"><?php echo mobile_display_logo(); ?></h1>
			<span class="btn_sidem fa fa-navicon"></span>
			<span class="btn_search fa fa-search"></span>
			<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_cart fa fa-shopping-cart"><span class="ic_num"><?php echo get_cart_count(); ?></span></a>
		</div>
		<div id="hd_sch">
			<section>
			<form name="fsearch" method="post" onsubmit="return fsearch_submit(this);" autocomplete="off">
				<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
				<input type="search" name="ss_tx" value="<?php echo $ss_tx; ?>" class="search_inp" maxlength="255">
				<input type="submit" value="&#xf002;" id="sch_submit">
			</form>
			<script>
			function fsearch_submit(f){
				if(!f.ss_tx.value){
					alert('Please enter search term.');
					f.ss_tx.focus();
					return false;
				}

				f.action = tb_mobile_shop_url+'/search_update.php';
				return true;
			}
			</script>
			</section>
			<script>
			$(function(){
				// Tap the top search button to see the search window and turn it off
				$('.btn_search').click(function(){
					if($("#hd_sch").css('display') == 'none'){
						$("#hd_sch").slideDown('fast');
						$(this).attr('class','btn_search ionicons ion-android-close');
					} else {
						$("#hd_sch").slideUp('fast');
						$(this).attr('class','btn_search fa fa-search');
					}
				});
			});
			</script>

			<div class="m_rkw_se" id="rkw_open">
				<?php echo mobile_display_tick("this week's search term", 6); ?>
				<button type="button" class="btn_open"></button>
			</div>
			<div class="m_rkw_se" id="rkw_close" style="display:none;">
				<h2>this week's search word ranking</h2>
				<button type="button" class="btn_close"></button>
			</div>
			<div class="m_rkw_bg" style="display:none;">
				<?php echo mobile_display_rank(); ?>
			</div>

			<script>
			// this week's popular search term
			$(".m_rkw_se .btn_open").click(function(){
				$("#rkw_open").hide();
				$("#rkw_close").show();
				$(".m_rkw_bg").show();
			});

			// Close popular searches this week.
			$(".m_rkw_se .btn_close").click(function(){
				$("#rkw_open").show();
				$("#rkw_close").hide();
				$(".m_rkw_bg").hide();
			});

			// Rolling Top Searches
			function tick(){
				$('#ticker li:first').slideUp( function () {
					$(this).appendTo($('#ticker')).slideDown();
				});
			}
			setInterval(function(){ tick () }, 4000);
			</script>
		</div>
	</header>

	<!-- content -->
	<div id="container"<?php if(!defined("_MINDEX_")) { ?> class="sub_wrap"<?php } ?>>
		<nav id="gnb">
			<ul>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/listtype.php?type=2">
best seller</a></li>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/listtype.php?type=3">new product</a></li>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/listtype.php?type=4">popular item</a></li>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/listtype.php?type=5">
Recommended products</a></li>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/brand.php">Brand Shop</a></li>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/plan.php">Planned Exhibition</a></li>
				<li><a href="<?php echo TB_MSHOP_URL; ?>/timesale.php">Time Sale</a></li>
			</ul>
		</nav>
		<script>
		//Top Slide Menu
		var menuScroll = null;
		$(window).ready(function() {
			menuScroll = new iScroll('gnb', {
				hScrollbar:false, vScrollbar:false, bounce:false, click:true
			});
		});
		</script>
		<?php if(!defined("_MINDEX_")) { ?>
		<div id="content_title">
			<span><?php echo ($pg['pagename'] ? $pg['pagename'] : $tb['title']); ?></span>
		</div>
		<?php } ?>
