<?php
if (!defined('_TUBEWEB_')) exit;

?>

<!-- Footer Area Start -->

<footer class="footer" id="footer">
	<div class="main-container">
		<div class="footer-left fl">
			<a href="/index.php#main_category"><img style="width: 150px;" src="/img/footer/tail_logo.png" alt="logo"></a>
		</div>
		<div class="footer-right fl">
			<div class="top">
				<ul>
					<!-- <li><a href="<?php echo TB_BBS_URL; ?>/policy.php">개인정보처리방침</a></li>
		  <li><a href="<?php echo TB_BBS_URL; ?>/provision.php">이용약관</a></li> -->
					<li><a href="javascript:void(0)">개인정보처리방침</a></li>
					<li><a href="javascript:void(0)">이용약관</a></li>
				</ul>
			</div>
			<div class="bottom">
				<ul>
					<li>
						<div><span class="subject">상호명 :</span><span class="con"><?php echo $config['company_name']; ?></span><span class="subject">대표 :</span><span class="con"><?php echo $config['company_owner']; ?></span><span class="subject"> 주소 :</span><span class="con"><?php echo $config['company_addr']; ?></span></div>
					</li>
					<li>
						<div><span class="subject">전화번호 :</span><span class="con">070-4814-3964</span><span class="subject">문의메일 :</span><span class="con"><?php echo $super['email']; ?></span></div>
					</li>
					<li>
						<div><span class="subject">사업자등록번호 :</span><span class="con"><?php echo $config['company_saupja_no']; ?></span><span class="subject">통신판매업신고번호 : </span><span class="con"><?php echo $config['tongsin_no']; ?></span></div>
					</li>
				</ul>
				<p class="mob">(주) SBJ</p>
			</div>
		</div>
	</div>
</footer><!-- Footer Area Start -->
<div class="m-quick-nav">
	<!-- <div class="m-home">
	<?php
	$tnb = array();
	$tnb[] = '<a href="/index.php#main_category"><img src="' . TB_IMG_URL . '/home.png" alt="구구홈"><span>구구홈</span></a>';
	$tnb_str = implode(PHP_EOL, $tnb);
	echo $tnb_str;
	?>          
    </div> -->
	<ul>
		<?php
		$tnb = array();
		$tnb[] = '<li><a href="/index.php#main_category"><img src="/img/footer/home_gugu.png" alt="구구홈"><span>구구홈</span></a></li>';
		$tnb[] = '<li><a href="' . TB_SHOP_URL . '/myinquiry.php"><img src="/img/footer/regi_gugu_off.png" alt="내구독"><span>내구독</span></a></li>';
		$tnb[] = '<li><a href="' . TB_SHOP_URL . '/wish.php"><img src="/img/footer/wish_gugu_off.png" alt="찜"><span>찜</span></a></li>';
		$tnb[] = '<li><a href="' . TB_BBS_URL . '/m_mypagelist.php"><img src="/img/footer/my_gugu_off.png" alt="내정보"><span>내정보</span></a></li>';
		$tnb_str = implode(PHP_EOL, $tnb);
		echo $tnb_str;
		?>
	</ul>
</div>
<div id="footer-pop-up">
	<img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
	<h4>이용약관 동의</h4>
	<p><?php echo nl2br($config['shop_provision']); ?></p>
	<a href="javascript:void(0)">확인</a>
</div>
<div id="footer-pop-up-private">
	<img src="<?php echo TB_IMG_URL; ?>/agree-close.png" alt="">
	<h4>개인정보처리방침</h4>
	<p><?php echo nl2br($config['shop_private']); ?></p>
	<a href="javascript:void(0)">확인</a>
</div>
<!-- My JS -->
<script src="<?php echo TB_JS_URL; ?>/main.js?ver=<?php echo TB_JS_VER; ?>"></script>
</body>

</html>