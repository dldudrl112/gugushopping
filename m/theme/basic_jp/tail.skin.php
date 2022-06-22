<?php
if(!defined("_TUBEWEB_")) exit; // 個別ページアクセス不可
?>

	</div>
	<span class="btn_top fa fa-chevron-up"></span>
	<span class="btn_bottom fa fa-chevron-down"></span>

	<?php
	if($default['de_insta_access_token']) { // インスタグラム
	   $userId = explode(".", $default['de_insta_access_token']);
	?>
	<script src="<?php echo TB_JS_URL; ?>/instafeed.min.js"></script>
	<script>
		var userFeed = new Instafeed({
			get: 'user',
			userId: "<?php echo $userId[0]; ?>",
			limit: 6,
			template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></li>',
			accessToken: "<?php echo $default['de_insta_access_token']; ?>"
		});
		userFeed.run();
	</script>

	<div class="insta">
		<h2 class="tac"><i class="fa fa-instagram"></i> INSTAGRAM<a href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>" target="_blank">@ <?php echo $default['de_insta_url']; ?></a></h2>
		<ul id="instafeed">
		</ul>
	</div>
	<?php } ?>

	<div class="sns_wrap">
		<?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
		<?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
		<?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
		<?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
		<?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
		<?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
		<?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
		<?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo TB_MTHEME_URL; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
	</div>

	<footer id="ft">
		<ul class="ft_menu">
			<?php if(TB_DEVICE_BUTTON_DISPLAY && TB_IS_MOBILE) { ?>
			<li><a href="<?php echo TB_URL; ?>/index.php?device=pc">PCバージョン</a></li>
			<?php } ?>
			<?php if($config['partner_reg_yes']) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/partner_reg.php">ショッピングモール分譲申請</a></li>
			<?php } ?>
			<?php if($config['seller_reg_yes']) { ?>
			<li><a href="<?php echo TB_MBBS_URL; ?>/seller_reg.php">オンライン入店申請</a></li>
			<?php } ?>
			<li><a href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');">事業者情報確認</a></li>
		</ul>
		<dl class="ft_cs">
			<dt>顧客センター/口座案内</dt>
			<dd class="tel"><?php echo $config['company_tel']; ?></dd>
			<dd>相談 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
			<dd>お昼 : <?php echo $config['company_lunch']; ?></dd>
			<?php $bank = unserialize($default['de_bank_account']); ?>
			<dd><?php echo $bank[0]['name']; ?> <span class="bank_num"><?php echo $bank[0]['account']; ?></span> 預金主 : <?php echo $bank[0]['holder']; ?></dd>
		</dl>
		<dl class="ft_address">
			<dd><strong><?php echo $config['company_name']; ?></strong> <span class="marl15">代表者 : <?php echo $config['company_owner']; ?></span></dd>
			<dd><?php echo $config['company_addr']; ?></dd>
			<dd>顧客センター : <?php echo $config['company_tel']; ?> <span class="marl15">FAX : <?php echo $config['company_fax']; ?></span></dd>
			<dd>事業者登録番号 : <?php echo $config['company_saupja_no']; ?></dd>
			<dd>通信販売業申告 : <?php echo $config['tongsin_no']; ?></dd>
			<dd>E-mail : <?php echo $super['email']; ?></dd>
			<dd>個人情報保護責任者 : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)</dd>
		</dl>
		<p class="ft_crt">COPYRIGHT © <?php echo $config['company_name']; ?> ALL RIGHTS RESERVED.</p>
	</footer>
</div>

<script>
$(function() {
	// 上部へ戻る
	$(".btn_top").click(function(){
		$("html, body").animate({ scrollTop: 0 }, 300);
	});
	// 下位に移動
    $(".btn_bottom").click(function(){
		$("html, body").animate({ scrollTop: $(document).height() }, 300);
    });

	$(window).scroll(function () {
		if($(this).scrollTop() > 0) {
			$(".btn_top, .btn_bottom").fadeIn(300);
		} else {
			$(".btn_top, .btn_bottom").fadeOut(300);
		}
	});

	// 上部メニューのスクロール時 fixed
	var adheight = $(".top_ad").height() + $("#gnb").height();
	$(window).scroll(function () {
		if($(this).scrollTop() > adheight) {
			$("#header").addClass('active');
			$("#container").addClass('padt45');
		} else {
			$("#header").removeClass('active');
			$("#container").removeClass('padt45');
		}
	});

	// リスト横本数調整
	$('.sct_li_type > a').on('click', function() {
		var this_type = $(this).closest('.sct_li_type');
		var this_a = $(this);
		var listtype = $(this).attr('href');

		// リンク>画像の初期化
		this_type.find('a').each(function() {
			var imgSrc = $(this).find('img').attr('src').replace('<?php echo TB_MTHEME_URL; ?>/img/', '').split('.');
			$(this).find('img').attr('src', '<?php echo TB_MTHEME_URL; ?>/img/'+imgSrc[0].replace('_on','')+'.'+imgSrc[1]);
		});

		// 選択したリンク > イメージ _on 添付
		var img_src = this_a.find('img').attr('src').replace('<?php echo TB_MTHEME_URL; ?>/img/', '').split('.');
		this_a.find('img').attr('src', '<?php echo TB_MTHEME_URL; ?>/img/'+img_src[0]+'_on'+'.'+img_src[1]);

		this_type.next('ul').removeClass('wli1 wli2 wli3');
		this_type.next('ul').addClass(listtype);

		return false;
	});
});
</script>
