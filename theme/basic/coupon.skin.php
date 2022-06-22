<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="sub-page my-page sub08-03" id="section">
    <div class="container">
	<!-- Mem_info Area Start -->
	<? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
	<!-- Mem_info Area End -->
            <div class="sub08-03-content-wrap content-wrap">
			<!-- Left_box Area Start -->
			
			<!-- Left_box Area End -->
				<div class="content-box">                       
					<h3><a href="javascript:history.back()"><img src="<?php echo TB_IMG_URL; ?>/m-back.png" alt="뒤로가기"></a>쿠폰</h3>
					<div class="coupon-wrap">                                
						<div class="coupon-reg">
							<h4>쿠폰 등록하기</h4>
							<div class="box">
							  <span>쿠폰 번호 입력</span>
							  <form name="fqaform" id="fqaform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fqaform_submit(this);" autocomplete="off">
							  <input type="hidden" name="token" value="<?php echo $token; ?>">
							  <div class="coupon-code">
								<input type="text" name="gi_num1" id="gi_num1" maxlength="4">
								<input type="text" name="gi_num2" id="gi_num2" maxlength="4">
								<input type="text" name="gi_num3" id="gi_num3" maxlength="4">
								<input type="text" name="gi_num4" id="gi_num4" maxlength="4">
								<button type="submit">입력</button>
							  </div>
							  </form>
							</div>
							<!-- 쿠폰번호 틀렸을때 출력 -->
							<div class="error-box" onclick="coupon_dis();">
								<p>유효하지 않은 쿠폰입니다.
									쿠폰 코드를 다시 한번 확인해주세요.</p>
							</div>
						</div>
						<div class="coupon-list">
							<ul>
							<?php for($i=0; $row=sql_fetch_array($result); $i++) {	?>
								<li>
									<div class="content">
										<span><?=$row['cp_subject']?></span>
										<span><?=number_format($row['cp_sale_amt'])?>원 할인 쿠폰</span>
										<span>사용기간: <?=$row['cp_inv_sdate']?> - <?=$row['cp_inv_edate']?></span>
									</div>
								</li>
							<?php
							}
							if($i==0)
								echo '<li class="empty">사용가능한 쿠폰이 없습니다.</li>';
							?>	
							</ul>
						</div>
						<?php
						echo get_paging2($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
						?>
					</div>    
				</div>
		</div><!-- content-wrap -->
	</div><!-- container -->
</div><!-- section -->
<script>
var err = '<?=$err?>';
if(err == 'fail'){
	$(".error-box").show();
}else{
	$(".error-box").hide();
}

function coupon_dis(){
	$(".error-box").hide();
}

function fqaform_submit(f) {
	if(confirm("등록 하시겠습니까?") == false)
		return false;

	return true;
}
</script>
