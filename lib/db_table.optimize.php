<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가


// 실행일 비교
if(isset($default['de_optimize_date']) && $default['de_optimize_date'] >= TB_TIME_YMD)
    return;

// 설정일이 지난 장바구니 상품 삭제
if($default['de_cart_keep_term'] > 0) {
	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_cart_keep_term'] * 86400));
	$sql = " delete from shop_cart where left(ct_time,10) < '$tmp_before_date' and ct_select='0' and od_id='' ";
	sql_query($sql, FALSE);
}

// 설정일이 지난 찜상품 삭제
if($default['de_wish_keep_term'] > 0) {
	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_wish_keep_term'] * 86400));
	$sql = " delete from shop_wish where left(wi_time,10) < '$tmp_before_date' ";
	sql_query($sql, FALSE);
}

// 설정일이 지난 배송완료상품 구매확정
if($default['de_final_keep_term'] > 0) {
	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_final_keep_term'] * 86400));
	$sql = " update shop_order
				set user_ok = '1'
				  , user_date = '".TB_TIME_YMDHIS."'
			  where left(invoice_date,10) < '$tmp_before_date'
		        and user_ok = '0'
			    and dan = '5' ";
	sql_query($sql, FALSE);
}

// 설정일이 지난 미입금된 주문내역 자동취소
if($default['de_misu_keep_term'] > 0) {
	$tmp_before_date = date("Y-m-d", TB_SERVER_TIME - ($default['de_misu_keep_term'] * 86400));
	$sql = " select *
			   from shop_order
			  where left(od_time,10) < '$tmp_before_date'
				and dan = '1'
			  order by index_no ";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		change_order_status_6($row['od_no']);

		// 메모남김
		$sql = " update shop_order
					set shop_memo = CONCAT(shop_memo,\"\\n미입금 자동 주문취소 - ".TB_TIME_YMDHIS." (취소이유 : {$default['de_misu_keep_term']}일경과)\")
				  where od_no = '{$row['od_no']}' ";
		sql_query($sql);
	}
}


// 빌링 데이터 조회하여 재주문 처리 
$sql	=	"select * from shop_order 
			 where  od_billkey != '' and  dan in (2,3,4,5,7,8)  and user_ok = '1' 
			 group by od_billkey
			 order by od_time desc 
			 ";
$res = sql_query($sql);

while($row=sql_fetch_array($res)) {

	$sql		=	" select * from shop_cart where od_id = '".$row["od_id"]."' 
					  group by gs_id order by io_type asc, index_no asc ";
	$result	=	sql_query($sql);

	$cycle_month			=	0;

	$set_cart_id			=	"";
	$ct_direct				=	"";
	$ct_qty					=	0;
	$io_id					=	"";
	$io_type					=	"";
	$io_price				=	"";
	$ct_option				=	"";	
	$ct_send_cost		=	"";	

	//주기 정보 체크
	for($k=0; $ct=sql_fetch_array($result); $k++) {

		$set_cart_id		=	$ct["ca_id"];
		$ct_direct			=	$ct["ct_direct"];
		$ct_qty				=	$ct["ct_qty"];
		$io_id				=	$ct["io_id"];
		$io_type				=	$ct["io_type"];
		$io_price			=	$ct["io_price"];
		$ct_option			=	$ct["ct_option"];
		$ct_send_cost	=	$ct["ct_send_cost"];

		if($ct["io_type"] == 0){

			if(strpos($ct["ct_option"], "주기") !== false){
				$arr_ct_option		=	explode("/", $ct["ct_option"]);
				$arr_cycle_text	=	explode(":",trim($arr_ct_option[1]));
				$cycle_month		=	str_replace("개월","",trim($arr_cycle_text[1]));
			}
		}
	}


	//주기 데이터가 있을 경우
	if($cycle_month > 0){


		//마지막 결제 데이터값 체크
		$sql	=	"select receipt_time from shop_order where od_billkey  = '".$row["od_billkey"]."' order by receipt_time desc limit 0,1";
		$time	=	sql_fetch($sql);

		if($time["receipt_time"] != "" && $time["receipt_time"] != "0000-00-00 00:00:00"){

			$target_date			=	date("YmdHis");
			$recv_date				=	date("YmdHis", strtotime("{$time['receipt_time']} +{$cycle_month} month"));


			//빌링 주기에 따른 처리
			if($recv_date <= $target_date){

				//오토빌 승인 요청 처리 
				if($default['de_card_test']) {
					// 테스트
					$default['de_inicis_mid'] = 'INIBillTst';
					$default['de_inicis_admin_key'] = '1111';
					$default['de_inicis_bill_key'] = 'rKnPljRn5m6J9Mzz';
				}else{
					$default['de_inicis_bill_key'] = '8hnSF5JCNUnqcHMA';	
				}

				$gs	=	get_goods($row['gs_id']);
			
				//결제 방법에 따른 변수 처리 
				if($row["paymethod"] == "신용카드"){
					$m_pgId					=	"INIpayBill";
					$m_payMethod		=	"Card";	
				}else{
					$m_pgId					=	"INIpayATCD";	
					$m_payMethod		=	"HPP";	
				}

				$od_id						=	get_uniqid(); // 주문번호 생성

				$timestamp				=	TB_TIME_YHS;

				$api_url						=	"https://iniapi.inicis.com/api/v1/billing";



				//빌링 승인 요청 API 호출
				$parm_data["type"]					=	"Billing";
				$parm_data["paymethod"]		=	$m_payMethod;
				$parm_data["timestamp"]		=	$timestamp;
				$parm_data["clientIp"]				=	$_SERVER['REMOTE_ADDR'];
				$parm_data["mid"]					=	$default['de_inicis_mid'];
				$parm_data["url"]					=	TB_URL;
				$parm_data["moid"]				=	$od_id;
				$parm_data["goodName"]		=	addslashes($gs["gname"]);
				$parm_data["buyerName"]		=	$row["name"];
				$parm_data["buyerEmail"]		=	$row["email"];
				$parm_data["buyerTel"]			=	$row["cellphone"];
				$parm_data["price"]				=	($row["use_price"]+$row["use_point"]+$row["coupon_price"]);
				$parm_data["billKey"]				=	$row['od_billkey'];

				$parm_data["authentification"]		=	"00";
				$parm_data["currency"]				=	"WON";


				$params								=	$default['de_inicis_bill_key'].$parm_data["type"].$m_payMethod.$timestamp.$_SERVER['REMOTE_ADDR'].$default['de_inicis_mid'].$od_id.$parm_data["price"].$parm_data["billKey"];		//hash(KEY+type+paymethod+timestamp+clientIp+mid+moid+price+billKey)
				$sign										=	hash("sha512",trim($params));
				$parm_data["hashData"]			=	$sign;

				
				$postdata	=	http_build_query($parm_data);
				

				$ch = curl_init();

				curl_setopt($ch, CURLOPT_URL, $api_url);
				curl_setopt($ch, CURLOPT_HEADER, 0); // 헤더 출력 여부
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1000);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Content-type: application/x-www-form-urlencoded;charset=utf-8'
				  ));

				$output	= curl_exec($ch);
				curl_close($ch);

				$data = json_decode($output,true);


				if($data["resultCode"] == "00"){

					//최종결제요청 결과 성공 DB처리
					$tno				=	$data["tid"];
					$amount		=	$data["price"];
					$app_time		=	$data["payDate"].$data["payTime"];
					$pay_method	=	$m_payMethod;
					$pay_type		=	$PAY_METHOD[$pay_method];
					$commid		=	'';
					$mobile_no	=	$parm_data["buyerTel"];
					$app_no			=	$data["payAuthCode"];
					$card_name	=	$CARD_CODE[$data["cardCode"]];
					$billkey			=	$parm_data["billKey"];

					//cart data insert
					$od_no			=	cart_uniqid();

					$sql = " insert into shop_cart
								( ca_id, mb_id, gs_id, ct_direct, ct_time, ct_price, ct_supply_price, ct_qty, ct_point, io_id, io_type, io_price, ct_option, ct_send_cost, od_id, od_no, ct_ip )
							VALUES ";
					$sql.= "( '$set_cart_id', '{$row['mb_id']}', '{$gs['index_no']}', '$ct_direct', '".TB_TIME_YMDHIS."', '{$gs['goods_price']}', '{$gs['supply_price']}', '$ct_qty', '{$gs['gpoint']}', '$io_id', '$io_type', '$io_price', '$ct_option', '$ct_send_cost', '$od_id', '$od_no', '{$_SERVER['REMOTE_ADDR']}' )";
					sql_query($sql);


					//order data insert
					$od_tno						=	$tno;
					$od_app_no				=	$app_no;
					$od_receipt_time			=	preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $app_time);
					$od_bank_account		=	$card_name;

					$sql = "insert into shop_order
						   set od_id							= '{$od_id}'
							 , od_no							= '{$od_no}'
							 , mb_id							= '{$row['mb_id']}'
							 , name							= '{$row['name']}'
							 , cellphone					= '{$row['cellphone']}'
							 , telephone					= '{$row['telephone']}'
							 , email							= '{$row['email']}'
							 , zip								= '{$row['zip']}'
							 , addr1							= '{$row['addr1']}'
							 , addr2							= '{$row['addr2']}'
							 , addr3							= '{$row['addr3']}'
							 , addr_jibeon					= '{$row['addr_jibeon']}'
							 , b_name						= '{$row['b_name']}'
							 , b_cellphone				= '{$row['b_cellphone']}'
							 , b_telephone				= '{$row['b_telephone']}'
							 , b_zip							= '{$row['b_zip']}'
							 , b_addr1						= '{$row['b_addr1']}'
							 , b_addr2						= '{$row['b_addr2']}'
							 , b_addr3						= '{$row['b_addr3']}'
							 , b_addr_jibeon				= '{$row['b_addr_jibeon']}'
							 , gs_id							= '{$row['gs_id']}'
							 , gs_notax						= '{$row['gs_notax']}'
							 , seller_id						= '{$row['seller_id']}'
							 , goods_price				= '{$row['goods_price']}'
							 , supply_price				= '{$row['supply_price']}'
							 , sum_point					= '{$row['sum_point']}'
							 , sum_qty						= '{$row['sum_qty']}'
							 , coupon_price				= '0'
							 , coupon_lo_id				= ''
							 , coupon_cp_id				= ''
							 , use_price					= '{$data['price']}'
							 , use_point					= '0'
							 , baesong_price				= '{$row['baesong_price']}'
							 , baesong_price2			= '{$row['baesong_price2']}'
							 , paymethod					= '{$row['paymethod']}'
							 , bank							= '{$row['bank']}'
							 , deposit_name				= '{$row['deposit_name']}'
							 , dan							= '2'
							 , memo						= '{$row['memo']}'
							 , taxsave_yes				= '{$row['taxsave_yes']}'
							 , taxbill_yes					= '{$row['taxbill_yes']}'
							 , company_saupja_no	= '{$row['company_saupja_no']}'
							 , company_name			= '{$row['company_name']}'
							 , company_owner			= '{$row['company_owner']}'
							 , company_addr			= '{$row['company_addr']}'
							 , company_item				= '{$row['company_item']}'
							 , company_service		= '{$row['company_service']}'
							 , tax_hp						= '{$row['tax_hp']}'
							 , tax_saupja_no				= '{$row['tax_saupja_no']}'
							 , od_time						= '".TB_TIME_YMDHIS."'
							 , od_pwd						= '{$row['od_pwd']}'
							 , od_ip							= '{$row['od_ip']}'
							 , od_test						= '{$row['od_test']}'
							 , od_tax_flag					= '{$row['od_tax_flag']}'
							 , od_settle_pid				= '{$row['od_settle_pid']}'
							 , pt_id							= '{$row['pt_id']}'
							 , receipt_time				= '$od_receipt_time'
							 , od_pg							= '{$row['od_pg']}'
							 , od_billkey					= '{$row['od_billkey']}'
							 , od_tno						= '$od_tno'
							 , od_app_no					= '$od_app_no'
							 , shop_id						= '{$row['shop_id']}'";
						sql_query($sql);

				} //end pg result				
			} //end 빌링 주기에 따른 처리				
		}//end receipt_time			
	}
}



// 실행일 기록
if(isset($default['de_optimize_date'])) {
    sql_query(" update shop_default set de_optimize_date = '".TB_TIME_YMD."' ");
}

unset($tmp_before_date);
?>