<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 좌측메뉴 시작 { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">CS CENTER</p>
		<p class="kor">고객센터</p>
	</div>
	<dl class="aside_cs">	
		<?php
		$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			$bo_href = TB_BBS_URL.'/list.php?boardid='.$row['index_no'];
			echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
		}
		?>	
		<dt><a href="<?php echo TB_BBS_URL; ?>/review.php">고객상품평</a></dt>
		<dt><a href="<?php echo TB_BBS_URL; ?>/qna_list.php">1:1 상담문의</a></dt>		
		<dt><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">자주묻는질문</a></dt>		
		<?php
		// FAQ MASTER
		$fm_sql = "select * from shop_faq_cate order by index_no asc";
		$fm_result = sql_query($fm_sql);
		for($i=0;$row=sql_fetch_array($fm_result);$i++){
			if($i==0) echo "<dd>\n<ul>\n";
			$fm_href = TB_BBS_URL.'/faq.php?faqcate='.$row['index_no'];
			echo '<li><a href="'.$fm_href.'">'.$row['catename'].'</a></li>'.PHP_EOL;
		}
		if($i > 0) echo "</ul>\n</dd>\n";
		?>
	</dl>
</aside>
<!-- } 좌측메뉴 끝 -->


<!-- Mypage Area Start -->
<div id="mypage" class="pd_12">
	<div class="container1">

		<!-- Mem_info Area Start -->
		<div class="mem_info">
			<div class="left_box">
				<div class="img_box"><img src="<?php echo TB_IMG_URL; ?>/my_img.png" alt="my_img"></div>
			</div>
			<div class="right_box">
					<dl class="info">
					<dt>
						<div class="ico_box"><img src="<?php echo TB_IMG_URL; ?>/my_sns_ico.png" alt=""></div>
						<div class="name"><?php echo get_text($member['name']); ?></div>
						<div class="system"><a><img src="<?php echo TB_IMG_URL; ?>/my_system.png" alt="ico"></a></div>
					</dt>
					<dd>포인트: <span><a href="<?php echo TB_SHOP_URL; ?>/point.php"><?php echo number_format($member['point']); ?></a>P</span></dd>
					</dl>
					<div class="btn_box">
					<a>포인트 충전하기</a>
					</div>
			</div>
		</div><!-- Mem_info Area End -->

		<!-- My_box Area Start -->
		<div class="my_box">

			<!-- Left_box Area Start -->
			<div class="left_box">
				<!-- Top Area Start -->
				<div class="top">
					<h3>내정보</h3>
					<ul>
						<li class="on"><a>결제 내역</a></li>
						<li><a href="<?php echo TB_SHOP_URL; ?>/point.php">포인트내역</a></li>
						<li><a href="<?php echo TB_SHOP_URL; ?>/gift.php">쿠폰내역</a></li>
					</ul>
				</div>
				<!-- Bottom Area Start -->
				<div class="bottom">
					<h3>고객센터</h3>
					<ul>
						<li><a href="<?php echo TB_BBS_URL; ?>/qna_list.php">1 : 1 문의하기</a></li>
						<li><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">FAQ</a></li>
						<li><a href="<?php echo TB_BBS_URL; ?>/provision.php">이용약관</a></li>
						<li><a href="<?php echo TB_BBS_URL; ?>/policy.php">개인정보 취급방침</a></li>
					</ul>
				</div>
				<!-- Text_box Area Start -->
				<div class="text_box">
					<p>월-금: 10:00 – 18:00</p>
					<p>(점심시간: 12:00~13:00)</p>
					<p>공휴일&주말 휴무</p>
				</div>
			</div><!-- Left_box Area End -->

			<!-- Right_box Area Start -->
			<div class="right_box">
				<!-- Content_box Area Start -->
				<div class="content_box">
					<!-- Sub_title01 Area Start -->
					<div class="sub_title01"><h3>결제내역</h3></div><!-- Sub_title01 Area End -->
					<div id="my_pay_list">
						<ul>
							<li>
								<div class="text_box">
									<div class="state"><span class="state1">결제완료</span></div>
									<div class="box">
										<div class="left_box">
											<div class="img_box"><img src="<?php echo TB_IMG_URL; ?>/my_page_mem.png" alt="mem"></div>
											<div class="name">이청비</div>
										</div>
										<div class="right_box">
											<div class="txt">21년 신년 나의 운세는 어떨까요?</div>
											<dl>
												<dt class="date">2021.02.19</dt>
												<dd class="price">30,000원</dd>
											</dl>
										</div>
									</div>
								</div>
								<div class="btn">
									<a class="tel_btn" href="tel:010-0000-0000">전화하기</a>
								</div>
							</li>
							<li>
								<div class="text_box">
									<div class="state"><span class="state2">상담완료</span></div>
									<div class="box">
										<div class="left_box">
											<div class="img_box"><img src="<?php echo TB_IMG_URL; ?>/my_page_mem.png" alt="mem"></div>
											<div class="name">이청비</div>
										</div>
										<div class="right_box">
											<div class="txt">21년 신년 나의 운세는 어떨까요?</div>
											<dl>
												<dt class="date">2021.02.19</dt>
												<dd class="price">30,000원</dd>
											</dl>
										</div>
									</div>
								</div>
								<div class="btn">
									<a class="review_btn" href="<?php echo TB_BBS_URL; ?>/review.php">리뷰쓰기</a>
								</div>
							</li>
						</ul>
					</div>
				</div><!-- Content_box Area End -->
			</div><!-- Right_box Area End -->

		</div><!-- My_box Area End -->

	</div>
	</div><!-- Mypage Area End -->
