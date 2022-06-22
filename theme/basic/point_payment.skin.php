<?php
if(!defined('_TUBEWEB_')) exit;
?>
<!-- Mypage Area Start -->
<div id="mypage" class="pd_12">

  <!-- Mem_info Area Start -->
  <? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
  <!-- Mem_info Area End -->

  <!-- My_box Area Start -->
	<div class="my_box">
	<div class="container1">
	  <!-- Left_box Area Start -->
	  <? include_once(TB_THEME_PATH.'/aside_my.skin.php');?>
	  <!-- Left_box Area End -->
	  <!-- Right_box Area Start -->
	  <div class="right_box">
		<!-- Content_box Area Start -->
			<div class="content_box">
				<!-- Sub_title01 Area Start -->
				<div class="sub_title01">
				  <!-- 모바일 히스토리 뒤로가기 버튼 -->
				  <div id="history_back">
					<button onclick="history.back()"><img src="./images/hi_back_ico.png" alt="history_back"></button>
				  </div><!-- 모바일 히스토리 뒤로가기 버튼 -->
				  <h3>포인트 충전하기</h3>
				</div><!-- Sub_title01 Area End -->
				<div id="charging_points">
				  <h4>충전 금액 선택</h4>
				  <form name="buyform" id="buyform" method="post" action="<?php echo $form_action_url; ?>" autocomplete="off" >
					  <ul>
						<li class="list_hd">
						  <div class="list_1">선택</div>
						  <div class="list_2">결제 금액</div>
						  <div class="list_3">충전 포인트</div>
						  <div class="list_4">추가</div>
						</li>
						<li class="list_bd">
						  <div class="list_1"><input type="radio" name="price_type" value="1" id="pr_3" checked="checked"></div>
						  <div class="list_2">30,000</div>
						  <div class="list_3">33,000P</div>
						  <div class="list_4">+3,000</div>
						</li>
						<li class="list_bd">
						  <div class="list_1"><input type="radio" name="price_type" value="2" id="pr_5"></div>
						  <div class="list_2">50,000</div>
						  <div class="list_3">57,000P</div>
						  <div class="list_4">+7,000</div>
						</li>
						<li class="list_bd">
						  <div class="list_1"><input type="radio" name="price_type" value="3" id="pr_8"></div>
						  <div class="list_2">80,000</div>
						  <div class="list_3">90,000P</div>
						  <div class="list_4">+10,000</div>
						</li>
						<li class="list_bd">
						  <div class="list_1"><input type="radio" name="price_type" value="4" id="pr_10"></div>
						  <div class="list_2">100,000</div>
						  <div class="list_3">115,000P</div>
						  <div class="list_4">+15,000</div>
						</li>
						<li class="list_bd">
						  <div class="list_1"><input type="radio" name="price_type" value="5" id="pr_15"></div>
						  <div class="list_2">150,000</div>
						  <div class="list_3">175,000P</div>
						  <div class="list_4">+25,000</div>
						</li>
					  </ul>
				  </form>

				  <label class="Information">
					<input type="checkbox" id="check_agree">
					<span>결제 동의 안내문구</span>
				  </label>
				  <div class="btn_box">
					<button type="button" onclick="fbuyform_submit();">충전하기</button>
				  </div>
				  <div class="text_deco"><u>로그인</u> 후 포인트 충전이 가능합니다.</div>
				</div>
			  </div><!-- Content_box Area End -->
		  </div><!-- Right_box Area End -->
		</div>
	</div><!-- My_box Area End -->
</div><!-- Mypage Area End -->
<script>

	function fbuyform_submit(){

		var f	=	document.buyform;
		
		if($("#check_agree").is(":checked") == false){
			alert("결제 동의 안내에 동의를 하셔야 합니다.");
			return;
		}


		f.submit();		

	}

</script>