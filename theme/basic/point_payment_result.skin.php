<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="settle_inicis" style="display:none;">
<?php
	require_once(TB_SHOP_PATH.'/settle_point_inicis.inc.php');
	require_once(TB_SHOP_PATH.'/inicis/orderform.1.php')	;
?>
</div>
<div class="settle_kakopay" style="display:none;">
<?php
	require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
	require_once(TB_SHOP_PATH.'/kakaopay/orderform.1.php');
?>
</div>

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
              <div class="sub_title01"><h3>포인트 충전 결제하기</h3></div><!-- Sub_title01 Area End -->
              <div id="point_pay">
                
                <!-- Sub_title06 Area Start -->
                <div class="sub_title06 tit1">
                  <div class="container2">
                    <div class="ico_box"><img src="<?php echo TB_IMG_URL; ?>/pay_ico1.png" alt="ico1"></div>
                    <h4>상품을 확인해주세요.</h4>
                  </div>
                </div><!-- Sub_title06 Area End -->

                <!-- List_box Area Start -->
                <div class="list_box1">
                  <div class="container2">
                    <ul>
                      <li>
                        <dl>
                          <dt><img src="<?php echo TB_IMG_URL; ?>/product_pay_ico1.png" alt="ico1">결제 금액</dt>
                          <dd><?=number_format(get_session("payment_price"))?>원</dd>
                        </dl>
                      </li>
                      <li>
                        <dl>
                          <dt><img src="<?php echo TB_IMG_URL; ?>/product_pay_ico2.png" alt="ico2">추가</dt>
                          <dd>+<?=number_format(get_session("payment_point")-get_session("payment_price"))?>원</dd>
                        </dl>
                      </li>
                      <li>
                        <dl>
                          <dt><img src="<?php echo TB_IMG_URL; ?>/product_pay_ico1.png" alt="ico1">충전 포인트</dt>
                          <dd><?=number_format(get_session("payment_point"))?>원</dd>
                        </dl>
                      </li>
                    </ul>
                  </div>
                </div><!-- List_box Area End -->

                <!-- Sub_title06 Area Start -->
                <div class="sub_title06 tit3">
                  <div class="container2">
                    <div class="ico_box"><img src="<?php echo TB_IMG_URL; ?>/pay_ico3.png" alt="ico3"></div>
                    <h4>상품을 확인해주세요.</h4>
                  </div>
                </div><!-- Sub_title06 Area End -->

					<form class="list_box2" name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>"  autocomplete="off" >	
					<div class="settle_inicis"><?php	require_once(TB_SHOP_PATH.'/inicis/orderform.2.php');?></div>
					<div class="settle_kakopay">
					<?php
						require_once(TB_SHOP_PATH.'/kakaopay/orderform.2.php');
						require_once(TB_SHOP_PATH.'/kakaopay/orderform.3.php');
					?>
					</div>
					<div class="container2">
						<ul>
							<?php
							$escrow_title = "";
							if($default['de_escrow_use']) {
								$escrow_title = "에스크로 ";
							}

							if($is_kakaopay_use) {
								echo ' <li><label for="kakao_pay" ><input type="radio" name="paymethod" id="kakao_pay" class="screen-hidden"  value="KAKAOPAY" ><span>카카오페이</span></label></li>'.PHP_EOL;
							}

							if($default['de_card_use']) {
								echo '<li><label for="paymethod_card"><input type="radio" name="paymethod" id="paymethod_card" class="screen-hidden" value="신용카드" ><span>신용/체크카드</span></label></li>'.PHP_EOL;
							}

							if($default['de_hp_use']) {
								echo '<li><label for="paymethod_hp"><input type="radio" name="paymethod" id="paymethod_hp" class="screen-hidden" value="휴대폰" ><span>휴대폰</span></label></li>'.PHP_EOL;
							}

							if($default['de_iche_use']) {
								echo '<li><label for="paymethod_iche"><input type="radio" name="paymethod" id="paymethod_iche" class="screen-hidden" value="계좌이체" ><span>'.$escrow_title.'계좌이체</span></label></li>'.PHP_EOL;
							}


							// PG 간편결제
							if($default['de_easy_pay_use']) {
								switch($default['de_pg_service']) {
									case 'lg':
										$pg_easy_pay_name = 'PAYNOW';
										break;
									case 'inicis':
										$pg_easy_pay_name = 'KPAY';
										break;
									case 'kcp':
										$pg_easy_pay_name = 'PAYCO';
										break;
								}

								echo '<li><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'"><input type="radio" name="paymethod" id="paymethod_easy_pay" class="screen-hidden" value="간편결제" ><span>'.$pg_easy_pay_name.'</span></label></li>'.PHP_EOL;
							}
							?>
						  <li>
							  <input type="button" value="다음" onclick="fbuyform_submit(document.forderform);">   
						  </li>
						</ul>
				  </div>
				  </form>
              </div>
            </div><!-- Content_box Area End -->
		  </div><!-- Right_box Area End -->
		</div>
	</div><!-- My_box Area End -->
</div><!-- Mypage Area End -->
<script>

	var form_action_url = "<?php echo $order_action_url; ?>";

	function fbuyform_submit(f){

		var paymethod_check = false;

		for(var i=0; i<f.elements.length; i++){
			if(f.elements[i].name == "paymethod" && f.elements[i].checked==true){
				paymethod_check = true;
			}
		}

		if(!paymethod_check) {
			alert("결제방법을 선택하세요.");
			return;
		}

		//결제방법에 따른 데이터 처리 
		$.ajax({
			type: "POST",
			data: "paymethod="+getRadioVal(f.paymethod),
			url: tb_shop_url+"/ajax.orderdataload.php",
			success: function(data) {
				if(data == "N"){
					alert("결제에 실패하였습니다.\n다시 시도해주세요.");
					return;
				}else{

					if(getRadioVal(f.paymethod) == "KAKAOPAY"){

						$(".settle_inicis").remove();


					
					}else if(getRadioVal(f.paymethod) == "신용카드"){

						$(".settle_kakopay").remove();

						if( f.action != form_action_url ){
							f.action = form_action_url;
							f.removeAttribute("target");
							f.removeAttribute("accept-charset");
						}

						f.gopaymethod.value = "Card";
						f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");

					    f.price.value       = f.good_mny.value;
						<?php if($default['de_tax_flag_use']) { ?>
						f.tax.value         = f.comm_vat_mny.value;
						f.taxfree.value     = f.comm_free_mny.value;
						<?php } ?>
						f.buyername.value   = f.od_name.value;
						f.buyeremail.value  = f.od_email.value;
						f.buyertel.value    = f.od_hp.value ? f.od_hp.value : f.od_tel.value;
						f.recvname.value    = f.od_b_name.value;
						f.recvtel.value     = f.od_b_hp.value ? f.od_b_hp.value : f.od_b_tel.value;
						f.recvpostnum.value = f.od_b_zip.value;
						f.recvaddr.value    = f.od_b_addr1.value + " " +f.od_b_addr2.value;

						if(!make_signature(f))
							return;

						paybtn(f);
					}
				}
			}
		});	
	}

</script>