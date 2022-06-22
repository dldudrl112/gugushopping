<?php
if(!defined('_TUBEWEB_')) exit;

if(defined('_INDEX_')) { // index只运行
	include_once(TB_LIB_PATH.'/popup.inc.php'); // 弹出层
}
?>

<div id="wrapper">
	<div id="header">
		<?php if(!get_cookie("ck_hd_banner")) { // 顶级大横幅 ?>
		<div id="hd_banner">
			<?php if($banner1 = display_banner_bg(1, $pt_id)) { // 旗帜你在吗？ ?>
			<?php echo $banner1; ?>
			<img src="<?php echo TB_IMG_URL; ?>/bt_close.gif" id="hd_close">
			<?php } // banner end ?>
		</div>
		<?php } // cookie end ?>
		<div id="tnb">
			<div id="tnb_inner">
				<ul class="fr">
					<?php
					$tnb = array();
					if($is_admin)
						$tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">
管理员</a></li>';
					if($member['id']) {
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/logout.php">注销</a></li>';
					} else {
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/login.php?url='.$urlencode.'">登录</a></li>';
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/register.php">注册会员</a></li>';
					}
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/mypage.php">我的页面 </a></li>';
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/cart.php">菜篮子<span class="ic_num">'. get_cart_count().'</span></a></li>';
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/orderinquiry.php">订购/配送查询</a></li>';
					$tnb[] = '<li><a href="'.TB_BBS_URL.'/faq.php?faqcate=1">客户中心</a></li>';
					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
		</div>
		<div id="hd">
			<!-- 上端领域开始 { -->
			<div id="hd_inner">
				<div class="hd_bnr">
					<span><?php echo display_banner(2, $pt_id); ?></span>
				</div>
				<h1 class="hd_logo">
					<?php echo display_logo(); ?>
				</h1>
				<div id="hd_sch">
					<fieldset class="sch_frm">
						<legend>网站内全部搜索</legend>
						<form name="fsearch" id="fsearch" method="post" action="<?php echo TB_SHOP_URL; ?>/search_update.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
						<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
						<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="请输入您的搜索字词">
						<button type="submit" class="sch_submit fa fa-search" value="
取回"></button>
						</form>
						<script>
						function fsearch_submit(f){
							if(!f.ss_tx.value){
								alert('
输入您的搜索字词.');
								return false;
							}
							return true;
						}
						</script>
					</fieldset>
				</div>
			</div>
			<div id="gnb">
				<div id="gnb_inner">
					<div class="all_cate">
						<span class="allc_bt"><i class="fa fa-bars"></i> 所有类别</span>
						<div class="con_bx">
							<ul>
							<?php
							$mod = 5;
							$res = sql_query_cgy('all');
							for($i=0; $row=sql_fetch_array($res); $i++) {
								$href = TB_SHOP_URL.'/list.php?ca_id='.$row['catecode'];

								if($i && $i%$mod == 0) echo "</ul>\n<ul>\n";
							?>
								<li class="c_box">
									<a href="<?php echo $href; ?>" class="cate_tit"><?php echo $row['catename']; ?></a>
									<?php
									$r = sql_query_cgy($row['catecode'], 'COUNT');
									if($r['cnt'] > 0) {
									?>
									<ul>
										<?php
										$res2 = sql_query_cgy($row['catecode']);
										while($row2 = sql_fetch_array($res2)) {
											$href2 = TB_SHOP_URL.'/list.php?ca_id='.$row2['catecode'];
										?>
										<li><a href="<?php echo $href2; ?>"><?php echo $row2['catename']; ?></a></li>
										<?php } ?>
									</ul>
									<?php } ?>
								</li>
							<?php } ?>
							</ul>
						</div>
						<script>
						$(function(){
							$('.all_cate .allc_bt').click(function(){
								if($('.all_cate .con_bx').css('display') == 'none'){
									$('.all_cate .con_bx').show();
									$(this).html('<i class="ionicons ion-ios-close-empty"></i> 所有类别');
								} else {
									$('.all_cate .con_bx').hide();
									$(this).html('<i class="fa fa-bars"></i> 所有类别');
								}
							});
						});
						</script>
					</div>
					<div class="gnb_li">
						<ul>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=2">
畅销书</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=3">新产品</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=4">最受欢迎</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=5">推荐产品</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/brand.php">品牌商店</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/plan.php">计划展览</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/timesale.php">时间销售</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- }
顶端区域结束 -->
			<script>
			$(function(){
				// 按照顶部菜单操作
				var elem1 = $("#hd_banner").height() + $("#tnb").height() + $("#hd_inner").height();
				var elem2 = $("#hd_banner").height() + $("#tnb").height() + $("#hd").height();
				var elem3 = $("#gnb").height();
				$(window).scroll(function () {
					if($(this).scrollTop() > elem1) {
						$("#gnb").addClass('gnd_fixed');
						$("#hd").css({'padding-bottom':elem3})
					} else if($(this).scrollTop() < elem2) {
						$("#gnb").removeClass('gnd_fixed');
						$("#hd").css({'padding-bottom':'0'})
					}
				});
			});
			</script>
		</div>

		<?php
		if(defined('_INDEX_')) { // index只运行
			$sql = sql_banner_rows(0, $pt_id);
			$res = sql_query($sql);
			$mbn_rows = sql_num_rows($res);
			if($mbn_rows) {
		?>
		<!-- 
启动主幻灯片横幅 { -->
		<div id="mbn_wrap">
			<?php
			$txt_w = (100 / $mbn_rows);
			$txt_arr = array();
			for($i=0; $row=sql_fetch_array($res); $i++)
			{
				if($row['bn_text'])
					$txt_arr[] = $row['bn_text'];

				$a1 = $a2 = $bg = '';
				$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
				if(is_file($file) && $row['bn_file']) {
					if($row['bn_link']) {
						$a1 = "<a href=\"{$row['bn_link']}\" target=\"{$row['bn_target']}\">";
						$a2 = "</a>";
					}

					$row['bn_bg'] = preg_replace("/([^a-zA-Z0-9])/", "", $row['bn_bg']);
					if($row['bn_bg']) $bg = "#{$row['bn_bg']} ";

					$file = rpc($file, TB_PATH, TB_URL);
					echo "<div class=\"mbn_img\" style=\"background:{$bg}url('{$file}') no-repeat top center;\">{$a1}{$a2}</div>\n";
				}
			}
			?>
		</div>
		<script>
		$(document).on('ready', function() {
			<?php if(count($txt_arr) > 0) { ?>
			var txt_arr = <?php echo json_encode($txt_arr); ?>;

			$('#mbn_wrap').slick({
				autoplay: true,
				autoplaySpeed: 4000,
				dots: true,
				fade: true,
				customPaging: function(slider, i) {
					return "<span>"+txt_arr[i]+"</span>";
				}
			});
			$('#mbn_wrap .slick-dots li').css('width', '<?php echo $txt_w; ?>%');
			<?php } else { ?>
			$('#mbn_wrap').slick({
				autoplay: true,
				autoplaySpeed: 4000,
				dots: true,
				fade: true
			});
			<?php } ?>
		});
		</script>
		<!-- } 主幻灯片横幅结束 -->
		<?php }
		}
		?>
	</div>

	<div id="container">
		<?php
		if(!is_mobile()) { // 只有不是手机连接时才暴露
			include_once(TB_THEME_PATH.'/quick.skin.php'); // 快速菜单
		}

		if(!defined('_INDEX_')) { // index如果没有，请运行。
			echo '<div class="cont_inner">'.PHP_EOL;
		}
		?>
