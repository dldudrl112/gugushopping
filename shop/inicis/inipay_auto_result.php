<?php
require_once(TB_SHOP_PATH.'/settle_inicis_auto.inc.php');

/* INIauth_bill.php
 *
 * 이니페이 플러그인을 통해 요청된 실시간 신용카드 빌링 등록을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *  
 * http://www.inicis.com
 * Copyright (C) 2004 Inicis Co., Ltd. All rights reserved.
 */

  //모바일일 경우 처리
 if(is_mobile()){

	print_r($_REQUEST);
	exit;
 }else{

	/* * ************************
	 * 1. 라이브러리 인클루드 *
	 * ************************ */
	require(TB_SHOP_PATH.'/inicis/INIpay41Lib.php');

	/* * *************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 * ************************************* */
	$inipay	=	new INIpay41;



	/* * *********************
	 * 2. 정보 설정 *
	 * ********************* */
	$inipay->m_inipayHome				=	$siteDomain;						// 이니페이 홈디렉터리
	$inipay->m_keyPw						=	$admin_key;						// 키패스워드(상점아이디에 따라 변경)
	$inipay->m_type							=	 "auth_bill";							// 고정 (절대 수정금지)
	$inipay->m_pgId							=	"INIpayBill";					    // 고정 (절대 수정금지)
	$inipay->m_subPgIp					=	 "203.238.3.10";					// 고정 (절대 수정금지)
	$inipay->m_payMethod				=	$_POST["paymethod"];		// 고정 (절대 수정금지)
	$inipay->m_billtype						=	"Card";								// 고정 (절대 수정금지)
	$inipay->m_debug						=	"false";								// 로그모드("true"로 설정하면 상세한 로그가 생성됨)
	$inipay->m_mid							=	$mid;									// 상점아이디
	$inipay->m_goodName				=	$_POST["goodname"];		// 상품명 (최대 40자)
	$inipay->m_buyerName				=	$_POST["buyername"];		// 구매자 (최대 15자)
	$inipay->m_url							=	TB_SHOP_URL;					// 사이트 URL		
	$inipay->m_merchantreserved1	=	$merchantreserved1;           // 예비필드 1
	$inipay->m_merchantreserved2	=	$merchantreserved2;           // 예비필드 2
	$inipay->m_merchantreserved3	=	$merchantreserved3;			 // 회원 ID
	$inipay->m_encrypted					=	$_POST["encrypted"];
	$inipay->m_sessionKey				=	$_POST["sessionkey"];




	/* * ************************************************************
	 * 3. 본인인증 절차를 통한 실시간 신용카드 빌링 등록 요청처리 *
	 * ************************************************************ */

	$inipay->startAction();


	/* * ************************************************************
	 *   4. 본인인증 절차를 통한 실시간 신용카드 빌링 등록 결과   *
	 * *************************************************************
	 *                                                   	      *
	 * $inipay->m_resultCode           // "00"이면 빌키생성 성공  *
	 * $inipay->m_resultMsg            // 결과에 대한 설명        *
	 * $inipay->m_cardCode             // 카드사 코드             *
	 * $inipay->m_billKey              // BILL KEY                *
	 * $inipay->m_cardPass             // 카드 비밀번호 앞 두자리 *
	 * $inipay->m_cardKind             // 카드종류(개인-0,법인-1) *
	 * $inipay->m_tid                  // 거래번호                * 
	 * ************************************************************ */
	if($inipay->m_resultCode == "00"){

	}else{
		alert("빌링 결제에 실패하였습니다.");
		exit;
	}

 }

?>
