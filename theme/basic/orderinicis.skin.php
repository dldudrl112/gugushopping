<?php
if(!defined('_TUBEWEB_')) exit;
?>
<div class="sub-page sub03-02" id="section">
	<div class="container">
	<!-- Sub_title Area Start -->
		<div class="sub_title">
			<h2><img src="<?php echo TB_IMG_URL; ?>/sub03-02-title.png" alt="title"></h2>
		</div><!-- Sub_title Area End -->

		<!-- Section1 Area Start -->
		<div class="section1 sec">
			<!-- Txt Area Start -->
			<div class="txt">
				<h3>주문내역을 확인해 주세요.</h3>
			</div>
			<!-- Box Area Start -->
		<?php
		$goods = '';
		$goods_count = -1;

		//오토빌링 상품 주문량
		$goods_item_count	=	0;

		$sql = " select *
				   from shop_cart
				  where od_id = '$od_id'
					and ct_select = '0'
				  group by gs_id
				  order by index_no ";
		$result = sql_query($sql);
		
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$rw	=	get_order($row['od_no']);
			$gs	=	get_goods($row['gs_id'], 'index_no, gname,tc_code, autobill, simg1');

			if(!$goods)
				$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

			$goods_count++;

			$it_name = stripslashes($gs['gname']);
			$it_options = print_complete_options($row['gs_id'], $od_id);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}
					
			//상품 구분 오토빌링 
			if($gs["autobill"] > 0){
				$goods_item_count++;
			}

			$it_image	=	get_it_image($gs['index_no'], $gs['simg1'], 258, 258);
		?>
			<div class="txt">
				<div class="img-box"><?=$it_image?></div>
			</div>
			<div class="box">			
				<dl class="product">
					<dt>상품</dt>
					<dd><?=$it_name?></dd>
				</dl>
				<dl class="price">
					<dt>금액</dt>
					<dd><span><?php echo get_price($row['gs_id']); ?></span></dd>
				</dl>
				<?php if($goods_item_count > 0){ ?>
				<dl class="date">
					<dt>결제일</dt>
					<dd>매월 <?=date("d");?>일</dd>
				</dl>
				<?php } ?>
			</div>
		<?php
		}

		if($goods_count) $goods .= ' 외 '.$goods_count.'건';

		// 복합과세처리
		$comm_tax_mny  = 0; // 과세금액
		$comm_vat_mny  = 0; // 부가세
		$comm_free_mny = 0; // 면세금액
		if($default['de_tax_flag_use']) {
			$info = comm_tax_flag($od_id);
			$comm_tax_mny  = $info['comm_tax_mny'];
			$comm_vat_mny  = $info['comm_vat_mny'];
			$comm_free_mny = $info['comm_free_mny'];
		}
		?>
		</div><!-- Section1 Area End -->

		<!-- Section2 Area Start -->
		<div class="section2 sec">			
			<!-- Txt Area Start -->
			<div class="txt">
				<h3>배송정보를 입력해 주세요.</h3>
			</div>

			<!-- Pay_info Area Start --> 
			<div class="list_box">
				<dl>
					<dt>수령인</dt>
					<dd><?php echo $od['b_name']; ?></dd>
				</dl>
				<dl>
					<dt>연락처</dt>
					<dd><?php echo $od['b_cellphone']; ?></dd>
				</dl>
				<dl>
					<dt>주소</dt>
					<dd>
						<div class="button-line"><?php echo $od['b_zip']; ?></div>
						<div class="post_addr2"><?php echo $od['b_addr1']; ?></div>
					</dd>
				</dl>
				<dl>
					<dt>상세주소</dt>
					<dd>
						<?php echo $od['b_addr2']; ?>
					</dd>
				</dl>
				<?php if($od['memo']){ ?>
				<dl>
					<dt>배송 메모</dt>
					<dd><?php echo $od['memo']; ?></dd>
				</dl>
				<?php } ?>
			</div>
		</div><!-- Section2 Area End -->

		<div class="section3 sec">
			<div class="txt">
				<h3>쿠폰 및 포인트 사용</h3>
			</div>
			<div class="list_box">		
				<dl>
					<dt>쿠폰 할인</dt>
					<dd>
					  <div class="button-line"><?php echo display_price($stotal['coupon']); ?></div>                
					</dd>
				</dl>
				<dl>
					<dt>포인트</dt>
					<dd>
						<div class="point">
							<span class="subject">사용포인트:</span>
							<?php echo display_price($stotal['usepoint']); ?>
						</div>							  
					</dd>
				</dl>
			</div>
		</div>
		<?php
				if(is_mobile()){
					require_once(TB_MSHOP_PATH.'/settle_inicis.inc.php');
					// 결제대행사별 코드 include (스크립트 등)
					//오토빌링 상품의 경우 
					if($goods_item_count > 0){

						require_once(TB_MSHOP_PATH.'/inicis/order_auto_form.1.php');
					}else{
						require_once(TB_MSHOP_PATH.'/inicis/orderform.1.php');					
					}
				}else{
					require_once(TB_SHOP_PATH.'/settle_inicis.inc.php');
					// 결제대행사별 코드 include (스크립트 등)
					require_once(TB_SHOP_PATH.'/inicis/orderform.1.php');
				}

		?>
		<!-- Section3 Area Start -->
		<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">
		<?php
			if(is_mobile()){
					//오토빌링 상품의 경우 
					if($goods_item_count > 0){
						require_once(TB_SHOP_PATH.'/inicis/orderform.2.php');
					}else{
						// 결제대행사별 코드 include (결제대행사 정보 필드)
						require_once(TB_MSHOP_PATH.'/inicis/orderform.2.php');					
					}

			}else{
				// 결제대행사별 코드 include (결제대행사 정보 필드)
				require_once(TB_SHOP_PATH.'/inicis/orderform.2.php');
			}
		?>
		<div class="section4 sec">
			<!-- Txt Area Start -->
			<div class="txt">
				<div class="img-box"><img src="<?php echo TB_IMG_URL; ?>/sub03-02-img2.png" alt="img2"></div>
				<h3>결제정보</h3>
			</div>
			<!-- Product_pay Area Start -->
			<div class="list_box">		
				<dl>
					<dt>결제방법</dt>
					<dd>
					  <div class="button-line"><?php echo $od['paymethod']; ?></div>                
					</dd>
				</dl>		
				<dl>
					<dt>최종 결제금액</dt>
					<dd>
					  <div class="button-line"><?php echo display_price($tot_price); ?></div>                
					</dd>
				</dl>				
			</div>
			<ul>
				<li><a href = "javascript:forderform_check(document.forderform);">결제하기</a></li>
			</ul>
		</div><!-- Section3 Area End -->		  
	</div>
</div>
</form>
<script>
var form_action_url = "<?php echo $order_action_url; ?>";


/* 결제방법에 따른 처리 후 결제등록요청 실행 */
function pay_auto_approval(){
    var f = document.sm_form;
    var pf = document.forderform;

    // 금액체크
    if(!payment_check(pf))
        return false;

	var paymethod = "";
	var width = 330;
	var height = 480;
	var xpos = (screen.width - width) / 2;
	var ypos = (screen.width - height) / 2;
	var position = "top=" + ypos + ",left=" + xpos;
	var features = position + ", width=320, height=440";

	switch(pf.od_settle_case.value) {
		case "계좌이체":
			paymethod = "bank";
			break;
		case "가상계좌":
			paymethod = "vbank";
			break;
		case "휴대폰":
			paymethod = "mobile";
			f.action			=	"https://inilite.inicis.com/inibill/inibill_hpp.jsp";
			break;
		case "신용카드":
			paymethod		=	"wcard";
			f.action			=	"https://inilite.inicis.com/inibill/inibill_card.jsp";
			break;
	}

	f.buyername.value		=	pf.od_name.value;
	f.buyertel.value			=	pf.od_hp.value;
	f.buyeremail.value		=	pf.od_email.value;


	f.returnurl.value			=	"<?php echo $return_url.$od_id; ?>";


	// 주문 정보 임시저장
	var order_data = $(pf).serialize();
	var save_result = "";
	//alert($(f).serialize());
	$.ajax({
		type: "POST",
		data: order_data,
		url: tb_url+"/shop/ajax.orderdatasave.php",
		cache: false,
		async: false,
		success: function(data) {
			save_result = data;
		}
	});

	if(save_result) {
		alert(save_result);
		return false;
	}

	f.submit();

    return false;
}


/* 결제방법에 따른 처리 후 결제등록요청 실행 */
function pay_approval(){
    var f = document.sm_form;
    var pf = document.forderform;

    // 금액체크
    if(!payment_check(pf))
        return false;

	var paymethod = "";
	var width = 330;
	var height = 480;
	var xpos = (screen.width - width) / 2;
	var ypos = (screen.width - height) / 2;
	var position = "top=" + ypos + ",left=" + xpos;
	var features = position + ", width=320, height=440";
	var p_reserved = f.DEF_RESERVED.value;
	f.P_RESERVED.value = p_reserved;
	switch(pf.od_settle_case.value) {
		case "계좌이체":
			paymethod = "bank";
			break;
		case "가상계좌":
			paymethod = "vbank";
			break;
		case "휴대폰":
			paymethod = "mobile";
			break;
		case "신용카드":
			paymethod = "wcard";
			f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "");
			break;
		case "간편결제":
			paymethod = "wcard";
			f.P_RESERVED.value = p_reserved+"&d_kpay=Y&d_kpay_app=Y";
			break;
		case "삼성페이":
			paymethod = "wcard";
			f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "")+"&d_samsungpay=Y";
			//f.DEF_RESERVED.value = f.DEF_RESERVED.value.replace("&useescrow=Y", "");
			f.P_SKIP_TERMS.value = "Y"; //약관을 skip 해야 제대로 실행됨
			break;
	}

	f.P_AMT.value		=	f.good_mny.value;
	f.P_UNAME.value	=	pf.od_name.value;
	f.P_MOBILE.value	=	pf.od_hp.value;
	f.P_EMAIL.value		=	pf.od_email.value;

	<?php if($default['de_tax_flag_use']) { ?>
	f.P_TAX.value = pf.comm_vat_mny.value;
	f.P_TAXFREE = pf.comm_free_mny.value;
	<?php } ?>

	f.P_RETURN_URL.value = "<?php echo $return_url.$od_id; ?>";
	f.action = "https://mobile.inicis.com/smart/" + paymethod + "/";

	// 주문 정보 임시저장
	var order_data = $(pf).serialize();
	var save_result = "";
	$.ajax({
		type: "POST",
		data: order_data,
		url: tb_url+"/shop/ajax.orderdatasave.php",
		cache: false,
		async: false,
		success: function(data) {
			save_result = data;
		}
	});

	if(save_result) {
		alert(save_result);
		return false;
	}

	f.submit();

    return false;
}

function forderform_check(f)
{
    // 금액체크
    if(!payment_check(f))
        return false;


	//모바일 submit
	<?php if(is_mobile()){?>	
		<?php if($goods_item_count > 0){ ?>
			if(!make_auto_signature(document.sm_form))
				return false;
			pay_auto_approval();

		<?php }else{ ?>
			pay_approval();
		<?php } ?>

		return false;

	<?php }?>


    if( f.action != form_action_url ){
        f.action = form_action_url;
        f.removeAttribute("target");
        f.removeAttribute("accept-charset");
    }

    switch(f.od_settle_case.value)
    {
        case "계좌이체":
            f.gopaymethod.value = "DirectBank";
            break;
        case "가상계좌":
            f.gopaymethod.value = "VBank";
            break;
        case "휴대폰":
            f.gopaymethod.value = "HPP";
            break;
        case "신용카드":
            f.gopaymethod.value = "Card";
            f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
            break;
        case "간편결제":
            f.gopaymethod.value = "Kpay";
            break;
    }

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

	// 주문정보 임시저장
	var order_data = $(f).serialize();
	var save_result = "";
	$.ajax({
		type: "POST",
		data: order_data,
		url: tb_url+"/shop/ajax.orderdatasave.php",
		cache: false,
		async: false,
		success: function(data) {
			save_result = data;
		}
	});

	if(save_result) {
		alert(save_result);
		return false;
	}
	
	if(!make_signature(f))
		return false;

	paybtn(f);

}

// 결제체크
function payment_check(f)
{
    var tot_price = parseInt(f.good_mny.value);

	if(f.od_settle_case.value == '계좌이체') {
		if(tot_price < 150) {
			alert("계좌이체는 150원 이상 결제가 가능합니다.");
			return false;
		}
	}

    if(f.od_settle_case.value == '신용카드') {
		if(tot_price < 1000) {
			alert("신용카드는 1000원 이상 결제가 가능합니다.");
			return false;
		}
    }

	if(f.od_settle_case.value == '휴대폰') {
		if(tot_price < 350) {
			alert("휴대폰은 350원 이상 결제가 가능합니다.");
			return false;
		}
    }

    return true;
}

forderform_check(document.forderform);
</script>
<!-- } 이니시스 결제 끝 -->
