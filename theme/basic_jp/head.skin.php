<?php
if(!defined('_TUBEWEB_')) exit;

if(defined('_INDEX_')) { // indexでのみ実行
	include_once(TB_LIB_PATH.'/popup.inc.php'); // ポップアップレイヤ
}
?>

<div id="wrapper">
	<div id="header">
		<?php if(!get_cookie("ck_hd_banner")) { // 上部の大きなバナー ?>
		<div id="hd_banner">
			<?php if($banner1 = display_banner_bg(1, $pt_id)) { // バナーがいるか? ?>
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
						$tnb[] = '<li><a href="'.$is_admin.'" target="_blank" class="fc_eb7">관리자</a></li>';
					if($member['id']) {
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/logout.php">ログアウト</a></li>';
					} else {
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/login.php?url='.$urlencode.'">ログイン</a></li>';
						$tnb[] = '<li><a href="'.TB_BBS_URL.'/register.php">会員加入</a></li>';
					}
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/mypage.php">マイページ</a></li>';
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/cart.php">カート<span class="ic_num">'. get_cart_count().'</span></a></li>';
					$tnb[] = '<li><a href="'.TB_SHOP_URL.'/orderinquiry.php">注文/配送照会</a></li>';
					$tnb[] = '<li><a href="'.TB_BBS_URL.'/faq.php?faqcate=1">お客様センター</a></li>';
					$tnb_str = implode(PHP_EOL, $tnb);
					echo $tnb_str;
					?>
				</ul>
			</div>
		</div>
		<div id="hd">
			<!-- 上段部領域開始 { -->
			<div id="hd_inner">
				<div class="hd_bnr">
					<span><?php echo display_banner(2, $pt_id); ?></span>
				</div>
				<h1 class="hd_logo">
					<?php echo display_logo(); ?>
				</h1>
				<div id="hd_sch">
					<fieldset class="sch_frm">
						<legend>サイト内の全検索</legend>
						<form name="fsearch" id="fsearch" method="post" action="<?php echo TB_SHOP_URL; ?>/search_update.php" onsubmit="return fsearch_submit(this);" autocomplete="off">
						<input type="hidden" name="hash_token" value="<?php echo TB_HASH_TOKEN; ?>">
						<input type="text" name="ss_tx" class="sch_stx" maxlength="20" placeholder="検索語を入力してください。">
						<button type="submit" class="sch_submit fa fa-search" value="検索"></button>
						</form>
						<script>
						function fsearch_submit(f){
							if(!f.ss_tx.value){
								alert(/'検索語を入力してください。');
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
						<span class="allc_bt"><i class="fa fa-bars"></i> 全カテゴリ</span>
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
									$(this).html('<i class="ionicons ion-ios-close-empty"></i> すべてのカテゴリー');
								} else {
									$('.all_cate .con_bx').hide();
									$(this).html('<i class="fa fa-bars"></i> すべてのカテゴリー');
								}
							});
						});
						</script>
					</div>
					<div class="gnb_li">
						<ul>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=2">ベストセラー</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=3">新商品</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=4">人気商品</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/listtype.php?type=5">おすすめ商品</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/brand.php">ブランドショップ</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/plan.php">企画展</a></li>
							<li><a href="<?php echo TB_SHOP_URL; ?>/timesale.php">タイムセール</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- } 上段部領域 おしまい -->
			<script>
			$(function(){
				// 上のメニュー付き
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
		if(defined('_INDEX_')) { // indexでのみ実行
			$sql = sql_banner_rows(0, $pt_id);
			$res = sql_query($sql);
			$mbn_rows = sql_num_rows($res);
			if($mbn_rows) {
		?>
		<!-- メインスライドバナー開始 { -->
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
		<!-- } メインスライドバナー おしまい -->
		<?php }
		}
		?>
	</div>

	<div id="container">
		<?php
		if(!is_mobile()) { // モバイル接続でない時だけ露出
			include_once(TB_THEME_PATH.'/quick.skin.php'); // クイックメニュー
		}

		if(!defined('_INDEX_')) { // indexでなければ実行
			echo '<div class="cont_inner">'.PHP_EOL;
		}
		?>
