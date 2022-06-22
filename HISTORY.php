<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
?>

/*******************************************************************************
/* 2018-11-04 (분양몰 2.0.9)
/*******************************************************************************
(공급사 신청 보안패치)
	수정) /public_html/m/bbs/seller_reg.php
	수정) /public_html/m/bbs/seller_reg_from.php
	수정) /public_html/bbs/seller_reg.php
	수정) /public_html/bbs/seller_reg_from.php

(관리자페이지 회원정보수정시 shop_member 테이블 mb_id -> id 로 필드 오타수정)
	수정) /public_html/admin/pop_memberformupdate.php

(무통장 입금 때 고객에게 계좌정보 보냄 기능패치)
	수정) /public_html/shop/orderformupdate.php
	수정) /public_html/m/shop/orderformupdate.php

(주문상태에 따른 합계 금액 - 금액합계 오류수정)
	수정) /public_html/lib/common.lib.php
		  admin_order_status_sum() 함수 수정

(모바일 회원가입시 sns로그인 사용안함일때 박스노출 부분 오류수정)
	수정) /public_html/m/theme/basic/register.skin.php

(상품테이블 선택필드 노출 오류수정)
	수정) /public_html/lib/global.lib.php
	function get_goods($gs_id, $fileds='*')


/*******************************************************************************
/* 2018-10-05 (분양몰 2.0.8)
/*******************************************************************************
(상품등록시 최소/최대구매수량 콤마 오류 수정)
	수정) /public_html/admin/goods/goods_form.php
	수정) /public_html/admin/goods/goods_form_update.php
	수정) /public_html/mypage/partner_goods_form.php
	수정) /public_html/mypage/partner_goods_form_update.php
	수정) /public_html/mypage/seller_goods_form.php
	수정) /public_html/mypage/seller_goods_form_update.php

/*******************************************************************************
/* 2018-09-04 (분양몰 2.0.7)
/*******************************************************************************
(회원정보 수정시 새비밀번호 최소입력하라는 오류 수정)
	수정) /public_html/theme/basic/register_mod.skin.php
	수정) /public_html/m/theme/basic/register_form.skin.php

/*******************************************************************************
/* 2018-09-04 (분양몰 2.0.6)
/*******************************************************************************
(쿠폰관리 (인쇄용) 대량으로 쿠폰번호 생성시 중복방지 패치)
	수정) /public_html/lib/common.lib.php
	get_gift() 삭제
	get_coupon_id() 생성

	수정) /public_html/admin/goods/goods_gift_form_update.php
	수정) /public_html/admin/goods/goods_gift_serial.php
	수정) /public_html/config.php

/*******************************************************************************
/* 2018-08-08 (분양몰 2.0.5)
/*******************************************************************************
(쿠폰관리 (인쇄용) 엑셀저장시 한글깨짐 현상 오류 수정)
	수정) /public_html/admin/goods/goods_gift_excel.php

(모바일 인스타그램 userId 쌍따옴표 누락 수정)
	수정) /public_html/m/theme/basic/tail.skin.php

(모바일 검색창 추가)
	수정) /public_html/m/bbs/board_list.php
	수정) /public_html/m/theme/basic/board_list.skin.php
	수정) /public_html/m/theme/basic/board_gallery.skin.php
	수정) /public_html/m/theme/basic/style.css
	삭제) /public_html/m/js/jquery.lazyload.min.js
	추가) /public_html/m/js/imagesloaded.pkgd.min.js

/*******************************************************************************
/* 2018-08-08 (분양몰 2.0.4)
/*******************************************************************************
(배송완료 후 쿠폰발급 오류수정)
	수정) /public_html/bbs/register_form_update.php
	수정) /public_html/lib/global.lib.php
	수정) /public_html/m/shop/pop_coupon_update.php
	수정) /public_html/m/shop/orderformupdate.php
	수정) /public_html/m/bbs/register_form_update.php
	수정) /public_html/plugin/login-oauth/oauth_check.php
	수정) /public_html/shop/pop_coupon_update.php
	수정) /public_html/shop/orderformupdate.php
	수정) /public_html/config.php
	수정) /public_html/HISTORY.php

/*******************************************************************************
/* 2018-08-05 (분양몰 2.0.3)
/*******************************************************************************
(sns공유 오타수정)
	/public_html/shop/view.php
	수정전) $sns_url = TB_SHOP_URL.'/view.php?index_no='.$gs_id;
	수정후) $sns_url = TB_SHOP_URL.'/view.php?index_no='.$index_no;

(약관 nl2br() 태그 삭제)
	/public_html/theme/basic/seller_reg_from.skin.php
	/public_html/theme/basic/partner_reg.skin.php
	/public_html/theme/basic/register.skin.php
	/public_html/m/theme/basic/seller_reg_from.skin.php
	/public_html/m/theme/basic/partner_reg.skin.php

(define 선언 추가)
	/public_html/config.php
	추가) define('TB_VERSION', '분양몰 v2.0.3');

(업데이트 히스토리 파일추가)
	추가) /public_html/HISTORY.php

/*******************************************************************************
/* 2018-08-03 (분양몰 2.0.2)
/*******************************************************************************
(카테고리 인덱스값 추가)
	/public_html/admin/category/category_sql.php
	/public_html/install/sql_db.sql

/*******************************************************************************
/* 2018-07-31 (분양몰 2.0.1)
/*******************************************************************************
(가맹점카테고리 설정오류 수정)
	/public_html/extend/shop.extend.php
	삭제) // 기본값 본사 카테고리 테이블명
	삭제) $tb['category_table'] = 'shop_cate';

	/public_html/partner.config.php
	추가) // 기본값 본사 카테고리 테이블명
	추가) $tb['category_table'] = 'shop_cate';